<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  </script>
  <title>KatiBayan - Evaluation</title>
  <link rel="stylesheet" href="{{ asset('css/evaluation.css') }}">
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
        
        <a href="{{ route('evaluation') }}" class="active">
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
            
            <!-- Theme Toggle -->
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
                            <h4>{{ $user->given_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}</h4>
                            <div class="badges-wrapper">
                                <div class="profile-badge">
                                    <span class="badge">{{ $roleBadge ?? 'GUEST' }}</span>
                                    <span class="badge">{{ $age ?? 'N/A' }} yrs old</span>
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

    <!-- Programs and Events Attended -->
    <section class="programs-section">
        <div class="programs-header">
            <h2>Programs and Events Attended</h2>
            <p>Evaluate the programs and events you attended and share your feedback with us.</p>
        </div>

        @php
            $allActivities = $allActivities ?? ['events' => collect(), 'programs' => collect()];
            
            $eventsToEvaluate = $allActivities['events']->filter(function($event) {
                return !($event->evaluations && $event->evaluations->where('user_id', Auth::id())->count());
            });

            $programsToEvaluate = $allActivities['programs']->filter(function($program) {
                return !($program->evaluations && $program->evaluations->where('user_id', Auth::id())->count());
            });

            $totalToEvaluate = $eventsToEvaluate->count() + $programsToEvaluate->count();
        @endphp

        <p class="programs-note">
            You have <span>{{ $totalToEvaluate }} activities</span> to evaluate. Please evaluate them now.
        </p>

        <!-- Events to Evaluate -->
        @if($eventsToEvaluate->count() > 0)
            <div class="activity-type-section">
                <h3>Events to Evaluate</h3>
                <div class="program-list">
                    @foreach($eventsToEvaluate as $event)
                        <div class="program-card" id="event-card-{{ $event->id }}" data-activity-type="event" data-activity-id="{{ $event->id }}">
                            <div class="program-img-wrapper">
                                @if($event->image && Storage::disk('public')->exists($event->image))
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="program-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                                @else
                                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image" class="program-img">
                                @endif
                                <span class="status-dot"></span>
                                <span class="activity-badge event-badge">Event</span>
                            </div>
                            <div class="program-info">
                                <h3>{{ $event->title }}</h3>
                                <p class="date">{{ $event->event_date->format('F d, Y') }} | {{ $event->formatted_time ?? 'Time not specified' }}</p>
                                <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                            </div>
                            <button class="evaluate-btn" data-activity-type="event" data-activity-id="{{ $event->id }}">Evaluate</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Programs to Evaluate -->
        @if($programsToEvaluate->count() > 0)
            <div class="activity-type-section">
                <h3>Programs to Evaluate</h3>
                <div class="program-list">
                    @foreach($programsToEvaluate as $program)
                        <div class="program-card" id="program-card-{{ $program->id }}" data-activity-type="program" data-activity-id="{{ $program->id }}">
                            <div class="program-img-wrapper">
                                @if($program->display_image && Storage::disk('public')->exists($program->display_image))
                                    <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" class="program-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                                @else
                                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg==" alt="Program Image" class="program-img">
                                @endif
                                <span class="status-dot"></span>
                                <span class="activity-badge program-badge">Program</span>
                            </div>
                            <div class="program-info">
                                <h3>{{ $program->title }}</h3>
                                <p class="date">{{ $program->event_date->format('F d, Y') }} | {{ $program->event_time ? \Carbon\Carbon::parse($program->event_time)->format('g:i A') : 'Time not specified' }}</p>
                                <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $program->location }}</p>
                            </div>
                            <button class="evaluate-btn" data-activity-type="program" data-activity-id="{{ $program->id }}">Evaluate</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($totalToEvaluate === 0)
            <div class="no-events-message">
                <i class="fas fa-calendar-check"></i>
                <p>No activities to evaluate at the moment.</p>
                <p class="sub-message">
                    @if($allActivities['events']->count() > 0 || $allActivities['programs']->count() > 0)
                        You have attended activities, but they may have already been evaluated.
                    @else
                        Attend events or register for programs to see them here for evaluation.
                    @endif
                </p>
            </div>
        @endif

        <!-- Already Evaluated Section -->
        @php
            $evaluatedEvents = $allActivities['events']->filter(function($event) {
                return $event->evaluations && $event->evaluations->where('user_id', Auth::id())->count();
            });

            $evaluatedPrograms = $allActivities['programs']->filter(function($program) {
                return $program->evaluations && $program->evaluations->where('user_id', Auth::id())->count();
            });

            $totalEvaluated = $evaluatedEvents->count() + $evaluatedPrograms->count();
        @endphp

        @if($totalEvaluated > 0)
            <div class="evaluated-section">
                <h3>Already Evaluated ({{ $totalEvaluated }})</h3>
                
                <!-- Evaluated Events -->
                @if($evaluatedEvents->count() > 0)
                    <div class="activity-type-section">
                        <h4>Events</h4>
                        <div class="program-list evaluated">
                            @foreach($evaluatedEvents as $event)
                                <div class="program-card evaluated" data-activity-type="event" data-activity-id="{{ $event->id }}">
                                    <div class="program-img-wrapper">
                                        @if($event->image && Storage::disk('public')->exists($event->image))
                                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="program-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                                        @else
                                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image" class="program-img">
                                        @endif
                                        <span class="status-dot completed"><i class="fas fa-check"></i></span>
                                        <span class="activity-badge event-badge">Event</span>
                                    </div>
                                    <div class="program-info">
                                        <h3>{{ $event->title }}</h3>
                                        <p class="date">{{ $event->event_date->format('F d, Y') }} | {{ $event->formatted_time ?? 'Time not specified' }}</p>
                                        <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                                    </div>
                                    <button class="evaluate-btn done" disabled>
                                        <i class="fa-solid fa-check"></i> Evaluated
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Evaluated Programs -->
                @if($evaluatedPrograms->count() > 0)
                    <div class="activity-type-section">
                        <h4>Programs</h4>
                        <div class="program-list evaluated">
                            @foreach($evaluatedPrograms as $program)
                                <div class="program-card evaluated" data-activity-type="program" data-activity-id="{{ $program->id }}">
                                    <div class="program-img-wrapper">
                                        @if($program->display_image && Storage::disk('public')->exists($program->display_image))
                                            <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" class="program-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                                        @else
                                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg==" alt="Program Image" class="program-img">
                                        @endif
                                        <span class="status-dot completed"><i class="fas fa-check"></i></span>
                                        <span class="activity-badge program-badge">Program</span>
                                    </div>
                                    <div class="program-info">
                                        <h3>{{ $program->title }}</h3>
                                        <p class="date">{{ $program->event_date->format('F d, Y') }} | {{ $program->event_time ? \Carbon\Carbon::parse($program->event_time)->format('g:i A') : 'Time not specified' }}</p>
                                        <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $program->location }}</p>
                                    </div>
                                    <button class="evaluate-btn done" disabled>
                                        <i class="fa-solid fa-check"></i> Evaluated
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </section>

    <!-- Evaluation Modal -->
    <div id="evaluationModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>

            <!-- Page 1: Questions -->
            <div id="page1" class="page">
                <h2>Evaluation Form</h2>
                <p class="subtitle">Share your feedback with us.</p>
                <hr>

                <p class="instruction">
                    Please evaluate the program/event you attended by answering the questions below. 
                    Your feedback will help us improve future activities. Kindly provide honest and 
                    constructive responses. All evaluations will remain confidential.
                </p>

                <label class="label">Activity Name</label>
                <input type="text" class="event-input" id="modalActivityName" readonly>

                <input type="hidden" id="currentActivityId">
                <input type="hidden" id="currentActivityType">

                <p class="label">Instruction:</p>
                <p class="instruction">
                    Please rate the following statements from 1 to 5, where 1 means Strongly Disagree 
                    and 5 means Strongly Agree
                </p>

                <div class="questions">
    @foreach($evaluationQuestions as $index => $question)
    <div class="question">
        <p>
            Question {{ $index + 1 }}: {{ $question->question_text }}
            <span class="required">*</span>
        </p>

        <div class="scale">
            @for($i = 1; $i <= 5; $i++)
            <label>
                <input type="radio"
                       name="ratings[{{ $question->id }}]"
                       value="{{ $i }}"
                       required>
                <span>
                    <div class="circle">{{ $i }}</div>
                    @if($i == 1) Strongly Disagree
                    @elseif($i == 2) Disagree
                    @elseif($i == 3) Neutral
                    @elseif($i == 4) Agree
                    @else Strongly Agree
                    @endif
                </span>
            </label>
            @endfor
        </div>
    </div>
    @endforeach
</div>


                <div class="pagination">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                </div>
                <div class="actions">
                    <button class="next-btn">Next</button>
                </div>
            </div>

            <!-- Page 2: Comments -->
            <div id="page2" class="page" style="display:none;">
                <h2>Comments</h2>
                <p class="instruction">
                    Please share your comments and suggestions to help us improve our services and upcoming events.
                </p>
                <textarea class="comment-box" id="comments" placeholder="Enter your comments here..."></textarea>
                <p class="instruction">
                    Once you have submitted this evaluation, your certificate for this activity will be available in your profile. Thank you!
                </p>

                <div class="pagination">
                    <span class="dot active"></span>
                    <span class="dot active"></span>
                </div>

                <div class="actions">
                    <button class="submit-btn">Submit Evaluation</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content success">
            <div class="check-circle">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2>Submitted</h2>
            <p>Your evaluation has been submitted successfully. <br>Kindly wait for your certificate. Thank you.</p>
            <button class="ok-btn">OK</button>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Initialize Lucide icons
        if (window.lucide) {
            lucide.createIcons();
        }

        // ================= THEME TOGGLE =================
        function applyTheme(isDark) {
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            
            // Set data-theme attribute
            html.setAttribute('data-theme', isDark ? 'dark' : 'light');
            
            // Update toggle button icon
            const icon = isDark ? 'sun' : 'moon';
            
            if (themeToggle) {
                themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
                // Re-create Lucide icons
                if (window.lucide) {
                    lucide.createIcons();
                }
            }
            
            // Store preference
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Initialize theme
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (prefersDark ? 'dark' : 'light');
        const isDarkMode = initialTheme === 'dark';
        
        applyTheme(isDarkMode);

        // Theme toggle button
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const isDark = currentTheme === 'dark';
                applyTheme(!isDark);
            });
        }

        // ================= Sidebar =================
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        const navItems = document.querySelectorAll('.nav-item > a');

        function closeAllSubmenus() {
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('open'));
        }

        menuToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            if (!sidebar.classList.contains('open')) closeAllSubmenus();
        });

        navItems.forEach(link => {
            link.addEventListener('click', e => {
                if (!sidebar.classList.contains('open')) return;
                const parentItem = link.parentElement;
                const isOpen = parentItem.classList.contains('open');
                closeAllSubmenus();
                if (!isOpen) parentItem.classList.add('open');
                e.preventDefault();
            });
        });

        document.addEventListener('click', e => {
            if (!sidebar.contains(e.target) && !menuToggle?.contains(e.target)) {
                sidebar.classList.remove('open');
                closeAllSubmenus();
            }
        });

        // ================= Profile & Notifications =================
        const profileWrapper = document.querySelector('.profile-wrapper');
        const profileToggle = document.getElementById('profileToggle');
        const profileDropdown = document.querySelector('.profile-dropdown');

        const notifWrapper = document.querySelector(".notification-wrapper");
        const notifBell = notifWrapper?.querySelector(".fa-bell");
        const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

        profileToggle?.addEventListener('click', e => {
            e.stopPropagation();
            profileWrapper.classList.toggle('active');
            notifWrapper?.classList.remove('active');
        });

        profileDropdown?.addEventListener('click', e => e.stopPropagation());

        notifBell?.addEventListener('click', e => {
            e.stopPropagation();
            notifWrapper.classList.toggle('active');
            profileWrapper?.classList.remove('active');
        });

        notifDropdown?.addEventListener('click', e => e.stopPropagation());

        document.addEventListener('click', e => {
            if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
            if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
        });

        // ================= Mark notifications as read =================
        function initMarkAsRead() {
            const csrfToken = window.csrfToken;
            if (!csrfToken) return;

            document.querySelectorAll('.notif-link[data-id]').forEach(link => {
                link.addEventListener('click', function(e) {
                    const notifId = this.dataset.id;
                    const destinationUrl = this.href;

                    // Remove notification from UI
                    const notifItem = this.closest('li');
                    notifItem?.remove();

                    // Update notification count
                    const countEl = document.querySelector('.notif-count');
                    if (countEl) {
                        let currentCount = parseInt(countEl.textContent) || 0;
                        countEl.textContent = Math.max(0, currentCount - 1);
                        if (parseInt(countEl.textContent) === 0) {
                            countEl.remove();
                        }
                    }

                    // Mark as read via AJAX
                    fetch(`/notifications/${notifId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: notifId })
                    }).catch(err => console.error('Error marking as read:', err));

                    // Navigate if needed
                    if (destinationUrl && destinationUrl !== '#') {
                        window.location.href = destinationUrl;
                    }
                });
            });
        }
        initMarkAsRead();

        // ================= Time Update =================
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

        // ================= Evaluation Modal =================
        const evaluationModal = document.getElementById("evaluationModal");
        const successModal = document.getElementById("successModal");
        const closeBtn = evaluationModal?.querySelector(".close-btn");
        const nextBtn = evaluationModal?.querySelector(".next-btn");
        const submitBtn = evaluationModal?.querySelector(".submit-btn");
        const okBtn = successModal?.querySelector(".ok-btn");
        const page1 = document.getElementById("page1");
        const page2 = document.getElementById("page2");
        const modalActivityName = document.getElementById("modalActivityName");
        const currentActivityId = document.getElementById("currentActivityId");
        const currentActivityType = document.getElementById("currentActivityType");
        const comments = document.getElementById("comments");

        let currentEvaluatingActivityId = null;
        let currentEvaluatingActivityType = null;

        // Open modal with activity data
        document.querySelectorAll(".evaluate-btn:not(.done)").forEach(btn => {
            btn.addEventListener("click", async () => {
                const activityId = btn.getAttribute("data-activity-id");
                const activityType = btn.getAttribute("data-activity-type");
                const activityCard = btn.closest(".program-card");
                const activityName = activityCard.querySelector("h3").textContent;
                
                currentEvaluatingActivityId = activityId;
                currentEvaluatingActivityType = activityType;
                currentActivityId.value = activityId;
                currentActivityType.value = activityType;
                modalActivityName.value = activityName;

                // Reset form
                page1.style.display = "block";
                page2.style.display = "none";
                comments.value = "";
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.checked = false;
                });

                evaluationModal.style.display = "flex";
            });
        });

        closeBtn?.addEventListener("click", () => {
            evaluationModal.style.display = "none";
            currentEvaluatingActivityId = null;
            currentEvaluatingActivityType = null;
        });

        // Next button
        nextBtn?.addEventListener("click", () => {
            const allQuestions = page1.querySelectorAll('.question');
            let allAnswered = true;

            allQuestions.forEach(question => {
                const radios = question.querySelectorAll('input[type="radio"]');
                const answered = Array.from(radios).some(radio => radio.checked);
                if (!answered) {
                    allAnswered = false;
                    question.style.border = "2px solid #ff4444";
                    question.style.padding = "10px";
                    question.style.borderRadius = "5px";
                } else {
                    question.style.border = "";
                    question.style.padding = "";
                }
            });

            if (!allAnswered) {
                alert("Please answer all required questions before proceeding!");
                return;
            }

            page1.style.display = "none";
            page2.style.display = "block";
        });

        // Submit evaluation - UPDATED VERSION
submitBtn?.addEventListener("click", async () => {
    if (!currentEvaluatingActivityId || !currentEvaluatingActivityType) {
        alert("No activity selected for evaluation.");
        return;
    }

    try {
        // Collect ratings - CORRECTED VERSION
        const ratings = {};
        
        // Get all question inputs
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const name = radio.getAttribute('name');
            
            // Extract question ID from name="ratings[1]" format
            if (name && name.startsWith('ratings[')) {
                const questionId = name.match(/ratings\[(\d+)\]/)?.[1];
                if (questionId) {
                    ratings[questionId] = parseInt(radio.value);
                }
            }
        });

        // Validate that all questions are answered
        const totalQuestions = document.querySelectorAll('.question').length;
        if (Object.keys(ratings).length < totalQuestions) {
            alert("Please answer all required questions before submitting!");
            return;
        }

        // Prepare evaluation data
        const evaluationData = {
            ratings: ratings,
            comments: comments.value.trim()
        };

        // Add activity ID based on type
        if (currentEvaluatingActivityType === 'event') {
            evaluationData.event_id = currentEvaluatingActivityId;
        } else if (currentEvaluatingActivityType === 'program') {
            evaluationData.program_id = currentEvaluatingActivityId;
        }

        console.log('Submitting evaluation:', evaluationData);

        // Submit evaluation
        const response = await fetch('/evaluation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(evaluationData)
        });

        console.log('Response status:', response.status);

        // Try to parse response
        let result;
        try {
            const text = await response.text();
            console.log('Raw response:', text);
            
            if (!text) {
                throw new Error('Empty response from server');
            }
            
            result = JSON.parse(text);
            console.log('Parsed response:', result);
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            throw new Error('Invalid response from server');
        }

        if (result.success) {
            evaluationModal.style.display = "none";
            successModal.style.display = "flex";

            // Update UI
            const evaluatedBtn = document.querySelector(`.evaluate-btn[data-activity-id="${currentEvaluatingActivityId}"][data-activity-type="${currentEvaluatingActivityType}"]`);
            if (evaluatedBtn) {
                evaluatedBtn.innerHTML = '<i class="fa-solid fa-check"></i> Evaluated';
                evaluatedBtn.classList.add('done');
                evaluatedBtn.disabled = true;

                const statusDot = evaluatedBtn.closest('.program-card').querySelector('.status-dot');
                if (statusDot) {
                    statusDot.classList.add('completed');
                    statusDot.innerHTML = '<i class="fas fa-check"></i>';
                }
            }

            currentEvaluatingActivityId = null;
            currentEvaluatingActivityType = null;
        } else {
            alert(result.error || 'Failed to submit evaluation. Please try again.');
        }

    } catch (error) {
        console.error('Error submitting evaluation:', error);
        alert('Failed to submit evaluation: ' + error.message);
    }
});

        okBtn?.addEventListener("click", () => {
            successModal.style.display = "none";
            window.location.reload();
        });

        window.addEventListener("click", e => {
            if (e.target === evaluationModal) evaluationModal.style.display = "none";
            if (e.target === successModal) successModal.style.display = "none";
        });

        // Handle anchor links
        if (window.location.hash) {
            const targetElement = document.querySelector(window.location.hash);
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                    targetElement.style.backgroundColor = '#f8f9fa';
                    targetElement.style.transition = 'background-color 2s';
                    setTimeout(() => {
                        targetElement.style.backgroundColor = '';
                    }, 2000);
                }, 500);
            }
        }

        // ================= Mobile Sidebar =================
        const mobileBtn = document.getElementById('mobileMenuBtn');
        
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
    });

    // Logout confirmation
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
</body>
</html>