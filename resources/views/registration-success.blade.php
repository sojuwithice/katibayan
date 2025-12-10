<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - KatiBayan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .success-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .success-icon {
            color: #4CAF50;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        
        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn {
            background-color: #3C87C4;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: #2a6ba3;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #3C87C4;
            padding: 1rem;
            margin: 1.5rem 0;
            text-align: left;
            border-radius: 4px;
        }
        
        .info-box h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .info-box p {
            color: #666;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .email-display {
            background-color: #f0f8ff;
            padding: 0.75rem;
            border-radius: 4px;
            margin-top: 1rem;
            font-family: monospace;
            color: #3C87C4;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1>Registration Successful!</h1>
        
        <!-- SK Role Message -->
        @if(isset($role) && $role === 'sk')
            <p>Thank you for registering as an SK Chairperson. Your account is pending admin approval.</p>
            <a href="/loginpage" class="btn">Return to Login</a>
            
            <div class="info-box">
                <h3>What happens next?</h3>
                <p>1. Your account will be reviewed by an administrator</p>
                <p>2. You will receive an email with your account details once approved</p>
                <p>3. Approval typically takes 1-3 business days</p>
            </div>
        
        <!-- KK Role Message -->
        @elseif(isset($role) && $role === 'kk')
            <p>Thank you for registering as a KK Member with KatiBayan.</p>
            <a href="/loginpage" class="btn">Go to Login</a>
            
            <div class="info-box">
                <h3>Important Information:</h3>
                <p>✓ Account details have been sent to your email</p>
                <p>✓ Change your password immediately after first login</p>
            </div>
        
        <!-- Default/Generic Message -->
        @else
            <p>Thank you for registering with KatiBayan. You will receive further instructions via email.</p>
            <a href="/loginpage" class="btn">Return to Login</a>
        @endif
        
        <!-- Show email address if available -->
        @if(isset($email) && !empty($email))
            <div class="info-box">
                <h3>Email Sent To:</h3>
                <div class="email-display">{{ $email }}</div>
                <p style="font-size: 0.8rem; color: #777; margin-top: 0.5rem;">
                    Check your inbox (and spam folder) for account details
                </p>
            </div>
        @endif
        
        <!-- Debug information (remove in production) -->
        @if(app()->environment('local'))
            <div style="margin-top: 1rem; padding: 0.5rem; background: #f0f0f0; border-radius: 4px; font-size: 0.8rem; color: #666;">
                <strong>Debug:</strong> Role: {{ $role ?? 'not set' }}, Email: {{ $email ?? 'not set' }}
            </div>
        @endif
    </div>
</body>
</html>