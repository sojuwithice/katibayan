<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Events</title>
  <link rel="stylesheet" href="{{ asset('css/sk-eventpage.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
  <!-- Fixed QR Code library imports -->
  
</head>
<body>
  
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

      <a href="{{ route('sk-eventpage') }}" class="active">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <div class="evaluation-item nav-item">
        <a href="{{ route('sk-evaluation-feedback') }}" class="evaluation-link nav-link">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="#">Feedbacks</a>
          <a href="#">Polls</a>
          <a href="#">Suggestion Box</a>
        </div>
      </div>

      <a href="#">
        <i data-lucide="file-chart-column"></i>
        <span class="label">Reports</span>
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
            
            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- Event and Program Section -->
    <section class="event-section">
      <!-- Event Header -->
      <div class="event-header">
        <h2>Event and Program</h2>
        
        <!-- Barangay Filter Indicator -->
        <div class="barangay-indicator">
          <i class="fas fa-map-marker-alt"></i>
          <span>Showing events for your barangay</span>
        </div>

        <div class="create-activity-dropdown">
          <a href="#" class="create-activity">
            Create Activity <i class="fa-solid fa-calendar-plus"></i>
          </a>

          <ul class="dropdown-menu">
            <li>
              <a href="{{ route('create-event') }}">
                <span class="dot blue"></span> Event
              </a>
            </li>
            <li>
              <a href="{{ route('create-program') }}">
                <span class="dot yellow"></span> Program
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Display Success/Error Messages -->
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-error">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Tabs + Dropdown Container - MOVED TO TOP -->
      <div class="tabs-container">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab active" data-filter="all">All</button>
          <button class="tab" data-filter="upcoming">Upcoming</button>
          <button class="tab" data-filter="ongoing">Ongoing</button>
          <button class="tab" data-filter="completed">Completed</button>
        </div>

        <!-- Custom Category Dropdown -->
        <div class="category-dropdown">
          <label for="category">Category:</label>
          <div class="custom-select" id="category" tabindex="0" role="listbox" aria-haspopup="listbox">
            <div class="selected" data-value="all">
              <span class="selected-text">All</span>
              <i data-lucide="chevron-down" class="dropdown-icon"></i>
            </div>
            <ul class="options" role="presentation">
              <li data-value="all" role="option">All</li>
              <li data-value="active_citizenship" role="option">Active Citizenship</li>
              <li data-value="economic_empowerment" role="option">Economic Empowerment</li>
              <li data-value="education" role="option">Education</li>
              <li data-value="health" role="option">Health</li>
              <li data-value="sports" role="option">Sports</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Check if there are events -->
      @if($events->count() > 0)
        
        <!-- Happening Today Section -->
        @if($todayEvents->count() > 0)
          <div class="event-category happening" data-status="ongoing">
            <span class="tag">Happening Today</span>
            @foreach($todayEvents as $event)
              @php
                // CORRECTED: Determine current status based on date, time, and launch status
                $currentDateTime = now();
                $eventDateTime = \Carbon\Carbon::parse($event->event_date->format('Y-m-d') . ' ' . $event->event_time);
                
                if ($eventDateTime->isPast()) {
                    // Event date/time has passed - it's completed
                    $currentStatus = 'completed';
                } elseif ($event->is_launched && $eventDateTime->isToday()) {
                    // Event is launched AND happening today - it's ongoing
                    $currentStatus = 'ongoing';
                } elseif ($event->is_launched && $eventDateTime->isFuture()) {
                    // Event is launched but date is in future - it should still be upcoming
                    $currentStatus = 'upcoming';
                } else {
                    // Event is not launched and date is in future - it's upcoming
                    $currentStatus = 'upcoming';
                }
              @endphp
              <div class="event-card" data-event-id="{{ $event->id }}" data-status="{{ $currentStatus }}" data-category="{{ $event->category }}">
                <div class="event-date">
                  <span class="day">{{ $event->event_date->format('D') }}</span>
                  <span class="num">{{ $event->event_date->format('d') }}</span>
                </div>
                <div class="event-details">
                  <h3>{{ $event->title }}</h3>
                  <p><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                  <p><i class="fas fa-users"></i> Committee on {{ ucfirst(str_replace('_', ' ', $event->category)) }}</p>
                  @if($event->description)
                    <div class="event-description">
                      <p>{{ Str::limit($event->description, 150) }}</p>
                    </div>
                  @endif
                  <div class="event-datetime">
                    <span class="label">DATE AND TIME</span>
                    <span class="divider"></span>
                    <span class="value">{{ $event->event_date_time }}</span>
                  </div>
                </div>
                <div class="event-action">
                  @if(!$event->is_launched && $currentStatus === 'upcoming')
                    <button class="launch-btn" data-event-id="{{ $event->id }}">Launch Event</button>
                  @elseif($event->is_launched && $currentStatus === 'ongoing')
                    <span class="launched-badge">Launched</span>
                  @elseif($event->is_launched && $currentStatus === 'upcoming')
                    <span class="launched-badge">Launched</span>
                  @elseif($currentStatus === 'completed')
                    <span class="completed-badge">Completed</span>
                  @endif
                  
                  @if($currentStatus !== 'completed')
                    <a href="{{ route('edit-event', $event->id) }}" class="edit-btn">
                      Edit <i class="fa-solid fa-pen"></i>
                    </a>
                  @endif
                  
                  <button class="delete-btn" data-event-id="{{ $event->id }}">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        <!-- Grouped Events by Month -->
        @foreach($groupedEvents as $month => $eventsInMonth)
          <div class="event-category">
            <h4>{{ $month }}</h4>
            @foreach($eventsInMonth as $event)
              @php
                // CORRECTED: Determine current status based on date, time, and launch status
                $currentDateTime = now();
                $eventDateTime = \Carbon\Carbon::parse($event->event_date->format('Y-m-d') . ' ' . $event->event_time);
                
                if ($eventDateTime->isPast()) {
                    // Event date/time has passed - it's completed
                    $currentStatus = 'completed';
                } elseif ($event->is_launched && $eventDateTime->isToday()) {
                    // Event is launched AND happening today - it's ongoing
                    $currentStatus = 'ongoing';
                } elseif ($event->is_launched && $eventDateTime->isFuture()) {
                    // Event is launched but date is in future - it should still be upcoming
                    $currentStatus = 'upcoming';
                } else {
                    // Event is not launched and date is in future - it's upcoming
                    $currentStatus = 'upcoming';
                }
              @endphp
              <div class="event-card" data-event-id="{{ $event->id }}" data-status="{{ $currentStatus }}" data-category="{{ $event->category }}">
                <div class="event-date">
                  <span class="day">{{ $event->event_date->format('D') }}</span>
                  <span class="num">{{ $event->event_date->format('d') }}</span>
                </div>
                <div class="event-details">
                  <h3>{{ $event->title }}</h3>
                  <p><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                  <p><i class="fas fa-users"></i> Committee on {{ ucfirst(str_replace('_', ' ', $event->category)) }}</p>
                  @if($event->description)
                    <div class="event-description">
                      <p>{{ Str::limit($event->description, 150) }}</p>
                    </div>
                  @endif
                  <div class="event-datetime">
                    <span class="label">DATE AND TIME</span>
                    <span class="divider"></span>
                    <span class="value">{{ $event->event_date_time }}</span>
                  </div>
                </div>
                <div class="event-action">
                  @if(!$event->is_launched && $currentStatus === 'upcoming')
                    <button class="launch-btn" data-event-id="{{ $event->id }}">Launch Event</button>
                  @elseif($event->is_launched && $currentStatus === 'ongoing')
                    <span class="launched-badge">Launched</span>
                  @elseif($event->is_launched && $currentStatus === 'upcoming')
                    <span class="launched-badge">Launched</span>
                  @elseif($currentStatus === 'completed')
                    <span class="completed-badge">Completed</span>
                  @endif
                  
                  @if($currentStatus !== 'completed')
                    <a href="{{ route('edit-event', $event->id) }}" class="edit-btn">
                      Edit <i class="fa-solid fa-pen"></i>
                    </a>
                  @endif
                  
                  <button class="delete-btn" data-event-id="{{ $event->id }}">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        @endforeach

      @else
        <!-- No Events State -->
        <div class="no-events">
          <i class="fas fa-calendar-times"></i>
          <h3>No Events Yet in Your Barangay</h3>
          <p>Get started by creating your first event or program for your barangay.</p>
          <a href="{{ route('create-event') }}" class="btn-create-event">
            Create Your First Event
          </a>
        </div>
      @endif
    </section>

    <!-- Event Modal -->
    <div id="eventModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Launch Event</h2>
        <p><strong>Title</strong><br> <span id="modalEventTitle"></span></p>
        <p><strong>Committee on:</strong> <span id="modalEventCategory"></span></p>
        <img id="modalEventImage" src="" alt="Event Banner" class="event-banner" style="display: none;">
        <p><em>Event Date: <span id="modalEventDateTime"></span> &nbsp;&nbsp; Location: <span id="modalEventLocation"></span></em></p>
        <h3 id="modalEventDescriptionTitle"></h3>
        <p id="modalEventDescription"></p>
        <p class="published">
          Published by: <span id="modalEventPublisher"></span><br>
          Committee on <span id="modalEventCommittee"></span>
        </p>
        <div class="modal-actions">
          <a id="proceedPasscode" class="launch-btn" href="#">Generate QR & Passcode</a>

        </div>
      </div>
    </div>

    

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
      <div class="modal-content delete-modal">
        <div class="delete-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2>Delete Event</h2>
        <p>Are you sure you want to delete this event? This action cannot be undone.</p>
        <div class="modal-actions">
          <button id="confirmDelete" class="delete-btn-confirm">Delete</button>
          <button id="cancelDelete" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Initialize icons
      if (typeof lucide !== "undefined" && lucide.createIcons) lucide.createIcons();

      // Global variables
      let currentEventId = null;
      let currentStatusFilter = 'all';
      let currentCategoryFilter = 'all';
      let qrCodeInstance = null;

      // --- UI elements ---
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');
      const profileWrapper = document.querySelector('.profile-wrapper');
      const profileToggle = document.getElementById('profileToggle');
      const notifWrapper = document.querySelector(".notification-wrapper");

      // Sidebar toggle
      if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('open');
        });
      }

      // Evaluation submenu toggle
      const evaluationItem = document.querySelector('.evaluation-item');
      const evaluationLink = document.querySelector('.evaluation-link');
      evaluationLink?.addEventListener('click', (e) => {
        e.preventDefault();
        evaluationItem?.classList.toggle('open');
      });

      // Time auto-update
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

      // Notifications / profile dropdowns
      if (notifWrapper) {
        const bell = notifWrapper.querySelector(".fa-bell");
        bell?.addEventListener("click", (e) => {
          e.stopPropagation();
          notifWrapper.classList.toggle("active");
          profileWrapper?.classList.remove("active");
        });
      }

      if (profileWrapper && profileToggle) {
        profileToggle.addEventListener("click", (e) => {
          e.stopPropagation();
          profileWrapper.classList.toggle("active");
          notifWrapper?.classList.remove("active");
        });
      }

      // Close dropdowns when clicking outside
      document.addEventListener("click", (e) => {
        if (sidebar && !sidebar.contains(e.target) && menuToggle && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
        }
        if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
        if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
        document.querySelectorAll('.custom-select .options.show').forEach(o => o.classList.remove('show'));
      });

      // ========== TAB FILTERING FUNCTIONALITY ==========
      const tabs = document.querySelectorAll('.tab');
      
      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          // Remove active class from all tabs
          tabs.forEach(t => t.classList.remove('active'));
          // Add active class to clicked tab
          tab.classList.add('active');
          
          // Get the filter value
          currentStatusFilter = tab.getAttribute('data-filter');
          
          // Apply filters
          applyFilters();
        });
      });

      // Custom Select functionality for category filtering
      const customSelect = document.querySelector("#category");
      if (customSelect) {
        const selected = customSelect.querySelector(".selected");
        const options = customSelect.querySelector(".options");
        const items = options.querySelectorAll("li");

        selected.addEventListener("click", () => {
          const isOpen = options.style.display === "block";
          options.style.display = isOpen ? "none" : "block";
          customSelect.classList.toggle("open", !isOpen);
        });

        items.forEach(item => {
          item.addEventListener("click", () => {
            customSelect.querySelector(".selected-text").textContent = item.textContent;
            options.style.display = "none";
            customSelect.classList.remove("open");
            
            // Update category filter
            currentCategoryFilter = item.getAttribute('data-value');
            
            // Apply filters
            applyFilters();
          });
        });
      }

      // Apply both status and category filters
      function applyFilters() {
        const eventCards = document.querySelectorAll('.event-card');
        const eventCategories = document.querySelectorAll('.event-category');
        
        let hasVisibleEvents = false;

        eventCategories.forEach(category => {
          let categoryHasVisibleEvents = false;
          const categoryEventCards = category.querySelectorAll('.event-card');
          
          categoryEventCards.forEach(card => {
            const eventStatus = card.getAttribute('data-status');
            const eventCategory = card.getAttribute('data-category');
            
            // Check if event matches both status and category filters
            const statusMatch = currentStatusFilter === 'all' || eventStatus === currentStatusFilter;
            const categoryMatch = currentCategoryFilter === 'all' || eventCategory === currentCategoryFilter;
            
            if (statusMatch && categoryMatch) {
              card.style.display = 'flex';
              categoryHasVisibleEvents = true;
              hasVisibleEvents = true;
            } else {
              card.style.display = 'none';
            }
          });
          
          // Show/hide category header based on visible events
          const categoryHeader = category.querySelector('h4');
          const happeningTag = category.querySelector('.tag');
          
          if (categoryHeader || happeningTag) {
            if (categoryHasVisibleEvents) {
              category.style.display = 'block';
            } else {
              category.style.display = 'none';
            }
          }
        });

        // Show no events message if no events match filters
        const noEventsElement = document.querySelector('.no-events');
        if (!hasVisibleEvents && eventCards.length > 0) {
          if (!noEventsElement) {
            const noEventsHTML = `
              <div class="no-events">
                <i class="fas fa-calendar-times"></i>
                <h3>No Events Match Your Filters</h3>
                <p>Try adjusting your filter criteria to see more events.</p>
                <button class="btn-create-event" onclick="resetFilters()">
                  Reset Filters
                </button>
              </div>
            `;
            document.querySelector('.event-section').insertAdjacentHTML('beforeend', noEventsHTML);
          }
        } else if (noEventsElement && eventCards.length > 0) {
          noEventsElement.remove();
        }
      }

      // Reset filters function
      window.resetFilters = function() {
        // Reset tabs
        tabs.forEach(tab => {
          tab.classList.remove('active');
          if (tab.getAttribute('data-filter') === 'all') {
            tab.classList.add('active');
          }
        });
        
        // Reset category dropdown
        const selected = customSelect.querySelector(".selected");
        const selectedText = customSelect.querySelector(".selected-text");
        selected.setAttribute('data-value', 'all');
        selectedText.textContent = 'All';
        
        // Reset filter variables
        currentStatusFilter = 'all';
        currentCategoryFilter = 'all';
        
        // Apply reset
        applyFilters();
        
        // Remove no events message if it exists
        const noEventsElement = document.querySelector('.no-events');
        if (noEventsElement) {
          noEventsElement.remove();
        }
      };

      // SIMPLE Launch Event functionality using event delegation
      document.addEventListener('click', function(e) {
        // Check if the clicked element is a launch button
        if (e.target.classList.contains('launch-btn')) {
          e.preventDefault();
          e.stopPropagation();
          
          // Get the event ID from the button's data attribute
          const eventId = e.target.getAttribute('data-event-id');
          console.log('Launch button clicked, event ID:', eventId);
          
          if (!eventId) {
            console.log('Button clicked but no event ID found');
            return;
          }

          currentEventId = eventId;
          console.log('Current event ID set to:', currentEventId);

          // Fetch and show event details
          fetchEventDetails(currentEventId, e.target);
        }
        
        // Also handle delete buttons with event delegation
        if (e.target.classList.contains('delete-btn')) {
          e.preventDefault();
          currentEventId = e.target.getAttribute('data-event-id');
          document.getElementById("deleteModal").style.display = "block";
        }
      });

      // Function to fetch event details
      async function fetchEventDetails(eventId, button) {
        try {
          // Show loading state
          const originalText = button.textContent;
          button.disabled = true;
          button.textContent = 'Loading...';

          console.log('Fetching event details for ID:', eventId);
          const response = await fetch(`/events/${eventId}`, {
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
          });
          
          console.log('Response status:', response.status);
          
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          
          const event = await response.json();
          console.log('Event data received:', event);
          
          // Populate modal with event data
          document.getElementById('modalEventTitle').textContent = event.title || 'No title';
          document.getElementById('modalEventCategory').textContent = event.category ? ucfirst(event.category.replace(/_/g, ' ')) : 'No category';
          document.getElementById('modalEventDateTime').textContent = event.event_date_time || 'No date/time';
          document.getElementById('modalEventLocation').textContent = event.location || 'No location';
          document.getElementById('modalEventDescriptionTitle').textContent = event.title || 'Description';
          document.getElementById('modalEventDescription').textContent = event.description || 'No description provided.';
          document.getElementById('modalEventPublisher').textContent = event.published_by || 'Unknown';
          document.getElementById('modalEventCommittee').textContent = event.category ? ucfirst(event.category.replace(/_/g, ' ')) : 'Unknown';
          
          // Handle event image
          const modalImage = document.getElementById('modalEventImage');
          if (event.image) {
            console.log('Event image URL:', event.image);
            modalImage.src = event.image;
            modalImage.style.display = 'block';
            modalImage.alt = event.title || 'Event image';
            
            // Add error handling for image load
            modalImage.onerror = function() {
              console.error('Failed to load event image:', event.image);
              this.style.display = 'none';
            };
          } else {
            modalImage.style.display = 'none';
          }
          
          document.getElementById("eventModal").style.display = "block";
          
        } catch (error) {
          console.error('Error fetching event details:', error);
          alert('Error loading event details: ' . error.message);
        } finally {
          // Reset button state
          button.disabled = false;
          button.textContent = 'Launch Event';
        }
      }

      // Generate QR & Passcode
document.getElementById("proceedPasscode").addEventListener("click", async () => {
  if (!currentEventId) {
    alert('No event selected for QR generation');
    return;
  }

  const proceedBtn = document.getElementById("proceedPasscode");

  try {
    // Show loading state
    proceedBtn.disabled = true;
    proceedBtn.textContent = 'Generating...';

    // Generate random passcode
    const passcode = generateRandomPasscode();
    console.log('Generated passcode:', passcode);

    // Save passcode to database
    const passcodeResponse = await fetch(`/events/${currentEventId}/generate-passcode`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ passcode })
    });

    if (!passcodeResponse.ok) throw new Error('Failed to save passcode');
    const passcodeResult = await passcodeResponse.json();
    if (!passcodeResult.success) throw new Error(passcodeResult.error || 'Failed to save passcode');

    // Launch the event
    const launchResponse = await fetch(`/events/${currentEventId}/launch`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    });

    if (!launchResponse.ok) throw new Error('Failed to launch event');
    const launchResult = await launchResponse.json();
    if (!launchResult.success) throw new Error(launchResult.error || 'Failed to launch event');

    // ✅ Redirect directly to qr.blade.php
    window.location.href = `/events/${currentEventId}/qr`;

  } catch (error) {
    console.error('Error generating QR and passcode:', error);
    alert('Error: ' + error.message);
  } finally {
    // Reset button state
    proceedBtn.disabled = false;
    proceedBtn.textContent = 'Generate QR & Passcode';
  }
});

function generateRandomPasscode(length = 6) {
  const chars = '0123456789';
  return Array.from({ length }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
}



      // Create Activity dropdown
      const createActivityDropdown = document.querySelector(".create-activity-dropdown");
      const createActivityBtn = createActivityDropdown?.querySelector(".create-activity");

      if (createActivityDropdown && createActivityBtn) {
        createActivityBtn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          createActivityDropdown.classList.toggle("active");
        });

        document.addEventListener("click", (e) => {
          if (!createActivityDropdown.contains(e.target)) {
            createActivityDropdown.classList.remove("active");
          }
        });
      }

      // Utility functions
      function generateRandomPasscode() {
        return Math.random().toString(36).substring(2, 10).toUpperCase();
      }

      function ucfirst(str) {
        return str.charAt(0).toUpperCase() . str.slice(1);
      }

      // QR Code Generation
      async function generateQRCode(passcode) {
        const qrcodeContainer = document.getElementById("qrcode");
        if (!qrcodeContainer) {
          console.error('QR code container not found');
          return;
        }
        
        qrcodeContainer.innerHTML = "";
        
        try {
          // Method 1: Try QRCodeStyling first
          if (typeof QRCodeStyling !== 'undefined') {
            console.log('Using QRCodeStyling library');
            qrCodeInstance = new QRCodeStyling({
              width: 250,
              height: 250,
              type: "canvas",
              data: passcode,
              dotsOptions: {
                color: "#3C87C4",
                type: "rounded"
              },
              backgroundOptions: {
                color: "#ffffff",
              },
              imageOptions: {
                crossOrigin: "anonymous",
                margin: 0
              }
            });

            await qrCodeInstance.append(qrcodeContainer);
            console.log('QR Code generated successfully with QRCodeStyling');
            return;
          }
          
          // Method 2: Try basic QRCode library
          if (typeof QRCode !== 'undefined') {
            console.log('Using basic QRCode library');
            new QRCode(qrcodeContainer, {
              text: passcode,
              width: 250,
              height: 250,
              colorDark: "#3C87C4",
              colorLight: "#ffffff",
              correctLevel: QRCode.CorrectLevel.H
            });
            console.log('Basic QR Code generated successfully');
            return;
          }
          
          // Fallback: Create a simple visual representation
          console.log('Using fallback QR representation');
          qrcodeContainer.innerHTML = `
            <div style="text-align: center; padding: 20px; border: 2px dashed #ccc; border-radius: 10px; background: #f9f9f9;">
              <div style="font-size: 18px; font-weight: bold; color: #3C87C4; margin-bottom: 10px;">QR CODE</div>
              <div style="font-size: 14px; color: #666; margin-bottom: 15px;">Passcode for attendance:</div>
              <div style="font-size: 24px; font-weight: bold; color: #333; letter-spacing: 3px; font-family: monospace; background: #fff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">${passcode}</div>
              <div style="font-size: 12px; color: #999; margin-top: 15px;">(Scan this code with your device)</div>
            </div>
          `;
          
        } catch (error) {
          console.error('Error generating QR code:', error);
          // Ultimate fallback
          qrcodeContainer.innerHTML = `
            <div style="text-align:center; color:#666; padding: 20px;">
              <i class="fas fa-qrcode" style="font-size: 48px; color: #3C87C4; margin-bottom: 10px;"></i>
              <div style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Attendance Passcode</div>
              <div style="font-size: 20px; font-weight: bold; color: #333; letter-spacing: 2px; font-family: monospace; background: #f0f0f0; padding: 10px; border-radius: 5px;">${passcode}</div>
              <div style="font-size: 12px; color: #999; margin-top: 10px;">Share this code with attendees</div>
            </div>
          `;
        }
      }

      // Auto-update event statuses every minute
      setInterval(() => {
        const eventCards = document.querySelectorAll('.event-card');
        eventCards.forEach(card => {
          const eventId = card.getAttribute('data-event-id');
          const currentStatus = card.getAttribute('data-status');
          
          // Only check events that are not completed
          if (currentStatus !== 'completed') {
            // This would ideally make an API call to check status
            // For now, we'll just re-apply filters
            applyFilters();
          }
        });
      }, 60000); // Check every minute

      // Delete Confirmation
const confirmDeleteBtn = document.getElementById("confirmDelete");
const cancelDeleteBtn = document.getElementById("cancelDelete");
const deleteModal = document.getElementById("deleteModal");

confirmDeleteBtn.addEventListener("click", async () => {
  if (!currentEventId) return;

  try {
    const response = await fetch(`/events/${currentEventId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    });

    if (!response.ok) throw new Error('Failed to delete event');

    // Close modal
    deleteModal.style.display = 'none';

    // ✅ Reload page instead of showing success message
    window.location.reload();

  } catch (error) {
    console.error('Error deleting event:', error);
    alert('Error deleting event: ' + error.message);
  }
});

// Cancel Delete
cancelDeleteBtn.addEventListener("click", () => {
  deleteModal.style.display = "none";
});

    });
  </script>
</body>
</html>