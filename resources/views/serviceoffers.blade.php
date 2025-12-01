<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Service Offers</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('css/serviceoffers.css') }}">
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
      <a href="{{ route('dashboard.index') }}" >
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
        <span class="label">Service Offer </span>
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
          <img src="{{ Auth::check() && Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
     alt="User" class="avatar" id="profileToggle">

          <div class="profile-dropdown">
            <div class="profile-header">
  <img src="{{ Auth::check() && Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
       alt="User" class="profile-avatar">

  <div class="profile-info">
    <h4>
      {{ Auth::user()->given_name ?? '' }}
      {{ Auth::user()->middle_name ?? '' }}
      {{ Auth::user()->last_name ?? '' }}
      {{ Auth::user()->suffix ?? '' }}
    </h4>
    <div class="profile-badge">
      <span class="badge">{{ $roleBadge ?? '' }}</span>
      <span class="badge">{{ $age ?? '' }} yrs old</span>
    </div>
  </div>
</div>
<hr>
  <ul class="profile-menu">
      <li>
        <a href="{{ route('profilepage') }}">
        <i class="fas fa-user"></i> Profile</a>
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

    <main class="content">
    <section class="assistance-section">
      <div class="assistance-card">
        {{-- Walang Edit Button dito dahil display-only ito para sa Youth --}}
        
        <h2>Need Assistance?</h2>
        
        {{-- Iche-check kung may laman ang variables galing sa controller --}}
        
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
              {{-- Clickable link --}}
              <a href="{{ $assistance_fb_link }}" target="_blank" rel="noopener noreferrer">{{ $assistance_fb_link }}</a>
            </div>
            @endif
            
            @if(!empty($assistance_msgr_link))
            <div class="link-item">
              <i class="fab fa-facebook-messenger"></i>
              {{-- Clickable link --}}
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

  <div id="serviceModal" class="modal">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      
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

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === Lucide icons ===
      lucide.createIcons();

      // === Elements ===
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');
      
      // === BAGONG DEFINITION PARA SA MODAL LOGIC ===
      // Kunin ang modal elements (Kailangan ito para gumana ang modal)
      const serviceModal = document.getElementById('serviceModal');
      const closeModal = document.getElementById('closeModal');
      const servicesContainer = document.getElementById('servicesContainer'); // Kunin ang container ng services
      // =============================================


      // Submenus
      const profileItem = document.querySelector('.profile-item');
      const profileLink = profileItem?.querySelector('.profile-link');
      const eventsItem = document.querySelector('.events-item');
      const eventsLink = eventsItem?.querySelector('.events-link');

      // Profile & notifications dropdown (topbar)
      const profileWrapper = document.querySelector('.profile-wrapper');
      const profileToggle = document.getElementById('profileToggle');
      const profileDropdown = document.querySelector('.profile-dropdown');

      const notifWrapper = document.querySelector(".notification-wrapper");
      const notifBell = notifWrapper?.querySelector(".fa-bell");
      const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

      // CSRF Token for API requests
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      // === Sidebar toggle ===
      menuToggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');

        if (!sidebar.classList.contains('open')) {
          profileItem?.classList.remove('open');
          eventsItem?.classList.remove('open');
        }
      });

      // Helper: close all submenus
      function closeAllSubmenus() {
        profileItem?.classList.remove('open');
        eventsItem?.classList.remove('open');
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

     // ... (Iba pang code) ...

// Close modal
closeModal?.addEventListener('click', () => {
  serviceModal.classList.remove('active'); 
});

serviceModal?.addEventListener('click', (e) => {
  if (e.target === serviceModal) {
    serviceModal.classList.remove('active'); 
  }
});

// === FIX: EVENT DELEGATION PARA SA READ MORE BUTTONS ===
servicesContainer?.addEventListener('click', (e) => {
    // Tinitingnan kung ang click ay nagmula sa isang element na may class na 'read-more-btn'
    if (e.target.classList.contains('read-more-btn')) {
        e.preventDefault(); // Pigilan ang default action ng <a> (na mag-jump sa #)
        const serviceId = e.target.getAttribute('data-service-id');
        if (serviceId) {
            loadServiceDetails(serviceId);
        }
    }
});
// ========================================================


// Load service details function
async function loadServiceDetails(serviceId) {
  try {
    // Tinitiyak na tama ang URL (assuming Laravel route is correct)
    const response = await fetch(`/services/${serviceId}/details`); 
    const data = await response.json();
    
    if (data.success) {
      const service = data.service;
      
      // Populate modal with service data
      document.getElementById('modalServiceTitle').textContent = service.title;
      document.getElementById('modalServiceDescription').textContent = service.description;
      // Gumagamit ng tamang path
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
          // Tinitiyak na ang JSON parsing ay gumagana nang tama
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

      // I-apply ang 'active' class para lumabas ang modal
      serviceModal.classList.add('active'); 

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
    // Tinitingnan kung may laman ang data (hindi empty string o null)
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
</script>
</body>
</html>