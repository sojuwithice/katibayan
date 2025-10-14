<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Suggestion</title>
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
          <a href="{{ route('suggestionbox') }}">Suggestion Box</a>
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
          <span class="notif-count" id="notificationCount">0</span>
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notification</strong> <span id="dropdownNotificationCount">0</span>
            </div>
            <ul class="notif-list" id="notificationList">
              <!-- Notifications will be loaded here -->
            </ul>
          </div>
        </div>

        <!-- Profile Avatar -->
        @auth
        <div class="profile-wrapper">
          <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ Auth::user()->given_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }} {{ Auth::user()->suffix ?? '' }}</h4>
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
              <li class="logout-item">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
        @else
        <div class="profile-wrapper">
          <img src="{{ asset('images/default-avatar.png') }}" alt="User" class="avatar">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ asset('images/default-avatar.png') }}" alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>Guest User</h4>
                <div class="profile-badge">
                  <span class="badge">GUEST</span>
                </div>
              </div>
            </div>
            <hr>
            <ul class="profile-menu">
              <li>
                <a href="{{ route('login') }}">
                  <i class="fas fa-sign-in-alt"></i> Login
                </a>
              </li>
              <li>
                <a href="{{ route('faqspage') }}">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>
            </ul>
          </div>
        </div>
        @endauth
      </div>
    </header>

    <!-- Suggestions Header -->
    <div class="suggestions-card">
      <h2>Youth Suggestions</h2>
      <div class="total-suggestions">
        Total suggestions <span id="totalSuggestions">0</span>
      </div>
    </div>

    <!-- Overview of Suggestions -->
    <div class="overview-card">
      <h3 class="overview-title">Overview of Suggestions<br>by Committee</h3>
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

          <label class="filter-label">Committee:</label>

          <!-- Committee Dropdown -->
          <div class="custom-dropdown" data-type="committee">
            <div class="dropdown-selected">
              <span>All</span>
              <div class="icon-circle">
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <ul class="dropdown-options">
              <li data-value="all">All</li>
              <li data-value="active">Active Citizenship</li>
              <li data-value="economic">Economic Empowerment</li>
              <li data-value="education">Education</li>
              <li data-value="health">Health</li>
              <li data-value="sports">Sports</li>
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

    // === Chart Initialization ===
    let suggestionChart;
    function initializeChart() {
      const ctx = document.getElementById('suggestionChart').getContext('2d');
      suggestionChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Active Citizenship', 'Economic', 'Education', 'Health', 'Sports'],
          datasets: [{
            label: 'Suggestions',
            data: [0, 0, 0, 0, 0],
            backgroundColor: [
              'rgba(253, 220, 108, 0.9)',
              'rgba(200, 220, 200, 0.9)',
              'rgba(173, 216, 230, 0.9)',
              'rgba(240, 190, 220, 0.9)',
              'rgba(255, 200, 150, 0.9)'
            ],
            borderRadius: 6,
            barThickness: 50
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'right',
              labels: {
                color: '#2b3a55',
                font: { size: 13, weight: '600' }
              }
            }
          },
          scales: {
            x: {
              ticks: { color: '#2b3a55', font: { weight: '600' } },
              grid: { display: false }
            },
            y: {
              beginAtZero: true,
              ticks: { color: '#2b3a55' },
              grid: { color: '#e0e0e0' }
            }
          }
        }
      });
    }

    // === Suggestion Data Management ===
    let allSuggestions = [];
    let currentMonth = "this";
    let currentCommittee = "all";

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

      const committeeCounts = {
        'active': 0,
        'economic': 0,
        'education': 0,
        'health': 0,
        'sports': 0
      };

      allSuggestions.forEach(suggestion => {
        if (committeeCounts.hasOwnProperty(suggestion.committee)) {
          committeeCounts[suggestion.committee]++;
        }
      });

      suggestionChart.data.datasets[0].data = [
        committeeCounts.active,
        committeeCounts.economic,
        committeeCounts.education,
        committeeCounts.health,
        committeeCounts.sports
      ];

      suggestionChart.update();
    }

    // Function to format committee name for display
    function formatCommittee(committee) {
      const committeeNames = {
        'active': 'Active Citizenship',
        'economic': 'Economic Empowerment',
        'education': 'Education',
        'health': 'Health',
        'sports': 'Sports'
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
        
        const committeeMatch = currentCommittee === 'all' || suggestion.committee === currentCommittee;
        
        return monthMatch && committeeMatch;
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
      const committeeClass = suggestion.committee;
      const committeeName = formatCommittee(suggestion.committee);
      const userName = suggestion.user ? 
        `${suggestion.user.given_name} ${suggestion.user.middle_name || ''} ${suggestion.user.last_name} ${suggestion.user.suffix || ''}`.trim() : 
        'Unknown User';
      
      const userInitials = suggestion.user ? 
        `${suggestion.user.given_name?.charAt(0) || ''}${suggestion.user.last_name?.charAt(0) || ''}` : 
        'UU';
      
      const suggestionId = `#KK${suggestion.id.toString().padStart(8, '0')}`;

      return `
        <div class="suggestion-card" data-category="${suggestion.committee}">
          <div class="suggestion-avatar">${userInitials}</div>
          <div class="suggestion-content">
            <h5>${userName} <span class="suggestion-id">${suggestionId}</span></h5>
            <p>${suggestion.suggestions}</p>
            <div class="suggestion-footer">
              <span class="badge ${committeeClass}">${committeeName}</span>
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
          } else if (type === "committee") {
            currentCommittee = option.dataset.value;
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
  });
  </script>
</body>
</html>