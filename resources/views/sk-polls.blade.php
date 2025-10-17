<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - SK Polls</title>
  <link rel="stylesheet" href="{{ asset('css/sk-polls.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  
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

    <a href="{{ route('sk-polls') }}" class="active">
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
                <h4>{{ $user->given_name }} {{ $user->last_name }}</h4>
                <div class="profile-badge">
                  <span class="badge">SK-Member</span>
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

    <div class="poll-container">
      <!-- Poll Header -->
      <div class="poll-header">
        <h2>Poll Management</h2>
        <a href="#" class="btn create-poll-btn">Create Poll</a>
      </div>

      <!-- Responses Subheader -->
      <div class="responses-header">
        <h3>Active Polls</h3>
      </div>

      <div id="pollsList">
        @if($polls->count() > 0)
          @foreach($polls as $poll)
            <div class="poll-card" data-poll-id="{{ $poll->id }}" data-vote-counts="{{ json_encode($poll->getVoteCounts()) }}">
              <div class="poll-top">
                <p class="question">
                  <span class="label">Question:</span>
                  <em>{{ $poll->question }}</em>
                  @if($poll->committee)
                    <span class="committee-tag">{{ ucfirst($poll->committee) }}</span>
                  @endif
                </p>
                <div class="poll-actions">
                  <a href="#" class="btn view-respondents" data-poll-id="{{ $poll->id }}">View Respondents</a>
                  <button class="delete-poll" data-poll-id="{{ $poll->id }}">Delete</button>
                </div>
              </div>

              <div class="poll-content">
                <div class="poll-options">
                  @foreach($poll->options as $index => $option)
                    @php
                      $colors = ['#5A2D91', '#D9A441', '#0C2744', '#2E8B57', '#FF6B6B'];
                      $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="option">
                 <span class="swatch" style="background-color: <?php echo $color; ?>"></span>
                      {{ $option }} 
                      <span class="vote-count">({{ $poll->votes->where('option_index', $index)->count() }} votes)</span>
                    </div>
                  @endforeach
                </div>

                <div class="poll-chart-wrap">
                  <canvas id="pollChart{{ $poll->id }}"></canvas>
                  <p class="responses">Total Responses: {{ $poll->votes->count() }}</p>
                  <p class="end-date">Ends: {{ \Carbon\Carbon::parse($poll->end_date)->format('M d, Y') }}</p>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="no-polls">
            <p>No polls created yet.</p>
            <p>Create your first poll to engage with your barangay youth!</p>
          </div>
        @endif
      </div>
    </div>

  </div>

  <!-- RESPONDENTS MODAL -->
  <div class="modal-overlay" id="respondentsOverlay">
    <div class="respondents-modal">
      <span class="modal-close">&times;</span>
      <h3>Respondents</h3>
      <div id="respondentsList">
        <!-- Respondents will be loaded here -->
      </div>
    </div>
  </div>

  <!-- CREATE POLL MODAL -->
  <div class="modal-overlay" id="createPollOverlay">
    <div class="create-poll-modal">
      <span class="modal-close">&times;</span>
      <h2>Create Poll</h2>
      <p>Empower the youth to voice their ideas through activity polls</p>

      <form id="createPollForm">
        @csrf
        
        <label for="pollQuestion"><em>Poll Question:</em></label>
        <input type="text" id="pollQuestion" name="question" placeholder="Enter your question" required>

        <label for="pollCommittee"><em>Committee (Optional):</em></label>
        <select id="pollCommittee" name="committee">
          <option value="">Select Committee</option>
          <option value="active citizenship">Active Citizenship</option>
          <option value="economic empowerment">Economic Empowerment</option>
          <option value="education">Education</option>
          <option value="health">Health</option>
          <option value="sports">Sports</option>
        </select>

        <label for="pollEnd"><em>Poll end date</em></label>
        <input type="date" id="pollEnd" name="end_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>

        <label><em>Create choices</em></label>
        <div id="pollOptions">
          <div class="option-item">
            <input type="text" name="options[]" placeholder="Option 1" class="poll-option" required>
            <span class="remove-option">×</span>
          </div>
          <div class="option-item">
            <input type="text" name="options[]" placeholder="Option 2" class="poll-option" required>
            <span class="remove-option">×</span>
          </div>
        </div>
        <button type="button" class="add-option">+ Add another option</button>
        
        <div class="modal-actions">
          <button type="button" class="cancel-btn">Cancel</button>
          <button type="submit" class="submit-btn">Create Poll</button>
        </div>
      </form>
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

  // === Notifications & Profile ===
  const notifWrapper = document.querySelector(".notification-wrapper");
  const profileWrapper = document.querySelector(".profile-wrapper");
  const profileToggle = document.getElementById("profileToggle");

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

  if (profileWrapper && profileToggle) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileWrapper.classList.toggle("active");
      notifWrapper?.classList.remove("active");
    });
  }

  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
    if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
  });

  // === Poll Charts ===
  function createPollChart(id, dataValues) {
    const ctx = document.getElementById(id);
    if (!ctx) return;

    const total = dataValues.reduce((a, b) => a + b, 0);
    if (total === 0) {
      // Show empty state
      ctx.parentElement.innerHTML = '<p class="no-votes">No votes yet</p>';
      return;
    }

    new Chart(ctx, {
      type: "pie",
      data: {
        labels: dataValues.map((_, index) => `Option ${index + 1}`),
        datasets: [{
          data: dataValues,
          backgroundColor: ["#5A2D91", "#D9A441", "#0C2744", "#2E8B57", "#FF6B6B"],
          borderWidth: 2,
          borderColor: "#fff"
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          datalabels: {
            color: "#fff",
            font: { weight: "bold", size: 14 },
            formatter: (value) => {
              return ((value / total) * 100).toFixed(0) + "%";
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  }

  // Initialize charts for each poll using data attributes
  document.querySelectorAll('.poll-card').forEach(pollCard => {
    const pollId = pollCard.dataset.pollId;
    const voteCounts = JSON.parse(pollCard.dataset.voteCounts);
    createPollChart(`pollChart${pollId}`, voteCounts);
  });

  // ================= CREATE POLL MODAL =================
  const createPollBtn = document.querySelector(".create-poll-btn");
  const createPollOverlay = document.getElementById("createPollOverlay");
  const createPollForm = document.getElementById("createPollForm");

  // Open modal
  if (createPollBtn) {
    createPollBtn.addEventListener("click", (e) => {
      e.preventDefault();
      createPollOverlay.style.display = "flex";
    });
  }

  // Close modal
  createPollOverlay.querySelector(".modal-close").addEventListener("click", () => {
    createPollOverlay.style.display = "none";
    createPollForm.reset();
    resetOptions();
  });

  // Close when clicking outside
  createPollOverlay.addEventListener("click", (e) => {
    if (e.target.id === "createPollOverlay") {
      createPollOverlay.style.display = "none";
      createPollForm.reset();
      resetOptions();
    }
  });

  // Cancel button
  document.querySelector('.cancel-btn')?.addEventListener('click', () => {
    createPollOverlay.style.display = "none";
    createPollForm.reset();
    resetOptions();
  });

  // --- Add option functionality ---
  function resetOptions() {
    const optionsContainer = document.getElementById("pollOptions");
    optionsContainer.innerHTML = `
      <div class="option-item">
        <input type="text" name="options[]" placeholder="Option 1" class="poll-option" required>
        <span class="remove-option">×</span>
      </div>
      <div class="option-item">
        <input type="text" name="options[]" placeholder="Option 2" class="poll-option" required>
        <span class="remove-option">×</span>
      </div>
    `;
    reindexOptions();
  }

  function reindexOptions() {
    const optionsContainer = document.getElementById("pollOptions");
    const items = optionsContainer.querySelectorAll(".option-item");
    items.forEach((wrapper, i) => {
      const input = wrapper.querySelector("input");
      input.placeholder = `Option ${i + 1}`;

      const removeBtn = wrapper.querySelector(".remove-option");
      if (i < 2) {
        removeBtn.style.display = "none";
      } else {
        removeBtn.style.display = "flex";
      }
    });
  }

  function createOptionField() {
    const wrapper = document.createElement("div");
    wrapper.className = "option-item";

    const input = document.createElement("input");
    input.type = "text";
    input.name = "options[]";
    input.className = "poll-option";
    input.placeholder = `Option ${document.getElementById("pollOptions").children.length + 1}`;
    input.required = true;

    const removeBtn = document.createElement("span");
    removeBtn.textContent = "×";
    removeBtn.className = "remove-option";

    removeBtn.addEventListener("click", () => {
      if (document.getElementById("pollOptions").children.length > 2) {
        wrapper.remove();
        reindexOptions();
      }
    });

    wrapper.appendChild(input);
    wrapper.appendChild(removeBtn);
    return wrapper;
  }

  document.querySelector(".add-option")?.addEventListener("click", (e) => {
    e.preventDefault();
    const optionField = createOptionField();
    document.getElementById("pollOptions").appendChild(optionField);
    reindexOptions();
  });

  // Form submission
  createPollForm.addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const options = Array.from(formData.getAll('options[]')).filter(opt => opt.trim() !== '');
    
    // Validate at least 2 options
    if (options.length < 2) {
      alert('Please provide at least 2 options');
      return;
    }

    const data = {
      question: formData.get('question'),
      options: options,
      end_date: formData.get('end_date'),
      committee: formData.get('committee'),
      _token: '{{ csrf_token() }}'
    };
    
    // Show loading state
    const submitBtn = createPollForm.querySelector('.submit-btn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creating...';
    
    fetch('/sk-polls', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Close modal and reload page
        createPollOverlay.style.display = "none";
        createPollForm.reset();
        resetOptions();
        location.reload();
      } else {
        alert('Error creating poll: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error creating poll');
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Create Poll';
    });
  });

  // ================= RESPONDENTS MODAL =================
  document.querySelectorAll(".view-respondents").forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const pollId = btn.dataset.pollId;
      
      // Show loading
      document.getElementById('respondentsList').innerHTML = '<div class="loading">Loading respondents...</div>';
      document.getElementById("respondentsOverlay").style.display = "flex";
      
      fetch(`/sk-polls/${pollId}/respondents`)
        .then(response => response.json())
        .then(data => {
          const respondentsList = document.getElementById('respondentsList');
          if (data.respondents && data.respondents.length > 0) {
            respondentsList.innerHTML = data.respondents.map(respondent => `
              <div class="respondent-item">
                <img src="${respondent.avatar}" alt="User" class="respondent-avatar">
                <span>${respondent.name}</span>
              </div>
            `).join('');
          } else {
            respondentsList.innerHTML = '<p class="no-votes">No respondents yet</p>';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('respondentsList').innerHTML = '<p class="error-message">Error loading respondents</p>';
        });
    });
  });

  // Close respondents modal
  document.querySelector("#respondentsOverlay .modal-close").addEventListener("click", () => {
    document.getElementById("respondentsOverlay").style.display = "none";
  });

  // Close when clicking outside
  document.getElementById("respondentsOverlay").addEventListener("click", (e) => {
    if (e.target.id === "respondentsOverlay") {
      e.currentTarget.style.display = "none";
    }
  });

  // ================= DELETE POLL =================
  document.querySelectorAll(".delete-poll").forEach(btn => {
    btn.addEventListener("click", function() {
      const pollId = this.dataset.pollId;
      const pollTitle = this.closest('.poll-card').querySelector('.question em').textContent;
      
      if (confirm(`Are you sure you want to delete the poll: "${pollTitle}"? This action cannot be undone.`)) {
        // Show loading
        this.disabled = true;
        this.textContent = 'Deleting...';
        
        fetch(`/sk-polls/${pollId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Remove poll card from DOM
            document.querySelector(`[data-poll-id="${pollId}"]`).remove();
            
            // Show success message or reload if no polls left
            if (document.querySelectorAll('.poll-card').length === 0) {
              location.reload();
            }
          } else {
            alert('Error deleting poll: ' + (data.message || 'Unknown error'));
            this.disabled = false;
            this.textContent = 'Delete';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error deleting poll');
          this.disabled = false;
          this.textContent = 'Delete';
        });
      }
    });
  });

  // Initial setup
  reindexOptions();
});
</script>
</body>
</html>