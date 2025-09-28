<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/sk-eval-review.css') }}">
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

      <a href="{{ route('youth-profilepage') }}">
        <i data-lucide="users"></i>
        <span class="label">Youth Profile</span>
      </a>

      <a href="{{ route('sk-eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <div class="evaluation-item nav-item">
        <a href="{{ route('sk-evaluation-feedback') }}" class="evaluation-link nav-link active">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('sk-evaluation-feedback') }}">Feedbacks</a>
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
  <div class="evaluation-container">

    <!-- Header -->
<div class="evaluation-header">
  <button class="back-btn"><i class="fas fa-arrow-left"></i></button>
  <div>
    <h2>Kalinisan sa bagong Pilipinas Program</h2>
    <p class="event-details">Date: 2025-09-20 | Venue: Barangay Hall</p>
  </div>
</div>

<!-- Tabs -->
<div class="tab-buttons">
  <button class="tab-btn active" data-tab="rating">Rating</button>
  <button class="tab-btn" data-tab="comments">Comments</button>
</div>

<!-- ================== RATING TAB ================== -->
<div id="rating" class="tab-content active">

  <!-- Stats -->
  <div class="stats">
    <div class="stat-card">
      <h3>Average Rating of this Event</h3>
      <div class="rating-score">4.5 / 5</div>
      <small>Based on the <b class="highlight">100 responses</b></small>
    </div>
    <div class="stat-card">
      <h3>Rating Distribution</h3>
      <canvas id="ratingChart"></canvas>
    </div>
  </div>

  <!-- Question Breakdown -->
  <div class="question-section">
    <h3>
      Question Breakdown
      <span class="see-respondents">See Respondents</span>
    </h3>

    <div class="question-card">
      <div class="question-text">Question 1: Was the purpose of the program/event explained clearly?</div>
      <div class="rating">Rating: 5/5</div>
    </div>

    <div class="question-card">
      <div class="question-text">Question 2: Was the time given for the program/event enough?</div>
      <div class="rating">Rating: 4/5</div>
    </div>

    <div class="question-card">
      <div class="question-text">Question 3: Were you able to join and participate in the activities?</div>
      <div class="rating">Rating: 5/5</div>
    </div>

    <div class="question-card">
      <div class="question-text">Question 4: Did you learn something new from this program/event?</div>
      <div class="rating">Rating: 5/5</div>
    </div>

    <div class="question-card">
      <div class="question-text">Question 5: Did the SK officials/facilitators treat all participants fairly and equally?</div>
      <div class="rating">Rating: 5/5</div>
    </div>

    <div class="question-card">
      <div class="question-text">Question 6: Did the SK officials/facilitators show enthusiasm and commitment in leading the program/event?</div>
      <div class="rating">Rating: 5/5</div>
    </div>

    <div class="question-card">
      <div class="question-text">Question 7: Overall, are you satisfied with this program/event?</div>
      <div class="rating">Rating: 5/5</div>
    </div>
  </div>
</div>

<!-- ================== COMMENTS TAB ================== -->
<!-- Feedback Section -->
<div id="comments" class="tab-content">
  
  <!-- Filters inside comments -->
  <div class="feedback-filters">
    <button class="active">All</button>
    <button>5 - Strongly Agree</button>
    <button>4 - Agree</button>
    <button>3 - Neutral</button>
    <button>2 - Disagree</button>
    <button>1 - Strongly Disagree</button>
  </div>

  <!-- Section Title -->
  <h3>Feedback from participants</h3>

  <!-- Comment Card -->
  <div class="feedback-card">
    <div class="feedback-left">
      <img src="https://i.pravatar.cc/60?img=1" alt="profile" />
      <div>
        <div class="name-stars">
          <h4>Beverly J. Hills</h4>
          <div class="stars">★★★★★ <span>5</span></div>
        </div>
        <p>I gained a lot of knowledge</p>
      </div>
    </div>
    <div class="feedback-right">
      <span>09/09/2025&nbsp;&nbsp;6:00 PM</span>
    </div>
  </div>

  <div class="feedback-card">
    <div class="feedback-left">
      <img src="https://i.pravatar.cc/60?img=2" alt="profile" />
      <div>
        <div class="name-stars">
          <h4>Beverly J. Hills</h4>
          <div class="stars">★★★★★ <span>5</span></div>
        </div>
        <p>Goods and foods. Yess!</p>
      </div>
    </div>
    <div class="feedback-right">
      <span>09/09/2025&nbsp;&nbsp;6:00 PM</span>
    </div>
  </div>

  <div class="feedback-card">
    <div class="feedback-left">
      <img src="https://i.pravatar.cc/60?img=3" alt="profile" />
      <div>
        <div class="name-stars">
          <h4>Joey Y. Yes</h4>
          <div class="stars">★★★★☆ <span>4</span></div>
        </div>
        <p>
          The program is great I hope sa sunod mas mahaba ang time pero overall it’s good
        </p>
      </div>
    </div>
    <div class="feedback-right">
      <span>09/09/2025&nbsp;&nbsp;6:00 PM</span>
    </div>
  </div>

  <div class="feedback-card">
    <div class="feedback-left">
      <img src="https://i.pravatar.cc/60?img=4" alt="profile" />
      <div>
        <div class="name-stars">
          <h4>Jay Park</h4>
          <div class="stars">★★★★★ <span>5</span></div>
        </div>
        <p>I gained a lot of knowledge</p>
      </div>
    </div>
    <div class="feedback-right">
      <span>09/09/2025&nbsp;&nbsp;6:00 PM</span>
    </div>
  </div>
</div>


</main>







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

// Rating Distribution Chart
const ctx = document.getElementById('ratingChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['1: Strongly Disagree', '2: Disagree', '3: Neutral', '4: Agree', '5: Strongly Agree'],
    datasets: [{
      label: 'Responses',
      data: [0, 2, 5, 15, 78], // sample data
      backgroundColor: '#0C4B92',
      borderRadius: 6,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: true, labels: { color: "#01214A" } }
    },
    scales: {
      x: { ticks: { color: "#4b5c77", font: { size: 12 } } },
      y: { beginAtZero: true, ticks: { stepSize: 25, color: "#4b5c77" } }
    }
  }
});

// === Tabs Switching ===
const tabButtons = document.querySelectorAll(".tab-btn");
const tabContents = document.querySelectorAll(".tab-content");

tabButtons.forEach(btn => {
  btn.addEventListener("click", () => {

    tabButtons.forEach(b => b.classList.remove("active"));
    tabContents.forEach(c => c.classList.remove("active"));

    btn.classList.add("active");
    const tabId = btn.getAttribute("data-tab");
    document.getElementById(tabId).classList.add("active");
  });
});

  
});
</script>
</body>
</html>