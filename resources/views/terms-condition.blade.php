<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Terms and Conditions</title>
  <link rel="stylesheet" href="{{ asset('css/terms.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
      <li><a href="index.html">Home</a></li>
      <li><a href="index.html#features">Features</a></li>
      <li><a href="index.html#faqs">FAQs</a></li>
      <li><a href="index.html#about">About Us</a></li>
      <li><a href="user-guide.html">User Guide</a></li>
      <li class="mobile-login">
        <a href="#" class="login-btn">Login your Account</a>
      </li>

      <li class="mobile-theme-toggle">
        <button class="theme-toggle" id="mobileThemeToggle">
          <i class="fas fa-moon"></i>
        </button>
      </li>
    </ul>

    <!-- Desktop login button -->
    <div class="login-container">
      <a href="#" class="login-btn">Login your Account</a>
      <button class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
      </button>
    </div>
  </nav>

  <!-- Terms Header -->
  <section class="terms-header" id="home">
    <div class="terms-header-content">
      <h1>Terms and Conditions</h1>
      <p>Please read these Terms and Conditions carefully before using the KatiBayan Web Portal. Your access to and use of the service is conditioned on your acceptance of and compliance with these terms.</p>
      <div class="last-updated">
        <i class="fas fa-calendar-alt"></i> Last Updated: March 15, 2025
      </div>
    </div>
  </section>

  <!-- Mobile Swipe Container (Visible on Mobile Only) -->
  <div class="mobile-swipe-container">
    <div class="mobile-swipe-wrapper" id="swipeWrapper">
      <!-- Slide 1: Introduction -->
      <div class="mobile-swipe-slide" data-slide="1">
        <h2>1. Introduction</h2>
        <p>Welcome to KatiBayan, the official Katipunan ng Kabataan Web Portal ("the Portal"). These Terms and Conditions govern your use of our website and services.</p>
        
        <div class="highlight-box">
          <p><i class="fas fa-info-circle"></i> KatiBayan is designed for youth participation, community engagement, and effective management of Sangguniang Kabataan activities.</p>
        </div>
        
        <p>By accessing or using the Portal, you agree to be bound by these Terms. If you disagree with any part of the terms, you may not access the service.</p>
        
        <div class="swipe-instruction">
          <i class="fas fa-hand-point-up"></i> Swipe left to continue reading
        </div>
      </div>

      <!-- Slide 2: User Accounts -->
      <div class="mobile-swipe-slide" data-slide="2">
        <h2>2. User Accounts</h2>
        
        <h3>Eligibility</h3>
        <p>To use the Portal, you must:</p>
        <ul>
          <li>Be between 15-30 years old for KK membership</li>
          <li>Be a duly elected SK official for SK roles</li>
          <li>Provide accurate and complete registration information</li>
        </ul>

        <h3>Account Security</h3>
        <p>You are responsible for:</p>
        <ul>
          <li>Maintaining the confidentiality of your account</li>
          <li>All activities under your account</li>
          <li>Immediately notifying us of unauthorized use</li>
        </ul>
      </div>

      <!-- Slide 3: Acceptable Use -->
      <div class="mobile-swipe-slide" data-slide="3">
        <h2>3. Acceptable Use</h2>
        
        <h3>Permitted Uses</h3>
        <p>You may use the Portal to:</p>
        <ul>
          <li>Register for youth activities and programs</li>
          <li>Participate in community engagement</li>
          <li>Access SK programs and events information</li>
        </ul>

        <h3>Prohibited Activities</h3>
        <p>You agree not to:</p>
        <ul>
          <li>Use the Portal for illegal purposes</li>
          <li>Harass or intimidate other users</li>
          <li>Post false or misleading content</li>
        </ul>
      </div>

      <!-- Slide 4: Privacy -->
      <div class="mobile-swipe-slide" data-slide="4">
        <h2>4. Privacy & Data Protection</h2>
        <p>Your privacy is important to us. Please read our <a href="#" style="color: #3C87C4;">Privacy Policy</a> to understand how we protect your information.</p>
        
        <div class="highlight-box">
          <p><i class="fas fa-shield-alt"></i> We comply with the Data Privacy Act of 2012 (Republic Act No. 10173).</p>
        </div>
        
        <h3>Data Collection</h3>
        <p>We collect:</p>
        <ul>
          <li>Personal information for registration</li>
          <li>Usage data to improve our services</li>
          <li>Contact information for communication</li>
        </ul>
      </div>

      <!-- Slide 5: Intellectual Property -->
      <div class="mobile-swipe-slide" data-slide="5">
        <h2>5. Intellectual Property</h2>
        <p>The Portal and its original content are owned by KatiBayan and protected by intellectual property laws.</p>
        
        <h3>User Content</h3>
        <p>By posting content, you grant us a license to use it for operating the Portal.</p>
        
        <h3>Restrictions</h3>
        <p>You may not:</p>
        <ul>
          <li>Copy or modify Portal content without permission</li>
          <li>Use KatiBayan name/logo without authorization</li>
          <li>Reverse engineer or extract source code</li>
        </ul>
      </div>

      <!-- Slide 6: Final Terms -->
      <div class="mobile-swipe-slide" data-slide="6">
        <h2>6. Final Terms</h2>
        
        <h3>Limitation of Liability</h3>
        <p>KatiBayan is not liable for:</p>
        <ul>
          <li>Indirect or consequential damages</li>
          <li>Loss of data or profits</li>
          <li>Service interruptions</li>
        </ul>

        <h3>Governing Law</h3>
        <p>These Terms are governed by Philippine laws. Disputes shall be subject to Philippine courts.</p>
        
        <h3>Contact Information</h3>
        <ul>
          <li><strong>Email:</strong> katibayan.system@gmail.com</li>
          <li><strong>Office Hours:</strong> Mon-Fri, 8 AM to 5 PM</li>
        </ul>
        
        <div class="swipe-nav">
          <button class="swipe-btn" id="prevBtn" disabled>
            <i class="fas fa-chevron-left"></i> Previous
          </button>
          
          <button class="swipe-btn" id="acceptMobileBtn">
            Accept Terms <i class="fas fa-check-circle"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Swipe Indicator Dots -->
    <div class="swipe-indicator" id="swipeIndicator">
      <div class="swipe-dot active" data-slide="1"></div>
      <div class="swipe-dot" data-slide="2"></div>
      <div class="swipe-dot" data-slide="3"></div>
      <div class="swipe-dot" data-slide="4"></div>
      <div class="swipe-dot" data-slide="5"></div>
      <div class="swipe-dot" data-slide="6"></div>
    </div>
  </div>

  <!-- Desktop Terms Content (Visible on Desktop Only) -->
  <section class="terms-content">
    <div class="terms-container">
      <!-- Introduction -->
      <div class="terms-section" id="section1">
        <h2>1. Introduction</h2>
        <p>Welcome to KatiBayan, the official Katipunan ng Kabataan Web Portal ("the Portal"). These Terms and Conditions govern your use of our website and services. By accessing or using the Portal, you agree to be bound by these Terms. If you disagree with any part of the terms, you may not access the service.</p>
        
        <div class="highlight-box">
          <p><i class="fas fa-info-circle"></i> KatiBayan is designed for youth participation, community engagement, and effective management of Sangguniang Kabataan activities.</p>
        </div>
      </div>

      <!-- User Accounts -->
      <div class="terms-section" id="section2">
        <h2>2. User Accounts and Registration</h2>
        
        <h3>2.1 Eligibility</h3>
        <p>To use the Portal, you must:</p>
        <ul>
          <li>Be between 15-30 years old for KK membership</li>
          <li>Be a duly elected SK official for SK roles</li>
          <li>Provide accurate and complete registration information</li>
          <li>Use only one account per individual</li>
        </ul>

        <h3>2.2 Account Security</h3>
        <p>You are responsible for:</p>
        <ul>
          <li>Maintaining the confidentiality of your account credentials</li>
          <li>All activities that occur under your account</li>
          <li>Immediately notifying us of any unauthorized use of your account</li>
          <li>Using secure passwords and logging out after each session</li>
        </ul>
      </div>

      <!-- Acceptable Use -->
      <div class="terms-section" id="section3">
        <h2>3. Acceptable Use</h2>
        
        <h3>3.1 Permitted Uses</h3>
        <p>You may use the Portal to:</p>
        <ul>
          <li>Register for youth activities and programs</li>
          <li>Participate in community engagement initiatives</li>
          <li>Access information about SK programs and events</li>
          <li>Communicate with other youth members and officials</li>
          <li>Submit proposals and feedback for youth development</li>
        </ul>

        <h3>3.2 Prohibited Activities</h3>
        <p>You agree not to:</p>
        <ul>
          <li>Use the Portal for any illegal purposes</li>
          <li>Harass, threaten, or intimidate other users</li>
          <li>Post false, misleading, or defamatory content</li>
          <li>Impersonate any person or entity</li>
          <li>Attempt to gain unauthorized access to other accounts</li>
        </ul>
      </div>

      <!-- Privacy Policy -->
      <div class="terms-section" id="section4">
        <h2>4. Privacy and Data Protection</h2>
        <p>Your privacy is important to us. Please read our <a href="#" style="color: #3C87C4; text-decoration: underline;">Privacy Policy</a> to understand how we collect, use, and protect your personal information.</p>
        
        <div class="highlight-box">
          <p><i class="fas fa-shield-alt"></i> We comply with the Data Privacy Act of 2012 (Republic Act No. 10173) and implement appropriate security measures to protect your data.</p>
        </div>
        
        <h3>4.1 Data Collection</h3>
        <p>We collect information necessary for:</p>
        <ul>
          <li>User registration and account management</li>
          <li>Providing and improving our services</li>
          <li>Communicating important updates and announcements</li>
          <li>Complying with legal obligations</li>
        </ul>
      </div>

      <!-- Intellectual Property -->
      <div class="terms-section" id="section5">
        <h2>5. Intellectual Property</h2>
        <p>The Portal and its original content, features, and functionality are owned by KatiBayan and are protected by international copyright, trademark, and other intellectual property laws.</p>
        
        <h3>5.1 User Content</h3>
        <p>By posting content on the Portal, you grant us a non-exclusive, worldwide, royalty-free license to use, modify, and display such content for the purposes of operating the Portal.</p>
        
        <h3>5.2 Restrictions</h3>
        <p>You may not:</p>
        <ul>
          <li>Copy, modify, or distribute Portal content without permission</li>
          <li>Use the KatiBayan name or logo without authorization</li>
          <li>Reverse engineer or attempt to extract source code</li>
          <li>Create derivative works based on the Portal</li>
        </ul>
      </div>

      <!-- Final Sections -->
      <div class="terms-section" id="section6">
        <h2>6. Final Terms</h2>
        
        <h3>6.1 Limitation of Liability</h3>
        <p>To the maximum extent permitted by law, KatiBayan shall not be liable for:</p>
        <ul>
          <li>Any indirect, incidental, or consequential damages</li>
          <li>Loss of data, profits, or business opportunities</li>
          <li>Errors or omissions in Portal content</li>
          <li>Third-party actions or content</li>
          <li>Service interruptions or technical issues</li>
        </ul>

        <h3>6.2 Changes to Terms</h3>
        <p>We reserve the right to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect.</p>
        
        <h3>6.3 Governing Law</h3>
        <p>These Terms shall be governed and construed in accordance with the laws of the Republic of the Philippines, without regard to its conflict of law provisions.</p>
        
        <h3>6.4 Contact Information</h3>
        <p>If you have any questions about these Terms and Conditions, please contact us:</p>
        <ul>
          <li><strong>Email:</strong> katibayan.system@gmail.com</li>
          <li><strong>Office Hours:</strong> Monday to Friday, 8:00 AM to 5:00 PM</li>
          <li><strong>Response Time:</strong> Within 3-5 business days</li>
        </ul>
      </div>

      <!-- Desktop Navigation -->
      <div class="desktop-nav">
        <button class="desktop-nav-btn" id="desktopPrevBtn" disabled>
          <i class="fas fa-chevron-left"></i> Previous Section
        </button>
        
        <div class="desktop-progress">
          Section <span id="currentSection">1</span> of 6
        </div>
        
        <button class="desktop-nav-btn" id="desktopNextBtn">
          Next Section <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <!-- Final Acceptance Button -->
      <div class="acceptance-section">
        <button class="accept-btn" id="acceptDesktopBtn">
          <i class="fas fa-check-circle"></i> I Accept the Terms and Conditions
        </button>
      </div>
    </div>
  </section>

  <!-- Acknowledgement Modal -->
  <div class="ack-modal" id="ackModal">
    <div class="ack-modal-content">
      <div class="ack-modal-icon">
        <i class="fas fa-file-contract"></i>
      </div>
      <h3>Accept Terms and Conditions</h3>
      <p>By accepting, you acknowledge that you have read, understood, and agree to be bound by the KatiBayan Terms and Conditions. This agreement is legally binding.</p>
      <p><strong>Do you accept these terms?</strong></p>
      <div class="ack-modal-buttons">
        <button class="ack-btn decline" id="declineBtn">Decline</button>
        <button class="ack-btn accept" id="acceptBtn">Accept</button>
      </div>
    </div>
  </div>

  <!-- Scroll to Top Button -->
  <div class="scroll-top" id="scrollTop">
    <i class="fas fa-chevron-up"></i>
  </div>

  <!-- Footer -->
  <footer class="footer">  
    <div class="footer-container">
      <!-- CONTACT INFORMATION -->
      <div class="footer-contact">
        <h3>CONTACT INFORMATION</h3>
        <p>For inquiries or feedback about our Terms and Conditions, contact us via email.</p>
        <!-- EMAIL + BUTTON INLINE -->
        <div class="email-wrapper">
          <div class="email-container">
            <p class="email-text"><i class="fa fa-envelope"></i> katibayan.system@gmail.com</p>
            <button id="emailBtn" class="email-btn">Send us email<i class="fa fa-paper-plane" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
      <!-- QUICK LINKS -->
      <div class="footer-links">
        <h3>QUICK LINKS</h3>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="index.html#features">Main feature</a></li>
          <li><a href="index.html#faqs">FAQs</a></li>
          <li><a href="index.html#about">About us</a></li>
        </ul>
      </div>
      <!-- LEGAL -->
      <div class="footer-legal">
        <h3>LEGAL</h3>
        <ul>
          <li><a href="#" style="font-weight: bold; color: #FFE9AD;">Terms and Conditions</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="footer-legal">
        <h3>USER GUIDE</h3>
        <ul>
          <li><a href="user-guide.html">Learn to Use</a></li>
          <li><a href="user-guide.html#user-role">Registration Guide</a></li>
        </ul>
      </div>
    </div>
    
    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <p>© 2025 | All Rights Reserved | Powered by KatiBayan</p>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const menuToggle = document.getElementById('menu-toggle');
      const navLinks = document.getElementById('nav-links');
      const themeToggle = document.getElementById('themeToggle');
      const mobileThemeToggle = document.getElementById('mobileThemeToggle');
      const emailBtn = document.getElementById('emailBtn');
      const ackModal = document.getElementById('ackModal');
      const acceptBtn = document.getElementById('acceptBtn');
      const declineBtn = document.getElementById('declineBtn');
      const scrollTopBtn = document.getElementById('scrollTop');
      
      // Mobile Swipe Variables
      const swipeWrapper = document.getElementById('swipeWrapper');
      const swipeDots = document.querySelectorAll('.swipe-dot');
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const acceptMobileBtn = document.getElementById('acceptMobileBtn');
      
      // Desktop Navigation Variables
      const desktopPrevBtn = document.getElementById('desktopPrevBtn');
      const desktopNextBtn = document.getElementById('desktopNextBtn');
      const currentSectionSpan = document.getElementById('currentSection');
      const acceptDesktopBtn = document.getElementById('acceptDesktopBtn');
      
      let currentSlide = 0;
      let currentDesktopSection = 1;
      const totalSlides = 6;
      const totalDesktopSections = 6;
      
      // Initialize Mobile Swipe
      function initMobileSwipe() {
        updateMobileNav();
        
        // Swipe functionality
        let touchStartX = 0;
        let touchEndX = 0;
        
        swipeWrapper.addEventListener('touchstart', e => {
          touchStartX = e.changedTouches[0].screenX;
        });
        
        swipeWrapper.addEventListener('touchend', e => {
          touchEndX = e.changedTouches[0].screenX;
          handleSwipe();
        });
        
        // Button navigation
        prevBtn.addEventListener('click', () => {
          if (currentSlide > 0) {
            currentSlide--;
            updateSlidePosition();
            updateMobileNav();
          }
        });
        
        // Dot navigation
        swipeDots.forEach((dot, index) => {
          dot.addEventListener('click', () => {
            currentSlide = index;
            updateSlidePosition();
            updateMobileNav();
          });
        });
        
        // Accept button for mobile
        acceptMobileBtn.addEventListener('click', () => {
          showAcknowledgementModal();
        });
      }
      
      function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
          if (diff > 0 && currentSlide < totalSlides - 1) {
            // Swipe left - next slide
            currentSlide++;
          } else if (diff < 0 && currentSlide > 0) {
            // Swipe right - previous slide
            currentSlide--;
          }
          updateSlidePosition();
          updateMobileNav();
        }
      }
      
      function updateSlidePosition() {
        swipeWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
      }
      
      function updateMobileNav() {
        // Update dots
        swipeDots.forEach((dot, index) => {
          dot.classList.toggle('active', index === currentSlide);
        });
        
        // Update buttons
        prevBtn.disabled = currentSlide === 0;
        
        // Show/hide accept button on last slide
        if (currentSlide === totalSlides - 1) {
          acceptMobileBtn.style.display = 'flex';
        } else {
          acceptMobileBtn.style.display = 'none';
        }
        
        // Update instruction text
        const instruction = document.querySelector('.swipe-instruction');
        if (instruction) {
          if (currentSlide === totalSlides - 1) {
            instruction.innerHTML = '<i class="fas fa-hand-point-up"></i> Tap Accept to agree to terms';
          } else {
            instruction.innerHTML = '<i class="fas fa-hand-point-up"></i> Swipe left to continue reading';
          }
        }
      }
      
      // Initialize Desktop Navigation
      function initDesktopNavigation() {
        updateDesktopNav();
        
        desktopPrevBtn.addEventListener('click', () => {
          if (currentDesktopSection > 1) {
            currentDesktopSection--;
            scrollToDesktopSection();
            updateDesktopNav();
          }
        });
        
        desktopNextBtn.addEventListener('click', () => {
          if (currentDesktopSection < totalDesktopSections) {
            currentDesktopSection++;
            scrollToDesktopSection();
            updateDesktopNav();
          }
        });
        
        // Accept button for desktop
        acceptDesktopBtn.addEventListener('click', () => {
          showAcknowledgementModal();
        });
      }
      
      function scrollToDesktopSection() {
        const section = document.getElementById(`section${currentDesktopSection}`);
        if (section) {
          section.scrollIntoView({ behavior: 'smooth' });
        }
      }
      
      function updateDesktopNav() {
        currentSectionSpan.textContent = currentDesktopSection;
        desktopPrevBtn.disabled = currentDesktopSection === 1;
        desktopNextBtn.disabled = currentDesktopSection === totalDesktopSections;
      }
      
      // Acknowledgement Modal
      function showAcknowledgementModal() {
        ackModal.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
      
      function hideAcknowledgementModal() {
        ackModal.classList.remove('active');
        document.body.style.overflow = '';
      }
      
      // Toggle mobile menu
      menuToggle.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        const icon = menuToggle.querySelector('i');
        if (navLinks.classList.contains('active')) {
          icon.classList.remove('fa-bars');
          icon.classList.add('fa-times');
        } else {
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      });

      // Hide mobile menu when a link is clicked
      const links = navLinks.querySelectorAll('a');
      links.forEach(link => {
        link.addEventListener('click', function() {
          if (navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
            const icon = menuToggle.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
          }
        });
      });

      // Theme toggle functionality
      function toggleTheme() {
        const body = document.body;
        const isDark = body.classList.contains('dark-theme');
        
        if (isDark) {
          body.classList.remove('dark-theme');
          localStorage.setItem('theme', 'light');
        } else {
          body.classList.add('dark-theme');
          localStorage.setItem('theme', 'dark');
        }
        
        updateThemeIcons();
      }
      
      function updateThemeIcons() {
        const body = document.body;
        const isDark = body.classList.contains('dark-theme');
        const icons = document.querySelectorAll('.theme-toggle i');
        
        icons.forEach(icon => {
          if (isDark) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
          } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
          }
        });
      }
      
      // Check for saved theme preference
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
      }
      updateThemeIcons();
      
      // Add dark theme styles
      const style = document.createElement('style');
      style.textContent = `
        body.dark-theme {
          background: #1a1a1a;
          color: #f0f0f0;
        }
        
        body.dark-theme .terms-content,
        body.dark-theme .mobile-swipe-slide {
          background: #2d2d2d;
          color: #e0e0e0;
        }
        
        body.dark-theme .terms-section p,
        body.dark-theme .terms-section li,
        body.dark-theme .highlight-box p,
        body.dark-theme .mobile-swipe-slide p,
        body.dark-theme .mobile-swipe-slide li {
          color: #d0d0d0;
        }
        
        body.dark-theme .terms-section h2,
        body.dark-theme .terms-section h3,
        body.dark-theme .mobile-swipe-slide h2,
        body.dark-theme .mobile-swipe-slide h3 {
          color: #8ab4f8;
        }
        
        body.dark-theme .highlight-box {
          background: #1e3a5f;
          border-left-color: #3C87C4;
        }
        
        body.dark-theme .ack-modal-content {
          background: #2d2d2d;
          color: #f0f0f0;
        }
        
        body.dark-theme .nav-links {
          background-color: #2d2d2d;
        }
        
        body.dark-theme .nav-links a {
          color: #f0f0f0;
        }
        
        body.dark-theme .terms-section {
          border-bottom-color: #444;
        }
        
        body.dark-theme .swipe-instruction {
          color: #aaa;
        }
      `;
      document.head.appendChild(style);
      
      // Add event listeners for theme toggles
      if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
      }
      
      if (mobileThemeToggle) {
        mobileThemeToggle.addEventListener('click', toggleTheme);
      }

      // Email button functionality
      if (emailBtn) {
        emailBtn.addEventListener('click', function() {
          window.location.href = 'mailto:katibayan.system@gmail.com?subject=Terms and Conditions Inquiry&body=Hello KatiBayan Team, I have a question about your Terms and Conditions:';
        });
      }

      // Modal buttons
      if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
          alert('Thank you for accepting the Terms and Conditions. You may now proceed to use the KatiBayan Web Portal.');
          localStorage.setItem('termsAccepted', 'true');
          hideAcknowledgementModal();
          // Redirect to home or registration page
          // window.location.href = 'index.html';
        });
      }
      
      if (declineBtn) {
        declineBtn.addEventListener('click', function() {
          alert('You must accept the Terms and Conditions to use the KatiBayan Web Portal.');
          hideAcknowledgementModal();
        });
      }

      // Scroll to top functionality
      if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
          if (window.pageYOffset > 300) {
            scrollTopBtn.style.display = 'flex';
          } else {
            scrollTopBtn.style.display = 'none';
          }
        });

        scrollTopBtn.addEventListener('click', function() {
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        });
      }

      // Smooth scrolling for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          if (this.getAttribute('href') === '#') return;
          
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 80,
              behavior: 'smooth'
            });
          }
        });
      });

      // Initialize based on device
      function initBasedOnDevice() {
        if (window.innerWidth <= 768) {
          initMobileSwipe();
        } else {
          initDesktopNavigation();
        }
      }
      
      // Check on load and resize
      initBasedOnDevice();
      window.addEventListener('resize', initBasedOnDevice);

      // Check if terms were previously accepted
      if (localStorage.getItem('termsAccepted') === 'true') {
        console.log('Terms and Conditions previously accepted.');
      }
    });
  </script>
</body>
</html>