<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - SK Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/sk-dashboard.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Pass PHP data to JavaScript -->
  <script>
    window.demographicsData = <?php echo json_encode($demographicsData ?? []); ?>;
    window.populationData = <?php echo json_encode($populationData ?? []); ?>;
    window.ageGroupData = <?php echo json_encode($ageGroupData ?? []); ?>;
    window.remindersData = <?php echo json_encode($remindersData ?? []); ?>;
    window.csrfToken = '{{ csrf_token() }}';
  </script>
</head>
<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
  <button class="menu-toggle">Menu</button>
  <div class="divider"></div>
  <nav class="nav">
    <a href="{{ route('sk.dashboard') }}" class="active">
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
          <i class="fas fa-bell" id="notificationBell"></i>
          <span class="notif-count" id="notificationCount">0</span>
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notifications</strong> 
              <span id="notificationsHeaderCount">0</span>
              <button class="mark-all-read" id="markAllRead">Mark all as read</button>
            </div>
            <ul class="notif-list" id="notificationsList">
              <li class="notif-loading">Loading notifications...</li>
            </ul>
          </div>
        </div>

        <!-- Profile Avatar -->
        <div class="profile-wrapper">
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ $user->given_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->suffix }}</h4>
                <div class="profile-badge">
                  <span class="badge">{{ $roleBadge }}</span>
                  <span class="badge">{{ $age }} yrs old</span>
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
              <li class="logout-item">
                <a href="loginpage" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
            
            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- main content -->
    <div class="welcome-card">
    <h2>Welcome, SK Chair {{ Auth::user()->given_name ?? 'User' }}!</h2>
    </div>


<div class="dashboard-container">
  <!-- LEFT PANEL -->
  <div class="left-panel">
    
    <!-- Engagement (row1 col1) -->
    <div class="card engagement-card">
      <h3>Youth Engagement Level</h3>
      <canvas id="engagementChart"></canvas>
    </div>

    <!-- Youth Engagement in Activities (row1 col1) -->
<div class="card activities-card">
  <div class="card-header">
    <h3>Youth Engagement in Activities</h3>
  </div>
  <canvas id="activitiesChart"></canvas>
</div>

    <!-- Youth Age (row1+row2 col2) -->
<div class="card youth-age-card">
  
  <!-- Card Header -->
  <div class="card-header">
    <h3>Youth Age Group</h3>
    <button class="options-btn">⋯</button>
    <!-- Dropdown -->
    <div class="options-dropdown">
      <ul>
        <li>Purok 1</li>
        <li>Purok 2</li>
        <li>Purok 3</li>
        <li>Purok 4</li>
        <li>Purok 5</li>
        <li>Purok 6</li>
        <li>Purok 7</li>
        <li>Purok 8</li>
      </ul>
    </div>
  </div>

  <div class="chart-container">
  <!-- Chart -->
  <canvas id="ageChart"></canvas>

  <!-- Custom Legend -->
  <div class="legend">
    <div class="legend-item">
      <span class="dot child"></span> Child Youth 15-17
    </div>
    <div class="legend-item">
      <span class="dot core"></span> Core Youth 18-24
    </div>
    <div class="legend-item">
      <span class="dot adult"></span> Adult Youth 25-30
    </div>
  </div>
</div>


</div>



    <!-- Demographics (row2 col1) -->
    <div class="card demographics-card">
      <h3>Youth Demographics by Classification</h3>
      <canvas id="demographicsChart"></canvas>
      <div class="legend">
        <span><span class="dot male"></span> Male</span>
        <span><span class="dot female"></span> Female</span>
      </div>
    </div>

    <!-- Announcements -->
<div class="announcements-section">
  <div class="announcements-header">
    <h3 class="announcements-title">Announcements</h3>
    <button class="options-btn header-options">⋯</button>

    <!-- Dropdown for header -->
    <div class="options-dropdown">
      <ul>
        <li>All</li>
        <li>Events</li>
        <li>Programs</li>
        <li>System Update</li>
      </ul>
    </div>
  </div>

  <div class="announcements">
    <div class="card">
      <div class="card-content">
        <div class="icon"><i class="fas fa-info"></i></div>
        <div class="text">
          <strong>Important Announcement: No Office Today</strong>
          <p>The office is closed today. We sincerely apologize for any inconvenience.</p>
        </div>
      </div>
      <button class="options-btn">⋯</button>
      <!-- Dropdown for this card -->
      <div class="options-dropdown">
        <ul>
          <li>Edit</li>
          <li>Delete</li>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon"><i class="fas fa-print"></i></div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options-btn">⋯</button>
      <div class="options-dropdown">
        <ul>
          <li>Edit</li>
          <li>Delete</li>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon"><i class="fas fa-print"></i></div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options-btn">⋯</button>
      <div class="options-dropdown">
        <ul>
          <li>Edit</li>
          <li>Delete</li>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon"><i class="fas fa-print"></i></div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options-btn">⋯</button>
      <div class="options-dropdown">
        <ul>
          <li>Edit</li>
          <li>Delete</li>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-content">
        <div class="icon"><i class="fas fa-print"></i></div>
        <div class="text">
          <strong>Notice: No Printing Service Today</strong>
          <p>Please be informed that printing services are closed today.</p>
        </div>
      </div>
      <button class="options-btn">⋯</button>
      <div class="options-dropdown">
        <ul>
          <li>Edit</li>
          <li>Delete</li>
        </ul>
      </div>
    </div>
  </div>
</div>




  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <!-- Calendar -->
    <div class="calendar card">
      <header>
        <button class="prev"><i class="fas fa-chevron-left"></i></button>
        <h3></h3>
        <button class="next"><i class="fas fa-chevron-right"></i></button>
        <a href="{{ route('sk-eventpage') }}" title="View full month">
          <i class="fas fa-calendar calendar-toggle"></i>
        </a>
      </header>
      <div class="days"></div>
    </div>

    <!-- Reminders -->
<div class="reminders-card">
  <h3 class="reminders-title">Reminders</h3>

  <!-- Today Section -->
  <div class="reminders-section">
    <h4 class="section-label">Today</h4>
    <div id="todayReminders">
      <!-- Today's events will be loaded here dynamically -->
      <div class="no-reminders">No events for today</div>
    </div>
  </div>

  <!-- Upcoming Section -->
  <div class="reminders-section">
    <h4 class="section-label">Upcoming</h4>
    <div id="upcomingReminders">
      <!-- Upcoming events will be loaded here dynamically -->
      <div class="no-reminders">No upcoming events</div>
    </div>
  </div>
</div>

<!-- Youth Population -->
<div class="youth-population card">
  <h3 class="population-title">Youth Population</h3>
  <div class="population-chart">
    <canvas id="populationChart"></canvas>
    <div class="population-center">
      <span class="population-total" id="populationTotal">0</span>
      <p>Youth population in your barangay</p>
    </div>
  </div>

  <div class="population-legend">
    <div class="legend-item">
      <span>Female</span>
      <span id="femaleCount">0</span>
      <span class="dot female"></span>
    </div>
    <div class="legend-item">
      <span>Male</span>
      <span id="maleCount">0</span>
      <span class="dot male"></span>
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

      // Isara muna lahat ng ibang bukas na submenu
      document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('open');
      });

      // Kung hindi pa bukas yung pinindot mo, buksan mo siya.
      if (!wasOpen) {
        parentItem.classList.add('open');
      }
    });
  });

  // === Notifications System ===
  let notifications = [];

  // Load notifications
  async function loadNotifications() {
      try {
          const response = await fetch('{{ route("notifications.list") }}');
          const data = await response.json();
          
          if (data.notifications) {
              notifications = data.notifications;
              updateNotificationUI();
          }
      } catch (error) {
          console.error('Error loading notifications:', error);
      }
  }

  // Update notification count
  async function updateNotificationCount() {
      try {
          const response = await fetch('{{ route("notifications.count") }}');
          const data = await response.json();
          
          const notifCount = document.getElementById('notificationCount');
          const headerCount = document.getElementById('notificationsHeaderCount');
          
          if (notifCount) notifCount.textContent = data.count;
          if (headerCount) headerCount.textContent = data.count;
      } catch (error) {
          console.error('Error updating notification count:', error);
      }
  }

  // Update notifications UI
  function updateNotificationUI() {
      const notificationsList = document.getElementById('notificationsList');
      if (!notificationsList) return;

      if (notifications.length === 0) {
          notificationsList.innerHTML = '<li class="no-notifications">No notifications</li>';
          return;
      }

      notificationsList.innerHTML = notifications.map(notif => `
          <li class="notification-item ${notif.is_read ? 'read' : 'unread'}" data-id="${notif.id}">
              <div class="notif-icon">
                  <i class="fas fa-star ${notif.is_read ? 'read' : 'unread'}"></i>
              </div>
              <div class="notif-content">
                  <strong>${notif.message}</strong>
                  <p>${notif.created_at}</p>
              </div>
              ${!notif.is_read ? '<span class="notif-dot"></span>' : ''}
          </li>
      `).join('');

      // Add click events to notification items
      document.querySelectorAll('.notification-item').forEach(item => {
          item.addEventListener('click', async (e) => {
              e.stopPropagation();
              const notificationId = item.dataset.id;
              
              // Mark as read
              await markNotificationAsRead(notificationId);
              
              // Remove highlight immediately
              item.classList.add('read');
              item.classList.remove('unread');
              const dot = item.querySelector('.notif-dot');
              if (dot) dot.remove();
              
              // Update count
              await updateNotificationCount();
          });
      });
  }

  // Mark notification as read
  async function markNotificationAsRead(notificationId) {
      try {
          const response = await fetch(`/notifications/${notificationId}/read`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': window.csrfToken
              }
          });
          
          const data = await response.json();
          return data.success;
      } catch (error) {
          console.error('Error marking notification as read:', error);
          return false;
      }
  }

  // Mark all as read
  async function markAllAsRead() {
      try {
          const response = await fetch('{{ route("notifications.read-all") }}', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': window.csrfToken
              }
          });
          
          const data = await response.json();
          if (data.success) {
              // Update UI immediately
              document.querySelectorAll('.notification-item').forEach(item => {
                  item.classList.add('read');
                  item.classList.remove('unread');
                  const dot = item.querySelector('.notif-dot');
                  if (dot) dot.remove();
              });
              
              await updateNotificationCount();
          }
      } catch (error) {
          console.error('Error marking all as read:', error);
      }
  }

  // Initialize notifications
  loadNotifications();
  updateNotificationCount();

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

  // === Notifications Toggle ===
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
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');

    // Close options dropdown when clicking outside
    document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
  });

  // === Set up mark all as read button ===
  const markAllReadBtn = document.getElementById('markAllRead');
  if (markAllReadBtn) {
      markAllReadBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          markAllAsRead();
      });
  }

  // === Load Reminders from Backend ===
  function loadReminders() {
    const remindersData = window.remindersData || {};
    const todayEvents = remindersData.today || [];
    const upcomingEvents = remindersData.upcoming || [];

    // Load Today's Events
    const todayContainer = document.getElementById('todayReminders');
    if (todayEvents.length > 0) {
      todayContainer.innerHTML = '';
      todayEvents.forEach(event => {
        const reminderItem = createReminderItem(event, 'today');
        todayContainer.appendChild(reminderItem);
      });
    } else {
      todayContainer.innerHTML = '<div class="no-reminders">No events for today</div>';
    }

    // Load Upcoming Events
    const upcomingContainer = document.getElementById('upcomingReminders');
    if (upcomingEvents.length > 0) {
      upcomingContainer.innerHTML = '';
      upcomingEvents.forEach(event => {
        const reminderItem = createReminderItem(event, 'upcoming');
        upcomingContainer.appendChild(reminderItem);
      });
    } else {
      upcomingContainer.innerHTML = '<div class="no-reminders">No upcoming events</div>';
    }
  }

  // Function to create reminder item
  function createReminderItem(event, type) {
    const reminderItem = document.createElement('div');
    reminderItem.className = 'reminder-item';
    reminderItem.setAttribute('data-event-id', event.id);
    
    const categoryClass = event.category ? `category-${event.category.replace('_', '-')}` : '';
    
    reminderItem.innerHTML = `
      <div class="reminder-date ${categoryClass}">${event.date}</div>
      <div class="reminder-text">
        <strong>${event.title}</strong>
        <p>${event.full_date_time} • ${event.location}</p>
        ${event.category ? `<span class="event-category-badge">${formatCategory(event.category)}</span>` : ''}
      </div>
    `;

    // Add click event to navigate to events page
    reminderItem.style.cursor = 'pointer';
    reminderItem.addEventListener('click', () => {
      window.location.href = "{{ route('sk-eventpage') }}";
    });

    return reminderItem;
  }

  // Format category for display
  function formatCategory(category) {
    const categoryMap = {
      'active_citizenship': 'Active Citizenship',
      'economic_empowerment': 'Economic Empowerment',
      'education': 'Education',
      'health': 'Health',
      'sports': 'Sports'
    };
    return categoryMap[category] || category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
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

  // === Youth Engagement Chart ===
  const engagementCtx = document.getElementById('engagementChart')?.getContext('2d');
  if (engagementCtx) {
    new Chart(engagementCtx, {
      type: 'bar',
      data: {
        labels: ['Active', 'Less Active', 'Inactive'], 
        datasets: [{
          label: 'Youth Count',
          data: [120, 80, 60],
          backgroundColor: ['#3C87C6', '#7EE081', '#C3423F'],
          borderRadius: 10
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'right',
            labels: {
              boxWidth: 12,
              boxHeight: 12,
              padding: 10,
              font: { size: 10 },
              generateLabels: (chart) => {
                const dataset = chart.data.datasets[0];
                const customLabels = ['Active Youth', 'Less Active Youth', 'Inactive Youth']; 
                return dataset.data.map((_, index) => ({
                  text: customLabels[index],
                  fillStyle: dataset.backgroundColor[index],
                  strokeStyle: dataset.backgroundColor[index],
                  index: index
                }));
              }
            }
          },
          title: { display: false }
        },
        scales: {
          x: {
            ticks: { display: false },
            grid: { drawTicks: false, drawBorder: false }
          },
          y: { beginAtZero: true }
        }
      }
    });
  }

  // === Youth Engagement in Activities Chart ===
const activitiesCtx = document.getElementById('activitiesChart')?.getContext('2d');
if (activitiesCtx) {
  new Chart(activitiesCtx, {
    type: 'bar',
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
      datasets: [{
        label: "Participants",
        data: [45, 60, 40, 80, 70, 55, 90, 65],
        backgroundColor: "#3C87C6",
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
}

  // === Youth Demographics Chart ===
  const demoCtx = document.getElementById('demographicsChart')?.getContext('2d');
  if (demoCtx) {
    // Get data from window object
    const demographicsData = window.demographicsData || {};
    
    console.log('Demographics Data:', demographicsData);
    
    // Use actual data from database
    const labels = demographicsData.labels || [
      'In-School Youth',
      'Out-of-School Youth',
      'Working Youth',
      'Person with disabilities',
      'Indigenous'
    ];
    
    const maleData = demographicsData.male_data || [0, 0, 0, 0, 0];
    const femaleData = demographicsData.female_data || [0, 0, 0, 0, 0];

    new Chart(demoCtx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          { 
            label: 'Male', 
            data: maleData, 
            backgroundColor: '#3C87C6',
            barPercentage: 0.6,
            categoryPercentage: 0.8
          },
          { 
            label: 'Female', 
            data: femaleData, 
            backgroundColor: '#E96BA8',
            barPercentage: 0.6,
            categoryPercentage: 0.8
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y', 
        scales: {
          x: { 
            beginAtZero: true, 
            grid: { 
              drawBorder: false,
              color: "rgba(0,0,0,0.1)"
            },
            ticks: {
              color: '#01214A',
              font: {
                size: 11
              }
            }
          },
          y: { 
            ticks: { 
              color: '#01214A', 
              font: { 
                weight: 600,
                size: 11
              } 
            }, 
            grid: { 
              display: false 
            } 
          }
        },
        plugins: {
          legend: { display: false },
          title: { display: false },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: { size: 12 },
            bodyFont: { size: 11 },
            callbacks: {
              label: function(context) {
                return `${context.dataset.label}: ${context.raw} youth`;
              }
            }
          }
        }
      }
    });
  }

  // === Youth Age Chart ===
  const ageCtx = document.getElementById('ageChart')?.getContext('2d');
  if (ageCtx) {
    // Get age group data from window object - FILTERED BY SAME BARANGAY
    const ageGroupData = window.ageGroupData || {};
    const childCount = ageGroupData.child_count || 0;
    const coreCount = ageGroupData.core_count || 0;
    const adultCount = ageGroupData.adult_count || 0;
    
    console.log('Age Group Data:', ageGroupData);
    
    new Chart(ageCtx, {
      type: 'pie',
      data: {
        labels: ["Child Youth 15-17", "Core Youth 18-24", "Adult Youth 25-30"],
        datasets: [{
          label: "Age Group",
          data: [childCount, coreCount, adultCount], 
          backgroundColor: ["#FFCA3A", "#3C87C6", "#8AC926"],
          borderWidth: 1,
          borderColor: "#fff"
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } }
      }
    });
  }

  // === Youth Population Chart ===
  const populationChart = document.getElementById('populationChart');
  if (populationChart) {
    const ctx = populationChart.getContext('2d');
    
    // Get population data from window object - FILTERED BY SAME BARANGAY
    const populationData = window.populationData || {};
    const maleCount = populationData.male_count || 0;
    const femaleCount = populationData.female_count || 0;
    const totalCount = populationData.total_count || 0;
    
    // Update the population numbers in the HTML
    document.getElementById('populationTotal').textContent = totalCount;
    document.getElementById('maleCount').textContent = maleCount;
    document.getElementById('femaleCount').textContent = femaleCount;
    
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Female', 'Male'],
        datasets: [{
          data: [femaleCount, maleCount],
          backgroundColor: ['#f48fb1', '#114B8C'],
          borderWidth: 0,
          cutout: '70%' 
        }]
      },
      options: {
        plugins: {
          legend: { display: false }, 
          tooltip: { enabled: true }
        }
      }
    });
  }

  // Load reminders when page loads
  loadReminders();

  // Refresh notifications every 30 seconds
  setInterval(() => {
      loadNotifications();
      updateNotificationCount();
  }, 30000);

document.querySelectorAll('.options-btn, .header-options').forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.stopPropagation();

    const dropdown = btn.nextElementSibling;
    if (!dropdown || !dropdown.classList.contains('options-dropdown')) return;

    document.querySelectorAll('.options-dropdown.show').forEach(d => {
      if (d !== dropdown) d.classList.remove('show');
    });

    
    dropdown.classList.toggle('show');
  });
});

document.addEventListener('click', () => {
  document.querySelectorAll('.options-dropdown.show').forEach(d => d.classList.remove('show'));
});


  
});
</script>
</body>
</html>