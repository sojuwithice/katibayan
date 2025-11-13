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
      <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : 'https://i.pravatar.cc/80' }}" alt="Admin" class="avatar" id="profileToggle">
    <div class="profile-dropdown">
    <div class="profile-header">
      <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : 'https://i.pravatar.cc/80' }}" alt="Admin" class="profile-avatar">
    <div class="profile-info">
      <h4>{{ $admin->given_name }} {{ $admin->last_name }}</h4>
      <div class="profile-badge">
        <span>Administrator</span>
        <span>{{ $admin->account_number }}</span>
      </div>
  </div>
 </div>
      <hr>
        <ul>
         <li><a href="#"><i class="fas fa-user"></i> KatiBayan Profile</a></li>
         <li>
            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
         </li>
        </ul>
        </div>
      </div>
    </div>
</header>
<div class="welcome-card">
    <h2>Welcome Back, {{ $admin->given_name }}!</h2>
</div>
<section class="dashboard-widgets">
      <div class="stat-card">
        <h4>OVERALL POPULATION</h4>
        <p class="subtitle">TOTAL IN ALL BARANGAY</p>
      <div class="population-content">
      <div class="population-total">
        <span class="circle-number">{{ $totalPopulation }}</span>
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
        <span class="rating-percent">{{ $ratingStats['rating_percentage'] }}%</span>
    </div>
    <div class="rating-details">
        <div class="average-rating">
            <span class="rating-number">{{ $ratingStats['average_rating'] }}</span>
            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($ratingStats['average_rating']))
                        <i class="fa-solid fa-star"></i>
                    @elseif($i - 0.5 <= $ratingStats['average_rating'])
                        <i class="fa-solid fa-star-half-stroke"></i>
                    @else
                        <i class="fa-regular fa-star"></i>
                    @endif
                @endfor
            </div>
        </div>
        <p>Based on {{ $ratingStats['total_ratings'] }} user ratings</p>
    </div>
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
            <a href="{{ route('users-feedback') }}" class="view-all">View All</a>
        </div>

        <div class="user-comments">
            @forelse($recentFeedbacks as $feedback)
            <div class="comment">
                <img src="{{ $feedback->user->avatar ? asset('storage/' . $feedback->user->avatar) : 'https://i.pravatar.cc/40?img=' . $loop->index }}" alt="User" class="user-img">
                <div class="comment-details">
                    <div class="comment-header">
                        <span class="user-id">#{{ $feedback->user->account_number ?? 'N/A' }}</span>
                        <div class="rating-stars">
                            @if($feedback->rating)
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $feedback->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                                <span class="rating-score">{{ $feedback->rating }}/5</span>
                            @else
                                <span class="no-rating">No rating</span>
                            @endif
                        </div>
                    </div>
                    <p class="comment-text">{{ $feedback->message }}</p>
                    <div class="comment-footer">
                        <span class="date">{{ $feedback->created_at->format('m/d/Y') }}</span>
                        <span class="time">{{ $feedback->created_at->format('g:i A') }}</span>
                        <span class="feedback-type {{ $feedback->type }}">{{ ucfirst($feedback->type) }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="comment">
                <img src="https://i.pravatar.cc/40?img=1" alt="User" class="user-img">
                <div class="comment-details">
                    <div class="comment-header">
                        <span class="user-id">No Feedback</span>
                    </div>
                    <p class="comment-text">No user feedback available yet.</p>
                    <div class="comment-footer">
                        <span class="date">--/--/----</span>
                        <span class="time">--:-- --</span>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>

<div class="manage-card bottom-card">
<div class="header">
    <h4>Manage Account <span class="notif-count">{{ $pendingAccountsCount }}</span></h4>
    <a href="{{ route('user-management2') }}" class="view-all">View All</a>
</div>

<div class="account-list">
    @forelse($pendingAccounts as $account)
    <div class="account">
        <div class="account-info">
            <strong>{{ $account->given_name }} {{ $account->last_name }}</strong>
            <p>{{ $account->role === 'sk' ? 'SK' : 'KK' }} | {{ $account->barangay->name ?? 'Unknown Barangay' }}, {{ $account->purok_zone }}</p>
        </div>
        <span class="new-user">New User</span>
    </div>
    @empty
    <div class="account">
        <div class="account-info">
            <strong>No pending accounts</strong>
            <p>All accounts have been processed</p>
        </div>
    </div>
    @endforelse
</div>
</div>
</section>
</div>
</div>
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded - checking chart data...');
    
    // ==============================
    // Sidebar Toggle
    // ==============================
    var toggleBtn = document.querySelector('.menu-toggle');
    var sidebar = document.querySelector('.sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }

    // ==============================
    // Dropdown Toggles (Profile and Notification)
    // ==============================
    var profileWrapper = document.querySelector('.profile-wrapper');
    var profileToggle = document.getElementById('profileToggle');
    var notifWrapper = document.querySelector('.notification-wrapper');

    // Profile Toggle
    if (profileToggle) {
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (profileWrapper) {
                profileWrapper.classList.toggle('active');
            }
            if (notifWrapper) {
                notifWrapper.classList.remove('active');
            }
        });
    }

    // Notification Toggle
    if (notifWrapper) {
        notifWrapper.addEventListener('click', function(e) {
            e.stopPropagation();
            notifWrapper.classList.toggle('active');
            if (profileWrapper) {
                profileWrapper.classList.remove('active');
            }
        });
    }

    // Close all dropdowns when clicking elsewhere on the document
    document.addEventListener('click', function(e) {
        if (profileWrapper && !profileWrapper.contains(e.target)) {
            profileWrapper.classList.remove('active');
        }
        if (notifWrapper && !notifWrapper.contains(e.target)) {
            notifWrapper.classList.remove('active');
        }
    });

    // ==============================
    // Time Update
    // ==============================
    function updateTime() {
        var now = new Date();
        var timeElement = document.getElementById('current-time');
        if (timeElement) {
            var options = { weekday: 'short', hour: '2-digit', minute: '2-digit', hour12: true };
            var timeString = now.toLocaleTimeString('en-US', options).toUpperCase();
            var parts = timeString.split(' ');
            
            var dayTime = parts[0] + ' ' + parts[1];
            var amPm = parts[2];
            
            timeElement.innerHTML = dayTime + ' <span>' + amPm + '</span>';
        }
    }

    updateTime();
    setInterval(updateTime, 60000);

    // ==============================
    // Chart.js Configuration
    // ==============================

    // Population Chart (Bar) - Dynamic based on actual population data with SK/KK breakdown
    var populationCtx = document.getElementById('populationChart');
    if (populationCtx) {
        console.log('Creating population chart...');
        
        // Get PHP values directly
        var emsBarrioTotal = <?php echo isset($barangayPopulations['ems_barrio']['total']) ? $barangayPopulations['ems_barrio']['total'] : 0; ?>;
        var emsBarrioSouthTotal = <?php echo isset($barangayPopulations['ems_barrio_south']['total']) ? $barangayPopulations['ems_barrio_south']['total'] : 0; ?>;
        var emsBarrioEastTotal = <?php echo isset($barangayPopulations['ems_barrio_east']['total']) ? $barangayPopulations['ems_barrio_east']['total'] : 0; ?>;

        var emsBarrioSK = <?php echo isset($barangayPopulations['ems_barrio']['sk']) ? $barangayPopulations['ems_barrio']['sk'] : 0; ?>;
        var emsBarrioSouthSK = <?php echo isset($barangayPopulations['ems_barrio_south']['sk']) ? $barangayPopulations['ems_barrio_south']['sk'] : 0; ?>;
        var emsBarrioEastSK = <?php echo isset($barangayPopulations['ems_barrio_east']['sk']) ? $barangayPopulations['ems_barrio_east']['sk'] : 0; ?>;

        var emsBarrioKK = <?php echo isset($barangayPopulations['ems_barrio']['kk']) ? $barangayPopulations['ems_barrio']['kk'] : 0; ?>;
        var emsBarrioSouthKK = <?php echo isset($barangayPopulations['ems_barrio_south']['kk']) ? $barangayPopulations['ems_barrio_south']['kk'] : 0; ?>;
        var emsBarrioEastKK = <?php echo isset($barangayPopulations['ems_barrio_east']['kk']) ? $barangayPopulations['ems_barrio_east']['kk'] : 0; ?>;

        console.log('Chart Data:', {
            emsBarrioTotal: emsBarrioTotal,
            emsBarrioSouthTotal: emsBarrioSouthTotal,
            emsBarrioEastTotal: emsBarrioEastTotal,
            emsBarrioSK: emsBarrioSK,
            emsBarrioSouthSK: emsBarrioSouthSK,
            emsBarrioEastSK: emsBarrioEastSK,
            emsBarrioKK: emsBarrioKK,
            emsBarrioSouthKK: emsBarrioSouthKK,
            emsBarrioEastKK: emsBarrioEastKK
        });

        var barangayLabels = ['Em\'s Barrio', 'Em\'s Barrio South', 'Em\'s Barrio East'];
        var barangayTotals = [emsBarrioTotal, emsBarrioSouthTotal, emsBarrioEastTotal];
        var skCounts = [emsBarrioSK, emsBarrioSouthSK, emsBarrioEastSK];
        var kkCounts = [emsBarrioKK, emsBarrioSouthKK, emsBarrioEastKK];

        // Find max value for step size calculation
        var maxValue = Math.max(emsBarrioTotal, emsBarrioSouthTotal, emsBarrioEastTotal);
        var stepSize = maxValue > 0 ? Math.ceil(maxValue / 5) : 1;

        // Create the chart
        var populationChart = new Chart(populationCtx, {
            type: 'bar',
            data: {
                labels: barangayLabels,
                datasets: [{
                    data: barangayTotals,
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
                    tooltip: { 
                        callbacks: { 
                            label: function(context) {
                                var index = context.dataIndex;
                                return [
                                    'SK: ' + skCounts[index],
                                    'KK: ' + kkCounts[index],
                                    'Total: ' + context.parsed.y
                                ];
                            } 
                        } 
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }, 
                        ticks: { 
                            stepSize: stepSize
                        } 
                    },
                    x: { 
                        grid: { display: false },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
        
        console.log('Population chart created successfully');
    } else {
        console.error('Population chart canvas not found!');
    }

    // Rating Chart (Doughnut) - Dynamic based on actual ratings
    var ratingCtx = document.getElementById('ratingChart');
    if (ratingCtx) {
        var ratingPercentage = <?php echo isset($ratingStats['rating_percentage']) ? $ratingStats['rating_percentage'] : 0; ?>;
        var remainingPercentage = 100 - ratingPercentage;
        
        var ratingChart = new Chart(ratingCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [ratingPercentage, remainingPercentage],
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
    var monthYearDisplay = document.getElementById('monthYear');
    var calendarBody = document.querySelector('.calendar-table tbody');
    var prevMonthBtn = document.querySelector('.calendar-header .prev');
    var nextMonthBtn = document.querySelector('.calendar-header .next');

    if (monthYearDisplay && calendarBody && prevMonthBtn && nextMonthBtn) {
        var currentDate = new Date(); 

        function renderCalendar() {
            calendarBody.innerHTML = '';
            var year = currentDate.getFullYear();
            var month = currentDate.getMonth();

            monthYearDisplay.textContent = currentDate.toLocaleString('en-US', { month: 'long', year: 'numeric' });

            var firstDayIndex = new Date(year, month, 1).getDay();
            var startDayOffset = (firstDayIndex + 6) % 7; 

            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var today = new Date();
            var currentDay = today.getDate();
            var currentMonth = today.getMonth();
            var currentYear = today.getFullYear();
            
            var date = 1;

            for (var i = 0; i < 6; i++) {
                var row = document.createElement('tr');
                var weekHasDay = false;

                for (var j = 0; j < 7; j++) {
                    var cell = document.createElement('td');
                    
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
        prevMonthBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
        
        renderCalendar();
    }
});
</script>
</body>
</html>