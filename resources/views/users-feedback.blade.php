<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KatiBayan - Admin Dashboard</title>
    
    <link rel="stylesheet" href="css/users-feedback.css">
    
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
                            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
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
            </div>

            <div class="suggestions-subheader">
                <h4 class="group-title">This month</h4>

                <div class="filters">
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

                    <label class="filter-label">Category:</label>

                    <div class="custom-dropdown" data-type="category">
                        <div class="dropdown-selected">
                            <span>All</span>
                            <div class="icon-circle">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                        <ul class="dropdown-options" id="category-dropdown-options">
                            <li data-value="all"><span class="category-dot all"></span> All</li>
                            <li data-value="suggestion"><span class="category-dot suggestion"></span> Suggestion</li>
                            <li data-value="bug-issue"><span class="category-dot bug-issue"></span> Bug or Issue</li>
                            <li data-value="appreciation"><span class="category-dot appreciation"></span> Appreciation</li>
                            <li data-value="others"><span class="category-dot others"></span> Others</li>
                        </ul>
                        </div>
                </div>
            </div>

            <div class="suggestions-group" data-month="this">
                <div class="suggestion-card" data-category="appreciation">
                    <img src="https://i.pravatar.cc/55?img=4" alt="User Avatar" class="suggestion-avatar">
                    <div class="suggestion-content">
                        <div class="suggestion-row">
                            <span class="suggestion-id">#KK202529G3P</span>
                            <div class="star-rating">
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star empty">★</span>
                            </div>
                        </div>
                        <p class="feedback-text">Infairness sa system ha! taray slay ka jan sa color luv it</p>
                    </div>
                    <div class="suggestion-meta">
                        <span class="badge appreciation">Appreciation</span>
                        <span class="date">09/09/2025 6:00 PM</span>
                    </div>
                </div>

                <div class="suggestion-card" data-category="appreciation">
                    <img src="https://i.pravatar.cc/55?img=5" alt="User Avatar" class="suggestion-avatar">
                    <div class="suggestion-content">
                        <div class="suggestion-row">
                            <span class="suggestion-id">#KK202529G3P</span>
                            <div class="star-rating">
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                            </div>
                        </div>
                        <p class="feedback-text">Yes na yes for the good system</p>
                    </div>
                    <div class="suggestion-meta">
                        <span class="badge appreciation">Appreciation</span>
                        <span class="date">09/09/2025 6:00 PM</span>
                    </div>
                </div>

                <div class="suggestion-card" data-category="bug-issue">
                    <img src="https://i.pravatar.cc/55?img=6" alt="User Avatar" class="suggestion-avatar">
                    <div class="suggestion-content">
                        <div class="suggestion-row">
                            <span class="suggestion-id">#KK202529G3P</span>
                            <div class="star-rating">
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                            </div>
                        </div>
                        <p class="feedback-text">Hello po, I can't click the edit profile button I think something wrong po. Pa fix po thank you!</p>
                    </div>
                    <div class="suggestion-meta">
                        <span class="badge bug-issue">Bug or Issue</span>
                        <span class="date">09/09/2025 6:00 PM</span>
                    </div>
                </div>
            </div>

            <div class="suggestions-group" data-month="last">
                <div class="suggestion-card" data-category="suggestion">
                    <img src="https://i.pravatar.cc/55?img=7" alt="User Avatar" class="suggestion-avatar">
                    <div class="suggestion-content">
                        <div class="suggestion-row">
                            <span class="suggestion-id">#KK20232504JP</span>
                            <div class="star-rating">
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                            </div>
                        </div>
                        <p class="feedback-text">Can we have dark mode for the system? It would be easier on the eyes at night.</p>
                    </div>
                    <div class="suggestion-meta">
                        <span class="badge suggestion">Suggestion</span>
                        <span class="date">08/25/2025 3:30 PM</span>
                    </div>
                </div>
                
                <div class="suggestion-card" data-category="others">
                    <img src="https://i.pravatar.cc/55?img=8" alt="User Avatar" class="suggestion-avatar">
                    <div class="suggestion-content">
                        <div class="suggestion-row">
                            <span class="suggestion-id">#KK20232503AN</span>
                            <div class="star-rating">
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star empty">★</span>
                                <span class="star empty">★</span>
                            </div>
                        </div>
                        <p class="feedback-text">The mobile version needs improvement for better user experience.</p>
                    </div>
                    <div class="suggestion-meta">
                        <span class="badge others">Others</span>
                        <span class="date">08/20/2025 10:15 AM</span>
                    </div>
                </div>
            </div>

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
    setInterval(updateTime, 60000); // Update every minute

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
                // Close profile dropdown if open
                if (profileWrapper.classList.contains('active')) {
                    profileWrapper.classList.remove('active');
                }
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            // Close profile and notification dropdowns
            document.querySelectorAll('.profile-wrapper.active, .notification-wrapper.active').forEach(el => {
                if (!el.contains(e.target)) {
                   el.classList.remove('active');
                }
            });

            // Close custom filter dropdowns
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

        // --- Custom Dropdown Logic for Month and Category ---
        const customDropdowns = document.querySelectorAll('.custom-dropdown');

        customDropdowns.forEach(dropdown => {
            const selected = dropdown.querySelector('.dropdown-selected');
            const optionsList = dropdown.querySelector('.dropdown-options');
            const iconCircle = selected.querySelector('.icon-circle');

            selected.addEventListener('click', (e) => {
                e.stopPropagation();
                // Close other open dropdowns
                document.querySelectorAll('.dropdown-options.active').forEach(openOptions => {
                    if (openOptions !== optionsList) {
                        openOptions.classList.remove('active');
                        openOptions.closest('.custom-dropdown').querySelector('.icon-circle').classList.remove('rotate');
                    }
                });
                
                // Toggle the current dropdown
                optionsList.classList.toggle('active');
                iconCircle.classList.toggle('rotate');
            });

            optionsList.querySelectorAll('li').forEach(option => {
                option.addEventListener('click', () => {
                    const newTextNode = option.cloneNode(true);
                    
                    // 1. Update the selected display
                    const dotElement = newTextNode.querySelector('.category-dot');
                    if (dotElement) {
                         dotElement.remove(); // Remove the dot span for clean display
                    }
                    const displayText = newTextNode.textContent.trim();
                    selected.querySelector('span').textContent = displayText;
                    
                    // 2. Update active class in options
                    optionsList.querySelectorAll('li').forEach(li => li.classList.remove('active'));
                    option.classList.add('active');
                    
                    // 3. Close the dropdown
                    optionsList.classList.remove('active');
                    iconCircle.classList.remove('rotate');

                    // 4. Apply filtering
                    filterSuggestions();
                });
            });
        });

        // --- FIXED Filtering Logic ---
        function filterSuggestions() {
            // Find the active list item for each filter
            const monthDropdown = document.querySelector('.custom-dropdown[data-type="month"] .dropdown-options li.active');
            const categoryDropdown = document.querySelector('.custom-dropdown[data-type="category"] .dropdown-options li.active');
            
            // Get the current filter values
            const currentMonth = monthDropdown.getAttribute('data-value'); 
            const currentCategory = categoryDropdown.getAttribute('data-value');

            // 1. Filter by Month
            document.querySelectorAll('.suggestions-group').forEach(group => {
                const groupMonth = group.getAttribute('data-month');
                const shouldShowGroup = currentMonth === 'all' || groupMonth === currentMonth;
                group.style.display = shouldShowGroup ? 'block' : 'none';

                // 2. Filter Cards within visible month groups by Category
                if (shouldShowGroup) {
                    group.querySelectorAll('.suggestion-card').forEach(card => {
                        const cardCategory = card.getAttribute('data-category');
                        const shouldShowCard = currentCategory === 'all' || cardCategory === currentCategory;
                        card.style.display = shouldShowCard ? 'flex' : 'none';
                    });
                }
            });
            
            // Update the group title text
            const monthText = monthDropdown.textContent.trim();
            document.querySelector('.group-title').textContent = (monthText === 'All') ? 'All Feedback' : monthText;
        }
        
        // Initialize the active state for the default values ('This month' and 'All')
        document.querySelector('.custom-dropdown[data-type="month"] .dropdown-options li[data-value="this"]').classList.add('active');
        document.querySelector('.custom-dropdown[data-type="category"] .dropdown-options li[data-value="all"]').classList.add('active');
        
        // Initial filter application on page load
        filterSuggestions();
    });
</script>

</body>
</html>