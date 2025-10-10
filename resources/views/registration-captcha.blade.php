<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Verify you are human</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    html,body{height:100%;margin:0;background:#fff;color:#333;font-family:Arial,Helvetica,sans-serif}
    .wrap{max-width:820px;margin:6% auto;padding:40px;text-align:left}
    h1{font-weight:600;margin:0 0 8px;color:#333;font-size:40px}
    p.lead{color:#666;font-size:18px;margin-bottom:30px}
    .captcha-box{padding:18px;display:flex;align-items:left;gap:16px;justify-content:left}
    .submit-note{margin-top:24px;color:#888;font-size:14px}
    .error{color:#ff0000;margin-top:12px}
    .center{display:flex;justify-content:left;margin-top:20px}
  </style>

  <!-- Google reCAPTCHA v2 -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <div class="wrap">
    <h1>katibayan.web.com</h1>
    <p class="lead">Complete the action below to proceed with your registration.</p>

    @if ($errors->any())
      <div class="error">
          {{ implode(', ', $errors->all()) }}
      </div>
    @endif

    <div class="captcha-box">
      <!-- Left: checkbox widget -->
      <form id="captchaForm" method="POST" action="{{ route('register.complete') }}">
        @csrf

        <!-- reCAPTCHA widget: callback will auto-submit the form -->
        <div class="g-recaptcha"
             data-sitekey="{{ $siteKey }}"
             data-callback="onCaptchaSuccess"
             data-size="normal">
        </div>

        <div class="center">
          <noscript>
            <p style="color:#666">Please enable JavaScript to complete the captcha.</p>
          </noscript>
        </div>
      </form>

      
    </div>

    <p class="submit-note">katibayan.web.com needs to review the security of your connection before proceeding.</p>
  </div>

  <script>
    // Called by reCAPTCHA when user succeeds → auto submit the form with the token
    function onCaptchaSuccess(token) {
      // token is automatically placed into the g-recaptcha-response hidden input by Google iframe.
      document.getElementById('captchaForm').submit();
    }
  </script>
</body>
</html>