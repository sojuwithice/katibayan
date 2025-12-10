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
  <style>
    /* Additional styles for End Program functionality */
    .program-actions {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-top: 15px;
    }
    
    .program-btn {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 15px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      width: 100%;
      font-size: 14px;
    }
    
    .view-registrations {
      background: #3C87C4;
      color: white;
    }
    
    .view-registrations:hover {
      background: #2a6da9;
    }
    
    .end-program {
      background: #ff6b6b;
      color: white;
    }
    
    .end-program:hover {
      background: #ff5252;
    }
    
    .program-btn i {
      font-size: 14px;
    }
    
    /* Modal styles for ending program */
    .end-program-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    
    .end-program-modal-content {
      background: white;
      border-radius: 12px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    
    .end-program-header {
      padding: 20px;
      border-bottom: 1px solid #e0e0e0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .end-program-header h3 {
      margin: 0;
      color: #333;
    }
    
    .close-modal {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #666;
    }
    
    .close-modal:hover {
      color: #333;
    }
    
    .end-program-body {
      padding: 20px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #333;
    }
    
    .form-group input[type="date"],
    .form-group textarea,
    .form-group input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
    }
    
    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }
    
    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .checkbox-group input[type="checkbox"] {
      width: 18px;
      height: 18px;
    }
    
    .end-program-footer {
      padding: 20px;
      border-top: 1px solid #e0e0e0;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    
    .btn {
      padding: 10px 20px;
      border-radius: 6px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-cancel {
      background: #f0f0f0;
      color: #666;
    }
    
    .btn-cancel:hover {
      background: #e0e0e0;
    }
    
    .btn-confirm {
      background: #ff6b6b;
      color: white;
    }
    
    .btn-confirm:hover {
      background: #ff5252;
    }
    
    .btn-confirm:disabled {
      background: #ccc;
      cursor: not-allowed;
    }
    
    /* Program status badge */
    .program-status {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 10px;
    }
    
    .status-active {
      background: #e8f5e8;
      color: #2e7d32;
    }
    
    .status-ended {
      background: #ffebee;
      color: #c62828;
    }
    
    /* Alert styles */
    .alert {
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .alert-success {
      background: #e8f5e8;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }
    
    .alert-error {
      background: #ffebee;
      color: #c62828;
      border: 1px solid #ffcdd2;
    }
    
    .alert-info {
      background: #e8f0fa;
      color: #3C87C4;
      border: 1px solid #bbdefb;
    }
    
    /* Loading overlay */
    .loading-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      z-index: 2000;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }
    
    .spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #3C87C4;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 15px;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    /* Ended program style */
    .program-card.ended {
      opacity: 0.8;
      border: 1px solid #ffcdd2;
    }
    
    .program-card.ended .program-actions .view-registrations {
      background: #666;
    }
    
    .program-card.ended .program-actions .view-registrations:hover {
      background: #555;
    }
  </style>
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
          @if(in_array($user->role, ['sk', 'sk_chairperson']))
            <br><small>As an SK official, you can end programs that don't have end dates.</small>
          @endif
        </p>
      </div>

      <!-- Success Message -->
      @if(session('success'))
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          {{ session('success') }}
        </div>
      @endif

      <!-- Error Message -->
      @if(session('error'))
        <div class="alert alert-error">
          <i class="fas fa-exclamation-circle"></i>
          {{ session('error') }}
        </div>
      @endif

      <!-- Info Message -->
      @if(session('info'))
        <div class="alert alert-info">
          <i class="fas fa-info-circle"></i>
          {{ session('info') }}
        </div>
      @endif

      <!-- Current Month Programs Section -->
      <section class="programs-section">
        <div class="month-badge">Program for this month ({{ date('F Y') }})</div>
        
        <div class="programs-row">
          @forelse($currentMonthPrograms as $program)
            <div class="program-card {{ $program->status === 'ended' ? 'ended' : '' }}">
              @if($program->display_image)
                <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" class="program-img">
              @else
                <img src="{{ asset('images/default-program.jpg') }}" alt="{{ $program->title }}" class="program-img">
              @endif
              
              <span class="program-status status-{{ $program->status }}">
                {{ ucfirst($program->status) }}
              </span>
              
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
              
              <!-- Check if program has end date -->
              @php
                $hasEndDate = !empty($program->event_end_date) && $program->event_end_date != $program->event_date;
                $canEndProgram = (!$hasEndDate || $program->event_end_date === null) && 
                                 $program->status === 'active' && 
                                 ($program->user_id === $user->id || in_array($user->role, ['sk', 'sk_chairperson']));
              @endphp
              
              <div class="program-actions">
                <a href="{{ route('youth-registration-list', ['programId' => $program->id]) }}" class="program-btn view-registrations">
                  <span>View Youth Registration</span>
                  <i class="fa-solid fa-chevron-right"></i>
                </a>
                
                @if($canEndProgram)
                  <button class="program-btn end-program" data-program-id="{{ $program->id }}" data-program-title="{{ $program->title }}">
                    <span>End Program</span>
                    <i class="fa-solid fa-flag-checkered"></i>
                  </button>
                @endif
              </div>
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
              <div class="program-card {{ $program->status === 'ended' ? 'ended' : '' }}">
                @if($program->display_image)
                  <img src="{{ asset('storage/' . $program->display_image) }}" alt="{{ $program->title }}" class="program-img">
                @else
                  <img src="{{ asset('images/default-program.jpg') }}" alt="{{ $program->title }}" class="program-img">
                @endif
                
                <span class="program-status status-{{ $program->status }}">
                  {{ ucfirst($program->status) }}
                </span>
                
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
                
                <!-- Check if program has end date -->
                @php
                  $hasEndDate = !empty($program->event_end_date) && $program->event_end_date != $program->event_date;
                  $canEndProgram = (!$hasEndDate || $program->event_end_date === null) && 
                                   $program->status === 'active' && 
                                   ($program->user_id === $user->id || in_array($user->role, ['sk', 'sk_chairperson']));
                @endphp
                
                <div class="program-actions">
                  <a href="{{ route('youth-registration-list', ['programId' => $program->id]) }}" class="program-btn view-registrations">
                    <span>View Youth Registration</span>
                    <i class="fa-solid fa-chevron-right"></i>
                  </a>
                  
                  @if($canEndProgram)
                    <button class="program-btn end-program" data-program-id="{{ $program->id }}" data-program-title="{{ $program->title }}">
                      <span>End Program</span>
                      <i class="fa-solid fa-flag-checkered"></i>
                    </button>
                  @endif
                </div>
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

  <!-- End Program Modal -->
  <div id="endProgramModal" class="end-program-modal">
    <div class="end-program-modal-content">
      <div class="end-program-header">
        <h3>End Program</h3>
        <button class="close-modal">&times;</button>
      </div>
      <form id="endProgramForm">
        <div class="end-program-body">
          <div class="form-group">
            <label for="programTitle">Program Title</label>
            <input type="text" id="programTitle" readonly>
          </div>
          
          <div class="form-group">
            <label for="endDate">End Date <small>(Optional - defaults to today)</small></label>
            <input type="date" id="endDate" name="end_date" min="{{ date('Y-m-d') }}">
          </div>
          
          <div class="form-group">
            <label for="reason">Reason for Ending Program <small>(Optional)</small></label>
            <textarea id="reason" name="reason" placeholder="Enter reason for ending the program..."></textarea>
          </div>
          
          <div class="form-group">
            <div class="checkbox-group">
              <input type="checkbox" id="notifyParticipants" name="notify_participants" value="1" checked>
              <label for="notifyParticipants">Notify all participants that the program has ended</label>
            </div>
          </div>
          
          <input type="hidden" id="programId" name="program_id">
          @csrf
        </div>
        <div class="end-program-footer">
          <button type="button" class="btn btn-cancel">Cancel</button>
          <button type="submit" class="btn btn-confirm">End Program</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Loading Overlay -->
  <div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
    <p>Processing...</p>
  </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Initialize Lucide icons ===
  lucide.createIcons();
  
  // === Sidebar toggle ===
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

  if (profileWrapper && profileToggle) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
      notifWrapper?.classList.remove("active");
    });
    const profileDropdown = document.querySelector(".profile-dropdown");
    if (profileDropdown) profileDropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  // Close dropdowns when clicking outside
  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
  });

  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.display = 'none';
    }, 5000);
  });

  // Logout confirmation
  window.confirmLogout = function(event) {
    event.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
      document.getElementById('logout-form').submit();
    }
  };

  // === End Program Functionality ===
  const endProgramModal = document.getElementById('endProgramModal');
  const endProgramForm = document.getElementById('endProgramForm');
  const programTitleInput = document.getElementById('programTitle');
  const programIdInput = document.getElementById('programId');
  const endDateInput = document.getElementById('endDate');
  const loadingOverlay = document.getElementById('loadingOverlay');
  
  // Set minimum date for end date input (today)
  if (endDateInput) {
    const today = new Date().toISOString().split('T')[0];
    endDateInput.min = today;
    endDateInput.value = today;
  }

  // Open end program modal
  document.addEventListener('click', (e) => {
    if (e.target.closest('.end-program')) {
      const button = e.target.closest('.end-program');
      const programId = button.dataset.programId;
      const programTitle = button.dataset.programTitle;
      
      // Populate modal with program info
      programTitleInput.value = programTitle;
      programIdInput.value = programId;
      
      // Show modal
      endProgramModal.style.display = 'flex';
    }
  });

  // Close modal
  const closeModalButtons = document.querySelectorAll('.close-modal, .btn-cancel');
  closeModalButtons.forEach(button => {
    button.addEventListener('click', () => {
      endProgramModal.style.display = 'none';
      endProgramForm.reset();
    });
  });

  // Close modal when clicking outside
  endProgramModal.addEventListener('click', (e) => {
    if (e.target === endProgramModal) {
      endProgramModal.style.display = 'none';
      endProgramForm.reset();
    }
  });

  // Handle form submission
  if (endProgramForm) {
    endProgramForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const programId = programIdInput.value;
      const formData = new FormData(endProgramForm);
      
      // Show loading
      loadingOverlay.style.display = 'flex';
      
      try {
        const response = await fetch(`/programs/${programId}/end`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Hide modal and loading
          endProgramModal.style.display = 'none';
          loadingOverlay.style.display = 'none';
          
          // Show success message
          showAlert('Program ended successfully!', 'success');
          
          // Update the program card
          updateProgramCard(programId, data.program);
          
          // Reset form
          endProgramForm.reset();
        } else {
          throw new Error(data.message || 'Failed to end program');
        }
      } catch (error) {
        console.error('Error ending program:', error);
        loadingOverlay.style.display = 'none';
        showAlert('Error: ' + error.message, 'error');
      }
    });
  }

  // Update program card after ending
  function updateProgramCard(programId, programData) {
    const programCard = document.querySelector(`.end-program[data-program-id="${programId}"]`)?.closest('.program-card');
    if (!programCard) return;
    
    // Remove the End Program button
    const endProgramBtn = programCard.querySelector('.end-program');
    if (endProgramBtn) {
      endProgramBtn.remove();
    }
    
    // Update status badge
    const statusBadge = programCard.querySelector('.program-status');
    if (statusBadge) {
      statusBadge.textContent = 'Ended';
      statusBadge.className = 'program-status status-ended';
    }
    
    // Update program card style
    programCard.classList.add('ended');
    
    // Update View Registration button style
    const viewBtn = programCard.querySelector('.view-registrations');
    if (viewBtn) {
      viewBtn.style.background = '#666';
    }
    
    // Update program date if end date was added
    const programDateElement = programCard.querySelector('.program-date');
    if (programDateElement && programData.event_end_date) {
      const startDate = new Date(programData.event_date);
      const endDate = new Date(programData.event_end_date);
      
      if (startDate.toDateString() !== endDate.toDateString()) {
        const startDateStr = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const endDateStr = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        programDateElement.innerHTML = `<i class="fas fa-calendar"></i> ${startDateStr} - ${endDateStr}`;
      }
    }
  }

  // Show alert message
  function showAlert(message, type = 'success') {
    // Remove existing alerts
    const existingAlert = document.querySelector('.dynamic-alert');
    if (existingAlert) {
      existingAlert.remove();
    }
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} dynamic-alert`;
    alertDiv.innerHTML = `
      <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation' : 'info'}-circle"></i>
      ${message}
    `;
    
    // Insert after welcome card
    const welcomeCard = document.querySelector('.welcome-card');
    if (welcomeCard) {
      welcomeCard.insertAdjacentElement('afterend', alertDiv);
    }
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      if (alertDiv.parentNode) {
        alertDiv.style.display = 'none';
        setTimeout(() => {
          if (alertDiv.parentNode) {
            alertDiv.remove();
          }
        }, 300);
      }
    }, 5000);
  }
});
</script>
</body>
</html>