<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Poll</title>
  <link rel="stylesheet" href="{{ asset('css/polls.css') }}">
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
      <a href="{{ route('dashboard.index') }}">
        <i data-lucide="layout-dashboard"></i>
        <span class="label">Dashboard</span>
      </a>
      <div class="profile-item nav-item">
        <a href="#" class="profile-link">
          <i data-lucide="circle-user"></i>
          <span class="label">Profile</span>
          <i data-lucide="chevron-down" class="submenu-arrow"></i>
        </a>
        <div class="submenu">
          <a href="{{ route('profilepage') }}">My Profile</a>
          <a href="{{ route('certificatepage') }}">Certificates</a>
        </div>
      </div>

      <a href="{{ route('eventpage') }}" class="events-link">
        <i data-lucide="calendar"></i>
        <span class="label">Events and Programs</span>
      </a>
      
      <a href="{{ route('evaluation') }}">
          <i data-lucide="user-star"></i>
          <span class="label">Evaluation</span>
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
      <button id="mobileMenuBtn" class="mobile-hamburger">
  <i data-lucide="menu"></i>
</button>
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

  <div class="polls-container">
    <div class="polls-header-box">
      <div class="polls-header">
        <h2>Polls</h2>

        <div class="committee-select">
          <label for="committee">Select a Committee:</label>
          <!-- Custom Dropdown -->
          <div class="custom-dropdown">
            <button class="dropdown-toggle">
              <span class="selected-text">All</span>
              <span class="arrow-circle">
                <i data-lucide="chevron-down"></i>
              </span>
            </button>
            <ul class="dropdown-menu">
              <li data-value="all">All</li>
              @foreach($committees as $committee)
                <li data-value="{{ strtolower($committee) }}">{{ $committee }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    <h3 class="suggestions-title">Suggestions Board</h3>

    <div id="pollsList">
      @if($polls->count() > 0)
        @foreach($polls as $poll)
          <div class="poll-box" data-poll-id="{{ $poll->id }}" data-committee="{{ $poll->committee ? strtolower($poll->committee) : '' }}">
            <div class="poll-header">
              <p>
                <span class="label">Question:</span> {{ $poll->question }}
                @if($poll->committee)
                  <span class="committee-badge">{{ $poll->committee }}</span>
                @endif
              </p>
              <div class="poll-meta">
                <div class="avatars">
                  @foreach($poll->votes->take(4) as $vote)
                    <img src="{{ $vote->user->avatar ? asset('storage/' . $vote->user->avatar) : asset('images/default-avatar.png') }}" 
                         alt="User" class="avatar-img">
                  @endforeach
                </div>
                @if($poll->votes->count() > 4)
                  <span class="others">+ {{ $poll->votes->count() - 4 }} others</span>
                @endif
                
                @if($poll->userHasVoted(Auth::id()))
                  <button class="vote-btn voted">
                    <i class="fas fa-check"></i> Voted
                  </button>
                @else
                  <button class="vote-btn">Vote</button>
                @endif
              </div>
            </div>

            <div class="poll-body hidden">
              <h4 class="choices-label">Choices</h4>
              
              @foreach($poll->options as $index => $option)
                <label class="choice">
                  <input type="radio" name="poll{{ $poll->id }}" value="{{ $index }}" 
                         {{ $poll->getUserVote(Auth::id()) == $index ? 'checked' : '' }}>
                  <span class="choice-text">{{ $option }}</span>
                </label>
              @endforeach

              <div class="poll-footer">
                <span class="end-date">Ends: {{ \Carbon\Carbon::parse($poll->end_date)->format('M d, Y') }}</span>
                <span class="total-votes">Total Votes: {{ $poll->votes->count() }}</span>
              </div>

              @if($poll->userHasVoted(Auth::id()))
                <div class="change-vote-section">
                  <button class="change-vote-btn" data-poll-id="{{ $poll->id }}">
                    <i class="fas fa-edit"></i> Change Vote
                  </button>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      @else
        <div class="no-polls">
          <p>No active polls available for your barangay.</p>
          <p>Check back later for new polls from your SK officials.</p>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- Change Vote Confirmation Modal -->
<div id="changeVoteModal" class="modal-overlay hidden">
  <div class="modal-box">
    <p>Are you sure you want to change your vote? You can only change your vote once.</p>
    <div class="modal-actions">
      <button class="cancel-btn">Cancel</button>
      <button class="confirm-change-btn">Yes, Change Vote</button>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // === Lucide icons ===
  lucide.createIcons();

  // === Sidebar ===
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  const profileItem = document.querySelector('.profile-item');
  const profileLink = profileItem?.querySelector('.profile-link');

  menuToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    sidebar.classList.toggle('open');
    if (!sidebar.classList.contains('open')) {
      profileItem?.classList.remove('open');
    }
  });

  function closeAllSubmenus() {
    profileItem?.classList.remove('open');
  }

  profileLink?.addEventListener('click', (e) => {
    e.preventDefault();
    if (sidebar.classList.contains('open')) {
      const isOpen = profileItem.classList.contains('open');
      closeAllSubmenus();
      if (!isOpen) profileItem.classList.add('open');
    }
  });

  // === Profile & Notifications dropdowns ===
  const profileWrapper = document.querySelector('.profile-wrapper');
  const profileToggle = document.getElementById('profileToggle');
  const profileDropdown = document.querySelector('.profile-dropdown');
  const notifWrapper = document.querySelector(".notification-wrapper");
  const notifBell = notifWrapper?.querySelector(".fa-bell");
  const notifDropdown = notifWrapper?.querySelector(".notif-dropdown");

  document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove('open');
      closeAllSubmenus();
    }
    if (profileWrapper && !profileWrapper.contains(e.target)) {
      profileWrapper.classList.remove('active');
    }
    if (notifWrapper && !notifWrapper.contains(e.target)) {
      notifWrapper.classList.remove('active');
    }
  });

  profileToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    profileWrapper.classList.toggle('active');
    notifWrapper?.classList.remove('active');
  });

  profileDropdown?.addEventListener('click', e => e.stopPropagation());

  notifBell?.addEventListener('click', (e) => {
    e.stopPropagation();
    notifWrapper.classList.toggle('active');
    profileWrapper?.classList.remove('active');
  });

  notifDropdown?.addEventListener('click', e => e.stopPropagation());

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

  // === Custom dropdown - FIXED COMMITTEE FILTER ===
  const dropdown = document.querySelector(".custom-dropdown");
  if (dropdown) {
    const toggle = dropdown.querySelector(".dropdown-toggle");
    const menu = dropdown.querySelector(".dropdown-menu");
    const selected = dropdown.querySelector(".selected-text");
    
    toggle.addEventListener("click", () => {
      const isOpen = dropdown.classList.toggle("open");
      menu.style.display = isOpen ? "block" : "none";
    });
    
    menu.querySelectorAll("li").forEach(item => {
      item.addEventListener("click", () => {
        selected.textContent = item.textContent;
        menu.style.display = "none";
        dropdown.classList.remove("open");
        
        // Filter polls by committee - FIXED VERSION
        const committee = item.dataset.value;
        document.querySelectorAll('.poll-box').forEach(poll => {
          if (committee === 'all') {
            poll.style.display = 'block';
          } else {
            const pollCommittee = poll.dataset.committee;
            if (pollCommittee === committee) {
              poll.style.display = 'block';
            } else {
              poll.style.display = 'none';
            }
          }
        });
      });
    });
    
    document.addEventListener("click", e => {
      if (!dropdown.contains(e.target)) {
        menu.style.display = "none";
        dropdown.classList.remove("open");
      }
    });
  }

  // === Polls toggle & vote button ===
  document.querySelectorAll('.vote-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const poll = this.closest('.poll-box');
      const body = poll.querySelector('.poll-body');
      
      // Toggle current poll
      body.classList.toggle('hidden');
      
      // Close other polls
      document.querySelectorAll('.poll-body').forEach(b => {
        if (b !== body) b.classList.add('hidden');
      });
    });
  });

  // === Voting functionality ===
  document.querySelectorAll('.choice input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
      if (this.disabled) return;
      
      const pollBox = this.closest('.poll-box');
      const pollId = pollBox.dataset.pollId;
      const optionIndex = this.value;
      
      // Send vote to server
      fetch(`/polls/${pollId}/vote`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ option_index: optionIndex })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update UI
          const voteBtn = pollBox.querySelector('.vote-btn');
          voteBtn.classList.add('voted');
          voteBtn.innerHTML = '<i class="fas fa-check"></i> Voted';
          
          // Add change vote button
          const changeVoteSection = document.createElement('div');
          changeVoteSection.className = 'change-vote-section';
          changeVoteSection.innerHTML = `
            <button class="change-vote-btn" data-poll-id="${pollId}">
              <i class="fas fa-edit"></i> Change Vote
            </button>
          `;
          pollBox.querySelector('.poll-body').appendChild(changeVoteSection);
          
          // Add event listener to the new change vote button
          changeVoteSection.querySelector('.change-vote-btn').addEventListener('click', function() {
            showChangeVoteModal(pollId);
          });
          
          // Reload poll results
          loadPollResults(pollId);
        } else {
          alert(data.error);
          this.checked = false;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error submitting vote');
        this.checked = false;
      });
    });
  });

  // === Change Vote Functionality ===
  let currentPollIdForChange = null;

  function showChangeVoteModal(pollId) {
    currentPollIdForChange = pollId;
    document.getElementById('changeVoteModal').classList.remove('hidden');
  }

  // Change Vote Modal Event Listeners
  const changeVoteModal = document.getElementById('changeVoteModal');
  const cancelChangeBtn = changeVoteModal.querySelector('.cancel-btn');
  const confirmChangeBtn = changeVoteModal.querySelector('.confirm-change-btn');

  cancelChangeBtn?.addEventListener('click', () => {
    changeVoteModal.classList.add('hidden');
    currentPollIdForChange = null;
  });

  confirmChangeBtn?.addEventListener('click', () => {
    if (currentPollIdForChange) {
      resetVote(currentPollIdForChange);
      changeVoteModal.classList.add('hidden');
      currentPollIdForChange = null;
    }
  });

  changeVoteModal?.addEventListener('click', (e) => {
    if (e.target === changeVoteModal) {
      changeVoteModal.classList.add('hidden');
      currentPollIdForChange = null;
    }
  });

  // Reset vote function
  function resetVote(pollId) {
    fetch(`/polls/${pollId}/reset-vote`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const pollBox = document.querySelector(`[data-poll-id="${pollId}"]`);
        
        // Reset UI
        const voteBtn = pollBox.querySelector('.vote-btn');
        voteBtn.classList.remove('voted');
        voteBtn.innerHTML = 'Vote';
        
        // Enable radio buttons
        pollBox.querySelectorAll('input[type="radio"]').forEach(input => {
          input.disabled = false;
          input.checked = false;
        });
        
        // Remove change vote button
        const changeVoteSection = pollBox.querySelector('.change-vote-section');
        if (changeVoteSection) {
          changeVoteSection.remove();
        }
        
        // Reload results
        loadPollResults(pollId);
      } else {
        alert(data.error);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error resetting vote');
    });
  }

  // Add event listeners to existing change vote buttons
  document.querySelectorAll('.change-vote-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const pollId = this.dataset.pollId;
      showChangeVoteModal(pollId);
    });
  });

  // Load poll results
  function loadPollResults(pollId) {
    fetch(`/polls/${pollId}/results`)
      .then(response => response.json())
      .then(data => {
        const pollBox = document.querySelector(`[data-poll-id="${pollId}"]`);
        
        // Update total votes
        const totalVotes = pollBox.querySelector('.total-votes');
        if (totalVotes) {
          totalVotes.textContent = `Total Votes: ${data.total_votes}`;
        }
        
        // Update avatars in header
        const avatarsContainer = pollBox.querySelector('.poll-meta .avatars');
        avatarsContainer.innerHTML = '';
        
        // Add first 4 voter avatars
        data.poll.votes.slice(0, 4).forEach(vote => {
          const img = document.createElement('img');
          img.src = vote.user.avatar ? `/storage/${vote.user.avatar}` : '/images/default-avatar.png';
          img.alt = 'User';
          img.className = 'avatar-img';
          avatarsContainer.appendChild(img);
        });
        
        // Update "others" count
        const othersSpan = pollBox.querySelector('.others');
        if (othersSpan && data.total_votes > 4) {
          othersSpan.textContent = `+ ${data.total_votes - 4} others`;
        } else if (othersSpan && data.total_votes <= 4) {
          othersSpan.remove();
        }
      })
      .catch(error => console.error('Error loading results:', error));
  }
});
</script>

<script>
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const sidebar = document.querySelector('.sidebar');
  const mainContent = document.querySelector('.main'); // (BAGO)

  mobileBtn?.addEventListener('click', (e) => {
    e.stopPropagation(); // (BAGO)
    sidebar.classList.toggle('open');
    document.body.classList.toggle('mobile-sidebar-active'); // (BAGO)
  });

  // Close sidebar when clicking outside (mobile only)
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768 &&
      sidebar.classList.contains('open') && // (BAGO) Check kung open
      !sidebar.contains(e.target) &&
      !mobileBtn.contains(e.target)) {
      
      sidebar.classList.remove('open');
      document.body.classList.remove('mobile-sidebar-active'); // (BAGO)
    }
  });
</script>
</body>
</html>