<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - SK Analytics</title>
   <link rel="stylesheet" href="{{ asset('css/sk-view.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body>
<main class="dashboard-container">

    <header class="topbar">
        <div class="logo">
           <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="KatiBayan Logo" class="logo-img">
             <div class="logo-text">
                <span><span class="blue">K</span>ati<span class="blue">B</span>ayan.</span>
                <small>Katipunan ng Kabataan Web Portal</small>
            </div>
        </div>
        </div>

        <div class="topbar-right">
            <div class="time" id="currentTime">Loading...</div>

            <div class="topbar-icons">
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
                                    <strong>Program Evaluation Due</strong>
                                    <p>The evaluation for the KK-Assembly is due tomorrow.</p>
                                </div>
                                <span class="notif-dot"></span>
                            </li>
                            <li>
                                <div class="notif-icon"></div>
                                <div class="notif-content">
                                    <strong>New Project Proposal</strong>
                                    <p>Kagawad Dela Cruz submitted a new project proposal.</p>
                                </div>
                                <span class="notif-dot"></span>
                            </li>
                            <li>
                                <div class="notif-icon"></div>
                                <div class="notif-content">
                                    <strong>Meeting Reminder</strong>
                                    <p>SK Monthly Meeting is scheduled for Friday at 2 PM.</p>
                                </div>
                            </li>
                             <li>
                                <div class="notif-icon"></div>
                                <div class="notif-content">
                                    <strong>Report Received</strong>
                                    <p>Received Accomplishment Report from Kagawad Santos.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="profile-wrapper">
                    <img src="images/KatiBayan-Logo_B.png" alt="User" class="avatar" id="profileToggle"> <div class="profile-dropdown">
                        <div class="profile-header">
                            <img src="https://via.placeholder.com/55" alt="User" class="profile-avatar"> <div class="profile-info">
                                <h4>Marijoy S. Novora</h4>
                                <div class="profile-badge">
                                    <span class="badge">SK Kagawad</span>
                                    <span class="badge">19 yrs old</span>
                                    
                                </div>
                                <span class="badge-2">Kagawad</span>
                            </div>
                        </div>
                        <hr>
                        <ul class="profile-menu">
                             <li>
                                 <button class="back-to-profile" id="btn-back-profile">Back to Profile</button>
                            </li>
                            <li class="logout-item">
                                <a href="#">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="welcome-section">
        <div class="welcome-text">
            <h1>Marijoy S. Novora</h1>
            <p>19 years old <span class="tag tag-kagawad">SK Kagawad</span></p>
        </div>
         <button class="btn btn-secondary" id="setCommitteeBtn">Edit your committee</button>
        </section>

        <!-- Committee Selection Modal -->
        <div class="modal-overlay" id="committeeModal">
            <div class="committee-modal">
                <div class="modal-header">
                    <h2>Edit Your Committee</h2>
                    <button class="close-modal" id="closeModal">&times;</button>
                </div>
                <div class="modal-content">
                    <div class="modal-section">
                        <p style="color: #252525ff; margin-bottom: 1rem; font-size: 0.9rem;">
                          Select your respective committee.
                        </p>
                        <div class="committee-options">
                            <div class="committee-option" data-committee="health">
                                <input type="checkbox" id="health" name="committees" value="health">
                                <label for="health">Committee on Health</label>
                            </div>
                            <div class="committee-option" data-committee="education">
                                <input type="checkbox" id="education" name="committees" value="education">
                                <label for="education">Committee on Education</label>
                            </div>
                            <div class="committee-option" data-committee="sports">
                                <input type="checkbox" id="sports" name="committees" value="sports">
                                <label for="sports">Committee on Sports</label>
                            </div>
                            <div class="committee-option" data-committee="culture">
                                <input type="checkbox" id="culture" name="committees" value="culture">
                                <label for="culture">Committee on Culture</label>
                            </div>
                            <div class="committee-option" data-committee="environment">
                                <input type="checkbox" id="environment" name="committees" value="environment">
                                <label for="environment">Committee on Environment</label>
                            </div>
                            <div class="committee-option" data-committee="citizenship">
                                <input type="checkbox" id="citizenship" name="committees" value="citizenship">
                                <label for="citizenship">Committee on Active Citizenship</label>
                            </div>
                            <div class="committee-option" data-committee="social">
                                <input type="checkbox" id="social" name="committees" value="social">
                                <label for="social">Committee on Social Inclusion</label>
                            </div>
                            <div class="committee-option" data-committee="finance">
                                <input type="checkbox" id="finance" name="committees" value="finance">
                                <label for="finance">Committee on Finance</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-section">
                        <h3>Current Selection</h3>
                        <div id="selectedCommittees" style="
                            background: #f8f9fa;
                            padding: 1rem;
                            border-radius: 8px;
                            min-height: 60px;
                            border: 2px dashed #ddd;
                        ">
                            <p style="color: #999; margin: 0; font-style: italic;">No committees selected yet</p>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" id="cancelSelection">Cancel</button>
                    <button class="btn btn-primary" id="saveCommittees">Save Committees</button>
                </div>
            </div>
        </div>

    </section>

    <div class="dashboard-grid">
        <div class="grid-col-1">
            <div class="card sk-committee">
                <h2>SK COMMITTEE</h2>
                <ul>
                    <li class="committee-item">
                        <span class="name">MARIJOY S. NOVORA</span>
                        <div class="role-group">
                            <span class="role-tag role-chairperson">SK CHAIRPERSON</span>
                        </div>
                    </li>
                    <li class="members-header">MEMBERS</li>
                    <li class="committee-item">
                        <span class="name">JUAN DELA CRUZ</span>
                        <div class="role-group">
                            <span class="role-tag role-treasurer">SK TREASURER</span>
                        </div>
                    </li>
                    <li class="committee-item">
                        <span class="name">MARIA SANTOS</span>
                        <div class="role-group">
                            <span class="role-tag role-secretary">SK SECRETARY</span>
                        </div>
                    </li>

                    <li class="committee-item">
                        <span class="name">PETER GARCIA</span>
                        <div class="role-group">
                            <span class="role-tag role-kagawad">SK KAGAWAD</span>
                            <span class="committee-role">COMMITTEE ON HEALTH</span>
                            <span class="committee-role">COMMITTEE ON ACTIVE CITIZENSHIP</span>
                        </div>
                    </li>
                    <li class="committee-item">
                        <span class="name">ANNA REYES</span>
                        <div class="role-group">
                            <span class="role-tag role-kagawad">SK KAGAWAD</span>
                            <span class="committee-role">COMMITTEE ON SOCIAL INCLUSION</span>
                        </div>
                    </li>
                     <li class="committee-item">
                        <span class="name">MARK LIM</span>
                        <div class="role-group">
                            <span class="role-tag role-kagawad">SK KAGAWAD</span>
                            <span class="committee-role">COMMITTEE ON EDUCATION</span>
                         </div>
                    </li>
                     <li class="committee-item">
                        <span class="name">ISABELA DAVID</span>
                         <div class="role-group">
                            <span class="role-tag role-kagawad">SK KAGAWAD</span>
                             <span class="committee-role">COMMITTEE ON ENVIRONMENT</span>
                        </div>
                    </li>
                    <li class="committee-item">
                         <span class="name">CARLO FERNANDEZ</span>
                        <div class="role-group">
                            <span class="role-tag role-kagawad">SK KAGAWAD</span>
                            <span class="committee-role">COMMITTEE ON SPORTS DEV</span>
                        </div>
                    </li>
                    <li class="committee-item">
                        <span class="name">SOFIA ANGELES</span>
                        <div class="role-group">
                            <span class="role-tag role-kagawad">SK KAGAWAD</span>
                             <span class="committee-role">COMMITTEE ON CULTURE & ARTS</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="grid-col-2">
            <div class="card send-report">
                <h2>SEND REPORT TO YOUR SK CHAIR</h2>
                <form>
                    <div class="form-group">
                        <label for="report-type">Report Type</label>
                        <select id="report-type" name="report-type">
                            <option value="">Select type of report</option>
                            <option value="accomplishment">Accomplishment Report</option>
                            <option value="financial">Propose Project</option>
                        </select>
                    </div>
                    <div class="form-group file-upload">
                        <label for="file-attach">Attach files</label>
                        <div class="file-input-wrapper">
                            <button type="button" class="file-input-btn">
                                <i class="fas fa-cloud-upload-alt"></i> Choose Files or Drag & Drop
                            </button>
                            <input type="file" id="file-attach" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx">
                        </div>
                        <div class="file-size-warning">Max file size: 10MB per file</div>
                        <div class="file-list" id="fileList">
                            <div class="file-empty-state">No files selected</div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Full Width Propose Project Section -->
    <div class="card propose-project-full">
        <h2>Propose Project</h2>
        <ul>
            <li>
                <div class="project-info">
                    <span class="project-name">Community Pantry Drive</span>
                    <span class="project-status">Active Citizenship</span>
                </div>
                <span class="project-date">Nov 15, 2025</span>
            </li>
             <li>
                <div class="project-info">
                    <span class="project-name">Free Medical Check-up</span>
                     <span class="project-status">Health</span>
                 </div>
                 <span class="project-date">Nov 20, 2025</span>
            </li>
            <li>
                <div class="project-info">
                     <span class="project-name">Basketball League Finals</span>
                    <span class="project-status">Sports</span>
                </div>
                <span class="project-date">Nov 28, 2025</span>
            </li>
            <li>
                <div class="project-info">
                    <span class="project-name">Scholarship Application Opening</span>
                    <span class="project-status">Education</span>
                </div>
                <span class="project-date">Dec 01, 2025</span>
            </li>
             <li>
                <div class="project-info">
                     <span class="project-name">Barangay Christmas Decor Contest</span>
                    <span class="project-status">Culture & Arts</span>
                </div>
                 <span class="project-date">Dec 10, 2025</span>
             </li>
             <li>
                <div class="project-info">
                     <span class="project-name">Coastal Clean-up Drive</span>
                     <span class="project-status">Environment</span>
                 </div>
                 <span class="project-date">Dec 18, 2025</span>
             </li>
        </ul>
    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // === Profile dropdown toggle ===
        const profileToggle = document.getElementById('profileToggle');
        const profileWrapper = document.querySelector('.profile-wrapper');
        const profileDropdown = document.querySelector('.profile-dropdown');

        // === Notifications dropdown toggle ===
        const notifBell = document.querySelector('.notification-wrapper .fa-bell');
        const notifWrapper = document.querySelector('.notification-wrapper');
        const notifDropdown = document.querySelector('.notif-dropdown');

        // Profile dropdown toggle
        profileToggle?.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent click from closing immediately
            profileWrapper.classList.toggle('active');
            // Close notification dropdown if open
            notifWrapper?.classList.remove('active');
        });
        // Prevent clicks inside dropdown from closing it
        profileDropdown?.addEventListener('click', e => e.stopPropagation());

        // Notifications dropdown toggle
        notifBell?.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent click from closing immediately
            notifWrapper.classList.toggle('active');
             // Close profile dropdown if open
            profileWrapper?.classList.remove('active');
        });
         // Prevent clicks inside dropdown from closing it
        notifDropdown?.addEventListener('click', e => e.stopPropagation());

        // === Time auto-update ===
        const timeEl = document.getElementById("currentTime");
        function updateTime() {
            if (!timeEl) return;
            const now = new Date();

            const shortWeekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
            const weekday = shortWeekdays[now.getDay()];

            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0) as 12
            const timeString = `${hours}:${minutes} ${ampm}`;

            // Format: MON 10:00 AM
            timeEl.textContent = `${weekday} ${timeString}`;
        }
        updateTime(); // Run once on load
        setInterval(updateTime, 60000); // Update every minute

        // === File Upload Management ===
        const fileInput = document.getElementById('file-attach');
        const fileListContainer = document.getElementById('fileList'); // Renamed for clarity
        let currentFiles = []; // Use a different variable name

        fileInput?.addEventListener('change', (e) => {
            const newFiles = Array.from(e.target.files);
            // Append new files, avoiding duplicates based on name and size (basic check)
             newFiles.forEach(newFile => {
                if (!currentFiles.some(existingFile => existingFile.name === newFile.name && existingFile.size === newFile.size)) {
                    currentFiles.push(newFile);
                }
             });
            updateFileListUI(fileListContainer, currentFiles);
        });

        function updateFileListUI(container, filesToShow) {
            if (!container) return;
            container.innerHTML = ''; // Clear previous list

            if (filesToShow.length === 0) {
                container.innerHTML = '<div class="file-empty-state">No files selected</div>';
                return;
            }

            filesToShow.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';

                // Format file size
                let fileSize = '';
                if (file.size < 1024) fileSize = file.size + ' bytes';
                else if (file.size < 1048576) fileSize = (file.size / 1024).toFixed(1) + ' KB';
                else fileSize = (file.size / 1048576).toFixed(1) + ' MB';

                fileItem.innerHTML = `
                    <span class="file-name" title="${file.name}">${file.name}</span>
                    <span class="file-size">${fileSize}</span>
                    <span class="file-remove" data-index="${index}" title="Remove file">
                        <i class="fas fa-times"></i>
                    </span>
                `;
                container.appendChild(fileItem);
            });

            // Add remove functionality AFTER creating the elements
            attachRemoveListeners(container, filesToShow);
        }

        function attachRemoveListeners(container, filesArray) {
             container.querySelectorAll('.file-remove').forEach(removeBtn => {
                // Remove previous listener to avoid duplicates if re-rendering
                removeBtn.replaceWith(removeBtn.cloneNode(true));
            });

            // Attach new listeners
            container.querySelectorAll('.file-remove').forEach(removeBtn => {
                removeBtn.addEventListener('click', (e) => {
                    const indexToRemove = parseInt(e.target.closest('.file-remove').dataset.index);
                     if (!isNaN(indexToRemove) && indexToRemove >= 0 && indexToRemove < filesArray.length) {
                        filesArray.splice(indexToRemove, 1); // Remove from the array
                        updateFileListUI(container, filesArray); // Update the UI
                    }
                });
            });
        }

        // === Form Submissions ===
        const reportForm = document.querySelector('.send-report form');
        const reportSuccessMsg = document.getElementById('reportSuccess'); // Renamed for clarity

        reportForm?.addEventListener('submit', (e) => {
            e.preventDefault(); // Stop default form submission
            const reportType = document.getElementById('report-type').value;

            // Basic validation
            if (!reportType) {
                 alert('Please select a report type.');
                 return;
             }
             if (currentFiles.length === 0) {
                 alert('Please attach at least one file.');
                return;
            }

            // Simulate submission (replace with actual AJAX/fetch later)
            console.log('Simulating report submission:');
            console.log('Report Type:', reportType);
            console.log('Files:', currentFiles.map(f => f.name)); // Log file names
             showSuccess(); // Show success message (simulation)
        });

        function showSuccess() {
             reportSuccessMsg.style.display = 'block';

            // Reset form fields
            reportForm.reset(); // Resets select dropdown
            currentFiles = []; // Clear the file array
            updateFileListUI(fileListContainer, currentFiles); // Clear the file list UI

            // Hide the success message after 5 seconds
            setTimeout(() => {
                reportSuccessMsg.style.display = 'none';
            }, 5000);
        }

        // === Close dropdowns when clicking outside ===
        window.addEventListener('click', (e) => {
             // Close profile dropdown if click is outside
            if (!profileWrapper?.contains(e.target)) {
                 profileWrapper?.classList.remove('active');
             }
             // Close notification dropdown if click is outside
             if (!notifWrapper?.contains(e.target)) {
                notifWrapper?.classList.remove('active');
            }
        });

        // === Committee Modal Elements ===
        const setCommitteeBtn = document.getElementById('setCommitteeBtn');
        const committeeModal = document.getElementById('committeeModal');
        const closeModal = document.getElementById('closeModal');
        const cancelSelection = document.getElementById('cancelSelection');
        const saveCommittees = document.getElementById('saveCommittees');
        const committeeOptions = document.querySelectorAll('.committee-option');
        const selectedCommittees = document.getElementById('selectedCommittees');

        // === Committee Modal Functions ===
        setCommitteeBtn?.addEventListener('click', () => {
            committeeModal.classList.add('active');
        });

        closeModal?.addEventListener('click', () => {
            committeeModal.classList.remove('active');
        });

        cancelSelection?.addEventListener('click', () => {
            committeeModal.classList.remove('active');
            resetSelections();
        });

        // Close modal when clicking outside
        committeeModal?.addEventListener('click', (e) => {
            if (e.target === committeeModal) {
                committeeModal.classList.remove('active');
                resetSelections();
            }
        });

        // Committee selection functionality with checkboxes
        committeeOptions.forEach(option => {
            const checkbox = option.querySelector('input[type="checkbox"]');
            
            option.addEventListener('click', (e) => {
                if (e.target !== checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
                
                if (checkbox.checked) {
                    option.classList.add('selected');
                } else {
                    option.classList.remove('selected');
                }
                
                updateSelectedCommittees();
            });

            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    option.classList.add('selected');
                } else {
                    option.classList.remove('selected');
                }
                updateSelectedCommittees();
            });
        });

        function updateSelectedCommittees() {
            const selected = Array.from(committeeOptions)
                .filter(option => option.querySelector('input').checked)
                .map(option => {
                    const label = option.querySelector('label').textContent;
                    return `<div class="selected-committee">${label}</div>`;
                });

            if (selected.length > 0) {
                selectedCommittees.innerHTML = selected.join('');
            } else {
                selectedCommittees.innerHTML = '<p style="color: #999; margin: 0; font-style: italic;">No committees selected yet</p>';
            }
        }

        function resetSelections() {
            committeeOptions.forEach(option => {
                const checkbox = option.querySelector('input[type="checkbox"]');
                checkbox.checked = false;
                option.classList.remove('selected');
            });
            updateSelectedCommittees();
        }

        // Save committees
        saveCommittees?.addEventListener('click', () => {
            const selected = Array.from(committeeOptions)
                .filter(option => option.querySelector('input').checked)
                .map(option => option.querySelector('label').textContent);

            if (selected.length === 0) {
                alert('Please select at least one committee.');
                return;
            }

            // Here you would typically send the data to your backend
            console.log('Selected committees:', selected);
            
            // Show success message
            alert('Committees saved successfully!');
            
            // Close modal
            committeeModal.classList.remove('active');
            
            // You can update the UI here to reflect the selected committees
            // For example, update the user's committee display
        });
    });
</script>
</body>
</html>