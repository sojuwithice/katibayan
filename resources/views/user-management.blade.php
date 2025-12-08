<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KatiBayan - User Management</title>
    <link rel="stylesheet" href="{{ asset('css/user-management2.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <a href="{{ route('admin-analytics') }}">
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
                    <span class="notif-count" id="notification-count">0</span>
                    <div class="notif-dropdown">
                        <div class="notif-header">
                            <strong>Notification</strong> <span id="notif-dropdown-count">0</span>
                        </div>
                        <ul class="notif-list" id="notification-list">
                            <!-- Notifications will be loaded here -->
                        </ul>
                    </div>
                </div>
                <div class="profile-wrapper">
                    <img src="{{ auth()->guard('admin')->user()->avatar ?? 'https://i.pravatar.cc/80' }}" alt="User" class="avatar" id="profileToggle">
                    <div class="profile-dropdown">
                        <div class="profile-header">
                            <img src="{{ auth()->guard('admin')->user()->avatar ?? 'https://i.pravatar.cc/80' }}" alt="User" class="profile-avatar">
                            <div class="profile-info">
                                <h4>{{ auth()->guard('admin')->user()->given_name ?? 'Admin' }}</h4>
                                <div class="profile-badge">
                                    <span>Administrator</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <ul>
                            <li><a href="{{ route('admin.logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="welcome-card">
            <h2>User Management</h2>
            <p>Manage SK officials and KK members accounts</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <section class="content-section">
            <div class="tab-navigation">
                <button class="tab-btn active" data-tab="sk-tab">
                    SK Officials
                    <span class="tab-count" id="sk-count">{{ $skCount ?? 0 }}</span>
                </button>
                <button class="tab-btn" data-tab="kk-tab">
                    KK Members
                    <span class="tab-count" id="kk-count">{{ $kkCount ?? 0 }}</span>
                </button>
            </div>

            <div class="content-card">
                <h4>User Accounts</h4>
                <p class="subtitle">Manage and review user registration requests</p>

                {{-- Search and Filter Controls --}}
                <div class="table-controls">
                    <div class="search-box">
                        <input type="text" id="search-input" placeholder="Search by name, email, or account number...">
                    </div>
                    <div class="filter-controls">
                        {{-- SK Side Filter (Status) --}}
                        <select id="status-filter" class="filter-select" style="display: none;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="locked">Locked</option>
                        </select>
                        
                        {{-- KK Side Filter (Barangay) --}}
                        <select id="barangay-filter" class="filter-select" style="display: none;">
                            <option value="">All Barangays</option>
                            <option value="Em's Barrio">Em's Barrio</option>
                            <option value="Em's Barrio South">Em's Barrio South</option>
                            <option value="Em's Barrio East">Em's Barrio East</option>
                        </select>
                    </div>
                </div>

                {{-- SK Officials Tab --}}
                <div class="tab-content active" id="sk-tab">
                    <div class="table-container">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th width="150">Account #</th>
                                    <th width="200">Name</th>
                                    <th width="150">Contact</th>
                                    <th width="150">Barangay</th>
                                    <th width="120">Position</th>
                                    <th width="120">Status</th>
                                    <th width="150">Date Registered</th>
                                    <th width="250">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sk-table-body">
                                @php
                                    $skUsersList = $skUsers->where('role', 'sk')->sortByDesc('created_at');
                                @endphp
                                
                                @forelse ($skUsersList as $user)
                                    <tr data-user-id="{{ $user->id }}" data-role="sk" data-status="{{ $user->account_status }}" data-is-locked="{{ $user->is_locked }}" data-barangay="{{ $user->barangay->name ?? '' }}" data-barangay-id="{{ $user->barangay_id }}">
                                        <td class="compact-cell">
                                            <strong>{{ $user->account_number }}</strong>
                                        </td>
                                        <td class="compact-cell">
                                            <strong>{{ $user->given_name }} {{ $user->last_name }}</strong><br>
                                            <small>{{ $user->email }}</small>
                                        </td>
                                        <td class="compact-cell">
                                            {{ $user->contact_no }}
                                        </td>
                                        <td class="compact-cell">
                                            {{ $user->barangay->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <span class="role-badge badge-sk">SK Official</span><br>
                                            <small>{{ $user->sk_role ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($user->account_status === 'pending')
                                                <span class="status-badge status-pending">Pending</span>
                                            @elseif($user->account_status === 'approved')
                                                @if($user->is_locked)
                                                    <span class="status-badge status-locked">Locked</span>
                                                @else
                                                    <span class="status-badge status-approved">Approved</span>
                                                @endif
                                            @elseif($user->account_status === 'rejected')
                                                <span class="status-badge status-rejected">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('M d, Y') }}<br>
                                            <small>{{ $user->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="actions">
                                            {{-- For SK: Show Accept/Reject only for pending --}}
                                            @if($user->account_status === 'pending')
                                                <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn btn-accept" onclick="return confirmAction(event, 'approve', '{{ $user->given_name }}', this)">
                                                        <i class="fas fa-check"></i> Accept
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn btn-reject" onclick="return confirmAction(event, 'reject', '{{ $user->given_name }}', this)">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            @elseif($user->account_status === 'approved')
                                                {{-- For approved SK: Lock/Unlock --}}
                                                @if($user->is_locked)
                                                    <button class="action-btn btn-unlock" onclick="toggleLock({{ $user->id }}, 'unlock', this)">
                                                        <i class="fas fa-unlock"></i> Unlock
                                                    </button>
                                                @else
                                                    <button class="action-btn btn-lock" onclick="toggleLock({{ $user->id }}, 'lock', this)">
                                                        <i class="fas fa-lock"></i> Lock
                                                    </button>
                                                @endif
                                            @endif
                                            
                                            {{-- View button for all --}}
                                            <button class="action-btn btn-view" onclick="viewUserDetails({{ $user->id }})">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            
                                            {{-- Delete button for all --}}
                                            <button class="action-btn btn-delete" onclick="confirmDelete({{ $user->id }}, '{{ $user->given_name }}', '{{ $user->barangay_id }}', this)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="no-data">
                                            <i class="fas fa-users-slash"></i><br>
                                            No SK officials found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- KK Members Tab --}}
                <div class="tab-content" id="kk-tab">
                    <div class="table-container">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th width="150">Account #</th>
                                    <th width="200">Name</th>
                                    <th width="100">Age</th>
                                    <th width="150">Contact</th>
                                    <th width="150">Barangay</th>
                                    <th width="150">Education</th>
                                    <th width="150">Date Registered</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="kk-table-body">
                                @php
                                    $kkUsersList = $skUsers->where('role', 'kk')->sortByDesc('created_at');
                                @endphp
                                
                                @forelse ($kkUsersList as $user)
                                    @php
                                        $age = \Carbon\Carbon::parse($user->date_of_birth)->age;
                                    @endphp
                                    <tr data-user-id="{{ $user->id }}" data-role="kk" data-is-locked="{{ $user->is_locked }}" data-barangay="{{ $user->barangay->name ?? '' }}" data-barangay-id="{{ $user->barangay_id }}">
                                        <td class="compact-cell">
                                            <strong>{{ $user->account_number }}</strong>
                                        </td>
                                        <td class="compact-cell">
                                            <strong>{{ $user->given_name }} {{ $user->last_name }}</strong><br>
                                            <small>{{ $user->email }}</small>
                                        </td>
                                        <td>
                                            {{ $age }} <span class="age-badge">yrs</span>
                                        </td>
                                        <td class="compact-cell">
                                            {{ $user->contact_no }}
                                        </td>
                                        <td class="compact-cell">
                                            {{ $user->barangay->name ?? 'N/A' }}
                                        </td>
                                        <td class="compact-cell">
                                            {{ $user->education }}
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('M d, Y') }}<br>
                                            <small>{{ $user->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="actions">
                                            {{-- For KK: Only Lock/Unlock --}}
                                            @if($user->is_locked)
                                                <button class="action-btn btn-unlock" onclick="toggleLock({{ $user->id }}, 'unlock', this)">
                                                    <i class="fas fa-unlock"></i> Unlock
                                                </button>
                                            @else
                                                <button class="action-btn btn-lock" onclick="toggleLock({{ $user->id }}, 'lock', this)">
                                                    <i class="fas fa-lock"></i> Lock
                                                </button>
                                            @endif
                                            
                                            {{-- View button --}}
                                            <button class="action-btn btn-view" onclick="viewUserDetails({{ $user->id }})">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            
                                            {{-- Delete button --}}
                                            <button class="action-btn btn-delete" onclick="confirmDelete({{ $user->id }}, '{{ $user->given_name }}', '{{ $user->barangay_id }}', this)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="no-data">
                                            <i class="fas fa-users-slash"></i><br>
                                            No KK members found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

{{-- Loading Overlay --}}
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loading-text">Processing...</div>
        <div class="loading-subtext" id="loading-subtext">Please wait</div>
        <div class="loading-progress">
            <div class="loading-progress-bar"></div>
        </div>
    </div>
</div>

{{-- User Details Modal --}}
<div class="modal-overlay" id="user-details-modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>User Details</h3>
            <button class="modal-close" onclick="closeModal('user-details-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="user-details-content">
                <!-- User details will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-btn btn-view" onclick="closeModal('user-details-modal')">Close</button>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
            <button class="modal-close" onclick="closeModal('delete-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <p id="delete-message">Are you sure you want to delete this user?</p>
            <input type="hidden" id="user-to-delete">
            <input type="hidden" id="user-barangay-id">
            <input type="hidden" id="user-role">
        </div>
        <div class="modal-footer">
            <button class="action-btn btn-view" onclick="closeModal('delete-modal')">Cancel</button>
            <button class="action-btn btn-delete" onclick="deleteUser()">Delete User</button>
        </div>
    </div>
</div>

{{-- Lock/Unlock Confirmation Modal --}}
<div class="modal-overlay" id="lock-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-lock"></i> Account Access</h3>
            <button class="modal-close" onclick="closeModal('lock-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <p id="lock-message">Are you sure you want to lock this user account?</p>
            <input type="hidden" id="user-to-lock">
            <input type="hidden" id="lock-action">
        </div>
        <div class="modal-footer">
            <button class="action-btn btn-view" onclick="closeModal('lock-modal')">Cancel</button>
            <button class="action-btn btn-lock" id="lock-confirm-btn">Confirm</button>
        </div>
    </div>
</div>

{{-- Include SweetAlert2 from package if installed via composer --}}
@if(class_exists('RealRashid\SweetAlert\SweetAlertServiceProvider'))
    @include('sweetalert::alert')
@endif

<script>
    // Global variables
    let currentLoadingAction = null;
    let currentLoadingButton = null;

    // Loading overlay functions
    function showLoading(message = 'Processing...', submessage = 'Please wait') {
        const overlay = document.getElementById('loading-overlay');
        const loadingText = document.getElementById('loading-text');
        const loadingSubtext = document.getElementById('loading-subtext');
        
        if (overlay && loadingText && loadingSubtext) {
            loadingText.textContent = message;
            loadingSubtext.textContent = submessage;
            overlay.style.display = 'flex';
            
            // Disable body scroll
            document.body.style.overflow = 'hidden';
        }
    }

    function hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.style.display = 'none';
            // Re-enable body scroll
            document.body.style.overflow = '';
            
            // Reset button loading state if applicable
            if (currentLoadingButton) {
                currentLoadingButton.classList.remove('loading');
                currentLoadingButton.disabled = false;
                currentLoadingButton = null;
            }
        }
    }

    function setButtonLoading(button, isLoading = true) {
        if (button) {
            currentLoadingButton = button;
            if (isLoading) {
                button.classList.add('loading');
                button.disabled = true;
            } else {
                button.classList.remove('loading');
                button.disabled = false;
            }
        }
    }

    // Global functions for modal handling
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
        }
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
        }
    }

    // Check if SK official can be deleted
    async function checkSKDeletion(userId, barangayId, userName) {
        try {
            showLoading('Checking dependencies', 'Verifying if SK can be deleted...');
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch(`/admin/users/${userId}/check-delete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ barangay_id: barangayId })
            });
            
            hideLoading();
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
            
        } catch (error) {
            console.error('Error checking SK deletion:', error);
            hideLoading();
            throw error;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - Initializing user management');
        
        // Update time
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

        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        const statusFilter = document.getElementById('status-filter');
        const barangayFilter = document.getElementById('barangay-filter');

        function switchTabFilters(activeTabId) {
            if (activeTabId === 'sk-tab') {
                // Show status filter for SK, hide barangay filter
                if (statusFilter) statusFilter.style.display = '';
                if (barangayFilter) barangayFilter.style.display = 'none';
            } else if (activeTabId === 'kk-tab') {
                // Show barangay filter for KK, hide status filter
                if (statusFilter) statusFilter.style.display = 'none';
                if (barangayFilter) barangayFilter.style.display = '';
            }
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                console.log('Tab clicked:', button.getAttribute('data-tab'));
                // Remove active class from all tabs
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked tab
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                const tabContent = document.getElementById(tabId);
                if (tabContent) {
                    tabContent.classList.add('active');
                    // Switch filters based on active tab
                    switchTabFilters(tabId);
                }
            });
        });

        // Initialize filters based on default active tab (SK)
        switchTabFilters('sk-tab');

        // Profile dropdown
        const profileToggle = document.getElementById('profileToggle');
        const profileWrapper = document.querySelector('.profile-wrapper');
        
        if (profileToggle) {
            profileToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                profileWrapper.classList.toggle('active');
            });
        }

        // Notification dropdown
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

        // Search functionality
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const currentTab = document.querySelector('.tab-content.active');
                if (!currentTab) return;
                
                const currentTabId = currentTab.id;
                const tableBody = document.getElementById(currentTabId === 'sk-tab' ? 'sk-table-body' : 'kk-table-body');
                if (!tableBody) return;
                
                const rows = tableBody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Filter by status (SK side)
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                const status = this.value;
                const tableBody = document.getElementById('sk-table-body');
                if (!tableBody) return;
                
                const rows = tableBody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    if (!status) {
                        row.style.display = '';
                        return;
                    }
                    
                    const rowStatus = row.getAttribute('data-status');
                    const statusBadge = row.querySelector('.status-badge');
                    const badgeText = statusBadge ? statusBadge.textContent.toLowerCase() : '';
                    
                    let show = false;
                    if (status === 'locked') {
                        show = badgeText === 'locked';
                    } else if (status === 'pending') {
                        show = badgeText === 'pending';
                    } else if (status === 'approved') {
                        show = badgeText === 'approved';
                    } else if (status === 'rejected') {
                        show = badgeText === 'rejected';
                    } else if (status === 'active') {
                        show = badgeText === 'active';
                    }
                    
                    row.style.display = show ? '' : 'none';
                });
            });
        }

        // Filter by barangay (KK side)
        if (barangayFilter) {
            barangayFilter.addEventListener('change', function() {
                const barangay = this.value;
                const tableBody = document.getElementById('kk-table-body');
                if (!tableBody) return;
                
                const rows = tableBody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    if (!barangay) {
                        row.style.display = '';
                        return;
                    }
                    
                    const rowBarangay = row.getAttribute('data-barangay');
                    const show = rowBarangay === barangay;
                    row.style.display = show ? '' : 'none';
                });
            });
        }

        // Modal close functionality
        const closeButtons = document.querySelectorAll('.modal-close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal-overlay');
                if (modal) {
                    modal.classList.remove('active');
                }
            });
        });
        
        // Close modals when clicking outside
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modals.forEach(modal => modal.classList.remove('active'));
            }
        });

        // Initialize lock confirm button
        const lockConfirmBtn = document.getElementById('lock-confirm-btn');
        if (lockConfirmBtn) {
            lockConfirmBtn.addEventListener('click', performLockAction);
        }

        // Debug: Log counts
        console.log('SK Count Element:', document.getElementById('sk-count'));
        console.log('KK Count Element:', document.getElementById('kk-count'));
        console.log('SK Count from server:', {{ $skCount ?? 0 }});
        console.log('KK Count from server:', {{ $kkCount ?? 0 }});

        // Load notifications
        loadNotifications();
        setInterval(loadNotifications, 30000); // Refresh every 30 seconds
    });

    async function confirmAction(event, action, userName, button) {
        event.preventDefault();
        
        const actionText = action === 'approve' ? 'approve' : 'reject';
        const result = await Swal.fire({
            title: `Confirm ${actionText}`,
            text: `Are you sure you want to ${actionText} ${userName}'s account?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'approve' ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${actionText} it!`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            // Set button loading state
            setButtonLoading(button, true);
            showLoading(`${actionText.charAt(0).toUpperCase() + actionText.slice(1)}ing user account`, 'This may take a few seconds...');
            
            // Submit the form
            button.closest('form').submit();
        }
        
        return false; // Prevent default form submission
    }

    async function confirmDelete(userId, userName, barangayId, button = null) {
        if (button) {
            currentLoadingButton = button;
        }
        
        // Get user role from button's parent row
        const userRow = button ? button.closest('tr') : null;
        const userRole = userRow ? userRow.getAttribute('data-role') : 'sk';
        
        document.getElementById('user-to-delete').value = userId;
        document.getElementById('user-barangay-id').value = barangayId;
        document.getElementById('user-role').value = userRole;
        
        // Check if this is an SK official before showing modal
        if (userRole === 'sk') {
            try {
                const checkResult = await checkSKDeletion(userId, barangayId, userName);
                
                if (checkResult.can_delete) {
                    // SK can be deleted - show confirmation modal
                    document.getElementById('delete-message').textContent = 
                        `Are you sure you want to delete ${userName}'s account? This action cannot be undone.`;
                    openModal('delete-modal');
                } else {
                    // SK cannot be deleted - show error message
                    await Swal.fire({
                        icon: 'error',
                        title: 'Cannot Delete SK Official',
                        html: checkResult.message || 
                             '<p>This SK official cannot be deleted because:</p>' +
                             '<ul style="text-align: left; margin: 10px 0;">' +
                             '<li>There are KK members in this barangay</li>' +
                             '</ul>' +
                             '<p><strong>Note:</strong> You can only delete an SK official if there are no KK members in the same barangay OR if there is another SK official in the same barangay to take over.</p>',
                        confirmButtonColor: '#3C87C4'
                    });
                    return;
                }
            } catch (error) {
                console.error('Error checking SK deletion:', error);
                // Fallback to showing modal if check fails
                document.getElementById('delete-message').textContent = 
                    `Are you sure you want to delete ${userName}'s account? This action cannot be undone.`;
                openModal('delete-modal');
            }
        } else {
            // KK member - always show confirmation modal
            document.getElementById('delete-message').textContent = 
                `Are you sure you want to delete ${userName}'s account? This action cannot be undone.`;
            openModal('delete-modal');
        }
    }

    async function deleteUser() {
        const userId = document.getElementById('user-to-delete').value;
        const barangayId = document.getElementById('user-barangay-id').value;
        const userRole = document.getElementById('user-role').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        console.log('Deleting user ID:', userId, 'Role:', userRole, 'Barangay ID:', barangayId);
        
        // Show loading overlay
        showLoading('Deleting user account', 'This may take a few seconds...');
        closeModal('delete-modal');
        
        try {
            // Try DELETE method first
            let response = await fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            // If DELETE fails, try POST method
            if (!response.ok) {
                console.log('DELETE failed, trying POST method...');
                response = await fetch(`/admin/users/${userId}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
            }
            
            // Hide loading
            hideLoading();
            
            // Check if response is OK
            if (!response.ok) {
                console.error('Response not OK:', response.status, response.statusText);
                let errorMessage = `HTTP error! status: ${response.status}`;
                
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    // If response is not JSON, try to get text
                    try {
                        const text = await response.text();
                        if (text) errorMessage = text;
                    } catch (e2) {
                        // Ignore if we can't get text either
                    }
                }
                
                throw new Error(errorMessage);
            }
            
            const data = await response.json();
            console.log('Delete response:', data);
            
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Remove the user row from the table
                const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                if (userRow) {
                    userRow.remove();
                    
                    // Update counts
                    const role = userRow.getAttribute('data-role');
                    if (role === 'sk') {
                        const skCountElement = document.getElementById('sk-count');
                        if (skCountElement) {
                            let currentCount = parseInt(skCountElement.textContent) || 0;
                            skCountElement.textContent = Math.max(0, currentCount - 1);
                        }
                    } else if (role === 'kk') {
                        const kkCountElement = document.getElementById('kk-count');
                        if (kkCountElement) {
                            let currentCount = parseInt(kkCountElement.textContent) || 0;
                            kkCountElement.textContent = Math.max(0, currentCount - 1);
                        }
                    }
                }
                
                // Check if table is empty
                const currentTab = document.querySelector('.tab-content.active');
                if (currentTab) {
                    const tableBody = currentTab.querySelector('tbody');
                    if (tableBody && tableBody.children.length === 0) {
                        const noDataRow = document.createElement('tr');
                        noDataRow.innerHTML = `
                            <td colspan="8" class="no-data">
                                <i class="fas fa-users-slash"></i><br>
                                No users found
                            </td>
                        `;
                        tableBody.appendChild(noDataRow);
                    }
                }
                
            } else {
                // Show specific error message for SK deletion
                let errorMessage = data.message || 'Failed to delete user';
                if (userRole === 'sk') {
                    errorMessage = 'Cannot delete SK official. There are KK members in this barangay and no other SK official to take over.';
                }
                
                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error('Error deleting user:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: error.message || 'Failed to delete user. Please check your routes and permissions.',
                confirmButtonColor: '#dc3545'
            });
        } finally {
            hideLoading();
        }
    }

    function toggleLock(userId, action, button = null) {
        if (button) {
            currentLoadingButton = button;
        }
        document.getElementById('user-to-lock').value = userId;
        document.getElementById('lock-action').value = action;
        
        const actionText = action === 'lock' ? 'lock' : 'unlock';
        document.getElementById('lock-message').textContent = `Are you sure you want to ${actionText} this user account?`;
        
        const lockBtn = document.getElementById('lock-confirm-btn');
        if (lockBtn) {
            lockBtn.innerHTML = action === 'lock' ? '<i class="fas fa-lock"></i> Lock Account' : '<i class="fas fa-unlock"></i> Unlock Account';
            lockBtn.className = action === 'lock' ? 'action-btn btn-lock' : 'action-btn btn-unlock';
        }
        
        openModal('lock-modal');
    }

    async function performLockAction() {
        const userId = document.getElementById('user-to-lock').value;
        const action = document.getElementById('lock-action').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        console.log(`Lock action ${action} for user ID:`, userId);
        
        // Show loading overlay
        const actionText = action === 'lock' ? 'Locking' : 'Unlocking';
        showLoading(`${actionText} user account`, 'Please wait...');
        closeModal('lock-modal');
        
        try {
            const response = await fetch(`/admin/users/${userId}/lock`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ action: action })
            });
            
            // Hide loading
            hideLoading();
            
            // Check if response is OK
            if (!response.ok) {
                let errorMessage = `HTTP error! status: ${response.status}`;
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    // Ignore JSON parse error
                }
                throw new Error(errorMessage);
            }
            
            const data = await response.json();
            console.log('Lock response:', data);
            
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Update the user row status
                const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                if (userRow && data.user) {
                    const role = userRow.getAttribute('data-role');
                    
                    if (role === 'sk') {
                        const statusCell = userRow.querySelector('td:nth-child(6)'); // Status column for SK
                        if (statusCell && data.user.status_badge) {
                            statusCell.innerHTML = data.user.status_badge;
                        }
                    }
                    // For KK, we don't have status column, so we just update the actions
                    
                    // Update the is_locked attribute
                    userRow.setAttribute('data-is-locked', action === 'lock' ? 'true' : 'false');
                    
                    // Update actions cell
                    const actionsCell = userRow.querySelector('.actions');
                    const userName = userRow.querySelector('td:nth-child(2) strong')?.textContent || '';
                    const barangayId = userRow.getAttribute('data-barangay-id');
                    
                    if (actionsCell) {
                        if (role === 'sk') {
                            const userStatus = userRow.getAttribute('data-status');
                            if (userStatus === 'approved') {
                                if (action === 'lock') {
                                    actionsCell.innerHTML = `
                                        <button class="action-btn btn-unlock" onclick="toggleLock(${userId}, 'unlock', this)">
                                            <i class="fas fa-unlock"></i> Unlock
                                        </button>
                                        <button class="action-btn btn-view" onclick="viewUserDetails(${userId})">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button class="action-btn btn-delete" onclick="confirmDelete(${userId}, '${userName}', '${barangayId}', this)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    `;
                                } else {
                                    actionsCell.innerHTML = `
                                        <button class="action-btn btn-lock" onclick="toggleLock(${userId}, 'lock', this)">
                                            <i class="fas fa-lock"></i> Lock
                                        </button>
                                        <button class="action-btn btn-view" onclick="viewUserDetails(${userId})">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button class="action-btn btn-delete" onclick="confirmDelete(${userId}, '${userName}', '${barangayId}', this)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    `;
                                }
                            }
                        } else if (role === 'kk') {
                            if (action === 'lock') {
                                actionsCell.innerHTML = `
                                    <button class="action-btn btn-unlock" onclick="toggleLock(${userId}, 'unlock', this)">
                                        <i class="fas fa-unlock"></i> Unlock
                                    </button>
                                    <button class="action-btn btn-view" onclick="viewUserDetails(${userId})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="action-btn btn-delete" onclick="confirmDelete(${userId}, '${userName}', '${barangayId}', this)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                `;
                            } else {
                                actionsCell.innerHTML = `
                                    <button class="action-btn btn-lock" onclick="toggleLock(${userId}, 'lock', this)">
                                        <i class="fas fa-lock"></i> Lock
                                    </button>
                                    <button class="action-btn btn-view" onclick="viewUserDetails(${userId})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="action-btn btn-delete" onclick="confirmDelete(${userId}, '${userName}', '${barangayId}', this)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                `;
                            }
                        }
                    }
                }
            } else {
                throw new Error(data.message || 'Failed to update lock status');
            }
        } catch (error) {
            console.error('Error updating lock status:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Failed to update account status'
            });
        } finally {
            hideLoading();
        }
    }

    async function viewUserDetails(userId) {
        console.log('Fetching user details for ID:', userId);
        
        // Show loading
        showLoading('Loading user details', 'Please wait...');
        
        try {
            const response = await fetch(`/admin/users/${userId}/details`);
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('User data received:', data);
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to load user details');
            }
            
            const content = document.getElementById('user-details-content');
            
            // Build the status badge
            let statusBadge = '';
            if (data.is_locked) {
                statusBadge = '<span class="status-badge status-locked" style="margin-top: 5px; display: inline-block;">Locked</span>';
            } else {
                switch(data.account_status) {
                    case 'pending': statusBadge = '<span class="status-badge status-pending" style="margin-top: 5px; display: inline-block;">Pending</span>'; break;
                    case 'approved': statusBadge = '<span class="status-badge status-approved" style="margin-top: 5px; display: inline-block;">Approved</span>'; break;
                    case 'rejected': statusBadge = '<span class="status-badge status-rejected" style="margin-top: 5px; display: inline-block;">Rejected</span>'; break;
                    default: statusBadge = '<span class="status-badge status-active" style="margin-top: 5px; display: inline-block;">Active</span>';
                }
            }
            
            content.innerHTML = `
                <div style="display: flex; gap: 20px; margin-bottom: 20px; align-items: center;">
                    <img src="${data.avatar || 'https://i.pravatar.cc/150'}" alt="User" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid #3C87C4;">
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 10px 0; color: #3C87C4;">${data.full_name}</h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <strong>Account Number:</strong><br>
                                <span style="font-weight: bold; color: #01214A;">${data.account_number}</span>
                            </div>
                            <div>
                                <strong>Role:</strong><br>
                                <span class="role-badge ${data.role === 'sk' ? 'badge-sk' : 'badge-kk'}" style="margin-top: 5px; display: inline-block;">
                                    ${data.role === 'sk' ? 'SK Official' : 'KK Member'}
                                </span>
                            </div>
                            <div>
                                <strong>Status:</strong><br>
                                ${statusBadge}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="detail-section">
                        <h4 style="color: #3C87C4; border-bottom: 2px solid #3C87C4; padding-bottom: 5px; margin-bottom: 15px;">
                            <i class="fas fa-user-circle"></i> Personal Information
                        </h4>
                        <div class="detail-row">
                            <strong>Email:</strong>
                            <span>${data.email || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Contact Number:</strong>
                            <span>${data.contact_no || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Date of Birth:</strong>
                            <span>${data.date_of_birth || 'N/A'} (${data.age || 'N/A'} years old)</span>
                        </div>
                        <div class="detail-row">
                            <strong>Gender:</strong>
                            <span>${data.sex || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Civil Status:</strong>
                            <span>${data.civil_status || 'N/A'}</span>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h4 style="color: #3C87C4; border-bottom: 2px solid #3C87C4; padding-bottom: 5px; margin-bottom: 15px;">
                            <i class="fas fa-map-marker-alt"></i> Address Information
                        </h4>
                        <div class="detail-row">
                            <strong>Complete Address:</strong>
                            <span>${data.address || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Barangay:</strong>
                            <span>${data.barangay || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>City/Municipality:</strong>
                            <span>${data.city || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Province:</strong>
                            <span>${data.province || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Purok/Zone:</strong>
                            <span>${data.purok_zone || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Zip Code:</strong>
                            <span>${data.zip_code || 'N/A'}</span>
                        </div>
                    </div>
                    
                    ${data.role === 'sk' ? `
                    <div class="detail-section">
                        <h4 style="color: #3C87C4; border-bottom: 2px solid #3C87C4; padding-bottom: 5px; margin-bottom: 15px;">
                            <i class="fas fa-user-tie"></i> SK Position Details
                        </h4>
                        <div class="detail-row">
                            <strong>SK Position:</strong>
                            <span>${data.sk_role || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Committee Assignment:</strong>
                            <span>${data.committees || 'N/A'}</span>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="detail-section">
                        <h4 style="color: #3C87C4; border-bottom: 2px solid #3C87C4; padding-bottom: 5px; margin-bottom: 15px;">
                            <i class="fas fa-graduation-cap"></i> Education & Employment
                        </h4>
                        <div class="detail-row">
                            <strong>Education Level:</strong>
                            <span>${data.education || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Work Status:</strong>
                            <span>${data.work_status || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Youth Classification:</strong>
                            <span>${data.youth_classification || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>SK Voter Status:</strong>
                            <span>${data.sk_voter || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #eee;">
                    <h4 style="color: #3C87C4; margin-bottom: 15px;">
                        <i class="fas fa-history"></i> Account History
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="detail-row">
                            <strong>Account Created:</strong>
                            <span>${data.created_at || 'N/A'}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Last Updated:</strong>
                            <span>${data.updated_at || 'N/A'}</span>
                        </div>
                        ${data.is_locked && data.locked_at ? `
                        <div class="detail-row">
                            <strong>Account Locked:</strong>
                            <span>Yes</span>
                        </div>
                        <div class="detail-row">
                            <strong>Locked Date:</strong>
                            <span>${data.locked_at}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <style>
                    .detail-section {
                        background: #f9f9f9;
                        padding: 15px;
                        border-radius: 8px;
                        border-left: 4px solid #3C87C4;
                    }
                    .detail-row {
                        display: flex;
                        justify-content: space-between;
                        padding: 8px 0;
                        border-bottom: 1px solid #eee;
                    }
                    .detail-row:last-child {
                        border-bottom: none;
                    }
                    .detail-row strong {
                        color: #555;
                        min-width: 160px;
                    }
                    .detail-row span {
                        color: #333;
                        text-align: right;
                        flex: 1;
                    }
                </style>
            `;
            
            // Hide loading
            hideLoading();
            
            // Open the modal
            openModal('user-details-modal');
            
        } catch (error) {
            console.error('Error loading user details:', error);
            hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load user details. Please try again.'
            });
        }
    }

    async function loadNotifications() {
        try {
            const response = await fetch('/admin/notifications');
            const data = await response.json();
            
            const notificationList = document.getElementById('notification-list');
            const notificationCount = document.getElementById('notification-count');
            const dropdownCount = document.getElementById('notif-dropdown-count');
            
            if (notificationList && data.notifications) {
                notificationList.innerHTML = '';
                data.notifications.forEach(notification => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <div class="notif-icon"></div>
                        <div class="notif-content">
                            <strong>${notification.title}</strong>
                            <p>${notification.message}</p>
                            <small>${notification.time_ago}</small>
                        </div>
                        <span class="notif-dot"></span>
                    `;
                    notificationList.appendChild(li);
                });
                
                if (data.notifications.length === 0) {
                    notificationList.innerHTML = '<li style="text-align: center; color: #666; padding: 20px;">No new notifications</li>';
                }
            }
            
            if (notificationCount && dropdownCount) {
                const count = data.count || 0;
                notificationCount.textContent = count;
                dropdownCount.textContent = count;
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }
</script>
</body>
</html>