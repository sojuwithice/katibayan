<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Youth Assistance</title>
  <link rel="stylesheet" href="{{ asset('css/youth-assistance.css') }}">
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
          <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://i.pravatar.cc/80' }}" alt="User" class="avatar" id="profileToggle">
          <div class="profile-dropdown">
            <div class="profile-header">
              <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://i.pravatar.cc/80' }}" alt="User" class="profile-avatar">
              <div class="profile-info">
                <h4>{{ Auth::user()->given_name }} {{ Auth::user()->last_name }}</h4>
                <div class="profile-badge">
                  <span class="badge">{{ Auth::user()->role == 'sk' ? 'SK Member' : 'KK Member' }}</span>
                  <span class="badge">{{ \Carbon\Carbon::parse(Auth::user()->date_of_birth)->age }} yrs old</span>
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

    <!-- Youth Assistance Records -->
    <section class="youth-assistance">
      <h2>Youth Assistance Records</h2>
      <p class="subtitle">
        This will help you recognize youth who need support, monitor the type of assistance provided, 
        and ensure timely follow-up for their well-being
      </p>

      <!-- Summary Cards -->
      <div class="summary-cards">
        <div class="card">
          <span class="count">{{ $pwdCount }}</span>
          <span class="label">Person's with Disability (PWD)</span>
        </div>
        <div class="card">
          <span class="count">{{ $oosyCount }}</span>
          <span class="label">Out of School Youth</span>
        </div>
        <div class="card">
          <span class="count">{{ $unemployedCount }}</span>
          <span class="label">Unemployed</span>
        </div>
        <div class="card">
          <span class="count">{{ $singleParentCount }}</span>
          <span class="label">Single Parent</span>
        </div>
      </div>

      <div class="table-container">
        <!-- Filter row -->
        <div class="filter-bar">
          <!-- LEFT: Search + Category -->
          <div class="filter-left">
            <input type="text" placeholder="Search" class="search-input" id="searchInput">
            <div class="custom-dropdown">
              <div class="dropdown-selected" data-value="all">All</div>
              <ul class="dropdown-options">
                <li data-value="all">All</li>
                <li data-value="pwd">PWD</li>
                <li data-value="oosy">Out of School Youth</li>
                <li data-value="unemployed">Unemployed</li>
                <li data-value="single-parent">Single Parent</li>
              </ul>
            </div>
          </div>

          <!-- RIGHT: Total -->
          <div class="total">Total Youth in need: <span id="totalCount">{{ $youthInNeed->count() }}</span></div>
        </div>

        <!-- Table -->
        <div class="table-wrapper">
          <table class="youth-table">
            <thead>
              <tr>
                <th>Youth Name</th>
                <th>Age</th>
                <th>Purok</th>
                <th>Youth Classification</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="youthTableBody">
              @foreach($youthInNeed as $youth)
                <tr>
                  <td>
                    <img src="{{ $youth->avatar ? asset('storage/' . $youth->avatar) : 'https://i.pravatar.cc/40?img=' . $loop->index }}" alt="">
                    {{ $youth->given_name }} {{ $youth->middle_name ? $youth->middle_name . ' ' : '' }}{{ $youth->last_name }}
                  </td>
                  <td>{{ \Carbon\Carbon::parse($youth->date_of_birth)->age }}</td>
                  <td>{{ $youth->purok_zone }}</td>
                  <td>
                    @php
                      $tagClass = '';
                      $tagText = '';
                      
                      if (str_contains($youth->youth_classification, 'PWD')) {
                        $tagClass = 'tag-pwd';
                        $tagText = 'Person\'s with Disability (PWD)';
                      } elseif ($youth->youth_classification == 'Out-of-School Youth') {
                        $tagClass = 'tag-oosy';
                        $tagText = 'Out of School Youth';
                      } elseif ($youth->work_status == 'Unemployed') {
                        $tagClass = 'tag-unemployed';
                        $tagText = 'Unemployed';
                      } elseif ($youth->civil_status == 'Single Parent') {
                        $tagClass = 'tag-single';
                        $tagText = 'Single Parent';
                      } else {
                        $tagClass = 'tag-other';
                        $tagText = $youth->youth_classification;
                      }
                    @endphp
                    <span class="tag {{ $tagClass }}">{{ $tagText }}</span>
                  </td>
                  <td>
                    <button class="btn btn-outline" onclick="contactYouth('{{ $youth->contact_no }}', '{{ $youth->email }}')">Contact</button>
                    <button class="btn btn-primary" onclick="sendHelp('{{ $youth->id }}')">Send Help</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </section>
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
        }
        if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove('active');
        if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove('active');
      });

      // === Custom Dropdown ===
      const dropdown = document.querySelector(".custom-dropdown");
      if (dropdown) {
        const selected = dropdown.querySelector(".dropdown-selected");
        const options = dropdown.querySelector(".dropdown-options");
        const items = options.querySelectorAll("li");

        // Toggle dropdown
        selected.addEventListener("click", (e) => {
          e.stopPropagation();
          options.style.display = options.style.display === "block" ? "none" : "block";
        });

        // Select option
        items.forEach(item => {
          item.addEventListener("click", () => {
            selected.textContent = item.textContent;
            selected.dataset.value = item.dataset.value;
            options.style.display = "none";
            filterYouth(); // Trigger filtering
          });
        });

        // Close if click outside
        document.addEventListener("click", (e) => {
          if (!dropdown.contains(e.target)) {
            options.style.display = "none";
          }
        });
      }

      // === Search functionality ===
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', () => {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(filterYouth, 300);
        });
      }
    });

    // Filter youth function
    function filterYouth() {
      const category = document.querySelector('.dropdown-selected').dataset.value;
      const search = document.getElementById('searchInput').value;

      fetch('{{ route("youth-assistance.filter") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          category: category,
          search: search
        })
      })
      .then(response => response.json())
      .then(data => {
        updateTable(data.youth);
        document.getElementById('totalCount').textContent = data.total;
      })
      .catch(error => console.error('Error:', error));
    }

    // Update table with filtered data
    function updateTable(youth) {
      const tableBody = document.getElementById('youthTableBody');
      tableBody.innerHTML = '';

      youth.forEach(user => {
        // Calculate age from date_of_birth
        const age = calculateAge(user.date_of_birth);
        
        // Determine tag class and text
        let tagClass = '';
        let tagText = '';
        
        if (user.youth_classification && user.youth_classification.includes('PWD')) {
          tagClass = 'tag-pwd';
          tagText = 'Person\'s with Disability (PWD)';
        } else if (user.youth_classification === 'Out-of-School Youth') {
          tagClass = 'tag-oosy';
          tagText = 'Out of School Youth';
        } else if (user.work_status === 'Unemployed') {
          tagClass = 'tag-unemployed';
          tagText = 'Unemployed';
        } else if (user.civil_status === 'Single Parent') {
          tagClass = 'tag-single';
          tagText = 'Single Parent';
        } else {
          tagClass = 'tag-other';
          tagText = user.youth_classification || 'Needs Assistance';
        }

        const row = `
          <tr>
            <td>
              <img src="${user.avatar ? '/storage/' + user.avatar : 'https://i.pravatar.cc/40?img=' + Math.floor(Math.random() * 10)}" alt="">
              ${user.given_name} ${user.middle_name ? user.middle_name + ' ' : ''}${user.last_name}
            </td>
            <td>${age}</td>
            <td>${user.purok_zone}</td>
            <td><span class="tag ${tagClass}">${tagText}</span></td>
            <td>
              <button class="btn btn-outline" onclick="contactYouth('${user.contact_no}', '${user.email}')">Contact</button>
              <button class="btn btn-primary" onclick="sendHelp('${user.id}')">Send Help</button>
            </td>
          </tr>
        `;
        tableBody.innerHTML += row;
      });
    }

    // Calculate age from date string
    function calculateAge(dateString) {
      const birthDate = new Date(dateString);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      return age;
    }

    // Contact youth function
    function contactYouth(contactNo, email) {
      if (contactNo) {
        window.open(`tel:${contactNo}`, '_self');
      } else if (email) {
        window.open(`mailto:${email}`, '_self');
      } else {
        alert('No contact information available');
      }
    }

    // Send help function
    function sendHelp(userId) {
      // Implement send help functionality
      alert(`Sending help to user ID: ${userId}`);
      // You can implement modal or redirect to assistance form here
    }
  </script>
</body>
</html>