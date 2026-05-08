<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #f44336;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #f44336;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .column {
            flex: 1;
            margin-right: 20px;
        }
        .label {
            font-weight: bold;
            color: #333;
            font-size: 12px;
        }
        .value {
            color: #555;
            font-size: 12px;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #e0e0e0;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #999;
        }
        table td {
            padding: 8px;
            border: 1px solid #999;
            font-size: 12px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .submitted-date {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Re-Enrollment Submission Details</h1>
        <p>Nextgen Childcare Centre</p>
        <p>Submission ID: #{{ $reEnrolment->id }}</p>
    </div>

    <!-- Child Information -->
    <div class="section">
        <div class="section-title">Child Information</div>
        <div class="row">
            <div class="column">
                <div class="label">Child Name</div>
                <div class="value">{{ $reEnrolment->child_name }}</div>
            </div>
            <div class="column">
                <div class="label">Date of Birth</div>
                <div class="value">{{ $reEnrolment->child_dob?->format('d M Y') ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Parent Information -->
    <div class="section">
        <div class="section-title">Parent/Guardian Information</div>
        <div class="row">
            <div class="column">
                <div class="label">Parent Email</div>
                <div class="value">{{ $reEnrolment->parent_email }}</div>
            </div>
        </div>
    </div>

    <!-- Current Attendance -->
    <div class="section">
        <div class="section-title">Current Attendance</div>
        <div class="row">
            <div class="column">
                <div class="label">Days Attending</div>
                <div class="value">{{ $reEnrolment->current_days_formatted ?: 'Not specified' }}</div>
            </div>
            <div class="column">
                <div class="label">Session Option</div>
                <div class="value">{{ $reEnrolment->session_option_display ?: 'Not specified' }}</div>
            </div>
        </div>
    </div>

    <!-- Requested Attendance -->
    <div class="section">
        <div class="section-title">Requested Attendance</div>
        <div class="row">
            <div class="column">
                <div class="label">Requested Days</div>
                <div class="value">{{ $reEnrolment->requested_days_formatted ?: 'Not specified' }}</div>
            </div>
            <div class="column">
                <div class="label">Requested Session</div>
                <div class="value">{{ $reEnrolment->session_option_display ?: 'Not specified' }}</div>
            </div>
        </div>
    </div>

    <!-- Kinder Program -->
    <div class="section">
        <div class="section-title">Kinder Program</div>
        <div class="row">
            <div class="column">
                <div class="label">Program Selected</div>
                <div class="value">{{ $reEnrolment->kinder_program_display ?: 'Not attending' }}</div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="section">
        <div class="section-title">Additional Information</div>
        @if($reEnrolment->finishing_child_name)
        <div class="row">
            <div class="column">
                <div class="label">Finishing Child Name</div>
                <div class="value">{{ $reEnrolment->finishing_child_name }}</div>
            </div>
        </div>
        @endif

        @if($reEnrolment->last_day)
        <div class="row">
            <div class="column">
                <div class="label">Last Day</div>
                <div class="value">{{ $reEnrolment->last_day?->format('d M Y') }}</div>
            </div>
        </div>
        @endif

        @if($reEnrolment->holiday_dates)
        <div class="row">
            <div class="column">
                <div class="label">Holiday Dates</div>
                <div class="value">{{ $reEnrolment->holiday_dates }}</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Submission Status -->
    <div class="section">
        <div class="section-title">Submission Status</div>
        <div class="row">
            <div class="column">
                <div class="label">Submitted Date</div>
                <div class="value">{{ $reEnrolment->created_at?->format('d M Y H:i') ?? 'N/A' }}</div>
            </div>
            <div class="column">
                <div class="label">Status</div>
                <div class="value">
                    @if($reEnrolment->processed_at)
                        <strong style="color: green;">Completed</strong> - {{ $reEnrolment->processed_at->format('d M Y H:i') }}
                    @else
                        <strong style="color: orange;">Pending</strong>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="submitted-date">
        <p>Generated on: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="footer">
        <p>This is an official document from Nextgen Childcare Centre. Please retain for your records.</p>
        <p>&copy; {{ date('Y') }} Nextgen Childcare Centre. All rights reserved.</p>
    </div>
</body>
</html>
