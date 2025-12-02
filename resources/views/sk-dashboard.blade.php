<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
        
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
  <title>KatiBayan - SK Dashboard</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('css/sk-dashboard.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Load Lucide with proper fallback -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Pass PHP data to JavaScript -->
  <script>
    window.demographicsData = <?php echo json_encode($demographicsData ?? []); ?>;
    window.populationData = <?php echo json_encode($populationData ?? []); ?>;
    window.ageGroupData = <?php echo json_encode($ageGroupData ?? []); ?>;
    window.remindersData = <?php echo json_encode($remindersData ?? []); ?>;
    window.monthlyEventsData = <?php echo json_encode($monthlyEventsData ?? []); ?>;
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
      <i data-lucide="layout-dashboard" class="lucide-icon"></i>
      <span class="label">Dashboard</span>
    </a>

    <a href="{{ route('sk.analytics') }}">
      <i data-lucide="chart-pie" class="lucide-icon"></i>
      <span class="label">Analytics</span>
    </a>

    <a href="{{ route('youth-profilepage') }}">
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
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
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

        <div class="notification-wrapper">
    <i class="fas fa-bell"></i>
    @php
        $skNotificationCount = $notifications->where('recipient_role', 'sk')->count();
    @endphp

    @if($skNotificationCount > 0)
        <span class="notif-count">{{ $skNotificationCount }}</span>
    @endif

    <div class="notif-dropdown">
        <div class="notif-header">
            <strong>Notification</strong>
            @if($skNotificationCount > 0)
                <span>{{ $skNotificationCount }}</span>
            @endif
        </div>

        <ul class="notif-list">
            @foreach($notifications->where('recipient_role', 'sk') as $notif)
                @php
                    // Define default link (palitan kung may specific route)
                    $link = '#';
                    if ($notif->type === 'sk_request_approved') {
                        $link = route('profile.show'); // halimbawa route
                    }

                    $title = $notif->title ?? 'Notification';
                    $message = $notif->message ?? 'You have a new notification.';
                @endphp

                <li>
                    <a href="{{ $link }}" class="notif-link {{ $notif->is_read ? '' : 'unread' }}" data-id="{{ $notif->id }}">
                        <div class="notif-dot-container">
                            @if(!$notif->is_read)
                                <span class="notif-dot"></span>
                            @else
                                <span class="notif-dot-placeholder"></span>
                            @endif
                        </div>

                        <div class="notif-main-content">
                            <div class="notif-header-line">
                                <strong>{{ $title }}</strong>
                                <span class="notif-timestamp">
                                    {{ $notif->created_at->format('m/d/Y g:i A') }}
                                </span>
                            </div>
                            <p class="notif-message">{{ $message }}</p>
                        </div>
                    </a>
                </li>
            @endforeach

            @if($notifications->where('recipient_role', 'sk')->isEmpty())
                <li class="no-notifications">
                    <p>No new notifications</p>
                </li>
            @endif
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

    <!-- Monthly Events (row1 col1) -->
<div class="card monthly-events-card">
  <div class="card-header">
    <h3>Monthly Events</h3>
  </div>
  <canvas id="monthlyEventsChart"></canvas>
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

    <div class="card sk-committee-card">
  <div class="card-header">
    <h3>SK COMMITTEE</h3>
    <button class="options-btn">⋯</button>
    <div class="options-dropdown">
      <ul>
        <li><a href="#" id="openRequestListBtn">See Request List</a></li>
        <li><a href="#">Manage Roles</a></li>
      </ul>
    </div>
  </div>

  <div class="sk-chairperson">
    <div class="sk-member-item">
      <div class="member-info">
        @if($skChairperson)
            <span class="member-name">
                {{-- Format: MARI JOY S. NOVORA --}}
                {{ strtoupper($skChairperson->given_name) }} 
                {{ $skChairperson->middle_name ? strtoupper(substr($skChairperson->middle_name, 0, 1)) . '.' : '' }} 
                {{ strtoupper($skChairperson->last_name) }}
            </span>
            <span class="member-role">SK CHAIRPERSON</span>
        @else
            <span class="member-name" style="color:#999;">(VACANT)</span>
            <span class="member-role">SK CHAIRPERSON</span>
        @endif
      </div>
    </div>
  </div>

  <h4 class="sk-members-title">MEMBERS</h4>

  <div class="sk-members-list">
    
    @foreach($skMembers as $member)
    <div class="sk-member-item">
      <div class="member-info">
        
        <span class="member-name">
            {{ strtoupper($member->given_name) }} 
            {{ $member->middle_name ? strtoupper(substr($member->middle_name, 0, 1)) . '.' : '' }} 
            {{ strtoupper($member->last_name) }}
        </span>

        <span class="member-role">
            {{ strtoupper(str_replace('_', ' ', $member->sk_role)) }}
        </span>

        {{-- Checheck kung may laman ang committee column, tapos i-e-explode by comma --}}
        @if(!empty($member->committees))
            @foreach(explode(',', $member->committees) as $committee)
                <span class="member-committee">
                  <span class="dot"></span> {{ strtoupper(trim($committee)) }}
                </span>
            @endforeach
        @endif

      </div>
    </div>
    @endforeach

  </div>
</div>

<div id="requestListModal" class="request-list-overlay">
  
  <div class="request-list-modal-content">
    
    <div class="request-list-header">
      <h2>Request List <span class="badge">3</span></h2>
      </div>
    
    <div class="request-list-body">
      
      <div class="request-item">
        <div class="request-info">
          <span class="request-timestamp">Just now 10/10/2025</span>
          <p class="request-text"><strong>MARI JOY S. NOVORA</strong> is asking request to access SK role</p>
        </div>
        <div class="request-actions">
          <button class="btn btn-accept">Accept</button>
          <button class="btn btn-reject">Reject</button>
        </div>
      </div>

      <div class="request-item">
        <div class="request-info">
          <span class="request-timestamp">Just now 10/10/2025</span>
          <p class="request-text"><strong>MARI JOY S. NOVORA</strong> is asking request to access SK role</p>
        </div>
        <div class="request-actions">
          <button class="btn btn-accept">Accept</button>
          <button class="btn btn-reject">Reject</button>
        </div>
      </div>

      <div class="request-item">
        <div class="request-info">
          <span class="request-timestamp">Just now 10/10/2025</span>
          <p class="request-text"><strong>MARI JOY S. NOVORA</strong> is asking request to access SK role</p>
        </div>
        <div class="request-actions">
          <button class="btn btn-accept">Accept</button>
          <button class="btn btn-reject">Reject</button>
        </div>
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
      </header>
      <div class="days"></div>
    </div>

    <!-- Reminders -->
<div class="reminders-card">
  <h3 class="reminders-title">Reminders</h3>

  <div class="reminders-scroll-area">
    
    <div class="reminders-section">
      <h4 class="section-label">Today</h4>
      <div id="todayReminders">
        <div class="no-reminders">No events for today</div>
      </div>
    </div>

    <div class="reminders-section">
      <h4 class="section-label">Upcoming</h4>
      <div id="upcomingReminders">
        <div class="no-reminders">No upcoming events</div>
      </div>
    </div>

  </div> </div>



  </div>
</div>

<script>
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

// Lucide Icons Initialization with Error Handling
function initializeLucideIcons() {
    if (typeof lucide === 'undefined') {
        console.warn('Lucide not loaded, using Font Awesome fallback');
        replaceLucideIcons();
        return;
    }
    
    try {
        lucide.createIcons();
        console.log('Lucide icons initialized successfully');
    } catch (error) {
        console.error('Error initializing Lucide:', error);
        replaceLucideIcons();
    }
}

// Fallback function to replace Lucide icons with Font Awesome
function replaceLucideIcons() {
    console.log('Using Font Awesome as fallback for icons');
    
    const iconMap = {
        'layout-dashboard': 'fas fa-chart-pie',
        'chart-pie': 'fas fa-chart-pie',
        'users': 'fas fa-users',
        'calendar': 'fas fa-calendar-alt',
        'chevron-down': 'fas fa-chevron-down',
        'message-square-quote': 'fas fa-comment-alt',
        'vote': 'fas fa-vote-yea',
        'lightbulb': 'fas fa-lightbulb',
        'file-chart-column': 'fas fa-chart-bar',
        'hand-heart': 'fas fa-hands-helping',
        'bell': 'fas fa-bell',
        'user': 'fas fa-user',
        'cog': 'fas fa-cog',
        'question-circle': 'fas fa-question-circle',
        'star': 'fas fa-star',
        'sign-out-alt': 'fas fa-sign-out-alt',
        'chevron-left': 'fas fa-chevron-left',
        'chevron-right': 'fas fa-chevron-right',
        'arrow-left': 'fas fa-arrow-left'
    };

    document.querySelectorAll('.lucide-icon').forEach(icon => {
        const iconName = icon.getAttribute('data-lucide');
        if (iconName && iconMap[iconName]) {
            const faIcon = document.createElement('i');
            faIcon.className = iconMap[iconName];
            icon.parentNode.replaceChild(faIcon, icon);
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
  // Initialize Lucide icons first
  initializeLucideIcons();
  
  // === sidebar toggle ===
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

      // Close all other submenus
      document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('open');
      });

      // Open clicked submenu if it wasn't open
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
    if (sidebar && !sidebar.contains(e.target) && menuToggle && !menuToggle.contains(e.target)) {
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

  // === Youth Engagement Chart ===
  const engagementCtx = document.getElementById('engagementChart')?.getContext('2d');
  if (engagementCtx) {
    try {
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
    } catch (error) {
      console.error('Error creating engagement chart:', error);
    }
  }

  // === Monthly Events Chart ===
  const monthlyEventsCtx = document.getElementById('monthlyEventsChart')?.getContext('2d');
  if (monthlyEventsCtx) {
    try {
      const monthlyEventsData = window.monthlyEventsData || {};
      
      // Use actual data from backend
      const labels = monthlyEventsData.labels || ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      const events = monthlyEventsData.events || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

      new Chart(monthlyEventsCtx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: "Events",
            data: events,
            backgroundColor: "#3C87C6",
            borderRadius: 6,
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const events = context.raw;
                  return `${events} ${events === 1 ? 'event' : 'events'}`;
                },
                title: function(context) {
                  return `${context[0].label} Events`;
                }
              }
            }
          },
          scales: {
            y: { 
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return value + (value === 1 ? ' event' : ' events');
                }
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
    } catch (error) {
      console.error('Error creating monthly events chart:', error);
    }
  }

  // === Youth Demographics Chart ===
  const demoCtx = document.getElementById('demographicsChart')?.getContext('2d');
  if (demoCtx) {
    try {
      const demographicsData = window.demographicsData || {};
      
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
    } catch (error) {
      console.error('Error creating demographics chart:', error);
    }
  }

  // === Youth Age Chart ===
  const ageCtx = document.getElementById('ageChart')?.getContext('2d');
  if (ageCtx) {
    try {
      const ageGroupData = window.ageGroupData || {};
      const childCount = ageGroupData.child_count || 0;
      const coreCount = ageGroupData.core_count || 0;
      const adultCount = ageGroupData.adult_count || 0;
      
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
    } catch (error) {
      console.error('Error creating age chart:', error);
    }
  }

  // === Youth Population Chart ===
  const populationChart = document.getElementById('populationChart');
  if (populationChart) {
    try {
      const ctx = populationChart.getContext('2d');
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
    } catch (error) {
      console.error('Error creating population chart:', error);
    }
  }

  // Load reminders when page loads
  loadReminders();

  // Refresh notifications every 30 seconds
  setInterval(() => {
      loadNotifications();
      updateNotificationCount();
  }, 30000);

  // Options dropdown functionality
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

// --- Request List Modal Logic (FIXED) ---
  
  const openRequestListBtn = document.getElementById('openRequestListBtn');
  const requestListModal = document.getElementById('requestListModal');
  const skCommitteeDropdown = document.querySelector('.sk-committee-card .options-dropdown');
  
  // === ETO 'YUNG FIX ===
  // Gagamitin natin 'yung global variable na ginagamit mo sa buong script
  const csrfToken = window.csrfToken; 

  // Naglagay ako ng "if (requestListModal)" para sigurado
  if (requestListModal) {
    
    const requestListBody = requestListModal.querySelector('.request-list-body');
    const requestListBadge = requestListModal.querySelector('.request-list-header .badge');

    /**
     * Function para buksan ang modal AT kumuha ng data
     */
    async function openAndPopulateRequestList() {
      // Nilipat ko 'yung safety check dito
      if (!requestListModal || !requestListBody) {
        console.error('Request List Modal elements not found.');
        return; 
      }

      requestListModal.style.display = 'flex';
      requestListBody.innerHTML = '<p>Loading requests...</p>'; // Loading state

      try {
        const response = await fetch("{{ route('sk.requests.index') }}");
        const requests = await response.json();

        requestListBody.innerHTML = ''; // Clear loading message
        
        // Safety check para sa badge
        if(requestListBadge) {
          requestListBadge.textContent = requests.length;
        }

        if (requests.length === 0) {
          requestListBody.innerHTML = '<p style="padding: 0 40px;">No pending requests.</p>';
          return;
        }

       requests.forEach(req => {
        
        let userName = 'Unknown User';
        if (req.user) {
          userName = `${req.user.given_name} ${req.user.last_name}`;
        }

        const itemHTML = `
          <div class="request-item" data-id="${req.id}">
            <div class="request-info">
              <span class="request-timestamp">${new Date(req.created_at).toLocaleString()}</span>
              <p class="request-text"><strong>${userName}</strong> is asking request to access SK role</p>
            </div>
            <div class="request-actions">
              <button class="btn btn-accept" data-action="approve">Accept</button>
              <button class="btn btn-reject" data-action="reject">Reject</button>
            </div>
          </div>
        `;
        requestListBody.insertAdjacentHTML('beforeend', itemHTML);
      });

      } catch (error) {
        console.error('Error fetching requests:', error);
        requestListBody.innerHTML = '<p style="padding: 0 40px;">Could not load requests. Please try again.</p>';
      }
    }

    /**
     * Event listener para sa "See Request List"
     */
    if (openRequestListBtn) {
      openRequestListBtn.addEventListener('click', function(e) {
        e.preventDefault();
        openAndPopulateRequestList(); 
        
        if (skCommitteeDropdown && skCommitteeDropdown.classList.contains('show')) {
          skCommitteeDropdown.classList.remove('show');
        }
      });
    }

    // Event delegation for Accept/Reject buttons
if (requestListBody) {
  requestListBody.addEventListener('click', async function(e) {
    const button = e.target;
    const action = button.dataset.action;

    if (!action) return;

    const csrfToken = window.csrfToken;

    if (!csrfToken) {
      console.error('CSRF token missing!');
      alert('CSRF token is missing. Refresh the page.');
      return;
    }

    const requestItem = button.closest('.request-item');
    const requestId = requestItem.dataset.id;
    const url = `/sk/requests/${requestId}/${action}`;

    // Disable buttons during request
    requestItem.querySelectorAll('.btn').forEach(btn => btn.disabled = true);
    button.textContent = 'Processing...';

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ _token: csrfToken })
      });

      const data = await response.json().catch(() => ({}));

      if (response.ok) {
        const actionContainer = requestItem.querySelector('.request-actions');
        actionContainer.innerHTML = action === 'approve'
          ? '<span class="status-badge approved">Accepted</span>'
          : '<span class="status-badge rejected">Rejected</span>';

        // Update badge count
        if (requestListBadge) {
          const currentCount = parseInt(requestListBadge.textContent);
          requestListBadge.textContent = Math.max(0, currentCount - 1);
        }
      } else {
        console.error(data);
        alert(data.message || 'Failed to process request.');

        requestItem.querySelectorAll('.btn').forEach(btn => btn.disabled = false);
        button.textContent = action === 'approve' ? 'Accept' : 'Reject';
      }

    } catch (error) {
      console.error('Error processing request:', error);
      alert('An error occurred.');

      requestItem.querySelectorAll('.btn').forEach(btn => btn.disabled = false);
      button.textContent = action === 'approve' ? 'Accept' : 'Reject';
    }
  });
}

    /**
     * Event listener para sa pag-close ng modal
     */
    requestListModal.addEventListener('click', function(e) {
      if (e.target === requestListModal) {
        requestListModal.style.display = 'none';
      }
    });

  } else {
    console.warn('Request List Modal (id="requestListModal") not found.'); 
  }
  
  
  // --- DITO NAGTATAPOS 'YUNG REQUEST LIST CODE ---
});
</script>
</body>
</html>