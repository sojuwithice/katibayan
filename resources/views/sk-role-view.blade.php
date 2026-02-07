<!DOCTYPE html>
<html lang="en" class="sk-view">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>KatiBayan - SK Role</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
  
  <!-- CSS Files -->
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sk-view.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="sk-view">
<main class="dashboard-container">

    <header class="topbar">
        <!-- Logo with correct dashboard classes -->
        <div class="logo">
            <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="Logo">
            <div class="logo-text">
                <span class="title">Katibayan</span>
                <span class="subtitle">Web Portal</span>
            </div>
        </div>

        <div class="topbar-right">
            <!-- Time Widget -->
            <div class="time" id="currentTime">MON 10:00 <span>AM</span></div>

            <!-- Theme Toggle Button -->
            <button class="theme-toggle" id="themeToggle">
                <i data-lucide="moon"></i>
            </button>

            <!-- Notification System -->
            <div class="notification-wrapper">
                <i class="fas fa-bell"></i>
                @if(isset($notificationCount) && $notificationCount > 0)
                    <span class="notif-count">{{ $notificationCount }}</span>
                @endif
                <div class="notif-dropdown">
                    <div class="notif-header">
                        <strong>Notification</strong>
                        @if(isset($notificationCount) && $notificationCount > 0)
                            <span>{{ $notificationCount }}</span>
                        @endif
                    </div>
                    
                    <ul class="notif-list">
                        <li>
                            <a href="#" class="notif-link">
                                <div class="notif-dot-container">
                                    <span class="notif-dot"></span>
                                </div>
                                <div class="notif-main-content">
                                    <div class="notif-header-line">
                                        <strong>Program Evaluation Due</strong>
                                        <span class="notif-timestamp">12/15/2024 2:30 PM</span>
                                    </div>
                                    <p class="notif-message">The evaluation for the KK-Assembly is due tomorrow.</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="notif-link">
                                <div class="notif-dot-container">
                                    <span class="notif-dot"></span>
                                </div>
                                <div class="notif-main-content">
                                    <div class="notif-header-line">
                                        <strong>New Project Proposal</strong>
                                        <span class="notif-timestamp">12/14/2024 10:15 AM</span>
                                    </div>
                                    <p class="notif-message">Kagawad Dela Cruz submitted a new project proposal.</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="notif-link">
                                <div class="notif-dot-container">
                                    <span class="notif-dot-placeholder"></span>
                                </div>
                                <div class="notif-main-content">
                                    <div class="notif-header-line">
                                        <strong>Meeting Reminder</strong>
                                        <span class="notif-timestamp">12/13/2024 4:45 PM</span>
                                    </div>
                                    <p class="notif-message">SK Monthly Meeting is scheduled for Friday at 2 PM.</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            @if(Auth::check())
                @php
                    $user = Auth::user();
                    
                    // Calculate Age
                    $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';

                    // Fix "KK-Member" Logic
                    $rawRole = strtolower($user->role);
                    if ($rawRole === 'kk' || $rawRole === 'resident') {
                        $roleBadge = 'KK-Member';
                    } else {
                        $roleBadge = ucfirst($rawRole);
                    }

                    // SK Role Logic
                    $skTitle = '';
                    if (!empty($user->sk_role)) {
                        $skTitle = $user->sk_role; 
                    } elseif ($user->role === 'sk_chairperson') {
                        $skTitle = 'Chairperson';
                    }
                @endphp

                <!-- Profile Dropdown -->
                <div class="profile-wrapper">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                         alt="User" class="avatar" id="profileToggle"> 

                    <div class="profile-dropdown">
                        <div class="profile-header">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="User" class="profile-avatar"> 

                            <div class="profile-info">
                                <h4>{{ $user->given_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->suffix }}</h4>
                                
                                <div class="badges-wrapper">
                                    <!-- Blue Badge -->
                                    <div class="profile-badge">
                                        <span class="badge">{{ $roleBadge }}</span>
                                        <span class="badge">{{ $age }} yrs old</span>
                                    </div>

                                    <!-- Yellow SK Badge -->
                                    @if($skTitle)
                                        <div class="profile-badge sk-badge-yellow">
                                            <span>{{ $skTitle }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        
                        <!-- Back to Profile Button -->
                        <div class="profile-button-container">
                            <a href="{{ route('dashboard.index') }}" class="profile-sk-button">
                                Back to Profile
                            </a>
                        </div>

                        <!-- Menu Items -->
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
            @endif
        </div>
    </header>

    <!-- Feedback Modal (Same as dashboard) -->
    <div id="feedbackModal" class="modal-overlay">
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
          <textarea id="message" name="message" rows="5" placeholder="Share your feedback with us..."></textarea>

          <input type="hidden" name="rating" id="ratingInput">
          
          <div class="form-actions">
            <button type="submit" class="submit-btn">Submit</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success Modal -->
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

    <section class="welcome-section">
        @php
            // Get User Data
            $fullName = $user->given_name . ' ' . $user->middle_name . ' ' . $user->last_name . ' ' . $user->suffix;
            $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';
            
            // Get SK Role
            $skRole = $user->sk_role ?? 'Member';
            $roleClass = strtolower($skRole);

            // Get Committees
            $savedCommittees = !empty($user->committees) ? json_decode($user->committees, true) : [];
            $hasCommittees = !empty($savedCommittees);
            
            // Button Text
            $buttonText = $hasCommittees ? 'Edit your committee' : 'Set your committee';
        @endphp

        <script>
            window.userCommittees = @json($savedCommittees);
        </script>

        <div class="welcome-text">
            <h1>{{ $fullName }}</h1>
            <p>
                {{ $age }} years old 
                <span class="tag tag-{{ $roleClass }}">SK {{ $skRole }}</span>
            </p>
        </div>
        
        <button class="btn btn-secondary" id="setCommitteeBtn">
            {{ $buttonText }}
        </button>
    </section>

    <!-- Committee Modal -->
    <div class="modal-overlay" id="committeeModal">
        <div class="committee-modal">
            <div class="modal-header">
                <h2>Select Your Committee</h2>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <div class="modal-content">
                <div class="modal-section">
                    <p>
                      Please select your respective committee to proceed.
                    </p>
                    <div class="committee-options">
                        <div class="committee-option" data-committee="health">
                            <input type="checkbox" id="health" name="committees" value="health">
                            <label for="health">Committee on Health</label>
                        </div>
                        <div class="committee-option" data-committee="education">
                            <input type="checkbox" id="education" name="committees" value="education">
                            <label for="education">Committee on Education</label>
                        </div>
                        <div class="committee-option" data-committee="sports">
                            <input type="checkbox" id="sports" name="committees" value="sports">
                            <label for="sports">Committee on Sports</label>
                        </div>
                        <div class="committee-option" data-committee="culture">
                            <input type="checkbox" id="culture" name="committees" value="culture">
                            <label for="culture">Committee on Culture</label>
                        </div>
                        <div class="committee-option" data-committee="environment">
                            <input type="checkbox" id="environment" name="committees" value="environment">
                            <label for="environment">Committee on Environment</label>
                        </div>
                        <div class="committee-option" data-committee="citizenship">
                            <input type="checkbox" id="citizenship" name="committees" value="citizenship">
                            <label for="citizenship">Committee on Active Citizenship</label>
                        </div>
                        <div class="committee-option" data-committee="social">
                            <input type="checkbox" id="social" name="committees" value="social">
                            <label for="social">Committee on Social Inclusion</label>
                        </div>
                        <div class="committee-option" data-committee="finance">
                            <input type="checkbox" id="finance" name="committees" value="finance">
                            <label for="finance">Committee on Finance</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-section">
                    <h3>Current Selection</h3>
                    <div id="selectedCommittees">
                        <p style="color: var(--text-color); opacity: 0.7; margin: 0; font-style: italic;">No committees selected yet</p>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="cancelSelection">Cancel</button>
                <button class="btn btn-primary" id="saveCommittees">Save Committees</button>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="grid-col-1">
            <div class="card sk-committee">
                <h2>SK COMMITTEE</h2>
                <ul>
                    <!-- Chairperson -->
                    <li class="committee-item">
                        @if($chairperson)
                            <span class="name">
                                {{ strtoupper($chairperson->given_name . ' ' . $chairperson->last_name) }}
                            </span>
                            <div class="role-group">
                                <span class="role-tag role-chairperson">SK CHAIRPERSON</span>
                            </div>
                        @else
                            <span class="name" style="color: var(--text-color); opacity: 0.7; font-style: italic;">(VACANT)</span>
                            <div class="role-group">
                                <span class="role-tag role-chairperson">SK CHAIRPERSON</span>
                            </div>
                        @endif
                    </li>

                    <li class="members-header">MEMBERS</li>

                    @forelse($members as $member)
                        @php
                            // Format Name
                            $fullName = strtoupper($member->given_name . ' ' . $member->last_name);
                            
                            // Format Role Class
                            $roleClass = 'role-' . strtolower($member->sk_role);
                            
                            // Check Committees
                            $commList = !empty($member->committees) ? json_decode($member->committees, true) : [];
                        @endphp

                        <li class="committee-item">
                            <span class="name">{{ $fullName }}</span>
                            
                            <div class="role-group">
                                <span class="role-tag {{ $roleClass }}">
                                    SK {{ strtoupper($member->sk_role) }}
                                </span>

                                @if(!empty($commList))
                                    @foreach($commList as $comm)
                                        <span class="committee-role">
                                            COMMITTEE ON {{ strtoupper($comm) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </li>

                    @empty
                        <li class="committee-item" style="justify-content: center; padding: 20px;">
                            <span class="name" style="color: var(--text-color); opacity: 0.7; font-style: italic; font-weight: normal; font-size: 13px;">
                                No registered SK members yet.
                            </span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="grid-col-2">
            <div class="card send-report">
    <div class="card-header-flex">
        <h2>SEND REPORT TO YOUR SK CHAIR</h2>
        <!-- Three dots history button -->
        <button class="history-dots-btn" id="historyDotsBtn" title="View History">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    </div>
                
                <form id="sendReportForm">
                    <div class="form-group">
                        <label for="report-type">Report Type</label>
                        <select id="report-type" name="report_type" required>
                            <option value="">Select type of report</option>
                            <option value="accomplishment">Accomplishment Report</option>
                            <option value="financial">Propose Project</option>
                        </select>
                    </div>

                    <div class="form-group file-upload">
                        <label for="file-attach">Attach files</label>
                        <div class="file-input-wrapper">
                            <button type="button" class="file-input-btn" id="browseBtn">
                                <i class="fas fa-cloud-upload-alt"></i> Choose Files or Drag & Drop
                            </button>
                            <input type="file" id="file-attach" name="files[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" style="display:none;">
                        </div>
                        
                        <div class="file-size-warning">Max file size: 10MB per file</div>
                        
                        <div class="file-list" id="uploadFileList">
                            <div class="file-empty-state">No files selected</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit Report</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card propose-project-full">
        <div class="card-header-flex">
            <h2>Accomplished Projects</h2>
            
            <div class="year-filter-wrapper">
                <select id="projectYearFilter" class="year-select">
                    @php
                        $currentYear = \Carbon\Carbon::now()->year;
                        $availableYears = $completedProjects->keys(); 
                    @endphp

                    @if(!$availableYears->contains($currentYear))
                        <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                    @endif

                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                    
                    <option value="all">All Years</option>
                </select>
            </div>
        </div>
        
        <div class="projects-list-container">
            @forelse($completedProjects as $year => $projects)
                <div class="year-group-container" 
                     id="year-group-{{ $year }}" 
                     style="{{ $year == $currentYear ? 'display: block;' : 'display: none;' }}">
                    
                    <div class="year-header-small">
                        <span>Records for {{ $year }}</span>
                    </div>

                    <ul class="project-year-group">
                        @foreach($projects as $project)
                            <li>
                                <div class="project-info">
                                    <span class="project-name">{{ $project->title }}</span>
                                    <span class="project-status">
                                        {{ $project->type }}
                                    </span>
                                </div>
                                <span class="project-date">
                                    {{ \Carbon\Carbon::parse($project->date)->format('M d') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div style="padding: 20px; text-align: center;">
                    <span style="color: var(--text-color); opacity: 0.7; font-style: italic;">
                        No accomplished projects yet.
                    </span>
                </div>
            @endforelse
            
            <div id="no-data-message" style="display: none; padding: 20px; text-align: center;">
                <span style="color: var(--text-color); opacity: 0.7; font-style: italic;">No records found for this year.</span>
            </div>
        </div>
    </div>
</main>


<!-- History Log Modal -->
<div id="historyModal" class="modal-overlay history-modal">
    <div class="modal-content history-modal-content">
        <div class="history-modal-header">
            <h2>Report History</h2>
            <button class="close-modal" id="closeHistoryModal">&times;</button>
        </div>
        
        <div class="history-filter-section">
            <div class="filter-group">
                <label for="filterMonth">Month</label>
                <select id="filterMonth" class="filter-select">
                    <option value="all">All Months</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="filterYear">Year</label>
                <select id="filterYear" class="filter-select">
                    <option value="all">All Years</option>
                    @php
                        $currentYear = date('Y');
                        for($year = $currentYear; $year >= $currentYear - 5; $year--) {
                            echo "<option value=\"{$year}\">{$year}</option>";
                        }
                    @endphp
                </select>
            </div>
            
            <button class="btn btn-secondary" id="applyFilters">Apply Filters</button>
            <button class="btn btn-outline" id="resetFilters">Reset</button>
        </div>
        
        <div class="history-list-container">
            <div class="history-list-header">
                <span class="header-report-type">Report Type</span>
                <span class="header-date">Date Submitted</span>
                <span class="header-files">Files</span>
                <span class="header-remarks">Notes</span>
            </div>
            
            <div class="history-list" id="historyList">
                <!-- History items will be loaded here -->
                <div class="history-empty-state">
                    <i class="fas fa-history"></i>
                    <p>No report history found</p>
                </div>
            </div>
        </div>
        
        <div class="history-modal-footer">
            <div class="pagination-info">
                Showing <span id="startCount">0</span>-<span id="endCount">0</span> of <span id="totalCount">0</span> reports
            </div>
            <div class="pagination-controls">
                <button class="pagination-btn" id="prevPage" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span id="currentPage">1</span>
                <button class="pagination-btn" id="nextPage" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Main JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // =========================================================
    // 1. TIME UPDATE FUNCTION
    // =========================================================
    function updateTime() {
        const timeEl = document.querySelector(".time");
        if (!timeEl) return;

        const now = new Date();
        const shortWeekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
        const shortMonths = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        const weekday = shortWeekdays[now.getDay()];
        const month = shortMonths[now.getMonth()];
        const day = now.getDate();
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, "0");
        const ampm = hours >= 12 ? "PM" : "AM";
        hours = hours % 12 || 12;

        timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
    }

    // Initial call and set interval
    updateTime();
    setInterval(updateTime, 60000);

    // =========================================================
    // 2. THEME TOGGLE FUNCTIONALITY
    // =========================================================
    const themeToggle = document.getElementById('themeToggle');
    
    function applyTheme(isDark) {
        const body = document.body;
        body.classList.toggle('dark-mode', isDark);
        
        const icon = isDark ? 'sun' : 'moon';
        if (themeToggle) {
            themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
    }

    // Initialize theme
    const savedTheme = localStorage.getItem('theme') === 'dark';
    applyTheme(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isDark = !document.body.classList.contains('dark-mode');
            applyTheme(isDark);
        });
    }

    // =========================================================
    // 3. PROFILE DROPDOWN & NOTIFICATION LOGIC
    // =========================================================
    const profileToggle = document.getElementById('profileToggle');
    const profileWrapper = document.querySelector('.profile-wrapper');
    const notifWrapper = document.querySelector('.notification-wrapper');
    const notifBell = notifWrapper?.querySelector('.fa-bell');

    // Profile Dropdown
    if (profileToggle && profileWrapper) {
        profileToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            profileWrapper.classList.toggle("active");
            notifWrapper?.classList.remove("active");
        });
    }

    // Notifications Dropdown
    if (notifWrapper && notifBell) {
        notifBell.addEventListener("click", (e) => {
            e.stopPropagation();
            notifWrapper.classList.toggle("active");
            profileWrapper?.classList.remove("active");
        });
    }

    // Global Click Listener
    document.addEventListener("click", (e) => {
        if (profileWrapper && !profileWrapper.contains(e.target)) {
            profileWrapper.classList.remove("active");
        }
        if (notifWrapper && !notifWrapper.contains(e.target)) {
            notifWrapper.classList.remove("active");
        }
    });

    // Logout Confirmation
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    }

    // =========================================================
    // 4. FEEDBACK MODAL FUNCTIONALITY (Same as dashboard)
    // =========================================================
    const feedbackTriggerBtn = document.getElementById('openFeedbackBtn');
    const feedbackModal = document.getElementById('feedbackModal');
    
    if (feedbackTriggerBtn && feedbackModal) {
        const feedbackCloseBtn = document.getElementById('closeFeedbackModal');
        const feedbackStars = document.querySelectorAll('#starRating i');
        const feedbackRatingInput = document.getElementById('ratingInput');
        const feedbackForm = document.getElementById('feedbackForm');
        const submitBtn = feedbackForm?.querySelector('.submit-btn');
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

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Add CSRF token to form data
                if (csrfToken) {
                    formData.append('_token', csrfToken);
                }

                fetch(feedbackForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
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

    // =========================================================
    // 5. SEND REPORT & FILE UPLOAD LOGIC
    // =========================================================
    const fileInput = document.getElementById('file-attach');
    const fileListContainer = document.getElementById('uploadFileList'); 
    const browseBtn = document.getElementById('browseBtn'); 
    const sendReportForm = document.getElementById('sendReportForm'); 
    const submitBtn = document.getElementById('submitBtn');
    
    let currentFiles = [];

    // Trigger file input click
    if (browseBtn && fileInput) {
        browseBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.click();
        });
    }

    // Handle file selection
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            const newFiles = Array.from(e.target.files);
            
            newFiles.forEach(newFile => {
                if (!currentFiles.some(existing => existing.name === newFile.name)) {
                    currentFiles.push(newFile);
                }
            });

            updateFileListUI(fileListContainer, currentFiles);
            fileInput.value = '';
        });
    }

    // Helper: Update UI List
    function updateFileListUI(container, filesToShow) {
        if (!container) return;
        container.innerHTML = '';
        
        if (filesToShow.length === 0) {
            container.innerHTML = '<div class="file-empty-state">No files selected</div>';
            return;
        }

        filesToShow.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            
            // Format size logic
            let size = (file.size / 1024).toFixed(1) + ' KB';
            if(file.size > 1024 * 1024) size = (file.size / (1024 * 1024)).toFixed(1) + ' MB';

            fileItem.innerHTML = `
                <div style="display:flex; align-items:center; gap:8px; overflow:hidden;">
                    <i class="fas fa-file" style="color:var(--text-color);"></i>
                    <span class="file-name" title="${file.name}" style="font-size:0.9rem;">${file.name}</span>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:0.8rem; color:var(--text-color); opacity:0.7;">${size}</span>
                    <span class="file-remove" data-index="${index}" style="cursor:pointer; color:#ef4444;"><i class="fas fa-times"></i></span>
                </div>
            `;
            container.appendChild(fileItem);
        });

        // Add remove functionality
        container.querySelectorAll('.file-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = parseInt(e.currentTarget.dataset.index);
                currentFiles.splice(idx, 1);
                updateFileListUI(container, currentFiles);
            });
        });
    }

    // Submit form
    if (sendReportForm) {
        sendReportForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const reportType = document.getElementById('report-type').value;
            
            if (!reportType) {
                alert('Please select a report type.');
                return;
            }
            if (currentFiles.length === 0) {
                alert('Please attach at least one file.');
                return;
            }

            // Prepare Data
            const formData = new FormData();
            formData.append('report_type', reportType);
            
            currentFiles.forEach(file => {
                formData.append('files[]', file);
            });

            // UI Loading
            const origText = submitBtn.innerText;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch('/submit-report', { 
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    sendReportForm.reset();
                    currentFiles = [];
                    updateFileListUI(fileListContainer, currentFiles);
                } else {
                    alert('Failed: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred. Check console.');
            } finally {
                submitBtn.innerText = origText;
                submitBtn.disabled = false;
            }
        });
    }

    // =========================================================
    // 6. COMMITTEE SELECTION LOGIC
    // =========================================================
    const setCommitteeBtn = document.getElementById('setCommitteeBtn');
    const committeeModal = document.getElementById('committeeModal');
    const closeModal = document.getElementById('closeModal');
    const cancelSelection = document.getElementById('cancelSelection');
    const saveCommittees = document.getElementById('saveCommittees');
    const committeeOptions = document.querySelectorAll('.committee-option');
    const selectedCommittees = document.getElementById('selectedCommittees');

    function loadSavedCommittees() {
        committeeOptions.forEach(opt => {
            const cb = opt.querySelector('input');
            cb.checked = false;
            opt.classList.remove('selected');
        });

        const saved = window.userCommittees || [];
        if (saved.length > 0) {
            saved.forEach(value => {
                const targetCb = document.querySelector(`input[value="${value}"]`);
                if (targetCb) {
                    targetCb.checked = true;
                    targetCb.closest('.committee-option').classList.add('selected');
                }
            });
        }
        updateSelectedCommitteesUI();
    }

    // Initialize
    loadSavedCommittees(); 
    if (!window.userCommittees || window.userCommittees.length === 0) {
        if (committeeModal) committeeModal.classList.add('active');
    }

    setCommitteeBtn?.addEventListener('click', () => {
        loadSavedCommittees();
        committeeModal.classList.add('active');
    });

    closeModal?.addEventListener('click', () => committeeModal.classList.remove('active'));
    cancelSelection?.addEventListener('click', () => {
        committeeModal.classList.remove('active');
        loadSavedCommittees();
    });

    committeeModal?.addEventListener('click', (e) => {
        if (e.target === committeeModal) {
            committeeModal.classList.remove('active');
            loadSavedCommittees();
        }
    });

    // Checkbox Interactions
    committeeOptions.forEach(option => {
        const checkbox = option.querySelector('input[type="checkbox"]');
        
        option.addEventListener('click', (e) => {
            if (e.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
            }
            option.classList.toggle('selected', checkbox.checked);
            updateSelectedCommitteesUI();
        });

        checkbox.addEventListener('change', () => {
            option.classList.toggle('selected', checkbox.checked);
            updateSelectedCommitteesUI();
        });
    });

    function updateSelectedCommitteesUI() {
        const selected = Array.from(committeeOptions)
            .filter(option => option.querySelector('input').checked)
            .map(option => `<div class="selected-committee">${option.querySelector('label').textContent}</div>`);

        if (selectedCommittees) {
            selectedCommittees.innerHTML = selected.length > 0 
                ? selected.join('') 
                : '<p style="color: var(--text-color); opacity: 0.7; margin: 0; font-style: italic;">No committees selected yet</p>';
        }
    }

    // Save Committees Logic
    saveCommittees?.addEventListener('click', async () => {
        const selected = Array.from(committeeOptions)
            .filter(option => option.querySelector('input').checked)
            .map(option => option.querySelector('input').value);

        if (selected.length === 0) {
            alert('Please select at least one committee.');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const originalText = saveCommittees.textContent;
        saveCommittees.textContent = 'Saving...';
        saveCommittees.disabled = true;

        try {
            const response = await fetch('/sk/update-committees', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ committees: selected })
            });

            const data = await response.json();

            if (response.ok) {
                alert('Committees saved successfully!');
                committeeModal.classList.remove('active');
                window.location.reload(); 
            } else {
                alert('Failed to save: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Something went wrong.');
        } finally {
            saveCommittees.textContent = originalText;
            saveCommittees.disabled = false;
        }
    });

    // =========================================================
    // 7. YEAR FILTER LOGIC
    // =========================================================
    const yearFilter = document.getElementById('projectYearFilter');
    const yearGroups = document.querySelectorAll('.year-group-container');
    const noDataMsg = document.getElementById('no-data-message');

    if (yearFilter) {
        yearFilter.addEventListener('change', function() {
            const selectedYear = this.value;
            let hasVisibleData = false;

            yearGroups.forEach(group => {
                if (selectedYear === 'all') {
                    group.style.display = 'block';
                    hasVisibleData = true;
                } else {
                    if (group.id === `year-group-${selectedYear}`) {
                        group.style.display = 'block';
                        hasVisibleData = true;
                    } else {
                        group.style.display = 'none';
                    }
                }
            });

            if (noDataMsg) {
                noDataMsg.style.display = hasVisibleData ? 'none' : 'block';
            }
        });
        
        // Initial Trigger
        const event = new Event('change');
        yearFilter.dispatchEvent(event);
    }

    // =========================================================
// 8. HISTORY MODAL FUNCTIONALITY - REAL DATA
// =========================================================
const historyDotsBtn = document.getElementById('historyDotsBtn');
const historyModal = document.getElementById('historyModal');
const closeHistoryModal = document.getElementById('closeHistoryModal');
const applyFilters = document.getElementById('applyFilters');
const resetFilters = document.getElementById('resetFilters');
const filterMonth = document.getElementById('filterMonth');
const filterYear = document.getElementById('filterYear');
const historyList = document.getElementById('historyList');
const prevPage = document.getElementById('prevPage');
const nextPage = document.getElementById('nextPage');
const startCount = document.getElementById('startCount');
const endCount = document.getElementById('endCount');
const totalCount = document.getElementById('totalCount');
const currentPage = document.getElementById('currentPage');

let currentPageNumber = 1;
const itemsPerPage = 5;
let totalItems = 0;
let totalPages = 1;
let isLoading = false;

// Open History Modal
if (historyDotsBtn) {
    historyDotsBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        historyModal.style.display = 'flex';
        
        // Set current month and year
        const now = new Date();
        filterMonth.value = (now.getMonth() + 1).toString();
        filterYear.value = now.getFullYear().toString();
        
        // Load data
        await loadHistoryData();
    });
}

// Close History Modal
if (closeHistoryModal) {
    closeHistoryModal.addEventListener('click', () => {
        historyModal.style.display = 'none';
    });
}

// Close modal when clicking outside
if (historyModal) {
    historyModal.addEventListener('click', (e) => {
        if (e.target === historyModal) {
            historyModal.style.display = 'none';
        }
    });
}

// Apply Filters
if (applyFilters) {
    applyFilters.addEventListener('click', async () => {
        currentPageNumber = 1;
        await loadHistoryData();
    });
}

// Reset Filters
if (resetFilters) {
    resetFilters.addEventListener('click', async () => {
        filterMonth.value = 'all';
        filterYear.value = 'all';
        currentPageNumber = 1;
        await loadHistoryData();
    });
}

// Pagination
if (prevPage) {
    prevPage.addEventListener('click', async () => {
        if (currentPageNumber > 1) {
            currentPageNumber--;
            await loadHistoryData();
        }
    });
}

if (nextPage) {
    nextPage.addEventListener('click', async () => {
        if (currentPageNumber < totalPages) {
            currentPageNumber++;
            await loadHistoryData();
        }
    });
}

// Load History Data from API
async function loadHistoryData() {
    if (isLoading) return;
    
    isLoading = true;
    
    // Show loading state
    if (historyList) {
        historyList.innerHTML = `
            <div class="history-empty-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading history...</p>
            </div>
        `;
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Prepare query params
    const params = new URLSearchParams({
        page: currentPageNumber,
        per_page: itemsPerPage
    });
    
    if (filterMonth.value !== 'all') {
        params.append('month', filterMonth.value);
    }
    
    if (filterYear.value !== 'all') {
        params.append('year', filterYear.value);
    }
    
    try {
        const response = await fetch(`/reports/history?${params.toString()}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            updateHistoryList(data.data);
            updatePaginationControls(data);
        } else {
            showError('Failed to load history');
        }
    } catch (error) {
        console.error('Error loading history:', error);
        showError('Error loading report history');
    } finally {
        isLoading = false;
    }
}

// Update History List
function updateHistoryList(data) {
    if (!historyList) return;
    
    if (!data || data.length === 0) {
        historyList.innerHTML = `
            <div class="history-empty-state">
                <i class="fas fa-history"></i>
                <p>No report history found</p>
            </div>
        `;
        return;
    }
    
    historyList.innerHTML = data.map(item => `
        <div class="history-item">
            <div>
                <span class="report-type-badge ${item.report_type}">
                    ${getReportTypeLabel(item.report_type)}
                </span>
                <div style="font-size: 0.85rem; margin-top: 4px; color: var(--text-color); opacity: 0.8;">
                    ${item.title || 'No title'}
                </div>
            </div>
            <div class="history-date">
                ${formatDate(item.date)}
            </div>
            <div class="file-count">
                <i class="fas fa-file"></i> ${item.files} file${item.files > 1 ? 's' : ''}
            </div>
            <div class="history-remarks" title="${item.remarks || ''}">
                ${item.remarks || 'No remarks'}
            </div>
        </div>
    `).join('');
}

// Helper function for report type label
function getReportTypeLabel(type) {
    const labels = {
        'accomplishment': 'Accomplishment Report',
        'financial': 'Propose Project',
        'proposal': 'Project Proposal'
    };
    return labels[type] || type;
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Update Pagination Controls
function updatePaginationControls(data) {
    if (!totalCount || !startCount || !endCount || !currentPage || !prevPage || !nextPage) return;
    
    totalItems = data.total || 0;
    totalPages = data.total_pages || 1;
    const perPage = data.per_page || itemsPerPage;
    
    const startIndex = ((currentPageNumber - 1) * perPage) + 1;
    const endIndex = Math.min(startIndex + perPage - 1, totalItems);
    
    totalCount.textContent = totalItems;
    startCount.textContent = totalItems > 0 ? startIndex : 0;
    endCount.textContent = endIndex;
    currentPage.textContent = currentPageNumber;
    
    prevPage.disabled = currentPageNumber === 1;
    nextPage.disabled = currentPageNumber === totalPages || totalPages === 0;
}

// Show error message
function showError(message) {
    if (historyList) {
        historyList.innerHTML = `
            <div class="history-empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
            </div>
        `;
    }
}

// Also, update your send report form to refresh history after submission
if (sendReportForm) {
    sendReportForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const reportType = document.getElementById('report-type').value;
        
        if (!reportType) {
            alert('Please select a report type.');
            return;
        }
        if (currentFiles.length === 0) {
            alert('Please attach at least one file.');
            return;
        }

        // Prepare Data
        const formData = new FormData();
        formData.append('report_type', reportType);
        
        // Add title and remarks if you have them
        const reportTitle = prompt('Enter report title (optional):', '');
        if (reportTitle) formData.append('title', reportTitle);
        
        const reportRemarks = prompt('Enter remarks (optional):', '');
        if (reportRemarks) formData.append('remarks', reportRemarks);
        
        currentFiles.forEach(file => {
            formData.append('files[]', file);
        });

        // UI Loading
        const origText = submitBtn.innerText;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const response = await fetch('/submit-report', { 
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                sendReportForm.reset();
                currentFiles = [];
                updateFileListUI(fileListContainer, currentFiles);
                
                // Refresh history if modal is open
                if (historyModal.style.display === 'flex') {
                    await loadHistoryData();
                }
            } else {
                alert('Failed: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error(error);
            alert('An error occurred. Check console.');
        } finally {
            submitBtn.innerText = origText;
            submitBtn.disabled = false;
        }
    });
}

});
</script>
</body>
</html>