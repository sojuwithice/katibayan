@php
    // No need for complex logic here anymore since it's handled in the controller
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('dashboard.index') }}" class="active">
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>
      <div class="profile-item nav-item">
        <a href="#" class="profile-link">
          <i data-lucide="circle-user"></i>
          <span class="label">Profile</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('profilepage') }}">My Profile</a>
          <a href="{{ route('certificatepage') }}">Certificates</a>
        </div>
      </div>

      <a href="{{ route('eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>
      
      <a href="{{ route('evaluation') }}">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
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
    @if($notificationCount > 0)
        <span class="notif-count">{{ $notificationCount }}</span>
    @endif
    <div class="notif-dropdown">
        <div class="notif-header">
            <strong>Notification</strong>
            @if($notificationCount > 0)
                <span>{{ $notificationCount }}</span>
            @endif
        </div>
        
        <ul class="notif-list">

            @foreach ($generalNotifications as $notif)
                @php
                    $link = '#'; // Default
                    if ($notif->type == 'certificate_schedule') {
                        $link = route('certificatepage'); 
                    }
                @endphp
                
                <li>
                    <a href="{{ $link }}" class="notif-link {{ $notif->is_read == 0 ? 'unread' : '' }}" data-id="{{ $notif->id }}">
                        
                        <div class="notif-dot-container">
                            @if ($notif->is_read == 0)
                                <span class="notif-dot"></span>
                            @else
                                <span class="notif-dot-placeholder"></span>
                            @endif
                        </div>

                        <div class="notif-main-content">
                            <div class="notif-header-line">
                                <strong>{{ $notif->title }}</strong>
                                <span class="notif-timestamp">
                                    {{ $notif->created_at->format('m/d/Y g:i A') }}
                                </span>
                            </div>
                            <p class="notif-message">{{ $notif->message }}</p>
                        </div>
                    </a>
                </li>
            @endforeach

            @foreach($unevaluatedEvents as $event)
                <li>
                    <a href="{{ route('evaluation.show', $event->id) }}" class="notif-link unread" data-event-id="{{ $event->id }}">
                        
                        <div class="notif-dot-container">
                             <span class="notif-dot"></span> </div>
                        
                        <div class="notif-main-content">
                            <div class="notif-header-line">
                                <strong>Program Evaluation Required</strong>
                                @if($event->attendances->first())
                                    <span class="notif-timestamp">
                                        {{ $event->attendances->first()->created_at->format('m/d/Y g:i A') }}
                                    </span>
                                @endif
                            </div>
                            <p class="notif-message">Please evaluate "{{ $event->title }}"</p>
                        </div>
                    </a>
                </li>
            @endforeach

            @if($generalNotifications->isEmpty() && $unevaluatedEvents->isEmpty())
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

    <div id="feedbackModal" class="modal-overlay">
      <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>

        <h2>Send us feedback</h2>
        <p>Help us improve by sharing your thoughts, suggestions, and experiences with our service.</p>

        <div class="feedback-options">
          <div class="option-card">
            <i class="fas fa-star"></i>
            <p><strong>Star Rating</strong><br>Rate your experience with 1–5 stars</p>
          </div>
          <div class="option-card">
            <i class="fas fa-comment"></i> 
            <p><strong>Comment Section</strong><br>Share your thoughts</p>
          </div>
          <div class="option-card">
            <i class="fas fa-bolt"></i>
            <p><strong>Quick Submission</strong><br>Simple and intuitive feedback process</p>
          </div>
        </div>

        <h3>Enjoying it? Rate us!</h3>
        <div class="star-rating" id="starRating">
          <i class="far fa-star" data-value="1"></i>
          <i class="far fa-star" data-value="2"></i>
          <i class="far fa-star" data-value="3"></i>
          <i class="far fa-star" data-value="4"></i>
          <i class="far fa-star" data-value="5"></i>
        </div>

        <form id="feedbackForm" action="{{ route('feedback.submit') }}" method="POST">
          @csrf
          
          <label for="type">Feedback Type</label>
          
          <div class="custom-select-wrapper" id="customSelect">
            <div class="custom-select-trigger">
              <span id="selectedFeedbackType">Select feedback type</span>
              <div class="custom-arrow"></div>
            </div>
            
            <div class="custom-options-list">
              <div class="custom-option" data-value="suggestion">
                <span class="dot suggestion"></span> Suggestion
              </div>
              <div class="custom-option" data-value="bug">
                <span class="dot bug"></span> Bug or Issue
              </div>
              <div class="custom-option" data-value="appreciation">
                <span class="dot appreciation"></span> Appreciation
              </div>
              <div class="custom-option" data-value="others">
                <span class="dot others"></span> Others
              </div>
            </div>
            
            <select id="type" name="type" required style="display: none;">
              <option value="" disabled selected>Select feedback type</option>
              <option value="suggestion">Suggestion</option>
              <option value="bug">Bug or Issue</option>
              <option value="appreciation">Appreciation</option>
              <option value="others">Others</option>
            </select>
          </div>
          <label for="message">Your message</label>
          <textarea id="message" name="message" rows="5" required></textarea>

          <input type="hidden" name="rating" id="ratingInput">
          
          <div class="form-actions">
            <button type="submit" class="submit-btn">Submit</button>
          </div>
        </form>
      </div>
    </div>

    <div id="successModal" class="modal-overlay simple-alert-modal">
      <div class="modal-content">
        <div class="success-icon">
          <i class="fas fa-check"></i>
        </div>
        <h2>Submitted</h2>
        <p>Thank you for your feedback! Your thoughts help us improve.</p>
        <button id="closeSuccessModal" class="ok-btn">Ok</button>
      </div>
    </div>

    <!-- === Pages Container === -->
    <div id="dashboard-page" class="page active">
      <div class="row">

        <!-- Welcome -->
        <section class="welcome">
          <div class="slides">
            <!-- Slide 1: Welcome -->
            <div class="slide">
              <h2>Welcome, {{ $user->given_name }}!</h2>
              <h3>Have a nice day!</h3><br>
              <p>
                <span>KatiBayan</span> provides a platform for the youth to stay updated on SK events 
                and programs while fostering active participation in community development.
              </p>
            </div>

            <!-- Slide 2 -->
            <div class="slide event">
              <div class="date">
                <span class="month">AUG</span>
                <span class="day">22</span>
              </div>
              <div class="event-info">
                <p><strong>UPCOMING!</strong> Anti-Rabies Vaccination</p>
                <small>Please, Don't Forget to Participate</small>
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

        <!-- Calendar -->
        <div class="calendar">
          <header>
            <button class="prev"><i class="fas fa-chevron-left"></i></button>
            <h3></h3>
            <button class="next"><i class="fas fa-chevron-right"></i></button>
            <a href="{{ route('eventpage') }}" title="View full month">
              <i class="fas fa-calendar calendar-toggle"></i>
            </a>
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
              <div class="progress-footer" style="--progress: {{ $attendancePercentage }}%">
                <div class="bar">
                  <span style="width: var(--progress)"></span>
                </div>
                <small>{{ $attendedCount }}/{{ $totalEvents }}</small>
              </div>
            </div>

            <!-- Evaluation -->
            <div class="card">
              <div class="card-content">
                <div class="text">
                  <h4>Evaluation</h4>
                  <p>
                    @if($eventsToEvaluate > 0)
                      You have {{ $eventsToEvaluate }} program{{ $eventsToEvaluate > 1 ? 's' : '' }} to evaluate.
                    @else
                      All evaluations completed!
                    @endif
                  </p>
                </div>
                <div class="icon">
                  <i data-lucide="thumbs-up"></i>
                </div>
              </div>
              <div class="progress-footer" style="--progress: {{ $attendedEvents > 0 ? ($evaluatedEvents / $attendedEvents * 100) : 0 }}%">
                <div class="bar">
                  <span style="width: var(--progress)"></span>
                </div>
                <small>{{ $evaluatedEvents }}/{{ $attendedEvents }}</small>
              </div>
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
              <a href="{{ route('polls.page') }}">Join the poll →</a>
            </div>
          </div>
        </div>

        <!-- Events -->
        <div class="events-section">
          <h3 class="events-title">Upcoming Events</h3>
          <div class="events">
            <div class="events-top">
              <button class="events-menu">⋯</button>
            </div>
            <ul>
              @if($displayItems->count() > 0)
                @foreach($displayItems as $item)
                  <li class="{{ $item['is_holiday'] ? 'holiday-item' : '' }}">
                    <span class="date {{ $item['is_holiday'] ? 'holiday' : '' }}">
                      <strong>{{ $item['month'] }}</strong>
                      <span>{{ $item['day'] }}</span>
                    </span>
                    <div class="event-info">
                      <p>{{ $item['title'] }}</p>
                      <small>{{ $item['status'] }}</small>
                    </div>
                  </li>
                @endforeach
              @else
                <li>
                  <div class="event-info">
                    <p>No upcoming events or holidays</p>
                    <small>Check back later</small>
                  </div>
                </li>
              @endif
            </ul>
          </div>
        </div>

        <div class="announcements-section">
  <h3 class="announcements-title">Announcements</h3>
  <div class="announcements" id="announcementsList"> {{-- Added ID --}}

    @forelse ($announcements as $announcement)
      {{-- Check if this is a certificate schedule announcement --}}
      @php
        $isCertificateSchedule = optional($announcement)->type == 'certificate_schedule';
        preg_match("/'([^']+)'/", optional($announcement)->message, $matches);
        $eventTitleFromMessage = $matches[1] ?? null;
        $announcementId = optional($announcement)->id;
        $relatedEventId = null; 
      @endphp

      {{-- Add data attributes and a class to the card --}}
      <div class="card {{ $isCertificateSchedule ? 'certificate-schedule-announcement' : '' }}"
           @if($isCertificateSchedule && $announcementId)
             data-announcement-id="{{ $announcementId }}"
           @endif
           style="{{ $isCertificateSchedule ? 'cursor: pointer;' : '' }}"
           >
        <div class="card-content">
          <div class="icon">
            @if($isCertificateSchedule)
               <i class="fas fa-calendar-check" style="color: #FFCA3A;"></i>
            @else
               <i class="fas fa-info-circle"></i>
            @endif
          </div>
          <div class="text">
            <strong>{{ optional($announcement)->title }}</strong>
            <p>{{ optional($announcement)->message }}</p>
            <small style="color: #888; margin-top: 5px; display: block;">
                 Posted: {{ optional($announcement)->created_at?->diffForHumans() ?? 'Just now' }}
            </small>
          </div>
        </div>
      </div>
    
    @empty

      {{-- === ITO YUNG FIX (Naka-Gitna) === --}}
      <div style="
          display: flex;
          flex-direction: column;
          justify-content: center; 
          align-items: center;     
          height: 100%;            
          min-height: 200px;       
          padding: 20px;
          color: #999;
      ">
        
        <i class="fas fa-bell-slash" style="
            font-size: 2.5rem; 
            color: #ccc; 
            margin-bottom: 15px;
        "></i>
        
        <strong style="
            font-size: 1.1rem; 
            color: #888; 
            display: block; 
            margin-bottom: 5px;
            font-weight: 600;
        ">
          No Announcements Yet
        </strong>
        
        <p style="font-size: 0.9rem; margin: 0; color: #999;">
          Check back later for new updates.
        </p>
      </div>
      {{-- === END NG FIX === --}}

    @endforelse

  </div>
</div>

        <!-- Suggestion Box -->
        <div class="suggestion-box">
          <h2>Suggestion Box</h2>
          <p class="subtitle">You matter. Your voice counts.</p>
          <a href="{{ route('suggestionbox') }}" class="suggestion-btn">
            Share with us <i class="fas fa-paper-plane"></i>
          </a>
          <p class="note">Everyone is encouraged to share their ideas and suggestions — we're glad to hear from you!</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Evaluation Modal -->
  <div id="evaluationModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="modal-header">
        <h2>Evaluate Event</h2>
        <span id="modalEventName" class="event-name"></span>
      </div>
      <div class="modal-body">
        <form id="evaluationForm">
          @csrf
          <input type="hidden" id="evaluationEventId" name="event_id">
          
          <div class="rating-section">
            <label>Overall Rating:</label>
            <div class="star-rating">
              <span class="star" data-rating="1">★</span>
              <span class="star" data-rating="2">★</span>
              <span class="star" data-rating="3">★</span>
              <span class="star" data-rating="4">★</span>
              <span class="star" data-rating="5">★</span>
            </div>
            <input type="hidden" id="rating" name="rating" required>
          </div>

          <div class="form-group">
            <label for="comments">Comments/Suggestions:</label>
            <textarea id="comments" name="comments" rows="4" placeholder="Share your thoughts about the event..."></textarea>
          </div>

          <div class="form-group">
            <label>Would you recommend this event to others?</label>
            <div class="recommendation">
              <label>
                <input type="radio" name="recommend" value="yes" required> Yes
              </label>
              <label>
                <input type="radio" name="recommend" value="no" required> No
              </label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="submit-evaluation-btn">Submit Evaluation</button>
        <button class="close-btn">Close</button>
      </div>
    </div>
  </div>

  <script>
    // CSRF Token for AJAX requests
    window.csrfToken = '{{ csrf_token() }}';
  
  /**
   * Updates the time in the topbar.
   */
  function updateTime() {
    const timeEl = document.querySelector(".time");
    if (!timeEl) return;
    
    const now = new Date();
    const shortWeekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
    const shortMonths = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    const weekday = shortWeekdays[now.getDay()];
    const month = shortMonths[now.getMonth()];
    const day = now.getDate();
    let hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, "0");
    const ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;

      timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
    }

    /**
     * Initializes sidebar toggle and profile submenu.
     */
    function initSidebar() {
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');
      const profileItem = document.querySelector('.profile-item');
      const profileLink = profileItem?.querySelector('.profile-link');

      if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('open');
          if (!sidebar.classList.contains('open')) {
            profileItem?.classList.remove('open');
          }
        });
      }

      function closeAllSubmenus() {
        profileItem?.classList.remove('open');
      }

      if (profileItem && profileLink) {
        profileLink.addEventListener('click', (e) => {
          e.preventDefault();
          if (sidebar.classList.contains('open')) {
            const isOpen = profileItem.classList.contains('open');
            closeAllSubmenus();
            if (!isOpen) profileItem.classList.add('open');
          }
        });
      }
    }

  /**
   * Initializes topbar dropdowns (notifications, profile)
   * and handles global clicks to close them.
   */
  function initTopbar(openEvaluationModal) { // Tumatanggap ng function
    // --- Time ---
    updateTime();
    setInterval(updateTime, 60000);

      // --- Elements ---
      const notifWrapper = document.querySelector(".notification-wrapper");
      const profileWrapper = document.querySelector(".profile-wrapper");
      const profileToggle = document.getElementById("profileToggle");
      const profileDropdown = profileWrapper?.querySelector(".profile-dropdown");

    // --- Notifications Dropdown ---
    if (notifWrapper) {
      const bell = notifWrapper.querySelector(".fa-bell");
      bell?.addEventListener("click", (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle("active");
        profileWrapper?.classList.remove("active");
      });
      
      const dropdown = notifWrapper.querySelector(".notif-dropdown");
      dropdown?.addEventListener("click", (e) => e.stopPropagation());

      // Handle evaluation notification clicks
      // (BAGO) Pinalitan ko 'to para hanapin 'yung .notif-link na may data-event-id
      notifWrapper.querySelectorAll('.notif-link[data-event-id]').forEach(notification => {
        notification.addEventListener('click', function(e) {
          e.preventDefault(); // Pigilan 'yung default link behavior
          const eventId = this.getAttribute('data-event-id');
          if (openEvaluationModal) {
            openEvaluationModal(eventId); // Gamitin 'yung function na pinasa
          }
          notifWrapper.classList.remove('active'); 
        });
      });
    }

      // --- Profile Dropdown ---
      if (profileWrapper && profileToggle && profileDropdown) {
        profileToggle.addEventListener("click", (e) => {
          e.stopPropagation();
          profileWrapper.classList.toggle("active");
          notifWrapper?.classList.remove("active");
        });

        profileDropdown.addEventListener("click", (e) => e.stopPropagation());
      }

      // --- Global Click Listener for Topbar/Sidebar ---
      document.addEventListener("click", (e) => {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (sidebar && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
          document.querySelector('.profile-item')?.classList.remove('open');
        }
        if (profileWrapper && !profileWrapper.contains(e.target)) {
          profileWrapper.classList.remove('active');
        }
        if (notifWrapper && !notifWrapper.contains(e.target)) {
          notifWrapper.classList.remove('active');
        }
      });


    }

    /**
     * Initializes the 7-day week calendar.
     */
    function initCalendar() {
      const calendar = document.querySelector(".calendar");
      if (!calendar) return; 

      const daysContainer = calendar.querySelector(".days");
      const header = calendar.querySelector("header h3");
      const prevBtn = calendar.querySelector(".prev");
      const nextBtn = calendar.querySelector(".next");
      
      if (!daysContainer || !header || !prevBtn || !nextBtn) return;

    const weekdays = ["MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN"];
    const holidays = [
      // (Assuming 2024 for example, update this as needed)
      "2024-01-01", "2024-04-09", "2024-04-10", "2024-05-01",
      "2024-06-12", "2024-08-26", "2024-11-30", "2024-12-25", 
      "2024-12-30", "2024-12-31"
    ];
    let today = new Date();
    let currentView = new Date();

      function renderCalendar(baseDate) {
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

          const dateStr = `${thisDay.getFullYear()}-${(thisDay.getMonth() + 1).toString().padStart(2, '0')}-${thisDay.getDate().toString().padStart(2, '0')}`;

          if (holidays.includes(dateStr)) {
            dateEl.classList.add('holiday');
          }

          if (thisDay.getDate() === today.getDate() && 
              thisDay.getMonth() === today.getMonth() && 
              thisDay.getFullYear() === today.getFullYear()) {
            dayEl.classList.add("active");
          }

          dayEl.appendChild(weekdayEl);
          dayEl.appendChild(dateEl);
          daysContainer.appendChild(dayEl);
        }
      }

      renderCalendar(currentView);

      prevBtn.addEventListener("click", () => {
        currentView.setDate(currentView.getDate() - 7);
        renderCalendar(currentView);
      });
      
      nextBtn.addEventListener("click", () => {
        currentView.setDate(currentView.getDate() + 7);
        renderCalendar(currentView);
      });
    }

    /**
     * Initializes the Welcome Slider / Carousel.
     */
    function initWelcomeSlider() {
      const welcomeSection = document.querySelector(".welcome");
      if (!welcomeSection) return; 

      const slideTrack = welcomeSection.querySelector(".slides");
      const slides = welcomeSection.querySelectorAll(".slide");
      const dotsContainer = welcomeSection.querySelector(".dots");

      if (!slideTrack || slides.length === 0 || !dotsContainer) return;

      let currentIndex = 0;
      let autoPlay;
      const dots = [];

      slides.forEach((_, i) => {
        const dot = document.createElement("button");
        if (i === 0) dot.classList.add("active");
        dot.addEventListener("click", () => {
          currentIndex = i;
          updateSlide();
          restartAuto();
        });
        dotsContainer.appendChild(dot);
        dots.push(dot);
      });

    function updateSlide() {
      // (FIX) Recalculate width on update, important for resize
      const containerWidth = welcomeSection.getBoundingClientRect().width;
      if (containerWidth === 0) return; // Avoid error if hidden
      slideTrack.style.transform = `translateX(-${currentIndex * containerWidth}px)`;
      
      dots.forEach(dot => dot.classList.remove("active"));
      if(dots[currentIndex]) dots[currentIndex].classList.add("active");
    }

      function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlide();
      }
      
      function startAuto() {
        stopAuto(); 
        autoPlay = setInterval(nextSlide, 4000);
      }
      
      function stopAuto() {
        clearInterval(autoPlay);
      }
      
      function restartAuto() {
        stopAuto();
        startAuto();
      }

      updateSlide();
      startAuto();

      welcomeSection.addEventListener("mouseenter", stopAuto);
      welcomeSection.addEventListener("mouseleave", startAuto);
      window.addEventListener("resize", updateSlide);
    }

  /**
   * (FIXED STRUCTURE)
   * Initializes the Event Evaluation Modal.
   * Returns the function to open the modal.
   */
  function initEvaluationModal() {
    const evalModal = document.getElementById('evaluationModal');
    
    // (FIX) Define the opener function here
    function openEvaluationModal(eventId) {
      if (!evalModal) {
        console.error("Evaluation modal not found in DOM.");
        return;
      }
      
      // Fetch event details
      fetch(`/events/${eventId}`)
        .then(response => {
          if (!response.ok) throw new Error('Event not found or server error');
          return response.json();
        })
        .then(event => {
          document.getElementById('modalEventName').textContent = event.title;
          document.getElementById('evaluationEventId').value = event.id;
          evalModal.style.display = 'block';
        })
        .catch(error => {
          console.error('Error fetching event details:', error);
          alert('Error loading event details');
        });
    }
    
    // If modal doesn't exist, return a dummy function to avoid errors
    if (!evalModal) {
      return function() { 
        console.warn("Tried to open evaluation modal, but it was not found."); 
      };
    }

    // --- Modal exists, proceed with setup ---
    const evalCloseIcon = evalModal.querySelector('.close');
    const evalCloseBtn = evalModal.querySelector('.close-btn');
    const evalSubmitBtn = evalModal.querySelector('.submit-evaluation-btn');
    const evalStars = evalModal.querySelectorAll('.star');
    const evalForm = document.getElementById('evaluationForm');

    // Star rating logic
    evalStars.forEach(star => {
      star.addEventListener('click', function() {
        const rating = this.getAttribute('data-rating');
        document.getElementById('rating').value = rating;
        
        evalStars.forEach((s, index) => {
          s.classList.toggle('active', index < rating);
        });
      });
    });

    // Close modal function
    const closeModal = () => {
      evalModal.style.display = 'none';
      // Reset form
      evalForm.reset();
      evalStars.forEach(s => s.classList.remove('active'));
    };

    evalCloseIcon?.addEventListener('click', closeModal);
    evalCloseBtn?.addEventListener('click', closeModal);
    // Close on outside click
    evalModal.addEventListener('click', (e) => {
      if (e.target === evalModal) {
        closeModal();
      }
    });

    // Submit evaluation logic
    evalSubmitBtn?.addEventListener('click', function() {
      const formData = new FormData(evalForm);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!formData.get('rating')) {
          alert('Please provide a rating');
          return;
        }

      fetch('/evaluations', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: formData // Send as FormData
      })
      .then(response => {
        if (!response.ok) {
           return response.json().then(err => { throw err; });
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          alert('Evaluation submitted successfully!');
          closeModal();
          location.reload(); // Reload to update progress/notifications
        } else {
          // Handle validation errors or other specific errors
          let errorMsg = data.message || 'Submission failed.';
          if (data.errors) {
            errorMsg += '\n' + Object.values(data.errors).join('\n');
          }
          alert(errorMsg);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while submitting.');
      });
    });

    // (FIX) Return the opener function
    return openEvaluationModal;
  }

  /**
   * Initializes the "Send Feedback" Modal.
   */
  function initFeedbackModal() {
    const feedbackTriggerBtn = document.querySelector('.profile-menu li:nth-child(4) a'); // "Send Feedback" item
    const feedbackModal = document.getElementById('feedbackModal');
    if (!feedbackTriggerBtn || !feedbackModal) {
      console.warn("Feedback modal or trigger not found.");
      return;
    }
    
    const feedbackCloseBtn = document.getElementById('closeModal');
    const feedbackStars = document.querySelectorAll('#starRating i');
    const feedbackRatingInput = document.getElementById('ratingInput');
    
    const feedbackForm = document.getElementById('feedbackForm');
    const submitBtn = feedbackForm?.querySelector('.submit-btn');
    
    const successModal = document.getElementById('successModal');
    const closeSuccessBtn = document.getElementById('closeSuccessModal');

    // Custom Select Box Logic
    const customSelect = document.getElementById('customSelect');
    if (customSelect) {
      const trigger = customSelect.querySelector('.custom-select-trigger');
      const selectedText = document.getElementById('selectedFeedbackType');
      const optionsList = customSelect.querySelector('.custom-options-list');
      const options = customSelect.querySelectorAll('.custom-option');
      const realSelect = document.getElementById('type');

      // Toggle dropdown
      trigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        customSelect.classList.toggle('open');
      });
      // Toggle dropdown
      trigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        customSelect.classList.toggle('open');
      });

      // Handle option click
      options?.forEach(option => {
        option.addEventListener('click', () => {
          const value = option.getAttribute('data-value');
          const text = option.textContent.trim();
          
          if(selectedText) selectedText.textContent = text;
          if(realSelect) realSelect.value = value; 
          trigger?.classList.add('selected'); 
          
          customSelect.classList.remove('open');
        });
      });
      
      // Close custom select on outside click
      document.addEventListener('click', () => {
        customSelect.classList.remove('open');
      });
    }

    // Open modal
    feedbackTriggerBtn.addEventListener('click', (e) => {
      e.preventDefault(); // Prevent link from navigating
      feedbackModal.style.display = 'flex';
    });

      // Close modal
      feedbackCloseBtn?.addEventListener('click', () => {
        feedbackModal.style.display = 'none';
      });

    // Close when clicking outside
    window.addEventListener('click', (e) => {
      if (e.target === feedbackModal) {
        feedbackModal.style.display = 'none';
      }
      if (e.target === successModal) {
        successModal.style.display = 'none';
      }
    });

      // Star rating system
      feedbackStars.forEach(star => {
        star.addEventListener('click', () => {
          const rating = star.getAttribute('data-value');
          if(feedbackRatingInput) feedbackRatingInput.value = rating;

          feedbackStars.forEach(s => {
            s.classList.remove('fas');
            s.classList.add('far');
          });
          for (let i = 0; i < rating; i++) {
            feedbackStars[i].classList.remove('far');
            feedbackStars[i].classList.add('fas');
          }
        });
      });

    // AJAX Form Submission
    if (feedbackForm) { 
      feedbackForm.addEventListener('submit', function(e) {
          e.preventDefault(); 
          
          const formData = new FormData(feedbackForm);
          const submitButtonText = submitBtn.textContent;
          
          if(submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
          }

          fetch(feedbackForm.action, {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': formData.get('_token'),
                  'Accept': 'application/json' 
              },
              body: formData
          })
          .then(response => response.json()) // Assume it always returns JSON
          .then(data => {
              if (data.success) { 
                  feedbackModal.style.display = 'none';
                  if(successModal) successModal.style.display = 'flex';
              } else {
                  // Handle validation errors or other errors
                  let errorMsg = data.message || 'Submission failed.';
                  if (data.errors) {
                    errorMsg += '\n' + Object.values(data.errors).join('\n');
                  }
                  throw new Error(errorMsg);
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert(error.message || 'An error occurred. Please try again.');
          })
          .finally(() => {
              // Reset form in finally block
              if(submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = submitButtonText;
              }
              
              feedbackForm.reset();
              feedbackStars.forEach(s => {
                s.classList.remove('fas');
                s.classList.add('far');
              });
              if(feedbackRatingInput) feedbackRatingInput.value = '';
              
              // Reset custom select
              const selectedText = document.getElementById('selectedFeedbackType');
              const trigger = customSelect?.querySelector('.custom-select-trigger');
              const realSelect = document.getElementById('type');
              if(selectedText) selectedText.textContent = 'Select feedback type';
              trigger?.classList.remove('selected');
              if(realSelect) realSelect.value = '';
          });
      });
    } 

    // Close success modal
    closeSuccessBtn?.addEventListener('click', () => {
        if(successModal) successModal.style.display = 'none';
    });
  }

  function initMarkAsRead() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (!csrfToken) {
    console.error('CSRF token not found.');
    return;
  }

  // Select all notifications with a data-id attribute
  document.querySelectorAll('.notif-link[data-id]').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const notifId = this.dataset.id;
      const destinationUrl = this.href;

      // Remove notification visually
      const notifItem = this.closest('li');
      notifItem?.remove();

      // Update notification count
      const countEl = document.querySelector('.notif-count');
      if (countEl) {
        let currentCount = parseInt(countEl.textContent) || 0;
        countEl.textContent = Math.max(0, currentCount - 1);
        if (parseInt(countEl.textContent) === 0) {
          countEl.remove();
          // Optional: remove red dot on bell icon
          const bellDot = document.querySelector('.notif-dot');
          if (bellDot) bellDot.remove();
        }
      }

      // If no notifications left, show "No new notifications"
      const notifList = document.querySelector('.notif-list');
      if (notifList && notifList.children.length === 0) {
        notifList.innerHTML = `<li class="no-notifications"><p>No new notifications</p></li>`;
      }

      // Send AJAX request to mark as read
      fetch(`/notifications/${notifId}/read`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: notifId })
      })
      .then(res => res.json())
      .then(data => {
        if (!data.success) console.error('Error marking notification as read:', data.message);
      })
      .catch(err => console.error('Fetch error:', err))
      .finally(() => {
        if (destinationUrl && destinationUrl !== '#') {
          window.location.href = destinationUrl;
        }
      });
    });
  });
}

  /**
   * Initializes Certificate Claim
   * (BINAGO) - Inalis na ang modal. 
   * Kapag cliniclick ang card, mag-mark as read at diretso redirect.
   */
  function initCertificateModal() {
    
    const announcementCards = document.querySelectorAll('.certificate-schedule-announcement');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const redirectUrl = '{{ route('certificatepage') }}'; 

    announcementCards.forEach(card => {
      card.addEventListener('click', (event) => {
        event.preventDefault(); // Para mapigilan ang default behavior

        // Kunin 'yung ID para sa "mark as read"
        const annId = card.dataset.announcementId; 

        if (!annId) {
            // Kung walang ID, i-redirect na lang
            window.location.href = redirectUrl;
            return;
        }

        // 1. Mark as read (POST request)
        fetch(`/notifications/mark-as-read/${annId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ id: annId }) 
        })
        .catch(error => {
          console.error('Error marking notification as read:', error);
          // Kahit mag-error sa pag-mark as read, i-redirect pa rin
        })
        .finally(() => {
          // 2. Redirect
          window.location.href = redirectUrl;
        });
      });
    });
  }

  /**
   * Handles logout confirmation.
   * This is called directly from the HTML's onclick attribute.
   */
  function confirmLogout(event) {
    event.preventDefault(); // Prevent the <a> tag's default action
    if (confirm('Are you sure you want to logout?')) {
      document.getElementById('logout-form').submit();
    }
  }


  // ==========================================================
  //  APP INITIALIZATION (MAIN)
  // ==========================================================
  document.addEventListener("DOMContentLoaded", () => {
    // Initialize Lucide icons first
    lucide.createIcons();
    
    // Initialize all components
    initSidebar();
    
    // (FIX) Kunin 'yung function na nireturn ng initEvaluationModal()
    const openEvalModalFn = initEvaluationModal(); 
    
    // Ipasa 'yung function sa initTopbar()
    initTopbar(openEvalModalFn); 
    
    initCalendar(); // <-- Ito 'yung para sa calendar
    initWelcomeSlider();
    initFeedbackModal();
    initMarkAsRead();
    initCertificateModal();
  });


</script>
</body>
</html>