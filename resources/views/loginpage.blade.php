<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Login</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    .lockout-message {
      background: #f8d7da;
      border: 1px solid #f5c6cb;
      color: #721c24;
      padding: 12px;
      border-radius: 5px;
      margin-bottom: 15px;
      font-size: 0.9rem;
    }
    
    .attempts-warning {
      color: #856404;
      background: #fff3cd;
      padding: 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      margin-bottom: 10px;
      border: 1px solid #ffeaa7;
    }
    
    .disabled-field {
      background-color: #f8f9fa;
      cursor: not-allowed;
      opacity: 0.7;
    }
  </style>
</head>
<body>

  <div class="container">
    <!-- Left side -->
    <div class="left-panel">
      <div class="welcome-text">Welcome to</div>
      <div class="brand-text">Kati<span>Bayan</span></div>
      <div class="portal-badge">Web Portal</div>
    </div>

    <!-- Right side -->
    <div class="right-panel">
      <button class="theme-toggle" id="themeToggle">
        <i data-lucide="moon"></i>
      </button>

      <div class="login-box">
        <h2 class="login-title">LOGIN</h2>
        <p class="login-subtitle">Please login to your account</p>

        <!-- Display errors -->
        @if ($errors->any())
        <div class="error-messages">
            @foreach ($errors->all() as $error)
                <p style="color:red; font-size:0.9rem;">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Lockout Message -->
        @if (session('lockout_message'))
        <div class="lockout-message">
            <i class="fas fa-exclamation-triangle"></i> {{ session('lockout_message') }}
        </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
        <div class="success-message" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <!-- Account Number -->
            <div class="form-group">
                <input type="text" id="account_number" name="account_number" placeholder="Enter your Account Number" value="{{ old('account_number') }}" required 
                    class="@if(session('lockout_message')) disabled-field @endif">
                @error('account_number')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group password-group">
                <input type="password" id="password" name="password" placeholder="Password" required 
                    class="@if(session('lockout_message')) disabled-field @endif">
                <span class="toggle-password" onclick="togglePassword()">
                    <i id="eyeIcon" class="fa fa-eye-slash"></i>
                </span>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Attempts Counter -->
            @if(session('attempts_remaining'))
            <div class="attempts-warning">
                <i class="fas fa-exclamation-circle"></i> 
                {{ session('attempts_remaining') }}
            </div>
            @endif

            <!-- Remember me / Forgot password -->
            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                        class="@if(session('lockout_message')) disabled-field @endif"> 
                    Remember me in 7 days
                </label>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-button @if(session('lockout_message')) disabled-field @endif" 
                @if(session('lockout_message')) disabled @endif>
                Login
            </button>
        </form>

        <div class="register-section">
          <p>Don't have an account? Please register first. 
            <a href="{{ url('/register') }}" class="register-link">Register</a>
          </p>
        </div>

        <button type="button" class="back-button" id="backButton">
          <a href="{{ url('/') }}" class="back-button">
            <i data-lucide="arrow-left"></i> Back
          </a>
        </button>
      </div>
    </div>
  </div>

  <!-- Forgot Password Modal (same as your existing code) -->
  <div id="forgotModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeForgotModal()">&times;</span>

        <div class="modal-header">
            <i class="fas fa-lock icon"></i>
            <h2>Forgot Password</h2>
            <p id="forgotDescription">Enter your registered email to receive a 6-digit OTP.</p>
        </div>

        <div id="emailSection" class="form-section">
            <input type="email" id="emailInput" placeholder="Enter your email" required>
            <button class="submit-btn" id="sendOtpBtn">Send OTP</button>
        </div>

        <div id="otpSection" class="form-section" style="display:none;">
            <div class="otp-container">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
            </div>
            <div class="resend-container">
                <span id="timerText"></span>
                <a href="#" id="resendOtpLink" style="display:none;">Resend OTP</a>
            </div>
            <button class="submit-btn" id="verifyOtpBtn">Verify OTP</button>
        </div>

        <div id="resetSection" class="form-section" style="display:none;">
            <div class="password-input-container">
                <input type="password" id="newPassword" placeholder="New Password" required>
                <i class="fas fa-eye-slash toggle-password-icon" id="toggleNewPassword"></i>
            </div>
            <div class="password-input-container">
                <input type="password" id="password_confirmation" placeholder="Confirm Password" required>
                <i class="fas fa-eye-slash toggle-password-icon" id="toggleConfirmPassword"></i>
            </div>
            <button class="submit-btn" id="resetPasswordBtn">Reset Password</button>
        </div>

        <p id="forgotMessage" class="form-message"></p>
    </div>
  </div>

  <!-- JS -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    // Show/Hide password
    function togglePassword() {
      const passwordField = document.getElementById("password");
      const eyeIcon = document.getElementById("eyeIcon");

      if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
      } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
      }
    }

    // Init Lucide icons
    lucide.createIcons();

    // Theme Toggle
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
    });

    function updateIcon(theme) {
      const icon = theme === "dark" ? "sun" : "moon";
      themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
      lucide.createIcons(); 
    }

    // Auto-remove lockout after 2 minutes
    <?php if(session('lockout_message')): ?>
    setTimeout(() => {
        window.location.reload();
    }, 120000); // 2 minutes
    <?php endif; ?>

    // === 1. GLOBAL VARIABLES & ELEMENT SELECTION ===
    let otpTimer;
    const modal = document.getElementById('forgotModal');
    const forgotLink = document.querySelector('.forgot-password');
    const messageBox = document.getElementById("forgotMessage");
    const otpInputs = document.querySelectorAll(".otp-input");

    // === 2. HELPER FUNCTIONS ===
    function closeForgotModal() {
        modal.style.display = 'none';
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

    function requestOtp(isResend = false) {
        const email = document.getElementById("emailInput").value;
        if (!email) {
            messageBox.textContent = "Please enter your email address.";
            messageBox.className = 'form-message error';
            return;
        }
        messageBox.textContent = isResend ? "Resending OTP..." : "Sending OTP...";
        messageBox.className = 'form-message';
        fetch("{{ route('forgot-password.send-otp') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ email })
        })
        .then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
        .then(data => {
            if (data.success) {
                messageBox.textContent = "A new OTP has been sent to your email!";
                messageBox.className = 'form-message success';
                if (!isResend) {
                    document.getElementById("emailSection").style.display = "none";
                    document.getElementById("otpSection").style.display = "block";
                }
                startOtpTimer();
            }
        })
        .catch(err => {
            messageBox.textContent = err.error || "Server error. Please try again.";
            messageBox.className = 'form-message error';
        });
    }

    function setupPasswordToggle(inputId, toggleId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(toggleId);
        toggleIcon.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // === 3. EVENT LISTENERS ===
    forgotLink.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'block';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) closeForgotModal();
    });

    document.getElementById("sendOtpBtn").addEventListener("click", () => requestOtp(false));
    document.getElementById("resendOtpLink").addEventListener("click", (e) => {
        e.preventDefault();
        requestOtp(true);
    });

    document.getElementById("verifyOtpBtn").addEventListener("click", () => {
        const email = document.getElementById("emailInput").value;
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        messageBox.textContent = "Verifying...";
        messageBox.className = 'form-message';
        fetch("{{ route('forgot-password.verify-otp') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ email, otp })
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
        const email = document.getElementById("emailInput").value;
        const password = document.getElementById("newPassword").value;
        const password_confirmation = document.getElementById("password_confirmation").value;
        messageBox.textContent = "";
        messageBox.className = 'form-message';
        if (password.length < 8) {
            messageBox.textContent = "Password must be at least 8 characters.";
            messageBox.classList.add('error');
            return;
        }
        if (password !== password_confirmation) {
            messageBox.textContent = "Passwords do not match.";
            messageBox.classList.add('error');
            return;
        }
        fetch("{{ route('forgot-password.reset') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ email, password, password_confirmation })
        })
        .then(res => res.json())
        .then(data => {
            messageBox.textContent = data.message;
            if (data.success) {
                messageBox.classList.add('success');
                setTimeout(closeForgotModal, 3000);
            } else {
                messageBox.classList.add('error');
            }
        })
        .catch(() => {
            messageBox.textContent = "Error resetting password.";
            messageBox.classList.add('error');
        });
    });

    otpInputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            if (input.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });
        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && input.value.length === 0 && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

    setupPasswordToggle('newPassword', 'toggleNewPassword');
    setupPasswordToggle('password_confirmation', 'toggleConfirmPassword');
  </script>

</body>
</html>