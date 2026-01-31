<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Youth Participation</title>
  <link rel="stylesheet" href="{{ asset('css/youth-participation.css') }}">
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

 <!-- Theme Toggle Button - ADDED HERE -->
        <button class="theme-toggle" id="themeToggle">
          <i data-lucide="moon"></i>
        </button>

        <!-- Notifications -->
        <div class="notification-wrapper" id="notificationWrapper">
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
        <div class="profile-wrapper" id="profileWrapper">
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

    <main class="container">
      <!-- Header Title -->
      <div class="welcome-card">
        <h2>Youth Participation Record</h2>
        <span class="year-badge">{{ date('Y') }}</span>
      </div>

      <!-- Youth Participation Record Section -->
      <section class="participation-section">
        <!-- Left Card -->
        <div class="card participation-card">
          <div class="committee-filter">
            <label for="committee">Committee</label>
            <div class="custom-select" id="committee" tabindex="0" role="listbox" aria-haspopup="listbox">
              <div class="selected" data-value="all">
                <span class="selected-text">All Committees</span>
                <i data-lucide="chevron-down" class="dropdown-icon"></i>
              </div>
              <ul class="options" role="presentation">
                <li data-value="all" role="option">All Committees</li>
                <li data-value="active_citizenship" role="option">Active Citizenship</li>
                <li data-value="economic_empowerment" role="option">Economic Empowerment</li>
                <li data-value="education" role="option">Education</li>
                <li data-value="health" role="option">Health</li>
                <li data-value="sports" role="option">Sports</li>
              </ul>
            </div>
          </div>

          <table class="participation-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Event Name</th>
                <th>Attendees</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if($events->count() > 0)
                @foreach($events as $event)
                  @php
                    $eventDate = \Carbon\Carbon::parse($event->event_date);
                    $formattedDate = $eventDate->format('F j, Y');
                    $categorySlug = strtolower(str_replace(' ', '_', $event->category));
                  @endphp
                  <tr class="event-row" data-category="{{ $categorySlug }}">
                    <td><em>{{ $formattedDate }}</em></td>
                    <td>
                      {{ $event->title }}<br>
                      @if($event->description)
                        <small>({{ Str::limit($event->description, 100) }})</small>
                      @endif
                    </td>
                    <td>{{ $event->attendances_count }} attendees</td>
                    <td>
                      <a href="{{ route('attendees.index', ['event_id' => $event->id]) }}" class="btn-view">View Attendees</a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="4" style="text-align: center; padding: 20px;">
                    <i class="fas fa-calendar-times" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
                    <p>No launched events found for your barangay.</p>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        <!-- Right Card -->
        <aside class="card top-youth-card">
          <!-- Header -->
          <div class="card-header">
            <h3>Top Active Youth</h3>
          </div>

          <!-- See All -->
          <div class="see-all">
            <a href="{{ route('youth-statuspage') }}">See All</a>
          </div>

          <!-- Youth List -->
          <ul class="youth-list">
            @if($topYouth->count() > 0)
              @foreach($topYouth as $index => $youth)
                <li>
                  <!-- Fixed: Use the same avatar logic as profile section -->
                  <img src="{{ $youth['avatar'] ? asset('storage/' . $youth['avatar']) : asset('images/default-avatar.png') }}" 
                       alt="{{ $youth['name'] }}" class="youth-avatar">
                  <div>
                    <strong>{{ $youth['name'] }}</strong><br>
                    <a href="#">{{ $youth['attendance_count'] }} Events and Programs Attended</a>
                  </div>
                </li>
              @endforeach
            @else
              <li style="text-align: center; padding: 20px;">
                <i class="fas fa-users" style="font-size: 24px; color: #ccc; margin-bottom: 10px;"></i>
                <p>No attendance records found.</p>
              </li>
            @endif
          </ul>
        </aside>
      </section>
    </main>
  </div>

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
      
      // Define variables for dropdown elements
      const notifWrapper = document.getElementById('notificationWrapper');
      const profileWrapper = document.getElementById('profileWrapper');
      const profileToggle = document.getElementById('profileToggle');

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

      // Time auto-update
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

      // Notifications / profile dropdowns
      if (notifWrapper) {
        const bell = notifWrapper.querySelector(".fa-bell");
        bell?.addEventListener("click", (e) => {
          e.stopPropagation();
          notifWrapper.classList.toggle("active");
          profileWrapper?.classList.remove("active");
        });
      }

      if (profileWrapper && profileToggle) {
        profileToggle.addEventListener("click", (e) => {
          e.stopPropagation();
          profileWrapper.classList.toggle("active");
          notifWrapper?.classList.remove("active");
        });
      }

      // Close dropdowns when clicking outside
      document.addEventListener("click", (e) => {
        if (sidebar && !sidebar.contains(e.target) && menuToggle && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
        }
        if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
        if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
        document.querySelectorAll('.custom-select.open').forEach(o => o.classList.remove('open'));
      });

      // Committee Filter Dropdown functionality
      const committeeSelect = document.querySelector("#committee");
      let currentCategoryFilter = 'all';
      
      if (committeeSelect) {
        const selected = committeeSelect.querySelector(".selected");
        const options = committeeSelect.querySelector(".options");
        const items = options.querySelectorAll("li");

        selected.addEventListener("click", (e) => {
          e.stopPropagation();
          committeeSelect.classList.toggle("open");
        });

        items.forEach(item => {
          item.addEventListener("click", () => {
            committeeSelect.querySelector(".selected-text").textContent = item.textContent;
            committeeSelect.classList.remove("open");
            
            // Update category filter
            currentCategoryFilter = item.getAttribute('data-value');
            
            // Apply filters
            applyCommitteeFilter();
          });
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
          if (!committeeSelect.contains(e.target)) {
            committeeSelect.classList.remove("open");
          }
        });
      }

      // Apply committee filter
      function applyCommitteeFilter() {
        const eventRows = document.querySelectorAll('.event-row');
        let hasVisibleEvents = false;

        eventRows.forEach(row => {
          const eventCategory = row.getAttribute('data-category');
          
          // Check if event matches committee filter
          const categoryMatch = currentCategoryFilter === 'all' || eventCategory === currentCategoryFilter;
          
          if (categoryMatch) {
            row.style.display = 'table-row';
            hasVisibleEvents = true;
          } else {
            row.style.display = 'none';
          }
        });

        // Show no events message if no events match filters
        const noEventsRow = document.querySelector('.participation-table tbody tr:first-child');
        if (!hasVisibleEvents && eventRows.length > 0) {
          if (!document.querySelector('.no-events-message')) {
            const noEventsHTML = `
              <tr class="no-events-message">
                <td colspan="4" style="text-align: center; padding: 20px;">
                  <i class="fas fa-filter" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
                  <p>No events match the selected committee filter.</p>
                  <button class="btn-reset-filter" onclick="resetCommitteeFilter()" style="margin-top: 10px; padding: 8px 16px; background: #3C87C4; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Reset Filter
                  </button>
                </td>
              </tr>
            `;
            document.querySelector('.participation-table tbody').insertAdjacentHTML('beforeend', noEventsHTML);
          }
        } else {
          const noEventsMessage = document.querySelector('.no-events-message');
          if (noEventsMessage) {
            noEventsMessage.remove();
          }
        }
      }

      // Reset committee filter function
      window.resetCommitteeFilter = function() {
        const committeeSelect = document.querySelector("#committee");
        const selected = committeeSelect.querySelector(".selected");
        const selectedText = committeeSelect.querySelector(".selected-text");
        
        selected.setAttribute('data-value', 'all');
        selectedText.textContent = 'All Committees';
        
        currentCategoryFilter = 'all';
        applyCommitteeFilter();
        
        const noEventsMessage = document.querySelector('.no-events-message');
        if (noEventsMessage) {
          noEventsMessage.remove();
        }
      };

      // Logout confirmation
      window.confirmLogout = function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      };
    });
  </script>
</body>
</html>