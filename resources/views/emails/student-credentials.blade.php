<!DOCTYPE html>
<html>
<head>
    <title>Library Account Credentials</title>
</head>
<body>
    <h2>Welcome to Graduate Library!</h2>
    <p>Dear {{ $name }},</p>
    <p>Your library account has been created successfully.</p>
    
    <h3>Login Credentials:</h3>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    
    <p><a href="{{ $login_url }}">Click here to login</a></p>
    
    <p>Please change your password after first login.</p>
</body>
</html>