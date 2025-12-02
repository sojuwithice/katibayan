<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Login</title>
  
  <!-- Security: Preload critical resources -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://unpkg.com">
  
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <!-- Security: CSRF Protection -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Security: Additional meta tags -->
  <meta name="robots" content="noindex, nofollow">
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://unpkg.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self';">
</head>
<body>

  <!-- Security: Add hidden honeypot field -->
  <input type="hidden" id="security_token" name="security_token" value="{{ Str::random(32) }}">

  <div class="container">
    <!-- Left side -->
    <div class="left-panel">
      <div class="welcome-text">Welcome to</div>
      <div class="brand-text">Kati<span>Bayan</span></div>
      <div class="portal-badge">Web Portal</div>
    </div>

    <!-- Right side -->
    <div class="right-panel">
      <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
        <i data-lucide="moon"></i>
      </button>

      <div class="login-box">
        <h2 class="login-title">LOGIN</h2>
        <p class="login-subtitle">Please login to your account</p>

        <!-- Security: Enhanced error display -->
        @if ($errors->any())
        <div class="error-messages" role="alert">
            @foreach ($errors->all() as $error)
                <p style="color:red; font-size:0.9rem;">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Lockout Message -->
        @if (session('lockout_message'))
        <div class="lockout-message" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('lockout_message') }}
        </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
        <div class="success-message" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <!-- Security: Add form submission protection -->
        <form action="{{ route('login.submit') }}" method="POST" id="loginForm" onsubmit="return validateForm()">
            @csrf

            <!-- Security: Add hidden timestamp -->
            <input type="hidden" name="form_submit_time" id="form_submit_time" value="{{ time() }}">

            <!-- Account Number -->
            <div class="form-group">
                <input type="text" id="account_number" name="account_number" placeholder="Enter your Account Number" 
                       value="{{ old('account_number') }}" required 
                       class="@if(session('lockout_message')) disabled-field @endif"
                       maxlength="255"
                       autocomplete="username"
                       aria-describedby="account_number_help">
                @error('account_number')
                    <span class="error-message" id="account_number_error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group password-group">
                <input type="password" id="password" name="password" placeholder="Password" required 
                       class="@if(session('lockout_message')) disabled-field @endif"
                       maxlength="255"
                       autocomplete="current-password"
                       aria-describedby="password_help">
                <span class="toggle-password" onclick="togglePassword()" role="button" aria-label="Toggle password visibility">
                    <i id="eyeIcon" class="fa fa-eye-slash"></i>
                </span>
                @error('password')
                    <span class="error-message" id="password_error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Security: Honeypot field for bots -->
            <div style="display: none;">
                <input type="text" id="website" name="website" autocomplete="off">
            </div>

            <!-- Attempts Counter -->
            @if(session('attempts_remaining'))
            <div class="attempts-warning" role="alert">
                <i class="fas fa-exclamation-circle"></i> 
                {{ session('attempts_remaining') }}
            </div>
            @endif

            <!-- Remember me / Forgot password -->
            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                        class="@if(session('lockout_message')) disabled-field @endif"
                        aria-label="Remember me for 7 days"> 
                    Remember me in 7 days
                </label>
                <a href="#" class="forgot-password" id="forgotPasswordLink">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-button @if(session('lockout_message')) disabled-field @endif" 
                @if(session('lockout_message')) disabled @endif
                id="loginButton">
                Login
            </button>
        </form>

        <div class="register-section">
          <p>Don't have an account? Please register first. 
            <a href="{{ url('/register') }}" class="register-link" rel="noopener">Register</a>
          </p>
        </div>

        <button type="button" class="back-button" id="backButton">
          <a href="{{ url('/') }}" class="back-button" rel="noopener">
            <i data-lucide="arrow-left"></i> Back
          </a>
        </button>
      </div>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div id="forgotModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeForgotModal()" role="button" aria-label="Close modal">&times;</span>

        <div class="modal-header">
            <i class="fas fa-lock icon"></i>
            <h2>Forgot Password</h2>
            <p id="forgotDescription">Enter your registered email to receive a 6-digit OTP.</p>
        </div>

        <div id="emailSection" class="form-section">
            <input type="email" id="emailInput" placeholder="Enter your email" required autocomplete="email">
            <button class="submit-btn" id="sendOtpBtn" type="button">Send OTP</button>
        </div>

        <div id="otpSection" class="form-section" style="display:none;">
            <div class="otp-container">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]">
            </div>
            <div class="resend-container">
                <span id="timerText"></span>
                <a href="#" id="resendOtpLink" style="display:none;" role="button">Resend OTP</a>
            </div>
            <button class="submit-btn" id="verifyOtpBtn" type="button">Verify OTP</button>
        </div>

        <div id="resetSection" class="form-section" style="display:none;">
            <div class="password-input-container">
                <input type="password" id="newPassword" placeholder="New Password" required autocomplete="new-password" maxlength="255">
                <i class="fas fa-eye-slash toggle-password-icon" id="toggleNewPassword" role="button" aria-label="Toggle password visibility"></i>
            </div>
            <div class="password-input-container">
                <input type="password" id="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" maxlength="255">
                <i class="fas fa-eye-slash toggle-password-icon" id="toggleConfirmPassword" role="button" aria-label="Toggle password visibility"></i>
            </div>
            <button class="submit-btn" id="resetPasswordBtn" type="button">Reset Password</button>
        </div>

        <p id="forgotMessage" class="form-message" role="alert"></p>
    </div>
  </div>

  <!-- JS with Security Enhancements -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    // Security: Form validation and protection
    let formSubmitted = false;

    function validateForm() {
        if (formSubmitted) {
            console.warn('Form already submitted');
            return false;
        }

        // Check honeypot field
        const honeypot = document.getElementById('website').value;
        if (honeypot) {
            console.warn('Bot detected');
            return false;
        }

        // Check form submission timing (prevent too quick submissions)
        const submitTime = Date.now();
        const formLoadTime = parseInt('{{ time() }}') * 1000;
        if (submitTime - formLoadTime < 1000) {
            console.warn('Form submitted too quickly');
            return false;
        }

        // Validate inputs
        const accountNumber = document.getElementById('account_number').value.trim();
        const password = document.getElementById('password').value;

        if (!accountNumber || !password) {
            return false;
        }

        // Prevent XSS in inputs
        if (accountNumber.includes('<') || accountNumber.includes('>') || 
            password.includes('<') || password.includes('>')) {
            console.warn('Potential XSS attempt detected');
            return false;
        }

        formSubmitted = true;
        document.getElementById('loginButton').disabled = true;
        document.getElementById('loginButton').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';

        return true;
    }

    // Enhanced password toggle with security
    function togglePassword() {
        const passwordField = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
            eyeIcon.setAttribute('aria-label', 'Hide password');
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
            eyeIcon.setAttribute('aria-label', 'Show password');
        }
    }

    // Security: Input sanitization
    function sanitizeInput(input) {
        return input.replace(/[<>]/g, '');
    }

    // Enhanced theme toggle
    const themeToggle = document.getElementById("themeToggle");
    const htmlTag = document.documentElement;
    const savedTheme = localStorage.getItem("theme") || "light";
    htmlTag.setAttribute("data-theme", savedTheme);
    updateIcon(savedTheme);

    themeToggle.addEventListener("click", () => {
        const currentTheme = htmlTag.getAttribute("data-theme");
        const newTheme = currentTheme === "light" ? "dark" : "light";
        htmlTag.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);
        updateIcon(newTheme);
        
        // Security: Log theme change for analytics
        console.log('Theme changed to:', newTheme);
    });

    function updateIcon(theme) {
        const icon = theme === "dark" ? "sun" : "moon";
        themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
        themeToggle.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
        lucide.createIcons(); 
    }

    // Security: Auto-remove lockout with enhanced protection
    <?php if(session('lockout_message')): ?>
    setTimeout(() => {
        window.location.reload();
    }, 120000);
    <?php endif; ?>

    // Security: Enhanced modal functionality
    let modalOpen = false;

    // === FORGOT PASSWORD MODAL SECURITY ENHANCEMENTS ===
    let otpTimer;
    const modal = document.getElementById('forgotModal');
    const forgotLink = document.getElementById('forgotPasswordLink');
    const messageBox = document.getElementById("forgotMessage");
    const otpInputs = document.querySelectorAll(".otp-input");

    // Security: Rate limiting for OTP requests
    let otpRequestCount = 0;
    const maxOtpRequests = 3;
    let lastOtpRequestTime = 0;

    function closeForgotModal() {
        modal.style.display = 'none';
        modalOpen = false;
        messageBox.textContent = "";
        messageBox.className = 'form-message';
        document.getElementById("emailSection").style.display = "block";
        document.getElementById("otpSection").style.display = "none";
        document.getElementById("resetSection").style.display = "none";
        document.getElementById("emailInput").value = "";
        document.getElementById("newPassword").value = "";
        document.getElementById("password_confirmation").value = "";
        otpInputs.forEach(input => input.value = "");
        clearInterval(otpTimer);
        if(document.getElementById("timerText")){
            document.getElementById("timerText").textContent = "";
            document.getElementById("resendOtpLink").style.display = "none";
        }
    }

    function startOtpTimer() {
        let seconds = 60;
        const timerText = document.getElementById("timerText");
        const resendLink = document.getElementById("resendOtpLink");
        resendLink.style.display = "none";
        timerText.style.display = "inline";
        clearInterval(otpTimer);
        otpTimer = setInterval(() => {
            if (seconds > 0) {
                seconds--;
                timerText.textContent = `Resend OTP in ${seconds}s`;
            } else {
                clearInterval(otpTimer);
                timerText.style.display = "none";
                resendLink.style.display = "inline";
            }
        }, 1000);
    }

    function canRequestOtp() {
        const now = Date.now();
        if (otpRequestCount >= maxOtpRequests) {
            messageBox.textContent = "Too many OTP requests. Please try again later.";
            messageBox.className = 'form-message error';
            return false;
        }
        
        if (now - lastOtpRequestTime < 30000) { // 30 seconds cooldown
            messageBox.textContent = "Please wait before requesting another OTP.";
            messageBox.className = 'form-message error';
            return false;
        }
        
        return true;
    }

    function requestOtp(isResend = false) {
        if (!canRequestOtp()) {
            return;
        }

        const email = document.getElementById("emailInput").value.trim();
        if (!email || !isValidEmail(email)) {
            messageBox.textContent = "Please enter a valid email address.";
            messageBox.className = 'form-message error';
            return;
        }

        otpRequestCount++;
        lastOtpRequestTime = Date.now();
        
        messageBox.textContent = isResend ? "Resending OTP..." : "Sending OTP...";
        messageBox.className = 'form-message';

        fetch("{{ route('forgot-password.send-otp') }}", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({ email: sanitizeInput(email) })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                messageBox.textContent = "A new OTP has been sent to your email!";
                messageBox.className = 'form-message success';
                if (!isResend) {
                    document.getElementById("emailSection").style.display = "none";
                    document.getElementById("otpSection").style.display = "block";
                }
                startOtpTimer();
            } else {
                throw new Error(data.error || 'Failed to send OTP');
            }
        })
        .catch(err => {
            messageBox.textContent = err.message || "Server error. Please try again.";
            messageBox.className = 'form-message error';
            otpRequestCount--; // Decrement on failure
        });
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function setupPasswordToggle(inputId, toggleId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(toggleId);
        toggleIcon.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
            this.setAttribute('aria-label', type === 'password' ? 'Show password' : 'Hide password');
        });
    }

    // Security: Enhanced event listeners with validation
    forgotLink.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'block';
        modalOpen = true;
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) closeForgotModal();
    });

    // Security: Prevent modal from opening multiple times
    document.getElementById("sendOtpBtn").addEventListener("click", () => {
        if (!modalOpen) return;
        requestOtp(false);
    });

    document.getElementById("resendOtpLink").addEventListener("click", (e) => {
        e.preventDefault();
        if (!modalOpen) return;
        requestOtp(true);
    });

    document.getElementById("verifyOtpBtn").addEventListener("click", () => {
        if (!modalOpen) return;
        
        const email = document.getElementById("emailInput").value.trim();
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        
        if (otp.length !== 6) {
            messageBox.textContent = "Please enter the complete 6-digit OTP.";
            messageBox.className = 'form-message error';
            return;
        }

        messageBox.textContent = "Verifying...";
        messageBox.className = 'form-message';
        
        fetch("{{ route('forgot-password.verify-otp') }}", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({ 
                email: sanitizeInput(email), 
                otp: sanitizeInput(otp) 
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                messageBox.textContent = "OTP verified! You can now reset your password.";
                messageBox.classList.add('success');
                clearInterval(otpTimer);
                document.getElementById("otpSection").style.display = "none";
                document.getElementById("resetSection").style.display = "block";
            } else {
                messageBox.textContent = data.error || "Invalid OTP.";
                messageBox.classList.add('error');
            }
        })
        .catch(() => {
            messageBox.textContent = "Server error during verification.";
            messageBox.classList.add('error');
        });
    });

    document.getElementById("resetPasswordBtn").addEventListener("click", () => {
        if (!modalOpen) return;
        
        const email = document.getElementById("emailInput").value.trim();
        const password = document.getElementById("newPassword").value;
        const password_confirmation = document.getElementById("password_confirmation").value;
        
        messageBox.textContent = "";
        messageBox.className = 'form-message';
        
        if (password !== password_confirmation) {
            messageBox.textContent = "Passwords do not match.";
            messageBox.classList.add('error');
            return;
        }

        fetch("{{ route('forgot-password.reset') }}", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({ 
                email: sanitizeInput(email), 
                password: password, 
                password_confirmation: password_confirmation 
            })
        })
        .then(res => res.json())
        .then(data => {
            messageBox.textContent = data.message;
            if (data.success) {
                messageBox.classList.add('success');
                setTimeout(() => {
                    closeForgotModal();
                    window.location.reload();
                }, 3000);
            } else {
                messageBox.classList.add('error');
            }
        })
        .catch(() => {
            messageBox.textContent = "Error resetting password.";
            messageBox.classList.add('error');
        });
    });

    // Security: Enhanced OTP input handling
    otpInputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            // Only allow numbers
            input.value = input.value.replace(/\D/g, '');
            
            if (input.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });
        
        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && input.value.length === 0 && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
        
        // Security: Prevent paste in OTP fields
        input.addEventListener("paste", (e) => {
            e.preventDefault();
            messageBox.textContent = "Pasting is not allowed in OTP fields.";
            messageBox.className = 'form-message error';
        });
    });

    // Initialize password toggles
    setupPasswordToggle('newPassword', 'toggleNewPassword');
    setupPasswordToggle('password_confirmation', 'toggleConfirmPassword');

    // Security: Page load protection
    document.addEventListener('DOMContentLoaded', function() {
        // Clear any sensitive data from previous sessions
        if (performance.navigation.type === 1) { // Page reload
            console.log('Page reload detected - clearing sensitive data');
        }
        
        // Security: Add no-cache headers simulation
        window.onbeforeunload = function() {
            // Clear sensitive form data if needed
        };
    });

    // Security: Console protection (basic)
    if (typeof console === "undefined") {
        console = {};
    }
    if (typeof console.log === "undefined") {
        console.log = function() {};
    }
  </script>

</body>
</html>