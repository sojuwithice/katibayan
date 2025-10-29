<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Create Program</title>
  <link rel="stylesheet" href="{{ asset('css/create-program.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
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

      <a href="{{ route('youth-profilepage') }}" class="active">
        <i data-lucide="users"></i>
        <span class="label">Youth Profile</span>
      </a>

      <a href="{{ route('sk-eventpage') }}" class="events-link">
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
  <h2>Create Program</h2>
  <p class="subtitle">Set up events or programs designed for youth involvement.</p>

  <form id="programForm" action="{{ route('programs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Title -->
    <div class="form-group">
      <label for="title" style="display:block; font-weight:700; margin-bottom:0.5rem;">Title</label>
      <input type="text" id="title" name="title" required>
    </div>

    <!-- Row: Start Date, End Date, Time, Category -->
    <div class="form-row">
      <!-- Start Date -->
      <div class="form-group date">
        <label for="date">Event Start Date</label>
        <input type="date" id="date" name="event_date" required>
      </div>

      <!-- End Date -->
      <div class="form-group date">
        <label for="endDate">Event End Date</label>
        <input type="date" id="endDate" name="event_end_date">
      </div>

      <!-- Time -->
      <div class="form-group time">
        <label for="time">Time</label>
        <div class="input-with-icon">
          <input type="text" id="time" readonly name="event_time" required>
          <i data-lucide="clock" class="icon"></i>
        </div>
      </div>

      <!-- Category -->
      <div class="form-group category">
        <label for="category">Select Event Category</label>
        <div class="custom-select" id="category" tabindex="0" role="listbox" aria-haspopup="listbox">
          <div class="selected" data-value="">
            <span class="selected-text">Select Category</span>
            <div class="icon-circle small">
              <i data-lucide="chevron-down" class="dropdown-icon"></i>
            </div>
          </div>
          <input type="hidden" id="categoryInput" name="category" required>
          <ul class="options" role="presentation">
            <li data-value="active citizenship" role="option">Active Citizenship</li>
            <li data-value="economic empowerment" role="option">Economic Empowerment</li>
            <li data-value="education" role="option">Education</li>
            <li data-value="health" role="option">Health</li>
            <li data-value="sports" role="option">Sports</li>
            <li class="add-category">+ Add another category</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Note -->
    <div class="notification-item">
      <div class="icon-circle">
        <i class="fa-solid fa-bell"></i>
      </div>
      <p class="note" id="eventDateNote">This event will take place on —, beginning at —.</p>
    </div>

    <!-- Location -->
    <div class="form-group">
      <label for="location" style="display:block; font-weight:700; margin-bottom:0.5rem;">Location</label>
      <input type="text" id="location" name="location" required>
    </div>

    <!-- Description -->
    <div class="form-group">
      <label for="description" style="display:block; font-weight:700; margin-bottom:0.5rem;">Add Description</label>
      <textarea id="description" rows="4" name="description"></textarea>
    </div>

    <!-- Upload -->
    <label>Upload Display</label>
    <div class="upload-box" id="uploadBox">
      <input type="file" id="upload" hidden accept="image/*" name="display_image">

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

    <!-- Publisher -->
    <div class="form-group">
      <label for="publisher" style="display:block; font-weight:700; margin-bottom:0.5rem;">Published By:</label>
      <input type="text" id="publisher" name="published_by" required>
    </div>

    <!-- Youth Registration Option -->
    <div class="form-group">
      <label style="display:block; font-weight:100; margin-bottom:0.7rem;">
        Choose an option (for youth registration)
      </label>
      <div class="registration-options">
        <label>
          <input type="radio" name="registration_type" value="create">
          Create Registration
        </label>
        <label style="margin-left: 1rem;">
          <input type="radio" name="registration_type" value="link">
          Add link source
        </label>
      </div>
    </div>

    <!-- Link Source Field -->
    <div id="linkSourceField" class="form-group" style="display:none;">
      <div class="link-source-wrapper">
        <i class="fas fa-link link-icon"></i>
        <input type="url" id="linkSource" placeholder="Link Source" name="link_source">
      </div>

      <!-- Post Program Button for Link Source -->
      <div class="form-actions">
        <button type="submit" class="btn-submit postProgramBtn">Post Program</button>
      </div>
    </div>

    <!-- Create Registration Fields -->
    <div id="createRegistrationFields" class="regform-container" style="display:none;">
      <h3 class="regform-title">Registration Form</h3>

      <!-- Title -->
      <div class="regform-block-wide">
        <label class="regform-label">Title</label>
        <input type="text" class="regform-input" id="registrationTitle" name="registration_title">
      </div>

      <!-- Description -->
      <div class="regform-block-wide">
        <label class="regform-label">Add description</label>
        <textarea class="regform-textarea" rows="3" id="registrationDescription" name="registration_description"></textarea>
      </div>

      <!-- Registration Period -->
      <div class="regform-period">
        <h4>Registration Period (Set date and time)</h4>
        <div class="regform-dates">
          <!-- Opens -->
          <div class="regform-block">
            <label class="regform-label">Registration Opens</label>
            <div class="regform-datetime">
              <div class="regform-datetime-icon">
                <input type="date" id="openDate" name="registration_open_date" />
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="regform-datetime-icon">
                <input type="time" id="openTime" name="registration_open_time" />
                <i class="fas fa-clock"></i>
              </div>
            </div>
          </div>

          <!-- Closes -->
          <div class="regform-block">
            <label class="regform-label">Registration Closes</label>
            <div class="regform-datetime">
              <div class="regform-datetime-icon">
                <input type="date" id="closeDate" name="registration_close_date" />
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="regform-datetime-icon">
                <input type="time" id="closeTime" name="registration_close_time" />
                <i class="fas fa-clock"></i>
              </div>
            </div>
          </div>
        </div>

        <p class="regform-note" id="regNote">
          Registration opens: —<br>
          Registration closes: —
        </p>
      </div>

      <!-- Registration Fields -->
      <div class="regform-fields">
        <h4>Registration Fields</h4>

        <!-- Default Fields Container -->
        <div id="defaultFields">
          <!-- Default fields will be added here dynamically -->
        </div>

        <!-- Extra question fields (container for all fields) -->
        <div id="extra-fields">
          <!-- One field -->
          <div class="regform-field" data-field-id="field_1">
            <!-- 3 dots sa labas -->
            <span class="regform-dots">⋯</span>

            <!-- Dropdown menu -->
            <div class="dots-menu hidden">
              <p class="edit-option"><i class="fas fa-edit"></i> Edit</p>
              <p class="delete-option"><i class="fas fa-trash"></i> Delete</p>
            </div>

            <!-- Card -->
            <div class="regform-extra">
              <!-- Card content -->
              <div class="regform-main">
                <div class="regform-top">
                  <!-- Question input -->
                  <input type="text" placeholder="Add question" class="field-label" />

                  <!-- Answer type dropdown (upper right) -->
                  <div class="answer-type-wrapper">
                    <span class="answer-type">Choose type of answer</span>
                    <div class="answer-dropdown">
                      <p data-type="text">Short answer</p>
                      <p data-type="radio">Radio button</p>
                      <p data-type="select">Dropdown</p>
                      <p data-type="file">File upload</p>
                    </div>
                  </div>
                </div>

                <!-- Answer preview (short) -->
                <div class="answer-preview hidden" data-type="text">
                  <input type="text" placeholder="Short answer" disabled />
                </div>

                <!-- Radio button preview -->
                <div class="answer-radio hidden" data-type="radio">
                  <div class="options-box">
                    <div class="option-item">
                      <input type="radio" name="sampleRadio_1" disabled>
                      <span contenteditable="true" class="editable">Option 1</span>
                    </div>
                    <div class="option-item">
                      <input type="radio" name="sampleRadio_1" disabled>
                      <span contenteditable="true" class="editable">Option 2</span>
                    </div>
                  </div>
                  <a href="#" class="add-option">+ Add option</a>
                </div>

                <!-- Dropdown preview -->
                <div class="answer-select hidden" data-type="select">
                  <div class="options-box">
                    <div class="option-item">
                      <span contenteditable="true" class="editable">Option 1</span>
                    </div>
                    <div class="option-item">
                      <span contenteditable="true" class="editable">Option 2</span>
                    </div>
                  </div>
                  <a href="#" class="add-option">+ Add option</a>
                </div>

                <!-- File upload preview -->
                <div class="answer-file hidden" data-type="file">
                  <input type="file" disabled />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Add button -->
        <div class="add-btn-wrapper">
          <button type="button" class="regform-add-btn" id="addFieldBtn">
            + Add Another Field
          </button>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-submit postProgramBtn">Post Program</button>
        </div>
      </div>
    </div>
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
      <button type="button" id="cancelAddCategory">Cancel</button>
      <button type="button" id="saveAddCategory">Add</button>
    </div>
  </div>
</div>

<!-- Posted Modal -->
<div id="postedModal" class="posted-modal">
  <div class="posted-modal-content">
    <div class="posted-icon">
      <i class="fa-solid fa-circle-check"></i>
    </div>
    <h2>Posted</h2>
    <p>The program has already been created and posted.</p>
    <button id="closePostedModal">OK</button>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // === safe icon init ===
  if (typeof lucide !== "undefined" && lucide.createIcons) lucide.createIcons();

  // --- SIDEBAR / MENU ---
  const menuToggle = document.querySelector(".menu-toggle");
  const sidebar = document.querySelector(".sidebar");
  const profileItem = document.querySelector(".profile-item");

  if (menuToggle && sidebar) {
    menuToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      sidebar.classList.toggle("open");
      if (!sidebar.classList.contains("open")) profileItem?.classList.remove("open");
    });
  }

  // --- EVALUATION SUBMENU ---
  const evaluationItem = document.querySelector(".evaluation-item");
  const evaluationLink = document.querySelector(".evaluation-link");
  evaluationLink?.addEventListener("click", (e) => {
    e.preventDefault();
    evaluationItem?.classList.toggle("open");
  });

  // --- PROFILE DROPDOWN FIX ---
  const profileWrapper = document.querySelector(".profile-wrapper");
  const profileToggle = document.getElementById("profileToggle");
  const profileDropdown = document.querySelector(".profile-dropdown");

  if (profileWrapper && profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!profileWrapper.contains(e.target)) {
        profileWrapper.classList.remove("active");
      }
    });

    // Prevent dropdown from closing when clicking inside it
    profileDropdown.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  // === CUSTOM SELECT WITH MODAL ===
  const customSelect = document.querySelector(".custom-select");
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
        selected.querySelector(".selected-text").textContent = option.textContent;
        selected.setAttribute("data-value", option.dataset.value);
        categoryInput.value = option.dataset.value;
        optionsContainer.classList.remove("show");
        optionsContainer.style.display = "none";
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

    window.addEventListener("click", (e) => { if (e.target === modal) modal.style.display = "none"; });

    const addNewCategory = () => {
      const newValue = newCategoryInput.value.trim();
      if (newValue !== "") {
        const newOption = document.createElement("li");
        newOption.setAttribute("data-value", newValue.toLowerCase().replace(/\s+/g, "-"));
        newOption.setAttribute("role", "option");
        newOption.textContent = newValue;
        attachOptionListener(newOption);
        optionsContainer.insertBefore(newOption, addCategoryBtn);
        selected.querySelector(".selected-text").textContent = newValue;
        selected.setAttribute("data-value", newOption.dataset.value);
        categoryInput.value = newOption.dataset.value;
        modal.style.display = "none";
      }
    };
    saveBtn.addEventListener("click", addNewCategory);
    newCategoryInput.addEventListener("keypress", (e) => { if (e.key === "Enter") { e.preventDefault(); addNewCategory(); } });
  }

  // --- UPLOAD BOX ---
  const uploadBox = document.getElementById("uploadBox");
  const uploadInput = document.getElementById("upload");
  const uploadLabel = document.getElementById("uploadLabel");
  const previewContainer = document.getElementById("imagePreview");
  const previewImg = document.getElementById("previewImg");
  const removeBtn = document.getElementById("removeBtn");

  uploadInput.addEventListener("change", function() { showPreview(this.files[0]); });
  uploadBox.addEventListener("dragover", (e) => { e.preventDefault(); uploadBox.style.borderColor = "#01214A"; uploadBox.style.background = "#eef5ff"; });
  uploadBox.addEventListener("dragleave", () => { uploadBox.style.borderColor = "#3C87C4"; uploadBox.style.background = "#f9fbfd"; });
  uploadBox.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = "#3C87C4";
    uploadBox.style.background = "#f9fbfd";
    const file = e.dataTransfer.files[0];
    if (file) { uploadInput.files = e.dataTransfer.files; showPreview(file); }
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
    const hour = hourEl.textContent;
    const minute = minuteEl.textContent;
    const ampm = amBtn.classList.contains("active") ? "AM" : "PM";
    timeInput.value = `${hour}:${minute} ${ampm}`;
    timeModal.style.display = "none";
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

  // === EVENT DATE NOTE UPDATE ===
  const startDateInput = document.getElementById("date");
  const endDateInput = document.getElementById("endDate");
  const eventDateNote = document.getElementById("eventDateNote");

  function updateEventDateNote() {
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;
    const time = timeInput.value;
    
    if (!startDate) {
      eventDateNote.textContent = "This event will take place on —, beginning at —.";
      return;
    }
    
    const startDateObj = new Date(startDate);
    const formattedStartDate = startDateObj.toLocaleDateString("en-US", { 
      month: "long", 
      day: "numeric", 
      year: "numeric" 
    });
    
    let noteText = `This event will take place on ${formattedStartDate}`;
    
    if (endDate && endDate !== startDate) {
      const endDateObj = new Date(endDate);
      const formattedEndDate = endDateObj.toLocaleDateString("en-US", { 
        month: "long", 
        day: "numeric", 
        year: "numeric" 
      });
      noteText += ` to ${formattedEndDate}`;
    }
    
    if (time) {
      noteText += `, beginning at ${time}`;
    } else {
      noteText += `, beginning at —`;
    }
    
    noteText += ".";
    eventDateNote.textContent = noteText;
  }

  // Listen for changes to start date, end date, and time
  startDateInput.addEventListener("change", updateEventDateNote);
  endDateInput.addEventListener("change", updateEventDateNote);
  timeInput.addEventListener("input", updateEventDateNote);

  // === YOUTH REGISTRATION OPTION TOGGLE ===
  const registrationOptions = document.querySelectorAll('input[name="registration_type"]');
  const createRegSection = document.getElementById("createRegistrationFields");
  const linkSourceField = document.getElementById("linkSourceField");
  const titleInput = document.getElementById("title");
  const registrationTitleInput = document.getElementById("registrationTitle");
  const registrationDescriptionInput = document.getElementById("registrationDescription");

  // Function to update registration title
  function updateRegistrationTitle() {
    const title = titleInput.value.trim();
    if (title) {
      registrationTitleInput.value = `${title} Registration Form`;
    } else {
      registrationTitleInput.value = "Registration Form";
    }
  }

  // Function to update registration description with user's barangay
  function updateRegistrationDescription() {
    const barangayName = `{!! $barangay->name ?? 'Barangay' !!}`;
    const defaultDescription = `Open to all bona fide residents of ${barangayName}. Slots are limited, and once all slots are filled, we will make an official announcement. Slot allocation will be based on the timestamp of registration.`;
    
    registrationDescriptionInput.value = defaultDescription;
  }

  // Listen for changes to the main title
  titleInput.addEventListener("input", updateRegistrationTitle);

  // Handle registration option changes
  registrationOptions.forEach(option => {
    option.addEventListener("change", () => {
      if (option.value === "create") {
        createRegSection.style.display = "block";
        linkSourceField.style.display = "none";
        
        // Update registration title and description when switching to create registration
        updateRegistrationTitle();
        updateRegistrationDescription();
        
        // Initialize default fields
        initializeDefaultFields();
      } else if (option.value === "link") {
        createRegSection.style.display = "none";
        linkSourceField.style.display = "block";
      }
    });
  });

  // === DEFAULT FIELDS INITIALIZATION ===
  function initializeDefaultFields() {
    const defaultFieldsContainer = document.getElementById('defaultFields');
    const defaultFields = [
      { type: 'full_name', label: 'Full Name' },
      { type: 'email', label: 'Email Address' },
      { type: 'contact_no', label: 'Contact Number' },
      { type: 'age', label: 'Age' },
      { type: 'barangay', label: 'Barangay' }
    ];

    defaultFieldsContainer.innerHTML = '';
    
    defaultFields.forEach(field => {
      const fieldHtml = `
        <div class="default-field" data-field-type="${field.type}">
          <label class="regform-label">${field.label}</label>
          <input type="text" class="regform-input" readonly style="background-color: #f5f5f5;">
          <input type="hidden" name="default_fields[]" value="${field.type}">
        </div>
      `;
      defaultFieldsContainer.innerHTML += fieldHtml;
    });
  }

  // === DATE + NOTE AUTO UPDATE ===
  const openDate = document.getElementById("openDate");
  const openTime = document.getElementById("openTime");
  const closeDate = document.getElementById("closeDate");
  const closeTime = document.getElementById("closeTime");
  const note = document.getElementById("regNote");

  function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    return d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
  }

  function formatTime(timeStr) {
    if (!timeStr) return "";
    const [h, m] = timeStr.split(":");
    const d = new Date();
    d.setHours(h, m);
    return d.toLocaleTimeString("en-US", { hour: "numeric", minute: "2-digit" });
  }

  function updateNote() {
    const open = `${formatDate(openDate.value)} ${formatTime(openTime.value)}`.trim();
    const close = `${formatDate(closeDate.value)} ${formatTime(closeTime.value)}`.trim();

    note.innerHTML = `
      Registration opens: ${open || "—"}<br>
      Registration closes: ${close || "—"}
    `;
  }

  [openDate, openTime, closeDate, closeTime].forEach(input => input.addEventListener("change", updateNote));

  // === DOTS MENU INIT ===
  function initDotsMenu(field) {
    const dots = field.querySelector(".regform-dots");
    const menu = field.querySelector(".dots-menu");

    if (!dots || !menu) return;

    // Toggle menu
    dots.addEventListener("click", (e) => {
      e.stopPropagation();
      document.querySelectorAll(".dots-menu").forEach(m => { if (m !== menu) m.classList.add("hidden"); });
      menu.classList.toggle("hidden");
    });

    // Edit option
    const editBtn = menu.querySelector(".edit-option");
    if (editBtn) {
      editBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const input = field.querySelector(".field-label");
        if (input) input.focus();
        menu.classList.add("hidden");
      });
    }

    // Delete option
    const deleteBtn = menu.querySelector(".delete-option");
    if (deleteBtn) {
      deleteBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        field.remove();
      });
    }
  }

  // === ADD FIELD (clone template) ===
  const addFieldBtn = document.getElementById("addFieldBtn");
  const extraFieldsContainer = document.getElementById("extra-fields");
  const templateField = extraFieldsContainer.firstElementChild?.cloneNode(true);

  let fieldCounter = 1;

  addFieldBtn.addEventListener("click", () => {
    if (!templateField) return;
    fieldCounter++;
    const clone = templateField.cloneNode(true);
    const fieldId = `field_${fieldCounter}`;
    
    clone.setAttribute('data-field-id', fieldId);
    
    // Reset and update field
    const questionInput = clone.querySelector(".field-label");
    questionInput.value = "";
    
    const answerType = clone.querySelector(".answer-type");
    answerType.textContent = "Choose type of answer";
    
    // Reset all previews
    clone.querySelectorAll(".answer-preview, .answer-radio, .answer-select, .answer-file").forEach(el => {
      el.classList.add("hidden");
    });
    
    // Update radio button names
    const radioInputs = clone.querySelectorAll('input[type="radio"]');
    radioInputs.forEach(input => {
      input.name = `sampleRadio_${fieldCounter}`;
    });
    
    // Reset dropdown menu
    clone.querySelectorAll(".dots-menu").forEach(menu => menu.classList.add("hidden"));
    
    // Reset options
    const optionsBoxes = clone.querySelectorAll(".options-box");
    optionsBoxes.forEach(box => {
      const firstTwo = box.querySelectorAll(".option-item:not(:nth-child(-n+2))");
      firstTwo.forEach(item => item.remove());
      
      const editableSpans = box.querySelectorAll(".editable");
      editableSpans[0].textContent = "Option 1";
      editableSpans[1].textContent = "Option 2";
    });

    extraFieldsContainer.appendChild(clone);
    initDotsMenu(clone);
    initAnswerTypeDropdown(clone);
    initAddOptionButtons(clone);
  });

  // Initialize existing fields
  document.querySelectorAll("#extra-fields .regform-field").forEach(field => {
    initDotsMenu(field);
    initAnswerTypeDropdown(field);
    initAddOptionButtons(field);
  });

  document.addEventListener("click", () => {
    document.querySelectorAll(".dots-menu").forEach(menu => menu.classList.add("hidden"));
    document.querySelectorAll(".answer-dropdown").forEach(dropdown => dropdown.classList.remove("open"));
  });

  // === ANSWER TYPE DROPDOWN FUNCTIONALITY ===
  function initAnswerTypeDropdown(field) {
    const answerType = field.querySelector(".answer-type");
    const dropdown = field.querySelector(".answer-dropdown");
    const options = dropdown.querySelectorAll("p");

    answerType.addEventListener("click", (e) => {
      e.stopPropagation();
      document.querySelectorAll(".answer-dropdown.open").forEach(d => {
        if (d !== dropdown) d.classList.remove("open");
      });
      dropdown.classList.toggle("open");
    });

    options.forEach(option => {
      option.addEventListener("click", (e) => {
        e.stopPropagation();
        const type = option.getAttribute("data-type");
        const typeText = option.textContent;
        
        answerType.textContent = typeText;
        dropdown.classList.remove("open");
        
        // Hide all previews
        field.querySelectorAll(".answer-preview, .answer-radio, .answer-select, .answer-file").forEach(el => {
          el.classList.add("hidden");
        });
        
        // Show selected preview
        const selectedPreview = field.querySelector(`[data-type="${type}"]`);
        if (selectedPreview) {
          selectedPreview.classList.remove("hidden");
        }
      });
    });
  }

  // === ADD OPTION BUTTONS ===
  function initAddOptionButtons(field) {
    const addOptionButtons = field.querySelectorAll(".add-option");
    
    addOptionButtons.forEach(button => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        const optionsBox = e.target.previousElementSibling;
        const optionCount = optionsBox.querySelectorAll(".option-item").length + 1;
        
        const newOption = document.createElement("div");
        newOption.className = "option-item";
        
        if (optionsBox.classList.contains("answer-radio")) {
          newOption.innerHTML = `<input type="radio" name="sampleRadio_${fieldCounter}" disabled><span contenteditable="true" class="editable">Option ${optionCount}</span>`;
        } else {
          newOption.innerHTML = `<span contenteditable="true" class="editable">Option ${optionCount}</span>`;
        }
        
        optionsBox.appendChild(newOption);
      });
    });
  }

  // === COMPLETE FORM SUBMISSION - FIXED END DATE ISSUE ===
  const programForm = document.getElementById("programForm");
  const postedModal = document.getElementById("postedModal");
  const closePostedModal = document.getElementById("closePostedModal");

  programForm.addEventListener("submit", function(e) {
    e.preventDefault();
    
    // DEBUG: Log all form values before submission
    console.log('=== FORM DATA BEFORE SUBMISSION ===');
    const formDataDebug = new FormData(this);
    for (let [key, value] of formDataDebug.entries()) {
      console.log(`${key}: ${value}`);
    }
    
    // Collect custom fields data
    const customFields = [];
    const customFieldElements = document.querySelectorAll('#extra-fields .regform-field');
    
    customFieldElements.forEach((field, index) => {
      const labelInput = field.querySelector('.field-label');
      const answerType = field.querySelector('.answer-type');
      const options = [];
      
      // Get options for radio/select fields
      const optionsBox = field.querySelector('.options-box');
      if (optionsBox) {
        const optionItems = optionsBox.querySelectorAll('.editable');
        optionItems.forEach(item => {
          if (item.textContent.trim()) {
            options.push(item.textContent.trim());
          }
        });
      }
      
      if (labelInput && labelInput.value.trim() && answerType.textContent !== 'Choose type of answer') {
        const fieldType = getFieldTypeFromText(answerType.textContent);
        
        // Create field data in the format expected by the backend
        const fieldData = {
          type: 'custom',
          field_type: fieldType,
          label: labelInput.value.trim(),
          options: options.length > 0 ? options : null,
          required: true,
          editable: true
        };
        
        customFields.push(fieldData);
      }
    });
    
    // Create form data - FIXED: Use the actual form element directly
    const formData = new FormData(this);
    
    // Add custom fields as JSON string
    if (customFields.length > 0) {
      formData.set('custom_fields', JSON.stringify(customFields));
    } else {
      formData.set('custom_fields', '[]'); // Empty array as JSON string
    }

    // DEBUG: Log all form values after modification
    console.log('=== FORM DATA AFTER MODIFICATION ===');
    for (let [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`);
    }

    // Show loading state
    const submitBtn = document.querySelector('.postProgramBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
    submitBtn.disabled = true;

    // Submit form via AJAX
    fetch(this.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(response => {
      // Check if response is JSON
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        // If not JSON, try to parse as text first to get error details
        return response.text().then(text => {
          console.error('Server returned non-JSON response:', text);
          throw new Error(`Server returned HTML instead of JSON. Status: ${response.status}. The server might have validation errors or be experiencing issues.`);
        });
      }
      return response.json();
    })
    .then(data => {
      console.log('Server response:', data);
      if (data.success) {
        postedModal.style.display = "flex";
        // Reset form
        programForm.reset();
        // Reset custom select
        document.querySelector('.selected-text').textContent = 'Select Category';
        document.getElementById('categoryInput').value = '';
        // Reset registration sections
        createRegSection.style.display = "none";
        linkSourceField.style.display = "none";
        // Reset radio buttons
        document.querySelectorAll('input[name="registration_type"]').forEach(radio => {
          radio.checked = false;
        });
        // Reset image preview
        previewImg.src = "";
        previewContainer.style.display = "none";
        uploadLabel.style.display = "flex";
        // Reset custom fields
        document.querySelectorAll('#extra-fields .regform-field').forEach(field => {
          if (field !== templateField) field.remove();
        });
      } else {
        throw new Error(data.message || 'Unknown error occurred');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error creating program: ' + error.message);
    })
    .finally(() => {
      // Reset button state
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    });
  });

  // Helper function to convert answer type text to field type
  function getFieldTypeFromText(text) {
    const typeMap = {
      'Short answer': 'text',
      'Radio button': 'radio',
      'Dropdown': 'select',
      'File upload': 'file'
    };
    return typeMap[text] || 'text';
  }

  // Close modal
  closePostedModal.addEventListener("click", () => {
    postedModal.style.display = "none";
    // Redirect to programs list after successful creation
    window.location.href = "{{ route('youth-program-registration') }}";
  });

  // Close when clicking outside
  window.addEventListener("click", (e) => {
    if (e.target === postedModal) {
      postedModal.style.display = "none";
      window.location.href = "{{ route('youth-program-registration') }}";
    }
  });

  // Initialize the registration description on page load
  updateRegistrationDescription();
});
</script>

</body>
</html>