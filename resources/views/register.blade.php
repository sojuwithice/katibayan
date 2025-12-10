<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Register</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://accounts.google.com/gsi/client" async defer></script>

  <meta name="google-signin-client_id" content="559838334805-eijtdl99nj2m05ohpcasmg6p8v5v2a5e.apps.googleusercontent.com">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  
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

<!-- Email Verification Error Modal -->
<div id="emailVerificationModal" class="modal-overlay" style="display: none;">
  <div class="modal simple-confirmation-modal">
    <div class="modal-header">
      <h2>Email Verification Required</h2>
      <button type="button" class="modal-close-btn" id="closeEmailVerificationBtn" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="modal-body">
      <div class="confirmation-icon">
        <i class="fas fa-exclamation-circle" style="color: #f44336;"></i>
      </div>
      
      <div class="confirmation-message">
        <h3>Please verify your email first!</h3>
        <p>You need to verify your email address before proceeding to the next step.</p>
        <div class="verification-steps">
          <p><i class="fas fa-envelope"></i> Step 1: Enter your email address in the "Recovery Email Address" field</p>
          <p><i class="fas fa-paper-plane"></i> Step 2: Click "Send OTP" button to receive verification code</p>
          <p><i class="fas fa-key"></i> Step 3: Enter the 6-digit code sent to your email</p>
          <p><i class="fas fa-check-circle"></i> Step 4: Click "Verify" to complete email verification</p>
        </div>
      </div>
    </div>
    
    <div class="modal-footer">
      <button type="button" id="understandEmailBtn" class="confirm-btn">
        I Understand
      </button>
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
            <label for="step1_lastname">Last Name<span class="required-asterisk">*</span></label>
            <input type="text" id="step1_lastname" name="last_name" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
          <div class="input-wrapper">
            <label for="step1_givenname">Given Name<span class="required-asterisk">*</span></label>
            <input type="text" id="step1_givenname" name="given_name" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
          <div class="input-wrapper">
            <label for="step1_middlename">Middle Name</label>
            <input type="text" id="step1_middlename" name="middle_name">
          </div>
          <div class="input-wrapper">
            <label for="step1_suffix">Suffix (optional)</label>
            <input type="text" id="step1_suffix" name="suffix">
          </div>
        </div>

        <div class="form-grid">
          <div class="select-wrapper">
            <label for="regionInput">Region<span class="required-asterisk">*</span></label>
            <input type="text" id="regionInput" readonly required>
            <ul class="dropdown-options">
              @foreach($regions as $region)
              <li data-id="{{ $region->id }}">{{ $region->name }}</li>
              @endforeach
            </ul>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="select-wrapper">
            <label for="provinceInput">Province<span class="required-asterisk">*</span></label>
            <input type="text" id="provinceInput" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="select-wrapper">
            <label for="cityInput">City/Municipality<span class="required-asterisk">*</span></label>
            <input type="text" id="cityInput" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="select-wrapper">
            <label for="barangayInput">Barangay<span class="required-asterisk">*</span></label>
            <input type="text" id="barangayInput" readonly disabled required>
            <ul class="dropdown-options"></ul>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
        </div>

        <div class="form-grid">
          <div class="input-wrapper">
            <label for="step1_zip">Zip Code<span class="required-asterisk">*</span></label>
            <input type="text" id="step1_zip" name="zip_code" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="input-wrapper">
            <label for="step1_purok">Purok/Zone<span class="required-asterisk">*</span></label>
            <input type="text" id="step1_purok" name="purok_zone" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
        </div>

        <div class="form-grid-4">
          <div class="input-icon input-wrapper">
            <label for="step1_dob">Date of Birth<span class="required-asterisk">*</span></label>
            <input type="date" id="step1_dob" name="date_of_birth" placeholder="Date of Birth" required>
            <span class="icon" id="calendarIcon"><i data-lucide="calendar" class="lucide-icon"></i></span>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="select-wrapper">
            <label for="step1_sex">Sex<span class="required-asterisk">*</span></label>
            <input type="text" id="step1_sex" name="sex" placeholder="Sex" readonly required>
            <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
            <ul class="dropdown-options">
              <li data-value="male">Male</li>
              <li data-value="female">Female</li>
            </ul>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="input-wrapper">
            <label for="step1_email">Email Address<span class="required-asterisk">*</span></label>
            <input type="email" id="step1_email" name="email" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>

          <div class="input-wrapper">
            <label for="step1_contact">Contact No.<span class="required-asterisk">*</span></label>
            <input type="tel" id="step1_contact" name="contact_no" required>
            <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
          </div>
        </div>
      </div>

      <div class="section-header">
        <h3>II. Demographics</h3>
      </div>
      <div class="form-grid">
        <div class="select-wrapper">
          <label for="step1_civil">Civil Status<span class="required-asterisk">*</span></label>
          <input type="text" id="step1_civil" name="civil_status"readonly required>
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
          <label for="step1_education">Educational Background<span class="required-asterisk">*</span></label>
          <input type="text" id="step1_education" name="education"readonly required>
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
          <label for="step1_work_status">Work Status<span class="required-asterisk">*</span></label>
          <input type="text" id="step1_work_status" name="work_status" readonly required>
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
          <label for="step1_youth_class">Youth Classification<span class="required-asterisk">*</span></label>
          <input type="text" id="step1_youth_class" name="youth_classification"readonly required>
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
          <label for="step1_sk_voter">Are you a registered SK voter?<span class="required-asterisk">*</span></label>
          <input type="text" id="step1_sk_voter" name="sk_voter" placeholder="Are you a registered SK voter?" readonly required>
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
      
      <!-- Updated role selection instructions -->
      <div class="info-note" id="roleInfoNote">
        <i data-lucide="info" class="info-icon"></i>
        <span>
          <strong>SK Chairperson:</strong> Requires admin approval (no matching needed)<br>
          <strong>KK Member:</strong> Auto-approved with instant access
        </span>
      </div>

      <div class="select-wrapper short-select">
        <label for="role_select">Select your role<span class="required-asterisk">*</span></label>
        <input type="text" id="role_select" name="role"readonly required>
        <ul class="dropdown-options">
          <li data-value="sk">SK Chairperson</li>
          <li data-value="kk">KK Member</li>
        </ul>
        <span class="arrow"><i data-lucide="chevron-down" class="lucide-icon"></i></span>
        <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">This field is required</span>
      </div>
      
      <!-- Role-specific information notes -->
      <div id="skInfoNote" class="role-info-note sk" style="display: none;">
        <i data-lucide="shield" style="color: #ffc107; margin-right: 8px;"></i>
        <strong>SK Chairperson Registration:</strong> Your account requires admin approval. You will receive login credentials via email once approved by the administrator.
      </div>
      
      <div id="kkInfoNote" class="role-info-note kk" style="display: none;">
        <i data-lucide="check-circle" style="color: #4caf50; margin-right: 8px;"></i>
        <strong>KK Member Registration:</strong> Your account will be auto-approved and you will receive login credentials immediately via email.
      </div>
      
      <!-- File upload section - CHANGED: Two separate file inputs -->
      <div id="verificationUploads" style="display: none;">
        <div class="file-section">
          <div id="uploadInstruction">
            <!-- Instruction text will be updated by JavaScript -->
          </div>
          <div class="upload-box">
            <div class="upload-row">
              <label for="verification_document" class="upload-label">Choose File</label>
              <span id="fileText" class="file-text">Accepted: PDF, PNG, JPG (1 file only), max 5 MB</span>
            </div>
            <!-- SK OATH CERTIFICATE -->
            <input type="file" id="oath_certificate" name="oath_certificate" 
             accept="application/pdf, image/png, image/jpeg, image/jpg" hidden style="display: none;">
            <!-- KK BARANGAY INDIGENCY -->
            <input type="file" id="barangay_indigency" name="barangay_indigency" 
             accept="application/pdf, image/png, image/jpeg, image/jpg" hidden style="display: none;">
            <div class="image-preview-container" id="documentPreviewContainer"></div>
          </div>
          <span id="ocrLoadingStatus" style="color: blue; font-size: 12px; margin-top: 5px; display: none;">
            Processing image for OCR...
          </span>
          <span class="field-error" style="display: none; color: #d00; font-size: 12px; margin-top: 5px;">
            This field is required
          </span>
        </div>
      </div>

      <h2>IV. Account Setup & Verification</h2>
      <p>Confirmation of your account details.</p>

      <div class="form-grid email-row">
        <div class="input-wrapper">
          <label for="emailInput">Recovery Email Address<span class="required-asterisk">*</span></label>
          <input type="email" id="emailInput" class="email-input-box" required />
        </div>
               
        <button id="sendOtpBtn" class="primary-btn">
          <span>Send OTP</span>
        </button>

        <input type="hidden" id="contactInput" name="verified_email" />
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
          <p><strong>Document Type:</strong> <span id="review_document_type"></span></p>
          <p><strong>Uploaded File:</strong> <span id="review_files"></span></p>
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
  <button type="button" class="next-btn" id="nextSubmitBtn">Next</button>
</div>
  </form>
</section>

<div id="confirmationModal" class="modal-overlay" style="display: none;">
  <div class="modal simple-confirmation-modal">
    <div class="modal-header">
      <h2>Confirm Registration</h2>
      <button type="button" class="modal-close-btn" id="closeConfirmationBtn" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="modal-body">
      <div class="confirmation-icon">
        <i class="fas fa-question-circle"></i>
      </div>
      
      <div class="confirmation-message">
        <h3>Are you sure you want to submit your registration?</h3>
        <p class="warning-text">
          <i class="fas fa-exclamation-triangle"></i> 
          Once submitted, you cannot edit your information anymore.
        </p>
      </div>
    </div>
    
    <div class="modal-footer">
      <button type="button" id="cancelSubmission" class="cancel-btn">
        Cancel
      </button>
      <button type="button" id="confirmSubmission" class="confirm-btn">
        Yes, Submit Now
      </button>
    </div>
  </div>
</div>

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
// --- START: OCR FUNCTIONS (DISABLED) ---
function matchOcrDataToForm(data) {
    let fieldsUpdated = 0;
    const fieldsMap = {
        'lastName': 'step1_lastname',
        'firstName': 'step1_givenname',
        'birthdate': 'step1_dob',
        'email': 'step1_email',
    };

    for (const dataKey in fieldsMap) {
        const inputId = fieldsMap[dataKey];
        const inputElement = document.getElementById(inputId) || document.querySelector(`[name="${inputId}"]`);
        
        if (inputElement && data[dataKey]) {
            inputElement.value = data[dataKey];
            inputElement.dispatchEvent(new Event('input'));
            fieldsUpdated++;
        }
    }

    if (fieldsUpdated > 0) {
        alert(`Successfully populated ${fieldsUpdated} fields using OCR data! Please review the details.`);
    } else {
        alert('OCR data found, but no fields were automatically populated. Please ensure the ID details are clear.');
    }
}

function processFileForOCR(file) {
    const loadingMessage = document.getElementById('ocrLoadingStatus');
    
    if (loadingMessage) {
        loadingMessage.textContent = 'File uploaded successfully. Please enter details manually.';
        loadingMessage.style.display = 'block';
        setTimeout(() => {
            loadingMessage.style.display = 'none';
        }, 3000);
    }
    
    console.log('File uploaded:', file.name, 'OCR feature is currently disabled.');
    return;
}

function isImageOrPdf(file) {
    const acceptedTypes = [
        'application/pdf', 
        'image/jpeg', 
        'image/jpg',
        'image/png', 
        'image/webp'
    ];
    
    const fileExtension = file.name.split('.').pop().toLowerCase();
    const validExtensions = ['pdf', 'jpeg', 'jpg', 'png', 'webp'];
    
    return acceptedTypes.includes(file.type) || validExtensions.includes(fileExtension);
}
// --- END: OCR FUNCTIONS ---

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
        'arrow-left': 'fas fa-arrow-left',
        'shield': 'fas fa-shield-alt',
        'check-circle': 'fas fa-check-circle',
        'info': 'fas fa-info-circle'
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
                // Set visible label
                input.value = option.textContent;

                // Save internal value
                input.dataset.value = option.getAttribute('data-value');
                
                // For sex field, ensure proper value is stored
                if (input.name === "sex") {
                    input.dataset.value = option.getAttribute('data-value').toLowerCase();
                }
                
                localStorage.setItem(input.name || input.id, input.dataset.value);

                // Close dropdown
                wrapper.classList.remove("open");
                dropdown.style.display = "none";

                // Clear any field error for this input
                const errorSpan = wrapper.querySelector('.field-error');
                if (errorSpan) errorSpan.style.display = 'none';

                // Show/hide UPLOAD FIELD and role info based on role
                if (input.name === "role") {
                    const verificationUploadsContainer = document.getElementById('verificationUploads');
                    const uploadInstruction = document.getElementById('uploadInstruction');
                    const roleValue = input.dataset.value ? input.dataset.value.toLowerCase() : '';
                    const skInfoNote = document.getElementById('skInfoNote');
                    const kkInfoNote = document.getElementById('kkInfoNote');

                    if (roleValue === "sk" || roleValue === "kk") {
                        // Show the single upload container
                        if (verificationUploadsContainer) {
                            verificationUploadsContainer.style.display = "block";
                            
                            // Update instruction text based on role
                            if (uploadInstruction) {
                                if (roleValue === "sk") {
                                    uploadInstruction.innerHTML = 'Upload Oath Taking Certificate<span class="required-asterisk">*</span>';
                                } else if (roleValue === "kk") {
                                    uploadInstruction.innerHTML = 'Upload Barangay Indigency or Valid ID with Full Address<span class="required-asterisk">*</span>';
                                }
                            }
                        }
                        
                        // Show role-specific info notes
                        if (roleValue === "sk" && skInfoNote) {
                            skInfoNote.style.display = "block";
                            if (kkInfoNote) kkInfoNote.style.display = "none";
                        } else if (roleValue === "kk" && kkInfoNote) {
                            kkInfoNote.style.display = "block";
                            if (skInfoNote) skInfoNote.style.display = "none";
                        }
                    } else {
                        // No role selected, hide upload section
                        if (verificationUploadsContainer) verificationUploadsContainer.style.display = "none";
                        if (skInfoNote) skInfoNote.style.display = "none";
                        if (kkInfoNote) kkInfoNote.style.display = "none";
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

// ---- MULTI-STEP FORM WITH REAL-TIME VALIDATION & SCROLL-TO-ERROR ----
let currentStep = 1;
const steps = document.querySelectorAll(".step-content");
const progressSteps = document.querySelectorAll(".step");
const backBtn = document.querySelector(".back-btn");
const nextBtn = document.querySelector(".next-btn");
const form = document.getElementById("multiStepForm");
const currentStepInput = document.getElementById("currentStep");
const stepErrors = document.getElementById("stepErrors");
const stepErrorsList = document.getElementById("stepErrorsList");

// Email verification modal elements
const emailVerificationModal = document.getElementById("emailVerificationModal");
const closeEmailVerificationBtn = document.getElementById("closeEmailVerificationBtn");
const understandEmailBtn = document.getElementById("understandEmailBtn");

// Confirmation Modal Elements
const confirmationModal = document.getElementById("confirmationModal");
const closeConfirmationBtn = document.getElementById("closeConfirmationBtn");
const cancelSubmissionBtn = document.getElementById("cancelSubmission");
const confirmSubmissionBtn = document.getElementById("confirmSubmission");

// Show a specific step and update button text
function showStep(step) {
    steps.forEach((s,i) => s.classList.toggle('active', i === step-1));
    progressSteps.forEach((p,i) => {
        p.classList.toggle('active', i < step);
        p.classList.toggle('completed', i < step-1);
    });

    currentStep = step;
    if(currentStepInput) currentStepInput.value = currentStep;
    
    // Update button text and class based on current step
    if (nextBtn) {
        if (currentStep === steps.length) {
            nextBtn.textContent = "Submit";
            nextBtn.classList.add("step3-submit-btn");
        } else {
            nextBtn.textContent = "Next";
            nextBtn.classList.remove("step3-submit-btn");
        }
    }
    
    if (currentStep === steps.length) {
        fillStep3();
    }
}

// Validate current step with enhanced PDF/image validation
function validateCurrentStep() {
    const currentStepElement = document.querySelector(`.step-content[data-step="${currentStep}"]`);
    const requiredFields = currentStepElement.querySelectorAll('[required]');
    const errors = [];

    // Clear previous errors visuals
    stepErrors.style.display = 'none';
    stepErrorsList.innerHTML = '';
    document.querySelectorAll('.field-error').forEach(error => error.style.display = 'none');
    document.querySelectorAll('.input-error').forEach(input => input.classList.remove('input-error'));

    requiredFields.forEach(field => {
        let isValid = true;
        let errorMessage = 'This field is required';

        if (field.type === 'checkbox') {
            isValid = field.checked;
        } else if (field.type === 'file') {
            // File validation - check which file input is active based on role
            const roleInput = document.querySelector('input[name="role"]'); 
            let selectedRole = '';
            
            if (roleInput) {
                selectedRole = roleInput.dataset.value || roleInput.value;
                selectedRole = selectedRole.toLowerCase();
            }
            
            // Only validate file if role is selected
            if (!selectedRole) return;
            
            // Get the correct file input based on role
            let activeFileInput;
            if (selectedRole === "sk") {
                activeFileInput = document.getElementById('oath_certificate');
            } else if (selectedRole === "kk") {
                activeFileInput = document.getElementById('barangay_indigency');
            }
            
            if (activeFileInput) {
                isValid = activeFileInput.files.length > 0;
                
                if (isValid && activeFileInput.files.length > 0) {
                    const file = activeFileInput.files[0];
                    const maxSizeMB = 5;
                    const maxSizeBytes = maxSizeMB * 1024 * 1024;
                    
                    // Validate file type
                    if (!isImageOrPdf(file)) {
                        isValid = false;
                        errorMessage = `Invalid file type. Please upload PDF, JPG, PNG, or WEBP files only.`;
                    }
                    // Validate file size
                    else if (file.size > maxSizeBytes) {
                        isValid = false;
                        errorMessage = `File size exceeds ${maxSizeMB}MB limit.`;
                    }
                }
                
                if (!isValid) {
                    let fieldName = selectedRole === "sk" ? 'Oath Taking Certificate' : 'Barangay Indigency';
                    errors.push({
                        element: activeFileInput, 
                        message: fieldName + ': ' + errorMessage
                    });

                    activeFileInput.classList.add('input-error');
                    const fieldWrapper = activeFileInput.closest('.file-section');
                    if (fieldWrapper) {
                        const errorSpan = fieldWrapper.querySelector('.field-error');
                            if (errorSpan) {
                            errorSpan.textContent = errorMessage;
                            errorSpan.style.display = 'block';
                        }
                    }
                }
            }
        } else if (field.name === "sex") {
            // Sex validation - handle case-insensitive
            const sexValue = field.value.trim().toLowerCase();
            isValid = sexValue === 'male' || sexValue === 'female';
            if (!isValid) errorMessage = 'Please select either Male or Female';
        } else {
            isValid = field.value.trim() !== '';
            // Validation for custom location dropdowns
            if (['regionInput','provinceInput','cityInput','barangayInput'].includes(field.id)) {
                const hiddenIdField = document.getElementById(field.id.replace('Input','Id'));
                isValid = hiddenIdField && hiddenIdField.value !== '';
            }
        }

        if (!isValid && field.type !== 'file') {
            let fieldName = field.placeholder || field.name;
            errors.push({
                element: field, 
                message: fieldName + ': ' + errorMessage
            });

            field.classList.add('input-error');
            const fieldWrapper = field.closest('.input-wrapper, .select-wrapper, .checkbox-group, .file-section');
            if (fieldWrapper) {
                const errorSpan = fieldWrapper.querySelector('.field-error');
                if (errorSpan) {
                    errorSpan.textContent = errorMessage;
                    errorSpan.style.display = 'block';
                }
            }
        }
    });

    return errors;
}

// Check if email is verified
function isEmailVerified() {
    const sendOtpBtn = document.getElementById("sendOtpBtn");
    // Check if the button text says "Verified"
    return sendOtpBtn && sendOtpBtn.innerHTML.includes('Verified') && sendOtpBtn.disabled === true;
}

// Show email verification error modal
function showEmailVerificationModal() {
    if (emailVerificationModal) {
        emailVerificationModal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
}

// Close email verification modal
function closeEmailVerificationModal() {
    if (emailVerificationModal) {
        emailVerificationModal.style.display = "none";
        document.body.style.overflow = "auto";
    }
}

// Setup email verification modal event listeners
if (closeEmailVerificationBtn) {
    closeEmailVerificationBtn.addEventListener('click', closeEmailVerificationModal);
}

if (understandEmailBtn) {
    understandEmailBtn.addEventListener('click', closeEmailVerificationModal);
}

// Close modal when clicking outside
if (emailVerificationModal) {
    emailVerificationModal.addEventListener('click', (e) => {
        if (e.target === emailVerificationModal) {
            closeEmailVerificationModal();
        }
    });
}

// Handle Next/Submit button click
nextBtn?.addEventListener('click', (e) => {
    e.preventDefault(); 

    // Age validation for Step 1
    if (currentStep === 1) {
        const dobInput = document.getElementById('step1_dob');
        if (dobInput) {
            const isAgeValid = validateAgeForRegistration(dobInput); 
            if (!isAgeValid) {
                dobInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
        }
    }

    // Check if trying to proceed from step 2 to step 3 without email verification
    if (currentStep === 2 && !isEmailVerified()) {
        // Show email verification error modal
        showEmailVerificationModal();
        return; // Stop further execution
    }

    const errors = validateCurrentStep();

    if (errors.length === 0) {
        // --- VALID ---
        if (currentStep < steps.length) {
            currentStep++;
            showStep(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // --- STEP 3 (Final Step): SHOW CONFIRMATION MODAL ---
            showConfirmationModal();
        }
    } else {
        // --- INVALID (Error Handling) ---
        const firstErrorField = errors[0].element; 

        if (firstErrorField) {
            const stepContainer = firstErrorField.closest(".step-content");
            const stepNumber = parseInt(stepContainer.dataset.step);
            
            if (stepNumber !== currentStep) {
                showStep(stepNumber);
            }

            // SCROLL PAPUNTA SA ERROR
            firstErrorField.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center', 
                inline: 'nearest'
            });

            // Focus input
            setTimeout(() => {
                firstErrorField.focus();
            }, 500);
        }
    }
});

// Handle Back
backBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
});

// Real-time input validation cleanup
document.querySelectorAll('input, select, textarea').forEach(input => {
    input.addEventListener('input', () => {
        if (input.value.trim() !== '') {
            input.classList.remove('input-error');
            const wrapper = input.closest('.input-wrapper, .select-wrapper, .checkbox-group, .file-section');
            const err = wrapper?.querySelector('.field-error');
            if (err) err.style.display = 'none';
        }
    });
});

// Initialize first step
showStep(currentStep);

// ======================
// SIMPLE CONFIRMATION MODAL FUNCTIONS
// ======================

// Show confirmation modal
function showConfirmationModal() {
    if (confirmationModal) {
        confirmationModal.style.display = "flex";
        document.body.style.overflow = "hidden";
    } else {
        // Fallback: direct submission if modal not found
        submitFinalForm();
    }
}

// Close confirmation modal
function closeConfirmationModal() {
    if (confirmationModal) {
        confirmationModal.style.display = "none";
        document.body.style.overflow = "auto";
    }
}

// Setup modal event listeners
if (closeConfirmationBtn) {
    closeConfirmationBtn.addEventListener('click', closeConfirmationModal);
}

if (cancelSubmissionBtn) {
    cancelSubmissionBtn.addEventListener('click', closeConfirmationModal);
}

// Close modal when clicking outside
if (confirmationModal) {
    confirmationModal.addEventListener('click', (e) => {
        if (e.target === confirmationModal) {
            closeConfirmationModal();
        }
    });
}

// Confirm submission
if (confirmSubmissionBtn) {
    confirmSubmissionBtn.addEventListener('click', function() {
        // Close modal first
        closeConfirmationModal();
        
        // Submit the form
        submitFinalForm();
    });
}

// Function to submit the final form
function submitFinalForm() {
    // Show loading state on submit button
    if (nextBtn) {
        const originalText = nextBtn.textContent;
        nextBtn.innerHTML = '<span class="spinner"></span> Submitting...';
        nextBtn.disabled = true;
    }
    
    // Ensure internal role value is submitted instead of the full text
    document.querySelectorAll("input[name='role']").forEach(input => {
        if (input.dataset.value) input.value = input.dataset.value;
    });
    
    // Ensure sex value is properly formatted for submission
    const sexInput = document.querySelector('input[name="sex"]');
    if (sexInput && sexInput.dataset.value) {
        sexInput.value = sexInput.dataset.value;
    }
    
    // Clear localStorage
    localStorage.clear();
    
    // Submit the form after a small delay for visual feedback
    setTimeout(() => {
        form.submit();
    }, 800);
}

// Add spinner CSS
const style = document.createElement('style');
style.textContent = `
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-right: 8px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .verification-steps {
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #3C87C4;
    }
    
    .verification-steps p {
        margin: 8px 0;
        color: #555;
        font-size: 14px;
    }
    
    .verification-steps i {
        color: #3C87C4;
        margin-right: 10px;
        width: 20px;
    }
`;
document.head.appendChild(style);

    // ---- SINGLE FILE UPLOAD SETUP ----
    function setupFileUpload() {
        const oathCertificateInput = document.getElementById('oath_certificate');
        const barangayIndigencyInput = document.getElementById('barangay_indigency');
        const text = document.getElementById('fileText');
        const uploadLabel = document.querySelector('.upload-label');
        
        if (uploadLabel && text) {
            // Handle click on upload label
            uploadLabel.addEventListener("click", (e) => {
                e.preventDefault();
                
                // Get the active file input based on selected role
                const roleInput = document.querySelector('input[name="role"]');
                const roleValue = roleInput?.dataset.value || roleInput?.value || '';
                
                if (roleValue === "sk" && oathCertificateInput) {
                    oathCertificateInput.click();
                } else if (roleValue === "kk" && barangayIndigencyInput) {
                    barangayIndigencyInput.click();
                }
            });
            
            // Handle file change for SK Oath Certificate
            if (oathCertificateInput) {
                oathCertificateInput.addEventListener("change", handleFileChange);
            }
            
            // Handle file change for KK Barangay Indigency
            if (barangayIndigencyInput) {
                barangayIndigencyInput.addEventListener("change", handleFileChange);
            }
            
            function handleFileChange(event) {
                const files = event.target.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    const maxSizeMB = 5;
                    const maxSizeBytes = maxSizeMB * 1024 * 1024;
                    
                    // Validate file type
                    if (!isImageOrPdf(file)) {
                        alert('Invalid file type. Please upload PDF, JPG, PNG, or WEBP files only.');
                        event.target.value = '';
                        text.textContent = "Accepted: PDF, PNG, JPG (1 file only), max 5 MB";
                        return;
                    }
                    
                    // Validate file size
                    if (file.size > maxSizeBytes) {
                        alert(`File size exceeds ${maxSizeMB}MB limit. Please upload a smaller file.`);
                        event.target.value = '';
                        text.textContent = "Accepted: PDF, PNG, JPG (1 file only), max 5 MB";
                        return;
                    }
                    
                    // Update display text
                    text.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    
                    // Process OCR
                    processFileForOCR(file);
                    
                    // Show preview
                    showFilePreview(file);
                } else {
                    text.textContent = "Accepted: PDF, PNG, JPG (1 file only), max 5 MB";
                }
                
                // Clear file error when file is selected
                if (files.length > 0) {
                    const fieldWrapper = event.target.closest('.file-section');
                    if (fieldWrapper) {
                        const errorSpan = fieldWrapper.querySelector('.field-error');
                        if (errorSpan) errorSpan.style.display = 'none';
                    }
                }
            }
        }
    }
    
    // Initialize file upload
    setupFileUpload();

    // ** File Preview Functionality **
    function showFilePreview(file) {
        const previewContainer = document.getElementById('documentPreviewContainer');
        
        if (!previewContainer) return;
        
        previewContainer.innerHTML = ''; // Clear previous previews

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add('img-preview');
            img.onload = function() {
                URL.revokeObjectURL(this.src); // Clean up memory
            };
            previewContainer.appendChild(img);
        } else if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
            const pdfDiv = document.createElement('div');
            pdfDiv.classList.add('pdf-preview');
            pdfDiv.innerHTML = `<span>PDF</span><br><small>${file.name}</small>`;
            previewContainer.appendChild(pdfDiv);
        }
    }

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

    const regionDropdown = regionInput?.nextElementSibling;
    const provinceDropdown = provinceInput?.nextElementSibling;
    const cityDropdown = cityInput?.nextElementSibling;
    const barangayDropdown = barangayInput?.nextElementSibling;

    function closeAllDropdowns() {
        [regionDropdown, provinceDropdown, cityDropdown, barangayDropdown].forEach(dd => dd && (dd.style.display = "none"));
    }

    function openDropdown(input, dropdown) {
        closeAllDropdowns();
        if (dropdown) dropdown.style.display = "block";
    }

    // Show dropdown on input click
    [regionInput, provinceInput, cityInput, barangayInput].forEach((input, i) => {
        if (!input) return;
        input.addEventListener("click", e => {
            e.stopPropagation();
            const dropdown = input.nextElementSibling;
            if (!input.disabled) openDropdown(input, dropdown);
        });
    });

    // Event delegation for dropdown items with ID storage
    function setupDropdownSelection(parentDropdown, input, hiddenInput, fetchNext = null) {
        if (!parentDropdown) return;
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
        if (provinceInput) provinceInput.disabled = false;
        if (provinceDropdown) provinceDropdown.innerHTML = "";
        if (provinceIdInput) provinceIdInput.value = "";
        
        cityInput.value = "";
        if (cityInput) cityInput.disabled = true;
        if (cityDropdown) cityDropdown.innerHTML = "";
        if (cityIdInput) cityIdInput.value = "";
        
        barangayInput.value = "";
        if (barangayInput) barangayInput.disabled = true;
        if (barangayDropdown) barangayDropdown.innerHTML = "";
        if (barangayIdInput) barangayIdInput.value = "";

        fetch(`/get-provinces/${regionId}`)
            .then(res => res.json())
            .then(provinces => {
                if (provinceDropdown) {
                    provinces.forEach(p => {
                        const li = document.createElement("li");
                        li.textContent = p.name;
                        li.dataset.id = p.id;
                        provinceDropdown.appendChild(li);
                    });
                }
            })
            .catch(error => console.error('Error fetching provinces:', error));
    });

    // Province → City
    setupDropdownSelection(provinceDropdown, provinceInput, provinceIdInput, provinceId => {
        cityInput.value = "";
        if (cityInput) cityInput.disabled = false;
        if (cityDropdown) cityDropdown.innerHTML = "";
        if (cityIdInput) cityIdInput.value = "";
        
        barangayInput.value = "";
        if (barangayInput) barangayInput.disabled = true;
        if (barangayDropdown) barangayDropdown.innerHTML = "";
        if (barangayIdInput) barangayIdInput.value = "";

        fetch(`/get-cities/${provinceId}`)
            .then(res => res.json())
            .then(cities => {
                if (cityDropdown) {
                    cities.forEach(c => {
                        const li = document.createElement("li");
                        li.textContent = c.name;
                        li.dataset.id = c.id;
                        cityDropdown.appendChild(li);
                    });
                }
            })
            .catch(error => console.error('Error fetching cities:', error));
    });

    // City → Barangay
    setupDropdownSelection(cityDropdown, cityInput, cityIdInput, cityId => {
        barangayInput.value = "";
        if (barangayInput) barangayInput.disabled = false;
        if (barangayDropdown) barangayDropdown.innerHTML = "";
        if (barangayIdInput) barangayIdInput.value = "";

        fetch(`/get-barangays/${cityId}`)
            .then(res => res.json())
            .then(barangays => {
                if (barangayDropdown) {
                    barangays.forEach(b => {
                        const li = document.createElement("li");
                        li.textContent = b.name;
                        li.dataset.id = b.id;
                        barangayDropdown.appendChild(li);
                    });
                }
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
        const roleInput = document.querySelector("input[name='role']");
        const roleValue = roleInput?.dataset.value || roleInput?.value || "";
        document.getElementById("review_role").textContent =
            roleValue === "sk" ? "SK Chairperson" : 
            roleValue === "kk" ? "KK Member" : "";
        
        // Document Type
        document.getElementById("review_document_type").textContent =
            roleValue === "sk" ? "Oath Taking Certificate" : 
            roleValue === "kk" ? "Barangay Indigency/Valid ID" : "";

        // Get uploaded file name based on role
        let uploadedFile = "";
        if (roleValue === "sk") {
            const oathCertificateFile = document.getElementById("oath_certificate")?.files[0]?.name;
            uploadedFile = oathCertificateFile || "No file uploaded";
        } else if (roleValue === "kk") {
            const barangayIndigencyFile = document.getElementById("barangay_indigency")?.files[0]?.name;
            uploadedFile = barangayIndigencyFile || "No file uploaded";
        }
        
        document.getElementById("review_files").textContent = uploadedFile;
    }

    // --- OTP VERIFICATION ---
    const emailInput = document.getElementById("emailInput");
    const sendOtpBtn = document.getElementById("sendOtpBtn");
    const contactInput = document.getElementById("contactInput");
    const codeModal = document.getElementById("codeModal");
    const codeMessage = document.getElementById("codeMessage");
    const codeBoxes = document.querySelectorAll(".code-box");
    const codeVerifyBtn = document.getElementById("codeVerifyBtn");
    const resendLink = document.getElementById("resendLink");
    const successModal = document.getElementById("successModal");
    const successOkBtn = document.getElementById("successOkBtn");

    let resendTimer = 30;
    let resendInterval;
    const defaultSendOtpText = '<span>Send OTP</span>';

    // Send OTP Listener
    sendOtpBtn?.addEventListener("click", async () => {
        const email = emailInput.value.trim();

        if (!email) {
            return showMessage("Please enter your email address.");
        }

        // Basic email validation
        if (!/\S+@\S+\.\S+/.test(email)) {
            return showMessage("Please enter a valid email address.");
        }

        // 1. Loading State (Spinner)
        sendOtpBtn.disabled = true;
        sendOtpBtn.innerHTML = '<span class="spinner"></span> Sending...';

        // Store email
        if (contactInput) {
            contactInput.value = email;
            localStorage.setItem(contactInput.name || contactInput.id, email);
        }

        try {
            // 2. Send OTP
            await autoSendOTP(email);
        } catch (error) {
            // Restore button state on failure
            sendOtpBtn.disabled = false;
            sendOtpBtn.innerHTML = defaultSendOtpText;
        }
    });

    function autoSendOTP(email) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        return new Promise((resolve, reject) => {
            fetch("/send-otp", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ email }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (codeModal) codeModal.style.display = "flex";
                    showMessage(`We sent a code to ${email}`);
                    clearCodeBoxes();
                    codeBoxes[0]?.focus();
                    startResendTimer();
                    resolve(data); 
                } else {
                    showMessage(data.error || "Failed to send OTP.");
                    reject(data.error); 
                }
            })
            .catch(err => {
                console.error("Error sending OTP:", err);
                showMessage("An error occurred while sending OTP.");
                reject(err);
            });
        });
    }

    resendLink?.addEventListener("click", async e => {
        e.preventDefault();
        if (resendLink.disabled) return;

        const email = contactInput.value.trim();
        if (!email) return showMessage("Please enter your email address first.");

        // Loading State for Resend Link
        resendLink.disabled = true; 
        const originalResendText = resendLink.textContent;
        resendLink.textContent = "Sending...";

        try {
            await autoSendOTP(email);
        } catch (error) {
            // Restore state if error
            resendLink.disabled = false;
            resendLink.textContent = originalResendText;
        }
    });

    codeVerifyBtn?.addEventListener("click", async e => {
        e.preventDefault();
        const code = Array.from(codeBoxes).map(b => b.value).join("");
        const email = contactInput.value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (code.length !== 6) return showMessage("Enter the 6-digit code.");

        codeVerifyBtn.disabled = true;

        try {
            const res = await fetch("/verify-otp", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ email, code: code.split("") }),
            });

            const data = await res.json();

            if (data.verified) {
                // Hide OTP modal
                if (codeModal) codeModal.style.display = "none";

                // ✅ Permanently disable SEND OTP button & update text
                if (sendOtpBtn) {
                    sendOtpBtn.disabled = true;
                    sendOtpBtn.innerHTML = '<span>Verified</span>'; 
                }

                // Show success modal
                if (successModal) successModal.style.display = "flex";

            } else {
                showMessage(data.error || "Invalid or expired code.");
            }

        } catch (err) {
            console.error("Error verifying OTP:", err);
        } finally {
            codeVerifyBtn.disabled = false;
        }
    });

    successOkBtn?.addEventListener("click", () => {
        if (successModal) successModal.style.display = "none";
    });

    // OTP BOX NAVIGATION
    codeBoxes.forEach((box, i) => {
        box.addEventListener("input", () => {
            if (box.value && i < codeBoxes.length - 1) codeBoxes[i + 1].focus();
        });
        box.addEventListener("keydown", e => {
            if (e.key === "Backspace" && !box.value && i > 0) codeBoxes[i - 1].focus();
        });
    });

    function clearCodeBoxes() { 
        codeBoxes.forEach(b => b.value = ""); 
    }

    function startResendTimer() {
        if (resendLink) resendLink.disabled = true;
        let timeLeft = resendTimer;
        if (resendLink) resendLink.textContent = `Resend (${timeLeft}s)`;

        clearInterval(resendInterval);
        resendInterval = setInterval(() => {
            timeLeft--;
            if (resendLink) resendLink.textContent = `Resend (${timeLeft}s)`;
            if (timeLeft <= 0) {
                clearInterval(resendInterval);
                if (resendLink) {
                    resendLink.disabled = false;
                    resendLink.textContent = "Resend";
                }
            }
        }, 1000);
    }

    function showMessage(msg) {
        if (codeMessage) {
            const original = codeMessage.innerText;
            codeMessage.innerText = msg;
            setTimeout(() => codeMessage.innerText = original, 5000);
        }
    }

    // ---- SAVE STEP & INPUTS ON RELOAD ----
    const registrationFailed = window.location.search.includes("failed=1") || {{ $errors->any() ? 'true' : 'false' }};

    if (registrationFailed) {
        console.warn("Registration failed. Resetting progress to Step 1.");
        localStorage.clear();
        currentStep = 1;
        showStep(currentStep);
        if(currentStepInput) currentStepInput.value = currentStep;
    } else {
        // Restore saved step if no registration failure
        const savedStep = localStorage.getItem("currentStep");

        if (!savedStep || isNaN(savedStep) || savedStep < 1 || savedStep > steps.length) {
            currentStep = 1;
        } else {
            currentStep = parseInt(savedStep);
        }

        showStep(currentStep);
        if(currentStepInput) currentStepInput.value = currentStep;
    }

    // Save step changes
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
    const formInputsToSave = form.querySelectorAll("input[type='text'], input[type='email'], input[type='date'], input[type='number'], input[type='hidden'], input[type='checkbox']");

    // Restore saved input values
    formInputsToSave.forEach(input => {
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
    formInputsToSave.forEach(input => {
        input.addEventListener("input", () => {
            if (input.type === "checkbox") {
                localStorage.setItem(input.name || input.id, input.checked);
            } else {
                localStorage.setItem(input.name || input.id, input.value);
            }
        });
    });

    // ---- RESTORE ROLE-BASED FIELD DISPLAY ----
    const roleSelectInput = document.querySelector('input[name="role"]');
    if (roleSelectInput) {
        const roleValue = localStorage.getItem(roleSelectInput.name || roleSelectInput.id);
        const verificationUploadsContainer = document.getElementById('verificationUploads');
        const uploadInstruction = document.getElementById('uploadInstruction');
        const skInfoNote = document.getElementById('skInfoNote');
        const kkInfoNote = document.getElementById('kkInfoNote');

        if (roleValue) {
            // Restore display value from saved internal value
            const option = document.querySelector(`.dropdown-options li[data-value="${roleValue}"]`);
            if (option) {
                roleSelectInput.value = option.textContent;
                roleSelectInput.dataset.value = roleValue;
            }

            const lowerCaseRole = roleValue.toLowerCase();
            if (lowerCaseRole === "sk" || lowerCaseRole === "kk") {
                if (verificationUploadsContainer) {
                    verificationUploadsContainer.style.display = "block";
                    
                    // Update instruction text based on role
                    if (uploadInstruction) {
                        if (lowerCaseRole === "sk") {
                            uploadInstruction.innerHTML = 'Upload Oath Taking Certificate<span class="required-asterisk">*</span>';
                        } else if (lowerCaseRole === "kk") {
                            uploadInstruction.innerHTML = 'Upload Barangay Indigency or Valid ID with Full Address<span class="required-asterisk">*</span>';
                        }
                    }
                }
                
                // Show role-specific info notes
                if (lowerCaseRole === "sk" && skInfoNote) {
                    skInfoNote.style.display = "block";
                } else if (lowerCaseRole === "kk" && kkInfoNote) {
                    kkInfoNote.style.display = "block";
                }
            } else {
                if (verificationUploadsContainer) verificationUploadsContainer.style.display = "none";
                if (skInfoNote) skInfoNote.style.display = "none";
                if (kkInfoNote) kkInfoNote.style.display = "none";
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

    // Age calculation and validation functions
    function calculateAge(dobString) {
        const today = new Date();
        const birthDate = new Date(dobString);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    function validateAgeForRegistration(dateOfBirthInput) {
        const dobValue = dateOfBirthInput.value;
        const errorSpan = dateOfBirthInput.closest('.input-wrapper')?.querySelector('.field-error');
        
        const minAge = 15;
        const maxAge = 30;

        if (!dobValue) {
            if (errorSpan) {
                errorSpan.textContent = 'Date of Birth is required';
                errorSpan.style.display = 'block';
            }
            dateOfBirthInput.classList.add('input-error');
            return false;
        }

        const age = calculateAge(dobValue);

        if (age < minAge || age > maxAge) {
            const errorMessage = `The age must be between ${minAge} and ${maxAge} years old. Your age is ${age}.`;
            
            if (errorSpan) {
                errorSpan.textContent = errorMessage;
                errorSpan.style.display = 'block';
            }
            dateOfBirthInput.classList.add('input-error');
            return false;
        } else {
            // Valid age, hide error
            if (errorSpan) errorSpan.style.display = 'none';
            dateOfBirthInput.classList.remove('input-error');
            return true;
        }
    }
});
</script>
</body>
</html>