<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
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
    
    <a href="{{ route('reports') }}" class="active">
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

    <section class="reports-section">
  <!-- Title Bar -->
  <div class="reports-titlebar">
    <h2>Reports Compilation</h2>
  </div>

  <!-- Main Card -->
<div class="reports-card">
  <div class="files-header">
    <h3>Files</h3>
    <button class="add-folder-btn">
      Add Folder
      <span class="icon-circle"><i class="fas fa-plus"></i></span>
    </button>
  </div>

    <div class="quick-access">
  <button class="back-btn" style="display:none;">
    <i class="fas fa-arrow-left"></i> Back to Folders
  </button>

  <h4 class="quick-label">Quick Access</h4>

  <div class="folder-list" id="folderList">

    <div class="folder-card">
      <div class="blue-bar"></div>
      <i class="fas fa-folder"></i>
      <span>Documentation</span>
    </div>

    <div class="folder-card">
      <div class="blue-bar"></div>
      <i class="fas fa-folder"></i>
      <span>Summary</span>
    </div>

    <div class="folder-card">
      <div class="blue-bar"></div>
      <i class="fas fa-folder"></i>
      <span>Youth Files</span>
    </div>

    <div class="folder-card">
      <div class="blue-bar"></div>
      <i class="fas fa-folder"></i>
      <span>Documentation</span>
    </div>

    <div class="folder-card">
      <div class="blue-bar"></div>
      <i class="fas fa-folder"></i>
      <span>Documentation</span>
    </div>

    <div class="folder-card">
      <div class="blue-bar"></div>
      <i class="fas fa-folder"></i>
      <span>Documentation</span>
    </div>

  </div>
</div>


    <!-- Search & Category -->
<div class="file-tools">
  <div class="all-files">All Files</div>

  <div class="tools-right">
    <div class="search-box">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Search" />
    </div>

    <div class="category-box">
      <label>Category:</label>
      <div class="custom-select">
        <div class="select-trigger">
          <span class="selected">All</span>
          <div class="arrow-circle">
            <i class="fas fa-chevron-down"></i>
          </div>
        </div>
        <div class="options">
          <div class="option" data-value="All">All</div>
          <div class="option" data-value="Summary">Summary</div>
          <div class="option" data-value="Documentation">Documentation</div>
          <div class="option" data-value="Youth">Youth</div>
        </div>
      </div>
</div>
  </div>
</div>


    <div class="file-list">
  <div class="file-header">
    <span>Name</span>
    <span>File size</span>
  </div>

  <!-- Scrollable area -->
  <div class="file-list-container">
    <div class="file-row">
      <span class="file-name">Document 1</span>
      <span class="file-size">1.2 MB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 2</span>
      <span class="file-size">850 KB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 3</span>
      <span class="file-size">2.4 MB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 4</span>
      <span class="file-size">512 KB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 5</span>
      <span class="file-size">3.1 MB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 6</span>
      <span class="file-size">1.8 MB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 7</span>
      <span class="file-size">700 KB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 8</span>
      <span class="file-size">4.5 MB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 9</span>
      <span class="file-size">950 KB</span>
    </div>
    <div class="file-row">
      <span class="file-name">Document 10</span>
      <span class="file-size">2.6 MB</span>
    </div>
  </div>
</div>


  </div>
</section>

<!-- Add Folder Modal -->
<div id="addFolderModal" class="modal-overlay">
  <div class="modal-box">
    <h3>Add New Folder</h3>
    <input type="text" id="folderNameInput" placeholder="Enter folder name" />
    <div class="modal-actions">
      <button id="cancelAddFolder">Cancel</button>
      <button id="confirmAddFolder">Add Folder</button>
    </div>
  </div>
</div>

<!-- Add File Modal -->
<div id="addFileModal" class="modal-overlay">
  <div class="modal-box">
    <h3>Add New File</h3>
    <input type="file" id="fileInput" />
    <div class="modal-actions">
      <button id="cancelAddFile">Cancel</button>
      <button id="confirmAddFile">Add File</button>
    </div>
  </div>
</div>


<!-- Context Menu -->
<div id="contextMenu" class="context-menu">
  <ul>
    <li id="renameFolder">Rename</li>
    <li id="deleteFolder">Delete</li>
  </ul>
</div>

<!-- Rename Modal -->
<div class="modal" id="renameModal">
  <div class="modal-content">
    <h3>Rename Folder</h3>
    <input type="text" id="renameInput" placeholder="Enter new folder name" />
    <div class="modal-actions">
      <button class="cancel-btn" id="cancelRename">Cancel</button>
      <button class="save-btn" id="saveRename">Save</button>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
  <div class="modal-content">
    <h3>Delete Folder</h3>
    <p>Are you sure you want to delete this folder?</p>
    <div class="modal-actions">
      <button class="cancel-btn" id="cancelDelete">Cancel</button>
      <button class="delete-btn" id="confirmDelete">Delete</button>
    </div>
  </div>
</div>

    
    








<script>
document.addEventListener("DOMContentLoaded", () => {

  // ==========================================================
  // === INITIAL SETUP ===
  // ==========================================================
  lucide.createIcons();
  const qs = s => document.querySelector(s);
  const qsa = s => document.querySelectorAll(s);

  // ==========================================================
  // === SIDEBAR TOGGLE & SUBMENUS ===
  // ==========================================================
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

  // ==========================================================
  // === CALENDAR SETUP ===
  // ==========================================================
  const daysContainer = qs(".calendar .days");
  const header = qs(".calendar header h3");
  const prevBtn = qs(".calendar .prev");
  const nextBtn = qs(".calendar .next");
  const weekdays = ["MON","TUE","WED","THU","FRI","SAT","SUN"];
  const holidays = [
    "2025-01-01","2025-04-09","2025-04-17","2025-04-18","2025-05-01",
    "2025-06-06","2025-06-12","2025-08-25","2025-11-30","2025-12-25","2025-12-30"
  ];
  let today = new Date();
  let currentView = new Date();

  const renderCalendar = baseDate => {
    if (!daysContainer || !header) return;
    daysContainer.innerHTML = "";

    const start = new Date(baseDate);
    start.setDate(baseDate.getDate() - (baseDate.getDay() === 0 ? 6 : baseDate.getDay() - 1));
    const middle = new Date(start);
    middle.setDate(start.getDate() + 3);
    header.textContent = middle.toLocaleDateString("en-US", { month: "long", year: "numeric" });

    for (let i = 0; i < 7; i++) {
      const d = new Date(start);
      d.setDate(start.getDate() + i);
      const el = document.createElement("div");
      el.className = "day";

      const weekday = document.createElement("span");
      weekday.className = "weekday";
      weekday.textContent = weekdays[i];

      const date = document.createElement("span");
      date.className = "date";
      date.textContent = d.getDate();

      const dateStr = `${d.getFullYear()}-${(d.getMonth()+1).toString().padStart(2,'0')}-${d.getDate().toString().padStart(2,'0')}`;
      if (holidays.includes(dateStr)) date.classList.add('holiday');
      if (d.toDateString() === today.toDateString()) el.classList.add("active");

      el.append(weekday, date);
      daysContainer.appendChild(el);
    }
  };

  renderCalendar(currentView);
  prevBtn?.addEventListener("click", () => { currentView.setDate(currentView.getDate() - 7); renderCalendar(currentView); });
  nextBtn?.addEventListener("click", () => { currentView.setDate(currentView.getDate() + 7); renderCalendar(currentView); });

  // ==========================================================
  // === TIME AUTO-UPDATE ===
  // ==========================================================
  const timeEl = qs(".time");
  const updateTime = () => {
    if (!timeEl) return;
    const now = new Date();
    const shortW = ["SUN","MON","TUE","WED","THU","FRI","SAT"];
    const shortM = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
    const h = now.getHours();
    const m = now.getMinutes().toString().padStart(2, "0");
    const ampm = h >= 12 ? "PM" : "AM";
    timeEl.innerHTML = `${shortW[now.getDay()]}, ${shortM[now.getMonth()]} ${now.getDate()} ${h % 12 || 12}:${m} <span>${ampm}</span>`;
  };
  updateTime();
  setInterval(updateTime, 60000);

  // ==========================================================
  // === NOTIFICATION & PROFILE DROPDOWNS ===
  // ==========================================================
  const notifWrapper = qs(".notification-wrapper");
  const profileWrapper = qs(".profile-wrapper");
  const profileToggle = qs("#profileToggle");
  const profileDropdown = qs(".profile-dropdown");

  notifWrapper?.querySelector(".fa-bell")?.addEventListener("click", e => {
    e.stopPropagation();
    notifWrapper.classList.toggle("active");
    profileWrapper?.classList.remove("active");
  });

  profileToggle?.addEventListener("click", e => {
    e.stopPropagation();
    profileWrapper.classList.toggle("active");
    notifWrapper?.classList.remove("active");
  });

  document.addEventListener("click", e => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) sidebar.classList.remove('open');
    profileWrapper?.classList.remove('active');
    notifWrapper?.classList.remove('active');
    qsa('.options-dropdown').forEach(drop => drop.classList.remove('show'));
  });

  // ==========================================================
  // === CUSTOM SELECT ===
  // ==========================================================
  const select = qs('.custom-select');
  if (select) {
    const trigger = select.querySelector('.select-trigger');
    const selectedText = select.querySelector('.selected');
    const options = select.querySelectorAll('.option');

    trigger.addEventListener('click', () => select.classList.toggle('active'));
    options.forEach(opt => {
      opt.addEventListener('click', () => {
        selectedText.textContent = opt.textContent;
        options.forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        select.classList.remove('active');
      });
    });
    document.addEventListener('click', e => { if (!select.contains(e.target)) select.classList.remove('active'); });
  }

  // ==========================================================
  // === FOLDER & FILE MANAGEMENT ===
  // ==========================================================
  const folderList = qs('.folder-list');
  const fileListContainer = qs('.file-list-container');
  const addFolderModal = qs('#addFolderModal');
  const addFileModal = qs('#addFileModal');
  const addFolderBtn = qs('.add-folder-btn');
  const backBtn = qs('.back-btn');
  const quickLabel = qs('.quick-label');
  const folderNameInput = qs('#folderNameInput');
  const confirmAddFolder = qs('#confirmAddFolder');
  const cancelAddFolder = qs('#cancelAddFolder');
  const fileInput = qs('#fileInput');
  const confirmAddFile = qs('#confirmAddFile');
  const cancelAddFile = qs('#cancelAddFile');

  let insideFolder = false;
  let selectedFolder = null;
  let selectedFile = null;

  // Add Folder
  addFolderBtn?.addEventListener('click', () => {
    if (insideFolder) addFileModal.style.display = 'flex';
    else addFolderModal.style.display = 'flex';
  });

  confirmAddFolder?.addEventListener('click', () => {
    const name = folderNameInput.value.trim();
    if (!name) return;
    const card = document.createElement('div');
    card.className = 'folder-card';
    card.innerHTML = `<div class="blue-bar"></div><i class="fas fa-folder"></i><span>${name}</span>`;
    folderList.appendChild(card);
    addFolderModal.style.display = 'none';
  });

  cancelAddFolder?.addEventListener('click', () => addFolderModal.style.display = 'none');
  cancelAddFile?.addEventListener('click', () => addFileModal.style.display = 'none');

  confirmAddFile?.addEventListener('click', () => {
    const file = fileInput.files[0];
    if (!file) return alert("Select a file first");
    const row = document.createElement('div');
    row.className = 'file-row';
    const sizeKB = file.size / 1024;
    const size = sizeKB < 1024 ? `${sizeKB.toFixed(1)} KB` : `${(sizeKB/1024).toFixed(1)} MB`;
    row.innerHTML = `<span class="file-name">${file.name}</span><span class="file-size">${size}</span>`;
    fileListContainer.appendChild(row);
    addFileModal.style.display = 'none';
  });

  // Folder click → activate folder, hide others, hide label, switch to file view
folderList?.addEventListener('click', e => {
  const folder = e.target.closest('.folder-card');
  if (!folder) return;

  // Activate clicked folder
  folderList.querySelectorAll('.folder-card').forEach(f => {
    f.classList.toggle('active', f === folder);
    f.style.display = f === folder ? 'flex' : 'none';
  });

  // Hide Quick Access label
  quickLabel.style.display = 'none';

  // Switch UI state
  insideFolder = true;
  backBtn.style.display = 'inline-flex';
  addFolderBtn.innerHTML = `Add File <span class="icon-circle"><i class="fas fa-upload"></i></span>`;
});

// Back button → restore folder view and show label
backBtn?.addEventListener('click', () => {
  insideFolder = false;

  // Show all folders again
  folderList.querySelectorAll('.folder-card').forEach(f => {
    f.classList.remove('active');
    f.style.display = 'flex';
  });

  // Restore Quick Access label
  quickLabel.style.display = 'block';

  // Reset buttons
  backBtn.style.display = 'none';
  addFolderBtn.innerHTML = `Add Folder <span class="icon-circle"><i class="fas fa-plus"></i></span>`;
});



  // ==========================================================
  // === CONTEXT MENU (RENAME / DELETE) ===
  // ==========================================================
  const contextMenu = qs('#contextMenu');
  const renameModal = qs('#renameModal');
  const deleteModal = qs('#deleteModal');
  const renameInput = qs('#renameInput');
  const renameOption = qs('#renameFolder');
  const deleteOption = qs('#deleteFolder');
  const saveRename = qs('#saveRename');
  const cancelRename = qs('#cancelRename');
  const confirmDelete = qs('#confirmDelete');
  const cancelDelete = qs('#cancelDelete');

  folderList?.addEventListener('contextmenu', e => {
    e.preventDefault();
    selectedFolder = e.target.closest('.folder-card');
    if (!selectedFolder) return;
    contextMenu.style.display = 'block';
    contextMenu.style.left = `${e.pageX}px`;
    contextMenu.style.top = `${e.pageY}px`;
  });

  fileListContainer?.addEventListener('contextmenu', e => {
    e.preventDefault();
    selectedFile = e.target.closest('.file-row');
    if (!selectedFile) return;
    contextMenu.style.display = 'block';
    contextMenu.style.left = `${e.pageX}px`;
    contextMenu.style.top = `${e.pageY}px`;
  });

  // Rename
  renameOption?.addEventListener('click', () => {
    const target = selectedFolder || selectedFile;
    if (!target) return;
    renameInput.value = target.querySelector('.file-name, span')?.textContent || "";
    renameModal.style.display = 'flex';
    contextMenu.style.display = 'none';
  });

  saveRename?.addEventListener('click', () => {
    const newName = renameInput.value.trim();
    if (!newName) return;
    if (selectedFolder) selectedFolder.querySelector('span').textContent = newName;
    if (selectedFile) selectedFile.querySelector('.file-name').textContent = newName;
    renameModal.style.display = 'none';
  });

  cancelRename?.addEventListener('click', () => renameModal.style.display = 'none');

  // Delete
  deleteOption?.addEventListener('click', () => {
    if (selectedFolder || selectedFile) deleteModal.style.display = 'flex';
    contextMenu.style.display = 'none';
  });

  confirmDelete?.addEventListener('click', () => {
    if (selectedFolder) selectedFolder.remove();
    if (selectedFile) selectedFile.remove();
    deleteModal.style.display = 'none';
    selectedFolder = selectedFile = null;
  });

  cancelDelete?.addEventListener('click', () => deleteModal.style.display = 'none');

  // Hide context menu
  window.addEventListener('click', e => {
    if (!contextMenu.contains(e.target)) contextMenu.style.display = 'none';
  });

});
</script>


</body>
</html>