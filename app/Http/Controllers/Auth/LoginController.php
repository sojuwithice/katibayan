<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    protected $maxAttempts = 5; // 5 attempts
    protected $decayMinutes = 2; // 2 minutes lockout

    // Show login form
    public function showLoginForm()
    {
        // Check if user is already locked out when loading the form
        $throttleKey = $this->throttleKey(request());
        if ($this->limiter()->tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = $this->limiter()->availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            
            return view('loginpage')->with('lockout_message', "Too many login attempts. Please try again in {$minutes} minutes.");
        }

        return view('loginpage'); 
    }

    // Handle login with rate limiting
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'account_number' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if the user has too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            $user = Auth::user();

            // Role-based redirect
            if ($user->role === 'admin') {
                return redirect()->intended('/admin-dashboard')
                    ->with('success', 'Welcome, Admin!');
            } elseif ($user->role === 'sk') {
                return redirect()->intended('/sk-dashboard')
                    ->with('success', 'Logged in successfully.');
            } elseif ($user->role === 'kk') {
                return redirect()->intended('/dashboard')
                    ->with('success', 'Logged in successfully.');
            }

            return redirect()->intended('/')
                ->with('success', 'Logged in successfully.');
        }

        // Increment login attempts
        $this->incrementLoginAttempts($request);

        // Check if this failed attempt triggered a lockout
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Calculate remaining attempts
        $attempts = $this->limiter()->attempts($this->throttleKey($request));
        $remaining = $this->maxAttempts - $attempts;
        
        // Only show attempts warning if there are remaining attempts
        if ($remaining > 0 && $remaining <= 3) {
            return back()->withErrors([
                'account_number' => 'Invalid account number or password.',
            ])->with('attempts_remaining', "You have {$remaining} attempt(s) remaining before temporary lockout.")->withInput();
        }

        return back()->withErrors([
            'account_number' => 'Invalid account number or password.',
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }

    /**
     * Get the rate limiter instance.
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('account_number'));
    }

    /**
     * Determine if the user has too many failed login attempts.
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts
        );
    }

    /**
     * Increment the login attempts for the user.
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes * 60
        );
    }

    /**
     * Clear the login locks for the given user credentials.
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Fire an event when a lockout occurs.
     */
    protected function fireLockoutEvent(Request $request)
    {
        Log::warning('Lockout: Too many login attempts.', [
            'account_number' => $request->input('account_number'),
            'ip_address' => $request->ip()
        ]);
    }

    /**
     * Redirect the user after determining they are locked out.
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return back()->with('lockout_message', 'Too many login attempts. Please try again in '.$this->decayMinutes.' minutes.')
                    ->withInput($request->only('account_number', 'remember'));
    }
}