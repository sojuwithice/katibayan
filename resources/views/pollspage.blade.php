<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Poll</title>
  <link rel="stylesheet" href="{{ asset('css/polls.css') }}">
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
               <li><i class="fas fa-star"></i> Send Feedback to Katibayan</li>
              <li class="logout-item">
                <a href="loginpage" onclick="confirmLogout(event)">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </ul>
          </div>
        </div>
      </div>
    </header>


   <div class="polls-container">
  <div class="polls-header-box">
    <div class="polls-header">
      <h2>Polls</h2>

      <div class="committee-select">
        <label for="committee">Select a Committee:</label>

        <!-- Custom Dropdown -->
        <div class="custom-dropdown">
          <button class="dropdown-toggle">
  <span class="selected-text">All</span>
  <span class="arrow-circle">
    <i data-lucide="chevron-down"></i>
  </span>
</button>

          <ul class="dropdown-menu">
            <li data-value="all">All</li>
            <li data-value="active citizenship">Active Citizenship</li>
            <li data-value="economic empowerment">Economic Empowerment</li>
            <li data-value="education">Education</li>
            <li data-value="health">Health</li>
            <li data-value="sports">Sports</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>


  <h3 class="suggestions-title">Suggestions Board</h3>

       <!-- Poll Card 1 -->
      <div class="poll-box">
        <div class="poll-header">
          <p><span class="label">Question:</span> Would you like us to organize a Drag Race event for the youth?</p>
          <div class="poll-meta">
            <div class="avatars">
              <span class="avatar"></span>
              <span class="avatar"></span>
              <span class="avatar"></span>
              <span class="avatar"></span>
            </div>
            <span class="others">+ others</span>
            <button class="vote-btn">Vote</button>

          </div>
        </div>

        <div class="poll-body hidden">
          <h4 class="choices-label">Choices</h4>

          <label class="choice">
            <input type="radio" name="poll1" value="yes">
            <span class="choice-text">Yes, that would be exciting</span>
            <div class="vote-info">
              <div class="avatars"><span class="avatar"></span><span class="avatar"></span></div>
              <span class="count">35 people voted</span>
            </div>
          </label>

          <label class="choice">
            <input type="radio" name="poll1" value="maybe">
            <span class="choice-text">Maybe, I need more details</span>
            <div class="vote-info">
              <div class="avatars"><span class="avatar"></span></div>
              <span class="count">15 people voted</span>
            </div>
          </label>

          <label class="choice">
            <input type="radio" name="poll1" value="no">
            <span class="choice-text">No, not interested</span>
            <div class="vote-info">
              <div class="avatars"><span class="avatar"></span><span class="avatar"></span></div>
              <span class="count">10 people voted</span>
            </div>
          </label>

          <a href="#" class="add-suggestion">Add another suggestion</a>
        </div>
      </div>

      <!-- Poll Card 2 -->
      <div class="poll-box">
        <div class="poll-header">
          <p><span class="label">Question:</span> Would you like us to organize a Drag Race event for the youth?</p>
          <div class="poll-meta">
            <div class="avatars">
              <span class="avatar"></span>
              <span class="avatar"></span>
              <span class="avatar"></span>
              <span class="avatar"></span>
            </div>
            <span class="others">+ others</span>
            <button class="vote-btn">Vote</button>
          </div>
        </div>

        <div class="poll-body hidden">
          <h4 class="choices-label">Choices</h4>

          <label class="choice">
            <input type="radio" name="poll2" value="yes">
            <span class="choice-text">Yes, that would be exciting</span>
            <div class="vote-info">
              <div class="avatars"><span class="avatar"></span><span class="avatar"></span></div>
              <span class="count">35 people voted</span>
            </div>
          </label>

          <label class="choice">
            <input type="radio" name="poll2" value="maybe">
            <span class="choice-text">Maybe, I need more details</span>
            <div class="vote-info">
              <div class="avatars"><span class="avatar"></span></div>
              <span class="count">15 people voted</span>
            </div>
          </label>

          <label class="choice">
            <input type="radio" name="poll2" value="no">
            <span class="choice-text">No, not interested</span>
            <div class="vote-info">
              <div class="avatars"><span class="avatar"></span><span class="avatar"></span></div>
              <span class="count">10 people voted</span>
            </div>
          </label>

          <a href="#" class="add-suggestion">Add another suggestion</a>
        </div>
      </div>
    </div><!-- end polls-container -->

  </div><!-- end main -->

 
<!-- Poll Change Answer Modal -->
<div id="confirmModal" class="modal-overlay hidden">
  <div class="modal-box">
    <p>Are you sure you want to change your answer?</p>
    <div class="modal-actions">
      <button class="cancel-btn">Cancel</button>
      <button class="confirm-btn">Yes</button>
    </div>
  </div>
</div>








 

 













<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons ===
  lucide.createIcons();

  // === Sidebar ===
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  const profileItem = document.querySelector('.profile-item');
  const profileLink = profileItem?.querySelector('.profile-link');
  const eventsItem = document.querySelector('.events-item');
  const eventsLink = eventsItem?.querySelector('.events-link');

  menuToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    sidebar.classList.toggle('open');
    if (!sidebar.classList.contains('open')) {
      profileItem?.classList.remove('open');
      eventsItem?.classList.remove('open');
    }
  });

  function closeAllSubmenus() {
    profileItem?.classList.remove('open');
    eventsItem?.classList.remove('open');
  }

  profileLink?.addEventListener('click', (e) => {
    e.preventDefault();
    if (sidebar.classList.contains('open')) {
      const isOpen = profileItem.classList.contains('open');
      closeAllSubmenus();
      if (!isOpen) profileItem.classList.add('open');
    }
  });

  eventsLink?.addEventListener('click', (e) => {
    e.preventDefault();
    if (sidebar.classList.contains('open')) {
      const isOpen = eventsItem.classList.contains('open');
      closeAllSubmenus();
      if (!isOpen) eventsItem.classList.add('open');
    }
  });

  // === Profile & Notifications dropdowns ===
  const profileWrapper = document.querySelector('.profile-wrapper');
  const profileToggle = document.getElementById('profileToggle');
  const profileDropdown = document.querySelector('.profile-dropdown');
  const notifWrapper = document.querySelector(".notification-wrapper");
  const notifBell = notifWrapper?.querySelector(".fa-bell");
  const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

  document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
      closeAllSubmenus();
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) {
      profileWrapper.classList.remove('active');
    }
    if (notifWrapper && !notifWrapper.contains(e.target)) {
      notifWrapper.classList.remove('active');
    }
  });

  profileToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    profileWrapper.classList.toggle('active');
    notifWrapper?.classList.remove('active');
  });

  profileDropdown?.addEventListener('click', e => e.stopPropagation());

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
  const holidays = [
    "2025-01-01", "2025-04-09", "2025-04-17", "2025-04-18",
    "2025-05-01", "2025-06-06", "2025-06-12", "2025-08-25",
    "2025-11-30", "2025-12-25", "2025-12-30"
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
      ) dayEl.classList.add("active");

      dayEl.appendChild(weekdayEl);
      dayEl.appendChild(dateEl);
      daysContainer.appendChild(dayEl);
    }
  }
  renderCalendar(currentView);
  document.querySelector(".calendar .prev")?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() - 7);
    renderCalendar(currentView);
  });
  document.querySelector(".calendar .next")?.addEventListener("click", () => {
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

  // Example: MON, AUG 8 10:00 AM
  timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
}
updateTime();
setInterval(updateTime, 60000);


  // === Password toggle ===
  const tempPassword = document.getElementById('tempPassword');
  const toggleIcon = document.querySelector('.toggle-password');
  if (tempPassword && toggleIcon) {
    let hidden = true;
    const realPassword = "marijoy";
    toggleIcon.addEventListener('click', () => {
      if (hidden) {
        tempPassword.textContent = realPassword;
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
        hidden = false;
      } else {
        tempPassword.textContent = "•••••";
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
        hidden = true;
      }
    });
  }


  // === Custom dropdown ===
  const dropdown = document.querySelector(".custom-dropdown");
  if (dropdown) {
    const toggle = dropdown.querySelector(".dropdown-toggle");
    const menu = dropdown.querySelector(".dropdown-menu");
    const selected = dropdown.querySelector(".selected-text");
    toggle.addEventListener("click", () => {
      const isOpen = dropdown.classList.toggle("open");
      menu.style.display = isOpen ? "block" : "none";
    });
    menu.querySelectorAll("li").forEach(item => {
      item.addEventListener("click", () => {
        selected.textContent = item.textContent;
        menu.style.display = "none";
        dropdown.classList.remove("open");
      });
    });
    document.addEventListener("click", e => {
      if (!dropdown.contains(e.target)) {
        menu.style.display = "none";
        dropdown.classList.remove("open");
      }
    });
  }

  // === Polls toggle & vote button ===
document.querySelectorAll('.vote-btn').forEach(btn => {
  const poll = btn.closest('.poll-box');
  const body = poll.querySelector('.poll-body');

  let hasVoted = false;

  function updateButton() {
    const selectedOption = poll.querySelector('input[type="radio"]:checked');
    if (selectedOption) {
      hasVoted = true;
      btn.classList.add('voted');
      btn.innerHTML = '<i class="fas fa-check"></i> Voted';
    } else if (!hasVoted) {
      btn.classList.remove('voted');
      btn.innerHTML = 'Vote';
    }
  }

  poll.querySelectorAll('input[type="radio"]').forEach(input => {
    input.addEventListener('change', updateButton);
  });

  btn.addEventListener('click', () => {
    body.classList.toggle('hidden');

    document.querySelectorAll('.poll-body').forEach(b => {
      if (b !== body) b.classList.add('hidden');
    });

    updateButton();
  });
});




// === Poll Change Answer Modal ===
const pollSelected = new WeakMap();
let pending = null;

const confirmModal = document.getElementById('confirmModal');
const cancelBtn = confirmModal?.querySelector('.cancel-btn');
const confirmBtn = confirmModal?.querySelector('.confirm-btn');

function getChoiceValue(input) {
  if (input.value) return input.value;
  const textEl = input.closest('.choice')?.querySelector('.choice-text');
  return textEl ? textEl.textContent.trim() : "";
}

document.querySelectorAll('.poll-box').forEach(poll => {
  poll.querySelectorAll('.choice input[type="radio"]').forEach(input => {
    input.addEventListener('click', (e) => {
      const newValue = getChoiceValue(input);
      const prevValue = pollSelected.get(poll) || null;

      if (prevValue && prevValue !== newValue) {
        e.preventDefault();

        setTimeout(() => {
          input.checked = false;
          const prevInput = [...poll.querySelectorAll('.choice input')]
            .find(i => getChoiceValue(i) === prevValue);
          if (prevInput) prevInput.checked = true;
        }, 0);

        pending = { poll, input, value: newValue };
        confirmModal.classList.remove('hidden');
      } else {
        pollSelected.set(poll, newValue);
      }
    });
  });
});

cancelBtn?.addEventListener('click', () => {
  confirmModal.classList.add('hidden');
  pending = null;
});

confirmBtn?.addEventListener('click', () => {
  if (pending) {
    const { poll, input, value } = pending;
    pollSelected.set(poll, value);
    input.checked = true;

   
    const btn = poll.querySelector('.vote-btn');
    btn.classList.add('voted');
    btn.innerHTML = '<i class="fas fa-check"></i> Voted';


    pending = null;
  }
  confirmModal.classList.add('hidden');
});

confirmModal?.addEventListener('click', (e) => {
  if (e.target === confirmModal) {
    confirmModal.classList.add('hidden');
    pending = null;
  }
});
});
</script>