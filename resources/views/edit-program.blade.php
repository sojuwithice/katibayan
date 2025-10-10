<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/edit-program.css') }}">
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
  <h2>Edit Program</h2>
  <p class="subtitle">Set up events or programs designed for youth involvement.</p>

  <form>
    <!-- Title -->
    <div class="form-group">
  <label for="title" style="display:block; font-weight:700; margin-bottom:0.5rem;">Title</label>
  <input type="text" id="title">
</div>

    <!-- Row: Date, Time, Category -->
    <div class="form-row">
      <!-- Date -->
      <div class="form-group date">
        <label for="date">Event Date</label>
        <input type="date" id="date">
      </div>

      <!-- Time -->
      <div class="form-group time">
        <label for="time">Time</label>
        <div class="input-with-icon">
          <input type="text" id="time" readonly>
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
  <p class="note">This event will take place on September 13, 2025, beginning at 1:00 PM.</p>
</div>


    <!-- Location -->
    <div class="form-group">
  <label for="location" style="display:block; font-weight:700; margin-bottom:0.5rem;">Location</label>
  <input type="text" id="location">
</div>

    <!-- Description -->
    <div class="form-group">
  <label for="description" style="display:block; font-weight:700; margin-bottom:0.5rem;">Add Description</label>
  <textarea id="description" rows="4"></textarea>
</div>

    

    <!-- Upload -->
    <label>Upload Display</label>
    <div class="upload-box" id="uploadBox">
      <input type="file" id="upload" hidden accept="image/*">

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
  <input type="text" id="publisher">
</div>

<!-- Youth Registration Option -->
    <div class="form-group">
      <label style="display:block; font-weight:100; margin-bottom:0.7rem;">
        Choose an option (for youth registration)
      </label>
      <div class="registration-options">
        <label>
          <input type="radio" name="registrationOption" value="create">
          Create Registration
        </label>
        <label style="margin-left: 1rem;">
          <input type="radio" name="registrationOption" value="link">
          Add link source
        </label>
      </div>
    </div>

    <!-- Link Source Field -->
<div id="linkSourceField" class="form-group" style="display:none;">
  <div class="link-source-wrapper">
    <i class="fas fa-link link-icon"></i>
    <input type="url" id="linkSource" placeholder="Link Source">
  </div>

  <!-- Post Program Button for Link Source -->
  <div class="form-actions">
  <button type="submit" class="btn-submit postProgramBtn" id="programBtn">Update Program</button>
</div>

</div>


    <!-- Create Registration Fields -->
<div id="createRegistrationFields" class="regform-container" style="display:none;">
  <h3 class="regform-title">Registration Form</h3>

  <!-- Title -->
<div class="regform-block-wide">
  <label class="regform-label">Title</label>
  <input type="text" class="regform-input" value="2025 BADMINTON REGISTRATION FORM">
</div>

<!-- Description -->
<div class="regform-block-wide">
  <label class="regform-label">Add description</label>
  <textarea class="regform-textarea" rows="3">Open to all bona fide residents of Barangay 3, EM's Bo. East, Legazpi City, Albay. Slots are limited, and once all slots are filled, we will make an official announcement. Slot allocation will be based on the timestamp of registration.</textarea>
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
          <input type="date" id="openDate" />
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="regform-datetime-icon">
          <input type="time" id="openTime" />
          <i class="fas fa-clock"></i>
        </div>
      </div>
    </div>

    <!-- Closes -->
    <div class="regform-block">
      <label class="regform-label">Registration Closes</label>
      <div class="regform-datetime">
        <div class="regform-datetime-icon">
          <input type="date" id="closeDate" />
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="regform-datetime-icon">
      <input type="time" id="closeTime" name="closeTime" />
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

  <div>
    <label class="regform-label">Name</label>
    <input type="text" class="regform-input">
  </div>

  <div>
    <label class="regform-label">Age</label>
    <input type="number" class="regform-input">
  </div>
  
  <div class="regform-gender">
  <label class="regform-label">Gender</label>
  <div class="regform-gender-options">
    <label><input type="radio" name="gender"><span>Female</span></label>
    <label><input type="radio" name="gender"><span>Male</span></label>
    <label><input type="radio" name="gender"><span>Other</span></label>
  </div>
</div>


  <div>
    <label class="regform-label">Contact Number</label>
    <input type="text" class="regform-input">
  </div>

  <div>
    <label class="regform-label">Purok</label>
    <input type="text" class="regform-input">
  </div>

  <!-- Extra question fields (container for all fields) -->
<div id="extra-fields">
  <!-- One field -->
  <div class="regform-field">
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
          <input type="text" placeholder="Add question" />

          <!-- Answer type dropdown (upper right) -->
          <div class="answer-type-wrapper">
            <span class="answer-type" onclick="toggleDropdown(this)">
              Choose type of answer
            </span>
            <div class="answer-dropdown">
              <p onclick="selectAnswerType(this, 'short')">Short answer</p>
              <p onclick="selectAnswerType(this, 'radio')">Radio button</p>
              <p onclick="selectAnswerType(this, 'file')">File upload</p>
            </div>
          </div>
        </div>

        <!-- Answer preview (short) -->
<div class="answer-preview hidden" data-type="short">
  <input type="text" placeholder="Short answer" disabled />
</div>

<!-- Radio button preview -->
<div class="answer-radio hidden" data-type="radio">
  <div class="options-box">
    <div class="option-item">
      <input type="radio" name="sampleRadio">
      <span contenteditable="true" class="editable">Option 1</span>
    </div>
    <div class="option-item">
      <input type="radio" name="sampleRadio">
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
  <button type="submit" class="btn-submit postProgramBtn" id="programBtn">Update Program</button>
</div>


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

<!-- Done Modal -->
<div id="doneModal" class="done-modal hidden">
  <div class="done-modal-content">
    <div class="check-icon">
      <i class="fa-solid fa-check"></i>
    </div>
    <h2>Done</h2>
    <p>The program has already been edited. Please check the details again.</p>
    <button id="okBtn">OK</button>
  </div>
</div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal hidden">
  <div class="confirm-modal-content">
    <p>
      Are you sure you want to edit this field? Editing will reschedule the event. Do you want to continue?
    </p>
    <div class="btn-group">
      <button id="cancelBtn" class="cancel-btn">Cancel</button>
      <button id="yesBtn" class="yes-btn">Yes</button>
    </div>
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

  // --- CALENDAR ---
  const weekdays = ["MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN"];
  const daysContainer = document.querySelector(".calendar .days");
  const header = document.querySelector(".calendar header h3");
  let today = new Date();
  let currentView = new Date();
  const holidays = [
    "2025-01-01","2025-04-09","2025-04-17","2025-04-18",
    "2025-05-01","2025-06-06","2025-06-12","2025-08-25",
    "2025-11-30","2025-12-25","2025-12-30"
  ];

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

      const month = (thisDay.getMonth() + 1).toString().padStart(2, "0");
      const day = thisDay.getDate().toString().padStart(2, "0");
      const dateStr = `${thisDay.getFullYear()}-${month}-${day}`;

      if (holidays.includes(dateStr)) dateEl.classList.add("holiday");
      if (
        thisDay.getDate() === today.getDate() &&
        thisDay.getMonth() === today.getMonth() &&
        thisDay.getFullYear() === today.getFullYear()
      ) {
        dayEl.classList.add("active");
      }

      dayEl.appendChild(weekdayEl);
      dayEl.appendChild(dateEl);
      daysContainer.appendChild(dayEl);
    }
  }
  renderCalendar(currentView);
  document.querySelector(".calendar .prev")?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() - 7);
    renderCalendar(currentView);
  });
  document.querySelector(".calendar .next")?.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() + 7);
    renderCalendar(currentView);
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
  const profileDropdown = document.querySelector(".profile-dropdown");

  if (notifWrapper) {
    notifWrapper.querySelector(".fa-bell")?.addEventListener("click", (e) => {
      e.stopPropagation();
      notifWrapper.classList.toggle("active");
      profileWrapper?.classList.remove("active");
    });
    notifWrapper.querySelector(".notif-dropdown")?.addEventListener("click", (e) => e.stopPropagation());
  }
  if (profileWrapper && profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
      notifWrapper?.classList.remove("active");
    });
    profileDropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  // === CUSTOM SELECT WITH MODAL ===
  const customSelect = document.querySelector(".custom-select");
  if (customSelect) {
    const selected = customSelect.querySelector(".selected");
    const optionsContainer = customSelect.querySelector(".options");
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
  const newVal = `${hour}:${minute} ${ampm}`;

  timeModal.style.display = "none";

  // Reset to old until confirmed
  timeInput.value = timeInput.getAttribute("data-old") || "";

  // Trigger confirm modal
  showConfirmModal(timeInput, newVal);
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

    // === YOUTH REGISTRATION OPTION TOGGLE ===
const registrationOptions = document.querySelectorAll('input[name="registrationOption"]');
const createRegSection = document.getElementById("createRegistrationFields");
const linkSourceField = document.getElementById("linkSourceField");

registrationOptions.forEach(option => {
  option.addEventListener("change", () => {
    if (option.value === "create") {
      createRegSection.style.display = "block";
      linkSourceField.style.display = "none";
    } else if (option.value === "link") {
      createRegSection.style.display = "none";
      linkSourceField.style.display = "block";
    }
  });
});


  // === DATE + NOTE AUTO UPDATE ===
  document.querySelectorAll('.datetime-input i').forEach(icon => {
    icon.addEventListener('click', () => { icon.previousElementSibling.showPicker?.(); });
  });

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
        const input = field.querySelector("input[type='text'], textarea");
        if (input) input.removeAttribute("disabled");
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

  addFieldBtn.addEventListener("click", () => {
    if (!templateField) return;
    const clone = templateField.cloneNode(true);

    // Reset
    clone.querySelectorAll("input[type='text'], textarea").forEach(input => input.value = "");
    clone.querySelectorAll(".answer-preview, .answer-radio, .answer-file").forEach(el => el.classList.add("hidden"));
    clone.querySelector(".answer-type").textContent = "Choose type of answer";
    clone.querySelectorAll(".answer-dropdown p").forEach(p => p.classList.remove("selected"));
    clone.querySelectorAll(".dots-menu").forEach(menu => menu.classList.add("hidden"));

    extraFieldsContainer.appendChild(clone);
    initDotsMenu(clone);
  });

  document.querySelectorAll("#extra-fields .regform-field").forEach(initDotsMenu);

  document.addEventListener("click", () => {
    document.querySelectorAll(".dots-menu").forEach(menu => menu.classList.add("hidden"));
  });

  // === DROPDOWN FUNCTIONALITY ===
  document.addEventListener("click", (e) => {
    const typeBtn = e.target.closest(".answer-type");
    const optionP = e.target.closest(".answer-dropdown p");

    if (typeBtn) {
      const wrapper = typeBtn.closest(".answer-type-wrapper");
      const dropdown = wrapper.querySelector(".answer-dropdown");

      document.querySelectorAll(".answer-dropdown.open").forEach(d => { if (d !== dropdown) d.classList.remove("open"); });
      dropdown.classList.toggle("open");
      return;
    }

    if (optionP) {
      const extra = optionP.closest(".regform-extra");
      const preview = extra.querySelector(".answer-preview");
      const typeText = extra.querySelector(".answer-type");
      const dropdown = optionP.closest(".answer-dropdown");

      typeText.style.display = "none";
      dropdown.classList.remove("open");
      extra.querySelectorAll(".answer-preview, .answer-radio, .answer-file").forEach(el => el.classList.add("hidden"));

      if (optionP.textContent === "Short answer") {
        preview.classList.remove("hidden");
        preview.innerHTML = `<textarea placeholder="Short answer" rows="1" style="width:100%; resize: both; min-height: 30px; padding:6px; font-size:0.9rem;"></textarea>`;
      }

      if (optionP.textContent === "Radio button") {
        extra.querySelector(".answer-radio").classList.remove("hidden");
      }

      if (optionP.textContent === "File upload") {
        const fileBox = extra.querySelector(".answer-file");
        fileBox.classList.remove("hidden");
        fileBox.innerHTML = `<input type="file" style="font-size:0.9rem;" />`;
      }
      return;
    }

    if (!e.target.closest(".answer-type-wrapper")) {
      document.querySelectorAll(".answer-dropdown.open").forEach(d => d.classList.remove("open"));
    }
  });

  // === RADIO DYNAMIC OPTIONS ===
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("add-option")) {
      e.preventDefault();
      const optionsBox = e.target.previousElementSibling;
      const newOption = document.createElement("div");
      newOption.className = "option-item";
      newOption.innerHTML = `<input type="radio" name="sampleRadio"><span contenteditable="true" class="editable">New option</span>`;
      optionsBox.appendChild(newOption);
    }
  });

/* =====================
     DONE MODAL
  ====================== */
  const doneModal = document.getElementById("doneModal");
  const okBtn = document.getElementById("okBtn");
  const updateBtns = document.querySelectorAll(".postProgramBtn");

  updateBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault(); 
      doneModal.classList.remove("hidden");
      doneModal.style.display = "flex";
    });
  });
  okBtn?.addEventListener("click", () => { doneModal.style.display = "none"; });

 
/* =====================
   CONFIRMATION MODAL (Date & Time change)
====================== */
const confirmModal = document.getElementById("confirmModal");
const cancelBtn = document.getElementById("cancelBtn");
const yesBtn = document.getElementById("yesBtn");

const dateInput = document.getElementById("date");
const timeField = document.getElementById("time");

let pendingChange = null; // store new value temporarily

function showConfirmModal(inputEl, newValue) {
  confirmModal.style.display = "flex";
  confirmModal.setAttribute("data-target", inputEl.id);
  pendingChange = { el: inputEl, value: newValue };
}

// init old values
if (dateInput) dateInput.setAttribute("data-old", dateInput.value);
if (timeField) timeField.setAttribute("data-old", timeField.value);


dateInput?.addEventListener("change", (e) => {
  e.preventDefault(); 
  const newVal = e.target.value;
  e.target.value = e.target.getAttribute("data-old") || "";
  showConfirmModal(dateInput, newVal);
});


timeField?.addEventListener("input", (e) => {
  e.preventDefault();
  const newVal = e.target.value;
  e.target.value = e.target.getAttribute("data-old") || "";
  showConfirmModal(timeField, newVal);
});

// Cancel → discard changes
cancelBtn?.addEventListener("click", (e) => {
  e.preventDefault();
  confirmModal.style.display = "none";
  pendingChange = null;
});

// Yes → accept changes
yesBtn?.addEventListener("click", (e) => {
  e.preventDefault();
  if (pendingChange) {
    pendingChange.el.value = pendingChange.value;
    pendingChange.el.setAttribute("data-old", pendingChange.value);
    pendingChange = null;
  }
  confirmModal.style.display = "none";
});

// Click outside modal
window.addEventListener("click", (e) => {
  if (e.target === confirmModal) {
    confirmModal.style.display = "none";
    pendingChange = null;
  }
});

// === Success popup ===
const successPopup = document.getElementById("successPopup");

function showSuccessPopup(message = "Event updated successfully!") {
  successPopup.querySelector("p").textContent = message;
  successPopup.classList.add("show");

  // Hide after 3 seconds
  setTimeout(() => {
    successPopup.classList.remove("show");
  }, 3000);
}


});

</script>




</body>
</html>