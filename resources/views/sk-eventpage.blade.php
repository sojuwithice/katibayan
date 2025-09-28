<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/sk-eventpage.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qr-code-styling/lib/qr-code-styling.js"></script>





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

      <a href="{{ route('sk-eventpage') }}" class="active">
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


    <!-- Event and Program Section -->
<section class="event-section">
  <!-- Event and Program Section -->
<div class="event-header">
  <h2>Event and Program</h2>

  <div class="create-activity-dropdown">
    <a href="#" class="create-activity">
      Create Activity <i class="fa-solid fa-calendar-plus"></i>
    </a>

    <ul class="dropdown-menu">
      <li>
        <a href="{{ route('create-event') }}">
          <span class="dot blue"></span> Event
        </a>
      </li>
      <li>
        <a href="{{ route('create-program') }}">
          <span class="dot yellow"></span> Program
        </a>
      </li>
    </ul>
  </div>
</div>


  <!-- Happening Today -->
  <div class="event-category happening">
    <span class="tag">Happening Today</span>
    <div class="event-card">
      <div class="event-date">
        <span class="day">Thu</span>
        <span class="num">09</span>
      </div>
      <div class="event-details">
        <h3>Kalinisan sa Bagong Pilipinas Program</h3>
        <p><i class="fas fa-map-marker-alt"></i> Barangay Hall (Starting Point)</p>
        <p><i class="fas fa-users"></i> Committee on Active Citizenship</p>
        <div class="event-datetime">
        <span class="label">DATE AND TIME</span>
        <span class="divider"></span>
        <span class="value">September 22, 2025 | 9:00 AM</span>
        </div>
      </div>
      <div class="event-action">
        <button class="launch-btn">Launch Event</button>
      </div>
    </div>
  </div>

  <!-- Modal -->
<div id="eventModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    
    <h2>Launch Event</h2>
    <p><strong>Title</strong><br> Kalinisan sa Bagong Pilipinas Program</p>
    <p><strong>Committee on:</strong> Active Citizenship</p>
    
    <img src="{{ asset('images/kalinisan.jpeg') }}" alt="Event Banner" class="event-banner">

    <p><em>Event Date: September 9, 2025 at 1:00 PM &nbsp;&nbsp; Location: Barangay Hall</em></p>
    <h3>Kalinisan sa Bagong Pilipinas</h3>
    <p>
      The Kalinisan sa Bagong Pilipinas is more than just a clean-up drive—it is a nationwide campaign
      that calls on every Filipino to take part in building a cleaner, greener, and more disciplined nation.
      ...
    </p>

    <p class="published">
      Published by: Hon. Marijoy<br>
      Committee on Active Citizenship
    </p>

    <div class="modal-actions">
  <button id="proceedPasscode" class="launch-btn">Launch Event</button>
</div>

  </div>
</div>

<!-- Passcode Modal -->
<div id="passcodeModal" class="modal">
  <div class="modal-content">
    <span class="close passcode-close">&times;</span>

    <h2>Create Passcode for Attendance Tracking</h2>
    <p>
      The passcode is automatically converted into a QR code for easier tracking
      of attendance. The QR code can be displayed digitally, making the attendance
      process more efficient and accessible.
    </p>
    <input type="text" placeholder="Enter the passcode here" />
    <button>Generate</button>
  </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="modal">
  <div class="modal-content qr-content">
    <span class="close qr-close">&times;</span>

    <h2>Scan for your attendance</h2>
    <p><strong>Title:</strong> Kalinisan sa Bagong Pilipinas Program</p>

    <div id="qrcode"></div>

    <p class="small-text">
      Having trouble scanning the QR code?<br>
      Here’s the passcode below.
    </p>

    <input type="text" id="generatedPasscode" readonly />

    <p class="footer-text">
      This will mark your attendance in the program.
    </p>
  </div>
</div>






  <!-- Tabs + Dropdown Container -->
<div class="tabs-container">
  <!-- Tabs -->
  <div class="tabs">
    <button class="tab active">All</button>
    <button class="tab">Upcoming</button>
    <button class="tab">Rescheduled</button>
    <button class="tab">Completed</button>
  </div>

  <!-- Custom Category Dropdown (must match classes used in JS) -->
  <div class="category-dropdown">
    <label for="category">Category:</label>

    <div class="custom-select" id="category" tabindex="0" role="listbox" aria-haspopup="listbox">
  <div class="selected" data-value="all">
    <span class="selected-text">All</span>
    <i data-lucide="chevron-down" class="dropdown-icon"></i>
  </div>
  <ul class="options" role="presentation">
    <li data-value="all" role="option">All</li>
    <li data-value="active citizenship" role="option">Active Citizenship</li>
    <li data-value="economic empowerment" role="option">Economic Empowerment</li>
    <li data-value="education" role="option">Education</li>
    <li data-value="health" role="option">Health</li>
    <li data-value="sports" role="option">Sports</li>
  </ul>
</div>

  </div>
</div>




  

  <!-- This Month -->
  <div class="event-category">
    <h4>This Month</h4>
    <div class="event-card">
      <div class="event-date">
        <span class="day">Thu</span>
        <span class="num">09</span>
      </div>
      <div class="event-details">
        <h3>Kalinisan sa Bagong Pilipinas Program</h3>
        <p><i class="fas fa-map-marker-alt"></i> Barangay Hall (Starting Point)</p>
        <p><i class="fas fa-users"></i> Committee on Active Citizenship</p>
        <div class="event-datetime">
        <span class="label">DATE AND TIME</span>
        <span class="divider"></span>
        <span class="value">September 22, 2025 | 9:00 AM</span>
        </div>
        </div>
        <div class="event-action">
            <a href="{{ route('edit-event') }}" class="edit-btn">
                Edit <i class="fa-solid fa-pen"></i>
            </a>
        </div>
    </div>
  </div>

  <!-- October -->
  <div class="event-category">
    <h4>October</h4>
    <div class="event-card">
      <div class="event-date">
        <span class="day">Thu</span>
        <span class="num">09</span>
      </div>
      <div class="event-details">
        <h3>Kalinisan sa Bagong Pilipinas Program</h3>
        <p><i class="fas fa-map-marker-alt"></i> Barangay Hall (Starting Point)</p>
        <p><i class="fas fa-users"></i> Committee on Active Citizenship</p>
        <div class="event-datetime">
        <span class="label">DATE AND TIME</span>
        <span class="divider"></span>
        <span class="value">September 22, 2025 | 9:00 AM</span>
        </div>
      </div>
      <div class="event-action">
            <button class="edit-btn">
                Edit<i class="fa-solid fa-pen"></i>  
            </button>
     </div>
    </div>
  </div>
</section>








<script>
document.addEventListener("DOMContentLoaded", () => {
  // Initialize icons (safe-check)
  if (typeof lucide !== "undefined" && lucide.createIcons) lucide.createIcons();

  // --- UI elements (safe lookups) ---
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  const profileItem = document.querySelector('.profile-item');
  const profileWrapper = document.querySelector('.profile-wrapper');
  const profileToggle = document.getElementById('profileToggle');
  const profileDropdown = document.querySelector('.profile-dropdown');
  const notifWrapper = document.querySelector(".notification-wrapper");

  // Sidebar toggle (only if both exist)
  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');
      if (!sidebar.classList.contains('open')) profileItem?.classList.remove('open');
    });
  }

  // Example submenu toggle (defensive)
  const evaluationItem = document.querySelector('.evaluation-item');
  const evaluationLink = document.querySelector('.evaluation-link');
  evaluationLink?.addEventListener('click', (e) => {
    e.preventDefault();
    evaluationItem?.classList.toggle('open');
  });

  // --- Calendar code (kept as-is, but guarded) ---
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
  prevBtn?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() - 7);
    renderCalendar(currentView);
  });
  nextBtn?.addEventListener("click", () => {
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

  // === Notifications / profile dropdowns ===
  if (notifWrapper) {
    const bell = notifWrapper.querySelector(".fa-bell");
    bell?.addEventListener("click", (e) => {
      e.stopPropagation();
      notifWrapper.classList.toggle("active");
      profileWrapper?.classList.remove("active");
    });
    notifWrapper.querySelector(".notif-dropdown")?.addEventListener("click", (e) => e.stopPropagation());
  }
  if (profileWrapper && profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
      notifWrapper?.classList.remove("active");
    });
    profileDropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  // --- Prevent runtime errors when checking containment on null elements ---
  document.addEventListener("click", (e) => {
    // close sidebar if click outside (defensive)
    if (sidebar && menuToggle) {
      if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
        sidebar.classList.remove('open');
        profileItem?.classList.remove('open');
      }
    } else if (sidebar) {
      if (!sidebar.contains(e.target)) sidebar.classList.remove('open');
    }

    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');

    // Close any open custom-select options
    document.querySelectorAll('.custom-select .options.show').forEach(o => o.classList.remove('show'));
  });

  // === Highlight Holidays in Events (defensive) ===
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
      eventItem.querySelector('.date')?.classList.add('holiday');
    }
  });

  // === CUSTOM SELECT: works for multiple .custom-select on page ===
    const customSelect = document.querySelector("#category");
    const selected = customSelect.querySelector(".selected");
    const options = customSelect.querySelector(".options");
    const items = options.querySelectorAll("li");

    selected.addEventListener("click", () => {
    const isOpen = options.style.display === "block";
    options.style.display = isOpen ? "none" : "block";
    customSelect.classList.toggle("open", !isOpen);
    });

    items.forEach(item => {
    item.addEventListener("click", () => {
        customSelect.querySelector(".selected-text").textContent = item.textContent;
        options.style.display = "none";
        customSelect.classList.remove("open");
    });
    });

    document.addEventListener("click", (e) => {
    if (!customSelect.contains(e.target)) {
        options.style.display = "none";
        customSelect.classList.remove("open");
    }
    });

  // Launch Event button → open event modal
document.querySelectorAll(".launch-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("eventModal").style.display = "block";
  });
});

// Event modal → proceed to passcode modal
document.getElementById("proceedPasscode").addEventListener("click", () => {
  document.getElementById("eventModal").style.display = "none";
  document.getElementById("passcodeModal").style.display = "flex"; 
});


// Close modals
document.querySelectorAll(".close").forEach(btn => {
  btn.addEventListener("click", () => {
    btn.closest(".modal").style.display = "none";
  });
});

document.querySelector("#passcodeModal button").addEventListener("click", () => {
  const input = document.querySelector("#passcodeModal input").value;
  const passcode = input || "Linis1MA3Ys"; // fallback

  // Close Passcode Modal
  document.getElementById("passcodeModal").style.display = "none";

  // Open QR Modal
  document.getElementById("qrModal").style.display = "flex";

  // Insert passcode to readonly input
  document.getElementById("generatedPasscode").value = passcode;

  // Clear previous QR
  document.getElementById("qrcode").innerHTML = "";

  // Generate gradient QR using QRCodeStyling
  const qrCode = new QRCodeStyling({
  width: 250,
  height: 250,
  data: passcode,
  dotsOptions: {
    type: "square",
    gradient: {
      type: "linear",
      rotation: 135, 
      colorStops: [
        { offset: 0, color: "#3C87C4" }, 
        { offset: 1, color: "#F9CD55" }  
      ]
    }
  },
  backgroundOptions: { color: "#ffffff" },
  image: "" 
});


  // Append to container
  qrCode.append(document.getElementById("qrcode"));
});

// Close QR modal
document.querySelector(".qr-close").addEventListener("click", () => {
  document.getElementById("qrModal").style.display = "none";
});

// === Create Activity dropdown toggle ===
const createActivityDropdown = document.querySelector(".create-activity-dropdown");
const createActivityBtn = createActivityDropdown?.querySelector(".create-activity");

if (createActivityDropdown && createActivityBtn) {
  createActivityBtn.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    createActivityDropdown.classList.toggle("active");
  });

  // Close kapag nag-click sa labas
  document.addEventListener("click", (e) => {
    if (!createActivityDropdown.contains(e.target)) {
      createActivityDropdown.classList.remove("active");
    }
  });
}



});
</script>







</body>
</html>
