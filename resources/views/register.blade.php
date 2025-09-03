<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Register</title>
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>

<!-- Terms and Conditions Modal -->
<div class="modal-overlay" id="termsModal">
  <div class="modal">
    <div class="modal-header">
      <h2>Terms and Conditions</h2>
    </div>
    <div class="modal-body">
      <h3>1. Acceptance of Terms</h3>
      <p>By creating an account and using this system, you agree to comply with and be bound by these Terms and Conditions. If you do not agree, you may not proceed with the registration or use the system.</p>

      <h3>2. Purpose of the System</h3>
      <p>This system is designed to collect, manage, and analyze user information for official purposes such as youth profiling, community development, governance, and service delivery.</p>

      <h3>3. User Responsibilities</h3>
      <p>You agree to provide accurate, complete, and updated information during registration. You are responsible for maintaining the confidentiality of your account credentials. You agree not to use the system for unlawful, fraudulent, or unauthorized activities.</p>

      <h3>4. Data Privacy and Security</h3>
      <p>You agree to provide accurate, complete, and updated information during registration. You are responsible for maintaining the confidentiality of your account credentials. You agree not to use the system for unlawful, fraudulent, or unauthorized activities.</p>
    </div>
    <div class="modal-footer">
      <button class="accept-btn" id="acceptBtn">I have read and accept the Term and Condition</button>
      <button class="cancel-btn" id="cancelBtn">Cancel</button>
    </div>
  </div>
</div>

<!-- Header -->
<header class="header">
  <div class="header-left">
    <div class="logo"></div>
    <h2>Welcome to KatiBayan</h2>
  </div>
  <button class="theme-toggle" id="themeToggle">
    <i data-lucide="sun"></i>
  </button>
</header>

<!-- Banner -->
<section class="banner">
  <h1>REGISTER NOW</h1>
  <p>
    The <span class="highlight">KatiBayan</span> Web Portal helps you connect with your SK Leaders and become 
    an active youth member for a better community. So, what are you waiting for? Sign up now!
  </p>
</section>

<!-- Registration Portal -->
<section class="form-container">
  <h2 class="form-title">Registration Portal</h2>

  <!-- Steps with icons -->
  <div class="steps">
    <div class="step active" data-step="1">
      <div class="circle"><i data-lucide="user"></i></div>
      <p>Personal Information</p>
    </div>
    <div class="step" data-step="2">
      <div class="circle"><i data-lucide="settings"></i></div>
      <p>Account Setup</p>
    </div>
    <div class="step" data-step="3">
      <div class="circle"><i data-lucide="check-square"></i></div>
      <p>Verification and Review</p>
    </div>
  </div>

  <div class="steps-divider"></div>

  <!-- Form -->
  <form id="multiStepForm" class="register-form">

    <!-- STEP 1 -->
    <section class="step-content" data-step="1">
      <div class="profile-section">
        <div class="profile-header">
          <div>
            <h3>I. KK Profile</h3>
            <p>Enter your account details</p>
          </div>
        </div>

        <div class="form-grid">
          <div class="input-wrapper">
            <input type="text" id="step1_lastname" name="last_name" placeholder="Last Name" required>
          </div>
          <div class="input-wrapper">
            <input type="text" id="step1_givenname" name="given_name" placeholder="Given Name" required>
          </div>
          <input type="text" id="step1_middlename" name="middle_name" placeholder="Middle Name">
          <input type="text" id="step1_suffix" name="suffix" placeholder="Suffix (optional)">
        </div>

        <div class="select-wrapper full-width">
          <input type="text" id="step1_address" placeholder="Enter your full address">
        </div>

        <div class="form-grid-4">
          <div class="input-icon">
            <input type="date" id="step1_dob" placeholder="Date of Birth">
            <span class="icon" id="calendarIcon"><i data-lucide="calendar"></i></span>
          </div>

          <div class="select-wrapper">
            <input type="text" id="step1_sex" placeholder="Sex" readonly>
            <span class="arrow"><i data-lucide="chevron-down"></i></span>
            <ul class="dropdown-options">
              <li data-value="male">Male</li>
              <li data-value="female">Female</li>
            </ul>
          </div>

          <input type="email" id="step1_email" placeholder="Email Address">
          <input type="tel" placeholder="Contact No.">
        </div>
      </div>

      <div class="section-header">
        <h3>II. Demographics</h3>
      </div>
      <div class="form-grid">
        <div class="select-wrapper">
          <input type="text" id="step1_civil" placeholder="Civil Status" readonly>
          <ul class="dropdown-options">
            <li>Single</li>
            <li>Married</li>
            <li>Widowed</li>
            <li>Divorced</li>
            <li>Separated</li>
            <li>Anulled</li>
            <li>Unknown</li>
            <li>Live-in</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
        <div class="select-wrapper">
          <input type="text" name="education" placeholder="Educational Background" readonly>
          <ul class="dropdown-options">
            <li>Elementary Level</li>
            <li>Elementary Graduate</li>
            <li>High School Level</li>
            <li>High School Graduate</li>
            <li>Vocational Graduate</li>
            <li>College Level</li>
            <li>College Graduate</li>
            <li>Masters Level</li>
            <li>Masters Graduate</li>
            <li>Doctorate Level</li>
            <li>Doctorate Graduate</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
        <div class="select-wrapper">
          <input type="text" name="work_status" placeholder="Work Status" readonly>
          <ul class="dropdown-options">
            <li>Student</li>
            <li>Employed</li>
            <li>Unemployed</li>
            <li>Self-Employed</li>
            <li>Currently looking for a Job</li>
            <li>Not Interested Looking for a Job</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
      </div>

      <div class="form-grid">
        <div class="select-wrapper">
          <input type="text" name="youth_classification" placeholder="Youth Classification" readonly>
          <ul class="dropdown-options">
            <li>In-School Youth</li>
            <li>Out-of-School Youth</li>
            <li>Working Youth</li>
            <li>Youth with Specific Needs</li>
            <li>Person with Disability (PWD)</li>
            <li>Children in Conflict with the Law (CICL)</li>
            <li>Indigenous People (IP)</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>

        <div class="select-wrapper">
          <input type="text" name="sk_voter" placeholder="Are you a registered SK voter?" readonly>
          <ul class="dropdown-options">
            <li>Yes</li>
            <li>No</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
      </div>
    </section>

    <!-- STEP 2 -->
    <section class="step-content" data-step="2">

      <h2>III. Verification Document</h2>
        <div class="select-wrapper short-select">
          <input type="text" name="role" placeholder="Select your role (SK, KK, etc)" readonly>
          <ul class="dropdown-options">
            <li>SK</li>
            <li>KK</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>

        <div class="file-section">
          <p>Upload Oath Taking Certificate or Appointment Letter</p>
          <div class="upload-box">
            <label for="documentUpload1" class="upload-label">Choose File</label>
            <input type="file" id="documentUpload1" accept=".pdf" required hidden>
            <span id="fileText1" class="file-text">Accepted: PDF, max 5 MB</span>
          </div>
        </div>

        <div class="file-section">
          <p>Upload Proof of Residency  (e.g. Barangay Indigency or valid ID with Full Address)</p>
          <div class="upload-box">
            <label for="documentUpload2" class="upload-label">Choose File</label>
            <input type="file" id="documentUpload2" accept=".pdf" required hidden>
            <span id="fileText2" class="file-text">Accepted: PDF, max 5 MB</span>
          </div>
        </div>

        <h2>IV. Account Setup & Verification</h2>
      <p>Confirmation of your account details.</p>

      <div class="form-grid">
  
<!-- STEP 2 -->
    <div class="input-with-btn">
      <input 
        type="text" 
        id="contactInput" 
        placeholder="Please enter your recovery email or phone" 
        readonly>
      <button type="button" id="verifyBtn" class="verify-btn">Verify</button>
    </div>

    <!-- MODAL -->
    <div class="modal-overlay" id="methodModal" style="display:none;">
      <div class="method-modal">
        <div class="method-header">
          <h2>Choose a Recovery Method</h2>
        </div>
        <div class="method-body">
          <div class="method-option" id="mobileOption">
            <img src="https://img.icons8.com/ios-filled/50/3C87C4/smartphone.png" alt="Smartphone">
            <span>Mobile Number</span>
          </div>
          <div class="method-option" id="googleOption">
        <img src="https://img.icons8.com/color/48/google-logo.png" alt="Google">
        <span>Google Account</span>
      </div>
      <divider>
        </div>
        <div class="method-footer">
          <button id="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" required>
          I certify that the information and documents I submitted are true and correct. <span class="required">*</span>
        </label>
      </div>
    </section>

    <!-- STEP 3 -->
<section class="step-content" data-step="3">

  <div class="review-section">
    <h2>I. KK Profile</h2>
    <div class="review-grid">
      <p><strong>Last Name:</strong> <span id="review_lastname"></span></p>
      <p><strong>Given Name:</strong> <span id="review_givenname"></span></p>
      <p><strong>Middle Name:</strong> <span id="review_middlename"></span></p>
      <p><strong>Suffix:</strong> <span id="review_suffix"></span></p>
      <p><strong>Address:</strong> <span id="review_address"></span></p>
      <p><strong>Date of Birth:</strong> <span id="review_dob"></span></p>
      <p><strong>Sex:</strong> <span id="review_sex"></span></p>
      <p><strong>Email:</strong> <span id="review_email"></span></p>
      <p><strong>Contact No.:</strong> <span id="review_contact"></span></p>
    </div>

    <h2>II. Demographics</h2>
    <div class="review-grid">
      <p><strong>Civil Status:</strong> <span id="review_civil"></span></p>
      <p><strong>Education:</strong> <span id="review_education"></span></p>
      <p><strong>Work Status:</strong> <span id="review_work"></span></p>
      <p><strong>Youth Classification:</strong> <span id="review_youth"></span></p>
      <p><strong>SK Voter:</strong> <span id="review_sk"></span></p>
    </div>

    <h2>III. Document</h2>
    <div class="review-grid">
      <p><strong>Role:</strong> <span id="review_role"></span></p>
      <p><strong>Uploaded Files:</strong> <span id="review_files"></span></p>
    </div>

    
  </div>

  <div class="checkbox-group">
    <label>
      <input type="checkbox" required>
      I certify that the information and documents I submitted are true and correct. <span class="required">*</span>
    </label>

    <label>
      <input type="checkbox" required>
      I confirm that I have reviewed my information and this will be my final submission. <span class="required">*</span>
    </label>
  </div>
</section>



    <!-- Form Actions -->
<div class="form-actions">
  <button type="button" class="back-btn">Back
  </button>

  <button type="button" class="next-btn">Next</button>
</div>


  </form>
</section>

<!-- Footer Section -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-logo">
      <img src="{{ asset('images/sklogo.png') }}" alt="KatiBayan Logo" class="logo-img">
      <span>Sangguniang Kabataan</span>
    </div>

    <div class="footer-contact">
      <h3>CONTACT INFORMATION</h3>
      <p>Address: SK Office, Barangay 3, EM’s Barrio East, Legazpi City, Albay</p>
      <p>Email: skbrrgy3emsbarrioeast@gmail.com</p>
      <p>Mobile: 09XX-XXX-XXXX</p>
      <p>Facebook Page: <a href="#">SK Brgy. 3 EM's Barrio East, Legazpi City</a></p>
    </div>

    <div class="footer-links">
      <h3>QUICK LINKS</h3>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Programs & Events</a></li>
        <li><a href="#">Committees</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>

    <div class="footer-legal">
      <h3>LEGAL</h3>
      <ul>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms and Conditions</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2025 SK Barangay 3 | All Rights Reserved | Powered by KatiBayan</p>
  </div>
</footer>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
lucide.createIcons();

const themeToggle = document.getElementById("themeToggle");
const body = document.body;

// ---- THEME TOGGLE ----
const savedTheme = localStorage.getItem("theme");
function applyTheme(isDark) {
  if (isDark) {
    body.classList.add("dark-mode");
    themeToggle.innerHTML = `<i data-lucide="moon"></i>`;
  } else {
    body.classList.remove("dark-mode");
    themeToggle.innerHTML = `<i data-lucide="sun"></i>`;
  }
  lucide.createIcons();
  localStorage.setItem("theme", isDark ? "dark" : "light");
}
applyTheme(savedTheme === "dark");

themeToggle?.addEventListener("click", () => {
  const isDark = !body.classList.contains("dark-mode");
  applyTheme(isDark);
});

// ---- DATE PICKER ICON ----
document.querySelectorAll(".input-icon").forEach(wrapper => {
  const dateInput = wrapper.querySelector('input[type="date"]');
  const calendarIcon = wrapper.querySelector(".icon");
  if (dateInput && calendarIcon) {
    calendarIcon.addEventListener("click", () => {
      if (dateInput.showPicker) dateInput.showPicker();
      else dateInput.focus();
    });
  }
});

// ---- TERMS MODAL ----
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("termsModal");
  const acceptBtn = document.getElementById("acceptBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  if (modal) {
    modal.style.display = "flex";
    acceptBtn?.addEventListener("click", () => modal.style.display = "none");
    cancelBtn?.addEventListener("click", () => {
      window.location.href = "/loginpage";
    });
  }
});

// ---- CUSTOM SELECT DROPDOWNS ----
document.querySelectorAll(".select-wrapper").forEach(wrapper => {
  const input = wrapper.querySelector("input");
  const dropdown = wrapper.querySelector(".dropdown-options");
  if (!input || !dropdown) return;

  input.addEventListener("click", () => {
    document.querySelectorAll(".select-wrapper").forEach(w => {
      if (w !== wrapper) {
        w.classList.remove("open");
        const otherDropdown = w.querySelector(".dropdown-options");
        if (otherDropdown) otherDropdown.style.display = "none";
      }
    });
    wrapper.classList.toggle("open");
    dropdown.style.display = wrapper.classList.contains("open") ? "block" : "none";
  });

  dropdown.querySelectorAll("li").forEach(option => {
    option.addEventListener("click", () => {
      input.value = option.textContent;
      wrapper.classList.remove("open");
      dropdown.style.display = "none";
    });
  });
});

document.addEventListener("click", e => {
  if (!e.target.closest(".select-wrapper")) {
    document.querySelectorAll(".select-wrapper").forEach(wrapper => {
      wrapper.classList.remove("open");
      const dropdown = wrapper.querySelector(".dropdown-options");
      if (dropdown) dropdown.style.display = "none";
    });
  }
});

// ---- MULTI-STEP FORM ----
document.addEventListener("DOMContentLoaded", () => {
  let currentStep = 1;
  const steps = document.querySelectorAll(".step-content");
  const progressSteps = document.querySelectorAll(".step");
  const backBtn = document.querySelector(".back-btn");
  const nextBtn = document.querySelector(".next-btn");

  // gawin editable yung step2_email palagi
  document.getElementById("step2_email")?.removeAttribute("readonly");

  function showStep(step) {
    steps.forEach((s, i) => s.classList.toggle("active", i === step - 1));

    progressSteps.forEach((p, i) => {
      p.classList.toggle("active", i < step);
      p.classList.toggle("completed", i < step - 1);
    });

    if (step === 1) {
      backBtn.style.display = "inline-flex";
      backBtn.onclick = () => { window.location.href = "/loginpage"; };
    } else {
      backBtn.style.display = "inline-flex";
      backBtn.onclick = e => {
        e.preventDefault();
        if (currentStep > 1) {
          currentStep--;
          showStep(currentStep);
        }
      };
    }

    
if (step === steps.length) {
  nextBtn.innerText = "Submit";
  nextBtn.type = "submit"; 
} else {
  nextBtn.innerText = "Next";
  nextBtn.type = "button";
}
}

  // NEXT BUTTON
  nextBtn.addEventListener("click", () => {
    if (currentStep === 2) {
      fillStep3();
    }
    if (currentStep < steps.length) {
      currentStep++;
      showStep(currentStep);
    }
  });

  showStep(currentStep);
});

// ---- STEP 2 ROLE & FILES LOGIC ----
document.addEventListener("DOMContentLoaded", () => {
  const roleInputEl = document.querySelector(".select-wrapper input[name='role']");
  if (!roleInputEl) return;

  const roleWrapper = roleInputEl.closest(".select-wrapper");
  const roleInput = roleWrapper?.querySelector("input[name='role']");
  const roleOptions = roleWrapper?.querySelectorAll("ul li") || [];
  const fileSections = document.querySelectorAll(".file-section");

  if (roleInput) roleInput.value = "";
  fileSections.forEach(section => section.style.display = "none");

  const roleSections = {
    "SK": ["documentUpload1"],
    "KK": ["documentUpload2"]
  };

  roleOptions.forEach(option => {
    option.addEventListener("click", () => {
      const selectedRole = option.textContent.trim();
      if (roleInput) roleInput.value = selectedRole;

      fileSections.forEach(section => section.style.display = "none");

      if (roleSections[selectedRole]) {
        roleSections[selectedRole].forEach(id => {
          const fileSection = document.getElementById(id)?.closest(".file-section");
          if (fileSection) fileSection.style.display = "block";
        });
      }
    });
  });
});

// ---- VERIFY MODAL ----
document.addEventListener("DOMContentLoaded", () => {
  const contactInput = document.getElementById("contactInput");
  const methodModal  = document.getElementById("methodModal");
  const closeModal   = document.getElementById("closeModal");

  // open on input click
  contactInput?.addEventListener("click", () => {
    methodModal.style.display = "flex";
  });

  // close on button
  closeModal?.addEventListener("click", () => {
    methodModal.style.display = "none";
  });

  // close on backdrop
  methodModal?.addEventListener("click", (e) => {
    if (e.target === methodModal) methodModal.style.display = "none";
  });
});




// ---- FILE UPLOAD LABEL UPDATE ----
function setupFileUpload(inputId, fileTextId) {
  const input = document.getElementById(inputId);
  const text = document.getElementById(fileTextId);
  if (input && text) {
    input.addEventListener("change", () => {
      text.textContent = input.files.length > 0
        ? input.files[0].name
        : "Accepted: PDF, max 5 MB";
    });
  }
}
setupFileUpload("documentUpload1", "fileText1");
setupFileUpload("documentUpload2", "fileText2");

// ---- STEP 3 REVIEW ----
function fillStep3() {
  document.getElementById("review_lastname").textContent =
    document.getElementById("step1_lastname")?.value || "";
  document.getElementById("review_givenname").textContent =
    document.getElementById("step1_givenname")?.value || "";
  document.getElementById("review_middlename").textContent =
    document.getElementById("step1_middlename")?.value || "";
  document.getElementById("review_suffix").textContent =
    document.getElementById("step1_suffix")?.value || "";
  document.getElementById("review_address").textContent =
    document.getElementById("step1_address")?.value || "";
  document.getElementById("review_dob").textContent =
    document.getElementById("step1_dob")?.value || "";
  document.getElementById("review_sex").textContent =
    document.getElementById("step1_sex")?.value || "";
  document.getElementById("review_email").textContent =
    document.getElementById("step1_email")?.value || "";
  document.getElementById("review_contact").textContent =
    document.querySelector("input[type='tel']")?.value || "";

  document.getElementById("review_civil").textContent =
    document.getElementById("step1_civil")?.value || "";
  document.getElementById("review_education").textContent =
    document.querySelector("input[name='education']")?.value || "";
  document.getElementById("review_work").textContent =
    document.querySelector("input[name='work_status']")?.value || "";
  document.getElementById("review_youth").textContent =
    document.querySelector("input[name='youth_classification']")?.value || "";
  document.getElementById("review_sk").textContent =
    document.querySelector("input[name='sk_voter']")?.value || "";


  document.getElementById("review_role").textContent =
    document.querySelector("input[name='role']")?.value || "";

  const doc1 = document.getElementById("documentUpload1")?.files[0]?.name;
  const doc2 = document.getElementById("documentUpload2")?.files[0]?.name;
  let filesText = "";
  if (doc1) filesText += `File 1: ${doc1} `;
  if (doc2) filesText += `File 2: ${doc2}`;
  document.getElementById("review_files").textContent =
    filesText || "No files uploaded";
}
</script>




</body>
</html>
