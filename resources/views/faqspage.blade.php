<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    // Make CSRF token globally available
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  </script>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
  <title>KatiBayan - FAQs Page</title>
  <link rel="stylesheet" href="{{ asset('css/faqspage.css') }}">
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
      <a href="{{ route('dashboard.index') }}">
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
      
      <a href="{{ route('faqspage') }}" class="active">
        <i data-lucide="help-circle"></i>
        <span class="label">FAQs</span>
      </a>
    </nav>
  </aside>

  <!-- Main -->
  <div class="main">

    <!-- Topbar -->
    <header class="topbar">
      <button id="mobileMenuBtn" class="mobile-hamburger">
        <i data-lucide="menu"></i>
      </button>

      <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div class="logo-text">
          <span class="title">Katibayan</span>
          <span class="subtitle">Web Portal</span>
        </div>
      </div>

      <div class="topbar-right">
        <div class="time">MON 10:00 <span>AM</span></div>

        <!-- Theme Toggle Button -->
        <button class="theme-toggle" id="themeToggle">
          <i data-lucide="moon"></i>
        </button>
        
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
                  $link = '#';
                  $onclickAction = '';
                  
                  if ($notif->type == 'certificate_schedule') {
                    $link = route('certificatepage'); 
                  } 
                  elseif ($notif->type == 'sk_request_approved' || $notif->type == 'App\Notifications\SkRequestAccepted') { 
                    $link = '#'; 
                    $onclickAction = 'openSetRoleModal(); return false;';
                  }

                  $title = $notif->data['title'] ?? $notif->title ?? 'Notification';
                  $message = $notif->data['message'] ?? $notif->message ?? 'You have a new notification.';
                @endphp
                
                <li>
                  <a href="{{ $link }}" 
                     class="notif-link {{ $notif->is_read == 0 ? 'unread' : '' }}" 
                     data-id="{{ $notif->id }}"
                     @if($onclickAction) onclick="{{ $onclickAction }}" @endif>
                    
                    <div class="notif-dot-container">
                      @if ($notif->is_read == 0)
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

              @foreach($unevaluatedActivities as $activity)
                <li>
                  <a href="{{ route('evaluation.show', $activity['id']) }}" class="notif-link unread" 
                     data-{{ $activity['type'] }}-id="{{ $activity['id'] }}">
                    
                    <div class="notif-dot-container">
                      <span class="notif-dot"></span>
                    </div>
                    
                    <div class="notif-main-content">
                      <div class="notif-header-line">
                        <strong>{{ ucfirst($activity['type']) }} Evaluation Required</strong>
                        <span class="notif-timestamp">
                          {{ $activity['created_at']->format('m/d/Y g:i A') }}
                        </span>
                      </div>
                      <p class="notif-message">Please evaluate "{{ $activity['title'] }}"</p>
                    </div>
                  </a>
                </li>
              @endforeach

              @if($generalNotifications->isEmpty() && $unevaluatedActivities->isEmpty())
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
                
                <div class="badges-wrapper">
                  <div class="profile-badge">
                    <span class="badge">{{ $roleBadge }}</span>
                    <span class="badge">{{ $age }} yrs old</span>
                  </div>

                  @php
                    $skTitle = '';
                    if (!empty(Auth::user()->sk_role)) {
                      $skTitle = Auth::user()->sk_role; 
                    } 
                    elseif (Auth::user()->role === 'sk_chairperson') {
                      $skTitle = 'SK Chairperson';
                    }
                  @endphp

                  @if($skTitle)
                    <div class="profile-badge sk-badge-yellow">
                      <span>{{ $skTitle }}</span>
                    </div>
                  @endif
                </div>
              </div>
            </div>
            <hr>

            <div class="profile-button-container">
              @php
                $isSkOfficial = !empty(Auth::user()->sk_role) || Auth::user()->role === 'sk_chairperson';
              @endphp

              @if($isSkOfficial)
                <a href="{{ route('sk.role.view') }}" class="profile-sk-button">
                  Switch to SK Role
                </a>
              @else
                <a href="#" class="profile-sk-button" id="accessSKRoleBtn" data-url="{{ route('sk.request.access') }}">
                  Access SK role
                </a>
              @endif
            </div>

            <ul class="profile-menu">
              <li>
                <a href="{{ route('profilepage') }}">
                  <i class="fas fa-user"></i> Profile
                </a>
              </li>
              
              <li>
                <a href="{{ route('faqspage') }}">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>

              <li>
                <a href="#" id="openFeedbackBtn">
                  <i class="fas fa-star"></i> Send Feedback to Katibayan
                </a>
              </li>
              
              <li class="logout-item">
                <a href="loginpage" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- SK Access Modal -->
    <div id="skAccessModal" class="modal-overlay" style="display: none;">
      <div class="sk-modal-box"> 
        <div class="modal-step active" data-step="1">
          <h2>Do you want to request access?</h2>
          <p>This will send a request to your SK Chairperson for permission to access your SK role. Would you like to proceed?</p>
          
          <div class="modal-actions" style="justify-content: flex-end;">
            <button type="button" class="btn btn-cancel" data-action="close">Cancel</button>
            <button type="button" class="btn btn-confirm" data-action="confirm-request">Yes</button>
          </div>
        </div>

        <div class="modal-step" data-step="2">
          <div class="spinner"></div>
          <p>Please wait while we send your request to the SK Chairperson. This will just take a moment.</p>
        </div>

        <div class="modal-step" data-step="3">
          <div class="modal-icon-wrapper success">
            <i class="fas fa-check"></i>
          </div>
          <h2>Request Sent</h2>
          <p>Thank you. Your request has been submitted. You will be notified once it is reviewed and approved.</p>
          <div class="modal-actions">
            <button type="button" class="btn btn-confirm" data-action="close">OK</button>
          </div>
        </div>

        <div class="modal-step" data-step="4">
          <div class="modal-icon-wrapper error">
            <i class="fas fa-exclamation-triangle"></i> 
          </div>
          <h2>Something went wrong</h2>
          <p>Please check your network and try again.</p>
          <div class="modal-actions">
            <button type="button" class="btn btn-confirm" data-action="try-again">Try again</button>
          </div>
        </div>
      </div> 
    </div> 

    <!-- Set Role Modal -->
    <div id="setRoleModal" class="modal-overlay" style="display: none;">
      <div class="set-role-modal-content">
        <h2>Choose your role as SK</h2>
        
        <form id="setRoleForm">
          <div class="role-options-list">
            <label class="role-option">
              <input type="radio" name="sk_role" value="Kagawad" checked>
              <span class="radio-circle"></span>
              <span class="role-name">Kagawad</span>
            </label>
            
            <label class="role-option">
              <input type="radio" name="sk_role" value="Secretary">
              <span class="radio-circle"></span>
              <span class="role-name">Secretary</span>
            </label>
            
            <label class="role-option">
              <input type="radio" name="sk_role" value="Treasurer">
              <span class="radio-circle"></span>
              <span class="role-name">Treasurer</span>
            </label>
          </div>
          
          <div class="modal-actions">
            <button type="submit" class="btn btn-confirm">Set Role</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Feedback Modal -->
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
          <textarea id="message" name="message" rows="5"></textarea>

          <input type="hidden" name="rating" id="ratingInput">
          
          <div class="form-actions">
            <button type="submit" class="submit-btn">Submit</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal-overlay simple-alert-modal">
      <div class="modal-content">
        <div class="success-icon">
          <i class="fas fa-check"></i>
        </div>
        <h2>Submitted</h2>
        <p>Thank you for your feedback! Your thoughts help us improve.</p>
        <button id="closeSuccessModal" class="ok-btn">OK</button>
      </div>
    </div>

    <!-- FAQs Section -->
    <section class="faqs-wrapper">
      <div class="faqs-container">
        <!-- LEFT SIDE -->
        <div class="faqs-left">
          <div class="faqs-badge">
            <h1>FAQs</h1>
          </div>
          <div class="faqs-header">
            <h2>Frequently asked <span>Questions</span></h2>
            <p>about KatiBayan</p>
          </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="faqs-right">
          <div class="faq-item">
            <button class="faq-question">
              What is KatiBayan Web Portal?
              <span class="arrow">&#9662;</span>
            </button>
            <div class="faq-answer">
              <p>
                The KatiBayan Web Portal is a digital platform developed to support
                Sangguniang Kabataan (SK) officials in streamlining youth profiling,
                managing events and programs, monitoring participation, sending
                announcements, and enabling data-driven decision-making through
                automated reporting and analytics.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              Who can use this system?
              <span class="arrow">&#9662;</span>
            </button>
            <div class="faq-answer">
              <p>
                The system can be used by SK officials, youth members, and administrators
                for efficient coordination and communication.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              How is youth information protected in the system?
              <span class="arrow">&#9662;</span>
            </button>
            <div class="faq-answer">
              <p>
                Data is protected through encryption, authentication, and secure access
                levels to ensure youth information remains private.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              Can the system help track event attendance and feedback?
              <span class="arrow">&#9662;</span>
            </button>
            <div class="faq-answer">
              <p>
                Yes, the portal provides attendance logs, feedback forms, and reports to
                evaluate the success of youth programs and events.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              Can I use the system offline?
              <span class="arrow">&#9662;</span>
            </button>
            <div class="faq-answer">
              <p>
                No, the portal requires an internet connection to ensure real-time data
                access and synchronization.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              What if I forgot my password? What should I do?
              <span class="arrow">&#9662;</span>
            </button>
            <div class="faq-answer">
              <p>
                Use the "Forgot Password" option on the login page to reset your password
                via email verification.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              Where can I edit my personal information?
              <span class="arrow">&#9662;</span>
            </button>
            <div class="faq-answer">
              <p>
                You can edit your personal information from your profile settings once you
                are logged in to the system.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Evaluation Modal -->
  <div id="evaluationModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="modal-header">
        <h2>Evaluate Activity</h2>
        <span id="modalActivityName" class="activity-name"></span>
      </div>
      <div class="modal-body">
        <form id="evaluationForm">
          @csrf
          <input type="hidden" id="evaluationActivityId" name="activity_id">
          <input type="hidden" id="evaluationActivityType" name="activity_type">
          
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
            <textarea id="comments" name="comments" rows="4" placeholder="Share your thoughts about the activity..."></textarea>
          </div>

          <div class="form-group">
            <label>Would you recommend this activity to others?</label>
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

  // Theme Toggle Function
  function applyTheme(isDark) {
    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    
    body.classList.toggle('dark-mode', isDark);
    
    const icon = isDark ? 'sun' : 'moon';

    if (themeToggle) {
      themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
    }

    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
    
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
  }

  // Update Time
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

  // Initialize Sidebar
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

  // Initialize Topbar
  function initTopbar(openEvaluationModal) {
    updateTime();
    setInterval(updateTime, 60000);

    const notifWrapper = document.querySelector(".notification-wrapper");
    const profileWrapper = document.querySelector(".profile-wrapper");
    const profileToggle = document.getElementById("profileToggle");
    const profileDropdown = profileWrapper?.querySelector(".profile-dropdown");

    // Notifications Dropdown
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
      notifWrapper.querySelectorAll('.notif-link[data-event-id], .notif-link[data-program-id]').forEach(notification => {
        notification.addEventListener('click', function(e) {
          e.preventDefault();
          const eventId = this.getAttribute('data-event-id');
          const programId = this.getAttribute('data-program-id');

          if (openEvaluationModal) {
            if (eventId) {
              openEvaluationModal(eventId, 'event');
            } else if (programId) {
              openEvaluationModal(programId, 'program');
            }
          }
          notifWrapper.classList.remove('active');
        });
      });
    }

    // Profile Dropdown
    if (profileWrapper && profileToggle && profileDropdown) {
      profileToggle.addEventListener("click", (e) => {
        e.stopPropagation();
        profileWrapper.classList.toggle("active");
        notifWrapper?.classList.remove("active");
      });

      profileDropdown.addEventListener("click", (e) => e.stopPropagation());
    }

    // Global Click Listener
    document.addEventListener("click", (e) => {
      const sidebar = document.querySelector('.sidebar');
      const menuToggle = document.querySelector('.menu-toggle');
      
      if (window.innerWidth > 768 && sidebar && menuToggle && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
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

  // Initialize FAQs Accordion
  function initFAQs() {
    document.querySelectorAll('.faq-question').forEach(button => {
      button.addEventListener('click', () => {
        const item = button.parentElement;
        const faqItems = document.querySelectorAll('.faq-item');

        // Close lahat muna
        faqItems.forEach(faq => {
          if (faq !== item) {
            faq.classList.remove('active');
          }
        });

        // Toggle yung current
        item.classList.toggle('active');
      });
    });
  }

  // Initialize Evaluation Modal
  function initEvaluationModal() {
    const evalModal = document.getElementById('evaluationModal');

    function openEvaluationModal(activityId, activityType = 'event') {
      if (!evalModal) {
        console.error("Evaluation modal not found in DOM.");
        return;
      }

      const apiUrl = activityType === 'event' ? `/events/${activityId}` : `/programs/${activityId}`;

      fetch(apiUrl)
        .then(response => {
          if (!response.ok) throw new Error('Activity not found or server error');
          return response.json();
        })
        .then(activity => {
          document.getElementById('modalActivityName').textContent = activity.title;
          document.getElementById('evaluationActivityId').value = activity.id;
          document.getElementById('evaluationActivityType').value = activityType;
          evalModal.style.display = 'block';
        })
        .catch(error => {
          console.error('Error fetching activity details:', error);
          alert('Error loading activity details');
        });
    }

    if (!evalModal) {
      return function() {
        console.warn("Tried to open evaluation modal, but it was not found.");
      };
    }

    const evalCloseIcon = evalModal.querySelector('.close');
    const evalCloseBtn = evalModal.querySelector('.close-btn');
    const evalSubmitBtn = evalModal.querySelector('.submit-evaluation-btn');
    const evalStars = evalModal.querySelectorAll('.star');
    const evalForm = document.getElementById('evaluationForm');

    evalStars.forEach(star => {
      star.addEventListener('click', function() {
        const rating = this.getAttribute('data-rating');
        document.getElementById('rating').value = rating;

        evalStars.forEach((s, index) => {
          s.classList.toggle('active', index < rating);
        });
      });
    });

    const closeModal = () => {
      evalModal.style.display = 'none';
      evalForm.reset();
      evalStars.forEach(s => s.classList.remove('active'));
    };

    evalCloseIcon?.addEventListener('click', closeModal);
    evalCloseBtn?.addEventListener('click', closeModal);
    evalModal.addEventListener('click', (e) => {
      if (e.target === evalModal) {
        closeModal();
      }
    });

    evalSubmitBtn?.addEventListener('click', function() {
      const formData = new FormData(evalForm);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      if (!formData.get('rating')) {
        alert('Please provide a rating');
        return;
      }

      const activityType = formData.get('activity_type');
      if (activityType === 'event') {
        formData.set('event_id', formData.get('activity_id'));
      } else {
        formData.set('program_id', formData.get('activity_id'));
      }

      fetch('/evaluation', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            return response.json().then(err => {
              throw err;
            });
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            alert('Evaluation submitted successfully!');
            closeModal();
            location.reload();
          } else {
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

    return openEvaluationModal;
  }

  // Initialize Feedback Modal
  function initFeedbackModal() {
    const feedbackTriggerBtn = document.getElementById('openFeedbackBtn');
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

    // Custom Select Box
    const customSelect = document.getElementById('customSelect');
    if (customSelect) {
      const trigger = customSelect.querySelector('.custom-select-trigger');
      const selectedText = document.getElementById('selectedFeedbackType');
      const optionsList = customSelect.querySelector('.custom-options-list');
      const options = customSelect.querySelectorAll('.custom-option');
      const realSelect = document.getElementById('type');

      trigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        customSelect.classList.toggle('open');
      });

      options?.forEach(option => {
        option.addEventListener('click', () => {
          const value = option.getAttribute('data-value');
          const text = option.textContent.trim();

          if (selectedText) selectedText.textContent = text;
          if (realSelect) realSelect.value = value;
          trigger?.classList.add('selected');

          customSelect.classList.remove('open');
        });
      });

      document.addEventListener('click', () => {
        customSelect.classList.remove('open');
      });
    }

    feedbackTriggerBtn.addEventListener('click', (e) => {
      e.preventDefault();
      feedbackModal.style.display = 'flex';
    });

    feedbackCloseBtn?.addEventListener('click', () => {
      feedbackModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      if (e.target === feedbackModal) {
        feedbackModal.style.display = 'none';
      }
      if (e.target === successModal) {
        successModal.style.display = 'none';
      }
    });

    feedbackStars.forEach(star => {
      star.addEventListener('click', () => {
        const rating = star.getAttribute('data-value');
        if (feedbackRatingInput) feedbackRatingInput.value = rating;

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

    if (feedbackForm) {
      feedbackForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(feedbackForm);
        const submitButtonText = submitBtn.textContent;

        if (submitBtn) {
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
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              feedbackModal.style.display = 'none';
              if (successModal) successModal.style.display = 'flex';
            } else {
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
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = submitButtonText;
            }

            feedbackForm.reset();
            feedbackStars.forEach(s => {
              s.classList.remove('fas');
              s.classList.add('far');
            });
            if (feedbackRatingInput) feedbackRatingInput.value = '';

            const selectedText = document.getElementById('selectedFeedbackType');
            const trigger = customSelect?.querySelector('.custom-select-trigger');
            const realSelect = document.getElementById('type');
            if (selectedText) selectedText.textContent = 'Select feedback type';
            trigger?.classList.remove('selected');
            if (realSelect) realSelect.value = '';
          });
      });
    }

    closeSuccessBtn?.addEventListener('click', () => {
      if (successModal) successModal.style.display = 'none';
    });
  }

  function initMarkAsRead() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
      console.error('CSRF token not found.');
      return;
    }

    document.querySelectorAll('.notif-link[data-id]').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const notifId = this.dataset.id;
        const destinationUrl = this.href;

        const notifItem = this.closest('li');
        notifItem?.remove();

        const countEl = document.querySelector('.notif-count');
        if (countEl) {
          let currentCount = parseInt(countEl.textContent) || 0;
          countEl.textContent = Math.max(0, currentCount - 1);
          if (parseInt(countEl.textContent) === 0) {
            countEl.remove();
            const bellDot = document.querySelector('.notif-dot');
            if (bellDot) bellDot.remove();
          }
        }

        const notifList = document.querySelector('.notif-list');
        if (notifList && notifList.children.length === 0) {
          notifList.innerHTML = `<li class="no-notifications"><p>No new notifications</p></li>`;
        }

        fetch(`/notifications/${notifId}/read`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              id: notifId
            })
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

  function confirmLogout(event) {
    event.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
      document.getElementById('logout-form').submit();
    }
  }

  // Main Initialization
  document.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons();

    // Theme Toggle
    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    
    const savedTheme = localStorage.getItem('theme') === 'dark';
    applyTheme(savedTheme);

    if (themeToggle) {
      themeToggle.addEventListener('click', () => {
        const isDark = !body.classList.contains('dark-mode');
        applyTheme(isDark);
      });
    }

    // Initialize Components
    initSidebar();
    const openEvalModalFn = initEvaluationModal();
    initTopbar(openEvalModalFn);
    initFAQs();
    initFeedbackModal();
    initMarkAsRead();
  });
  </script>

  <script>
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const sidebar = document.querySelector('.sidebar');
  const mainContent = document.querySelector('.main');

  mobileBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    sidebar.classList.toggle('open');
    document.body.classList.toggle('mobile-sidebar-active');
  });

  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768 &&
      sidebar.classList.contains('open') &&
      !sidebar.contains(e.target) &&
      !mobileBtn.contains(e.target)) {
      
      sidebar.classList.remove('open');
      document.body.classList.remove('mobile-sidebar-active');
    }
  });
  </script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // SK Access Modal
    const skModal = document.getElementById('skAccessModal');
    const openModalBtn = document.getElementById('accessSKRoleBtn');

    function showModalStep(stepNumber) {
      if (!skModal) return;
      skModal.querySelectorAll('.modal-step').forEach(step => {
        step.classList.remove('active');
        step.style.display = 'none';
      });
      const activeStep = skModal.querySelector(`.modal-step[data-step="${stepNumber}"]`);
      if (activeStep) {
        activeStep.classList.add('active');
        activeStep.style.display = 'block';
      }
    }

    function closeSkModal() {
      if (skModal) skModal.style.display = 'none';
    }

    async function handleSubmitRequest() {
      console.log("Sending request to backend...");
      showModalStep(2);

      const btn = document.getElementById('accessSKRoleBtn');
      const skAccessUrl = btn?.dataset.url || '/sk/request-access';
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      if (!csrfToken) {
        console.error('CSRF Token is missing!');
        showModalStep(4);
        return;
      }

      try {
        const response = await fetch(skAccessUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ _token: csrfToken })
        });

        const data = await response.json();

        if (response.ok) {
          showModalStep(3);
        } else {
          console.error(data.message);
          const errText = skModal.querySelector('.modal-step[data-step="4"] p');
          if(errText) errText.textContent = data.message || 'Failed to submit request.';
          showModalStep(4);
        }
      } catch (error) {
        console.error('Fetch error:', error);
        showModalStep(4);
      }
    }

    if (openModalBtn) {
      openModalBtn.addEventListener('click', function(e) {
        e.preventDefault();
        showModalStep(1);
        skModal.style.display = 'flex';
      });
    }

    if (skModal) {
      skModal.addEventListener('click', function(e) {
        const action = e.target.dataset.action;
        if (!action) return;

        switch (action) {
          case 'close':
            closeSkModal();
            break;
          case 'confirm-request':
            handleSubmitRequest();
            break;
          case 'try-again':
            handleSubmitRequest();
            break;
        }
      });
    }

    // Set Role Modal
    const setRoleModal = document.getElementById('setRoleModal');
    const setRoleForm = document.getElementById('setRoleForm');

    window.openSetRoleModal = function() {
      if (setRoleModal) {
        setRoleModal.style.display = 'flex';
        console.log('Opening Set Role Modal...');
      } else {
        console.error('Error: Cannot find modal with id "setRoleModal"');
      }
    };

    window.addEventListener('click', function(e) {
      if (e.target === setRoleModal) {
        setRoleModal.style.display = 'none';
      }
    });

    if (setRoleForm) {
      setRoleForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const btn = setRoleForm.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        
        btn.textContent = "Saving...";
        btn.disabled = true;

        const formData = new FormData(setRoleForm);
        const selectedRole = formData.get('sk_role');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
          const response = await fetch('/sk/set-role', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ role: selectedRole })
          });

          const data = await response.json();

          if (response.ok) {
            alert('Role set successfully! Redirecting...');
            window.location.reload();
          } else {
            alert(data.message || 'Failed to set role.');
            btn.textContent = originalText;
            btn.disabled = false;
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Something went wrong. Please try again.');
          btn.textContent = originalText;
          btn.disabled = false;
        }
      });
    }
  });
  </script>
</body>
</html>