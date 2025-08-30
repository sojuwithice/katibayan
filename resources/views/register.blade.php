<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Register</title>
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Icons -->
  <script src="https://unpkg.com/lucide-icons/dist/umd/lucide.js"></script>
</head>
<body>


  <!-- Terms and Conditions Modal -->
<div class="modal-overlay" id="termsModal">
  <div class="modal">
    <div class="modal-header">
      <h2>Terms and Conditions</h2>
    </div>
    <div class="modal-body">
      <h3>1. Acceptance of Terms</h3>
      <p>By creating an account and using this system, you agree to comply with and be bound by these Terms and Conditions. If you do not agree, you may not proceed with the registration or use the system.</p>

      <h3>2. Purpose of the System</h3>
      <p>This system is designed to collect, manage, and analyze user information for official purposes such as youth profiling, community development, governance, and service delivery.</p>

      <h3>3. User Responsibilities</h3>
      <p>You agree to provide accurate, complete, and updated information during registration. You are responsible for maintaining the confidentiality of your account credentials. You agree not to use the system for unlawful, fraudulent, or unauthorized activities.</p>

      <h3>4. Data Privacy and Security</h3>
      <p>You agree to provide accurate, complete, and updated information during registration. You are responsible for maintaining the confidentiality of your account credentials. You agree not to use the system for unlawful, fraudulent, or unauthorized activities.</p>
    </div>
    <div class="modal-footer">
      <button class="accept-btn" id="acceptBtn">I have read and accept the Term and Condition</button>
      <button class="cancel-btn" id="cancelBtn">Cancel</button>
    </div>
  </div>
</div>


  <!-- Header -->
  <header class="header">
    <div class="header-left">
      <div class="logo"></div>
      <h2>Welcome to KatiBayan</h2>
    </div>
    <button class="theme-toggle" id="themeToggle">
      <i data-lucide="sun"></i>
    </button>
  </header>

  <!-- Banner -->
  <section class="banner">
    <h1>REGISTER NOW</h1>
    <p>
      The <span class="highlight">KatiBayan</span> Web Portal helps you connect with your SK Leaders and become 
      an active youth member for a better community. So, what are you waiting for? Sign up now!
    </p>
  </section>

  <!-- Registration Portal -->
  <section class="form-container">
    <h2 class="form-title">Registration Portal</h2>

    <!-- Steps with icons -->
    <div class="steps">
      <div class="step active">
        <div class="circle"><i data-lucide="user"></i></div>
        <p>Personal Information</p>
      </div>
      <div class="step">
        <div class="circle"><i data-lucide="settings"></i></div>
        <p>Account Setup</p>
      </div>
      <div class="step">
        <div class="circle"><i data-lucide="check-square"></i></div>
        <p>Verification and Review</p>
      </div>
    </div>

    <!-- Divider before form -->
    <div class="steps-divider"></div>

    <!-- Form -->
    <form class="register-form">

      <!-- Profile -->
      <div class="profile-section">
        <div class="profile-header">
          <div>
            <h3>I. KK Profile</h3>
            <p>Enter your account details</p>
          </div>
          <div class="select-wrapper short-select">
            <input type="text" name="role" placeholder="Select your role (SK, KK, etc)" readonly>
            <ul class="dropdown-options">
              <li>SK</li>
              <li>KK</li>
              <li>Other</li>
            </ul>
            <span class="arrow"><i data-lucide="chevron-down"></i></span>
          </div>

        </div>

        <div class="form-grid">
          <input type="text" placeholder="Last Name">
          <input type="text" placeholder="Given Name">
          <input type="text" placeholder="Middle Name">
          <input type="text" placeholder="Suffix (optional)">
        </div>

        <div class="select-wrapper full-width">
          <input type="text" placeholder="Enter your full address">
        </div>

        <div class="form-grid-4">
          <div class="input-icon">
            <input type="date" id="dob" placeholder="Date of Birth">
            <span class="icon" id="calendarIcon"><i data-lucide="calendar"></i></span>
          </div>

          <div class="select-wrapper">
          <input type="text" name="sex" placeholder="Sex" readonly>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
          <ul class="dropdown-options">
            <li data-value="male">Male</li>
            <li data-value="female">Female</li>
          </ul>
        </div>


          <input type="email" placeholder="Email Address">
          <input type="tel" placeholder="Contact No.">
        </div>
      </div>

      <!-- Demographics -->
      <div class="section-header">
        <h3>II. Demographics</h3>
      </div>
      <div class="form-grid">
  <!-- Civil Status -->
  <div class="select-wrapper">
    <input type="text" name="civil_status" placeholder="Civil Status" readonly>
    <ul class="dropdown-options">
      <li>Single</li>
      <li>Married</li>
      <li>Widowed</li>
      <li>Divorced</li>
      <li>Separated</li>
      <li>Anulled</li>
      <li>Unknown</li>
      <li>Live-in</li>
    </ul>
    <span class="arrow"><i data-lucide="chevron-down"></i></span>
  </div>

  <!-- Educational Background -->
  <div class="select-wrapper">
    <input type="text" name="education" placeholder="Educational Background" readonly>
    <ul class="dropdown-options">
      <li>Elementary Level</li>
      <li>Elementary Graduate</li>
      <li>High School Level</li>
      <li>High School Graduate</li>
      <li>Vocational Graduate</li>
      <li>College Level</li>
      <li>College Graduate</li>
      <li>Masters Level</li>
      <li>Masters Graduate</li>
      <li>Doctorate Level</li>
      <li>Doctorate Graduate</li>
    </ul>
    <span class="arrow"><i data-lucide="chevron-down"></i></span>
  </div>

  <!-- Work Status -->
  <div class="select-wrapper">
    <input type="text" name="work_status" placeholder="Work Status" readonly>
    <ul class="dropdown-options">
      <li>Student</li>
      <li>Employed</li>
      <li>Unemployed</li>
      <li>Self-Employed</li>
      <li>Currently looking for a Job</li>
      <li>Not Interested Looking for a Job</li>
    </ul>
    <span class="arrow"><i data-lucide="chevron-down"></i></span>
  </div>
</div>

<div class="form-grid">
  <!-- Youth Classification -->
  <div class="select-wrapper">
    <input type="text" name="youth_classification" placeholder="Youth Classification" readonly>
    <ul class="dropdown-options">
      <li>In-School Youth</li>
      <li>Out-of-School Youth</li>
      <li>Working Youth</li>
      <li>Youth with Specific Needs</li>
      <li>Person with Disability (PWD)</li>
      <li>Children in Conflict with the Law (CICL)</li>
      <li>Indigenous People (IP)</li>
    </ul>
    <span class="arrow"><i data-lucide="chevron-down"></i></span>
  </div>

  <!-- SK Voter -->
  <div class="select-wrapper">
    <input type="text" name="sk_voter" placeholder="Are you a registered SK voter?" readonly>
    <ul class="dropdown-options">
      <li>Yes</li>
      <li>No</li>
    </ul>
    <span class="arrow"><i data-lucide="chevron-down"></i></span>
  </div>
</div>

    </form>
  </section>

  <!-- Form Actions (Outside Form) -->
<div class="form-actions">
  <a href="{{ url('/loginpage') }}" class="back-btn">
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
  </svg>
  Back to Login
</a>

  <button type="button" class="next-btn">Next</button>
</div>


  <!-- Footer Section -->
  <footer class="footer">
    <div class="footer-container">
      <!-- Logo -->
      <div class="footer-logo">
        <img src="{{ asset('images/sklogo.png') }}" alt="KatiBayan Logo" class="logo-img">
        <span>Sangguniang Kabataan</span>
      </div>

      <!-- Contact Info -->
      <div class="footer-contact">
        <h3>CONTACT INFORMATION</h3>
        <p>Address: SK Office, Barangay 3, EM’s Barrio East, Legazpi City, Albay</p>
        <p>Email: skbrrgy3emsbarrioeast@gmail.com</p>
        <p>Mobile: 09XX-XXX-XXXX</p>
        <p>Facebook Page: <a href="#">SK Brgy. 3 EM's Barrio East, Legazpi City</a></p>
      </div>

      <!-- Quick Links -->
      <div class="footer-links">
        <h3>QUICK LINKS</h3>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Programs & Events</a></li>
          <li><a href="#">Committees</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>

      <!-- Legal -->
      <div class="footer-legal">
        <h3>LEGAL</h3>
        <ul>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms and Conditions</a></li>
        </ul>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <p>© 2025 SK Barangay 3 | All Rights Reserved | Powered by KatiBayan</p>
    </div>
  </footer>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  // Render lucide icons
  lucide.createIcons();

  const themeToggle = document.getElementById('themeToggle');
  const body = document.body;

  // =====================
  // THEME TOGGLE
  // =====================
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    body.classList.add('dark-mode');
    themeToggle.innerHTML = `<i data-lucide="moon"></i>`;
  } else {
    body.classList.remove('dark-mode');
    themeToggle.innerHTML = `<i data-lucide="sun"></i>`;
  }
  lucide.createIcons();

  themeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    const isDark = body.classList.contains('dark-mode');
    const icon = isDark ? 'moon' : 'sun';

    themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
    lucide.createIcons();

    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  });

  // =====================
  // DATE PICKER ICON
  // =====================
  document.querySelectorAll('.input-icon').forEach(wrapper => {
    const dateInput = wrapper.querySelector('input[type="date"]');
    const calendarIcon = wrapper.querySelector('.icon');

    if (dateInput && calendarIcon) {
      calendarIcon.addEventListener('click', () => {
        if (dateInput.showPicker) {
          dateInput.showPicker(); 
        } else {
          dateInput.focus(); 
        }
      });
    }
  });

  // ============================
  // TERMS & CONDITIONS MODAL
  // ============================
  window.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("termsModal");
    const acceptBtn = document.getElementById("acceptBtn");
    const cancelBtn = document.getElementById("cancelBtn");

    if (modal) {
      // Show modal on page load
      modal.style.display = "flex";

      // Accept terms
      acceptBtn.addEventListener("click", () => {
        modal.style.display = "none";
      });

      // Cancel = redirect back 
      cancelBtn.addEventListener("click", () => {
        window.location.href = "/loginpage";
      });
    }
  });

  // ============================
  // CUSTOM SELECT DROPDOWN
  // ============================
  document.querySelectorAll(".select-wrapper").forEach(wrapper => {
    const input = wrapper.querySelector("input");
    const dropdown = wrapper.querySelector(".dropdown-options");

    if (!input || !dropdown) return; // skip kung walang laman

    // Toggle dropdown
    input.addEventListener("click", () => {
      document.querySelectorAll(".select-wrapper").forEach(w => {
        if (w !== wrapper) {
          w.classList.remove("open");
          const otherDropdown = w.querySelector(".dropdown-options");
          if (otherDropdown) otherDropdown.style.display = "none";
        }
      });
      wrapper.classList.toggle("open");
      dropdown.style.display = wrapper.classList.contains("open") ? "block" : "none";
    });

    // Select option
    dropdown.querySelectorAll("li").forEach(option => {
      option.addEventListener("click", () => {
        input.value = option.textContent;
        wrapper.classList.remove("open");
        dropdown.style.display = "none";
      });
    });
  });

  // Close if click outside
  document.addEventListener("click", e => {
    if (!e.target.closest(".select-wrapper")) {
      document.querySelectorAll(".select-wrapper").forEach(wrapper => {
        wrapper.classList.remove("open");
        const dropdown = wrapper.querySelector(".dropdown-options");
        if (dropdown) dropdown.style.display = "none";
      });
    }
  });
</script>


</body>
</html>
