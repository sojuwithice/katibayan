<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Admin;
use App\Mail\FailedLoginAlert;

class LoginController extends Controller
{
    protected $maxAttempts = 3;
    protected $decayMinutes = 5;

    public function showLoginForm()
    {
        $throttleKey = $this->throttleKey(request());
        if ($this->limiter()->tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = $this->limiter()->availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            
            return view('loginpage')->with('lockout_message', "Too many login attempts. Please try again in {$minutes} minutes.");
        }

        $accountLocked = session('account_locked', false);
        $lockedUserEmail = session('locked_user_email', '');

        session()->regenerateToken();

        return view('loginpage', [
            'account_locked' => $accountLocked,
            'locked_user_email' => $lockedUserEmail
        ]); 
    }

    public function login(Request $request)
    {
        Log::info('Login attempt started', ['ip' => $request->ip()]);

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
            'password' => 'required|string|max:255',
        ]);

        $user = User::where('account_number', $this->sanitizeInput($request->input('account_number')))->first();
        
        if ($user && $user->is_locked) {
            Log::warning('Locked account login attempt', [
                'account_number' => $user->account_number,
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);
            
            $this->clearLoginAttempts($request);
            
            $request->session()->put('account_locked', true);
            $request->session()->put('locked_user_email', $user->email);
            
            return redirect()->route('login')->with('account_locked', true);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            if ($user) {
                $this->sendFailedLoginAlert($user, $request, $this->maxAttempts, true);
            }
            
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        usleep(rand(100000, 300000));

        $adminCredentials = [
            'username' => $this->sanitizeInput($request->input('account_number')),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($adminCredentials, $request->boolean('remember'))) {
            $this->logSuccessfulLogin($request, 'admin');
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            
            return redirect()->route('admindashb')
                ->with('success', 'Welcome, Admin!');
        }

        $userCredentials = [
            'account_number' => $this->sanitizeInput($request->input('account_number')),
            'password' => $request->input('password'),
        ];

        if ($user && Auth::attempt($userCredentials, $request->boolean('remember'))) {
            $this->logSuccessfulLogin($request, 'user');
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            
            $request->session()->forget('account_locked');
            $request->session()->forget('locked_user_email');

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

        return $this->handleFailedLogin($request);
    }

    protected function handleFailedLogin(Request $request)
    {
        usleep(rand(200000, 500000));
        $this->incrementLoginAttempts($request);

        $user = User::where('account_number', $this->sanitizeInput($request->input('account_number')))->first();
        
        if ($user && $user->is_locked) {
            $request->session()->put('account_locked', true);
            $request->session()->put('locked_user_email', $user->email);
            return redirect()->route('login')->with('account_locked', true);
        }

        $attempts = $this->limiter()->attempts($this->throttleKey($request));
        $remaining = $this->maxAttempts - $attempts;
        
        Log::info('Failed login attempt details', [
            'account_number' => $request->input('account_number'),
            'attempts' => $attempts,
            'remaining' => $remaining,
            'user_found' => $user ? 'Yes' : 'No'
        ]);
        
        if ($user) {
            if ($attempts == 2) {
                Log::info('Sending 2nd attempt alert for user: ' . $user->id);
                $this->sendFailedLoginAlert($user, $request, $attempts, false);
            }
            
            if ($this->hasTooManyLoginAttempts($request)) {
                Log::info('Sending lockout alert for user: ' . $user->id);
                $this->sendFailedLoginAlert($user, $request, $attempts, true);
            }
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $errorMessage = 'Invalid account number or password.';
        if ($remaining > 0 && $remaining <= 3) {
            $errorMessage .= " You have {$remaining} attempt(s) remaining.";
        }

        return back()->withErrors([
            'account_number' => $errorMessage,
        ])->withInput($request->only('account_number', 'remember'));
    }

    protected function sendFailedLoginAlert($user, $request, $attempts = null, $isLockout = false)
    {
        try {
            Log::info('Attempting to send email alert', [
                'user_id' => $user->id,
                'email' => $user->email,
                'attempts' => $attempts,
                'is_lockout' => $isLockout
            ]);

            if (!$attempts) {
                $attempts = $this->limiter()->attempts($this->throttleKey($request));
            }
            
            $remainingAttempts = $this->maxAttempts - $attempts;
            $subject = $isLockout ? 
                "🚨 ACCOUNT LOCKOUT: {$user->account_number} - {$user->given_name} {$user->last_name}" :
                "⚠️ Failed Login Attempts: {$user->account_number} - {$user->given_name} {$user->last_name}";
            
            $data = [
                'user_name' => $user->given_name . ' ' . $user->last_name,
                'account_number' => $user->account_number,
                'email' => $user->email,
                'attempts' => $attempts,
                'max_attempts' => $this->maxAttempts,
                'remaining_attempts' => $remainingAttempts,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'is_lockout' => $isLockout,
                'lockout_duration' => $this->decayMinutes
            ];
            
            Log::info('Sending failed login alert email', [
                'to' => 'katibayan.system@gmail.com',
                'subject' => $subject,
                'data' => $data
            ]);
            
            // Send ONLY the actual alert email
            Mail::to('katibayan.system@gmail.com')
                ->send(new FailedLoginAlert($subject, $data));
            
            Log::info('Failed login alert sent successfully', [
                'user_id' => $user->id,
                'account_number' => $user->account_number,
                'attempts' => $attempts,
                'email_sent' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('FAILED TO SEND LOGIN ALERT EMAIL', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? 'unknown'
            ]);
        }
    }

    protected function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    protected function isValidRequest(Request $request)
    {
        $referer = $request->header('referer');
        $host = $request->getHost();
        
        if ($referer && !str_contains($referer, $host)) {
            return false;
        }

        $userAgent = $request->userAgent();
        if (!$userAgent || strlen($userAgent) > 500) {
            return false;
        }

        return true;
    }

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

    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    protected function throttleKey(Request $request)
    {
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

    public function logout(Request $request)
    {
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
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('remember_web_' . sha1(static::class)));

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}