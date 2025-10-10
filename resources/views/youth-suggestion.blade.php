<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/youth-suggestion.css') }}">
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
        <a href="{{ route('sk-evaluation-feedback') }}" class="evaluation-link nav-link">
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
    

  <!-- Suggestions Header -->
  <div class="suggestions-card">
    <h2>Youth Suggestions</h2>
    <div class="total-suggestions">
      Total suggestions <span>20</span>
    </div>
  </div>

  <!-- Overview of Suggestions -->
  <div class="overview-card">
    <h3 class="overview-title">Overview of Suggestions<br>by Category</h3>
    <div class="chart-container">
      <canvas id="suggestionChart"></canvas>
    </div>
  </div>

  <!-- Suggestions List -->
<div class="suggestions-section">
  <h3 class="suggestions-title">Suggestions List</h3>

  <div class="suggestions-subheader">
    <h4 class="group-title">This month</h4>

    <div class="filters">
      <!-- Month Dropdown -->
      <div class="custom-dropdown" data-type="month">
        <div class="dropdown-selected">
          <span>This month</span>
          <div class="icon-circle">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
        <ul class="dropdown-options">
          <li data-value="all">All</li>
          <li data-value="this">This month</li>
          <li data-value="last">Last month</li>
        </ul>
      </div>

      <label class="filter-label">Category:</label>

      <!-- Category Dropdown -->
      <div class="custom-dropdown" data-type="category">
        <div class="dropdown-selected">
          <span>All</span>
          <div class="icon-circle">
    <i class="fa-solid fa-chevron-down"></i>
  </div>
        </div>
        <ul class="dropdown-options">
          <li data-value="all">All</li>
          <li data-value="event">Events</li>
          <li data-value="program">Programs</li>
          <li data-value="other">Other</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- ✅ THIS MONTH LIST -->
<div class="suggestions-group" data-month="this">


  <div class="suggestion-card" data-category="event">
    <img src="https://i.pravatar.cc/80?img=8" alt="User" class="suggestion-avatar">
    <div class="suggestion-content">
      <h5>Jay Park <span class="suggestion-id">#KK2025296JP</span></h5>
      <p>Hello po, pwede po ba kayo mag event ng art contest? gusto ko po kasi sumali kaso wala pa nagpapaevent na ganon. Sana mapansin tnx.</p>
      <div class="suggestion-footer">
        <span class="badge event">Events</span>
        <span class="date">09/09/2025 6:00 PM</span>
      </div>
    </div>
  </div>

  <div class="suggestion-card" data-category="program">
    <img src="https://i.pravatar.cc/80?img=9" alt="User" class="suggestion-avatar">
    <div class="suggestion-content">
      <h5>Ammiel N. Lim <span class="suggestion-id">#KK2025296JP</span></h5>
      <p>Hello SK Chair. Si Sassa to, pwede pa request ng program for awareness sa HIV thankies.</p>
      <div class="suggestion-footer">
        <span class="badge program">Programs</span>
        <span class="date">09/09/2025 6:00 PM</span>
      </div>
    </div>
  </div>
</div>


<!-- ✅ LAST MONTH LIST -->
<div class="suggestions-group" data-month="last">
  

  <div class="suggestion-card" data-category="event">
    <img src="https://i.pravatar.cc/80?img=8" alt="User" class="suggestion-avatar">
    <div class="suggestion-content">
      <h5>Jay Park <span class="suggestion-id">#KK2025296JP</span></h5>
      <p>Hello po, pwede po ba kayo mag event ng art contest? gusto ko po kasi sumali kaso wala pa nagpapaevent na ganon. Sana mapansin tnx.</p>
      <div class="suggestion-footer">
        <span class="badge event">Events</span>
        <span class="date">09/09/2025 6:00 PM</span>
      </div>
    </div>
  </div>

  <div class="suggestion-card" data-category="program">
    <img src="https://i.pravatar.cc/80?img=9" alt="User" class="suggestion-avatar">
    <div class="suggestion-content">
      <h5>Ammiel N. Lim <span class="suggestion-id">#KK2025296ANL</span></h5>
      <p>Hello SK Chair. Si Sassa to, pwede pa request ng program for awareness sa HIV thankies.</p>
      <div class="suggestion-footer">
        <span class="badge program">Programs</span>
        <span class="date">09/09/2025 6:00 PM</span>
      </div>
    </div>
  </div>

  <div class="suggestion-card" data-category="other">
    <img src="https://i.pravatar.cc/80?img=10" alt="User" class="suggestion-avatar">
    <div class="suggestion-content">
      <h5>Miguel G. Dominguez <span class="suggestion-id">#KK2025296MGD</span></h5>
      <p>May I suggest serving snacks like siopao during events? It would also be nice if pancit could be included, SK Chair. Thank you!</p>
      <div class="suggestion-footer">
        <span class="badge other">Other</span>
        <span class="date">09/09/2025 6:00 PM</span>
      </div>
    </div>
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

  const ctx = document.getElementById('suggestionChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Programs', 'Events', 'Others'],
        datasets: [{
          label: 'Suggestions',
          data: [90, 75, 50],
          backgroundColor: [
            'rgba(253, 220, 108, 0.9)',
            'rgba(200, 220, 200, 0.9)',
            'rgba(240, 190, 220, 0.9)'
          ],
          borderRadius: 6,
          barThickness: 60
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              color: '#2b3a55',
              font: { size: 13, weight: '600' }
            }
          }
        },
        scales: {
          x: {
            ticks: { color: '#2b3a55', font: { weight: '600' } },
            grid: { display: false }
          },
          y: {
            ticks: { color: '#2b3a55' },
            grid: { color: '#e0e0e0' }
          }
        }
      }
    });

    // === Custom Dropdown ===
const dropdowns = document.querySelectorAll(".custom-dropdown");

// Track current filters
let currentMonth = "this";
let currentCategory = "all";

function filterSuggestions() {
  document.querySelectorAll(".suggestions-group").forEach(group => {
    const groupMonth = group.dataset.month;

    // Show group if:
    // 1) "all" is selected, or 2) group matches current month
    const isMonth = currentMonth === "all" || groupMonth === currentMonth;
    group.style.display = isMonth ? "block" : "none";

    // Update group title dynamically
    const titleEl = group.querySelector(".group-title");
    if (titleEl) {
      if (currentMonth === "this") titleEl.textContent = "This month";
      else if (currentMonth === "last") titleEl.textContent = "Last month";
      else titleEl.textContent = groupMonth === "this" ? "This month" : "Last month";
    }
  });

  // Update subheader above filters
  const subheaderTitle = document.querySelector(".suggestions-subheader .group-title");
  if (subheaderTitle) {
    if (currentMonth === "all") subheaderTitle.textContent = "All suggestions";
    else subheaderTitle.textContent = currentMonth === "this" ? "This month" : "Last month";
  }

  // Filter cards inside groups
  document.querySelectorAll(".suggestion-card").forEach(card => {
    const cardMonth = card.closest(".suggestions-group").dataset.month;
    const matchMonth = currentMonth === "all" || cardMonth === currentMonth;
    const matchCategory = currentCategory === "all" || card.dataset.category === currentCategory;
    card.style.display = matchMonth && matchCategory ? "flex" : "none";
  });
}



// Function to close all dropdowns except the currently open one
function closeAllDropdowns(except = null) {
  dropdowns.forEach(dropdown => {
    const options = dropdown.querySelector(".dropdown-options");
    const icon = dropdown.querySelector(".icon-circle");
    if (!except || dropdown !== except) {
      options.classList.remove("active");
      if (icon) icon.classList.remove("rotate");
    }
  });
}

// Initialize each dropdown
dropdowns.forEach(dropdown => {
  const selected = dropdown.querySelector(".dropdown-selected");
  const optionsList = dropdown.querySelector(".dropdown-options");
  const options = optionsList.querySelectorAll("li");
  const icon = selected.querySelector(".icon-circle");
  const type = dropdown.dataset.type;

  // Toggle dropdown open/close
  selected.addEventListener("click", (e) => {
    e.stopPropagation();
    closeAllDropdowns(dropdown);

    optionsList.classList.toggle("active");
    if (icon) icon.classList.toggle("rotate");
  });

  // Select an option
  options.forEach(option => {
    option.addEventListener("click", () => {
      selected.querySelector("span").textContent = option.textContent;
      optionsList.classList.remove("active");
      if (icon) icon.classList.remove("rotate");

      // Update current filters
      if (type === "month") currentMonth = option.dataset.value;
      if (type === "category") currentCategory = option.dataset.value;

      // Apply filter and update group title
      filterSuggestions();
    });
  });
});

// Close dropdowns when clicking outside
document.addEventListener("click", () => closeAllDropdowns());

// Initial filter on page load
filterSuggestions();




  
});
</script>
</body>
</html>