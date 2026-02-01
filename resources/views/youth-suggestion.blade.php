<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Youth Suggestions</title>
  <link rel="stylesheet" href="{{ asset('css/youth-suggestion.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

      <a href="{{ route('youth-suggestion') }}" class="active">
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
      <button id="mobileMenuBtn" class="mobile-hamburger">
        <i data-lucide="menu"></i>
      </button>
      <div class="logo">
        <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="Logo">
        <div class="logo-text">
          <span class="title">Katibayan</span>
          <span class="subtitle">Web Portal</span>
        </div>
      </div>

      <div class="topbar-right">
        <div class="time">MON 10:00 <span>AM</span></div>

        <button class="theme-toggle" id="themeToggle">
          <i data-lucide="moon"></i>
        </button>

        <!-- Notifications -->
        <div class="notification-wrapper">
          <i class="fas fa-bell"></i>
          @if($totalNotificationCount > 0)
            <span class="notif-count">{{ $totalNotificationCount }}</span>
          @endif
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notification</strong>
              @if($totalNotificationCount > 0)
                <span>{{ $totalNotificationCount }}</span>
              @endif
            </div>
            
            <ul class="notif-list">
              {{-- General Notifications --}}
              @foreach ($generalNotifications as $notif)
                @php
                  $link = '#'; // Default
                  $onclickAction = ''; // Default na walang click action
                  
                  // --- Para sa Links ---
                  if ($notif->type == 'certificate_schedule') {
                    $link = route('certificatepage'); 
                  } 
                  // FIX: Check kung SK Request Approved (galing sa Controller mo)
                  elseif ($notif->type == 'sk_request_approved' || $notif->type == 'App\Notifications\SkRequestAccepted') { 
                    $link = '#'; 
                    // Dito tinatawag ang Javascript function para sa Modal
                    $onclickAction = 'openSetRoleModal(); return false;';
                  }

                  // --- Para sa Title at Message ---
                  $title = $notif->data['title'] ?? $notif->title ?? 'Notification';
                  $message = $notif->data['message'] ?? $notif->message ?? 'You have a new notification.';
                @endphp
                
                <li>
                  <a href="{{ $link }}" 
                    class="notif-link {{ $notif->is_read == 0 ? 'unread' : '' }}" 
                    data-id="{{ $notif->id }}"
                    @if($onclickAction) onclick="{{ $onclickAction }}" @endif>
                    
                    <div class="notif-dot-container">
                      @if ($notif->is_read == 0)
                        <span class="notif-dot"></span>
                      @else
                        <span class="notif-dot-placeholder"></span>
                      @endif
                    </div>

                    <div class="notif-main-content">
                      <div class="notif-header-line">
                        <strong>{{ $title }}</strong>
                        <span class="notif-timestamp">
                          {{ $notif->created_at->format('m/d/Y g:i A') }}
                        </span>
                      </div>
                      <p class="notif-message">{{ $message }}</p>
                    </div>
                  </a>
                </li>
              @endforeach

              {{-- Evaluation Notifications --}}
              @foreach($unevaluatedActivities as $activity)
                <li>
                  <a href="{{ route('evaluation.show', $activity['id']) }}" class="notif-link unread" 
                    data-{{ $activity['type'] }}-id="{{ $activity['id'] }}">
                    
                    <div class="notif-dot-container">
                      <span class="notif-dot"></span>
                    </div>
                    
                    <div class="notif-main-content">
                      <div class="notif-header-line">
                        <strong>{{ ucfirst($activity['type']) }} Evaluation Required</strong>
                        <span class="notif-timestamp">
                          {{ $activity['created_at']->format('m/d/Y g:i A') }}
                        </span>
                      </div>
                      <p class="notif-message">Please evaluate "{{ $activity['title'] }}"</p>
                    </div>
                  </a>
                </li>
              @endforeach

              @if($generalNotifications->isEmpty() && $unevaluatedActivities->isEmpty())
                <li class="no-notifications">
                  <p>No new notifications</p>
                </li>
              @endif
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
                
                <div class="badges-wrapper">
                  <div class="profile-badge">
                    <span class="badge">{{ $roleBadge }}</span>
                    <span class="badge">{{ $age }} yrs old</span>
                  </div>
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
              <li>
                <a href="{{ route('faqspage') }}">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>
              <li>
                <a href="#" id="openFeedbackBtn">
                  <i class="fas fa-star"></i> Send Feedback to Katibayan
                </a>
              </li>
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

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal-overlay">
      <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h2>Send us feedback</h2>
        <p>Help us improve by sharing your thoughts, suggestions, and experiences with our service.</p>

        <h3>Enjoying it? Rate us!</h3>
        <div class="star-rating" id="starRating">
          <i class="far fa-star" data-value="1"></i>
          <i class="far fa-star" data-value="2"></i>
          <i class="far fa-star" data-value="3"></i>
          <i class="far fa-star" data-value="4"></i>
          <i class="far fa-star" data-value="5"></i>
        </div>

        <form id="feedbackForm" action="{{ route('feedback.submit') }}" method="POST">
          @csrf
          
          <label for="type">Feedback Type</label>
          
          <div class="custom-select-wrapper" id="customSelect">
            <div class="custom-select-trigger">
              <span id="selectedFeedbackType">Select feedback type</span>
              <div class="custom-arrow"></div>
            </div>
            
            <div class="custom-options-list">
              <div class="custom-option" data-value="suggestion">
                <span class="dot suggestion"></span> Suggestion
              </div>
              <div class="custom-option" data-value="bug">
                <span class="dot bug"></span> Bug or Issue
              </div>
              <div class="custom-option" data-value="appreciation">
                <span class="dot appreciation"></span> Appreciation
              </div>
              <div class="custom-option" data-value="others">
                <span class="dot others"></span> Others
              </div>
            </div>
            
            <select id="type" name="type" required style="display: none;">
              <option value="" disabled selected>Select feedback type</option>
              <option value="suggestion">Suggestion</option>
              <option value="bug">Bug or Issue</option>
              <option value="appreciation">Appreciation</option>
              <option value="others">Others</option>
            </select>
          </div>
          
          <label for="message">Your message</label>
          <textarea id="message" name="message" rows="5" placeholder="Share your thoughts with us..."></textarea>

          <input type="hidden" name="rating" id="ratingInput">
          
          <div class="form-actions">
            <button type="submit" class="submit-btn">Submit</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success Modal -->
    <div id="successFeedbackModal" class="modal-overlay simple-alert-modal">
      <div class="modal-content">
        <div class="success-icon">
          <i class="fas fa-check"></i>
        </div>
        <h2>Submitted</h2>
        <p>Thank you for your feedback! Your thoughts help us improve.</p>
        <button id="closeSuccessModal" class="ok-btn">OK</button>
      </div>
    </div>

    <!-- Suggestions Header -->
    <div class="suggestions-card">
      <h2>Youth Suggestions</h2>
      <div class="total-suggestions">
        Total suggestions <span id="totalSuggestions">0</span>
      </div>
    </div>

    <!-- Overview of Suggestions -->
    <div class="overview-card">
      <h3 class="overview-title">Overview of Suggestions<br>by Type</h3>
      <div class="chart-container">
        <canvas id="suggestionChart"></canvas>
      </div>
    </div>

    <!-- Suggestions List -->
    <div class="suggestions-section">
      <h3 class="suggestions-title">Suggestions List</h3>

      <div class="suggestions-subheader">
        <h4 class="group-title" id="currentGroupTitle">This month</h4>

        <div class="filters">
          <!-- Month Dropdown -->
          <div class="custom-dropdown" data-type="month">
            <div class="dropdown-selected">
              <span>This month</span>
              <div class="icon-circle">
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <ul class="dropdown-options">
              <li data-value="all">All</li>
              <li data-value="this">This month</li>
              <li data-value="last">Last month</li>
            </ul>
          </div>

          <label class="filter-label">Suggestion Type:</label>

          <!-- Type Dropdown -->
          <div class="custom-dropdown" data-type="type">
            <div class="dropdown-selected">
              <span>All</span>
              <div class="icon-circle">
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <ul class="dropdown-options">
              <li data-value="all">All</li>
              <li data-value="event">Events</li>
              <li data-value="program">Program</li>
              <li data-value="others">Others</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Suggestions Container -->
      <div id="suggestionsContainer">
        <!-- Suggestions will be loaded here dynamically -->
        <div class="loading-message">Loading suggestions...</div>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", () => {
    // === Lucide icons + sidebar toggle ===
    lucide.createIcons();
    
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');

    if (menuToggle && sidebar) {
      menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');
      });
    }

    // Mobile hamburger menu
    if (mobileMenuBtn) {
      mobileMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');
        document.body.classList.toggle('mobile-sidebar-active');
      });
    }

    // Close sidebar when clicking outside (mobile only)
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 768 &&
        sidebar.classList.contains('open') &&
        !sidebar.contains(e.target) &&
        !mobileMenuBtn.contains(e.target)) {
        
        sidebar.classList.remove('open');
        document.body.classList.remove('mobile-sidebar-active');
      }
    });

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

    // === Theme Toggle ===
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    
    // Load saved theme
    const savedTheme = localStorage.getItem('theme') === 'dark';
    applyTheme(savedTheme);
    
    if (themeToggle) {
      themeToggle.addEventListener('click', () => {
        const isDark = !body.classList.contains('dark-mode');
        applyTheme(isDark);
      });
    }
    
    function applyTheme(isDark) {
      body.classList.toggle('dark-mode', isDark);
      const icon = isDark ? 'sun' : 'moon';
      themeToggle.innerHTML = `<i data-lucide="${icon}"></i>`;
      lucide.createIcons();
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
    }

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

    // === Feedback Modal ===
    const feedbackBtn = document.getElementById('openFeedbackBtn');
    const feedbackModal = document.getElementById('feedbackModal');
    const closeFeedbackBtn = document.getElementById('closeModal');
    const feedbackStars = document.querySelectorAll('#starRating i');
    const feedbackRatingInput = document.getElementById('ratingInput');
    const feedbackForm = document.getElementById('feedbackForm');
    const successFeedbackModal = document.getElementById('successFeedbackModal');
    const closeSuccessBtn = document.getElementById('closeSuccessModal');

    // Open feedback modal
    if (feedbackBtn) {
      feedbackBtn.addEventListener('click', (e) => {
        e.preventDefault();
        feedbackModal.style.display = 'flex';
      });
    }

    // Close feedback modal
    if (closeFeedbackBtn) {
      closeFeedbackBtn.addEventListener('click', () => {
        feedbackModal.style.display = 'none';
      });
    }

    // Close when clicking outside
    window.addEventListener('click', (e) => {
      if (e.target === feedbackModal) {
        feedbackModal.style.display = 'none';
      }
      if (e.target === successFeedbackModal) {
        successFeedbackModal.style.display = 'none';
      }
    });

    // Star rating system
    feedbackStars.forEach(star => {
      star.addEventListener('click', () => {
        const rating = star.getAttribute('data-value');
        if (feedbackRatingInput) feedbackRatingInput.value = rating;

        feedbackStars.forEach(s => {
          s.classList.remove('fas');
          s.classList.add('far');
        });
        for (let i = 0; i < rating; i++) {
          feedbackStars[i].classList.remove('far');
          feedbackStars[i].classList.add('fas');
        }
      });
    });

    // Feedback form submission
    if (feedbackForm) {
      feedbackForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(feedbackForm);
        const submitBtn = feedbackForm.querySelector('.submit-btn');
        const submitButtonText = submitBtn.textContent;

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Submitting...';
        }

        fetch(feedbackForm.action, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': formData.get('_token'),
              'Accept': 'application/json'
            },
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              feedbackModal.style.display = 'none';
              if (successFeedbackModal) successFeedbackModal.style.display = 'flex';
            } else {
              let errorMsg = data.message || 'Submission failed.';
              if (data.errors) {
                errorMsg += '\n' + Object.values(data.errors).join('\n');
              }
              throw new Error(errorMsg);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred. Please try again.');
          })
          .finally(() => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = submitButtonText;
            }

            feedbackForm.reset();
            feedbackStars.forEach(s => {
              s.classList.remove('fas');
              s.classList.add('far');
            });
            if (feedbackRatingInput) feedbackRatingInput.value = '';

            const selectedText = document.getElementById('selectedFeedbackType');
            const customSelect = document.getElementById('customSelect');
            const trigger = customSelect?.querySelector('.custom-select-trigger');
            const realSelect = document.getElementById('type');
            if (selectedText) selectedText.textContent = 'Select feedback type';
            trigger?.classList.remove('selected');
            if (realSelect) realSelect.value = '';
          });
      });
    }

    // Close success modal
    if (closeSuccessBtn) {
      closeSuccessBtn.addEventListener('click', () => {
        if (successFeedbackModal) successFeedbackModal.style.display = 'none';
      });
    }

    // Custom select functionality for feedback modal
    const customSelect = document.getElementById('customSelect');
    if (customSelect) {
      const trigger = customSelect.querySelector('.custom-select-trigger');
      const selectedText = document.getElementById('selectedFeedbackType');
      const optionsList = customSelect.querySelector('.custom-options-list');
      const options = customSelect.querySelectorAll('.custom-option');
      const realSelect = document.getElementById('type');

      // Toggle dropdown
      trigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        customSelect.classList.toggle('open');
      });

      // Handle option click
      options?.forEach(option => {
        option.addEventListener('click', () => {
          const value = option.getAttribute('data-value');
          const text = option.textContent.trim();

          if (selectedText) selectedText.textContent = text;
          if (realSelect) realSelect.value = value;
          trigger?.classList.add('selected');

          customSelect.classList.remove('open');
        });
      });

      // Close custom select on outside click
      document.addEventListener('click', () => {
        customSelect.classList.remove('open');
      });
    }

    // === Chart Initialization ===
    let suggestionChart;
    function initializeChart() {
      const ctx = document.getElementById('suggestionChart').getContext('2d');
      suggestionChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Events', 'Program', 'Others'],
          datasets: [{
            data: [0, 0, 0],
            backgroundColor: [
              'rgba(253, 220, 108, 0.9)',
              'rgba(173, 216, 230, 0.9)',
              'rgba(200, 220, 200, 0.9)'
            ],
            borderRadius: 6,
            barThickness: 50
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false // Removed the legend
            }
          },
          scales: {
            x: {
              ticks: { 
                color: '#2b3a55', 
                font: { 
                  weight: '600',
                  size: 14 
                } 
              },
              grid: { display: false }
            },
            y: {
              beginAtZero: true,
              ticks: { 
                color: '#2b3a55',
                font: {
                  size: 12
                }
              },
              grid: { color: '#e0e0e0' }
            }
          }
        }
      });
    }

    // === Suggestion Data Management ===
    let allSuggestions = [];
    let currentMonth = "this";
    let currentType = "all";

    // Function to fetch suggestions from the same barangay
    async function fetchSuggestions() {
      try {
        const response = await fetch('/sk-suggestions', {
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          }
        });

        if (response.ok) {
          const data = await response.json();
          allSuggestions = data.suggestions || [];
          updateUI();
        } else {
          console.error('Failed to fetch suggestions');
        }
      } catch (error) {
        console.error('Error fetching suggestions:', error);
      }
    }

    // Function to update chart data
    function updateChartData() {
      if (!suggestionChart) return;

      const typeCounts = {
        'event': 0,
        'program': 0,
        'others': 0
      };

      allSuggestions.forEach(suggestion => {
        if (typeCounts.hasOwnProperty(suggestion.committee)) {
          typeCounts[suggestion.committee]++;
        }
      });

      suggestionChart.data.datasets[0].data = [
        typeCounts.event,
        typeCounts.program,
        typeCounts.others
      ];

      suggestionChart.update();
    }

    // Function to format committee name for display
    function formatCommittee(committee) {
      const committeeNames = {
        'event': 'Events',
        'program': 'Program',
        'others': 'Others'
      };
      return committeeNames[committee] || committee;
    }

    // Function to format date
    function formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        month: '2-digit',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }

    // Function to check if suggestion is from this month
    function isThisMonth(dateString) {
      const date = new Date(dateString);
      const now = new Date();
      return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
    }

    // Function to check if suggestion is from last month
    function isLastMonth(dateString) {
      const date = new Date(dateString);
      const now = new Date();
      const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
      return date.getMonth() === lastMonth.getMonth() && date.getFullYear() === lastMonth.getFullYear();
    }

    // Function to render suggestions
    function renderSuggestions() {
      const container = document.getElementById('suggestionsContainer');
      
      if (allSuggestions.length === 0) {
        container.innerHTML = '<div class="no-suggestions">No suggestions found for your barangay.</div>';
        return;
      }

      // Filter suggestions based on current filters
      const filteredSuggestions = allSuggestions.filter(suggestion => {
        const monthMatch = currentMonth === 'all' || 
                          (currentMonth === 'this' && isThisMonth(suggestion.created_at)) ||
                          (currentMonth === 'last' && isLastMonth(suggestion.created_at));
        
        const typeMatch = currentType === 'all' || suggestion.committee === currentType;
        
        return monthMatch && typeMatch;
      });

      // Update total count
      document.getElementById('totalSuggestions').textContent = filteredSuggestions.length;

      if (filteredSuggestions.length === 0) {
        container.innerHTML = '<div class="no-suggestions">No suggestions match the current filters.</div>';
        return;
      }

      // Group by month
      const thisMonthSuggestions = filteredSuggestions.filter(s => isThisMonth(s.created_at));
      const lastMonthSuggestions = filteredSuggestions.filter(s => isLastMonth(s.created_at));

      let html = '';

      // This month group
      if (thisMonthSuggestions.length > 0) {
        html += `<div class="suggestions-group" data-month="this">`;
        thisMonthSuggestions.forEach(suggestion => {
          html += createSuggestionCard(suggestion);
        });
        html += `</div>`;
      }

      // Last month group
      if (lastMonthSuggestions.length > 0) {
        html += `<div class="suggestions-group" data-month="last">`;
        lastMonthSuggestions.forEach(suggestion => {
          html += createSuggestionCard(suggestion);
        });
        html += `</div>`;
      }

      container.innerHTML = html;
    }

    // Function to create suggestion card HTML
    function createSuggestionCard(suggestion) {
      const typeClass = suggestion.committee;
      const typeName = formatCommittee(suggestion.committee);
      
      // Check if anonymous
      const isAnonymous = suggestion.is_anonymous || !suggestion.user;
      
      let userName, userInitials, userColor;
      
      if (isAnonymous) {
        userName = 'Anonymous User';
        userInitials = 'AN';
        userColor = '#808080'; // Gray for anonymous
      } else if (suggestion.user) {
        userName = `${suggestion.user.given_name || ''} ${suggestion.user.middle_name || ''} ${suggestion.user.last_name || ''} ${suggestion.user.suffix || ''}`.trim();
        userInitials = `${suggestion.user.given_name?.charAt(0) || ''}${suggestion.user.last_name?.charAt(0) || ''}`;
        
        // Generate a consistent color based on user ID
        const colors = [
          '#3C87C4', '#4CAF50', '#FF9800', '#9C27B0', 
          '#2196F3', '#FF5722', '#009688', '#795548'
        ];
        const userId = suggestion.user.id || 0;
        userColor = colors[userId % colors.length];
      } else {
        userName = 'Unknown User';
        userInitials = 'UU';
        userColor = '#3C87C4';
      }
      
      const suggestionId = `#KK${suggestion.id.toString().padStart(8, '0')}`;
      const avatarStyle = `background-color: ${userColor}; color: white;`;

      return `
        <div class="suggestion-card" data-category="${suggestion.committee}">
          <div class="suggestion-avatar" style="${avatarStyle}">${userInitials}</div>
          <div class="suggestion-content">
            <h5>${userName} <span class="suggestion-id">${suggestionId}</span></h5>
            <p>${suggestion.suggestions}</p>
            <div class="suggestion-footer">
              <span class="badge ${typeClass}">${typeName}</span>
              <span class="date">${formatDate(suggestion.created_at)}</span>
            </div>
          </div>
        </div>
      `;
    }

    // Function to update the entire UI
    function updateUI() {
      updateChartData();
      renderSuggestions();
      updateGroupTitle();
    }

    // Function to update group title based on filters
    function updateGroupTitle() {
      const titleEl = document.getElementById('currentGroupTitle');
      if (currentMonth === 'all') {
        titleEl.textContent = 'All suggestions';
      } else if (currentMonth === 'this') {
        titleEl.textContent = 'This month';
      } else if (currentMonth === 'last') {
        titleEl.textContent = 'Last month';
      }
    }

    // === Custom Dropdown ===
    const dropdowns = document.querySelectorAll(".custom-dropdown");

    // Function to close all dropdowns except the currently open one
    function closeAllDropdowns(except = null) {
      dropdowns.forEach(dropdown => {
        const options = dropdown.querySelector(".dropdown-options");
        const icon = dropdown.querySelector(".icon-circle");
        if (!except || dropdown !== except) {
          options.classList.remove("active");
          if (icon) icon.classList.remove("rotate");
        }
      });
    }

    // Initialize each dropdown
    dropdowns.forEach(dropdown => {
      const selected = dropdown.querySelector(".dropdown-selected");
      const optionsList = dropdown.querySelector(".dropdown-options");
      const options = optionsList.querySelectorAll("li");
      const icon = selected.querySelector(".icon-circle");
      const type = dropdown.dataset.type;

      // Toggle dropdown open/close
      selected.addEventListener("click", (e) => {
        e.stopPropagation();
        closeAllDropdowns(dropdown);

        optionsList.classList.toggle("active");
        if (icon) icon.classList.toggle("rotate");
      });

      // Select an option
      options.forEach(option => {
        option.addEventListener("click", () => {
          selected.querySelector("span").textContent = option.textContent;
          optionsList.classList.remove("active");
          if (icon) icon.classList.remove("rotate");

          // Update current filters
          if (type === "month") {
            currentMonth = option.dataset.value;
          } else if (type === "type") {
            currentType = option.dataset.value;
          }

          // Apply filter and update UI
          updateUI();
        });
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", () => closeAllDropdowns());

    // Initialize the application
    initializeChart();
    fetchSuggestions();

    // Set up periodic refresh (every 30 seconds)
    setInterval(fetchSuggestions, 30000);
    
    // === Logout Confirmation ===
    window.confirmLogout = function(event) {
      event.preventDefault();
      if (confirm('Are you sure you want to logout?')) {
        document.getElementById('logout-form').submit();
      }
    };
  });
  </script>
</body>
</html>