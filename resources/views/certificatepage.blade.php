<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Profile Page</title>
  <link rel="stylesheet" href="{{ asset('css/certificatepage.css') }}">
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
      <a href="{{ route('dashboard.index') }}">
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>
      <div class="profile-item nav-item">
        <a href="#" class="profile-link active">
          <i data-lucide="circle-user"></i>
          <span class="label">Profile</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('profilepage') }}">My Profile</a>
          <a href="#" class="active">Certificates</a>
        </div>
      </div>

      <a href="#">
        <i data-lucide="calendar"></i>
        <span class="label">Calendar</span>
      </a>
      <a href="#">
        <i data-lucide="megaphone"></i>
        <span class="label">Announcements</span>
      </a>
      <a href="#">
        <i data-lucide="settings"></i>
        <span class="label">Settings</span>
      </a>
      <a href="#">
        <i data-lucide="user-star"></i>
        <span class="label">Evaluation</span>
      </a>
      <a href="#">
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
                    <span>KK-Member</span>
                    <span>19 yrs old</span>
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
              <li><i class="fas fa-question-circle"></i> FAQs</li>
              <li><i class="fas fa-star"></i> Send Feedback to Katibayan</li>
            </ul>
          </div>
        </div>
      </div>
    </header>

   <!-- Certificates Section -->
<section class="certificates">

  <!-- Header box with border -->
  <div class="certificates-header">
    <h2>Your Certificates</h2>
    <p>You have a total of 6 certificates.</p>
  </div>

  <!-- This Month -->
  <div class="certificates-group">
    <h3>This Month</h3>
    <div class="cert-grid">
      <div class="cert-card">
        <img src="{{ asset('images/certificate.png') }}" alt="Certificate">
        <div class="cert-info">
          <p class="cert-title">Certificate completed in:</p>
          <p class="cert-desc">International Day Against Drug Abuse and Illicit Trafficking</p>
          <button class="print-btn">Print with SK</button>
        </div>
      </div>

      <div class="cert-card">
        <img src="{{ asset('images/certificate.png') }}" alt="Certificate">
        <div class="cert-info">
          <p class="cert-title">Certificate completed in:</p>
          <p class="cert-desc">International Day Against Drug Abuse and Illicit Trafficking</p>
          <button class="print-btn">Print with SK</button>
        </div>
      </div>

      <div class="cert-card">
        <img src="{{ asset('images/certificate.png') }}" alt="Certificate">
        <div class="cert-info">
          <p class="cert-title">Certificate completed in:</p>
          <p class="cert-desc">International Day Against Drug Abuse and Illicit Trafficking</p>
          <button class="print-btn">Print with SK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Last Month -->
  <div class="certificates-group">
    <h3>Last Month</h3>
    <div class="cert-grid">
        <div class="cert-card">
    <div class="cert-img">
        <img src="{{ asset('images/certificate.png') }}" alt="Certificate">
    </div>
    <div class="cert-info">
        <div class="cert-text">
        <p class="cert-title">Certificate completed in:</p>
        <p class="cert-desc">International Day Against Drug Abuse and Illicit Trafficking</p>
        </div>
        <button class="print-btn">Print with SK</button>
    </div>
    </div>


      <div class="cert-card">
        <img src="{{ asset('images/certificate.png') }}" alt="Certificate">
        <div class="cert-info">
          <p class="cert-title">Certificate completed in:</p>
          <p class="cert-desc">International Day Against Drug Abuse and Illicit Trafficking</p>
          <button class="print-btn">Print with SK</button>
        </div>
      </div>

      <div class="cert-card">
        <img src="{{ asset('images/certificate.png') }}" alt="Certificate">
        <div class="cert-info">
          <p class="cert-title">Certificate completed in:</p>
          <p class="cert-desc">International Day Against Drug Abuse and Illicit Trafficking</p>
          <button class="print-btn">Print with SK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal-box">
    <div class="modal-icon">
      <i class="fa-solid fa-check"></i>
    </div>
    <h2>Request Submitted!</h2>
    <p>You’ll be notified once your certificate is ready for claiming.</p>
    <button id="closeModal">OK</button>
  </div>
</div>

</section>

























    <script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons ===
  lucide.createIcons();

  // === Elements ===
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  const profileItem = document.querySelector('.profile-item');
  const profileLink = profileItem.querySelector('.profile-link');

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
      profileItem.classList.remove('open'); // close submenu
    }
  });

  // === Profile submenu toggle ===
  profileLink.addEventListener('click', (e) => {
    e.preventDefault();
    if (sidebar.classList.contains('open')) {
      profileItem.classList.toggle('open');
    }
  });

  // === Close sidebar when clicking outside ===
  document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
      profileItem.classList.remove('open');
    }

    // Close profile dropdown if clicked outside
    if (!profileWrapper.contains(e.target)) {
      profileWrapper.classList.remove('active');
    }

    // Close notifications dropdown if clicked outside
    if (notifWrapper && !notifWrapper.contains(e.target)) {
      notifWrapper.classList.remove('active');
    }
  });

  // === Profile dropdown toggle ===
  if (profileToggle) {
    profileToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle('active');
      notifWrapper?.classList.remove('active'); // close notif if open
    });
  }

  if (profileDropdown) {
    profileDropdown.addEventListener('click', e => e.stopPropagation());
  }

  // === Notifications dropdown toggle ===
  if (notifBell) {
    notifBell.addEventListener('click', (e) => {
      e.stopPropagation();
      notifWrapper.classList.toggle('active');
      profileWrapper?.classList.remove('active'); // close profile if open
    });
  }

  if (notifDropdown) {
    notifDropdown.addEventListener('click', e => e.stopPropagation());
  }

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
    const weekday = shortWeekdays[now.getDay()];
    let hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, "0");
    const ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;
    timeEl.innerHTML = `${weekday} ${hours}:${minutes} <span>${ampm}</span>`;
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

  // Get elements
  const modalOverlay = document.getElementById('modalOverlay');
  const closeModal = document.getElementById('closeModal');
  const printBtns = document.querySelectorAll('.print-btn');

  // Open modal kapag na-click ang "Print with SK"
  printBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      modalOverlay.style.display = 'flex';
    });
  });

  // Close modal kapag nag-click ng OK
  closeModal.addEventListener('click', () => {
    modalOverlay.style.display = 'none';
  });

  // Optional: Close modal kapag nag-click sa labas ng box
  modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
      modalOverlay.style.display = 'none';
    }
  });
});

</script>