<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Profile Page</title>
  <link rel="stylesheet" href="{{ asset('css/eventpage.css') }}">
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

    <!-- Events and Programs -->
      <section class="events-section">
        <!-- LEFT -->
        <div class="events-left">
          <h2>Events and Programs</h2>
          <p>This page serves as your guide to upcoming events designed to empower the youth, foster engagement, and build stronger communities.</p>
        </div>

        <!-- RIGHT -->
        <div class="events-right">
          <h3>Today's Agenda 
        <i class="fa-solid fa-thumbtack"></i>
      </h3>

          <div class="agenda-card">
            <div class="agenda-banner">
              <div class="agenda-date">
                <span class="month">SEP.</span>
                <span class="day">15</span>
                <span class="year">2025</span>
              </div>
              <img src="{{ asset('images/drugs.jpeg') }}" alt="Event Banner">
            </div>
            <div class="agenda-actions">
            <a href="#" class="details-btn">
                See full details 
                <span class="icon-circle">
                  <i class="fa-solid fa-chevron-right"></i>
                </span>
              </a>

              <a href="{{ route('attendancepage') }}" class="attend-btn">Attend Now</a>

            </div>
          </div>
        </div>
      </section>


      <!-- Upcoming Activities -->
      <section class="upcoming-section">
        <h2>UPCOMING ACTIVITIES</h2>
        
        <div class="committee-bar">
          <h3>Committee</h3>
          <div class="committee-tabs">
            <button class="active">All</button>
            <button>Active Citizenship</button>
            <button>Economic Empowerment</button>
            <button>Education</button>
            <button>Health</button>
            <button>Sports</button>
          </div>
        </div>
      </section>


<section class="programs-section">
  <!-- Programs bar -->
  <div class="programs-bar">
    <h3>Programs for you</h3>
    <a href="#" class="see-all">See All</a>
  </div>

  <!-- Scrollable cards row -->
   <div class="programs-scroll">
    <div class="programs-container">
    <!-- Program card 1 -->
    <article class="program-card">
  <div class="program-media">
    <img src="{{ asset('images/drugs.jpeg') }}" alt="Program 1">
    <button class="register-btn">REGISTER NOW!</button>
  </div>
  <div class="program-body">
    <p class="program-title">Available Testing for TESDA Courses</p>
    <p class="program-desc">This program short summary is najbabxicaba anuwcbibxciabxka nquvuabx</p>
    <div class="program-actions">
      <a class="read-more" href="#">
        READ MORE 
        <span class="circle-btn">
          <i class="fas fa-chevron-right"></i>
        </span>
      </a>
    </div>
  </div>
</article>


    <!-- Program card 2 -->
    <article class="program-card">
  <div class="program-media">
    <img src="{{ asset('images/linggo.jpeg') }}" alt="Program 1">
    <button class="register-btn">REGISTER NOW!</button>
  </div>
  <div class="program-body">
    <p class="program-title">Linggo ng Kabataan</p>
    <p class="program-desc">This program short summary is najbabxicaba anuwcbibxciabxka nquvuabx</p>
    <div class="program-actions">
      <a class="read-more" href="#">
        READ MORE 
        <span class="circle-btn">
          <i class="fas fa-chevron-right"></i>
        </span>
      </a>
    </div>
  </div>
</article>

    <!-- Program card 3 -->
    <article class="program-card">
  <div class="program-media">
    <img src="{{ asset('images/basketball.jpg') }}" alt="Program 1">
    <button class="register-btn">REGISTER NOW!</button>
  </div>
  <div class="program-body">
    <p class="program-title">Basketball Tournament</p>
    <p class="program-desc">This program short summary is najbabxicaba anuwcbibxciabxka nquvuabx</p>
    <div class="program-actions">
      <a class="read-more" href="#">
        READ MORE 
        <span class="circle-btn">
          <i class="fas fa-chevron-right"></i>
        </span>
      </a>
    </div>
  </div>
</article>

    <article class="program-card">
  <div class="program-media">
    <img src="{{ asset('images/drugs.jpeg') }}" alt="Program 1">
    <button class="register-btn">REGISTER NOW!</button>
  </div>
  <div class="program-body">
    <p class="program-title">Available Testing for TESDA Courses</p>
    <p class="program-desc">This program short summary is najbabxicaba anuwcbibxciabxka nquvuabx</p>
    <div class="program-actions">
      <a class="read-more" href="#">
        READ MORE 
        <span class="circle-btn">
          <i class="fas fa-chevron-right"></i>
        </span>
      </a>
    </div>
  </div>
</article>

    <article class="program-card">
  <div class="program-media">
    <img src="{{ asset('images/drugs.jpeg') }}" alt="Program 1">
    <button class="register-btn">REGISTER NOW!</button>
  </div>
  <div class="program-body">
    <p class="program-title">Available Testing for TESDA Courses</p>
    <p class="program-desc">This program short summary is najbabxicaba anuwcbibxciabxka nquvuabx</p>
    <div class="program-actions">
      <a class="read-more" href="#">
        READ MORE 
        <span class="circle-btn">
          <i class="fas fa-chevron-right"></i>
        </span>
      </a>
    </div>
  </div>
</article>
  </div>
</div>
</section>


<!-- === List of Events (stacked) === -->
<section class="events-list-section">
  <div class="section-header">
    <h3>List of Events</h3>
    <a href="#" class="see-all">See All</a>
  </div>

  <div class="events-wrapper">
    <!-- Event row 1 -->
    <article class="event-card">
      <div class="event-left">
        <div class="event-thumb upcoming">
          <!-- placeholder image -->
          <img src="{{ asset('images/linis.jpg') }}" alt="Event banner">
        </div>
      </div>

      <div class="event-right">
        <a class="view-details" href="#">View more details</a>
        <h4 class="event-title">Kalinsan sa bagong Pilipinas Program</h4>
        <div class="event-meta">
          <p><i class="fas fa-location-dot"></i> Barangay Hall (Starting Point)</p>
          <p><i class="fas fa-users"></i> Committee on Active Citizenship</p>
        </div>

        <div class="event-footer">
          <div class="event-when">
            <div class="when-label">WHEN</div>
            <div class="event-date">SEPTEMBER 22, 2025 | 9:00 AM</div>
          </div>
        </div>

      </div>
    </article>

    <!-- Event row 2 -->
    <article class="event-card">
      <div class="event-left">
        <div class="event-thumb upcoming">
          <img src="{{ asset('images/linis.jpg') }}" alt="Event banner">
        </div>
      </div>

      <div class="event-right">
        <a class="view-details" href="#">View more details</a>
        <h4 class="event-title">Kalinsan sa bagong Pilipinas Program</h4>
        <div class="event-meta">
          <p><i class="fas fa-location-dot"></i> Barangay Hall (Starting Point)</p>
          <p><i class="fas fa-users"></i> Committee on Active Citizenship</p>
        </div>
        <div class="event-footer">
          <div class="event-when">
          <span class="when-label">WHEN</span>
          <span class="event-date">SEPTEMBER 22, 2025 | 9:00 AM</span>
        </div>
</div>
      </div>
    </article>

    <article class="event-card">
      <div class="event-left">
        <div class="event-thumb">
          <img src="{{ asset('images/drugs.jpeg') }}" alt="Event banner">
        </div>
      </div>

      <div class="event-right">
        <a class="view-details" href="#">View more details</a>
        <h4 class="event-title">Kontra Droga</h4>
        <div class="event-meta">
          <p><i class="fas fa-location-dot"></i> Barangay Hall (Starting Point)</p>
          <p><i class="fas fa-users"></i> Committee on Active Citizenship</p>
        </div>
        <div class="event-footer">
          <div class="event-when">
          <span class="when-label">WHEN</span>
          <span class="event-date">SEPTEMBER 22, 2025 | 9:00 AM</span>
        </div>
</div>
      </div>
    </article>

    
  </div>
</section>


















    <script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons ===
  lucide.createIcons();

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
  menuToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    sidebar.classList.toggle('open');

    if (!sidebar.classList.contains('open')) {
      profileItem?.classList.remove('open');
      eventsItem?.classList.remove('open');
    }
  });

  // Helper: close all submenus
  function closeAllSubmenus() {
    profileItem?.classList.remove('open');
    eventsItem?.classList.remove('open');
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

  // === Events submenu toggle ===
  if (eventsItem && eventsLink) {
    eventsLink.addEventListener('click', (e) => {
      e.preventDefault();
      if (sidebar.classList.contains('open')) {
        const isOpen = eventsItem.classList.contains('open');
        closeAllSubmenus();
        if (!isOpen) eventsItem.classList.add('open');
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
      modalOverlay.style.display = 'flex';
    });
  });

  closeModal?.addEventListener('click', () => {
    modalOverlay.style.display = 'none';
  });

  modalOverlay?.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
      modalOverlay.style.display = 'none';
    }
  });


  document.querySelectorAll('.program-desc').forEach(el => {
  let text = el.textContent.trim().split(" ");
  if (text.length > 100) {
    el.textContent = text.slice(0, 100).join(" ") + "...";
  }
});

});
</script>
