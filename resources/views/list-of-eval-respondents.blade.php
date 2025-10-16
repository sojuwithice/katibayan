<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Evaluation Respondents</title>
  <link rel="stylesheet" href="{{ asset('css/list-of-eval-respondents.css') }}">
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

      <a href="{{ route('sk-eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <div class="evaluation-item nav-item">
        <a href="{{ route('sk-evaluation-feedback') }}" class="evaluation-link nav-link active">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('sk-evaluation-feedback') }}">Feedbacks</a>
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
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ $user->given_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}</h4>
                <div class="profile-badge">
                  <span class="badge">{{ $roleBadge ?? 'SK Member' }}</span>
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

    <div class="respondents-container">
      <div class="respondents-header">
        <div class="header-left">
          <button class="back-btn" id="backBtn"><i class="fas fa-arrow-left"></i></button>
          <div>
            <h2>List of Respondents - {{ $event->title ?? 'Event' }}</h2>
            <p class="event-info">Total Respondents: {{ $evaluations->count() ?? 0 }}</p>
          </div>
        </div>
        <div class="header-actions">
          <button class="export-btn" id="exportBtn">
            <i class="fas fa-download"></i> Export to Excel
          </button>
        </div>
      </div>

      <!-- Filters -->
      <div class="filters-section">
        <div class="filter-group">
          <label for="ratingFilter">Filter by Rating:</label>
          <select id="ratingFilter">
            <option value="all">All Ratings</option>
            <option value="5">5 - Strongly Agree</option>
            <option value="4">4 - Agree</option>
            <option value="3">3 - Neutral</option>
            <option value="2">2 - Disagree</option>
            <option value="1">1 - Strongly Disagree</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="searchFilter">Search:</label>
          <input type="text" id="searchFilter" placeholder="Search by name...">
        </div>
      </div>

      <div class="respondents-table">
        <table id="respondentsTable">
          <thead>
            <tr>
              <th>Profile</th>
              <th>Name</th>
              <th>KK - Number</th>
              <th>Age</th>
              <th>Youth Age Group</th>
              <th>Purok</th>
              <th>Overall Rating</th>
              <th>Evaluation Date</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($evaluations) && $evaluations->count() > 0)
              @foreach($evaluations as $evaluation)
                @php
                  $user = $evaluation->user;
                  $ratings = json_decode($evaluation->ratings, true);
                  $overallRating = $ratings ? round(array_sum($ratings) / count($ratings)) : 0;
                  
                  // Calculate age
                  $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';
                  
                  // Determine youth age group
                  $youthAgeGroup = 'N/A';
                  if ($age !== 'N/A') {
                    if ($age >= 15 && $age <= 30) {
                      $youthAgeGroup = 'Core Youth';
                    } elseif ($age >= 31 && $age <= 35) {
                      $youthAgeGroup = 'Senior Youth';
                    } else {
                      $youthAgeGroup = 'Other';
                    }
                  }
                @endphp
                <tr data-rating="{{ $overallRating }}">
                  <td>
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://i.pravatar.cc/50?img=' . $loop->index }}" 
                         alt="Profile" class="profile-img">
                  </td>
                  <td>{{ $user->given_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }} {{ $user->suffix ?? '' }}</td>
                  <td>
                    <a href="#" class="account-link">{{ $user->account_number ?? 'N/A' }}</a>
                  </td>
                  <td>{{ $age }}</td>
                  <td>{{ $youthAgeGroup }}</td>
                  <td>{{ $user->purok_zone ?? 'N/A' }}</td>
                  <td>
                    <div class="rating-display">
                      <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= $overallRating)
                            <i class="fas fa-star"></i>
                          @else
                            <i class="far fa-star"></i>
                          @endif
                        @endfor
                      </span>
                      <span class="rating-text">{{ $overallRating }}/5</span>
                    </div>
                  </td>
                  <td>{{ $evaluation->submitted_at->format('M d, Y g:i A') }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="8" class="no-data">
                  <i class="fas fa-clipboard-list"></i>
                  <p>No respondents found for this event.</p>
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if(isset($evaluations) && $evaluations->count() > 0)
        <div class="pagination">
          <button class="page-btn" id="prevPage">Previous</button>
          <span class="page-info">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></span>
          <button class="page-btn" id="nextPage">Next</button>
        </div>
      @endif
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === Lucide icons ===
      if (window.lucide) lucide.createIcons();

      // ================= Sidebar =================
      const sidebar = document.querySelector('.sidebar');
      const menuToggle = document.querySelector('.menu-toggle');
      const navItems = document.querySelectorAll('.nav-item > a');

      function closeAllSubmenus() {
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('open'));
      }

      // Toggle sidebar open/close
      menuToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        if (!sidebar.classList.contains('open')) closeAllSubmenus();
      });

      // Toggle submenus
      navItems.forEach(link => {
        link.addEventListener('click', e => {
          if (!sidebar.classList.contains('open')) return;

          const parentItem = link.parentElement;
          const isOpen = parentItem.classList.contains('open');

          closeAllSubmenus();
          if (!isOpen) parentItem.classList.add('open');

          e.preventDefault();
        });
      });

      // Close sidebar if clicked outside
      document.addEventListener('click', e => {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
          closeAllSubmenus();
        }
      });

      // ================= Profile & Notifications =================
      const profileWrapper = document.querySelector('.profile-wrapper');
      const profileToggle = document.getElementById('profileToggle');
      const profileDropdown = document.querySelector('.profile-dropdown');

      const notifWrapper = document.querySelector(".notification-wrapper");
      const notifBell = notifWrapper?.querySelector(".fa-bell");
      const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

      profileToggle?.addEventListener('click', e => {
        e.stopPropagation();
        profileWrapper.classList.toggle('active');
        notifWrapper?.classList.remove('active');
      });

      profileDropdown?.addEventListener('click', e => e.stopPropagation());

      notifBell?.addEventListener('click', e => {
        e.stopPropagation();
        notifWrapper.classList.toggle('active');
        profileWrapper?.classList.remove('active');
      });

      notifDropdown?.addEventListener('click', e => e.stopPropagation());

      document.addEventListener('click', e => {
        if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
        if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
      });

      // ================= Time auto-update =================
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

      // ================= Back Button =================
      document.getElementById("backBtn").addEventListener("click", () => {
        window.history.back();
      });

      // ================= Table Filtering =================
      const ratingFilter = document.getElementById('ratingFilter');
      const searchFilter = document.getElementById('searchFilter');
      const tableRows = document.querySelectorAll('#respondentsTable tbody tr');

      function filterTable() {
        const ratingValue = ratingFilter.value;
        const searchValue = searchFilter.value.toLowerCase();
        
        tableRows.forEach(row => {
          const rating = row.getAttribute('data-rating');
          const name = row.cells[1].textContent.toLowerCase();
          const accountNumber = row.cells[2].textContent.toLowerCase();
          
          const ratingMatch = ratingValue === 'all' || rating === ratingValue;
          const searchMatch = name.includes(searchValue) || accountNumber.includes(searchValue);
          
          row.style.display = ratingMatch && searchMatch ? '' : 'none';
        });
        
        updatePagination();
      }

      ratingFilter.addEventListener('change', filterTable);
      searchFilter.addEventListener('input', filterTable);

      // ================= Export Functionality =================
      document.getElementById('exportBtn').addEventListener('click', exportToExcel);

      function exportToExcel() {
        // Create workbook
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Evaluation Respondents');
        
        // Add headers
        worksheet.addRow([
          'Name', 
          'KK Number', 
          'Age', 
          'Youth Age Group', 
          'Purok', 
          'Overall Rating', 
          'Evaluation Date'
        ]);
        
        // Add data rows
        tableRows.forEach(row => {
          if (row.style.display !== 'none') {
            const cells = row.cells;
            worksheet.addRow([
              cells[1].textContent,
              cells[2].textContent,
              cells[3].textContent,
              cells[4].textContent,
              cells[5].textContent,
              cells[6].querySelector('.rating-text').textContent,
              cells[7].textContent
            ]);
          }
        });
        
        // Style headers
        worksheet.getRow(1).font = { bold: true };
        
        // Generate and download
        workbook.xlsx.writeBuffer().then(buffer => {
          const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          saveAs(blob, `Evaluation_Respondents_${new Date().toISOString().split('T')[0]}.xlsx`);
        });
      }

      // ================= Pagination =================
      const rowsPerPage = 10;
      let currentPage = 1;

      function updatePagination() {
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
        
        document.getElementById('totalPages').textContent = totalPages;
        document.getElementById('currentPage').textContent = currentPage;
        
        // Show/hide appropriate rows
        visibleRows.forEach((row, index) => {
          const startIndex = (currentPage - 1) * rowsPerPage;
          const endIndex = startIndex + rowsPerPage;
          row.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
        });
        
        // Enable/disable pagination buttons
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;
      }

      document.getElementById('prevPage').addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          updatePagination();
        }
      });

      document.getElementById('nextPage').addEventListener('click', () => {
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
        
        if (currentPage < totalPages) {
          currentPage++;
          updatePagination();
        }
      });

      // Initialize pagination
      updatePagination();

      // ================= Logout Confirmation =================
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