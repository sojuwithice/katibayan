<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/view-youth-profile.css') }}">
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

    <div class="nav-item">
      <a href="#" class="nav-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
        <i data-lucide="chevron-down" class="submenu-arrow"></i>
      </a>
      <div class="submenu">
        <a href="{{ route('sk-eventpage') }}">Events List</a>
        <a href="{{ route('youth-program-registration') }}">Youth Registration</a>
      </div>
    </div>

    <a href="{{ route('sk-evaluation-feedback') }}">
      <i data-lucide="message-square-quote"></i>
      <span class="label">Feedbacks</span>
    </a>

    <a href="{{ route('sk-polls') }}">
      <i data-lucide="vote"></i>
      <span class="label">Polls</span>
    </a>

    <a href="{{ route('youth-suggestion') }}">
      <i data-lucide="lightbulb"></i>
      <span class="label">Suggestion Box</span>
    </a>
    
    <a href="{{ route('reports') }}">
      <i data-lucide="file-chart-column"></i>
      <span class="label">Reports</span>
    </a>

    <a href="{{ route('sk-services-offer') }}">
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
        <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="Logo">
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
    <img src="{{ $currentUser && $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('images/default-avatar.png') }}" 
         alt="User" class="avatar" id="profileToggle">
    <div class="profile-dropdown">
        <div class="profile-header">
            <img src="{{ $currentUser && $currentUser->avatar ? asset('storage/' . $currentUser->avatar) : asset('images/default-avatar.png') }}" 
                 alt="User" class="profile-avatar">
            <div class="profile-info">
                <h4>{{ $currentUser->given_name }} {{ $currentUser->middle_name ?? '' }} {{ $currentUser->last_name }} {{ $currentUser->suffix ?? '' }}</h4>
                <div class="profile-badge">
                    <span class="badge">{{ $roleBadge }}</span>
                    <span class="badge">{{ $currentUserAge }} yrs old</span>
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
    </header>

    <main class="youth-profile-container">
  <!-- Profile Header -->
  <section class="profile-header">
    <div class="profile-left">
        <div class="photo-wrapper">
            <img src="{{ $youth->avatar ? asset('storage/' . $youth->avatar) : 'https://i.pravatar.cc/120' }}" 
                 alt="Profile" class="profile-photo">
        </div>
        <div class="profile-info">
            <h2>{{ $youth->given_name }} {{ $youth->middle_name ?? '' }} {{ $youth->last_name }} {{ $youth->suffix ?? '' }} 
                <span>| {{ $age }} years old</span>
            </h2>
            <div class="profile-tags">
                <span class="tag member">{{ $youth->role === 'sk' ? 'SK Member' : 'KK Member' }}</span>
                @if($isRegisteredVoter)
                <span class="tag voter">Registered Voter</span>
                @endif
                <span class="tag location">
                    <i class="fa-solid fa-location-dot"></i> 
                    {{ $youth->barangay->name ?? 'N/A' }}, {{ $youth->city->name ?? 'N/A' }}
                </span>
            </div>
        </div>
    </div>
</section>


  <!-- GRID SECTION -->
<div class="profile-grid">
  <!-- LEFT SIDE -->
  <div class="left-column">
    <div class="top-cards">
      <!-- === Youth Progress Card === -->
      <div class="progress-card card">
        <p>Youth Progress</p>
        <div class="progress-ring">
          <svg>
            <circle class="bg" cx="45" cy="45" r="40"></circle>
            <circle
              class="progress"
              cx="45"
              cy="45"
              r="40"
              stroke-dasharray="251"
              stroke-dashoffset="{{ 251 - (251 * $progressPercentage / 100) }}"
            ></circle>
          </svg>
          <div class="progress-text">{{ $progressPercentage }}%</div>
        </div>
        <span class="note">
          @if($progressPercentage < 30)
            Still a long journey ahead!
          @elseif($progressPercentage < 70)
            Making good progress!
          @else
            Excellent participation!
          @endif
        </span>
      </div>

      <!-- === Evaluated Programs Card === -->
      <div class="card evaluated-card">
        <h3>Evaluated Programs</h3>
        <div class="big-number">{{ $evaluatedPrograms }}</div>
        <div class="bar-container">
          <div class="bar-fill" style="width: {{ min($evaluatedPrograms * 20, 100) }}%;"></div>
        </div>
        <div class="note">
          @if($evaluatedPrograms > 0)
            {{ $evaluatedPrograms }} program{{ $evaluatedPrograms > 1 ? 's' : '' }} evaluated
          @else
            No programs evaluated yet
          @endif
        </div>
      </div>
    </div>

    <!-- === Events and Programs === -->
    <div class="events-card">
      <div class="events-header">
        <h3>Events and Programs Attended</h3>
        <div class="custom-dropdown">
          <div class="dropdown-selected">
            All Months <i class="fa-solid fa-chevron-down"></i>
          </div>
          <ul class="dropdown-options">
            <li data-value="all">All Months</li>
            <li data-value="This Month">This Month</li>
            <li data-value="Last Month">Last Month</li>
          </ul>
        </div>
      </div>

      <div class="event-list" id="eventList">
        @forelse($eventsByMonth as $month => $events)
          <div class="month-label">{{ $month }}</div>
          @foreach($events as $event)
            <div class="event-item" data-month="{{ $month }}">
              <div class="date">{{ $event['date'] }}</div>
              <p>{{ $event['title'] }}</p>
            </div>
          @endforeach
        @empty
          <div class="no-events">
            <p>No events attended yet.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE -->
  <div class="right-column">
    <div class="card kk-profile">
      <div class="kk-header">
        <h2>KK Profile</h2>
        <button class="print-btn"><i class="fa-solid fa-print"></i> Print</button>
      </div>

      <p class="kk-desc">
        The KK profiling is an organized summary of the demographic information of the Katipunan ng Kabataan members. 
        This provides a clear basis for developing programs and policies that respond to the needs of the youth sector.
      </p>

      <hr class="divider" />

      <!-- Scrollable Info Section -->
      <div class="kk-scrollable">
        <div class="kk-section">
          <h3>I. Profile</h3>
          <p class="subtext">Name of Respondent</p>

          <div class="info-grid">
            <div><strong>Last Name:</strong> {{ $youth->last_name }}</div>
            <div><strong>First Name:</strong> {{ $youth->given_name }}</div>
            <div><strong>Middle Name:</strong> {{ $youth->middle_name ?? 'N/A' }}</div>
            <div><strong>Address:</strong> 
                {{ ($youth->purok_zone ? $youth->purok_zone . ', ' : '') }}
                {{ $youth->barangay->name ?? '' }}{{ $youth->barangay && $youth->city ? ', ' : '' }}
                {{ $youth->city->name ?? '' }}{{ $youth->zip_code ? ' ' . $youth->zip_code : '' }}
                {{ !$youth->purok_zone && !$youth->barangay && !$youth->city ? 'N/A' : '' }}
            </div>
            <div><strong>Date of Birth:</strong> {{ $formattedDOB }}</div>
            <div><strong>Sex:</strong> {{ ucfirst($youth->sex) }}</div>
            <div><strong>Contact Number:</strong> {{ $youth->contact_no ?? 'N/A' }}</div>
            <div><strong>Personal Email:</strong> {{ $youth->email }}</div>
        </div>

          <h3>II. Demographics</h3>
          <p class="subtext">Please provide your demographic details as accurately as possible</p>

          <div class="info-grid">
            <div><strong>Educational Attainment:</strong> {{ $youth->education ?? 'N/A' }}</div>
            <div><strong>Occupation:</strong> {{ $youth->work_status ?? 'N/A' }}</div>
            <div><strong>Youth Classification:</strong> {{ $youthClassification }}</div>
            <div><strong>Civil Status:</strong> {{ ucfirst($youth->civil_status) }}</div>
            <div><strong>Barangay:</strong> {{ $youth->barangay->name ?? 'N/A' }}</div>
            <div><strong>Registered Voter:</strong> {{ $isRegisteredVoter ? 'Yes' : 'No' }}</div>
            

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- END of .profile-grid -->

</main>



  

  <script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons + sidebar toggle ===
  lucide.createIcons();
  
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');

  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');
    });
  }

  // === Submenus ===
  const submenuTriggers = document.querySelectorAll('.nav-item > .nav-link');

  submenuTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault(); 
      
      const parentItem = trigger.closest('.nav-item');
      const wasOpen = parentItem.classList.contains('open');

      document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('open');
      });

      if (!wasOpen) {
        parentItem.classList.add('open');
      }
    });
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

const printBtn = document.querySelector('.print-btn');
printBtn?.addEventListener('click', () => {
    const contentToPrint = document.querySelector('.kk-scrollable');
    if (!contentToPrint) return;

    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Youth Profile - {{ $youth->given_name }} {{ $youth->last_name }}</title>
            <style>
                body { font-family: 'Montserrat', sans-serif; padding: 20px; line-height: 1.6; }
                h2 { color: #333; margin-bottom: 10px; }
                h3 { margin: 20px 0 10px 0; color: #555; }
                .kk-section { margin-bottom: 20px; }
                .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 10px 0; }
                .info-grid div { padding: 5px 0; border-bottom: 1px solid #eee; }
                strong { color: #333; }
                .divider { border: 0; border-top: 2px solid #ccc; margin: 20px 0; }
                @media print {
                    body { padding: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <h2>Youth Profile - {{ $youth->given_name }} {{ $youth->last_name }}</h2>
            ${contentToPrint.innerHTML}
        </body>
    </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
});

  const dropdown = document.querySelector('.custom-dropdown');
const selected = dropdown.querySelector('.dropdown-selected');
const options = dropdown.querySelector('.dropdown-options');
const eventItems = document.querySelectorAll('.event-item');
const monthLabels = document.querySelectorAll('.month-label');

// Toggle dropdown visibility
selected.addEventListener('click', () => {
  dropdown.classList.toggle('active');
});

// Handle option click
options.querySelectorAll('li').forEach(li => {
  li.addEventListener('click', () => {
    const value = li.dataset.value;
    selected.innerHTML = li.textContent + ' <i class="fa-solid fa-chevron-down"></i>';
    dropdown.classList.remove('active');

    // Call filter function
    filterEvents(value);
  });
});

// Close dropdown if clicked outside
document.addEventListener('click', (e) => {
  if (!dropdown.contains(e.target)) dropdown.classList.remove('active');
});

// === Filter Events Function ===
// === Filter Events Function ===
function filterEvents(filter) {
    eventItems.forEach(item => {
        const month = item.dataset.month.toLowerCase();
        
        if (filter === 'all') {
            item.style.display = 'block';
        } else if (filter === 'This Month') {
            // Get current month and year
            const now = new Date();
            const currentMonth = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }).toLowerCase();
            item.style.display = month === currentMonth ? 'block' : 'none';
        } else if (filter === 'Last Month') {
            // Get last month
            const now = new Date();
            const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            const lastMonthStr = lastMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }).toLowerCase();
            item.style.display = month === lastMonthStr ? 'block' : 'none';
        } else {
            item.style.display = 'none';
        }
    });

    monthLabels.forEach(label => {
        const month = label.textContent.toLowerCase();
        const hasVisibleEvents = [...eventItems].some(
            item => item.style.display === 'block' && item.dataset.month.toLowerCase() === month
        );
        label.style.display = hasVisibleEvents ? 'block' : 'none';
    });
}




});
</script>
</body>
</html>