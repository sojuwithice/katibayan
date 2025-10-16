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
          <span class="notif-count" id="notificationCount">0</span>
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notification</strong> <span id="dropdownNotifCount">0</span>
            </div>
            <ul class="notif-list" id="notificationList">
              <li>
                <div class="notif-content">
                  <p>No new notifications</p>
                </div>
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
                <h4>{{ $user->given_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}</h4>
                <div class="profile-badge">
                  <span class="badge">{{ $roleBadge ?? 'GUEST' }}</span>
                  <span class="badge">{{ $age ?? 'N/A' }} yrs old</span>
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
                <a href="{{ route('loginpage') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
      // === Lucide icons ===
      if (window.lucide) {
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
        const certHeader = document.getElementById('certHeader');
        const certCountText = document.getElementById('certificateCountText');
        
        if (!certificates || certificates.length === 0) {
          showEmptyState();
          return;
        }
        
        // Update header
        certCountText.textContent = `You have a total of ${certificates.length} certificate${certificates.length !== 1 ? 's' : ''}.`;
        
        // Hide empty state
        emptyState.style.display = 'none';
        
        // Group certificates by month
        const groupedCerts = groupCertificatesByMonth(certificates);
        
        // Generate HTML for certificate groups
        let html = '';
        
        for (const [monthYear, certs] of Object.entries(groupedCerts)) {
          html += `
            <div class="certificates-group">
              <h3>${monthYear}</h3>
              <div class="cert-grid">
          `;
          
          certs.forEach(cert => {
            html += `
              <div class="cert-card" data-event-id="${cert.event_id}">
                <div class="cert-img">
                  <img src="${cert.event_image || 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='}" 
                       alt="${cert.event_title}" class="cert-image" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                </div>
                <div class="cert-info">
                  <div class="cert-text">
                    <p class="cert-title">Certificate completed in:</p>
                    <p class="cert-desc">${cert.event_title}</p>
                    <p class="cert-date">${cert.event_date}</p>
                  </div>
                  <button class="print-btn" data-event-id="${cert.event_id}">Print with SK</button>
                </div>
              </div>
            `;
          });
          
          html += `
              </div>
            </div>
          `;
        }
        
        container.innerHTML = html;
        
        // Add event listeners to print buttons
        document.querySelectorAll('.print-btn').forEach(btn => {
          btn.addEventListener('click', handlePrintRequest);
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
      function handlePrintRequest(e) {
        const eventId = e.target.getAttribute('data-event-id');
        
        // Show the modal
        const modalOverlay = document.getElementById('modalOverlay');
        modalOverlay.style.display = 'flex';
        
        // In a real implementation, you would send a request to generate/print the certificate
        console.log('Print requested for event:', eventId);
        
        // You could add AJAX call here to request certificate generation
        /*
        fetch('/evaluation/request-print', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ event_id: eventId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            modalOverlay.style.display = 'flex';
          }
        });
        */
      }

      // === Modal Handling ===
      const modalOverlay = document.getElementById('modalOverlay');
      const closeModal = document.getElementById('closeModal');

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
      }

      // Start the page
      initializePage();
    });
  </script>
</body>
</html>