<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  <div class="dashboard">
    
    <aside class="sidebar">
  <button class="menu-toggle">Menu</button>
  <div class="divider"></div>
  <nav class="nav">
    <a href="#">
      <i data-lucide="layout-dashboard"></i>
      <span class="label">Dashboard</span>
    </a>
    <a href="#">
      <i data-lucide="circle-user"></i>
      <span class="label">Profile</span>
    </a>
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
    <main class="main">
      
      <header class="topbar">
  <div class="logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
    <div class="logo-text">
      <span class="title">Katibayan</span>
      <span class="subtitle">Web Portal</span>
    </div>
  </div>

  <div class="topbar-right">
    <div class="search-box">
      <i data-lucide="search"></i>
      <input type="text" placeholder="Search something">
    </div>

    <div class="time">MON 10:00 <span>AM</span></div>
    <div class="notification-wrapper">
        <i class="fas fa-bell"></i>
        <span class="notif-count">3</span>

        <!-- Dropdown -->
        <div class="notif-dropdown">
          <div class="notif-header">
            <strong>Notification</strong> <span>3</span>
          </div>
          <ul class="notif-list">
            <li>
              <div class="notif-icon"></div>
              <div class="notif-content">
                <strong>Program Evaluation</strong>
                <p>we need evaluation for the KK-Assembly Event</p>
              </div>
              <span class="notif-dot"></span>
            </li>
            <li>
              <div class="notif-icon"></div>
              <div class="notif-content">
                <strong>Program Evaluation</strong>
                <p>we need evaluation for the KK-Assembly Event</p>
              </div>
              <span class="notif-dot"></span>
            </li>
            <li>
              <div class="notif-icon"></div>
              <div class="notif-content">
                <strong>Program Evaluation</strong>
                <p>we need evaluation for the KK-Assembly Event</p>
              </div>
              <span class="notif-dot"></span>
            </li>
          </ul>
        </div>
      </div>
    <img src="https://i.pravatar.cc/40" alt="User" class="avatar">
  </div>

</header>



      <!-- Row for Welcome + Calendar -->
<div class="row">
  <!-- Welcome -->
<section class="welcome">
  <div class="slides">
    
    <!-- Slide 1: Welcome -->
    <div class="slide">
      <h2>Welcome, Marijoy!</h2>
      <h3>Have a nice day!</h3><br>
      <p>
        <span>KatiBayan</span> provides a platform for the youth to stay updated on SK events 
        and programs while fostering active participation in community development.
      </p>
    </div>

    <!-- Slide 2: Anti-Rabies -->
<div class="slide event">
  <div class="date">
    <span class="month">AUG</span>
    <span class="day">22</span>
  </div>
  <div class="event-info">
    <p><strong>UPCOMING!</strong> Anti-Rabies Vaccination</p>
    <small>Please, Don’t Forget to Participate</small>
    <span class="desc">KatiBayan provides a platform for the youth to stay updated on SK events and 
      programs while fostering active participation in community development</span>
  </div>
  <div class="event-banner" style="background-image: url('images/vaccine.jpg');"></div>
</div>

<!-- Slide 3 -->
<div class="slide event">
  <div class="date">
    <span class="month">SEP</span>
    <span class="day">10</span>
  </div>
  <div class="event-info">
    <p><strong>LEADERSHIP TRAINING</strong></p>
    <small>Boost your skills as a youth leader</small>
    <span class="desc">Join our 2-day leadership bootcamp</span>
  </div>
  <div class="event-banner" style="background-image: url('images/team.jpg');"></div>
</div>


  </div>

  <!-- Pagination dots -->
  <div class="dots"></div>
</section>


  <div class="calendar">
  <header>
    <button class="prev"><i class="fas fa-chevron-left"></i></button>
    <h3></h3>
    <button class="next"><i class="fas fa-chevron-right"></i></button>
    <!-- Calendar icon upper-right -->
<i class="fas fa-calendar calendar-toggle" title="View full month"></i>

  </header>

  
  <div class="days"></div>
</div>



        <!-- Progress -->
<div class="progress">
  <h3>Your Progress</h3>
  <div class="progress-cards">
    
    <!-- Attendance -->
    <div class="card">
  <div class="card-content">
    <div class="text">
      <h4>Attendance</h4>
      <p>Monitor your progress</p>
    </div>
    <div class="icon">
      <i data-lucide="users"></i>
    </div>
  </div>

  <!-- Progress + Small sa iisang row -->
  <div class="progress-footer">
    <div class="bar"><span style="width:5%"></span></div>
    <small>5/100</small>
  </div>
</div>


    <!-- Evaluation -->
    <div class="card">
      <div class="card-content">
        <div class="text">
          <h4>Evaluation</h4>
          <p>You have 1 program to evaluate.</p>
        </div>
        <div class="icon">
          <i data-lucide="thumbs-up"></i>
        </div>
      </div>
      <small>0/1</small>
    </div>

    <!-- Poll -->
    <div class="card">
      <div class="card-content">
        <div class="text">
          <h4>Poll</h4>
          <p>Help shape our community.</p>
        </div>
        <div class="icon">
          <i data-lucide="bar-chart-3"></i>
        </div>
      </div>
      <a href="#">Join the poll →</a>
    </div>

  </div>
</div>

<!-- Events -->
  <div class="events-section">
  <!-- Title sa labas -->
  <h3 class="events-title">Upcoming Events</h3>

  <!-- White container -->
  <div class="events">
    <div class="events-top">
      <button class="events-menu">⋯</button>
    </div>

    <ul>
  <li>
    <span class="date">
      <strong>SEP</strong>
      <span>26</span>
    </span>
    <div class="event-info">
      <p>Anti-Rabbies Vaccine</p>
      <small>Upcoming</small>
    </div>
  </li>
  <li>
    <span class="date">
      <strong>OCT</strong>
      <span>02</span>
    </span>
    <div class="event-info">
      <p>KK Assembly</p>
      <small>Upcoming</small>
    </div>
  </li>
  <li>
    <span class="date">
      <strong>OCT</strong>
      <span>15</span>
    </span>
    <div class="event-info">
      <p>Baranggay Clean-up</p>
      <small>Upcoming</small>
    </div>
  </li>

  <li>
    <span class="date">
      <strong>DEC</strong>
      <span>25</span>
    </span>
    <div class="event-info">
      <p>Christmas Celebration</p>
      <small>Holiday</small>
    </div>
  </li>
</ul>


  </div>
</div>


<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>

        <!-- Announcements -->
<div class="announcements-section">
  <!-- Title sa labas -->
  <h3 class="announcements-title">Announcements</h3>

  <!-- White container -->
  <div class="announcements">
    <div class="card">
      <div class="card-content">
        <div class="icon">
          <i class="fas fa-info"></i>
        </div>
        <div class="text">
          <strong>Important Announcement: No Office Today</strong>
          <p>The office is closed today. We sincerely apologize for any inconvenience.</p>
        </div>
      </div>
      <button class="options">⋯</button>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon">
          <i class="fas fa-print"></i>
        </div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options">⋯</button>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon">
          <i class="fas fa-print"></i>
        </div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options">⋯</button>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon">
          <i class="fas fa-print"></i>
        </div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options">⋯</button>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon">
          <i class="fas fa-print"></i>
        </div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options">⋯</button>
    </div>
  </div>
</div>



        <!-- Suggestion Box -->
        <div class="suggestion-box">
          <h2>Suggestion Box</h2>
          <p class="subtitle">You matter. Your voice counts.</p>

          <a href="#" class="suggestion-btn">
            Share with us <i class="fas fa-paper-plane"></i>
          </a>

          <p class="note">
            Everyone is encouraged to share their ideas and suggestions — we’re glad to hear from you!
          </p>
        </div>

      </div>

    </main>
  </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons + sidebar toggle ===
  lucide.createIcons();
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }

  // === Calendar Functionality ===
  const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
  const daysContainer = document.querySelector(".calendar .days");
  const header = document.querySelector(".calendar header h3");
  let today = new Date();
  let currentView = new Date();

  // Philippine Holidays 2025 (YYYY-MM-DD)
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

      if (holidays.includes(dateStr)) {
        dateEl.classList.add('holiday');
      }

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
  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      currentView.setDate(currentView.getDate() - 7);
      renderCalendar(currentView);
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      currentView.setDate(currentView.getDate() + 7);
      renderCalendar(currentView);
    });
  }

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

  // === Notifications dropdown ===
  const wrapper = document.querySelector(".notification-wrapper");
  if (wrapper) {
    const bell = wrapper.querySelector(".fa-bell");
    const dropdown = wrapper.querySelector(".notif-dropdown");
    if (bell) {
      bell.addEventListener("click", (e) => {
        e.stopPropagation();
        wrapper.classList.toggle("active");
      });
    }
    if (dropdown) dropdown.addEventListener("click", (e) => e.stopPropagation());
    document.addEventListener("click", () => wrapper.classList.remove("active"));
  }

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

  // === Welcome Slider ===
  const welcomeSection = document.querySelector(".welcome");
  const slideTrack = welcomeSection?.querySelector(".slides");
  const slides = welcomeSection?.querySelectorAll(".slide");
  const dotsContainer = welcomeSection?.querySelector(".dots");

  let currentIndex = 0;
  let autoPlay;

  if (welcomeSection && slideTrack && slides.length > 0 && dotsContainer) {
    // Create dots
    slides.forEach((_, i) => {
      const dot = document.createElement("button");
      if (i === 0) dot.classList.add("active");
      dot.addEventListener("click", () => {
        currentIndex = i;
        updateSlide();
        restartAuto();
      });
      dotsContainer.appendChild(dot);
    });
    const dots = dotsContainer.querySelectorAll("button");

    // Update slide position
    function updateSlide() {
  const containerWidth = welcomeSection.getBoundingClientRect().width;
  slideTrack.style.transform = `translateX(-${currentIndex * containerWidth}px)`;
  dots.forEach(dot => dot.classList.remove("active"));
  dots[currentIndex].classList.add("active");
}

    // Auto slide
    function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      updateSlide();
    }
    function startAuto() {
      autoPlay = setInterval(nextSlide, 4000);
    }
    function stopAuto() {
      clearInterval(autoPlay);
    }
    function restartAuto() {
      stopAuto();
      startAuto();
    }

    // Init
    updateSlide();
    startAuto();

    // Pause on hover
    welcomeSection.addEventListener("mouseenter", stopAuto);
    welcomeSection.addEventListener("mouseleave", startAuto);

    // Responsive resize
    window.addEventListener("resize", updateSlide);
  }
});
</script>







</body>
</html>
