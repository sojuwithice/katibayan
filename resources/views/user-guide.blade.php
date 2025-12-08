<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - User Guide</title>
  <link rel="stylesheet" href="{{ asset('css/user-guide.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>

  <nav class="navbar">
    <div class="logo">
      <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="KatiBayan Logo" class="logo-img">
      <div class="logo-text">
        <span>KatiBayan</span>
        <small>Katipunan ng Kabataan Web Portal</small>
      </div>
    </div>

    <!-- menu -->
    <div class="menu-toggle" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </div>

    <!-- Nav links -->
    <ul class="nav-links" id="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#features">Features</a></li>
      <li><a href="#faqs">FAQs</a></li>
      <li><a href="#about">About Us</a></li>
      <li class="mobile-login">
        <a href="{{ route('loginpage') }}" class="login-btn">Login your Account</a>
      </li>

      <li class="mobile-theme-toggle">
        <button class="theme-toggle" id="mobileThemeToggle">
          <i data-lucide="moon"></i>
        </button>
      </li>
    </ul>

    <!-- Desktop login button -->
    <div class="login-container">
      <a href="{{ route('loginpage') }}" class="login-btn">Login your Account</a>
      <button class="theme-toggle" id="themeToggle">
        <i data-lucide="moon"></i>
      </button>
    </div>
  </nav>

  <!--User Guide -->
  <section class="user-guide" id="home">
    <div class="user-guide-text">
      <h1>User Guide</h1>
      <p>Katibayan provides a User Guide to help you understand the features, functions, and processes of the system. 
        This guide is designed to give clear, step-by-step instructions so that users can navigate and utilize the system efficiently. 
        It is useful for both first-time users and those familiar with the system who want to explore advanced features.</p>
    </div>
    <div class="video">
      <div class="video-wrapper">
        <iframe 
          src="https://www.youtube.com/embed/PFiShoa77sw?autoplay=1&mute=1&loop=1&playlist=PFiShoa77sw&controls=1&rel=0" 
          title="KatiBayan User Guide"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
          allowfullscreen>
        </iframe>
      </div>
    </div>
  </section>

  <section class="registration-role" id="registration-role">
    <div class="registration-guide">
      <h2>How to register?</h2>
      <p>To register on the KatiBayan Web Portal, each role is designed to align with the system's main goals: strengthening youth participation, encouraging engagement, and supporting the Sangguniang Kabataan in managing community involvement effectively.</p>
      <p>Selecting the correct role ensures proper access to system features and responsibilities, allowing the platform to promote transparency, organized youth engagement, and meaningful participation.</p>
    </div>
    
    <section class="banner" id="banner">
      <h2>You must select your role</h2>
      
      <div class="role-cards-vertical">
        <div class="role-card-rectangle left-card">
          <div class="card-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="card-content-full">
            <h3>KK (Katipunan ng Kabataan)</h3>
            <p>If you are <strong>15-30 years old</strong>, your role in the system is KK (Katipunan ng Kabataan).</p>
            <p>This includes <strong>SK council members</strong>, specifically:</p>
            <ul class="card-list-full">
              <li><strong>SK Kagawad</strong></li>
              <li><strong>SK Treasurer</strong></li>
              <li><strong>SK Secretary</strong></li>
            </ul>
            <p>Although they hold SK positions, they are still part of the youth sector and actively participate in community engagements. Being under the KK role allows members to join activities, view programs, and contribute to youth initiatives within the system.</p>
            <div class="card-highlight">
              <i class="fas fa-info-circle"></i>
              <span>This role focuses on participation and engagement in youth activities.</span>
            </div>
          </div>
        </div>

        <!-- Second Role Card - Right aligned (Icon on RIGHT) -->
        <div class="role-card-rectangle right-card">
          <div class="card-content-full">
            <h3>SK Chairperson</h3>
            <p>If you are the SK Chairperson, select your role as <strong>SK Chairperson</strong>.</p>
           <p>You are responsible for managing youth programs, creating and overseeing events, 
            and monitoring the participation of KK members. Your role focuses on coordinating, supervising, 
            and ensuring that all activities support youth development and promote engagement.</p>
          
            <div class="card-highlight">
              <i class="fas fa-exclamation-circle"></i>
              <span>As SK Chairperson, you manage youth events, profiling, and related features, but are not the system administrator</span>
            </div>
          </div>
          <div class="card-icon">
            <i class="fas fa-user-tie"></i>
          </div>
        </div>
      </div>
      
      <h2>How can I get my KatiBayan Account?</h2>
      <div class="account-section">
        <p>To get your KatiBayan account, you must enter your personal email registered during registration.</p>
      </div>

      <h2>Do you have more question? Contact us!</h2>
      <div class="account-section">
        <p>You can send an email to us if you have any questions, concerns, or need further assistance.</p>
          <p class="email-link"> Just click the this link: 
          <a href="#" id="gmailLink">katibayan.system@gmail.com</a>
          </p>
      </div>
    </section>
  </section>

  <footer class="footer">  
    <div class="footer-above"></div>
    <div class="footer-container">
      <!-- CONTACT INFORMATION -->
      <div class="footer-contact">
        <h3>CONTACT INFORMATION</h3>
        <p>For inquiries or feedback, contact us via email.</p>
        <!-- EMAIL + BUTTON INLINE -->
        <div class="email-wrapper">
          <div class="email-container">
            <p class="email-text"><i class="fa fa-envelope"></i> katibayan.system@gmail.com </p>
            <button id="emailBtn" class="email-btn">Send us email<i class="fa fa-paper-plane" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
      <!-- QUICK LINKS -->
      <div class="footer-links">
        <h3>QUICK LINKS</h3>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#features">Main feature</a></li>
          <li><a href="#faqs">Faqs</a></li>
          <li><a href="#about">About us</a></li>
        </ul>
      </div>
      <!-- LEGAL -->
      <div class="footer-legal">
        <h3>LEGAL</h3>
        <ul>
          <li><a href="#">Terms and Conditions</a></li>
        </ul>
      </div>
      <div class="footer-legal">
        <h3>USER GUIDE</h3>
        <ul>
          <li><a href="#">Learn to Use</a></li>
        </ul>
      </div>
    </div>
    
    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <p>© 2025 | All Rights Reserved | Powered by KatiBayan</p>
    </div>
  </footer>

<script>
 document.getElementById("gmailLink").addEventListener("click", function (e) {
    e.preventDefault();
    window.open(
      "https://mail.google.com/mail/?view=cm&fs=1&to=katibayan.system@gmail.com",
      "_blank"
    );
  });

  document.getElementById("emailBtn").addEventListener("click", function() {
    window.open("https://mail.google.com/mail/?view=cm&fs=1&to=katibayan.system@gmail.com", "_blank");
  });


  document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM loaded - initializing scripts');
      
      // Mobile menu toggle
      const menuToggle = document.getElementById('menu-toggle');
      const navLinks = document.getElementById('nav-links');
      
      if (menuToggle) {
        menuToggle.addEventListener('click', function() {
          console.log('Menu toggle clicked');
          navLinks.classList.toggle('active');
        });
      }

      // Hide mobile menu when a link is clicked
      const links = navLinks.querySelectorAll('a');
      links.forEach(link => {
        link.addEventListener('click', function() {
          if (navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
          }
        });
      });

      // === DARK/LIGHT MODE TOGGLE ===
      const body = document.body;
      const toggles = document.querySelectorAll('.theme-toggle');

      // Function to apply theme
      function applyTheme(isDark) {
        body.classList.toggle('dark-mode', isDark);
        const icon = isDark ? 'sun' : 'moon';

        toggles.forEach(btn => {
          btn.innerHTML = `<i data-lucide="${icon}"></i>`;
        });

        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
      }

      // Load saved theme
      const savedTheme = localStorage.getItem('theme') === 'dark';
      applyTheme(savedTheme);

      // Add event listeners to all toggles
      toggles.forEach(btn => {
        btn.addEventListener('click', () => {
          const isDark = !body.classList.contains('dark-mode');
          applyTheme(isDark);
        });
      });

      // Email button functionality
      const emailBtn = document.getElementById('emailBtn');
      if (emailBtn) {
        emailBtn.addEventListener('click', function() {
          window.location.href = 'mailto:katibayan.system@gmail.com?subject=KatiBayan Inquiry&body=Hello KatiBayan Team,';
        });
      }

      // Initialize Lucide icons if available
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
      
      console.log('All scripts initialized successfully');
    });
</script>
</body>
</html>