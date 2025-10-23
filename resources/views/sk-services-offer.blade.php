<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/sk-services-offer.css') }}">
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

    <div class="nav-item">
      <a href="#" class="nav-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
        <i data-lucide="chevron-down" class="submenu-arrow"></i>
      </a>
      <div class="submenu">
        <a href="{{ route('sk-eventpage') }}">Events List</a>
        <a href="{{ route('youth-program-registration') }}">Youth Registration</a>
      </div>
    </div>

    <a href="{{ route('sk-evaluation-feedback') }}">
      <i data-lucide="message-square-quote"></i>
      <span class="label">Feedbacks</span>
    </a>

    <a href="{{ route('sk-polls') }}">
      <i data-lucide="vote"></i>
      <span class="label">Polls</span>
    </a>

    <a href="{{ route('youth-suggestion') }}">
      <i data-lucide="lightbulb"></i>
      <span class="label">Suggestion Box</span>
    </a>
    
    <a href="{{ route('reports') }}">
      <i data-lucide="file-chart-column"></i>
      <span class="label">Reports</span>
    </a>

    <a href="{{ route('sk-services-offer') }}" class="active">
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

    <main class="content">

  <section class="service-offer">
  <div class="section-header">
    <h2>Service Offer</h2>
    <p>
      Discover the services offered by the SK. These are designed to make it easier for youth to participate
      in events, receive recognition, and access opportunities for learning and engagement. Explore the list
      below to see what we can provide for you.
    </p>
  </div>

  <div class="section-content">
  <div class="service-toolbar">
    <button class="add-btn">
      <i class="fas fa-plus"></i> Add Service Offer
    </button>
  </div>

  <!-- Container for all cards -->
  <div class="service-row">
    <div class="service-card">
      <i class="fas fa-ellipsis-h card-menu"></i>

        <!-- Dropdown menu -->
        <div class="options-dropdown">
        <p class="edit-option"><i class="fas fa-pen"></i> Edit</p>
        <p class="delete-option"><i class="fas fa-trash"></i> Delete</p>
        </div>

      <div class="card-header">
        <img src="{{ asset('images/print.jpeg') }}" alt="Service Image">
      </div>
      <div class="card-body">
        <h3>Free Printing Services</h3>
        <button class="read-more">Read More</button>
      </div>
    </div>

    <div class="service-card">
      <i class="fas fa-ellipsis-h card-menu"></i>

<!-- Dropdown menu -->
<div class="options-dropdown">
  <p class="edit-option"><i class="fas fa-pen"></i> Edit</p>
  <p class="delete-option"><i class="fas fa-trash"></i> Delete</p>
</div>

      <div class="card-header">
        <img src="{{ asset('images/print.jpeg') }}" alt="Service Image">
      </div>
      <div class="card-body">
        <h3>Free Printing Services</h3>
        <button class="read-more">Read More</button>
      </div>
    </div>
  </div>
</div>


</section>

<!-- === ORG CHART SECTION === -->
<section class="org-chart">
  <div class="section-header">
    <h2>Organizational Chart</h2>
    <p>
      The organizational chart of the Sangguniang Kabataan of Barangay 3 EM’s Barrio East illustrates the
      structure of its committees and defines the roles and responsibilities of each official.
    </p>
  </div>

  <div class="section-content org-upload">
    <div class="upload-container">
      <div class="upload-box" id="orgUploadBox">
        <div class="upload-placeholder">
          <i class="fas fa-image fa-3x"></i>
          <p>Drag your photo here or <a href="#" id="browseLink">Browse from device</a></p>
        </div>
        <input type="file" id="orgFileInput" accept="image/*" hidden>
      </div>
    </div>
  </div>
</section>




</main>

  <!-- === SERVICE MODAL === -->
<div class="service-modal" id="serviceModal">
  <div class="service-modal-content">
    <span class="close-modal" id="closeModal">&times;</span>

    <div class="modal-header">
      <img src="{{ asset('images/print.jpeg') }}" alt="Free Printing Poster" class="modal-poster">
    </div>

    <div class="modal-body">
      <h2>Free Printing Services</h2>
      <p>
        Supporting the youth and students of Barangay 3, EM’s Barrio East, Legazpi City. As part of our
        commitment to helping the youth in their education, the Sangguniang Kabataan of Barangay 3 is offering
        <strong>Free Printing Services</strong>. This program aims to ease the burden of school expenses by providing
        free printing, scanning, and copying of academic requirements.
      </p>

      <div class="modal-sections">
        <div class="modal-section">
          <h3>Services Offered</h3>
          <ul>
            <li>Print, scan, and copy</li>
            <li>Modules, assignments, handouts, and projects</li>
          </ul>
        </div>

        <div class="modal-section">
          <h3>Pick-Up Location</h3>
          <p>2nd Floor SK Office, Barangay 3 Multi-Purpose Hall, EM’s Barrio East, Legazpi City</p>
        </div>

        <div class="modal-section">
          <h3>How to Avail</h3>
          <ul>
            <li>FB Messenger: SK Brgy 3 EM’s Barrio East Legazpi City</li>
            <li>Email: skbrgy3embarrioeast@gmail.com</li>
          </ul>
        </div>

        <div class="modal-section">
          <h3>For Assistance</h3>
          <ul>
            <li>SK Chairperson: Lowell A. Apuac</li>
            <li>SK Secretary: Ian G. Atiaza</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
  

<!-- === ADD SERVICE OFFER MODAL === -->
<div class="add-service-modal" id="addServiceModal">
  <div class="add-service-container">
    <!-- Close Button -->
    <button class="close-modal" id="closeAddService">
      <i class="fas fa-times"></i>
    </button>

    <!-- Modal Header -->
    <header class="add-header">
      <h2>Add Service Offer</h2>
      <p>
        SK officials can list the services they provide for the youth through this feature. 
        It helps ensure that programs and opportunities are easily accessible to all members.
      </p>
    </header>

    <!-- Modal Form -->
    <form class="add-service-form">
      <!-- Upload Display -->
      <div class="form-group">
        <label>Upload Display</label>
        <div class="add-upload-box" id="serviceUploadBox">
          <i class="fas fa-image"></i>
          <p>Drag your photo here or <span class="browse">Browse from device</span></p>
          <input type="file" id="serviceUploadInput" hidden>
        </div>
      </div>

      <!-- Title -->
      <div class="form-group">
        <label for="serviceTitle">Title</label>
        <input type="text" id="serviceTitle" placeholder="">
      </div>

      <!-- Description -->
      <div class="form-group">
        <label for="serviceDescription">Add Description</label>
        <textarea id="serviceDescription" rows="4"></textarea>
      </div>

      <!-- Post Button -->
      <div class="post-btn-container">
        <button type="button" class="post-btn">Post</button>
      </div>
    </form>
  </div>
</div>


<!-- === DELETE CONFIRMATION MODAL === -->
<div class="delete-modal" id="deleteModal">
  <div class="delete-modal-content">
    <h3>Delete Service Offer</h3>
    <p>Are you sure you want to delete this service? This action cannot be undone.</p>
    <div class="delete-actions">
      <button class="cancel-btn" id="cancelDelete">Cancel</button>
      <button class="confirm-btn" id="confirmDelete">Delete</button>
    </div>
  </div>
</div>

<!-- === CONFIRMATION MODAL === -->
<div class="confirm-modal" id="confirmModal">
  <div class="confirm-content">
    <h3>Save Organizational Chart</h3>
    <p>Are you sure you want to save this new organizational chart?</p>
    <div class="confirm-buttons">
      <button id="confirmCancel">Cancel</button>
      <button id="confirmYes">Yes, Save</button>
    </div>
  </div>
</div>




<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons + sidebar toggle ===
  lucide.createIcons();
  
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');

  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('open');
    });
  }

  // === Submenus ===
  const submenuTriggers = document.querySelectorAll('.nav-item > .nav-link');

  submenuTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault(); 
      
      const parentItem = trigger.closest('.nav-item');
      const wasOpen = parentItem.classList.contains('open');

      document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('open');
      });

      if (!wasOpen) {
        parentItem.classList.add('open');
      }
    });
  });

  // === Calendar ===
  const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
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
      const month = (thisDay.getMonth() + 1).toString().padStart(2,'0');
      const day = thisDay.getDate().toString().padStart(2,'0');
      const dateStr = `${thisDay.getFullYear()}-${month}-${day}`;
      if (holidays.includes(dateStr)) dateEl.classList.add('holiday');
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
  const prevBtn = document.querySelector(".calendar .prev");
  const nextBtn = document.querySelector(".calendar .next");
  if (prevBtn) prevBtn.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() - 7);
    renderCalendar(currentView);
  });
  if (nextBtn) nextBtn.addEventListener("click", () => {
    currentView.setDate(currentView.getDate() + 7);
    renderCalendar(currentView);
  });

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

  // === Notifications ===
  const notifWrapper = document.querySelector(".notification-wrapper");
  const profileWrapper = document.querySelector(".profile-wrapper");
  const profileToggle = document.getElementById("profileToggle");
  const profileDropdown = document.querySelector(".profile-dropdown");
  if (notifWrapper) {
    const bell = notifWrapper.querySelector(".fa-bell");
    if (bell) {
      bell.addEventListener("click", (e) => {
        e.stopPropagation();
        notifWrapper.classList.toggle("active");
        profileWrapper?.classList.remove("active");
      });
    }
    const dropdown = notifWrapper.querySelector(".notif-dropdown");
    if (dropdown) dropdown.addEventListener("click", (e) => e.stopPropagation());
  }
  if (profileWrapper && profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
      notifWrapper?.classList.remove("active");
    });
    profileDropdown.addEventListener("click", (e) => e.stopPropagation());
  }

  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
      profileItem?.classList.remove('open');
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
    document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
  });

  // === Highlight Holidays in Events ===
  document.querySelectorAll('.events li').forEach(eventItem => {
    const dateEl = eventItem.querySelector('.date span');
    const monthEl = eventItem.querySelector('.date strong');
    if (!dateEl || !monthEl) return;
    const monthMap = {
      JAN: "01", FEB: "02", MAR: "03", APR: "04", MAY: "05", JUN: "06",
      JUL: "07", AUG: "08", SEP: "09", OCT: "10", NOV: "11", DEC: "12"
    };
    const monthNum = monthMap[monthEl.textContent.trim().toUpperCase()];
    const day = dateEl.textContent.trim().padStart(2,'0');
    const dateStr = `2025-${monthNum}-${day}`;
    if (holidays.includes(dateStr)) {
      eventItem.querySelector('.date').classList.add('holiday');
    }
  });

  // === SERVICE MODAL ===
  const modal = document.getElementById("serviceModal");
  const readMoreButtons = document.querySelectorAll(".read-more");
  const closeModal = document.getElementById("closeModal");
  readMoreButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    });
  });
  closeModal.addEventListener("click", () => {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  });
  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      document.body.style.overflow = "auto";
    }
  });

  // === ADD SERVICE MODAL ===
const addServiceModal = document.getElementById("addServiceModal");
const addServiceBtn = document.querySelector(".add-btn");
const closeAddService = document.getElementById("closeAddService");

const serviceUploadBox = document.getElementById("serviceUploadBox");
const serviceFileInput = document.getElementById("serviceUploadInput");

// === OPEN MODAL ===
addServiceBtn.addEventListener("click", () => {
  addServiceModal.style.display = "flex";
  document.body.style.overflow = "hidden";
});

// === CLOSE MODAL ===
closeAddService.addEventListener("click", () => {
  addServiceModal.style.display = "none";
  document.body.style.overflow = "auto";
});

// === CLOSE BY CLICKING OUTSIDE ===
window.addEventListener("click", (e) => {
  if (e.target === addServiceModal) {
    addServiceModal.style.display = "none";
    document.body.style.overflow = "auto";
  }
});

// === CLICK BOX TO UPLOAD ===
serviceUploadBox.addEventListener("click", () => serviceFileInput.click());

// === PREVIEW IMAGE ===
serviceFileInput.addEventListener("change", () => {
  if (serviceFileInput.files && serviceFileInput.files[0]) {
    const file = serviceFileInput.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
      serviceUploadBox.innerHTML = `
        <img src="${e.target.result}" 
             alt="Preview" 
             style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
      `;
    };
    reader.readAsDataURL(file);
  }
});


  // === ORG CHART UPLOAD ===
const orgUploadBox = document.getElementById("orgUploadBox");
const orgFileInput = document.getElementById("orgFileInput");
const orgBrowseLink = document.getElementById("browseLink");

const confirmModal = document.getElementById("confirmModal");
const confirmYes = document.getElementById("confirmYes");
const confirmCancel = document.getElementById("confirmCancel");

let selectedFile = null; // temporary storage before confirm
let hasImage = false;

if (orgUploadBox && orgFileInput) {
  // Browse link click
  orgBrowseLink.addEventListener("click", (e) => {
    e.preventDefault();
    orgFileInput.click();
  });

  // Box click (also opens picker)
  orgUploadBox.addEventListener("click", () => orgFileInput.click());

  // When image selected
  orgFileInput.addEventListener("change", () => {
    selectedFile = orgFileInput.files[0];
    if (!selectedFile) return;
    confirmModal.classList.add("active"); // show confirmation
  });

  // Confirm Save
  confirmYes.addEventListener("click", () => {
    confirmModal.classList.remove("active");
    if (!selectedFile) return;

    const reader = new FileReader();
    reader.onload = (e) => {
      hasImage = true;
      orgUploadBox.innerHTML = `
        <img src="${e.target.result}" alt="Organizational Chart">
        <div class="update-overlay">
          <button class="update-btn">Update Image</button>
        </div>
      `;

      // Update image handler
      const updateBtn = orgUploadBox.querySelector(".update-btn");
      updateBtn.addEventListener("click", (ev) => {
        ev.stopPropagation();
        orgFileInput.click();
      });
    };
    reader.readAsDataURL(selectedFile);
    selectedFile = null; // reset after use
  });

  // Cancel confirmation
  confirmCancel.addEventListener("click", () => {
    confirmModal.classList.remove("active");
    orgFileInput.value = ""; // reset file input (no change)
    selectedFile = null;
  });
}




// === SERVICE CARD ELLIPSIS MENU ===
const cardMenus = document.querySelectorAll('.card-menu');

cardMenus.forEach(menu => {
  menu.addEventListener('click', (e) => {
    e.stopPropagation();
    // Close other open dropdowns
    document.querySelectorAll('.options-dropdown').forEach(drop => {
      if (drop !== menu.nextElementSibling) drop.classList.remove('show');
    });
    // Toggle current dropdown
    const dropdown = menu.nextElementSibling;
    dropdown.classList.toggle('show');
  });
});

// Close dropdown if click outside
document.addEventListener('click', () => {
  document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
});

// === Click handlers for Edit / Delete ===
document.addEventListener('click', (e) => {
  if (e.target.closest('.edit-option')) {
    const card = e.target.closest('.service-card');
    alert(`Edit: ${card.querySelector('h3').textContent}`);
  }
  if (e.target.closest('.delete-option')) {
    const card = e.target.closest('.service-card');
    if (confirm(`Delete "${card.querySelector('h3').textContent}"?`)) {
      card.remove();
    }
  }
});

// === DELETE MODAL FUNCTIONALITY ===
const deleteModal = document.getElementById("deleteModal");
const cancelDelete = document.getElementById("cancelDelete");
const confirmDelete = document.getElementById("confirmDelete");
let currentCardToDelete = null;

// When click delete option
document.querySelectorAll('.delete-option').forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    deleteModal.style.display = "flex";
    currentCardToDelete = e.target.closest(".service-card");
  });
});

// Close modal (cancel)
cancelDelete.addEventListener("click", () => {
  deleteModal.style.display = "none";
  currentCardToDelete = null;
});

// Confirm delete
confirmDelete.addEventListener("click", () => {
  if (currentCardToDelete) {
    currentCardToDelete.remove();
    currentCardToDelete = null;
  }
  deleteModal.style.display = "none";
});

  

});
</script>

</body>
</html>