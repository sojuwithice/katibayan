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

      <!-- Field Builder Section -->
      <div class="field-builder">
        <div class="field-builder-header">
          <h3 class="field-builder-title">Custom Registration Fields</h3>
          <button type="button" class="add-field-btn" id="addCustomFieldBtn">
            <i class="fas fa-plus"></i> Add Field
          </button>
        </div>
        
        <div class="fields-container" id="fieldsContainer">
          <!-- Fields will be added here dynamically -->
          <div class="fields-empty" id="fieldsEmpty">
            <i class="fas fa-file-alt"></i>
            <h3>No fields added yet</h3>
            <p>Click "Add Field" to start creating your registration form</p>
          </div>
        </div>
      </div>

      <!-- Hidden input for custom fields data -->
      <input type="hidden" name="custom_fields" id="customFieldsData" value="">

      <!-- Submit Button -->
      <div class="form-actions">
        <button type="submit" class="btn-submit postProgramBtn">Post Program</button>
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
        <button type="button" class.arrow down" data-type="hour">▼</button>
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
// Field Builder Templates
const fieldTemplates = {
  shortAnswer: `
    <div class="field-item" data-field-type="short_answer">
      <div class="drag-handle">
        <i class="fas fa-grip-vertical"></i>
      </div>
      <div class="field-header">
        <input type="text" class="field-title-input" placeholder="Question" required>
        <div class="field-type-selector">
          <div class="field-type-toggle">
            <span>Short answer</span>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="field-type-dropdown">
            <div class="field-type-option" data-type="short_answer">
              <i class="fas fa-font"></i>
              <span>Short answer</span>
            </div>
            <div class="field-type-option" data-type="multiple_choice">
              <i class="fas fa-dot-circle"></i>
              <span>Multiple choice</span>
            </div>
            <div class="field-type-option" data-type="file_upload">
              <i class="fas fa-file-upload"></i>
              <span>File upload</span>
            </div>
            <div class="field-type-option" data-type="dropdown">
              <i class="fas fa-caret-down"></i>
              <span>Dropdown</span>
            </div>
          </div>
        </div>
      </div>
      <div class="field-content">
        <input type="text" class="short-answer-input" placeholder="Short answer text" disabled>
      </div>
      <div class="field-actions">
        <div class="required-toggle">
          <input type="checkbox" class="required-checkbox" checked>
          <span>Required</span>
        </div>
        <button type="button" class="remove-field-btn">
          <i class="fas fa-trash"></i> Remove
        </button>
      </div>
    </div>
  `,

  multipleChoice: `
    <div class="field-item" data-field-type="multiple_choice">
      <div class="drag-handle">
        <i class="fas fa-grip-vertical"></i>
      </div>
      <div class="field-header">
        <input type="text" class="field-title-input" placeholder="Question" required>
        <div class="field-type-selector">
          <div class="field-type-toggle">
            <span>Multiple choice</span>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="field-type-dropdown">
            <div class="field-type-option" data-type="short_answer">
              <i class="fas fa-font"></i>
              <span>Short answer</span>
            </div>
            <div class="field-type-option" data-type="multiple_choice">
              <i class="fas fa-dot-circle"></i>
              <span>Multiple choice</span>
            </div>
            <div class="field-type-option" data-type="file_upload">
              <i class="fas fa-file-upload"></i>
              <span>File upload</span>
            </div>
            <div class="field-type-option" data-type="dropdown">
              <i class="fas fa-caret-down"></i>
              <span>Dropdown</span>
            </div>
          </div>
        </div>
      </div>
      <div class="field-content">
        <div class="multiple-choice-options">
          <div class="mc-option">
            <input type="radio" name="option_group" disabled>
            <input type="text" class="option-input" placeholder="Option 1" value="Option 1">
            <button type="button" class="remove-option-btn" style="display:none;">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="mc-option">
            <input type="radio" name="option_group" disabled>
            <input type="text" class="option-input" placeholder="Option 2" value="Option 2">
            <button type="button" class="remove-option-btn" style="display:none;">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <button type="button" class="add-option-btn">
          <i class="fas fa-plus"></i> Add Option
        </button>
      </div>
      <div class="field-actions">
        <div class="required-toggle">
          <input type="checkbox" class="required-checkbox" checked>
          <span>Required</span>
        </div>
        <button type="button" class="remove-field-btn">
          <i class="fas fa-trash"></i> Remove
        </button>
      </div>
    </div>
  `,

  fileUpload: `
    <div class="field-item" data-field-type="file_upload">
      <div class="drag-handle">
        <i class="fas fa-grip-vertical"></i>
      </div>
      <div class="field-header">
        <input type="text" class="field-title-input" placeholder="Question" required>
        <div class="field-type-selector">
          <div class="field-type-toggle">
            <span>File upload</span>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="field-type-dropdown">
            <div class="field-type-option" data-type="short_answer">
              <i class="fas fa-font"></i>
              <span>Short answer</span>
            </div>
            <div class="field-type-option" data-type="multiple_choice">
              <i class="fas fa-dot-circle"></i>
              <span>Multiple choice</span>
            </div>
            <div class="field-type-option" data-type="file_upload">
              <i class="fas fa-file-upload"></i>
              <span>File upload</span>
            </div>
            <div class="field-type-option" data-type="dropdown">
              <i class="fas fa-caret-down"></i>
              <span>Dropdown</span>
            </div>
          </div>
        </div>
      </div>
      <div class="field-content">
        <input type="file" class="file-input" style="display:none;" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        <button type="button" class="file-upload-btn">
          <i class="fas fa-upload"></i> Upload File
        </button>
        <div class="file-upload-preview">
          <div class="file-preview-content">
            <i class="fas fa-file file-icon"></i>
            <div class="file-info">
              <div class="file-name">filename.jpg</div>
              <div class="file-size">1.2 MB</div>
            </div>
            <button type="button" class="remove-file-btn">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="field-actions">
        <div class="required-toggle">
          <input type="checkbox" class="required-checkbox" checked>
          <span>Required</span>
        </div>
        <button type="button" class="remove-field-btn">
          <i class="fas fa-trash"></i> Remove
        </button>
      </div>
    </div>
  `,

  dropdown: `
    <div class="field-item" data-field-type="dropdown">
      <div class="drag-handle">
        <i class="fas fa-grip-vertical"></i>
      </div>
      <div class="field-header">
        <input type="text" class="field-title-input" placeholder="Question" required>
        <div class="field-type-selector">
          <div class="field-type-toggle">
            <span>Dropdown</span>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="field-type-dropdown">
            <div class="field-type-option" data-type="short_answer">
              <i class="fas fa-font"></i>
              <span>Short answer</span>
            </div>
            <div class="field-type-option" data-type="multiple_choice">
              <i class="fas fa-dot-circle"></i>
              <span>Multiple choice</span>
            </div>
            <div class="field-type-option" data-type="file_upload">
              <i class="fas fa-file-upload"></i>
              <span>File upload</span>
            </div>
            <div class="field-type-option" data-type="dropdown">
              <i class="fas fa-caret-down"></i>
              <span>Dropdown</span>
            </div>
          </div>
        </div>
      </div>
      <div class="field-content">
        <select class="dropdown-select" disabled>
          <option>Option 1</option>
          <option>Option 2</option>
        </select>
        <div class="multiple-choice-options" style="margin-top: 1rem;">
          <div class="mc-option">
            <input type="text" class="option-input" placeholder="Option 1" value="Option 1">
            <button type="button" class="remove-option-btn" style="display:none;">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="mc-option">
            <input type="text" class="option-input" placeholder="Option 2" value="Option 2">
            <button type="button" class="remove-option-btn" style="display:none;">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <button type="button" class="add-option-btn">
          <i class="fas fa-plus"></i> Add Option
        </button>
      </div>
      <div class="field-actions">
        <div class="required-toggle">
          <input type="checkbox" class="required-checkbox" checked>
          <span>Required</span>
        </div>
        <button type="button" class="remove-field-btn">
          <i class="fas fa-trash"></i> Remove
        </button>
      </div>
    </div>
  `
};

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
        
        // Initialize field builder
        initializeFieldBuilder();
      } else if (option.value === "link") {
        createRegSection.style.display = "none";
        linkSourceField.style.display = "block";
      }
    });
  });

  // === FIELD BUILDER FUNCTIONALITY ===
  let fieldCounter = 0;
  const fieldsContainer = document.getElementById('fieldsContainer');
  const fieldsEmpty = document.getElementById('fieldsEmpty');
  const addCustomFieldBtn = document.getElementById('addCustomFieldBtn');
  const customFieldsData = document.getElementById('customFieldsData');

  function initializeFieldBuilder() {
    // Clear existing fields
    fieldsContainer.innerHTML = '';
    fieldsEmpty.style.display = 'block';
    fieldCounter = 0;
    
    // Add event listener for add field button
    addCustomFieldBtn.addEventListener('click', addField);
    
    // Add first field by default
    addField('short_answer');
  }

  function addField(type = 'short_answer') {
    fieldCounter++;
    
    // Hide empty state
    fieldsEmpty.style.display = 'none';
    
    // Create field element
    const fieldElement = document.createElement('div');
    fieldElement.innerHTML = fieldTemplates[type === 'multiple_choice' ? 'multipleChoice' : 
                                            type === 'file_upload' ? 'fileUpload' :
                                            type === 'dropdown' ? 'dropdown' : 'shortAnswer'];
    
    const field = fieldElement.firstElementChild;
    field.setAttribute('data-field-id', fieldCounter);
    
    // Add to container
    fieldsContainer.appendChild(field);
    
    // Initialize field functionality
    initializeField(field);
    
    // Update custom fields data
    updateCustomFieldsData();
    
    return field;
  }

  function initializeField(field) {
    // Field type selector
    const typeToggle = field.querySelector('.field-type-toggle');
    const typeDropdown = field.querySelector('.field-type-dropdown');
    const typeOptions = typeDropdown.querySelectorAll('.field-type-option');
    
    typeToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      typeDropdown.classList.toggle('show');
    });
    
    typeOptions.forEach(option => {
      option.addEventListener('click', () => {
        const newType = option.dataset.type;
        const fieldId = field.getAttribute('data-field-id');
        
        // Remove old field
        field.remove();
        
        // Add new field with same ID
        const newField = addField(newType);
        newField.setAttribute('data-field-id', fieldId);
        
        // Copy title if it exists
        const oldTitle = field.querySelector('.field-title-input')?.value;
        if (oldTitle) {
          newField.querySelector('.field-title-input').value = oldTitle;
        }
        
        // Copy required status
        const oldRequired = field.querySelector('.required-checkbox')?.checked;
        if (oldRequired !== undefined) {
          newField.querySelector('.required-checkbox').checked = oldRequired;
        }
      });
    });
    
    // Remove field button
    const removeBtn = field.querySelector('.remove-field-btn');
    removeBtn.addEventListener('click', () => {
      field.remove();
      if (fieldsContainer.children.length === 1) { // Only empty state remains
        fieldsEmpty.style.display = 'block';
      }
      updateCustomFieldsData();
    });
    
    // Required toggle
    const requiredCheckbox = field.querySelector('.required-checkbox');
    requiredCheckbox.addEventListener('change', updateCustomFieldsData);
    
    // Title input
    const titleInput = field.querySelector('.field-title-input');
    titleInput.addEventListener('input', updateCustomFieldsData);
    
    // Field type specific initialization
    const fieldType = field.getAttribute('data-field-type');
    
    if (fieldType === 'multiple_choice' || fieldType === 'dropdown') {
      initializeMultipleChoiceField(field);
    } else if (fieldType === 'file_upload') {
      initializeFileUploadField(field);
    }
    
    // Drag and drop
    initializeDragAndDrop(field);
  }

  function initializeMultipleChoiceField(field) {
    const optionsContainer = field.querySelector('.multiple-choice-options');
    const addOptionBtn = field.querySelector('.add-option-btn');
    const isDropdown = field.getAttribute('data-field-type') === 'dropdown';
    
    // Show remove buttons for options beyond first two
    const existingOptions = optionsContainer.querySelectorAll('.mc-option');
    existingOptions.forEach((option, index) => {
      if (index >= 2) {
        const removeBtn = option.querySelector('.remove-option-btn');
        removeBtn.style.display = 'block';
        
        removeBtn.addEventListener('click', () => {
          option.remove();
          updateCustomFieldsData();
        });
      }
      
      // Option input change listener
      const optionInput = option.querySelector('input[type="text"]');
      optionInput.addEventListener('input', updateCustomFieldsData);
    });
    
    // Add option button
    addOptionBtn.addEventListener('click', () => {
      const optionCount = optionsContainer.querySelectorAll('.mc-option').length + 1;
      
      const optionDiv = document.createElement('div');
      optionDiv.className = 'mc-option';
      
      if (!isDropdown) {
        optionDiv.innerHTML = `
          <input type="radio" name="option_group_${fieldCounter}" disabled>
          <input type="text" class="option-input" placeholder="Option ${optionCount}">
          <button type="button" class="remove-option-btn">
            <i class="fas fa-times"></i>
          </button>
        `;
      } else {
        optionDiv.innerHTML = `
          <input type="text" class="option-input" placeholder="Option ${optionCount}">
          <button type="button" class="remove-option-btn">
            <i class="fas fa-times"></i>
          </button>
        `;
      }
      
      optionsContainer.appendChild(optionDiv);
      
      // Add event listener to remove button
      const removeBtn = optionDiv.querySelector('.remove-option-btn');
      removeBtn.addEventListener('click', () => {
        optionDiv.remove();
        updateCustomFieldsData();
      });
      
      // Add event listener to option input
      const optionInput = optionDiv.querySelector('.option-input');
      optionInput.addEventListener('input', updateCustomFieldsData);
      
      updateCustomFieldsData();
    });
  }

  function initializeFileUploadField(field) {
    const fileInput = field.querySelector('.file-input');
    const uploadBtn = field.querySelector('.file-upload-btn');
    const preview = field.querySelector('.file-upload-preview');
    const removeFileBtn = field.querySelector('.remove-file-btn');
    const fileName = field.querySelector('.file-name');
    const fileSize = field.querySelector('.file-size');
    
    uploadBtn.addEventListener('click', () => {
      fileInput.click();
    });
    
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        preview.classList.add('show');
        updateCustomFieldsData();
      }
    });
    
    removeFileBtn.addEventListener('click', () => {
      fileInput.value = '';
      preview.classList.remove('show');
      updateCustomFieldsData();
    });
  }

  function initializeDragAndDrop(field) {
    const dragHandle = field.querySelector('.drag-handle');
    
    dragHandle.addEventListener('mousedown', startDrag);
    dragHandle.addEventListener('touchstart', startDrag);
    
    function startDrag(e) {
      e.preventDefault();
      const fields = Array.from(fieldsContainer.querySelectorAll('.field-item:not(.dragging)'));
      const draggedField = field;
      
      draggedField.classList.add('dragging');
      
      const dragStartY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
      const fieldRect = draggedField.getBoundingClientRect();
      const offsetY = dragStartY - fieldRect.top;
      
      function moveHandler(e) {
        e.preventDefault();
        const clientY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
        const newY = clientY - offsetY;
        
        draggedField.style.position = 'fixed';
        draggedField.style.top = `${newY}px`;
        draggedField.style.left = `${fieldRect.left}px`;
        draggedField.style.width = `${fieldRect.width}px`;
        draggedField.style.zIndex = '10000';
        
        // Find the field to swap with
        const closestField = fields.reduce((closest, currentField) => {
          const box = currentField.getBoundingClientRect();
          const offset = clientY - box.top - box.height / 2;
          
          if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: currentField };
          } else {
            return closest;
          }
        }, { offset: Number.NEGATIVE_INFINITY, element: null }).element;
        
        if (closestField) {
          fieldsContainer.insertBefore(draggedField, closestField);
        } else {
          fieldsContainer.appendChild(draggedField);
        }
      }
      
      function stopHandler() {
        draggedField.classList.remove('dragging');
        draggedField.style.position = '';
        draggedField.style.top = '';
        draggedField.style.left = '';
        draggedField.style.width = '';
        draggedField.style.zIndex = '';
        
        document.removeEventListener('mousemove', moveHandler);
        document.removeEventListener('mouseup', stopHandler);
        document.removeEventListener('touchmove', moveHandler);
        document.removeEventListener('touchend', stopHandler);
        
        updateCustomFieldsData();
      }
      
      document.addEventListener('mousemove', moveHandler);
      document.addEventListener('mouseup', stopHandler);
      document.addEventListener('touchmove', moveHandler);
      document.addEventListener('touchend', stopHandler);
    }
  }

  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  function updateCustomFieldsData() {
    const fields = Array.from(fieldsContainer.querySelectorAll('.field-item'));
    const fieldsData = fields.map(field => {
      const type = field.getAttribute('data-field-type');
      const title = field.querySelector('.field-title-input').value || '';
      const required = field.querySelector('.required-checkbox')?.checked || false;
      
      let options = [];
      if (type === 'multiple_choice' || type === 'dropdown') {
        const optionInputs = field.querySelectorAll('.option-input');
        options = Array.from(optionInputs).map(input => input.value).filter(val => val.trim() !== '');
      }
      
      return {
        type: type,
        label: title,
        required: required,
        options: options.length > 0 ? options : null
      };
    }).filter(field => field.label.trim() !== ''); // Remove empty fields
    
    customFieldsData.value = JSON.stringify(fieldsData);
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

  // === COMPLETE FORM SUBMISSION ===
  const programForm = document.getElementById("programForm");
  const postedModal = document.getElementById("postedModal");
  const closePostedModal = document.getElementById("closePostedModal");

  programForm.addEventListener("submit", function(e) {
    e.preventDefault();
    
    // Update custom fields data before submission
    updateCustomFieldsData();
    
    // Show loading state
    const submitBtn = document.querySelector('.postProgramBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
    submitBtn.disabled = true;

    // Submit form via AJAX
    fetch(this.action, {
      method: 'POST',
      body: new FormData(this),
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(response => {
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        return response.text().then(text => {
          console.error('Server returned non-JSON response:', text);
          throw new Error(`Server returned HTML instead of JSON. Status: ${response.status}`);
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
        // Reset field builder
        fieldsContainer.innerHTML = '';
        fieldsEmpty.style.display = 'block';
        customFieldsData.value = '';
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
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', (e) => {
    // Close all field type dropdowns
    document.querySelectorAll('.field-type-dropdown.show').forEach(dropdown => {
      if (!dropdown.contains(e.target) && !dropdown.previousElementSibling.contains(e.target)) {
        dropdown.classList.remove('show');
      }
    });
  });
});
</script>

</body>
</html>