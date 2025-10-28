<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Youth Registration List</title>
  <link rel="stylesheet" href="{{ asset('css/youth-registration-list.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    /* Additional styles for attendance tracking */
    .day-attendance-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
      padding: 8px;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
    }
    
    .day-attendance-item label {
      display: flex;
      align-items: center;
      cursor: pointer;
      width: 100%;
    }
    
    .day-attendance-item input[type="checkbox"] {
      margin-right: 10px;
    }
    
    .day-label {
      font-weight: 500;
    }
    
    .day-attendance-summary {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .day-attendance-badge {
      background: #f0f0f0;
      padding: 2px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
    }
    
    .day-attendance-badge.present {
      background: #e8f5e8;
      color: #2e7d32;
    }
    
    .day-attendance-badge.absent {
      background: #ffebee;
      color: #c62828;
    }
    
    .attendance-summary-small {
      font-size: 12px;
      color: #666;
    }
    
    .days-attendance-container {
      max-height: 300px;
      overflow-y: auto;
      padding: 10px;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
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
        <a href="#" class="nav-link">
          <i data-lucide="calendar"></i>
          <span class="label">Events and Programs</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('sk-eventpage') }}">Events List</a>
          <a href="{{ route('youth-program-registration') }}">Youth Registration</a>
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
        <div class="time" id="currentTime">MON 10:00 <span>AM</span></div>

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

    <div class="registration-list-container">
      <!-- Header -->
      <div class="header-section">
        <h2>{{ $program->title }} - Registration List</h2>
        <div class="program-info">
          <span class="program-date">
            <i class="fas fa-calendar"></i>
            @if($program->event_end_date && $program->event_end_date != $program->event_date)
              {{ \Carbon\Carbon::parse($program->event_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($program->event_end_date)->format('M d, Y') }}
            @else
              {{ \Carbon\Carbon::parse($program->event_date)->format('M d, Y') }}
            @endif
            at {{ \Carbon\Carbon::parse($program->event_time)->format('g:i A') }}
          </span>
          <span class="program-category">
            <i class="fas fa-tag"></i>
            {{ ucfirst($program->category) }}
          </span>
          <span class="total-registrations">
            <i class="fas fa-users"></i>
            {{ count($registrations) }} Registration(s)
          </span>
          <span class="attendance-summary">
            <i class="fas fa-user-check"></i>
            <span id="attendedCount">{{ $attendedCount ?? 0 }}</span> Attended / 
            <span id="totalCount">{{ count($registrations) }}</span> Total
          </span>
        </div>
      </div>

      <!-- Program Days Section -->
      <div class="program-days-section">
        <h3>Program Days</h3>
        <div class="days-container" id="programDaysContainer">
          <!-- Days will be dynamically generated here -->
        </div>
      </div>

      <!-- Actions Row -->
      <div class="actions-row">
        <div class="left-actions">
          <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search by name, email, or reference ID" id="searchInput">
          </div>
          <button class="filter-btn" id="filterBtn">
            <i class="fa fa-filter"></i> Filter
          </button>
        </div>

        <div class="right-action">
          <button class="download-btn" id="downloadBtn">
            Download All <i class="fa-solid fa-download"></i>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="table-wrapper">
        <table class="attendees-table" id="registrationsTable">
          <thead>
            <tr>
              <th>Reference ID</th>
              <th>Name</th>
              <th>Age</th>
              <th>Contact Number</th>
              <th>Barangay</th>
              <th>Registered At</th>
              <th>Overall Attendance</th>
              <th>Daily Attendance</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $registration)
              <tr data-registration-id="{{ $registration['id'] }}">
                <td>
                  <span class="reference-id">{{ $registration['reference_id'] }}</span>
                </td>
                <td>
                  <div class="user-info">
                    <strong>{{ $registration['user_name'] }}</strong>
                    <small>{{ $registration['email'] }}</small>
                  </div>
                </td>
                <td>{{ $registration['age'] ?? 'N/A' }}</td>
                <td>{{ $registration['contact_no'] ?? 'N/A' }}</td>
                <td>{{ $registration['barangay'] ?? 'N/A' }}</td>
                <td>{{ $registration['registered_at'] }}</td>
                <td>
                  <span class="attendance-status attendance-{{ $registration['attended'] ? 'present' : 'absent' }}">
                    {{ $registration['attended'] ? 'Present' : 'Absent' }}
                  </span>
                </td>
                <td>
                  <div class="daily-attendance" id="dailyAttendance_{{ $registration['id'] }}">
                    <!-- Daily attendance will be populated by JavaScript -->
                  </div>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="manage-attendance-btn" data-registration-id="{{ $registration['id'] }}">
                      <i class="fas fa-calendar-check"></i> Manage Days
                    </button>
                    <button class="view-details-btn" data-registration-id="{{ $registration['id'] }}">
                      <i class="fas fa-eye"></i> Details
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="no-data">
                  <i class="fas fa-users-slash"></i>
                  No registrations found for this program
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Back Button -->
      <div class="back-section">
        <a href="{{ route('youth-program-registration') }}" class="back-btn">
          <i class="fas fa-arrow-left"></i> Back to Programs
        </a>
      </div>
    </div>
  </div>

  <!-- Registration Details Modal -->
  <div id="registrationDetailsModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="modal-header">
        <h2>Registration Details</h2>
        <span class="reference-id-modal" id="modalReferenceId"></span>
      </div>
      <div class="modal-body">
        <div class="details-grid">
          <div class="detail-section">
            <h4>Personal Information</h4>
            <div class="detail-item">
              <label>Full Name:</label>
              <span id="modalUserName"></span>
            </div>
            <div class="detail-item">
              <label>Email:</label>
              <span id="modalUserEmail"></span>
            </div>
            <div class="detail-item">
              <label>Contact Number:</label>
              <span id="modalUserContact"></span>
            </div>
            <div class="detail-item">
              <label>Age:</label>
              <span id="modalUserAge"></span>
            </div>
            <div class="detail-item">
              <label>Barangay:</label>
              <span id="modalUserBarangay"></span>
            </div>
          </div>
          
          <div class="detail-section">
            <h4>Registration Information</h4>
            <div class="detail-item">
              <label>Overall Attendance:</label>
              <span id="modalAttendanceStatus" class="attendance-status"></span>
            </div>
            <div class="detail-item">
              <label>Registered At:</label>
              <span id="modalRegisteredAt"></span>
            </div>
          </div>
          
          <div class="detail-section" id="dailyAttendanceSection">
            <h4>Daily Attendance</h4>
            <div id="modalDailyAttendance"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="close-btn">Close</button>
      </div>
    </div>
  </div>

  <!-- Daily Attendance Modal -->
  <div id="dailyAttendanceModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="modal-header">
        <h2>Manage Daily Attendance</h2>
        <span class="reference-id-modal" id="dailyModalReferenceId"></span>
      </div>
      <div class="modal-body">
        <div class="user-info-header">
          <h4 id="dailyModalUserName"></h4>
          <p id="dailyModalUserInfo"></p>
        </div>
        <div class="days-attendance-container" id="daysAttendanceContainer">
          <!-- Days attendance checkboxes will be populated here -->
        </div>
      </div>
      <div class="modal-footer">
        <button class="cancel-btn" id="cancelDailyAttendance">Cancel</button>
        <button class="confirm-btn" id="saveDailyAttendance">Save Attendance</button>
      </div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Basic sidebar toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }

    // Update time
    function updateTime() {
        const timeEl = document.getElementById('currentTime');
        if (!timeEl) return;
        
        const now = new Date();
        const options = { 
            weekday: 'short', 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        };
        const timeString = now.toLocaleTimeString('en-US', options);
        timeEl.innerHTML = timeString.replace('AM', '<span>AM</span>').replace('PM', '<span>PM</span>');
    }
    
    updateTime();
    setInterval(updateTime, 60000);

    // Simple search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#registrationsTable tbody tr');
            
            rows.forEach(row => {
                if (row.classList.contains('no-data')) return;
                
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Filter button
    const filterBtn = document.getElementById('filterBtn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const attendance = prompt('Filter by attendance (present/absent/all):', 'all');
            const rows = document.querySelectorAll('#registrationsTable tbody tr');
            
            rows.forEach(row => {
                if (row.classList.contains('no-data')) return;
                
                if (!attendance || attendance.toLowerCase() === 'all') {
                    row.style.display = '';
                    return;
                }
                
                const status = row.querySelector('.attendance-status')?.textContent.toLowerCase();
                row.style.display = status === attendance.toLowerCase() ? '' : 'none';
            });
        });
    }

    // Download functionality
    const downloadBtn = document.getElementById('downloadBtn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            alert('Download functionality would be implemented here');
        });
    }

    // Program days generation
    function generateProgramDays() {
        const container = document.getElementById('programDaysContainer');
        if (!container) return;
        
        // Get program dates from PHP variables
        const startDate = new Date('{{ $program->event_date }}');
        const endDate = new Date('{{ $program->event_end_date ? $program->event_end_date : $program->event_date }}');
        
        let currentDate = new Date(startDate);
        let dayNumber = 1;
        
        container.innerHTML = '';
        
        while (currentDate <= endDate) {
            const dayElement = document.createElement('div');
            dayElement.className = 'program-day';
            dayElement.innerHTML = `
                <span class="day-number">Day ${dayNumber}</span>
                <span class="day-date">${currentDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</span>
                <span class="day-attendance-count" id="dayAttendanceCount_${dayNumber}">0/{{ count($registrations) }}</span>
            `;
            container.appendChild(dayElement);
            
            currentDate.setDate(currentDate.getDate() + 1);
            dayNumber++;
        }
    }
    
    generateProgramDays();

    // Initialize daily attendance displays
    function initializeDailyAttendance() {
        const rows = document.querySelectorAll('#registrationsTable tbody tr[data-registration-id]');
        
        rows.forEach(row => {
            const registrationId = row.getAttribute('data-registration-id');
            const dailyAttendanceCell = document.getElementById('dailyAttendance_' + registrationId);
            
            if (dailyAttendanceCell) {
                // Initially set to 0/total days
                const totalDays = getTotalProgramDays();
                dailyAttendanceCell.innerHTML = `<span class="attendance-summary-small">0/${totalDays} days</span>`;
            }
        });
    }
    
    initializeDailyAttendance();

    // Get total program days
    function getTotalProgramDays() {
        const startDate = new Date('{{ $program->event_date }}');
        const endDate = new Date('{{ $program->event_end_date ? $program->event_end_date : $program->event_date }}');
        
        const timeDiff = endDate.getTime() - startDate.getTime();
        const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end dates
        
        return dayDiff;
    }

    // Modal management
    const modals = {
        registrationDetails: document.getElementById('registrationDetailsModal'),
        dailyAttendance: document.getElementById('dailyAttendanceModal')
    };

    const closeButtons = document.querySelectorAll('.close, .close-btn, .cancel-btn');
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            Object.values(modals).forEach(modal => {
                if (modal) modal.style.display = 'none';
            });
        });
    });

    // View details button
    document.addEventListener('click', function(e) {
        if (e.target.closest('.view-details-btn')) {
            const button = e.target.closest('.view-details-btn');
            const registrationId = button.getAttribute('data-registration-id');
            showRegistrationDetails(registrationId);
        }
        
        if (e.target.closest('.manage-attendance-btn')) {
            const button = e.target.closest('.manage-attendance-btn');
            const registrationId = button.getAttribute('data-registration-id');
            showDailyAttendanceModal(registrationId);
        }
    });

    // Show registration details
    function showRegistrationDetails(registrationId) {
        const row = document.querySelector('[data-registration-id="' + registrationId + '"]');
        if (!row || !modals.registrationDetails) return;

        // Get data from table row
        const referenceId = row.querySelector('.reference-id')?.textContent || 'N/A';
        const userName = row.querySelector('.user-info strong')?.textContent || 'N/A';
        const userEmail = row.querySelector('.user-info small')?.textContent || 'N/A';
        const userAge = row.cells[2]?.textContent || 'N/A';
        const userContact = row.cells[3]?.textContent || 'N/A';
        const userBarangay = row.cells[4]?.textContent || 'N/A';
        const registeredAt = row.cells[5]?.textContent || 'N/A';
        const attendanceStatus = row.querySelector('.attendance-status')?.textContent || 'N/A';

        // Populate modal
        document.getElementById('modalReferenceId').textContent = referenceId;
        document.getElementById('modalUserName').textContent = userName;
        document.getElementById('modalUserEmail').textContent = userEmail;
        document.getElementById('modalUserAge').textContent = userAge;
        document.getElementById('modalUserContact').textContent = userContact;
        document.getElementById('modalUserBarangay').textContent = userBarangay;
        document.getElementById('modalRegisteredAt').textContent = registeredAt;
        
        const attendanceElement = document.getElementById('modalAttendanceStatus');
        if (attendanceElement) {
            attendanceElement.textContent = attendanceStatus;
            attendanceElement.className = 'attendance-status attendance-' + attendanceStatus.toLowerCase();
        }

        // Load daily attendance details
        loadDailyAttendanceDetails(registrationId);

        modals.registrationDetails.style.display = 'block';
    }

    // Load daily attendance details for the modal
    function loadDailyAttendanceDetails(registrationId) {
        const container = document.getElementById('modalDailyAttendance');
        if (!container) return;

        // Fetch actual attendance data from server
        container.innerHTML = '<p>Loading daily attendance...</p>';
        
        fetch(`/programs/daily-attendance/${registrationId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const totalDays = getTotalProgramDays();
                let presentDays = 0;
                let html = '';
                
                // Generate day items based on actual data
                for (let i = 1; i <= totalDays; i++) {
                    const dayKey = `day_${i}`;
                    const isPresent = data.attendance_data[dayKey] || false;
                    if (isPresent) presentDays++;
                    
                    html += `
                        <div class="day-attendance-item">
                            <span class="day-label">Day ${i}</span>
                            <span class="day-attendance-badge ${isPresent ? 'present' : 'absent'}">
                                ${isPresent ? 'Present' : 'Absent'}
                            </span>
                        </div>
                    `;
                }
                
                html = `<div class="attendance-summary">${presentDays}/${totalDays} days attended</div>` + html;
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p>Error loading attendance data</p>';
            }
        })
        .catch(error => {
            console.error('Error loading attendance details:', error);
            container.innerHTML = '<p>Error loading attendance data</p>';
        });
    }

    // Show daily attendance modal
    function showDailyAttendanceModal(registrationId) {
        const row = document.querySelector('[data-registration-id="' + registrationId + '"]');
        if (!row || !modals.dailyAttendance) return;

        const referenceId = row.querySelector('.reference-id')?.textContent || 'N/A';
        const userName = row.querySelector('.user-info strong')?.textContent || 'N/A';

        document.getElementById('dailyModalReferenceId').textContent = referenceId;
        document.getElementById('dailyModalUserName').textContent = userName;
        document.getElementById('dailyModalUserInfo').textContent = 'Reference: ' + referenceId;

        // Store the current registration ID for saving
        modals.dailyAttendance.dataset.registrationId = registrationId;

        // Generate days checkboxes
        generateAttendanceCheckboxes(registrationId);

        modals.dailyAttendance.style.display = 'block';
    }

    // Generate attendance checkboxes
    function generateAttendanceCheckboxes(registrationId) {
        const container = document.getElementById('daysAttendanceContainer');
        if (!container) return;

        const totalDays = getTotalProgramDays();
        
        // Show loading state
        container.innerHTML = '<p>Loading attendance data...</p>';

        // Fetch existing attendance data from server
        fetch(`/programs/daily-attendance/${registrationId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            container.innerHTML = '';
            
            if (data.success) {
                const attendanceData = data.attendance_data || {};
                
                for (let i = 1; i <= totalDays; i++) {
                    const dayKey = `day_${i}`;
                    const isChecked = attendanceData[dayKey] || false;
                    
                    const dayElement = document.createElement('div');
                    dayElement.className = 'day-attendance-item';
                    dayElement.innerHTML = `
                        <label>
                            <input type="checkbox" name="attendance_day_${i}" value="day_${i}" data-day="${i}" ${isChecked ? 'checked' : ''}>
                            <span class="day-label">Day ${i}</span>
                        </label>
                    `;
                    container.appendChild(dayElement);
                }
            } else {
                container.innerHTML = '<p>Error loading attendance data</p>';
            }
        })
        .catch(error => {
            console.error('Error loading attendance:', error);
            container.innerHTML = '<p>Error loading attendance data</p>';
        });
    }

    // Save daily attendance
    const saveAttendanceBtn = document.getElementById('saveDailyAttendance');
    if (saveAttendanceBtn) {
        saveAttendanceBtn.addEventListener('click', function() {
            const registrationId = modals.dailyAttendance.dataset.registrationId;
            if (!registrationId) {
                alert('Error: No registration ID found');
                return;
            }

            saveDailyAttendance(registrationId);
        });
    }

    // Save daily attendance to server
    function saveDailyAttendance(registrationId) {
        const checkboxes = document.querySelectorAll('#daysAttendanceContainer input[type="checkbox"]');
        const attendanceData = {};
        let presentCount = 0;
        const totalDays = getTotalProgramDays();

        // Collect attendance data
        checkboxes.forEach(checkbox => {
            const day = checkbox.dataset.day;
            attendanceData[`day_${day}`] = checkbox.checked;
            if (checkbox.checked) presentCount++;
        });

        // Show loading state
        const saveBtn = document.getElementById('saveDailyAttendance');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        // Send data to server - FIXED: Using the correct route without registrationId parameter
        fetch('{{ route("programs.update-daily-attendance") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                registration_id: registrationId,
                attendance_data: attendanceData,
                present_count: presentCount,
                total_days: totalDays
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                updateAttendanceDisplay(registrationId, presentCount, totalDays);
                alert('Attendance saved successfully!');
                modals.dailyAttendance.style.display = 'none';
            } else {
                throw new Error(data.message || 'Failed to save attendance');
            }
        })
        .catch(error => {
            console.error('Error saving attendance:', error);
            alert('Error saving attendance: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }

    // Update attendance display in the table
    function updateAttendanceDisplay(registrationId, presentCount, totalDays) {
        // Update the daily attendance cell
        const dailyAttendanceCell = document.getElementById('dailyAttendance_' + registrationId);
        if (dailyAttendanceCell) {
            dailyAttendanceCell.innerHTML = `<span class="attendance-summary-small">${presentCount}/${totalDays} days</span>`;
        }

        // Update overall attendance status if all days are present
        const row = document.querySelector(`[data-registration-id="${registrationId}"]`);
        if (row && presentCount === totalDays && totalDays > 0) {
            const overallStatus = row.querySelector('.attendance-status');
            if (overallStatus) {
                overallStatus.textContent = 'Present';
                overallStatus.className = 'attendance-status attendance-present';
                
                // Update the attended count in the header
                updateAttendedCount(1);
            }
        } else if (row && presentCount === 0) {
            const overallStatus = row.querySelector('.attendance-status');
            if (overallStatus) {
                overallStatus.textContent = 'Absent';
                overallStatus.className = 'attendance-status attendance-absent';
                
                // Update the attended count in the header
                updateAttendedCount(-1);
            }
        }
    }

    // Update the attended count in the header
    function updateAttendedCount(change) {
        const attendedCountEl = document.getElementById('attendedCount');
        if (attendedCountEl) {
            const currentCount = parseInt(attendedCountEl.textContent) || 0;
            attendedCountEl.textContent = Math.max(0, currentCount + change);
        }
    }

    // Close modals when clicking outside
    Object.values(modals).forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    });

    // Logout confirmation
    window.confirmLogout = function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    };

    // Notification and profile dropdowns
    const notifWrapper = document.querySelector('.notification-wrapper');
    const profileWrapper = document.querySelector('.profile-wrapper');

    if (notifWrapper) {
        notifWrapper.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            if (profileWrapper) profileWrapper.classList.remove('active');
        });
    }

    if (profileWrapper) {
        profileWrapper.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            if (notifWrapper) notifWrapper.classList.remove('active');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        if (notifWrapper) notifWrapper.classList.remove('active');
        if (profileWrapper) profileWrapper.classList.remove('active');
    });
});
</script>
</body>
</html>