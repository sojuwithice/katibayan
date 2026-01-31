<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/certificate-request.css') }}">
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
        <i data-lucide="layout-dashboard" class="lucide-icon"></i>
        <span class="label">Dashboard</span>
      </a>

      <a href="{{ route('youth-profilepage') }}" class="active">
        <i data-lucide="users" class="lucide-icon"></i>
        <span class="label">Youth Profile</span>
      </a>

      <div class="nav-item">
        <a href="#" class="nav-link">
          <i data-lucide="calendar" class="lucide-icon"></i>
          <span class="label">Events and Programs</span>
          <i data-lucide="chevron-down" class="submenu-arrow lucide-icon"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('sk-eventpage') }}">Events List</a>
          <a href="{{ route('youth-program-registration') }}">Youth Registration</a>
        </div>
      </div>

      <a href="{{ route('sk-evaluation-feedback') }}">
        <i data-lucide="message-square-quote" class="lucide-icon"></i>
        <span class="label">Feedbacks</span>
      </a>

      <a href="{{ route('sk-polls') }}">
        <i data-lucide="vote" class="lucide-icon"></i>
        <span class="label">Polls</span>
      </a>

      <a href="{{ route('youth-suggestion') }}">
        <i data-lucide="lightbulb" class="lucide-icon"></i>
        <span class="label">Suggestion Box</span>
      </a>
      
      <a href="{{ route('reports') }}">
        <i data-lucide="file-chart-column" class="lucide-icon"></i>
        <span class="label">Reports</span>
      </a>

      <a href="{{ route('sk-services-offer') }}">
        <i data-lucide="hand-heart" class="lucide-icon"></i>
        <span class="label">Service Offer</span>
      </a>
    </nav>
  </aside>


  <!-- Main -->
  <div class="main">

    <!-- Topbar -->
    <header class="topbar">
      <div class="logo">
        <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="Logo">
        <div class="logo-text">
          <span class="title">Katibayan</span>
          <span class="subtitle">Web Portal</span>
        </div>
      </div>

      <div class="topbar-right">
        <div class="time">MON 10:00 <span>AM</span></div>

 <!-- Theme Toggle Button - ADDED HERE -->
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
  <!-- Header Title -->
  <div class="welcome-card">
    <h2>Youth Certificate Request</h2>
  </div>

  <!-- Section -->
<section class="events-section">
  <div class="events-header">
    <h3>Events and Programs Attended by the Youth</h3>
    <div class="dropdown">
      <button class="filter-btn">
        This Month
        <span class="circle-icon">
          <i class="fas fa-chevron-down"></i>
        </span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="#">This Week</a></li>
        <li><a href="#">Last Week</a></li>
        <li><a href="#">This Month</a></li>
        <li><a href="#">Last Month</a></li>
        <li><a href="#">This Year</a></li>
        <li><a href="#">Last Year</a></li>
        <li><a href="#">All Time</a></li>
      </ul>
    </div>
  </div>

  <div class="cards-container">

    {{-- Ginamit na natin 'yung array keys (e.g., $req['key']) --}}
    @foreach ($requests as $req)
      
      {{-- Ginamit na natin 'yung bago at generic na data attributes --}}
      <div class="event-card" 
           data-date="{{ $req['date'] ? \Carbon\Carbon::parse($req['date'])->toDateString() : now()->toDateString() }}" 
           data-activity-id="{{ $req['activity_id'] }}"
           data-activity-type="{{ $req['activity_type'] }}">

        <div class="event-info">
          
          {{-- Ginamit '$req['title']' --}}
          <h4>{{ $req['title'] ?? 'Untitled Activity' }}</h4> 
          
          {{-- Ginamit '$req['date']' --}}
          @if($req['date'])
          <p class="event-date">
            Held on {{ \Carbon\Carbon::parse($req['date'])->format('F j, Y') }}.
          </p>
          @endif

          {{-- Ginamit '$req['total_requests']'. Tinanggal ko muna 'yung attendees_count --}}
          <p class="event-subtext">
            {{ $req['total_requests'] }} youth are requesting for this certificate.
          </p>
          
          {{-- Nagdagdag ng badge para malaman kung event o program --}}
          <span class="activity-badge" style="background-color: {{ $req['activity_type'] == 'event' ? '#007bff' : '#28a745' }}; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.8em;">
            {{ ucfirst($req['activity_type']) }}
          </span>
          
        </div>
        <div class="event-action">
          {{-- Ginamit '$req['total_requests']' --}}
          <span class="circle-badge">{{ $req['total_requests'] }}</span> 
          
          {{-- Ginamit 'yung bagong route structure --}}
          <a href="{{ route('certificate.showList', ['type' => $req['activity_type'], 'id' => $req['activity_id']]) }}" class="arrow-btn">
            <i class="fas fa-chevron-right"></i>
          </a>
        </div>
      </div>
    @endforeach

    @if ($requests->isEmpty())
      <p style="text-align:center; margin-top:2rem;">No certificate requests found.</p>
    @endif
  </div>

</main>





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


//filter dropdown
const dropdown = document.querySelector(".dropdown");
if (dropdown) {
    const btn = dropdown.querySelector(".filter-btn");
    const label = btn.childNodes[0]; // Ito yung text node "This Month"
    const options = dropdown.querySelectorAll(".dropdown-menu li a");
    const cards = document.querySelectorAll(".event-card"); // Kunin lahat ng cards
    
    // FIX 1: Tama na ang selector para sa "No requests" message
    const noRequestsMessage = document.querySelector(".cards-container > p");

    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdown.classList.toggle("open");
    });

    options.forEach(option => {
      option.addEventListener("click", (e) => {
        e.preventDefault();
        label.textContent = option.textContent + " "; 
        dropdown.classList.remove("open");

        const filter = option.textContent.trim();
        const now = new Date();
        let cardsShown = 0; // Counter para sa visible cards

        cards.forEach(card => {
          const dateStr = card.dataset.date; // e.g., "2025-10-20 09:00:00"
          if (!dateStr) {
            card.style.display = "none";
            return;
          }

          // ===== ITO ANG PINAKAMALAKING PAGBABAGO (FIX 2) =====
          // Manually parse ang date para maiwasan ang timezone issue
          // Kinukuha natin 'yung "2025-10-20" na part
          const dateOnlyStr = dateStr.split(' ')[0]; 
          const parts = dateOnlyStr.split('-'); // ["2025", "10", "20"]
          
          // Gumawa ng date na sigurado tayong local timezone at 12:00 AM
          // Note: months ay 0-indexed (Jan=0, Feb=1, ... Oct=9)
          const cardDate = new Date(parts[0], parts[1] - 1, parts[2]);
          // Hindi na kailangan ng .setHours(0,0,0,0) kasi 12AM na 'to by default
          // =======================================================
          

          let show = true;
          
          // Gumawa ng 'today' na 12:00 AM din ang oras
          const today = new Date(now);
          today.setHours(0, 0, 0, 0);


          if (filter === "This Week") {
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - (today.getDay() === 0 ? 6 : today.getDay() - 1));
            
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);
            endOfWeek.setHours(23, 59, 59, 999); 

            show = cardDate >= startOfWeek && cardDate <= endOfWeek;
          }
          else if (filter === "Last Week") {
            const startOfThisWeek = new Date(today);
            startOfThisWeek.setDate(today.getDate() - (today.getDay() === 0 ? 6 : today.getDay() - 1));

            const startOfLastWeek = new Date(startOfThisWeek);
            startOfLastWeek.setDate(startOfThisWeek.getDate() - 7);

            const endOfLastWeek = new Date(startOfLastWeek);
            endOfLastWeek.setDate(startOfLastWeek.getDate() + 6);
            endOfLastWeek.setHours(23, 59, 59, 999); 

            show = cardDate >= startOfLastWeek && cardDate <= endOfLastWeek;
          }
          else if (filter === "This Month") {
            show = cardDate.getMonth() === now.getMonth() &&
                   cardDate.getFullYear() === now.getFullYear();
          }
          else if (filter === "Last Month") {
            const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            show = cardDate.getMonth() === lastMonth.getMonth() &&
                   cardDate.getFullYear() === lastMonth.getFullYear();
          }
          else if (filter === "This Year") {
            show = cardDate.getFullYear() === now.getFullYear();
          }
          else if (filter === "Last Year") {
            show = cardDate.getFullYear() === now.getFullYear() - 1;
          }
          else if (filter === "All Time") {
            show = true;
          }

          // Itago o ipakita ang card
          card.style.display = show ? "flex" : "none";
          if (show) {
            cardsShown++;
          }
        });

        // Ipakita o itago yung "No requests" message
        if (noRequestsMessage) {
            noRequestsMessage.style.display = (cardsShown === 0) ? "block" : "none";
        }

      });
    });

    document.addEventListener("click", () => {
      dropdown.classList.remove("open");
    });
}


function getViewedActivities() {
    const viewed = localStorage.getItem('viewedCertificateActivities'); // Pinalitan 'yung key
    return viewed ? JSON.parse(viewed) : [];
}

// --- 1. Tatakbo ito kapag nag-load ang page ---
// (In-update para basahin 'yung 'data-activity-id' at 'data-activity-type')
const viewedActivityIds = getViewedActivities();
document.querySelectorAll('.event-card').forEach(card => {
    // Kunin 'yung bagong data attributes
    const activityId = card.dataset.activityId; 
    const activityType = card.dataset.activityType;
    const uniqueActivityId = `${activityType}_${activityId}`; // Gagawa ng key e.g., "program_4"

    // Kung 'yung unique ID ay nasa listahan ng viewed, itago 'yung badge
    if (activityId && viewedActivityIds.includes(uniqueActivityId)) {
        const badge = card.querySelector('.circle-badge');
        if (badge) {
            badge.style.display = 'none';
        }
    }
});

// --- 2. Tatakbo ito kapag may na-click na arrow button ---
// (In-update para i-save 'yung unique ID)
const cardsContainer = document.querySelector('.cards-container');
cardsContainer?.addEventListener('click', (e) => {
    const arrowBtn = e.target.closest('.arrow-btn');
    if (!arrowBtn) return; 

    // Hanapin 'yung parent card at kunin 'yung bagong data attributes
    const card = arrowBtn.closest('.event-card');
    const activityId = card?.dataset.activityId; 
    const activityType = card?.dataset.activityType;
    
    if (activityId && activityType) {
        const uniqueActivityId = `${activityType}_${activityId}`; // e.g., "event_17"
        
        let viewed = getViewedActivities();
        
        if (!viewed.includes(uniqueActivityId)) {
            viewed.push(uniqueActivityId);
            // I-save ulit 'yung updated na listahan sa bagong key
            localStorage.setItem('viewedCertificateActivities', JSON.stringify(viewed));
        }
    }
});
  
});
</script>
</body>
</html>