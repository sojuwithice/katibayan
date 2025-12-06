<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    // Make CSRF token globally available
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  </script>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
  <title>KatiBayan - Service Offers</title>
  <link rel="stylesheet" href="{{ asset('css/serviceoffers.css') }}">
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

      <a href="{{ route('serviceoffers') }}" class="active">
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
    <div id="feedbackModal" class="modal-overlay" style="display: none;">
      <div class="modal-content">
        <span class="close-btn" id="closeFeedbackModal">&times;</span>

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
    <div id="successModal" class="modal-overlay" style="display: none;">
      <div class="modal-content">
        <div class="success-icon">
          <i class="fas fa-check"></i>
        </div>
        <h2>Submitted</h2>
        <p>Thank you for your feedback! Your thoughts help us improve.</p>
        <button id="closeSuccessModal" class="ok-btn">OK</button>
      </div>
    </div>

    <!-- Main Content -->
    <main class="content">
      <section class="assistance-section">
        <div class="assistance-card">
          <h2>Need Assistance?</h2>
          
          @if(empty($assistance_description) && empty($assistance_fb_link) && empty($assistance_msgr_link))
            <p>
              Contact information has not been set up yet by the SK Officials.
            </p>
          @else
            <p>
              {{ $assistance_description ?? 'You may contact us on our facebook page or you can directly message the SK Chairman through the link below' }}
            </p>
            <div class="assistance-links">
              
              @if(!empty($assistance_fb_link))
              <div class="link-item">
                <i class="fab fa-facebook"></i>
                <a href="{{ $assistance_fb_link }}" target="_blank" rel="noopener noreferrer">{{ $assistance_fb_link }}</a>
              </div>
              @endif
              
              @if(!empty($assistance_msgr_link))
              <div class="link-item">
                <i class="fab fa-facebook-messenger"></i>
                <a href="{{ $assistance_msgr_link }}" target="_blank" rel="noopener noreferrer">{{ $assistance_msgr_link }}</a>
              </div>
              @endif

            </div>
          @endif
        </div>
      </section>

      <!-- Service Offers Section -->
      <section class="service-offer">
        <div class="service-offer-container">
          <div class="section-header">
            <h2>Service Offer</h2>
          </div>
          <p class="section-desc">
            Discover the services offered by the SK. These are designed to make it easier for youth to participate in events,
            receive recognition, and access opportunities for learning and engagement.
          </p>
        </div>

        <div class="service-offer-scroll">
          <div class="card-grid" id="servicesContainer">
            @forelse($services as $service)
            <div class="service-card" data-service-id="{{ $service->id }}">
              <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('images/print.jpeg') }}" alt="{{ $service->title }}">
              <h3>{{ $service->title }}</h3>
              <a href="#" class="btn print-btn read-more-btn" data-service-id="{{ $service->id }}">Read More</a>
            </div>
            @empty
            <div class="no-services">
              <p>No services available for {{ $barangayName }} yet.</p>
            </div>
            @endforelse
          </div>
        </div>
      </section>

      <!-- Organizational Chart -->
      <section class="org-chart">
        <div class="org-chart-container">
          <h2>Organizational Chart</h2>
          <p class="section-desc">
            The organizational chart of the Sangguniang Kabataan of {{ $barangayName }} illustrates the structure of its committees
            and defines the roles and responsibilities of each official.
          </p>
        </div>

        <div class="org-image-wrapper">
          @if(isset($organizationalCharts) && $organizationalCharts->isNotEmpty())
            @foreach($organizationalCharts as $chart)
            <div class="chart-display-item">
              <img src="{{ asset('storage/' . $chart->image_path) }}" 
                   alt="Organizational Chart of {{ $barangayName }}" 
                   class="main-org-chart-img">
            </div>
            @endforeach
          @else
            <div class="no-org-chart">
              <p>No organizational chart available for {{ $barangayName }} yet.</p>
            </div>
          @endif
        </div>
      </section>
    </main>

    <!-- Service Modal -->
    <div id="serviceModal" class="modal" style="display: none;">
      <div class="modal-content">
        <span id="closeServiceModal" class="close">&times;</span>
        
        <div class="modal-body">
          <img id="modalServiceImage" src="" alt="Service Image" class="modal-poster">
          <h2 id="modalServiceTitle"></h2>
          <p id="modalServiceDescription" class="intro"></p>

          <div id="servicesOfferedSection" style="display: none;">
            <h3>Services Offered</h3>
            <ul id="modalServicesOffered"></ul>
          </div>

          <div id="locationSection" style="display: none;">
            <h3>Pick-Up Location</h3>
            <p id="modalLocation"></p>
          </div>

          <div id="howToAvailSection" style="display: none;">
            <h3>How to Avail</h3>
            <p id="modalHowToAvail"></p>
          </div>

          <div id="contactInfoSection" style="display: none;">
            <h3>For Assistance</h3>
            <p id="modalContactInfo"></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", () => {
    // === Theme Toggle Function ===
    function applyTheme(isDark) {
      const body = document.body;
      const themeToggle = document.getElementById('themeToggle');
      
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
    
    // Initialize theme
    const savedTheme = localStorage.getItem('theme') === 'dark';
    applyTheme(savedTheme);
    
    // Theme toggle event
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
      themeToggle.addEventListener('click', () => {
        const isDark = document.documentElement.getAttribute('data-theme') !== 'dark';
        applyTheme(isDark);
      });
    }
    
    // === Lucide icons ===
    lucide.createIcons();

    // === Elements ===
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    // === Modal Elements ===
    const serviceModal = document.getElementById('serviceModal');
    const closeServiceModal = document.getElementById('closeServiceModal');
    const servicesContainer = document.getElementById('servicesContainer');

    // Submenus
    const profileItem = document.querySelector('.profile-item');
    const profileLink = profileItem?.querySelector('.profile-link');

    // Profile & notifications dropdown (topbar)
    const profileWrapper = document.querySelector('.profile-wrapper');
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = profileWrapper?.querySelector('.profile-dropdown');

    const notifWrapper = document.querySelector(".notification-wrapper");
    const notifBell = notifWrapper?.querySelector(".fa-bell");
    const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

    // === Sidebar toggle ===
    menuToggle?.addEventListener('click', (e) => {
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

    // === Service Modal ===
    // Close service modal
    closeServiceModal?.addEventListener('click', () => {
      serviceModal.style.display = 'none';
    });

    serviceModal?.addEventListener('click', (e) => {
      if (e.target === serviceModal) {
        serviceModal.style.display = 'none';
      }
    });

    // Event delegation for Read More buttons
    servicesContainer?.addEventListener('click', (e) => {
      if (e.target.classList.contains('read-more-btn')) {
        e.preventDefault();
        const serviceId = e.target.getAttribute('data-service-id');
        if (serviceId) {
          loadServiceDetails(serviceId);
        }
      }
    });

    // Load service details function
    async function loadServiceDetails(serviceId) {
      try {
        const response = await fetch(`/services/${serviceId}/details`);
        const data = await response.json();
        
        if (data.success) {
          const service = data.service;
          
          // Populate modal with service data
          document.getElementById('modalServiceTitle').textContent = service.title;
          document.getElementById('modalServiceDescription').textContent = service.description;
          document.getElementById('modalServiceImage').src = service.image ? 
            `/storage/${service.image}` : '/images/print.jpeg';

          // Show/hide sections based on available data
          toggleSection('servicesOfferedSection', service.services_offered);
          toggleSection('locationSection', service.location);
          toggleSection('howToAvailSection', service.how_to_avail);
          toggleSection('contactInfoSection', service.contact_info);

          // Populate services offered list
          if (service.services_offered) {
            const servicesList = document.getElementById('modalServicesOffered');
            servicesList.innerHTML = '';
            try {
              const servicesArray = JSON.parse(service.services_offered);
              if (Array.isArray(servicesArray)) {
                servicesArray.forEach(serviceItem => {
                  const li = document.createElement('li');
                  li.textContent = serviceItem;
                  servicesList.appendChild(li);
                });
              }
            } catch (e) {
              console.error('Error parsing services offered:', e);
            }
          }

          // Populate other fields
          document.getElementById('modalLocation').textContent = service.location || '';
          document.getElementById('modalHowToAvail').textContent = service.how_to_avail || '';
          document.getElementById('modalContactInfo').textContent = service.contact_info || '';

          // Show modal
          serviceModal.style.display = 'block';

        } else {
          alert('Error loading service details');
        }
      } catch (error) {
        console.error('Error loading service details:', error);
        alert('Error loading service details');
      }
    }

    function toggleSection(sectionId, data) {
      const section = document.getElementById(sectionId);
      if (section) {
        section.style.display = data ? 'block' : 'none';
      }
    }

    // === Logout confirmation ===
    function confirmLogout(event) {
      event.preventDefault();
      if (confirm('Are you sure you want to logout?')) {
        document.getElementById('logout-form').submit();
      }
    }

    // Make confirmLogout available globally
    window.confirmLogout = confirmLogout;

    // === Notification mark as read ===
    function initMarkAsRead() {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (!csrfToken) return;

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
            body: JSON.stringify({ id: notifId })
          }).catch(err => console.error('Fetch error:', err));

          if (destinationUrl && destinationUrl !== '#') {
            window.location.href = destinationUrl;
          }
        });
      });
    }
    
    initMarkAsRead();
    
    // === SK Access Modal Functions ===
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

    // === Set Role Modal Functions ===
    const setRoleModal = document.getElementById('setRoleModal');
    const setRoleForm = document.getElementById('setRoleForm');

    window.openSetRoleModal = function() {
      if (setRoleModal) {
        setRoleModal.style.display = 'flex';
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

    // === Feedback Modal Functions ===
    const feedbackModal = document.getElementById('feedbackModal');
    const openFeedbackBtn = document.getElementById('openFeedbackBtn');
    const closeFeedbackModal = document.getElementById('closeFeedbackModal');
    const feedbackStars = document.querySelectorAll('#starRating i');
    const feedbackRatingInput = document.getElementById('ratingInput');
    const feedbackForm = document.getElementById('feedbackForm');
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

    // Open feedback modal
    if (openFeedbackBtn) {
        openFeedbackBtn.addEventListener('click', (e) => {
            e.preventDefault();
            feedbackModal.style.display = 'flex';
        });
    }

    // Close feedback modal
    if (closeFeedbackModal) {
        closeFeedbackModal.addEventListener('click', () => {
            feedbackModal.style.display = 'none';
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === feedbackModal) {
            feedbackModal.style.display = 'none';
        }
        if (e.target === successModal) {
            successModal.style.display = 'none';
        }
    });

    // Star rating functionality
    if (feedbackStars.length > 0) {
        feedbackStars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-value');
                if (feedbackRatingInput) feedbackRatingInput.value = rating;

                // Remove all active classes
                feedbackStars.forEach(s => {
                    s.classList.remove('fas');
                    s.classList.remove('active');
                    s.classList.add('far');
                });
                
                // Add active class to clicked star and all previous stars
                for (let i = 0; i < rating; i++) {
                    feedbackStars[i].classList.remove('far');
                    feedbackStars[i].classList.add('fas', 'active');
                }
            });
        });
    }

    // Form submission
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(feedbackForm);
            const submitButton = this.querySelector('.submit-btn');
            const submitButtonText = submitButton.textContent;

            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';

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
                    // Close feedback modal and show success modal
                    feedbackModal.style.display = 'none';
                    successModal.style.display = 'flex';
                    
                    // Reset form
                    feedbackForm.reset();
                    feedbackStars.forEach(s => {
                        s.classList.remove('fas', 'active');
                        s.classList.add('far');
                    });
                    if (feedbackRatingInput) feedbackRatingInput.value = '';
                    
                    const selectedText = document.getElementById('selectedFeedbackType');
                    const trigger = customSelect?.querySelector('.custom-select-trigger');
                    if (selectedText) selectedText.textContent = 'Select feedback type';
                    trigger?.classList.remove('selected');
                } else {
                    let errorMsg = data.message || 'Submission failed.';
                    if (data.errors) {
                        errorMsg += '\n' + Object.values(data.errors).join('\n');
                    }
                    alert(errorMsg);
                    submitButton.disabled = false;
                    submitButton.textContent = submitButtonText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitButton.disabled = false;
                submitButton.textContent = submitButtonText;
            });
        });
    }

    // Close success modal
    if (closeSuccessBtn) {
        closeSuccessBtn.addEventListener('click', () => {
            successModal.style.display = 'none';
        });
    }
  });
  </script>

  <script>
  // Mobile sidebar toggle
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