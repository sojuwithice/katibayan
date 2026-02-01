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
  <!-- Sidebar -->
  <aside class="sidebar">
  <button class="menu-toggle">Menu</button>
  <div class="divider"></div>
  <nav class="nav">
    <a href="{{ route('sk.dashboard') }}">
      <i data-lucide="layout-dashboard"></i>
      <span class="label">Dashboard</span>
    </a>


    <a href="{{ route('youth-profilepage') }}">
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
        <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="Logo">
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
              <a href="{{ route('evaluation.respondents', ['event_id' => $event->id]) }}" class="see-respondents">See Respondents</a>
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
            <button class="filter-btn active" data-rating="all">All</button>
            <button class="filter-btn" data-rating="5">5 - Strongly Agree</button>
            <button class="filter-btn" data-rating="4">4 - Agree</button>
            <button class="filter-btn" data-rating="3">3 - Neutral</button>
            <button class="filter-btn" data-rating="2">2 - Disagree</button>
            <button class="filter-btn" data-rating="1">1 - Strongly Disagree</button>
          </div>

          <!-- Section Title -->
          <h3>Feedback from participants ({{ $event->evaluations->count() ?? 0 }})</h3>

          <!-- Comment Cards -->
          @if(isset($event) && $event->evaluations->count() > 0)
            @foreach($event->evaluations as $evaluation)
              @php
                $ratings = json_decode($evaluation->ratings, true);
                $overallRating = $ratings ? round(array_sum($ratings) / count($ratings)) : 0;
                $stars = str_repeat('★', $overallRating) . str_repeat('☆', 5 - $overallRating);
              @endphp
              
              <div class="feedback-card" data-rating="{{ $overallRating }}">
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
      const ctx = document.getElementById('ratingChart');
      
      if (ctx) {
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
      }

      // ================= Feedback Filters =================
      const filterButtons = document.querySelectorAll('.filter-btn');
      const feedbackCards = document.querySelectorAll('.feedback-card');
      
      filterButtons.forEach(button => {
        button.addEventListener('click', () => {
          filterButtons.forEach(btn => btn.classList.remove('active'));
          button.classList.add('active');
          
          const ratingFilter = button.getAttribute('data-rating');
          
          // Filter feedback cards
          feedbackCards.forEach(card => {
            if (ratingFilter === 'all') {
              card.style.display = 'flex';
            } else {
              const cardRating = card.getAttribute('data-rating');
              if (cardRating === ratingFilter) {
                card.style.display = 'flex';
              } else {
                card.style.display = 'none';
              }
            }
          });
        });
      });

      // ================= Export Functionality =================
      const seeRespondentsBtn = document.querySelector('.see-respondents');
      seeRespondentsBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        // Navigate to respondents page with event ID
        window.location.href = seeRespondentsBtn.href;
      });

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