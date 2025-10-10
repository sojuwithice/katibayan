@php
    // No need for complex logic here anymore since it's handled in the controller
@endphp

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
              @if($unevaluatedEvents->count() > 0)
                @foreach($unevaluatedEvents as $event)
                  <li class="evaluation-notification" data-event-id="{{ $event->id }}">
                    <div class="notif-icon" style="background-color: #4CAF50;">
                      <i class="fas fa-star" style="color: white;"></i>
                    </div>
                    <div class="notif-content">
                      <strong>Program Evaluation Required</strong>
                      <p>Please evaluate "{{ $event->title }}"</p>
                      <small>Attended on {{ $event->attendances->first()->attended_at->format('M j, Y') }}</small>
                    </div>
                    <span class="notif-dot unread"></span>
                  </li>
                @endforeach
              @else
                <li class="no-notifications">
                  <div class="notif-content">
                    <p>No new notifications</p>
                  </div>
                </li>
              @endif
            </ul>
            @if($unevaluatedEvents->count() > 0)
              <div class="notif-footer">
                <a href="{{ route('evaluation') }}" class="view-all-evaluations">View All Evaluations</a>
              </div>
            @endif
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

        <!-- Announcements -->
        <div class="announcements-section">
          <h3 class="announcements-title">Announcements</h3>
          <div class="announcements">
            <div class="card">
              <div class="card-content">
                <div class="icon"><i class="fas fa-info"></i></div>
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
  document.addEventListener("DOMContentLoaded", () => {
    // === Lucide icons + sidebar toggle ===
    lucide.createIcons();
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle && sidebar) {
      menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');
        if (!sidebar.classList.contains('open')) {
          profileItem?.classList.remove('open');
        }
      });
    }

    // === Profile submenu ===
    const profileItem = document.querySelector('.profile-item');
    const profileLink = document.querySelector('.profile-link');

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

    // === Calendar Functionality ===
    const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
    const daysContainer = document.querySelector(".calendar .days");
    const header = document.querySelector(".calendar header h3");
    let today = new Date();
    let currentView = new Date();

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

    // === Notifications dropdown ===
    const notifWrapper = document.querySelector(".notification-wrapper");
    
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

      // Handle evaluation notification clicks
      document.querySelectorAll('.evaluation-notification').forEach(notification => {
        notification.addEventListener('click', function() {
          const eventId = this.getAttribute('data-event-id');
          openEvaluationModal(eventId);
        });
      });
    }

    // === Profile dropdown (topbar) ===
    const profileWrapper = document.querySelector(".profile-wrapper");
    const profileToggle = document.getElementById("profileToggle");
    const profileDropdown = document.querySelector(".profile-dropdown");

    if (profileWrapper && profileToggle && profileDropdown) {
      profileToggle.addEventListener("click", (e) => {
        e.stopPropagation();
        profileWrapper.classList.toggle("active");
        notifWrapper?.classList.remove("active");
      });

      profileDropdown.addEventListener("click", (e) => e.stopPropagation());
    }

    // === Close dropdowns when clicking outside ===
    document.addEventListener("click", (e) => {
      if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
        sidebar.classList.remove('open');
        profileItem?.classList.remove('open');
      }
      if (profileWrapper && !profileWrapper.contains(e.target)) {
        profileWrapper.classList.remove('active');
      }
      if (notifWrapper && !notifWrapper.contains(e.target)) {
        notifWrapper.classList.remove('active');
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

      function updateSlide() {
        const containerWidth = welcomeSection.getBoundingClientRect().width;
        slideTrack.style.transform = `translateX(-${currentIndex * containerWidth}px)`;
        dots.forEach(dot => dot.classList.remove("active"));
        dots[currentIndex].classList.add("active");
      }

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

      updateSlide();
      startAuto();

      welcomeSection.addEventListener("mouseenter", stopAuto);
      welcomeSection.addEventListener("mouseleave", startAuto);
      window.addEventListener("resize", updateSlide);
    }

    // === Evaluation Modal Functionality ===
    const evaluationModal = document.getElementById('evaluationModal');
    const closeModal = evaluationModal?.querySelector('.close');
    const closeBtn = evaluationModal?.querySelector('.close-btn');
    const submitBtn = evaluationModal?.querySelector('.submit-evaluation-btn');
    const starRating = evaluationModal?.querySelectorAll('.star');

    // Star rating functionality
    if (starRating) {
      starRating.forEach(star => {
        star.addEventListener('click', function() {
          const rating = this.getAttribute('data-rating');
          document.getElementById('rating').value = rating;
          
          // Update star display
          starRating.forEach((s, index) => {
            if (index < rating) {
              s.classList.add('active');
            } else {
              s.classList.remove('active');
            }
          });
        });
      });
    }

    function openEvaluationModal(eventId) {
      // Fetch event details and populate modal
      fetch(`/events/${eventId}`)
        .then(response => response.json())
        .then(event => {
          document.getElementById('modalEventName').textContent = event.title;
          document.getElementById('evaluationEventId').value = event.id;
          evaluationModal.style.display = 'block';
          notifWrapper.classList.remove('active');
        })
        .catch(error => {
          console.error('Error fetching event details:', error);
          alert('Error loading event details');
        });
    }

    // Close modal functions
    if (closeModal) {
      closeModal.addEventListener('click', () => {
        evaluationModal.style.display = 'none';
      });
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        evaluationModal.style.display = 'none';
      });
    }

    if (evaluationModal) {
      evaluationModal.addEventListener('click', (e) => {
        if (e.target === evaluationModal) {
          evaluationModal.style.display = 'none';
        }
      });
    }

    // Submit evaluation
    if (submitBtn) {
      submitBtn.addEventListener('click', function() {
        const form = document.getElementById('evaluationForm');
        const formData = new FormData(form);

        if (!formData.get('rating')) {
          alert('Please provide a rating');
          return;
        }

        fetch('/evaluations', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Evaluation submitted successfully!');
            evaluationModal.style.display = 'none';
            // Remove the notification
            const notification = document.querySelector(`.evaluation-notification[data-event-id="${formData.get('event_id')}"]`);
            if (notification) {
              notification.remove();
            }
            // Refresh page to update progress
            location.reload();
          } else {
            alert('Error submitting evaluation: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error submitting evaluation');
        });
      });
    }
  });
  </script>
</body>
</html>