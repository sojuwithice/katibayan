<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KatiBayan - Admin Settings</title>
    <link rel="stylesheet" href="css/admin-settings.css">
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
  <a href="{{ route('user-management2') }}">
    <i class="fas fa-users"></i>
    <span class="label">User Management</span>
  </a>
  <a href="{{ route('users-feedback') }}">
    <i class="fas fa-comments"></i>
    <span class="label">User Feedback</span>
  </a>
  <a href="{{ route('admin-settings') }}" class="active">
    <i class="fas fa-cog"></i>
    <span class="label">Settings</span>
  </a>
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
                        </div>
                </div>
                <div class="profile-wrapper">
                    <img src="https://i.pravatar.cc/80" alt="User" class="avatar" id="profileToggle">
                    <div class="profile-dropdown">
                        </div>
                </div>
            </div>
        </header>

        <h2 class="page-title">KatiBayan Settings</h2>
        <p class="page-subtitle">Manage your system</p>
        

        <div class="settings-container">

            <div class="settings-card">
                <div class="card-header-blue">
                    Update System Logo
                </div>
                <div class="card-body">
                    <div class="logo-upload-area">
                        <div class="logo-preview">
                            <img src="https://i.imgur.com/gqfG485.png" alt="Logo Preview">
                        </div>
                        <div class="logo-upload-info">
                            <strong>KatiBayan Logo</strong>
                            <p>Upload your system's logo. Recommended size: 150x150px. Supported formats: JPG, PNG, SVG. The logo will be displayed in the header and throughout the application.</p>
                            <button class="btn btn-secondary">Upload Logo</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <div class="card-header-blue">
                    Edit Contact Information
                </div>
                <div class="card-body">
                    <label class="form-label">Manage your system</label>
                    <input type="text" class="form-control-large" value="katibayan.admin@gmail.com">
                </div>
            </div>


            <div class="settings-card">
                <div class="card-header-blue">
                    Admin Settings
                </div>
                <div class="card-body">
                    
                    <h3 class="card-section-title">General Settings</h3>
                    <p class="card-section-subtitle">Manage basic site configuration</p>
                    
                    <div class="form-group">
                        <div class="form-info">
                            <label for="site-name">Site Name</label>
                            <p>This is the name that appears in the topbar and browser tab.</p>
                        </div>
                        <input type="text" id="site-name" value="KatiBayan">
                    </div>
                    
                    <div class="form-group">
                        <div class="form-info">
                            <label>Maintenance Mode</label>
                            <p>Temporarily disable the site for non-admin users.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="divider-light"></div>

                    <h3 class="card-section-title">Security & Roles</h3>
                    <p class="card-section-subtitle">Manage user permissions and security policies.</p>
                    <p class="form-info-placeholder">No security settings to configure at this time.</p>

                    <div class="divider-light"></div>

                    <h3 class="card-section-title">Email Notifications</h3>
                    <p class="card-section-subtitle">Configure which automated emails the system sends.</p>
                    
                    <div class="form-group">
                        <div class="form-info">
                            <label>Welcome Email</label>
                            <p>Send an email to new users upon registration.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="form-info">
                            <label>New Feedback Alert</label>
                            <p>Send an email to admins when new feedback is submitted.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="form-info">
                            <label>Password Reset</label>
                            <p>Allow users to reset their own passwords via email.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                </div>
            </div>
            
            <div class="settings-footer">
                <button class="btn btn-primary">Save Changes</button>
            </div>
            
        </div>
        </div>
</div>

<script>
    // This JS is for the layout (sidebar, topbar, etc.)
    document.addEventListener('DOMContentLoaded', function() {
        // Update current time
        function updateTime() {
            const now = new Date();
            const day = now.toLocaleDateString('en-US', { weekday: 'short' }).toUpperCase();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });
            document.getElementById('current-time').textContent = `${day} ${timeString}`;
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
        
        // Sidebar toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
        }

        // Close all dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            // Close profile and notification
            document.querySelectorAll('.profile-wrapper.active, .notification-wrapper.active').forEach(el => {
                if (!el.contains(e.target)) {
                   el.classList.remove('active');
                }
            });
        });
        
        // Prevent profile/notif dropdowns from closing on inner click
        document.querySelectorAll('.profile-dropdown, .notif-dropdown').forEach(el => {
            el.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

    });
</script>

</body>
</html>