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
  <style>
    /* Additional styles for address formatting */
    .address-cell {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .address-cell:hover {
        white-space: normal;
        overflow: visible;
        background: #f8f9fa;
        position: relative;
        z-index: 1;
    }
    
    .alert {
        padding: 12px 16px;
        margin: 16px 0;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .alert.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .no-action {
        color: #6c757d;
        font-style: italic;
    }
    
    .no-file {
        color: #6c757d;
        font-style: italic;
    }
    
    .no-data {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        padding: 20px;
    }
  </style>
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

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

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
                            @forelse ($skUsers as $user)
                                <tr>
                                    <td>{{ $user->given_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->contact_no }}</td>
                                    <td class="address-cell">
                                        @php
                                            $addressParts = [];
                                            if (!empty($user->purok_zone)) {
                                                $addressParts[] = $user->purok_zone;
                                            }
                                            if (!empty($user->barangay->name ?? null)) {
                                                $addressParts[] = $user->barangay->name;
                                            }
                                            if (!empty($user->city->name ?? null)) {
                                                $addressParts[] = $user->city->name;
                                            }
                                            if (!empty($user->province->name ?? null)) {
                                                $addressParts[] = $user->province->name;
                                            }
                                            if (!empty($user->region->name ?? null)) {
                                                $addressParts[] = $user->region->name;
                                            }
                                            if (!empty($user->zip_code)) {
                                                $addressParts[] = $user->zip_code;
                                            }
                                            
                                            echo implode(', ', $addressParts) ?: 'No address provided';
                                        @endphp
                                    </td>
                                    <td>
                                        @if($user->role === 'sk')
                                            SK Official
                                        @elseif($user->role === 'kk')
                                            KK Member
                                        @else
                                            {{ strtoupper($user->role ?? 'N/A') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->account_status === 'pending')
                                            <span class="status-pending">Pending</span>
                                        @elseif($user->account_status === 'approved')
                                            <span class="status-approved">Approved</span>
                                        @elseif($user->account_status === 'rejected')
                                            <span class="status-rejected">Rejected</span>
                                        @else
                                            <span class="status-unknown">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $filePath = null;
                                            $fileName = null;
                                            $fileType = null;
                                            
                                            if($user->role === 'sk' && optional($user->skOfficial)->oath_certificate_path) {
                                                $filePath = asset('storage/' . $user->skOfficial->oath_certificate_path);
                                                $fileName = 'Oath Certificate';
                                                $fileType = pathinfo($user->skOfficial->oath_certificate_path, PATHINFO_EXTENSION);
                                            } elseif($user->role === 'kk' && optional($user->kkMember)->barangay_indigency_path) {
                                                $filePath = asset('storage/' . $user->kkMember->barangay_indigency_path);
                                                $fileName = 'Barangay Indigency';
                                                $fileType = pathinfo($user->kkMember->barangay_indigency_path, PATHINFO_EXTENSION);
                                            }
                                        @endphp
                                        
                                        @if($filePath)
                                            <a href="#" class="file-link" 
                                               data-file="{{ $fileName }}"
                                               data-type="{{ in_array($fileType, ['pdf', 'jpg', 'jpeg', 'png', 'gif']) ? $fileType : 'other' }}"
                                               data-filepath="{{ $filePath }}"
                                               data-username="{{ $user->given_name }} {{ $user->last_name }}"
                                               data-uploaddate="{{ $user->created_at->format('M d, Y') }}">
                                                <i class="fas fa-file-pdf"></i>
                                                {{ $fileName }}
                                            </a>
                                        @else
                                            <span class="no-file">No file uploaded</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        @if($user->account_status === 'pending')
                                            {{-- Approve Button --}}
                                            <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="accept" onclick="return confirm('Are you sure you want to approve this user?')">Accept</button>
                                            </form>

                                            {{-- Reject Button --}}
                                            <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="reject" onclick="return confirm('Are you sure you want to reject this user?')">Reject</button>
                                            </form>
                                        @else
                                            <span class="no-action">
                                                @if($user->account_status === 'approved')
                                                    Approved
                                                @elseif($user->account_status === 'rejected')
                                                    Rejected
                                                @else
                                                    No actions available
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="no-data">No accounts to review</td>
                                </tr>
                            @endforelse
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
                <img id="previewImage" src="" alt="File Preview" style="display: none; max-width: 100%; max-height: 500px;">
                <div id="pdfPreview" style="display: none;">
                    <iframe id="pdfFrame" width="100%" height="500px" frameborder="0"></iframe>
                </div>
                <div id="imagePreview" style="display: none;">
                    <img id="imageFrame" src="" alt="Image Preview" style="max-width: 100%; max-height: 500px;">
                </div>
                <div id="unsupportedPreview" style="display: none; text-align: center; padding: 40px;">
                    <i class="fas fa-file fa-5x" style="color: #3C87C4; margin-bottom: 15px;"></i>
                    <p>This file type cannot be previewed in the browser.</p>
                    <a href="#" id="downloadFile" class="file-link" style="display: inline-block; margin-top: 10px;">
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
        const imagePreview = document.getElementById('imagePreview');
        const imageFrame = document.getElementById('imageFrame');
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
                const filePath = this.getAttribute('data-filepath');
                const userName = this.getAttribute('data-username');
                const uploadDateValue = this.getAttribute('data-uploaddate');
                
                // Set file info
                fileName.textContent = file;
                fileType.textContent = type.toUpperCase();
                uploadedBy.textContent = userName;
                uploadDate.textContent = uploadDateValue || new Date().toLocaleDateString();
                
                // Show appropriate preview
                previewImage.style.display = 'none';
                pdfPreview.style.display = 'none';
                imagePreview.style.display = 'none';
                unsupportedPreview.style.display = 'none';
                
                if (type === 'pdf') {
                    pdfPreview.style.display = 'block';
                    pdfFrame.src = filePath;
                } else if (['jpg', 'jpeg', 'png', 'gif'].includes(type)) {
                    imagePreview.style.display = 'block';
                    imageFrame.src = filePath;
                } else {
                    unsupportedPreview.style.display = 'block';
                    downloadFile.href = filePath;
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
    });
</script>

</body>
</html>