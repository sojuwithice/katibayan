<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Edit Event</title>
  <link rel="stylesheet" href="{{ asset('css/edit-event.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  
  <!-- Sidebar -->
  <aside class="sidebar">
    <button class="menu-toggle">Menu</button>
    <div class="divider"></div>
    <nav class="nav">
      <a href="{{ route('sk.dashboard') }}">
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>

      <a href="#">
        <i data-lucide="chart-pie"></i>
        <span class="label">Analytics</span>
      </a>

      <a href="{{ route('youth-profilepage') }}">
        <i data-lucide="users"></i>
        <span class="label">Youth Profile</span>
      </a>

      <a href="{{ route('sk-eventpage') }}" class="active">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <div class="evaluation-item nav-item">
        <a href="#" class="evaluation-link nav-link">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('sk-evaluation-feedback') }}">Feedbacks</a>
          <a href="{{ route('sk-polls') }}">Polls</a>
          <a href="{{ route('youth-suggestion') }}">Suggestion Box</a>
        </div>
      </div>

      <a href="{{ route('reports') }}">
        <i data-lucide="file-chart-column"></i>
        <span class="label">Reports</span>
      </a>

      <a href="{{ route('sk-services-offer') }}">
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
        <div class="time">MON 10:00 <span>AM</span></div>

        <!-- Notifications -->
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
                  <strong>Program Evaluation</strong>
                  <p>We need evaluation for the KK-Assembly Event</p>
                </div>
                <span class="notif-dot"></span>
              </li>
              <li>
                <div class="notif-icon"></div>
                <div class="notif-content">
                  <strong>Program Evaluation</strong>
                  <p>We need evaluation for the KK-Assembly Event</p>
                </div>
                <span class="notif-dot"></span>
              </li>
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
                <h4>{{ $user->given_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->suffix }}</h4>
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
                <a href="loginpage" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
            
            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- Event Form -->
    <section class="event-form">
      <h2>Edit Event</h2>
      <p class="subtitle">Update event details for youth involvement.</p>

      <!-- Display Success/Error Messages -->
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-error">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="editEventForm" method="POST" action="{{ route('events.update', $event->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="form-group">
          <label for="title" style="display:block; font-weight:700; margin-bottom:0.5rem;">Title</label>
          <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required>
        </div>

        <!-- Row: Date, Time, Category -->
        <div class="form-row">
          <!-- Date -->
          <div class="form-group date">
            <label for="date">Event Date</label>
            <input type="date" id="date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
          </div>

          <!-- Time -->
          <div class="form-group time">
            <label for="time">Time</label>
            <div class="input-with-icon">
              <input type="text" id="time" name="event_time" value="{{ old('event_time', $event->event_time) }}" readonly required>
              <i data-lucide="clock" class="icon"></i>
            </div>
          </div>

          <!-- Category -->
          <div class="form-group category">
            <label for="category">Select Event Category</label>
            <div class="custom-select" id="categorySelect" tabindex="0" role="listbox" aria-haspopup="listbox">
              <div class="selected" data-value="{{ $event->category }}">
                <span class="selected-text">{{ ucfirst(str_replace('_', ' ', $event->category)) }}</span>
                <div class="icon-circle small">
                  <i data-lucide="chevron-down" class="dropdown-icon"></i>
                </div>
              </div>
              <input type="hidden" id="category" name="category" value="{{ $event->category }}" required>
              <ul class="options" role="presentation">
                <li data-value="active_citizenship" role="option">Active Citizenship</li>
                <li data-value="economic_empowerment" role="option">Economic Empowerment</li>
                <li data-value="education" role="option">Education</li>
                <li data-value="health" role="option">Health</li>
                <li data-value="sports" role="option">Sports</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Note -->
        <div class="notification-item">
          <div class="icon-circle">
            <i class="fa-solid fa-bell"></i>
          </div>
          <p class="note">This event will take place on <span id="displayDate">{{ $event->event_date->format('F j, Y') }}</span>, beginning at <span id="displayTime">{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</span>.</p>
        </div>

        <!-- Location -->
        <div class="form-group">
          <label for="location" style="display:block; font-weight:700; margin-bottom:0.5rem;">Location</label>
          <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" required>
        </div>

        <!-- Description -->
        <div class="form-group">
          <label for="description" style="display:block; font-weight:700; margin-bottom:0.5rem;">Add Description</label>
          <textarea id="description" name="description" rows="4">{{ old('description', $event->description) }}</textarea>
        </div>

        <!-- Upload -->
        <label>Upload Display</label>
        <div class="upload-box" id="uploadBox">
          <input type="file" id="upload" name="image" hidden accept="image/*">

          <!-- Browse UI -->
          <label for="upload" class="upload-label" id="uploadLabel" style="@if($event->image) display:none; @else display:flex; @endif">
            <i class="fas fa-image"></i>
            <p>Drag your photo here or <span>Browse from device</span></p>
          </label>

          <!-- Preview UI -->
          <div class="image-preview" id="imagePreview" style="@if($event->image) display:flex; @else display:none; @endif">
            <img id="previewImg" src="{{ $event->image ? asset('storage/' . $event->image) : '' }}" alt="Preview">
            <button type="button" id="removeBtn">Remove</button>
          </div>
        </div>

        <!-- Current Image Info -->
        @if($event->image)
          <div class="current-image-info">
            <p><small>Current image: {{ basename($event->image) }}</small></p>
          </div>
        @endif

        <!-- Publisher -->
        <div class="form-group">
          <label for="publisher" style="display:block; font-weight:700; margin-bottom:0.5rem;">Published By:</label>
          <input type="text" id="publisher" name="published_by" value="{{ old('published_by', $event->published_by) }}" required>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit">Update Event</button>
        <a href="{{ route('sk-eventpage') }}" class="btn-cancel">Cancel</a>
      </form>
    </section>

    <!-- Time Picker Modal -->
    <div id="timePickerModal" class="time-modal">
      <div class="time-modal-content">
        <p class="modal-title">Set time</p>
        <div class="time-controls">
          <div class="time-unit">
            <button type="button" class="arrow up" data-type="hour">▲</button>
            <div id="hour" class="time-value">01</div>
            <button type="button" class="arrow down" data-type="hour">▼</button>
          </div>
          <span class="colon">:</span>
          <div class="time-unit">
            <button type="button" class="arrow up" data-type="minute">▲</button>
            <div id="minute" class="time-value">00</div>
            <button type="button" class="arrow down" data-type="minute">▼</button>
          </div>
          <div class="ampm-toggle">
            <button type="button" id="amBtn" class="ampm active">AM</button>
            <button type="button" id="pmBtn" class="ampm">PM</button>
          </div>
        </div>
        <div class="time-actions">
          <button type="button" id="cancelTime" class="btn cancel">Cancel</button>
          <button type="button" id="setTime" class="btn set">Set</button>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="editEventModal" class="modal-overlay">
      <div class="modal-box">
        <p>Are you sure you want to edit this field? Editing will reschedule the event. Do you want to continue?</p>
        <div class="modal-actions">
          <button class="btn cancel" id="cancelEditEvent">Cancel</button>
          <button class="btn save" id="saveEditEvent">Yes</button>
        </div>
      </div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="success-popup">
      <p>Event updated successfully!</p>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", () => {
    // === Initialize Lucide icons ===
    if (typeof lucide !== "undefined" && lucide.createIcons) lucide.createIcons();

    // --- SIDEBAR / MENU ---
    const menuToggle = document.querySelector(".menu-toggle");
    const sidebar = document.querySelector(".sidebar");

    if (menuToggle && sidebar) {
      menuToggle.addEventListener("click", (e) => {
        e.stopPropagation();
        sidebar.classList.toggle("open");
      });
    }

    // --- EVALUATION SUBMENU ---
    const evaluationItem = document.querySelector(".evaluation-item");
    const evaluationLink = document.querySelector(".evaluation-link");
    evaluationLink?.addEventListener("click", (e) => {
      e.preventDefault();
      evaluationItem?.classList.toggle("open");
    });

    // --- TIME AUTO UPDATE ---
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

    // --- NOTIFICATIONS + PROFILE ---
    const notifWrapper = document.querySelector(".notification-wrapper");
    const profileWrapper = document.querySelector(".profile-wrapper");
    const profileToggle = document.getElementById("profileToggle");

    if (notifWrapper) {
      notifWrapper.querySelector(".fa-bell")?.addEventListener("click", (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle("active");
        profileWrapper?.classList.remove("active");
      });
    }

    if (profileWrapper && profileToggle) {
      profileToggle.addEventListener("click", (e) => {
        e.stopPropagation();
        profileWrapper.classList.toggle("active");
        notifWrapper?.classList.remove("active");
      });
    }

    // Close dropdowns when clicking outside
    document.addEventListener("click", () => {
      notifWrapper?.classList.remove("active");
      profileWrapper?.classList.remove("active");
    });

    // === CUSTOM SELECT ===
    const customSelect = document.querySelector(".custom-select");
    if (customSelect) {
      const selected = customSelect.querySelector(".selected");
      const optionsContainer = customSelect.querySelector(".options");
      const hiddenInput = document.getElementById("category");

      selected.addEventListener("click", (e) => {
        e.stopPropagation();
        optionsContainer.classList.toggle("show");
        optionsContainer.style.display = optionsContainer.classList.contains("show") ? "block" : "none";
      });

      optionsContainer.querySelectorAll("li").forEach(option => {
        option.addEventListener("click", () => {
          selected.querySelector(".selected-text").textContent = option.textContent;
          selected.setAttribute("data-value", option.dataset.value);
          hiddenInput.value = option.dataset.value;
          optionsContainer.classList.remove("show");
          optionsContainer.style.display = "none";
        });
      });

      document.addEventListener("click", (e) => {
        if (!customSelect.contains(e.target)) {
          optionsContainer.classList.remove("show");
          optionsContainer.style.display = "none";
        }
      });
    }

    // --- UPLOAD BOX ---
    const uploadBox = document.getElementById("uploadBox");
    const uploadInput = document.getElementById("upload");
    const uploadLabel = document.getElementById("uploadLabel");
    const previewContainer = document.getElementById("imagePreview");
    const previewImg = document.getElementById("previewImg");
    const removeBtn = document.getElementById("removeBtn");

    uploadInput.addEventListener("change", function() { 
        showPreview(this.files[0]); 
    });
    
    uploadBox.addEventListener("dragover", (e) => { 
        e.preventDefault(); 
        uploadBox.style.borderColor = "#01214A"; 
        uploadBox.style.background = "#eef5ff"; 
    });
    
    uploadBox.addEventListener("dragleave", () => { 
        uploadBox.style.borderColor = "#3C87C4"; 
        uploadBox.style.background = "#f9fbfd"; 
    });
    
    uploadBox.addEventListener("drop", (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = "#3C87C4";
        uploadBox.style.background = "#f9fbfd";
        const file = e.dataTransfer.files[0];
        if (file) { 
            uploadInput.files = e.dataTransfer.files; 
            showPreview(file); 
        }
    });

    function showPreview(file) {
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = "flex";
                uploadLabel.style.display = "none";
            }
            reader.readAsDataURL(file);
        }
    }

    removeBtn.addEventListener("click", () => {
        uploadInput.value = "";
        previewImg.src = "";
        previewContainer.style.display = "none";
        uploadLabel.style.display = "flex";
    });

    // === TIME PICKER MODAL ===
    const timeInput = document.getElementById("time");
    const timeModal = document.getElementById("timePickerModal");
    const hourEl = document.getElementById("hour");
    const minuteEl = document.getElementById("minute");
    const amBtn = document.getElementById("amBtn");
    const pmBtn = document.getElementById("pmBtn");
    const cancelTimeBtn = document.getElementById("cancelTime");
    const setTimeBtn = document.getElementById("setTime");

    // Parse current time value and set initial state
    function initializeTimePicker() {
        const currentTime = timeInput.value;
        if (currentTime) {
            const timeParts = currentTime.split(':');
            if (timeParts.length >= 2) {
                let hours = parseInt(timeParts[0]);
                const minutes = timeParts[1].split(' ')[0];
                const ampm = currentTime.includes('PM') ? 'PM' : 'AM';
                
                // Convert to 12-hour format for display
                if (hours > 12) hours -= 12;
                if (hours === 0) hours = 12;
                
                hourEl.textContent = String(hours).padStart(2, '0');
                minuteEl.textContent = minutes;
                
                if (ampm === 'PM') {
                    pmBtn.classList.add('active');
                    amBtn.classList.remove('active');
                } else {
                    amBtn.classList.add('active');
                    pmBtn.classList.remove('active');
                }
            }
        }
    }

    // Initialize time picker with current values
    initializeTimePicker();

    // Open modal
    function openTimeModal(e) {
        e.stopPropagation();
        timeModal.style.display = "flex";
    }
    timeInput.addEventListener("click", openTimeModal);

    // Cancel button
    cancelTimeBtn.addEventListener("click", () => {
        timeModal.style.display = "none";
    });

    // Set button
    setTimeBtn.addEventListener("click", () => {
        let hour = parseInt(hourEl.textContent);
        const minute = minuteEl.textContent;
        const ampm = amBtn.classList.contains("active") ? "AM" : "PM";
        
        // Convert to 24-hour format for storage
        if (ampm === 'PM' && hour < 12) hour += 12;
        if (ampm === 'AM' && hour === 12) hour = 0;
        
        const time24 = `${String(hour).padStart(2, '0')}:${minute}`;
        const time12 = `${hourEl.textContent}:${minute} ${ampm}`;
        
        timeInput.value = time24;
        document.getElementById('displayTime').textContent = time12;
        
        timeModal.style.display = "none";
    });

    // Increment / Decrement
    document.querySelectorAll(".arrow").forEach(btn => {
        btn.addEventListener("click", () => {
            const type = btn.dataset.type;
            if (type === "hour") {
                let val = parseInt(hourEl.textContent, 10);
                val = btn.classList.contains("up") ? (val >= 12 ? 1 : val + 1) : (val <= 1 ? 12 : val - 1);
                hourEl.textContent = String(val).padStart(2, "0");
            }
            if (type === "minute") {
                let val = parseInt(minuteEl.textContent, 10);
                val = btn.classList.contains("up") ? (val >= 59 ? 0 : val + 1) : (val <= 0 ? 59 : val - 1);
                minuteEl.textContent = String(val).padStart(2, "0");
            }
        });
    });

    // AM/PM toggle
    amBtn.addEventListener("click", () => {
        amBtn.classList.add("active");
        pmBtn.classList.remove("active");
    });
    
    pmBtn.addEventListener("click", () => {
        pmBtn.classList.add("active");
        amBtn.classList.remove("active");
    });

    // Backdrop close
    timeModal.addEventListener("click", (e) => {
        if (e.target === timeModal) timeModal.style.display = "none";
    });

    // ESC close
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && timeModal.style.display === "flex") {
            timeModal.style.display = "none";
        }
    });

    // === DATE CHANGE HANDLER ===
    const dateInput = document.getElementById("date");
    dateInput.addEventListener("change", function() {
        const newDate = new Date(this.value);
        document.getElementById('displayDate').textContent = newDate.toLocaleDateString('en-US', { 
            month: 'long', 
            day: 'numeric', 
            year: 'numeric' 
        });
    });

    // === FORM SUBMISSION ===
    const editEventForm = document.getElementById('editEventForm');
    editEventForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const title = document.getElementById('title').value.trim();
        const date = document.getElementById('date').value;
        const time = document.getElementById('time').value;
        const category = document.getElementById('category').value;
        const location = document.getElementById('location').value.trim();
        const publisher = document.getElementById('publisher').value.trim();
        
        if (!title || !date || !time || !category || !location || !publisher) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Submit the form
        this.submit();
    });

    // === SUCCESS POPUP ===
    const successPopup = document.getElementById("successPopup");
    function showSuccessPopup(message = "Event updated successfully!") {
        successPopup.querySelector("p").textContent = message;
        successPopup.classList.add("show");
        setTimeout(() => {
            successPopup.classList.remove("show");
        }, 3000);
    }

    // Check if there's a success message from the server
    const successMessage = document.querySelector('.alert-success');
    if (successMessage) {
        showSuccessPopup(successMessage.textContent.trim());
    }
  });
  </script>
</body>
</html>