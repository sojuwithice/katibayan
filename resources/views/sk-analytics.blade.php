<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - SK Analytics</title>
   <link rel="stylesheet" href="{{ asset('css/sk-analytics.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body>
  <!-- Sidebar -->
   <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="#">
        <i class="fas fa-chart-pie"></i>
        <span class="label">Dashboard</span>
      </a>

      <a href="#" class="active">
        <i class="fa-solid fa-chart-simple"></i>
        <span class="label">Analytics</span>
      </a>

      <a href="#">
        <i class="fas fa-users"></i>
        <span class="label">Youth Profile</span>
      </a>

      <div class="nav-item">
        <a href="#" class="nav-link">
          <i class="fas fa-calendar-alt"></i>
          <span class="label">Events and Programs</span>
          <i class="fas fa-chevron-down submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="#">Events List</a>
          <a href="#">Youth Registration</a>
        </div>
      </div>

      <a href="#">
        <i class="fas fa-comment-alt"></i>
        <span class="label">Feedbacks</span>
      </a>

      <a href="#">
        <i class="fas fa-vote-yea"></i>
        <span class="label">Polls</span>
      </a>

      <a href="#">
        <i class="fas fa-lightbulb"></i>
        <span class="label">Suggestion Box</span>
      </a>
      
      <a href="#">
        <i class="fas fa-chart-bar"></i>
        <span class="label">Reports</span>
      </a>

      <a href="#">
        <i class="fas fa-hands-helping"></i>
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
          <i class="fas fa-bell" id="notificationBell"></i>
          <span class="notif-count" id="notificationCount">3</span>
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notifications</strong> 
              <span id="notificationsHeaderCount">3</span>
              <button class="mark-all-read" id="markAllRead">Mark all as read</button>
            </div>
            <ul class="notif-list" id="notificationsList">
              <li class="notification-item unread" data-id="1">
                <div class="notif-icon">
                  <i class="fas fa-star unread"></i>
                </div>
                <div class="notif-content">
                  <strong>New Event Registration</strong>
                  <p>5 minutes ago</p>
                </div>
                <span class="notif-dot"></span>
              </li>
              <li class="notification-item unread" data-id="2">
                <div class="notif-icon">
                  <i class="fas fa-star unread"></i>
                </div>
                <div class="notif-content">
                  <strong>Youth Profile Updated</strong>
                  <p>1 hour ago</p>
                </div>
                <span class="notif-dot"></span>
              </li>
              <li class="notification-item read" data-id="3">
                <div class="notif-icon">
                  <i class="fas fa-star read"></i>
                </div>
                <div class="notif-content">
                  <strong>Monthly Report Ready</strong>
                  <p>Yesterday</p>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- Profile Avatar -->
        <div class="profile-wrapper">
          <div class="avatar" id="profileToggle" style="background: #3C87C4; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">SK</div>
          <div class="profile-dropdown">
            <div class="profile-header">
              <div class="profile-avatar" style="background: #3C87C4; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">SK</div>
              <div class="profile-info">
                <h4>SK Chair User</h4>
                <div class="profile-badge">
                  <span class="badge">SK Chairperson</span>
                  <span class="badge">25 yrs old</span>
                </div>
              </div>
            </div>
            <hr>
            <ul class="profile-menu">
              <li>
                <a href="#">
                  <i class="fas fa-user"></i> Profile
                </a>
              </li>
              <li><i class="fas fa-cog"></i> Manage Password</li>
              <li>
                <a href="#">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>
              <li><i class="fas fa-star"></i> Send Feedback to Katibayan</li>
              <li class="logout-item">
                <a href="#" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
      <!-- Welcome Card -->
      <div class="welcome-card">
        <h2>Analytics</h2>
      </div>

      <!-- Top Row - 2x2 Containers -->
      <div class="card engagement-card">
        <h3>Youth Engagement Level</h3>
        <div class="chart-container">
          <canvas id="engagementChart"></canvas>
        </div>
      </div>

      <div class="card activities-card">
        <div class="card-header">
          <h3>Monthly Proposed Youth Activities</h3>
        </div>
        <div class="chart-container">
          <canvas id="activitiesChart"></canvas>
        </div>
      </div>

      <div class="card demographics-card">
        <h3>Youth Demographics by Classification</h3>
        <div class="chart-container">
          <canvas id="demographicsChart"></canvas>
        </div>
        <div class="legend">
          <span><span class="dot male"></span> Male</span>
          <span><span class="dot female"></span> Female</span>
        </div>
      </div>

      <div class="card youth-age-card">
        <div class="card-header">
          <h3>Youth Age Group</h3>
          <button class="options-btn">⋯</button>
          <!-- Dropdown -->
          <div class="options-dropdown">
            <ul>
              <li>Purok 1</li>
              <li>Purok 2</li>
              <li>Purok 3</li>
              <li>Purok 4</li>
              <li>Purok 5</li>
              <li>Purok 6</li>
              <li>Purok 7</li>
              <li>Purok 8</li>
            </ul>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="ageChart"></canvas>
          <!-- Custom Legend -->
          <div class="legend">
            <div class="legend-item">
              <span class="dot child"></span> Child Youth 15-17
            </div>
            <div class="legend-item">
              <span class="dot core"></span> Core Youth 18-24
            </div>
            <div class="legend-item">
              <span class="dot adult"></span> Adult Youth 25-30
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Row - 2 Containers (SWAPPED POSITIONS) -->
      <div class="card sk-committee">
        <h2>SK COMMITTEE</h2>
        <ul>
          <li class="committee-item">
            <span class="name">MARIJOY S. NOVORA</span>
            <div class="role-group">
              <span class="role-tag role-chairperson">SK CHAIRPERSON</span>
            </div>
          </li>
          <li class="members-header">MEMBERS</li>
          <li class="committee-item">
            <span class="name">JUAN DELA CRUZ</span>
            <div class="role-group">
              <span class="role-tag role-treasurer">SK TREASURER</span>
            </div>
          </li>
          <li class="committee-item">
            <span class="name">MARIA SANTOS</span>
            <div class="role-group">
              <span class="role-tag role-secretary">SK SECRETARY</span>
            </div>
          </li>
          <li class="committee-item">
            <span class="name">PETER GARCIA</span>
            <div class="role-group">
              <span class="role-tag role-kagawad">SK KAGAWAD</span>
              <span class="committee-role">COMMITTEE ON HEALTH</span>
              <span class="committee-role">COMMITTEE ON ACTIVE CITIZENSHIP</span>
            </div>
          </li>
          <li class="committee-item">
            <span class="name">ANNA REYES</span>
            <div class="role-group">
              <span class="role-tag role-kagawad">SK KAGAWAD</span>
              <span class="committee-role">COMMITTEE ON SOCIAL INCLUSION</span>
            </div>
          </li>
          <li class="committee-item">
            <span class="name">MARK LIM</span>
            <div class="role-group">
              <span class="role-tag role-kagawad">SK KAGAWAD</span>
              <span class="committee-role">COMMITTEE ON EDUCATION</span>
            </div>
          </li>
          <li class="committee-item">
            <span class="name">ISABELA DAVID</span>
            <div class="role-group">
              <span class="role-tag role-kagawad">SK KAGAWAD</span>
              <span class="committee-role">COMMITTEE ON ENVIRONMENT</span>
            </div>
          </li>
          <li class="committee-item">
            <span class="name">CARLO FERNANDEZ</span>
            <div class="role-group">
              <span class="role-tag role-kagawad">SK KAGAWAD</span>
              <span class="committee-role">COMMITTEE ON SPORTS DEV</span>
            </div>
          </li>
          <li class="committee-item">
            <span class="name">SOFIA ANGELES</span>
            <div class="role-group">
              <span class="role-tag role-kagawad">SK KAGAWAD</span>
              <span class="committee-role">COMMITTEE ON CULTURE & ARTS</span>
            </div>
          </li>
        </ul>
      </div>

      <div class="youth-population card">
        <h3 class="population-title">Youth Population</h3>
        <div class="population-chart">
          <canvas id="populationChart"></canvas>
          <div class="population-center">
            <span class="population-total" id="populationTotal">650</span>
            <p>Youth population in your barangay</p>
          </div>
        </div>
        <div class="population-legend">
          <div class="legend-item">
            <span>Female</span>
            <span id="femaleCount">320</span>
            <span class="dot female"></span>
          </div>
          <div class="legend-item">
            <span>Male</span>
            <span id="maleCount">330</span>
            <span class="dot male"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      console.log("DOM loaded, initializing charts...");
      
      // Initialize all charts
      initializeCharts();
      
      // Initialize other functionality
      initializeSidebar();
      initializeNotifications();
      initializeTime();
      initializeDropdowns();
    });

    function initializeCharts() {
      console.log("Initializing charts...");
      
      // 1. Engagement Chart
      const engagementCtx = document.getElementById('engagementChart');
      if (engagementCtx) {
        try {
          new Chart(engagementCtx, {
            type: 'bar',
            data: {
              labels: ['Active', 'Less Active', 'Inactive'],
              datasets: [{
                label: 'Youth Count',
                data: [120, 80, 60],
                backgroundColor: ['#3C87C6', '#7EE081', '#C3423F'],
                borderRadius: 10,
                borderWidth: 0
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: 'top',
                  labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: {
                      size: 12
                    }
                  }
                }
              },
              scales: {
                x: {
                  grid: {
                    display: false
                  }
                },
                y: {
                  beginAtZero: true,
                  grid: {
                    color: 'rgba(0,0,0,0.05)'
                  }
                }
              }
            }
          });
          console.log("Engagement chart created successfully");
        } catch (error) {
          console.error('Error creating engagement chart:', error);
        }
      }

      // 2. Activities Chart
      const activitiesCtx = document.getElementById('activitiesChart');
      if (activitiesCtx) {
        try {
          new Chart(activitiesCtx, {
            type: 'bar',
            data: {
              labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
              datasets: [{
                label: "Participants",
                data: [45, 60, 40, 80, 70, 55, 90, 65],
                backgroundColor: "#3C87C6",
                borderRadius: 6,
                borderWidth: 0
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false
                }
              },
              scales: {
                x: {
                  grid: {
                    display: false
                  }
                },
                y: {
                  beginAtZero: true,
                  grid: {
                    color: 'rgba(0,0,0,0.05)'
                  }
                }
              }
            }
          });
          console.log("Activities chart created successfully");
        } catch (error) {
          console.error('Error creating activities chart:', error);
        }
      }

      // 3. Age Group Chart
      const ageCtx = document.getElementById('ageChart');
      if (ageCtx) {
        try {
          // Sample data
          const ageData = {
            child_count: 150,
            core_count: 300,
            adult_count: 120
          };
          
          new Chart(ageCtx, {
            type: 'pie',
            data: {
              labels: ["Child Youth 15-17", "Core Youth 18-24", "Adult Youth 25-30"],
              datasets: [{
                data: [ageData.child_count, ageData.core_count, ageData.adult_count],
                backgroundColor: ["#FFCA3A", "#3C87C6", "#8AC926"],
                borderWidth: 2,
                borderColor: "#fff"
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          });
          console.log("Age chart created successfully");
        } catch (error) {
          console.error('Error creating age chart:', error);
        }
      }

      // 4. Demographics Chart
      const demoCtx = document.getElementById('demographicsChart');
      if (demoCtx) {
        try {
          // Sample data
          const demographicsData = {
            labels: ['In-School Youth', 'Out-of-School Youth', 'Working Youth', 'Person with disabilities', 'Indigenous'],
            male_data: [120, 80, 150, 30, 25],
            female_data: [110, 70, 130, 35, 20]
          };

          new Chart(demoCtx, {
            type: 'bar',
            data: {
              labels: demographicsData.labels,
              datasets: [
                {
                  label: 'Male',
                  data: demographicsData.male_data,
                  backgroundColor: '#3C87C6',
                  barPercentage: 0.6,
                  categoryPercentage: 0.8
                },
                {
                  label: 'Female',
                  data: demographicsData.female_data,
                  backgroundColor: '#E96BA8',
                  barPercentage: 0.6,
                  categoryPercentage: 0.8
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              indexAxis: 'y',
              scales: {
                x: {
                  beginAtZero: true,
                  grid: {
                    color: "rgba(0,0,0,0.1)"
                  }
                },
                y: {
                  grid: {
                    display: false
                  }
                }
              },
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          });
          console.log("Demographics chart created successfully");
        } catch (error) {
          console.error('Error creating demographics chart:', error);
        }
      }

      // 5. Population Chart
      const populationCtx = document.getElementById('populationChart');
      if (populationCtx) {
        try {
          // Sample data
          const populationData = {
            male_count: 330,
            female_count: 320,
            total_count: 650
          };

          new Chart(populationCtx, {
            type: 'doughnut',
            data: {
              labels: ['Female', 'Male'],
              datasets: [{
                data: [populationData.female_count, populationData.male_count],
                backgroundColor: ['#f48fb1', '#114B8C'],
                borderWidth: 0
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: '70%',
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          });
          console.log("Population chart created successfully");
        } catch (error) {
          console.error('Error creating population chart:', error);
        }
      }
    }

    function initializeSidebar() {
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');

      if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('open');
        });
      }

      // Submenu functionality
      const submenuTriggers = document.querySelectorAll('.nav-item > .nav-link');
      submenuTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
          e.preventDefault();
          const parentItem = trigger.closest('.nav-item');
          const wasOpen = parentItem.classList.contains('open');

          // Close all other submenus
          document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('open');
          });

          // Open clicked submenu if it wasn't open
          if (!wasOpen) {
            parentItem.classList.add('open');
          }
        });
      });
    }

    function initializeNotifications() {
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
        if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove("active");
        if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove("active");
      });

      // Mark as read functionality
      const markAllReadBtn = document.getElementById('markAllRead');
      if (markAllReadBtn) {
          markAllReadBtn.addEventListener('click', (e) => {
              e.stopPropagation();
              document.querySelectorAll('.notification-item').forEach(item => {
                  item.classList.add('read');
                  item.classList.remove('unread');
                  const dot = item.querySelector('.notif-dot');
                  if (dot) dot.remove();
              });
              document.getElementById('notificationCount').textContent = '0';
              document.getElementById('notificationsHeaderCount').textContent = '0';
          });
      }

      // Individual notification click
      document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', (e) => {
          e.stopPropagation();
          item.classList.add('read');
          item.classList.remove('unread');
          const dot = item.querySelector('.notif-dot');
          if (dot) dot.remove();
          
          // Update count
          const unreadCount = document.querySelectorAll('.notification-item.unread').length;
          document.getElementById('notificationCount').textContent = unreadCount;
          document.getElementById('notificationsHeaderCount').textContent = unreadCount;
        });
      });
    }

    function initializeTime() {
      const timeEl = document.querySelector(".time");
      if (timeEl) {
        function updateTime() {
          const now = new Date();
          const shortWeekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
          const shortMonths = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
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
      }
    }

    function initializeDropdowns() {
      // Options dropdown functionality
      document.querySelectorAll('.options-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.stopPropagation();
          const dropdown = btn.nextElementSibling;
          if (dropdown && dropdown.classList.contains('options-dropdown')) {
            dropdown.classList.toggle('show');
          }
        });
      });

      // Close dropdowns when clicking outside
      document.addEventListener('click', () => {
        document.querySelectorAll('.options-dropdown.show').forEach(d => {
          d.classList.remove('show');
        });
      });
    }

    function confirmLogout(event) {
      event.preventDefault();
      if (confirm('Are you sure you want to logout?')) {
        // Perform logout action
        alert('Logging out...');
      }
    }
  </script>
</body>
</html>