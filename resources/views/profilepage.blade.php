<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Profile Page</title>
  <link rel="stylesheet" href="{{ asset('css/profilepage.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('dashboard.index') }}" class="active">
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>
      <div class="profile-item nav-item">
        <a href="#" class="profile-link">
          <i data-lucide="circle-user"></i>
          <span class="label">Profile</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('profilepage') }}">My Profile</a>
          <a href="{{ route('certificatepage') }}">Certificates</a>
        </div>
      </div>

      <a href="{{ route('eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <a href="#">
        <i data-lucide="megaphone"></i>
        <span class="label">Announcements</span>
      </a>

      <a href="{{ route('evaluation') }}">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
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
                    <span>KK-Member</span>
                    <span>19 yrs old</span>
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

    <div class="profile-calendar">
      <!-- Profile Card -->
      <div class="profile-card">
        <div class="avatar-wrapper">
          <img src="https://i.pravatar.cc/80" alt="User" class="profile-avatar">
          <button class="avatar-camera">
            <i class="fas fa-camera"></i>
          </button>
        </div>
        <div class="profile-info">
          <h2>Marijoy S. Novora <span>|</span> <span class="age">21</span> <span class="years">years old</span></h2>
          
          <!-- badges row -->
          <div class="badges-row">
            <span class="badge blue">KK Member</span>
            <span class="status">Registered Voter</span>
          </div>

          <!-- address row -->
          <div class="address">
            <i class="fas fa-location-dot"></i> Purok 3, EM’s Barrio East Legazpi City
          </div>
        </div>
        <button class="edit-btn"><i class="fas fa-pen-to-square"></i></button>
      </div>

      <!-- Calendar -->
      <div class="calendar">
        <header>
          <button class="prev"><i class="fas fa-chevron-left"></i></button>
          <h3></h3>
          <button class="next"><i class="fas fa-chevron-right"></i></button>
          <i class="fas fa-calendar calendar-toggle" title="View full month"></i>
        </header>
        <div class="days"></div>
      </div>
    </div> 

    <div class="main-content">
  <div class="content-grid">
    
    <!-- Left Column -->
    <div class="left-column">
      <!-- Progress + Evaluation -->
        <div class="progress-eval-row">
        <div class="progress-card">
            <h3>Progress</h3>
            <div class="progress-circle">75%</div>
            <p>Still a long journey ahead!<p>
        </div>
        <div class="evaluation-card">
          <h3>Evaluated Programs</h3>
          <div class="progress-wrapper">
            <span class="progress-number">3</span> <!-- number ABOVE -->
            <div class="progress-bar">
              <div class="progress-fill" style="width: 60%;"></div>
            </div>
          </div>
          <p>You have 3 events/programs to evaluate</p>

        </div>

        </div>

        <!-- Email + Password -->
        <div class="credentials-card">
          <button class="settings-btn"><i class="fas fa-gear"></i></button>
          <h3>Email Address
            <i class="fas fa-envelope email-icon"></i>
          </h3>
          <div class="field">
              <label>KK-Email</label>
              <p>kkmarijoysn2025118@gmail.com</p>
          </div>
          <div class="field password-field">
                <label>Temporary Password</label>
                <div class="password-wrapper">
                    <p id="tempPassword">marijoy</p>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal-overlay" id="passwordModal">
          <div class="modal">
            <span class="close-btn" id="closeModal">&times;</span>
            <h2>Manage Account Password</h2>
            <p class="info-text">
              <i class="fas fa-circle-info info-icon"></i>
              Ensure your account’s security by following the required password format when changing your KK password
            </p>

            <label class="required">Current Password </label>
            <input type="password" id="currentPassword">

            <label class="required">New Password </label>
            <input type="password" id="newPassword">

            <label class="required">Confirm Password </label>
            <input type="password" id="confirmPassword">

            <div class="show-pass">
              <input type="checkbox" id="showPass"> <label for="showPass">Show Password</label>
            </div>

      <p class="req-heading">Required Password Format:</p>
      <div class="requirements">
        <p id="req-length" class="invalid"> Must be 8 characters or more</p>
        <p id="req-upper" class="invalid"> At least one uppercase letter</p>
        <p id="req-lower" class="invalid"> At least one lowercase letter</p>
        <p id="req-number" class="invalid"> At least one number</p>
        <p id="req-symbol" class="invalid"> At least one symbol</p>
        <p id="req-match" class="invalid"> Passwords must match</p>
      </div>

      <button class="save-btn">Save Changes</button>
    </div>
</div>
    </div>
          <!-- Success Modal -->
      <div class="success-overlay" id="successModal">
        <div class="modal success-modal">
          <h2>Your password has been changed successfully</h2>
          <p class="subtitle">Please don't forget your password</p>
          <button class="ok-btn" id="okBtn">OK</button>
        </div>
      </div>



    

    <!-- Right Column -->
    <div class="right-column">
      <div class="kk-profile card">
        <h3>KK Profile</h3>
        <p class="subtitle">
          The KK profiling is an organized summary of the demographic information of the Katipunan ng Kabataan members.
          This provides a clear basis for developing programs and policies that respond to the needs of the youth sector.
        </p>
        <hr>
        <div class="profile-details">
          <button class="edit-btn"><i class="fas fa-pen-to-square"></i></button>
          <div class="details-scroll">
          <h4>I. Profile</h4>
          <h3>Name of Respondent</h3>
            <p><strong>Last Name:</strong> Novora</p>
            <p><strong>First Name:</strong> Marijoy</p>
            <p><strong>Middle Name:</strong> Satonia</p>
            <p><strong>Age:</strong> 21</p>
            <p><strong>Address:</strong> Purok 3, EM’s Barrio East Legazpi City</p>
            <p><strong>Date of Birth:</strong> November 9, 2003</p>
            <p><strong>Sex:</strong> Female</p>
            <p><strong>Contact Number:</strong> 0920384****</p>
            <p><strong>Personal Email:</strong> Marijoyr42@gmail.com</p>
          <h4>II. Demographics</h4>
          <h3>Please provide your demographic details as accurately as possible</h3>
            <p><strong>Civil Status:</strong> Single</p>
            <p><strong>Educational Background:</strong> College Level</p>
            <p><strong>Age Group:</strong> Young Adult 15-30 years old</p>
            <p><strong>Work Status:</strong>Employed</p>
            <p><strong>Youth Classification:</strong> In school youth</p>
            <p><strong>Registered Voter:</strong>Yes</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


  </div> <!-- END Main -->

  <script src="{{ asset('js/profilepage.js') }}"></script>
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons ===
  lucide.createIcons();

  // === Sidebar & Profile/Events Dropdowns ===
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');

  const profileItem = document.querySelector('.profile-item');
  const profileLink = profileItem?.querySelector('.profile-link');

  const eventsItem = document.querySelector('.events-item');
  const eventsLink = eventsItem?.querySelector('.events-link');

  const profileWrapper = document.querySelector('.profile-wrapper');
  const profileToggle = document.getElementById('profileToggle');
  const profileDropdown = document.querySelector('.profile-dropdown');

  const notifWrapper = document.querySelector(".notification-wrapper");
  const notifBell = notifWrapper?.querySelector(".fa-bell");
  const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

  // Helper function to close all submenus
  function closeAllSubmenus() {
    profileItem?.classList.remove('open');
    eventsItem?.classList.remove('open');
  }

  // === Sidebar toggle ===
  menuToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    sidebar.classList.toggle('open');
    if (!sidebar.classList.contains('open')) closeAllSubmenus();
  });

  // === Profile submenu toggle ===
  profileLink?.addEventListener('click', (e) => {
    e.preventDefault();
    if (sidebar.classList.contains('open')) {
      const isOpen = profileItem.classList.contains('open');
      closeAllSubmenus();
      if (!isOpen) profileItem.classList.add('open');
    }
  });

  // === Events submenu toggle ===
  eventsLink?.addEventListener('click', (e) => {
    e.preventDefault();
    if (sidebar.classList.contains('open')) {
      const isOpen = eventsItem.classList.contains('open');
      closeAllSubmenus();
      if (!isOpen) eventsItem.classList.add('open');
    }
  });

  // === Close sidebar & submenus when clicking outside ===
  document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
      closeAllSubmenus();
    }
    if (!profileWrapper?.contains(e.target)) profileWrapper?.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
  });

  // === Profile dropdown toggle ===
  profileToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    profileWrapper.classList.toggle('active');
    notifWrapper?.classList.remove('active');
  });
  profileDropdown?.addEventListener('click', e => e.stopPropagation());

  // === Notifications dropdown toggle ===
  notifBell?.addEventListener('click', (e) => {
    e.stopPropagation();
    notifWrapper.classList.toggle('active');
    profileWrapper?.classList.remove('active');
  });
  notifDropdown?.addEventListener('click', e => e.stopPropagation());

  // === Calendar ===
  const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
  const daysContainer = document.querySelector(".calendar .days");
  const header = document.querySelector(".calendar header h3");
  let today = new Date();
  let currentView = new Date();
  const holidays = ["2025-01-01","2025-04-09","2025-04-17","2025-04-18","2025-05-01","2025-06-06","2025-06-12","2025-08-25","2025-11-30","2025-12-25","2025-12-30"];

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
      const month = (thisDay.getMonth()+1).toString().padStart(2,'0');
      const day = thisDay.getDate().toString().padStart(2,'0');
      const dateStr = `${thisDay.getFullYear()}-${month}-${day}`;
      if (holidays.includes(dateStr)) dateEl.classList.add('holiday');
      if (thisDay.getDate()===today.getDate() && thisDay.getMonth()===today.getMonth() && thisDay.getFullYear()===today.getFullYear()) dayEl.classList.add("active");
      dayEl.appendChild(weekdayEl);
      dayEl.appendChild(dateEl);
      daysContainer.appendChild(dayEl);
    }
  }
  renderCalendar(currentView);
  document.querySelector(".calendar .prev")?.addEventListener("click", () => { currentView.setDate(currentView.getDate()-7); renderCalendar(currentView); });
  document.querySelector(".calendar .next")?.addEventListener("click", () => { currentView.setDate(currentView.getDate()+7); renderCalendar(currentView); });

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

  // Example: MON, AUG 8 10:00 AM
  timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
}
updateTime();
setInterval(updateTime, 60000);


  // === Password toggle & modal logic ===
  const modalOverlay = document.getElementById("passwordModal");
  const successModal = document.getElementById("successModal");
  const closeModal = document.getElementById("closeModal");
  const closeSuccess = document.getElementById("closeSuccess");
  const okBtn = document.getElementById("okBtn");
  const saveBtn = document.querySelector(".save-btn");
  const openModalBtn = document.querySelector(".settings-btn");

  const currentPass = document.getElementById("currentPassword");
  const newPass = document.getElementById("newPassword");
  const confirmPass = document.getElementById("confirmPassword");
  const showPass = document.getElementById("showPass");

  const reqs = {
    length: document.getElementById("req-length"),
    upper: document.getElementById("req-upper"),
    lower: document.getElementById("req-lower"),
    number: document.getElementById("req-number"),
    symbol: document.getElementById("req-symbol"),
    match: document.getElementById("req-match")
  };

  function validatePassword() {
    const pass = newPass.value;
    const confirm = confirmPass.value;
    reqs.length.classList.toggle("valid", pass.length >= 8);
    reqs.length.classList.toggle("invalid", pass.length < 8);
    reqs.upper.classList.toggle("valid", /[A-Z]/.test(pass));
    reqs.upper.classList.toggle("invalid", !/[A-Z]/.test(pass));
    reqs.lower.classList.toggle("valid", /[a-z]/.test(pass));
    reqs.lower.classList.toggle("invalid", !/[a-z]/.test(pass));
    reqs.number.classList.toggle("valid", /[0-9]/.test(pass));
    reqs.number.classList.toggle("invalid", !/[0-9]/.test(pass));
    reqs.symbol.classList.toggle("valid", /[^A-Za-z0-9]/.test(pass));
    reqs.symbol.classList.toggle("invalid", !/[^A-Za-z0-9]/.test(pass));
    reqs.match.classList.toggle("valid", pass && pass === confirm);
    reqs.match.classList.toggle("invalid", pass !== confirm);

    const allValid = Object.values(reqs).every(r => r.classList.contains("valid"));
    saveBtn.disabled = !allValid;
    saveBtn.style.opacity = allValid ? "1" : "0.6";
    saveBtn.style.cursor = allValid ? "pointer" : "not-allowed";
  }

  newPass?.addEventListener("input", validatePassword);
  confirmPass?.addEventListener("input", validatePassword);

  showPass?.addEventListener("change", () => {
    const type = showPass.checked ? "text" : "password";
    [currentPass, newPass, confirmPass].forEach(input => { if(input) input.type = type; });
  });

  // Open/Close Password Modal
  openModalBtn?.addEventListener("click", () => {
    modalOverlay.classList.add("show");
    successModal.classList.remove("show");
    currentPass.value = newPass.value = confirmPass.value = "";
    showPass.checked = false;
    [currentPass, newPass, confirmPass].forEach(input => input.type = "password");
    Object.values(reqs).forEach(r => r.classList.remove("valid"));
    Object.values(reqs).forEach(r => r.classList.add("invalid"));
    saveBtn.disabled = true;
    saveBtn.style.opacity = "0.6";
    saveBtn.style.cursor = "not-allowed";
  });

  closeModal?.addEventListener("click", () => modalOverlay.classList.remove("show"));
  modalOverlay?.addEventListener("click", e => { if(e.target === modalOverlay) modalOverlay.classList.remove("show"); });

  saveBtn?.addEventListener("click", () => {
    modalOverlay.classList.remove("show");
    successModal.classList.add("show");
  });

  closeSuccess?.addEventListener("click", () => successModal.classList.remove("show"));
  okBtn?.addEventListener("click", () => successModal.classList.remove("show"));
  successModal?.addEventListener("click", e => { if(e.target === successModal) successModal.classList.remove("show"); });

});
</script>


