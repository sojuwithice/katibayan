<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/create-program.css') }}">
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
        <a href="#" class="evaluation-link nav-link">
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
  <h2>Create Program</h2>
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


});
</script>












</body>
</html>
