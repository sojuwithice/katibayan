<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KatiBayan - Service Offers</title>
<link rel="stylesheet" href="{{ asset('css/sk-services-offer.css') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://unpkg.com/lucide@latest"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

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
<img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="Logo">
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



<main class="content">
<section class="assistance-section">
<div class="assistance-card">
<a href="#" class="edit-assistance-btn" id="editAssistanceBtn" title="Edit Assistance Info">
<i class="fas fa-pen"></i>
</a>


<h2>Need Assistance?</h2>
@if(empty($assistance_description) && empty($assistance_fb_link) && empty($assistance_msgr_link))
<p>
Contact information has not been set up yet. Click the edit icon
<i class="fas fa-pen" style="font-size: 0.8em;"></i> to add details.
</p>
@else

<p>
{{ $assistance_description ?? 'You may contact us on our facebook page or you can directly message the SK Chairman through the link below' }}
</p>
<div class="assistance-links">
@if(!empty($assistance_fb_link))

<div class="link-item">
<i class="fab fa-facebook"></i>
<a href="{{ $assistance_fb_link }}" target="_blank" rel="noopener noreferrer">{{ $assistance_fb_link }}</a>
</div>

@endif
@if(!empty($assistance_msgr_link))

<div class="link-item">
<i class="fab fa-facebook-messenger"></i>
<a href="{{ $assistance_msgr_link }}" target="_blank" rel="noopener noreferrer">{{ $assistance_msgr_link }}</a>
</div>
@endif
</div>
@endif
</div>

</section>
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
<button class="add-btn" id="addServiceBtn">
<i class="fas fa-plus"></i> Add Service Offer
</button>
</div>



<!-- Container for all cards -->
<div class="service-row" id="servicesContainer">
@forelse($services as $service)
<div class="service-card" data-service-id="{{ $service->id }}">
<i class="fas fa-ellipsis-h card-menu"></i>



<!-- Dropdown menu -->

<div class="options-dropdown">
<p class="edit-option" data-service-id="{{ $service->id }}"><i class="fas fa-pen"></i> Edit</p>
<p class="delete-option" data-service-id="{{ $service->id }}"><i class="fas fa-trash"></i> Delete</p>
</div>


<div class="card-header">
<img src="{{ $service->image ? asset('storage/' . $service->image) : asset('images/print.jpeg') }}" alt="{{ $service->title }}">
</div>
<div class="card-body">
<h3>{{ $service->title }}</h3>
<button class="read-more" data-service-id="{{ $service->id }}">Read More</button>
</div>
</div>
@empty
<div class="no-services">
<p>No services available for your barangay yet.</p>
</div>
@endforelse
</div>
</div>
</section>



<section class="org-chart">
    <div class="section-header">
    <h2>Organizational Chart</h2>
    <p>The organizational chart of the Sangguniang Kabataan of {{ $barangayName }} illustrates the structure of its committees and defines the roles and responsibilities of each official.</p>
</div>

    <div class="section-content org-upload">
        <div class="upload-container">
            <div class="upload-box" id="orgUploadBox">
                <div id="orgPreviewContainer" class="preview-column">

    {{-- FIX 2: Check kung hindi empty ang Collection, at gamitin ang @foreach --}}
    @if($organizationalChart->isNotEmpty())
        
        @foreach($organizationalChart as $chart)
            <div class="preview-item hover-overlay" data-id="{{ $chart->id }}">
                
                <img src="{{ asset('storage/' . $chart->image_path) }}" class="preview-img">
                
                <div class="overlay-buttons">
                    
                    {{-- Update button --}}
                    <button type="button" class="update-existing-btn btn-action" title="Update Chart" 
                         onclick="document.getElementById('orgFileInput').click();">
                         Update Image
                    </button>
                    
                </div>
                
            </div>
        @endforeach
        
    @else
        {{-- Placeholder shows when the collection is empty --}}
        <div class="upload-placeholder">
            <i class="fas fa-image fa-3x"></i>
            <p>Drag your photo(s) here or <a href="#" id="browseLink">Browse from device</a></p>
        </div>
    @endif

</div>

<input type="hidden" name="delete_images" id="deleteImagesInput">

<input type="file" id="orgFileInput" accept="image/*" multiple hidden>
            </div>
        </div>
    </div>
</section>




<!-- === SERVICE DETAILS MODAL === -->
<div class="service-modal" id="serviceModal">
<div class="service-modal-content">
<span class="close-modal" id="closeModal">&times;</span>
<div class="modal-header">
<img id="modalServiceImage" src="" alt="Service Poster" class="modal-poster">
</div>


<div class="modal-body">
<h2 id="modalServiceTitle"></h2>
<p id="modalServiceDescription"></p>


<div class="modal-sections">
<div class="modal-section" id="servicesOfferedSection" style="display: none;">
<h3>Services Offered</h3>
<ul id="modalServicesOffered"></ul>
</div>

<div class="modal-section" id="locationSection" style="display: none;">
<h3>Pick-Up Location</h3>
<p id="modalLocation"></p>
</div>



<div class="modal-section" id="howToAvailSection" style="display: none;">
<h3>How to Avail</h3>
<p id="modalHowToAvail"></p>
</div>


<div class="modal-section" id="contactInfoSection" style="display: none;">
<h3>For Assistance</h3>
<p id="modalContactInfo"></p>
</div>
</div>
</div>
</div>
</div>



<!-- === ADD SERVICE OFFER MODAL === -->
<div class="add-service-modal" id="addServiceModal">
<div class="add-service-container">
<button class="close-modal" id="closeAddService">
<i class="fas fa-times"></i>
</button>


<header class="add-header">
<h2>Add Service Offer</h2>
<p>
SK officials can list the services they provide for the youth through this feature.
It helps ensure that programs and opportunities are easily accessible to all members.
</p>

</header>
<form class="add-service-form" id="serviceForm">
@csrf
<input type="hidden" id="editServiceId" name="service_id">


<div class="form-group">
<label>Upload Display</label>
<div class="add-upload-box" id="serviceUploadBox">
<i class="fas fa-image"></i>
<p>Drag your photo here or <span class="browse">Browse from device</span></p>

<input type="file" id="serviceUploadInput" name="image" accept="image/*" hidden>
</div>
<div class="file-name" id="fileName"></div>
</div>


<div class="form-group">
<label for="serviceTitle">Title</label>
<input type="text" id="serviceTitle" name="title" placeholder="Enter service title" required>
</div>



<div class="form-group">
<label for="serviceDescription">Description</label>
<textarea id="serviceDescription" name="description" rows="4" placeholder="Describe the service..." required></textarea>
</div>



<div class="form-group">
<label for="servicesOffered">Services Offered (comma separated)</label>
<input type="text" id="servicesOffered" name="services_offered" placeholder="e.g., Printing, Scanning, Copying">
</div>



<div class="form-group">
<label for="serviceLocation">Location</label>
<input type="text" id="serviceLocation" name="location" placeholder="Enter service location">
</div>
<div class="form-group">
<label for="howToAvail">How to Avail</label>
<textarea id="howToAvail" name="how_to_avail" rows="3" placeholder="Instructions on how to avail the service..."></textarea>
</div>


<div class="form-group">
<label for="contactInfo">Contact Information</label>
<textarea id="contactInfo" name="contact_info" rows="3" placeholder="Contact details for assistance..."></textarea>
</div>

<div class="post-btn-container">
<button type="submit" class="post-btn" id="submitServiceBtn">Post Service</button>
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


<div class="add-service-modal" id="assistanceModal">
<div class="add-service-container">
<button class="close-modal" id="closeAssistanceModal">
<i class="fas fa-times"></i>
</button>



<header class="add-header">
<h2>Update Assistance Info</h2>
<p>
Edit the contact links and description for the 'Need Assistance' card.
</p>
</header>


<form class="add-service-form" id="assistanceForm">
@csrf
@method('POST')
<div class="form-group">
<label for="assistanceDescription">Description</label>
<textarea id="assistanceDescription" name="assistance_description" rows="4" placeholder="e.g., You may contact us on our facebook page...">{{ $assistance_description ?? '' }}</textarea>
</div>



<div class="form-group">
<label for="assistanceFbLink">Facebook Page Link</label>
<input type="text" id="assistanceFbLink" name="assistance_fb_link"
placeholder="e.g., https://www.facebook.com/yourpage"
value="{{ $assistance_fb_link ?? '' }}">
</div>



<div class="form-group">
<label for="assistanceMsgrLink">Messenger Link</label>
<input type="text" id="assistanceMsgrLink" name="assistance_msgr_link"
placeholder="e.g., https://m.me/yourpage"
value="{{ $assistance_msgr_link ?? '' }}">
</div>
<div class="post-btn-container">
<button type="submit" class="post-btn" id="submitAssistanceBtn">Save Changes</button>
</div>
</form>
</div>
</div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      
      // === START: VARIABLE DEFINITIONS ===
      
      // CSRF Token
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
      
      // Basic UI
      const menuToggle = document.querySelector('.menu-toggle');
      const sidebar = document.querySelector('.sidebar');
      const submenuTriggers = document.querySelectorAll('.nav-item > .nav-link');
      const timeEl = document.querySelector(".time");
      const notifWrapper = document.querySelector(".notification-wrapper");
      const profileWrapper = document.querySelector(".profile-wrapper");

      // Service Cards
      const cardMenus = document.querySelectorAll('.card-menu');
      
      // Modals
      const serviceModal = document.getElementById("serviceModal");
      const addServiceModal = document.getElementById("addServiceModal");
      const deleteModal = document.getElementById("deleteModal");
      const confirmModal = document.getElementById("confirmModal");
      const assistanceModal = document.getElementById("assistanceModal"); 
      const closeAssistanceModal = document.getElementById("closeAssistanceModal"); 
      
      // Forms
      const assistanceForm = document.getElementById("assistanceForm"); 
      const serviceForm = document.getElementById("serviceForm");
      
      // File Uploads
      const serviceUploadBox = document.getElementById("serviceUploadBox");
      const serviceFileInput = document.getElementById("serviceUploadInput");
      const orgUploadBox = document.getElementById("orgUploadBox");
      const orgFileInput = document.getElementById("orgFileInput");
      const orgBrowseLink = document.getElementById("browseLink");
      
      // === END: VARIABLE DEFINITIONS ===


      // === START: INITIALIZERS & CORE UI LISTENERS ===

      // Initialize Icons
      lucide.createIcons();
      
      // Time Update
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

      // Sidebar Toggle
      if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('open');
        });
      }

      // Submenus
      submenuTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
          e.preventDefault();
          const parentItem = trigger.closest('.nav-item');
          const wasOpen = parentItem.classList.contains('open');
          document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('open'));
          if (!wasOpen) parentItem.classList.add('open');
        });
      });

      // Notifications & Profile Dropdowns
      if (notifWrapper) {
        const bell = notifWrapper.querySelector(".fa-bell");
        if (bell) {
          bell.addEventListener("click", (e) => {
            e.stopPropagation();
            notifWrapper.classList.toggle("active");
            profileWrapper?.classList.remove("active");
          });
        }
      }
      if (profileWrapper) {
        const profileToggle = document.getElementById("profileToggle");
        if (profileToggle) {
          profileToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            profileWrapper.classList.toggle("active");
            notifWrapper?.classList.remove("active");
          });
        }
      }

      // Global click listener (to close dropdowns)
      document.addEventListener("click", () => {
        if (profileWrapper) profileWrapper.classList.remove('active');
        if (notifWrapper) notifWrapper.classList.remove('active');
        document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
      });
      
      // === END: INITIALIZERS & CORE UI LISTENERS ===


      // === START: EVENT LISTENERS ===

      // Service Card Listeners
      cardMenus.forEach(menu => {
        menu.addEventListener('click', (e) => {
          e.stopPropagation();
          document.querySelectorAll('.options-dropdown').forEach(drop => drop.classList.remove('show'));
          const dropdown = menu.nextElementSibling;
          if (dropdown && dropdown.classList.contains('options-dropdown')) {
            dropdown.classList.toggle('show');
          }
        });
      });

      document.querySelectorAll('.read-more').forEach(btn => {
        btn.addEventListener('click', async (e) => {
          const serviceId = e.target.dataset.serviceId;
          await loadServiceDetails(serviceId);
        });
      });

      document.querySelectorAll('.edit-option').forEach(btn => {
        btn.addEventListener('click', async (e) => {
          const serviceId = e.target.closest('.edit-option').dataset.serviceId;
          await loadServiceForEdit(serviceId);
        });
      });

      document.querySelectorAll('.delete-option').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const serviceId = e.target.closest('.delete-option').dataset.serviceId;
          showDeleteModal(serviceId);
        });
      });

      // Modal Close Listeners
      document.getElementById("closeModal")?.addEventListener("click", () => serviceModal.style.display = "none");
      document.getElementById("closeAddService")?.addEventListener("click", () => addServiceModal.style.display = "none");
      document.getElementById("cancelDelete")?.addEventListener("click", () => deleteModal.style.display = "none");
      document.getElementById("confirmCancel")?.addEventListener("click", () => confirmModal.classList.remove("active"));
      closeAssistanceModal?.addEventListener("click", () => assistanceModal.style.display = "none"); 

      // Window click listener (to close modals)
      window.addEventListener("click", (e) => {
        if (e.target === serviceModal) serviceModal.style.display = "none";
        if (e.target === addServiceModal) addServiceModal.style.display = "none";
        if (e.target === deleteModal) deleteModal.style.display = "none";
        if (e.target === confirmModal) confirmModal.classList.remove("active");
        if (e.target === assistanceModal) assistanceModal.style.display = "none"; 
      });

      // "Add Service" Button
      document.getElementById("addServiceBtn")?.addEventListener("click", () => {
        resetServiceForm();
        addServiceModal.style.display = "flex";
      });

      // "Edit Assistance" Button
      document.getElementById("editAssistanceBtn")?.addEventListener("click", (e) => {
        e.preventDefault();
        assistanceModal.style.display = "flex";
      });
      
      // Form Submit Listeners
      serviceForm?.addEventListener("submit", handleServiceSubmit);
      assistanceForm?.addEventListener("submit", handleAssistanceSubmit);

      // File Upload Listeners
      serviceUploadBox?.addEventListener("click", () => serviceFileInput?.click());
      serviceFileInput?.addEventListener("change", handleImagePreview);
      
      // === ORG CHART LISTENERS FIX ===
      orgBrowseLink?.addEventListener("click", (e) => {
        e.preventDefault();
        orgFileInput?.click();
      });
      
      // I-trigger ang click kung ang user ay nag-click sa placeholder o sa 'Update' button
      orgUploadBox?.addEventListener("click", (e) => {
        const target = e.target;
        const isPlaceholder = orgUploadBox.querySelector('.upload-placeholder');
        const isUpdateBtn = target.classList.contains('update-btn');
        
        if (isPlaceholder || isUpdateBtn) {
            orgFileInput?.click();
        }
      });
      
      orgFileInput?.addEventListener("change", () => {
        const files = orgFileInput.files; 
        if (files.length === 0) return;
        
        // Gumawa ng temporary container para sa bagong content
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'relative';
        tempContainer.style.width = '100%';
        tempContainer.style.height = '100%';
        
        let updateBtnText = `Update ${files.length} Image${files.length > 1 ? 's' : ''}`;
        
        if (files.length === 1) {
            // Case 1: Single file upload - Show image preview
            const file = files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                tempContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Organizational Chart Preview" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                    <div class="update-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
                        <button class="update-btn" style="color: white; border: 1px solid white; padding: 10px 20px;">${updateBtnText}</button>
                    </div>
                `;
                orgUploadBox.innerHTML = ''; // Linisin ang existing content
                orgUploadBox.appendChild(tempContainer);
                confirmModal.classList.add("active");
            };
            reader.readAsDataURL(file);
        } else {
            // Case 2: Multiple files upload - Show list preview
            let previewHtml = '';
            previewHtml += '<div class="multi-file-preview" style="padding: 15px; color: #fff; max-height: 100%; overflow-y: auto;">';
            previewHtml += `<p><strong>${files.length} file(s) selected:</strong></p><ul style="list-style-type: disc; padding-left: 20px; text-align: left;">`;
            for (let i = 0; i < files.length && i < 5; i++) { // Limitahan sa 5 files para sa preview
                previewHtml += `<li style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">${files[i].name}</li>`;
            }
            if (files.length > 5) {
                 previewHtml += `<li>...and ${files.length - 5} more.</li>`;
            }
            previewHtml += '</ul></div>';
            
            tempContainer.innerHTML = previewHtml + `
                <div class="update-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                    <div style="max-height: 60%; overflow-y: auto; padding: 10px;">
                        ${previewHtml}
                    </div>
                    <button class="update-btn" style="color: white; border: 1px solid white; padding: 10px 20px; margin-top: 15px;">${updateBtnText}</button>
                </div>
            `;
            orgUploadBox.innerHTML = ''; // Linisin ang existing content
            orgUploadBox.appendChild(tempContainer);
            confirmModal.classList.add("active");
        }
      });
      
      document.getElementById("confirmYes")?.addEventListener("click", uploadOrganizationalChart);

      // === END: EVENT LISTENERS ===


      // === START: FUNCTION DEFINITIONS ===

      // --- Assistance Form ---
async function handleAssistanceSubmit(e) {
    e.preventDefault();
    
    // Kunin ang mga value galing sa form fields
    const description = document.getElementById('assistanceDescription').value;
    const fbLink = document.getElementById('assistanceFbLink').value;
    const msgrLink = document.getElementById('assistanceMsgrLink').value;

    const formData = new FormData();
    // Tiyakin na ang names ng appended data ay katulad ng sa HTML form at sa Controller
    formData.append('_token', csrfToken);
    formData.append('assistance_description', description); 
    formData.append('assistance_fb_link', fbLink);           
    formData.append('assistance_msgr_link', msgrLink);       

    const updateUrl = "{{ route('sk.assistance.update') }}"; // Tiyaking tama ang route name

    try {
      const response = await fetch(updateUrl, {
        method: 'POST',
        body: formData,
        // Dito natin inalis ang Content-Type dahil ginagamit natin ang FormData para sa text at files (kung sakali)
        // Ang browser na ang bahalang mag-set ng tamang boundary sa Content-Type.
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message || 'Assistance info updated successfully!');
        assistanceModal.style.display = "none";
        // Gumawa ng hard reload para siguradong ma-refresh ang data
        location.reload(true); 
        
      } else {
        if(result.errors) {
          let errorMsg = "Please check your inputs:\n";
          for(const key in result.errors) {
            errorMsg += `- ${result.errors[key].join(', ')}\n`;
          }
          alert(errorMsg);
        } else {
          alert('Error: ' + (result.message || 'Something went wrong'));
        }
      }
    } catch (error) {
      console.error('Error updating assistance info:', error);
      alert('An error occurred while submitting the form.');
    }
}

      // --- Service Details ---
      async function loadServiceDetails(serviceId) {
        try {
          const response = await fetch(`/services/${serviceId}/details`);
          const data = await response.json();
          
          if (data.success) {
            const service = data.service;
            document.getElementById('modalServiceTitle').textContent = service.title;
            document.getElementById('modalServiceDescription').textContent = service.description;
            document.getElementById('modalServiceImage').src = service.image ? 
              `/storage/${service.image}` : '/images/print.jpeg';

            toggleSection('servicesOfferedSection', service.services_offered);
            toggleSection('locationSection', service.location);
            toggleSection('howToAvailSection', service.how_to_avail);
            toggleSection('contactInfoSection', service.contact_info);

            if (service.services_offered) {
              const servicesList = document.getElementById('modalServicesOffered');
              servicesList.innerHTML = '';
              try {
                const servicesArray = JSON.parse(service.services_offered);
                if (Array.isArray(servicesArray)) {
                  servicesArray.forEach(serviceItem => {
                    const li = document.createElement('li');
                    li.textContent = serviceItem;
                    servicesList.appendChild(li);
                  });
                }
              } catch (e) {
                console.error('Error parsing services offered:', e);
              }
            }

            document.getElementById('modalLocation').textContent = service.location || '';
            document.getElementById('modalHowToAvail').textContent = service.how_to_avail || '';
            document.getElementById('modalContactInfo').textContent = service.contact_info || '';

            serviceModal.style.display = "flex";
          }
        } catch (error) {
          console.error('Error loading service details:', error);
          alert('Error loading service details');
        }
      }

      function toggleSection(sectionId, data) {
        const section = document.getElementById(sectionId);
        if (section) {
          section.style.display = data ? 'block' : 'none';
        }
      }

      // --- Service Form ---
      async function loadServiceForEdit(serviceId) {
        try {
          const response = await fetch(`/services/${serviceId}/details`);
          const data = await response.json();
          
          if (data.success) {
            const service = data.service;
            document.getElementById('editServiceId').value = service.id;
            document.getElementById('serviceTitle').value = service.title;
            document.getElementById('serviceDescription').value = service.description;
            document.getElementById('servicesOffered').value = service.services_offered ? 
              JSON.parse(service.services_offered).join(', ') : '';
            document.getElementById('serviceLocation').value = service.location || '';
            document.getElementById('howToAvail').value = service.how_to_avail || '';
            document.getElementById('contactInfo').value = service.contact_info || '';
            document.getElementById('submitServiceBtn').textContent = 'Update Service';

            if (service.image) {
              serviceUploadBox.innerHTML = `
                <img src="/storage/${service.image}" 
                     alt="Preview" 
                     style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
              `;
            }

            addServiceModal.style.display = "flex";
          }
        } catch (error) {
          console.error('Error loading service for edit:', error);
          alert('Error loading service for editing');
        }
      }

      function resetServiceForm() {
        document.getElementById('serviceForm').reset();
        document.getElementById('editServiceId').value = '';
        document.getElementById('submitServiceBtn').textContent = 'Post Service';
        serviceUploadBox.innerHTML = `
          <i class="fas fa-image"></i>
          <p>Drag your photo here or <span class="browse">Browse from device</span></p>
        `;
      }

      function handleImagePreview() {
        const file = serviceFileInput.files[0];
        if (file) {
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
      }

        async function handleServiceSubmit(e) {
          e.preventDefault();
          
          const serviceId = document.getElementById('editServiceId').value;
          const isEditing = !!serviceId; 

          const formData = new FormData(serviceForm);

          const servicesOfferedInput = document.getElementById('servicesOffered').value;
          if (servicesOfferedInput) {
              const servicesArray = servicesOfferedInput.split(',').map(s => s.trim()).filter(s => s.length > 0);
              formData.set('services_offered', JSON.stringify(servicesArray));
          } else {
              formData.set('services_offered', '[]');
          }

          const url = isEditing ? `/services/${serviceId}` : '/services';
          const method = 'POST';

          if (isEditing) {
              formData.append('_method', 'PUT'); 
          }
          
          const imageFile = serviceFileInput.files[0];
          if (imageFile) {
            formData.set('image', imageFile); 
          }


          try {
            const response = await fetch(url, {
              method: method, 
              body: formData,
            });

            const result = await response.json();

            if (result.success) {
              alert(result.message || (isEditing ? 'Service updated successfully!' : 'Service added successfully!'));
              addServiceModal.style.display = "none";
              location.reload();
            } else {
              alert('Error: ' + (result.message || 'Something went wrong'));
            }
          } catch (error) {
            console.error('Error submitting service:', error);
            alert('Error submitting service');
          }
        }

        // --- Service Deletion ---
        function showDeleteModal(serviceId) {
          deleteModal.style.display = "flex";
          document.getElementById('confirmDelete').onclick = () => deleteService(serviceId);
        }

        async function deleteService(serviceId) {
          try {
            const response = await fetch(`/services/${serviceId}`, {
              method: 'POST', 
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json' 
              },
              body: JSON.stringify({
                  _method: 'DELETE', 
                  _token: csrfToken 
              })
            });

            const result = await response.json();

            if (result.success) {
              alert(result.message || 'Service deleted successfully.');
              deleteModal.style.display = "none";
              document.querySelector(`.service-card[data-service-id="${serviceId}"]`)?.remove();
              
              if (document.querySelectorAll('.service-card').length === 0) {
                document.getElementById('servicesContainer').innerHTML = '<div class="no-services"><p>No services available for your barangay yet.</p></div>';
              }
            } else {
              alert('Error: ' + (result.message || 'Something went wrong'));
            }
          } catch (error) {
            console.error('Error deleting service:', error);
            alert('Error deleting service');
          }
        }

      // --- Org Chart FIX with better error handling ---
      async function uploadOrganizationalChart() {
        const files = orgFileInput.files; 
        if (files.length === 0) return;

        const formData = new FormData();
        formData.append('_token', csrfToken);
        
        // I-append ang lahat ng files gamit ang array notation
        for (let i = 0; i < files.length; i++) {
            formData.append('chart_images[]', files[i]); 
        }

        try {
          const response = await fetch('/organizational-chart', {
            method: 'POST',
            body: formData,
          });
          
          // CRITICAL: Check for non-200 status codes (like 419 CSRF error or 422 Validation error)
          if (!response.ok) {
              const errorText = await response.text();
              console.error("Server Error Response:", errorText);

              try {
                  const errorJson = JSON.parse(errorText);
                  let errorMessage = 'Upload failed: ' + (errorJson.message || 'Server returned an error.');
                  
                  // Check for Laravel validation errors
                  if (errorJson.errors) {
                    errorMessage += "\n\nValidation Errors:";
                    for(const key in errorJson.errors) {
                      errorMessage += `\n- ${errorJson.errors[key].join(', ')}`;
                    }
                  }
                  alert(errorMessage);
              } catch (e) {
                  alert(`Upload failed (Status: ${response.status}). Check console for raw server response.`);
              }
              confirmModal.classList.remove("active");
              return;
          }

          const result = await response.json();

          if (result.success) {
            alert(result.message || (files.length > 1 ? 'Organizational Charts successfully uploaded.' : 'Organizational Chart successfully uploaded.'));
            confirmModal.classList.remove("active");
            location.reload(); 
          } else {
            // Handle success: false, but status OK (e.g., custom error message from controller)
            alert('Upload failed: ' + (result.message || 'Something went wrong on the server side (success: false).'));
            confirmModal.classList.remove("active");
          }
        } catch (error) {
          console.error('Error uploading organizational chart:', error);
          alert('An internal network error occurred during upload. Check console for details.');
          confirmModal.classList.remove("active");
        }
      }

      // --- Logout ---
      window.confirmLogout = function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      };
      
      
    });
  </script>
</body>
</html>