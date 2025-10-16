<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - List of Attendees</title>
  <link rel="stylesheet" href="{{ asset('css/list-of-attendees.css') }}">
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

      <a href="{{ route('sk-eventpage') }}">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <div class="evaluation-item nav-item">
        <a href="{{ route('sk-evaluation-feedback') }}" class="evaluation-link nav-link">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="#">Feedbacks</a>
          <a href="#">Polls</a>
          <a href="#">Suggestion Box</a>
        </div>
      </div>

      <a href="#">
        <i data-lucide="file-chart-column"></i>
        <span class="label">Reports</span>
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
          <img src="{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ auth()->user()->given_name ?? '' }} {{ auth()->user()->middle_name ?? '' }} {{ auth()->user()->last_name ?? '' }} {{ auth()->user()->suffix ?? '' }}</h4>
                <div class="profile-badge">
                  <span class="badge">{{ auth()->user()->role === 'sk' ? 'SK Member' : 'KK Member' }}</span>
                  <span class="badge">{{ auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->age : 'N/A' }} yrs old</span>
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
      <div class="attendees-container">
        <div class="attendees-card">

          <!-- Header -->
          <div class="attendees-header">
            <a href="{{ route('youth-participation') }}" class="back-btn">
              <i class="fas fa-arrow-left"></i>
            </a>
            <h2>List Of Attendees</h2>
            @if(isset($event) && $event)
              <div class="event-info">
                <h3>{{ $event->title }}</h3>
                <p class="event-details">
                  <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }} 
                  | <i class="fas fa-clock"></i> {{ $event->formatted_time }}
                  | <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                </p>
              </div>
            @endif
          </div>

          <!-- Search + Filter + Export -->
          <div class="attendees-controls">
            <div class="search-box">
              <i class="fas fa-search"></i>
              <input type="text" id="searchInput" placeholder="Search attendees...">
            </div>
            
            <div class="filter-export-group">
              <button class="filter-btn" id="filterBtn">
                <i class="fas fa-filter"></i> Filter
              </button>
              
              <div class="export-dropdown">
                <button class="export-btn">
                  <i class="fas fa-download"></i> Export
                </button>
                <div class="export-options">
                  <a href="#" id="exportPDF">PDF</a>
                  <a href="#" id="exportExcel">Excel</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="attendees-table-wrapper">
            <table class="attendees-table" id="attendeesTable">
              <thead>
                <tr>
                  <th>KK Number</th>
                  <th>Name</th>
                  <th>Age</th>
                  <th>Purok</th>
                  <th>Youth Age Group</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="attendeesTableBody">
                @if(isset($attendances) && $attendances->count() > 0)
                  @foreach($attendances as $attendance)
                    @php
                      $user = $attendance->user;
                      if (!$user) continue;
                      
                      // Build full name
                      $fullnameParts = array_filter([
                          $user->given_name ?? '',
                          $user->middle_name ?? '',
                          $user->last_name ?? '',
                          $user->suffix ?? ''
                      ]);
                      $fullname = implode(' ', $fullnameParts);
                      
                      // Calculate age
                      $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : '-';
                      
                      // Determine youth age group
                      $youthAgeGroup = 'Unknown';
                      if ($age !== '-') {
                          if ($age >= 15 && $age <= 30) {
                              $youthAgeGroup = 'Core Youth';
                          } elseif ($age < 15) {
                              $youthAgeGroup = 'Early Youth';
                          } else {
                              $youthAgeGroup = 'Senior Youth';
                          }
                      }
                      
                      // Determine status
                      $status = 'Active Youth'; // You can customize this based on your logic
                    @endphp
                    <tr class="attendee-row">
                      <td>{{ $user->account_number ?? 'N/A' }}</td>
                      <td>{{ $fullname ?: 'Unknown User' }}</td>
                      <td>{{ $age }}</td>
                      <td>{{ $user->purok_zone ?? 'N/A' }}</td>
                      <td>{{ $youthAgeGroup }}</td>
                      <td>
                        <span class="status-badge {{ strtolower(str_replace(' ', '-', $status)) }}">
                          {{ $status }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="6" class="no-data">
                      <i class="fas fa-users-slash"></i>
                      <p>No attendees found for this event.</p>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>

          <!-- Attendance Summary -->
          @if(isset($attendances) && $attendances->count() > 0)
            <div class="attendance-summary">
              <div class="summary-item">
                <span class="summary-label">Total Attendees:</span>
                <span class="summary-value">{{ $attendances->count() }}</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Core Youth (15-30):</span>
                <span class="summary-value" id="coreYouthCount">0</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Active Members:</span>
                <span class="summary-value" id="activeMembersCount">0</span>
              </div>
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Initialize icons
      if (typeof lucide !== "undefined" && lucide.createIcons) lucide.createIcons();

      // Global variables - FIXED: Properly handle the PHP variable
      let currentEventId = <?php echo isset($event) && $event ? $event->id : 'null'; ?>;
      let allAttendees = [];

      // === UI elements ===
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');
      const profileWrapper = document.querySelector('.profile-wrapper');
      const profileToggle = document.getElementById('profileToggle');
      const notifWrapper = document.querySelector(".notification-wrapper");

      // Initialize attendee data
      function initializeAttendeeData() {
        const rows = document.querySelectorAll('.attendee-row');
        allAttendees = Array.from(rows).map(row => {
          return {
            element: row,
            kkNumber: row.cells[0].textContent.trim(),
            name: row.cells[1].textContent.trim(),
            age: parseInt(row.cells[2].textContent) || 0,
            purok: row.cells[3].textContent.trim(),
            ageGroup: row.cells[4].textContent.trim(),
            status: row.cells[5].textContent.trim()
          };
        });
        
        updateSummaryCounts();
      }

      // Update summary counts
      function updateSummaryCounts() {
        const coreYouthCount = allAttendees.filter(attendee => attendee.ageGroup === 'Core Youth').length;
        const activeMembersCount = allAttendees.filter(attendee => attendee.status === 'Active Youth').length;
        
        const coreYouthElement = document.getElementById('coreYouthCount');
        const activeMembersElement = document.getElementById('activeMembersCount');
        
        if (coreYouthElement) {
          coreYouthElement.textContent = coreYouthCount;
        }
        if (activeMembersElement) {
          activeMembersElement.textContent = activeMembersCount;
        }
      }

      // Search functionality
      const searchInput = document.getElementById('searchInput');
      searchInput?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.attendee-row');
        
        let visibleCount = 0;
        
        rows.forEach(row => {
          const rowText = row.textContent.toLowerCase();
          if (rowText.includes(query)) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        // Show no results message if needed
        const noDataRow = document.querySelector('.no-data');
        const tbody = document.getElementById('attendeesTableBody');
        
        if (visibleCount === 0 && query !== '') {
          if (!noDataRow && tbody) {
            tbody.innerHTML = `
              <tr class="no-data">
                <td colspan="6">
                  <i class="fas fa-search"></i>
                  <p>No attendees match your search.</p>
                </td>
              </tr>
            `;
          }
        } else if (visibleCount > 0 && tbody) {
          // Remove any existing no-data rows if we have results
          const existingNoData = tbody.querySelector('.no-data');
          if (existingNoData) {
            existingNoData.remove();
          }
        }
      });

      // Filter functionality
      const filterBtn = document.getElementById('filterBtn');
      filterBtn?.addEventListener('click', function() {
        const statusFilter = prompt("Filter by Status (ex: Active Youth, type 'all' to show all):", "all");
        if (statusFilter === null) return;

        const rows = document.querySelectorAll('.attendee-row');
        let visibleCount = 0;

        rows.forEach(row => {
          const statusCell = row.cells[5]?.textContent.trim();
          
          if (statusFilter.toLowerCase() === 'all' || (statusCell && statusCell.toLowerCase().includes(statusFilter.toLowerCase()))) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        // Update UI based on filter results
        if (visibleCount === 0 && statusFilter.toLowerCase() !== 'all') {
          alert('No attendees match the selected filter.');
        }
      });

      // Export functionality
      const exportPDF = document.getElementById('exportPDF');
      const exportExcel = document.getElementById('exportExcel');

      exportPDF?.addEventListener('click', function(e) {
        e.preventDefault();
        exportToPDF();
      });

      exportExcel?.addEventListener('click', function(e) {
        e.preventDefault();
        exportToExcel();
      });

      function exportToPDF() {
        // Check if jsPDF is available
        if (typeof window.jspdf === 'undefined') {
          alert('PDF export library not loaded. Please try again.');
          return;
        }

        try {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF();
          
          const eventTitle = document.querySelector('.event-info h3')?.textContent || 'Event Attendees';
          const eventDate = document.querySelector('.event-details')?.textContent || '';
          
          // Add title
          doc.setFontSize(16);
          doc.text(eventTitle, 14, 15);
          doc.setFontSize(10);
          doc.text(eventDate, 14, 22);
          
          // Add table
          doc.autoTable({
            html: '#attendeesTable',
            startY: 30,
            styles: { fontSize: 8 },
            headStyles: { fillColor: [60, 135, 196] }
          });
          
          doc.save('attendees-list.pdf');
        } catch (error) {
          console.error('PDF export error:', error);
          alert('Error generating PDF. Please try again.');
        }
      }

      function exportToExcel() {
        try {
          const table = document.getElementById('attendeesTable');
          if (!table) {
            alert('No data to export.');
            return;
          }
          
          const ws = XLSX.utils.table_to_sheet(table);
          const wb = XLSX.utils.book_new();
          XLSX.utils.book_append_sheet(wb, ws, "Attendees");
          XLSX.writeFile(wb, "attendees-list.xlsx");
        } catch (error) {
          console.error('Excel export error:', error);
          alert('Error generating Excel file. Please try again.');
        }
      }

      // Sidebar toggle
      if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('open');
        });
      }

      // Evaluation submenu toggle
      const evaluationItem = document.querySelector('.evaluation-item');
      const evaluationLink = document.querySelector('.evaluation-link');
      evaluationLink?.addEventListener('click', (e) => {
        e.preventDefault();
        evaluationItem?.classList.toggle('open');
      });

      // Time auto-update
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

      // Notifications / profile dropdowns
      if (notifWrapper) {
        const bell = notifWrapper.querySelector(".fa-bell");
        bell?.addEventListener("click", (e) => {
          e.stopPropagation();
          notifWrapper.classList.toggle("active");
          profileWrapper?.classList.remove("active");
        });
      }

      if (profileWrapper && profileToggle) {
        profileToggle.addEventListener("click", (e) => {
          e.stopPropagation();
          profileWrapper.classList.toggle("active");
          notifWrapper?.classList.remove("active");
        });
      }

      // Export dropdown
      const exportBtn = document.querySelector('.export-btn');
      const exportOptions = document.querySelector('.export-options');
      if (exportBtn && exportOptions) {
        exportBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          exportOptions.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
          if (!exportBtn.contains(e.target) && !exportOptions.contains(e.target)) {
            exportOptions.classList.remove('show');
          }
        });
      }

      // Close dropdowns when clicking outside
      document.addEventListener("click", (e) => {
        if (sidebar && !sidebar.contains(e.target) && menuToggle && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
        }
        if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
        if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
      });

      // Logout confirmation
      window.confirmLogout = function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      };

      // Initialize attendee data when page loads
      initializeAttendeeData();
    });
  </script>
</body>
</html>