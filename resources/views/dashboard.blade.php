<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    // Make CSRF token globally available
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Override browser default alert, confirm, and prompt
    window.originalAlert = window.alert;
    window.originalConfirm = window.confirm;
    window.originalPrompt = window.prompt;
    
    // Custom alert function
    window.alert = function(message, title = 'Alert', icon = 'info', callback = null) {
      window.showCustomAlert(message, title, icon, callback);
      return undefined;
    };
    
    // Custom confirm function
    window.confirm = function(message, title = 'Confirmation', icon = 'warning', callback = null) {
      return window.showCustomConfirm(message, title, icon, callback);
    };
    
    // Custom prompt function
    window.prompt = function(message, defaultValue = '', title = 'Input Required', callback = null) {
      return window.showCustomPrompt(message, defaultValue, title, callback);
    };
  </script>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
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
        
        <!-- Notification Wrapper - UPDATED -->
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
                  
                  if ($notif['type'] == 'certificate_schedule') {
                    $link = route('certificatepage'); 
                  } 
                  elseif ($notif['type'] == 'sk_request_approved' || $notif['type'] == 'App\Notifications\SkRequestAccepted') { 
                    $link = '#'; 
                    $onclickAction = 'openSetRoleModal(); return false;';
                  }
                  elseif ($notif['notification_type'] == 'evaluation_required') {
                    $link = route('evaluation.show', $notif['activity_id']);
                  }

                  $title = $notif['title'] ?? 'Notification';
                  $message = $notif['message'] ?? 'You have a new notification.';
                  $isRead = $notif['is_read'] ?? 0;
                @endphp
                
                <li>
                  <a href="{{ $link }}" 
                     class="notif-link {{ $isRead == 0 ? 'unread' : '' }}" 
                     @if(isset($notif['id']) && !str_starts_with($notif['id'], 'eval_'))
                         data-id="{{ $notif['id'] }}"
                     @endif
                     @if(isset($notif['activity_id']))
                         data-{{ $notif['activity_type'] }}-id="{{ $notif['activity_id'] }}"
                     @endif
                     @if($onclickAction) onclick="{{ $onclickAction }}" @endif>
                    
                    <div class="notif-dot-container">
                      @if ($isRead == 0)
                        <span class="notif-dot"></span>
                      @else
                        <span class="notif-dot-placeholder"></span>
                      @endif
                    </div>

                    <div class="notif-main-content">
                      <div class="notif-header-line">
                        <strong>{{ $title }}</strong>
                        <span class="notif-timestamp">
                          @if($notif['created_at'] instanceof \Carbon\Carbon)
                            {{ $notif['created_at']->format('m/d/Y g:i A') }}
                          @else
                            {{ \Carbon\Carbon::parse($notif['created_at'])->format('m/d/Y g:i A') }}
                          @endif
                        </span>
                      </div>
                      <p class="notif-message">{{ $message }}</p>
                    </div>
                  </a>
                </li>
              @endforeach

              @if($generalNotifications->isEmpty())
                <li class="no-notifications">
                  <p>No new notifications</p>
                </li>
              @endif
            </ul>
          </div>
        </div>

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
                <a href="{{ route('faqs') }}">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>

              <li>
                <a href="#" id="openFeedbackBtn">
                  <i class="fas fa-star"></i> Send Feedback to Katibayan
                </a>
              </li>
              
              <li class="logout-item">
                <a href="#" onclick="showLogoutConfirmation(); return false;">
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

    <!-- ========================================== -->
    <!-- CUSTOM MODALS FOR BROWSER POPUP REPLACEMENT -->
    <!-- ========================================== -->

    <!-- Custom Alert Modal -->
    <div id="customAlertModal" class="alert-modal-overlay">
      <div class="alert-modal-box">
        <div class="alert-modal-icon" id="alertModalIcon">
          <i class="fas fa-info-circle"></i>
        </div>
        <h3 class="alert-modal-title" id="alertModalTitle">Alert</h3>
        <p class="alert-modal-message" id="alertModalMessage"></p>
        <div class="alert-modal-actions">
          <button class="alert-modal-btn ok" id="alertModalOK">OK</button>
        </div>
      </div>
    </div>

    <!-- Custom Confirm Modal (Logout & Others) -->
    <div id="customConfirmModal" class="confirmation-modal-overlay">
      <div class="confirmation-modal-box">
        <div class="confirmation-modal-icon" id="confirmModalIcon">
          <i class="fas fa-question-circle"></i>
        </div>
        <h3 class="confirmation-modal-title" id="confirmModalTitle">Confirmation</h3>
        <p class="confirmation-modal-message" id="confirmModalMessage"></p>
        <div class="confirmation-modal-actions">
          <button class="confirmation-modal-btn cancel" id="confirmModalCancel">Cancel</button>
          <button class="confirmation-modal-btn confirm" id="confirmModalOK">Confirm</button>
        </div>
      </div>
    </div>

    <!-- Custom Prompt Modal -->
    <div id="customPromptModal" class="prompt-modal-overlay">
      <div class="prompt-modal-box">
        <h3 class="prompt-modal-title" id="promptModalTitle">Input Required</h3>
        <p class="prompt-modal-message" id="promptModalMessage"></p>
        <input type="text" class="prompt-modal-input" id="promptModalInput" placeholder="Enter your response...">
        <div class="prompt-modal-actions">
          <button class="prompt-modal-btn cancel" id="promptModalCancel">Cancel</button>
          <button class="prompt-modal-btn ok" id="promptModalOK">OK</button>
        </div>
      </div>
    </div>

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

    <!-- Dashboard Content -->
    <div id="dashboard-page" class="page active">
      <div class="row">

        <section class="welcome">
          <div class="slides">
            @foreach($sliderItems as $index => $item)
              @if($item['type'] == 'welcome')
                <!-- Welcome Slide -->
                <div class="slide welcome-slide">
                  <h2>Welcome, {{ $user->given_name }}!</h2>
                  <h3>Have a nice day!</h3><br>
                  <p>
                    <span>KatiBayan</span> provides a platform for the youth to stay updated on SK events 
                    and programs while fostering active participation in community development.
                  </p>
                </div>
              
              @elseif($item['type'] == 'no_events')
                <!-- No Events Slide -->
                <div class="slide no-events-slide">
                  <i class="fas fa-calendar-times"></i>
                  <h3>No Upcoming Activities</h3>
                  <p>Check back later for new events and programs in your barangay!</p>
                </div>
              
              @elseif($item['type'] == 'event')
                <!-- Event Slide -->
                @php
                  $event = $item['data'];
                  $eventDate = \Carbon\Carbon::parse($event['event_date']);
                  $eventMonth = $eventDate->format('M');
                  $eventDay = $eventDate->format('d');
                  
                  // Get image URL or use default
                  $imageUrl = $event['image'] ? asset('storage/' . $event['image']) : asset('images/default-event.jpg');
                  
                  // Truncate description
                  $description = $event['description'] ?? 'No description available.';
                  if(strlen($description) > 100) {
                    $description = substr($description, 0, 100) . '...';
                  }
                  
                  // Format time
                  $time = $event['event_time'] ? \Carbon\Carbon::parse($event['event_time'])->format('g:i A') : 'TBA';
                @endphp
                
                <div class="slide event-slide">
                  <div class="left">
                    <div class="date">
                      <span class="month">{{ $eventMonth }}</span>
                      <span class="day">{{ $eventDay }}</span>
                    </div>
                    <div class="event-info">
                      <span class="activity-type event">EVENT</span>
                      <h4>{{ $event['title'] }}</h4>
                      <div class="details">
                        <i class="fas fa-clock"></i> {{ $time }}<br>
                        <i class="fas fa-map-marker-alt"></i> {{ $event['location'] }}
                      </div>
                      <p class="description">{{ $description }}</p>
                    </div>
                  </div>
                  <div class="event-banner" style="background-image: url('{{ $imageUrl }}');"></div>
                </div>
              
              @elseif($item['type'] == 'program')
                <!-- Program Slide -->
                @php
                  $program = $item['data'];
                  $programDate = \Carbon\Carbon::parse($program['event_date']);
                  $programMonth = $programDate->format('M');
                  $programDay = $programDate->format('d');
                  
                  // Get image URL or use default
                  $imageUrl = $program['display_image'] ? asset('storage/' . $program['display_image']) : asset('images/default-program.jpg');
                  
                  // Truncate description
                  $description = $program['description'] ?? 'No description available.';
                  if(strlen($description) > 100) {
                    $description = substr($description, 0, 100) . '...';
                  }
                  
                  // Format time
                  $time = $program['event_time'] ? \Carbon\Carbon::parse($program['event_time'])->format('g:i A') : 'TBA';
                  
                  // Registration type
                  $regType = $program['registration_type'] == 'create' ? 'Registration Open' : 'External Link';
                @endphp
                
                <div class="slide event-slide">
                  <div class="left">
                    <div class="date">
                      <span class="month">{{ $programMonth }}</span>
                      <span class="day">{{ $programDay }}</span>
                    </div>
                    <div class="event-info">
                      <span class="activity-type program">PROGRAM</span>
                      <h4>{{ $program['title'] }}</h4>
                      <div class="details">
                        <i class="fas fa-clock"></i> {{ $time }}<br>
                        <i class="fas fa-map-marker-alt"></i> {{ $program['location'] }}
                      </div>
                      <p class="description">{{ $description }}</p>
                    </div>
                  </div>
                  <div class="event-banner" style="background-image: url('{{ $imageUrl }}');"></div>
                </div>
              @endif
            @endforeach

            <!-- Fallback if somehow no slides exist -->
            @if($sliderItems->count() == 0)
              <div class="slide welcome-slide">
                <h2>Welcome, {{ $user->given_name }}!</h2>
                <h3>Have a nice day!</h3><br>
                <p>
                  <span>KatiBayan</span> provides a platform for the youth to stay updated on SK events 
                  and programs while fostering active participation in community development.
                </p>
              </div>
              
              <div class="slide no-events-slide">
                <i class="fas fa-calendar-times"></i>
                <h3>No Upcoming Activities</h3>
                <p>Check back later for new events and programs in your barangay!</p>
              </div>
            @endif
          </div>
          
          <!-- Pagination dots -->
          <div class="dots">
            @for($i = 0; $i < $sliderItems->count(); $i++)
              <button class="{{ $i === 0 ? 'active' : '' }}"></button>
            @endfor
          </div>
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
                    @if($activitiesToEvaluate > 0)
                      You have {{ $activitiesToEvaluate }} {{ $activitiesToEvaluate == 1 ? 'activity' : 'activities' }} to evaluate.
                    @else
                      All evaluations completed!
                    @endif
                  </p>
                </div>
                <div class="icon">
                  <i data-lucide="thumbs-up"></i>
                </div>
              </div>
              <div class="progress-footer" style="--progress: {{ $totalActivities > 0 ? ($evaluatedActivities / $totalActivities * 100) : 0 }}%">
                <div class="bar">
                  <span style="width: var(--progress)"></span>
                </div>
                <small>{{ $evaluatedActivities }}/{{ $totalActivities }}</small>
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
          <div class="announcements" id="announcementsList">
            @forelse ($announcements as $announcement)
              @php
                $isCertificateSchedule = optional($announcement)->type == 'certificate_schedule';
                preg_match("/'([^']+)'/", optional($announcement)->message, $matches);
                $eventTitleFromMessage = $matches[1] ?? null;
                $announcementId = optional($announcement)->id;
                $relatedEventId = null; 
              @endphp

              <div class="card {{ $isCertificateSchedule ? 'certificate-schedule-announcement' : '' }}"
                   @if($isCertificateSchedule && $announcementId)
                     data-announcement-id="{{ $announcementId }}"
                   @endif
                   style="{{ $isCertificateSchedule ? 'cursor: pointer;' : '' }}">
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

  // ============================================
  // CUSTOM MODAL FUNCTIONS (Alert, Confirm, Prompt)
  // ============================================

  let currentAlertResolve = null;
  let currentConfirmResolve = null;
  let currentPromptResolve = null;

  // Custom Alert Function
  window.showCustomAlert = function(message, title = 'Alert', icon = 'info', callback = null) {
    const modal = document.getElementById('customAlertModal');
    const modalTitle = document.getElementById('alertModalTitle');
    const modalMessage = document.getElementById('alertModalMessage');
    const modalIcon = document.getElementById('alertModalIcon');
    const okBtn = document.getElementById('alertModalOK');

    // Set modal content
    modalTitle.textContent = title;
    modalMessage.textContent = message;

    // Set icon
    const iconElement = modalIcon.querySelector('i');
    iconElement.className = '';
    if (icon === 'warning') {
      iconElement.className = 'fas fa-exclamation-triangle';
      modalIcon.className = 'alert-modal-icon warning';
    } else if (icon === 'error') {
      iconElement.className = 'fas fa-times-circle';
      modalIcon.className = 'alert-modal-icon error';
    } else if (icon === 'success') {
      iconElement.className = 'fas fa-check-circle';
      modalIcon.className = 'alert-modal-icon success';
    } else {
      iconElement.className = 'fas fa-info-circle';
      modalIcon.className = 'alert-modal-icon info';
    }

    // Show modal
    modal.classList.add('active');

    // Return promise
    return new Promise((resolve) => {
      currentAlertResolve = resolve;

      const handleClose = () => {
        modal.classList.remove('active');
        okBtn.removeEventListener('click', handleClose);
        modal.removeEventListener('click', handleOutsideClick);
        if (currentAlertResolve) {
          currentAlertResolve();
          currentAlertResolve = null;
        }
        if (callback) callback();
      };

      const handleOutsideClick = (e) => {
        if (e.target === modal) {
          handleClose();
        }
      };

      okBtn.addEventListener('click', handleClose);
      modal.addEventListener('click', handleOutsideClick);
    });
  };

  // Custom Confirm Function
  window.showCustomConfirm = function(message, title = 'Confirmation', icon = 'warning', callback = null) {
    const modal = document.getElementById('customConfirmModal');
    const modalTitle = document.getElementById('confirmModalTitle');
    const modalMessage = document.getElementById('confirmModalMessage');
    const modalIcon = document.getElementById('confirmModalIcon');
    const okBtn = document.getElementById('confirmModalOK');
    const cancelBtn = document.getElementById('confirmModalCancel');

    // Set modal content
    modalTitle.textContent = title;
    modalMessage.textContent = message;

    // Set icon
    const iconElement = modalIcon.querySelector('i');
    iconElement.className = '';
    if (icon === 'warning') {
      iconElement.className = 'fas fa-exclamation-triangle';
      modalIcon.className = 'confirmation-modal-icon warning';
    } else if (icon === 'question') {
      iconElement.className = 'fas fa-question-circle';
      modalIcon.className = 'confirmation-modal-icon info';
    } else {
      iconElement.className = 'fas fa-question-circle';
      modalIcon.className = 'confirmation-modal-icon info';
    }

    // Show modal
    modal.classList.add('active');

    // Return promise
    return new Promise((resolve) => {
      currentConfirmResolve = resolve;

      const handleConfirm = () => {
        modal.classList.remove('active');
        okBtn.removeEventListener('click', handleConfirm);
        cancelBtn.removeEventListener('click', handleCancel);
        modal.removeEventListener('click', handleOutsideClick);
        if (currentConfirmResolve) {
          currentConfirmResolve(true);
          currentConfirmResolve = null;
        }
        if (callback) callback(true);
      };

      const handleCancel = () => {
        modal.classList.remove('active');
        okBtn.removeEventListener('click', handleConfirm);
        cancelBtn.removeEventListener('click', handleCancel);
        modal.removeEventListener('click', handleOutsideClick);
        if (currentConfirmResolve) {
          currentConfirmResolve(false);
          currentConfirmResolve = null;
        }
        if (callback) callback(false);
      };

      const handleOutsideClick = (e) => {
        if (e.target === modal) {
          handleCancel();
        }
      };

      okBtn.addEventListener('click', handleConfirm);
      cancelBtn.addEventListener('click', handleCancel);
      modal.addEventListener('click', handleOutsideClick);
    });
  };

  // Custom Prompt Function
  window.showCustomPrompt = function(message, defaultValue = '', title = 'Input Required', callback = null) {
    const modal = document.getElementById('customPromptModal');
    const modalTitle = document.getElementById('promptModalTitle');
    const modalMessage = document.getElementById('promptModalMessage');
    const modalInput = document.getElementById('promptModalInput');
    const okBtn = document.getElementById('promptModalOK');
    const cancelBtn = document.getElementById('promptModalCancel');

    // Set modal content
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modalInput.value = defaultValue;

    // Show modal and focus input
    modal.classList.add('active');
    setTimeout(() => {
      modalInput.focus();
      modalInput.select();
    }, 100);

    // Return promise
    return new Promise((resolve) => {
      currentPromptResolve = resolve;

      const handleConfirm = () => {
        const value = modalInput.value;
        modal.classList.remove('active');
        okBtn.removeEventListener('click', handleConfirm);
        cancelBtn.removeEventListener('click', handleCancel);
        modalInput.removeEventListener('keypress', handleKeyPress);
        modal.removeEventListener('click', handleOutsideClick);
        if (currentPromptResolve) {
          currentPromptResolve(value);
          currentPromptResolve = null;
        }
        if (callback) callback(value);
      };

      const handleCancel = () => {
        modal.classList.remove('active');
        okBtn.removeEventListener('click', handleConfirm);
        cancelBtn.removeEventListener('click', handleCancel);
        modalInput.removeEventListener('keypress', handleKeyPress);
        modal.removeEventListener('click', handleOutsideClick);
        if (currentPromptResolve) {
          currentPromptResolve(null);
          currentPromptResolve = null;
        }
        if (callback) callback(null);
      };

      const handleKeyPress = (e) => {
        if (e.key === 'Enter') {
          handleConfirm();
        }
      };

      const handleOutsideClick = (e) => {
        if (e.target === modal) {
          handleCancel();
        }
      };

      okBtn.addEventListener('click', handleConfirm);
      cancelBtn.addEventListener('click', handleCancel);
      modalInput.addEventListener('keypress', handleKeyPress);
      modal.addEventListener('click', handleOutsideClick);
    });
  };

  // Logout Confirmation Function
  function showLogoutConfirmation() {
    showCustomConfirm(
      'Are you sure you want to logout?',
      'Confirm Logout',
      'warning'
    ).then((confirmed) => {
      if (confirmed) {
        document.getElementById('logout-form').submit();
      }
    });
  }

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

  // Initialize Topbar - UPDATED NOTIFICATION HANDLING
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

      // Handle evaluation notification clicks - UPDATED
      notifWrapper.querySelectorAll('.notif-link[data-event-id], .notif-link[data-program-id]').forEach(notification => {
        notification.addEventListener('click', function(e) {
          // Don't prevent default for database notifications with data-id
          if (this.hasAttribute('data-id')) {
            return;
          }
          
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

  // Initialize Calendar
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
      "2025-01-01", "2025-04-09", "2025-04-17", "2025-04-18", "2025-05-01",
      "2025-06-06", "2025-06-12", "2025-08-25", "2025-11-30", "2025-12-25",
      "2025-12-30"
    ];
    let today = new Date();
    let currentView = new Date();

    function renderCalendar(baseDate) {
      daysContainer.innerHTML = "";

      const startOfWeek = new Date(baseDate);
      startOfWeek.setDate(baseDate.getDate() - (baseDate.getDay() === 0 ? 6 : baseDate.getDay() - 1));

      const middleDay = new Date(startOfWeek);
      middleDay.setDate(startOfWeek.getDate() + 3);
      header.textContent = middleDay.toLocaleDateString("en-US", {
        month: "long",
        year: "numeric"
      });

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

    // FIRST: Clear any existing dots
    dotsContainer.innerHTML = '';

    // Create dots ONLY for existing slides
    for (let i = 0; i < slides.length; i++) {
      const dot = document.createElement("button");
      if (i === 0) dot.classList.add("active");
      dot.addEventListener("click", () => {
        currentIndex = i;
        updateSlide();
        restartAuto();
      });
      dotsContainer.appendChild(dot);
      dots.push(dot);
    }

    function updateSlide() {
      // (FIX) Recalculate width on update, important for resize
      const containerWidth = welcomeSection.getBoundingClientRect().width;
      if (containerWidth === 0) return; // Avoid error if hidden
      slideTrack.style.transform = `translateX(-${currentIndex * containerWidth}px)`;

      dots.forEach(dot => dot.classList.remove("active"));
      if (dots[currentIndex]) dots[currentIndex].classList.add("active");
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
        showCustomAlert('Please provide a rating', 'Validation Error', 'warning');
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
            showCustomAlert('Evaluation submitted successfully!', 'Success', 'success');
            closeModal();
            setTimeout(() => location.reload(), 1500);
          } else {
            let errorMsg = data.message || 'Submission failed.';
            if (data.errors) {
              errorMsg += '\n' + Object.values(data.errors).join('\n');
            }
            showCustomAlert(errorMsg, 'Error', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showCustomAlert(error.message || 'An error occurred while submitting.', 'Error', 'error');
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
            showCustomAlert(error.message || 'An error occurred. Please try again.', 'Error', 'error');
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

  // UPDATED: Mark as Read Function
  function initMarkAsRead() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
      console.error('CSRF token not found.');
      return;
    }

    // Handle database notifications (not evaluation notifications)
    document.querySelectorAll('.notif-link[data-id]').forEach(link => {
      link.addEventListener('click', function(e) {
        // Don't prevent default for evaluation notifications
        if (this.hasAttribute('data-event-id') || this.hasAttribute('data-program-id')) {
          return;
        }
        
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

    // Handle evaluation notification clicks (system-generated)
    document.querySelectorAll('.notif-link[data-event-id], .notif-link[data-program-id]').forEach(link => {
      link.addEventListener('click', function(e) {
        // These should navigate directly to evaluation page
        // No need for AJAX mark as read since they're system-generated
        // and not stored in database notifications table
      });
    });
  }

  function initCertificateModal() {
    const announcementCards = document.querySelectorAll('.certificate-schedule-announcement');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const redirectUrl = '{{ route('certificatepage') }}';

    announcementCards.forEach(card => {
      card.addEventListener('click', (event) => {
        event.preventDefault();
        const annId = card.dataset.announcementId;

        if (!annId) {
          window.location.href = redirectUrl;
          return;
        }

        fetch(`/notifications/mark-as-read/${annId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              id: annId
            })
          })
          .catch(error => {
            console.error('Error marking notification as read:', error);
          })
          .finally(() => {
            window.location.href = redirectUrl;
          });
      });
    });
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
    initCalendar();
    initWelcomeSlider();
    initFeedbackModal();
    initMarkAsRead();
    initCertificateModal();
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
            showCustomAlert('Role set successfully! Redirecting...', 'Success', 'success');
            setTimeout(() => {
              window.location.reload();
            }, 1500);
          } else {
            showCustomAlert(data.message || 'Failed to set role.', 'Error', 'error');
            btn.textContent = originalText;
            btn.disabled = false;
          }
        } catch (error) {
          console.error('Error:', error);
          showCustomAlert('Something went wrong. Please try again.', 'Error', 'error');
          btn.textContent = originalText;
          btn.disabled = false;
        }
      });
    }
  });
  </script>
</body>
</html>