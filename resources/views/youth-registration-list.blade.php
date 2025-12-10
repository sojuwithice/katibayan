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
      justify-content: space-between;
      margin-bottom: 10px;
      padding: 8px;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
    }
    
    .day-attendance-item label {
      display: flex;
      align-items: center;
      cursor: pointer;
      flex-grow: 1;
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
    
    /* Add Day Button */
    .add-day-btn {
      background: #3C87C4;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 600;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .add-day-btn:hover {
      background: #2a6da9;
    }
    
    /* Custom Modal Styles */
    .custom-modal {
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
    
    .custom-modal-content {
      background: white;
      border-radius: 12px;
      width: 90%;
      max-width: 500px;
      max-height: 80vh;
      overflow-y: auto;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
      padding: 20px;
      border-bottom: 1px solid #e0e0e0;
    }
    
    .modal-header h2 {
      margin: 0;
      color: #333;
    }
    
    .modal-body {
      padding: 20px;
    }
    
    .modal-footer {
      padding: 20px;
      border-top: 1px solid #e0e0e0;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    
    .modal-close {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #666;
      background: none;
      border: none;
    }
    
    .modal-close:hover {
      color: #333;
    }
    
    /* Export Modal Specific */
    .export-options {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin: 20px 0;
    }
    
    .export-option {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .export-option:hover {
      border-color: #3C87C4;
      background: #f5f9fd;
    }
    
    .export-option.selected {
      border-color: #3C87C4;
      background: #e8f0fa;
    }
    
    .export-option i {
      font-size: 32px;
      color: #3C87C4;
      margin-bottom: 10px;
    }
    
    .export-option h4 {
      margin: 0 0 5px 0;
      color: #333;
    }
    
    .export-option p {
      margin: 0;
      color: #666;
      font-size: 14px;
    }
    
    /* Confirmation Modal */
    .confirmation-modal-content {
      text-align: center;
      padding: 30px;
    }
    
    .confirmation-icon {
      font-size: 48px;
      color: #4CAF50;
      margin-bottom: 20px;
    }
    
    .confirmation-text {
      margin-bottom: 20px;
      color: #333;
    }
    
    /* Remove Day Button */
    .remove-day-btn {
      background: #ff6b6b;
      color: white;
      border: none;
      padding: 4px 8px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      margin-left: 10px;
    }
    
    .remove-day-btn:hover {
      background: #ff5252;
    }
    
    /* Day Management Header */
    .day-management-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    
    .current-days-info {
      color: #666;
      font-size: 14px;
    }
    
    /* New Day Input Section */
    .new-day-input {
      display: flex;
      gap: 10px;
      margin-top: 15px;
      padding: 15px;
      background: #f9f9f9;
      border-radius: 8px;
    }
    
    .new-day-input input {
      flex: 1;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    .new-day-input button {
      background: #4CAF50;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
    }
    
    .new-day-input button:hover {
      background: #45a049;
    }
    
    /* Fix for event time display */
    .program-info {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 10px;
    }
    
    .program-date,
    .program-category,
    .total-registrations,
    .attendance-summary,
    .program-days {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 14px;
      color: #666;
    }
    
    .program-date i,
    .program-category i,
    .total-registrations i,
    .attendance-summary i,
    .program-days i {
      color: #3C87C4;
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
        <h2>{{ $program->title ?? 'Program Title' }} - Registration List</h2>
        <div class="program-info">
          <span class="program-date">
            <i class="fas fa-calendar"></i>
            @php
              // Safely parse dates
              $eventDate = isset($program->event_date) ? \Carbon\Carbon::parse($program->event_date) : null;
              $eventEndDate = isset($program->event_end_date) ? \Carbon\Carbon::parse($program->event_end_date) : null;
              $eventTime = isset($program->event_time) ? $program->event_time : '00:00:00';
            @endphp
            
            @if($eventDate && $eventEndDate && $eventEndDate->notEqualTo($eventDate))
              {{ $eventDate->format('M d, Y') }} - {{ $eventEndDate->format('M d, Y') }}
            @elseif($eventDate)
              {{ $eventDate->format('M d, Y') }}
            @else
              Date not specified
            @endif
            
            @if($eventTime)
              @php
                // Convert time to readable format
                try {
                  $time = \Carbon\Carbon::createFromFormat('H:i:s', $eventTime);
                  $formattedTime = $time->format('g:i A');
                } catch (\Exception $e) {
                  // If format is not H:i:s, try other formats
                  $formattedTime = date('g:i A', strtotime($eventTime));
                }
              @endphp
              at {{ $formattedTime }}
            @endif
          </span>
          <span class="program-category">
            <i class="fas fa-tag"></i>
            {{ ucfirst($program->category ?? 'Uncategorized') }}
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
          <span class="program-days">
            <i class="fas fa-calendar-alt"></i>
            <span id="totalDaysCount">{{ $program->number_of_days ?? 1 }}</span> Day(s)
          </span>
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
              @php
                // Calculate present days from attendance data
                $presentDays = 0;
                $totalDays = $program->number_of_days ?? 1;
                
                if (isset($registration['attendance_data']) && is_array($registration['attendance_data'])) {
                  $presentDays = array_sum(array_values($registration['attendance_data']));
                } elseif (isset($registration['custom_fields']) && is_array($registration['custom_fields'])) {
                  // Try to get attendance from custom fields
                  foreach ($registration['custom_fields'] as $key => $value) {
                    if (strpos($key, 'day_') === 0 && $value) {
                      $presentDays++;
                    }
                  }
                }
                
                // Check if attended (for overall attendance)
                $attended = $registration['attended'] ?? ($presentDays > 0);
              @endphp
              
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
                  <span class="attendance-status attendance-{{ $attended ? 'present' : 'absent' }}">
                    {{ $attended ? 'Present' : 'Absent' }}
                  </span>
                </td>
                <td>
                  <div class="daily-attendance" id="dailyAttendance_{{ $registration['id'] }}">
                    <span class="attendance-summary-small">
                      {{ $presentDays }}/{{ $totalDays }} days
                    </span>
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
        <div class="current-days-info" id="currentDaysInfo"></div>
      </div>
      <div class="modal-body">
        <div class="user-info-header">
          <h4 id="dailyModalUserName"></h4>
          <p id="dailyModalUserInfo"></p>
        </div>
        
        <div class="day-management-header">
          <h5>Daily Attendance</h5>
          @if(!$program->event_end_date || (!$program->event_end_date && $program->event_date) || ($program->event_end_date && $program->event_end_date == $program->event_date))
            <button class="add-day-btn" id="addDayBtn">
              <i class="fas fa-plus"></i> Add Day
            </button>
          @endif
        </div>
        
        <div class="days-attendance-container" id="daysAttendanceContainer">
          <!-- Days attendance checkboxes will be populated here -->
        </div>
        
        @if(!$program->event_end_date || (!$program->event_end_date && $program->event_date) || ($program->event_end_date && $program->event_end_date == $program->event_date))
        <div class="new-day-input" id="newDayInput" style="display: none;">
          <input type="text" id="newDayName" placeholder="Enter day name (e.g., Day 2, Workshop Day, etc.)">
          <button id="confirmAddDay">Add</button>
          <button id="cancelAddDay">Cancel</button>
        </div>
        @endif
      </div>
      <div class="modal-footer">
        <button class="cancel-btn" id="cancelDailyAttendance">Cancel</button>
        <button class="confirm-btn" id="saveDailyAttendance">Save Attendance</button>
      </div>
    </div>
  </div>

  <!-- Export/Download Modal -->
  <div id="exportModal" class="custom-modal">
    <div class="custom-modal-content">
      <button class="modal-close" id="closeExportModal">&times;</button>
      <div class="modal-header">
        <h2>Export Registration Data</h2>
      </div>
      <div class="modal-body">
        <div class="export-options" id="exportOptions">
          <div class="export-option" data-format="csv">
            <i class="fas fa-file-csv"></i>
            <h4>CSV Format</h4>
            <p>Comma separated values, compatible with Excel and Google Sheets</p>
          </div>
          <div class="export-option" data-format="excel">
            <i class="fas fa-file-excel"></i>
            <h4>Excel Format</h4>
            <p>Microsoft Excel file (.xlsx) with formatted columns</p>
          </div>
          <div class="export-option" data-format="pdf">
            <i class="fas fa-file-pdf"></i>
            <h4>PDF Report</h4>
            <p>Printable PDF document with attendance summary</p>
          </div>
        </div>
        
        <div class="export-filters">
          <h4>Filter Data</h4>
          <div style="margin: 15px 0;">
            <label style="display: block; margin-bottom: 5px;">
              <input type="checkbox" id="includeAllData" checked> Include all registration data
            </label>
            <label style="display: block; margin-bottom: 5px;">
              <input type="checkbox" id="includeAttendance" checked> Include attendance records
            </label>
            <label style="display: block; margin-bottom: 5px;">
              <input type="checkbox" id="includeCustomFields"> Include custom field responses
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="cancel-btn" id="cancelExport">Cancel</button>
        <button class="confirm-btn" id="confirmExport" disabled>Export Data</button>
      </div>
    </div>
  </div>

  <!-- Export Confirmation Modal -->
  <div id="exportConfirmationModal" class="custom-modal">
    <div class="custom-modal-content confirmation-modal-content">
      <div class="confirmation-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <h3>Export in Progress</h3>
      <p class="confirmation-text" id="exportConfirmationText">
        Your export file is being prepared. This may take a moment...
      </p>
      <div class="modal-footer">
        <button class="confirm-btn" id="closeConfirmationModal">OK</button>
      </div>
    </div>
  </div>

<script>
// Global variables
let currentProgramDays = {{ $program->number_of_days ?? 1 }};
let selectedExportFormat = null;
let currentRegistrationId = null;
let attendanceData = {};

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

    // Download/Export modal
    const downloadBtn = document.getElementById('downloadBtn');
    const exportModal = document.getElementById('exportModal');
    const closeExportModal = document.getElementById('closeExportModal');
    const cancelExport = document.getElementById('cancelExport');
    const exportOptions = document.querySelectorAll('.export-option');
    const confirmExport = document.getElementById('confirmExport');
    const exportConfirmationModal = document.getElementById('exportConfirmationModal');
    const closeConfirmationModal = document.getElementById('closeConfirmationModal');

    // Open export modal
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            exportModal.style.display = 'flex';
        });
    }

    // Close export modal
    if (closeExportModal) {
        closeExportModal.addEventListener('click', function() {
            exportModal.style.display = 'none';
        });
    }

    if (cancelExport) {
        cancelExport.addEventListener('click', function() {
            exportModal.style.display = 'none';
        });
    }

    // Select export format
    exportOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all options
            exportOptions.forEach(opt => opt.classList.remove('selected'));
            // Select current option
            this.classList.add('selected');
            selectedExportFormat = this.dataset.format;
            confirmExport.disabled = false;
        });
    });

    // Confirm export
    if (confirmExport) {
        confirmExport.addEventListener('click', function() {
            if (!selectedExportFormat) {
                alert('Please select an export format');
                return;
            }

            // Show confirmation modal
            exportModal.style.display = 'none';
            exportConfirmationModal.style.display = 'flex';

            // Start export process
            exportData(selectedExportFormat);
        });
    }

    // Close confirmation modal
    if (closeConfirmationModal) {
        closeConfirmationModal.addEventListener('click', function() {
            exportConfirmationModal.style.display = 'none';
            selectedExportFormat = null;
            exportOptions.forEach(opt => opt.classList.remove('selected'));
            confirmExport.disabled = true;
        });
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === exportModal) {
            exportModal.style.display = 'none';
        }
        if (e.target === exportConfirmationModal) {
            exportConfirmationModal.style.display = 'none';
            selectedExportFormat = null;
            exportOptions.forEach(opt => opt.classList.remove('selected'));
            confirmExport.disabled = true;
        }
    });

    // Export data function
    function exportData(format) {
        const includeAllData = document.getElementById('includeAllData').checked;
        const includeAttendance = document.getElementById('includeAttendance').checked;
        const includeCustomFields = document.getElementById('includeCustomFields').checked;

        // Prepare export data
        const exportData = {
            program_id: {{ $program->id ?? 0 }},
            format: format,
            include_all_data: includeAllData,
            include_attendance: includeAttendance,
            include_custom_fields: includeCustomFields,
            _token: '{{ csrf_token() }}'
        };

        // Show loading message
        document.getElementById('exportConfirmationText').textContent = 
            `Preparing ${format.toUpperCase()} export... This may take a moment.`;

        // Send export request
        fetch('{{ route("programs.export-registrations") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(exportData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update confirmation message
                document.getElementById('exportConfirmationText').textContent = 
                    'Export completed successfully! Your download will start automatically.';

                // Trigger download
                if (data.download_url) {
                    setTimeout(() => {
                        window.location.href = data.download_url;
                    }, 1000);
                }
            } else {
                throw new Error(data.message || 'Export failed');
            }
        })
        .catch(error => {
            console.error('Export error:', error);
            document.getElementById('exportConfirmationText').textContent = 
                'Error: ' + error.message;
        });
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
                let presentDays = 0;
                let html = '';
                
                // Check if we have dynamic days data
                const daysData = data.attendance_data || {};
                const totalDays = Object.keys(daysData).length || currentProgramDays;
                
                // Generate day items based on actual data
                for (let i = 1; i <= totalDays; i++) {
                    const dayKey = `day_${i}`;
                    const dayName = data.day_names ? (data.day_names[dayKey] || `Day ${i}`) : `Day ${i}`;
                    const isPresent = daysData[dayKey] || false;
                    if (isPresent) presentDays++;
                    
                    html += `
                        <div class="day-attendance-item">
                            <span class="day-label">${dayName}</span>
                            <span class="day-attendance-badge ${isPresent ? 'present' : 'absent'}">
                                ${isPresent ? 'Present' : 'Absent'}
                            </span>
                        </div>
                    `;
                }
                
                html = `<div class="attendance-summary">${presentDays}/${totalDays} days attended</div>` + html;
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p>Error loading attendance data: ' + (data.message || 'Unknown error') + '</p>';
            }
        })
        .catch(error => {
            console.error('Error loading attendance:', error);
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

        // Update days info
        document.getElementById('currentDaysInfo').textContent = `${currentProgramDays} day(s)`;

        // Store the current registration ID for saving
        currentRegistrationId = registrationId;

        // Generate days checkboxes
        generateAttendanceCheckboxes(registrationId);

        modals.dailyAttendance.style.display = 'block';
    }

    // Generate attendance checkboxes
    function generateAttendanceCheckboxes(registrationId) {
        const container = document.getElementById('daysAttendanceContainer');
        if (!container) return;

        // Show loading state
        container.innerHTML = '<p>Loading attendance data...</p>';

        fetch(`/programs/daily-attendance/${registrationId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Attendance data for modal:', data);
            container.innerHTML = '';
            
            if (data.success) {
                const attendanceData = data.attendance_data || {};
                const dayNames = data.day_names || {};
                
                // Store attendance data globally
                window.attendanceData = attendanceData;
                
                // Generate checkboxes for each day
                for (let i = 1; i <= currentProgramDays; i++) {
                    const dayKey = `day_${i}`;
                    const dayName = dayNames[dayKey] || `Day ${i}`;
                    const isChecked = attendanceData[dayKey] || false;
                    
                    const dayElement = document.createElement('div');
                    dayElement.className = 'day-attendance-item';
                    dayElement.dataset.dayKey = dayKey;
                    dayElement.innerHTML = `
                        <label>
                            <input type="checkbox" name="attendance_day_${i}" value="day_${i}" data-day="${i}" ${isChecked ? 'checked' : ''}>
                            <span class="day-label">${dayName}</span>
                        </label>
                        @if(!$program->event_end_date || $program->event_end_date == $program->event_date)
                        <button class="remove-day-btn" data-day="${i}" ${i <= 1 ? 'style="display:none;"' : ''}>
                            <i class="fas fa-times"></i> Remove
                        </button>
                        @endif
                    `;
                    container.appendChild(dayElement);
                }

                // Add event listeners to remove buttons
                document.querySelectorAll('.remove-day-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const dayNumber = parseInt(this.dataset.day);
                        removeDay(dayNumber);
                    });
                });
            } else {
                container.innerHTML = '<p>Error loading attendance data: ' + (data.message || 'Unknown error') + '</p>';
            }
        })
        .catch(error => {
            console.error('Error loading attendance:', error);
            container.innerHTML = '<p>Error loading attendance data</p>';
        });
    }

    // Add day functionality
    const addDayBtn = document.getElementById('addDayBtn');
    const newDayInput = document.getElementById('newDayInput');
    const newDayNameInput = document.getElementById('newDayName');
    const confirmAddDay = document.getElementById('confirmAddDay');
    const cancelAddDay = document.getElementById('cancelAddDay');

    if (addDayBtn) {
        addDayBtn.addEventListener('click', function() {
            newDayInput.style.display = 'flex';
            newDayNameInput.focus();
        });
    }

    if (cancelAddDay) {
        cancelAddDay.addEventListener('click', function() {
            newDayInput.style.display = 'none';
            newDayNameInput.value = '';
        });
    }

    if (confirmAddDay) {
        confirmAddDay.addEventListener('click', function() {
            const dayName = newDayNameInput.value.trim();
            if (!dayName) {
                alert('Please enter a day name');
                return;
            }

            // Add new day
            addNewDay(dayName);
            
            // Reset input
            newDayInput.style.display = 'none';
            newDayNameInput.value = '';
        });
    }

    // Add new day function
    function addNewDay(dayName) {
        currentProgramDays++;
        
        // Update UI
        document.getElementById('totalDaysCount').textContent = currentProgramDays;
        document.getElementById('currentDaysInfo').textContent = `${currentProgramDays} day(s)`;

        // Add checkbox for new day
        const container = document.getElementById('daysAttendanceContainer');
        const dayKey = `day_${currentProgramDays}`;
        
        const dayElement = document.createElement('div');
        dayElement.className = 'day-attendance-item';
        dayElement.dataset.dayKey = dayKey;
        dayElement.innerHTML = `
            <label>
                <input type="checkbox" name="attendance_day_${currentProgramDays}" value="day_${currentProgramDays}" data-day="${currentProgramDays}">
                <span class="day-label">${dayName}</span>
            </label>
            <button class="remove-day-btn" data-day="${currentProgramDays}">
                <i class="fas fa-times"></i> Remove
            </button>
        `;
        container.appendChild(dayElement);

        // Add event listener to remove button
        dayElement.querySelector('.remove-day-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const dayNumber = parseInt(this.dataset.day);
            removeDay(dayNumber);
        });

        // Store day name on server
        saveDayName(currentProgramDays, dayName);
    }

    // Remove day function
    function removeDay(dayNumber) {
        if (dayNumber <= 1) {
            alert('Cannot remove Day 1');
            return;
        }

        if (!confirm(`Are you sure you want to remove Day ${dayNumber}? This will remove attendance data for this day for all attendees.`)) {
            return;
        }

        // Remove day from server
        fetch('{{ route("programs.remove-day") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                program_id: {{ $program->id }},
                day_number: dayNumber,
                _token: '{{ csrf_token() }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update current program days
                currentProgramDays--;
                
                // Update UI
                document.getElementById('totalDaysCount').textContent = currentProgramDays;
                document.getElementById('currentDaysInfo').textContent = `${currentProgramDays} day(s)`;
                
                // Regenerate attendance checkboxes
                generateAttendanceCheckboxes(currentRegistrationId);
                
                // Update all rows in the table
                updateAllRowsAfterDayRemoval(dayNumber);
            } else {
                alert('Error removing day: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error removing day:', error);
            alert('Error removing day');
        });
    }

    // Save day name to server
    function saveDayName(dayNumber, dayName) {
        fetch('{{ route("programs.save-day-name") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                program_id: {{ $program->id }},
                day_number: dayNumber,
                day_name: dayName,
                _token: '{{ csrf_token() }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error saving day name:', data.message);
            }
        })
        .catch(error => {
            console.error('Error saving day name:', error);
        });
    }

    // Update all rows in table after day removal
    function updateAllRowsAfterDayRemoval(removedDay) {
        const rows = document.querySelectorAll('#registrationsTable tbody tr');
        rows.forEach(row => {
            if (!row.classList.contains('no-data')) {
                const registrationId = row.dataset.registrationId;
                // Update the daily attendance summary
                updateAttendanceSummary(registrationId);
            }
        });
    }

    // Update attendance summary for a row
    function updateAttendanceSummary(registrationId) {
        // This would typically fetch updated data from server
        // For now, we'll just reload the data
        setTimeout(() => {
            // Trigger a refresh of the attendance display
            const dailyAttendanceCell = document.getElementById('dailyAttendance_' + registrationId);
            if (dailyAttendanceCell) {
                dailyAttendanceCell.innerHTML = `<span class="attendance-summary-small">Loading...</span>`;
            }
        }, 500);
    }

    // Save daily attendance
    const saveAttendanceBtn = document.getElementById('saveDailyAttendance');
    if (saveAttendanceBtn) {
        saveAttendanceBtn.addEventListener('click', function() {
            saveDailyAttendance(currentRegistrationId);
        });
    }

    // Save daily attendance to server
    function saveDailyAttendance(registrationId) {
        if (!registrationId) {
            alert('Error: No registration ID found');
            return;
        }

        const checkboxes = document.querySelectorAll('#daysAttendanceContainer input[type="checkbox"]');
        const attendanceData = {};
        let presentCount = 0;

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

        // Save to server
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
                total_days: currentProgramDays,
                _token: '{{ csrf_token() }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                updateAttendanceDisplay(registrationId, presentCount, currentProgramDays);
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