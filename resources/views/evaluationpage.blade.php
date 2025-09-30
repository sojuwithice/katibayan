<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Evaluation</title>
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
      <a href="{{ route('dashboard.index') }}">
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

      <a href="{{ route('eventpage') }}">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <a href="#">
        <i data-lucide="megaphone"></i>
        <span class="label">Announcements</span>
      </a>

      <a href="{{ route('evaluation') }}" class="active">
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
                <h4>{{ $user->given_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}</h4>
                <div class="profile-badge">
                  <span class="badge">{{ $roleBadge ?? 'GUEST' }}</span>
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

      @php
        // Safely handle the attendedEvents variable
        $attendedEvents = $attendedEvents ?? collect();
        
        // Filter events that haven't been evaluated yet
        $eventsToEvaluate = $attendedEvents->filter(function($event) {
            // Check if evaluations relationship exists and filter
            return !($event->evaluations && $event->evaluations->where('user_id', Auth::id())->count());
        });
      @endphp

      <p class="programs-note">
        You have <span>{{ $eventsToEvaluate->count() }} programs</span> to evaluate. Please evaluate them now.
      </p>

      <div class="program-list">
        @if($eventsToEvaluate->count() > 0)
          @foreach($eventsToEvaluate as $event)
            <div class="program-card" data-event-id="{{ $event->id }}">
              <div class="program-img-wrapper">
                @if($event->image && Storage::disk('public')->exists($event->image))
                  <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="program-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                @else
                  <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image" class="program-img">
                @endif
                <span class="status-dot"></span>
              </div>
              <div class="program-info">
                <h3>{{ $event->title }}</h3>
                <p class="date">{{ $event->event_date->format('F d, Y') }} | {{ $event->formatted_time ?? 'Time not specified' }}</p>
                <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
              </div>
              <button class="evaluate-btn" data-event-id="{{ $event->id }}">Evaluate</button>
            </div>
          @endforeach
        @else
          <div class="no-events-message">
            <i class="fas fa-calendar-check"></i>
            <p>No events to evaluate at the moment.</p>
            <p class="sub-message">
              @if($attendedEvents->count() > 0)
                You have attended events, but they may have already been evaluated.
              @else
                Attend events to see them here for evaluation.
              @endif
            </p>
          </div>
        @endif
      </div>

      <!-- Already Evaluated Section -->
      @php
        $evaluatedEvents = $attendedEvents->filter(function($event) {
            return $event->evaluations && $event->evaluations->where('user_id', Auth::id())->count();
        });
      @endphp

      @if($evaluatedEvents->count() > 0)
        <div class="evaluated-section">
          <h3>Already Evaluated ({{ $evaluatedEvents->count() }})</h3>
          <div class="program-list evaluated">
            @foreach($evaluatedEvents as $event)
              <div class="program-card evaluated">
                <div class="program-img-wrapper">
                  @if($event->image && Storage::disk('public')->exists($event->image))
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="program-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4='">
                  @else
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FdmVudCBJbWFnZTwvdGV4dD48L3N2Zz4=" alt="Event Image" class="program-img">
                  @endif
                  <span class="status-dot completed"><i class="fas fa-check"></i></span>
                </div>
                <div class="program-info">
                  <h3>{{ $event->title }}</h3>
                  <p class="date">{{ $event->event_date->format('F d, Y') }} | {{ $event->formatted_time ?? 'Time not specified' }}</p>
                  <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                </div>
                <button class="evaluate-btn done" disabled>
                  <i class="fa-solid fa-check"></i> Evaluated
                </button>
              </div>
            @endforeach
          </div>
        </div>
      @endif
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
          <input type="text" class="event-input" id="modalEventName" readonly>

          <input type="hidden" id="currentEventId">

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
                <label><input type="radio" name="q1" value="1" required><span><div class="circle">1</div>Strongly Disagree</span></label>
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
                <label><input type="radio" name="q2" value="1" required><span><div class="circle">1</div>Strongly Disagree</span></label>
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
                <label><input type="radio" name="q3" value="1" required><span><div class="circle">1</div>Strongly Disagree</span></label>
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
                <label><input type="radio" name="q4" value="1" required><span><div class="circle">1</div>Strongly Disagree</span></label>
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
                <label><input type="radio" name="q5" value="1" required><span><div class="circle">1</div>Strongly Disagree</span></label>
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
                <label><input type="radio" name="q6" value="1" required><span><div class="circle">1</div>Strongly Disagree</span></label>
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
                <label><input type="radio" name="q7" value="1" required><span><div class="circle">1</div>Strongly Disagree</span></label>
                <label><input type="radio" name="q7" value="2"><span><div class="circle">2</div>Disagree</span></label>
                <label><input type="radio" name="q7" value="3"><span><div class="circle">3</div>Neutral</span></label>
                <label><input type="radio" name="q7" value="4"><span><div class="circle">4</div>Agree</span></label>
                <label><input type="radio" name="q7" value="5"><span><div class="circle">5</div>Strongly Agree</span></label>
              </div>
            </div>
          </div>

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
          <textarea class="comment-box" id="comments" placeholder="Enter your comments here..."></textarea>
          <p class="instruction">
            Once you have submitted this evaluation, your certificate for this event will be available in your profile. Thank you!
          </p>

          <div class="pagination">
            <span class="dot active"></span>
            <span class="dot active"></span>
          </div>

          <div class="actions">
            <button class="submit-btn">Submit Evaluation</button>
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

      // ================= Evaluation Modal =================
      const evaluationModal = document.getElementById("evaluationModal");
      const successModal = document.getElementById("successModal");
      const closeBtn = evaluationModal?.querySelector(".close-btn");
      const nextBtn = evaluationModal?.querySelector(".next-btn");
      const submitBtn = evaluationModal?.querySelector(".submit-btn");
      const okBtn = successModal?.querySelector(".ok-btn");
      const page1 = document.getElementById("page1");
      const page2 = document.getElementById("page2");
      const modalEventName = document.getElementById("modalEventName");
      const currentEventId = document.getElementById("currentEventId");
      const comments = document.getElementById("comments");

      let currentEvaluatingEventId = null;

      // Open modal with event data
      document.querySelectorAll(".evaluate-btn:not(.done)").forEach(btn => {
        btn.addEventListener("click", async () => {
          const eventId = btn.getAttribute("data-event-id");
          const eventCard = btn.closest(".program-card");
          const eventName = eventCard.querySelector("h3").textContent;
          
          currentEvaluatingEventId = eventId;
          currentEventId.value = eventId;
          modalEventName.value = eventName;

          // Reset form
          page1.style.display = "block";
          page2.style.display = "none";
          comments.value = "";
          document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.checked = false;
          });

          evaluationModal.style.display = "flex";
        });
      });

      closeBtn?.addEventListener("click", () => {
        evaluationModal.style.display = "none";
        currentEvaluatingEventId = null;
      });

      // Next button - validate required questions
      nextBtn?.addEventListener("click", () => {
        const allQuestions = page1.querySelectorAll('.question');
        let allAnswered = true;

        allQuestions.forEach(question => {
          const radios = question.querySelectorAll('input[type="radio"]');
          const answered = Array.from(radios).some(radio => radio.checked);
          if (!answered) {
            allAnswered = false;
            question.style.border = "2px solid #ff4444";
            question.style.padding = "10px";
            question.style.borderRadius = "5px";
          } else {
            question.style.border = "";
            question.style.padding = "";
          }
        });

        if (!allAnswered) {
          alert("Please answer all required questions before proceeding!");
          return;
        }

        page1.style.display = "none";
        page2.style.display = "block";
      });

      // Submit evaluation
      submitBtn?.addEventListener("click", async () => {
        if (!currentEvaluatingEventId) {
          alert("No event selected for evaluation.");
          return;
        }

        try {
          // Collect ratings
          const ratings = {};
          for (let i = 1; i <= 7; i++) {
            const radio = document.querySelector(`input[name="q${i}"]:checked`);
            if (radio) {
              ratings[`q${i}`] = parseInt(radio.value);
            }
          }

          // Submit evaluation
          const response = await fetch('/evaluation', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              event_id: currentEvaluatingEventId,
              ratings: ratings,
              comments: comments.value
            })
          });

          const result = await response.json();

          if (result.success) {
            evaluationModal.style.display = "none";
            successModal.style.display = "flex";

            // Update UI - mark as evaluated
            const evaluatedBtn = document.querySelector(`.evaluate-btn[data-event-id="${currentEvaluatingEventId}"]`);
            if (evaluatedBtn) {
              evaluatedBtn.innerHTML = '<i class="fa-solid fa-check"></i> Evaluated';
              evaluatedBtn.classList.add('done');
              evaluatedBtn.disabled = true;

              const statusDot = evaluatedBtn.closest('.program-card').querySelector('.status-dot');
              if (statusDot) {
                statusDot.classList.add('completed');
                statusDot.innerHTML = '<i class="fas fa-check"></i>';
              }
            }

            currentEvaluatingEventId = null;
          } else {
            alert(result.error || 'Failed to submit evaluation');
          }

        } catch (error) {
          console.error('Error submitting evaluation:', error);
          alert('Failed to submit evaluation. Please try again.');
        }
      });

      okBtn?.addEventListener("click", () => {
        successModal.style.display = "none";
        // Optionally reload the page to update the counts
        window.location.reload();
      });

      window.addEventListener("click", e => {
        if (e.target === evaluationModal) evaluationModal.style.display = "none";
        if (e.target === successModal) successModal.style.display = "none";
      });
    });
  </script>
</body>
</html>