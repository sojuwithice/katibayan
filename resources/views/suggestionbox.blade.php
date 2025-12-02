<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Suggestion Box</title>
  <link rel="stylesheet" href="{{ asset('css/suggestionbox.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  
    <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('dashboard.index') }}" >
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>
      <div class="profile-item nav-item">
        <a href="#" class="profile-link">
          <i data-lucide="circle-user"></i>
          <span class="label">Profile</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('profilepage') }}">My Profile</a>
          <a href="{{ route('certificatepage') }}">Certificates</a>
        </div>
      </div>

      <a href="{{ route('eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>
      
      <a href="{{ route('evaluation') }}">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
      </a>

      <a href="{{ route('serviceoffers') }}">
        <i data-lucide="hand-heart"></i>
        <span class="label">Service Offer </span>
      </a>
    </nav>
  </aside>

  <!-- Main -->
  <div class="main">

    <!-- Topbar -->
    <header class="topbar">
      <button id="mobileMenuBtn" class="mobile-hamburger">
  <i data-lucide="menu"></i>
</button>
      <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div class="logo-text">
          <span class="title">Katibayan</span>
          <span class="subtitle">Web Portal</span>
        </div>
      </div>

      <div class="topbar-right">
        <div class="time">MON 10:00 <span>AM</span></div>

          <button class="theme-toggle" id="themeToggle">
          <i data-lucide="moon"></i>
        </button>

        <!-- Notifications -->
        <div class="notification-wrapper">
          <i class="fas fa-bell"></i>
          <span class="notif-count">3</span>
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notification</strong> <span>3</span>
            </div>
            <ul class="notif-list">
              <li>
                <div class="notif-icon"></div>
                <div class="notif-content">
                  <strong>Program Evaluation</strong>
                  <p>We need evaluation for the KK-Assembly Event</p>
                </div>
                <span class="notif-dot"></span>
              </li>
              <li>
                <div class="notif-icon"></div>
                <div class="notif-content">
                  <strong>Program Evaluation</strong>
                  <p>We need evaluation for the KK-Assembly Event</p>
                </div>
                <span class="notif-dot"></span>
              </li>
              <li>
                <div class="notif-icon"></div>
                <div class="notif-content">
                  <strong>Program Evaluation</strong>
                  <p>We need evaluation for the KK-Assembly Event</p>
                </div>
                <span class="notif-dot"></span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Profile Avatar -->
        <div class="profile-wrapper">
          <img src="{{ isset($user) && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ isset($user) && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>
                  @if(isset($user))
                    {{ $user->given_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}
                  @else
                    Guest User
                  @endif
                </h4>
                <div class="profile-badge">
                  <span class="badge">{{ $roleBadge ?? 'GUEST' }}</span>
                  <span class="badge">{{ $age ?? 'N/A' }} yrs old</span>
                </div>
              </div>
            </div>
            <hr>
            <ul class="profile-menu">
              <li>
                <a href="{{ route('profilepage') }}">
                  <i class="fas fa-user"></i> Profile
                </a>
              </li>
              <li><i class="fas fa-cog"></i> Manage Password</li>
              <li>
                <a href="{{ route('faqspage') }}">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>
              <li><i class="fas fa-star"></i> Send Feedback to Katibayan</li>
              <li class="logout-item">
                <a href="#" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
            
            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- Suggestion Box -->
    <div class="suggestion-container">
      <div class="suggestion-header">
        <button class="back-btn" onclick="window.history.back()"><i class="fas fa-arrow-left"></i></button>
        <h2>Suggestion Box</h2>
      </div>
      <p class="subtitle">
        Your insights matter. Sharing your ideas helps us build a stronger connection with you.
      </p>

      <form class="suggestion-form" id="suggestionForm">
        @csrf
        <label for="committee">Select the committee for your suggested event or program.</label>
        <div class="custom-select">
          <div class="select-trigger">
            <span class="selected-text">Select Committee</span> 
            <i class="fas fa-chevron-down"></i>
          </div>
          <ul class="select-options">
            <li data-value="active">Active Citizenship</li>
            <li data-value="economic">Economic Empowerment</li>
            <li data-value="education">Education</li>
            <li data-value="health">Health</li>
            <li data-value="sports">Sports</li>
          </ul>
        </div>
        <input type="hidden" name="committee" id="committee" required>

        <label for="suggestions">Your suggestions and comments</label>
        <textarea id="suggestions" name="suggestions" rows="6" required minlength="10" maxlength="1000" placeholder="Please share your suggestions here..."></textarea>

        <button type="submit" class="submit-btn">
          Submit <i class="fas fa-paper-plane"></i>
        </button>
      </form>
    </div>
  </div> <!-- End Main -->

  <!-- Success Modal -->
  <div id="successModal" class="modal">
    <div class="modal-box">
      <div class="modal-icon"><i class="fas fa-check"></i></div>
      <h3>Submitted</h3>
      <p>Your suggestion has been submitted. <br>Thank you for sharing your ideas! This will help us a lot.</p>
      <button id="closeModalBtn" class="close-btn">OK</button>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {

      // === DARK/LIGHT MODE TOGGLE ===
      const body = document.body;
      const themeToggle = document.getElementById('themeToggle');

      // Function to apply theme
      function applyTheme(isDark) {
        body.classList.toggle('dark-mode', isDark);
        // Show sun when dark mode, moon when light mode
        const icon = isDark ? 'sun' : 'moon';

        if (themeToggle) {
          themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
        }

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
        
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
      }

      // Load saved theme
      const savedTheme = localStorage.getItem('theme') === 'dark';
      applyTheme(savedTheme);

      // Add event listener to theme toggle
      if (themeToggle) {
        themeToggle.addEventListener('click', () => {
          const isDark = !body.classList.contains('dark-mode');
          applyTheme(isDark);
        });
      }
      // === Lucide icons ===
      lucide.createIcons();

      // === Elements ===
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');

      // Submenus
      const profileItem = document.querySelector('.profile-item');
      const profileLink = profileItem?.querySelector('.profile-link');

      // Profile & notifications dropdown (topbar)
      const profileWrapper = document.querySelector('.profile-wrapper');
      const profileToggle = document.getElementById('profileToggle');
      const profileDropdown = document.querySelector('.profile-dropdown');

      const notifWrapper = document.querySelector(".notification-wrapper");
      const notifBell = notifWrapper?.querySelector(".fa-bell");
      const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

      // === Sidebar toggle ===
      menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');

        if (!sidebar.classList.contains('open')) {
          profileItem?.classList.remove('open');
        }
      });

      // Helper: close all submenus
      function closeAllSubmenus() {
        profileItem?.classList.remove('open');
      }

      // === Profile submenu toggle ===
      if (profileItem && profileLink) {
        profileLink.addEventListener('click', (e) => {
          e.preventDefault();
          if (sidebar.classList.contains('open')) {
            const isOpen = profileItem.classList.contains('open');
            closeAllSubmenus();
            if (!isOpen) profileItem.classList.add('open');
          }
        });
      }

      // === Close sidebar when clicking outside ===
      document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
          closeAllSubmenus();
        }

        if (profileWrapper && !profileWrapper.contains(e.target)) {
          profileWrapper.classList.remove('active');
        }

        if (notifWrapper && !notifWrapper.contains(e.target)) {
          notifWrapper.classList.remove('active');
        }
      });

      // === Profile dropdown toggle (topbar) ===
      if (profileToggle) {
        profileToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          profileWrapper.classList.toggle('active');
          notifWrapper?.classList.remove('active');
        });
      }

      profileDropdown?.addEventListener('click', e => e.stopPropagation());

      // === Notifications dropdown toggle ===
      if (notifBell) {
        notifBell.addEventListener('click', (e) => {
          e.stopPropagation();
          notifWrapper.classList.toggle('active');
          profileWrapper?.classList.remove('active');
        });
      }

      notifDropdown?.addEventListener('click', e => e.stopPropagation());

      // === Time auto-update ===
      const timeEl = document.querySelector(".time");
      function updateTime() {
        if (!timeEl) return;
        const now = new Date();

        const shortWeekdays = ["SUN","MON","TUE","WED","THU","FRI","SAT"];
        const shortMonths = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];

        const weekday = shortWeekdays[now.getDay()];
        const month = shortMonths[now.getMonth()];
        const day = now.getDate();

        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, "0");
        const ampm = hours >= 12 ? "PM" : "AM";
        hours = hours % 12 || 12;

        timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
      }
      updateTime();
      setInterval(updateTime, 60000);

      // === Custom Select Functionality ===
      const customSelect = document.querySelector('.custom-select');
      const trigger = customSelect.querySelector('.select-trigger');
      const selectedText = trigger.querySelector('.selected-text');
      const options = customSelect.querySelector('.select-options');
      const items = options.querySelectorAll('li');
      const hiddenInput = document.getElementById('committee');

      trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        options.style.display = options.style.display === 'block' ? 'none' : 'block';
        trigger.querySelector('i').style.transform = options.style.display === 'block' ? 'rotate(180deg)' : 'rotate(0deg)';
      });

      items.forEach(item => {
        item.addEventListener('click', (e) => {
          selectedText.textContent = item.textContent;
          hiddenInput.value = item.dataset.value;
          options.style.display = 'none';
          trigger.querySelector('i').style.transform = 'rotate(0deg)';
          e.stopPropagation();
        });
      });

      document.addEventListener('click', () => {
        options.style.display = 'none';
        trigger.querySelector('i').style.transform = 'rotate(0deg)';
      });

      // === Suggestion Form Submission ===
      const suggestionForm = document.getElementById('suggestionForm');
      const submitBtn = document.querySelector('.submit-btn');
      const modal = document.getElementById('successModal');
      const closeBtn = document.getElementById('closeModalBtn');

      suggestionForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate form
        const committee = hiddenInput.value;
        const suggestions = document.getElementById('suggestions').value;

        if (!committee) {
          alert('Please select a committee');
          return;
        }

        if (suggestions.length < 10) {
          alert('Please provide more detailed suggestions (at least 10 characters)');
          return;
        }

        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Submitting... <i class="fas fa-spinner fa-spin"></i>';

        try {
          const response = await fetch('/suggestions', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              committee: committee,
              suggestions: suggestions
            })
          });

          const data = await response.json();

          if (data.success) {
            // Show success modal
            modal.style.display = 'flex';
            // Reset form
            suggestionForm.reset();
            selectedText.textContent = 'Select Committee';
            hiddenInput.value = '';
          } else {
            alert('Failed to submit suggestion: ' + data.message);
          }
        } catch (error) {
          console.error('Error:', error);
          alert('An error occurred while submitting your suggestion. Please try again.');
        } finally {
          // Re-enable submit button
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Submit <i class="fas fa-paper-plane"></i>';
        }
      });

      // Close modal
      closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
      });

      // Click outside modal to close
      window.addEventListener('click', function(e) {
        if (e.target == modal) {
          modal.style.display = 'none';
        }
      });

      // Logout functionality
      window.confirmLogout = function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      };
    });
  </script>

  <script>
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const sidebar = document.querySelector('.sidebar');
  const mainContent = document.querySelector('.main'); // (BAGO)

  mobileBtn?.addEventListener('click', (e) => {
    e.stopPropagation(); // (BAGO)
    sidebar.classList.toggle('open');
    document.body.classList.toggle('mobile-sidebar-active'); // (BAGO)
  });

  // Close sidebar when clicking outside (mobile only)
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768 &&
      sidebar.classList.contains('open') && // (BAGO) Check kung open
      !sidebar.contains(e.target) &&
      !mobileBtn.contains(e.target)) {
      
      sidebar.classList.remove('open');
      document.body.classList.remove('mobile-sidebar-active'); // (BAGO)
    }
  });
</script>
</body>
</html>