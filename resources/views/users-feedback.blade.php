<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KatiBayan - Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="{{ asset('css/users-feedback.css') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
  <a href="{{ route('admin-analytics') }}" >
    <i class="fas fa-chart-pie"></i>
    <span class="label">Analytics</span>
  </a>
  <a href="{{ route('user-management2') }}" >
    <i class="fas fa-users"></i>
    <span class="label">User Management</span>
  </a>
  <a href="{{ route('users-feedback') }}" class="active">
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
                <div class="time" id="current-time">MON 10:00 AM</div>
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
                            <li>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="name-card">
            <h2>System Feedback</h2>
        </div>

        <div class="suggestions-section">
            <div class="suggestions-header">
                <div class="title-group">
                    <h3 class="suggestions-title">User feedback</h3>
                    <p class="feedback-subtitle">View all feedback and comments from users about the system.</p>
                </div>
                <div class="feedback-stats">
                    <div class="stat-card">
                        <span class="stat-number">{{ $totalFeedbacks }}</span>
                        <span class="stat-label">Total Feedback</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">{{ $pendingFeedbacks }}</span>
                        <span class="stat-label">Pending</span>
                    </div>
                </div>
            </div>

            <div class="suggestions-subheader">
                <h4 class="group-title" id="groupTitle">All Feedback</h4>

                <div class="filters">
                    <div class="custom-dropdown" data-type="month">
                        <div class="dropdown-selected">
                            <span>All Time</span>
                            <div class="icon-circle">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                        <ul class="dropdown-options">
                            <li data-value="all" class="active">All Time</li>
                            <li data-value="this">This Month</li>
                            <li data-value="last">Last Month</li>
                        </ul>
                    </div>

                    <label class="filter-label">Category:</label>

                    <div class="custom-dropdown" data-type="category">
                        <div class="dropdown-selected">
                            <span>All</span>
                            <div class="icon-circle">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                        <ul class="dropdown-options" id="category-dropdown-options">
                            <li data-value="all" class="active"><span class="category-dot all"></span> All</li>
                            <li data-value="suggestion"><span class="category-dot suggestion"></span> Suggestion</li>
                            <li data-value="bug"><span class="category-dot bug-issue"></span> Bug or Issue</li>
                            <li data-value="appreciation"><span class="category-dot appreciation"></span> Appreciation</li>
                            <li data-value="others"><span class="category-dot others"></span> Others</li>
                        </ul>
                    </div>

                    <div class="custom-dropdown" data-type="status">
                        <div class="dropdown-selected">
                            <span>All Status</span>
                            <div class="icon-circle">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                        <ul class="dropdown-options">
                            <li data-value="all" class="active">All Status</li>
                            <li data-value="pending">Pending</li>
                            <li data-value="reviewed">Reviewed</li>
                            <li data-value="resolved">Resolved</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="feedbackContainer">
                @if($feedbacks->count() > 0)
                    @php
                        $currentMonth = null;
                    @endphp
                    
                    @foreach($feedbacks as $feedback)
                        @php
                            $feedbackMonth = $feedback->created_at->format('Y-m');
                            $isThisMonth = $feedbackMonth === now()->format('Y-m');
                            $isLastMonth = $feedbackMonth === now()->subMonth()->format('Y-m');
                            
                            if ($feedbackMonth !== $currentMonth) {
                                $currentMonth = $feedbackMonth;
                                $monthDisplay = $isThisMonth ? 'this' : ($isLastMonth ? 'last' : 'older');
                        @endphp
                        
                        <div class="suggestions-group" data-month="{{ $monthDisplay }}">
                        @php } @endphp

                        <div class="suggestion-card" 
                             data-category="{{ $feedback->type }}" 
                             data-status="{{ $feedback->status }}"
                             data-id="{{ $feedback->id }}">
                            <img src="{{ $feedback->user->avatar ? asset('storage/' . $feedback->user->avatar) : 'https://i.pravatar.cc/55?img=' . ($feedback->user->id % 10) }}" 
                                 alt="User Avatar" class="suggestion-avatar">
                            <div class="suggestion-content">
                                <div class="suggestion-row">
                                    <span class="suggestion-id">#KK{{ $feedback->id }}{{ strtoupper(substr($feedback->user->given_name, 0, 2)) }}</span>
                                    @if($feedback->rating)
                                        <div class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $feedback->rating ? 'filled' : 'empty' }}">★</span>
                                            @endfor
                                        </div>
                                    @else
                                        <span class="no-rating">No rating</span>
                                    @endif
                                </div>
                                <p class="feedback-text">{{ $feedback->message }}</p>
                                <div class="user-info">
                                    <span class="user-name">{{ $feedback->user->given_name }} {{ $feedback->user->last_name }}</span>
                                    <span class="user-barangay">{{ $feedback->user->barangay->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="suggestion-meta">
                                <span class="badge {{ $feedback->type }}">{{ ucfirst($feedback->type) }}</span>
                                <span class="badge status-{{ $feedback->status }}">{{ ucfirst($feedback->status) }}</span>
                                <span class="date">{{ $feedback->created_at->format('m/d/Y g:i A') }}</span>
                                <div class="action-buttons">
                                    <button class="action-btn mark-reviewed" data-id="{{ $feedback->id }}" title="Mark as Reviewed">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="action-btn mark-resolved" data-id="{{ $feedback->id }}" title="Mark as Resolved">
                                        <i class="fas fa-flag-checkered"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if($loop->last || $feedbacks[$loop->index + 1]->created_at->format('Y-m') !== $currentMonth)
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="no-feedback">
                        <i class="fas fa-comments"></i>
                        <h3>No Feedback Yet</h3>
                        <p>User feedback will appear here once submitted.</p>
                    </div>
                @endif
            </div>

            @if($feedbacks->hasPages())
            <div class="pagination">
                {{ $feedbacks->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        setInterval(updateTime, 60000);

        // Profile dropdown toggle
        const profileToggle = document.getElementById('profileToggle');
        const profileWrapper = document.querySelector('.profile-wrapper');
        
        if (profileToggle) {
            profileToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                profileWrapper.classList.toggle('active');
            });
        }

        // Notification dropdown toggle
        const notificationWrapper = document.querySelector('.notification-wrapper');
        
        if (notificationWrapper) {
            notificationWrapper.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
                if (profileWrapper.classList.contains('active')) {
                    profileWrapper.classList.remove('active');
                }
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.profile-wrapper.active, .notification-wrapper.active').forEach(el => {
                if (!el.contains(e.target)) {
                   el.classList.remove('active');
                }
            });

            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-options.active').forEach(optionsList => {
                    optionsList.classList.remove('active');
                    optionsList.closest('.custom-dropdown').querySelector('.icon-circle').classList.remove('rotate');
                });
            }
        });

        // Prevent dropdown close when clicking inside
        document.querySelectorAll('.profile-dropdown, .notif-dropdown, .dropdown-options').forEach(el => {
            el.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Sidebar toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
        }

        // Custom Dropdown Logic
        const customDropdowns = document.querySelectorAll('.custom-dropdown');

        customDropdowns.forEach(dropdown => {
            const selected = dropdown.querySelector('.dropdown-selected');
            const optionsList = dropdown.querySelector('.dropdown-options');
            const iconCircle = selected.querySelector('.icon-circle');

            selected.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.dropdown-options.active').forEach(openOptions => {
                    if (openOptions !== optionsList) {
                        openOptions.classList.remove('active');
                        openOptions.closest('.custom-dropdown').querySelector('.icon-circle').classList.remove('rotate');
                    }
                });
                
                optionsList.classList.toggle('active');
                iconCircle.classList.toggle('rotate');
            });

            optionsList.querySelectorAll('li').forEach(option => {
                option.addEventListener('click', () => {
                    const newTextNode = option.cloneNode(true);
                    
                    const dotElement = newTextNode.querySelector('.category-dot');
                    if (dotElement) {
                         dotElement.remove();
                    }
                    const displayText = newTextNode.textContent.trim();
                    selected.querySelector('span').textContent = displayText;
                    
                    optionsList.querySelectorAll('li').forEach(li => li.classList.remove('active'));
                    option.classList.add('active');
                    
                    optionsList.classList.remove('active');
                    iconCircle.classList.remove('rotate');

                    filterSuggestions();
                });
            });
        });

        // Filtering Logic
        function filterSuggestions() {
            const monthDropdown = document.querySelector('.custom-dropdown[data-type="month"] .dropdown-options li.active');
            const categoryDropdown = document.querySelector('.custom-dropdown[data-type="category"] .dropdown-options li.active');
            const statusDropdown = document.querySelector('.custom-dropdown[data-type="status"] .dropdown-options li.active');
            
            const currentMonth = monthDropdown.getAttribute('data-value'); 
            const currentCategory = categoryDropdown.getAttribute('data-value');
            const currentStatus = statusDropdown.getAttribute('data-value');

            document.querySelectorAll('.suggestions-group').forEach(group => {
                const groupMonth = group.getAttribute('data-month');
                const shouldShowGroup = currentMonth === 'all' || groupMonth === currentMonth;
                group.style.display = shouldShowGroup ? 'block' : 'none';

                if (shouldShowGroup) {
                    group.querySelectorAll('.suggestion-card').forEach(card => {
                        const cardCategory = card.getAttribute('data-category');
                        const cardStatus = card.getAttribute('data-status');
                        
                        const shouldShowCard = (currentCategory === 'all' || cardCategory === currentCategory) &&
                                             (currentStatus === 'all' || cardStatus === currentStatus);
                        card.style.display = shouldShowCard ? 'flex' : 'none';
                    });
                }
            });
            
            const monthText = monthDropdown.textContent.trim();
            document.getElementById('groupTitle').textContent = monthText === 'All Time' ? 'All Feedback' : monthText;
        }
        
        // Initialize active states
        document.querySelectorAll('.custom-dropdown .dropdown-options li[data-value="all"]').forEach(li => {
            li.classList.add('active');
        });
        
        filterSuggestions();

        // Status update functionality
        document.querySelectorAll('.mark-reviewed, .mark-resolved').forEach(button => {
            button.addEventListener('click', function() {
                const feedbackId = this.getAttribute('data-id');
                const newStatus = this.classList.contains('mark-reviewed') ? 'reviewed' : 'resolved';
                const card = this.closest('.suggestion-card');
                
                updateFeedbackStatus(feedbackId, newStatus, card);
            });
        });

        function updateFeedbackStatus(feedbackId, newStatus, cardElement) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/admin/system-feedbacks/${feedbackId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status badge
                    const statusBadge = cardElement.querySelector('.status-pending, .status-reviewed, .status-resolved');
                    if (statusBadge) {
                        statusBadge.className = `badge status-${newStatus}`;
                        statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    }
                    
                    // Show success message
                    showNotification(`Feedback marked as ${newStatus}`, 'success');
                    
                    // Update pending count if needed
                    if (newStatus !== 'pending') {
                        updatePendingCount();
                    }
                } else {
                    throw new Error(data.message || 'Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error updating feedback status:', error);
                showNotification('Failed to update status', 'error');
            });
        }

        function updatePendingCount() {
            // You can implement AJAX call to get updated counts
            // For now, we'll just reload the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}"></i>
                <span>${message}</span>
            `;
            
            // Add styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#4CAF50' : '#f44336'};
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                z-index: 1000;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Auto-refresh feedback every 30 seconds
        setInterval(() => {
            fetch('/admin/system-feedbacks/stats')
                .then(response => response.json())
                .then(data => {
                    // Update stats if needed
                    const currentPending = parseInt(document.querySelector('.stat-card:nth-child(2) .stat-number').textContent);
                    if (currentPending !== data.pending) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error fetching stats:', error));
        }, 30000);
    });
</script>

</body>
</html>