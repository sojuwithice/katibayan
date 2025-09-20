<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Profile Page</title>
  <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('dashboard.index') }}" class="active">
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

    <!-- Topbar -->
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
    <h2>Scan QR Code</h2>
    <p>Place the QR code properly inside the area. Scanning will start automatically.</p>
  </div>

  <!-- Scanner -->
  <div id="reader" class="qr-reader"></div>

  <!-- Manual Entry -->
  <div class="manual-entry">
    <p>Having trouble scanning the QR code?<br>Enter the code manually below.</p>
    <input type="text" id="manualCode" placeholder="Enter the code">
    <button id="submitCode">Enter</button>
  </div>

  <p class="note">This will mark your attendance in the program.</p>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
  <div class="modal-content">
    <h2>Congratulations</h2>
    <p>
      You are accepted to attend the program! <br>
      Please wait for the evaluation to be completed. Thank you!
    </p>
    <button class="ok-btn">OK</button>
  </div>
</div>

<!-- Quit Confirmation Modal -->
<div id="quitModal" class="modal">
  <div class="modal-content">
    <p>Are you sure you don’t want to join this event?</p>
    <div class="modal-actions">
      <button class="cancel-btn">Cancel</button>
      <button class="quit-btn">Quit</button>
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

  // === Elements ===
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');

  // Submenus
  const profileItem = document.querySelector('.profile-item');
  const profileLink = profileItem?.querySelector('.profile-link');
  const eventsItem = document.querySelector('.events-item');
  const eventsLink = eventsItem?.querySelector('.events-link');

  // Profile & notifications dropdown (topbar)
  const profileWrapper = document.querySelector('.profile-wrapper');
  const profileToggle = document.getElementById('profileToggle');
  const profileDropdown = document.querySelector('.profile-dropdown');

  const notifWrapper = document.querySelector(".notification-wrapper");
  const notifBell = notifWrapper?.querySelector(".fa-bell");
  const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

  // === Sidebar toggle ===
  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');

      if (!sidebar.classList.contains('open')) {
        profileItem?.classList.remove('open');
        eventsItem?.classList.remove('open');
      }
    });
  }

  // Helper: close all submenus
  function closeAllSubmenus() {
    profileItem?.classList.remove('open');
    eventsItem?.classList.remove('open');
  }

  // === Profile submenu toggle ===
  if (profileItem && profileLink) {
    profileLink.addEventListener('click', (e) => {
      e.preventDefault();
      if (sidebar?.classList.contains('open')) {
        const isOpen = profileItem.classList.contains('open');
        closeAllSubmenus();
        if (!isOpen) profileItem.classList.add('open');
      }
    });
  }

  // === Events submenu toggle ===
  if (eventsItem && eventsLink) {
    eventsLink.addEventListener('click', (e) => {
      e.preventDefault();
      if (sidebar?.classList.contains('open')) {
        const isOpen = eventsItem.classList.contains('open');
        closeAllSubmenus();
        if (!isOpen) eventsItem.classList.add('open');
      }
    });
  }

  // === Close sidebar when clicking outside ===
  document.addEventListener('click', (e) => {
    if (sidebar && menuToggle && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
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
      profileWrapper?.classList.toggle('active');
      notifWrapper?.classList.remove('active');
    });
  }

  profileDropdown?.addEventListener('click', e => e.stopPropagation());

  // === Notifications dropdown toggle ===
  if (notifBell) {
    notifBell.addEventListener('click', (e) => {
      e.stopPropagation();
      notifWrapper?.classList.toggle('active');
      profileWrapper?.classList.remove('active');
    });
  }

  notifDropdown?.addEventListener('click', e => e.stopPropagation());

  // === Calendar ===
  const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
  const daysContainer = document.querySelector(".calendar .days");
  const header = document.querySelector(".calendar header h3");
  let today = new Date();
  let currentView = new Date();

  const holidays = [
    "2025-01-01", "2025-04-09", "2025-04-17", "2025-04-18",
    "2025-05-01", "2025-06-06", "2025-06-12", "2025-08-25",
    "2025-11-30", "2025-12-25", "2025-12-30"
  ];

  function renderCalendar(baseDate) {
    if (!daysContainer || !header) return;
    daysContainer.innerHTML = "";

    const startOfWeek = new Date(baseDate);
    startOfWeek.setDate(baseDate.getDate() - (baseDate.getDay() === 0 ? 6 : baseDate.getDay() - 1));

    const middleDay = new Date(startOfWeek);
    middleDay.setDate(startOfWeek.getDate() + 3);
    header.textContent = middleDay.toLocaleDateString("en-US", { month: "long", year: "numeric" });

    for (let i = 0; i < 7; i++) {
      const thisDay = new Date(startOfWeek);
      thisDay.setDate(startOfWeek.getDate() + i);

      const dayEl = document.createElement("div");
      dayEl.classList.add("day");

      const weekdayEl = document.createElement("span");
      weekdayEl.classList.add("weekday");
      weekdayEl.textContent = weekdays[i];

      const dateEl = document.createElement("span");
      dateEl.classList.add("date");
      dateEl.textContent = thisDay.getDate();

      const month = (thisDay.getMonth() + 1).toString().padStart(2,'0');
      const day = thisDay.getDate().toString().padStart(2,'0');
      const dateStr = `${thisDay.getFullYear()}-${month}-${day}`;

      if (holidays.includes(dateStr)) dateEl.classList.add('holiday');

      if (
        thisDay.getDate() === today.getDate() &&
        thisDay.getMonth() === today.getMonth() &&
        thisDay.getFullYear() === today.getFullYear()
      ) dayEl.classList.add("active");

      dayEl.appendChild(weekdayEl);
      dayEl.appendChild(dateEl);
      daysContainer.appendChild(dayEl);
    }
  }

  renderCalendar(currentView);

  const prevBtn = document.querySelector(".calendar .prev");
  const nextBtn = document.querySelector(".calendar .next");
  prevBtn?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() - 7);
    renderCalendar(currentView);
  });
  nextBtn?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() + 7);
    renderCalendar(currentView);
  });

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

  // Example: MON, AUG 8 10:00 AM
  timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
}
updateTime();
setInterval(updateTime, 60000);


  // === Password toggle ===
  const tempPassword = document.getElementById('tempPassword');
  const toggleIcon = document.querySelector('.toggle-password');
  if (tempPassword && toggleIcon) {
    let hidden = true;
    const realPassword = "marijoy";
    toggleIcon.addEventListener('click', () => {
      if (hidden) {
        tempPassword.textContent = realPassword;
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
        hidden = false;
      } else {
        tempPassword.textContent = "•••••";
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
        hidden = true;
      }
    });
  }

  // === Modal ===
  const modalOverlay = document.getElementById('modalOverlay');
  const closeModal = document.getElementById('closeModal');
  const printBtns = document.querySelectorAll('.print-btn');

  printBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      if (modalOverlay) modalOverlay.style.display = 'flex';
    });
  });

  closeModal?.addEventListener('click', () => {
    if (modalOverlay) modalOverlay.style.display = 'none';
  });

  modalOverlay?.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
      modalOverlay.style.display = 'none';
    }
  });

  // === Success Modal ===
  const successModal = document.getElementById("successModal");
  const okBtn = successModal?.querySelector(".ok-btn");

  function showSuccessModal() {
    successModal.style.display = "flex";
  }
  function closeSuccessModal() {
    successModal.style.display = "none";
  }

  okBtn.addEventListener("click", () => {
  window.location.href = "{{ route('eventpage') }}";
});

  // === Quit Modal ===
  // === Quit Confirmation Modal ===
const quitModal = document.getElementById("quitModal");
const qrCloseBtn = document.querySelector(".qr-close");
const cancelBtn = quitModal?.querySelector(".cancel-btn");
const quitBtn = quitModal?.querySelector(".quit-btn");

// Show quit modal when clicking the X
qrCloseBtn?.addEventListener("click", () => {
  quitModal.style.display = "flex";
});

// Close when clicking Cancel
cancelBtn?.addEventListener("click", () => {
  quitModal.style.display = "none";
});

// Close when clicking outside the modal box
quitModal?.addEventListener("click", (e) => {
  if (e.target === quitModal) {
    quitModal.style.display = "none";
  }
});

// Handle Quit button
// Handle Quit button
quitBtn?.addEventListener("click", () => {
  quitModal.style.display = "none";
  window.location.href = "{{ route('eventpage') }}"; 
});



  // === QR Scanner ===
  const qrReaderEl = document.getElementById("reader");
  if (qrReaderEl && window.Html5Qrcode) {
    try {
      let lastResult = null;
      function onScanSuccess(decodedText) {
        if (decodedText === lastResult) return;
        lastResult = decodedText;

        console.log("Scanned:", decodedText);
        showSuccessModal();
      }

      const html5QrCode = new Html5Qrcode("reader");
      html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        onScanSuccess,
        (err) => console.warn("QR scan error", err)
      );
    } catch (err) {
      console.error("QR Scanner init failed:", err);
    }
  }

  // === Manual Entry ===
  const submitCodeBtn = document.getElementById("submitCode");
  const manualInput = document.getElementById("manualCode");

  submitCodeBtn?.addEventListener("click", () => {
    const code = manualInput?.value.trim();
    if (code) {
      console.log("Manual code entered:", code);
      showSuccessModal();
    } else {
      alert("Please enter a code.");
    }
  });
});
</script>
