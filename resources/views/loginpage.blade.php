<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Login</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
      <div class="theme-toggle">
        <i id="themeIcon" class="fa fa-moon"></i>
      </div>

      <div class="login-box">
        <h2 class="login-title">LOGIN</h2>
        <p class="login-subtitle">Please login to your account</p>

        <form action="login.php" method="POST">
          <div class="form-group">
            <input type="text" id="kk-number" name="kk_number" placeholder="Enter your KK Number" required>
          </div>

          <div class="form-group password-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword()">
              <i id="eyeIcon" class="fa fa-eye-slash"></i>
            </span>
          </div>

          <div class="remember-forgot">
            <label>
              <input type="checkbox" name="remember"> Remember me in 7 days
            </label>
            <a href="#" class="forgot-password">Forgot Password?</a>
          </div>

          <button type="submit" class="login-button">Login</button>
        </form>

        <div class="register-section">
          <p>Don’t have an account? Please register first. 
            <a href="#" class="register-link">Register</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
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

    // Theme Toggle
    const themeIcon = document.getElementById("themeIcon");
    const htmlTag = document.documentElement;

    themeIcon.addEventListener("click", () => {
      if (htmlTag.getAttribute("data-theme") === "light") {
        htmlTag.setAttribute("data-theme", "dark");
        themeIcon.classList.remove("fa-moon");
        themeIcon.classList.add("fa-sun");
      } else {
        htmlTag.setAttribute("data-theme", "light");
        themeIcon.classList.remove("fa-sun");
        themeIcon.classList.add("fa-moon");
      }
    });
  </script>
</body>
</html>
