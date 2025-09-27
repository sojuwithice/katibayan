<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Credentials</title>
</head>
<body>
    <h2>Welcome to KatiBayan!</h2>
    <p>Your account has been created successfully. Below are your login credentials:</p>

    <p><strong>Account Number:</strong> {{ $accountNumber }}</p>
    <p><strong>Password:</strong> {{ $plainPassword }}</p>

</p>

    <p>You may now login using these credentials. For security, please change your password after logging in.</p>

    <br>
    <p>Thank you,<br>KatiBayan System</p>
</body>
</html>
