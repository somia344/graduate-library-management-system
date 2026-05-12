<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            color: #0D5C63;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 25px;
            line-height: 1.6;
            color: #2D3748;
        }
        .btn {
            display: inline-block;
            background: #0D5C63;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: 600;
        }
        .btn:hover {
            background: #084C52;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6B7280;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E2E8F0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📚 Graduate Library</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $name }}</strong>,</p>
            <p>We received a request to reset your password for your Graduate Library account.</p>
            <p style="text-align: center;">
                <a href="{{ $resetLink }}" class="btn">Reset Password</a>
            </p>
            <p>If you did not request a password reset, please ignore this email.</p>
            <p><strong>This link will expire in 60 minutes.</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Graduate Library. All rights reserved.</p>
            <p>123 Library Street, Education City</p>
        </div>
    </div>
</body>
</html>