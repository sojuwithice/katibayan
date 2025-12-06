<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Poll</title>
  <link rel="stylesheet" href="{{ asset('css/polls.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

      <button class="theme-toggle" id="themeToggle">
        <i data-lucide="moon"></i>
      </button>

      <!-- Notifications -->
      <div class="notification-wrapper">
        <i class="fas fa-bell"></i>
        @if($totalNotificationCount > 0)
          <span class="notif-count">{{ $totalNotificationCount }}</span>
        @endif
        <div class="notif-dropdown">
          <div class="notif-header">
            <strong>Notification</strong>
            @if($totalNotificationCount > 0)
              <span>{{ $totalNotificationCount }}</span>
            @endif
          </div>
          
          <ul class="notif-list">
            {{-- General Notifications --}}
            @foreach ($generalNotifications as $notif)
              @php
                $link = '#'; // Default
                $onclickAction = ''; // Default na walang click action
                
                // --- Para sa Links ---
                if ($notif->type == 'certificate_schedule') {
                  $link = route('certificatepage'); 
                } 
                // FIX: Check kung SK Request Approved (galing sa Controller mo)
                elseif ($notif->type == 'sk_request_approved' || $notif->type == 'App\Notifications\SkRequestAccepted') { 
                  $link = '#'; 
                  // Dito tinatawag ang Javascript function para sa Modal
                  $onclickAction = 'openSetRoleModal(); return false;';
                }

                // --- Para sa Title at Message ---
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

            {{-- Evaluation Notifications --}}
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
              
              <!-- FIXED: Added badges-wrapper like in dashboard -->
              <div class="badges-wrapper">
                <div class="profile-badge">
                  <span class="badge">{{ $roleBadge }}</span>
                  <span class="badge">{{ $age }} yrs old</span>
                </div>

                <!-- FIXED: Added SK badge logic -->
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
          
          <!-- Hidden Logout Form -->
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </header>

  <!-- SK Access Modal (Same as Dashboard) -->
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

  <!-- Set Role Modal (Same as Dashboard) -->
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

  <!-- Feedback Modal (Same as Dashboard) -->
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

  <!-- Success Modal (Same as Dashboard) -->
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

  <!-- Polls Content -->
  <div class="polls-container">
    <div class="polls-header-box">
      <div class="polls-header">
        <h2>Polls</h2>

        <div class="committee-select">
          <label for="committee">Select a Committee:</label>
          <!-- Custom Dropdown -->
          <div class="custom-dropdown">
            <button class="dropdown-toggle">
              <span class="selected-text">All</span>
              <span class="arrow-circle">
                <i data-lucide="chevron-down"></i>
              </span>
            </button>
            <ul class="dropdown-menu">
              <li data-value="all">All</li>
              @foreach($committees as $committee)
                <li data-value="{{ strtolower($committee) }}">{{ $committee }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    <h3 class="suggestions-title">Suggestions Board</h3>

    <div id="pollsList">
      @if($polls->count() > 0)
        @foreach($polls as $poll)
          <div class="poll-box" data-poll-id="{{ $poll->id }}" data-committee="{{ $poll->committee ? strtolower($poll->committee) : '' }}">
            <div class="poll-header">
              <p>
                <span class="label">Question:</span> {{ $poll->question }}
                @if($poll->committee)
                  <span class="committee-badge">{{ $poll->committee }}</span>
                @endif
              </p>
              <div class="poll-meta">
                <div class="avatars">
                  @foreach($poll->votes->take(4) as $vote)
                    <img src="{{ $vote->user->avatar ? asset('storage/' . $vote->user->avatar) : asset('images/default-avatar.png') }}" 
                         alt="User" class="avatar-img">
                  @endforeach
                </div>
                @if($poll->votes->count() > 4)
                  <span class="others">+ {{ $poll->votes->count() - 4 }} others</span>
                @endif
                
                @if($poll->userHasVoted(Auth::id()))
                  <button class="vote-btn voted">
                    <i class="fas fa-check"></i> Voted
                  </button>
                @else
                  <button class="vote-btn">Vote</button>
                @endif
              </div>
            </div>

            <div class="poll-body hidden">
              <h4 class="choices-label">Choices</h4>
              
              @foreach($poll->options as $index => $option)
                <label class="choice">
                  <input type="radio" name="poll{{ $poll->id }}" value="{{ $index }}" 
                         {{ $poll->getUserVote(Auth::id()) == $index ? 'checked' : '' }}>
                  <span class="choice-text">{{ $option }}</span>
                </label>
              @endforeach

              <div class="poll-footer">
                <span class="end-date">Ends: {{ \Carbon\Carbon::parse($poll->end_date)->format('M d, Y') }}</span>
                <span class="total-votes">Total Votes: {{ $poll->votes->count() }}</span>
              </div>

              @if($poll->userHasVoted(Auth::id()))
                <div class="change-vote-section">
                  <button class="change-vote-btn" data-poll-id="{{ $poll->id }}">
                    <i class="fas fa-edit"></i> Change Vote
                  </button>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      @else
        <div class="no-polls">
          <p>No active polls available for your barangay.</p>
          <p>Check back later for new polls from your SK officials.</p>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- Change Vote Confirmation Modal -->
<div id="changeVoteModal" class="modal-overlay hidden">
  <div class="modal-box">
    <p>Are you sure you want to change your vote? You can only change your vote once.</p>
    <div class="modal-actions">
      <button class="cancel-btn">Cancel</button>
      <button class="confirm-change-btn">Yes, Change Vote</button>
    </div>
  </div>
</div>

<!-- Evaluation Modal (Same as Dashboard) -->
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
function initTopbar(openEvaluationModal) {
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

    // Handle evaluation notification clicks for both events and programs
    notifWrapper.querySelectorAll('.notif-link[data-event-id], .notif-link[data-program-id]').forEach(notification => {
      notification.addEventListener('click', function(e) {
        e.preventDefault(); // Pigilan 'yung default link behavior
        const eventId = this.getAttribute('data-event-id');
        const programId = this.getAttribute('data-program-id');

        if (openEvaluationModal) {
          if (eventId) {
            openEvaluationModal(eventId, 'event'); // Specify activity type
          } else if (programId) {
            openEvaluationModal(programId, 'program'); // Specify activity type
          }
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
    
    // (FIX) Only run this sidebar logic on DESKTOP. 
    // Mobile has its own listener in the other script tag.
    if (window.innerWidth > 768 && sidebar && menuToggle && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
      document.querySelector('.profile-item')?.classList.remove('open');
    }

    // Ito ay para sa profile at notif dropdowns (OK ito)
    if (profileWrapper && !profileWrapper.contains(e.target)) {
      profileWrapper.classList.remove("active");
    }
    if (notifWrapper && !notifWrapper.contains(e.target)) {
      notifWrapper.classList.remove("active");
    }
  });
}

/**
 * (FIXED STRUCTURE)
 * Initializes the Activity Evaluation Modal.
 * Returns the function to open the modal.
 */
function initEvaluationModal() {
  const evalModal = document.getElementById('evaluationModal');

  // (FIX) Define the opener function here
  function openEvaluationModal(activityId, activityType = 'event') {
    if (!evalModal) {
      console.error("Evaluation modal not found in DOM.");
      return;
    }

    // Determine the API endpoint based on activity type
    const apiUrl = activityType === 'event' ? `/events/${activityId}` : `/programs/${activityId}`;

    // Fetch activity details
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

    // Determine which field to set based on activity type
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
        body: formData // Send as FormData
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

    // Handle option click
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

  // AJAX Form Submission
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

          // Reset custom select
          const selectedText = document.getElementById('selectedFeedbackType');
          const trigger = customSelect?.querySelector('.custom-select-trigger');
          const realSelect = document.getElementById('type');
          if (selectedText) selectedText.textContent = 'Select feedback type';
          trigger?.classList.remove('selected');
          if (realSelect) realSelect.value = '';
        });
    });
  }

  // Close success modal
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

  // ===================================================
  // ✅ THEME TOGGLE INITIALIZATION
  // ===================================================
  const body = document.body;
  const themeToggle = document.getElementById('themeToggle');
  
  // 1. Load saved theme and apply it immediately
  const savedTheme = localStorage.getItem('theme') === 'dark';
  applyTheme(savedTheme); 

  // 2. Add event listener to theme toggle
  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      // Tiyakin na ang theme check ay base sa body class
      const isDark = !body.classList.contains('dark-mode'); 
      applyTheme(isDark);
    });
  }
  // ===================================================

  // Initialize all components
  initSidebar();

  // (FIX) Kunin 'yung function na nireturn ng initEvaluationModal()
  const openEvalModalFn = initEvaluationModal();

  // Ipasa 'yung function sa initTopbar()
  initTopbar(openEvalModalFn);
  initFeedbackModal();
  initMarkAsRead();

  // Initialize the rest of the poll-specific functionality
  initPollFunctionality();
});

/**
 * Function to apply theme
 */
function applyTheme(isDark) {
  const body = document.body;
  const themeToggle = document.getElementById('themeToggle');
  
  body.classList.toggle('dark-mode', isDark);
  
  // Piliin kung 'sun' o 'moon' icon ang ipapakita (Lucide icons)
  const icon = isDark ? 'sun' : 'moon';

  if (themeToggle) {
    // Palitan ang icon sa loob ng button
    themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
  }

  // Re-initialize Lucide icons para mag-update ang icon
  // Tiyakin na defined ang 'lucide' bago tawagin
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
  
  // Save theme to localStorage and update HTML attribute (data-theme)
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
  document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
}

/**
 * Poll-specific functionality
 */
function initPollFunctionality() {
  // === Custom dropdown - FIXED COMMITTEE FILTER ===
  const dropdown = document.querySelector(".custom-dropdown");
  if (dropdown) {
    const toggle = dropdown.querySelector(".dropdown-toggle");
    const menu = dropdown.querySelector(".dropdown-menu");
    const selected = dropdown.querySelector(".selected-text");
    
    toggle.addEventListener("click", () => {
      const isOpen = dropdown.classList.toggle("open");
      menu.style.display = isOpen ? "block" : "none";
    });
    
    menu.querySelectorAll("li").forEach(item => {
      item.addEventListener("click", () => {
        selected.textContent = item.textContent;
        menu.style.display = "none";
        dropdown.classList.remove("open");
        
        // Filter polls by committee - FIXED VERSION
        const committee = item.dataset.value;
        document.querySelectorAll('.poll-box').forEach(poll => {
          if (committee === 'all') {
            poll.style.display = 'block';
          } else {
            const pollCommittee = poll.dataset.committee;
            if (pollCommittee === committee) {
              poll.style.display = 'block';
            } else {
              poll.style.display = 'none';
            }
          }
        });
      });
    });
    
    document.addEventListener("click", e => {
      if (!dropdown.contains(e.target)) {
        menu.style.display = "none";
        dropdown.classList.remove("open");
      }
    });
  }

  // === Polls toggle & vote button ===
  document.querySelectorAll('.vote-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const poll = this.closest('.poll-box');
      const body = poll.querySelector('.poll-body');
      
      // Toggle current poll
      body.classList.toggle('hidden');
      
      // Close other polls
      document.querySelectorAll('.poll-body').forEach(b => {
        if (b !== body) b.classList.add('hidden');
      });
    });
  });

  // === Voting functionality ===
  document.querySelectorAll('.choice input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
      if (this.disabled) return;
      
      const pollBox = this.closest('.poll-box');
      const pollId = pollBox.dataset.pollId;
      const optionIndex = this.value;
      
      // Send vote to server
      fetch(`/polls/${pollId}/vote`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({ option_index: optionIndex })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update UI
          const voteBtn = pollBox.querySelector('.vote-btn');
          voteBtn.classList.add('voted');
          voteBtn.innerHTML = '<i class="fas fa-check"></i> Voted';
          
          // Add change vote button
          const changeVoteSection = document.createElement('div');
          changeVoteSection.className = 'change-vote-section';
          changeVoteSection.innerHTML = `
            <button class="change-vote-btn" data-poll-id="${pollId}">
              <i class="fas fa-edit"></i> Change Vote
            </button>
          `;
          pollBox.querySelector('.poll-body').appendChild(changeVoteSection);
          
          // Add event listener to the new change vote button
          changeVoteSection.querySelector('.change-vote-btn').addEventListener('click', function() {
            showChangeVoteModal(pollId);
          });
          
          // Reload poll results
          loadPollResults(pollId);
        } else {
          alert(data.error);
          this.checked = false;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error submitting vote');
        this.checked = false;
      });
    });
  });

  // === Change Vote Functionality ===
  let currentPollIdForChange = null;

  function showChangeVoteModal(pollId) {
    currentPollIdForChange = pollId;
    document.getElementById('changeVoteModal').classList.remove('hidden');
  }

  // Change Vote Modal Event Listeners
  const changeVoteModal = document.getElementById('changeVoteModal');
  const cancelChangeBtn = changeVoteModal.querySelector('.cancel-btn');
  const confirmChangeBtn = changeVoteModal.querySelector('.confirm-change-btn');

  cancelChangeBtn?.addEventListener('click', () => {
    changeVoteModal.classList.add('hidden');
    currentPollIdForChange = null;
  });

  confirmChangeBtn?.addEventListener('click', () => {
    if (currentPollIdForChange) {
      resetVote(currentPollIdForChange);
      changeVoteModal.classList.add('hidden');
      currentPollIdForChange = null;
    }
  });

  changeVoteModal?.addEventListener('click', (e) => {
    if (e.target === changeVoteModal) {
      changeVoteModal.classList.add('hidden');
      currentPollIdForChange = null;
    }
  });

  // Reset vote function
  function resetVote(pollId) {
    fetch(`/polls/${pollId}/reset-vote`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.csrfToken
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const pollBox = document.querySelector(`[data-poll-id="${pollId}"]`);
        
        // Reset UI
        const voteBtn = pollBox.querySelector('.vote-btn');
        voteBtn.classList.remove('voted');
        voteBtn.innerHTML = 'Vote';
        
        // Enable radio buttons
        pollBox.querySelectorAll('input[type="radio"]').forEach(input => {
          input.disabled = false;
          input.checked = false;
        });
        
        // Remove change vote button
        const changeVoteSection = pollBox.querySelector('.change-vote-section');
        if (changeVoteSection) {
          changeVoteSection.remove();
        }
        
        // Reload results
        loadPollResults(pollId);
      } else {
        alert(data.error);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error resetting vote');
    });
  }

  // Add event listeners to existing change vote buttons
  document.querySelectorAll('.change-vote-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const pollId = this.dataset.pollId;
      showChangeVoteModal(pollId);
    });
  });

  // Load poll results
  function loadPollResults(pollId) {
    fetch(`/polls/${pollId}/results`)
      .then(response => response.json())
      .then(data => {
        const pollBox = document.querySelector(`[data-poll-id="${pollId}"]`);
        
        // Update total votes
        const totalVotes = pollBox.querySelector('.total-votes');
        if (totalVotes) {
          totalVotes.textContent = `Total Votes: ${data.total_votes}`;
        }
        
        // Update avatars in header
        const avatarsContainer = pollBox.querySelector('.poll-meta .avatars');
        avatarsContainer.innerHTML = '';
        
        // Add first 4 voter avatars
        data.poll.votes.slice(0, 4).forEach(vote => {
          const img = document.createElement('img');
          img.src = vote.user.avatar ? `/storage/${vote.user.avatar}` : '/images/default-avatar.png';
          img.alt = 'User';
          img.className = 'avatar-img';
          avatarsContainer.appendChild(img);
        });
        
        // Update "others" count
        const othersSpan = pollBox.querySelector('.others');
        if (othersSpan && data.total_votes > 4) {
          othersSpan.textContent = `+ ${data.total_votes - 4} others`;
        } else if (othersSpan && data.total_votes <= 4) {
          othersSpan.remove();
        }
      })
      .catch(error => console.error('Error loading results:', error));
  }
}

// ==========================================
// SK Access Modal and Set Role Modal Logic
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
  // 1. ACCESS SK ROLE MODAL (REQUEST LOGIC)
  const skModal = document.getElementById('skAccessModal');
  const openModalBtn = document.getElementById('accessSKRoleBtn');

  // Helper: Show specific step
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

  // Helper: Close SK Modal
  function closeSkModal() {
    if (skModal) skModal.style.display = 'none';
  }

  // Submit Request Logic
  async function handleSubmitRequest() {
    showModalStep(2); // Loading

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
        showModalStep(3); // Success
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

  // Event Listeners: Open Request Modal
  if (openModalBtn) {
    openModalBtn.addEventListener('click', function(e) {
      e.preventDefault();
      showModalStep(1);
      skModal.style.display = 'flex';
    });
  }

  // Event Listeners: Modal Buttons
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

  // 2. SET ROLE MODAL LOGIC
  const setRoleModal = document.getElementById('setRoleModal');
  const setRoleForm = document.getElementById('setRoleForm');

  // Make this GLOBAL so the notification onClick can call it
  window.openSetRoleModal = function() {
    if (setRoleModal) {
      setRoleModal.style.display = 'flex';
      console.log('Opening Set Role Modal...');
    } else {
      console.error('Error: Cannot find modal with id "setRoleModal"');
    }
  };

  // Close Set Role Modal on outside click
  window.addEventListener('click', function(e) {
    if (e.target === setRoleModal) {
      setRoleModal.style.display = 'none';
    }
  });

  // Handle Form Submit (Set Role)
  if (setRoleForm) {
    setRoleForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      const btn = setRoleForm.querySelector('button[type="submit"]');
      const originalText = btn.textContent;
      
      // UI Feedback
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

// ==========================================
// Mobile Sidebar Toggle
// ==========================================
const mobileBtn = document.getElementById('mobileMenuBtn');
const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main');

mobileBtn?.addEventListener('click', (e) => {
  e.stopPropagation();
  sidebar.classList.toggle('open');
  document.body.classList.toggle('mobile-sidebar-active');
});

// Close sidebar when clicking outside (mobile only)
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
</body>
</html>