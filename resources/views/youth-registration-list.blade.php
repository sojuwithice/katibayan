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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
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

    <div class="registration-list-container">
      <!-- Header -->
      <div class="header-section">
        <h2>{{ $program->title }} - Registration List</h2>
        <div class="program-info">
          <span class="program-date">
            <i class="fas fa-calendar"></i>
            {{ \Carbon\Carbon::parse($program->event_date)->format('M d, Y') }} at 
            {{ \Carbon\Carbon::parse($program->event_time)->format('g:i A') }}
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
              <th>Attendance</th>
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
                <td>{{ $registration['age'] }}</td>
                <td>{{ $registration['contact_no'] }}</td>
                <td>{{ $registration['barangay'] }}</td>
                <td>{{ $registration['registered_at'] }}</td>
                <td>
                  <span class="attendance-status attendance-{{ $registration['attended'] ? 'present' : 'absent' }}">
                    {{ $registration['attended'] ? 'Present' : 'Absent' }}
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    @if(!$registration['attended'])
                      <button class="mark-present-btn" data-registration-id="{{ $registration['id'] }}">
                        <i class="fas fa-user-check"></i> Mark Present
                      </button>
                    @else
                      <button class="mark-absent-btn" data-registration-id="{{ $registration['id'] }}">
                        <i class="fas fa-user-times"></i> Mark Absent
                      </button>
                    @endif
                    <button class="view-details-btn" data-registration-id="{{ $registration['id'] }}">
                      <i class="fas fa-eye"></i> Details
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="no-data">
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
  <div id="registrationDetailsModal" class="modal" style="display: none;">
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
              <label>Attendance:</label>
              <span id="modalAttendanceStatus" class="attendance-status"></span>
            </div>
            <div class="detail-item">
              <label>Registered At:</label>
              <span id="modalRegisteredAt"></span>
            </div>
          </div>
          
          <div class="detail-section" id="customFieldsSection" style="display: none;">
            <h4>Additional Information</h4>
            <div id="modalCustomFields"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="close-btn">Close</button>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div id="confirmationModal" class="modal" style="display: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirm Attendance</h2>
      </div>
      <div class="modal-body">
        <p id="confirmationMessage">Are you sure you want to mark this participant as present?</p>
      </div>
      <div class="modal-footer">
        <button class="cancel-btn" id="cancelConfirm">Cancel</button>
        <button class="confirm-btn" id="confirmAction">Confirm</button>
      </div>
    </div>
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

  // === Search Functionality ===
  const searchInput = document.getElementById('searchInput');
  const tableRows = document.querySelectorAll('#registrationsTable tbody tr');

  searchInput?.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    tableRows.forEach(row => {
      if (row.classList.contains('no-data')) return;
      
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(query) ? '' : 'none';
    });
  });

  // === Filter Functionality ===
  const filterBtn = document.getElementById('filterBtn');
  filterBtn?.addEventListener('click', () => {
    const attendance = prompt("Filter by Attendance (present, absent, all):");
    if (!attendance || attendance.toLowerCase() === 'all') {
      tableRows.forEach(row => row.style.display = '');
      return;
    }
    
    tableRows.forEach(row => {
      if (row.classList.contains('no-data')) return;
      
      const attendanceCell = row.cells[5]?.textContent.toLowerCase();
      row.style.display = attendanceCell && attendanceCell.includes(attendance.toLowerCase()) ? '' : 'none';
    });
  });

  // === Download Functionality ===
  const downloadBtn = document.getElementById('downloadBtn');
  downloadBtn?.addEventListener('click', () => {
    const rows = [];
    const headers = [];
    
    // Get headers
    document.querySelectorAll('#registrationsTable thead th').forEach(header => {
      headers.push(header.textContent);
    });
    rows.push(headers.join(','));
    
    // Get data rows
    document.querySelectorAll('#registrationsTable tbody tr:not(.no-data)').forEach(row => {
      const rowData = [];
      row.querySelectorAll('td').forEach((cell, index) => {
        // Skip actions column (last column)
        if (index < 7) {
          let cellText = cell.textContent.trim();
          // Remove attendance status HTML if present
          const attendanceStatus = cell.querySelector('.attendance-status');
          if (attendanceStatus) {
            cellText = attendanceStatus.textContent.trim();
          }
          rowData.push(`"${cellText.replace(/"/g, '""')}"`);
        }
      });
      rows.push(rowData.join(','));
    });
    
    const csvContent = rows.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', '{{ $program->title }} - Registrations.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });

  // === Attendance Management ===
  const confirmationModal = document.getElementById('confirmationModal');
  const confirmationMessage = document.getElementById('confirmationMessage');
  const cancelConfirm = document.getElementById('cancelConfirm');
  const confirmAction = document.getElementById('confirmAction');
  
  let currentRegistrationId = null;
  let currentAction = null;

  // Mark Present functionality
  document.addEventListener('click', (e) => {
    if (e.target.closest('.mark-present-btn')) {
      const button = e.target.closest('.mark-present-btn');
      const registrationId = button.getAttribute('data-registration-id');
      const userName = button.closest('tr').querySelector('.user-info strong').textContent;
      
      currentRegistrationId = registrationId;
      currentAction = 'present';
      confirmationMessage.textContent = `Are you sure you want to mark ${userName} as present for this program?`;
      confirmationModal.style.display = 'block';
    }
    
    if (e.target.closest('.mark-absent-btn')) {
      const button = e.target.closest('.mark-absent-btn');
      const registrationId = button.getAttribute('data-registration-id');
      const userName = button.closest('tr').querySelector('.user-info strong').textContent;
      
      currentRegistrationId = registrationId;
      currentAction = 'absent';
      confirmationMessage.textContent = `Are you sure you want to mark ${userName} as absent for this program?`;
      confirmationModal.style.display = 'block';
    }
  });

  // Confirm attendance action
  confirmAction.addEventListener('click', () => {
    if (currentRegistrationId && currentAction) {
      updateAttendance(currentRegistrationId, currentAction);
      confirmationModal.style.display = 'none';
    }
  });

  // Cancel confirmation
  cancelConfirm.addEventListener('click', () => {
    confirmationModal.style.display = 'none';
    currentRegistrationId = null;
    currentAction = null;
  });

  // Close confirmation modal
  confirmationModal.addEventListener('click', (e) => {
    if (e.target === confirmationModal) {
      confirmationModal.style.display = 'none';
      currentRegistrationId = null;
      currentAction = null;
    }
  });

  // Function to update attendance
  function updateAttendance(registrationId, action) {
    // Send AJAX request to update attendance
    fetch(`/program-registration/${registrationId}/attendance`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        attended: action === 'present'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update the UI
        const row = document.querySelector(`[data-registration-id="${registrationId}"]`);
        if (row) {
          const attendanceCell = row.cells[5];
          const actionsCell = row.cells[6];
          
          if (action === 'present') {
            // Update attendance status
            attendanceCell.innerHTML = '<span class="attendance-status attendance-present">Present</span>';
            
            // Update action buttons
            actionsCell.innerHTML = `
              <div class="action-buttons">
                <button class="mark-absent-btn" data-registration-id="${registrationId}">
                  <i class="fas fa-user-times"></i> Mark Absent
                </button>
                <button class="view-details-btn" data-registration-id="${registrationId}">
                  <i class="fas fa-eye"></i> Details
                </button>
              </div>
            `;
          } else {
            // Update attendance status
            attendanceCell.innerHTML = '<span class="attendance-status attendance-absent">Absent</span>';
            
            // Update action buttons
            actionsCell.innerHTML = `
              <div class="action-buttons">
                <button class="mark-present-btn" data-registration-id="${registrationId}">
                  <i class="fas fa-user-check"></i> Mark Present
                </button>
                <button class="view-details-btn" data-registration-id="${registrationId}">
                  <i class="fas fa-eye"></i> Details
                </button>
              </div>
            `;
          }
          
          // Update attendance count
          updateAttendanceCount();
        }
      } else {
        alert('Error updating attendance: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error updating attendance. Please try again.');
    });
  }

  // Function to update attendance count
  function updateAttendanceCount() {
    const presentCount = document.querySelectorAll('.attendance-status.attendance-present').length;
    const totalCount = document.querySelectorAll('#registrationsTable tbody tr:not(.no-data)').length;
    
    document.getElementById('attendedCount').textContent = presentCount;
    document.getElementById('totalCount').textContent = totalCount;
  }

  // === Registration Details Modal ===
  const registrationDetailsModal = document.getElementById('registrationDetailsModal');
  const closeModal = document.querySelectorAll('.close');
  const closeBtn = document.querySelectorAll('.close-btn');

  // Function to show registration details
  function showRegistrationDetails(registrationId) {
    // Find the registration data from the table
    const registrationRow = document.querySelector(`[data-registration-id="${registrationId}"]`);
    if (!registrationRow) return;

    // Get basic data from table
    const referenceId = registrationRow.querySelector('.reference-id')?.textContent || 'N/A';
    const userName = registrationRow.cells[1]?.querySelector('strong')?.textContent || 'N/A';
    const userEmail = registrationRow.cells[1]?.querySelector('small')?.textContent || 'N/A';
    const userAge = registrationRow.cells[2]?.textContent || 'N/A';
    const userContact = registrationRow.cells[3]?.textContent || 'N/A';
    const userBarangay = registrationRow.cells[4]?.textContent || 'N/A';
    const attendanceStatus = registrationRow.cells[5]?.querySelector('.attendance-status')?.textContent || 'N/A';
    const registeredAt = registrationRow.cells[5]?.textContent || 'N/A';

    // Populate modal with basic data
    document.getElementById('modalReferenceId').textContent = referenceId;
    document.getElementById('modalUserName').textContent = userName;
    document.getElementById('modalUserEmail').textContent = userEmail;
    document.getElementById('modalUserAge').textContent = userAge;
    document.getElementById('modalUserContact').textContent = userContact;
    document.getElementById('modalUserBarangay').textContent = userBarangay;
    document.getElementById('modalRegisteredAt').textContent = registeredAt;
    
    // Set attendance status with proper class
    const attendanceElement = document.getElementById('modalAttendanceStatus');
    attendanceElement.textContent = attendanceStatus;
    attendanceElement.className = `attendance-status attendance-${attendanceStatus.toLowerCase()}`;

    // For now, hide custom fields section since we don't have that data in the table
    document.getElementById('customFieldsSection').style.display = 'none';

    // Show modal
    registrationDetailsModal.style.display = 'block';
  }

  // Add click event to view details buttons
  document.addEventListener('click', (e) => {
    if (e.target.closest('.view-details-btn')) {
      e.preventDefault();
      const registrationId = e.target.closest('.view-details-btn').getAttribute('data-registration-id');
      showRegistrationDetails(registrationId);
    }
  });

  // Close modal functions
  closeModal.forEach(close => {
    close.addEventListener('click', () => {
      registrationDetailsModal.style.display = 'none';
    });
  });

  closeBtn.forEach(btn => {
    btn.addEventListener('click', () => {
      registrationDetailsModal.style.display = 'none';
    });
  });

  registrationDetailsModal.addEventListener('click', (e) => {
    if (e.target === registrationDetailsModal) {
      registrationDetailsModal.style.display = 'none';
    }
  });

  // Logout confirmation
  function confirmLogout(event) {
    event.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
      document.getElementById('logout-form').submit();
    }
  }
});
</script>
</body>
</html>