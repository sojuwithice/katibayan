<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Profile Page</title>
  <link rel="stylesheet" href="{{ asset('css/profilepage.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('dashboard.index') }}">
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>
      <div class="profile-item nav-item">
        <a href="#" class="profile-link">
          <i data-lucide="circle-user"></i>
          <span class="label">Profile</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('profilepage') }}" class="active">My Profile</a>
          <a href="{{ route('certificatepage') }}">Certificates</a>
        </div>
      </div>

      <a href="{{ route('eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <a href="{{ route('evaluation') }}">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
      </a>

        <a href="{{ route('serviceoffers') }}">
          <i data-lucide="hand-heart"></i>
          <span class="label">Service Offer</span>
        </a>

    </nav>
  </aside>

  <!-- Main -->
  <div class="main">

    <!-- Topbar -->
    <header class="topbar">
      <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div class="logo-text">
          <span class="title">Katibayan</span>
          <span class="subtitle">Web Portal</span>
        </div>
      </div>

      <div class="topbar-right">
        <div class="time" id="currentTime">Loading...</div>

        <!-- Notifications -->
        <div class="notification-wrapper">
          <i class="fas fa-bell"></i>
          @if($totalNotificationCount > 0)
            <span class="notif-count">{{ $totalNotificationCount }}</span>
          @endif
          <div class="notif-dropdown">
            <div class="notif-header">
              <strong>Notification</strong>
              @if($totalNotificationCount > 0)
                <span>{{ $totalNotificationCount }}</span>
              @endif
            </div>
            
            <ul class="notif-list">
              @foreach ($generalNotifications as $notif)
                @php
                  $link = '#'; // Default
                  if ($notif->type == 'certificate_schedule') {
                    $link = route('certificatepage'); 
                  }
                @endphp
                
                <li>
                  <a href="{{ $link }}" class="notif-link {{ $notif->is_read == 0 ? 'unread' : '' }}" data-id="{{ $notif->id }}">
                    
                    <div class="notif-dot-container">
                      @if ($notif->is_read == 0)
                        <span class="notif-dot"></span>
                      @else
                        <span class="notif-dot-placeholder"></span>
                      @endif
                    </div>

                    <div class="notif-main-content">
                      <div class="notif-header-line">
                        <strong>{{ $notif->title }}</strong>
                        <span class="notif-timestamp">
                          {{ $notif->created_at->format('m/d/Y g:i A') }}
                        </span>
                      </div>
                      <p class="notif-message">{{ $notif->message }}</p>
                    </div>
                  </a>
                </li>
              @endforeach

              @foreach($unevaluatedEvents as $event)
                <li>
                  <a href="{{ route('evaluation.show', $event->id) }}" class="notif-link unread" data-event-id="{{ $event->id }}">
                    
                    <div class="notif-dot-container">
                      <span class="notif-dot"></span>
                    </div>
                    
                    <div class="notif-main-content">
                      <div class="notif-header-line">
                        <strong>Program Evaluation Required</strong>
                        @if($event->attendances->first())
                          <span class="notif-timestamp">
                            {{ $event->attendances->first()->created_at->format('m/d/Y g:i A') }}
                          </span>
                        @endif
                      </div>
                      <p class="notif-message">Please evaluate "{{ $event->title }}"</p>
                    </div>
                  </a>
                </li>
              @endforeach

              @if($generalNotifications->isEmpty() && $unevaluatedEvents->isEmpty())
                <li class="no-notifications">
                  <p>No new notifications</p>
                </li>
              @endif
            </ul>
          </div>
        </div>

        <!-- Profile Avatar -->
        <div class="profile-wrapper">
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ $user ? $user->given_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name . ' ' . ($user->suffix ? $user->suffix : '') : 'Guest User' }}</h4>
                <div class="profile-badge">
                  <span class="badge">{{ $roleBadge }}</span>
                  <span class="badge">{{ $age }} yrs old</span>
                </div>
              </div>
            </div>
            <hr>
            <ul class="profile-menu">
              <li>
                <a href="{{ route('profilepage') }}">
                  <i class="fas fa-user"></i> Profile
                </a>
              </li>
              <li><i class="fas fa-cog"></i> Manage Password</li>
              <li>
                <a href="{{ route('faqspage') }}">
                  <i class="fas fa-question-circle"></i> FAQs
                </a>
              </li>
              <li><i class="fas fa-star"></i> Send Feedback to Katibayan</li>
              <li class="logout-item">
                <a href="#" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
          </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </header>

    <div class="profile-calendar">
      <!-- Profile Card -->
      <div class="profile-card">
        <div class="avatar-wrapper">
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="profile-avatar" id="profileAvatar">
          <div class="avatar-overlay" id="avatarOverlay">
            <i class="fas fa-camera"></i>
          </div>
        </div>
        <div class="profile-info">
          <h2>
            {{ $user ? $user->given_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name . ' ' . ($user->suffix ? $user->suffix : '') : 'Guest User' }} 
            <span>|</span> 
            <span class="age">{{ $age }}</span> 
            <span class="years">years old</span>
          </h2>
          
          <!-- badges row -->
          <div class="badges-row">
            <span class="badge blue">{{ $roleBadge }}</span>
            <span class="status">{{ $user && $user->sk_voter === 'Yes' ? 'Registered Voter' : 'Not Registered' }}</span>
          </div>

          <!-- address row -->
          <div class="address">
            <i class="fas fa-location-dot"></i> 
            @if($user && $user->purok_zone)
              {{ $user->purok_zone }}, 
              @if($user->barangay){{ $user->barangay->name }}, @endif
              @if($user->city){{ $user->city->name }}, @endif
              @if($user->province){{ $user->province->name }}, @endif
              @if($user->region){{ $user->region->name }} @endif
              {{ $user->zip_code }}
            @else
              Address not available
            @endif
          </div>
        </div>
        <button class="edit-btn" id="profileEditBtn"><i class="fas fa-pen-to-square"></i></button>
      </div>

      <!-- Calendar -->
      <div class="calendar">
        <header>
          <button class="prev"><i class="fas fa-chevron-left"></i></button>
          <h3></h3>
          <button class="next"><i class="fas fa-chevron-right"></i></button>
          <i class="fas fa-calendar calendar-toggle" title="View full month"></i>
        </header>
        <div class="days"></div>
      </div>
    </div> 

    <div class="main-content">
      <!-- Profile Message -->
      <div id="profileMessage" class="profile-message" style="display: none;"></div>

      <div class="content-grid">
        
        <!-- Left Column -->
        <div class="left-column">
          <!-- Progress + Evaluation -->
          <div class="progress-eval-row">
            <div class="progress-card">
                <h3>Progress</h3>
                <div class="progress-circle">75%</div>
                <p>Still a long journey ahead!<p>
            </div>
            <div class="evaluation-card">
              <h3>Evaluated Programs</h3>
              <div class="progress-wrapper">
                <span class="progress-number">3</span>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: 60%;"></div>
                </div>
              </div>
              <p>You have 3 events/programs to evaluate</p>
            </div>
          </div>

          <!-- Email + Password -->
          <div class="credentials-card">
            <button class="settings-btn"><i class="fas fa-gear"></i></button>
            <h3>KatiBayan Account
              <i class="fas fa-key key-icon"></i>
            </h3>
            <div class="field">
                <label>Email</label>
                <p>{{ $user ? $user->email : 'No email available' }}</p>
            </div>
            <div class="field password-field">
                  <label>Default Password</label>
                  <div class="password-wrapper">
                      <p id="tempPassword">••••••••</p>
                     <i class="fas fa-eye toggle-password" onclick="togglePassword('{{ $defaultPassword }}')"></i>
                  </div>
              </div>
          </div>

          <!-- Password Modal -->
          <div class="modal-overlay" id="passwordModal">
            <div class="modal">
              <span class="close-btn" id="closeModal">&times;</span>
              <h2>Manage Account Password</h2>
              <p class="info-text">
                <i class="fas fa-circle-info info-icon"></i>
                Ensure your account's security by following the required password format when changing your password
              </p>

              <form id="changePasswordForm">
                @csrf
                <label class="required">Current Password</label>
                <input type="password" id="currentPassword" name="current_password" required>

                <label class="required">New Password</label>
                <input type="password" id="newPassword" name="new_password" required>

                <label class="required">Confirm Password</label>
                <input type="password" id="confirmPassword" name="new_password_confirmation" required>

                <div class="show-pass">
                  <input type="checkbox" id="showPass"> <label for="showPass">Show Password</label>
                </div>

                <p class="req-heading">Required Password Format:</p>
                <div class="requirements">
                  <p id="req-length" class="invalid"> Must be 8 characters or more</p>
                  <p id="req-upper" class="invalid"> At least one uppercase letter</p>
                  <p id="req-lower" class="invalid"> At least one lowercase letter</p>
                  <p id="req-number" class="invalid"> At least one number</p>
                  <p id="req-symbol" class="invalid"> At least one symbol</p>
                  <p id="req-match" class="invalid"> Passwords must match</p>
                </div>

                <button type="submit" class="save-btn" id="savePasswordBtn">Save Changes</button>
              </form>
            </div>
          </div>

          <!-- Success Modal -->
          <div class="success-overlay" id="successModal">
            <div class="modal success-modal">
              <h2>Your password has been changed successfully</h2>
              <p class="subtitle">Please don't forget your password</p>
              <button class="ok-btn" id="okBtn">OK</button>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
          <div class="kk-profile card">
            <h3>KK Profile</h3>
            <p class="subtitle">
              The KK profiling is an organized summary of the demographic information of the Katipunan ng Kabataan members.
              This provides a clear basis for developing programs and policies that respond to the needs of the youth sector.
            </p>
            <hr>
            <div class="profile-details">
              <button class="edit-btn" id="detailsEditBtn"><i class="fas fa-pen-to-square"></i></button>
              <div class="details-scroll" id="profileDetails">
                <!-- Edit Buttons -->
                <div class="edit-buttons">
                  <button class="cancel-btn" id="cancelEditBtn">Cancel</button>
                  <button class="save-btn-profile" id="saveProfileBtn">Save Changes</button>
                </div>

                <h4>I. Profile</h4>
                <h3>Name of Respondent</h3>
                
                <!-- Last Name -->
                <div class="field-group">
                  <div class="field-label">Last Name:</div>
                  <div class="view-text">{{ $user ? $user->last_name : 'N/A' }}</div>
                  <input type="text" class="edit-input" name="last_name" value="{{ $user ? $user->last_name : '' }}" placeholder="Last Name">
                </div>

                <!-- First Name -->
                <div class="field-group">
                  <div class="field-label">First Name:</div>
                  <div class="view-text">{{ $user ? $user->given_name : 'N/A' }}</div>
                  <input type="text" class="edit-input" name="given_name" value="{{ $user ? $user->given_name : '' }}" placeholder="First Name">
                </div>

                <!-- Middle Name -->
                <div class="field-group">
                  <div class="field-label">Middle Name:</div>
                  <div class="view-text">{{ $user && $user->middle_name ? $user->middle_name : 'N/A' }}</div>
                  <input type="text" class="edit-input" name="middle_name" value="{{ $user ? $user->middle_name : '' }}" placeholder="Middle Name">
                </div>

                <!-- Suffix -->
                <div class="field-group">
                  <div class="field-label">Suffix:</div>
                  <div class="view-text">{{ $user && $user->suffix ? $user->suffix : 'N/A' }}</div>
                  <input type="text" class="edit-input" name="suffix" value="{{ $user ? $user->suffix : '' }}" placeholder="Suffix">
                </div>

                <!-- Age (Read-only) -->
                <div class="field-group">
                  <div class="field-label">Age:</div>
                  <div>{{ $age }}</div>
                </div>

                <!-- Address (Read-only) -->
                <div class="field-group">
                  <div class="field-label">Address:</div>
                  <div>
                    @if($user && $user->purok_zone)
                      {{ $user->purok_zone }}, 
                      @if($user->barangay){{ $user->barangay->name }}, @endif
                      @if($user->city){{ $user->city->name }}, @endif
                      @if($user->province){{ $user->province->name }}, @endif
                      @if($user->region){{ $user->region->name }} @endif
                      {{ $user->zip_code }}
                    @else
                      Address not available
                    @endif
                  </div>
                </div>

                <!-- Purok/Zone -->
                <div class="field-group">
                  <div class="field-label">Purok/Zone:</div>
                  <div class="view-text">{{ $user ? $user->purok_zone : 'N/A' }}</div>
                  <input type="text" class="edit-input" name="purok_zone" value="{{ $user ? $user->purok_zone : '' }}" placeholder="Purok/Zone">
                </div>

                <!-- Zip Code -->
                <div class="field-group">
                  <div class="field-label">Zip Code:</div>
                  <div class="view-text">{{ $user ? $user->zip_code : 'N/A' }}</div>
                  <input type="text" class="edit-input" name="zip_code" value="{{ $user ? $user->zip_code : '' }}" placeholder="Zip Code">
                </div>

                <!-- Date of Birth -->
                <div class="field-group">
                  <div class="field-label">Date of Birth:</div>
                  <div class="view-text">{{ $user && $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('F j, Y') : 'N/A' }}</div>
                  <input type="date" class="edit-input" name="date_of_birth" value="{{ $user ? $user->date_of_birth : '' }}">
                </div>

                <!-- Sex -->
                <div class="field-group">
                  <div class="field-label">Sex:</div>
                  <div class="view-text">{{ $user ? ucfirst($user->sex) : 'N/A' }}</div>
                  <select class="edit-input edit-select" name="sex">
                    <option value="male" {{ $user && $user->sex == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user && $user->sex == 'female' ? 'selected' : '' }}>Female</option>
                  </select>
                </div>

                <!-- Contact Number -->
                <div class="field-group">
                  <div class="field-label">Contact Number:</div>
                  <div class="view-text">{{ $user ? $user->contact_no : 'N/A' }}</div>
                  <input type="text" class="edit-input" name="contact_no" value="{{ $user ? $user->contact_no : '' }}" placeholder="Contact Number">
                </div>

                <!-- Email (Read-only) -->
                <div class="field-group">
                  <div class="field-label">Email:</div>
                  <div>{{ $user ? $user->email : 'N/A' }}</div>
                </div>
                
                <h4>II. Demographics</h4>
                <h3>Please provide your demographic details as accurately as possible</h3>

                <!-- Civil Status -->
                <div class="field-group">
                  <div class="field-label">Civil Status:</div>
                  <div class="view-text">{{ $user ? $user->civil_status : 'N/A' }}</div>
                  <select class="edit-input edit-select" name="civil_status">
                    <option value="Single" {{ $user && $user->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                    <option value="Married" {{ $user && $user->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                    <option value="Widowed" {{ $user && $user->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    <option value="Divorced" {{ $user && $user->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                    <option value="Separated" {{ $user && $user->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                  </select>
                </div>

                <!-- Educational Background -->
                <div class="field-group">
                  <div class="field-label">Educational Background:</div>
                  <div class="view-text">{{ $user ? $user->education : 'N/A' }}</div>
                  <select class="edit-input edit-select" name="education">
                    <option value="Elementary Level" {{ $user && $user->education == 'Elementary Level' ? 'selected' : '' }}>Elementary Level</option>
                    <option value="Elementary Graduate" {{ $user && $user->education == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                    <option value="High School Level" {{ $user && $user->education == 'High School Level' ? 'selected' : '' }}>High School Level</option>
                    <option value="High School Graduate" {{ $user && $user->education == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                    <option value="College Level" {{ $user && $user->education == 'College Level' ? 'selected' : '' }}>College Level</option>
                    <option value="College Graduate" {{ $user && $user->education == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                  </select>
                </div>

                <!-- Work Status -->
                <div class="field-group">
                  <div class="field-label">Work Status:</div>
                  <div class="view-text">{{ $user ? $user->work_status : 'N/A' }}</div>
                  <select class="edit-input edit-select" name="work_status">
                    <option value="Student" {{ $user && $user->work_status == 'Student' ? 'selected' : '' }}>Student</option>
                    <option value="Employed" {{ $user && $user->work_status == 'Employed' ? 'selected' : '' }}>Employed</option>
                    <option value="Unemployed" {{ $user && $user->work_status == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                    <option value="Self-Employed" {{ $user && $user->work_status == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                  </select>
                </div>

                <!-- Youth Classification -->
                <div class="field-group">
                  <div class="field-label">Youth Classification:</div>
                  <div class="view-text">{{ $user ? $user->youth_classification : 'N/A' }}</div>
                  <select class="edit-input edit-select" name="youth_classification">
                    <option value="In-School Youth" {{ $user && $user->youth_classification == 'In-School Youth' ? 'selected' : '' }}>In-School Youth</option>
                    <option value="Out-of-School Youth" {{ $user && $user->youth_classification == 'Out-of-School Youth' ? 'selected' : '' }}>Out-of-School Youth</option>
                    <option value="Working Youth" {{ $user && $user->youth_classification == 'Working Youth' ? 'selected' : '' }}>Working Youth</option>
                  </select>
                </div>

                <!-- Registered Voter -->
                <div class="field-group">
                  <div class="field-label">Registered Voter:</div>
                  <div class="view-text">{{ $user ? $user->sk_voter : 'N/A' }}</div>
                  <select class="edit-input edit-select" name="sk_voter">
                    <option value="Yes" {{ $user && $user->sk_voter == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $user && $user->sk_voter == 'No' ? 'selected' : '' }}>No</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- END Main -->

  <!-- Avatar Modal -->
  <div class="avatar-modal" id="avatarModal">
    <div class="avatar-modal-content">
      <h2>Change Profile Picture</h2>
      <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
           alt="Avatar Preview" class="avatar-preview" id="avatarPreview">
      
      <div class="avatar-options">
        <button class="avatar-option-btn avatar-upload-btn" id="uploadAvatarBtn">Upload Photo</button>
        <button class="avatar-option-btn avatar-remove-btn" id="removeAvatarBtn">Remove Photo</button>
        <button class="avatar-option-btn avatar-cancel-btn" id="cancelAvatarBtn">Cancel</button>
      </div>
      
      <input type="file" id="avatarFileInput" class="avatar-file-input" accept="image/*">
    </div>
  </div>

  <script>
    // Avatar Manager Class
    class AvatarManager {
      constructor() {
        this.avatarModal = document.getElementById('avatarModal');
        this.avatarPreview = document.getElementById('avatarPreview');
        this.avatarOverlay = document.getElementById('avatarOverlay');
        this.profileAvatar = document.getElementById('profileAvatar');
        this.uploadAvatarBtn = document.getElementById('uploadAvatarBtn');
        this.removeAvatarBtn = document.getElementById('removeAvatarBtn');
        this.cancelAvatarBtn = document.getElementById('cancelAvatarBtn');
        this.avatarFileInput = document.getElementById('avatarFileInput');
        
        this.init();
      }

      init() {
        this.bindEvents();
      }

      bindEvents() {
        // Open avatar modal when clicking on avatar overlay
        this.avatarOverlay?.addEventListener('click', () => {
          this.openAvatarModal();
        });

        // Upload avatar button
        this.uploadAvatarBtn?.addEventListener('click', () => {
          this.avatarFileInput.click();
        });

        // Remove avatar button
        this.removeAvatarBtn?.addEventListener('click', () => {
          this.removeAvatar();
        });

        // Cancel avatar modal
        this.cancelAvatarBtn?.addEventListener('click', () => {
          this.closeAvatarModal();
        });

        // File input change
        this.avatarFileInput?.addEventListener('change', (e) => {
          this.handleFileSelect(e);
        });

        // Close modal when clicking outside
        this.avatarModal?.addEventListener('click', (e) => {
          if (e.target === this.avatarModal) {
            this.closeAvatarModal();
          }
        });
      }

      openAvatarModal() {
        this.avatarModal.classList.add('show');
      }

      closeAvatarModal() {
        this.avatarModal.classList.remove('show');
      }

      handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.match('image.*')) {
          this.showMessage('Please select a valid image file.', 'profile-error');
          return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
          this.showMessage('Image size should be less than 2MB.', 'profile-error');
          return;
        }

        // Preview the image
        const reader = new FileReader();
        reader.onload = (e) => {
          this.avatarPreview.src = e.target.result;
          this.uploadAvatar(file);
        };
        reader.readAsDataURL(file);
      }

      async uploadAvatar(file) {
        try {
          const formData = new FormData();
          formData.append('avatar', file);
          
          // Get CSRF token
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          formData.append('_token', csrfToken);

          const response = await fetch('{{ route("profile.avatar.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrfToken
            }
          });

          const result = await response.json();

          if (result.success) {
            // Update all avatar images on the page
            this.updateAllAvatars(result.avatar_url);
            this.showMessage('Profile picture updated successfully!', 'profile-success');
            this.closeAvatarModal();
          } else {
            this.showMessage(result.message, 'profile-error');
          }
        } catch (error) {
          this.showMessage('Error uploading avatar: ' + error.message, 'profile-error');
        }
      }

      async removeAvatar() {
        try {
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          
          const response = await fetch('{{ route("profile.avatar.remove") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              _token: csrfToken
            })
          });

          const result = await response.json();

          if (result.success) {
            // Reset to default avatar
            this.updateAllAvatars('{{ asset("images/default-avatar.png") }}');
            this.showMessage('Profile picture removed successfully!', 'profile-success');
            this.closeAvatarModal();
          } else {
            this.showMessage(result.message, 'profile-error');
          }
        } catch (error) {
          this.showMessage('Error removing avatar: ' + error.message, 'profile-error');
        }
      }

      updateAllAvatars(avatarUrl) {
        // Update profile card avatar
        this.profileAvatar.src = avatarUrl;
        
        // Update topbar avatar
        const topbarAvatar = document.querySelector('.profile-wrapper .avatar');
        if (topbarAvatar) topbarAvatar.src = avatarUrl;
        
        // Update profile dropdown avatar
        const profileDropdownAvatar = document.querySelector('.profile-dropdown .profile-avatar');
        if (profileDropdownAvatar) profileDropdownAvatar.src = avatarUrl;
        
        // Update avatar preview in modal
        this.avatarPreview.src = avatarUrl;
      }

      showMessage(message, type) {
        const messageEl = document.getElementById('profileMessage');
        messageEl.textContent = message;
        messageEl.className = `profile-message ${type}`;
        messageEl.style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
          messageEl.style.display = 'none';
        }, 5000);
      }
    }

    // Password toggle function - Show actual default password
    function togglePassword(actualPassword) {
        const tempPassword = document.getElementById('tempPassword');
        const icon = document.querySelector('.toggle-password');
        
        if (tempPassword.textContent === '••••••••') {
            // Show the actual password
            tempPassword.textContent = actualPassword;
            tempPassword.classList.add('password-visible');
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            
            // Auto-hide after 10 seconds
            setTimeout(() => {
                if (tempPassword.textContent === actualPassword) {
                    tempPassword.textContent = '••••••••';
                    tempPassword.classList.remove('password-visible');
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }, 10000);
        } else {
            // Hide the password
            tempPassword.textContent = '••••••••';
            tempPassword.classList.remove('password-visible');
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Logout confirmation function
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    }

    // Profile Edit Functionality
    class ProfileEditor {
        constructor() {
            this.isEditMode = false;
            this.originalData = {};
            this.init();
        }

        init() {
            this.bindEvents();
            this.storeOriginalData();
        }

        bindEvents() {
            // Edit button events
            document.getElementById('profileEditBtn')?.addEventListener('click', () => this.toggleEditMode());
            document.getElementById('detailsEditBtn')?.addEventListener('click', () => this.toggleEditMode());

            // Save button event
            document.getElementById('saveProfileBtn')?.addEventListener('click', () => this.saveProfile());

            // Cancel button event
            document.getElementById('cancelEditBtn')?.addEventListener('click', () => this.cancelEdit());
        }

        storeOriginalData() {
            // Store original values for cancel functionality
            const fields = [
                'last_name', 'given_name', 'middle_name', 'suffix', 
                'date_of_birth', 'sex', 'contact_no', 'civil_status',
                'education', 'work_status', 'youth_classification', 'sk_voter',
                'purok_zone', 'zip_code'
            ];

            fields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    this.originalData[field] = input.value;
                }
            });
        }

        toggleEditMode() {
            this.isEditMode = !this.isEditMode;
            
            if (this.isEditMode) {
                this.enterEditMode();
            } else {
                this.exitEditMode();
            }
        }

        enterEditMode() {
            document.getElementById('profileDetails').classList.add('edit-mode');
            
            // Change edit button text/icon
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(btn => {
                btn.innerHTML = '<i class="fas fa-times"></i>';
                btn.title = 'Cancel Edit';
            });

            // Show edit buttons
            document.querySelector('.edit-buttons').style.display = 'flex';
            
            this.showMessage('You are now in edit mode. Make your changes and click Save.', 'profile-success');
        }

        exitEditMode() {
            document.getElementById('profileDetails').classList.remove('edit-mode');
            
            // Reset edit button
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(btn => {
                btn.innerHTML = '<i class="fas fa-pen-to-square"></i>';
                btn.title = 'Edit Profile';
            });

            // Hide edit buttons
            document.querySelector('.edit-buttons').style.display = 'none';
            
            this.hideMessage();
        }

        async saveProfile() {
            const saveBtn = document.getElementById('saveProfileBtn');
            const originalText = saveBtn.innerHTML;
            
            try {
                // Show loading state
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;
                
                // Collect form data - ONLY CHANGED FIELDS
                const formData = new FormData();
                const fields = [
                    'last_name', 'given_name', 'middle_name', 'suffix', 
                    'date_of_birth', 'sex', 'contact_no', 'civil_status',
                    'education', 'work_status', 'youth_classification', 'sk_voter',
                    'purok_zone', 'zip_code'
                ];

                // Only include fields that have changed
                let hasChanges = false;
                fields.forEach(field => {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input && input.value !== this.originalData[field]) {
                        formData.append(field, input.value);
                        hasChanges = true;
                    }
                });

                // If no changes, show message and exit
                if (!hasChanges) {
                    this.showMessage('No changes detected.', 'profile-info');
                    this.exitEditMode();
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    return;
                }

                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                formData.append('_token', csrfToken);

                // Send AJAX request
                const response = await fetch('{{ route("profile.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    this.showMessage(result.message, 'profile-success');
                    
                    // Update view texts with new values
                    this.updateViewTexts();
                    
                    // Exit edit mode after successful save
                    setTimeout(() => {
                        this.exitEditMode();
                        this.storeOriginalData(); // Store new values as original
                    }, 2000);
                    
                } else {
                    this.showMessage(result.message, 'profile-error');
                }

            } catch (error) {
                this.showMessage('Error saving profile: ' + error.message, 'profile-error');
            } finally {
                // Restore button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        }

        updateViewTexts() {
            const fields = [
                'last_name', 'given_name', 'middle_name', 'suffix', 
                'date_of_birth', 'sex', 'contact_no', 'civil_status',
                'education', 'work_status', 'youth_classification', 'sk_voter',
                'purok_zone', 'zip_code'
            ];

            fields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                const viewText = input?.closest('.field-group')?.querySelector('.view-text');
                
                if (input && viewText) {
                    if (field === 'date_of_birth' && input.value) {
                        // Format date for display
                        const date = new Date(input.value);
                        viewText.textContent = date.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                    } else if (field === 'sex' && input.value) {
                        viewText.textContent = input.value.charAt(0).toUpperCase() + input.value.slice(1);
                    } else {
                        viewText.textContent = input.value || 'N/A';
                    }
                }
            });
        }

        cancelEdit() {
            // Restore original values
            Object.keys(this.originalData).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.value = this.originalData[field];
                }
            });
            
            this.exitEditMode();
            this.showMessage('Changes cancelled.', 'profile-success');
            
            setTimeout(() => {
                this.hideMessage();
            }, 2000);
        }

        showMessage(message, type) {
            const messageEl = document.getElementById('profileMessage');
            messageEl.textContent = message;
            messageEl.className = `profile-message ${type}`;
            messageEl.style.display = 'block';
        }

        hideMessage() {
            const messageEl = document.getElementById('profileMessage');
            messageEl.style.display = 'none';
        }
    }

    // Password Modal Functionality
    class PasswordManager {
        constructor() {
            this.settingsBtn = document.querySelector('.settings-btn');
            this.passwordModal = document.getElementById('passwordModal');
            this.closeModal = document.getElementById('closeModal');
            this.okBtn = document.getElementById('okBtn');
            this.showPassCheckbox = document.getElementById('showPass');
            this.changePasswordForm = document.getElementById('changePasswordForm');
            this.savePasswordBtn = document.getElementById('savePasswordBtn');
            
            // Password validation elements
            this.currentPasswordInput = document.getElementById('currentPassword');
            this.newPasswordInput = document.getElementById('newPassword');
            this.confirmPasswordInput = document.getElementById('confirmPassword');
            
            // Requirement elements
            this.reqLength = document.getElementById('req-length');
            this.reqUpper = document.getElementById('req-upper');
            this.reqLower = document.getElementById('req-lower');
            this.reqNumber = document.getElementById('req-number');
            this.reqSymbol = document.getElementById('req-symbol');
            this.reqMatch = document.getElementById('req-match');
            
            // Requirements container
            this.requirementsContainer = document.querySelector('.requirements');
            this.reqHeading = document.querySelector('.req-heading');
            
            this.hasValidationStarted = false;
            this.init();
        }

        init() {
            this.bindEvents();
            this.hideRequirements(); // Hide requirements initially
        }

        bindEvents() {
            // Open modal when settings button is clicked
            this.settingsBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.passwordModal.classList.add('show');
                this.resetPasswordForm();
            });

            // Close modal when X button is clicked
            this.closeModal?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.passwordModal.classList.remove('show');
                this.resetPasswordForm();
            });

            // Close modal when OK button is clicked (in success modal)
            this.okBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const successModal = document.getElementById('successModal');
                successModal.classList.remove('show');
                this.passwordModal.classList.remove('show');
                this.resetPasswordForm();
            });

            // Close modal when clicking outside the modal
            this.passwordModal?.addEventListener('click', (e) => {
                if (e.target === this.passwordModal) {
                    this.passwordModal.classList.remove('show');
                    this.resetPasswordForm();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.passwordModal.classList.contains('show')) {
                    this.passwordModal.classList.remove('show');
                    this.resetPasswordForm();
                }
            });

            // Show/hide password functionality
            this.showPassCheckbox?.addEventListener('change', () => {
                const type = this.showPassCheckbox.checked ? 'text' : 'password';
                this.currentPasswordInput.type = type;
                this.newPasswordInput.type = type;
                this.confirmPasswordInput.type = type;
            });

            // Real-time password validation - only show when user starts typing
            this.newPasswordInput?.addEventListener('input', () => {
                if (!this.hasValidationStarted && this.newPasswordInput.value.length > 0) {
                    this.hasValidationStarted = true;
                    this.showRequirements();
                }
                this.validatePassword();
            });

            this.confirmPasswordInput?.addEventListener('input', () => {
                if (!this.hasValidationStarted && this.confirmPasswordInput.value.length > 0) {
                    this.hasValidationStarted = true;
                    this.showRequirements();
                }
                this.validatePassword();
            });

            // Hide requirements when both fields are empty
            this.newPasswordInput?.addEventListener('blur', () => {
                this.checkIfShouldHideRequirements();
            });

            this.confirmPasswordInput?.addEventListener('blur', () => {
                this.checkIfShouldHideRequirements();
            });

            // Form submission
            this.changePasswordForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Show requirements if they're not visible but validation fails
                if (!this.hasValidationStarted) {
                    this.hasValidationStarted = true;
                    this.showRequirements();
                }
                
                this.submitPasswordChange();
            });
        }

        showRequirements() {
            this.requirementsContainer.style.display = 'block';
            this.reqHeading.style.display = 'block';
        }

        hideRequirements() {
            this.requirementsContainer.style.display = 'none';
            this.reqHeading.style.display = 'none';
        }

        checkIfShouldHideRequirements() {
            // Hide requirements if both password fields are empty and no validation has started
            if (this.hasValidationStarted && 
                this.newPasswordInput.value === '' && 
                this.confirmPasswordInput.value === '') {
                this.hasValidationStarted = false;
                this.hideRequirements();
            }
        }

        validatePassword() {
            const password = this.newPasswordInput.value;
            const confirmPassword = this.confirmPasswordInput.value;
            
            let allValid = true;

            // Check length
            if (password.length >= 8) {
                this.reqLength.style.display = 'none';
            } else {
                this.reqLength.style.display = 'block';
                allValid = false;
            }

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                this.reqUpper.style.display = 'none';
            } else {
                this.reqUpper.style.display = 'block';
                allValid = false;
            }

            // Check lowercase
            if (/[a-z]/.test(password)) {
                this.reqLower.style.display = 'none';
            } else {
                this.reqLower.style.display = 'block';
                allValid = false;
            }

            // Check number
            if (/[0-9]/.test(password)) {
                this.reqNumber.style.display = 'none';
            } else {
                this.reqNumber.style.display = 'block';
                allValid = false;
            }

            // Check symbol
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                this.reqSymbol.style.display = 'none';
            } else {
                this.reqSymbol.style.display = 'block';
                allValid = false;
            }

            // Check match - only show if confirm password has content
            if (confirmPassword === '') {
                this.reqMatch.style.display = 'none';
            } else if (password === confirmPassword) {
                this.reqMatch.style.display = 'none';
            } else {
                this.reqMatch.style.display = 'block';
                allValid = false;
            }

            // Hide entire requirements section if all requirements are met
            if (allValid && password !== '' && confirmPassword !== '' && password === confirmPassword) {
                this.requirementsContainer.style.display = 'none';
                this.reqHeading.style.display = 'none';
            } else if (this.hasValidationStarted) {
                // Show requirements if any are not met
                this.showRequirements();
            }

            return allValid;
        }

        async submitPasswordChange() {
            const saveBtn = this.savePasswordBtn;
            const originalText = saveBtn.innerHTML;
            
            if (!this.validatePassword()) {
                this.showMessage('Please fix the password requirements before submitting.', 'profile-error');
                return;
            }

            try {
                // Show loading state
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing Password...';
                saveBtn.disabled = true;

                const formData = new FormData(this.changePasswordForm);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('{{ route("profile.change-password") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Show success modal
                    const successModal = document.getElementById('successModal');
                    successModal.classList.add('show');
                    
                    // Reset form
                    this.resetPasswordForm();
                } else {
                    this.showMessage(result.message, 'profile-error');
                }

            } catch (error) {
                this.showMessage('Error changing password: ' + error.message, 'profile-error');
            } finally {
                // Restore button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        }

        resetPasswordForm() {
            this.changePasswordForm.reset();
            this.hasValidationStarted = false;
            this.hideRequirements();
            
            // Reset all requirement displays
            [this.reqLength, this.reqUpper, this.reqLower, this.reqNumber, this.reqSymbol, this.reqMatch].forEach(el => {
                el.style.display = 'none';
                el.classList.remove('valid');
                el.classList.add('invalid');
            });
        }

        showMessage(message, type) {
            const messageEl = document.getElementById('profileMessage');
            messageEl.textContent = message;
            messageEl.className = `profile-message ${type}`;
            messageEl.style.display = 'block';
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                messageEl.style.display = 'none';
            }, 5000);
        }
    }

    // Notification Mark as Read Functionality
    function initMarkAsRead() {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (!csrfToken) {
          console.error('CSRF token not found.');
          return;
      }

      // Target general notifications (with data-id)
      const generalNotifLinks = document.querySelectorAll('.notif-link[data-id]');

      generalNotifLinks.forEach(link => {
          link.addEventListener('click', (e) => {
              if (!link.classList.contains('unread')) {
                  return; // Already read, let link work normally
              }

              e.preventDefault(); 
              
              const notifId = link.dataset.id;
              const destinationUrl = link.href;
              const dot = link.querySelector('.notif-dot'); 
              const placeholder = link.querySelector('.notif-dot-placeholder');

              // 1. Visually mark as read immediately
              link.classList.remove('unread');
              if (dot) dot.style.display = 'none'; 
              if (placeholder) placeholder.style.display = 'inline-block'; 

              // 2. Send request to server
              fetch(`/notifications/mark-as-read/${notifId}`, {
                  method: 'POST',
                  headers: {
                      'X-CSRF-TOKEN': csrfToken,
                      'Accept': 'application/json',
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({ id: notifId })
              })
              .catch(err => {
                  console.error('Failed to mark notification as read:', err);
              })
              .finally(() => {
                  // 3. Navigate after fetch (success or fail)
                  window.location.href = destinationUrl;
              });
          });
      });
    }

    // Initialize when DOM is loaded
    document.addEventListener("DOMContentLoaded", () => {
        // Initialize profile editor
        const profileEditor = new ProfileEditor();

        // Initialize password manager
        const passwordManager = new PasswordManager();

        // Initialize avatar manager
        const avatarManager = new AvatarManager();

        // Initialize notification mark as read
        initMarkAsRead();

        // === Sidebar & Profile/Events Dropdowns ===
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');

        const profileItem = document.querySelector('.profile-item');
        const profileLink = profileItem?.querySelector('.profile-link');

        const profileWrapper = document.querySelector('.profile-wrapper');
        const profileToggle = document.getElementById('profileToggle');
        const profileDropdown = document.querySelector('.profile-dropdown');

        const notifWrapper = document.querySelector(".notification-wrapper");
        const notifBell = notifWrapper?.querySelector(".fa-bell");
        const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

        // Helper function to close all submenus
        function closeAllSubmenus() {
            profileItem?.classList.remove('open');
        }

        // === Sidebar toggle ===
        menuToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('open');
            if (!sidebar.classList.contains('open')) closeAllSubmenus();
        });

        // === Profile submenu toggle ===
        profileLink?.addEventListener('click', (e) => {
            e.preventDefault();
            if (sidebar.classList.contains('open')) {
                const isOpen = profileItem.classList.contains('open');
                closeAllSubmenus();
                if (!isOpen) profileItem.classList.add('open');
            }
        });

        // === Close sidebar & submenus when clicking outside ===
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('open');
                closeAllSubmenus();
            }
            if (!profileWrapper?.contains(e.target)) profileWrapper?.classList.remove('active');
            if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
        });

        // === Profile dropdown toggle ===
        profileToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            profileWrapper.classList.toggle('active');
            notifWrapper?.classList.remove('active');
        });
        profileDropdown?.addEventListener('click', e => e.stopPropagation());

        // === Notifications dropdown toggle ===
        notifBell?.addEventListener('click', (e) => {
            e.stopPropagation();
            notifWrapper.classList.toggle('active');
            profileWrapper?.classList.remove('active');
        });
        notifDropdown?.addEventListener('click', e => e.stopPropagation());

        // === Calendar ===
        const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
        const daysContainer = document.querySelector(".calendar .days");
        const header = document.querySelector(".calendar header h3");
        let today = new Date();
        let currentView = new Date();
        const holidays = ["2025-01-01","2025-04-09","2025-04-17","2025-04-18","2025-05-01","2025-06-06","2025-06-12","2025-08-25","2025-11-30","2025-12-25","2025-12-30"];

        function renderCalendar(baseDate) {
            if (!daysContainer || !header) return;
            daysContainer.innerHTML = "";
            const startOfWeek = new Date(baseDate);
            startOfWeek.setDate(baseDate.getDate() - (baseDate.getDay() === 0 ? 6 : baseDate.getDay() - 1));
            const middleDay = new Date(startOfWeek);
            middleDay.setDate(startOfWeek.getDate() + 3);
            header.textContent = middleDay.toLocaleDateString("en-US", { month: "long", year: "numeric" });
            for (let i = 0; i < 7; i++) {
                const thisDay = new Date(startOfWeek);
                thisDay.setDate(startOfWeek.getDate() + i);
                const dayEl = document.createElement("div");
                dayEl.classList.add("day");
                const weekdayEl = document.createElement("span");
                weekdayEl.classList.add("weekday");
                weekdayEl.textContent = weekdays[i];
                const dateEl = document.createElement("span");
                dateEl.classList.add("date");
                dateEl.textContent = thisDay.getDate();
                const month = (thisDay.getMonth()+1).toString().padStart(2,'0');
                const day = thisDay.getDate().toString().padStart(2,'0');
                const dateStr = `${thisDay.getFullYear()}-${month}-${day}`;
                if (holidays.includes(dateStr)) dateEl.classList.add('holiday');
                if (thisDay.getDate()===today.getDate() && thisDay.getMonth()===today.getMonth() && thisDay.getFullYear()===today.getFullYear()) dayEl.classList.add("active");
                dayEl.appendChild(weekdayEl);
                dayEl.appendChild(dateEl);
                daysContainer.appendChild(dayEl);
            }
        }
        renderCalendar(currentView);
        document.querySelector(".calendar .prev")?.addEventListener("click", () => { currentView.setDate(currentView.getDate()-7); renderCalendar(currentView); });
        document.querySelector(".calendar .next")?.addEventListener("click", () => { currentView.setDate(currentView.getDate()+7); renderCalendar(currentView); });

        // === Time auto-update ===
        const timeEl = document.querySelector(".time");
        function updateTime() {
            if (!timeEl) return;
            const now = new Date();

            const shortWeekdays = ["SUN","MON","TUE","WED","THU","FRI","SAT"];
            const shortMonths = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];

            const weekday = shortWeekdays[now.getDay()];
            const month = shortMonths[now.getMonth()];
            const day = now.getDate();

            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, "0");
            const ampm = hours >= 12 ? "PM" : "AM";
            hours = hours % 12 || 12;

            timeEl.innerHTML = `${weekday}, ${month} ${day} ${hours}:${minutes} <span>${ampm}</span>`;
        }
        updateTime();
        setInterval(updateTime, 60000);

        // Initialize the password as hidden by default
        document.getElementById('tempPassword').textContent = '••••••••';
        
        // Lucide icons
        lucide.createIcons();
    });
  </script>
</body>
</html>