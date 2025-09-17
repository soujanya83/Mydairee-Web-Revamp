<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nextgen Montessori Re-Enrolment 2026</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #3a7c8c;
            --secondary-color: #f8f9fa;
            --accent-color: #e9c46a;
        }
        
        body {
            background-color: #f9f9f9;
            color: #333;
        }
        
        .logo-container {
            text-align: center;
            padding: 20px 0;
            background-color: white;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .section-title {
            color: var(--primary-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        
        .day-checkbox {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .day-option {
            flex: 1;
            min-width: 100px;
            text-align: center;
        }
        
        .form-check-input {
            margin-top: 0;
        }
        
        .highlight-box {
            background-color: var(--secondary-color);
            border-left: 4px solid var(--accent-color);
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2c6371;
            border-color: #2c6371;
        }
        
        @media (max-width: 768px) {
            .day-option {
                min-width: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="https://nextgenmontessori.com.au/wp-content/uploads/2025/02/Group-392.png" alt="Nextgen Montessori Logo" class="img-fluid" style="max-height: 80px;">
    </div>

    <div class="container form-container">
        <h1 class="text-center mb-4">Re-Enrolment 2026</h1>
        
        <div class="alert alert-info">
            <p>We thank you for being part of Nextgen Montessori in 2025. We are planning our rooms for 2026 and ask you to supply your preferred days. Your enrolment is ongoing, so your current days in 2025, will be moved across for you for 2026 e.g.: if you attend Monday, Tuesday, and Wednesday then these days will be automatically transferred across to 2026 for you.
</p>
            <p class="mb-0">Many parents choose to change their days in the new year, so if you wish to change or increase days, we ask that you advise now, so we can start planning.</p>
            <p class="mb-0"><strong>I wish to change my child’s enrolment in 2026.</strong></p>
        </div>
        
        <form id="reEnrolmentForm" action="{{ route('re-enrolment.store') }}" method="POST">
    @csrf            <!-- Child Information Section -->
            <h3 class="section-title">Child Information</h3>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="childName" class="form-label required-field">Child's Name</label>
                    <input type="text" class="form-control" id="childName" name="child_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="childDOB" class="form-label required-field">Date of Birth</label>
                    <input type="date" class="form-control" id="childDOB" name="child_dob" required>
                </div>
		<div class="col-md-6 mb-3">
                    <label for="ParentEmailid" class="form-label required-field">Parent Email ID</label>
                    <input type="text" class="form-control" id="ParentEmailid" name="parent_email" required>
                </div>

            </div>
            
            <!-- Current Days Section -->
            <h3 class="section-title">Current Days (2025)</h3>
            <p class="text-muted">Please select the days your child currently attends:</p>
            
            <div class="day-checkbox mb-4">
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="currentMonday" name="current_days[]" value="monday">
            <label class="form-check-label" for="currentMonday">Monday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="currentTuesday" name="current_days[]" value="tuesday">
            <label class="form-check-label" for="currentTuesday">Tuesday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="currentWednesday" name="current_days[]" value="wednesday">
            <label class="form-check-label" for="currentWednesday">Wednesday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="currentThursday" name="current_days[]" value="thursday">
            <label class="form-check-label" for="currentThursday">Thursday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="currentFriday" name="current_days[]" value="friday">
            <label class="form-check-label" for="currentFriday">Friday</label>
        </div>
    </div>
            
            <!-- Requested Days for 2026 -->
            <h3 class="section-title">Requested Days for 2026</h3>
            <p class="text-muted">Please select the days you would like your child to attend in 2026:</p>
            
            <div class="day-checkbox mb-4">
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="requestedMonday" name="requested_days[]" value="monday">
            <label class="form-check-label" for="requestedMonday">Monday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="requestedTuesday" name="requested_days[]" value="tuesday">
            <label class="form-check-label" for="requestedTuesday">Tuesday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="requestedWednesday" name="requested_days[]" value="wednesday">
            <label class="form-check-label" for="requestedWednesday">Wednesday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="requestedThursday" name="requested_days[]" value="thursday">
            <label class="form-check-label" for="requestedThursday">Thursday</label>
        </div>
        <div class="day-option">
            <input class="form-check-input" type="checkbox" id="requestedFriday" name="requested_days[]" value="friday">
            <label class="form-check-label" for="requestedFriday">Friday</label>
        </div>
    </div>
            
            <!-- Requested Sessions -->
            <h3 class="section-title">Requested Sessions</h3>
            <p class="text-muted">Please select your preferred session:</p>
            
            <div class="mb-4">
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="session_option" id="session9hrs" value="9_hours">
            <label class="form-check-label" for="session9hrs">
                <strong>9 Hours</strong> (8:30am - 5:30pm)
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="session_option" id="session10hrs1" value="10_hours_8_6">
            <label class="form-check-label" for="session10hrs1">
                <strong>10 Hours</strong> (8:00am - 6:00pm)
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="session_option" id="session10hrs2" value="10_hours_8_30_6_30">
            <label class="form-check-label" for="session10hrs2">
                <strong>10 Hours</strong> (8:30am - 6:30pm)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="session_option" id="sessionFullDay" value="full_day">
            <label class="form-check-label" for="sessionFullDay">
                <strong>Full Day</strong> (7:00am - 6:30pm)
            </label>
        </div>
    </div>
            
            <!-- Kinder Program -->
            <h3 class="section-title">Kinder Program</h3>
            <p class="text-muted">Please indicate if your child will be attending Kinder at Nextgen:</p>
            
            <div class="mb-4">
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="kinder_program" id="kinder3yo" value="3_year_old">
            <label class="form-check-label" for="kinder3yo">3-year-old Kinder</label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="kinder_program" id="kinder4yo" value="4_year_old">
            <label class="form-check-label" for="kinder4yo">4-year-old Kinder</label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="kinder_program" id="kinderUnfunded" value="unfunded">
            <label class="form-check-label" for="kinderUnfunded">Unfunded Kinder (3-5 years)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kinder_program" id="kinderNone" value="not_attending" checked>
            <label class="form-check-label" for="kinderNone">Not attending Kinder at Nextgen</label>
        </div>
    </div>
            
            <div class="highlight-box">
                <i class="bi bi-info-circle-fill"></i> If your child is attending the kindergarten at Nextgen Montessori, kindly fill the funded Kinder funding forms, available at the reception from November 2025.
            </div>
            
            <!-- Finishing Up Section -->
            <h3 class="section-title">Finishing Up</h3>
            <p class="text-muted">If your child will be attending Primary school in 2026 and finishing up at Nextgen Montessori, please provide the details below:</p>
            
            <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <label for="finishing_child_name" class="form-label">Child's Name</label>
            <input type="text" class="form-control" id="finishing_child_name" name="finishing_child_name">
        </div>
        <div class="col-md-6 mb-3">
            <label for="last_day" class="form-label">Last day at Nextgen Montessori</label>
            <input type="date" class="form-control" id="last_day" name="last_day">
        </div>
    </div>
            
            <div class="highlight-box">
                <i class="bi bi-exclamation-triangle-fill"></i> Please note that your child must attend their final booked day physically or Centrelink will remove the CCS for all absences leading up to the final booked day.
            </div>
            
            <!-- Holiday Information -->
            <h3 class="section-title">Holiday Plans</h3>
            <p class="text-muted">If you are away on holidays (which are more than a week) between <strong>October 2025 – January 2026</strong>, kindly mention the dates below to organise the educator's Annual leave for that period.</p>
            
            <div class="mb-4">
        <label for="holiday_dates" class="form-label">Holiday Dates</label>
        <textarea class="form-control" id="holiday_dates" name="holiday_dates" rows="3" placeholder="Please specify the dates you will be away"></textarea>
    </div>
            
            <!-- Update Information Reminder -->
            <div class="alert alert-warning">
                <h4><i class="bi bi-arrow-repeat"></i> Information Update Reminder</h4>
                <p>We also take this time to remind you of any personal information that needs updating. This can be done on <a href="#" class="alert-link">iParent Portal</a> or via email to the center at <a href="mailto:truganina@nextgenmontessori.com.au" class="alert-link">truganina@nextgenmontessori.com.au</a>.</p>
                <p class="mb-0">This includes change of address, work details, phone numbers, authorised contacts etc, as it is your responsibility to ensure that these are kept up to date and current.</p>
            </div>
            
            <!-- Submit Section -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-circle"></i> Submit Re-Enrolment
        </button>
    </div>
            
            <div class="text-center mt-4">
                <p class="text-muted"><small>Please return this form by <strong>1st October 2025</strong> to confirm your child's bookings for 2026.</small></p>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>

document.getElementById('reEnrolmentForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Disable submit button and show loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for submitting your re-enrolment form for 2026!');
            this.reset(); // Reset form
        } else {
            alert('Error: ' + (data.message || 'Please try again.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

    </script>
</body>
</html>