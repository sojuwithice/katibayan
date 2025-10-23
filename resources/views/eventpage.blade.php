<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Events & Programs</title>
  <link rel="stylesheet" href="{{ asset('css/eventpage.css') }}">
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
          // Use the variables passed from controller, with fallbacks
          $today = $today ?? Carbon::today();
          $currentDateTime = $currentDateTime ?? Carbon::now();
          
          // FIXED: Show ALL launched events happening today regardless of time
          $validTodayEvents = $todayEvents->filter(function($event) use ($today) {
              if (!$event->is_launched) {
                  return false;
              }
              
              $eventDate = $event->event_date instanceof Carbon 
                ? $event->event_date 
                : Carbon::parse($event->event_date);
              
              // Simply check if the event date is today and it's launched
              return $eventDate->isSameDay($today);
          });

          // Get today's programs
          $todayPrograms = $programs->filter(function($program) use ($today) {
              $programDate = $program->event_date instanceof Carbon 
                ? $program->event_date 
                : Carbon::parse($program->event_date);
              
              return $programDate->isSameDay($today);
          });
        @endphp

        @if($validTodayEvents->count() > 0 || $todayPrograms->count() > 0)
          <!-- Today's Events -->
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
                  <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image">
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
                  // Check if event has ended to show appropriate button
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

          <!-- Today's Programs -->
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
                  <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                @else
                  <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg==" alt="Program Image">
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
                  // Check if program has ended to show appropriate button
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

    <!-- Upcoming Activities -->
    <section class="upcoming-section">
      <h2>UPCOMING ACTIVITIES</h2>
      
      <div class="committee-bar">
        <h3>Committee</h3>
        <div class="committee-tabs">
          <button class="committee-tab active" data-category="all">All</button>
          <button class="committee-tab" data-category="active_citizenship">Active Citizenship</button>
          <button class="committee-tab" data-category="economic_empowerment">Economic Empowerment</button>
          <button class="committee-tab" data-category="education">Education</button>
          <button class="committee-tab" data-category="health">Health</button>
          <button class="committee-tab" data-category="sports">Sports</button>
        </div>
      </div>
    </section>

    <!-- Programs Section -->
    <section class="programs-section">
      <div class="programs-bar">
        <h3>Launched Events</h3>
        <a href="#" class="see-all">See All</a>
      </div>

      <div class="programs-scroll">
        <div class="programs-container">
          @php
            // FIXED: Only show launched events that are in the future (considering date AND time)
            $launchedEvents = $events->filter(function($event) use ($currentDateTime) {
                if (!$event->is_launched) {
                    return false;
                }
                
                $eventDate = $event->event_date instanceof Carbon 
                  ? $event->event_date 
                  : Carbon::parse($event->event_date);
                
                // Create full datetime object for the event
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
                
                // Only show events that haven't happened yet
                return $eventDateTime->gt($currentDateTime);
            });
          @endphp

          @if($launchedEvents->count() > 0)
            @foreach($launchedEvents as $event)
              <article class="program-card" data-category="{{ $event->category }}">
                <div class="program-media">
                  @if($event->image && Storage::disk('public')->exists($event->image))
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                  @else
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image">
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

    <!-- New Programs Section -->
    <section class="programs-section">
      <div class="programs-bar">
        <h3>Available Programs</h3>
        <a href="#" class="see-all">See All</a>
      </div>

      <div class="programs-scroll">
        <div class="programs-container">
          @php
            // Get upcoming programs (future dates)
            $upcomingPrograms = $programs->filter(function($program) use ($currentDateTime) {
                $programDate = $program->event_date instanceof Carbon 
                  ? $program->event_date 
                  : Carbon::parse($program->event_date);
                
                // Create full datetime object for the program
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
                
                // Only show programs that haven't happened yet
                return $programDateTime->gt($currentDateTime);
            });
          @endphp

          @if($upcomingPrograms->count() > 0)
            @foreach($upcomingPrograms as $program)
              <article class="program-card" data-category="{{ $program->category }}">
                <div class="program-media">
                  @if($program->display_image && Storage::disk('public')->exists($program->display_image))
                    <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                  @else
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI5MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==" alt="Program Image">
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

    <!-- List of Events (stacked) -->
    <section class="events-list-section">
      <div class="section-header">
        <h3>All Upcoming Events & Programs</h3>
        <a href="#" class="see-all">See All</a>
      </div>

      <div class="events-wrapper">
        @php
          // FIXED: Only show events that haven't happened yet (considering date AND time)
          $upcomingEvents = $events->filter(function($event) use ($currentDateTime) {
              if (!$event->is_launched) {
                  return false;
              }
              
              $eventDate = $event->event_date instanceof Carbon 
                ? $event->event_date 
                : Carbon::parse($event->event_date);
              
              // Create full datetime object for the event
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
              
              // Only show events that haven't happened yet
              return $eventDateTime->gt($currentDateTime);
          });

          // Combine events and programs for the stacked list
          $allUpcomingActivities = $upcomingEvents->merge($upcomingPrograms)->sortBy('event_date');
        @endphp

        @if($allUpcomingActivities->count() > 0)
          @foreach($allUpcomingActivities as $activity)
            <article class="event-card" data-category="{{ $activity->category }}">
              <div class="event-left">
                <div class="event-thumb upcoming">
                  @if(isset($activity->image) && $activity->image && Storage::disk('public')->exists($activity->image))
                    <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                  @elseif(isset($activity->display_image) && $activity->display_image && Storage::disk('public')->exists($activity->display_image))
                    <img src="{{ asset('storage/' . $activity->display_image) }}" alt="{{ $activity->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Qcm9ncmFtIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                  @else
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Iy93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5BY3Rpdml0eSBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Activity Image">
                  @endif
                  <span class="activity-type">
                    {{ isset($activity->image) ? 'Event' : 'Program' }}
                  </span>
                </div>
              </div>

              <div class="event-right">
                @if(isset($activity->image))
                  <a class="view-details view-event-details" href="#" data-event-id="{{ $activity->id }}">View more details</a>
                @else
                  <a class="view-details view-program-details" href="#" data-program-id="{{ $activity->id }}">View more details</a>
                @endif
                <h4 class="event-title">{{ $activity->title }}</h4>
                <div class="event-meta">
                  <p><i class="fas fa-location-dot"></i> {{ $activity->location }}</p>
                  <p><i class="fas fa-users"></i> Committee on {{ ucfirst(str_replace('_', ' ', $activity->category)) }}</p>
                </div>

                <div class="event-footer">
                  <div class="event-when">
                    <div class="when-label">WHEN</div>
                    <div class="event-date">
                      @php
                        $activityDate = $activity->event_date instanceof Carbon 
                          ? $activity->event_date 
                          : Carbon::parse($activity->event_date);
                      @endphp
                      {{ $activityDate->format('F d, Y') }} | {{ $activity->event_time ? Carbon::parse($activity->event_time)->format('h:i A') : 'Time not specified' }}
                    </div>
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        @else
          <div class="no-events-message">
            <i class="fas fa-calendar-times"></i>
            <p>No upcoming events or programs scheduled in your barangay.</p>
          </div>
        @endif
      </div>
    </section>
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
        <a href="{{ route('attendancepage') }}" class="register-modal-btn">Register for Event</a>
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
                  <button type="submit" class="submit-btn">
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

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === Lucide icons ===
      if (typeof lucide !== "undefined" && lucide.createIcons) {
        lucide.createIcons();
      }

      // === Elements ===
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');

      // Submenus
      const profileItem = document.querySelector('.profile-item');
      const profileLink = profileItem?.querySelector('.profile-link');

      // Profile & notifications dropdown (topbar)
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

      // === Committee Filtering ===
      const committeeTabs = document.querySelectorAll('.committee-tab');
      const programCards = document.querySelectorAll('.program-card');
      const eventCards = document.querySelectorAll('.event-card');

      committeeTabs.forEach(tab => {
        tab.addEventListener('click', () => {
          // Remove active class from all tabs
          committeeTabs.forEach(t => t.classList.remove('active'));
          // Add active class to clicked tab
          tab.classList.add('active');
          
          const category = tab.getAttribute('data-category');
          
          // Filter program cards
          programCards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
              card.style.display = 'block';
            } else {
              card.style.display = 'none';
            }
          });
          
          // Filter event cards
          eventCards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
              card.style.display = 'flex';
            } else {
              card.style.display = 'none';
            }
          });
        });
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
          
          // Populate modal with event data
          document.getElementById('modalEventTitle').textContent = event.title;
          document.getElementById('modalEventCategory').textContent = event.category ? event.category.replace(/_/g, ' ').toUpperCase() : 'No category';
          document.getElementById('modalEventDateTime').textContent = event.event_date_time || 'Date not specified';
          document.getElementById('modalEventLocation').textContent = event.location || 'Location not specified';
          document.getElementById('modalEventPublisher').textContent = event.published_by || 'Publisher not specified';
          document.getElementById('modalEventDescription').textContent = event.description || 'No description available.';
          
          // Handle event image
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
          
          // Update register button with event ID
          const registerBtn = document.querySelector('.register-modal-btn');
          if (registerBtn) {
            registerBtn.href = `{{ route('attendancepage') }}?event_id=${eventId}`;
          }
          
          eventModal.style.display = 'block';
        } catch (error) {
          console.error('Error fetching event details:', error);
          alert('Error loading event details. Please try again.');
        }
      }

      // Helper function to create custom field HTML
      function createCustomFieldHTML(field, index) {
        const fieldId = `custom_field_${index}`;
        const fieldName = `registration_data[custom_fields][${fieldId}]`;
        
        switch (field.field_type) {
          case 'text':
            return `
              <div class="form-group">
                <label class="form-label">${field.label} ${field.required ? '*' : ''}</label>
                <input type="text" 
                       class="form-input" 
                       name="${fieldName}" 
                       placeholder="${field.label}"
                       ${field.required ? 'required' : ''}>
              </div>
            `;
            
          case 'radio':
            let radioOptions = '';
            if (field.options && field.options.length > 0) {
              field.options.forEach((option, optIndex) => {
                radioOptions += `
                  <div class="radio-option">
                    <input type="radio" 
                           id="${fieldId}_${optIndex}" 
                           name="${fieldName}" 
                           value="${option}"
                           ${field.required ? 'required' : ''}>
                    <label for="${fieldId}_${optIndex}">${option}</label>
                  </div>
                `;
              });
            }
            return `
              <div class="form-group">
                <label class="form-label">${field.label} ${field.required ? '*' : ''}</label>
                <div class="radio-group">
                  ${radioOptions}
                </div>
              </div>
            `;
            
          case 'select':
            let selectOptions = '<option value="">Select an option</option>';
            if (field.options && field.options.length > 0) {
              field.options.forEach(option => {
                selectOptions += `<option value="${option}">${option}</option>`;
              });
            }
            return `
              <div class="form-group">
                <label class="form-label">${field.label} ${field.required ? '*' : ''}</label>
                <select class="form-input" 
                        name="${fieldName}"
                        ${field.required ? 'required' : ''}>
                  ${selectOptions}
                </select>
              </div>
            `;
            
          case 'file':
            return `
              <div class="form-group">
                <label class="form-label">${field.label} ${field.required ? '*' : ''}</label>
                <input type="file" 
                       class="form-input" 
                       name="${fieldName}"
                       ${field.required ? 'required' : ''}>
              </div>
            `;
            
          default:
            return '';
        }
      }

      // Enhanced Program Details Modal Functionality - FIXED VERSION
      async function showProgramDetails(programId) {
        try {
          // Show loading state
          const programModal = document.getElementById('programModal');
          programModal.style.display = 'block';
          
          // Create loading element if it doesn't exist
          let loadingElement = programModal.querySelector('.loading-program');
          if (!loadingElement) {
            loadingElement = document.createElement('div');
            loadingElement.className = 'loading-program';
            loadingElement.innerHTML = `
              <i class="fas fa-spinner fa-spin"></i>
              <p>Loading program details...</p>
            `;
            programModal.querySelector('.modal-body').appendChild(loadingElement);
          } else {
            loadingElement.style.display = 'block';
          }

          const response = await fetch(`/programs/${programId}`);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          
          const program = await response.json();
          
          // Hide loading
          loadingElement.style.display = 'none';

          // SAFELY populate modal with program data - check if elements exist first
          const modalProgramTitle = document.getElementById('modalProgramTitle');
          const modalProgramCategory = document.getElementById('modalProgramCategory');
          const modalProgramCategoryText = document.getElementById('modalProgramCategoryText');
          const modalProgramDateTime = document.getElementById('modalProgramDateTime');
          const modalProgramLocation = document.getElementById('modalProgramLocation');
          const modalProgramPublisher = document.getElementById('modalProgramPublisher');
          const modalProgramDescription = document.getElementById('modalProgramDescription');

          if (modalProgramTitle) modalProgramTitle.textContent = program.title || 'No title';
          if (modalProgramCategory) modalProgramCategory.textContent = program.category ? 
            program.category.replace(/_/g, ' ').toUpperCase() : 'No category';
          if (modalProgramCategoryText) modalProgramCategoryText.textContent = program.category ? 
            program.category.replace(/_/g, ' ') : 'Not specified';

          // Format date and time - FIXED: Proper date parsing
          let dateTimeString = 'Date not specified';
          if (program.event_date) {
            try {
              const eventDate = new Date(program.event_date);
              if (!isNaN(eventDate.getTime())) {
                dateTimeString = eventDate.toLocaleDateString('en-US', { 
                  year: 'numeric', 
                  month: 'long', 
                  day: 'numeric' 
                });
                
                // Add time if available
                if (program.event_time) {
                  try {
                    // Parse time properly - handle different time formats
                    let timeString = program.event_time;
                    // If time is in 24-hour format, convert to 12-hour
                    if (timeString.includes(':')) {
                      const [hours, minutes] = timeString.split(':');
                      const hour = parseInt(hours);
                      const ampm = hour >= 12 ? 'PM' : 'AM';
                      const displayHour = hour % 12 || 12;
                      timeString = `${displayHour}:${minutes} ${ampm}`;
                    }
                    dateTimeString += ` at ${timeString}`;
                  } catch (timeError) {
                    console.warn('Error parsing time:', timeError);
                    // If time parsing fails, just use the date
                  }
                } else {
                  dateTimeString += ' (All Day)';
                }
              }
            } catch (dateError) {
              console.warn('Error parsing date:', dateError);
            }
          }
          
          if (modalProgramDateTime) modalProgramDateTime.textContent = dateTimeString;
          if (modalProgramLocation) modalProgramLocation.textContent = program.location || 'Location not specified';
          if (modalProgramPublisher) modalProgramPublisher.textContent = program.published_by || 'Publisher not specified';
          if (modalProgramDescription) modalProgramDescription.textContent = program.description || 'No description available.';

          // Handle program image
          const modalImage = document.getElementById('modalProgramImage');
          const noImage = document.querySelector('.no-image');
          if (modalImage && noImage) {
            if (program.display_image) {
              modalImage.src = program.display_image;
              modalImage.style.display = 'block';
              noImage.style.display = 'none';
              modalImage.alt = program.title || 'Program Image';
              
              modalImage.onerror = function() {
                this.style.display = 'none';
                noImage.style.display = 'flex';
              };
            } else {
              modalImage.style.display = 'none';
              noImage.style.display = 'flex';
            }
          }

          // Handle registration type
          const linkRegistration = document.getElementById('linkRegistration');
          const createRegistration = document.getElementById('createRegistration');
          const noRegistration = document.getElementById('noRegistration');

          // Hide all registration sections first
          if (linkRegistration) linkRegistration.style.display = 'none';
          if (createRegistration) createRegistration.style.display = 'none';
          if (noRegistration) noRegistration.style.display = 'none';

          if (program.registration_type === 'link' && program.link_source) {
            // Show link registration
            if (linkRegistration) {
              linkRegistration.style.display = 'block';
              const programLink = document.getElementById('modalProgramLink');
              if (programLink) {
                programLink.href = program.link_source;
                programLink.textContent = program.link_source;
              }
            }
          } else if (program.registration_type === 'create') {
            // Show create registration form
            if (createRegistration) {
              createRegistration.style.display = 'block';
              
              // Set hidden program ID
              const hiddenProgramId = document.getElementById('hiddenProgramId');
              if (hiddenProgramId) hiddenProgramId.value = program.id;
              
              // Set registration form title and description
              const registrationFormTitle = document.getElementById('registrationFormTitle');
              const registrationDescription = document.getElementById('registrationDescription');
              
              if (registrationFormTitle) {
                registrationFormTitle.textContent = 
                  program.registration_title || `${program.title} - Registration Form`;
              }
              if (registrationDescription) {
                registrationDescription.textContent = 
                  program.registration_description || 'Please fill out the form below to register for this program.';
              }

              // Set registration period - FIXED: Proper date/time parsing
              const registrationOpenPeriod = document.getElementById('registrationOpenPeriod');
              const registrationClosePeriod = document.getElementById('registrationClosePeriod');
              
              if (registrationOpenPeriod && registrationClosePeriod) {
                // Format open period
                let openPeriod = 'Not specified';
                if (program.registration_open_date) {
                  try {
                    const openDate = new Date(program.registration_open_date);
                    if (!isNaN(openDate.getTime())) {
                      openPeriod = openDate.toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                      });
                      
                      if (program.registration_open_time) {
                        try {
                          let openTimeString = program.registration_open_time;
                          if (openTimeString.includes(':')) {
                            const [hours, minutes] = openTimeString.split(':');
                            const hour = parseInt(hours);
                            const ampm = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;
                            openTimeString = `${displayHour}:${minutes} ${ampm}`;
                          }
                          openPeriod += ` at ${openTimeString}`;
                        } catch (timeError) {
                          console.warn('Error parsing open time:', timeError);
                        }
                      }
                    }
                  } catch (dateError) {
                    console.warn('Error parsing open date:', dateError);
                  }
                }
                
                // Format close period
                let closePeriod = 'Not specified';
                if (program.registration_close_date) {
                  try {
                    const closeDate = new Date(program.registration_close_date);
                    if (!isNaN(closeDate.getTime())) {
                      closePeriod = closeDate.toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                      });
                      
                      if (program.registration_close_time) {
                        try {
                          let closeTimeString = program.registration_close_time;
                          if (closeTimeString.includes(':')) {
                            const [hours, minutes] = closeTimeString.split(':');
                            const hour = parseInt(hours);
                            const ampm = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;
                            closeTimeString = `${displayHour}:${minutes} ${ampm}`;
                          }
                          closePeriod += ` at ${closeTimeString}`;
                        } catch (timeError) {
                          console.warn('Error parsing close time:', timeError);
                        }
                      }
                    }
                  } catch (dateError) {
                    console.warn('Error parsing close date:', dateError);
                  }
                }

                registrationOpenPeriod.textContent = openPeriod;
                registrationClosePeriod.textContent = closePeriod;
              }

              // Render custom fields - FIXED: Check for custom_fields structure
              const dynamicFieldsContainer = document.getElementById('dynamicCustomFields');
              if (dynamicFieldsContainer) {
                dynamicFieldsContainer.innerHTML = '';
                
                // Check if custom_fields exists and is an array
                if (program.custom_fields && Array.isArray(program.custom_fields)) {
                  let hasCustomFields = false;
                  
                  program.custom_fields.forEach((field, index) => {
                    // Check if this is a custom field (not auto-filled)
                    if (field.type === 'custom' || field.editable === true) {
                      const fieldHtml = createCustomFieldHTML(field, index);
                      dynamicFieldsContainer.innerHTML += fieldHtml;
                      hasCustomFields = true;
                    }
                  });
                  
                  if (!hasCustomFields) {
                    dynamicFieldsContainer.innerHTML = '<p class="no-custom-fields">No additional registration fields required.</p>';
                  }
                } else {
                  dynamicFieldsContainer.innerHTML = '<p class="no-custom-fields">No additional registration fields required.</p>';
                }
              }

              // Reset form
              const registrationForm = document.getElementById('programRegistrationForm');
              if (registrationForm) {
                registrationForm.reset();
                registrationForm.style.display = 'block';
                
                // Remove any existing success messages
                const successMessage = registrationForm.querySelector('.registration-success');
                if (successMessage) {
                  successMessage.remove();
                }

                // Handle form submission
                registrationForm.onsubmit = function(e) {
                  e.preventDefault();
                  submitProgramRegistration(program.id, new FormData(this));
                };
              }
            }
          } else {
            // No registration available
            if (noRegistration) noRegistration.style.display = 'block';
          }

        } catch (error) {
          console.error('Error fetching program details:', error);
          const programModal = document.getElementById('programModal');
          if (programModal) {
            const modalBody = programModal.querySelector('.modal-body');
            if (modalBody) {
              modalBody.innerHTML = `
                <div class="loading-program">
                  <i class="fas fa-exclamation-triangle"></i>
                  <p>Error loading program details. Please try again.</p>
                  <small>${error.message}</small>
                </div>
              `;
            }
          }
        }
      }

      // Enhanced Program Registration Form Submission - COMPLETELY FIXED VERSION
      async function submitProgramRegistration(programId, formData) {
        try {
          const submitBtn = document.querySelector('#programRegistrationForm .submit-btn');
          if (!submitBtn) {
            throw new Error('Submit button not found');
          }
          
          const originalText = submitBtn.innerHTML;
          
          // Show loading state
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
          submitBtn.disabled = true;

          // Prepare registration data for JSON storage
          const registrationData = {};
          
          // Collect all custom field values
          const customFields = {};
          for (let [key, value] of formData.entries()) {
            if (key.startsWith('registration_data[custom_fields]')) {
              // Extract field name from the key
              const fieldMatch = key.match(/registration_data\[custom_fields\]\[(.*?)\]/);
              if (fieldMatch && fieldMatch[1]) {
                customFields[fieldMatch[1]] = value;
              }
            }
          }
          
          registrationData.custom_fields = customFields;

          console.log('Registration data to submit:', registrationData);

          // Create final form data with proper structure
          const finalFormData = new FormData();
          finalFormData.append('program_id', programId);
          finalFormData.append('registration_data', JSON.stringify(registrationData));
          finalFormData.append('_token', '{{ csrf_token() }}');

          const response = await fetch('/program-registrations', {
            method: 'POST',
            body: finalFormData,
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            }
          });

          const result = await response.json();

          if (response.ok && result.success) {
            // Show success message
            const registrationForm = document.querySelector('.registration-form-fields');
            if (registrationForm) {
              registrationForm.innerHTML = `
                <div class="registration-success">
                  <i class="fas fa-check-circle"></i>
                  <h4>Registration Submitted Successfully!</h4>
                  <p>Thank you for registering for this program. We'll contact you with further details.</p>
                  <p><strong>Reference ID:</strong> ${result.reference_id || 'N/A'}</p>
                  <p><strong>Submitted on:</strong> ${result.registration?.submitted_at || 'Just now'}</p>
                  <div class="success-actions">
                    <button type="button" class="close-success-btn" onclick="closeProgramModal()">Close</button>
                  </div>
                </div>
              `;
            }
            
            // The registration will now appear in the youth registration list
            console.log('Registration successful! Reference ID:', result.reference_id);
            
          } else {
            throw new Error(result.message || 'Failed to submit registration');
          }

        } catch (error) {
          console.error('Error submitting registration:', error);
          alert('Error submitting registration: ' + error.message);
          
          // Reset button
          const submitBtn = document.querySelector('#programRegistrationForm .submit-btn');
          if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Registration';
            submitBtn.disabled = false;
          }
        }
      }

      // Helper function to close program modal
      function closeProgramModal() {
        const programModal = document.getElementById('programModal');
        if (programModal) {
          programModal.style.display = 'none';
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

      // Logout confirmation
      function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      }

      // Make closeProgramModal available globally
      window.closeProgramModal = closeProgramModal;
    });
  </script>

</body>
</html>