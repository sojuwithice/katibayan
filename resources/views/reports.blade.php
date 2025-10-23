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
    <div class="file-actions-header">
      <button class="upload-file-btn" id="uploadFileBtn">
        <i class="fas fa-upload"></i>
        Upload File
      </button>
      <button class="add-folder-btn" id="addFolderBtn">
        Add Folder
        <span class="icon-circle"><i class="fas fa-plus"></i></span>
      </button>
    </div>
  </div>

    <div class="quick-access">
  <button class="back-btn" style="display:none;" id="backBtn">
    <i class="fas fa-arrow-left"></i> Back to Folders
  </button>

  <h4 class="quick-label">Quick Access</h4>

  <div class="folder-list" id="folderList">
    <!-- Folders will be dynamically populated -->
  </div>
</div>


    <!-- Search & Category -->
<div class="file-tools">
  <div class="all-files">All Files</div>

  <div class="tools-right">
    <div class="search-box">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Search" id="fileSearch" />
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
          <div class="option" data-value="Images">Images</div>
          <div class="option" data-value="PDF">PDF</div>
        </div>
      </div>
    </div>
  </div>
</div>


    <div class="file-list">
  <div class="file-header">
    <span>Name</span>
    <span>File size</span>
    <span>Type</span>
    <span>Date</span>
  </div>

  <!-- Scrollable area -->
  <div class="file-list-container" id="fileListContainer">
    <!-- Files will be dynamically populated -->
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

<!-- Upload File Modal -->
<div id="uploadFileModal" class="modal-overlay">
  <div class="modal-box">
    <h3>Upload Files</h3>
    <input type="file" id="fileInput" multiple />
    <div class="file-preview" id="filePreview"></div>
    <div class="modal-actions">
      <button id="cancelUploadFile">Cancel</button>
      <button id="confirmUploadFile">Upload Files</button>
    </div>
  </div>
</div>

<!-- File Preview Modal -->
<div id="filePreviewModal" class="modal-overlay">
  <div class="modal-box preview-modal">
    <h3 id="previewFileName">File Preview</h3>
    <div id="previewContent" class="preview-content">
      <!-- Preview content will be loaded here -->
    </div>
    <div class="modal-actions">
      <button id="downloadPreviewBtn">
        <i class="fas fa-download"></i> Download
      </button>
      <button id="closePreview">Close</button>
    </div>
  </div>
</div>

<!-- Context Menu -->
<div id="contextMenu" class="context-menu">
  <ul>
    <li id="openFile"><i class="fas fa-folder-open"></i> Open</li>
    <li id="renameItem"><i class="fas fa-edit"></i> Rename</li>
    <li id="downloadItem"><i class="fas fa-download"></i> Download</li>
    <li id="deleteItem"><i class="fas fa-trash"></i> Delete</li>
  </ul>
</div>

<!-- Rename Modal -->
<div class="modal-overlay" id="renameModal">
  <div class="modal-box">
    <h3>Rename Item</h3>
    <input type="text" id="renameInput" placeholder="Enter new name" />
    <div class="modal-actions">
      <button class="cancel-btn" id="cancelRename">Cancel</button>
      <button class="save-btn" id="saveRename">Save</button>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
  <div class="modal-box">
    <h3>Delete Item</h3>
    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
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

  // File manager data
  let files = JSON.parse(localStorage.getItem('fileManagerFiles')) || [];
  let folders = JSON.parse(localStorage.getItem('fileManagerFolders')) || [];
  let currentFolder = null;
  let selectedItem = null;

  // Initialize with sample data if empty
  if (folders.length === 0) {
    folders = [
      { id: '1', name: 'Documentation', color: '#4a6cf7' },
      { id: '2', name: 'Summary', color: '#10b981' },
      { id: '3', name: 'Youth Files', color: '#f59e0b' },
      { id: '4', name: 'Events', color: '#ef4444' },
      { id: '5', name: 'Reports', color: '#8b5cf6' },
      { id: '6', name: 'Archives', color: '#6b7280' }
    ];
    localStorage.setItem('fileManagerFolders', JSON.stringify(folders));
  }

  if (files.length === 0) {
    files = [
      { id: '1', name: 'Annual Report 2024.pdf', size: '1.2 MB', type: 'PDF', date: '2024-01-15', folder: '1' },
      { id: '2', name: 'Youth Profile Data.xlsx', size: '850 KB', type: 'Spreadsheet', date: '2024-01-10', folder: '3' },
      { id: '3', name: 'Event Photos.zip', size: '2.4 MB', type: 'Archive', date: '2024-01-08', folder: '4' },
      { id: '4', name: 'Meeting Minutes.docx', size: '512 KB', type: 'Document', date: '2024-01-05', folder: '1' },
      { id: '5', name: 'Budget Planning.pdf', size: '3.1 MB', type: 'PDF', date: '2024-01-03', folder: '2' },
      { id: '6', name: 'Youth Survey Results.pdf', size: '1.8 MB', type: 'PDF', date: '2023-12-28', folder: '3' },
      { id: '7', name: 'Event Banner.png', size: '700 KB', type: 'Image', date: '2023-12-25', folder: '4' },
      { id: '8', name: 'Financial Report.xlsx', size: '4.5 MB', type: 'Spreadsheet', date: '2023-12-20', folder: '2' }
    ];
    localStorage.setItem('fileManagerFiles', JSON.stringify(files));
  }

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
        filterFilesByCategory(opt.dataset.value);
      });
    });
    document.addEventListener('click', e => { if (!select.contains(e.target)) select.classList.remove('active'); });
  }

  // ==========================================================
  // === FILE MANAGER FUNCTIONALITY ===
  // ==========================================================
  
  // Initialize file manager
  function initFileManager() {
    renderFolders();
    renderFiles();
    setupEventListeners();
  }

  // Render folders
  function renderFolders() {
    const folderList = qs('#folderList');
    folderList.innerHTML = '';

    folders.forEach(folder => {
      const folderCard = document.createElement('div');
      folderCard.className = 'folder-card';
      folderCard.dataset.id = folder.id;
      
      folderCard.innerHTML = `
        <div class="blue-bar" style="background: ${folder.color}"></div>
        <i class="fas fa-folder"></i>
        <span>${folder.name}</span>
      `;

      folderCard.addEventListener('click', () => {
        openFolder(folder.id);
      });

      folderCard.addEventListener('contextmenu', (e) => {
        e.preventDefault();
        selectedItem = { type: 'folder', id: folder.id };
        showContextMenu(e);
      });

      folderList.appendChild(folderCard);
    });
  }

  // Render files
  function renderFiles(filteredFiles = null) {
    const fileListContainer = qs('#fileListContainer');
    fileListContainer.innerHTML = '';

    const filesToRender = filteredFiles || (currentFolder ? 
      files.filter(file => file.folder === currentFolder) : files);

    if (filesToRender.length === 0) {
      fileListContainer.innerHTML = `
        <div class="empty-state">
          <i class="fas fa-folder-open"></i>
          <p>No files found</p>
          <button class="upload-file-btn" id="uploadEmptyBtn">
            <i class="fas fa-upload"></i>
            Upload your first file
          </button>
        </div>
      `;
      qs('#uploadEmptyBtn')?.addEventListener('click', () => {
        qs('#uploadFileModal').style.display = 'flex';
      });
      return;
    }

    filesToRender.forEach(file => {
      const fileRow = document.createElement('div');
      fileRow.className = 'file-row';
      fileRow.dataset.id = file.id;

      const fileIcon = getFileIcon(file.type);
      
      fileRow.innerHTML = `
        <span class="file-name">
          <i class="${fileIcon}"></i>
          ${file.name}
        </span>
        <span class="file-size">${file.size}</span>
        <span class="file-type">${file.type}</span>
        <span class="file-date">${formatDate(file.date)}</span>
      `;

      fileRow.addEventListener('click', (e) => {
        if (!e.target.closest('.file-action')) {
          previewFile(file.id);
        }
      });

      fileRow.addEventListener('contextmenu', (e) => {
        e.preventDefault();
        selectedItem = { type: 'file', id: file.id };
        showContextMenu(e);
      });

      fileListContainer.appendChild(fileRow);
    });
  }

  // Get file icon based on type
  function getFileIcon(type) {
    const icons = {
      'PDF': 'fas fa-file-pdf',
      'Document': 'fas fa-file-word',
      'Spreadsheet': 'fas fa-file-excel',
      'Image': 'fas fa-file-image',
      'Archive': 'fas fa-file-archive',
      'default': 'fas fa-file'
    };
    return icons[type] || icons.default;
  }

  // Format date
  function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
      year: 'numeric', 
      month: 'short', 
      day: 'numeric' 
    });
  }

  // Open folder
  function openFolder(folderId) {
    currentFolder = folderId;
    const folder = folders.find(f => f.id === folderId);
    
    // Update UI for folder view
    qs('#backBtn').style.display = 'inline-flex';
    qs('.quick-label').textContent = folder.name;
    qs('.all-files').textContent = `Files in ${folder.name}`;
    
    // Hide all folders except the selected one
    qsa('.folder-card').forEach(card => {
      card.style.display = card.dataset.id === folderId ? 'flex' : 'none';
    });

    // Render files in this folder
    renderFiles();
  }

  // Preview file
  function previewFile(fileId) {
    const file = files.find(f => f.id === fileId);
    if (!file) return;

    const previewModal = qs('#filePreviewModal');
    const previewFileName = qs('#previewFileName');
    const previewContent = qs('#previewContent');

    previewFileName.textContent = file.name;
    
    // Simple preview based on file type
    if (file.type === 'Image') {
      previewContent.innerHTML = `
        <div class="image-preview">
          <i class="fas fa-file-image" style="font-size: 48px; color: #3498db;"></i>
          <p>Image preview would be displayed here</p>
          <p><strong>File:</strong> ${file.name}</p>
          <p><strong>Size:</strong> ${file.size}</p>
          <p><strong>Type:</strong> ${file.type}</p>
        </div>
      `;
    } else if (file.type === 'PDF') {
      previewContent.innerHTML = `
        <div class="pdf-preview">
          <i class="fas fa-file-pdf" style="font-size: 48px; color: #e74c3c;"></i>
          <p>PDF preview would be displayed here</p>
          <p><strong>File:</strong> ${file.name}</p>
          <p><strong>Size:</strong> ${file.size}</p>
          <p><strong>Type:</strong> ${file.type}</p>
        </div>
      `;
    } else {
      previewContent.innerHTML = `
        <div class="file-preview">
          <i class="${getFileIcon(file.type)}" style="font-size: 48px; color: #2ecc71;"></i>
          <p><strong>File:</strong> ${file.name}</p>
          <p><strong>Size:</strong> ${file.size}</p>
          <p><strong>Type:</strong> ${file.type}</p>
          <p><strong>Uploaded:</strong> ${formatDate(file.date)}</p>
        </div>
      `;
    }

    // Set up download button
    qs('#downloadPreviewBtn').onclick = () => downloadFile(fileId);

    previewModal.style.display = 'flex';
  }

  // Download file
  function downloadFile(fileId) {
    const file = files.find(f => f.id === fileId);
    if (file) {
      alert(`Downloading: ${file.name}`);
      // In a real application, this would trigger an actual download
    }
  }

  // Filter files by category
  function filterFilesByCategory(category) {
    if (category === 'All') {
      renderFiles();
    } else {
      const filteredFiles = files.filter(file => file.type === category);
      renderFiles(filteredFiles);
    }
  }

  // Show context menu
  function showContextMenu(e) {
    const contextMenu = qs('#contextMenu');
    contextMenu.style.display = 'block';
    contextMenu.style.left = `${e.pageX}px`;
    contextMenu.style.top = `${e.pageY}px`;
  }

  // Setup event listeners
  function setupEventListeners() {
    // Back button
    qs('#backBtn').addEventListener('click', () => {
      currentFolder = null;
      qs('#backBtn').style.display = 'none';
      qs('.quick-label').textContent = 'Quick Access';
      qs('.all-files').textContent = 'All Files';
      
      // Show all folders again
      qsa('.folder-card').forEach(card => {
        card.style.display = 'flex';
      });

      renderFiles();
    });

    // Search functionality
    qs('#fileSearch').addEventListener('input', (e) => {
      const searchTerm = e.target.value.toLowerCase();
      const filteredFiles = files.filter(file => 
        file.name.toLowerCase().includes(searchTerm)
      );
      renderFiles(filteredFiles);
    });

    // Add folder modal
    qs('#addFolderBtn').addEventListener('click', () => {
      qs('#addFolderModal').style.display = 'flex';
    });

    qs('#cancelAddFolder').addEventListener('click', () => {
      qs('#addFolderModal').style.display = 'none';
    });

    qs('#confirmAddFolder').addEventListener('click', () => {
      const folderName = qs('#folderNameInput').value.trim();
      if (folderName) {
        const newFolder = {
          id: Date.now().toString(),
          name: folderName,
          color: getRandomColor()
        };
        folders.push(newFolder);
        localStorage.setItem('fileManagerFolders', JSON.stringify(folders));
        renderFolders();
        qs('#addFolderModal').style.display = 'none';
        qs('#folderNameInput').value = '';
      }
    });

    // Upload file modal
    qs('#uploadFileBtn').addEventListener('click', () => {
      qs('#uploadFileModal').style.display = 'flex';
    });

    qs('#cancelUploadFile').addEventListener('click', () => {
      qs('#uploadFileModal').style.display = 'none';
      qs('#filePreview').innerHTML = '';
    });

    qs('#confirmUploadFile').addEventListener('click', () => {
      const fileInput = qs('#fileInput');
      if (fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach(file => {
          const fileType = getFileTypeFromName(file.name);
          const newFile = {
            id: Date.now().toString() + Math.random().toString(36).substr(2, 9),
            name: file.name,
            size: formatFileSize(file.size),
            type: fileType,
            date: new Date().toISOString(),
            folder: currentFolder || '1'
          };
          files.unshift(newFile);
        });
        localStorage.setItem('fileManagerFiles', JSON.stringify(files));
        renderFiles();
        qs('#uploadFileModal').style.display = 'none';
        qs('#fileInput').value = '';
        qs('#filePreview').innerHTML = '';
      }
    });

    // File input change
    qs('#fileInput').addEventListener('change', (e) => {
      const preview = qs('#filePreview');
      preview.innerHTML = '';
      
      Array.from(e.target.files).forEach(file => {
        const fileElement = document.createElement('div');
        fileElement.className = 'file-preview-item';
        fileElement.innerHTML = `
          <i class="${getFileIcon(getFileTypeFromName(file.name))}"></i>
          <span>${file.name}</span>
          <small>${formatFileSize(file.size)}</small>
        `;
        preview.appendChild(fileElement);
      });
    });

    // Preview modal controls
    qs('#closePreview').addEventListener('click', () => {
      qs('#filePreviewModal').style.display = 'none';
    });

    // Context menu actions
    qs('#openFile').addEventListener('click', () => {
      if (selectedItem.type === 'file') {
        previewFile(selectedItem.id);
      } else if (selectedItem.type === 'folder') {
        openFolder(selectedItem.id);
      }
      qs('#contextMenu').style.display = 'none';
    });

    qs('#renameItem').addEventListener('click', () => {
      if (selectedItem) {
        showRenameModal();
      }
      qs('#contextMenu').style.display = 'none';
    });

    qs('#downloadItem').addEventListener('click', () => {
      if (selectedItem.type === 'file') {
        downloadFile(selectedItem.id);
      }
      qs('#contextMenu').style.display = 'none';
    });

    qs('#deleteItem').addEventListener('click', () => {
      if (selectedItem) {
        showDeleteModal();
      }
      qs('#contextMenu').style.display = 'none';
    });

    // Rename modal
    qs('#cancelRename').addEventListener('click', () => {
      qs('#renameModal').style.display = 'none';
    });

    qs('#saveRename').addEventListener('click', () => {
      const newName = qs('#renameInput').value.trim();
      if (newName && selectedItem) {
        if (selectedItem.type === 'folder') {
          const folder = folders.find(f => f.id === selectedItem.id);
          if (folder) folder.name = newName;
          localStorage.setItem('fileManagerFolders', JSON.stringify(folders));
          renderFolders();
        } else if (selectedItem.type === 'file') {
          const file = files.find(f => f.id === selectedItem.id);
          if (file) file.name = newName;
          localStorage.setItem('fileManagerFiles', JSON.stringify(files));
          renderFiles();
        }
        qs('#renameModal').style.display = 'none';
      }
    });

    // Delete modal
    qs('#cancelDelete').addEventListener('click', () => {
      qs('#deleteModal').style.display = 'none';
    });

    qs('#confirmDelete').addEventListener('click', () => {
      if (selectedItem) {
        if (selectedItem.type === 'folder') {
          folders = folders.filter(f => f.id !== selectedItem.id);
          // Also remove files in this folder
          files = files.filter(f => f.folder !== selectedItem.id);
          localStorage.setItem('fileManagerFolders', JSON.stringify(folders));
        } else if (selectedItem.type === 'file') {
          files = files.filter(f => f.id !== selectedItem.id);
        }
        localStorage.setItem('fileManagerFiles', JSON.stringify(files));
        renderFolders();
        renderFiles();
        qs('#deleteModal').style.display = 'none';
      }
    });

    // Close modals when clicking outside
    qsa('.modal-overlay').forEach(modal => {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.style.display = 'none';
        }
      });
    });

    // Close context menu when clicking elsewhere
    document.addEventListener('click', () => {
      qs('#contextMenu').style.display = 'none';
    });
  }

  // Helper functions
  function getRandomColor() {
    const colors = ['#4a6cf7', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280'];
    return colors[Math.floor(Math.random() * colors.length)];
  }

  function getFileTypeFromName(fileName) {
    const ext = fileName.split('.').pop().toLowerCase();
    const types = {
      'pdf': 'PDF',
      'doc': 'Document',
      'docx': 'Document',
      'xls': 'Spreadsheet',
      'xlsx': 'Spreadsheet',
      'jpg': 'Image',
      'jpeg': 'Image',
      'png': 'Image',
      'gif': 'Image',
      'zip': 'Archive',
      'rar': 'Archive'
    };
    return types[ext] || 'Document';
  }

  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  // Initialize the file manager
  initFileManager();

});
</script>

</body>
</html>