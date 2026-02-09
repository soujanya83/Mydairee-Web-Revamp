@extends('layout.master')
@section('title', 'Head Checks')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .print-btn {
        transition: 0.3s;
    }
    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
</style>
<style>
    
    /* Label styling */
.custom-label {
    display: block;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 6px;
    font-size: 15px;
}

/* Input styling */
.custom-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    background-color: #fff;
    font-size: 14px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: border-color 0.3s, box-shadow 0.3s;
}

.custom-input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
}


<style>
.is-invalid {
    border-color: #dc3545 !important;
}

.toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
}

.toast {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.toast-success {
    background-color: #28a745;
    /* Green for success */
}

.toast-error {
    background-color: #dc3545;
    /* Red for error */
}

.toast-close-button {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    color: white;
    margin-left: 10px;
}

.toast-message {
    flex: 1;

}

.c_list .avatar {
    height: 45px;
    width: 50px;
}
</style>
</style>
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --gradient-primary: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            --gradient-success: linear-gradient(180deg, #1cc88a 10%, #13855c 100%);
        }

        body {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            padding: 2rem 0;
            min-height: 100vh;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-title {
            color: var(--sd-accent, #4e73df);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        body[class*="theme-"] .page-title {
            color: #000;
        }

        .breadcrumb-text {
            color: var(--dark-color);
            font-size: 1.1rem;
        }

        .breadcrumb-text a {
            color: var(--sd-accent, #4e73df);
            text-decoration: none;
            font-weight: 600;
        }

        body[class*="theme-"] .breadcrumb-text a {
            color: #000;
        }

        .breadcrumb-text a:hover {
            text-decoration: underline;
        }

        /* .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        } */

        .headcheck-card {
            background: linear-gradient(145deg, #ffffff, #f8f9fc);
            border: none;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .headcheck-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(180deg, var(--sd-accent, #4e73df) 10%, var(--sd-accent, #224abe) 100%);
        }

        .headcheck-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .headcheck-card .card-body {
            padding: 2rem;
        }

        .custom-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-input {
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .custom-input:focus {
            border-color: var(--sd-accent, #4e73df);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            background: #fff;
        }

        .time-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .form-number {
            width: 70px;
            text-align: center;
            font-weight: 600;
        }

        .time-separator {
            font-weight: bold;
            color: var(--sd-accent, #4e73df);
            font-size: 1.2rem;
        }

        .time-icon {
            color: var(--sd-accent, #4e73df);
            font-size: 1.2rem;
            margin-left: 0.5rem;
        }

        .form-time {
            margin-top: 0.5rem;
        }

        .btn-custom {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-add {
            background: var(--gradient-success);
            color: white;
            box-shadow: 0 4px 15px rgba(28, 200, 138, 0.4);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(28, 200, 138, 0.6);
            color: white;
        }

        .btn-save {
            background: linear-gradient(180deg, var(--sd-accent, #4e73df) 10%, var(--sd-accent, #224abe) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
        }

        body[class*="theme-"] .btn-save {
            color: #000;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.6);
            color: white;
        }

        body[class*="theme-"] .btn-save:hover {
            color: #000;
        }

        .btn-remove {
            background: linear-gradient(180deg,rgb(229, 182, 182) 10%,hsl(0, 36.60%, 83.90%) 100%);
            color: red;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-remove:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(231, 74, 59, 0.4);
            color: red;
        }

        .empty-state {
            background: linear-gradient(145deg, #f8f9fc, #ffffff);
            border: 2px dashed #e3e6f0;
            border-radius: 15px;
            padding: 3rem;
            text-align: center;
            margin: 2rem 0;
        }

        .empty-state-icon {
            font-size: 3rem;
            color: var(--info-color);
            margin-bottom: 1rem;
        }

        .empty-state-title {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: #858796;
            font-size: 1.1rem;
        }

        .action-buttons {
            padding: 2rem 0;
            text-align: center;
            border-top: 2px solid #e3e6f0;
            margin-top: 2rem;
        }

        .card-number {
            position: absolute;
            top: 15px;
            right: 20px;
            background: linear-gradient(180deg, var(--sd-accent, #4e73df) 10%, var(--sd-accent, #224abe) 100%);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        body[class*="theme-"] .card-number {
            color: #000;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .form-container {
                padding: 1.5rem;
            }
            
            .headcheck-card .card-body {
                padding: 1.5rem;
            }
            
            .time-input-group {
                justify-content: center;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection
@section('content')

<div class="d-flex justify-content-end align-items-center" style="margin-right: 20px; margin-top: -50px; gap: 10px; flex-wrap: wrap;">

    {{-- Center Dropdown --}}
    <div class="dropdown mr-2">
        <button class="btn btn-outline-info btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important; left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
                   style="background-color:white;" data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Room Dropdown --}}
    <div class="dropdown mr-2">
        @if(empty($rooms))
            <div class="btn btn-outline-info btn-lg dropdown-toggle">NO ROOMS AVAILABLE</div>
        @else
            <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="roomDropdown" data-toggle="dropdown">
                {{ strtoupper($rooms->firstWhere('id', request('roomid', $roomid))->name ?? 'Select Room') }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="roomDropdown">
                @foreach($rooms as $room)
                    <a class="dropdown-item" href="{{ url()->current() }}?centerid={{ $centerid }}&roomid={{ $room->id }}">
                        {{ strtoupper($room->name) }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Date Picker --}}
    @php
        $calDate = request('date')
            ? \Carbon\Carbon::parse(request('date'))->format('d-m-Y')
            : \Carbon\Carbon::parse($date ?? now())->format('d-m-Y');
    @endphp
    <div class="form-group mb-0">
        <div class="input-group date">
            <input type="text" class="form-control drop-down btn btn-outline-info btn-lg" id="txtCalendar" name="start_date" value="{{ $calDate }}">
            <span class="">
                <i class="simple-icon-calendar"></i>
            </span>
        </div>
    </div>

</div>
<hr>

  <main class="main-container default-transition" style="padding-block:2em;padding-inline:2em;">
        <div class="default-transition">
            <div class="container-fluid">
                
                <div class="d-flex justify-content-end mb-3">
                    <form action="{{ route('headcheck.print')}}" method="post" class="class">
                                   <input type="hidden" name="roomid" value="{{ request('roomid', $roomid) }}">
                                   @csrf
                                <input type="hidden" name="centerid" value="{{ request('centerid', $centerid) }}">
                                <input type="hidden" name="diarydate" value="{{ $calDate }}">
    <button class="btn btn-primary shadow-sm px-4 py-2 rounded-pill print-btn"
            type="submit">
        🖨 view
    </button>
    </form>
</div>

                <div class="row">
                    <!-- Page Header -->
                    <!-- <div class="col-12 mb-4">
                        <div class="page-header fade-in">
                            <div class="d-flex justify-content-between align-items-end flex-wrap">
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
                                    <h1 class="page-title mb-0">
                                        <i class="fas fa-clipboard-check me-3"></i>Daily Head Check
                                    </h1>
                                    <p class="breadcrumb-text mb-0 mx-md-4">
                                        <a href=""><i class="fas fa-home me-1"></i>Dashboard</a>
                                        <span class="mx-2">|</span> 
                                        <span><i class="fas fa-users me-1"></i>Head Checks</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Head Check Form -->
                    <div class="col-12">
                        <div class="form-container fade-in">
                            <form action="{{ route('headchecks.store') }}" method="POST" id="headCheckForm">
                                @csrf
                                <input type="hidden" name="roomid" value="{{ request('roomid', $roomid) }}">
                                <input type="hidden" name="centerid" value="{{ request('centerid', $centerid) }}">
                                <input type="hidden" name="diarydate" value="{{ $calDate }}">

                                <div id="form-fields">
                                    @php $i = 1; @endphp
                                    @forelse($headChecks as $key => $hc)
                                        @php
                                         

                                                 preg_match('/(\d+)h:(\d+)m/', $hc->time, $matches);
    $formattedTime = sprintf('%02d:%02d', $matches[1], $matches[2]);
                                        @endphp
                                        
        <div class="headcheck-card card fade-in mb-3 position-relative">
    <div class="card-number">{{ $i }}</div>
    <div class="card-body">
        <input type="hidden" name="headcheck[]" id="headcheckid" value="{{ $hc->id }}">

        <div class="row g-3">
            <!-- Time -->
            <div class="col-lg-3 col-md-6">
                <label class="custom-label">
                    <i class="fas fa-clock me-2"></i>Time
                </label>

                @php

              
                    preg_match('/(\d+)h:(\d+)m/', $hc->time, $matches);
                    $formattedTime = sprintf('%02d:%02d', $matches[1], $matches[2]);
                @endphp
                <input type="time" name="timePicker[]" class="custom-input" value="{{ $formattedTime }}" required>
            </div>

            <!-- Head Count -->
            <div class="col-lg-3 col-md-6">
                <label class="custom-label">
                    <i class="fas fa-users me-2"></i>Head Count
                </label>
                <input type="number" class="custom-input" name="headCount[]" value="{{ $hc->headcount }}"
                       placeholder="Enter count" required>
            </div>

               <!-- <div class="col-lg-2 col-md-4">
                <label class="custom-label">
                    <i class="fas fa-pen-nib me-2"></i>Signature
                </label>
                <select name="signature[]" class="custom-input">
                    <option value="">Select</option>
                    @foreach($staffs as $staff)
                        <option value="{{ $staff->name }}">{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div> -->

            <!-- Comments -->
            <div class="col-lg-5 col-md-8">
                <label class="custom-label">
                    <i class="fas fa-comment me-2"></i>Signature
                </label>
                <input type="text" class="custom-input" name="signature[]" value="{{ $hc->signature }}"
                       placeholder="Signature" required>
            </div>

            <!-- Signature -->
         
        </div>

        <!-- Remove Button at Bottom Right -->
        @if($i == 1 && $date == now()->format('Y-m-d'))
          <div class="d-flex justify-content-end mt-3">
    <button type="button" class="btn btn-danger btn-sm minus-btn" title="Remove Entry">
        <i class="fas fa-trash-alt"></i>
    </button>
</div>
 
        @endif
    </div>
</div>


                                       
                                    @empty
                                        @if(!($date ?? now()->format('Y-m-d')) == now()->format('Y-m-d'))
                                            <!-- Empty State for Past Dates -->
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </div>
                                                <h5 class="empty-state-title">No Head Checks Found</h5>
                                                <p class="empty-state-text">No head check entries were recorded for this date.</p>
                                            </div>
                                        @else
                                            <!-- Empty State for Today -->
                                            <div class="empty-state" id="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </div>
                                                <h5 class="empty-state-title">No Head Checks Found</h5>
                                                <p class="empty-state-text">Click the "Add New Entry" button to create your first head check entry.</p>
                                            </div>
                                        @endif
                                    @endforelse
                                </div>

                                <!-- Action Buttons -->
                                @if(($date ?? now()->format('Y-m-d')) == now()->format('Y-m-d'))
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-custom btn-add me-3" id="add-btn">
                                            <i class="fas fa-plus mx-1"></i>Add New
                                        </button>
                                        <button type="submit" class="btn btn-custom btn-save" id="save_headcheck">
                                            <i class="fas fa-save mx-1"></i>Save
                                        </button>
                                    </div>
                                @endif
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="toast-container" class="toast-bottom-right"
        style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>
    </main>



@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
    flatpickr("#txtCalendar", {
        dateFormat: "d-m-Y",
        defaultDate: "{{ $calDate }}",
        maxDate: "today"
    });
</script>
<!-- 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script> -->
    
    <script>
        $(document).ready(function() {
            // Get initial entry count from existing entries
            let entryCount = $('.headcheck-card').length;

            // Hide empty state if we have entries
            if (entryCount > 0) {
                $('#empty-state').addClass('d-none');
            }

            // Add new entry
            $('#add-btn').click(function() {
                entryCount++;
                const currentTime = new Date();
                const hours = currentTime.getHours().toString().padStart(2, '0');
                const minutes = currentTime.getMinutes().toString().padStart(2, '0');
                
         const newEntry = `
    <div class="headcheck-card card fade-in mb-3 position-relative">
        <div class="card-number">${entryCount}</div>
        <div class="card-body">
            <input type="hidden" name="headcheck[]" id="headcheckid" value="">

            <div class="row g-3">
                <!-- Time -->
                <div class="col-lg-3 col-md-6">
                    <label class="custom-label">
                        <i class="fas fa-clock me-2"></i>Time
                    </label>
                    <input type="time" name="timePicker[]" class="custom-input" value="${hours}:${minutes}" required>
                </div>

                <!-- Head Count -->
                <div class="col-lg-3 col-md-6">
                    <label class="custom-label">
                        <i class="fas fa-users me-2"></i>Head Count
                    </label>
                    <input type="number" class="custom-input" name="headCount[]" placeholder="Enter count" required>
                </div>

                <!-- Comments / Signature -->
                <div class="col-lg-5 col-md-8">
                    <label class="custom-label">
                        <i class="fas fa-comment me-2"></i>Signature
                    </label>
                    <input type="text" class="custom-input" name="signature[]" placeholder="Signature" required>
                </div>
            </div>

            <!-- Remove Button -->
            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-danger btn-sm minus-btn" title="Remove Entry">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
`;

                
                // Hide empty state and append new entry
                $('#empty-state').addClass('d-none');
                $('#form-fields').append(newEntry);
                updateCardNumbers();
            });

            // Remove entry
            $(document).on('click', '.btn-remove', function() {
                $(this).closest('.headcheck-card').fadeOut(300, function() {
                    $(this).remove();
                    entryCount--;
                    updateCardNumbers();
                    checkEmptyState();
                });
            });

            // Update card numbers
            function updateCardNumbers() {
                $('.headcheck-card').each(function(index) {
                    $(this).find('.card-number').text(index + 1);
                });
            }

            // Check if we should show empty state
            function checkEmptyState() {
                if ($('.headcheck-card').length === 0) {
                    $('#empty-state').removeClass('d-none');
                } else {
                    $('#empty-state').addClass('d-none');
                }
            }

            // Sync time inputs
            $(document).on('change', '.form-number', function() {
                const row = $(this).closest('.row');
                const hours = row.find('input[name="hour[]"]').val().padStart(2, '0');
                const minutes = row.find('input[name="mins[]"]').val().padStart(2, '0');
                row.find('input[name="timePicker[]"]').val(`${hours}:${minutes}`);
            });

            $(document).on('change', '.form-time', function() {
                const row = $(this).closest('.row');
                const timeValue = $(this).val();
                const [hours, minutes] = timeValue.split(':');
                row.find('input[name="hour[]"]').val(parseInt(hours));
                row.find('input[name="mins[]"]').val(parseInt(minutes));
            });

            // Form validation
            function validateForm() {
                let isValid = true;
                let errors = [];

                $('.headcheck-card').each(function(index) {
                    const cardNum = index + 1;
                    const headCount = $(this).find('input[name="headCount[]"]').val();
                    const comments = $(this).find('input[name="comments[]"]').val();
                    
                    const hour = $(this).find('input[name="hour[]"]').val();
                    const mins = $(this).find('input[name="mins[]"]').val();

                    if (!headCount || headCount < 0) {
                        errors.push(`Entry ${cardNum}: Head count is required and must be positive`);
                        isValid = false;
                    }

                    if (!comments || comments ) {
                       errors.push(`Entry ${cardNum}: comment is required`);
                        isValid = false;
                    }

                    if (!hour || hour < 0 || hour > 23) {
                        errors.push(`Entry ${cardNum}: Valid hour (0-23) is required`);
                        isValid = false;
                    }

                    if (!mins || mins < 0 || mins > 59) {
                        errors.push(`Entry ${cardNum}: Valid minutes (0-59) is required`);
                        isValid = false;
                    }
                });

                if (!isValid) {


                    showtoast('error', errors.join('\n'));
                }

                return isValid;
            }

            // Form submission - Remove preventDefault to allow actual form submission
            $('#headCheckForm').submit(function(e) {
                // Validate form before submission
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
                
                // Show loading state
                const saveBtn = $('#save_headcheck');
                const originalText = saveBtn.html();
                saveBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);
                
                // Allow form to submit naturally to Laravel
                // The loading state will be reset on page reload/redirect
            });

            // Auto-save functionality (optional)
            let autoSaveTimeout;
            $(document).on('input', '.custom-input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(function() {
                    // Show auto-save indicator
                    if ($('#auto-save-indicator').length === 0) {
                        $('body').append('<div id="auto-save-indicator" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;"><div class="alert alert-info alert-sm"><i class="fas fa-save me-2"></i>Auto-saving...</div></div>');
                        
                        setTimeout(function() {
                            $('#auto-save-indicator').fadeOut(function() {
                                $(this).remove();
                            });
                        }, 2000);
                    }
                }, 3000);
            });
        });

        function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }

    function print(headchecks){
        console.log(headchecks);

    }
    </script>


<script>
  // Your sample JSON
  const headchecks = @json($headChecks);

//   const container = document.getElementById('data-container');

  // Create card layout dynamically
  headchecks.forEach(item => {
    const card = document.createElement('div');
    card.style.cssText = `
      background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);
      padding:15px; width:260px; margin:10px; display:inline-block; vertical-align:top;
    `;
    
    card.innerHTML = `
      <h3 style="color:#4CAF50; margin:0 0 10px 0;">Head Check #${item.id}</h3>
      <p><strong>Date:</strong> ${item.diarydate}</p>
      <p><strong>Time:</strong> ${item.time}</p>
      <p><strong>Headcount:</strong> ${item.headcount}</p>
      <p><strong>Room:</strong> ${item.roomid}</p>
      <p><strong>Comments:</strong> ${item.comments}</p>
      <button style="
        background:linear-gradient(135deg,#4CAF50,#45a049);
        color:#fff;border:none;padding:6px 12px;border-radius:6px;cursor:pointer;width:100%;
      " onclick='openInNewTab(${JSON.stringify(item)})'>Open in New Tab</button>
    `;
    
    container.appendChild(card);
  });

  // Function to open the data in a new tab
  function print(data) {
    const newTab = window.open('', '_blank');
    newTab.document.write(`
      <html>
        <head>
          <title>Head Check #${data.id}</title>
          <style>
            body { font-family: Arial; padding: 20px; }
            h2 { color: #4CAF50; }
            p { font-size: 16px; }
            button { 
              margin-top: 20px; padding:8px 16px; background:#4CAF50; color:#fff; 
              border:none; border-radius:6px; cursor:pointer;
            }
          </style>
        </head>
        <body>
          <h2>Head Check #${data.id}</h2>
          <p><strong>Date:</strong> ${data.diarydate}</p>
          <p><strong>Time:</strong> ${data.time}</p>
          <p><strong>Headcount:</strong> ${data.headcount}</p>
          <p><strong>Room ID:</strong> ${data.roomid}</p>
          <p><strong>Comments:</strong> ${data.comments}</p>
          <p><strong>Created By:</strong> ${data.createdBy}</p>
          <button onclick="window.print()">Print</button>
        </body>
      </html>
    `);
    newTab.document.close();
  }
</script>


<script>
$(document).ready(function() {

    function syncTimePicker(row) {
        const hourInput = row.querySelector('.form-hour');
        const minsInput = row.querySelector('.form-mins');
        const timePicker = row.querySelector('.form-time');

        if (!hourInput || !minsInput || !timePicker) {
            console.error('Missing inputs in row');
            return;
        }

        timePicker.addEventListener('change', function () {
            const [hour, mins] = this.value.split(':');
            hourInput.value = hour;
            minsInput.value = mins;
        });

        hourInput.addEventListener('change', function () {
            timePicker.value = `${hourInput.value.padStart(2, '0')}:${minsInput.value.padStart(2, '0')}`;
        });

        minsInput.addEventListener('change', function () {
            timePicker.value = `${hourInput.value.padStart(2, '0')}:${minsInput.value.padStart(2, '0')}`;
        });
    }

    document.querySelectorAll('.rowInnerHeadCheck, .InnerHeadCheck').forEach(row => {
        syncTimePicker(row);
    });

    $('.add-btn').on('click', function () {
        const currentTime = new Date().toLocaleTimeString('en-AU', { timeZone: 'Australia/Sydney', hour12: false, hour: '2-digit', minute: '2-digit' });
        const [hour, mins] = currentTime.split(':');

        const newRow = `
       
        <div class="col-12 card px-lg-4 py-lg-3">
            <div class="row rowInnerHeadCheck form-row w-100 ">
                <div class="form-group col-md-3 col-sm-12">
                    <label>Time</label><br>
                    <input type="number" min="0" max="24" value="${hour}" name="hour[]" class="form-hour form-number w-40 custom-input my-1"> H :
                    <input type="number" min="00" max="59" value="${mins}" name="mins[]" class="form-mins form-number w-40 custom-input my-1"> M
                    &nbsp;<i class="fa-solid fa-clock"></i>&nbsp;<input type="time" name="timePicker[]" class="form-time custom-input my-1" value="${currentTime}">
                </div>
                <div class="form-group col-md-3 col-sm-12">
                    <label>Head Count</label>
                    <input type="number" class="form-control custom-input my-1" name="headCount[]">
                </div>
                
                <div class="form-group commentGroup col-md-3 col-sm-12">
                    <label>Signature</label>
                    <input type="text" class="form-control commentField custom-input my-1" name="comments[]">
                </div>
                <div class="btn-group" style="display:contents;">
                    <div class="form-group lastGroup col-md-1 col-sm-12" style="margin-top: 28px;">
                        <a href="#!" class="btn btn-outline-danger minus-btn btn-block custom-input my-1" style="width: fit-content;">Remove</a>
                    </div>
                </div>
            </div>
            </div>
             <hr>
        `;

        $('#form-fields').append(newRow);
        const addedRow = $('#form-fields .rowInnerHeadCheck').last()[0];
        syncTimePicker(addedRow);
    });

 $(document).on('click', '.minus-btn', function () {
    let button = $(this);
    let row = button.closest('.headcheck-card');
    let headCheckId = row.find('#headcheckid').val();

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this entry?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: headCheckId ? 'btn btn-sm btn-danger mx-2' : 'btn btn-sm btn-warning',
            cancelButton: 'btn btn-sm btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            if (headCheckId) {
                $.ajax({
                    url: "{{ route('headcheck.delete') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { headCheckId: headCheckId },
                    success: function(response) {
                        if (response.Status === 'SUCCESS') {
                            // Redirect or remove row
                            window.location.href = "{{ route('headChecks') }}";
                        } else {
                            Swal.fire('Error', response.Message || 'Failed to delete', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            } else {
                // Remove row directly if there's no ID
                button.closest('.rowInnerHeadCheck, .InnerHeadCheck, .headcheck-card').remove();
            }
        }
    });
});


    // Fetch rooms based on center ID
    $(document).on('change', '#centerId', function () {
        const centerId = $(this).val();

        $.ajax({
            url: "{{ route('headchecks.getCenterRooms') }}", // Update this route name
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'x-device-id': '', // Fill if needed
                'x-token': '609ca994bf421'
            },
            data: {
                userid: 3,
                centerId: centerId
            },
            success: function (res) {
                $("#roomid").html('<option>-- Select Room --</option>');
                if (res.Rooms) {
                    res.Rooms.forEach(function (room) {
                        $("#roomid").append('<option value="' + room.id + '">' + room.name + '</option>');
                    });
                }
            },
            error: function (err) {
                console.error("Failed to fetch rooms", err);
            }
        });
    });

    // On room change, submit the form
    $(document).on('change', '#roomid', function () {
        $('#headCheckForm').submit();
    });

    // On calendar change, redirect
    $(document).on('change', '#txtCalendar', function () {
        let date = $(this).val();
        // alert(date);
        let url = "{{ url('headChecks') }}?centerid={{ $centerid }}&roomid={{ $roomid }}&date=" + date;
        window.location.href = url;
    });

    // Save form
    $(document).on('click', '#save_headcheck', function () {
        $('#headCheckForm').submit();
    });
});

// $(document).on('click', '.minus-btn', function () {
//     alert();
//     // This finds the current row that contains the clicked button
//     let row = $(this).closest('.rowInnerHeadCheck, .InnerHeadCheck');

//     // This finds the input with class .headcheckid inside only that row
//     let headCheckId = row.find('.headcheckid').val();

//   if (headCheckId) {
//     $.ajax({
//         url: "{{ route('headcheck.delete') }}",
//         type: "POST",
//         dataType: "json",
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         data: {
//             headCheckId: headCheckId
//         },
//         success: function(response) {
//             if (response.Status === 'SUCCESS') {
//                 // alert('Deleted successfully');
//                 window.href.reload();
//                 // Optionally remove the row from DOM
//                 // $(this).closest('.rowInnerHeadCheck').remove();
//             } else {
//                 alert(response.Message || 'Failed to delete');
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('AJAX error:', error);
//         }
//     });
// }


//     console.log('Selected ID:', headCheckId);
// });

</script>


@endpush
@include('layout.footer')
@stop