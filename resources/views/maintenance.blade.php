<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KatiBayan - Maintenance Mode</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 20px;
        }
        
        .maintenance-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 60px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .maintenance-icon {
            font-size: 80px;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .message {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .countdown {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .countdown-title {
            font-size: 16px;
            margin-bottom: 10px;
            opacity: 0.8;
        }
        
        #countdown-timer {
            font-size: 32px;
            font-weight: 700;
            font-family: monospace;
        }
        
        .contact-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .contact-info p {
            margin: 10px 0;
            font-size: 14px;
        }
        
        .contact-info i {
            margin-right: 10px;
            color: #ffd166;
        }
        
        .logo {
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 80px;
            filter: brightness(0) invert(1);
        }
        
        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin-top: 20px;
            overflow: hidden;
        }
        
        .progress {
            height: 100%;
            background: #ffd166;
            width: 70%;
            animation: progress 2s infinite ease-in-out;
        }
        
        @keyframes progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 0%; }
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 30px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            .message {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="KatiBayan" onerror="this.style.display='none'">
        </div>
        
        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1>We're Under Maintenance</h1>
        
        <div class="message">
            {{ $message ?? 'We are currently performing scheduled maintenance. We apologize for any inconvenience.' }}
        </div>
        
        @if(isset($retry) && $retry)
        <div class="countdown">
            <div class="countdown-title">Estimated time until completion:</div>
            <div id="countdown-timer">{{ gmdate("H:i:s", $retry) }}</div>
        </div>
        @endif
        
        <div class="progress-bar">
            <div class="progress"></div>
        </div>
        
        <div class="contact-info">
            <p><i class="fas fa-clock"></i> Maintenance started: {{ isset($time) ? date('F j, Y, g:i a', $time) : 'Recently' }}</p>
            <p><i class="fas fa-envelope"></i> Contact: katibayan.admin@gmail.com</p>
            <p><i class="fas fa-phone"></i> Emergency: +63 912 345 6789</p>
        </div>
    </div>

    <script>
        @if(isset($retry) && $retry)
        // Countdown timer
        function updateCountdown() {
            const retrySeconds = {{ $retry ?? 3600 }};
            const startTime = {{ $time ?? time() }};
            const currentTime = Math.floor(Date.now() / 1000);
            const elapsed = currentTime - startTime;
            const remaining = Math.max(0, retrySeconds - elapsed);
            
            const hours = Math.floor(remaining / 3600);
            const minutes = Math.floor((remaining % 3600) / 60);
            const seconds = remaining % 60;
            
            document.getElementById('countdown-timer').textContent = 
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        @endif
        
        // Auto-refresh page every 5 minutes to check if maintenance is over
        setTimeout(function() {
            window.location.reload();
        }, 300000); // 5 minutes
        
        // Try to access admin panel (for testing)
        function tryAdminAccess() {
            fetch('/admin/check-access')
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/admin';
                    }
                })
                .catch(error => console.log('Admin access not available'));
        }
        
        // Try admin access every minute
        setInterval(tryAdminAccess, 60000);
    </script>
</body>
</html>