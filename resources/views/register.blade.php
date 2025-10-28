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

  <meta name="google-signin-client_id" content="559838334805-eijtdl99nj2m05ohpcasmg6p8v5v2a5e.apps.googleusercontent.com">

  <style>
    /* Remove asterisk from middle name and suffix fields */
    .input-wrapper:has(#step1_middlename)::after,
    .input-wrapper:has(#step1_suffix)::after {
      content: none !important;
    }
  </style>
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
    <i data-lucide="sun" class="lucide-icon"></i>
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
      <div class="circle"><i data-lucide="user" class="lucide-icon"></i></div>
      <p>Personal Information</p>
    </div>
    <div class="step" data-step="2">
      <div class="circle"><i data-lucide="settings" class="lucide-icon"></i></div>
      <p>Account Setup</p>
    </div>
    <div class="step" data-step="3">
      <div class="circle"><i data-lucide="check-square" class="lucide-icon"></i></div>
      <p>Verification and Review</p>
    </div>
  </div>

  <div class="steps-divider"></div>

  <!-- Form -->
  <form id="multiStepForm" class="register-form" action="{{ route('register.preview') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($errors->any())
<div class="error-container" style="background: #fee; border: 1px solid #f00; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
    <h4 style="color: #d00; margin-top: 0;">Please fix the following errors:</h4>
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
            <li style="color: #d00;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('error'))
<div class="error-message" style="background: #fee; border: 1px solid #f00; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #d00;">
    {{ session('error') }}
</div>
@endif

<!-- Real-time validation errors container -->
<div id="stepErrors" class="error-container" style="display: none; background: #fee; border: 1px solid #f00; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
    <h4 style="color: #d00; margin-top: 0;">Please fill out the required fields:</h4>
    <ul id="stepErrorsList" style="margin: 0; padding-left: 20px;"></ul>
</div>
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
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
          <div class="input-wrapper">
            <input type="text" id="step1_givenname" name="given_name" placeholder="Given Name" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
          <div class="input-wrapper">
            <input type="text" id="step1_middlename" name="middle_name" placeholder="Middle Name">
            <!-- No error for optional field -->
          </div>
          <div class="input-wrapper">
            <input type="text" id="step1_suffix" name="suffix" placeholder="Suffix (optional)">
            <!-- No error for optional field -->
          </div>
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
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <!-- Province -->
          <div class="select-wrapper">
            <input type="text" id="provinceInput" placeholder="-- Select Province --" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
          
          <!-- City/Municipality -->
          <div class="select-wrapper">
            <input type="text" id="cityInput" placeholder="-- Select City/Municipality --" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <!-- Barangay -->
          <div class="select-wrapper">
            <input type="text" id="barangayInput" placeholder="-- Select Barangay --" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
        </div>

        <div class="form-grid">
          <!-- Zip Code -->
          <div class="input-wrapper">
            <input type="text" id="step1_zip" name="zip_code" placeholder="Zip Code" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <!-- Purok/Zone -->
          <div class="input-wrapper">
            <input type="text" id="step1_purok" name="purok_zone" placeholder="Purok/Zone" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
        </div>

        <div class="form-grid-4">
          <div class="input-icon input-wrapper">
            <input type="date" id="step1_dob" name="date_of_birth" placeholder="Date of Birth" required>
            <span class="icon" id="calendarIcon"><i data-lucide="calendar" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="select-wrapper">
            <input type="text" id="step1_sex" name="sex" placeholder="Sex" readonly required>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <ul class="dropdown-options">
              <li data-value="male">Male</li>
              <li data-value="female">Female</li>
            </ul>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="input-wrapper">
            <input type="email" id="step1_email" name="email" placeholder="Email Address" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="input-wrapper">
            <input type="tel" name="contact_no" placeholder="Contact No." required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
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
          <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
          <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
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
          <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
          <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
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
          <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
          <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
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
          <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
          <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
        </div>

        <div class="select-wrapper">
          <input type="text" name="sk_voter" placeholder="Are you a registered SK voter?" readonly required>
          <ul class="dropdown-options">
           <li data-value="Yes">Yes</li>
            <li data-value="No">No</li>
          </ul>
          <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
          <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
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
    <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
    <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
  </div>

      <!-- SK Specific Fields -->
      <div id="skFields" style="display: none;">
    <div class="file-section">
      <p>Upload Oath Taking Certificate</p>
      <div class="upload-box">
        <div class="upload-row">
          <label for="oath_certificate" class="upload-label">Choose Files</label>
          <span id="fileText1" class="file-text">Accepted: PDF, PNG, JPG (up to 2 files), max 5 MB</span>
        </div>

        <input type="file" id="oath_certificate" name="oath_certificate[]" accept="application/pdf, image/png, image/jpeg" hidden multiple>

        <div class="image-preview-container" id="skPreviewContainer"></div>
      </div>

      <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
    </div>
  </div>


      <!-- KK Specific Fields -->
      <div id="kkFields" style="display: none;">
    <div class="file-section">
      <p>Upload Barangay Indigency or Valid ID with Full Address</p>
      <div class="upload-box">
        <div class="upload-row">
          <label for="barangay_indigency" class="upload-label">Choose Files</label>
          <span id="fileText3" class="file-text">Accepted: PDF, PNG, JPG (up to 2 files), max 5 MB</span>
        </div>

        <input type="file" id="barangay_indigency" name="barangay_indigency[]" accept="application/pdf, image/png, image/jpeg" hidden multiple>

        <div class="image-preview-container" id="kkPreviewContainer"></div>
      </div>

      <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
    </div>
  </div>

      

<h2>IV. Account Setup & Verification</h2>
<p>Confirmation of your account details.</p>

<div class="form-grid">
  <div class="input-with-btn">
    <input
      type="text"
      id="contactInput"
      placeholder="Click here to choose your Google account"
      readonly
      style="cursor: pointer;"
    />

    <button type="button" id="openMethodBtn" class="verify-btn">Verify</button>
  </div>
</div>



<!-- ENTER CODE MODAL -->
<div class="modal-overlay" id="codeModal" style="display:none;">
  <div class="code-modal">
    <h2>Enter the code</h2>
    <p id="codeMessage">Please enter the 6-digit code sent to your email.</p>
    <div class="code-inputs">
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
      <input type="text" maxlength="1" class="code-box" />
    </div>
    <p class="resend">
      Didn't get the code? Tap <a href="#" id="resendLink">Resend</a>
    </p>
    <button id="codeVerifyBtn" type="button">Verify</button>
  </div>
</div>


<!-- SUCCESS MODAL -->
<div class="modal-overlay" id="successModal" style="display:none;">
  <div class="success-modal">
    <div class="success-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
        <circle cx="12" cy="12" r="11" fill="#3C87C4" stroke="none"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12.5l2.5 2.5 5-5"/>
      </svg>
    </div>
    <h2>Verified</h2>
    <p>Verification complete! Please proceed to review your details.</p>
    <button id="successOkBtn">Ok</button>
  </div>
</div>


      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="certify_info" required>
          I certify that the information and documents I submitted are true and correct. <span class="required">*</span>
        </label>
        <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
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
        <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>

        <label>
          <input type="checkbox" name="confirm_submission" required>
          I confirm that I have reviewed my information and this will be my final submission. <span class="required">*</span>
        </label>
        <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
      </div>
    </section>

    <!-- Form Actions -->
    <div class="form-actions">
      <button type="button" class="back-btn">Back</button>
      <button type="button" class="next-btn">Next</button>
      
      <!-- REMOVED: reCAPTCHA container from here -->
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

<script>
// Lucide Icons Initialization with Error Handling
function initializeLucideIcons() {
    if (typeof lucide === 'undefined') {
        console.warn('Lucide not loaded, using Font Awesome fallback');
        replaceLucideIcons();
        return;
    }
    
    try {
        lucide.createIcons();
        console.log('Lucide icons initialized successfully');
    } catch (error) {
        console.error('Error initializing Lucide:', error);
        replaceLucideIcons();
    }
}

// Fallback function to replace Lucide icons with Font Awesome
function replaceLucideIcons() {
    console.log('Using Font Awesome as fallback for icons');
    
    const iconMap = {
        'sun': 'fas fa-sun',
        'moon': 'fas fa-moon',
        'user': 'fas fa-user',
        'settings': 'fas fa-cog',
        'check-square': 'fas fa-check-square',
        'chevron-down': 'fas fa-chevron-down',
        'calendar': 'fas fa-calendar',
        'arrow-left': 'fas fa-arrow-left'
    };

    document.querySelectorAll('.lucide-icon').forEach(icon => {
        const iconName = icon.getAttribute('data-lucide');
        if (iconName && iconMap[iconName]) {
            const faIcon = document.createElement('i');
            faIcon.className = iconMap[iconName];
            icon.parentNode.replaceChild(faIcon, icon);
        }
    });
}

// Initialize Lucide when DOM is ready
document.addEventListener("DOMContentLoaded", function() {
    initializeLucideIcons();
    
    // ---- THEME TOGGLE ----
    const themeToggle = document.getElementById("themeToggle");
    const body = document.body;

    const savedTheme = localStorage.getItem("theme");
    function applyTheme(isDark) {
        if (isDark) {
            body.classList.add("dark-mode");
            themeToggle.innerHTML = `<i data-lucide="moon" class="lucide-icon"></i>`;
        } else {
            body.classList.remove("dark-mode");
            themeToggle.innerHTML = `<i data-lucide="sun" class="lucide-icon"></i>`;
        }
        initializeLucideIcons();
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

                // Clear any field error for this input
                const errorSpan = wrapper.querySelector('.field-error');
                if (errorSpan) errorSpan.style.display = 'none';

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

    // ---- MULTI-STEP FORM WITH REAL-TIME VALIDATION ----
    let currentStep = 1;
    const steps = document.querySelectorAll(".step-content");
    const progressSteps = document.querySelectorAll(".step");
    const backBtn = document.querySelector(".back-btn");
    const nextBtn = document.querySelector(".next-btn");
    const form = document.getElementById("multiStepForm");
    const currentStepInput = document.getElementById("currentStep");
    const stepErrors = document.getElementById("stepErrors");
    const stepErrorsList = document.getElementById("stepErrorsList");

    // Function to validate current step
    function validateCurrentStep() {
        const currentStepElement = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        const errors = [];
        
        // Clear previous errors
        stepErrors.style.display = 'none';
        stepErrorsList.innerHTML = '';
        document.querySelectorAll('.field-error').forEach(error => error.style.display = 'none');
        
        // Check each required field
        requiredFields.forEach(field => {
            let isValid = true;
            
            if (field.type === 'checkbox') {
                isValid = field.checked;
            } else if (field.type === 'file') {
                // Special handling for file inputs - only validate if they are visible and for the selected role
                const roleInput = document.querySelector('input[name="role"]');
                const selectedRole = roleInput ? roleInput.value.toLowerCase() : '';
                
                if (field.id === 'oath_certificate' && selectedRole !== 'sk') {
                    return; // Skip SK file validation if role is not SK
                }
                if (field.id === 'barangay_indigency' && selectedRole !== 'kk') {
                    return; // Skip KK file validation if role is not KK
                }
                
                isValid = field.files.length > 0;
            } else {
                isValid = field.value.trim() !== '';
                
                // Special validation for location dropdowns
                if (field.id === 'regionInput' || field.id === 'provinceInput' || 
                    field.id === 'cityInput' || field.id === 'barangayInput') {
                    const hiddenIdField = document.getElementById(field.id.replace('Input', 'Id'));
                    isValid = hiddenIdField && hiddenIdField.value !== '';
                }
            }
            
            if (!isValid) {
                let fieldName = field.placeholder || field.name;
                // Make file field names more user-friendly
                if (field.type === 'file') {
                    if (field.id === 'oath_certificate') {
                        fieldName = 'Oath Taking Certificate';
                    } else if (field.id === 'barangay_indigency') {
                        fieldName = 'Barangay Indigency';
                    }
                }
                errors.push(fieldName);
                
                // Show individual field error
                const fieldWrapper = field.closest('.input-wrapper, .select-wrapper, .checkbox-group, .file-section');
                if (fieldWrapper) {
                    const errorSpan = fieldWrapper.querySelector('.field-error');
                    if (errorSpan) errorSpan.style.display = 'block';
                }
            }
        });
        
        return errors;
    }

    function showStep(step) {
        steps.forEach((s, i) => s.classList.toggle("active", i === step - 1));
        progressSteps.forEach((p, i) => {
            p.classList.toggle("active", i < step);
            p.classList.toggle("completed", i < step - 1);
        });

        if (step === steps.length) {
            nextBtn.innerText = "Submit";
            nextBtn.type = "button";
            
            // Fill review data
            fillStep3();
            
            nextBtn.onclick = () => {
                const errors = validateCurrentStep();
                if (errors.length === 0) {
                    // Submit form directly - this will go to preview route
                    // which then redirects to captcha page
                    form.submit();
                } else {
                    stepErrors.style.display = 'block';
                    errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        li.style.color = '#d00';
                        stepErrorsList.appendChild(li);
                    });
                }
            };
        } else {
            nextBtn.innerText = "Next";
            nextBtn.type = "button";
            nextBtn.onclick = () => {
                const errors = validateCurrentStep();
                if (errors.length > 0) {
                    stepErrors.style.display = 'block';
                    errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        li.style.color = '#d00';
                        stepErrorsList.appendChild(li);
                    });
                    return;
                }
                
                // Clear errors if validation passes
                stepErrors.style.display = 'none';
                stepErrorsList.innerHTML = '';
                
                if (currentStep === 2) fillStep3();
                if (currentStep < steps.length) {
                    currentStep++;
                    showStep(currentStep);
                    currentStepInput.value = currentStep;
                }
            };
        }
    }

    backBtn?.addEventListener("click", () => {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            currentStepInput.value = currentStep;
            
            // Clear errors when going back
            stepErrors.style.display = 'none';
            stepErrorsList.innerHTML = '';
            document.querySelectorAll('.field-error').forEach(error => error.style.display = 'none');
        }
    });

    // Add real-time validation for input fields
    document.querySelectorAll('input[required]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                const fieldWrapper = this.closest('.input-wrapper, .select-wrapper, .checkbox-group');
                if (fieldWrapper) {
                    const errorSpan = fieldWrapper.querySelector('.field-error');
                    if (errorSpan) errorSpan.style.display = 'none';
                }
            }
        });
        
        // For dropdowns, clear error when value is selected
        if (input.readOnly && input.placeholder && input.placeholder.includes('Select')) {
            input.addEventListener('click', function() {
                const fieldWrapper = this.closest('.select-wrapper');
                if (fieldWrapper) {
                    const errorSpan = fieldWrapper.querySelector('.field-error');
                    if (errorSpan) errorSpan.style.display = 'none';
                }
            });
        }
    });

    showStep(currentStep);

    // ---- FILE UPLOAD LABEL UPDATE ----
    function setupFileUpload(inputId, fileTextId) {
        const input = document.getElementById(inputId);
        const text = document.getElementById(fileTextId);
        if (input && text) {
            input.addEventListener("change", () => {
                text.textContent = input.files.length > 0
                    ? input.files[0].name
                    : "Accepted: PDF, max 5 MB";
                
                // Clear file error when file is selected
                if (input.files.length > 0) {
                    const fieldWrapper = input.closest('.file-section');
                    if (fieldWrapper) {
                        const errorSpan = fieldWrapper.querySelector('.field-error');
                        if (errorSpan) errorSpan.style.display = 'none';
                    }
                }
            });
        }
    }
    setupFileUpload("oath_certificate", "fileText1");
    setupFileUpload("barangay_indigency", "fileText3");

    // ---- LOCATION DROPDOWNS WITH ID STORAGE ----
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

                // Clear location error when selection is made
                const fieldWrapper = input.closest('.select-wrapper');
                if (fieldWrapper) {
                    const errorSpan = fieldWrapper.querySelector('.field-error');
                    if (errorSpan) errorSpan.style.display = 'none';
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

    // OTP functionality
    const contactInput = document.getElementById("contactInput");
    const verifyBtn = document.getElementById("openMethodBtn");
    

    // --- Disable Verify button initially ---
verifyBtn.disabled = true;
verifyBtn.style.opacity = "0.6";
verifyBtn.style.cursor = "not-allowed";


    const codeModal = document.getElementById("codeModal");
    const codeMessage = document.getElementById("codeMessage");
    const codeBoxes = document.querySelectorAll(".code-box");
    const codeVerifyBtn = document.getElementById("codeVerifyBtn");
    const resendLink = document.getElementById("resendLink");

    const successModal = document.getElementById("successModal");
    const successOkBtn = document.getElementById("successOkBtn");

    const clientId = document.querySelector('meta[name="google-signin-client_id"]')?.getAttribute("content");
    let resendTimer = 30;
    let resendInterval;

    // Initialize Google OAuth
    if (typeof google !== 'undefined' && google.accounts && google.accounts.oauth2) {
        const tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: clientId,
            scope: "email profile openid",
            callback: async (response) => {
                if (response.access_token) {
                    try {
                        const res = await fetch("https://www.googleapis.com/oauth2/v3/userinfo", {
                            headers: { Authorization: `Bearer ${response.access_token}` },
                        });
                        const userInfo = await res.json();
                        contactInput.value = userInfo.email;
                        // --- Enable Verify button after selecting Google account ---
verifyBtn.disabled = false;
verifyBtn.style.opacity = "1";
verifyBtn.style.cursor = "pointer";

                    } catch (error) {
                        console.error("Error fetching user info:", error);
                    }
                }
            }
        });

        // Google Account Selection (click input to choose account)
contactInput.addEventListener("click", () => {
    // Only open if verify button is still disabled (hasn’t chosen yet)
    if (verifyBtn.disabled) {
        tokenClient.requestAccessToken();
    }
});



        // Send OTP Button
        verifyBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    if (verifyBtn.disabled) return; // stop if disabled


            const email = contactInput.value.trim();
            
            if (!email) {
                // If no email, open Google Picker instead of error
                // Make sure not verified before opening
                if (!verifyBtn.disabled) {
                    tokenClient.requestAccessToken();
                }
                return; // Stop here, don't continue fetch
            }

            // If there's email, proceed to send OTP
            fetch("/send-otp", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ email }),
            })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    codeModal.style.display = "flex";
                    showMessage(`We sent a code to ${email}`);
                    clearCodeBoxes();
                    codeBoxes[0].focus();
                    startResendTimer();
                } else {
                    showMessage(data.error || "Failed to send OTP.");
                }
            })
            .catch((err) => console.error("Error sending OTP:", err));
        });

        // Resend OTP Link
        resendLink.addEventListener("click", (e) => {
            e.preventDefault();
            if (resendLink.disabled) return;

            const email = contactInput.value.trim();
            if (!email) return showMessage("Please select your Google account first.");

            fetch("/send-otp", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ email }),
            })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    showMessage(`A new code has been sent to ${email}`);
                    clearCodeBoxes();
                    codeBoxes[0].focus();
                    startResendTimer();
                } else {
                    showMessage(data.error || "Failed to resend OTP.");
                }
            })
            .catch((err) => console.error("Error resending OTP:", err));
        });

        // Verify OTP Button
        codeVerifyBtn.addEventListener("click", async (e) => {
            e.preventDefault();
            const code = Array.from(codeBoxes).map((box) => box.value).join("");
            const email = contactInput.value.trim();

            if (code.length !== 6) return showMessage("Please enter the 6-digit code.");

            try {
                const res = await fetch("/verify-otp", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ email, code: code.split("") }),
                });

                const data = await res.json();
                if (data.verified === true) {
                    codeModal.style.display = "none";
                    setTimeout(() => {
                        successModal.style.display = "flex";
                    }, 150);

                    verifyBtn.textContent = "Verified";
                    verifyBtn.disabled = true;
                    verifyBtn.classList.add("verified");
                } else {
                    showMessage(data.error || "Invalid or expired code.");
                }
            } catch (err) {
                console.error("Error verifying OTP:", err);
            }
        });

        // Success Modal OK Button
        successOkBtn.addEventListener("click", () => {
            successModal.style.display = "none";
        });

        // OTP Input Box Logic
        codeBoxes.forEach((box, index) => {
            box.addEventListener("input", (e) => {
                if (e.target.value && index < codeBoxes.length - 1) {
                    codeBoxes[index + 1].focus();
                }
            });
            box.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && !e.target.value && index > 0) {
                    codeBoxes[index - 1].focus();
                }
            });
        });

        function clearCodeBoxes() {
            codeBoxes.forEach((box) => (box.value = ""));
        }

        function startResendTimer() {
            resendLink.disabled = true;
            let timeLeft = resendTimer;
            resendLink.textContent = `Resend (${timeLeft}s)`;

            clearInterval(resendInterval);
            resendInterval = setInterval(() => {
                timeLeft--;
                resendLink.textContent = `Resend (${timeLeft}s)`;
                if (timeLeft <= 0) {
                    clearInterval(resendInterval);
                    resendLink.disabled = false;
                    resendLink.textContent = "Resend";
                }
            }, 1000);
        }

        function showMessage(msg) {
            const originalMessage = codeMessage.innerText;
            codeMessage.innerText = msg;
            setTimeout(() => {
                codeMessage.innerText = originalMessage;
            }, 5000);
        }
    } else {
        console.error('Google OAuth library not loaded');
    }

    // ---- SAVE STEP & INPUTS ON RELOAD ----

// ✅ Check if registration failed (from Laravel redirect or query param)
const registrationFailed = window.location.search.includes("failed=1") || {{ $errors->any() ? 'true' : 'false' }};

if (registrationFailed) {
    console.warn("Registration failed. Resetting progress to Step 1.");
    // Clear all saved data to ensure it always starts at Step 1
    localStorage.clear();
    currentStep = 1;
    showStep(currentStep);
    currentStepInput.value = currentStep;
} else {
    // 🔹 Restore saved step if no registration failure
    const savedStep = localStorage.getItem("currentStep");

    if (!savedStep || isNaN(savedStep) || savedStep < 1 || savedStep > steps.length) {
        currentStep = 1;
    } else {
        currentStep = parseInt(savedStep);
    }

    showStep(currentStep);
    currentStepInput.value = currentStep;
}

// ---- SAVE STEP CHANGES ----
function saveCurrentStepAfterChange() {
    localStorage.setItem("currentStep", currentStep);
}

nextBtn?.addEventListener("click", () => {
    setTimeout(saveCurrentStepAfterChange, 100);
});

backBtn?.addEventListener("click", () => {
    setTimeout(saveCurrentStepAfterChange, 100);
});

// ---- SAVE INPUT VALUES ----
const formInputs = form.querySelectorAll("input[type='text'], input[type='email'], input[type='date'], input[type='number'], input[type='hidden'], input[type='checkbox']");

// Restore saved input values
formInputs.forEach(input => {
    const savedValue = localStorage.getItem(input.name || input.id);
    if (savedValue !== null) {
        if (input.type === "checkbox") {
            input.checked = savedValue === "true";
        } else {
            input.value = savedValue;
        }
    }
});

// Save input values on change
formInputs.forEach(input => {
    input.addEventListener("input", () => {
        if (input.type === "checkbox") {
            localStorage.setItem(input.name || input.id, input.checked);
        } else {
            localStorage.setItem(input.name || input.id, input.value);
        }
    });
});

// ---- RESTORE ROLE-BASED FIELD DISPLAY ----
const roleInput = document.querySelector('input[name="role"]');
if (roleInput) {
    const roleValue = localStorage.getItem(roleInput.name || roleInput.id);
    const skFields = document.getElementById("skFields");
    const kkFields = document.getElementById("kkFields");

    if (roleValue) {
        if (roleValue.toLowerCase() === "sk") {
            skFields.style.display = "block";
            kkFields.style.display = "none";
        } else if (roleValue.toLowerCase() === "kk") {
            skFields.style.display = "none";
            kkFields.style.display = "block";
        } else {
            skFields.style.display = "none";
            kkFields.style.display = "none";
        }
    }
}

// ---- RESTORE LOCATION DROPDOWNS ----
["regionInput", "provinceInput", "cityInput", "barangayInput"].forEach(id => {
    const input = document.getElementById(id);
    const savedValue = localStorage.getItem(id);
    if (input && savedValue) input.value = savedValue;

    input?.addEventListener("input", () => localStorage.setItem(id, input.value));
});



function initFileUploadPreview(inputId, previewContainerId) {
  const input = document.getElementById(inputId);
  const previewContainer = document.getElementById(previewContainerId);

  input.addEventListener('change', () => {
    previewContainer.innerHTML = ''; // Clear previous previews

    const files = Array.from(input.files).slice(0, 2); // max 2 files

    files.forEach(file => {
      if (file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.classList.add('img-preview');
        previewContainer.appendChild(img);
      } else if (file.type === 'application/pdf') {
        const pdfDiv = document.createElement('div');
        pdfDiv.classList.add('pdf-preview');
        pdfDiv.innerHTML = `<span>PDF</span><br><small>${file.name}</small>`;
        previewContainer.appendChild(pdfDiv);
      }
    });
  });
}

// Initialize SK and KK previews
initFileUploadPreview('oath_certificate', 'skPreviewContainer');
initFileUploadPreview('barangay_indigency', 'kkPreviewContainer');

    

    // Hintayin na ma-load ang buong HTML
document.addEventListener("DOMContentLoaded", function() {

  // Dito mo ilagay lahat ng code mo
  
  const contactInput = document.querySelector("#contactInput");
  const verifyButton = document.querySelector("#openMethodBtn");

  if (contactInput && verifyButton) {
    
    console.log("Elements found!"); // Check mo sa console kung lumabas 'to
    
    verifyButton.addEventListener("click", function() {
      alert("Verify button clicked!");
      // Ilagay ang verify logic mo dito
    });

    contactInput.addEventListener("click", function() {
      alert("Input clicked!");
      // Ilagay ang logic mo para sa input dito
    });

  } else {
    console.error("Error: Hindi makita ang #contactInput or #openMethodBtn");
  }

});
});
</script>
</body>
</html>