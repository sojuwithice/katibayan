<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
</head>
<body>
  
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


  <div class="main">

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
            </ul>
          </div>
        </div>

        <!-- Profile Avatar -->
        <div class="profile-wrapper">
          <img src="{{ ($user ?? null) && $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ ($user ?? null) && $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
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

    <section class="reports-section">
  <div class="reports-titlebar">
    <h2>Reports Compilation</h2>
  </div>

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
    </div>
</div>


    <div class="file-tools">
  <div class="all-files">SK Report files</div>

  <div class="tools-right">
    <div class="search-box">
      <i class="fas fa-search"></i>
      <input type="text" id="fileSearch" placeholder="Search" />
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
    <span>Owner</span>
    <span>File size</span>
  </div>

  <div class="file-list-container" id="fileListContainer">
    </div>
</div>


  </div>
</section>

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

<div id="filePreviewModal" class="modal-overlay" style="display:none; align-items:center; justify-content:center;">
  <div class="modal-box" style="text-align:center;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <h3 id="previewFileName" style="margin:0; text-align:left; word-break:break-all;">FileName</h3>
        <button id="closePreviewBtn" style="background:none; border:none; font-size:18px; cursor:pointer;"><i class="fas fa-times"></i></button>
    </div>
    <div id="previewContent">
       <span style="color:#888;">Loading preview...</span>
    </div>
    <div class="modal-actions" style="justify-content: center; margin-top:20px;">
      <button class="delete-btn" id="downloadPreviewBtn" style="background:#4a6cf7; color:white; width:100%;">
        <i class="fas fa-download"></i> Download File
      </button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="renameModal" style="display:none;">
  <div class="modal-box">
    <h3>Rename Item</h3>
    <input type="text" id="renameInput" placeholder="Enter new name" />
    <div class="modal-actions">
      <button class="cancel-btn" id="cancelRename">Cancel</button>
      <button class="save-btn" id="saveRename" style="background:#10b981; color:white;">Save</button>
    </div>
  </div>
</div>

<div id="contextMenu" class="context-menu">
  <ul>
    <li id="downloadItem"><i class="fas fa-download" style="margin-right:8px; width:20px; text-align:center;"></i> Download</li>
    <li id="archiveItem"><i class="fas fa-box-archive" style="margin-right:8px; width:20px; text-align:center;"></i> Archive</li>
    <li id="backupItem"><i class="fas fa-copy" style="margin-right:8px; width:20px; text-align:center;"></i> Create Backup</li>
    
    <li id="renameItem"><i class="fas fa-edit" style="margin-right:8px; width:20px; text-align:center;"></i> Rename</li>
    
    <li id="deleteItem"><i class="fas fa-trash" style="margin-right:8px; width:20px; text-align:center; color:#e74c3c;"></i> Delete</li>
  </ul>
</div>

<div id="deleteModal" class="modal-overlay">
  <div class="modal-box">
    <h3>Delete Item</h3>
    <p>Are you sure you want to delete this?</p>
    <div class="modal-actions">
      <button class="cancel-btn" id="cancelDelete">Cancel</button>
      <button class="delete-btn" id="confirmDelete">Delete</button>
    </div>
  </div>
</div>

    
<script>
document.addEventListener("DOMContentLoaded", () => {

  // ==========================================================
  // === CONFIG & DATA ===
  // ==========================================================
  if (typeof lucide !== 'undefined') lucide.createIcons();
  
  const qs = s => document.querySelector(s);
  const qsa = s => document.querySelectorAll(s);
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // Data
  let folders = @json($folders ?? []);
  let files = @json($files ?? []);
  
  // DEFAULT FULL NAME (First + Last)
  const currentUserName = "{{ Auth::user()->given_name }} {{ Auth::user()->last_name }}"; 

  // ==========================================================
  // === UI ELEMENTS ===
  // ==========================================================
  const folderList = qs('#folderList');
  const fileListContainer = qs('#fileListContainer');
  const addFolderBtn = qs('.add-folder-btn');
  const backBtn = qs('.back-btn');
  const quickLabel = qs('.quick-label');
  const filesTitleLabel = qs('.all-files'); 

  // Modals & Context Menu
  const previewModal = qs('#filePreviewModal');
  const previewContent = qs('#previewContent');
  const contextMenu = qs('#contextMenu');
  const deleteModal = qs('#deleteModal');

  // State
  let insideFolder = false;
  let currentFolderId = null;
  let selectedItem = null;
  let previewFileId = null;
  
  // STATE: Unread Indicator (Orange Dot)
  // Default ay FALSE (may highlight), magiging TRUE kapag binuksan mo na.
  let reportsRead = false; 

  // ==========================================================
  // === RENDER FOLDERS (With Orange Dot) ===
  // ==========================================================
  function renderFolders() {
    if(!folderList) return;
    folderList.innerHTML = '';
    
    folders.forEach(folder => {
      const card = document.createElement('div');
      card.className = 'folder-card';
      card.dataset.id = folder.id;
      
      const isReportFolder = folder.name.includes('SK Report Files');
      
      // LOGIC: Orange Dot kung Report Folder at HINDI pa nabasa
      let dotHtml = '';
      if (isReportFolder && !reportsRead) {
          dotHtml = `<span class="notification-dot" style="
              position: absolute;
              top: 10px;
              right: 10px;
              width: 10px;
              height: 10px;
              background-color: #f59e0b; /* Orange */
              border-radius: 50%;
              box-shadow: 0 0 4px rgba(245, 158, 11, 0.6);
              z-index: 10;
          "></span>`;
      }
      
      card.style.position = 'relative'; // Para sa absolute positioning ng dot

      card.innerHTML = `
        ${dotHtml}
        <div class="blue-bar"></div>
        <i class="fas fa-folder"></i>
        <span>${folder.name}</span>
      `;
      
      card.addEventListener('click', () => {
          // Kapag kinlick ang folder na may reports, mark as read
          if(isReportFolder) {
              reportsRead = true;
          }
          openFolder(folder.id);
      });
      
      card.addEventListener('contextmenu', e => {
        e.preventDefault();
        selectedItem = { type: 'folder', id: folder.id, name: folder.name };
        showContextMenu(e);
      });

      folderList.appendChild(card);
    });
  }

  // ==========================================================
  // === RENDER FILES (Correct Name & Root Display) ===
  // ==========================================================
  function renderFiles() {
    if(!fileListContainer) return;
    fileListContainer.innerHTML = '';

    let filteredFiles = [];

    if (insideFolder && currentFolderId) {
        // SCENARIO 1: Inside a Folder
        filteredFiles = files.filter(f => f.folder_id == currentFolderId);
    } else {
        // SCENARIO 2: Root View (Combine Loose Files + Report Files)
        const reportFolder = folders.find(f => f.name.includes('SK Report Files'));
        
        if (reportFolder) {
            const reportFiles = files.filter(f => f.folder_id == reportFolder.id);
            const looseFiles = files.filter(f => !f.folder_id);
            filteredFiles = [...reportFiles, ...looseFiles];
            
            if(filesTitleLabel) filesTitleLabel.textContent = "SK Report Files";
        } else {
            filteredFiles = files.filter(f => !f.folder_id);
        }
    }
    
    if (filteredFiles.length === 0) {
        fileListContainer.innerHTML = `<div style="padding:20px; text-align:center; color:#888;">No files found</div>`;
        return;
    }

    // Sort: Newest ID first
    filteredFiles.sort((a, b) => b.id - a.id);

    filteredFiles.forEach(file => {
      const row = document.createElement('div');
      row.className = 'file-row';
      row.dataset.id = file.id;
      
      let icon = 'fas fa-file';
      let color = '#888';
      
      if(file.type === 'PDF') { icon = 'fas fa-file-pdf'; color = '#e74c3c'; }
      if(file.type === 'Image') { icon = 'fas fa-file-image'; color = '#f39c12'; }
      if(file.type.includes('Spreadsheet')) { icon = 'fas fa-file-excel'; color = '#27ae60'; }
      if(file.type.includes('Document')) { icon = 'fas fa-file-word'; color = '#2980b9'; }

      // --- FULL NAME DISPLAY ---
      // file.uploaded_by ay galing sa Controller regex (Full Name na kung bago)
      // currentUserName ay fallback (Full Name din)
      const uploaderName = file.uploaded_by || currentUserName;

      row.innerHTML = `
        <span class="file-name"><i class="${icon}" style="color:${color}; margin-right:8px;"></i> ${file.name}</span>
        <span class="file-owner">${uploaderName}</span>
        <span class="file-size">${file.size}</span>
      `;

      row.addEventListener('click', (e) => {
        e.stopPropagation();
        
        // Kapag binuksan mo ang file, considered "Read" na rin
        // (Optional: Kung gusto mo mawala yung dot pag nag-view ng file)
        const reportFolder = folders.find(f => f.name.includes('SK Report Files'));
        if(reportFolder && file.folder_id == reportFolder.id) {
             reportsRead = true;
             renderFolders(); // Update UI to remove dot
        }

        openPreview(file);
      });
      
      row.addEventListener('contextmenu', e => {
        e.preventDefault();
        selectedItem = { type: 'file', id: file.id, name: file.name };
        showContextMenu(e);
      });

      fileListContainer.appendChild(row);
    });
  }

  function openFolder(id) {
    insideFolder = true;
    currentFolderId = id;
    
    if(filesTitleLabel) filesTitleLabel.textContent = "Folder Files";
    if(quickLabel) quickLabel.style.display = 'none';
    if(backBtn) backBtn.style.display = 'inline-flex';
    if(addFolderBtn) addFolderBtn.innerHTML = `Add File <span class="icon-circle"><i class="fas fa-upload"></i></span>`;

    // Re-render folders para mag-update ang active state at mawala ang dot
    renderFolders(); 

    qsa('.folder-card').forEach(c => {
        if(c.dataset.id == id) {
            c.style.display = 'flex';
            c.classList.add('active'); 
        } else {
            c.style.display = 'none';
            c.classList.remove('active');
        }
    });
    renderFiles();
  }

  // ==========================================================
  // === PREVIEW & ACTIONS (Retained Logic) ===
  // ==========================================================
  function openPreview(file) {
      previewFileId = file.id;
      const pName = qs('#previewFileName');
      if(pName) pName.textContent = file.name;
      const modalBox = qs('#filePreviewModal .modal-box');
      if(!modalBox) return;

      const fileUrl = `/reports/view/${file.id}`;
      let contentHTML = '';

      if(file.type === 'Image') {
         modalBox.style.width = '700px'; 
         contentHTML = `<img src="${fileUrl}" alt="Preview" style="max-width:100%;" onerror="this.onerror=null;this.parentElement.innerHTML='Error loading image';">`;
      } else if(file.type === 'PDF') {
         modalBox.style.width = '800px'; 
         contentHTML = `<iframe src="${fileUrl}#toolbar=0" style="width:100%; height:500px;" title="PDF Preview"></iframe>`;
      } else {
         modalBox.style.width = '400px';
         let iconClass = "fas fa-file";
         let color = "#888";
         if(file.type.includes('Spreadsheet')) { iconClass = "fas fa-file-excel"; color = "#27ae60"; }
         else if(file.type.includes('Document')) { iconClass = "fas fa-file-word"; color = "#2980b9"; }
         
         contentHTML = `
            <div style="text-align:center; padding: 20px;">
                <i class="${iconClass}" style="font-size: 64px; color: ${color}; margin: 20px 0;"></i>
                <p><strong>${file.name}</strong></p>
                <p style="font-size:13px; color:#666; margin-top:10px;">Preview not available.</p>
                <a href="/reports/download/${file.id}" class="btn btn-primary" style="margin-top:15px; display:inline-block;">Download File</a>
            </div>
         `;
      }
      if(previewContent) previewContent.innerHTML = contentHTML;
      if(previewModal) previewModal.style.display = 'flex';
  }

  const closePreviewBtn = qs('#closePreviewBtn');
  if(closePreviewBtn) closePreviewBtn.addEventListener('click', () => { previewModal.style.display = 'none'; previewContent.innerHTML = ''; });
  
  const downloadPreviewBtn = qs('#downloadPreviewBtn');
  if(downloadPreviewBtn) downloadPreviewBtn.addEventListener('click', () => { if(previewFileId) window.location.href = `/reports/download/${previewFileId}`; });

  // Back Button
  backBtn?.addEventListener('click', () => {
    insideFolder = false;
    currentFolderId = null;
    if(filesTitleLabel) filesTitleLabel.textContent = "SK Report files"; 
    if(quickLabel) quickLabel.style.display = 'block';
    backBtn.style.display = 'none';
    addFolderBtn.innerHTML = `Add Folder <span class="icon-circle"><i class="fas fa-plus"></i></span>`;
    
    renderFolders(); // Re-render to show correct states
    qsa('.folder-card').forEach(c => {
        c.style.display = 'flex';
        c.classList.remove('active'); 
    });
    renderFiles(); 
  });

  // Modal Actions
  addFolderBtn?.addEventListener('click', () => {
    if (insideFolder) { if(qs('#addFileModal')) qs('#addFileModal').style.display = 'flex'; }
    else { if(qs('#addFolderModal')) qs('#addFolderModal').style.display = 'flex'; }
  });

  qs('#cancelAddFolder')?.addEventListener('click', () => qs('#addFolderModal').style.display = 'none');
  qs('#cancelAddFile')?.addEventListener('click', () => qs('#addFileModal').style.display = 'none');

  // Add Folder
  qs('#confirmAddFolder')?.addEventListener('click', () => {
    const name = qs('#folderNameInput').value.trim();
    if (!name) return;
    fetch("{{ route('reports.folder.store') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ name: name })
    }).then(res => res.json()).then(newFolder => {
        folders.push(newFolder);
        renderFolders();
        qs('#addFolderModal').style.display = 'none';
        qs('#folderNameInput').value = '';
    });
  });

  // Add File
  qs('#confirmAddFile')?.addEventListener('click', () => {
    const file = qs('#fileInput').files[0];
    if (!file || !currentFolderId) return;
    const formData = new FormData();
    formData.append('file', file);
    formData.append('folder_id', currentFolderId);
    qs('#confirmAddFile').innerText = "Uploading...";
    fetch("{{ route('reports.upload') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData
    }).then(res => res.json()).then(savedFile => {
        savedFile.uploaded_by = currentUserName; 
        files.push(savedFile);
        renderFiles();
        qs('#addFileModal').style.display = 'none';
        qs('#fileInput').value = '';
        qs('#confirmAddFile').innerText = "Add File";
    }).catch(() => {
        alert("Upload Failed");
        qs('#confirmAddFile').innerText = "Add File";
    });
  });

  // Context Menu
  function showContextMenu(e) {
    if(!contextMenu) return;
    contextMenu.style.display = 'block';
    contextMenu.style.left = `${e.pageX}px`;
    contextMenu.style.top = `${e.pageY}px`;
    
    const dlItem = qs('#downloadItem');
    const arcItem = qs('#archiveItem');
    const backItem = qs('#backupItem');
    const renItem = qs('#renameItem');

    if (selectedItem.type === 'folder') {
        if(dlItem) dlItem.style.display = 'none';
        if(arcItem) arcItem.style.display = 'none';
        if(backItem) backItem.style.display = 'none';
        if(renItem) renItem.style.display = 'block'; 
    } else {
        if(dlItem) dlItem.style.display = 'block';
        if(arcItem) arcItem.style.display = 'block';
        if(backItem) backItem.style.display = 'block';
        if(renItem) renItem.style.display = 'block'; 
    }
  }

  qs('#downloadItem')?.addEventListener('click', () => {
      if(selectedItem.type === 'file') window.location.href = `/reports/download/${selectedItem.id}`;
      contextMenu.style.display = 'none';
  });

  qs('#renameItem')?.addEventListener('click', () => {
      if(renameInput) renameInput.value = selectedItem.name;
      if(renameModal) renameModal.style.display = 'flex';
      contextMenu.style.display = 'none';
  });

  qs('#saveRename')?.addEventListener('click', () => {
      const newName = renameInput.value.trim();
      if(!newName) return;
      fetch(`/reports/rename/${selectedItem.type}/${selectedItem.id}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ name: newName })
      }).then(() => {
          if(selectedItem.type === 'folder') {
              const f = folders.find(x => x.id == selectedItem.id);
              if(f) f.name = newName;
              renderFolders();
          } else {
              const f = files.find(x => x.id == selectedItem.id);
              if(f) f.name = newName;
              renderFiles();
          }
          renameModal.style.display = 'none';
      });
  });

  qs('#cancelRename')?.addEventListener('click', () => renameModal.style.display = 'none');

  qs('#deleteItem')?.addEventListener('click', () => {
    if(deleteModal) deleteModal.style.display = 'flex';
    contextMenu.style.display = 'none';
  });
  qs('#cancelDelete')?.addEventListener('click', () => deleteModal.style.display = 'none');

  qs('#confirmDelete')?.addEventListener('click', () => {
    if (!selectedItem) return;
    fetch(`/reports/${selectedItem.type}/${selectedItem.id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    }).then(res => res.json()).then(() => {
        if (selectedItem.type === 'folder') {
            folders = folders.filter(f => f.id != selectedItem.id);
            files = files.filter(f => f.folder_id != selectedItem.id);
            renderFolders();
            if (insideFolder && currentFolderId == selectedItem.id && backBtn) backBtn.click();
        } else {
            files = files.filter(f => f.id != selectedItem.id);
            renderFiles();
        }
        deleteModal.style.display = 'none';
    });
  });

  window.addEventListener('click', e => {
    if (contextMenu && !contextMenu.contains(e.target)) contextMenu.style.display = 'none';
  });

  // Profile Dropdown
  const profileToggle = qs('#profileToggle');
  const profileWrapper = qs('.profile-wrapper'); 
  if (profileToggle && profileWrapper) {
      profileToggle.addEventListener('click', (e) => { e.stopPropagation(); profileWrapper.classList.toggle('active'); });
      window.addEventListener('click', (e) => { if (!profileWrapper.contains(e.target)) profileWrapper.classList.remove('active'); });
  }
  
  // INITIAL RENDER
  renderFolders();
  renderFiles();
  
});
</script>

</body>
</html>