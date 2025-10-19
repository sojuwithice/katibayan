<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Youth Registration</title>
  <link rel="stylesheet" href="{{ asset('css/youth-program-registration.css') }}">
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

      <div class="nav-item">
        <a href="#" class="nav-link active">
          <i data-lucide="calendar"></i>
          <span class="label">Events and Programs</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('sk-eventpage') }}">Events List</a>
          <a href="{{ route('youth-program-registration') }}" class="active">Youth Registration</a>
        </div>
      </div>

      <a href="{{ route('sk-evaluation-feedback') }}">
        <i data-lucide="message-square-quote"></i>
        <span class="label">Feedbacks</span>
      </a>

      <a href="{{ route('sk-polls') }}">
        <i data-lucide="vote"></i>
        <span class="label">Polls</span>
      </a>

      <a href="{{ route('youth-suggestion') }}">
        <i data-lucide="lightbulb"></i>
        <span class="label">Suggestion Box</span>
      </a>
      
      <a href="{{ route('reports') }}">
        <i data-lucide="file-chart-column"></i>
        <span class="label">Reports</span>
      </a>

      <a href="{{ route('sk-services-offer') }}">
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

    <main class="container">
      <!-- Header Title -->
      <div class="welcome-card">
        <h2>Youth Registration</h2>
        <p>
          View and manage youth registrations for programs. See all the information submitted by youth during registration.
        </p>
      </div>

      <!-- Success Message -->
      @if(session('success'))
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          {{ session('success') }}
        </div>
      @endif

      <!-- Program Registrations Modal -->
      <div id="registrationsModal" class="modal" style="display: none;">
        <div class="modal-content">
          <span class="close">&times;</span>
          <div class="modal-header">
            <h2 id="modalProgramTitle">Program Registrations</h2>
            <div class="program-meta">
              <span id="modalProgramDate" class="program-date"></span>
              <span id="modalProgramCategory" class="program-category"></span>
              <span id="modalTotalRegistrations" class="total-registrations"></span>
            </div>
          </div>
          <div class="modal-body">
            <div class="registrations-container">
              <div id="registrationsList" class="registrations-list">
                <!-- Registrations will be loaded here -->
              </div>
              <div id="noRegistrations" class="no-registrations" style="display: none;">
                <i class="fas fa-users-slash"></i>
                <h3>No Registrations Yet</h3>
                <p>No youth have registered for this program yet.</p>
              </div>
              <div id="loadingRegistrations" class="loading-registrations">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading registrations...</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Current Month Programs Section -->
      <section class="programs-section">
        <div class="month-badge">Program for this month ({{ date('F Y') }})</div>
        
        <div class="programs-row">
          @forelse($currentMonthPrograms as $program)
            <div class="program-card">
              @if($program->display_image)
                <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" class="program-img">
              @else
                <img src="{{ asset('images/default-program.jpg') }}" alt="{{ $program->title }}" class="program-img">
              @endif
              <h4>{{ $program->title }}</h4>
              <p class="program-date">
                <i class="fas fa-calendar"></i>
                {{ \Carbon\Carbon::parse($program->event_date)->format('M d, Y') }} at 
                {{ \Carbon\Carbon::parse($program->event_time)->format('g:i A') }}
              </p>
              <p class="program-category">
                <i class="fas fa-tag"></i>
                {{ ucfirst($program->category) }}
              </p>
              <button type="button" class="program-btn view-registrations" data-program-id="{{ $program->id }}">
                <span>View Youth Registration</span>
                <i class="fa-solid fa-chevron-right"></i>
              </button>
            </div>
          @empty
            <div class="no-programs">
              <i class="fas fa-calendar-times"></i>
              <p>No programs scheduled for this month</p>
            </div>
          @endforelse
        </div>
      </section>

      <!-- Upcoming Programs Section -->
      <section class="upcoming-section">
        <h2>Upcoming Programs</h2>

        @foreach($upcomingProgramsByMonth as $month => $programs)
          <div class="month-badge">{{ $month }}</div>

          <div class="programs-row">
            @foreach($programs as $program)
              <div class="program-card">
                @if($program->display_image)
                  <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" class="program-img">
                @else
                  <img src="{{ asset('images/default-program.jpg') }}" alt="{{ $program->title }}" class="program-img">
                @endif
                <h4>{{ $program->title }}</h4>
                <p class="program-date">
                  <i class="fas fa-calendar"></i>
                  {{ \Carbon\Carbon::parse($program->event_date)->format('M d, Y') }} at 
                  {{ \Carbon\Carbon::parse($program->event_time)->format('g:i A') }}
                </p>
                <p class="program-category">
                  <i class="fas fa-tag"></i>
                  {{ ucfirst($program->category) }}
                </p>
                <button type="button" class="program-btn view-registrations" data-program-id="{{ $program->id }}">
                  <span>View Youth Registration</span>
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            @endforeach
          </div>
        @endforeach

        @if($upcomingProgramsByMonth->isEmpty())
          <div class="no-programs">
            <i class="fas fa-calendar-plus"></i>
            <p>No upcoming programs scheduled</p>
          </div>
        @endif
      </section>
    </main>
  </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons + sidebar toggle ===
  lucide.createIcons();
  
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');

  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');
    });
  }

  // === Submenus ===
  const submenuTriggers = document.querySelectorAll('.nav-item > .nav-link');

  submenuTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault(); 
      
      const parentItem = trigger.closest('.nav-item');
      const wasOpen = parentItem.classList.contains('open');

      document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('open');
      });

      if (!wasOpen) {
        parentItem.classList.add('open');
      }
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

  // === Notifications ===
  const notifWrapper = document.querySelector(".notification-wrapper");
  const profileWrapper = document.querySelector(".profile-wrapper");
  const profileToggle = document.getElementById("profileToggle");
  const profileDropdown = document.querySelector(".profile-dropdown");

  if (notifWrapper) {
    const bell = notifWrapper.querySelector(".fa-bell");
    if (bell) {
      bell.addEventListener("click", (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle("active");
        profileWrapper?.classList.remove("active");
      });
    }
    const dropdown = notifWrapper.querySelector(".notif-dropdown");
    if (dropdown) dropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  if (profileWrapper && profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
      notifWrapper?.classList.remove("active");
    });
    profileDropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
  });

  // Auto-hide success message after 5 seconds
  const successAlert = document.querySelector('.alert-success');
  if (successAlert) {
    setTimeout(() => {
      successAlert.style.display = 'none';
    }, 5000);
  }

  // === Program Registrations Modal ===
  const registrationsModal = document.getElementById('registrationsModal');
  const closeModal = document.querySelector('.close');
  const viewRegistrationButtons = document.querySelectorAll('.view-registrations');

  // Function to load and display program registrations
  async function showProgramRegistrations(programId) {
    const registrationsList = document.getElementById('registrationsList');
    const noRegistrations = document.getElementById('noRegistrations');
    const loadingRegistrations = document.getElementById('loadingRegistrations');
    
    // Show loading
    registrationsList.innerHTML = '';
    noRegistrations.style.display = 'none';
    loadingRegistrations.style.display = 'block';
    
    // Show modal
    registrationsModal.style.display = 'block';

    try {
      const response = await fetch(`/youth-program-registration/${programId}/registrations`);
      const data = await response.json();

      loadingRegistrations.style.display = 'none';

      if (data.success) {
        // Update modal header with program info
        document.getElementById('modalProgramTitle').textContent = data.program.title;
        document.getElementById('modalProgramDate').textContent = 
          `Date: ${new Date(data.program.event_date).toLocaleDateString()} at ${data.program.event_time}`;
        document.getElementById('modalProgramCategory').textContent = 
          `Category: ${data.program.category}`;
        document.getElementById('modalTotalRegistrations').textContent = 
          `Total: ${data.program.total_registrations} registration(s)`;

        if (data.registrations.length > 0) {
          // Display registrations
          registrationsList.innerHTML = data.registrations.map(registration => `
            <div class="registration-card">
              <div class="registration-header">
                <div class="user-info">
                  <h4>${registration.user_name}</h4>
                  <div class="user-meta">
                    <span><i class="fas fa-envelope"></i> ${registration.email}</span>
                    <span><i class="fas fa-phone"></i> ${registration.contact_no}</span>
                    <span><i class="fas fa-birthday-cake"></i> ${registration.age} years old</span>
                    <span><i class="fas fa-map-marker-alt"></i> ${registration.barangay}</span>
                  </div>
                </div>
                <div class="registration-meta">
                  <span class="reference-id">Ref: ${registration.reference_id}</span>
                  <span class="registration-date">${registration.registered_at}</span>
                  <span class="status ${registration.status}">${registration.status}</span>
                </div>
              </div>
              
              <div class="registration-content">
                <!-- Motivation -->
                <div class="field-group">
                  <label>Motivation for Joining:</label>
                  <div class="field-value">${registration.motivation || 'Not provided'}</div>
                </div>
                
                <!-- Expectations -->
                <div class="field-group">
                  <label>Expectations:</label>
                  <div class="field-value">${registration.expectations || 'Not provided'}</div>
                </div>
                
                <!-- Special Requirements -->
                ${registration.special_requirements ? `
                <div class="field-group">
                  <label>Special Requirements:</label>
                  <div class="field-value">${registration.special_requirements}</div>
                </div>
                ` : ''}
                
                <!-- Custom Fields -->
                ${registration.custom_fields && Object.keys(registration.custom_fields).length > 0 ? `
                <div class="custom-fields">
                  <h5>Additional Information:</h5>
                  ${Object.entries(registration.custom_fields).map(([fieldId, value]) => `
                    <div class="field-group">
                      <label>${getFieldLabel(fieldId)}:</label>
                      <div class="field-value">${value || 'Not provided'}</div>
                    </div>
                  `).join('')}
                </div>
                ` : ''}
              </div>
            </div>
          `).join('');
        } else {
          noRegistrations.style.display = 'block';
        }
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      console.error('Error loading registrations:', error);
      loadingRegistrations.style.display = 'none';
      registrationsList.innerHTML = `
        <div class="error-message">
          <i class="fas fa-exclamation-triangle"></i>
          <p>Error loading registrations: ${error.message}</p>
        </div>
      `;
    }
  }

  // Helper function to get field labels
  function getFieldLabel(fieldId) {
    const fieldLabels = {
      'custom_field_1': 'Additional Question 1',
      'custom_field_2': 'Additional Question 2',
      'custom_field_3': 'Additional Question 3'
      // Add more field mappings as needed
    };
    return fieldLabels[fieldId] || fieldId.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  }

  // Add click event to view registration buttons
  viewRegistrationButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      const programId = button.getAttribute('data-program-id');
      showProgramRegistrations(programId);
    });
  });

  // Close modal
  closeModal.addEventListener('click', () => {
    registrationsModal.style.display = 'none';
  });

  registrationsModal.addEventListener('click', (e) => {
    if (e.target === registrationsModal) {
      registrationsModal.style.display = 'none';
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && registrationsModal.style.display === 'block') {
      registrationsModal.style.display = 'none';
    }
  });
});
</script>
</body>
</html>