<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Attendance</title>
  <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
  
  <!-- Sidebar (same as your existing sidebar) -->
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

      <a href="{{ route('eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <a href="#">
        <i data-lucide="megaphone"></i>
        <span class="label">Announcements</span>
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

    <!-- Topbar (same as your existing topbar) -->
    <header class="topbar">
      <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div class="logo-text">
          <span class="title">Katibayan</span>
          <span class="subtitle">Web Portal</span>
        </div>
      </div>

      <div class="topbar-right">
        <div class="time">MON 10:00 <span>AM</span></div>

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
          <img src="https://i.pravatar.cc/80" alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="https://i.pravatar.cc/80" alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>Marijoy S. Novora</h4>
                <div class="profile-badge">
                  <span class="badge">KK- Member</span>
                  <span class="badge">19 yrs old</span>
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
            </ul>
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
        <button class="ok-btn">OK</button>
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
  </div>

  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === Lucide icons ===
      if (window.lucide) {
        lucide.createIcons();
      }

      // === Sidebar and topbar functionality (same as your existing code) ===
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');
      const profileItem = document.querySelector('.profile-item');
      const profileLink = profileItem?.querySelector('.profile-link');
      const profileWrapper = document.querySelector('.profile-wrapper');
      const profileToggle = document.getElementById('profileToggle');
      const notifWrapper = document.querySelector(".notification-wrapper");
      const notifBell = notifWrapper?.querySelector(".fa-bell");

      // Sidebar toggle
      if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('open');
        });
      }

      // Profile submenu
      if (profileItem && profileLink) {
        profileLink.addEventListener('click', (e) => {
          e.preventDefault();
          if (sidebar?.classList.contains('open')) {
            profileItem.classList.toggle('open');
          }
        });
      }

      // Close sidebar when clicking outside
      document.addEventListener('click', (e) => {
        if (sidebar && menuToggle && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
          profileItem?.classList.remove('open');
        }
      });

      // Topbar dropdowns
      if (profileToggle) {
        profileToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          profileWrapper?.classList.toggle('active');
          notifWrapper?.classList.remove('active');
        });
      }

      if (notifBell) {
        notifBell.addEventListener('click', (e) => {
          e.stopPropagation();
          notifWrapper?.classList.toggle('active');
          profileWrapper?.classList.remove('active');
        });
      }

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

      // === Modals ===
      const successModal = document.getElementById("successModal");
      const errorModal = document.getElementById("errorModal");
      const quitModal = document.getElementById("quitModal");
      const okBtns = document.querySelectorAll(".ok-btn");
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
        btn.addEventListener("click", closeAllModals);
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

      // === QR Scanner ===
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
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
          
          if (data.success && data.attendances.length > 0) {
            tbody.innerHTML = data.attendances.map(attendance => `
              <tr>
                <td>${attendance.event_title}</td>
                <td>${attendance.event_date}</td>
                <td>${attendance.event_time}</td>
                <td>${attendance.location}</td>
                <td>${attendance.attended_at}</td>
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
              <td colspan="5" class="error-text">Error loading records.</td>
            </tr>
          `;
        }
      }

      // Load attendance records on page load
      loadAttendanceRecords();
    });
  </script>
</body>
</html>