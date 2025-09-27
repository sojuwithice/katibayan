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
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://accounts.google.com/gsi/client" async defer></script>
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
  <form id="multiStepForm" class="register-form" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Hidden fields for storing location IDs -->
    <input type="hidden" name="region_id" id="regionId">
    <input type="hidden" name="province_id" id="provinceId">
    <input type="hidden" name="city_id" id="cityId">
    <input type="hidden" name="barangay_id" id="barangayId">
    
    <input type="hidden" name="current_step" id="currentStep" value="1">

    <!-- STEP 1 -->
    <section class="step-content" data-step="1">
      <div class="profile-section">
        <div class="profile-header">
          <div>
            <h3>I. Account Profile</h3>
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

        <div class="form-grid">
          <!-- Region -->
          <div class="select-wrapper">
            <input type="text" id="regionInput" placeholder="-- Select Region --" readonly required>
            <ul class="dropdown-options">
              @foreach($regions as $region)
                <li data-id="{{ $region->id }}">{{ $region->name }}</li>
              @endforeach
            </ul>
            <span class="arrow"><i data-lucide="chevron-down"></i></span>
          </div>

          <!-- Province -->
          <div class="select-wrapper">
            <input type="text" id="provinceInput" placeholder="-- Select Province --" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down"></i></span>
          </div>
          
          <!-- City/Municipality -->
          <div class="select-wrapper">
            <input type="text" id="cityInput" placeholder="-- Select City/Municipality --" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down"></i></span>
          </div>

          <!-- Barangay -->
          <div class="select-wrapper">
            <input type="text" id="barangayInput" placeholder="-- Select Barangay --" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down"></i></span>
          </div>
        </div>

        <div class="form-grid">
          <!-- Zip Code -->
          <input type="text" id="step1_zip" name="zip_code" placeholder="Zip Code" required>

          <!-- Purok/Zone -->
          <input type="text" id="step1_purok" name="purok_zone" placeholder="Purok/Zone" required>
        </div>

        <div class="form-grid-4">
          <div class="input-icon">
            <input type="date" id="step1_dob" name="date_of_birth" placeholder="Date of Birth" required>
            <span class="icon" id="calendarIcon"><i data-lucide="calendar"></i></span>
          </div>

          <div class="select-wrapper">
            <input type="text" id="step1_sex" name="sex" placeholder="Sex" readonly required>
            <span class="arrow"><i data-lucide="chevron-down"></i></span>
            <ul class="dropdown-options">
              <li data-value="male">Male</li>
              <li data-value="female">Female</li>
            </ul>
          </div>

          <input type="email" id="step1_email" name="email" placeholder="Email Address" required>
          <input type="tel" name="contact_no" placeholder="Contact No." required>
        </div>
      </div>

      <div class="section-header">
        <h3>II. Demographics</h3>
      </div>
      <div class="form-grid">
        <div class="select-wrapper">
          <input type="text" id="step1_civil" name="civil_status" placeholder="Civil Status" readonly required>
          <ul class="dropdown-options">
            <li data-value="Single">Single</li>
            <li data-value="Married">Married</li>
            <li data-value="Widowed">Widowed</li>
            <li data-value="Divorced">Divorced</li>
            <li data-value="Separated">Separated</li>
            <li data-value="Anulled">Anulled</li>
            <li data-value="Unknown">Unknown</li>
            <li data-value="Live-in">Live-in</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
        <div class="select-wrapper">
          <input type="text" name="education" placeholder="Educational Background" readonly required>
          <ul class="dropdown-options">
           <li data-value="Elementary Level">Elementary Level</li>
            <li data-value="Elementary Graduate">Elementary Graduate</li>
            <li data-value="High School Level">High School Level</li>
            <li data-value="High School Graduate">High School Graduate</li>
            <li data-value="Vocational Graduate">Vocational Graduate</li>
            <li data-value="College Level">College Level</li>
            <li data-value="College Graduate">College Graduate</li>
            <li data-value="Masters Level">Masters Level</li>
            <li data-value="Masters Graduate">Masters Graduate</li>
            <li data-value="Doctorate Level">Doctorate Level</li>
            <li data-value="Doctorate Graduate">Doctorate Graduate</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
        <div class="select-wrapper">
          <input type="text" name="work_status" placeholder="Work Status" readonly required>
          <ul class="dropdown-options">
            <li data-value="Student">Student</li>
            <li data-value="Employed">Employed</li>
            <li data-value="Unemployed">Unemployed</li>
            <li data-value="Self-Employed">Self-Employed</li>
            <li data-value="Currently looking for a Job">Currently looking for a Job</li>
            <li data-value="Not Interested Looking for a Job">Not Interested Looking for a Job</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
      </div>

      <div class="form-grid">
        <div class="select-wrapper">
          <input type="text" name="youth_classification" placeholder="Youth Classification" readonly required>
          <ul class="dropdown-options">
           <li data-value="In-School Youth">In-School Youth</li>
            <li data-value="Out-of-School Youth">Out-of-School Youth</li>
            <li data-value="Working Youth">Working Youth</li>
            <li data-value="Youth with Specific Needs">Youth with Specific Needs</li>
            <li data-value="Person with Disability (PWD)">Person with Disability (PWD)</li>
            <li data-value="Children in Conflict with the Law (CICL)">Children in Conflict with the Law (CICL)</li>
            <li data-value="Indigenous People (IP)">Indigenous People (IP)</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>

        <div class="select-wrapper">
          <input type="text" name="sk_voter" placeholder="Are you a registered SK voter?" readonly required>
          <ul class="dropdown-options">
           <li data-value="Yes">Yes</li>
            <li data-value="No">No</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down"></i></span>
        </div>
      </div>
    </section>

    <!-- STEP 2 -->
    <section class="step-content" data-step="2">
      <h2>III. Verification Document</h2>
      <div class="select-wrapper short-select">
        <input type="text" name="role" placeholder="Select your role (SK, KK, etc)" readonly required>
        <ul class="dropdown-options">
         <li data-value="sk">SK</li>
          <li data-value="kk">KK</li>
        </ul>
        <span class="arrow"><i data-lucide="chevron-down"></i></span>
      </div>

      <!-- SK Specific Fields -->
      <div id="skFields" style="display: none;">
        <div class="file-section">
          <p>Upload Oath Taking Certificate</p>
          <div class="upload-box">
            <label for="oath_certificate" class="upload-label">Choose File</label>
            <input type="file" id="oath_certificate" name="oath_certificate" accept=".pdf" required hidden>
            <span id="fileText1" class="file-text">Accepted: PDF, max 5 MB</span>
          </div>
        </div>
      </div>

      <!-- KK Specific Fields -->
      <div id="kkFields" style="display: none;">
        <div class="file-section">
          <p>Upload Barangay Indigency or Valid ID with Full Address</p>
          <div class="upload-box">
            <label for="barangay_indigency" class="upload-label">Choose File</label>
            <input type="file" id="barangay_indigency" name="barangay_indigency" accept=".pdf" required hidden>
            <span id="fileText3" class="file-text">Accepted: PDF, max 5 MB</span>
          </div>
        </div>
      </div>

      <h2>IV. Account Setup & Verification</h2>
      <p>Confirmation of your account details.</p>

      <div class="form-grid">
  <div class="input-with-btn">
    <input 
      type="text" 
      id="contactInput" 
      placeholder="Please enter your recovery email or phone" 
      readonly>
    <button type="button" id="openMethodBtn" class="verify-btn">Verify</button>
  </div>
</div>

<!-- MODAL: Choose Method -->
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
    </div>
    <div class="method-footer">
      <button id="closeModal">Close</button>
    </div>
  </div>
</div>

<!-- ENTER CODE MODAL -->
<div class="modal-overlay" id="codeModal" style="display:none;">
  <div class="code-modal">
    <h2>Enter the code</h2>
    <p id="codeMessage"></p>
    <div class="code-inputs">
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
    </div>
    <p class="resend">Didn’t get the code? Tap <a href="#" id="resendLink">Resend</a></p>
    <a href="#" id="chooseMethod">choose another method</a>
    <button id="codeVerifyBtn">Verify</button>
  </div>
</div>


      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="certify_info" required>
          I certify that the information and documents I submitted are true and correct. <span class="required">*</span>
        </label>
      </div>
    </section>

    <!-- STEP 3 -->
    <section class="step-content" data-step="3">
      <div class="review-section">
        <h2>I. Profile</h2>
        <div class="review-grid">
          <p><strong>Last Name:</strong> <span id="review_lastname"></span></p>
          <p><strong>Given Name:</strong> <span id="review_givenname"></span></p>
          <p><strong>Middle Name:</strong> <span id="review_middlename"></span></p>
          <p><strong>Suffix:</strong> <span id="review_suffix"></span></p>
          <p><strong>Region:</strong> <span id="review_region"></span></p>
          <p><strong>Province:</strong> <span id="review_province"></span></p>
          <p><strong>City/Municipality:</strong> <span id="review_city"></span></p>
          <p><strong>Barangay:</strong> <span id="review_barangay"></span></p>
          <p><strong>Zip Code:</strong> <span id="review_zip"></span></p>
          <p><strong>Purok/Zone:</strong> <span id="review_purok"></span></p>
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
          <input type="checkbox" name="certify_final" required>
          I certify that the information and documents I submitted are true and correct. <span class="required">*</span>
        </label>

        <label>
          <input type="checkbox" name="confirm_submission" required>
          I confirm that I have reviewed my information and this will be my final submission. <span class="required">*</span>
        </label>
      </div>
    </section>

    <!-- Form Actions -->
    <div class="form-actions">
      <button type="button" class="back-btn">Back</button>
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
      <p>Address: SK Office, Barangay 3, EM's Barrio East, Legazpi City, Albay</p>
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

  dropdown?.querySelectorAll("li").forEach(option => {
    option.addEventListener("click", () => {
      // Use data-value if present, else use text
      input.value = option.getAttribute("data-value") || option.textContent;

      // Close dropdown
      wrapper.classList.remove("open");
      dropdown.style.display = "none";

      // Show/hide role-specific fields
      if (input.name === "role") {
        const skFields = document.getElementById("skFields");
        const kkFields = document.getElementById("kkFields");

        if (input.value.toLowerCase() === "sk") {
          skFields.style.display = "block";
          kkFields.style.display = "none";
        } else if (input.value.toLowerCase() === "kk") {
          skFields.style.display = "none";
          kkFields.style.display = "block";
        } else {
          skFields.style.display = "none";
          kkFields.style.display = "none";
        }
      }
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
  const form = document.getElementById("multiStepForm");
  const currentStepInput = document.getElementById("currentStep");

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
          currentStepInput.value = currentStep;
        }
      };
    }

    if (step === steps.length) {
      nextBtn.innerText = "Submit";
      nextBtn.type = "button";
      nextBtn.onclick = () => {
        // Validate all required fields are filled
        if (validateStep3()) {
          form.submit();
        }
      };
    } else {
      nextBtn.innerText = "Next";
      nextBtn.type = "button";
      nextBtn.onclick = () => {
        if (currentStep === 1 && !validateStep1()) {
          return;
        }
        if (currentStep === 2) {
          fillStep3();
        }
        if (currentStep < steps.length) {
          currentStep++;
          showStep(currentStep);
          currentStepInput.value = currentStep;
        }
      };
    }
  }

  function validateStep1() {
    const regionId = document.getElementById('regionId').value;
    const provinceId = document.getElementById('provinceId').value;
    const cityId = document.getElementById('cityId').value;
    const barangayId = document.getElementById('barangayId').value;
    
    if (!regionId || !provinceId || !cityId || !barangayId) {
      alert('Please complete all location fields (Region, Province, City, Barangay)');
      return false;
    }
    return true;
  }

  function validateStep3() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (let checkbox of checkboxes) {
      if (checkbox.required && !checkbox.checked) {
        alert('Please check all required checkboxes');
        return false;
      }
    }
    return true;
  }

  showStep(currentStep);
});

// ---- VERIFY MODAL ----
document.addEventListener("DOMContentLoaded", () => {
  const contactInput = document.getElementById("contactInput");
  const methodModal  = document.getElementById("methodModal");
  const closeModal   = document.getElementById("closeModal");
  const mobileOption = document.getElementById("mobileOption");
  const googleOption = document.getElementById("googleOption");

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

  // handle option clicks
  mobileOption?.addEventListener("click", () => {
    contactInput.value = "Mobile Number";
    methodModal.style.display = "none";
  });

  googleOption?.addEventListener("click", () => {
    contactInput.value = "Google Account";
    methodModal.style.display = "none";
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
setupFileUpload("oath_certificate", "fileText1");
setupFileUpload("barangay_indigency", "fileText3");

// ---- LOCATION DROPDOWNS WITH ID STORAGE ----
document.addEventListener("DOMContentLoaded", () => {
  const regionInput = document.getElementById("regionInput");
  const provinceInput = document.getElementById("provinceInput");
  const cityInput = document.getElementById("cityInput");
  const barangayInput = document.getElementById("barangayInput");

  // Hidden input fields for IDs
  const regionIdInput = document.getElementById("regionId");
  const provinceIdInput = document.getElementById("provinceId");
  const cityIdInput = document.getElementById("cityId");
  const barangayIdInput = document.getElementById("barangayId");

  const regionDropdown = regionInput.nextElementSibling;
  const provinceDropdown = provinceInput.nextElementSibling;
  const cityDropdown = cityInput.nextElementSibling;
  const barangayDropdown = barangayInput.nextElementSibling;

  function closeAllDropdowns() {
    [regionDropdown, provinceDropdown, cityDropdown, barangayDropdown].forEach(dd => dd.style.display = "none");
  }

  function openDropdown(input, dropdown) {
    closeAllDropdowns();
    dropdown.style.display = "block";
  }

  // Show dropdown on input click
  [regionInput, provinceInput, cityInput, barangayInput].forEach((input, i) => {
    input.addEventListener("click", e => {
      e.stopPropagation();
      const dropdown = input.nextElementSibling;
      if (!input.disabled) openDropdown(input, dropdown);
    });
  });

  // Event delegation for dropdown items with ID storage
  function setupDropdownSelection(parentDropdown, input, hiddenInput, fetchNext = null) {
    parentDropdown.addEventListener("click", e => {
      if (e.target.tagName === "LI") {
        input.value = e.target.textContent;
        
        // Store the ID in the hidden input field
        if (hiddenInput) {
          hiddenInput.value = e.target.dataset.id;
        }

        // Reset lower-level inputs
        if (fetchNext) fetchNext(e.target.dataset.id);

        closeAllDropdowns();
      }
    });
  }

  // Region → Province
  setupDropdownSelection(regionDropdown, regionInput, regionIdInput, regionId => {
    provinceInput.value = "";
    provinceInput.disabled = false;
    provinceDropdown.innerHTML = "";
    provinceIdInput.value = "";
    
    cityInput.value = "";
    cityInput.disabled = true;
    cityDropdown.innerHTML = "";
    cityIdInput.value = "";
    
    barangayInput.value = "";
    barangayInput.disabled = true;
    barangayDropdown.innerHTML = "";
    barangayIdInput.value = "";

    fetch(`/get-provinces/${regionId}`)
      .then(res => res.json())
      .then(provinces => {
        provinces.forEach(p => {
          const li = document.createElement("li");
          li.textContent = p.name;
          li.dataset.id = p.id;
          provinceDropdown.appendChild(li);
        });
      })
      .catch(error => console.error('Error fetching provinces:', error));
  });

  // Province → City
  setupDropdownSelection(provinceDropdown, provinceInput, provinceIdInput, provinceId => {
    cityInput.value = "";
    cityInput.disabled = false;
    cityDropdown.innerHTML = "";
    cityIdInput.value = "";
    
    barangayInput.value = "";
    barangayInput.disabled = true;
    barangayDropdown.innerHTML = "";
    barangayIdInput.value = "";

    fetch(`/get-cities/${provinceId}`)
      .then(res => res.json())
      .then(cities => {
        cities.forEach(c => {
          const li = document.createElement("li");
          li.textContent = c.name;
          li.dataset.id = c.id;
          cityDropdown.appendChild(li);
        });
      })
      .catch(error => console.error('Error fetching cities:', error));
  });

  // City → Barangay
  setupDropdownSelection(cityDropdown, cityInput, cityIdInput, cityId => {
    barangayInput.value = "";
    barangayInput.disabled = false;
    barangayDropdown.innerHTML = "";
    barangayIdInput.value = "";

    fetch(`/get-barangays/${cityId}`)
      .then(res => res.json())
      .then(barangays => {
        barangays.forEach(b => {
          const li = document.createElement("li");
          li.textContent = b.name;
          li.dataset.id = b.id;
          barangayDropdown.appendChild(li);
        });
      })
      .catch(error => console.error('Error fetching barangays:', error));
  });

  // Barangay selection
  setupDropdownSelection(barangayDropdown, barangayInput, barangayIdInput);

  // Close dropdowns on click outside
  document.addEventListener("click", closeAllDropdowns);
});

// ---- STEP 3 REVIEW ----
function fillStep3() {
  // Personal Information
  document.getElementById("review_lastname").textContent =
    document.getElementById("step1_lastname")?.value || "";
  document.getElementById("review_givenname").textContent =
    document.getElementById("step1_givenname")?.value || "";
  document.getElementById("review_middlename").textContent =
    document.getElementById("step1_middlename")?.value || "";
  document.getElementById("review_suffix").textContent =
    document.getElementById("step1_suffix")?.value || "";
  
  // Location Information
  document.getElementById("review_region").textContent =
    document.getElementById("regionInput")?.value || "";
  document.getElementById("review_province").textContent =
    document.getElementById("provinceInput")?.value || "";
  document.getElementById("review_city").textContent =
    document.getElementById("cityInput")?.value || "";
  document.getElementById("review_barangay").textContent =
    document.getElementById("barangayInput")?.value || "";
  document.getElementById("review_zip").textContent =
    document.getElementById("step1_zip")?.value || "";
  document.getElementById("review_purok").textContent =
    document.getElementById("step1_purok")?.value || "";
  
  // Personal Details
  document.getElementById("review_dob").textContent =
    document.getElementById("step1_dob")?.value || "";
  document.getElementById("review_sex").textContent =
    document.getElementById("step1_sex")?.value || "";
  document.getElementById("review_email").textContent =
    document.getElementById("step1_email")?.value || "";
  document.getElementById("review_contact").textContent =
    document.querySelector("input[name='contact_no']")?.value || "";

  // Demographics
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

  // Role and Documents
  document.getElementById("review_role").textContent =
    document.querySelector("input[name='role']")?.value || "";

  const oathCert = document.getElementById("oath_certificate")?.files[0]?.name;
  const barangayInd = document.getElementById("barangay_indigency")?.files[0]?.name;
  
  let filesText = "";
  if (oathCert) filesText += `Oath Certificate: ${oathCert} `;
  if (barangayInd) filesText += `Barangay Indigency: ${barangayInd}`;
  
  document.getElementById("review_files").textContent =
    filesText || "No files uploaded";
}

document.addEventListener("DOMContentLoaded", () => {
  const methodModal = document.getElementById("methodModal");
  const codeModal = document.getElementById("codeModal");
  const contactInput = document.getElementById("contactInput");
  const openMethodBtn = document.getElementById("openMethodBtn");
  const closeModal = document.getElementById("closeModal");
  const mobileOption = document.getElementById("mobileOption");
  const googleOption = document.getElementById("googleOption");
  const codeMessage = document.getElementById("codeMessage");
  const chooseMethod = document.getElementById("chooseMethod");
  const codeVerifyBtn = document.getElementById("codeVerifyBtn");
  const resendLink = document.getElementById("resendLink");
  const codeBoxes = document.querySelectorAll(".code-box");

  let selectedMethod = null;
  let currentMobile = "";

  // === Move focus between OTP boxes ===
  codeBoxes.forEach((box, index) => {
    box.addEventListener("input", (e) => {
      if (e.target.value.length === 1 && index < codeBoxes.length - 1) {
        codeBoxes[index + 1].focus();
      }
    });

    box.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !e.target.value && index > 0) {
        codeBoxes[index - 1].focus();
      }
    });
  });

  // === Verify Button (open method or send OTP) ===
  openMethodBtn.addEventListener("click", () => {
    if (!selectedMethod) {
      methodModal.style.display = "flex";
    } else if (selectedMethod === "mobile") {
      if (contactInput.value.trim() === "") {
        alert("Please enter your mobile number first.");
        return;
      }
      currentMobile = contactInput.value.trim();

      // Call backend to send OTP
      sendOtp(currentMobile).then((res) => {
        if (res.message) {
          codeMessage.innerHTML = `We sent a code to your mobile <b>${currentMobile}</b>`;
          codeBoxes.forEach((b) => (b.value = "")); // clear old inputs
          codeBoxes[0].focus();
          codeModal.style.display = "flex";
        }
      }).catch(() => alert("Failed to send OTP."));
    }
  });

  // === Close Method Modal ===
  closeModal.addEventListener("click", () => {
    methodModal.style.display = "none";
  });

  // === Mobile Option ===
  mobileOption.addEventListener("click", () => {
    methodModal.style.display = "none";
    contactInput.removeAttribute("readonly");
    contactInput.type = "tel";
    contactInput.placeholder = "+63 9XX XXX XXXX";
    contactInput.value = "";
    contactInput.focus();
    selectedMethod = "mobile";
  });

  // === Google Option (dummy for now) ===
  googleOption.addEventListener("click", () => {
    methodModal.style.display = "none";
    contactInput.setAttribute("readonly", true);

    const accounts = ["m****n673@gmail.com", "demo.account@gmail.com"];
    let account = prompt("Choose Google Account:\n" + accounts.join("\n"));

    if (account && accounts.includes(account)) {
      contactInput.value = account;
      selectedMethod = "google";

      codeMessage.innerHTML = `We sent a code to your email <b>${account}</b>`;
      codeBoxes.forEach((b) => (b.value = ""));
      codeBoxes[0].focus();
      codeModal.style.display = "flex";
    } else {
      alert("Please select a valid account.");
    }
  });

  // === Choose Another Method ===
  chooseMethod.addEventListener("click", (e) => {
    e.preventDefault();
    codeModal.style.display = "none";
    methodModal.style.display = "flex";
    selectedMethod = null;
    contactInput.value = "";
    contactInput.setAttribute("readonly", true);
    contactInput.type = "text";
    contactInput.placeholder = "Please enter your recovery email or phone";
  });

  // === Resend OTP ===
  resendLink.addEventListener("click", (e) => {
    e.preventDefault();
    if (selectedMethod === "mobile" && currentMobile) {
      sendOtp(currentMobile).then((res) => {
        if (res.message) {
          alert("OTP resent!");
          codeBoxes.forEach((b) => (b.value = ""));
          codeBoxes[0].focus();
        }
      });
    }
  });

  // === Code Verify Button ===
  codeVerifyBtn.addEventListener("click", () => {
  if (selectedMethod === "mobile") {
    const otp = Array.from(codeBoxes).map((b) => b.value).join("");
    if (otp.length < 6) {
      alert("Enter the 6-digit code.");
      return;
    }
    verifyOtp(currentMobile, otp)
      .then((res) => {
        if (res.status === "approved") {
          alert("OTP verified!");
          codeModal.style.display = "none";
        } else {
          alert("Invalid OTP, try again.");
        }
      })
      .catch(() => {
        alert("OTP verification failed.");
      });
  }
});



});

  



</script>
</body>
</html>