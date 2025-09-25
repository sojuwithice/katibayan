<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/list-of-attendees.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>



</head>
<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('sk.dashboard') }}">
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>

      <a href="#">
        <i data-lucide="chart-pie"></i>
        <span class="label">Analytics</span>
      </a>

      <a href="{{ route('youth-profilepage') }}" class="active">
        <i data-lucide="users"></i>
        <span class="label">Youth Profile</span>
      </a>

      <a href="{{ route('sk-eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <div class="evaluation-item nav-item">
        <a href="#" class="evaluation-link nav-link">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="#">Feedbacks</a>
          <a href="#">Polls</a>
          <a href="#">Suggestion Box</a>
        </div>
      </div>

      <a href="#">
        <i data-lucide="file-chart-column"></i>
        <span class="label">Reports</span>
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

    <main class="container">

<div class="attendees-container">
  <div class="attendees-card">

    <!-- Header -->
    <div class="attendees-header">
      <a href="{{ route('sk-eventpage') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i>
      </a>
      <h2>List Of Attendees</h2>
    </div>

    <!-- Search + Filter -->
    <div class="attendees-controls">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search">
      </div>
      <button class="filter-btn">
        <i class="fas fa-filter"></i> Filter
      </button>
    </div>

    <!-- Table -->
    <div class="attendees-table-wrapper">
      <table class="attendees-table">
        <thead>
          <tr>
            <th>KK Number</th>
            <th>Name</th>
            <th>Age</th>
            <th>Purok</th>
            <th>Youth age group</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Aika G. Barin</td>
            <td>21</td>
            <td>Purok 6</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Ethan M. Batumbakal</td>
            <td>21</td>
            <td>Purok 6</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Peter Pan Parker Ron M. Mortega</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Sarrah Joe</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Jane Rea May G. Mengorio</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Juliane Rebecca S. Dayandante</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Marijoy S. Novora</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Feliz D. Navidad</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Jose Mari Chan</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Felix D. StrayKid</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Cheolita Marie</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Poloy D. Yolo</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Beige N. Poloy</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
          <tr>
            <td>KKBA-EMS3-2025-0012</td>
            <td>Beige N. Poloy</td>
            <td>21</td>
            <td>Purok 2</td>
            <td>Core Youth</td>
            <td>Active Youth</td>
          </tr>
        </tbody>
      </table>
    </div>
    
  </div>
</div>











<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons + sidebar toggle ===
  lucide.createIcons();
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  const profileItem = document.querySelector('.profile-item');
  const profileLink = document.querySelector('.profile-link');
  const eventsItem = document.querySelector('.events-item');
  const eventsLink = document.querySelector('.events-link');

  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');
      if (!sidebar.classList.contains('open')) {
        profileItem?.classList.remove('open'); 
      }
    });
  }

  // === Submenus ===
const evaluationItem = document.querySelector('.evaluation-item');
const evaluationLink = document.querySelector('.evaluation-link');

evaluationLink?.addEventListener('click', (e) => {
  e.preventDefault();

  const isOpen = evaluationItem.classList.contains('open');
  evaluationItem.classList.remove('open');

  if (!isOpen) {
    evaluationItem.classList.add('open');
  }
});


  // === Calendar ===
  const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
  const daysContainer = document.querySelector(".calendar .days");
  const header = document.querySelector(".calendar header h3");
  let today = new Date();
  let currentView = new Date();

  const holidays = [
    "2025-01-01","2025-04-09","2025-04-17","2025-04-18",
    "2025-05-01","2025-06-06","2025-06-12","2025-08-25",
    "2025-11-30","2025-12-25","2025-12-30"
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
      ) {
        dayEl.classList.add("active");
      }

      dayEl.appendChild(weekdayEl);
      dayEl.appendChild(dateEl);
      daysContainer.appendChild(dayEl);
    }
  }

  renderCalendar(currentView);

  const prevBtn = document.querySelector(".calendar .prev");
  const nextBtn = document.querySelector(".calendar .next");
  if (prevBtn) prevBtn.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() - 7);
    renderCalendar(currentView);
  });
  if (nextBtn) nextBtn.addEventListener("click", () => {
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
    timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
  }
  updateTime();
  setInterval(updateTime, 60000);

  // === Notifications ===
  const notifWrapper = document.querySelector(".notification-wrapper");
  const profileWrapper = document.querySelector(".profile-wrapper");
  const profileToggle = document.getElementById("profileToggle");
  const profileDropdown = document.querySelector(".profile-dropdown");

  if (notifWrapper) {
    const bell = notifWrapper.querySelector(".fa-bell");
    if (bell) {
      bell.addEventListener("click", (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle("active");
        profileWrapper?.classList.remove("active");
      });
    }
    const dropdown = notifWrapper.querySelector(".notif-dropdown");
    if (dropdown) dropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  if (profileWrapper && profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
      notifWrapper?.classList.remove("active");
    });
    profileDropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
      profileItem?.classList.remove('open');
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');

    // Close options dropdown when clicking outside
    document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
  });

  // === Highlight Holidays in Events ===
  document.querySelectorAll('.events li').forEach(eventItem => {
    const dateEl = eventItem.querySelector('.date span');
    const monthEl = eventItem.querySelector('.date strong');
    if (!dateEl || !monthEl) return;

    const monthMap = {
      JAN: "01", FEB: "02", MAR: "03", APR: "04", MAY: "05", JUN: "06",
      JUL: "07", AUG: "08", SEP: "09", OCT: "10", NOV: "11", DEC: "12"
    };
    const monthNum = monthMap[monthEl.textContent.trim().toUpperCase()];
    const day = dateEl.textContent.trim().padStart(2,'0');
    const dateStr = `2025-${monthNum}-${day}`;

    if (holidays.includes(dateStr)) {
      eventItem.querySelector('.date').classList.add('holiday');
    }
  });

  // === Back Button Fallback ===
  const backBtn = document.querySelector('.back-btn');
  backBtn?.addEventListener('click', (e) => {
    if (backBtn.getAttribute('href') === '#') {
      e.preventDefault();
      history.back();
    }
  });

  // === Search Attendees ===
  const searchInput = document.querySelector('.search-box input');
  const tableRows = document.querySelectorAll('.attendees-table tbody tr');

  searchInput?.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    tableRows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(query) ? '' : 'none';
    });
  });

  // === Filter Attendees ===
  const filterBtn = document.querySelector('.filter-btn');
  filterBtn?.addEventListener('click', () => {
    const status = prompt("Filter by Status (ex: Active Youth):");
    if (!status) {
      tableRows.forEach(row => row.style.display = '');
      return;
    }
    tableRows.forEach(row => {
      const cell = row.cells[5]?.textContent.toLowerCase(); // Status column
      row.style.display = cell && cell.includes(status.toLowerCase()) ? '' : 'none';
    });
  });

  
});
</script>
</body>
</html>