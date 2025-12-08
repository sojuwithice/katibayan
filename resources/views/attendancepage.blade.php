<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  </script>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
  <title>KatiBayan - Attendance</title>
  <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('dashboard.index') }}">
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

      <a href="{{ route('eventpage') }}" class="active events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>
      
      <a href="{{ route('evaluation') }}">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
      </a>

      <a href="{{ route('serviceoffers') }}">
        <i data-lucide="hand-heart"></i>
        <span class="label">Service Offer</span>
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

        <!-- Theme Toggle Button -->
        <button class="theme-toggle" id="themeToggle">
          <i data-lucide="moon"></i>
        </button>
        
        <!-- Notifications -->
        <div class="notification-wrapper">
          <i class="fas fa-bell"></i>
          @if(isset($notificationCount) && $notificationCount > 0)
            <span class="notif-count">{{ $notificationCount }}</span>
          @endif
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notification</strong>
              @if(isset($notificationCount) && $notificationCount > 0)
                <span>{{ $notificationCount }}</span>
              @endif
            </div>
            
            <ul class="notif-list">
              @if(isset($generalNotifications) && $generalNotifications->count() > 0)
                @foreach ($generalNotifications as $notif)
                  @php
                    $link = '#';
                    $onclickAction = '';
                    
                    if ($notif->type == 'certificate_schedule') {
                      $link = route('certificatepage'); 
                    } 
                    elseif ($notif->type == 'sk_request_approved' || $notif->type == 'App\Notifications\SkRequestAccepted') { 
                      $link = '#'; 
                      $onclickAction = 'openSetRoleModal(); return false;';
                    }

                    $title = $notif->data['title'] ?? $notif->title ?? 'Notification';
                    $message = $notif->data['message'] ?? $notif->message ?? 'You have a new notification.';
                  @endphp
                  
                  <li>
                    <a href="{{ $link }}" 
                       class="notif-link {{ $notif->is_read == 0 ? 'unread' : '' }}" 
                       data-id="{{ $notif->id }}"
                       @if($onclickAction) onclick="{{ $onclickAction }}" @endif>
                      
                      <div class="notif-dot-container">
                        @if ($notif->is_read == 0)
                          <span class="notif-dot"></span>
                        @else
                          <span class="notif-dot-placeholder"></span>
                        @endif
                      </div>

                      <div class="notif-main-content">
                        <div class="notif-header-line">
                          <strong>{{ $title }}</strong>
                          <span class="notif-timestamp">
                            {{ $notif->created_at->format('m/d/Y g:i A') }}
                          </span>
                        </div>
                        <p class="notif-message">{{ $message }}</p>
                      </div>
                    </a>
                  </li>
                @endforeach
              @endif

              @if(isset($unevaluatedActivities) && $unevaluatedActivities->count() > 0)
                @foreach($unevaluatedActivities as $activity)
                  <li>
                    <a href="{{ route('evaluation.show', $activity['id']) }}" class="notif-link unread" 
                       data-{{ $activity['type'] }}-id="{{ $activity['id'] }}">
                      
                      <div class="notif-dot-container">
                        <span class="notif-dot"></span>
                      </div>
                      
                      <div class="notif-main-content">
                        <div class="notif-header-line">
                          <strong>{{ ucfirst($activity['type']) }} Evaluation Required</strong>
                          <span class="notif-timestamp">
                            {{ $activity['created_at']->format('m/d/Y g:i A') }}
                          </span>
                        </div>
                        <p class="notif-message">Please evaluate "{{ $activity['title'] }}"</p>
                      </div>
                    </a>
                  </li>
                @endforeach
              @endif

              @if((!isset($generalNotifications) || $generalNotifications->isEmpty()) && (!isset($unevaluatedActivities) || $unevaluatedActivities->isEmpty()))
                <li class="no-notifications">
                  <p>No new notifications</p>
                </li>
              @endif
            </ul>
          </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="profile-wrapper">
          @php
            $user = Auth::user();
            $age = $user && $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';
            $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'Member';
            $skTitle = '';
            if ($user && !empty($user->sk_role)) {
              $skTitle = $user->sk_role; 
            } 
            elseif ($user && $user->role === 'sk_chairperson') {
              $skTitle = 'SK Chairperson';
            }
            $isSkOfficial = $user && (!empty($user->sk_role) || $user->role === 'sk_chairperson');
          @endphp
          
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
                   
              <div class="profile-info">
                <h4>{{ $user ? $user->given_name . ' ' . $user->middle_name . ' ' . $user->last_name . ' ' . $user->suffix : 'Guest' }}</h4>
                
                <div class="badges-wrapper">
                  <div class="profile-badge">
                    <span class="badge">{{ $roleBadge }}</span>
                    <span class="badge">{{ $age }} yrs old</span>
                  </div>

                  @if($skTitle)
                    <div class="profile-badge sk-badge-yellow">
                      <span>{{ $skTitle }}</span>
                    </div>
                  @endif
                </div>
              </div>
            </div>
            <hr>

            <div class="profile-button-container">
              @if($isSkOfficial)
                <a href="{{ route('sk.role.view') }}" class="profile-sk-button">
                  Switch to SK Role
                </a>
              @else
                <a href="#" class="profile-sk-button" id="accessSKRoleBtn" data-url="{{ route('sk.request.access') }}">
                  Access SK role
                </a>
              @endif
            </div>

            <ul class="profile-menu">
              <li>
                <a href="{{ route('profilepage') }}">
                  <i class="fas fa-user"></i> Profile
                </a>
              </li>
              
              <li>
                <a href="{{ route('faqs') }}">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>

              <li>
                <a href="#" id="openFeedbackBtn">
                  <i class="fas fa-star"></i> Send Feedback to Katibayan
                </a>
              </li>
              
              <li class="logout-item">
                <a href="loginpage" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- QR Scanner Section -->
    <div class="qr-container">
      <button class="qr-close">&times;</button>
      <div class="qr-header">
        <h2>Scan QR Code for Attendance</h2>
        <p>Place the QR code properly inside the area. Scanning will start automatically.</p>
      </div>

      <!-- Scanner -->
      <div id="reader" class="qr-reader"></div>

      <!-- Manual Entry -->
      <div class="manual-entry">
        <p>Having trouble scanning the QR code?<br>Enter the passcode manually below.</p>
        <input type="text" id="manualCode" placeholder="Enter event passcode">
        <button id="submitCode">Submit Attendance</button>
      </div>

      <p class="note">This will mark your attendance in the program.</p>
    </div>

    <!-- Attendance Records Section -->
    <div class="attendance-records">
      <h2>My Attendance Records</h2>
      <div class="records-container">
        <table class="attendance-table">
          <thead>
            <tr>
              <th>Event</th>
              <th>Date</th>
              <th>Time</th>
              <th>Location</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="attendanceRecordsBody">
            <tr>
              <td colspan="5" class="loading-text">Loading attendance records...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
      <div class="modal-content">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h2>Attendance Successful!</h2>
        <p id="successMessage">
          Your attendance has been recorded successfully.
        </p>
        <div class="event-details" id="eventDetails" style="display: none;">
          <p><strong>Event:</strong> <span id="eventTitle"></span></p>
          <p><strong>Date:</strong> <span id="eventDate"></span></p>
          <p><strong>Time:</strong> <span id="eventTime"></span></p>
        </div>
        <button class="ok-btn" id="successOkBtn">OK</button>
      </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
      <div class="modal-content error">
        <div class="error-icon">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <h2>Attendance Failed</h2>
        <p id="errorMessage"></p>
        <button class="ok-btn">OK</button>
      </div>
    </div>

    <!-- Quit Confirmation Modal -->
    <div id="quitModal" class="modal">
      <div class="modal-content">
        <p>Are you sure you don't want to mark attendance for this event?</p>
        <div class="modal-actions">
          <button class="cancel-btn">Cancel</button>
          <button class="quit-btn">Quit</button>
        </div>
      </div>
    </div>

    <!-- SK Access Modal -->
    <div id="skAccessModal" class="modal" style="display: none;">
      <div class="sk-modal-box"> 
        <div class="modal-step active" data-step="1">
          <h2>Do you want to request access?</h2>
          <p>This will send a request to your SK Chairperson for permission to access your SK role. Would you like to proceed?</p>
          
          <div class="modal-actions" style="justify-content: flex-end;">
            <button type="button" class="btn btn-cancel" data-action="close">Cancel</button>
            <button type="button" class="btn btn-confirm" data-action="confirm-request">Yes</button>
          </div>
        </div>

        <div class="modal-step" data-step="2">
          <div class="spinner"></div>
          <p>Please wait while we send your request to the SK Chairperson. This will just take a moment.</p>
        </div>

        <div class="modal-step" data-step="3">
          <div class="modal-icon-wrapper success">
            <i class="fas fa-check"></i>
          </div>
          <h2>Request Sent</h2>
          <p>Thank you. Your request has been submitted. You will be notified once it is reviewed and approved.</p>
          <div class="modal-actions">
            <button type="button" class="btn btn-confirm" data-action="close">OK</button>
          </div>
        </div>

        <div class="modal-step" data-step="4">
          <div class="modal-icon-wrapper error">
            <i class="fas fa-exclamation-triangle"></i> 
          </div>
          <h2>Something went wrong</h2>
          <p>Please check your network and try again.</p>
          <div class="modal-actions">
            <button type="button" class="btn btn-confirm" data-action="try-again">Try again</button>
          </div>
        </div>
      </div> 
    </div>

    <!-- Set Role Modal -->
    <div id="setRoleModal" class="modal" style="display: none;">
      <div class="set-role-modal-content">
        <h2>Choose your role as SK</h2>
        
        <form id="setRoleForm">
          <div class="role-options-list">
            <label class="role-option">
              <input type="radio" name="sk_role" value="Kagawad" checked>
              <span class="radio-circle"></span>
              <span class="role-name">Kagawad</span>
            </label>
            
            <label class="role-option">
              <input type="radio" name="sk_role" value="Secretary">
              <span class="radio-circle"></span>
              <span class="role-name">Secretary</span>
            </label>
            
            <label class="role-option">
              <input type="radio" name="sk_role" value="Treasurer">
              <span class="radio-circle"></span>
              <span class="role-name">Treasurer</span>
            </label>
          </div>
          
          <div class="modal-actions">
            <button type="submit" class="btn btn-confirm">Set Role</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Initialize Lucide icons
      if (window.lucide) {
        lucide.createIcons();
      }

      // Theme Toggle Function
      function applyTheme(isDark) {
        const body = document.body;
        const themeToggle = document.getElementById('themeToggle');
        
        const icon = isDark ? 'sun' : 'moon';

        if (themeToggle) {
          themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
        }

        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
        
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
      }

      // Initialize theme
      const savedTheme = localStorage.getItem('theme') === 'dark';
      applyTheme(savedTheme);

      const themeToggle = document.getElementById('themeToggle');
      if (themeToggle) {
        themeToggle.addEventListener('click', () => {
          const isDark = document.documentElement.getAttribute('data-theme') === 'light';
          applyTheme(isDark);
        });
      }

      // Update Time
      function updateTime() {
        const timeEl = document.querySelector(".time");
        if (!timeEl) return;

        const now = new Date();
        const shortWeekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
        const shortMonths = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
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

      // Initialize Sidebar
      function initSidebar() {
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        const profileItem = document.querySelector('.profile-item');
        const profileLink = profileItem?.querySelector('.profile-link');

        if (menuToggle && sidebar) {
          menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('open');
            if (!sidebar.classList.contains('open')) {
              profileItem?.classList.remove('open');
            }
          });
        }

        if (profileItem && profileLink) {
          profileLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (sidebar.classList.contains('open')) {
              const isOpen = profileItem.classList.contains('open');
              profileItem?.classList.remove('open');
              if (!isOpen) profileItem.classList.add('open');
            }
          });
        }
      }

      initSidebar();

      // Initialize Topbar
      const notifWrapper = document.querySelector(".notification-wrapper");
      const profileWrapper = document.querySelector(".profile-wrapper");
      const profileToggle = document.getElementById("profileToggle");

      // Notifications Dropdown
      if (notifWrapper) {
        const bell = notifWrapper.querySelector(".fa-bell");
        bell?.addEventListener("click", (e) => {
          e.stopPropagation();
          notifWrapper.classList.toggle("active");
          profileWrapper?.classList.remove("active");
        });

        const dropdown = notifWrapper.querySelector(".notif-dropdown");
        dropdown?.addEventListener("click", (e) => e.stopPropagation());
      }

      // Profile Dropdown
      if (profileWrapper && profileToggle) {
        profileToggle.addEventListener("click", (e) => {
          e.stopPropagation();
          profileWrapper.classList.toggle("active");
          notifWrapper?.classList.remove("active");
        });

        const profileDropdown = profileWrapper.querySelector(".profile-dropdown");
        profileDropdown?.addEventListener("click", (e) => e.stopPropagation());
      }

      // Global Click Listener
      document.addEventListener("click", (e) => {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (window.innerWidth > 768 && sidebar && menuToggle && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
          document.querySelector('.profile-item')?.classList.remove('open');
        }

        if (profileWrapper && !profileWrapper.contains(e.target)) {
          profileWrapper.classList.remove('active');
        }
        if (notifWrapper && !notifWrapper.contains(e.target)) {
          notifWrapper.classList.remove('active');
        }
      });

      // Initialize QR Scanner
      const qrReaderEl = document.getElementById("reader");
      let html5QrCode = null;

      if (qrReaderEl && window.Html5Qrcode) {
        try {
          let lastResult = null;
          
          function onScanSuccess(decodedText) {
            if (decodedText === lastResult) return;
            lastResult = decodedText;

            console.log("Scanned passcode:", decodedText);
            markAttendance(decodedText);
          }

          html5QrCode = new Html5Qrcode("reader");
          html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            (err) => console.warn("QR scan error", err)
          );
        } catch (err) {
          console.error("QR Scanner init failed:", err);
          showErrorModal("Failed to initialize QR scanner. Please use manual entry.");
        }
      }

      // === Modals ===
      const successModal = document.getElementById("successModal");
      const errorModal = document.getElementById("errorModal");
      const quitModal = document.getElementById("quitModal");
      const okBtns = document.querySelectorAll(".ok-btn");
      const successOkBtn = document.getElementById("successOkBtn");
      const qrCloseBtn = document.querySelector(".qr-close");
      const cancelBtn = quitModal?.querySelector(".cancel-btn");
      const quitBtn = quitModal?.querySelector(".quit-btn");

      function showSuccessModal(eventData = null) {
        if (eventData) {
          document.getElementById('eventTitle').textContent = eventData.title;
          document.getElementById('eventDate').textContent = eventData.date;
          document.getElementById('eventTime').textContent = eventData.time;
          document.getElementById('eventDetails').style.display = 'block';
        }
        successModal.style.display = "flex";
      }

      function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        errorModal.style.display = "flex";
      }

      function closeAllModals() {
        successModal.style.display = "none";
        errorModal.style.display = "none";
        quitModal.style.display = "none";
      }

      // Modal event listeners
      okBtns.forEach(btn => {
        if (btn !== successOkBtn) {
          btn.addEventListener("click", closeAllModals);
        }
      });

      successOkBtn?.addEventListener("click", () => {
        closeAllModals();
      });

      qrCloseBtn?.addEventListener("click", () => {
        quitModal.style.display = "flex";
      });

      cancelBtn?.addEventListener("click", closeAllModals);

      quitBtn?.addEventListener("click", () => {
        window.location.href = "{{ route('eventpage') }}";
      });

      // Close modals when clicking outside
      [successModal, errorModal, quitModal].forEach(modal => {
        modal?.addEventListener("click", (e) => {
          if (e.target === modal) {
            closeAllModals();
          }
        });
      });

      // === Manual Entry ===
      const submitCodeBtn = document.getElementById("submitCode");
      const manualInput = document.getElementById("manualCode");

      submitCodeBtn?.addEventListener("click", () => {
        const passcode = manualInput?.value.trim();
        if (passcode) {
          markAttendance(passcode);
        } else {
          showErrorModal("Please enter a passcode.");
        }
      });

      manualInput?.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
          const passcode = manualInput.value.trim();
          if (passcode) {
            markAttendance(passcode);
          }
        }
      });

      // === Attendance Function ===
      async function markAttendance(passcode) {
        try {
          const response = await fetch('/attendance/mark', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ passcode })
          });

          const data = await response.json();

          if (data.success) {
            showSuccessModal({
              title: data.event?.title || '',
              date: data.event?.date || '',
              time: data.event?.time || ''
            });
            // Reload attendance records after successful attendance
            loadAttendanceRecords();
          } else {
            showErrorModal(data.error || data.message || 'Failed to mark attendance.');
          }
        } catch (err) {
          console.error('Fetch error:', err);
          showErrorModal('Network error. Please try again.');
        }
      }

      // === Load Attendance Records ===
      async function loadAttendanceRecords() {
        try {
          const response = await fetch("{{ route('attendance.my') }}");
          const data = await response.json();

          const tbody = document.getElementById('attendanceRecordsBody');
          
          if (data.success && data.attendances && data.attendances.length > 0) {
            tbody.innerHTML = data.attendances.map(attendance => `
              <tr>
                <td>${attendance.event_title || 'N/A'}</td>
                <td>${attendance.date || attendance.attended_at?.split(' ')[0] || 'N/A'}</td>
                <td>${attendance.time || attendance.attended_at?.split(' ')[1] || 'N/A'}</td>
                <td>${attendance.location || 'N/A'}</td>
                <td><span style="color: #28a745; font-weight: bold;">${attendance.status || 'Attended'}</span></td>
              </tr>
            `).join('');
          } else {
            tbody.innerHTML = `
              <tr>
                <td colspan="5" class="no-records">No attendance records found.</td>
              </tr>
            `;
          }
        } catch (error) {
          console.error("Error loading attendance records:", error);
          document.getElementById('attendanceRecordsBody').innerHTML = `
            <tr>
              <td colspan="5" class="error-text">Error loading records. Please try again later.</td>
            </tr>
          `;
        }
      }

      // Load attendance records on page load
      loadAttendanceRecords();

      // === SK Access Modal Functions ===
      const skModal = document.getElementById('skAccessModal');
      const openModalBtn = document.getElementById('accessSKRoleBtn');

      function showModalStep(stepNumber) {
        if (!skModal) return;
        skModal.querySelectorAll('.modal-step').forEach(step => {
          step.classList.remove('active');
          step.style.display = 'none';
        });
        const activeStep = skModal.querySelector(`.modal-step[data-step="${stepNumber}"]`);
        if (activeStep) {
          activeStep.classList.add('active');
          activeStep.style.display = 'block';
        }
      }

      function closeSkModal() {
        if (skModal) skModal.style.display = 'none';
      }

      async function handleSubmitRequest() {
        console.log("Sending request to backend...");
        showModalStep(2);

        const btn = document.getElementById('accessSKRoleBtn');
        const skAccessUrl = btn?.dataset.url || '/sk/request-access';

        try {
          const response = await fetch(skAccessUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': window.csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ _token: window.csrfToken })
          });

          const data = await response.json();

          if (response.ok) {
            showModalStep(3);
          } else {
            console.error(data.message);
            const errText = skModal.querySelector('.modal-step[data-step="4"] p');
            if(errText) errText.textContent = data.message || 'Failed to submit request.';
            showModalStep(4);
          }
        } catch (error) {
          console.error('Fetch error:', error);
          showModalStep(4);
        }
      }

      if (openModalBtn) {
        openModalBtn.addEventListener('click', function(e) {
          e.preventDefault();
          showModalStep(1);
          skModal.style.display = "flex";
        });
      }

      if (skModal) {
        skModal.addEventListener('click', function(e) {
          const action = e.target.dataset.action;
          if (!action) return;

          switch (action) {
            case 'close':
              closeSkModal();
              break;
            case 'confirm-request':
              handleSubmitRequest();
              break;
            case 'try-again':
              handleSubmitRequest();
              break;
          }
        });
      }

      // === Set Role Modal ===
      const setRoleModal = document.getElementById('setRoleModal');
      const setRoleForm = document.getElementById('setRoleForm');

      window.openSetRoleModal = function() {
        if (setRoleModal) {
          setRoleModal.style.display = "flex";
          console.log('Opening Set Role Modal...');
        } else {
          console.error('Error: Cannot find modal with id "setRoleModal"');
        }
      };

      window.addEventListener('click', function(e) {
        if (e.target === setRoleModal) {
          setRoleModal.style.display = 'none';
        }
      });

      if (setRoleForm) {
        setRoleForm.addEventListener('submit', async function(e) {
          e.preventDefault();

          const btn = setRoleForm.querySelector('button[type="submit"]');
          const originalText = btn.textContent;
          
          btn.textContent = "Saving...";
          btn.disabled = true;

          const formData = new FormData(setRoleForm);
          const selectedRole = formData.get('sk_role');

          try {
            const response = await fetch('/sk/set-role', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
              },
              body: JSON.stringify({ role: selectedRole })
            });

            const data = await response.json();

            if (response.ok) {
              alert('Role set successfully! Redirecting...');
              window.location.reload();
            } else {
              alert(data.message || 'Failed to set role.');
              btn.textContent = originalText;
              btn.disabled = false;
            }
          } catch (error) {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
            btn.textContent = originalText;
            btn.disabled = false;
          }
        });
      }

      // === Mark Notifications as Read ===
      function initMarkAsRead() {
        document.querySelectorAll('.notif-link[data-id]').forEach(link => {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            const notifId = this.dataset.id;
            const destinationUrl = this.href;

            const notifItem = this.closest('li');
            notifItem?.remove();

            const countEl = document.querySelector('.notif-count');
            if (countEl) {
              let currentCount = parseInt(countEl.textContent) || 0;
              countEl.textContent = Math.max(0, currentCount - 1);
              if (parseInt(countEl.textContent) === 0) {
                countEl.remove();
              }
            }

            const notifList = document.querySelector('.notif-list');
            if (notifList && notifList.children.length === 0) {
              notifList.innerHTML = `<li class="no-notifications"><p>No new notifications</p></li>`;
            }

            fetch(`/notifications/${notifId}/read`, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': window.csrfToken,
                  'Accept': 'application/json',
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: notifId })
              })
              .then(res => res.json())
              .then(data => {
                if (!data.success) console.error('Error marking notification as read:', data.message);
              })
              .catch(err => console.error('Fetch error:', err))
              .finally(() => {
                if (destinationUrl && destinationUrl !== '#') {
                  window.location.href = destinationUrl;
                }
              });
          });
        });
      }

      initMarkAsRead();

      // === Logout Confirmation ===
      function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      }

      // Add logout event listener
      const logoutLink = document.querySelector('.logout-item a');
      if (logoutLink) {
        logoutLink.addEventListener('click', confirmLogout);
      }
    });

    // Mobile Menu Toggle
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.querySelector('.sidebar');

    mobileBtn?.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');
      document.body.classList.toggle('mobile-sidebar-active');
    });

    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 768 &&
        sidebar.classList.contains('open') &&
        !sidebar.contains(e.target) &&
        !mobileBtn.contains(e.target)) {
        
        sidebar.classList.remove('open');
        document.body.classList.remove('mobile-sidebar-active');
      }
    });
  </script>
</body>
</html>