<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - QR Page</title>
  <link rel="stylesheet" href="{{ asset('css/qrcode.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- ✅ DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<!-- ✅ jQuery + DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>


  
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

      <a href="{{ route('youth-profilepage') }}" class="active">
        <i data-lucide="users"></i>
        <span class="label">Youth Profile</span>
      </a>

      <a href="{{ route('sk-eventpage') }}" class="events-link">
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
          <img src="https://i.pravatar.cc/80" alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="https://i.pravatar.cc/80" alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>Marijoy S. Novora</h4>
                <div class="profile-badge">
                  <span class="badge">KK- Member</span>
                  <span class="badge">19 yrs old</span>
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
            </ul>
          </div>
        </div>
      </div>
    </header>


    <div class="qr-wrapper">
  <button class="close-btn" onclick="window.location.href='/events'">&times;</button>
  
  <h2>Scan for your attendance</h2>
  <p class="event-title">Title: {{ $title }}</p>
  <canvas id="qr-code" width="360" height="360" style="display:block;margin:24px auto;"></canvas>
  <p>Having trouble scanning the QR code?<br>Here’s the passcode below.</p>
  <div class="passcode-box">{{ $passcode }}</div>
  <p class="note">This will mark your attendance in the program.</p>
</div>

 
<section class="attendance-list">
  <div class="attendance-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
    <h2>List of Attendees</h2>
    <button id="printAttendance" style="padding:6px 12px; background:#3C87C4; color:white; border:none; border-radius:4px; cursor:pointer;">
      <i class="fas fa-print"></i> Print Attendance
    </button>
  </div>

  <div class="table-wrapper">
    <table id="attendanceTable" class="display nowrap attendee-table">
      <thead>
        <tr>
          <th>Status</th>
          <th>Date</th>
          <th>Time</th>
          <th>KK Number ID</th>
          <th>Name of Youth</th>
          <th>Age</th>
          <th>Purok</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody id="attendanceRecordsBody">
        <tr>
          <td colspan="8">Loading...</td>
        </tr>
      </tbody>
    </table>
  </div>
</section>








<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons + sidebar toggle ===
  lucide.createIcons();
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  const profileItem = document.querySelector('.profile-item');
  const profileLink = document.querySelector('.profile-link');
  const eventsItem = document.querySelector('.events-item');
  const eventsLink = document.querySelector('.events-link');

  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');
      if (!sidebar.classList.contains('open')) {
        profileItem?.classList.remove('open'); 
      }
    });
  }

  // === Submenus ===
const evaluationItem = document.querySelector('.evaluation-item');
const evaluationLink = document.querySelector('.evaluation-link');

evaluationLink?.addEventListener('click', (e) => {
  e.preventDefault();

  const isOpen = evaluationItem.classList.contains('open');
  evaluationItem.classList.remove('open');

  if (!isOpen) {
    evaluationItem.classList.add('open');
  }
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

  
    // === Generate QR ===
  const qrCanvas = document.getElementById('qr-code');
  if (!qrCanvas) return;

  // Generate QR with QRious (draw base black)
  const qr = new QRious({
    element: qrCanvas,
    value: "{{ $event->passcode }}",
    size: 360,
    background: '#ffffff',
    foreground: '#000000',
    level: 'H'
  });

  // === Apply Blue→Gold Gradient ===
  function applyGradientToQR(canvas, startColor, endColor) {
    const ctx = canvas.getContext('2d');
    const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imgData.data;

    const startRGB = hexToRgb(startColor);
    const endRGB = hexToRgb(endColor);

    for (let y = 0; y < canvas.height; y++) {
      for (let x = 0; x < canvas.width; x++) {
        const idx = (y * canvas.width + x) * 4;
        const r = data[idx], g = data[idx + 1], b = data[idx + 2];

        // Detect dark pixels
        if (r < 100 && g < 100 && b < 100) {
          const t = (x + y) / (canvas.width + canvas.height);
          data[idx]     = startRGB.r + (endRGB.r - startRGB.r) * t;
          data[idx + 1] = startRGB.g + (endRGB.g - startRGB.g) * t;
          data[idx + 2] = startRGB.b + (endRGB.b - startRGB.b) * t;
        }
      }
    }

    ctx.putImageData(imgData, 0, 0);
  }

  function hexToRgb(hex) {
    const val = parseInt(hex.replace('#', ''), 16);
    return { r: (val >> 16) & 255, g: (val >> 8) & 255, b: val & 255 };
  }

  // Apply diagonal gradient (top-left → bottom-right)
  requestAnimationFrame(() => {
    applyGradientToQR(qrCanvas, '#3C87C4', '#C2B356');
  });

// Load attendance records
  async function loadAttendanceRecords(){
    try{
      const response = await fetch("{{ route('attendance.records') }}?event_id={{ $event->id }}",{
        method:"GET",
        headers: {"Accept":"application/json"}
      });
      const data = await response.json();
      const tbody = document.getElementById('attendanceRecordsBody');

      if(data.success && data.attendances.length > 0){
        tbody.innerHTML = data.attendances.map(att => `
        <tr>
          <td style="color: ${att.status === 'Attended' ? 'green' : 'black'};">
            ${att.status ?? 'Attended'}
          </td>
          <td>${att.date ?? '-'}</td>
          <td>${att.time ?? '-'}</td>
          <td>${att.account_number ?? '-'}</td>
          <td>${att.name ?? '-'}</td>
          <td>${att.age ?? '-'}</td>
          <td>${att.purok ?? '-'}</td>
          <td>${att.role ?? '-'}</td>
        </tr>
      `).join('');


        if(!$.fn.DataTable.isDataTable('#attendanceTable')){
          $('#attendanceTable').DataTable({
            responsive:true, paging:false, searching:false, info:false, ordering:false,
            language:{zeroRecords:"No attendance records found."}
          });
        }

      }else{
        tbody.innerHTML = `<tr><td colspan="8" class="no-records">No attendance records found.</td></tr>`;
      }

    }catch(error){
      console.error(error);
      document.getElementById('attendanceRecordsBody').innerHTML =
        `<tr><td colspan="8" class="error-text">Error loading records.</td></tr>`;
    }
  }

  loadAttendanceRecords();
  setInterval(loadAttendanceRecords, 15000);


  document.getElementById('printAttendance').addEventListener('click', () => {
  const eventTitle = "{{ $event->title ?? 'Untitled Event' }}";
  const eventLocation = "{{ $event->location ?? 'No location specified' }}";

  const dateCells = Array.from(document.querySelectorAll('#attendanceTable tbody tr td:nth-child(2)')); // 2nd column = Date
  const dates = dateCells.map(td => td.textContent.trim()).filter(d => d && d !== '—' && d !== 'N/A');

  let eventDate = 'Date not set';
  if (dates.length > 0) {
    const uniqueDates = [...new Set(dates)];
    eventDate = uniqueDates.length === 1 
      ? uniqueDates[0] 
      : `${uniqueDates[0]} - ${uniqueDates[uniqueDates.length - 1]}`;
  }

  const tableHTML = document.querySelector('.table-wrapper').innerHTML;

  const printContent = `
    <head>
  <title>${eventTitle} - Attendance List</title>
  <style>
    body { 
      font-family: 'Montserrat', sans-serif; 
      margin: 20px; 
      color: #000; 
      background: #fff;
    }
    h1 { 
      text-align: center; 
      margin-bottom: 4px; 
      font-size: 22px; 
      text-transform: uppercase; 
    }
    h2 { 
      text-align: center; 
      margin-bottom: 6px; 
      font-size: 16px;
    }
    .event-details { 
      text-align: center; 
      margin-bottom: 12px; 
      font-size: 13px;
    }
    table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-top: 8px; 
      font-size: 12px;
    }
    th, td { 
      border: 1px solid #000; 
      padding: 4px 6px; 
      text-align: left; 
    }
    th { 
      background-color: #e0e0e0; /* light gray */
      color: #000; 
      font-size: 12.5px;
    }
    @media print {
      body { margin: 12mm; }
      th { background-color: #ddd !important; -webkit-print-color-adjust: exact; }
    }
  </style>
</head>
<body>
  <h1>Attendance List</h1>
  <h2>${eventTitle}</h2>
  <div class="event-details">
    <p>Location: <strong>${eventLocation}</strong></p>
    <p>Date: <strong>${eventDate}</strong></p>
  </div>
  ${tableHTML}
</body>
</html>

  `;

  // Open new window and print
  const printWindow = window.open('', '', 'width=900,height=700');
  printWindow.document.write(printContent);
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  printWindow.close();
});









  
});
</script>
</body>
</html>