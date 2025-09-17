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
        <div style="padding: 20px 30px; background-color: #f4f7f9; font-family: Arial, sans-serif; color: #333;">
            <!-- Logo -->
            <div style="text-align: center; padding: 20px;">
                <a href="https://www.zinggerr.com" target="_blank">
                    <img src="http://www.mydiaree.com.au/assets/img/MYDIAREE-new-logo.png"
                        alt="Mydiaree Logo" style="max-width: 160px;">
                </a>
            </div>

            <!-- Content Box -->
            <div style="background: #ffffff; border-radius: 10px; padding: 25px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">
                <h2 style="font-size: 20px; margin-bottom: 15px; color: #2a4365;">
                    🌟 You’ve Got a New Enquiry!
                </h2>

                <p style="font-size: 16px; line-height: 1.6;">
                    Hello Team,
                    <br><br>
                    A new enquiry has just been submitted by
                    <strong style="color:#1a202c;">{{ $name ?? 'User' }}</strong>.
                    Please find the details below:
                </p>

                <p style="font-size: 16px; line-height: 1.6;">
                    <strong>📧 Email Address:</strong> {{ $email ?? 'N/A' }} <br>
                    <strong>📞 Phone Number:</strong> {{ $phone ?? 'N/A' }}
                </p>

                @if(!empty($userMessage))
                <p style="font-size: 16px; line-height: 1.6; margin-top: 15px;">
                    <strong>💬 Message:</strong><br>
                    <em style="color:#4a5568;">“{{ $userMessage }}”</em>
                </p>
                @endif

                <div style="margin-top: 20px; padding: 15px; background: #edf2f7; border-left: 4px solid #2b6cb0; border-radius: 6px;">
                    <p style="font-size: 14px; color: #2d3748; margin: 0;">
                        🚀 <strong>Action Needed:</strong> Kindly respond to this enquiry at the earliest
                        to ensure a smooth communication experience.
                    </p>
                </div>

                <br>
                <p style="font-size: 14px; color: #555;">
                    Warm regards, <br>
                    <strong>Team Mydiaree</strong>
                </p>
            </div>
        </div>


        <!-- Footer -->
        <div style="background-color: #49c5b6; padding: 15px; text-align: center; color: white; font-size: 12px;margin-top:-12px">
            &copy; {{ date('Y') }} Mydiaree. All rights reserved.
        </div>

    </div>
</body>

</html>