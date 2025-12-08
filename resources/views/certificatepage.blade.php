<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Certificates</title>
  <link rel="stylesheet" href="{{ asset('css/certificatepage.css') }}">
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
          <a href="{{ route('certificatepage') }}" class="active">Certificates</a>
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

    <!-- Topbar - MATCHES DASHBOARD -->
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

        <!-- Notifications - EXACT SAME AS DASHBOARD -->
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

        <!-- Profile Avatar - MATCHES DASHBOARD -->
        <div class="profile-wrapper">
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
                   
              <div class="profile-info">
                <h4>{{ $user->given_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->suffix }}</h4>
                
                <!-- Badges - MATCHES DASHBOARD -->
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
            
            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- SK Access Modal - MATCHES DASHBOARD -->
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

    <!-- Set Role Modal - MATCHES DASHBOARD -->
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

    <!-- Feedback Modal - MATCHES DASHBOARD -->
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

    <!-- Success Modal - MATCHES DASHBOARD -->
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

    <!-- Certificates Section -->
    <section class="certificates">
      <!-- Header box with border -->
      <div class="certificates-header">
        <h2 id="certHeader">Your Certificates</h2>
        <p id="certificateCountText">Loading certificates...</p>
      </div>

      <!-- Certificate groups will be dynamically populated -->
      <div id="certificatesContainer">
        <!-- Content will be loaded via JavaScript -->
      </div>

      <!-- Empty state -->
      <div class="empty-state" id="emptyState">
        <div class="empty-box">
          <div class="empty-icon">⌀</div>
          <p>You don't have any certificates yet</p>
          <p class="sub-message">Complete event evaluations to earn certificates</p>
        </div>
      </div>

      <!-- Certificate Request Modal -->
      <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
          <div class="modal-icon">
            <i class="fa-solid fa-check"></i>
          </div>
          <h2>Request Submitted!</h2>
          <p>You'll be notified once your certificate is ready for claiming.</p>
          <button id="closeModal">OK</button>
        </div>
      </div>
    </section>
  </div>

  <!-- JavaScript - SHARED FUNCTIONALITY -->
  <script>
    // Make CSRF token globally available
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener("DOMContentLoaded", () => {
      // === DARK/LIGHT MODE TOGGLE (Same as dashboard) ===
      const body = document.body;
      const themeToggle = document.getElementById('themeToggle');

      function applyTheme(isDark) {
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

      const savedTheme = localStorage.getItem('theme') === 'dark';
      applyTheme(savedTheme);

      if (themeToggle) {
        themeToggle.addEventListener('click', () => {
          const isDark = !body.classList.contains('dark-mode');
          applyTheme(isDark);
        });
      }
      
      // === Lucide icons ===
      if (window.lucide) {
        lucide.createIcons();
      }

      // === Shared UI Components (Same as dashboard) ===
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
      const timeEl = document.querySelector(".time");

      // === Sidebar toggle (Same as dashboard) ===
      menuToggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');
        if (!sidebar.classList.contains('open')) {
          profileItem?.classList.remove('open');
        }
      });

      function closeAllSubmenus() {
        profileItem?.classList.remove('open');
      }

      // === Profile submenu toggle (Same as dashboard) ===
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

      // === Global Click Listeners (Same as dashboard) ===
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

      // === Topbar Dropdowns (Same as dashboard) ===
      if (profileToggle) {
        profileToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          profileWrapper.classList.toggle('active');
          notifWrapper?.classList.remove('active');
        });
      }
      profileDropdown?.addEventListener('click', e => e.stopPropagation());

      if (notifBell) {
        notifBell.addEventListener('click', (e) => {
          e.stopPropagation();
          notifWrapper.classList.toggle('active');
          profileWrapper?.classList.remove('active');
        });
      }
      notifDropdown?.addEventListener('click', e => e.stopPropagation());

      // === Time auto-update (Same as dashboard) ===
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

      // === Notification Mark as Read (Same as dashboard) ===
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

      // Initialize notification mark as read
      initMarkAsRead();

      // === Certificate-specific JavaScript ===
      const certificateModalOverlay = document.getElementById('modalOverlay');
      const closeCertificateModal = document.getElementById('closeModal');

      // Helper function for certificate status button
      function getCertificateAction(cert) {
        const status = cert.request_status;
        
        const isEvent = !!cert.event_id;
        const activityId = isEvent ? cert.event_id : cert.program_id;
        const idType = isEvent ? 'data-event-id' : 'data-program-id';

        if (status === 'claimed') {
            return `<button class="print-btn status-claimed" disabled>Claimed</button>`;
        }

        if (cert.can_request_again) {
            const buttonText = (status === null) ? 'Print Request' : 'Request Again';
            return `<button class="print-btn" ${idType}="${activityId}">${buttonText}</button>`;
        }

        switch (status) {
          case 'requesting': 
            return `<button class="print-btn status-pending" disabled>Request Pending</button>`;
          case 'accepted': 
            return `<button class="print-btn status-accepted" disabled>Accepted</button>`;
          case 'ready_for_pickup':
            return `<button class="print-btn status-ready" disabled>Ready to Claim</button>`;
          case 'rejected':
            return `<button class="print-btn status-rejected" disabled>Request Rejected</button>`;
          default: 
            return `<button class="print-btn status-pending" disabled>Request Limit Reached</button>`;
        }
      }

      // Load Certificates
      async function loadCertificates() {
        try {
          const response = await fetch('/evaluation/certificates', {
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Accept': 'application/json'
            }
          });
          const data = await response.json();
          if (data.success) {
            displayCertificates(data.certificates);
          } else {
            console.error('Failed to load certificates:', data.error);
            showEmptyState();
          }
        } catch (error) {
          console.error('Error loading certificates:', error);
          showEmptyState();
        }
      }

      // Display Certificates
      function displayCertificates(certificates) {
        const container = document.getElementById('certificatesContainer');
        const emptyState = document.getElementById('emptyState');
        const certCountText = document.getElementById('certificateCountText');

        if (!certificates || certificates.length === 0) {
          showEmptyState();
          return;
        }

        certCountText.textContent = `You have a total of ${certificates.length} certificate${certificates.length !== 1 ? 's' : ''}.`;
        emptyState.style.display = 'none';

        const groupedCerts = groupCertificatesByMonth(certificates);
        let html = '';

        for (const [monthYear, certs] of Object.entries(groupedCerts)) {
          html += `
            <div class="certificates-group">
              <h3>${monthYear}</h3>
              <div class="cert-grid">
          `;

          html += certs.map(cert => `
            <div class="cert-card" data-event-id="${cert.event_id}">
              <div class="cert-img">
                <img
                  src="${cert.event_image || 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='}"
                  alt="${cert.event_title}"
                  class="cert-image"
                  onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='"
                >
              </div>
              <div class="cert-info">
                <div class="cert-text">
                  <p class="cert-title">Certificate completed in:</p>
                  <p class="cert-desc">${cert.event_title}</p>
                  <p class="cert-date">${cert.event_date}</p>
                </div>
                
                ${getCertificateAction(cert)}

              </div>
            </div>
          `).join('');

          html += `</div></div>`;
        }

        container.innerHTML = html;

        // Add event listener for print requests
        container.addEventListener('click', e => {
          if (e.target.classList.contains('print-btn') && !e.target.disabled) {
            handlePrintRequest(e);
          }
        });
      }

      // Group Certificates by Month
      function groupCertificatesByMonth(certificates) {
        const groups = {};
        certificates.forEach(cert => {
          const evalDate = new Date(cert.evaluated_at);
          const monthYear = evalDate.toLocaleDateString('en-US', { 
            month: 'long', 
            year: 'numeric' 
          });
          if (!groups[monthYear]) {
            groups[monthYear] = [];
          }
          groups[monthYear].push(cert);
        });
        return groups;
      }

      // Show Empty State
      function showEmptyState() {
        const container = document.getElementById('certificatesContainer');
        const emptyState = document.getElementById('emptyState');
        const certCountText = document.getElementById('certificateCountText');
        container.innerHTML = '';
        emptyState.style.display = 'flex';
        certCountText.textContent = 'You have a total of 0 certificates.';
      }

      // Handle Print Request
      async function handlePrintRequest(e) {
        const printButton = e.target;
        const originalText = printButton.textContent;
        
        const eventId = printButton.getAttribute('data-event-id');
        const programId = printButton.getAttribute('data-program-id');
        
        const payload = {};
        if (eventId && eventId !== 'null') {
            payload.event_id = eventId;
        } else if (programId && programId !== 'null') {
            payload.program_id = programId;
        } else {
            alert('Error: Activity ID not found.');
            return;
        }

        printButton.disabled = true;
        printButton.textContent = 'Submitting...';

        try {
          const response = await fetch('/certificate-request', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
          });

          const data = await response.json();

          if (response.ok) {
            certificateModalOverlay.style.display = 'flex';
            printButton.textContent = 'Request Pending';
            printButton.classList.add('status-pending');

          } else {
            alert('Error: ' + (data.message || 'Something went wrong.'));
            printButton.disabled = false;
            printButton.textContent = originalText;
          }
        } catch (error) {
          console.error('Error sending request:', error);
          alert('An error occurred while sending your request.');
          printButton.disabled = false;
          printButton.textContent = originalText;
        }
      }

      // Modal Handling
      closeCertificateModal?.addEventListener('click', () => {
        certificateModalOverlay.style.display = 'none';
      });

      certificateModalOverlay?.addEventListener('click', (e) => {
        if (e.target === certificateModalOverlay) {
          certificateModalOverlay.style.display = 'none';
        }
      });

      // Initialize Page
      function initializePage() {
        loadCertificates();
      }

      // Start the page
      initializePage();

    });
  </script>

  <!-- SK Access Modal JavaScript (Same as dashboard) -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
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

    // Set Role Modal Logic
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

  <!-- Feedback Modal Script (Same as dashboard) -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const feedbackTriggerBtn = document.getElementById('openFeedbackBtn');
    const feedbackModal = document.getElementById('feedbackModal');
    if (feedbackTriggerBtn && feedbackModal) {
      const feedbackCloseBtn = document.getElementById('closeModal');
      const feedbackStars = document.querySelectorAll('#starRating i');
      const feedbackRatingInput = document.getElementById('ratingInput');
      const feedbackForm = document.getElementById('feedbackForm');
      const submitBtn = feedbackForm?.querySelector('.submit-btn');
      const successModal = document.getElementById('successModal');
      const closeSuccessBtn = document.getElementById('closeSuccessModal');

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

  function confirmLogout(event) {
    event.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
      document.getElementById('logout-form').submit();
    }
  }
</script>
</body>
</html>