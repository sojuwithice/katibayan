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
  <title>KatiBayan - Events & Programs</title>
  <link rel="stylesheet" href="{{ asset('css/eventpage.css') }}">
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

      <a href="{{ route('eventpage') }}" class="active events-link">
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
        <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="Logo">
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
        

        <!-- Notification Wrapper - UPDATED TO EXACTLY MATCH DASHBOARD -->
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
            <!-- SK Role Button -->
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
            
            <!-- Hidden Logout Form -->
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

<!-- Feedback Success Modal -->
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

<!-- Registration Success Modal -->
<div id="registrationSuccessModal" class="modal-overlay" style="display: none;">
  <div class="modal-content" style="max-width: 500px; text-align: center; padding: 40px 30px;">
    <div class="success-icon" style="background: #4CAF50; width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
      <i class="fas fa-check-circle" style="color: white; font-size: 36px;"></i>
    </div>
    <h2 style="color: #4CAF50; margin-bottom: 10px;">Registration Successful!</h2>
    <div class="reference-id" style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin: 20px 0; font-family: monospace; font-size: 16px; font-weight: bold; color: #333;">
      Reference ID: <span id="referenceIdDisplay"></span>
    </div>
    <p style="color: #666; margin-bottom: 25px; line-height: 1.6;">
      You have successfully registered for the program. Your registration has been confirmed and saved in our system.
    </p>
    <div class="modal-actions" style="display: flex; gap: 10px; justify-content: center;">
      <button id="closeRegistrationSuccess" class="btn" style="background: #4CAF50; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.3s;">
        OK
      </button>
      <a href="#" id="viewMyRegistrations" class="btn" style="background: #3C87C4; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; transition: background 0.3s;">
        View My Registrations
      </a>
    </div>
  </div>
</div>

    <!-- Events and Programs -->
    <section class="events-section">
      <!-- LEFT -->
      <div class="events-left">
        <h2>Events and Programs</h2>
        <p>This page serves as your guide to upcoming events and programs designed to empower the youth, foster engagement, and build stronger communities.</p>
        
        <!-- Barangay Info -->
        @if($user->barangay)
          <div class="barangay-info">
            <i class="fas fa-map-marker-alt"></i>
            <span>Showing events and programs from your barangay: <strong>{{ $user->barangay->name }}</strong></span>
          </div>
        @endif
      </div>

      <!-- RIGHT -->
      <div class="events-right">
        <h3>Today's Agenda 
          <i class="fa-solid fa-thumbtack"></i>
        </h3>

        @php
          use Carbon\Carbon;
          use Illuminate\Support\Facades\Storage;
          $today = $today ?? Carbon::today();
          $currentDateTime = $currentDateTime ?? Carbon::now();
          
          $validTodayEvents = $todayEvents->filter(function($event) use ($today) {
              if (!$event->is_launched) return false;
              $eventDate = $event->event_date instanceof Carbon 
                ? $event->event_date 
                : Carbon::parse($event->event_date);
              return $eventDate->isSameDay($today);
          });

          $todayPrograms = $programs->filter(function($program) use ($today) {
              $programDate = $program->event_date instanceof Carbon 
                ? $program->event_date 
                : Carbon::parse($program->event_date);
              return $programDate->isSameDay($today);
          });
        @endphp

        @if($validTodayEvents->count() > 0 || $todayPrograms->count() > 0)
          @foreach($validTodayEvents as $event)
            <div class="agenda-card">
              <div class="agenda-banner">
                <div class="agenda-date">
                  @php
                    $eventDate = $event->event_date instanceof Carbon 
                      ? $event->event_date 
                      : Carbon::parse($event->event_date);
                  @endphp
                  <span class="month">{{ $eventDate->format('M') }}.</span>
                  <span class="day">{{ $eventDate->format('d') }}</span>
                  <span class="year">{{ $eventDate->format('Y') }}</span>
                </div>
                @if($event->image && Storage::disk('public')->exists($event->image))
                  <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                @else
                  <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9rPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image">
                @endif
              </div>
              <div class="agenda-content">
                <h4>{{ $event->title }}</h4>
                <span class="agenda-type event-type">Event</span>
                <p class="agenda-time">
                  <i class="fas fa-clock"></i>
                  {{ $event->event_time ? Carbon::parse($event->event_time)->format('g:i A') : 'All Day' }}
                </p>
              </div>
              <div class="agenda-actions">
                <a href="#" class="details-btn view-event-details" data-event-id="{{ $event->id }}">
                  See full details 
                  <span class="icon-circle">
                    <i class="fa-solid fa-chevron-right"></i>
                  </span>
                </a>
                
                @php
                  $eventDate = $event->event_date instanceof Carbon 
                    ? $event->event_date 
                    : Carbon::parse($event->event_date);
                  
                  $eventDateTime = $eventDate->copy();
                  if ($event->event_time) {
                      try {
                          $eventTime = Carbon::parse($event->event_time);
                          $eventDateTime->setTime($eventTime->hour, $eventTime->minute, $eventTime->second);
                      } catch (\Exception $e) {
                          $eventDateTime->endOfDay();
                      }
                  } else {
                      $eventDateTime->endOfDay();
                  }
                  
                  $hasEventEnded = $eventDateTime->lt($currentDateTime);
                @endphp
                
                @if($hasEventEnded)
                  <span class="attend-btn ended">Event Ended</span>
                @else
                  <a href="{{ route('attendancepage') }}?event_id={{ $event->id }}" class="attend-btn">Attend Now</a>
                @endif
              </div>
            </div>
          @endforeach

          @foreach($todayPrograms as $program)
            <div class="agenda-card">
              <div class="agenda-banner">
                <div class="agenda-date">
                  @php
                    $programDate = $program->event_date instanceof Carbon 
                      ? $program->event_date 
                      : Carbon::parse($program->event_date);
                  @endphp
                  <span class="month">{{ $programDate->format('M') }}.</span>
                  <span class="day">{{ $programDate->format('d') }}</span>
                  <span class="year">{{ $programDate->format('Y') }}</span>
                </div>
                @if($program->display_image && Storage::disk('public')->exists($program->display_image))
                  <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9rPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                @else
                  <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9rPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg==" alt="Program Image">
                @endif
              </div>
              <div class="agenda-content">
                <h4>{{ $program->title }}</h4>
                <span class="agenda-type program-type">Program</span>
                <p class="agenda-time">
                  <i class="fas fa-clock"></i>
                  {{ $program->event_time ? Carbon::parse($program->event_time)->format('g:i A') : 'All Day' }}
                </p>
              </div>
              <div class="agenda-actions">
                <a href="#" class="details-btn view-program-details" data-program-id="{{ $program->id }}">
                  See full details 
                  <span class="icon-circle">
                    <i class="fa-solid fa-chevron-right"></i>
                  </span>
                </a>
                
                @php
                  $programDate = $program->event_date instanceof Carbon 
                    ? $program->event_date 
                    : Carbon::parse($program->event_date);
                  
                  $programDateTime = $programDate->copy();
                  if ($program->event_time) {
                      try {
                          $programTime = Carbon::parse($program->event_time);
                          $programDateTime->setTime($programTime->hour, $programTime->minute, $programTime->second);
                      } catch (\Exception $e) {
                          $programDateTime->endOfDay();
                      }
                  } else {
                      $programDateTime->endOfDay();
                  }
                  
                  $hasProgramEnded = $programDateTime->lt($currentDateTime);
                @endphp
                
                @if($hasProgramEnded)
                  <span class="attend-btn ended">Program Ended</span>
                @else
                  @if($program->registration_type === 'link' && $program->link_source)
                    <a href="{{ $program->link_source }}" target="_blank" class="attend-btn">Register Now</a>
                  @elseif($program->registration_type === 'create')
                    <a href="#" class="attend-btn program-register" data-program-id="{{ $program->id }}">Register Now</a>
                  @else
                    <a href="#" class="attend-btn">Learn More</a>
                  @endif
                @endif
              </div>
            </div>
          @endforeach
        @else
          <div class="agenda-card no-events">
            <div class="agenda-banner">
              <div class="no-events-content">
                <i class="fas fa-calendar-times"></i>
                <p>No events or programs scheduled for today in your barangay</p>
              </div>
            </div>
          </div>
        @endif
      </div>
    </section>

    <!-- Events Section -->
    <section class="programs-section">
      <div class="programs-bar">
        <h3>Events</h3>
        <a href="#" class="see-all" id="seeAllEvents">See All</a>
      </div>

      <div class="programs-scroll">
        <div class="programs-container">
          @php
            $launchedEvents = $events->filter(function($event) use ($currentDateTime) {
                if (!$event->is_launched) return false;
                $eventDate = $event->event_date instanceof Carbon 
                  ? $event->event_date 
                  : Carbon::parse($event->event_date);
                if ($event->event_time) {
                    try {
                        $eventTime = Carbon::parse($event->event_time);
                        $eventDateTime = Carbon::create(
                            $eventDate->year,
                            $eventDate->month,
                            $eventDate->day,
                            $eventTime->hour,
                            $eventTime->minute,
                            $eventTime->second
                        );
                    } catch (\Exception $e) {
                        $eventDateTime = $eventDate->endOfDay();
                    }
                } else {
                    $eventDateTime = $eventDate->endOfDay();
                }
                return $eventDateTime->gt($currentDateTime);
            });
          @endphp

          @if($launchedEvents->count() > 0)
            @foreach($launchedEvents->take(3) as $event)
              <article class="program-card" data-category="{{ $event->category }}">
                <div class="program-media">
                  @if($event->image && Storage::disk('public')->exists($event->image))
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                  @else
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9rPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image">
                  @endif
                  <a href="{{ route('attendancepage') }}?event_id={{ $event->id }}" class="register-btn">REGISTER NOW!</a>
                </div>
                <div class="program-body">
                  <p class="program-title">{{ $event->title }}</p>
                  <p class="program-desc">
                    {{ $event->description ? Str::limit($event->description, 100) : 'No description available.' }}
                  </p>
                  <div class="program-actions">
                    <a class="read-more view-event-details" href="#" data-event-id="{{ $event->id }}">
                      READ MORE 
                      <span class="circle-btn">
                        <i class="fas fa-chevron-right"></i>
                      </span>
                    </a>
                  </div>
                </div>
              </article>
            @endforeach
          @else
            <div class="no-events-message">
              <i class="fas fa-calendar-times"></i>
              <p>No launched events available in your barangay at the moment.</p>
            </div>
          @endif
        </div>
      </div>
    </section>

    <!-- Programs Section -->
    <section class="programs-section">
      <div class="programs-bar">
        <h3>Programs</h3>
        <a href="#" class="see-all" id="seeAllPrograms">See All</a>
      </div>

      <div class="programs-scroll">
        <div class="programs-container">
          @php
            $upcomingPrograms = $programs->filter(function($program) use ($currentDateTime) {
                $programDate = $program->event_date instanceof Carbon 
                  ? $program->event_date 
                  : Carbon::parse($program->event_date);
                if ($program->event_time) {
                    try {
                        $programTime = Carbon::parse($program->event_time);
                        $programDateTime = Carbon::create(
                            $programDate->year,
                            $programDate->month,
                            $programDate->day,
                            $programTime->hour,
                            $programTime->minute,
                            $programTime->second
                        );
                    } catch (\Exception $e) {
                        $programDateTime = $programDate->endOfDay();
                    }
                } else {
                    $programDateTime = $programDate->endOfDay();
                }
                return $programDateTime->gt($currentDateTime);
            });
          @endphp

          @if($upcomingPrograms->count() > 0)
            @foreach($upcomingPrograms->take(3) as $program)
              <article class="program-card" data-category="{{ $program->category }}">
                <div class="program-media">
                  @if($program->display_image && Storage::disk('public')->exists($program->display_image))
                    <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                  @else
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Iy93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5BY3Rpdml0eSBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Program Image">
                  @endif
                  
                  @if($program->registration_type === 'link' && $program->link_source)
                    <a href="{{ $program->link_source }}" target="_blank" class="register-btn">REGISTER NOW!</a>
                  @elseif($program->registration_type === 'create')
                    <a href="#" class="register-btn program-register" data-program-id="{{ $program->id }}">REGISTER NOW!</a>
                  @else
                    <a href="#" class="register-btn">LEARN MORE</a>
                  @endif
                </div>
                <div class="program-body">
                  <p class="program-title">{{ $program->title }}</p>
                  <p class="program-desc">
                    {{ $program->description ? Str::limit($program->description, 100) : 'No description available.' }}
                  </p>
                  <div class="program-meta">
                    <span class="program-category-badge">{{ ucfirst($program->category) }}</span>
                    <span class="program-date">
                      <i class="fas fa-calendar"></i>
                      {{ Carbon::parse($program->event_date)->format('M d, Y') }}
                    </span>
                  </div>
                  <div class="program-actions">
                    <a class="read-more view-program-details" href="#" data-program-id="{{ $program->id }}">
                      READ MORE 
                      <span class="circle-btn">
                        <i class="fas fa-chevron-right"></i>
                      </span>
                    </a>
                  </div>
                </div>
              </article>
            @endforeach
          @else
            <div class="no-events-message">
              <i class="fas fa-calendar-plus"></i>
              <p>No upcoming programs available in your barangay at the moment.</p>
            </div>
          @endif
        </div>
      </div>
    </section>

    <!-- All Events & Programs Modal -->
    <div id="allEventsModal" class="modal-overlay">
      <div class="modal-content" style="max-width: 1000px; max-height: 90vh; overflow-y: auto;">
        <span class="close-btn" id="closeAllEventsModal">&times;</span>
        
        <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, #114B8C 100%);">
          <h2>All Events</h2>
          <div class="modal-tabs">
            <button class="modal-tab active" data-tab="past-events">Past Events</button>
            <button class="modal-tab" data-tab="attended-events">Events I Attended</button>
          </div>
        </div>
        
        <div class="modal-body" style="padding: 20px;">
          <!-- Past Events Tab -->
          <div class="modal-tab-content active" id="past-events-tab">
            <h3 style="margin-bottom: 20px; color: var(--text-color);">All Past Events</h3>
            <div class="events-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
              @php
                $pastEvents = $events->filter(function($event) use ($currentDateTime) {
                    if (!$event->is_launched) return false; // Only show launched events
                    $eventDate = $event->event_date instanceof Carbon 
                      ? $event->event_date 
                      : Carbon::parse($event->event_date);
                    if ($event->event_time) {
                        try {
                            $eventTime = Carbon::parse($event->event_time);
                            $eventDateTime = Carbon::create(
                                $eventDate->year,
                                $eventDate->month,
                                $eventDate->day,
                                $eventTime->hour,
                                $eventTime->minute,
                                $eventTime->second
                            );
                        } catch (\Exception $e) {
                            $eventDateTime = $eventDate->endOfDay();
                        }
                    } else {
                        $eventDateTime = $eventDate->endOfDay();
                    }
                    return $eventDateTime->lt($currentDateTime);
                });
              @endphp
              
              @if($pastEvents->count() > 0)
                @foreach($pastEvents as $event)
                  @php
                    $attended = false;
                    $hasCertificate = false;
                    $evaluated = false;
                    $evaluationLink = '#';
                    
                    if ($event->attendances) {
                      $attendance = $event->attendances->where('user_id', $user->id)->first();
                      $attended = $attendance ? true : false;
                      
                      if ($attended) {
                        $hasCertificate = $attendance->certificate_generated ?? false;
                        
                        // Check if evaluated
                        $evaluated = $event->evaluations && $event->evaluations->contains('user_id', $user->id);
                        $evaluationLink = route('evaluation.show', $event->id);
                      }
                    }
                  @endphp
                  <div class="past-event-card" style="background: var(--card-bg); border-radius: 12px; padding: 15px; border: 1px solid var(--border-color); position: relative; filter: grayscale(0.7); opacity: 0.8;">
                    @if($attended)
                      <div class="attended-badge" style="position: absolute; top: 10px; right: 10px; background: var(--accent-color); color: #000; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; z-index: 2;">
                        <i class="fas fa-check-circle"></i> Attended
                      </div>
                    @endif
                    
                    <div class="event-image" style="width: 100%; height: 150px; overflow: hidden; border-radius: 8px; margin-bottom: 10px; position: relative;">
                      @if($event->image && Storage::disk('public')->exists($event->image))
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(0.7);">
                      @else
                        <div style="width: 100%; height: 100%; background: var(--bg-color); display: flex; align-items: center; justify-content: center;">
                          <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--text-color); opacity: 0.5;"></i>
                        </div>
                      @endif
                    </div>
                    
                    <h4 style="margin: 10px 0 5px 0; color: var(--text-color);">{{ $event->title }}</h4>
                    <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 5px;">
                      <i class="fas fa-calendar"></i> 
                      {{ $event->event_date ? Carbon::parse($event->event_date)->format('M d, Y') : 'Date not specified' }}
                    </p>
                    <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 10px;">
                      <i class="fas fa-clock"></i> 
                      {{ $event->event_time ? Carbon::parse($event->event_time)->format('h:i A') : 'Time not specified' }}
                    </p>
                    
                    <div class="event-actions" style="display: flex; gap: 10px; margin-top: 10px;">
                      <!-- View Details Button (Always show for past events) -->
                      <a href="#" class="view-event-details" data-event-id="{{ $event->id }}" style="text-decoration: none; background: var(--secondary-color); color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                      </a>
                      
                      <!-- Certificate Button (Only if attended and has certificate) -->
                      @if($attended && $hasCertificate)
                        <a href="{{ route('certificatepage') }}" style="text-decoration: none; background: #28a745; color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                          <i class="fas fa-certificate"></i> Certificate
                        </a>
                      @endif
                      
                      <!-- Evaluate Button (Only if attended and not evaluated) -->
                      @if($attended && !$evaluated)
                        <a href="{{ $evaluationLink }}" style="text-decoration: none; background: var(--primary-color); color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                          <i class="fas fa-star"></i> Evaluate
                        </a>
                      @elseif($attended && $evaluated)
                        <span style="background: #28a745; color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                          <i class="fas fa-check"></i> Evaluated
                        </span>
                      @endif
                    </div>
                  </div>
                @endforeach
              @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-color); opacity: 0.7;">
                  <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 20px;"></i>
                  <p>No past events found.</p>
                </div>
              @endif
            </div>
          </div>
          
          <!-- Attended Events Tab -->
          <div class="modal-tab-content" id="attended-events-tab">
            <h3 style="margin-bottom: 20px; color: var(--text-color);">Events I've Attended</h3>
            <div class="events-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
              @php
                $attendedEvents = $events->filter(function($event) use ($user) {
                    return $event->attendances && $event->attendances->contains('user_id', $user->id);
                });
              @endphp
              
              @if($attendedEvents->count() > 0)
                @foreach($attendedEvents as $event)
                  @php
                    $hasCertificate = false;
                    $evaluated = false;
                    $evaluationLink = '#';
                    
                    $attendance = $event->attendances->where('user_id', $user->id)->first();
                    if ($attendance) {
                      $hasCertificate = $attendance->certificate_generated ?? false;
                      $evaluated = $event->evaluations && $event->evaluations->contains('user_id', $user->id);
                      $evaluationLink = route('evaluation.show', $event->id);
                    }
                    
                    // Check if event has ended
                    $eventDate = $event->event_date instanceof Carbon 
                      ? $event->event_date 
                      : Carbon::parse($event->event_date);
                    
                    $eventDateTime = $eventDate->copy();
                    if ($event->event_time) {
                        try {
                            $eventTime = Carbon::parse($event->event_time);
                            $eventDateTime->setTime($eventTime->hour, $eventTime->minute, $eventTime->second);
                        } catch (\Exception $e) {
                            $eventDateTime->endOfDay();
                        }
                    } else {
                        $eventDateTime->endOfDay();
                    }
                    
                    $hasEventEnded = $eventDateTime->lt($currentDateTime);
                  @endphp
                  <div class="attended-event-card" style="background: var(--card-bg); border-radius: 12px; padding: 15px; border: 2px solid var(--accent-color); position: relative;">
                    <div class="attended-badge" style="position: absolute; top: 10px; right: 10px; background: var(--accent-color); color: #000; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; z-index: 2;">
                      <i class="fas fa-check-circle"></i> Attended
                    </div>
                    
                    <div class="event-image" style="width: 100%; height: 150px; overflow: hidden; border-radius: 8px; margin-bottom: 10px; position: relative;">
                      @if($event->image && Storage::disk('public')->exists($event->image))
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover; @if($hasEventEnded) filter: grayscale(0.5); @endif">
                      @else
                        <div style="width: 100%; height: 100%; background: var(--bg-color); display: flex; align-items: center; justify-content: center;">
                          <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--text-color); opacity: 0.5;"></i>
                        </div>
                      @endif
                    </div>
                    
                    <h4 style="margin: 10px 0 5px 0; color: var(--text-color);">{{ $event->title }}</h4>
                    <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 5px;">
                      <i class="fas fa-calendar"></i> 
                      {{ $event->event_date ? Carbon::parse($event->event_date)->format('M d, Y') : 'Date not specified' }}
                    </p>
                    <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 10px;">
                      <i class="fas fa-clock"></i> 
                      {{ $event->event_time ? Carbon::parse($event->event_time)->format('h:i A') : 'Time not specified' }}
                    </p>
                    
                    @if($attendance && $attendance->attended_at)
                      <p style="font-size: 0.85rem; color: var(--primary-color); margin-bottom: 10px;">
                        <i class="fas fa-user-check"></i> 
                        Attended on: {{ Carbon::parse($attendance->attended_at)->format('M d, Y h:i A') }}
                      </p>
                    @endif
                    
                    <div class="event-actions" style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;">
                      <a href="#" class="view-event-details" data-event-id="{{ $event->id }}" style="text-decoration: none; background: var(--secondary-color); color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                      </a>
                      
                      @if($hasCertificate)
                        <a href="{{ route('certificatepage') }}" style="text-decoration: none; background: #28a745; color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                          <i class="fas fa-certificate"></i> Certificate
                        </a>
                      @endif
                      
                      @if(!$evaluated)
                        <a href="{{ $evaluationLink }}" style="text-decoration: none; background: var(--primary-color); color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                          <i class="fas fa-star"></i> Evaluate
                        </a>
                      @else
                        <span style="background: #28a745; color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                          <i class="fas fa-check"></i> Evaluated
                        </span>
                      @endif
                    </div>
                  </div>
                @endforeach
              @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-color); opacity: 0.7;">
                  <i class="fas fa-calendar-check" style="font-size: 3rem; margin-bottom: 20px;"></i>
                  <p>You haven't attended any events yet.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- All Programs Modal -->
    <div id="allProgramsModal" class="modal-overlay">
      <div class="modal-content" style="max-width: 1000px; max-height: 90vh; overflow-y: auto;">
        <span class="close-btn" id="closeAllProgramsModal">&times;</span>
        
        <div class="modal-header" style="background: linear-gradient(135deg, #FFB703 0%, #FB8500 100%);">
          <h2>All Programs</h2>
          <div class="modal-tabs">
            <button class="modal-tab active" data-tab="past-programs">Past Programs</button>
            <button class="modal-tab" data-tab="registered-programs">Programs I Registered</button>
          </div>
        </div>
        
        <div class="modal-body" style="padding: 20px;">
          <!-- Past Programs Tab -->
          <div class="modal-tab-content active" id="past-programs-tab">
            <h3 style="margin-bottom: 20px; color: var(--text-color);">All Past Programs</h3>
            <div class="programs-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
              @php
                $pastPrograms = $programs->filter(function($program) use ($currentDateTime) {
                    $programDate = $program->event_date instanceof Carbon 
                      ? $program->event_date 
                      : Carbon::parse($program->event_date);
                    if ($program->event_time) {
                        try {
                            $programTime = Carbon::parse($program->event_time);
                            $programDateTime = Carbon::create(
                                $programDate->year,
                                $programDate->month,
                                $programDate->day,
                                $programTime->hour,
                                $programTime->minute,
                                $programTime->second
                            );
                        } catch (\Exception $e) {
                            $programDateTime = $programDate->endOfDay();
                        }
                    } else {
                        $programDateTime = $programDate->endOfDay();
                    }
                    return $programDateTime->lt($currentDateTime);
                });
              @endphp
              
              @if($pastPrograms->count() > 0)
                @foreach($pastPrograms as $program)
                  @php
                    $registered = false;
                    $registration = null;
                    if (isset($program->registrations)) {
                      $registration = $program->registrations->where('user_id', $user->id)->first();
                      $registered = $registration ? true : false;
                    }
                  @endphp
                  <div class="past-program-card" style="background: var(--card-bg); border-radius: 12px; padding: 15px; border: 1px solid var(--border-color); position: relative; filter: grayscale(0.7); opacity: 0.8;">
                    @if($registered)
                      <div class="registered-badge" style="position: absolute; top: 10px; right: 10px; background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; z-index: 2;">
                        <i class="fas fa-user-check"></i> Registered
                      </div>
                    @endif
                    
                    <div class="program-image" style="width: 100%; height: 150px; overflow: hidden; border-radius: 8px; margin-bottom: 10px; position: relative;">
                      @if($program->display_image && Storage::disk('public')->exists($program->display_image))
                        <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(0.7);">
                      @else
                        <div style="width: 100%; height: 100%; background: var(--bg-color); display: flex; align-items: center; justify-content: center;">
                          <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--text-color); opacity: 0.5;"></i>
                        </div>
                      @endif
                    </div>
                    
                    <h4 style="margin: 10px 0 5px 0; color: var(--text-color);">{{ $program->title }}</h4>
                    <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 5px;">
                      <i class="fas fa-calendar"></i> 
                      {{ $program->event_date ? Carbon::parse($program->event_date)->format('M d, Y') : 'Date not specified' }}
                    </p>
                    
                    @if($program->event_end_date)
                      <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 5px;">
                        <i class="fas fa-calendar-check"></i> 
                        Ends: {{ Carbon::parse($program->event_end_date)->format('M d, Y') }}
                      </p>
                    @endif
                    
                    <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 10px;">
                      <i class="fas fa-clock"></i> 
                      {{ $program->event_time ? Carbon::parse($program->event_time)->format('h:i A') : 'Time not specified' }}
                    </p>
                    
                    <div class="program-actions" style="display: flex; gap: 10px; margin-top: 10px;">
                      <a href="#" class="view-program-details" data-program-id="{{ $program->id }}" style="text-decoration: none; background: var(--secondary-color); color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                      </a>
                    </div>
                  </div>
                @endforeach
              @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-color); opacity: 0.7;">
                  <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 20px;"></i>
                  <p>No past programs found.</p>
                </div>
              @endif
            </div>
          </div>
          
          <!-- Registered Programs Tab -->
          <div class="modal-tab-content" id="registered-programs-tab">
            <h3 style="margin-bottom: 20px; color: var(--text-color);">Programs I've Registered For</h3>
            <div class="programs-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
              @php
                $registeredPrograms = $programs->filter(function($program) use ($user) {
                    return isset($program->registrations) && $program->registrations->contains('user_id', $user->id);
                });
              @endphp
              
              @if($registeredPrograms->count() > 0)
                @foreach($registeredPrograms as $program)
                  @php
                    $registration = $program->registrations->where('user_id', $user->id)->first();
                    
                    // Check if program has ended
                    $programDate = $program->event_date instanceof Carbon 
                      ? $program->event_date 
                      : Carbon::parse($program->event_date);
                    
                    $programDateTime = $programDate->copy();
                    if ($program->event_time) {
                        try {
                            $programTime = Carbon::parse($program->event_time);
                            $programDateTime->setTime($programTime->hour, $programTime->minute, $programTime->second);
                        } catch (\Exception $e) {
                            $programDateTime->endOfDay();
                        }
                    } else {
                        $programDateTime->endOfDay();
                    }
                    
                    $hasProgramEnded = $programDateTime->lt($currentDateTime);
                  @endphp
                  
                  <div class="registered-program-card" style="background: var(--card-bg); border-radius: 12px; padding: 15px; border: 2px solid var(--primary-color); position: relative;">
                    <div class="registered-badge" style="position: absolute; top: 10px; right: 10px; background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; z-index: 2;">
                      <i class="fas fa-user-check"></i> Registered
                    </div>
                    
                    <div class="program-image" style="width: 100%; height: 150px; overflow: hidden; border-radius: 8px; margin-bottom: 10px; position: relative;">
                      @if($program->display_image && Storage::disk('public')->exists($program->display_image))
                        <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" style="width: 100%; height: 100%; object-fit: cover; @if($hasProgramEnded) filter: grayscale(0.5); @endif">
                      @else
                        <div style="width: 100%; height: 100%; background: var(--bg-color); display: flex; align-items: center; justify-content: center;">
                          <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--text-color); opacity: 0.5;"></i>
                        </div>
                      @endif
                    </div>
                    
                    <h4 style="margin: 10px 0 5px 0; color: var(--text-color);">{{ $program->title }}</h4>
                    <p style="font-size: 0.9rem; color: var(--text-color); opacity: 0.7; margin-bottom: 5px;">
                      <i class="fas fa-calendar"></i> 
                      {{ $program->event_date ? Carbon::parse($program->event_date)->format('M d, Y') : 'Date not specified' }}
                    </p>
                    
                    @if($registration && $registration->created_at)
                      <p style="font-size: 0.85rem; color: var(--primary-color); margin-bottom: 10px;">
                        <i class="fas fa-user-plus"></i> 
                        Registered on: {{ Carbon::parse($registration->created_at)->format('M d, Y h:i A') }}
                      </p>
                      
                      @if($registration->reference_id)
                        <p style="font-size: 0.85rem; color: var(--text-color); margin-bottom: 10px;">
                          <i class="fas fa-id-card"></i> 
                          Reference ID: {{ $registration->reference_id }}
                        </p>
                      @endif
                    @endif
                    
                    <div class="program-actions" style="display: flex; gap: 10px; margin-top: 10px;">
                      <a href="#" class="view-program-details" data-program-id="{{ $program->id }}" style="text-decoration: none; background: var(--secondary-color); color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                      </a>
                      
                      @if($hasProgramEnded)
                        <span style="background: #6c757d; color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">
                          <i class="fas fa-ban"></i> Program Ended
                        </span>
                      @endif
                    </div>
                  </div>
                @endforeach
              @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-color); opacity: 0.7;">
                  <i class="fas fa-calendar-plus" style="font-size: 3rem; margin-bottom: 20px;"></i>
                  <p>You haven't registered for any programs yet.</p>
                  <a href="#programs" style="display: inline-block; margin-top: 15px; background: var(--primary-color); color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-weight: 600;">
                  
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Event Details Modal -->
    <div id="eventModal" class="modal" style="display: none;">
      <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">
          <h2 id="modalEventTitle">Event Title</h2>
          <span id="modalEventCategory" class="event-category">Category</span>
        </div>
        <div class="modal-body">
          <img id="modalEventImage" src="" alt="Event Image" style="display: none;">
          <div class="event-details">
            <p><strong>Date & Time:</strong> <span id="modalEventDateTime"></span></p>
            <p><strong>Location:</strong> <span id="modalEventLocation"></span></p>
            <p><strong>Published by:</strong> <span id="modalEventPublisher"></span></p>
            <p><strong>Description:</strong></p>
            <p id="modalEventDescription"></p>
          </div>
        </div>
        <div class="modal-footer">
          @php
            $currentDateTime = Carbon::now();
          @endphp
          <div id="eventModalActions">
            <!-- Register button will be shown dynamically based on event status -->
          </div>
          <button class="close-btn">Close</button>
        </div>
      </div>
    </div>

    <!-- Enhanced Program Details Modal -->
    <div id="programModal" class="modal" style="display: none;">
      <div class="modal-content program-modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">
          <h2 id="modalProgramTitle">Program Title</h2>
          <span id="modalProgramCategory" class="program-category">Category</span>
        </div>
        <div class="modal-body">
          <!-- Program Image -->
          <div class="program-image-container">
            <img id="modalProgramImage" src="" alt="Program Image" class="program-image" style="display: none;">
            <div class="no-image" style="display: none;">
              <i class="fas fa-calendar-alt"></i>
              <span>No Image Available</span>
            </div>
          </div>

          <!-- Program Details Grid -->
          <div class="program-details-grid">
            <div class="detail-item">
              <i class="fas fa-calendar-day"></i>
              <div>
                <div class="detail-label">DATE & TIME</div>
                <div class="detail-value" id="modalProgramDateTime"></div>
              </div>
            </div>
            <div class="detail-item">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <div class="detail-label">LOCATION</div>
                <div class="detail-value" id="modalProgramLocation"></div>
              </div>
            </div>
            <div class="detail-item">
              <i class="fas fa-user-tie"></i>
              <div>
                <div class="detail-label">PUBLISHED BY</div>
                <div class="detail-value" id="modalProgramPublisher"></div>
              </div>
            </div>
            <div class="detail-item">
              <i class="fas fa-tag"></i>
              <div>
                <div class="detail-label">CATEGORY</div>
                <div class="detail-value" id="modalProgramCategoryText"></div>
              </div>
            </div>
          </div>

          <!-- Registration Type Section -->
          <div class="registration-section">
            <h4>Registration Information</h4>
            
            <!-- Link Source Registration -->
            <div id="linkRegistration" class="registration-type-link" style="display: none;">
              <div class="link-source-box">
                <i class="fas fa-link"></i>
                <div>
                  <div class="detail-label">EXTERNAL REGISTRATION</div>
                  <a href="#" id="modalProgramLink" target="_blank" class="external-link">
                    Click here to register externally
                    <i class="fas fa-external-link-alt"></i>
                  </a>
                </div>
              </div>
            </div>

            <!-- Create Registration Form -->
            <div id="createRegistration" class="registration-type-form" style="display: none;">
              <div class="registration-form">
                <h5 id="registrationFormTitle">Registration Form</h5>
                <p class="registration-description" id="registrationDescription"></p>
                
                <!-- Registration Period -->
                <div class="registration-period-info">
                  <div class="period-group">
                    <div class="period-label">Registration Opens</div>
                    <div class="period-value" id="registrationOpenPeriod"></div>
                  </div>
                  <div class="period-group">
                    <div class="period-label">Registration Closes</div>
                    <div class="period-value" id="registrationClosePeriod"></div>
                  </div>
                </div>

                <!-- Registration Status -->
                <div id="registrationStatus" class="registration-status" style="display: none;">
                  <i class="fas fa-info-circle"></i>
                  <span id="registrationStatusText"></span>
                </div>

                <!-- Registration Form Fields -->
                <form id="programRegistrationForm" class="registration-form-fields">
                  @csrf
                  <input type="hidden" name="program_id" id="hiddenProgramId">
                  
                  <!-- Auto-filled user data (from user profile) -->
                  <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" value="{{ $user->given_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->suffix }}" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" value="{{ $user->email }}" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" class="form-input" value="{{ $user->contact_no }}" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="text" class="form-input" value="{{ $age }} years old" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label">Barangay</label>
                    <input type="text" class="form-input" value="{{ $user->barangay->name ?? 'N/A' }}" readonly>
                  </div>

                  <!-- Dynamic custom fields from program creation -->
                  <div id="dynamicCustomFields"></div>

                  <div class="form-actions">
                    <button type="submit" class="submit-btn" id="submitRegistrationBtn">
                      <i class="fas fa-paper-plane"></i>
                      Submit Registration
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- No Registration Available -->
            <div id="noRegistration" class="no-registration" style="display: none;">
              <div class="no-registration-message">
                <i class="fas fa-info-circle"></i>
                <p>Registration details are not available for this program.</p>
              </div>
            </div>
          </div>

          <!-- Program Description -->
          <div class="description-section">
            <h4>About This Program</h4>
            <div class="description-content" id="modalProgramDescription"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="close-btn">Close</button>
        </div>
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

  document.addEventListener("DOMContentLoaded", () => {
    // === THEME TOGGLE FUNCTIONALITY ===
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

    // Initialize theme on page load
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    applyTheme(savedTheme === 'dark');

    // Add click event to theme toggle button
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
      themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const isDark = currentTheme === 'light';
        applyTheme(isDark);
      });
    }

    // === Lucide icons ===
    if (typeof lucide !== "undefined" && lucide.createIcons) {
      lucide.createIcons();
    }

    // === Elements ===
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const profileItem = document.querySelector('.profile-item');
    const profileLink = profileItem?.querySelector('.profile-link');
    const profileWrapper = document.querySelector('.profile-wrapper');
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.querySelector('.profile-dropdown');
    const notifWrapper = document.querySelector(".notification-wrapper");
    const notifBell = notifWrapper?.querySelector(".fa-bell");
    const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

    // === Sidebar toggle ===
    if (menuToggle && sidebar) {
      menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');
        if (!sidebar.classList.contains('open')) {
          profileItem?.classList.remove('open');
        }
      });
    }

    // Helper: close all submenus
    function closeAllSubmenus() {
      profileItem?.classList.remove('open');
    }

    // === Profile submenu toggle ===
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

    // === Close sidebar when clicking outside ===
    document.addEventListener('click', (e) => {
      if (sidebar && !sidebar.contains(e.target) && menuToggle && !menuToggle.contains(e.target)) {
        sidebar.classList.remove('open');
        closeAllSubmenus();
      }

      if (profileWrapper && !profileWrapper.contains(e.target)) {
        profileWrapper.classList.remove('active');
      }

      if (notifWrapper && !notifWrapper.contains(e.target)) {
        notifWrapper.classList.remove('active');
      }
    });

    // === Profile dropdown toggle (topbar) ===
    if (profileToggle) {
      profileToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        profileWrapper.classList.toggle('active');
        notifWrapper?.classList.remove('active');
      });
    }

    if (profileDropdown) {
      profileDropdown.addEventListener('click', e => e.stopPropagation());
    }

    // === Notifications dropdown toggle ===
    if (notifBell) {
      notifBell.addEventListener('click', (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle('active');
        profileWrapper?.classList.remove('active');
      });
    }

    if (notifDropdown) {
      notifDropdown.addEventListener('click', e => e.stopPropagation());
    }

    // === Mark as read functionality - UPDATED TO MATCH DASHBOARD ===
    function initMarkAsRead() {
      const csrfToken = window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
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

          const headerCount = document.querySelector('.notif-header span');
          if (headerCount) {
            let currentHeaderCount = parseInt(headerCount.textContent) || 0;
            const newHeaderCount = Math.max(0, currentHeaderCount - 1);
            if (newHeaderCount > 0) {
              headerCount.textContent = newHeaderCount;
            } else {
              headerCount.remove();
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
          const eventId = this.getAttribute('data-event-id');
          const programId = this.getAttribute('data-program-id');
          const notifItem = this.closest('li');
          notifItem?.remove();

          // Update notification counts
          const countEl = document.querySelector('.notif-count');
          if (countEl) {
            let currentCount = parseInt(countEl.textContent) || 0;
            countEl.textContent = Math.max(0, currentCount - 1);
            if (parseInt(countEl.textContent) === 0) {
              countEl.remove();
            }
          }

          const headerCount = document.querySelector('.notif-header span');
          if (headerCount) {
            let currentHeaderCount = parseInt(headerCount.textContent) || 0;
            const newHeaderCount = Math.max(0, currentHeaderCount - 1);
            if (newHeaderCount > 0) {
              headerCount.textContent = newHeaderCount;
            } else {
              headerCount.remove();
            }
          }

          const notifList = document.querySelector('.notif-list');
          if (notifList && notifList.children.length === 0) {
            notifList.innerHTML = `<li class="no-notifications"><p>No new notifications</p></li>`;
          }
        });
      });
    }

    // Initialize mark as read
    initMarkAsRead();

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

    // === Event Details Modal ===
    const eventModal = document.getElementById('eventModal');
    const programModal = document.getElementById('programModal');
    const closeModal = document.querySelectorAll('.close');
    const closeBtn = document.querySelectorAll('.close-btn');
    const viewDetailsButtons = document.querySelectorAll('.view-event-details');
    const viewProgramButtons = document.querySelectorAll('.view-program-details');

    // Function to fetch and display event details
    async function showEventDetails(eventId) {
      try {
        const response = await fetch(`/events/${eventId}`);
        if (!response.ok) throw new Error('Event not found');
        
        const event = await response.json();
        
        document.getElementById('modalEventTitle').textContent = event.title;
        document.getElementById('modalEventCategory').textContent = event.category ? event.category.replace(/_/g, ' ').toUpperCase() : 'No category';
        document.getElementById('modalEventDateTime').textContent = event.event_date_time || 'Date not specified';
        document.getElementById('modalEventLocation').textContent = event.location || 'Location not specified';
        document.getElementById('modalEventPublisher').textContent = event.published_by || 'Publisher not specified';
        document.getElementById('modalEventDescription').textContent = event.description || 'No description available.';
        
        const modalImage = document.getElementById('modalEventImage');
        if (event.image) {
          modalImage.src = event.image;
          modalImage.style.display = 'block';
          modalImage.alt = event.title;
          
          modalImage.onerror = function() {
            this.style.display = 'none';
          };
        } else {
          modalImage.style.display = 'none';
        }
        
        // Check if event has ended
        const eventActions = document.getElementById('eventModalActions');
        if (eventActions) {
          const eventEnded = new Date(event.event_date_time || event.event_date) < new Date();
          
          if (eventEnded) {
            eventActions.innerHTML = `
              <span class="attend-btn ended" style="margin-right: 10px;">Event Ended</span>
            `;
          } else {
            eventActions.innerHTML = `
              <a href="{{ route('attendancepage') }}?event_id=${eventId}" class="register-modal-btn">Register for Event</a>
            `;
          }
        }
        
        eventModal.style.display = 'block';
      } catch (error) {
        console.error('Error fetching event details:', error);
        showCustomAlert('Error loading event details. Please try again.', 'Error', 'error');
      }
    }

    // Add click event to all view details buttons
    viewDetailsButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const eventId = button.getAttribute('data-event-id');
        showEventDetails(eventId);
      });
    });

    viewProgramButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const programId = button.getAttribute('data-program-id');
        showProgramDetails(programId);
      });
    });

    // Program register buttons
    document.querySelectorAll('.program-register').forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const programId = button.getAttribute('data-program-id');
        showProgramDetails(programId);
      });
    });

    // Close modal functions
    closeModal.forEach(close => {
      close.addEventListener('click', () => {
        if (eventModal) eventModal.style.display = 'none';
        if (programModal) programModal.style.display = 'none';
      });
    });

    closeBtn.forEach(btn => {
      btn.addEventListener('click', () => {
        if (eventModal) eventModal.style.display = 'none';
        if (programModal) programModal.style.display = 'none';
      });
    });

    if (eventModal) {
      eventModal.addEventListener('click', (e) => {
        if (e.target === eventModal) {
          eventModal.style.display = 'none';
        }
      });
    }

    if (programModal) {
      programModal.addEventListener('click', (e) => {
        if (e.target === programModal) {
          programModal.style.display = 'none';
        }
      });
    }

    // Truncate program descriptions
    document.querySelectorAll('.program-desc').forEach(el => {
      let text = el.textContent.trim();
      if (text.length > 100) {
        el.textContent = text.substring(0, 100) + '...';
      }
    });

    // Mobile sidebar functionality
    const mobileBtn = document.getElementById('mobileMenuBtn');
    // sidebar is already declared above, so we don't redeclare it here

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
    
    // Initialize Feedback Modal
    initFeedbackModal();
    
    // Initialize SK Access Modal
    initSkAccessModal();
    
    // Initialize See All Modals
    initSeeAllModals();
  });

  // Function to fetch and display program details
  async function showProgramDetails(programId) {
    try {
      const response = await fetch(`/programs/${programId}`);
      if (!response.ok) throw new Error('Program not found');
      
      const program = await response.json();
      
      // Set basic program information
      document.getElementById('modalProgramTitle').textContent = program.title;
      document.getElementById('modalProgramCategory').textContent = program.category ? program.category.replace(/_/g, ' ').toUpperCase() : 'No category';
      document.getElementById('modalProgramCategoryText').textContent = program.category ? program.category.replace(/_/g, ' ') : 'No category';
      
      // Format date and time
      const programDate = program.event_date ? new Date(program.event_date).toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      }) : 'Date not specified';
      
      const programTime = program.event_time ? 
        (program.event_time.includes(':') ? 
          new Date(`2000-01-01T${program.event_time}`).toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit', 
            hour12: true 
          }) : 
          program.event_time) : 
        'Time not specified';
      
      document.getElementById('modalProgramDateTime').textContent = `${programDate} | ${programTime}`;
      document.getElementById('modalProgramLocation').textContent = program.location || 'Location not specified';
      document.getElementById('modalProgramPublisher').textContent = program.published_by || 'Publisher not specified';
      document.getElementById('modalProgramDescription').textContent = program.description || 'No description available.';
      
      // Set program image
      const programImage = document.getElementById('modalProgramImage');
      const noImagePlaceholder = document.querySelector('.program-image-container .no-image');
      
      if (program.display_image) {
        programImage.src = program.display_image;
        programImage.style.display = 'block';
        programImage.alt = program.title;
        if (noImagePlaceholder) noImagePlaceholder.style.display = 'none';
        
        programImage.onerror = function() {
          this.style.display = 'none';
          if (noImagePlaceholder) noImagePlaceholder.style.display = 'flex';
        };
      } else {
        programImage.style.display = 'none';
        if (noImagePlaceholder) noImagePlaceholder.style.display = 'flex';
      }
      
      // Handle registration type
      const linkRegistration = document.getElementById('linkRegistration');
      const createRegistration = document.getElementById('createRegistration');
      const noRegistration = document.getElementById('noRegistration');
      const registrationStatus = document.getElementById('registrationStatus');
      
      // Hide all registration sections first
      linkRegistration.style.display = 'none';
      createRegistration.style.display = 'none';
      noRegistration.style.display = 'none';
      registrationStatus.style.display = 'none';
      
      // Clear previous custom fields
      document.getElementById('dynamicCustomFields').innerHTML = '';
      
      if (program.registration_type === 'link' && program.link_source) {
        // External link registration
        linkRegistration.style.display = 'block';
        const modalLink = document.getElementById('modalProgramLink');
        modalLink.href = program.link_source;
        
        // Display a friendly link text
        const url = new URL(program.link_source);
        modalLink.textContent = `Register on ${url.hostname}`;
        
      } else if (program.registration_type === 'create') {
        // Internal registration form
        createRegistration.style.display = 'block';
        document.getElementById('hiddenProgramId').value = program.id;
        document.getElementById('registrationFormTitle').textContent = `Register for ${program.title}`;
        document.getElementById('registrationDescription').textContent = program.registration_description || 'Please fill out the registration form below.';
        
        // Set registration periods
        if (program.registration_open_date && program.registration_open_time) {
          const openDate = new Date(program.registration_open_date + 'T' + program.registration_open_time);
          document.getElementById('registrationOpenPeriod').textContent = openDate.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
          });
        } else {
          document.getElementById('registrationOpenPeriod').textContent = 'Immediately';
        }
        
        if (program.registration_close_date && program.registration_close_time) {
          const closeDate = new Date(program.registration_close_date + 'T' + program.registration_close_time);
          document.getElementById('registrationClosePeriod').textContent = closeDate.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
          });
        } else {
          document.getElementById('registrationClosePeriod').textContent = 'Until event date';
        }
        
        // Check registration status (open, closed, or upcoming)
        const now = new Date();
        const openDate = program.registration_open_date && program.registration_open_time ? 
          new Date(program.registration_open_date + 'T' + program.registration_open_time) : null;
        const closeDate = program.registration_close_date && program.registration_close_time ? 
          new Date(program.registration_close_date + 'T' + program.registration_close_time) : null;
        
        if (openDate && now < openDate) {
          // Registration hasn't opened yet
          registrationStatus.style.display = 'flex';
          registrationStatus.className = 'registration-status upcoming';
          registrationStatus.innerHTML = `<i class="fas fa-clock"></i> <span>Registration opens on ${openDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} at ${openDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</span>`;
          document.getElementById('submitRegistrationBtn').disabled = true;
          document.getElementById('submitRegistrationBtn').innerHTML = '<i class="fas fa-clock"></i> Registration Not Open Yet';
        } else if (closeDate && now > closeDate) {
          // Registration has closed
          registrationStatus.style.display = 'flex';
          registrationStatus.className = 'registration-status closed';
          registrationStatus.innerHTML = `<i class="fas fa-times-circle"></i> <span>Registration closed on ${closeDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} at ${closeDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</span>`;
          document.getElementById('submitRegistrationBtn').disabled = true;
          document.getElementById('submitRegistrationBtn').innerHTML = '<i class="fas fa-ban"></i> Registration Closed';
        } else {
          // Registration is open
          registrationStatus.style.display = 'flex';
          registrationStatus.className = 'registration-status open';
          registrationStatus.innerHTML = `<i class="fas fa-check-circle"></i> <span>Registration is open</span>`;
          document.getElementById('submitRegistrationBtn').disabled = false;
          document.getElementById('submitRegistrationBtn').innerHTML = '<i class="fas fa-paper-plane"></i> Submit Registration';
        }
        
        // Generate custom fields from program data
        if (program.custom_fields && Array.isArray(program.custom_fields) && program.custom_fields.length > 0) {
          const dynamicFieldsContainer = document.getElementById('dynamicCustomFields');
          program.custom_fields.forEach((field, index) => {
            const fieldId = `custom_field_${index}`;
            let fieldHtml = '';
            
            // Based on the field type from create-program.blade.php
            if (field.type === 'short_answer') {
              fieldHtml = `
                <div class="form-group">
                  <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                  <input type="text" 
                         class="form-input" 
                         id="${fieldId}"
                         name="custom_fields[${index}][answer]"
                         data-field-label="${field.label}"
                         data-field-type="${field.type}"
                         ${field.required ? 'required' : ''}
                         placeholder="Enter your answer">
                  <input type="hidden" name="custom_fields[${index}][label]" value="${field.label}">
                  <input type="hidden" name="custom_fields[${index}][type]" value="${field.type}">
                  <input type="hidden" name="custom_fields[${index}][required]" value="${field.required}">
                </div>
              `;
            } else if (field.type === 'multiple_choice') {
              const options = field.options && Array.isArray(field.options) ? field.options : (field.options ? [field.options] : []);
              fieldHtml = `
                <div class="form-group">
                  <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                  <div class="multiple-choice-options" style="display: flex; flex-direction: column; gap: 8px; margin-top: 5px;">
                    ${options.map((option, optIndex) => `
                      <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" 
                               name="custom_fields[${index}][answer]"
                               value="${option}"
                               ${field.required ? 'required' : ''}
                               ${optIndex === 0 ? 'required' : ''}>
                        ${option}
                      </label>
                    `).join('')}
                  </div>
                  <input type="hidden" name="custom_fields[${index}][label]" value="${field.label}">
                  <input type="hidden" name="custom_fields[${index}][type]" value="${field.type}">
                  <input type="hidden" name="custom_fields[${index}][required]" value="${field.required}">
                  ${options.map((option, optIndex) => `
                    <input type="hidden" name="custom_fields[${index}][options][${optIndex}]" value="${option}">
                  `).join('')}
                </div>
              `;
            } else if (field.type === 'dropdown') {
              const options = field.options && Array.isArray(field.options) ? field.options : (field.options ? [field.options] : []);
              fieldHtml = `
                <div class="form-group">
                  <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                  <select class="form-input" 
                          id="${fieldId}"
                          name="custom_fields[${index}][answer]"
                          ${field.required ? 'required' : ''}>
                    <option value="">Select ${field.label}</option>
                    ${options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                  </select>
                  <input type="hidden" name="custom_fields[${index}][label]" value="${field.label}">
                  <input type="hidden" name="custom_fields[${index}][type]" value="${field.type}">
                  <input type="hidden" name="custom_fields[${index}][required]" value="${field.required}">
                  ${options.map((option, optIndex) => `
                    <input type="hidden" name="custom_fields[${index}][options][${optIndex}]" value="${option}">
                  `).join('')}
                </div>
              `;
            } else if (field.type === 'file_upload') {
              fieldHtml = `
                <div class="form-group">
                  <label class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                  <input type="file" 
                         class="form-input" 
                         id="${fieldId}"
                         name="custom_fields[${index}][answer]"
                         ${field.required ? 'required' : ''}
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <small style="display: block; margin-top: 5px; color: #666;">Accepted formats: JPG, PNG, PDF, DOC, DOCX</small>
                  <input type="hidden" name="custom_fields[${index}][label]" value="${field.label}">
                  <input type="hidden" name="custom_fields[${index}][type]" value="${field.type}">
                  <input type="hidden" name="custom_fields[${index}][required]" value="${field.required}">
                </div>
              `;
            }
            
            dynamicFieldsContainer.innerHTML += fieldHtml;
          });
        } else {
          // No custom fields
          document.getElementById('dynamicCustomFields').innerHTML = `
            <div class="no-custom-fields" style="text-align: center; padding: 20px; background: var(--bg-color); border-radius: 8px; margin-bottom: 20px;">
              <i class="fas fa-info-circle" style="font-size: 2rem; color: var(--text-color); opacity: 0.5; margin-bottom: 10px;"></i>
              <p style="color: var(--text-color); opacity: 0.7;">No additional questions required for this program.</p>
            </div>
          `;
        }
        
      } else {
        // No registration available
        noRegistration.style.display = 'block';
      }
      
      // Show the modal
      document.getElementById('programModal').style.display = 'block';
      
      // Initialize form submission
      initProgramRegistrationForm();
      
    } catch (error) {
      console.error('Error fetching program details:', error);
      showCustomAlert('Error loading program details. Please try again.', 'Error', 'error');
    }
  }
  
  // Initialize Program Registration Form
  function initProgramRegistrationForm() {
    const registrationForm = document.getElementById('programRegistrationForm');
    if (!registrationForm) return;
    
    // Remove any existing event listeners
    const newRegistrationForm = registrationForm.cloneNode(true);
    registrationForm.parentNode.replaceChild(newRegistrationForm, registrationForm);
    
    // Add new event listener
    document.getElementById('programRegistrationForm').addEventListener('submit', handleProgramRegistrationSubmit);
  }
  
  // Handle Program Registration Form Submission
  async function handleProgramRegistrationSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('.submit-btn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    
    // Get form data
    const formData = new FormData(form);
    const programId = form.querySelector('#hiddenProgramId').value;
    
    // Collect custom fields data
    const customFieldsData = [];
    const customInputs = form.querySelectorAll('[name^="custom_fields"]');
    
    // Group custom fields by index
    const fieldsByIndex = {};
    
    customInputs.forEach(input => {
      const name = input.name;
      const match = name.match(/custom_fields\[(\d+)\]\[(\w+)\]/);
      
      if (match) {
        const index = match[1];
        const field = match[2];
        
        if (!fieldsByIndex[index]) {
          fieldsByIndex[index] = {};
        }
        
        if (field === 'answer') {
          if (input.type === 'file') {
            // Handle file uploads
            if (input.files.length > 0) {
              fieldsByIndex[index][field] = input.files[0];
            }
          } else if (input.type === 'radio') {
            // Only collect checked radio buttons
            if (input.checked) {
              fieldsByIndex[index][field] = input.value;
            }
          } else {
            fieldsByIndex[index][field] = input.value;
          }
        } else if (field === 'label' || field === 'type' || field === 'required') {
          fieldsByIndex[index][field] = input.value;
        } else if (field === 'options') {
          // Handle options array
          if (!fieldsByIndex[index].options) {
            fieldsByIndex[index].options = [];
          }
          fieldsByIndex[index].options.push(input.value);
        }
      }
    });
    
    // Convert to array
    Object.keys(fieldsByIndex).forEach(index => {
      customFieldsData.push(fieldsByIndex[index]);
    });
    
    // Prepare registration data
    const registrationData = {
      custom_fields: customFieldsData
    };
    
    // Create FormData for file uploads
    const registrationFormData = new FormData();
    registrationFormData.append('program_id', programId);
    registrationFormData.append('registration_data', JSON.stringify(registrationData));
    
    // Add CSRF token
    registrationFormData.append('_token', window.csrfToken);
    
    // Submit registration
    try {
      const response = await fetch('/programs/register', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          'Accept': 'application/json'
        },
        body: registrationFormData
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Close program modal
        document.getElementById('programModal').style.display = 'none';
        
        // Show success modal
        showRegistrationSuccessModal(data.reference_id);
      } else {
        throw new Error(data.message || 'Registration failed');
      }
    } catch (error) {
      console.error('Registration error:', error);
      showCustomAlert('Error submitting registration: ' + error.message, 'Error', 'error');
      
      // Reset button
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
    }
  }
  
  // Show Registration Success Modal
  function showRegistrationSuccessModal(referenceId) {
    const modal = document.getElementById('registrationSuccessModal');
    const referenceIdDisplay = document.getElementById('referenceIdDisplay');
    const closeBtn = document.getElementById('closeRegistrationSuccess');
    const viewRegistrationsBtn = document.getElementById('viewMyRegistrations');
    
    // Set reference ID
    referenceIdDisplay.textContent = referenceId;
    
    // Set up event listeners
    closeBtn.onclick = () => {
      modal.style.display = 'none';
      // Refresh the page to show updated registration status
      window.location.reload();
    };
    
    viewRegistrationsBtn.onclick = (e) => {
      e.preventDefault();
      modal.style.display = 'none';
      // Redirect to user's registrations page
      window.location.href = '/my-registrations';
    };
    
    // Close modal when clicking outside
    modal.onclick = (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
        window.location.reload();
      }
    };
    
    // Show the modal
    modal.style.display = 'flex';
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
  
  // Initialize SK Access Modal
  function initSkAccessModal() {
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
  }
  
  // Initialize See All Modals
  function initSeeAllModals() {
    // All Events Modal
    const seeAllEventsBtn = document.getElementById('seeAllEvents');
    const allEventsModal = document.getElementById('allEventsModal');
    const closeAllEventsModal = document.getElementById('closeAllEventsModal');
    
    // All Programs Modal
    const seeAllProgramsBtn = document.getElementById('seeAllPrograms');
    const allProgramsModal = document.getElementById('allProgramsModal');
    const closeAllProgramsModal = document.getElementById('closeAllProgramsModal');
    
    // Tab functionality for Events Modal
    const eventModalTabs = document.querySelectorAll('#allEventsModal .modal-tab');
    const eventTabContents = document.querySelectorAll('#allEventsModal .modal-tab-content');
    
    // Tab functionality for Programs Modal
    const programModalTabs = document.querySelectorAll('#allProgramsModal .modal-tab');
    const programTabContents = document.querySelectorAll('#allProgramsModal .modal-tab-content');
    
    // Open All Events Modal
    if (seeAllEventsBtn && allEventsModal) {
      seeAllEventsBtn.addEventListener('click', (e) => {
        e.preventDefault();
        allEventsModal.style.display = 'flex';
      });
    }
    
    // Close All Events Modal
    if (closeAllEventsModal && allEventsModal) {
      closeAllEventsModal.addEventListener('click', () => {
        allEventsModal.style.display = 'none';
      });
    }
    
    // Open All Programs Modal
    if (seeAllProgramsBtn && allProgramsModal) {
      seeAllProgramsBtn.addEventListener('click', (e) => {
        e.preventDefault();
        allProgramsModal.style.display = 'flex';
      });
    }
    
    // Close All Programs Modal
    if (closeAllProgramsModal && allProgramsModal) {
      closeAllProgramsModal.addEventListener('click', () => {
        allProgramsModal.style.display = 'none';
      });
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', (e) => {
      if (e.target === allEventsModal) {
        allEventsModal.style.display = 'none';
      }
      if (e.target === allProgramsModal) {
        allProgramsModal.style.display = 'none';
      }
    });
    
    // Event Modal Tab Switching
    eventModalTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const tabId = tab.getAttribute('data-tab');
        
        // Update active tab
        eventModalTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        
        // Show corresponding content
        eventTabContents.forEach(content => {
          content.classList.remove('active');
          if (content.id === `${tabId}-tab`) {
            content.classList.add('active');
          }
        });
      });
    });
    
    // Program Modal Tab Switching
    programModalTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const tabId = tab.getAttribute('data-tab');
        
        // Update active tab
        programModalTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        
        // Show corresponding content
        programTabContents.forEach(content => {
          content.classList.remove('active');
          if (content.id === `${tabId}-tab`) {
            content.classList.add('active');
          }
        });
      });
    });
    
    // Make view details buttons work in modals too
    document.addEventListener('click', (e) => {
      if (e.target.closest('.view-event-details')) {
        const button = e.target.closest('.view-event-details');
        const eventId = button.getAttribute('data-event-id');
        if (eventId) {
          e.preventDefault();
          allEventsModal.style.display = 'none';
          showEventDetails(eventId);
        }
      }
      
      if (e.target.closest('.view-program-details')) {
        const button = e.target.closest('.view-program-details');
        const programId = button.getAttribute('data-program-id');
        if (programId) {
          e.preventDefault();
          allProgramsModal.style.display = 'none';
          showProgramDetails(programId);
        }
      }
    });
  }
  </script>

</body>
</html>