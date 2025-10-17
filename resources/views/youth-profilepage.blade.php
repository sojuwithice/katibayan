<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Youth Profile</title>
  <link rel="stylesheet" href="{{ asset('css/youthprofile.css') }}">
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

    <a href="{{ route('youth-profilepage') }}" class="active">
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
                <h4>{{ $user->given_name }} {{ $user->middle_name ?? '' }} {{ $user->last_name }} {{ $user->suffix ?? '' }}</h4>
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
        <h2>Youth Profile</h2>
      </div>

      <!-- Profile Buttons -->
      <div class="profile-buttons">
        <!-- Card 1 -->
        <a href="{{ route('certificate-request') }}" class="btn">
          <div class="content">
            <h3>Request for Certificates</h3>
            <div class="bottom-row">
              <p>Youth are requesting for certificate <span class="count">{{ $certificateRequestsCount ?? 0 }}</span></p>
              <span class="arrow">
                <i class="fa-solid fa-chevron-right"></i>
              </span>
            </div>
          </div>
        </a>

        <!-- Card 2 -->
        <a href="{{ route('youth-participation') }}" class="btn">
          <div class="content">
            <h3>Youth Participation Record</h3>
            <div class="bottom-row">
              <p>View details</p>
              <span class="arrow">
                <i class="fa-solid fa-chevron-right"></i>
              </span>
            </div>
          </div>
        </a>

        <!-- Card 3 -->
        <a href="{{ route('youth-assistance') }}" class="btn">
          <div class="content">
            <h3>Youth Assistance</h3>
            <div class="bottom-row">
              <p>View details</p>
              <span class="arrow">
                <i class="fa-solid fa-chevron-right"></i>
              </span>
            </div>
          </div>
        </a>
        </button>
      </div>

      <!-- Instructions -->
      <div class="instructions-card">
        <h3>Katipunan ng Kabataan Youth Profile</h3>
        <p>Instructions:</p><br>
        <p>
          <em>
            <strong>Dear SK Officials,</strong><br><br>
            Congratulations for being elected as primary movers in youth development in your locality.
            The fundamental purpose of youth profiling is to be able to come up with a complete list of all youth ages 15-30 years old in your respective barangay. The said data gathering also aim to come up with relevant interventions through programs and projects based on the profiles of the youth in the area and identified issues and recommendations.<br><br>
            Please ensure that all youth in the barangay including yourselves are part of the list.
          </em>
          <br><br>
          <strong>Mabuhay ang Kabataang Pilipino!</strong>
        </p>
      </div>

      <div class="youth-profile-card">
        <!-- 1. Actions -->
        <div class="table-actions">
          <!-- Search -->
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search...">
          </div>

          <div class="actions">
            <!-- Sex Filter -->
            <div class="dropdown">
              <button class="dropdown-btn"><i class="fas fa-filter"></i> Sex</button>
              <div class="dropdown-content">
                <a href="#" data-filter-column="10" data-filter="">All</a>
                <a href="#" data-filter-column="10" data-filter="male">Male</a>
                <a href="#" data-filter-column="10" data-filter="female">Female</a>
              </div>
            </div>

            <!-- Civil Status Filter -->
            <div class="dropdown">
              <button class="dropdown-btn">Civil Status</button>
              <div class="dropdown-content">
                <a href="#" data-filter-column="11" data-filter="">All</a>
                <a href="#" data-filter-column="11" data-filter="single">Single</a>
                <a href="#" data-filter-column="11" data-filter="married">Married</a>
                <a href="#" data-filter-column="11" data-filter="widowed">Widowed</a>
                <a href="#" data-filter-column="11" data-filter="separated">Separated</a>
              </div>
            </div>

            <!-- Youth Classification Filter -->
            <div class="dropdown">
              <button class="dropdown-btn">Youth Classification</button>
              <div class="dropdown-content">
                <a href="#" data-filter-column="12" data-filter="">All</a>
                <a href="#" data-filter-column="12" data-filter="in-school youth">In-school Youth</a>
                <a href="#" data-filter-column="12" data-filter="out-of-school youth">Out-of-school Youth</a>
                <a href="#" data-filter-column="12" data-filter="core youth">Core Youth</a>
              </div>
            </div>

            <!-- Registered Voter Filter -->
            <div class="dropdown">
              <button class="dropdown-btn">Registered Voter</button>
              <div class="dropdown-content">
                <a href="#" data-filter-column="18" data-filter="">All</a>
                <a href="#" data-filter-column="18" data-filter="yes">Yes</a>
                <a href="#" data-filter-column="18" data-filter="no">No</a>
              </div>
            </div>

            <!-- Download Button -->
            <div class="dropdown">
              <button class="dropdown-btn"><i class="fas fa-download"></i> Download</button>
              <div class="dropdown-content">
                <a href="#" data-download="excel">Excel</a>
                <a href="#" data-download="pdf">PDF</a>
              </div>
            </div>

            <!-- Print Button -->
            <button id="printBtn" class="print-btn"><i class="fas fa-print"></i> Print</button>
          </div>
        </div>

      <!-- Table Wrapper -->
<div class="table-wrapper">
  <table id="youthTable" class="youth-table">
    <thead>
      <tr>
        <th>Region</th>
        <th>Province</th>
        <th>City/Municipality</th>
        <th>Barangay</th>
        <th>Surname</th>
        <th>Given name</th>
        <th>Middle name</th>
        <th>Suffix</th>
        <th>Age</th>
        <th>Date of Birth</th>
        <th>Sex assigned at birth</th>
        <th>Civil status</th>
        <th>Youth classification</th>
        <th>Youth age group</th>
        <th>Email address</th>
        <th>Contact number</th>
        <th>Highest educational attainment</th>
        <th>Work status</th>
        <th>Registered voter</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr data-id="{{ $user->id }}">
        <td>{{ $user->region->name ?? 'N/A' }}</td>
        <td>{{ $user->province->name ?? 'N/A' }}</td>
        <td>{{ $user->city->name ?? 'N/A' }}</td>
        <td>{{ $user->barangay->name ?? 'N/A' }}</td>
        <td>{{ $user->last_name }}</td>
        <td>{{ $user->given_name }}</td>
        <td>{{ $user->middle_name ?? '' }}</td>
        <td>{{ $user->suffix ?? '' }}</td>
        <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A' }}</td>
        <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('m/d/Y') : 'N/A' }}</td>
        <td>{{ ucfirst($user->sex) }}</td>
        <td>{{ $user->civil_status }}</td>
        <td>{{ $user->youth_classification }}</td>
        <td>
          @php
            if($user->date_of_birth) {
              $age = \Carbon\Carbon::parse($user->date_of_birth)->age;
              if($age >= 15 && $age <= 30) {
                echo 'Youth (15-30)';
              } else {
                echo 'Outside Youth Age';
              }
            } else {
              echo 'N/A';
            }
          @endphp
        </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->contact_no }}</td>
        <td>{{ $user->education }}</td>
        <td>{{ $user->work_status }}</td>
        <td>{{ $user->sk_voter }}</td>
        <td>
          <span class="role-badge {{ $user->role === 'sk' ? 'sk-badge' : 'kk-badge' }}">
            {{ $user->role === 'sk' ? 'SK Member' : 'KK Member' }}
          </span>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="20" style="text-align: center; padding: 20px;">
          <i class="fas fa-users" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
          <p style="color: #666; font-size: 16px;">No approved members found in your barangay.</p>
          <p style="color: #999; font-size: 14px;">Only approved members from your barangay are displayed here.</p>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
      </div>
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

      // === Calendar ===
      const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
      const daysContainer = document.querySelector(".calendar .days");
      const header = document.querySelector(".calendar header h3");
      let today = new Date();
      let currentView = new Date();

      const holidays = [
        "2025-01-01","2025-04-09","2025-04-17","2025-04-18",
        "2025-05-01","2025-06-06","2025-06-12","2025-08-25",
        "2025-11-30","2025-12-25","2025-12-30"
      ];

      function renderCalendar(baseDate) {
        if (!daysContainer || !header) return;
        daysContainer.innerHTML = "";

        const startOfWeek = new Date(baseDate);
        startOfWeek.setDate(baseDate.getDate() - (baseDate.getDay() === 0 ? 6 : baseDate.getDay() - 1));

        const middleDay = new Date(startOfWeek);
        middleDay.setDate(startOfWeek.getDate() + 3);
        header.textContent = middleDay.toLocaleDateString("en-US", { month: "long", year: "numeric" });

        for (let i = 0; i < 7; i++) {
          const thisDay = new Date(startOfWeek);
          thisDay.setDate(startOfWeek.getDate() + i);

          const dayEl = document.createElement("div");
          dayEl.classList.add("day");

          const weekdayEl = document.createElement("span");
          weekdayEl.classList.add("weekday");
          weekdayEl.textContent = weekdays[i];

          const dateEl = document.createElement("span");
          dateEl.classList.add("date");
          dateEl.textContent = thisDay.getDate();

          const month = (thisDay.getMonth() + 1).toString().padStart(2,'0');
          const day = thisDay.getDate().toString().padStart(2,'0');
          const dateStr = `${thisDay.getFullYear()}-${month}-${day}`;

          if (holidays.includes(dateStr)) dateEl.classList.add('holiday');
          if (
            thisDay.getDate() === today.getDate() &&
            thisDay.getMonth() === today.getMonth() &&
            thisDay.getFullYear() === today.getFullYear()
          ) {
            dayEl.classList.add("active");
          }

          dayEl.appendChild(weekdayEl);
          dayEl.appendChild(dateEl);
          daysContainer.appendChild(dayEl);
        }
      }

      renderCalendar(currentView);

      const prevBtn = document.querySelector(".calendar .prev");
      const nextBtn = document.querySelector(".calendar .next");
      if (prevBtn) prevBtn.addEventListener("click", () => {
        currentView.setDate(currentView.getDate() - 7);
        renderCalendar(currentView);
      });
      if (nextBtn) nextBtn.addEventListener("click", () => {
        currentView.setDate(currentView.getDate() + 7);
        renderCalendar(currentView);
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
          profileItem?.classList.remove('open');
        }
        if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
        if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');

        // Close options dropdown when clicking outside
        document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
      });

      // === Highlight Holidays in Events ===
      document.querySelectorAll('.events li').forEach(eventItem => {
        const dateEl = eventItem.querySelector('.date span');
        const monthEl = eventItem.querySelector('.date strong');
        if (!dateEl || !monthEl) return;

        const monthMap = {
          JAN: "01", FEB: "02", MAR: "03", APR: "04", MAY: "05", JUN: "06",
          JUL: "07", AUG: "08", SEP: "09", OCT: "10", NOV: "11", DEC: "12"
        };
        const monthNum = monthMap[monthEl.textContent.trim().toUpperCase()];
        const day = dateEl.textContent.trim().padStart(2,'0');
        const dateStr = `2025-${monthNum}-${day}`;

        if (holidays.includes(dateStr)) {
          eventItem.querySelector('.date').classList.add('holiday');
        }
      });

      // === SEARCH ===
      document.getElementById("searchInput").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#youthTable tbody tr");
        rows.forEach(row => {
          let text = row.textContent.toLowerCase();
          row.style.display = text.includes(value) ? "" : "none";
        });
      });

      // === Dropdown toggle (Filter + Download) ===
      document.querySelectorAll(".dropdown-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
          e.stopPropagation();
          let menu = this.nextElementSibling;

          // Close other open dropdowns
          document.querySelectorAll(".dropdown-content").forEach(dc => {
            if (dc !== menu) dc.classList.remove("show");
          });

          // Toggle current dropdown
          menu.classList.toggle("show");
        });
      });

      // Close dropdown if clicking outside
      document.addEventListener("click", function () {
        document.querySelectorAll(".dropdown-content").forEach(dc => dc.classList.remove("show"));
      });

      // === Filter ===
      document.querySelectorAll(".dropdown-content a[data-filter]").forEach(item => {
        item.addEventListener("click", function(e) {
          e.preventDefault();
          const filterValue = this.getAttribute("data-filter").toLowerCase();
          const filterColumn = parseInt(this.getAttribute("data-filter-column"));
          const rows = document.querySelectorAll("#youthTable tbody tr");

          rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const cellValue = cells[filterColumn].textContent.toLowerCase();
            
            if (!filterValue || cellValue === filterValue) {
              row.style.display = "";
            } else {
              row.style.display = "none";
            }
          });
        });
      });

      // === Download dropdown actions ===
      document.querySelectorAll(".dropdown-content a[data-download]").forEach(item => {
        item.addEventListener("click", function(e) {
          e.preventDefault();
          let choice = this.getAttribute("data-download");
          if (choice === "excel") {
            downloadExcel();
          } else if (choice === "pdf") {
            downloadPDF();
          }
        });
      });

      // === DOWNLOAD EXCEL ===
      async function downloadExcel() {
        let table = document.getElementById("youthTable");

        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet("Youth Profile");

        // ===== Title Row =====
        let colCount = table.querySelectorAll("thead th").length;
        worksheet.mergeCells("A1:" + String.fromCharCode(64 + colCount) + "1");
        let titleCell = worksheet.getCell("A1");
        titleCell.value = "Youth Profile Report";
        titleCell.font = { size: 16, bold: true, color: { argb: "FF000000" } }; 
        titleCell.alignment = { horizontal: "center", vertical: "middle" };
        titleCell.fill = { type: "pattern", pattern: "none" };
        titleCell.border = {
          top: { style: "thin" },
          left: { style: "thin" },
          bottom: { style: "thin" },
          right: { style: "thin" }
        };
        worksheet.addRow([]); 

        // ===== Headers =====
        let headers = [];
        table.querySelectorAll("thead th").forEach(th => headers.push(th.innerText));
        let headerRow = worksheet.addRow(headers);
        headerRow.eachCell(cell => {
          cell.font = { bold: true, color: { argb: "FF000000" } };
          cell.alignment = { horizontal: "center", vertical: "middle" };
          cell.fill = { type: "pattern", pattern: "none" }; 
          cell.border = {
            top: { style: "thin" },
            left: { style: "thin" },
            bottom: { style: "thin" },
            right: { style: "thin" }
          };
        });

        // ===== Data rows =====
        table.querySelectorAll("tbody tr").forEach(tr => {
          let row = [];
          tr.querySelectorAll("td").forEach(td => row.push(td.innerText));
          let excelRow = worksheet.addRow(row);
          excelRow.eachCell(cell => {
            cell.font = { color: { argb: "FF000000" } }; 
            cell.alignment = { vertical: "top", wrapText: true };
            cell.fill = { type: "pattern", pattern: "none" }; 
            cell.border = {
              top: { style: "thin" },
              left: { style: "thin" },
              bottom: { style: "thin" },
              right: { style: "thin" }
            };
          });
        });

        // ===== Auto column widths =====
        worksheet.columns.forEach(column => {
          let maxLength = 10;
          column.eachCell({ includeEmpty: true }, cell => {
            const columnLength = cell.value ? cell.value.toString().length : 10;
            if (columnLength > maxLength) maxLength = columnLength;
          });
          column.width = maxLength + 2;
        });

        // ===== Save file =====
        const buffer = await workbook.xlsx.writeBuffer();
        saveAs(new Blob([buffer]), "youth_profile.xlsx");
      }

      // === DOWNLOAD PDF (Black & White) ===
      function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF("l", "mm", "legal"); 

        // Title
        doc.setFontSize(14);
        doc.text("Youth Profile Report", 14, 15);

        // Collect headers & rows
        let table = document.getElementById("youthTable");
        let headers = [];
        table.querySelectorAll("thead th").forEach(th => headers.push(th.innerText));

        let rows = [];
        table.querySelectorAll("tbody tr").forEach(tr => {
          let row = [];
          tr.querySelectorAll("td").forEach(td => row.push(td.innerText));
          rows.push(row);
        });

        // AutoTable
        doc.autoTable({
          head: [headers],
          body: rows,
          startY: 25,
          theme: "grid",
          styles: {
            fontSize: 6,           
            cellPadding: 1.5,
            overflow: "linebreak",
            cellWidth: "auto",
            textColor: 0, 
            lineWidth: 0.1, 
            lineColor: 0   
          },
          headStyles: {
            fontStyle: "bold",
            halign: "center",
            fontSize: 7,
            textColor: 0,
            fillColor: false 
          },
          bodyStyles: {
            valign: "top",
            textColor: 0
          },
          tableWidth: "auto",      
          margin: { left: 10, right: 10 }, 
          didDrawPage: function (data) {
            let pageCount = doc.internal.getNumberOfPages();
            doc.setFontSize(8);
            doc.text(
              "Page " + pageCount,
              doc.internal.pageSize.getWidth() - 30,
              doc.internal.pageSize.getHeight() - 10
            );
          }
        });

        doc.save("youth_profile.pdf");
      }

      // === PRINT (same as PDF) ===
      document.getElementById("printBtn").addEventListener("click", function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF("l", "mm", "legal");

        // Title
        doc.setFontSize(14);
        doc.text("Youth Profile Report", 14, 15);

        // Collect headers & rows
        let table = document.getElementById("youthTable");
        let headers = [];
        table.querySelectorAll("thead th").forEach(th => headers.push(th.innerText));

        let rows = [];
        table.querySelectorAll("tbody tr").forEach(tr => {
          let row = [];
          tr.querySelectorAll("td").forEach(td => row.push(td.innerText));
          rows.push(row);
        });

        // AutoTable
        doc.autoTable({
          head: [headers],
          body: rows,
          startY: 25,
          theme: "grid",
          styles: {
            fontSize: 6,           
            cellPadding: 1.5,
            overflow: "linebreak",
            cellWidth: "auto",
            textColor: 0,
            lineWidth: 0.1, 
            lineColor: 0   
          },
          headStyles: {
            fontStyle: "bold",
            halign: "center",
            fontSize: 7,
            textColor: 0,
            fillColor: false
          },
          bodyStyles: {
            valign: "top",
            textColor: 0
          },
          tableWidth: "auto",      
          margin: { left: 10, right: 10 }, 
          didDrawPage: function (data) {
            let pageCount = doc.internal.getNumberOfPages();
            doc.setFontSize(8);
            doc.text(
              "Page " + pageCount,
              doc.internal.pageSize.getWidth() - 30,
              doc.internal.pageSize.getHeight() - 10
            );
          }
        });

        // ==== Force Print Dialog ====
        const blob = doc.output("blob");
        const blobURL = URL.createObjectURL(blob);
        const iframe = document.createElement("iframe");
        iframe.style.display = "none";
        iframe.src = blobURL;
        document.body.appendChild(iframe);

        iframe.onload = function () {
          iframe.contentWindow.focus();
          iframe.contentWindow.print();
        };
      });

      // === CLICK ROW TO VIEW PROFILE ===
      const youthTable = document.getElementById("youthTable");
      if (youthTable) {
        youthTable.querySelectorAll("tbody tr").forEach(row => {
          row.style.cursor = "pointer"; 
          row.addEventListener("click", () => {
            const youthId = row.getAttribute("data-id"); 
            if (!youthId) return; 
            window.location.href = "/view-youth-profile/" + youthId;
          });
        });
      }

      // === Logout Confirmation ===
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