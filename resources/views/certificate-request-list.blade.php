<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/certificate-request-list.css') }}">
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

    <main class="container" data-event-id="{{ $event->id }}">
  <!-- Header Title -->
      <!-- Program Info Card -->
    <section class="certificate-card">
  <p class="certificate-label">Claiming of Certificate for:</p>
  <div class="certificate-content">

    <div class="certificate-image">

      <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('images/default_cert_image.jpg') }}" 
     alt="{{ $event->title }}">
    </div>

    <div class="certificate-details">
      <p><strong>Title:</strong></p>

      <h3 class="cert-title">
        {{ $event->title }}
      </h3>

      <p class="cert-subtitle">{{ $event->description ? $event->description : 'No description provided.' }}</p>

      <p class="cert-date">{{ $event->event_date->format('F j, Y') }}</p>

    </div>
  </div>
</section>

    <!-- Certificate Request Header -->
<div class="request-header">
  <h3>Certificate Request List</h3>

  @if ($requests->contains('status', 'requesting'))
    {{-- Kung mayroon pang kahit isang "requesting", ipakita ang normal na button --}}
    <button class="accept-btn">Accept Request</button>
  @else
    {{-- Kung lahat ay na-process na (accepted, claimed, etc.), ipakita ang disabled na button --}}
    <button class="accept-btn accepted-btn" disabled>Accepted</button>
  @endif

</div>

<!-- Certificate Request Table -->
<!-- Certificate Request Table -->
<section class="request-list">
  <div class="table-wrapper">
    <table class="certificate-table">
      <thead>
        <tr>
          <th>KK Number ID</th>
          <th>Name</th>
          <th>Age</th>
          <th>Purok</th>
          <th>Status</th>
          <th>Certificate Status</th> </tr>
      </thead>
      <tbody>
@forelse ($requests as $req)
  <tr data-request-id="{{ $req->id }}">
    <td>{{ $req->user->account_number ?? 'N/A' }}</td>
    <td>{{ $req->user->name ?? 'Unknown' }}</td>
    <td>{{ $req->user->age ?? 'N/A' }}</td>
    <td>{{ $req->user->purok ?? 'N/A' }}</td>

    {{-- STATUS CELL (Request Status) --}}
    {{-- Ito ay magiging 'Requesting' or 'Accepted' lang --}}
    <td class="status {{ $req->status }}" id="status-cell-{{ $req->id }}">
      @if($req->status === 'requesting')
        Requesting
      @else
        Accepted {{-- Kahit 'ready_for_pickup' or 'claimed' pa, 'Accepted' ang display --}}
      @endif
    </td>

    {{-- ACTION CELL (Certificate Status) --}}
    {{-- Ito ang magbabago-bago: Pending -> Accepted -> Claim Button -> Claimed --}}
    <td class="action-cell" id="action-cell-{{ $req->id }}">
      @if($req->status === 'ready_for_pickup')
        <button class="claim-btn" data-id="{{ $req->id }}">Claim</button>

      @elseif($req->status === 'claimed')
        <span class="status-claimed">Claimed</span>

      @elseif($req->status === 'accepted')
        <span class="status-pending">Accepted</span>
        
      @else {{-- 'requesting' --}}
        <span class="status-pending">Pending</span>
      @endif
    </td>
  </tr>
@empty
  <tr>
    <td colspan="6" style="text-align:center;">No requests for this event yet.</td>
  </tr>
@endforelse
</tbody>
    </table>
  </div>
</section>



<!-- Accepted Modal -->
<div id="acceptedModal" class="accepted-modal">
  <div class="accepted-modal-content">
    <div class="accepted-icon">
      <i class="fas fa-check"></i>
    </div>
    <h2>Accepted</h2>
    <p>
      The requests for certificates have been accepted. A schedule will now be set for the claiming of certificates.
    </p>
    <button id="scheduleBtn">Set a schedule</button>
  </div>
</div>

<!-- Trigger -->


<!-- Modal -->
<div id="scheduleModal" class="schedule-modal">
  <div class="schedule-modal-content">
    <!-- Header -->
    <h2 class="modal-title">Certificate Release Schedule</h2>
    <p class="modal-subtitle">Schedule for the distribution of certificates to participants.</p>

    <!-- Body -->
    <div class="schedule-body">
      <!-- Calendar Section -->
      <div class="calendar-section">
        <p class="section-label">Set Date</p>

        <!-- Calendar Header -->
        <div class="calendar-header">
          <span class="nav-btn">&lt;</span>
          <span class="month-display">September 2025</span>
          <span class="nav-btn">&gt;</span>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
          <div class="day-label">Mon</div>
          <div class="day-label">Tue</div>
          <div class="day-label">Wed</div>
          <div class="day-label">Thu</div>
          <div class="day-label">Fri</div>
          <div class="day-label">Sat</div>
          <div class="day-label">Sun</div>

          <!-- Example days -->
          <div class="day">1</div>
          <div class="day">2</div>
          <div class="day">3</div>
          <div class="day">4</div>
          <div class="day">5</div>
          <div class="day">6</div>
          <div class="day">7</div>
          <div class="day">8</div>
          <div class="day">9</div>
          <div class="day">10</div>
          <div class="day">11</div>
          <div class="day">12</div>
          <div class="day">13</div>
          <div class="day">14</div>
          <div class="day">15</div>
          <div class="day">16</div>
          <div class="day">17</div>
          <div class="day">18</div>
          <div class="day">19</div>
          <div class="day">20</div>
          <div class="day">21</div>
          <div class="day">22</div>
          <div class="day">23</div>
          <div class="day">24</div>
          <div class="day">25</div>
          <div class="day active">26</div>
          <div class="day">27</div>
          <div class="day">28</div>
          <div class="day">29</div>
          <div class="day">30</div>
        </div>
      </div>

      <!-- Time + Location Section -->
<div class="time-location">
  <p class="section-label">Set Time</p>

  <!-- Time Picker -->
  <div class="time-picker">
    <!-- Hours -->
    <div class="time-column">
      <button class="circle-btn">▲</button>
      <div class="time-box">
        <span class="time-value">01</span>
      </div>
      <button class="circle-btn">▼</button>
    </div>

    <span class="colon">:</span>

    <!-- Minutes -->
    <div class="time-column">
  <button class="circle-btn">▲</button>  <!-- 1st child -->
  <div class="time-box">
    <span class="time-value">00</span>
  </div>                                <!-- 2nd child -->
  <button class="circle-btn">▼</button>  <!-- 3rd child -->
</div>


    <!-- AM/PM -->
    <div class="ampm">
      <button class="ampm-btn active">AM</button>
      <button class="ampm-btn">PM</button>
    </div>
  </div>

    <!-- Location -->
    <label class="location-label" for="location">Location:</label>
        <textarea id="location" class="location-input" placeholder="Enter location..."></textarea>
      </div>
    </div>

    <!-- Note -->
    <div class="note">
      <strong>Note:</strong> The claiming of certificates will be on September 26, 2025, at 1:00 PM at the Barangay Hall.
    </div>

    <!-- Info -->
    <div class="info">
        <i class="fa-solid fa-circle-info info-icon"></i>
        This will notify the youth to claim their certificates.
    </div>



    <!-- Footer -->
    <div class="footer">
      <button class="schedule-confirm">Set schedule</button>
    </div>
  </div>
</div>







  





<script>
document.addEventListener("DOMContentLoaded", () => {

  // ===== Lucide Icons + Sidebar Toggle =====
  if (typeof lucide !== "undefined") lucide.createIcons();
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  const profileItem = document.querySelector('.profile-item');

  menuToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    sidebar?.classList.toggle('open');
    if (!sidebar?.classList.contains('open')) profileItem?.classList.remove('open');
  });

  // ===== Submenus =====
  const evaluationItem = document.querySelector('.evaluation-item');
  const evaluationLink = document.querySelector('.evaluation-link');
  evaluationLink?.addEventListener('click', (e) => {
    e.preventDefault();
    const isOpen = evaluationItem.classList.contains('open');
    evaluationItem.classList.remove('open');
    if (!isOpen) evaluationItem.classList.add('open');
  });

  // ===== Dashboard Weekly Calendar =====
  const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
  const daysContainer = document.querySelector(".calendar .days");
  const header = document.querySelector(".calendar header h3");
  const holidays = [
    "2025-01-01","2025-04-09","2025-04-17","2025-04-18",
    "2025-05-01","2025-06-06","2025-06-12","2025-08-25",
    "2025-11-30","2025-12-25","2025-12-30"
  ];
  let today = new Date();
  let currentView = new Date();

  function renderDashboardCalendar(baseDate) {
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
      if (thisDay.toDateString() === today.toDateString()) dayEl.classList.add("active");

      dayEl.appendChild(weekdayEl);
      dayEl.appendChild(dateEl);
      daysContainer.appendChild(dayEl);
    }
  }

  renderDashboardCalendar(currentView);

  document.querySelector(".calendar .prev")?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() - 7);
    renderDashboardCalendar(currentView);
  });

  document.querySelector(".calendar .next")?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() + 7);
    renderDashboardCalendar(currentView);
  });

  // ===== Real-time Clock =====
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

  // ===== Notifications + Profile Dropdown =====
  const notifWrapper = document.querySelector(".notification-wrapper");
  const profileWrapper = document.querySelector(".profile-wrapper");
  const profileToggle = document.getElementById("profileToggle");

  notifWrapper?.querySelector(".fa-bell")?.addEventListener("click", e => {
    e.stopPropagation();
    notifWrapper.classList.toggle("active");
    profileWrapper?.classList.remove("active");
  });

  profileToggle?.addEventListener("click", e => {
    e.stopPropagation();
    profileWrapper.classList.toggle("active");
    notifWrapper?.classList.remove("active");
  });

  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) sidebar?.classList.remove('open');
    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
    document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
  });

  // ==========================================================
  // SETUP PARA SA AJAX
  // ==========================================================
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const mainContainer = document.querySelector('main.container');
  const eventId = mainContainer ? mainContainer.dataset.eventId : null;

  let toastContainer = document.querySelector('.toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);
  }

  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    let iconClass = '';
    if (type === 'success') iconClass = 'fa-solid fa-circle-check';
    if (type === 'error') iconClass = 'fa-solid fa-circle-xmark';
    if (type === 'info') iconClass = 'fa-solid fa-circle-info';
    if (type === 'warning') iconClass = 'fa-solid fa-triangle-exclamation';
    toast.innerHTML = `<i class="${iconClass} toast-icon"></i> <span>${message}</span>`;
    toastContainer.appendChild(toast);
    setTimeout(() => { toast.remove(); }, 3000);
  }


  // ==========================================================
  // STEP 1: ACCEPTED MODAL LOGIC
  // ==========================================================
  
  const acceptBtn = document.querySelector('.accept-btn');
  const acceptedModal = document.getElementById('acceptedModal');

  acceptBtn?.addEventListener('click', () => {
    if (!eventId) {
        showToast('Event ID not found. Cannot proceed.', 'error');
        return;
    }

    acceptBtn.disabled = true;
    acceptBtn.textContent = 'Accepting...';

    fetch('/accept-requests', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ event_id: eventId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to accept requests. Please try again.');
        }
        return response.json();
    })
    .then(data => {
        console.log(data.message); 
        acceptBtn.textContent = 'Accepted';
        acceptBtn.classList.add('accepted-btn'); 

        document.querySelectorAll('.certificate-table tbody .status.requesting').forEach(cell => {
            cell.textContent = 'Accepted';
            cell.classList.remove('requesting');
            cell.classList.add('accepted');
        });

        acceptedModal.style.display = 'flex';
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(error.message, 'error');
        acceptBtn.disabled = false;
        acceptBtn.textContent = 'Accept Request';
    });
  });

  // === FIX 1: TINANGGAL YUNG EVENT LISTENER PARA SA CLICK OUTSIDE ===
  // acceptedModal?.addEventListener('click', (e) => {
  //   if (e.target === acceptedModal) acceptedModal.style.display = 'none';
  // });


  // ==========================================================
  // STEP 2: SCHEDULE MODAL LOGIC
  // ==========================================================
  
  const scheduleBtn = document.getElementById("scheduleBtn");
  const scheduleModal = document.getElementById("scheduleModal");
  const closeScheduleBtn = scheduleModal?.querySelector(".close-btn"); // Para 'to sa 'X' button kung meron
  scheduleModal.style.display = "none";

  scheduleBtn?.addEventListener("click", () => {
    acceptedModal.style.display = "none";
    scheduleModal.style.display = "flex";
  });

  closeScheduleBtn?.addEventListener("click", () => scheduleModal.style.display = "none");
  // Tinanggal na rin dito yung click outside


  // ==========================================================
  // STEP 3: SCHEDULE MODAL CALENDAR & TIME
  // ==========================================================

  const calendarGrid = scheduleModal.querySelector(".calendar-grid");
  const monthDisplay = scheduleModal.querySelector(".month-display");
  const prevMonthBtn = scheduleModal.querySelector(".calendar-header .nav-btn:first-child");
  const nextMonthBtn = scheduleModal.querySelector(".calendar-header .nav-btn:last-child");

  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();
  let selectedDate = null;

  function renderScheduleCalendar(month, year) {
    if (!calendarGrid || !monthDisplay) return;
    
    const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    monthDisplay.textContent = `${monthNames[month]} ${year}`;
    calendarGrid.innerHTML = "";

    const dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    dayLabels.forEach(day => {
        const dayEl = document.createElement('div');
        dayEl.classList.add('day-label');
        dayEl.textContent = day;
        calendarGrid.appendChild(dayEl);
    });

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let startDay = (firstDay === 0) ? 6 : firstDay - 1;
    for (let i = 0; i < startDay; i++) {
      const empty = document.createElement('div');
      empty.classList.add('day', 'empty');
      calendarGrid.appendChild(empty);
    }

    for (let d = 1; d <= daysInMonth; d++) {
      const day = document.createElement('div');
      day.classList.add('day');
      day.textContent = d;
      
      const thisDate = new Date(year, month, d);
      const todayMidnight = new Date(today.toDateString());

      if (thisDate < todayMidnight) {
          day.classList.add('disabled');
      } else {
          day.addEventListener('click', () => {
              selectedDate = d;
              calendarGrid.querySelectorAll('.day.active').forEach(el => el.classList.remove('active'));
              day.classList.add('active');
          });
      }

      if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
        day.classList.add('today');
        if (!selectedDate) {
            day.classList.add('active');
            selectedDate = d;
        }
      }
      calendarGrid.appendChild(day);
    }
  }

  prevMonthBtn?.addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) { currentMonth = 11; currentYear--; }
    selectedDate = null;
    renderScheduleCalendar(currentMonth, currentYear);
  });

  nextMonthBtn?.addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) { currentMonth = 0; currentYear++; }
    selectedDate = null;
    renderScheduleCalendar(currentMonth, currentYear);
  });

  renderScheduleCalendar(currentMonth, currentYear);

  const timePicker = scheduleModal.querySelector('.time-picker');
  const timeColumns = timePicker.querySelectorAll('.time-column');
  const hourColumn = timeColumns[0];
  const minColumn = timeColumns[1];
  const hourValue = hourColumn.querySelector('.time-value');
  const minValue = minColumn.querySelector('.time-value');
  const hourUp = hourColumn.querySelector('button:first-of-type');
  const hourDown = hourColumn.querySelector('button:last-of-type');
  const minUp = minColumn.querySelector('button:first-of-type'); 
  const minDown = minColumn.querySelector('button:last-of-type');
  const ampmBtns = timePicker.querySelectorAll('.ampm-btn');
  let selectedAmPm = 'AM';

  hourUp.addEventListener('click', () => {
    let val = parseInt(hourValue.textContent);
    val = val === 12 ? 1 : val + 1;
    hourValue.textContent = val.toString().padStart(2, '0');
  });
  hourDown.addEventListener('click', () => {
    let val = parseInt(hourValue.textContent);
    val = val === 1 ? 12 : val - 1;
    hourValue.textContent = val.toString().padStart(2, '0');
  });
  minUp.addEventListener('click', () => {
    let val = parseInt(minValue.textContent);
    val = (val + 1) % 60;
    minValue.textContent = val.toString().padStart(2, '0');
  });
  minDown.addEventListener('click', () => {
    let val = parseInt(minValue.textContent);
    val = (val - 1 + 60) % 60;
    minValue.textContent = val.toString().padStart(2, '0');
  });
  ampmBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      ampmBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      selectedAmPm = btn.textContent;
    });
  });

  // ==========================================================
// STEP 4: CONFIRM SCHEDULE LOGIC (FULLY FIXED)
// ==========================================================

const confirmBtn = scheduleModal.querySelector('.schedule-confirm');
const locationInput = scheduleModal.querySelector('#location');

confirmBtn?.addEventListener('click', async () => {
    if (!selectedDate) {
        showToast('Please select a date.', 'error');
        return;
    }

    const location = locationInput.value.trim();
    if (!location) {
        showToast('Please enter a location.', 'error');
        return;
    }

    const formattedDate = `${currentYear}-${(currentMonth + 1)
        .toString()
        .padStart(2, '0')}-${selectedDate.toString().padStart(2, '0')}`;

    const formattedTime = `${hourValue.textContent}:${minValue.textContent} ${selectedAmPm}`;

    const scheduleData = {
        event_id: eventId,
        date: formattedDate,
        time: formattedTime,
        location: location,
    };

    console.log("Sending to /set-schedule:", scheduleData);

    confirmBtn.disabled = true;
    confirmBtn.textContent = "Setting...";

    try {
        const res = await fetch("/set-schedule", {
            method: "POST",
            cache: "no-store", // 🔥 prevents stale reload
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken, // Siguraduhin na 'csrfToken' ay defined sa taas
            },
            body: JSON.stringify(scheduleData),
        });

        if (!res.ok) {
            const err = await res.json().catch(() => null);
            throw new Error(err?.message || `Error ${res.status}`);
        }

        const data = await res.json();
        console.log("Success:", data);

        scheduleModal.style.display = "none";
        showToast(data.message || "Schedule set successfully!", "success");

        // ✅ AUTO RELOAD (HARD RELOAD NA NGAYON)
        setTimeout(() => {
            window.location.reload(true); // <--- ITO YUNG FIX
        }, 800); 

    } catch (error) {
        console.error("Schedule Error:", error);
        showToast(error.message, "error");

        confirmBtn.disabled = false;
        confirmBtn.textContent = "Set schedule";
    }
});



  // ==========================================================
// STEP 5: CLAIM BUTTON LOGIC (FIXED)
// ==========================================================

const tableWrapper = document.querySelector('.table-wrapper');

tableWrapper?.addEventListener('click', async (e) => {
    // Titingnan kung 'claim-btn' ang na-click
    if (!e.target.classList.contains('claim-btn')) {
        return;
    }

    const claimButton = e.target;
    const id = claimButton.dataset.id;
    
    if (!id) return;

    // I-disable ang button habang nagp-process
    claimButton.disabled = true;
    claimButton.textContent = 'Claiming...';

    try {
        const res = await fetch("{{ route('certificate.claim') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ id: id })
        });

        if (!res.ok) {
            const errData = await res.json().catch(() => null);
            const errorMsg = errData?.message || `Failed with status ${res.status}`;
            throw new Error(errorMsg);
        }

        const data = await res.json();
        
        if (data.success) { 
            
            showToast(data.message || 'Certificate claimed!', 'success');
        
            // --- FIX: Ibinalik ang auto-reload ---
            setTimeout(() => {
                location.reload();
            }, 1500); // 1.5 second delay
            // --- END NG FIX ---
        
        } else {
            throw new Error(data.message || 'Claiming failed. Please try again.');
        }

    } catch (error) {
        console.error('Error claiming certificate:', error);
        showToast(error.message, 'error');
        
        // Ibalik yung button sa dati kapag nagka-error
        if (claimButton) {
            claimButton.disabled = false;
            claimButton.textContent = 'Claim';
        }
    }
});
  

}); 
</script>

</body>
</html>