<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f44336;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Re-Enrollment Link</h2>
        </div>

        <div class="content">
            <p>Dear Parent/Guardian,</p>

            <p>This is to confirm that a re-enrollment request has been submitted for <strong>{{ $child_name }}</strong>.</p>

            <p>Please click the button below to view and manage the re-enrollment details:</p>

            <div style="text-align: center;">
                <a href="{{ $enrollment_link }}" class="button">View Re-Enrollment</a>
            </div>

            <p><strong>Submission Details:</strong></p>
            <ul>
                <li>Child Name: {{ $child_name }}</li>
                <li>Submitted Date: {{ $submitted_date }}</li>
                <li>Parent Email: {{ $parent_email }}</li>
            </ul>

            <p>If you did not submit this re-enrollment request, please contact us immediately.</p>

            <p>Thank you!</p>

            <p>Best regards,<br>
            <strong>Nextgen Childcare Centre</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Nextgen Childcare Centre. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
