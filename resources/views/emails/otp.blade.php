<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>


    <style>
        /* General Reset */
        body,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        .email-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #49c5b6;
            padding: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
        }

        .content {
            padding: 20px;
            text-align: left;
            color: #333333;
            line-height: 1.6;
        }

        .content p {
            margin-bottom: 10px;
        }

        .otp-box {
            display: block;
            margin: 20px auto;
            padding: 10px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #003366;
            background-color: #f0f8ff;
            border-radius: 4px;
            width: 200px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            padding: 10px;
        }

        .footer a {
            color: #49c5b6;
            text-decoration: none;
        }
    </style>
</head>

<body style="color: #333; font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px;">
    <div
        style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div
            style="background-color: #49c5b6; padding: 20px; text-align: center; color: white; font-size: 24px; font-weight: bold;">
            Mydiaree
        </div>
        <div style="padding: 0 30px 30px 30px; background-color: #e0ebef;">

        <!-- Logo -->
        <div style="text-align: center; padding: 20px;">
            <a href="https://www.zinggerr.com" target="_blank">
                <img src="http://www.mydiaree.com.au/assets/img/MYDIAREE-new-logo.png" alt="Mydiaree Logo" style="max-width: 150px;">

            </a>
        </div>

        <!-- Content -->
            {{-- <p style="font-size: 16px;">Hi {{ $userName ?? 'User' }},</p> --}}
            <p style="font-size: 16px;">We received a request to reset your password.</p>
            <p style="font-size: 16px;">Use the following OTP to complete the process:</p>

            <!-- OTP Box -->
            <div style="text-align: center; margin: 20px 0;">
                <span
                    style="display: inline-block; background-color: #ffffff; color: #0c6587; font-size: 24px; font-weight: bold; padding: 10px 20px; border-radius: 6px; letter-spacing: 6px; border: 2px dashed #0c6587;">
                    {{ $otp }}
                </span>
            </div>

            <p style="font-size: 14px;">This OTP is valid for 10 minutes. Do not share it with anyone.</p>
            <p style="font-size: 14px;">If you didn’t request a password reset, you can safely ignore this email.</p>

            <br>
            <p style="font-size: 14px;">Thanks,<br>Team Mydiaree</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #49c5b6; padding: 15px; text-align: center; color: white; font-size: 12px;margin-top:-12px" >
            &copy; {{ date('Y') }} Mydiaree. All rights reserved.
        </div>

    </div>
</body>

</html>
