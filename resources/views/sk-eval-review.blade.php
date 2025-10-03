<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Evaluation Review</title>
  <link rel="stylesheet" href="{{ asset('css/sk-eval-review.css') }}">
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

    <main class="container">
      <div class="evaluation-container">
        <!-- Header -->
        <div class="evaluation-header">
          <button class="back-btn" onclick="window.history.back()"><i class="fas fa-arrow-left"></i></button>
          <div>
            <h2>{{ $event->title ?? 'Event Title' }}</h2>
            <p class="event-details">Date: {{ $event->event_date->format('Y-m-d') ?? '2025-09-20' }} | Venue: {{ $event->location ?? 'Barangay Hall' }}</p>
          </div>
        </div>

        <!-- Tabs -->
        <div class="tab-buttons">
          <button class="tab-btn active" data-tab="rating">Rating</button>
          <button class="tab-btn" data-tab="comments">Comments</button>
        </div>

        <!-- ================== RATING TAB ================== -->
        <div id="rating" class="tab-content active">
          <!-- Stats -->
          <div class="stats">
            <div class="stat-card">
              <h3>Average Rating of this Event</h3>
              <div class="rating-score">{{ number_format($overallAverage ?? 4.5, 1) }} / 5</div>
              <small>Based on the <b class="highlight">{{ $totalEvaluations ?? 100 }} responses</b></small>
            </div>
            <div class="stat-card">
              <h3>Rating Distribution</h3>
              <canvas id="ratingChart"></canvas>
            </div>
          </div>

          <!-- Question Breakdown -->
          <div class="question-section">
            <h3>
              Question Breakdown
              <span class="see-respondents">See Respondents</span>
            </h3>

            @php
              function getQuestionText($number) {
                $questions = [
                  1 => 'Was the purpose of the program/event explained clearly?',
                  2 => 'Was the time given for the program/event enough?',
                  3 => 'Were you able to join and participate in the activities?',
                  4 => 'Did you learn something new from this program/event?',
                  5 => 'Did the SK officials/facilitators treat all participants fairly and equally?',
                  6 => 'Did the SK officials/facilitators show enthusiasm and commitment in leading the program/event?',
                  7 => 'Overall, are you satisfied with this program/event?'
                ];
                return $questions[$number] ?? 'Question not found';
              }
            @endphp

            @for($i = 1; $i <= 7; $i++)
              <div class="question-card">
                <div class="question-text">Question {{ $i }}: {{ getQuestionText($i) }}</div>
                <div class="rating">Rating: {{ number_format($averageRatings['q'.$i] ?? 4.5, 1) }}/5</div>
              </div>
            @endfor
          </div>
        </div>

        <!-- ================== COMMENTS TAB ================== -->
        <div id="comments" class="tab-content">
          <!-- Filters inside comments -->
          <div class="feedback-filters">
            <button class="active">All</button>
            <button>5 - Strongly Agree</button>
            <button>4 - Agree</button>
            <button>3 - Neutral</button>
            <button>2 - Disagree</button>
            <button>1 - Strongly Disagree</button>
          </div>

          <!-- Section Title -->
          <h3>Feedback from participants</h3>

          <!-- Comment Cards -->
          @if(isset($event) && $event->evaluations->count() > 0)
            @foreach($event->evaluations as $evaluation)
              @php
                $ratings = json_decode($evaluation->ratings, true);
                $overallRating = $ratings ? round(array_sum($ratings) / count($ratings)) : 0;
                $stars = str_repeat('★', $overallRating) . str_repeat('☆', 5 - $overallRating);
              @endphp
              
              <div class="feedback-card">
                <div class="feedback-left">
                  <img src="{{ $evaluation->user->avatar ? asset('storage/' . $evaluation->user->avatar) : 'https://i.pravatar.cc/60?img=' . $loop->index }}" alt="profile" />
                  <div>
                    <div class="name-stars">
                      <h4>{{ $evaluation->user->given_name ?? 'User' }} {{ $evaluation->user->last_name ?? '' }}</h4>
                      <div class="stars">{{ $stars }} <span>{{ $overallRating }}</span></div>
                    </div>
                    @if($evaluation->comments)
                      <p>{{ $evaluation->comments }}</p>
                    @else
                      <p class="no-comment">No additional comments provided.</p>
                    @endif
                  </div>
                </div>
                <div class="feedback-right">
                  <span>{{ $evaluation->submitted_at->format('m/d/Y g:i A') }}</span>
                </div>
              </div>
            @endforeach
          @else
            <!-- Sample data when no evaluations exist -->
            <div class="feedback-card">
              <div class="feedback-left">
                <img src="https://i.pravatar.cc/60?img=1" alt="profile" />
                <div>
                  <div class="name-stars">
                    <h4>Beverly J. Hills</h4>
                    <div class="stars">★★★★★ <span>5</span></div>
                  </div>
                  <p>I gained a lot of knowledge</p>
                </div>
              </div>
              <div class="feedback-right">
                <span>09/09/2025&nbsp;&nbsp;6:00 PM</span>
              </div>
            </div>

            <div class="feedback-card">
              <div class="feedback-left">
                <img src="https://i.pravatar.cc/60?img=2" alt="profile" />
                <div>
                  <div class="name-stars">
                    <h4>Beverly J. Hills</h4>
                    <div class="stars">★★★★★ <span>5</span></div>
                  </div>
                  <p>Goods and foods. Yess!</p>
                </div>
              </div>
              <div class="feedback-right">
                <span>09/09/2025&nbsp;&nbsp;6:00 PM</span>
              </div>
            </div>

            <div class="feedback-card">
              <div class="feedback-left">
                <img src="https://i.pravatar.cc/60?img=3" alt="profile" />
                <div>
                  <div class="name-stars">
                    <h4>Joey Y. Yes</h4>
                    <div class="stars">★★★★☆ <span>4</span></div>
                  </div>
                  <p>The program is great I hope sa sunod mas mahaba ang time pero overall it's good</p>
                </div>
              </div>
              <div class="feedback-right">
                <span>09/09/2025&nbsp;&nbsp;6:00 PM</span>
              </div>
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>

  <!-- Hidden input to pass PHP data to JavaScript -->
  <input type="hidden" id="ratingDistribution" value="{{ json_encode($ratingDistribution ?? [1 => 0, 2 => 2, 3 => 5, 4 => 15, 5 => 78]) }}">

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

      // ================= Tabs Switching =================
      const tabButtons = document.querySelectorAll(".tab-btn");
      const tabContents = document.querySelectorAll(".tab-content");

      tabButtons.forEach(btn => {
        btn.addEventListener("click", () => {
          tabButtons.forEach(b => b.classList.remove("active"));
          tabContents.forEach(c => c.classList.remove("active"));

          btn.classList.add("active");
          const tabId = btn.getAttribute("data-tab");
          document.getElementById(tabId).classList.add("active");
        });
      });

      // ================= Rating Distribution Chart =================
      const ctx = document.getElementById('ratingChart').getContext('2d');
      
      // Get rating data from hidden input
      const ratingDistributionInput = document.getElementById('ratingDistribution');
      
      // Default data as a JavaScript object
      let ratingData = {1: 0, 2: 2, 3: 5, 4: 15, 5: 78};
      
      if (ratingDistributionInput && ratingDistributionInput.value) {
        try {
          ratingData = JSON.parse(ratingDistributionInput.value);
        } catch (e) {
          console.error('Error parsing rating distribution:', e);
        }
      }
      
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['1: Strongly Disagree', '2: Disagree', '3: Neutral', '4: Agree', '5: Strongly Agree'],
          datasets: [{
            label: 'Responses',
            data: [
              ratingData[1] || 0,
              ratingData[2] || 0,
              ratingData[3] || 0,
              ratingData[4] || 0,
              ratingData[5] || 0
            ],
            backgroundColor: '#0C4B92',
            borderRadius: 6,
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { 
              display: true, 
              labels: { 
                color: "#01214A",
                font: {
                  family: "'Montserrat', sans-serif",
                  size: 12
                }
              } 
            }
          },
          scales: {
            x: { 
              ticks: { 
                color: "#4b5c77", 
                font: {
                  family: "'Montserrat', sans-serif",
                  size: 11
                }
              } 
            },
            y: { 
              beginAtZero: true, 
              ticks: { 
                stepSize: 25, 
                color: "#4b5c77",
                font: {
                  family: "'Montserrat', sans-serif",
                  size: 11
                }
              } 
            }
          }
        }
      });

      // ================= Feedback Filters =================
      const filterButtons = document.querySelectorAll('.feedback-filters button');
      
      filterButtons.forEach(button => {
        button.addEventListener('click', () => {
          filterButtons.forEach(btn => btn.classList.remove('active'));
          button.classList.add('active');
          // Add filtering logic here if needed
        });
      });

      // ================= Export Functionality =================
      // You can add export to PDF/Excel functionality here
      const seeRespondentsBtn = document.querySelector('.see-respondents');
      seeRespondentsBtn?.addEventListener('click', () => {
        // Implement see respondents functionality
        alert('Respondents list functionality would go here');
      });
    });
  </script>
</body>
</html>