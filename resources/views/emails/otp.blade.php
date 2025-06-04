<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .otp {
            font-size: 24px;
            color: #2d3748;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #aaa;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset Request</h2>
        <p>Hello,</p>
        <p>We received a request to reset your password. Use the following OTP to complete the process:</p>
        <div class="otp">{{ $otp }}</div>
        <p>This OTP is valid for 10 minutes. Do not share it with anyone.</p>
        <p>If you did not request a password reset, you can safely ignore this email.</p>
        <div class="footer">
            &copy; {{ date('Y') }} My Dairee. All rights reserved.
        </div>
    </div>
</body>
</html>
