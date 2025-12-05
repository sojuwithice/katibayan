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

        <!-- Notifications - UPDATED TO MATCH DASHBOARD -->
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
                  $link = '#'; // Default
                  if ($notif->type == 'certificate_schedule') {
                    $link = route('certificatepage'); 
                  }
                @endphp
                
                <li>
                  <a href="{{ $link }}" class="notif-link {{ $notif->is_read == 0 ? 'unread' : '' }}" data-id="{{ $notif->id }}">
                    
                    <div class="notif-dot-container">
                      @if ($notif->is_read == 0)
                        <span class="notif-dot"></span>
                      @else
                        <span class="notif-dot-placeholder"></span>
                      @endif
                    </div>

                    <div class="notif-main-content">
                      <div class="notif-header-line">
                        <strong>{{ $notif->title }}</strong>
                        <span class="notif-timestamp">
                          {{ $notif->created_at->format('m/d/Y g:i A') }}
                        </span>
                      </div>
                      <p class="notif-message">{{ $notif->message }}</p>
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

  <!-- Modal -->
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

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === DARK/LIGHT MODE TOGGLE ===
      const body = document.body;
      const themeToggle = document.getElementById('themeToggle');

      // Function to apply theme
      function applyTheme(isDark) {
        body.classList.toggle('dark-mode', isDark);
        // Show sun when dark mode, moon when light mode
        const icon = isDark ? 'sun' : 'moon';

        if (themeToggle) {
          themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
        }

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
        
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
      }

      // Load saved theme
      const savedTheme = localStorage.getItem('theme') === 'dark';
      applyTheme(savedTheme);

      // Add event listener to theme toggle
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
      const modalOverlay = document.getElementById('modalOverlay');
      const closeModal = document.getElementById('closeModal');
      const timeEl = document.querySelector(".time");

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

      // === Global Click Listeners (Close dropdowns/sidebar) ===
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

      // === Topbar Dropdowns ===
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

      // === Time auto-update ===
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

      // === Notification Mark as Read ===
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

      // === (HELPER FUNCTION) Para sa status button ===
      function getCertificateAction(cert) {
        const status = cert.request_status;
        
        // Alamin kung event_id o program_id ang gagamitin
        const isEvent = !!cert.event_id; // true kung may event_id
        const activityId = isEvent ? cert.event_id : cert.program_id;
        const idType = isEvent ? 'data-event-id' : 'data-program-id';

        // Rule 1: Kung claimed na, tapos na.
        if (status === 'claimed') {
            return `<button class="print-btn status-claimed" disabled>Claimed</button>`;
        }

        // Rule 2: Kung pwede siya mag-request (base sa logic ng Controller)
        if (cert.can_request_again) {
            const buttonText = (status === null) ? 'Print Request' : 'Request Again';
            // Gagamitin na natin yung 'idType' at 'activityId'
            return `<button class="print-btn" ${idType}="${activityId}">${buttonText}</button>`;
        }

        // Rule 3: Kung HINDI siya pwede mag-request
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
            // Para sa mga kaso na hindi nahabol (e.g. max na)
            return `<button class="print-btn status-pending" disabled>Request Limit Reached</button>`;
        }
      }

      // === Load Certificates ===
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

      // === Display Certificates ===
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
          // Check if 'print-btn' was clicked and it's not disabled
          if (e.target.classList.contains('print-btn') && !e.target.disabled) {
            handlePrintRequest(e);
          }
        });
      }

      // === Group Certificates by Month ===
      function groupCertificatesByMonth(certificates) {
        const groups = {};
        certificates.forEach(cert => {
          // Parse the evaluation date
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

      // === Show Empty State ===
      function showEmptyState() {
        const container = document.getElementById('certificatesContainer');
        const emptyState = document.getElementById('emptyState');
        const certCountText = document.getElementById('certificateCountText');
        container.innerHTML = '';
        emptyState.style.display = 'flex';
        certCountText.textContent = 'You have a total of 0 certificates.';
      }

      // === Handle Print Request ===
      async function handlePrintRequest(e) {
        const printButton = e.target;
        const originalText = printButton.textContent;
        
        // Get the activity ID (event or program)
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
          // Send request to certificate-request endpoint
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
            modalOverlay.style.display = 'flex';
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

      // === Modal Handling ===
      closeModal?.addEventListener('click', () => {
        modalOverlay.style.display = 'none';
      });

      modalOverlay?.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
          modalOverlay.style.display = 'none';
        }
      });

      // === Initialize Page ===
      function initializePage() {
        loadCertificates();
        initMarkAsRead(); // Initialize notification mark as read
      }

      // Start the page
      initializePage();

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