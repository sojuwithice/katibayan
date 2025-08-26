<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Landing</title>
  <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <nav class="navbar">
    <div class="logo">
  <img src="{{ asset('images/sklogo.png') }}" alt="KatiBayan Logo" class="logo-img">
  <div class="logo-text">
    <span><span class="blue">K</span>ati<span class="blue">B</span>ayan.</span>
    <small>Katipunan ng Kabataan Web Portal</small>
  </div>
</div>


    <!-- menu -->
    <div class="menu-toggle" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </div>

    <!-- Nav links -->
    <ul class="nav-links" id="nav-links">
  <li><a href="#">Home</a></li>
  <li><a href="#">Features</a></li>
  <li><a href="#">FAQs</a></li>
  <li><a href="#">About Us</a></li>
  <li class="mobile-login">
    <a href="{{ route('loginpage') }}" class="login-btn">Login your Account</a>
  </li>
</ul>

<!-- Desktop login button -->
<div class="login-container">
  <a href="{{ route('loginpage') }}" class="login-btn">Login your Account</a>
</div>
</nav>


<section class="welcome">
  <div class="welcome-container">
    <!-- Left side image -->
    <div class="welcome-img">
      <img src="{{ asset('images/laptop.png') }}" alt="laptop" class="laptop-img">
    </div>

    <!-- Right side text -->
    <div class="welcome-text">
      <h1>
        Welcome to 
        <span class="katibayan">
          <span class="yellow">K</span>ati<span class="yellow">B</span>ayan
        </span> 
        <br>web portal register now!</br>
      </h1>
      <p>KatiBayan will help you engage with your SK Council. Register now to be a part of it. If you already have an account, please log in.</p>
      <button class="register-btn">CLICK HERE TO REGISTER</button>
    </div>
  </div>
</section>



  <section class="explore">
    <button>Wanna know more? Just <span>Explore!</span></button>
  </section>


<section class="about-sk" style="background-image: url('{{ asset('images/about1.png') }}')">
  <div class="about-container">
    <!-- Left text content -->
    <div class="about-text-box">
      <h2>
        <span class="highlight">Discover</span> the Responsibilities <br>
        and Purpose of SK: Empowering <br> Youth Leadership
      </h2>
      <p>
        Uncover how the Sangguniang Kabataan serves as a platform for the youth to lead, 
        engage, and make meaningful contributions to their communities.
      </p>
    </div>

    <!-- Right video card -->
    <div class="about-card">
  <div class="video-wrapper">
    <iframe 
      src="https://www.youtube.com/embed/4BNWtvnndXs?autoplay=1&mute=1&loop=1&playlist=4BNWtvnndXs" 
      title="About SK"
      frameborder="0"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
      allowfullscreen>
    </iframe>
  </div>
</div>
</section>


<!-- Main Features Section -->
<section class="features">
    <button>Main Features of <span>Katibayan</span></button>
  </section>

  <div class="features-container">
  <!-- Card 1 -->
  <div class="feature-card">
    <div class="card-header">
      <h3>YOUTH PROFILE</h3>
      <div class="icon"><i class="fas fa-user"></i></div>
    </div>
    <p>View and manage youth information, including demographics, voter registration status, and expressed concerns or interests.</p>
  </div>

  <!-- Card 2 -->
  <div class="feature-card">
    <div class="card-header">
      <h3>EVENTS AND PROGRAMS</h3>
      <div class="icon"><i class="fas fa-calendar-alt"></i></div>
    </div>
    <p>Post, organize, and update youth-related events and programs easily.</p>
  </div>

  <!-- Card 3 -->
  <div class="feature-card">
    <div class="card-header">
      <h3>ANNOUNCEMENTS AND REMINDERS</h3>
      <div class="icon"><i class="fas fa-bullhorn"></i></div>
    </div>
    <p>Send announcements and automated reminders for upcoming events, registration deadlines, and important SK updates.</p>
  </div>

  <!-- Card 4 -->
  <div class="feature-card">
    <div class="card-header">
      <h3>ENGAGEMENT TRACKING AND PROGRAM EVALUATION</h3>
      <div class="icon"><i class="fas fa-star"></i></div>
    </div>
    <p>Monitor youth participation in events and programs, track attendance, and gather evaluations or feedback to assess effectiveness.</p>
  </div>

  <!-- Card 5 -->
  <div class="feature-card">
    <div class="card-header">
      <h3>YOUTH CONCERNS AND INTERESTS</h3>
      <div class="icon"><i class="fas fa-lightbulb"></i></div>
    </div>
    <p>Log and categorize youth-submitted concerns, suggestions, and needs to help SK officials better understand priorities.</p>
  </div>

  <!-- Card 6 -->
  <div class="feature-card">
    <div class="card-header">
      <h3>REPORTS AND ANALYTICS</h3>
      <div class="icon"><i class="fas fa-chart-bar"></i></div>
    </div>
    <p>Generate comprehensive reports on youth demographics, engagement, voter status, and program performance.</p>
  </div>
</div>

</section>

<!-- FAQ Section -->
<section class="faqs">
  <div class="faqs-left">
    <!-- Blue Label -->
    <div class="faqs-label">FAQS</div>
    <h2>Frequently asked <span>Questions</span></h2>
    <p>about KatiBayan</p>
  </div>

  <div class="faqs-right">
    <!-- FAQ Item -->
    <div class="faq-item">
      <button class="faq-question">
        What is KatiBayan Web Portal?
        <span class="arrow">&#9662;</span>
      </button>
      <div class="faq-answer">
        <p>
          The KatiBayan Web Portal is a digital platform developed to support
          Sangguniang Kabataan (SK) officials in streamlining youth profiling,
          managing events and programs, monitoring participation, sending
          announcements, and enabling data-driven decision-making through
          automated reporting and analytics.
        </p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">
        Who can use this system?
        <span class="arrow">&#9662;</span>
      </button>
      <div class="faq-answer">
        <p>
          The system can be used by SK officials, youth members, and administrators
          for efficient coordination and communication.
        </p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">
        How is youth information protected in the system?
        <span class="arrow">&#9662;</span>
      </button>
      <div class="faq-answer">
        <p>
          Data is protected through encryption, authentication, and secure access
          levels to ensure youth information remains private.
        </p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">
        Can the system help track event attendance and feedback?
        <span class="arrow">&#9662;</span>
      </button>
      <div class="faq-answer">
        <p>
          Yes, the portal provides attendance logs, feedback forms, and reports to
          evaluate the success of youth programs and events.
        </p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">
        Can I use the system offline?
        <span class="arrow">&#9662;</span>
      </button>
      <div class="faq-answer">
        <p>
          No, the portal requires an internet connection to ensure real-time data
          access and synchronization.
        </p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">
        What if I forgot my password? What should I do?
        <span class="arrow">&#9662;</span>
      </button>
      <div class="faq-answer">
        <p>
          Use the "Forgot Password" option on the login page to reset your password
          via email verification.
        </p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">
        Where can I edit my personal information?
        <span class="arrow">&#9662;</span>
      </button>
      <div class="faq-answer">
        <p>
          You can edit your personal information from your profile settings once you
          are logged in to the system.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- About Us Section -->
<section class="about-us">
  <!-- Top label -->
  <div class="about-label">ABOUT US</div>

  <!-- Container for image + text -->
  <div class="about-us-container">

    <!-- Left: Image with gradient -->
    <div class="about-image">
      <div class="gradient-circle">
        <img src="{{ asset('images/phone.png') }}" alt="Phone with Logo">
      </div>
    </div>

    <!-- Right: Text -->
    <div class="about-text">
      <h2>About <span>KatiBayan</span></h2>
      <p>
        The KatiBayan Web Portal is a dedicated online platform designed to empower 
        the Sangguniang Kabataan (SK) in fostering meaningful youth participation 
        and strengthening community engagement. Built with the vision of streamlining 
        processes, it enables SK officials to efficiently manage youth profiles, 
        organize events, monitor engagement, and make informed decisions based on 
        real-time data and analytics.
      </p>
      <p>
        The KatiBayan Web Portal is a dedicated online platform designed to empower 
        the Sangguniang Kabataan (SK) in fostering meaningful youth participation 
        and strengthening community engagement. Built with the vision of streamlining processes,
      </p>
    </div>

  </div> 
</section>


<!-- Footer Section -->
<footer class="footer">
  <div class="footer-container">
    <!-- Logo -->
    <div class="footer-logo">
      <img src="{{ asset('images/sklogo.png') }}" alt="KatiBayan Logo" class="logo-img">
      <span>Sangguniang Kabataan</span>
    </div>

    <!-- Contact Info -->
    <div class="footer-contact">
      <h3>CONTACT INFORMATION</h3>
      <p>Sangguniang Kabataan</p>
      <p>Sangguniang Kabataan</p>
      <p>Sangguniang Kabataan</p>
    </div>
  </div>
</footer>



  <script>
  // === NAVBAR TOGGLE ===
  const toggle = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');

  toggle.addEventListener('click', (e) => {
    e.stopPropagation(); 
    navLinks.classList.toggle('active');
  });

  const links = navLinks.querySelectorAll('a, .login-btn');
  links.forEach(link => {
    link.addEventListener('click', () => {
      navLinks.classList.remove('active');
    });
  });

  document.addEventListener('click', (e) => {
    if (!navLinks.contains(e.target) && e.target !== toggle) {
      navLinks.classList.remove('active');
    }
  });

  // === FAQ ACCORDION ===
  const faqButtons = document.querySelectorAll(".faq-question");

  faqButtons.forEach(button => {
    button.addEventListener("click", (e) => {
      e.stopPropagation();
      const faqItem = button.parentElement;
      const answer = faqItem.querySelector(".faq-answer");

      // --- close all other open items first ---
      document.querySelectorAll(".faq-item.active").forEach(item => {
        if (item !== faqItem) {
          item.classList.remove("active");
          const openAnswer = item.querySelector(".faq-answer");
          openAnswer.style.maxHeight = null;
        }
      });

      // --- toggle current one ---
      faqItem.classList.toggle("active");

      if (faqItem.classList.contains("active")) {
        answer.style.maxHeight = answer.scrollHeight + "px";
      } else {
        answer.style.maxHeight = null;
      }
    });
  });

  // === CLOSE FAQ WHEN CLICK OUTSIDE ===
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".faq-item")) {
      document.querySelectorAll(".faq-item.active").forEach(item => {
        item.classList.remove("active");
        const answer = item.querySelector(".faq-answer");
        answer.style.maxHeight = null;
      });
    }
  });
</script>



</body>
</html>