<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Admin Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/admin-analytics.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
<body>

<div class="dashboard"> 
    <aside class="sidebar">
      <button class="menu-toggle">Menu</button>
      <div class="divider"></div>
        <nav>
  <a href="{{ route('admindashb') }}">
    <i class="fas fa-home"></i>
    <span class="label">Dashboard</span>
  </a>
  <a href="{{ route('admin-analytics') }}" class="active">
    <i class="fas fa-chart-pie"></i>
    <span class="label">Analytics</span>
  </a>
  <a href="{{ route('user-management2') }}">
    <i class="fas fa-users"></i>
    <span class="label">User Management</span>
  </a>
  <a href="{{ route('users-feedback') }}">
    <i class="fas fa-comments"></i>
    <span class="label">User Feedback</span>
  </a>
  <a href="{{ route('admin-settings') }}">
    <i class="fas fa-cog"></i>
    <span class="label">Settings</span>
  </a>
</nav>
</aside>

    <div class="main">
        <header class="topbar">
        <div class="logo-text">
          <span class="title">Katibayan</span>
          <span class="subtitle">Web Portal</span>
        </div>

      <div class="topbar-right">
      <div class="time" id="current-time"></div>
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
             <strong>New Feedback</strong>
               <p>We need evaluation for the KK-Assembly Event</p>
          </div>
                <span class="notif-dot"></span>
          </li>
          <li>
          <div class="notif-icon"></div>
          <div class="notif-content">
              <strong>New Feedback</strong>
                <p>Hello please fix the print button thank you!</p>
          </div>
                <span class="notif-dot"></span>
          </li>
          </ul>
      </div>
    </div>
<div class="profile-wrapper">
      <img src="https://i.pravatar.cc/80" alt="User" class="avatar" id="profileToggle">
    <div class="profile-dropdown">
    <div class="profile-header">
      <img src="https://i.pravatar.cc/80" alt="User" class="profile-avatar">
    <div class="profile-info">
      <h4>Admin</h4>
  </div>
 </div>
      <hr>
        <ul>
         <li><a href="#"><i class="fas fa-user"></i> KatiBayan Profile</a></li>
         <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        </div>
      </div>
    </div>
</header>
<div class="welcome-card">
    <h2>Welcome Back, Admin!</h2>
</div>
<section class="dashboard-widgets">
      <div class="stat-card">
        <h4>OVERALL POPULATION</h4>
        <p class="subtitle">TOTAL IN ALL BARANGAY</p>
      <div class="population-content">
      <div class="population-total">
        <span class="circle-number">2,809</span>
        <p>Total in all Barangay</p>
    </div>
    <div class="population-chart-container">
        <canvas id="populationChart"></canvas>
        </div>
      </div>
    </div>
<div class="stat-card-rating">
    <h4>OVERALL SYSTEM RATING</h4>
<div class="rating-chart-container">
        <canvas id="ratingChart"></canvas>
        <span class="rating-percent">95%</span>
    </div>
        <p>Based on user feedback</p>
    </div>
    
    <!-- User Activity Overview Card -->
    <div class="user-activity-card">
      <h4>USER ACTIVITY OVERVIEW</h4>
      <div class="user-list">
        <div class="user-item">
          <img src="https://i.pravatar.cc/150?img=1" alt="User" class="user-avatar">
          <div class="user-details">
            <div class="user-name">Jay Park</div>
            <div class="user-id">#HOC0232565DP</div>
            <div class="user-location">Erris Banjo South Baanqay4</div>
            <div class="user-status">Active 1 minute ago</div>
          </div>
        </div>
        <div class="user-item">
          <img src="https://i.pravatar.cc/150?img=2" alt="User" class="user-avatar">
          <div class="user-details">
            <div class="user-name">Maria Santos</div>
            <div class="user-id">#SK0232565DP</div>
            <div class="user-location">North Baanqay2</div>
            <div class="user-status">Active 5 minutes ago</div>
          </div>
        </div>
        <div class="user-item">
          <img src="https://i.pravatar.cc/150?img=3" alt="User" class="user-avatar">
          <div class="user-details">
            <div class="user-name">Juan Dela Cruz</div>
            <div class="user-id">#KK0232565DP</div>
            <div class="user-location">Central Baanqay1</div>
            <div class="user-status">Active 10 minutes ago</div>
          </div>
        </div>
        <div class="user-item">
          <img src="https://i.pravatar.cc/150?img=4" alt="User" class="user-avatar">
          <div class="user-details">
            <div class="user-name">Ana Reyes</div>
            <div class="user-id">#SK0232565DP</div>
            <div class="user-location">West Baanqay3</div>
            <div class="user-status">Active 15 minutes ago</div>
          </div>
        </div>
        <div class="user-item">
          <img src="https://i.pravatar.cc/150?img=5" alt="User" class="user-avatar">
          <div class="user-details">
            <div class="user-name">Pedro Garcia</div>
            <div class="user-id">#KK0232565DP</div>
            <div class="user-location">East Baanqay5</div>
            <div class="user-status">Active 20 minutes ago</div>
          </div>
        </div>
        <div class="user-item">
          <img src="https://i.pravatar.cc/150?img=6" alt="User" class="user-avatar">
          <div class="user-details">
            <div class="user-name">Liza Martinez</div>
            <div class="user-id">#SK0232565DP</div>
            <div class="user-location">South Baanqay6</div>
            <div class="user-status">Active 25 minutes ago</div>
          </div>
        </div>
      </div>
    </div>
</section>

    <!-- Monthly System Rating Performance Card -->
    <div class="monthly-rating-card">
      <h4>MONTHLY SYSTEM RATING PERFORMANCE</h4>
      <div class="monthly-rating-chart-container">
        <canvas id="monthlyRatingChart"></canvas>
      </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ==============================
    // Sidebar Toggle
    // ==============================
    const toggleBtn = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });

    // ==============================
    // Dropdown Toggles (Profile and Notification)
    // ==============================
    const profileWrapper = document.querySelector('.profile-wrapper');
    const profileToggle = document.getElementById('profileToggle');
    const notifWrapper = document.querySelector('.notification-wrapper');

    // Profile Toggle
    profileToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        profileWrapper.classList.toggle('active');
        notifWrapper.classList.remove('active'); // Close other dropdown
    });

    // Notification Toggle
    notifWrapper.addEventListener('click', (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle('active');
        profileWrapper.classList.remove('active'); // Close other dropdown
    });

    // Close all dropdowns when clicking elsewhere on the document
    document.addEventListener('click', (e) => {
        if (!profileWrapper.contains(e.target)) {
            profileWrapper.classList.remove('active');
        }
        if (!notifWrapper.contains(e.target)) {
            notifWrapper.classList.remove('active');
        }
    });

    // ==============================
    // Time Update
    // ==============================
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            const options = { weekday: 'short', hour: '2-digit', minute: '2-digit', hour12: true };
            const parts = now.toLocaleTimeString('en-US', options).toUpperCase().split(' ');
            
            const dayTime = parts.slice(0, 2).join(' ').replace(',', '');
            const amPm = parts[2];
            
            timeElement.innerHTML = `${dayTime} <span>${amPm}</span>`;
        }
    }

    updateTime();
    setInterval(updateTime, 60000); // Update every minute

    // ==============================
    // Chart.js Configuration
    // ==============================

    // Population Chart (Bar)
    const populationCtx = document.getElementById('populationChart');
    if (populationCtx) {
        new Chart(populationCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Barangay 1', 'Barangay 2', 'Barangay 3'],
                datasets: [{
                    data: [850, 720, 680],
                    backgroundColor: ['#2E86C1', '#1B4F72', '#3498DB'],
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (context) => `Population: ${context.parsed.y}` } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' }, ticks: { stepSize: 200 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Rating Chart (Doughnut)
    const ratingCtx = document.getElementById('ratingChart');
    if (ratingCtx) {
        new Chart(ratingCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [95, 5],
                    backgroundColor: ['#2E86C1', '#E5E7EB'],
                    borderWidth: 0,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Monthly Rating Chart
    const monthlyRatingCtx = document.getElementById('monthlyRatingChart');
    if (monthlyRatingCtx) {
        const gradient = monthlyRatingCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(60, 135, 196, 0.5)');
        gradient.addColorStop(1, 'rgba(60, 135, 196, 0)');
        new Chart(monthlyRatingCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Average Rating',
                    data: [85, 82, 88, 87, 90, 92, 89, 93, 91, 94, 95, 96],
                    borderColor: '#3C87C4',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#3C87C4',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#01214A',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    y: {
                        min: 75,
                        max: 100,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { stepSize: 5, color: '#6b7280' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280' }
                    }
                }
            }
        });
    }
});
</script>
</body>
</html>
    