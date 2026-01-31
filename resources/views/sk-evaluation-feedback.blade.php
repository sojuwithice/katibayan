<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/sk-eval.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <a href="{{ route('sk-evaluation-feedback') }}" class="active">
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
          <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
               alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                   alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ $user->given_name }} {{ $user->middle_name ?? '' }} {{ $user->last_name }} {{ $user->suffix ?? '' }}</h4>
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

    <main class="container">
      <!-- Evaluation Section -->
      <section class="evaluation-section">
        <div class="evaluation-header">
          <h2>Evaluation and Feedback</h2>
          <p>The evaluation and feedback of the youth in accomplished events and programs will help you improve and generate new ideas for future activities.</p>
        </div>

        <div class="accomplished-events">
  <div class="accomplished-header">
    <h3>Accomplished Events and Program</h3>
    <button class="edit-eval-btn" id="openEvalModal">
      <i class="fas fa-pen"></i> Edit Evaluation Form
    </button>
  </div>
  <p class="subtitle">Choose an accomplished event or program to see the results.</p>


          <div class="accomplishment-list">
            @if($eventsWithEvaluations->count() > 0)
              @foreach($eventsWithEvaluations as $event)
                @php
                  // Calculate average rating for this event
                  $averageRating = $event->evaluations->avg(function($eval) {
                    $ratings = json_decode($eval->ratings, true);
                    return $ratings ? array_sum($ratings) / count($ratings) : 0;
                  });
                  
                  // Get latest evaluation for preview
                  $latestEvaluation = $event->evaluations->first();
                  $latestComment = $latestEvaluation ? $latestEvaluation->comments : null;
                @endphp

                <div class="accomplishment-card">
                  <!-- Date -->
                  <div class="accomplishment-date">
                    <span class="day">{{ $event->event_date->format('M') }}</span>
                    <span class="num">{{ $event->event_date->format('d') }}</span>
                  </div>

                  <!-- Details -->
                  <div class="accomplishment-details">
                    <h3>{{ $event->title }}</h3>
                    <p><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                    <p><i class="fas fa-users"></i> {{ $event->evaluations_count }} Evaluations</p>
                    
                    <!-- Rating -->
                    <div class="event-rating">
                      <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= round($averageRating))
                            <i class="fas fa-star"></i>
                          @else
                            <i class="far fa-star"></i>
                          @endif
                        @endfor
                        <span>({{ number_format($averageRating, 1) }})</span>
                      </div>
                    </div>

                    <!-- Latest Comment Preview -->
                    @if($latestComment)
                      <div class="latest-comment">
                        <strong>Latest Feedback:</strong>
                        <p>{{ Str::limit($latestComment, 100) }}</p>
                      </div>
                    @endif

                    <!-- Date & Time -->
                    <div class="accomplishment-datetime">
                      <span class="datetime-label">DATE AND TIME</span>
                      <span class="datetime-value">{{ $event->event_date->format('F d, Y') }} | {{ $event->event_time ?? 'Time not specified' }}</span>
                    </div>
                  </div>

                  <!-- Action -->
                  <div class="accomplishment-action">
                    <a href="{{ route('sk-eval-review', ['event_id' => $event->id]) }}" class="view-btn">
                      View Evaluation <i class="fa-solid fa-arrow-up-right"></i>
                    </a>
                  </div>
                </div>
              @endforeach
            @else
              <div class="no-evaluations">
                <i class="fas fa-clipboard-list"></i>
                <h4>No Evaluations Yet</h4>
                <p>Evaluations will appear here once users submit feedback for events in your barangay.</p>
              </div>
            @endif
          </div>
        </div>
      </section>
    </main>
  </div>


<!-- Edit Evaluation Modal -->
<div id="editEvaluationModal" class="modal">
  <div class="modal-content">

    <!-- Close Button -->
    <span class="close-btn" id="closeEvalModal">&times;</span>

    <!-- Modal Header -->
    <h2>Edit Evaluation Form</h2>
    <p class="instruction">
      Modify the evaluation questions. All questions can be edited and deleted.
    </p>

    <!-- Action Buttons -->
    <div class="action-buttons-wrapper">
      <button type="button" id="addQuestionBtn" class="add-question-btn">
        <i class="fas fa-plus"></i> Add Question
      </button>
      
      <!-- Refresh/Restore Default Button -->
      <button type="button" id="refreshDefaultBtn" class="refresh-default-btn">
        <i class="fas fa-redo"></i> Restore Default Questions
      </button>
    </div>

    <!-- Questions List -->
    <div class="questions" id="questionsContainer">
      @foreach($questions as $index => $question)
        @php
          $isDefault = $question->is_default ?? false;
        @endphp
        <div class="question" data-id="{{ $question->id }}" data-is-default="{{ $isDefault ? 'true' : 'false' }}">
          <label>Question {{ $index + 1 }}</label>
          
          <div class="question-input-wrapper">
            <input type="text"
                   class="question-input"
                   name="question_{{ $question->id }}"
                   value="{{ $question->question_text }}">
            
            @if($isDefault)
              <span class="default-tag">Default Question</span>
            @endif
            
            <button type="button" class="remove-question-btn" data-id="{{ $question->id }}" data-is-default="{{ $isDefault ? 'true' : 'false' }}">
              <i class="fas fa-trash"></i>
            </button>
          </div>

          @if($isDefault)
            <small class="text-info">
              <i class="fas fa-info-circle"></i> Default question
            </small>
          @endif
        </div>
      @endforeach
    </div>

    <!-- Actions -->
    <div class="actions">
      <button class="cancel-btn" id="cancelEvalEdit">Cancel</button>
      <button class="save-btn" id="saveEvalQuestions">Save Changes</button>
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

        // Close options dropdown when clicking outside
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

      // === Logout Confirmation ===
      function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
          document.getElementById('logout-form').submit();
        }
      }

      const openEvalModal = document.getElementById('openEvalModal');
const editModal = document.getElementById('editEvaluationModal');
const closeEvalModal = document.getElementById('closeEvalModal');
const cancelEvalEdit = document.getElementById('cancelEvalEdit');
const addQuestionBtn = document.getElementById('addQuestionBtn');
const saveEvalQuestions = document.getElementById('saveEvalQuestions');
const refreshDefaultBtn = document.getElementById('refreshDefaultBtn'); // Added

// Check if we're on SK side (has edit modal)
const isSkSide = !!editModal;

// Open / Close modal
if (openEvalModal && editModal) {
    openEvalModal.addEventListener('click', () => {
        editModal.style.display = 'block';
        if (isSkSide) {
            loadCurrentQuestions();
        }
    });

    if (closeEvalModal) {
        closeEvalModal.addEventListener('click', () => closeEditModal());
    }
    
    if (cancelEvalEdit) {
        cancelEvalEdit.addEventListener('click', () => closeEditModal());
    }

    window.addEventListener('click', e => { 
        if (e.target === editModal) closeEditModal(); 
    });
}

function closeEditModal() {
    if (editModal) {
        editModal.style.display = 'none';
    }
}

// Load current questions from database (for SK side)
function loadCurrentQuestions() {
    if (!isSkSide) return;
    
    fetch('/sk/evaluation/questions/list')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(questions => {
            const container = document.getElementById('questionsContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            questions.forEach((question, index) => {
                const questionDiv = createQuestionElement(question, index);
                container.appendChild(questionDiv);
            });
            
            // Attach remove event listeners
            attachRemoveListeners();
            
            // Enable/disable save button
            updateSaveButtonState();
        })
        .catch(error => {
            console.error('Error loading questions:', error);
            alert('Failed to load questions. Please try again.');
        });
}

// Create question HTML element
function createQuestionElement(question, index) {
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question';
    questionDiv.dataset.id = question.id;
    questionDiv.dataset.isDefault = question.is_default ? 'true' : 'false';
    
    const isDefault = question.is_default || false;
    
    let html = `
        <label>Question ${index + 1}</label>
        <div class="question-input-wrapper">
            <input type="text" 
                   class="question-input" 
                   name="question_${question.id}" 
                   value="${escapeHtml(question.question_text)}">
    `;
    
    if (isDefault) {
        html += `<span class="default-tag">Default Question</span>`;
    }
    
    // Remove button for ALL questions
    html += `<button type="button" class="remove-question-btn" data-id="${question.id}" data-is-default="${isDefault}">
               <i class="fas fa-trash"></i>
             </button>`;
    
    html += `</div>`;
    
    if (isDefault) {
        html += `<small class="text-info">
                   <i class="fas fa-info-circle"></i> Default question
                 </small>`;
    }
    
    questionDiv.innerHTML = html;
    return questionDiv;
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Add new question dynamically
if (addQuestionBtn) {
    addQuestionBtn.addEventListener('click', () => {
        const container = document.getElementById('questionsContainer');
        if (!container) return;
        
        const questionCount = container.querySelectorAll('.question').length + 1;
        const tempId = `new-${Date.now()}`;

        const newQuestionDiv = document.createElement('div');
        newQuestionDiv.className = 'question';
        newQuestionDiv.dataset.id = tempId;
        newQuestionDiv.dataset.isDefault = 'false';
        
        newQuestionDiv.innerHTML = `
            <label>Question ${questionCount}</label>
            <div class="question-input-wrapper">
                <input type="text" class="question-input" name="${tempId}" value="" placeholder="Enter question text..." required>
                <button type="button" class="remove-question-btn" data-temp-id="${tempId}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <small class="text-info">
                <i class="fas fa-plus-circle"></i> New question
            </small>
        `;

        container.appendChild(newQuestionDiv);
        
        // Focus on the new input
        const newInput = newQuestionDiv.querySelector('.question-input');
        newInput.focus();
        
        // Attach input event to update save button state when user types
        newInput.addEventListener('input', function() {
            updateSaveButtonState();
        });
        
        // Attach remove event to new question
        newQuestionDiv.querySelector('.remove-question-btn').addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this question?')) {
                newQuestionDiv.remove();
                updateQuestionLabels();
                updateSaveButtonState();
            }
        });
        
        updateQuestionLabels();
        updateSaveButtonState();
    });
}

// Restore default questions
if (refreshDefaultBtn) {
    refreshDefaultBtn.addEventListener('click', function() {
        if (confirm('This will restore all default questions and remove any custom questions. Current changes will be lost. Continue?')) {
            restoreDefaultQuestions();
        }
    });
}

// Sa restoreDefaultQuestions function
function restoreDefaultQuestions() {
    // Show loading state
    refreshDefaultBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Restoring...';
    refreshDefaultBtn.disabled = true;

    fetch('/sk/evaluation/questions/restore-default', {
        method: 'PUT', // Changed from POST to PUT
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Reload the questions list
            loadCurrentQuestions();
            alert('Default questions restored successfully!');
        } else {
            throw new Error(data.error || 'Failed to restore default questions');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to restore default questions: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        refreshDefaultBtn.innerHTML = '<i class="fas fa-redo"></i> Restore Default Questions';
        refreshDefaultBtn.disabled = false;
    });
}

// Attach remove event listeners
function attachRemoveListeners() {
    // For existing questions with IDs
    document.querySelectorAll('.remove-question-btn[data-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const questionId = this.dataset.id;
            const questionDiv = this.closest('.question');
            const isDefault = this.dataset.isDefault === 'true';
            
            let confirmMessage = isDefault 
                ? 'This is a default question. Are you sure you want to remove it?'
                : 'Are you sure you want to remove this question?';
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            deleteQuestion(questionId, questionDiv);
        });
    });
    
    // For temporary questions
    document.querySelectorAll('.remove-question-btn[data-temp-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const tempId = this.dataset.tempId;
            const questionDiv = this.closest('.question');
            
            if (confirm('Are you sure you want to remove this question?')) {
                questionDiv.remove();
                updateQuestionLabels();
                updateSaveButtonState();
            }
        });
    });
}

// Delete question via API
function deleteQuestion(questionId, questionDiv) {
    fetch(`/sk/evaluation/questions/${questionId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.error || 'Failed to delete question');
            });
        }
    })
    .then(data => {
        if (data.success) {
            questionDiv.remove();
            updateQuestionLabels();
            updateSaveButtonState();
        } else {
            throw new Error(data.error || 'Failed to delete question');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Failed to delete question.');
    });
}

// Update question labels after add/remove
function updateQuestionLabels() {
    const questions = document.querySelectorAll('#questionsContainer .question');
    questions.forEach((q, idx) => {
        const label = q.querySelector('label');
        if (label) label.textContent = `Question ${idx + 1}`;
    });
}

// Update save button state based on form validity
function updateSaveButtonState() {
    if (!saveEvalQuestions) return;
    
    const questions = document.querySelectorAll('#questionsContainer .question');
    
    // Enable save button if there are any questions
    saveEvalQuestions.disabled = questions.length === 0;
    
    // Optional: Add title/tooltip for better UX
    if (saveEvalQuestions.disabled) {
        saveEvalQuestions.title = "No questions to save";
    } else {
        saveEvalQuestions.title = "";
    }
}

// Save all questions
if (saveEvalQuestions) {
    saveEvalQuestions.addEventListener('click', async () => {
        const questionDivs = document.querySelectorAll('#questionsContainer .question');
        const payload = [];
        let firstEmptyQuestionIndex = -1;
        
        console.log('Total questions found:', questionDivs.length);
        
        // Validate all questions first
        for (let i = 0; i < questionDivs.length; i++) {
            const div = questionDivs[i];
            const input = div.querySelector('.question-input');
            
            if (!input) {
                console.warn('No input found for question at index', i);
                continue;
            }
            
            const questionText = input.value.trim();
            const isDefault = div.dataset.isDefault === 'true';
            
            console.log(`Question ${i + 1}:`, {
                id: div.dataset.id,
                text: questionText,
                isDefault: isDefault
            });
            
            const questionData = {
                id: div.dataset.id,
                question_text: questionText,
                is_default: isDefault,
                order: i + 1
            };
            
            // Check for empty questions
            if (!questionText) {
                if (firstEmptyQuestionIndex === -1) {
                    firstEmptyQuestionIndex = i + 1;
                }
            }
            
            payload.push(questionData);
        }

        // Check if there are empty questions
        if (firstEmptyQuestionIndex !== -1) {
            alert(`Question ${firstEmptyQuestionIndex} cannot be empty!`);
            const emptyInput = questionDivs[firstEmptyQuestionIndex - 1].querySelector('.question-input');
            if (emptyInput) {
                emptyInput.focus();
                emptyInput.classList.add('error-border');
                setTimeout(() => emptyInput.classList.remove('error-border'), 2000);
            }
            return;
        }

        // Validate at least one question exists
        if (payload.length === 0) {
            alert('Evaluation form must have at least one question!');
            return;
        }

        console.log('Payload to send:', payload);

        // Show loading state
        const originalText = saveEvalQuestions.innerHTML;
        const originalDisabled = saveEvalQuestions.disabled;
        saveEvalQuestions.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveEvalQuestions.disabled = true;

        try {
            const response = await fetch('/sk/evaluation/questions/save-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    questions: payload
                })
            });

            console.log('Response status:', response.status, response.statusText);

            // Try to parse JSON
            let data;
            try {
                const text = await response.text();
                console.log('Raw response:', text);
                
                if (!text) {
                    throw new Error('Empty response from server');
                }
                
                data = JSON.parse(text);
                console.log('Parsed response:', data);
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                throw new Error('Invalid response from server');
            }

            if (data.success) {
                let message = data.message || 'Questions saved successfully!';
                
                // Add info about updates if stats are available
                if (data.stats) {
                    const stats = data.stats;
                    if (stats.added_new > 0) {
                        message += `\n${stats.added_new} new question(s) were added.`;
                    }
                    if (stats.deleted > 0) {
                        message += `\n${stats.deleted} question(s) were deleted.`;
                    }
                    if (stats.updated > 0) {
                        message += `\n${stats.updated} question(s) were updated.`;
                    }
                }
                
                alert(message);
                closeEditModal();
                
                // Reload the page to reflect changes
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                let errorMessage = data.message || 'Error saving questions. Please try again.';
                
                // Handle specific error types
                if (data.error === 'no_questions') {
                    errorMessage = 'No questions were provided to save.';
                } else if (data.error === 'server_error') {
                    errorMessage = 'Server error. Please try again or contact support.';
                }
                
                alert(errorMessage);
            }
        } catch (error) {
            console.error('Save error:', error);
            
            if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                alert('Network error. Please check your connection and try again.');
            } else if (error.message.includes('Invalid response')) {
                alert('Server returned an invalid response. Please try again.');
            } else if (error.message.includes('Empty response')) {
                alert('Server returned an empty response. Please check if the endpoint exists.');
            } else {
                alert('Failed to save questions: ' + error.message);
            }
        } finally {
            // Reset button state
            saveEvalQuestions.innerHTML = originalText;
            saveEvalQuestions.disabled = originalDisabled;
        }
    });
}

// Get CSRF token
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Attach event listeners to existing remove buttons
    attachRemoveListeners();
    
    // Attach input events to update save button state
    if (editModal) {
        editModal.addEventListener('input', function(e) {
            if (e.target.classList.contains('question-input')) {
                // Optional: Remove error styling when user starts typing
                e.target.classList.remove('error-border');
                updateSaveButtonState();
            }
        });
    }
    
    // Initial state check
    updateSaveButtonState();
});


    });
  </script>
</body>
</html>