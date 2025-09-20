<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Profile Page</title>
  <link rel="stylesheet" href="{{ asset('css/evaluation.css') }}">
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


    <!-- Programs and Events Attended -->
<section class="programs-section">
  <div class="programs-header">
    <h2>Programs and Events Attended</h2>
    <p>Evaluate the program you attended and share your feedback with us.</p>
  </div>

  <p class="programs-note">
    You have <span>4 programs</span> to evaluate. Please evaluate them now.
  </p>

  <div class="program-list">
    <!-- Program Card -->
    <div class="program-card">
  <div class="program-img-wrapper">
    <img src="{{ asset('images/drugs.jpeg') }}" alt="Event" class="program-img">
    <span class="status-dot"></span>
  </div>
  <div class="program-info">
    <h3>International Day Against Drug Abuse and Illicit Trafficking</h3>
    <p class="date">September 15, 2025 | 9:00 AM</p>
  </div>
  <button class="evaluate-btn">Evaluate</button>
</div>


    <div class="program-card">
      <img src="{{ asset('images/drugs.jpeg') }}" alt="Event" class="program-img">
      <div class="program-info">
        <h3>International Day Against Drug Abuse and Illicit Trafficking</h3>
        <p class="date">September 15, 2025 | 9:00 AM</p>
      </div>
      <button class="evaluate-btn">Evaluate</button>
    </div>

    <div class="program-card">
      <img src="{{ asset('images/drugs.jpeg') }}" alt="Event" class="program-img">
      <div class="program-info">
        <h3>International Day Against Drug Abuse and Illicit Trafficking</h3>
        <p class="date">September 15, 2025 | 9:00 AM</p>
      </div>
      <button class="evaluate-btn">Evaluate</button>
    </div>

    <div class="program-card">
      <img src="{{ asset('images/drugs.jpeg') }}" alt="Event" class="program-img">
      <div class="program-info">
        <h3>International Day Against Drug Abuse and Illicit Trafficking</h3>
        <p class="date">September 15, 2025 | 9:00 AM</p>
      </div>
      <button class="evaluate-btn">Evaluate</button>
    </div>
  </div>
</section>

<!-- Evaluation Modal -->
<div id="evaluationModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>

    <!-- Page 1: Questions -->
    <div id="page1" class="page">
      <h2>Evaluation Form</h2>
      <p class="subtitle">Share your feedback with us.</p>
      <hr>

      <p class="instruction">
        Please evaluate the program/event you attended by answering the questions below. 
        Your feedback will help us improve future activities. Kindly provide honest and 
        constructive responses. All evaluations will remain confidential.
      </p>

      <label class="label">Event Name</label>
      <input type="text" class="event-input" 
             value="International Day Against Drug Abuse and Illicit Trafficking" readonly>

      <p class="label">Instruction:</p>
      <p class="instruction">
        Please rate the following statements from 1 to 5, where 1 means Strongly Disagree 
        and 5 means Strongly Agree
      </p>

      <div class="questions">
        <!-- Question 1 -->
        <div class="question">
          <p>Question 1: Was the purpose of the program/event explained clearly? <span class="required">*</span></p>
          <div class="scale">
            <label><input type="radio" name="q1" value="1"><span><div class="circle">1</div>Strongly Disagree</span></label>
            <label><input type="radio" name="q1" value="2"><span><div class="circle">2</div>Disagree</span></label>
            <label><input type="radio" name="q1" value="3"><span><div class="circle">3</div>Neutral</span></label>
            <label><input type="radio" name="q1" value="4"><span><div class="circle">4</div>Agree</span></label>
            <label><input type="radio" name="q1" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
          </div>
        </div>

        <!-- Question 2 -->
        <div class="question">
          <p>Question 2: Was the time given for the program/event enough? <span class="required">*</span></p>
          <div class="scale">
            <label><input type="radio" name="q2" value="1"><span><div class="circle">1</div>Strongly Disagree</span></label>
            <label><input type="radio" name="q2" value="2"><span><div class="circle">2</div>Disagree</span></label>
            <label><input type="radio" name="q2" value="3"><span><div class="circle">3</div>Neutral</span></label>
            <label><input type="radio" name="q2" value="4"><span><div class="circle">4</div>Agree</span></label>
            <label><input type="radio" name="q2" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
          </div>
        </div>

        <!-- Question 3 -->
        <div class="question">
          <p>Question 3: Were you able to join and participate in the activities? <span class="required">*</span></p>
          <div class="scale">
            <label><input type="radio" name="q3" value="1"><span><div class="circle">1</div>Strongly Disagree</span></label>
            <label><input type="radio" name="q3" value="2"><span><div class="circle">2</div>Disagree</span></label>
            <label><input type="radio" name="q3" value="3"><span><div class="circle">3</div>Neutral</span></label>
            <label><input type="radio" name="q3" value="4"><span><div class="circle">4</div>Agree</span></label>
            <label><input type="radio" name="q3" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
          </div>
        </div>

        <!-- Question 4 -->
        <div class="question">
          <p>Question 4: Did you learn something new from this program/event? <span class="required">*</span></p>
          <div class="scale">
            <label><input type="radio" name="q4" value="1"><span><div class="circle">1</div>Strongly Disagree</span></label>
            <label><input type="radio" name="q4" value="2"><span><div class="circle">2</div>Disagree</span></label>
            <label><input type="radio" name="q4" value="3"><span><div class="circle">3</div>Neutral</span></label>
            <label><input type="radio" name="q4" value="4"><span><div class="circle">4</div>Agree</span></label>
            <label><input type="radio" name="q4" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
          </div>
        </div>

        <!-- Question 5 -->
        <div class="question">
          <p>Question 5: Did the SK officials/facilitators treat all participants fairly and equally? <span class="required">*</span></p>
          <div class="scale">
            <label><input type="radio" name="q5" value="1"><span><div class="circle">1</div>Strongly Disagree</span></label>
            <label><input type="radio" name="q5" value="2"><span><div class="circle">2</div>Disagree</span></label>
            <label><input type="radio" name="q5" value="3"><span><div class="circle">3</div>Neutral</span></label>
            <label><input type="radio" name="q5" value="4"><span><div class="circle">4</div>Agree</span></label>
            <label><input type="radio" name="q5" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
          </div>
        </div>

        <!-- Question 6 -->
        <div class="question">
          <p>Question 6: Did the SK officials/facilitators show enthusiasm and commitment in leading the program/event? <span class="required">*</span></p>
          <div class="scale">
            <label><input type="radio" name="q6" value="1"><span><div class="circle">1</div>Strongly Disagree</span></label>
            <label><input type="radio" name="q6" value="2"><span><div class="circle">2</div>Disagree</span></label>
            <label><input type="radio" name="q6" value="3"><span><div class="circle">3</div>Neutral</span></label>
            <label><input type="radio" name="q6" value="4"><span><div class="circle">4</div>Agree</span></label>
            <label><input type="radio" name="q6" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
          </div>
        </div>

        <!-- Question 7 -->
        <div class="question">
          <p>Question 7: Overall, are you satisfied with this program/event? <span class="required">*</span></p>
          <div class="scale">
            <label><input type="radio" name="q7" value="1"><span><div class="circle">1</div>Strongly Disagree</span></label>
            <label><input type="radio" name="q7" value="2"><span><div class="circle">2</div>Disagree</span></label>
            <label><input type="radio" name="q7" value="3"><span><div class="circle">3</div>Neutral</span></label>
            <label><input type="radio" name="q7" value="4"><span><div class="circle">4</div>Agree</span></label>
            <label><input type="radio" name="q7" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
          </div>
        </div>
      </div>

      <!-- Pagination & Next -->
      <div class="pagination">
        <span class="dot active"></span>
        <span class="dot"></span>
      </div>
      <div class="actions">
        <button class="next-btn">Next</button>
      </div>
    </div>

    <!-- Page 2: Comments -->
    <div id="page2" class="page" style="display:none;">
      <h2>Comments</h2>
      <p class="instruction">
        Please share your comments and suggestions to help us improve our services and upcoming events.
      </p>
      <textarea class="comment-box"></textarea>
      <p class="instruction">
        Once you have submitted this evaluation, your certificate for this event will be available in your profile. Thank you!
      </p>

      <div class="pagination">
        <span class="dot active"></span>
        <span class="dot active"></span>
      </div>

      <div class="actions">
        <button class="submit-btn">Submit</button>
      </div>
    </div>
  </div>
</div>

<div id="successModal" class="modal">
  <div class="modal-content success">
    <div class="check-circle">
      <i class="fa-solid fa-check"></i>
    </div>
    <h2>Submitted</h2>
    <p>Your evaluation has been submitted successfully. <br>Kindly wait for your certificate. Thank you.</p>
    <button class="ok-btn">OK</button>
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

  // ================= Calendar =================
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
    header.textContent = middleDay.toLocaleDateString("en-US",{month:"long",year:"numeric"});

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

      const dateStr = `${thisDay.getFullYear()}-${String(thisDay.getMonth()+1).padStart(2,'0')}-${String(thisDay.getDate()).padStart(2,'0')}`;
      if (holidays.includes(dateStr)) dateEl.classList.add('holiday');
      if (thisDay.toDateString() === today.toDateString()) dayEl.classList.add("active");

      dayEl.append(weekdayEl, dateEl);
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


  // ================= Evaluation Modal =================
  const evaluationModal = document.getElementById("evaluationModal");
  const successModal = document.getElementById("successModal");

  const closeBtn = evaluationModal?.querySelector(".close-btn");
  const nextBtn = evaluationModal?.querySelector(".next-btn");
  const submitBtn = evaluationModal?.querySelector(".submit-btn");
  const okBtn = successModal?.querySelector(".ok-btn");

  const page1 = document.getElementById("page1");
  const page2 = document.getElementById("page2");

  const page1Dots = page1?.querySelectorAll(".pagination .dot");
  const page2Dots = page2?.querySelectorAll(".pagination .dot");

  const evaluatedButtons = new Set(); // Track done buttons

  function updateDots(dots, activeIndexes) {
    dots?.forEach((dot, i) => {
      dot.classList.toggle("active", activeIndexes.includes(i));
    });
  }

  // Open modal & mark active button
  document.querySelectorAll(".evaluate-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      if (!evaluationModal) return;
      evaluationModal.style.display = "flex";
      page1.style.display = "block";
      page2.style.display = "none";
      updateDots(page1Dots, [0]); 

      document.querySelectorAll(".evaluate-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });

  closeBtn?.addEventListener("click", () => {
    evaluationModal.style.display = "none";
  });

  // Next button
  nextBtn?.addEventListener("click", () => {
    const allBtns = page1.querySelectorAll(".evaluate-btn");
    let incompleteFound = false;

    allBtns.forEach(btn => {
      if (!evaluatedButtons.has(btn)) {
        incompleteFound = true;
        btn.style.border = "2px solid red";
      } else {
        btn.style.border = "";
      }
    });

    if (incompleteFound) {
      alert("Please evaluate all required items before proceeding!");
      return;
    }

    // All done → next page
    page1.style.display = "none";
    page2.style.display = "block";
    updateDots(page2Dots, [0,1]);
  });

  // Submit button
  submitBtn?.addEventListener("click", () => {
    evaluationModal.style.display = "none";
    successModal.style.display = "flex";

    const lastClickedBtn = document.querySelector(".evaluate-btn.active");
    if (lastClickedBtn) {
      lastClickedBtn.classList.add("done");
      lastClickedBtn.innerHTML = `<i class="fa-solid fa-check"></i> Done`;
      lastClickedBtn.classList.remove("active");

      const card = lastClickedBtn.closest(".program-card");
      const statusDot = card?.querySelector(".status-dot");
      if (statusDot) statusDot.classList.add("hidden");

      evaluatedButtons.add(lastClickedBtn); // Mark as evaluated
    }
  });

  okBtn?.addEventListener("click", () => {
    successModal.style.display = "none";
  });

  window.addEventListener("click", e => {
    if (e.target === evaluationModal) evaluationModal.style.display = "none";
    if (e.target === successModal) successModal.style.display = "none";
  });

});
</script>
