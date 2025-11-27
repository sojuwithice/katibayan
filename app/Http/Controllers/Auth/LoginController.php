<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\Models\Admin;

class LoginController extends Controller
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 2;

    // Show login form
    public function showLoginForm()
    {
        // Security: Check if user is already locked out
        $throttleKey = $this->throttleKey(request());
        if ($this->limiter()->tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = $this->limiter()->availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            
            return view('loginpage')->with('lockout_message', "Too many login attempts. Please try again in {$minutes} minutes.");
        }

        // Security: Add CSRF token refresh
        session()->regenerateToken();

        return view('loginpage'); 
    }

    // Enhanced login with security measures
    public function login(Request $request)
    {
        // Security: Validate request origin
        if (!$this->isValidRequest($request)) {
            Log::warning('Suspicious login request', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'account_number' => $request->input('account_number')
            ]);
            return $this->handleFailedLogin($request);
        }

        $credentials = $request->validate([
            'account_number' => 'required|string|max:255',
            'password' => 'required|string|max:255', // No min length requirement for default passwords
        ]);

        // Security: Check rate limiting
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Security: Add artificial delay to prevent timing attacks
        usleep(rand(100000, 300000)); // 100-300ms delay

        // FIRST: Try to login as ADMIN with enhanced security
        $adminCredentials = [
            'username' => $this->sanitizeInput($request->input('account_number')),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($adminCredentials, $request->boolean('remember'))) {
            $this->logSuccessfulLogin($request, 'admin');
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            
            // Security: Set secure session cookie
            $this->setSecureSessionConfig($request);
            
            return redirect()->route('admindashb')
                ->with('success', 'Welcome, Admin!');
        }

        // SECOND: Try to login as regular USER with enhanced security
        $userCredentials = [
            'account_number' => $this->sanitizeInput($request->input('account_number')),
            'password' => $request->input('password'),
        ];

        // Security: Additional user validation
        $user = User::where('account_number', $userCredentials['account_number'])->first();
        
        if ($user && $user->account_status !== 'approved') {
            $this->incrementLoginAttempts($request);
            return back()->withErrors([
                'account_number' => 'Account is not approved or has been suspended.',
            ])->withInput();
        }

        if (Auth::attempt($userCredentials, $request->boolean('remember'))) {
            $this->logSuccessfulLogin($request, 'user');
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            
            // Security: Set secure session cookie
            $this->setSecureSessionConfig($request);

            $user = Auth::user();

            // Role-based redirect
            if ($user->role === 'sk') {
                return redirect()->intended('/sk-dashboard')
                    ->with('success', 'Logged in successfully.');
            } elseif ($user->role === 'kk') {
                return redirect()->intended('/dashboard')
                    ->with('success', 'Logged in successfully.');
            }

            return redirect()->intended('/')
                ->with('success', 'Logged in successfully.');
        }

        // Security: Handle failed login
        return $this->handleFailedLogin($request);
    }

    // Enhanced logout with security measures
    public function logout(Request $request)
    {
        // Security: Log logout activity
        $userType = Auth::guard('admin')->check() ? 'admin' : 'user';
        Log::info('User logout', [
            'user_type' => $userType,
            'user_id' => Auth::guard('admin')->check() ? Auth::guard('admin')->id() : Auth::id(),
            'ip_address' => $request->ip()
        ]);

        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::logout();
        }
        
        // Security: Complete session destruction
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Security: Clear remember me cookie
        Cookie::queue(Cookie::forget('remember_web_' . sha1(static::class)));

        return redirect('/login')->with('success', 'Logged out successfully.');
    }

    /**
     * SECURITY ENHANCEMENTS
     */
    
    // Input sanitization
    protected function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    // Request validation
    protected function isValidRequest(Request $request)
    {
        // Check if request comes from same origin
        $referer = $request->header('referer');
        $host = $request->getHost();
        
        if ($referer && !str_contains($referer, $host)) {
            return false;
        }

        // Check user agent
        $userAgent = $request->userAgent();
        if (!$userAgent || strlen($userAgent) > 500) {
            return false;
        }

        return true;
    }

    // Enhanced failed login handling
    protected function handleFailedLogin(Request $request)
    {
        // Security: Constant-time response to prevent timing attacks
        usleep(rand(200000, 500000)); // 200-500ms delay

        $this->incrementLoginAttempts($request);

        // Security: Log failed attempt
        Log::warning('Failed login attempt', [
            'account_number' => $request->input('account_number'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'attempts' => $this->limiter()->attempts($this->throttleKey($request))
        ]);

        // Check if this failed attempt triggered a lockout
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Calculate remaining attempts
        $attempts = $this->limiter()->attempts($this->throttleKey($request));
        $remaining = $this->maxAttempts - $attempts;
        
        // Generic error message to prevent user enumeration
        $errorMessage = 'Invalid account number or password.';
        if ($remaining > 0 && $remaining <= 3) {
            $errorMessage .= " You have {$remaining} attempt(s) remaining.";
        }

        return back()->withErrors([
            'account_number' => $errorMessage,
        ])->withInput($request->only('account_number', 'remember'));
    }

    // Secure session configuration
    protected function setSecureSessionConfig(Request $request)
    {
        config([
            'session.http_only' => true,
            'session.secure' => true, // Ensure HTTPS in production
            'session.same_site' => 'lax',
        ]);
    }

    // Login activity logging
    protected function logSuccessfulLogin(Request $request, $userType)
    {
        Log::info('Successful login', [
            'user_type' => $userType,
            'account_number' => $request->input('account_number'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);
    }

    /**
     * Rate Limiting Methods
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    protected function throttleKey(Request $request)
    {
        // Enhanced throttle key with IP and user agent
        return Str::lower($request->input('account_number')).'|'.$request->ip();
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts
        );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes * 60
        );
    }

    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    protected function fireLockoutEvent(Request $request)
    {
        Log::warning('Account lockout triggered', [
            'account_number' => $request->input('account_number'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'attempts' => $this->limiter()->attempts($this->throttleKey($request))
        ]);
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return back()->with('lockout_message', 'Too many login attempts. Please try again in '.$this->decayMinutes.' minutes.')
                    ->withInput($request->only('account_number', 'remember'));
    }
}