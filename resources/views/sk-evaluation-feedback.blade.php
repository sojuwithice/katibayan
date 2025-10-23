<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/sk-eval.css') }}">
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

    <a href="{{ route('sk-evaluation-feedback') }}" class="active">
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
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ $user->given_name }} {{ $user->middle_name ?? '' }} {{ $user->last_name }} {{ $user->suffix ?? '' }}</h4>
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
      <!-- Evaluation Section -->
      <section class="evaluation-section">
        <div class="evaluation-header">
          <h2>Evaluation and Feedback</h2>
          <p>The evaluation and feedback of the youth in accomplished events and programs will help you improve and generate new ideas for future activities.</p>
        </div>

        <div class="accomplished-events">
          <h3>Accomplished Events and Program</h3>
          <p class="subtitle">Choose an accomplished event or program to see the results.</p>

          <div class="accomplishment-list">
            @if($eventsWithEvaluations->count() > 0)
              @foreach($eventsWithEvaluations as $event)
                @php
                  // Calculate average rating for this event
                  $averageRating = $event->evaluations->avg(function($eval) {
                    $ratings = json_decode($eval->ratings, true);
                    return $ratings ? array_sum($ratings) / count($ratings) : 0;
                  });
                  
                  // Get latest evaluation for preview
                  $latestEvaluation = $event->evaluations->first();
                  $latestComment = $latestEvaluation ? $latestEvaluation->comments : null;
                @endphp

                <div class="accomplishment-card">
                  <!-- Date -->
                  <div class="accomplishment-date">
                    <span class="day">{{ $event->event_date->format('M') }}</span>
                    <span class="num">{{ $event->event_date->format('d') }}</span>
                  </div>

                  <!-- Details -->
                  <div class="accomplishment-details">
                    <h3>{{ $event->title }}</h3>
                    <p><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                    <p><i class="fas fa-users"></i> {{ $event->evaluations_count }} Evaluations</p>
                    
                    <!-- Rating -->
                    <div class="event-rating">
                      <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= round($averageRating))
                            <i class="fas fa-star"></i>
                          @else
                            <i class="far fa-star"></i>
                          @endif
                        @endfor
                        <span>({{ number_format($averageRating, 1) }})</span>
                      </div>
                    </div>

                    <!-- Latest Comment Preview -->
                    @if($latestComment)
                      <div class="latest-comment">
                        <strong>Latest Feedback:</strong>
                        <p>{{ Str::limit($latestComment, 100) }}</p>
                      </div>
                    @endif

                    <!-- Date & Time -->
                    <div class="accomplishment-datetime">
                      <span class="datetime-label">DATE AND TIME</span>
                      <span class="datetime-value">{{ $event->event_date->format('F d, Y') }} | {{ $event->event_time ?? 'Time not specified' }}</span>
                    </div>
                  </div>

                  <!-- Action -->
                  <div class="accomplishment-action">
                    <a href="{{ route('sk-eval-review', ['event_id' => $event->id]) }}" class="view-btn">
                      View Evaluation <i class="fa-solid fa-arrow-up-right"></i>
                    </a>
                  </div>
                </div>
              @endforeach
            @else
              <div class="no-evaluations">
                <i class="fas fa-clipboard-list"></i>
                <h4>No Evaluations Yet</h4>
                <p>Evaluations will appear here once users submit feedback for events in your barangay.</p>
              </div>
            @endif
          </div>
        </div>
      </section>
    </main>
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

      // === Logout Confirmation ===
      function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      }
    });
  </script>
</body>
</html>