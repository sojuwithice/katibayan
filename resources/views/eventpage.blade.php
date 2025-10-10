<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Events</title>
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
          </div>
        </div>
      </div>
    </header>

    <!-- Events and Programs -->
    <section class="events-section">
      <!-- LEFT -->
      <div class="events-left">
        <h2>Events and Programs</h2>
        <p>This page serves as your guide to upcoming events designed to empower the youth, foster engagement, and build stronger communities.</p>
      </div>

      <!-- RIGHT -->
      <div class="events-right">
        <h3>Today's Agenda 
          <i class="fa-solid fa-thumbtack"></i>
        </h3>

        @php
          // Use Carbon to get today's date and ensure proper date comparison
          use Carbon\Carbon;
          
          $today = Carbon::today()->format('Y-m-d');
          $currentDateTime = Carbon::now();
          
          // Debug: Check what events we have
          $allEvents = $events ?? collect();
          
          // Filter today's events - only show events that are happening today AND haven't ended
          $todayEvents = $allEvents->filter(function($event) use ($today, $currentDateTime) {
              // Handle both string and Carbon date formats
              $eventDate = $event->event_date;
              
              if ($eventDate instanceof Carbon) {
                  $eventDateFormatted = $eventDate->format('Y-m-d');
              } else {
                  $eventDateFormatted = Carbon::parse($eventDate)->format('Y-m-d');
              }
              
              // Check if event is today and is launched
              $isToday = $eventDateFormatted === $today;
              $isLaunched = $event->is_launched;
              
              // If event has end time, check if current time is before end time
              if ($event->end_time) {
                  $eventEndDateTime = Carbon::parse($eventDateFormatted . ' ' . $event->end_time);
                  $hasNotEnded = $currentDateTime->lt($eventEndDateTime);
              } else {
                  // If no end time, consider it as today's full day event
                  $hasNotEnded = true;
              }
              
              return $isToday && $isLaunched && $hasNotEnded;
          });
        @endphp

        @if($todayEvents->count() > 0)
          @foreach($todayEvents as $event)
            <div class="agenda-card">
              <div class="agenda-banner">
                <div class="agenda-date">
                  @php
                    // Ensure we have a Carbon date object for formatting
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
              <div class="agenda-actions">
                <a href="#" class="details-btn view-event-details" data-event-id="{{ $event->id }}">
                  See full details 
                  <span class="icon-circle">
                    <i class="fa-solid fa-chevron-right"></i>
                  </span>
                </a>
                <a href="{{ route('attendancepage') }}" class="attend-btn">Attend Now</a>
              </div>
            </div>
          @endforeach
        @else
          <div class="agenda-card no-events">
            <div class="agenda-banner">
              <div class="no-events-content">
                <i class="fas fa-calendar-times"></i>
                <p>No events scheduled for today</p>
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
            // Proper date comparison for launched events - only future events
            $launchedEvents = $allEvents->filter(function($event) use ($currentDateTime) {
                // Handle both string and Carbon date formats
                $eventDate = $event->event_date;
                
                if ($eventDate instanceof Carbon) {
                    $eventDateTime = $eventDate;
                } else {
                    $eventDateTime = Carbon::parse($eventDate);
                }
                
                // If event has specific time, use it for comparison
                if ($event->start_time) {
                    $eventFullDateTime = Carbon::parse($eventDateTime->format('Y-m-d') . ' ' . $event->start_time);
                } else {
                    $eventFullDateTime = $eventDateTime->startOfDay();
                }
                
                return $event->is_launched && $currentDateTime->lt($eventFullDateTime);
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
                  <a href="{{ route('attendancepage') }}" class="register-btn">REGISTER NOW!</a>
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
              <p>No launched events available at the moment.</p>
            </div>
          @endif
        </div>
      </div>
    </section>

    <!-- List of Events (stacked) -->
    <section class="events-list-section">
      <div class="section-header">
        <h3>All Upcoming Events</h3>
        <a href="#" class="see-all">See All</a>
      </div>

      <div class="events-wrapper">
        @php
          // Proper date comparison for upcoming events - only future events
          $upcomingEvents = $allEvents->filter(function($event) use ($currentDateTime) {
              // Handle both string and Carbon date formats
              $eventDate = $event->event_date;
              
              if ($eventDate instanceof Carbon) {
                  $eventDateTime = $eventDate;
              } else {
                  $eventDateTime = Carbon::parse($eventDate);
              }
              
              // If event has specific time, use it for comparison
              if ($event->start_time) {
                  $eventFullDateTime = Carbon::parse($eventDateTime->format('Y-m-d') . ' ' . $event->start_time);
              } else {
                  $eventFullDateTime = $eventDateTime->startOfDay();
              }
              
              return $event->is_launched && $currentDateTime->lt($eventFullDateTime);
          });
        @endphp

        @if($upcomingEvents->count() > 0)
          @foreach($upcomingEvents as $event)
            <article class="event-card" data-category="{{ $event->category }}">
              <div class="event-left">
                <div class="event-thumb upcoming">
                  @if($event->image && Storage::disk('public')->exists($event->image))
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                  @else
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZwwvdGV4dD48L3N2Zz4=" alt="Event Image">
                  @endif
                </div>
              </div>

              <div class="event-right">
                <a class="view-details view-event-details" href="#" data-event-id="{{ $event->id }}">View more details</a>
                <h4 class="event-title">{{ $event->title }}</h4>
                <div class="event-meta">
                  <p><i class="fas fa-location-dot"></i> {{ $event->location }}</p>
                  <p><i class="fas fa-users"></i> Committee on {{ ucfirst(str_replace('_', ' ', $event->category)) }}</p>
                </div>

                <div class="event-footer">
                  <div class="event-when">
                    <div class="when-label">WHEN</div>
                    <div class="event-date">
                      @php
                        // Ensure we have a Carbon date object for formatting
                        $eventDate = $event->event_date instanceof Carbon 
                          ? $event->event_date 
                          : Carbon::parse($event->event_date);
                      @endphp
                      {{ $eventDate->format('F d, Y') }} | {{ $event->formatted_time ?? 'Time not specified' }}
                    </div>
                  </div>
                  <div class="event-action">
                    <a href="{{ route('attendancepage') }}" class="register-event-btn">Register Now</a>
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        @else
          <div class="no-events-message">
            <i class="fas fa-calendar-times"></i>
            <p>No upcoming events scheduled.</p>
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

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === Lucide icons ===
      lucide.createIcons();

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
      menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');

        if (!sidebar.classList.contains('open')) {
          profileItem?.classList.remove('open');
        }
      });

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
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
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

      profileDropdown?.addEventListener('click', e => e.stopPropagation());

      // === Notifications dropdown toggle ===
      if (notifBell) {
        notifBell.addEventListener('click', (e) => {
          e.stopPropagation();
          notifWrapper.classList.toggle('active');
          profileWrapper?.classList.remove('active');
        });
      }

      notifDropdown?.addEventListener('click', e => e.stopPropagation());

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
      const closeModal = document.querySelector('.close');
      const closeBtn = document.querySelector('.close-btn');
      const viewDetailsButtons = document.querySelectorAll('.view-event-details');

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
          
          eventModal.style.display = 'block';
        } catch (error) {
          console.error('Error fetching event details:', error);
          alert('Error loading event details. Please try again.');
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

      // Close modal functions
      closeModal?.addEventListener('click', () => {
        eventModal.style.display = 'none';
      });

      closeBtn?.addEventListener('click', () => {
        eventModal.style.display = 'none';
      });

      eventModal?.addEventListener('click', (e) => {
        if (e.target === eventModal) {
          eventModal.style.display = 'none';
        }
      });

      // Truncate program descriptions
      document.querySelectorAll('.program-desc').forEach(el => {
        let text = el.textContent.trim();
        if (text.length > 100) {
          el.textContent = text.substring(0, 100) + '...';
        }
      });
    });
  </script>

</body>
</html>