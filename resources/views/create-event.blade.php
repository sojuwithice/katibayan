<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Create Event</title>
  <link rel="stylesheet" href="{{ asset('css/create-event.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
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

      <a href="{{ route('sk-eventpage') }}" class="active events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>

      <div class="evaluation-item nav-item">
        <a href="{{ route('sk-evaluation-feedback') }}" class="evaluation-link nav-link">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="#">Feedbacks</a>
          <a href="#">Polls</a>
          <a href="#">Suggestion Box</a>
        </div>
      </div>

      <a href="#">
        <i data-lucide="file-chart-column"></i>
        <span class="label">Reports</span>
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
          <img src="https://i.pravatar.cc/80" alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="https://i.pravatar.cc/80" alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>Marijoy S. Novora</h4>
                <div class="profile-badge">
                  <span class="badge">KK- Member</span>
                  <span class="badge">19 yrs old</span>
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
            </ul>
          </div>
        </div>
      </div>
    </header>

    <!-- Event Form -->
    <section class="event-form">
      <h2>Create Event</h2>
      <p class="subtitle">Set up events or programs designed for youth involvement.</p>

      @if($errors->any())
        <div class="alert alert-error">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" id="eventForm">
        @csrf
        
        <!-- Title -->
        <div class="form-group">
          <label for="title" style="display:block; font-weight:700; margin-bottom:0.5rem;">Title</label>
          <input type="text" id="title" name="title" value="{{ old('title') }}" required>
          @error('title') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <!-- Row: Date, Time, Category -->
        <div class="form-row">
          <!-- Date -->
          <div class="form-group date">
            <label for="date">Event Date</label>
            <input type="date" id="date" name="event_date" value="{{ old('event_date') }}" required>
            @error('event_date') <span class="error-text">{{ $message }}</span> @enderror
          </div>

      <!-- Time -->
<div class="form-group time">
    <label for="time">Time</label>
    <div class="input-with-icon">
        <input type="text" id="time" name="event_time_display" readonly value="{{ old('event_time_display') }}" required>
        <input type="hidden" id="timeValue" name="event_time" value="{{ old('event_time') }}">
        <i data-lucide="clock" class="icon"></i>
    </div>
    @error('event_time') <span class="error-text">{{ $message }}</span> @enderror
    @error('event_time_display') <span class="error-text">{{ $message }}</span> @enderror
</div>

          <!-- Category -->
          <div class="form-group category">
            <label for="category">Select Event Category</label>
            <div class="custom-select" id="categorySelect" tabindex="0" role="listbox" aria-haspopup="listbox">
              <div class="selected" data-value="{{ old('category', '') }}">
                <span class="selected-text">{{ old('category') ? ucfirst(str_replace('_', ' ', old('category'))) : 'Select Category' }}</span>
                <div class="icon-circle small">
                  <i data-lucide="chevron-down" class="dropdown-icon"></i>
                </div>
              </div>
              <input type="hidden" name="category" id="categoryInput" value="{{ old('category') }}">
              <ul class="options" role="presentation">
                <li data-value="active_citizenship" role="option">Active Citizenship</li>
                <li data-value="economic_empowerment" role="option">Economic Empowerment</li>
                <li data-value="education" role="option">Education</li>
                <li data-value="health" role="option">Health</li>
                <li data-value="sports" role="option">Sports</li>
                <li class="add-category">+ Add another category</li>
              </ul>
            </div>
            @error('category') <span class="error-text">{{ $message }}</span> @enderror
          </div>
        </div>

        <!-- Dynamic Note -->
        <div class="notification-item" id="dateNote" style="display: none;">
          <div class="icon-circle">
            <i class="fa-solid fa-bell"></i>
          </div>
          <p class="note" id="noteText">This event will take place on <span id="eventDateDisplay"></span>, beginning at <span id="eventTimeDisplay"></span>.</p>
        </div>

        <!-- Location -->
        <div class="form-group">
          <label for="location" style="display:block; font-weight:700; margin-bottom:0.5rem;">Location</label>
          <input type="text" id="location" name="location" value="{{ old('location') }}" required>
          @error('location') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <!-- Description -->
        <div class="form-group">
          <label for="description" style="display:block; font-weight:700; margin-bottom:0.5rem;">Add Description</label>
          <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
          @error('description') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <!-- Upload -->
        <label>Upload Display</label>
        <div class="upload-box" id="uploadBox">
          <input type="file" id="upload" name="image" hidden accept="image/*">
          
          <!-- Browse UI -->
          <label for="upload" class="upload-label" id="uploadLabel">
            <i class="fas fa-image"></i>
            <p>Drag your photo here or <span>Browse from device</span></p>
          </label>

          <!-- Preview UI -->
          <div class="image-preview" id="imagePreview" style="display:none;">
            <img id="previewImg" src="" alt="Preview">
            <button type="button" id="removeBtn">Remove</button>
          </div>
        </div>
        @error('image') <span class="error-text">{{ $message }}</span> @enderror

        <!-- Publisher -->
        <div class="form-group">
          <label for="publisher" style="display:block; font-weight:700; margin-bottom:0.5rem;">Published By:</label>
          <input type="text" id="publisher" name="published_by" value="{{ old('published_by') }}" required>
          @error('published_by') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit">Post Event</button>
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

    <!-- Add Category Modal -->
    <div class="modal" id="addCategoryModal">
      <div class="modal-content">
        <h3>Add New Category</h3>
        <input type="text" id="newCategoryInput" placeholder="Enter category name">
        <div class="modal-actions">
          <button id="cancelAddCategory">Cancel</button>
          <button id="saveAddCategory">Add</button>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
      <div class="modal-content success-modal">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h3>Event Created Successfully!</h3>
        <p>Your event has been created and is now visible in the events list.</p>
        <div class="modal-actions">
          <button id="viewEvents" class="btn-primary">View Events</button>
          <button id="createAnother" class="btn-secondary">Create Another</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === Safe icon init ===
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

      // === CUSTOM SELECT WITH MODAL ===
      const customSelect = document.querySelector("#categorySelect");
      if (customSelect) {
        const selected = customSelect.querySelector(".selected");
        const optionsContainer = customSelect.querySelector(".options");
        const categoryInput = document.getElementById("categoryInput");
        const addCategoryBtn = optionsContainer.querySelector(".add-category");

        const modal = document.getElementById("addCategoryModal");
        const cancelBtn = document.getElementById("cancelAddCategory");
        const saveBtn = document.getElementById("saveAddCategory");
        const newCategoryInput = document.getElementById("newCategoryInput");

        selected.addEventListener("click", (e) => {
          e.stopPropagation();
          optionsContainer.classList.toggle("show");
          optionsContainer.style.display = optionsContainer.classList.contains("show") ? "block" : "none";
        });

        const attachOptionListener = (option) => {
          option.addEventListener("click", () => {
            const value = option.getAttribute("data-value");
            const text = option.textContent;
            
            selected.querySelector(".selected-text").textContent = text;
            selected.setAttribute("data-value", value);
            categoryInput.value = value;
            
            optionsContainer.classList.remove("show");
            optionsContainer.style.display = "none";
            updateDateNote();
          });
        };

        optionsContainer.querySelectorAll("li:not(.add-category)").forEach(attachOptionListener);

        document.addEventListener("click", (e) => {
          if (!customSelect.contains(e.target)) {
            optionsContainer.classList.remove("show");
            optionsContainer.style.display = "none";
          }
        });

        addCategoryBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          optionsContainer.classList.remove("show");
          optionsContainer.style.display = "none";
          modal.style.display = "flex";
          newCategoryInput.value = "";
          newCategoryInput.focus();
        });

        cancelBtn.addEventListener("click", () => { modal.style.display = "none"; });

        window.addEventListener("click", (e) => { 
          if (e.target === modal) modal.style.display = "none"; 
        });

        const addNewCategory = () => {
          const newValue = newCategoryInput.value.trim();
          if (newValue !== "") {
            const formattedValue = newValue.toLowerCase().replace(/\s+/g, "_");
            const newOption = document.createElement("li");
            newOption.setAttribute("data-value", formattedValue);
            newOption.setAttribute("role", "option");
            newOption.textContent = newValue;
            
            attachOptionListener(newOption);
            optionsContainer.insertBefore(newOption, addCategoryBtn);
            
            selected.querySelector(".selected-text").textContent = newValue;
            selected.setAttribute("data-value", formattedValue);
            categoryInput.value = formattedValue;
            
            modal.style.display = "none";
            updateDateNote();
          }
        };
        
        saveBtn.addEventListener("click", addNewCategory);
        newCategoryInput.addEventListener("keypress", (e) => { 
          if (e.key === "Enter") { 
            e.preventDefault(); 
            addNewCategory(); 
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

      uploadInput.addEventListener("change", function() { showPreview(this.files[0]); });
      
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
const timeValueInput = document.getElementById("timeValue");
const timeModal = document.getElementById("timePickerModal");
const hourEl = document.getElementById("hour");
const minuteEl = document.getElementById("minute");
const amBtn = document.getElementById("amBtn");
const pmBtn = document.getElementById("pmBtn");
const cancelTimeBtn = document.getElementById("cancelTime");
const setTimeBtn = document.getElementById("setTime");

// Set default time on page load
function setDefaultTime() {
    const now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    // Convert to 12-hour format for display
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    
    hourEl.textContent = hours.toString().padStart(2, '0');
    minuteEl.textContent = minutes.toString().padStart(2, '0');
    
    // Set AM/PM
    if (ampm === 'PM') {
        pmBtn.classList.add('active');
        amBtn.classList.remove('active');
    } else {
        amBtn.classList.add('active');
        pmBtn.classList.remove('active');
    }
    
    // Set initial values
    updateTimeValues();
}

// Update both display and hidden inputs
function updateTimeValues() {
    let hour = parseInt(hourEl.textContent, 10);
    const minute = minuteEl.textContent;
    const ampm = amBtn.classList.contains("active") ? "AM" : "PM";
    
    // Convert to 24-hour format for database
    let dbHour = hour;
    if (ampm === 'PM' && hour < 12) {
        dbHour = hour + 12;
    } else if (ampm === 'AM' && hour === 12) {
        dbHour = 0;
    }
    
    const displayTime = `${hour.toString().padStart(2, '0')}:${minute} ${ampm}`;
    const storageTime = `${dbHour.toString().padStart(2, '0')}:${minute}:00`;
    
    timeInput.value = displayTime;
    timeValueInput.value = storageTime;
}

// --- Open modal
function openTimeModal(e) {
    e.stopPropagation();
    timeModal.style.display = "flex";
}
timeInput.addEventListener("click", openTimeModal);

// --- Cancel button
cancelTimeBtn.addEventListener("click", () => {
    timeModal.style.display = "none";
});

// --- Set button
setTimeBtn.addEventListener("click", () => {
    updateTimeValues();
    timeModal.style.display = "none";
    updateDateNote();
});

// --- Increment / Decrement
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

// --- AM/PM toggle
amBtn.addEventListener("click", () => {
    amBtn.classList.add("active");
    pmBtn.classList.remove("active");
});

pmBtn.addEventListener("click", () => {
    pmBtn.classList.add("active");
    amBtn.classList.remove("active");
});

// --- Backdrop close
timeModal.addEventListener("click", (e) => {
    if (e.target === timeModal) timeModal.style.display = "none";
});

// --- ESC close
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && timeModal.style.display === "flex") {
        timeModal.style.display = "none";
    }
});

// Initialize default time when page loads
setDefaultTime();
      // === DATE NOTE UPDATE ===
      function updateDateNote() {
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');
        const noteElement = document.getElementById('dateNote');
        const eventDateDisplay = document.getElementById('eventDateDisplay');
        const eventTimeDisplay = document.getElementById('eventTimeDisplay');

        if (dateInput.value && timeInput.value) {
          const date = new Date(dateInput.value);
          const options = { year: 'numeric', month: 'long', day: 'numeric' };
          const formattedDate = date.toLocaleDateString('en-US', options);
          
          eventDateDisplay.textContent = formattedDate;
          eventTimeDisplay.textContent = timeInput.value;
          noteElement.style.display = 'flex';
        } else {
          noteElement.style.display = 'none';
        }
      }

      // Event listeners for date and time changes
      document.getElementById('date').addEventListener('change', updateDateNote);
      document.getElementById('time').addEventListener('change', updateDateNote);

      // Initialize date note if there are old values
      updateDateNote();

      // === FORM SUBMISSION ===
      const eventForm = document.getElementById('eventForm');
      const successModal = document.getElementById('successModal');
      const viewEventsBtn = document.getElementById('viewEvents');
      const createAnotherBtn = document.getElementById('createAnother');

      eventForm.addEventListener('submit', function(e) {
        // Basic validation
        const title = document.getElementById('title').value;
        const date = document.getElementById('date').value;
        const time = document.getElementById('time').value;
        const category = document.getElementById('categoryInput').value;
        const location = document.getElementById('location').value;
        const publisher = document.getElementById('publisher').value;

        if (!title || !date || !time || !category || !location || !publisher) {
          e.preventDefault();
          alert('Please fill in all required fields.');
          return;
        }

        // If we're here, form is valid and will submit via normal POST
      });

      // Success modal handlers (for future AJAX implementation)
      viewEventsBtn.addEventListener('click', function() {
        window.location.href = "{{ route('sk-eventpage') }}";
      });

      createAnotherBtn.addEventListener('click', function() {
        successModal.style.display = 'none';
        eventForm.reset();
        document.getElementById('dateNote').style.display = 'none';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('uploadLabel').style.display = 'flex';
      });

      // Close modals when clicking outside
      document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
          e.target.style.display = 'none';
        }
      });

      // Close dropdowns when clicking outside
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.notification-wrapper') && notifWrapper) {
          notifWrapper.classList.remove('active');
        }
        if (!e.target.closest('.profile-wrapper') && profileWrapper) {
          profileWrapper.classList.remove('active');
        }
        if (!e.target.closest('.custom-select') && customSelect) {
          const options = customSelect.querySelector('.options');
          options.classList.remove('show');
          options.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>