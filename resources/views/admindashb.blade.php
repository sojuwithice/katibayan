<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Admin Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/admindashb.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
<div class="dashboard"> 
    <aside class="sidebar">
      <button class="menu-toggle">Menu</button>
      <div class="divider"></div>
        <nav>
  <a href="{{ route('admindashb') }}" class="active">
    <i class="fas fa-home"></i>
    <span class="label">Dashboard</span>
  </a>
  <a href="{{ route('admin-analytics') }}">
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

<div class="stat-card-calendar">
    <div class="calendar-header">
        <button class="prev"><i class="fas fa-chevron-left"></i></button>
        <h3 id="monthYear"></h3>
        <button class="next"><i class="fas fa-chevron-right"></i></button>
    </div>
        <table class="calendar-table">
        <thead>
        <tr>
          <th>MON</th><th>TUE</th><th>WED</th><th>THU</th><th>FRI</th><th>SAT</th><th>SUN</th>
        </tr>
        </thead>
       <tbody>
     </tbody>
    </table>
  </div>
</section>

<section class="dashboard-secondary-widgets">
    <div class="stat-card bottom-card">
        <div class="feedback-header">
            <h4>USER FEEDBACK COMMENTS</h4>
            <a href="#" class="view-all">View All</a>
        </div>

        <div class="user-comments">
            
            <div class="comment">
                <img src="https://i.pravatar.cc/40?img=1" alt="User" class="user-img">
                <div class="comment-details">
                    <div class="comment-header">
                        <span class="user-id">#KK2025296JP</span>
                        <div class="rating-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="rating-score">5/5</span>
                        </div>
                    </div>
                    <p class="comment-text">Infairness sa system ha! Taray slay ka jan sa color luv it</p>
                    <div class="comment-footer">
                        <span class="date">09/09/2025</span>
                        <span class="time">6:00 PM</span>
                    </div>
                </div>
            </div>

            <div class="comment">
                 <img src="https://i.pravatar.cc/40?img=1" alt="User" class="user-img">
                <div class="comment-details">
                    <div class="comment-header">
                        <span class="user-id">#KK2025296JP</span>
                        <div class="rating-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="rating-score">5/5</span>
                        </div>
                    </div>
                    <p class="comment-text">Infairness sa system ha! Taray slay ka jan sa color luv it</p>
                    <div class="comment-footer">
                        <span class="date">09/09/2025</span>
                        <span class="time">6:00 PM</span>
                    </div>
                </div>
            </div>

            <div class="comment">
                 <img src="https://i.pravatar.cc/40?img=1" alt="User" class="user-img">
                <div class="comment-details">
                    <div class="comment-header">
                        <span class="user-id">#KK2025296JP</span>
                        <div class="rating-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="rating-score">5/5</span>
                        </div>
                    </div>
                    <p class="comment-text">Infairness sa system ha! Taray slay ka jan sa color luv it</p>
                    <div class="comment-footer">
                        <span class="date">09/09/2025</span>
                        <span class="time">6:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="manage-card bottom-card">
<div class="header">
    <h4>Manage Account <span class="notif-count">3</span></h4>
    <a href="#" class="view-all">View All</a>
</div>

<div class="account-list">
  <div class="account">
  <div class="account-info">
     <strong>Nina H. Kae</strong>
     <p>SK | Em's Barrio South, Purok 5</p>
  </div>
     <span class="new-user">New User</span>
   </div>
 <div class="account">
 <div class="account-info">
     <strong>Nina H. Kae</strong>
     <p>SK | Em's Barrio South, Purok 5</p>
 </div>
     <span class="new-user">New User</span>
 </div>
 <div class="account">
 <div class="account-info">
        <strong>Nina H. Kae</strong>
        <p>SK | Em's Barrio South, Purok 5</p>
  </div>
        <span class="new-user">New User</span>
    </div>
   </div>
 </div>
</section>
</div>
</div>
    
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ==============================
    // Sidebar Toggle - FIXED: Remove margin changes
    // ==============================
    const toggleBtn = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');

    toggleBtn.addEventListener('click', () => {
        // FIX: Only toggle the sidebar class, don't change margins
        sidebar.classList.toggle('open');
        // REMOVED: main.style.marginLeft and topbar.style.marginLeft
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

    // ==============================
    // Calendar Functionality
    // ==============================
    const monthYearDisplay = document.getElementById('monthYear');
    const calendarBody = document.querySelector('.calendar-table tbody');
    const prevMonthBtn = document.querySelector('.calendar-header .prev');
    const nextMonthBtn = document.querySelector('.calendar-header .next');

    let currentDate = new Date(); 

    function renderCalendar() {
        calendarBody.innerHTML = '';
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        monthYearDisplay.textContent = currentDate.toLocaleString('en-US', { month: 'long', year: 'numeric' });

        let firstDayIndex = new Date(year, month, 1).getDay();
        const startDayOffset = (firstDayIndex + 6) % 7; 

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        const currentDay = today.getDate();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();
        
        let date = 1;

        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');
            let weekHasDay = false;

            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');
                
                if (i === 0 && j < startDayOffset) {
                    cell.classList.add('empty');
                } else if (date > daysInMonth) {
                    cell.classList.add('empty');
                } else {
                    cell.textContent = date;
                    weekHasDay = true;
                    
                    if (date === currentDay && month === currentMonth && year === currentYear) {
                        cell.classList.add('today');
                    }
                    
                    if (date === 25 && month === 9 && year === 2025) {
                         cell.classList.add('active');
                    }
                    
                    date++;
                }
                row.appendChild(cell);
            }
            
            if (weekHasDay) {
                 calendarBody.appendChild(row);
            }
        }
    }

    // Event Listeners for Month Navigation
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    renderCalendar();
});
</script>
</body>
</html>
