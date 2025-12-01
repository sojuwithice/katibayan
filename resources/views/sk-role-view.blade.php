<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>KatiBayan - SK Analytics</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
   <link rel="stylesheet" href="{{ asset('css/sk-view.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
      /* Ensure modal works even if external CSS fails to load immediately */
      .modal-overlay {
          display: none; /* Hidden by default */
          position: fixed;
          top: 0; left: 0; width: 100%; height: 100%;
          background: rgba(0,0,0,0.5);
          z-index: 9999;
          justify-content: center;
          align-items: center;
      }
      .modal-overlay.active {
          display: flex; /* Show when active */
      }
  </style>
</head>
<body>
<main class="dashboard-container">

    <header class="topbar">
        <div class="logo">
           <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="KatiBayan Logo" class="logo-img">
             <div class="logo-text">
                <span><span class="blue">K</span>ati<span class="blue">B</span>ayan.</span>
                <small>Katipunan ng Kabataan Web Portal</small>
            </div>
        </div>

        <div class="topbar-right">
            <div class="time" id="currentTime">Loading...</div>

            <div class="topbar-icons">
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
                                    <strong>Program Evaluation Due</strong>
                                    <p>The evaluation for the KK-Assembly is due tomorrow.</p>
                                </div>
                                <span class="notif-dot"></span>
                            </li>
                            <li>
                                <div class="notif-icon"></div>
                                <div class="notif-content">
                                    <strong>New Project Proposal</strong>
                                    <p>Kagawad Dela Cruz submitted a new project proposal.</p>
                                </div>
                                <span class="notif-dot"></span>
                            </li>
                            <li>
                                <div class="notif-icon"></div>
                                <div class="notif-content">
                                    <strong>Meeting Reminder</strong>
                                    <p>SK Monthly Meeting is scheduled for Friday at 2 PM.</p>
                                </div>
                            </li>
                             <li>
                                <div class="notif-icon"></div>
                                <div class="notif-content">
                                    <strong>Report Received</strong>
                                    <p>Received Accomplishment Report from Kagawad Santos.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                @if(Auth::check())
    @php
        $user = Auth::user();
        
        // 1. Calculate Age
        $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';

        // 2. Fix "KK-Member" Logic
        $rawRole = strtolower($user->role);
        if ($rawRole === 'kk' || $rawRole === 'resident') {
            $roleBadge = 'KK-Member'; // Specific fix requested
        } else {
            $roleBadge = ucfirst($rawRole);
        }

        // 3. SK Role Logic (Yellow Badge)
        $skTitle = '';
        if (!empty($user->sk_role)) {
            $skTitle = $user->sk_role; 
        } elseif ($user->role === 'sk_chairperson') {
            $skTitle = 'Chairperson';
        }
    @endphp

    <div class="profile-wrapper">
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
             alt="User" class="avatar" id="profileToggle"> 

        <div class="profile-dropdown">
            <div class="profile-header">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                     alt="User" class="profile-avatar"> 

                <div class="profile-info">
                    <h4>{{ $user->given_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->suffix }}</h4>
                    
                    <div class="badges-wrapper">
                        
                        <div class="profile-badge">
                            <span class="badge">{{ $roleBadge }}</span>
                            <span class="badge">{{ $age }} yrs old</span>
                        </div>

                        @if($skTitle)
                            <span class="badge-2">{{ $skTitle }}</span>
                        @endif

                    </div>
                </div>
            </div>
            <hr>
            <ul class="profile-menu">
                <li class="no-hover-bg">
                    <a href="{{ route('dashboard.index') }}" class="back-to-profile" id="btn-back-profile">
                        Back to Profile
                    </a>
                </li>
                <li class="logout-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
@endif
            </div>
        </div>
    </header>

    <section class="welcome-section">
        @php
            // 1. Get User Data
            $fullName = $user->given_name . ' ' . $user->middle_name . ' ' . $user->last_name . ' ' . $user->suffix;
            $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';
            
            // 2. Get SK Role
            $skRole = $user->sk_role ?? 'Member';
            $roleClass = strtolower($skRole);

            // 3. GET COMMITTEES (Decode JSON from DB)
            // Siguraduhing naka-array ito. Kung null, empty array.
            $savedCommittees = !empty($user->committees) ? json_decode($user->committees, true) : [];
            $hasCommittees = !empty($savedCommittees);
            
            // 4. Button Text (Style is same for both)
            $buttonText = $hasCommittees ? 'Edit your committee' : 'Set your committee';
        @endphp

        <script>
            window.userCommittees = @json($savedCommittees);
        </script>

        <div class="welcome-text">
            <h1>{{ $fullName }}</h1>
            <p>
                {{ $age }} years old 
                <span class="tag tag-{{ $roleClass }}">SK {{ $skRole }}</span>
            </p>
        </div>
        
        <button class="btn btn-secondary" id="setCommitteeBtn">
            {{ $buttonText }}
        </button>
    </section>

        <div class="modal-overlay" id="committeeModal">
            <div class="committee-modal">
                <div class="modal-header">
                    <h2>Select Your Committee</h2>
                    <button class="close-modal" id="closeModal">&times;</button>
                </div>
                <div class="modal-content">
                    <div class="modal-section">
                        <p style="color: #252525ff; margin-bottom: 1rem; font-size: 0.9rem;">
                          Please select your respective committee to proceed.
                        </p>
                        <div class="committee-options">
                            <div class="committee-option" data-committee="health">
                                <input type="checkbox" id="health" name="committees" value="health">
                                <label for="health">Committee on Health</label>
                            </div>
                            <div class="committee-option" data-committee="education">
                                <input type="checkbox" id="education" name="committees" value="education">
                                <label for="education">Committee on Education</label>
                            </div>
                            <div class="committee-option" data-committee="sports">
                                <input type="checkbox" id="sports" name="committees" value="sports">
                                <label for="sports">Committee on Sports</label>
                            </div>
                            <div class="committee-option" data-committee="culture">
                                <input type="checkbox" id="culture" name="committees" value="culture">
                                <label for="culture">Committee on Culture</label>
                            </div>
                            <div class="committee-option" data-committee="environment">
                                <input type="checkbox" id="environment" name="committees" value="environment">
                                <label for="environment">Committee on Environment</label>
                            </div>
                            <div class="committee-option" data-committee="citizenship">
                                <input type="checkbox" id="citizenship" name="committees" value="citizenship">
                                <label for="citizenship">Committee on Active Citizenship</label>
                            </div>
                            <div class="committee-option" data-committee="social">
                                <input type="checkbox" id="social" name="committees" value="social">
                                <label for="social">Committee on Social Inclusion</label>
                            </div>
                            <div class="committee-option" data-committee="finance">
                                <input type="checkbox" id="finance" name="committees" value="finance">
                                <label for="finance">Committee on Finance</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-section">
                        <h3>Current Selection</h3>
                        <div id="selectedCommittees" style="
                            background: #f8f9fa;
                            padding: 1rem;
                            border-radius: 8px;
                            min-height: 60px;
                            border: 2px dashed #ddd;
                        ">
                            <p style="color: #999; margin: 0; font-style: italic;">No committees selected yet</p>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" id="cancelSelection">Cancel</button>
                    <button class="btn btn-primary" id="saveCommittees">Save Committees</button>
                </div>
            </div>
        </div>

    </section>

    <div class="dashboard-grid">
    <div class="grid-col-1">
        <div class="card sk-committee">
            <h2>SK COMMITTEE</h2>
            <ul>
                
                <li class="committee-item">
                    @if($chairperson)
                        <span class="name">
                            {{ strtoupper($chairperson->given_name . ' ' . $chairperson->last_name) }}
                        </span>
                        <div class="role-group">
                            <span class="role-tag role-chairperson">SK CHAIRPERSON</span>
                        </div>
                    @else
                        <span class="name" style="color: #999; font-style: italic;">(VACANT)</span>
                        <div class="role-group">
                            <span class="role-tag role-chairperson">SK CHAIRPERSON</span>
                        </div>
                    @endif
                </li>

                <li class="members-header">MEMBERS</li>

                @forelse($members as $member)
                    @php
                        // Format Name
                        $fullName = strtoupper($member->given_name . ' ' . $member->last_name);
                        
                        // Format Role Class (e.g. role-treasurer, role-kagawad)
                        $roleClass = 'role-' . strtolower($member->sk_role);
                        
                        // Check Committees (Decode JSON)
                        $commList = !empty($member->committees) ? json_decode($member->committees, true) : [];
                    @endphp

                    <li class="committee-item">
                        <span class="name">{{ $fullName }}</span>
                        
                        <div class="role-group">
                            <span class="role-tag {{ $roleClass }}">
                                SK {{ strtoupper($member->sk_role) }}
                            </span>

                            @if(!empty($commList))
                                @foreach($commList as $comm)
                                    <span class="committee-role" style="display: block; font-size: 10px; color: #666; margin-top: 4px; font-weight: 600;">
                                        COMMITTEE ON {{ strtoupper($comm) }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </li>

                @empty
                    <li class="committee-item" style="justify-content: center; padding: 20px;">
                        <span class="name" style="color: #999; font-style: italic; font-weight: normal; font-size: 13px;">
                            No registered SK members yet.
                        </span>
                    </li>
                @endforelse

            </ul>
        </div>
    </div>


        <div class="grid-col-2">
    <div class="card send-report">
        <h2>SEND REPORT TO YOUR SK CHAIR</h2>
        
        <form id="sendReportForm">
            <div class="form-group">
                <label for="report-type">Report Type</label>
                <select id="report-type" name="report_type" required> <option value="">Select type of report</option>
                    <option value="accomplishment">Accomplishment Report</option>
                    <option value="financial">Propose Project</option>
                </select>
            </div>

            <div class="form-group file-upload">
                <label for="file-attach">Attach files</label>
                <div class="file-input-wrapper">
                    <button type="button" class="file-input-btn" id="browseBtn">
                        <i class="fas fa-cloud-upload-alt"></i> Choose Files or Drag & Drop
                    </button>
                    <input type="file" id="file-attach" name="files[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" style="display:none;">
                </div>
                
                <div class="file-size-warning">Max file size: 10MB per file</div>
                
                <div class="file-list" id="uploadFileList">
                    <div class="file-empty-state">No files selected</div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">Submit Report</button>
        </form>
    </div>
</div>
    </div>

    <div class="card propose-project-full">
    <div class="card-header-flex">
        <h2>Accomplished Projects</h2>
        
        <div class="year-filter-wrapper">
            <select id="projectYearFilter" class="year-select">
                @php
                    $currentYear = \Carbon\Carbon::now()->year;
                    // Kunin lahat ng years na may record
                    $availableYears = $completedProjects->keys(); 
                @endphp

                @if(!$availableYears->contains($currentYear))
                    <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                @endif

                @foreach($availableYears as $year)
                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
                
                <option value="all">All Years</option>
            </select>
        </div>
    </div>
    
    <div class="projects-list-container">
        @forelse($completedProjects as $year => $projects)
            
            <div class="year-group-container" 
                 id="year-group-{{ $year }}" 
                 style="{{ $year == $currentYear ? 'display: block;' : 'display: none;' }}">
                
                <div class="year-header-small">
                    <span>Records for {{ $year }}</span>
                </div>

                <ul class="project-year-group">
                    @foreach($projects as $project)
                        <li>
                            <div class="project-info">
                                <span class="project-name">{{ $project->title }}</span>
                                <span class="project-status">
                                    {{ $project->type }}
                                </span>
                            </div>
                            <span class="project-date">
                                {{ \Carbon\Carbon::parse($project->date)->format('M d') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

        @empty
            <div style="padding: 20px; text-align: center;">
                <span style="color: #999; font-style: italic;">
                    No accomplished projects yet.
                </span>
            </div>
        @endforelse
        
        <div id="no-data-message" style="display: none; padding: 20px; text-align: center;">
            <span style="color: #999; font-style: italic;">No records found for this year.</span>
        </div>
    </div>
</div>

</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // =========================================================
    // 1. GLOBAL UI (Profile, Notifications, Dropdowns)
    // =========================================================
    const profileToggle = document.getElementById('profileToggle');
    const profileWrapper = document.querySelector('.profile-wrapper');
    const profileDropdown = document.querySelector('.profile-dropdown');
    const notifBell = document.querySelector('.notification-wrapper .fa-bell');
    const notifWrapper = document.querySelector('.notification-wrapper');
    const notifDropdown = document.querySelector('.notif-dropdown');

    // Toggle Profile
    profileToggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        profileWrapper.classList.toggle('active');
        notifWrapper?.classList.remove('active');
    });
    profileDropdown?.addEventListener('click', e => e.stopPropagation());

    // Toggle Notifications
    notifBell?.addEventListener('click', (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle('active');
        profileWrapper?.classList.remove('active');
    });
    notifDropdown?.addEventListener('click', e => e.stopPropagation());

    // Close Dropdowns on Outside Click
    window.addEventListener('click', (e) => {
        if (!profileWrapper?.contains(e.target)) profileWrapper?.classList.remove('active');
        if (!notifWrapper?.contains(e.target)) notifWrapper?.classList.remove('active');
    });


    // =========================================================
    // 2. TIME WIDGET UPDATE
    // =========================================================
    const timeEl = document.getElementById("currentTime");
    function updateTime() {
        if (!timeEl) return;
        const now = new Date();
        const shortWeekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
        const weekday = shortWeekdays[now.getDay()];
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        timeEl.textContent = `${weekday} ${hours}:${minutes} ${ampm}`;
    }
    updateTime();
    setInterval(updateTime, 60000);


    // =========================================================
    // 2. SEND REPORT & FILE UPLOAD LOGIC (FIXED IDs)
    // =========================================================
    
    // MGA TAMANG IDs BASE SA HTML MO:
    const fileInput = document.getElementById('file-attach');
    const fileListContainer = document.getElementById('uploadFileList'); 
    const browseBtn = document.getElementById('browseBtn'); 
    const sendReportForm = document.getElementById('sendReportForm'); 
    const submitBtn = document.getElementById('submitBtn');
    
    let currentFiles = [];

    // 1. TRIGGER CLICK (Pag-click ng button, bubukas ang file folder)
    if (browseBtn && fileInput) {
        browseBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.click();
        });
    }

    // 2. FILE SELECTION (Pag may napili na)
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            const newFiles = Array.from(e.target.files);
            
            newFiles.forEach(newFile => {
                // Check kung duplicate para hindi maulit
                if (!currentFiles.some(existing => existing.name === newFile.name)) {
                    currentFiles.push(newFile);
                }
            });

            updateFileListUI(fileListContainer, currentFiles);
            fileInput.value = ''; // Reset para pwede pumili ulit
        });
    }

    // HELPER: Update UI List (Ito ang magpapakita sa screen)
    function updateFileListUI(container, filesToShow) {
        if (!container) return;
        container.innerHTML = '';
        
        if (filesToShow.length === 0) {
            container.innerHTML = '<div class="file-empty-state">No files selected</div>';
            return;
        }

        filesToShow.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.style.cssText = "display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 8px; margin-bottom: 5px; border: 1px solid #e2e8f0; border-radius: 5px;";
            
            // Format size logic
            let size = (file.size / 1024).toFixed(1) + ' KB';
            if(file.size > 1024 * 1024) size = (file.size / (1024 * 1024)).toFixed(1) + ' MB';

            fileItem.innerHTML = `
                <div style="display:flex; align-items:center; gap:8px; overflow:hidden;">
                    <i class="fas fa-file" style="color:#64748b;"></i>
                    <span class="file-name" title="${file.name}" style="font-size:0.9rem;">${file.name}</span>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:0.8rem; color:#888;">${size}</span>
                    <span class="file-remove" data-index="${index}" style="cursor:pointer; color:#ef4444;"><i class="fas fa-times"></i></span>
                </div>
            `;
            container.appendChild(fileItem);
        });

        // Add remove functionality
        container.querySelectorAll('.file-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = parseInt(e.currentTarget.dataset.index);
                currentFiles.splice(idx, 1); // Remove from array
                updateFileListUI(container, currentFiles); // Re-render
            });
        });
    }

    // 3. SUBMIT FORM (AJAX)
    if (sendReportForm) {
        sendReportForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const reportType = document.getElementById('report-type').value;
            
            if (!reportType) {
                alert('Please select a report type.');
                return;
            }
            if (currentFiles.length === 0) {
                alert('Please attach at least one file.');
                return;
            }

            // Prepare Data
            const formData = new FormData();
            formData.append('report_type', reportType);
            
            // Append files manu-mano
            currentFiles.forEach(file => {
                formData.append('files[]', file);
            });

            // UI Loading
            const origText = submitBtn.innerText;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch('/submit-report', { 
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    sendReportForm.reset();
                    currentFiles = [];
                    updateFileListUI(fileListContainer, currentFiles);
                } else {
                    alert('Failed: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred. Check console.');
            } finally {
                submitBtn.innerText = origText; // Balik sa original text (Submit Report)
                submitBtn.disabled = false;
            }
        });
    }


    // =========================================================
    // 4. COMMITTEE SELECTION LOGIC
    // =========================================================
    const setCommitteeBtn = document.getElementById('setCommitteeBtn');
    const committeeModal = document.getElementById('committeeModal');
    const closeModal = document.getElementById('closeModal');
    const cancelSelection = document.getElementById('cancelSelection');
    const saveCommittees = document.getElementById('saveCommittees');
    const committeeOptions = document.querySelectorAll('.committee-option');
    const selectedCommittees = document.getElementById('selectedCommittees');

    function loadSavedCommittees() {
        committeeOptions.forEach(opt => {
            const cb = opt.querySelector('input');
            cb.checked = false;
            opt.classList.remove('selected');
        });

        const saved = window.userCommittees || [];
        if (saved.length > 0) {
            saved.forEach(value => {
                const targetCb = document.querySelector(`input[value="${value}"]`);
                if (targetCb) {
                    targetCb.checked = true;
                    targetCb.closest('.committee-option').classList.add('selected');
                }
            });
        }
        updateSelectedCommitteesUI();
    }

    // Initialize
    loadSavedCommittees(); 
    if (!window.userCommittees || window.userCommittees.length === 0) {
        if (committeeModal) committeeModal.classList.add('active');
    }

    setCommitteeBtn?.addEventListener('click', () => {
        loadSavedCommittees();
        committeeModal.classList.add('active');
    });

    closeModal?.addEventListener('click', () => committeeModal.classList.remove('active'));
    cancelSelection?.addEventListener('click', () => {
        committeeModal.classList.remove('active');
        loadSavedCommittees();
    });

    committeeModal?.addEventListener('click', (e) => {
        if (e.target === committeeModal) {
            committeeModal.classList.remove('active');
            loadSavedCommittees();
        }
    });

    // Checkbox Interactions
    committeeOptions.forEach(option => {
        const checkbox = option.querySelector('input[type="checkbox"]');
        
        option.addEventListener('click', (e) => {
            if (e.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
            }
            option.classList.toggle('selected', checkbox.checked);
            updateSelectedCommitteesUI();
        });

        checkbox.addEventListener('change', () => {
            option.classList.toggle('selected', checkbox.checked);
            updateSelectedCommitteesUI();
        });
    });

    function updateSelectedCommitteesUI() {
        const selected = Array.from(committeeOptions)
            .filter(option => option.querySelector('input').checked)
            .map(option => `<div class="selected-committee">${option.querySelector('label').textContent}</div>`);

        if (selectedCommittees) {
            selectedCommittees.innerHTML = selected.length > 0 
                ? selected.join('') 
                : '<p style="color: #999; margin: 0; font-style: italic;">No committees selected yet</p>';
        }
    }

    // Save Committees Logic
    saveCommittees?.addEventListener('click', async () => {
        const selected = Array.from(committeeOptions)
            .filter(option => option.querySelector('input').checked)
            .map(option => option.querySelector('input').value);

        if (selected.length === 0) {
            alert('Please select at least one committee.');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const originalText = saveCommittees.textContent;
        saveCommittees.textContent = 'Saving...';
        saveCommittees.disabled = true;

        try {
            const response = await fetch('/sk/update-committees', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ committees: selected })
            });

            const data = await response.json();

            if (response.ok) {
                alert('Committees saved successfully!');
                committeeModal.classList.remove('active');
                window.location.reload(); 
            } else {
                alert('Failed to save: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Something went wrong.');
        } finally {
            saveCommittees.textContent = originalText;
            saveCommittees.disabled = false;
        }
    });


    // =========================================================
    // 5. YEAR FILTER LOGIC
    // =========================================================
    const yearFilter = document.getElementById('projectYearFilter');
    const yearGroups = document.querySelectorAll('.year-group-container');
    const noDataMsg = document.getElementById('no-data-message');

    if (yearFilter) {
        yearFilter.addEventListener('change', function() {
            const selectedYear = this.value;
            let hasVisibleData = false;

            yearGroups.forEach(group => {
                if (selectedYear === 'all') {
                    group.style.display = 'block';
                    hasVisibleData = true;
                } else {
                    if (group.id === `year-group-${selectedYear}`) {
                        group.style.display = 'block';
                        hasVisibleData = true;
                    } else {
                        group.style.display = 'none';
                    }
                }
            });

            if (noDataMsg) {
                noDataMsg.style.display = hasVisibleData ? 'none' : 'block';
            }
        });
        
        // Initial Trigger
        const event = new Event('change');
        yearFilter.dispatchEvent(event);
    }

});
</script>
</body>
</html>