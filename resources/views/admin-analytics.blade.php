<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KatiBayan - Admin Analytics</title>
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
                </div>
                <div class="profile-wrapper">
                    <img src="https://i.pravatar.cc/80" alt="User" class="avatar" id="profileToggle">
                </div>
            </div>
        </header>

        <div class="welcome-card">
            <h2>Analytics Dashboard</h2>
            <p>Comprehensive overview of system usage and user activity</p>
        </div>

        <section class="dashboard-widgets">
            <div class="stat-card">
                <h4>OVERALL POPULATION</h4>
                <p class="subtitle">TOTAL IN ALL BARANGAY</p>
                <div class="population-content">
                    <div class="population-total">
                        <span class="circle-number">{{ $totalPopulation ?? 0 }}</span>
                        <p>Total in all Barangay</p>
                    </div>
                    <div class="population-chart-container">
                        <canvas id="populationChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="stat-card-rating">
                <h4>OVERALL SYSTEM RATING</h4>
                <div class="rating-content">
                    <div class="rating-main">
                        <span class="rating-number">{{ number_format($ratingStats['average_rating'] ?? 0, 1) }}/5</span>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($ratingStats['average_rating'] ?? 0))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= ($ratingStats['average_rating'] ?? 0))
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-total">Based on {{ $ratingStats['total_ratings'] ?? 0 }} ratings</span>
                    </div>
                    <div class="rating-chart-container">
                        <canvas id="ratingChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="user-activity-card">
                <h4>USER ACTIVITY OVERVIEW</h4>
                <div class="user-list">
                    @if(isset($recentUsers) && count($recentUsers) > 0)
                        @foreach($recentUsers as $user)
                        <div class="user-item">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://i.pravatar.cc/150?img=' . ($loop->index + 1) }}" alt="User" class="user-avatar">
                            <div class="user-details">
                                <div class="user-name">{{ $user->given_name ?? '' }} {{ $user->last_name ?? '' }}</div>
                                <div class="user-id">
                                    Account: {{ $user->account_number ?? 'N/A' }}
                                </div>
                                <div class="user-location">
                                    {{ $user->barangay->name ?? 'Unknown Barangay' }}, {{ $user->purok_zone ?? 'Unknown Purok' }}
                                </div>
                                <div class="user-status">
                                    <span class="status-badge {{ $user->role === 'sk' ? 'sk-badge' : 'kk-badge' }}">
                                        {{ $user->role === 'sk' ? 'SK Official' : 'KK Member' }}
                                    </span>
                                    • Joined {{ $user->created_at->diffForHumans() ?? '' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="no-users">
                        <p>No recent user activity</p>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <div class="monthly-rating-card">
            <h4>MONTHLY SYSTEM RATING PERFORMANCE</h4>
            <div class="monthly-rating-chart-container">
                <canvas id="monthlyRatingChart"></canvas>
            </div>
        </div>

        <div class="user-distribution-card">
            <h4>USER DISTRIBUTION BY BARANGAY</h4>
            <div class="distribution-content">
                <div class="distribution-chart">
                    <canvas id="userDistributionChart"></canvas>
                </div>
                <div class="distribution-stats">
                    <div class="role-stats">
                        <div class="stat-item">
                            <span class="stat-label">SK Officials</span>
                            <span class="stat-value">{{ $skCount ?? 0 }}</span>
                            <span class="stat-percentage">
                                @php
                                    $totalPop = $totalPopulation ?? 1;
                                    $skCountVal = $skCount ?? 0;
                                    $percentage = $totalPop > 0 ? ($skCountVal / $totalPop) * 100 : 0;
                                    echo number_format($percentage, 1) . '%';
                                @endphp
                            </span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">KK Members</span>
                            <span class="stat-value">{{ $kkCount ?? 0 }}</span>
                            <span class="stat-percentage">
                                @php
                                    $kkCountVal = $kkCount ?? 0;
                                    $percentage = $totalPop > 0 ? ($kkCountVal / $totalPop) * 100 : 0;
                                    echo number_format($percentage, 1) . '%';
                                @endphp
                            </span>
                        </div>
                        <div class="stat-item total-item">
                            <span class="stat-label">Total Users</span>
                            <span class="stat-value">{{ $totalPopulation ?? 0 }}</span>
                            <span class="stat-percentage">100%</span>
                        </div>
                    </div>
                    
                    <!-- Barangay Specific Distribution -->
                    <div class="barangay-breakdown">
                        <h5>Barangay Breakdown</h5>
                        <div class="barangay-item">
                            <span class="barangay-name">Em's Barrio</span>
                            <span class="barangay-count">{{ $barangayPopulations['ems_barrio']['total'] ?? 0 }}</span>
                            <span class="barangay-percentage">
                                @php
                                    $emsTotal = $barangayPopulations['ems_barrio']['total'] ?? 0;
                                    $percentage = $totalPop > 0 ? ($emsTotal / $totalPop) * 100 : 0;
                                    echo number_format($percentage, 1) . '%';
                                @endphp
                            </span>
                        </div>
                        <div class="barangay-item">
                            <span class="barangay-name">Em's Barrio South</span>
                            <span class="barangay-count">{{ $barangayPopulations['ems_barrio_south']['total'] ?? 0 }}</span>
                            <span class="barangay-percentage">
                                @php
                                    $emsSouthTotal = $barangayPopulations['ems_barrio_south']['total'] ?? 0;
                                    $percentage = $totalPop > 0 ? ($emsSouthTotal / $totalPop) * 100 : 0;
                                    echo number_format($percentage, 1) . '%';
                                @endphp
                            </span>
                        </div>
                        <div class="barangay-item">
                            <span class="barangay-name">Em's Barrio East</span>
                            <span class="barangay-count">{{ $barangayPopulations['ems_barrio_east']['total'] ?? 0 }}</span>
                            <span class="barangay-percentage">
                                @php
                                    $emsEastTotal = $barangayPopulations['ems_barrio_east']['total'] ?? 0;
                                    $percentage = $totalPop > 0 ? ($emsEastTotal / $totalPop) * 100 : 0;
                                    echo number_format($percentage, 1) . '%';
                                @endphp
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time Update
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            const options = { 
                weekday: 'short', 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: true 
            };
            const formattedTime = now.toLocaleTimeString('en-US', options).toUpperCase();
            timeElement.textContent = formattedTime;
        }
    }
    updateTime();
    setInterval(updateTime, 60000);

    // Population Chart
    const populationCtx = document.getElementById('populationChart');
    if (populationCtx) {
        const populationData = [
            {{ $barangayPopulations['ems_barrio']['total'] ?? 0 }},
            {{ $barangayPopulations['ems_barrio_south']['total'] ?? 0 }},
            {{ $barangayPopulations['ems_barrio_east']['total'] ?? 0 }}
        ];
        
        new Chart(populationCtx, {
            type: 'bar',
            data: {
                labels: ['Em\'s Barrio', 'Em\'s Barrio South', 'Em\'s Barrio East'],
                datasets: [{
                    data: populationData,
                    backgroundColor: ['#2E86C1', '#1B4F72', '#3498DB'],
                    borderColor: ['#2E86C1', '#1B4F72', '#3498DB'],
                    borderWidth: 1
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
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Rating Distribution Chart
    const ratingCtx = document.getElementById('ratingChart');
    if (ratingCtx) {
        // Create a safe rating distribution object
        const ratingDistribution = {
            1: {{ $ratingStats['rating_distribution'][1] ?? 0 }},
            2: {{ $ratingStats['rating_distribution'][2] ?? 0 }},
            3: {{ $ratingStats['rating_distribution'][3] ?? 0 }},
            4: {{ $ratingStats['rating_distribution'][4] ?? 0 }},
            5: {{ $ratingStats['rating_distribution'][5] ?? 0 }}
        };
        
        new Chart(ratingCtx, {
            type: 'bar',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    data: [
                        ratingDistribution[1],
                        ratingDistribution[2],
                        ratingDistribution[3],
                        ratingDistribution[4],
                        ratingDistribution[5]
                    ],
                    backgroundColor: ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#27ae60']
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
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Monthly Rating Chart
    const monthlyRatingCtx = document.getElementById('monthlyRatingChart');
    if (monthlyRatingCtx) {
        new Chart(monthlyRatingCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Average Rating',
                    data: [4.2, 4.3, 4.1, 4.4, 4.5, 4.6, 4.7, 4.5, 4.6, 4.7, 4.8, 4.7],
                    borderColor: '#3C87C4',
                    backgroundColor: 'rgba(60, 135, 196, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
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
                    y: {
                        min: 3,
                        max: 5,
                        ticks: {
                            callback: function(value) {
                                return value + '/5';
                            }
                        }
                    }
                }
            }
        });
    }

    // User Distribution Chart - Barangay Distribution
    const userDistributionCtx = document.getElementById('userDistributionChart');
    if (userDistributionCtx) {
        const barangayData = [
            {{ $barangayPopulations['ems_barrio']['total'] ?? 0 }},
            {{ $barangayPopulations['ems_barrio_south']['total'] ?? 0 }},
            {{ $barangayPopulations['ems_barrio_east']['total'] ?? 0 }}
        ];
        
        new Chart(userDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Em\'s Barrio', 'Em\'s Barrio South', 'Em\'s Barrio East'],
                datasets: [{
                    data: barangayData,
                    backgroundColor: ['#2E86C1', '#3498DB', '#5DADE2'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
</script>

</body>
</html>