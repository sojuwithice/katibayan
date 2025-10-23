<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - User Management</title>
  <link rel="stylesheet" href="{{ asset('css/user-management2.css') }}">
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
  <a href="{{ route('admindashb') }}">
    <i class="fas fa-home"></i>
    <span class="label">Dashboard</span>
  </a>
  <a href="{{ route('admin-analytics') }}" >
    <i class="fas fa-chart-pie"></i>
    <span class="label">Analytics</span>
  </a>
  <a href="{{ route('user-management2') }}" class="active">
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
            <h2>User Management</h2>
        </div>

        <section class="content-section">
            <div class="content-card">
                <h4>Manage Account</h4>
                <p class="subtitle">Manage system users and permissions.</p>

                <div class="table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Uploaded File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Juliane Dayandante</td>
                                <td>JulianeDayandante@gmail.com</td>
                                <td>093002391054</td>
                                <td>Em's Barrio South</td>
                                <td>SK Chairperson</td>
                                <td><span class="status-pending">Pending</span></td>
                                <td>
                                    <a href="#" class="file-link" data-file="oath_taking.pdf" data-type="pdf">
                                        <i class="fas fa-file-pdf"></i>
                                        Oath Taking
                                    </a>
                                </td>
                                <td class="actions">
                                    <button class="accept">Accept</button>
                                    <button class="reject">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Maria Santos</td>
                                <td>MariaSantos@gmail.com</td>
                                <td>09123456789</td>
                                <td>North Barangay</td>
                                <td>SK Treasurer</td>
                                <td><span class="status-pending">Pending</span></td>
                                <td>
                                    <a href="#" class="file-link" data-file="oath_taking.pdf" data-type="pdf">
                                        <i class="fas fa-file-pdf"></i>
                                        Oath Taking
                                    </a>
                                </td>
                                <td class="actions">
                                    <button class="accept">Accept</button>
                                    <button class="reject">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Juan Dela Cruz</td>
                                <td>JuanDelaCruz@gmail.com</td>
                                <td>09234567890</td>
                                <td>West Village</td>
                                <td>Barangay Secretary</td>
                                <td><span class="status-pending">Pending</span></td>
                                <td>
                                    <a href="#" class="file-link" data-file="oath_taking.pdf" data-type="pdf">
                                        <i class="fas fa-file-pdf"></i>
                                        Oath Taking
                                    </a>
                                </td>
                                <td class="actions">
                                    <button class="accept">Accept</button>
                                    <button class="reject">Reject</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- File Preview Modal -->
<div class="file-preview-modal" id="filePreviewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>File Preview</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="file-preview">
                <img id="previewImage" src="" alt="File Preview" style="display: none;">
                <div id="pdfPreview" style="display: none;">
                    <iframe id="pdfFrame" width="100%" height="500px" frameborder="0"></iframe>
                </div>
                <div id="unsupportedPreview" style="display: none;">
                    <i class="fas fa-file fa-5x" style="color: #3C87C4; margin-bottom: 15px;"></i>
                    <p>This file type cannot be previewed in the browser.</p>
                    <a href="#" id="downloadFile" class="file-link">
                        <i class="fas fa-download"></i>
                        Download File
                    </a>
                </div>
            </div>
            <div class="file-info">
                <h4>File Information</h4>
                <div class="file-details">
                    <strong>File Name:</strong>
                    <span id="fileName">-</span>
                    <strong>File Type:</strong>
                    <span id="fileType">-</span>
                    <strong>Uploaded By:</strong>
                    <span id="uploadedBy">-</span>
                    <strong>Upload Date:</strong>
                    <span id="uploadDate">-</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add JavaScript for interactive functionality
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
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.profile-wrapper.active, .notification-wrapper.active').forEach(el => {
                el.classList.remove('active');
            });
        });

        // Prevent dropdown close when clicking inside
        document.querySelectorAll('.profile-dropdown, .notif-dropdown').forEach(el => {
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

        // File preview functionality
        const fileLinks = document.querySelectorAll('.file-link');
        const modal = document.getElementById('filePreviewModal');
        const closeModal = document.querySelector('.close-modal');
        const previewImage = document.getElementById('previewImage');
        const pdfPreview = document.getElementById('pdfPreview');
        const pdfFrame = document.getElementById('pdfFrame');
        const unsupportedPreview = document.getElementById('unsupportedPreview');
        const downloadFile = document.getElementById('downloadFile');
        const fileName = document.getElementById('fileName');
        const fileType = document.getElementById('fileType');
        const uploadedBy = document.getElementById('uploadedBy');
        const uploadDate = document.getElementById('uploadDate');

        fileLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const file = this.getAttribute('data-file');
                const type = this.getAttribute('data-type');
                const userName = this.closest('tr').querySelector('td:first-child').textContent;
                
                // Set file info
                fileName.textContent = file;
                fileType.textContent = type.toUpperCase();
                uploadedBy.textContent = userName;
                uploadDate.textContent = new Date().toLocaleDateString();
                
                // Show appropriate preview
                previewImage.style.display = 'none';
                pdfPreview.style.display = 'none';
                unsupportedPreview.style.display = 'none';
                
                if (type === 'pdf') {
                    pdfPreview.style.display = 'block';
                    // For demo purposes, using a sample PDF URL
                    // In production, replace with your actual file path
                    pdfFrame.src = `https://docs.google.com/gview?url=https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf&embedded=true`;
                } else if (type === 'image') {
                    previewImage.style.display = 'block';
                    // For demo purposes, using a sample image URL
                    // In production, replace with your actual file path
                    previewImage.src = `https://via.placeholder.com/600x400/3C87C4/FFFFFF?text=${encodeURIComponent(file)}`;
                } else {
                    unsupportedPreview.style.display = 'block';
                    downloadFile.href = `#`; // Set actual file path in production
                    downloadFile.setAttribute('download', file);
                }
                
                modal.classList.add('active');
            });
        });

        // Close modal
        closeModal.addEventListener('click', function() {
            modal.classList.remove('active');
            // Clear iframe src when closing to stop loading
            pdfFrame.src = '';
        });

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('active');
                pdfFrame.src = '';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                modal.classList.remove('active');
                pdfFrame.src = '';
            }
        });

        // Accept/Reject button functionality
        const acceptButtons = document.querySelectorAll('.accept');
        const rejectButtons = document.querySelectorAll('.reject');

        acceptButtons.forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const statusCell = row.querySelector('.status-pending');
                const userName = row.querySelector('td:first-child').textContent;
                
                if (statusCell) {
                    statusCell.textContent = 'Approved';
                    statusCell.className = 'status-approved';
                    statusCell.style.backgroundColor = '#d4edda';
                    statusCell.style.color = '#155724';
                    
                    // Disable buttons after action
                    const actionsCell = row.querySelector('.actions');
                    actionsCell.innerHTML = '<span style="color: #28a745; font-weight: 600;">Approved</span>';
                    
                    // Show notification
                    alert(`User ${userName} has been approved successfully!`);
                }
            });
        });

        rejectButtons.forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const statusCell = row.querySelector('.status-pending');
                const userName = row.querySelector('td:first-child').textContent;
                
                if (statusCell) {
                    statusCell.textContent = 'Rejected';
                    statusCell.className = 'status-rejected';
                    statusCell.style.backgroundColor = '#f8d7da';
                    statusCell.style.color = '#721c24';
                    
                    // Disable buttons after action
                    const actionsCell = row.querySelector('.actions');
                    actionsCell.innerHTML = '<span style="color: #dc3545; font-weight: 600;">Rejected</span>';
                    
                    // Show notification
                    alert(`User ${userName} has been rejected.`);
                }
            });
        });
    });
</script>

</body>
</html>