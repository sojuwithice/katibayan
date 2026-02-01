<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KatiBayan - Terms and Conditions</title>
  <link rel="stylesheet" href="{{ asset('css/terms.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <nav class="navbar">
    <div class="logo">
      <img src="{{ asset('images/KatiBayan-Logo_B.png') }}" alt="KatiBayan Logo" class="logo-img">  
      <div class="logo-text">
        <span>KatiBayan</span>
        <small>Katipunan ng Kabataan Web Portal</small>
      </div>
    </div> 

    <!-- menu -->
    <div class="menu-toggle" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </div>

    <!-- Nav links -->
    <ul class="nav-links" id="nav-links">
      <li><a href="index.html">Home</a></li>
      <li><a href="index.html#features">Features</a></li>
      <li><a href="index.html#faqs">FAQs</a></li>
      <li><a href="index.html#about">About Us</a></li>
      <li class="mobile-login">
        <a href="#" class="login-btn">Login your Account</a>
      </li>

      <li class="mobile-theme-toggle">
        <button class="theme-toggle" id="mobileThemeToggle">
          <i class="fas fa-moon"></i>
        </button>
      </li>
    </ul>

    <!-- Desktop login button -->
    <div class="login-container">
      <a href="#" class="login-btn">Login your Account</a>
      <button class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
      </button>
    </div>
  </nav>

  <!-- Terms Header -->
  <section class="terms-header" id="home">
    <div class="terms-header-content">
      <h1>KatiBayan Terms and Conditions</h1>
      <p>Please read these Terms and Conditions carefully before using the KatiBayan Web Portal. Your access to and use of the service is conditioned on your acceptance of and compliance with these terms.</p>
      <div class="Effective Date">
        <i class="fas fa-calendar-alt"></i> Effective Date: March 15, 2025
      </div>
    </div>
  </section>

  <!-- Mobile Container (Visible on Mobile Only) -->
  <div class="mobile-container">
    <div class="mobile-navigation-arrows">
      <button class="nav-arrow" id="mobilePrevBtn" disabled>
        <i class="fas fa-chevron-left"></i>
      </button>
      
      <div class="slide-counter">
        <span id="currentSlide">1</span> / <span id="totalSlides">11</span>
      </div>
      
      <button class="nav-arrow" id="mobileNextBtn">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>

    <div class="mobile-slide-wrapper" id="slideWrapper">
      <!-- Slide 1: Introduction -->
      <div class="mobile-slide" data-slide="1">
        <h2>Introduction</h2>
        <p>Welcome to the KatiBayan System. These Terms and Conditions outline the rules, responsibilities, rights, and legal obligations associated with the use of this platform. By creating an account or using the system, you agree to be bound by all terms stated herein.</p>
        
        <div class="highlight-box">
          <p><i class="fas fa-info-circle"></i> By checking "I Agree" and continuing with registration, you acknowledge that you have read, understood, and accepted these Terms and Conditions in full.</p>
        </div>
        
        <p>If you do not agree with any part of these Terms and Conditions, you must not create an account or proceed with the use of the system.</p>
      </div>

      <!-- Slide 2: Acceptance & Purpose -->
      <div class="mobile-slide" data-slide="2">
        <h2>1. Acceptance of Terms</h2>
        <p>By registering and accessing the KatiBayan System, you acknowledge that you have read, understood, and agreed to these Terms and Conditions. If you do not agree with any part of these terms, you must stop using the system immediately.</p>

        <h2>2. Purpose of the System</h2>
        <p>The KatiBayan System is developed to support the Sangguniang Kabataan (SK) in:</p>
        <ul>
          <li>Youth profiling and engagement monitoring</li>
          <li>Community development and governance</li>
          <li>Delivery of public services</li>
        </ul>
        <p>All data collected and processed are intended solely for authorized purposes such as program planning, reporting, analytics, documentation, and youth involvement monitoring.</p>
      </div>

      <!-- Slide 3: User Responsibilities -->
      <div class="mobile-slide" data-slide="3">
        <h2>3. User Responsibilities</h2>
        <p>By using this system, you agree to:</p>
        <ul>
          <li>Provide accurate, truthful, and updated information during registration and profile updates</li>
          <li>Maintain the confidentiality of your account credentials</li>
          <li>Avoid using the system for illegal, harmful, fraudulent, or unauthorized activities</li>
          <li>Refrain from uploading false, misleading, offensive, or malicious content</li>
          <li>Inform authorized personnel immediately regarding any unauthorized access or suspicious activity</li>
          <li>Avoid hacking, disrupting, or manipulating the system's code, database, or security features</li>
        </ul>
        
        <div class="highlight-box">
          <p><i class="fas fa-exclamation-triangle"></i> Violation of these responsibilities may result in suspension, removal of privileges, or permanent account deactivation.</p>
        </div>
      </div>

      <!-- Slide 4: Legal Framework -->
      <div class="mobile-slide" data-slide="4">
        <h2>4. Legal Framework</h2>
        <p>The use, collection, storage, and processing of data within the KatiBayan System are governed by Philippine laws and policies:</p>
        
        <h3>4.1 Data Privacy Act of 2012 (RA 10173)</h3>
        <ul>
          <li>Protects personal and sensitive information</li>
          <li>Requires secure handling, processing, and storage of personal data</li>
          <li>Grants users rights such as consent, access, and correction</li>
        </ul>

        <h3>4.2 Sangguniang Kabataan Reform Act of 2015 (RA 10742)</h3>
        <ul>
          <li>Mandates proper documentation and profiling for youth governance</li>
          <li>Supports digital profiling and engagement systems</li>
          <li>Promotes accountability and transparency within the SK</li>
        </ul>
      </div>

      <!-- Slide 5: Legal Framework Continued -->
      <div class="mobile-slide" data-slide="5">
        <h2>4. Legal Framework (Continued)</h2>
        
        <h3>4.3 Local Government Code of 1991 (RA 7160)</h3>
        <ul>
          <li>Provides LGUs the authority to establish systems that benefit community welfare</li>
          <li>Supports youth documentation and development initiatives within barangays</li>
        </ul>

        <h3>4.4 Other Relevant Guidelines</h3>
        <ul>
          <li>DILG-issued policies on youth governance</li>
          <li>LGU ordinances on digital data collection and reporting</li>
        </ul>
        
        <p>By using this system, you acknowledge that your data may be processed in accordance with these laws and guidelines.</p>
      </div>

      <!-- Slide 6: Data Privacy and Security -->
      <div class="mobile-slide" data-slide="6">
        <h2>5. Data Privacy and Security</h2>
        <p>The KatiBayan System is committed to safeguarding user information. By using the platform, you agree that:</p>
        <ul>
          <li>Your personal data will be collected, stored, and processed securely following RA 10173</li>
          <li>Only authorized SK Chairperson and designated system administrators may access your data for legitimate purposes</li>
          <li>The system uses appropriate technical measures such as encryption, secure authentication, role-based access, and data protection protocols</li>
          <li>While protective measures are implemented, you understand that no system is completely free from risks, and you agree to use the platform responsibly to help maintain its security</li>
        </ul>
        
        <div class="highlight-box">
          <p><i class="fas fa-shield-alt"></i> We implement multiple layers of security to protect your personal information and ensure data confidentiality.</p>
        </div>
      </div>

      <!-- Slide 7: System Availability and Updates -->
      <div class="mobile-slide" data-slide="7">
        <h2>6. System Availability and Updates</h2>
        <p>The KatiBayan System may undergo maintenance, updates, or improvements without prior notice.</p>
        
        <p>Users acknowledge and agree that:</p>
        <ul>
          <li>Temporary downtime may occur, and continuous, uninterrupted service cannot be guaranteed</li>
          <li>System features, modules, or functionalities may be added, modified, or removed as part of ongoing development</li>
          <li>Administrators may temporarily restrict or suspend access for system integrity, security, or technical purposes</li>
          <li>Regular maintenance is necessary to ensure system performance and security</li>
        </ul>
        
        <div class="highlight-box">
          <p><i class="fas fa-tools"></i> We strive to schedule maintenance during off-peak hours to minimize disruption to users.</p>
        </div>
      </div>

      <!-- Slide 8: Limitation of Liability -->
      <div class="mobile-slide" data-slide="8">
        <h2>7. Limitation of Liability</h2>
        <p>By using this system, you agree that:</p>
        <ul>
          <li>The developers, administrators, and SK Chairperson shall not be held liable for any damages resulting from system downtime, unauthorized access, external attacks, data loss, or misuse caused by user negligence</li>
          <li>The system is provided on an "as-is" and "as-available" basis, without warranties of uninterrupted service or absolute security</li>
          <li>Users are fully responsible for the accuracy of data they submit and the proper management of their accounts</li>
          <li>The system operators are not responsible for any indirect, incidental, or consequential damages arising from system use</li>
        </ul>
        
        <div class="highlight-box">
          <p><i class="fas fa-balance-scale"></i> <strong>Disclaimer:</strong> To the maximum extent permitted by law, KatiBayan System operators disclaim all warranties, express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>
        </div>
      </div>

      <!-- Slide 9: Intellectual Property -->
      <div class="mobile-slide" data-slide="9">
        <h2>8. Intellectual Property</h2>
        <p>All content, design, code, databases, and materials within the KatiBayan System are protected by intellectual property rights. Users agree not to copy, reproduce, distribute, or reverse-engineer any part of the system without explicit written permission.</p>
        
        <h3>8.1 User-Generated Content</h3>
        <p>By uploading or submitting content, you grant the system a non-exclusive license to store, display, and use such content for authorized purposes.</p>
        
        <h3>8.2 Third-Party Materials</h3>
        <p>Any third-party logos, trademarks, or content remain the property of their respective owners.</p>
      </div>

      <!-- Slide 10: Governing Law -->
      <div class="mobile-slide" data-slide="10">
        <h2>9. Governing Law</h2>
        <p>These Terms and Conditions shall be governed by and construed in accordance with the laws of the Republic of the Philippines. Any disputes arising from the use of this system shall be subject to the exclusive jurisdiction of the courts within the Philippines.</p>
        
        <h3>9.1 Amendments</h3>
        <p>The system administrators reserve the right to modify these Terms and Conditions at any time. Users will be notified of significant changes, and continued use of the system after such modifications constitutes acceptance of the updated terms.</p>
        
        <h3>9.2 Severability</h3>
        <p>If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions shall remain in full force and effect.</p>
      </div>

      <!-- Slide 11: Final Terms -->
      <div class="mobile-slide" data-slide="11">
        <h2>User Acknowledgment</h2>
        
        <p>By checking "I Agree" and continuing with the registration or use of the platform, you acknowledge and confirm that:</p>
        <ul>
          <li>You have read, understood, and accepted these Terms and Conditions in full</li>
          <li>You consent to the collection, processing, and use of your personal information in accordance with the system's Privacy Policy and applicable Philippine laws</li>
          <li>You understand that the KatiBayan System is an official platform intended for youth profiling, engagement monitoring, community development, and governance, and that misuse may result in account suspension or legal consequences</li>
          <li>You agree to use the platform responsibly, ethically, and lawfully, and to comply with all policies implemented for the security, integrity, and proper functioning of the system</li>
          <li>You acknowledge that access to certain features depends on your user role, authorization level, and the accuracy of the information you provide</li>
        </ul>

        <h3>Contact Information</h3>
        <ul>
          <li><strong>Email:</strong> katibayan.system@gmail.com</li>
        </ul>
        
        <div class="mobile-accept-section">
          <button class="accept-btn" id="acceptMobileBtn">
            Accept Terms <i class="fas fa-check-circle"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Navigation Dots -->
    <div class="slide-dots" id="slideDots">
      <div class="slide-dot active" data-slide="1"></div>
      <div class="slide-dot" data-slide="2"></div>
      <div class="slide-dot" data-slide="3"></div>
      <div class="slide-dot" data-slide="4"></div>
      <div class="slide-dot" data-slide="5"></div>
      <div class="slide-dot" data-slide="6"></div>
      <div class="slide-dot" data-slide="7"></div>
      <div class="slide-dot" data-slide="8"></div>
      <div class="slide-dot" data-slide="9"></div>
      <div class="slide-dot" data-slide="10"></div>
      <div class="slide-dot" data-slide="11"></div>
    </div>
  </div>

  <!-- Desktop Terms Content (Visible on Desktop Only) -->
  <section class="terms-content">
    <div class="terms-container">
      <!-- Introduction -->
      <div class="terms-section" id="section1">
        <h2>Introduction</h2>
        <p>Welcome to the KatiBayan System. These Terms and Conditions outline the rules, responsibilities, rights, and legal obligations associated with the use of this platform. By creating an account or using the system, you agree to be bound by all terms stated herein.</p>
        
        <div class="highlight-box">
          <p><i class="fas fa-info-circle"></i> By checking "I Agree" and continuing with the registration or use of the platform, you acknowledge and confirm that you have read, understood, and accepted these Terms and Conditions in full.</p>
        </div>
        
        <p>If you do not agree with any part of these Terms and Conditions, you must not create an account or proceed with the use of the system.</p>
      </div>

      <!-- User Acknowledgment -->
      <div class="terms-section" id="section2">
        <h2>User Acknowledgment</h2>
        <p>By checking "I Agree" and continuing with the registration or use of the platform, you acknowledge and confirm that:</p>
        <ul>
          <li>You have read, understood, and accepted these Terms and Conditions in full</li>
          <li>You consent to the collection, processing, and use of your personal information in accordance with the system's Privacy Policy and applicable Philippine laws</li>
          <li>You understand that the KatiBayan System is an official platform intended for youth profiling, engagement monitoring, community development, and governance, and that misuse may result in account suspension or legal consequences</li>
          <li>You agree to use the platform responsibly, ethically, and lawfully, and to comply with all policies implemented for the security, integrity, and proper functioning of the system</li>
          <li>You acknowledge that access to certain features depends on your user role, authorization level, and the accuracy of the information you provide</li>
        </ul>
      </div>

      <!-- Acceptance of Terms -->
      <div class="terms-section" id="section3">
        <h2>1. Acceptance of Terms</h2>
        <p>By registering and accessing the KatiBayan System, you acknowledge that you have read, understood, and agreed to these Terms and Conditions. If you do not agree with any part of these terms, you must stop using the system immediately.</p>
      </div>

      <!-- Purpose of the System -->
      <div class="terms-section" id="section4">
        <h2>2. Purpose of the System</h2>
        <p>The KatiBayan System is developed to support the Sangguniang Kabataan (SK) in youth profiling, engagement monitoring, community development, governance, and delivery of public services.</p>
        <p>All data collected and processed are intended solely for authorized purposes such as:</p>
        <ul>
          <li>Program planning and implementation</li>
          <li>Reporting and documentation</li>
          <li>Analytics and research</li>
          <li>Youth involvement monitoring</li>
          <li>Governance and transparency initiatives</li>
        </ul>
      </div>

      <!-- User Responsibilities -->
      <div class="terms-section" id="section5">
        <h2>3. User Responsibilities</h2>
        <p>By using this system, you agree to:</p>
        <ul>
          <li>Provide accurate, truthful, and updated information during registration and profile updates</li>
          <li>Maintain the confidentiality of your account credentials</li>
          <li>Avoid using the system for illegal, harmful, fraudulent, or unauthorized activities</li>
          <li>Refrain from uploading false, misleading, offensive, or malicious content</li>
          <li>Inform authorized personnel immediately regarding any unauthorized access or suspicious activity</li>
          <li>Avoid hacking, disrupting, or manipulating the system's code, database, or security features</li>
        </ul>
        
        <div class="highlight-box">
          <p><i class="fas fa-exclamation-triangle"></i> Violation of these responsibilities may result in suspension, removal of privileges, or permanent account deactivation.</p>
        </div>
      </div>

      <!-- Legal Framework -->
      <div class="terms-section" id="section6">
        <h2>4. Legal Framework</h2>
        <p>The use, collection, storage, and processing of data within the KatiBayan System are governed by the following Philippine laws and policies:</p>
        
        <h3>4.1 Data Privacy Act of 2012 (RA 10173)</h3>
        <ul>
          <li>Protects personal and sensitive information</li>
          <li>Requires secure handling, processing, and storage of personal data</li>
          <li>Grants users rights such as consent, access, and correction</li>
        </ul>

        <h3>4.2 Sangguniang Kabataan Reform Act of 2015 (RA 10742)</h3>
        <ul>
          <li>Mandates proper documentation and profiling for youth governance</li>
          <li>Supports digital profiling and engagement systems</li>
          <li>Promotes accountability and transparency within the SK</li>
        </ul>

        <h3>4.3 Local Government Code of 1991 (RA 7160)</h3>
        <ul>
          <li>Provides LGUs the authority to establish systems that benefit community welfare</li>
          <li>Supports youth documentation and development initiatives within barangays</li>
        </ul>

        <h3>4.4 Other Relevant Guidelines</h3>
        <ul>
          <li>DILG-issued policies on youth governance</li>
          <li>LGU ordinances on digital data collection and reporting</li>
        </ul>
        
        <p>By using this system, you acknowledge that your data may be processed in accordance with these laws and guidelines.</p>
      </div>

      <!-- Data Privacy and Security -->
      <div class="terms-section" id="section7">
        <h2>5. Data Privacy and Security</h2>
        <p>The KatiBayan System is committed to safeguarding user information. By using the platform, you agree that:</p>
        <ul>
          <li>Your personal data will be collected, stored, and processed securely following RA 10173</li>
          <li>Only authorized SK Chairperson and designated system administrators may access your data for legitimate purposes</li>
          <li>The system uses appropriate technical measures such as encryption, secure authentication, role-based access, and data protection protocols</li>
          <li>While protective measures are implemented, you understand that no system is completely free from risks, and you agree to use the platform responsibly to help maintain its security</li>
        </ul>
        
        <div class="highlight-box">
          <p><i class="fas fa-shield-alt"></i> <strong>Security Measures:</strong> We implement multiple layers of security including data encryption, secure authentication protocols, role-based access control, regular security audits, and compliance with data protection standards to ensure the confidentiality and integrity of your information.</p>
        </div>
        
        <h3>5.1 Data Retention</h3>
        <p>Your personal data will be retained only for as long as necessary to fulfill the purposes for which it was collected, or as required by applicable laws and regulations.</p>
        
        <h3>5.2 Data Access Rights</h3>
        <p>As a data subject under RA 10173, you have the right to:</p>
        <ul>
          <li>Access your personal information</li>
          <li>Correct inaccurate or incomplete data</li>
          <li>Request deletion of your data under certain circumstances</li>
          <li>Object to the processing of your personal data</li>
          <li>Withdraw consent at any time</li>
        </ul>
      </div>

      <!-- System Availability and Updates -->
      <div class="terms-section" id="section8">
        <h2>6. System Availability and Updates</h2>
        <p>The KatiBayan System may undergo maintenance, updates, or improvements without prior notice. Users acknowledge and agree that:</p>
        
        <ul>
          <li>Temporary downtime may occur, and continuous, uninterrupted service cannot be guaranteed</li>
          <li>System features, modules, or functionalities may be added, modified, or removed as part of ongoing development</li>
          <li>Administrators may temporarily restrict or suspend access for system integrity, security, or technical purposes</li>
          <li>Regular maintenance is necessary to ensure system performance and security</li>
          <li>Advance notice will be provided for scheduled maintenance whenever possible</li>
          <li>Emergency maintenance may be performed without prior notice to address critical security or performance issues</li>
        </ul>
          <h3>6.1 System Updates</h3>
        <p>Updates to the system may include:</p>
        <ul>
          <li>Security patches and vulnerability fixes</li>
          <li>Performance improvements</li>
          <li>New features and functionality</li>
          <li>Bug fixes and stability improvements</li>
          <li>Compatibility updates</li>
        </ul>
        
        <h3>6.2 Service Level Agreement</h3>
        <p>While we strive for high availability, we do not guarantee specific uptime percentages. System availability may be affected by factors beyond our control including:</p>
        <ul>
          <li>Internet connectivity issues</li>
          <li>Third-party service disruptions</li>
          <li>Power outages</li>
          <li>Natural disasters</li>
          <li>Cybersecurity incidents</li>
        </ul>
      </div>

      <!-- Limitation of Liability -->
      <div class="terms-section" id="section9">
        <h2>7. Limitation of Liability</h2>
        <p>By using this system, you agree that:</p>
        <ul>
          <li>The developers, administrators, and SK Chairperson shall not be held liable for any damages resulting from system downtime, unauthorized access, external attacks, data loss, or misuse caused by user negligence</li>
          <li>The system is provided on an "as-is" and "as-available" basis, without warranties of uninterrupted service or absolute security</li>
          <li>Users are fully responsible for the accuracy of data they submit and the proper management of their accounts</li>
          <li>The system operators are not responsible for any indirect, incidental, or consequential damages arising from system use</li>
        </ul>
        
        <div class="highlight-box">
          <p><i class="fas fa-balance-scale"></i> <strong>Disclaimer:</strong> To the maximum extent permitted by law, KatiBayan System operators disclaim all warranties, express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>
        </div>
        
        <h3>7.1 User Liability</h3>
        <p>Users are liable for:</p>
        <ul>
          <li>Any content they upload or share through the system</li>
          <li>Unauthorized use of their account credentials</li>
          <li>Any damage caused to the system or other users through misuse</li>
          <li>Violation of applicable laws or regulations</li>
        </ul>
      </div>

      <!-- Intellectual Property -->
      <div class="terms-section" id="section10">
        <h2>8. Intellectual Property</h2>
        <p>All content, design, code, databases, and materials within the KatiBayan System are protected by intellectual property rights. Users agree not to copy, reproduce, distribute, or reverse-engineer any part of the system without explicit written permission.</p>
        
        <h3>8.1 User-Generated Content</h3>
        <p>By uploading or submitting content, you grant the system a non-exclusive license to store, display, and use such content for authorized purposes.</p>
        
        <h3>8.2 Third-Party Materials</h3>
        <p>Any third-party logos, trademarks, or content remain the property of their respective owners.</p>
      </div>

      <div class="desktop-nav">
        <button class="desktop-nav-btn" id="desktopPrevBtn" disabled>
          <i class="fas fa-chevron-left"></i> Previous Section
        </button>
        
        <div class="desktop-progress">
          Section <span id="currentSection">1</span> of 11
        </div>
        
        <button class="desktop-nav-btn" id="desktopNextBtn">
          Next Section <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <!-- Final Acceptance Button -->
      <div class="acceptance-section">
        <button class="accept-btn" id="acceptDesktopBtn">
          <i class="fas fa-check-circle"></i> I Accept the Terms and Conditions
        </button>
      </div>
    </div>
  </section>

  <!-- Acknowledgement Modal -->
  <div class="ack-modal" id="ackModal">
    <div class="ack-modal-content">
      <div class="ack-modal-icon">
        <i class="fas fa-file-contract"></i>
      </div>
      <h3>Accept Terms and Conditions</h3>
      <p>By accepting, you acknowledge that you have read, understood, and agree to be bound by the KatiBayan Terms and Conditions. This agreement is legally binding.</p>
      <p><strong>Do you accept these terms?</strong></p>
      <div class="ack-modal-buttons">
        <button class="ack-btn decline" id="declineBtn">Decline</button>
        <button class="ack-btn accept" id="acceptBtn">Accept</button>
      </div>
    </div>
  </div>

  <!-- Scroll to Top Button -->
  <div class="scroll-top" id="scrollTop">
    <i class="fas fa-chevron-up"></i>
  </div>

  <!-- Footer -->
  <footer class="footer">  
      <div class="footer-above"></div>
    <div class="footer-container">
      <!-- CONTACT INFORMATION -->
      <div class="footer-contact">
        <h3>CONTACT INFORMATION</h3>
        <p>For inquiries or feedback about our Terms and Conditions, contact us via email.</p>
        <!-- EMAIL + BUTTON INLINE -->
        <div class="email-wrapper">
          <div class="email-container">
            <p class="email-text"><i class="fa fa-envelope"></i> katibayan.system@gmail.com</p>
            <button id="emailBtn" class="email-btn">Send us email<i class="fa fa-paper-plane" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
      <!-- QUICK LINKS -->
      <div class="footer-links">
        <h3>QUICK LINKS</h3>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="index.html#features">Main feature</a></li>
          <li><a href="index.html#faqs">FAQs</a></li>
          <li><a href="index.html#about">About us</a></li>
        </ul>
      </div>
      <!-- LEGAL -->
      <div class="footer-legal">
        <h3>LEGAL</h3>
        <ul>
          <li><a href="#" style="font-weight: bold; color: #FFE9AD;">Terms and Conditions</a></li>
        </ul>
      </div>
      <div class="footer-legal">
        <h3>USER GUIDE</h3>
        <ul>
          <li><a href="user-guide.html">Learn to Use</a></li>
        </ul>
      </div>
    </div>
    
    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <p>© 2025 | All Rights Reserved | Powered by KatiBayan</p>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const menuToggle = document.getElementById('menu-toggle');
      const navLinks = document.getElementById('nav-links');
      const themeToggle = document.getElementById('themeToggle');
      const mobileThemeToggle = document.getElementById('mobileThemeToggle');
      const emailBtn = document.getElementById('emailBtn');
      const ackModal = document.getElementById('ackModal');
      const acceptBtn = document.getElementById('acceptBtn');
      const declineBtn = document.getElementById('declineBtn');
      const scrollTopBtn = document.getElementById('scrollTop');
      
      // Mobile Navigation Variables
      const slideWrapper = document.getElementById('slideWrapper');
      const slideDots = document.querySelectorAll('.slide-dot');
      const mobilePrevBtn = document.getElementById('mobilePrevBtn');
      const mobileNextBtn = document.getElementById('mobileNextBtn');
      const currentSlideSpan = document.getElementById('currentSlide');
      const totalSlidesSpan = document.getElementById('totalSlides');
      const acceptMobileBtn = document.getElementById('acceptMobileBtn');
      
      // Desktop Navigation Variables
      const desktopPrevBtn = document.getElementById('desktopPrevBtn');
      const desktopNextBtn = document.getElementById('desktopNextBtn');
      const currentSectionSpan = document.getElementById('currentSection');
      const acceptDesktopBtn = document.getElementById('acceptDesktopBtn');
      
      let currentSlide = 0;
      let currentDesktopSection = 1;
      const totalSlides = 11;
      const totalDesktopSections = 11;
      
      // Initialize Mobile Navigation
      function initMobileNavigation() {
        totalSlidesSpan.textContent = totalSlides;
        updateMobileNav();
        
        // Previous button
        mobilePrevBtn.addEventListener('click', () => {
          if (currentSlide > 0) {
            currentSlide--;
            updateSlidePosition();
            updateMobileNav();
          }
        });
        
        // Next button
        mobileNextBtn.addEventListener('click', () => {
          if (currentSlide < totalSlides - 1) {
            currentSlide++;
            updateSlidePosition();
            updateMobileNav();
          }
        });
        
        // Dot navigation
        slideDots.forEach((dot, index) => {
          dot.addEventListener('click', () => {
            currentSlide = index;
            updateSlidePosition();
            updateMobileNav();
          });
        });
        
        // Accept button for mobile
        acceptMobileBtn.addEventListener('click', () => {
          showAcknowledgementModal();
        });
      }
      
      function updateSlidePosition() {
        slideWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
        slideWrapper.style.transition = 'transform 0.3s ease';
      }
      
      function updateMobileNav() {
        // Update counter
        currentSlideSpan.textContent = currentSlide + 1;
        
        // Update dots
        slideDots.forEach((dot, index) => {
          dot.classList.toggle('active', index === currentSlide);
        });
        
        // Update buttons
        mobilePrevBtn.disabled = currentSlide === 0;
        mobileNextBtn.disabled = currentSlide === totalSlides - 1;
        
        // Show/hide accept button on last slide
        if (currentSlide === totalSlides - 1) {
          acceptMobileBtn.style.display = 'flex';
        } else {
          acceptMobileBtn.style.display = 'none';
        }
      }
      
      // Initialize Desktop Navigation
      function initDesktopNavigation() {
        updateDesktopNav();
        
        desktopPrevBtn.addEventListener('click', () => {
          if (currentDesktopSection > 1) {
            currentDesktopSection--;
            scrollToDesktopSection();
            updateDesktopNav();
          }
        });
        
        desktopNextBtn.addEventListener('click', () => {
          if (currentDesktopSection < totalDesktopSections) {
            currentDesktopSection++;
            scrollToDesktopSection();
            updateDesktopNav();
          }
        });
        
        // Accept button for desktop
        acceptDesktopBtn.addEventListener('click', () => {
          showAcknowledgementModal();
        });
      }
      
      function scrollToDesktopSection() {
        const section = document.getElementById(`section${currentDesktopSection}`);
        if (section) {
          section.scrollIntoView({ behavior: 'smooth' });
        }
      }
      
      function updateDesktopNav() {
        currentSectionSpan.textContent = currentDesktopSection;
        desktopPrevBtn.disabled = currentDesktopSection === 1;
        desktopNextBtn.disabled = currentDesktopSection === totalDesktopSections;
      }
      
      // Acknowledgement Modal
      function showAcknowledgementModal() {
        ackModal.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
      
      function hideAcknowledgementModal() {
        ackModal.classList.remove('active');
        document.body.style.overflow = '';
      }
      
      // Toggle mobile menu
      menuToggle.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        const icon = menuToggle.querySelector('i');
        if (navLinks.classList.contains('active')) {
          icon.classList.remove('fa-bars');
          icon.classList.add('fa-times');
        } else {
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      });

      // Hide mobile menu when a link is clicked
      const links = navLinks.querySelectorAll('a');
      links.forEach(link => {
        link.addEventListener('click', function() {
          if (navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
            const icon = menuToggle.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
          }
        });
      });

      // Theme toggle functionality
      function toggleTheme() {
        const body = document.body;
        const isDark = body.classList.contains('dark-theme');
        
        if (isDark) {
          body.classList.remove('dark-theme');
          localStorage.setItem('theme', 'light');
        } else {
          body.classList.add('dark-theme');
          localStorage.setItem('theme', 'dark');
        }
        
        updateThemeIcons();
      }
      
      function updateThemeIcons() {
        const body = document.body;
        const isDark = body.classList.contains('dark-theme');
        const icons = document.querySelectorAll('.theme-toggle i');
        
        icons.forEach(icon => {
          if (isDark) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
          } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
          }
        });
      }
      
      // Check for saved theme preference
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
      }
      updateThemeIcons();

      // Email button functionality
      emailBtn.addEventListener('click', function() {
        window.location.href = 'mailto:katibayan.system@gmail.com?subject=Terms and Conditions Inquiry&body=Hello KatiBayan Team, I have a question about your Terms and Conditions:';
      });

      // Modal buttons
      acceptBtn.addEventListener('click', function() {
        alert('Thank you for accepting the Terms and Conditions. You may now proceed to use the KatiBayan Web Portal.');
        localStorage.setItem('termsAccepted', 'true');
        hideAcknowledgementModal();
      });
      
      declineBtn.addEventListener('click', function() {
        alert('You must accept the Terms and Conditions to use the KatiBayan Web Portal.');
        hideAcknowledgementModal();
      });

      // Scroll to top functionality
      window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
          scrollTopBtn.style.display = 'flex';
        } else {
          scrollTopBtn.style.display = 'none';
        }
      });

      scrollTopBtn.addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });

      // Initialize based on device
      function initBasedOnDevice() {
        if (window.innerWidth <= 768) {
          initMobileNavigation();
        } else {
          initDesktopNavigation();
        }
      }
      
      // Check on load and resize
      initBasedOnDevice();
      window.addEventListener('resize', initBasedOnDevice);

      // Add event listeners for theme toggles
      if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
      }
      
      if (mobileThemeToggle) {
        mobileThemeToggle.addEventListener('click', toggleTheme);
      }

      // Check if terms were previously accepted
      if (localStorage.getItem('termsAccepted') === 'true') {
        console.log('Terms and Conditions previously accepted.');
      }
    });
  </script>
</body>
</html>