<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Enrollment 2026</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #e9c46a;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
        }
        h1 {
            color: #3a7c8c;
            margin: 0;
            font-size: 28px;
        }
        .greeting {
            font-size: 18px;
            color: #3a7c8c;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .highlight-box {
            background-color: #f8f9fa;
            border-left: 4px solid #e9c46a;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3a7c8c 0%, #2c6371 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(58, 124, 140, 0.3);
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(58, 124, 140, 0.4);
            color: white;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .important {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .deadline {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .deadline strong {
            color: #721c24;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://nextgenmontessori.com.au/wp-content/uploads/2025/02/Group-392.png" alt="Nextgen Montessori" class="logo">
            <h1>Re-Enrollment 2026</h1>
        </div>
        
        <div class="greeting">
            Dear {{ $parent->name }},
        </div>
        
        <div class="content">
            <p>We thank you for being part of <strong>Nextgen Montessori</strong> in 2025. We are planning our rooms for 2026 and ask you to supply your preferred days.</p>
            
            <div class="highlight-box">
                <p><strong>Your enrollment is ongoing</strong>, so your current days in 2025, will be moved across for you for 2026. For example: if you attend Monday, Tuesday, and Wednesday then these days will be automatically transferred across to 2026 for you.</p>
            </div>
            
            <p>Many parents choose to change their days in the new year, so if you wish to change or increase days, we ask that you advise now, so we can start planning.</p>
            
            <div class="important">
                <p><strong>📝 Action Required:</strong> If you wish to change your child's enrollment in 2026, please complete the re-enrollment form using the link below.</p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://mydiaree.com.au/re-enrollment/form" class="cta-button">
                    🔗 Complete Re-Enrollment Form
                </a>
            </div>
            
            <div class="deadline">
                <strong>⏰ Important Deadline: Please return this form by 1st October 2025</strong><br>
                <span style="color: #721c24;">to confirm your child's bookings for 2026</span>
            </div>
            
            <h3 style="color: #3a7c8c; margin-top: 30px;">What happens next?</h3>
            <ul style="padding-left: 20px;">
                <li>Click the link above to access the re-enrollment form</li>
                <li>Complete all required information about your child's preferences for 2026</li>
                <li>Submit the form before the deadline</li>
                <li>Our team will review and confirm your child's placement for 2026</li>
            </ul>
            
            <div class="highlight-box">
                <p><strong>💡 Need to update your information?</strong><br>
                This is also a great time to update any personal information such as address, work details, phone numbers, or authorized contacts. You can do this via the <a href="#" style="color: #3a7c8c;">iParent Portal</a> or by emailing us at <a href="mailto:truganina@nextgenmontessori.com.au" style="color: #3a7c8c;">truganina@nextgenmontessori.com.au</a></p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Questions?</strong> Feel free to contact us:</p>
            <p>
                📧 <a href="mailto:truganina@nextgenmontessori.com.au" style="color: #3a7c8c;">truganina@nextgenmontessori.com.au</a><br>
                🌐 <a href="https://nextgenmontessori.com.au" style="color: #3a7c8c;">nextgenmontessori.com.au</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                This email was sent to {{ $parent->email }}. If you believe you received this email in error, please contact us.
            </p>
        </div>
    </div>
</body>
</html>
