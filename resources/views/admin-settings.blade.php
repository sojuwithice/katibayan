<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KatiBayan - Admin Settings</title>
    <link rel="stylesheet" href="{{ asset('css/admin-settings.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            <!-- ADMIN SETTINGS SECTION (MOVED TO TOP) -->
            <div class="settings-card">
                <div class="card-header-blue">
                    <i class="fas fa-cogs"></i> Admin Settings
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
        <label id="maintenanceLabel">Maintenance Mode</label>
        <p>Temporarily disable the site for non-admin users.</p>
    </div>
    <label class="toggle-switch">
        <input type="checkbox" id="maintenanceToggle">
        <span class="slider"></span>
    </label>
</div>

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

                </div>
            </div>

            <!-- SYSTEM LOGS SECTION -->
            <div class="settings-card">
                <div class="card-header-blue">
                    <i class="fas fa-clipboard-list"></i> System Logs
                </div>
                <div class="card-body">
                    
                    <!-- Log Statistics -->
                    <div class="log-stats">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: #6f42c1;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Error Logs</h3>
                                <p class="stat-value" id="errorLogsCount">0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background: #20c997;">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Info Logs</h3>
                                <p class="stat-value" id="infoLogsCount">0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background: #fd7e14;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Last Cleanup</h3>
                                <p class="stat-value" id="lastLogCleanup">Never</p>
                            </div>
                        </div>
                    </div>

                    <!-- Log Actions -->
                    <div class="log-actions">
                        <button class="btn btn-primary" onclick="viewLogs('error')">
                            <i class="fas fa-exclamation-triangle"></i> View Error Logs
                        </button>
                        <button class="btn btn-secondary" onclick="viewLogs('all')">
                            <i class="fas fa-list"></i> View All Logs
                        </button>
                        <button class="btn btn-warning" onclick="clearOldLogs()">
                            <i class="fas fa-broom"></i> Clear Old Logs
                        </button>
                        <button class="btn btn-danger" onclick="clearAllLogs()">
                            <i class="fas fa-trash"></i> Clear All Logs
                        </button>
                    </div>

                    <!-- Log Filters -->
                    <div class="log-filters">
                        <div class="form-group-inline">
                            <label>Log Level:</label>
                            <select id="logLevelFilter" class="form-control-small">
                                <option value="all">All Levels</option>
                                <option value="error">Error</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                                <option value="debug">Debug</option>
                            </select>
                        </div>
                        <div class="form-group-inline">
                            <label>Date Range:</label>
                            <input type="date" id="logDateFrom" class="form-control-small">
                            <span>to</span>
                            <input type="date" id="logDateTo" class="form-control-small">
                        </div>
                        <button class="btn btn-secondary" onclick="filterLogs()">
                            <i class="fas fa-filter"></i> Filter Logs
                        </button>
                    </div>

                    <!-- Log Files List -->
                    <div class="log-files">
                        <h3>Log Files</h3>
                        <div class="table-responsive">
                            <table class="log-table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Size</th>
                                        <th>Last Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="logFilesBody">
                                    <tr>
                                        <td colspan="4" class="text-center">Loading log files...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Log Settings -->
                    <div class="log-settings">
                        <h3>Log Settings</h3>
                        <div class="form-group">
                            <div class="form-info">
                                <label>Log Retention</label>
                                <p>Automatically delete logs older than specified days</p>
                            </div>
                            <select class="form-control" id="logRetentionDays" style="width: 150px;">
                                <option value="7">7 days</option>
                                <option value="30" selected>30 days</option>
                                <option value="90">90 days</option>
                                <option value="365">1 year</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-info">
                                <label>Log Level</label>
                                <p>Set the minimum log level to record</p>
                            </div>
                            <select class="form-control" id="minLogLevel" style="width: 150px;">
                                <option value="debug">Debug (All)</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error" selected>Error</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-info">
                                <label>Log Directory</label>
                                <p id="logPath">storage/logs/</p>
                            </div>
                            <button class="btn btn-secondary" onclick="copyLogPath()">
                                <i class="fas fa-copy"></i> Copy Path
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- DATABASE BACKUP SYSTEM -->
            <div class="settings-card">
                <div class="card-header-blue">
                    <i class="fas fa-database"></i> Database Backup Management
                </div>
                <div class="card-body">
                    <!-- Backup Statistics -->
                    <div class="backup-stats">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Database Size</h3>
                                <p class="stat-value" id="dbSize">Loading...</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-hdd"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Total Backups</h3>
                                <p class="stat-value" id="totalBackups">0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Last Backup</h3>
                                <p class="stat-value" id="lastBackup">Never</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="backup-actions">
                        <button class="btn btn-primary" id="createBackupBtn">
                            <i class="fas fa-plus-circle"></i> Create Backup Now
                        </button>
                        <button class="btn btn-secondary" id="downloadAllBtn">
                            <i class="fas fa-download"></i> Download All
                        </button>
                        <button class="btn btn-danger" id="cleanupBtn">
                            <i class="fas fa-trash"></i> Clean Old Backups
                        </button>
                    </div>

                    <!-- Backup Files Table -->
                    <div class="backup-list">
                        <h3>Available Backups</h3>
                        <div class="table-responsive">
                            <table class="backup-table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Size</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="backupListBody">
                                    <!-- Backups will be loaded here -->
                                    <tr>
                                        <td colspan="4" class="text-center">Loading backups...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Backup Settings -->
                    <div class="backup-settings">
                        <h3>Backup Settings</h3>
                        <div class="form-group">
                            <div class="form-info">
                                <label>Automatic Daily Backup</label>
                                <p>Automatically create backup at 2:00 AM daily</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="autoBackup" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="form-info">
                                <label>Backup Retention</label>
                                <p>Delete backups older than:</p>
                            </div>
                            <select class="form-control" id="retentionDays" style="width: 150px;">
                                <option value="7">7 days</option>
                                <option value="30" selected>30 days</option>
                                <option value="90">90 days</option>
                                <option value="365">1 year</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-info">
                                <label>Backup Path</label>
                                <p id="backupPath">storage/app/backups/</p>
                            </div>
                            <button class="btn btn-secondary" onclick="copyBackupPath()">
                                <i class="fas fa-copy"></i> Copy Path
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END DATABASE BACKUP SYSTEM -->
            
            <div class="settings-footer">
                <button class="btn btn-primary">Save All Changes</button>
            </div>
            
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal" id="progressModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Processing</h3>
        </div>
        <div class="modal-body">
            <div class="progress-container">
                <div class="progress-bar" id="backupProgress"></div>
            </div>
            <p id="progressText">Processing...</p>
            <div class="progress-details" id="progressDetails"></div>
        </div>
    </div>
</div>

<!-- Log Viewer Modal -->
<div class="modal" id="logViewerModal">
    <div class="modal-content" style="max-width: 90%; width: 90%;">
        <div class="modal-header">
            <h3><i class="fas fa-file-alt"></i> Log Viewer</h3>
            <button class="close-modal" onclick="closeModal('logViewerModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="log-viewer-header">
                <div class="log-file-info">
                    <strong id="logFileName">Loading...</strong>
                    <small id="logFileSize" class="text-muted"></small>
                </div>
                <div class="log-viewer-actions">
                    <button class="btn btn-sm btn-secondary" onclick="refreshLogViewer()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="clearCurrentLog()">
                        <i class="fas fa-trash"></i> Clear Log
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="downloadCurrentLog()">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
            </div>
            <div class="log-content-container">
                <pre id="logContent" class="log-content">Loading log content...</pre>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal" id="confirmModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Action</h3>
            <button class="close-modal" onclick="closeModal('confirmModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmMessage">Are you sure?</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal('confirmModal')">Cancel</button>
                <button class="btn btn-danger" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Maintenance Mode Functions
document.addEventListener('DOMContentLoaded', function() {
    // Check maintenance mode status
    checkMaintenanceStatus();
    
    // Add event listener to maintenance mode toggle
    const maintenanceToggle = document.querySelector('input[type="checkbox"][onchange*="toggleMaintenance"]');
    if (maintenanceToggle) {
        maintenanceToggle.addEventListener('change', function() {
            toggleMaintenanceMode(this.checked);
        });
    } else {
        // If toggle doesn't exist, find it by label
        const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
        if (toggleSwitch) {
            toggleSwitch.addEventListener('change', function() {
                toggleMaintenanceMode(this.checked);
            });
        }
    }
});

// Check maintenance status
async function checkMaintenanceStatus() {
    try {
        const response = await fetch('/admin/maintenance/status');
        const data = await response.json();
        
        if (data.success) {
            const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
            if (toggleSwitch) {
                toggleSwitch.checked = data.enabled;
                
                // Update toggle label
                const label = toggleSwitch.closest('.form-group').querySelector('label');
                if (label) {
                    if (data.enabled) {
                        label.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Maintenance Mode (ON)';
                    } else {
                        label.innerHTML = 'Maintenance Mode';
                    }
                }
            }
            
            // If enabled, show maintenance info modal
            if (data.enabled && data.data) {
                showMaintenanceInfo(data.data);
            }
        }
    } catch (error) {
        console.error('Error checking maintenance status:', error);
    }
}

// Toggle maintenance mode
async function toggleMaintenanceMode(enabled) {
    if (enabled) {
        // Show maintenance settings modal
        showMaintenanceSettingsModal();
    } else {
        // Disable maintenance mode
        const confirmed = confirm('Disable maintenance mode? The site will be accessible to all users.');
        if (confirmed) {
            await disableMaintenanceMode();
        } else {
            // Revert toggle
            const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
            if (toggleSwitch) {
                toggleSwitch.checked = false;
            }
        }
    }
}

// Show maintenance settings modal
function showMaintenanceSettingsModal() {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fas fa-cogs"></i> Enable Maintenance Mode</h3>
                <button class="close-modal" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="maintenanceMessage">Message for Users:</label>
                    <textarea id="maintenanceMessage" class="form-control" rows="3" style="width: 100%;">
The site is currently under maintenance. We apologize for any inconvenience. Please check back later.
                    </textarea>
                </div>
                
                <div class="form-group">
                    <label for="allowedIps">Allowed IPs (comma-separated):</label>
                    <input type="text" id="allowedIps" class="form-control" placeholder="192.168.1.1, 203.0.113.5" style="width: 100%;">
                    <small class="text-muted">Leave empty to block all IPs. Admin IP: ${getCurrentIP()}</small>
                </div>
                
                <div class="form-group">
                    <label for="retryAfter">Estimated Duration (minutes):</label>
                    <select id="retryAfter" class="form-control" style="width: 100%;">
                        <option value="15">15 minutes</option>
                        <option value="30">30 minutes</option>
                        <option value="60" selected>1 hour</option>
                        <option value="120">2 hours</option>
                        <option value="240">4 hours</option>
                        <option value="480">8 hours</option>
                    </select>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> Enabling maintenance mode will block all non-admin users from accessing the site.
                </div>
                
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="this.parentElement.parentElement.parentElement.remove()">
                        Cancel
                    </button>
                    <button class="btn btn-primary" onclick="enableMaintenanceMode()">
                        <i class="fas fa-toggle-on"></i> Enable Maintenance
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Enable maintenance mode
async function enableMaintenanceMode() {
    const message = document.getElementById('maintenanceMessage').value;
    const allowedIps = document.getElementById('allowedIps').value.split(',').map(ip => ip.trim()).filter(ip => ip);
    const retryAfter = parseInt(document.getElementById('retryAfter').value) * 60; // Convert to seconds
    
    showProgressModal('Enabling maintenance mode...');
    
    try {
        const response = await fetch('/admin/maintenance/enable', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                allowed_ips: allowedIps,
                retry_after: retryAfter
            })
        });
        
        const data = await response.json();
        
        hideProgressModal();
        
        if (data.success) {
            showAlert('success', 'Maintenance mode enabled successfully!');
            
            // Close modal
            const modal = document.querySelector('.modal.active');
            if (modal) modal.remove();
            
            // Update toggle state
            const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
            if (toggleSwitch) {
                toggleSwitch.checked = true;
            }
            
            // Update label
            const label = toggleSwitch.closest('.form-group').querySelector('label');
            if (label) {
                label.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Maintenance Mode (ON)';
            }
            
            // Show test link
            setTimeout(() => {
                showAlert('info', '<a href="/" target="_blank" style="color: inherit; text-decoration: underline;">Test maintenance page →</a>', false);
            }, 1000);
        } else {
            showAlert('error', data.message || 'Failed to enable maintenance mode');
            
            // Revert toggle
            const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
            if (toggleSwitch) {
                toggleSwitch.checked = false;
            }
        }
    } catch (error) {
        hideProgressModal();
        showAlert('error', 'Failed to enable maintenance mode: ' + error.message);
        
        // Revert toggle
        const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
        if (toggleSwitch) {
            toggleSwitch.checked = false;
        }
    }
}

// Disable maintenance mode
async function disableMaintenanceMode() {
    showProgressModal('Disabling maintenance mode...');
    
    try {
        const response = await fetch('/admin/maintenance/disable', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        hideProgressModal();
        
        if (data.success) {
            showAlert('success', 'Maintenance mode disabled successfully!');
            
            // Update toggle state
            const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
            if (toggleSwitch) {
                toggleSwitch.checked = false;
            }
            
            // Update label
            const label = toggleSwitch.closest('.form-group').querySelector('label');
            if (label) {
                label.innerHTML = 'Maintenance Mode';
            }
        } else {
            showAlert('error', data.message || 'Failed to disable maintenance mode');
        }
    } catch (error) {
        hideProgressModal();
        showAlert('error', 'Failed to disable maintenance mode: ' + error.message);
    }
}

// Show maintenance info
function showMaintenanceInfo(data) {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle text-warning"></i> Maintenance Mode Active</h3>
                <button class="close-modal" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="maintenance-info">
                    <p><strong>Message:</strong> ${data.message || 'No message set'}</p>
                    <p><strong>Started:</strong> ${new Date(data.time * 1000).toLocaleString()}</p>
                    <p><strong>Duration:</strong> ${Math.floor(data.retry / 60)} minutes</p>
                    <p><strong>Allowed IPs:</strong> ${data.allowed?.join(', ') || 'None (admin only)'}</p>
                </div>
                
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="updateMaintenanceSettings()">
                        <i class="fas fa-edit"></i> Update Settings
                    </button>
                    <button class="btn btn-danger" onclick="disableMaintenanceMode()">
                        <i class="fas fa-toggle-off"></i> Disable
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Update maintenance settings
function updateMaintenanceSettings() {
    // Close current modal
    const modal = document.querySelector('.modal.active');
    if (modal) modal.remove();
    
    // Show settings modal
    showMaintenanceSettingsModal();
}

// Get current IP (for reference)
function getCurrentIP() {
    // This is a simple example - real IP detection would be server-side
    return 'Detecting...';
}
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

        // ============ BACKUP SYSTEM JAVASCRIPT ============
        // Load backup data
        loadBackupData();
        
        // Create Backup Button
        document.getElementById('createBackupBtn').addEventListener('click', createBackup);
        
        // Download All Button
        document.getElementById('downloadAllBtn').addEventListener('click', downloadAllBackups);
        
        // Cleanup Button
        document.getElementById('cleanupBtn').addEventListener('click', cleanupOldBackups);
        
        // Auto Backup Toggle
        document.getElementById('autoBackup').addEventListener('change', function() {
            saveSetting('auto_backup', this.checked);
        });
        
        // Retention Days Change
        document.getElementById('retentionDays').addEventListener('change', function() {
            saveSetting('backup_retention_days', this.value);
        });

        // ============ LOG SYSTEM JAVASCRIPT ============
        // Load log data
        loadLogData();
        
        // Log retention change
        document.getElementById('logRetentionDays').addEventListener('change', function() {
            saveSetting('log_retention_days', this.value);
        });
        
        // Log level change
        document.getElementById('minLogLevel').addEventListener('change', function() {
            saveSetting('min_log_level', this.value);
        });
    });

    // ============ BACKUP FUNCTIONS ============
    // Load backup data
    async function loadBackupData() {
        try {
            const response = await fetch('/admin/backup-data');
            const data = await response.json();
            
            if (data.success) {
                // Update statistics
                document.getElementById('dbSize').textContent = data.databaseSize;
                document.getElementById('totalBackups').textContent = data.totalBackups;
                
                // Format last backup date
                if (data.lastBackup) {
                    const lastBackupDate = new Date(data.lastBackup);
                    const formattedDate = lastBackupDate.toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    document.getElementById('lastBackup').textContent = formattedDate;
                } else {
                    document.getElementById('lastBackup').textContent = 'Never';
                }
                
                document.getElementById('backupPath').textContent = data.backupPath;
                
                // Update backup list
                updateBackupList(data.backups);
            } else {
                showAlert('error', 'Failed to load backup data');
            }
        } catch (error) {
            console.error('Error loading backup data:', error);
            showAlert('error', 'Failed to load backup data');
        }
    }

    // Update backup list table
    function updateBackupList(backups) {
        const tbody = document.getElementById('backupListBody');
        tbody.innerHTML = '';
        
        if (backups.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">No backups available</td>
                </tr>
            `;
            return;
        }
        
        backups.forEach(backup => {
            // Format date nicely
            const dateObj = new Date(backup.date);
            const formattedDate = dateObj.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <i class="fas fa-database"></i> ${backup.name}
                </td>
                <td><span class="badge badge-info">${backup.size}</span></td>
                <td>
                    <i class="far fa-calendar"></i> ${formattedDate}
                </td>
                <td class="action-buttons">
                    <button class="btn-icon btn-download" onclick="downloadBackup('${backup.name}')" title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn-icon btn-delete" onclick="deleteBackup('${backup.name}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Create new backup
    async function createBackup() {
        const btn = document.getElementById('createBackupBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        
        showProgressModal('Creating database backup...');
        
        try {
            const response = await fetch('/admin/backup/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            hideProgressModal();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus-circle"></i> Create Backup Now';
            
            if (data.success) {
                showAlert('success', 'Backup created successfully!');
                setTimeout(() => {
                    loadBackupData();
                }, 2000);
            } else {
                showAlert('error', data.message || 'Backup failed');
            }
        } catch (error) {
            hideProgressModal();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus-circle"></i> Create Backup Now';
            showAlert('error', 'An error occurred: ' + error.message);
        }
    }

    // Download single backup
    function downloadBackup(filename) {
        window.open(`/admin/backup/download/${filename}`, '_blank');
    }

    // Download all backups
    async function downloadAllBackups() {
        showProgressModal('Preparing backup archive...');
        
        try {
            const response = await fetch('/admin/backup/download-all');
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `katibayan_backups_${new Date().toISOString().slice(0,10)}.zip`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                showAlert('success', 'Download started');
            } else {
                showAlert('error', 'Failed to download backups');
            }
            
            hideProgressModal();
        } catch (error) {
            hideProgressModal();
            showAlert('error', 'Failed to download backups');
        }
    }

    // Delete backup
    async function deleteBackup(filename) {
        if (!confirm(`Are you sure you want to delete backup: ${filename}?`)) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/backup/delete/${filename}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showAlert('success', 'Backup deleted successfully');
                setTimeout(() => {
                    loadBackupData();
                }, 1500);
            } else {
                showAlert('error', data.message || 'Delete failed');
            }
        } catch (error) {
            showAlert('error', 'An error occurred');
        }
    }

    // Cleanup old backups
    async function cleanupOldBackups() {
        const retentionDays = document.getElementById('retentionDays').value;
        
        if (!confirm(`Delete all backups older than ${retentionDays} days?`)) {
            return;
        }
        
        showProgressModal('Cleaning up old backups...');
        
        try {
            const response = await fetch('/admin/backup/cleanup', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ days: retentionDays })
            });
            
            const data = await response.json();
            
            hideProgressModal();
            
            if (data.success) {
                showAlert('success', `Cleaned up ${data.deleted} old backups`);
                setTimeout(() => {
                    loadBackupData();
                }, 1500);
            } else {
                showAlert('error', data.message || 'Cleanup failed');
            }
        } catch (error) {
            hideProgressModal();
            showAlert('error', 'An error occurred');
        }
    }

    // Save setting
    async function saveSetting(key, value) {
        try {
            await fetch('/admin/settings/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ key, value })
            });
            showAlert('success', 'Setting saved successfully');
        } catch (error) {
            console.error('Error saving setting:', error);
            showAlert('error', 'Failed to save setting');
        }
    }

    // Copy backup path
    function copyBackupPath() {
        const path = document.getElementById('backupPath').textContent;
        navigator.clipboard.writeText(path).then(() => {
            showAlert('success', 'Backup path copied to clipboard');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            showAlert('error', 'Failed to copy path');
        });
    }

    // ============ LOG SYSTEM FUNCTIONS ============
    // Load log data
    async function loadLogData() {
        try {
            const response = await fetch('/admin/logs/data');
            const data = await response.json();
            
            if (data.success) {
                // Update statistics
                document.getElementById('errorLogsCount').textContent = data.error_count;
                document.getElementById('infoLogsCount').textContent = data.info_count;
                document.getElementById('lastLogCleanup').textContent = data.last_cleanup || 'Never';
                document.getElementById('logPath').textContent = data.log_path;
                
                // Update log files list
                updateLogFilesList(data.log_files);
            } else {
                showAlert('error', 'Failed to load log data');
            }
        } catch (error) {
            console.error('Error loading log data:', error);
            showAlert('error', 'Failed to load log data');
        }
    }

    // Update log files list
    function updateLogFilesList(logFiles) {
        const tbody = document.getElementById('logFilesBody');
        tbody.innerHTML = '';
        
        if (logFiles.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">No log files found</td>
                </tr>
            `;
            return;
        }
        
        logFiles.forEach(log => {
            // Format date nicely
            const dateObj = new Date(log.modified);
            const formattedDate = dateObj.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <i class="fas fa-file-alt"></i> ${log.name}
                    ${log.is_current ? '<span class="badge badge-primary">Current</span>' : ''}
                </td>
                <td><span class="badge badge-info">${log.size}</span></td>
                <td>
                    <i class="far fa-calendar"></i> ${formattedDate}
                </td>
                <td class="action-buttons">
                    <button class="btn-icon btn-view" onclick="viewLogFile('${log.name}')" title="View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon btn-download" onclick="downloadLogFile('${log.name}')" title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn-icon btn-clear" onclick="clearLogFile('${log.name}')" title="Clear">
                        <i class="fas fa-broom"></i>
                    </button>
                    <button class="btn-icon btn-delete" onclick="deleteLogFile('${log.name}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // View logs
    async function viewLogs(type) {
        showProgressModal(`Loading ${type} logs...`);
        
        try {
            const response = await fetch(`/admin/logs/view/${type}`);
            const data = await response.json();
            
            hideProgressModal();
            
            if (data.success) {
                showLogInModal(data.logs, `${type.charAt(0).toUpperCase() + type.slice(1)} Logs`);
            } else {
                showAlert('error', data.message || 'Failed to load logs');
            }
        } catch (error) {
            hideProgressModal();
            showAlert('error', 'Failed to load logs: ' + error.message);
        }
    }

    // View specific log file
    async function viewLogFile(filename) {
        showProgressModal(`Loading ${filename}...`);
        
        try {
            const response = await fetch(`/admin/logs/file/${filename}`);
            const data = await response.json();
            
            hideProgressModal();
            
            if (data.success) {
                document.getElementById('logFileName').textContent = filename;
                document.getElementById('logFileSize').textContent = data.size;
                document.getElementById('logContent').textContent = data.content;
                document.getElementById('logViewerModal').style.display = 'flex';
                currentLogFile = filename;
            } else {
                showAlert('error', data.message || 'Failed to load log file');
            }
        } catch (error) {
            hideProgressModal();
            showAlert('error', 'Failed to load log file: ' + error.message);
        }
    }

    // Show logs in modal
    function showLogInModal(logs, title) {
        let logContent = '';
        
        if (Array.isArray(logs)) {
            logs.forEach(log => {
                // Add color coding based on log level
                let logClass = '';
                if (log.includes('[error]')) logClass = 'log-error';
                else if (log.includes('[warning]')) logClass = 'log-warning';
                else if (log.includes('[info]')) logClass = 'log-info';
                else if (log.includes('[debug]')) logClass = 'log-debug';
                
                logContent += `<div class="${logClass}">${log}</div>`;
            });
        } else {
            logContent = logs;
        }
        
        document.getElementById('logFileName').textContent = title;
        document.getElementById('logFileSize').textContent = '';
        document.getElementById('logContent').innerHTML = logContent;
        document.getElementById('logViewerModal').style.display = 'flex';
        currentLogFile = null;
    }

    // Refresh log viewer
    function refreshLogViewer() {
        if (currentLogFile) {
            viewLogFile(currentLogFile);
        } else {
            showAlert('info', 'No log file selected');
        }
    }

    // Clear old logs
    async function clearOldLogs() {
        const days = document.getElementById('logRetentionDays').value;
        
        showConfirmModal(`Delete all logs older than ${days} days?`, async function() {
            showProgressModal('Clearing old logs...');
            
            try {
                const response = await fetch('/admin/logs/clear-old', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ days: days })
                });
                
                const data = await response.json();
                
                hideProgressModal();
                
                if (data.success) {
                    showAlert('success', `Cleared ${data.cleaned} old log files`);
                    setTimeout(() => {
                        loadLogData();
                    }, 1500);
                } else {
                    showAlert('error', data.message || 'Failed to clear old logs');
                }
            } catch (error) {
                hideProgressModal();
                showAlert('error', 'Failed to clear old logs: ' + error.message);
            }
        });
    }

    // Clear all logs
    async function clearAllLogs() {
        showConfirmModal('Clear ALL log files? This cannot be undone!', async function() {
            showProgressModal('Clearing all logs...');
            
            try {
                const response = await fetch('/admin/logs/clear-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                hideProgressModal();
                
                if (data.success) {
                    showAlert('success', 'All logs cleared successfully');
                    setTimeout(() => {
                        loadLogData();
                    }, 1500);
                } else {
                    showAlert('error', data.message || 'Failed to clear logs');
                }
            } catch (error) {
                hideProgressModal();
                showAlert('error', 'Failed to clear logs: ' + error.message);
            }
        });
    }

    // Clear current log file
    async function clearCurrentLog() {
        if (!currentLogFile) {
            showAlert('error', 'No log file selected');
            return;
        }
        
        showConfirmModal(`Clear ${currentLogFile}?`, async function() {
            showProgressModal('Clearing log file...');
            
            try {
                const response = await fetch(`/admin/logs/clear/${currentLogFile}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                hideProgressModal();
                
                if (data.success) {
                    showAlert('success', 'Log file cleared successfully');
                    viewLogFile(currentLogFile);
                    loadLogData();
                } else {
                    showAlert('error', data.message || 'Failed to clear log file');
                }
            } catch (error) {
                hideProgressModal();
                showAlert('error', 'Failed to clear log file: ' + error.message);
            }
        });
    }

    // Download log file
    function downloadLogFile(filename) {
        window.open(`/admin/logs/download/${filename}`, '_blank');
    }

    // Download current log
    function downloadCurrentLog() {
        if (currentLogFile) {
            downloadLogFile(currentLogFile);
        } else {
            showAlert('error', 'No log file selected');
        }
    }

    // Delete log file
    async function deleteLogFile(filename) {
        showConfirmModal(`Delete ${filename}? This cannot be undone!`, async function() {
            showProgressModal('Deleting log file...');
            
            try {
                const response = await fetch(`/admin/logs/delete/${filename}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                hideProgressModal();
                
                if (data.success) {
                    showAlert('success', 'Log file deleted successfully');
                    setTimeout(() => {
                        loadLogData();
                    }, 1500);
                } else {
                    showAlert('error', data.message || 'Failed to delete log file');
                }
            } catch (error) {
                hideProgressModal();
                showAlert('error', 'Failed to delete log file: ' + error.message);
            }
        });
    }

    // Filter logs
    async function filterLogs() {
        const level = document.getElementById('logLevelFilter').value;
        const dateFrom = document.getElementById('logDateFrom').value;
        const dateTo = document.getElementById('logDateTo').value;
        
        showProgressModal('Filtering logs...');
        
        try {
            const response = await fetch('/admin/logs/filter', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    level: level,
                    date_from: dateFrom,
                    date_to: dateTo
                })
            });
            
            const data = await response.json();
            
            hideProgressModal();
            
            if (data.success) {
                showLogInModal(data.logs, 'Filtered Logs');
            } else {
                showAlert('error', data.message || 'Failed to filter logs');
            }
        } catch (error) {
            hideProgressModal();
            showAlert('error', 'Failed to filter logs: ' + error.message);
        }
    }

    // Copy log path
    function copyLogPath() {
        const path = document.getElementById('logPath').textContent;
        navigator.clipboard.writeText(path).then(() => {
            showAlert('success', 'Log path copied to clipboard');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            showAlert('error', 'Failed to copy path');
        });
    }

    // ============ UTILITY FUNCTIONS ============
    // Show alert
    function showAlert(type, message, autoRemove = true) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.backup-alert');
        if (existingAlert) existingAlert.remove();
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} backup-alert`;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;
        
        const settingsContainer = document.querySelector('.settings-container');
        if (settingsContainer) {
            settingsContainer.insertBefore(alert, settingsContainer.firstChild);
            
            if (autoRemove) {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 5000);
            }
        }
    }

    // Show progress modal
    function showProgressModal(message) {
        document.getElementById('progressText').textContent = message;
        document.getElementById('progressModal').style.display = 'flex';
        document.getElementById('backupProgress').style.width = '50%';
    }

    // Hide progress modal
    function hideProgressModal() {
        document.getElementById('progressModal').style.display = 'none';
        document.getElementById('backupProgress').style.width = '0%';
    }

    // Show confirm modal
    function showConfirmModal(message, callback) {
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmModal').style.display = 'flex';
        
        const confirmBtn = document.getElementById('confirmActionBtn');
        confirmBtn.onclick = function() {
            callback();
            closeModal('confirmModal');
        };
    }

    // Close modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>

<style>
/* Backup System Styles */
.backup-stats, .log-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    border-left: 4px solid #3490dc;
}

.stat-icon {
    background: #3490dc;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-info h3 {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
}

.stat-value {
    margin: 5px 0 0;
    font-size: 18px;
    font-weight: bold;
    color: #343a40;
}

.backup-actions, .log-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.backup-list, .log-files {
    margin-bottom: 30px;
}

.backup-table, .log-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.backup-table th, .log-table th {
    background: #f8f9fa;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.backup-table td, .log-table td {
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
}

.backup-table tr:hover, .log-table tr:hover {
    background: #f8f9fa;
}

.text-center {
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-download, .btn-view {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-download:hover, .btn-view:hover {
    background: #1976d2;
    color: white;
}

.btn-delete {
    background: #ffebee;
    color: #d32f2f;
}

.btn-delete:hover {
    background: #d32f2f;
    color: white;
}

.btn-clear {
    background: #fff3e0;
    color: #f57c00;
}

.btn-clear:hover {
    background: #f57c00;
    color: white;
}

.backup-settings, .log-settings {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-top: 30px;
}

.backup-settings .form-group, .log-settings .form-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.backup-settings .form-group:last-child, .log-settings .form-group:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.backup-settings .form-info, .log-settings .form-info {
    flex: 1;
}

.backup-settings .form-info label, .log-settings .form-info label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #495057;
}

.backup-settings .form-info p, .log-settings .form-info p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.backup-settings .form-control, .log-settings .form-control {
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}

/* Log System Specific Styles */
.log-filters {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.form-group-inline {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-group-inline label {
    font-weight: 600;
    color: #495057;
    white-space: nowrap;
}

.form-control-small {
    padding: 6px 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
    width: 120px;
}

.log-content-container {
    background: #1e1e1e;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    max-height: 500px;
    overflow-y: auto;
}

.log-content {
    color: #d4d4d4;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.5;
    margin: 0;
    white-space: pre-wrap;
    word-break: break-all;
}

.log-error {
    color: #f48771;
}

.log-warning {
    color: #ffcc00;
}

.log-info {
    color: #9cdcfe;
}

.log-debug {
    color: #ce9178;
}

.log-viewer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #dee2e6;
}

.log-file-info {
    display: flex;
    flex-direction: column;
}

.log-file-info strong {
    font-size: 18px;
    color: #343a40;
}

.log-viewer-actions {
    display: flex;
    gap: 10px;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 4px;
    margin-left: 8px;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-info {
    background: #17a2b8;
    color: white;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    animation: modalSlide 0.3s;
}

@keyframes modalSlide {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #343a40;
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
    line-height: 1;
}

.modal-body {
    padding: 20px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.progress-container {
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    overflow: hidden;
    margin: 20px 0;
}

.progress-bar {
    height: 100%;
    background: #3490dc;
    width: 0%;
    transition: width 0.3s;
    animation: progressPulse 2s infinite;
}

@keyframes progressPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideDown 0.3s;
}

@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-warning {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.alert i {
    font-size: 18px;
}

.table-responsive {
    overflow-x: auto;
}

.text-muted {
    color: #6c757d !important;
}

.small {
    font-size: 12px;
}

.mt-2 {
    margin-top: 8px;
}

/* Card Section Styles */
.card-section-title {
    color: #343a40;
    font-size: 18px;
    margin-bottom: 8px;
    font-weight: 600;
}

.card-section-subtitle {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 25px;
}

.divider-light {
    height: 1px;
    background: #e9ecef;
    margin: 25px 0;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-primary {
    background: #3490dc;
    color: white;
}

.btn-primary:hover {
    background: #2779bd;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-danger {
    background: #e3342f;
    color: white;
}

.btn-danger:hover {
    background: #cc1f1a;
}

.btn-warning {
    background: #f6993f;
    color: white;
}

.btn-warning:hover {
    background: #ed8936;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}
</style>

</body>
</html>