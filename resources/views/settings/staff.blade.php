@extends('layout.master')
@section('title', 'Staff Settings')
@section('parentPageTitle', 'Settings')


<style>
    /* Make buttons look consistent & modern */
    .status-btn {
        width: 100% !important;
        padding: 10px 0 !important;
        font-size: 16px !important;
        border-radius: 8px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .status-btn:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Popup styling */
    .swal2-popup-custom {
        border-radius: 15px !important;
        padding: 20px !important;
    }


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

    .inline-options {
        display: flex;
        flex-wrap: wrap;
        /* allows wrapping to next line */
        gap: 8px;
        /* space between options */
        padding: 10px;
    }

    .inline-options a {
        display: inline-block;
        padding: 6px 12px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        cursor: pointer;
        transition: background 0.3s;
    }

    .inline-options a:hover {
        background: #007bff;
        color: #fff;
    }

    /* Dropdown container */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Button */
    .dropbtn {
        background-color: #dc3545;
        /* red button */
        color: white;
        padding: 6px 10px;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Dropdown content */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 120px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 5px;
    }

    /* Dropdown links */
    .dropdown-content a {
        color: #333;
        padding: 8px 12px;
        text-decoration: none;
        display: block;
    }

    /* .dropdown-content a:hover {
        background-color: #f1f1f1;
    } */

    /* Show dropdown on hover OR toggle */
    .dropdown.show .dropdown-content {
        display: block;
    }

    .theme-edit-btn {
        background: var(--sd-accent, #17a2b8) !important;
        color: var(--sd-bg, #fff) !important;
        border: 2px solid var(--sd-accent, #17a2b8) !important;
        transition: background 0.3s, color 0.3s, border 0.3s;
    }
    .theme-edit-btn:hover, .theme-edit-btn:focus {
        background: var(--sd-bg, #fff) !important;
        color: var(--sd-accent, #17a2b8) !important;
        border: 2px solid var(--sd-accent, #17a2b8) !important;
    }

    .theme-input {
        background: var(--sd-bg, #fff) !important;
        color: var(--sd-accent, #17a2b8) !important;
        border: 2px solid var(--sd-accent, #17a2b8) !important;
        transition: background 0.3s, color 0.3s, border 0.3s;
    }
    .theme-input:focus {
        background: var(--sd-bg, #fff) !important;
        color: var(--sd-accent, #17a2b8) !important;
        border: 2px solid var(--sd-accent, #17a2b8) !important;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
    }

    .theme-outline-btn {
        background: var(--sd-bg, #fff) !important;
        color: var(--sd-accent, #17a2b8) !important;
        border: 2px solid var(--sd-accent, #17a2b8) !important;
        transition: background 0.3s, color 0.3s, border 0.3s;
    }
    .theme-outline-btn:hover, .theme-outline-btn:focus {
        background: var(--sd-accent, #17a2b8) !important;
        color: var(--sd-bg, #fff) !important;
        border: 2px solid var(--sd-accent, #17a2b8) !important;
    }
</style>





@section('content')
    <div class="text-zero top-right-button-container d-flex justify-content-end g-2"
        style="margin-right: 20px;margin-top: -48px;">
        <div class="dropdown">
            <button class="btn btn-lg dropdown-toggle theme-outline-btn" type="button" id="centerDropdown"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="background: var(--sd-bg, #fff); color: var(--sd-accent, #17a2b8); border: 2px solid var(--sd-accent, #17a2b8);">
                {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
                style="top:3% !important;left:13px !important; background: var(--sd-bg, #fff); border: 1px solid var(--sd-accent, #17a2b8);">
                @foreach ($centers as $center)
                    <a href="javascript:void(0);"
                        class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold' : '' }}"
                        style="background: var(--sd-bg, #fff); color: var(--sd-accent, #17a2b8);"
                        data-id="{{ $center->id }}">
                        {{ $center->centerName }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="header float-end ml-1  text-zero top-right-button-container d-flex justify-content-end">
            <!-- <h2>Staff Settings<small></small> </h2> -->
            <button class="btn theme-outline-btn" style="float:right;margin-bottom:20px;" data-toggle="modal"
                data-target="#addSuperadminModal">
                <i class="fa fa-plus"></i>&nbsp; Add Staff
            </button>
        </div>



    </div>
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:20px">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:20px">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="col-4 d-flex justify-content-end align-items-center top-right-button-container">
        <i class="fas fa-filter mx-2" style="color: var(--sd-accent, #17a2b8);"></i>
        <input type="text" id="staffNameFilter" name="filterbyCentername" class="form-control theme-input"
            placeholder="Filter by name"
            style="background: var(--sd-bg, #fff); color: var(--sd-accent, #17a2b8); border: 2px solid var(--sd-accent, #17a2b8);">
    </div>
    <div class="row clearfix" style="margin-top:30px">

        <div class="col-lg-12">
            <div class="">

                <div class="body">
                    <div class="row staff-data">
                        @foreach ($staff as $index => $staffs)
                            @php
                                $maleAvatars = [
                                    'avatar1.jpg',
                                    'avatar5.jpg',
                                    'avatar8.jpg',
                                    'avatar9.jpg',
                                    'avatar10.jpg',
                                ];
                                $femaleAvatars = [
                                    'avatar2.jpg',
                                    'avatar3.jpg',
                                    'avatar4.jpg',
                                    'avatar6.jpg',
                                    'avatar7.jpg',
                                ];
                                $avatars = $staffs->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
                                $defaultAvatar = $avatars[array_rand($avatars)];
                                $avatar = $staffs->imageUrl
                                    ? asset($staffs->imageUrl)
                                    : asset('assets/img/xs/' . $defaultAvatar);

                                $userType = Auth::user()->userType;

                            @endphp


                            <div class="col-md-3 mb-4 staff-card" data-staff-name="{{ strtolower($staffs->name) }}"
                                data-id="{{ $staffs->id }}" data-href="{{ route('settings.staff.details', $staffs->id) }}">
                                <div class="card h-100 shadow-sm border-primary" style="cursor: pointer;">
                                    <div class="card-body text-center">
                                        <div class="d-flex justify-content-center align-items-center mb-0">
                                            {{-- Avatar --}}
                                            <img src="{{ $avatar }}" alt="Avatar" class="rounded-circle"
                                                width="80" height="80">

                                            @if ($userType == 'Superadmin')
                                                <form action="{{ route('settings.userWifi.changeStatus', $staffs->id) }}"
                                                    method="POST" style="position:absolute; top:5px; right:5px;"
                                                    onsubmit="return confirmRemoveAccess(event)">
                                                    @csrf
                                                    @if ($staffs->wifi_status == 1)
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Click to User Remove IP Access">
                                                            <i class="fas fa-location"></i> Access
                                                        </button>
                                                    @else
                                                        <div class="dropdown">
                                                            <button type="button" class="dropbtn dropdown-toggle"
                                                                title="Click to User Give IP Access">
                                                                <i class="fas fa-location"></i> No Access
                                                            </button>
                                                            <div class="dropdown-content inline-options">

                                                                <a href="#" data-hour="1" class="mt-0">1 Hour</a>
                                                                <a href="#" data-hour="4" class="mt-1">4 Hours</a>
                                                                <a href="#" data-hour="8" class="mt-1">8 Hours</a>
                                                                <a href="#" data-hour="168" class="mt-1">1 Week</a>
                                                                <a href="#" data-hour="720" class="mt-1">1 Month</a>
                                                                <a href="#" data-hour="8760" class="mt-1">1 Year</a>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="hours" class="selected-hour">
                                                    @endif
                                                </form>
                                            @endif
                                        </div>

                                        {{-- Name, Email, Contact --}}
                                        <h5 class="card-title mb-1">{{ $staffs->name }}</h5>
                                        <p class="card-text mb-1"><strong>Email:</strong> {{ $staffs->email }}</p>
                                        <p class="card-text mb-2"><strong>Contact:</strong> {{ $staffs->contactNo }}</p>

                                        {{-- Other Action Buttons --}}
                                        <div class="d-flex justify-content-center gap-3">
                                            <button class="btn btn-sm theme-edit-btn"
                                                onclick="openEditSuperadminModal({{ $staffs->id }})">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger ml-2"
                                                onclick="deleteSuperadmin({{ $staffs->id }})">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm border shadow-sm bg-white px-3 ml-2"
                                                onclick="UpdateStatusSuperadmin({{ $staffs->id }})">
                                                @if ($staffs->status === 'ACTIVE')
                                                    <i class="fa-solid fa-circle-check text-success me-1"></i>
                                                    <span class="text-success fw-bold">Active</span>
                                                @elseif($staffs->status === 'IN-ACTIVE')
                                                    <i class="fa-solid fa-circle-xmark text-danger me-1"></i>
                                                    <span class="text-danger fw-bold">Inactive</span>
                                                @else
                                                    <i class="fa-solid fa-clock text-warning me-1"></i>
                                                    <span class="text-warning fw-bold">Pending</span>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                    @if ($staffs->wifi_access_until != null)
                                        <span style="margin-left:32px"><span style="color:#dc3545"><b>Access
                                                    Expires:</b></span>
                                            <b>{{ \Carbon\Carbon::parse($staffs->wifi_access_until)->format('d M Y, h:i A') }}</b>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>

        </div>


        <div id="toast-container" class="toast-bottom-right"
            style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>



        <!-- Modal Form -->
        <div class="modal" id="addSuperadminModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Add New Staff</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                        <form id="superadminForm" enctype="multipart/form-data">
                            @csrf
                            <!-- Laravel CSRF -->

                            <h6 class="mb-3">Staff Details</h6>
                            <div class="form-row">
                                <!-- <div class="form-group col-md-6">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div> -->
                                <div class="form-group col-md-6">
                                    <label>Staff Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contact No</label>
                                    <input type="tel" class="form-control" name="contactNo" required>
                                </div>

                                <!-- <div class="form-group col-md-6">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control" name="dob" required>
                            </div> -->
                                <div class="form-group col-md-6">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="MALE">Male</option>
                                        <option value="FEMALE">Female</option>
                                        <option value="OTHERS">Other</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group col-md-6">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div> -->
                                <div class="form-group col-12">
                                    <label>Profile Image</label>
                                    <input type="file" class="form-control" name="imageUrl" accept="image/*">
                                </div>
                            </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn theme-outline-btn" style="background: var(--sd-bg, #fff); color: var(--sd-accent, #17a2b8); border: 2px solid var(--sd-accent, #17a2b8);" onclick="submitSuperadminForm()">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal" id="editSuperadminModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="editSuperadminForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Superadmin</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row">
                            <input type="hidden" name="id" id="editId">

                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" id="editName" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Email ID</label>
                                <input type="email" class="form-control" name="email" id="editEmail" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Password <span style="color:green;">(Optional- Leave blank if not
                                        changing)</span></label>
                                <input type="password" class="form-control" name="password" id="editPassword">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Contact No</label>
                                <input type="tel" class="form-control" name="contactNo" id="editContactNo" required>
                            </div>

                            <div class="form-group col-6">
                                <label>Change Image <span style="color:green;">(Optional)</span></label>
                                <input type="file" class="form-control" id="imageUrl" name="imageUrl"
                                    accept="image/*">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Gender</label>
                                <select class="form-control" name="gender" id="editGender" required>
                                    <option value="">Select Gender</option>
                                    <option value="MALE">Male</option>
                                    <option value="FEMALE">Female</option>
                                    <option value="OTHERS">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" onclick="updateSuperadmin()" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- spinner  -->
        <div id="loader"
            style="display:none;
     position: fixed;
     top: 50%; left: 50%;
     transform: translate(-50%, -50%);
     z-index: 9999;">
            <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Filter staff list on input change
                $('#staffNameFilter').on('input', function() {
                    var filterValue = $(this).val().toLowerCase().trim();

                    $('.staff-card').each(function() {
                        var staffName = $(this).data('staff-name');
                        if (staffName.includes(filterValue)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                $(document).on('click', '.staff-card', function(e) {
                    if ($(e.target).closest('a, button, form, input, select, .dropbtn, .dropdown-content, .inline-options').length) {
                        return;
                    }

                    var href = $(this).data('href');
                    if (!href || href === '/settings/staff/' + $(this).data('id')) {
                        var id = $(this).data('id');
                        if (id) {
                            href = '/settings/staff/' + id + '/details';
                        }
                    }

                    if (href) {
                        window.location.href = href;
                    }
                });
            });

            // Placeholder for other functions (ensure they are defined if used)
            function confirmRemoveAccess(event) {
                return confirm("Are you sure you want to remove WiFi access?");
            }
        </script>
        <script>
            function UpdateStatusSuperadmin(id) {
                Swal.fire({
                    title: '<h3 style="color:#17a2b8;">🔄 Update Status</h3>',
                    html: `
            <p class="mb-3"></p>
            <div class="d-flex flex-row gap-2">
                <button id="btn-active" class="swal2-confirm swal2-styled status-btn" style="background-color:white;color:#198754
">
                    <i class="fa-solid fa-circle-check text-success me-1"></i> Active
                </button>
                <button id="btn-inactive" class="swal2-confirm swal2-styled status-btn" style="background-color:white;color:#dc3545
">
                    <i class="fa-solid fa-ban text-danger me-1"></i> Inactive
                </button>
                <button id="btn-pending" class="swal2-confirm swal2-styled status-btn" style="background-color:white; color:#ffc107
;">
                     <i class="fa-solid fa-clock text-warning me-1"></i> Pending
                </button>
                <button id="btn-cancel" class="swal2-cancel swal2-styled status-btn" style="background-color:white;color:#6c757d
;" >
                      <i class="fa-solid fa-circle-xmark text-secondary me-1"></i> Cancel
                </button>
            </div>
        `,
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        // Click events for buttons
                        document.getElementById('btn-active').addEventListener('click', function() {
                            sendStatusUpdate(id, 'ACTIVE');
                        });
                        document.getElementById('btn-inactive').addEventListener('click', function() {
                            sendStatusUpdate(id, 'IN-ACTIVE');
                        });
                        document.getElementById('btn-pending').addEventListener('click', function() {
                            sendStatusUpdate(id, 'PENDING');
                        });
                        document.getElementById('btn-cancel').addEventListener('click', function() {
                            Swal.close();
                        });
                    },
                    customClass: {
                        popup: 'swal2-popup-custom'
                    }
                });
            }


            function sendStatusUpdate(id, status) {
                $.ajax({
                    url: "{{ route('settings.updateStatusSuperadmin') }}",
                    type: "POST",
                    data: {
                        id: id,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Updating...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Status updated to ' + status,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: res.message || 'Failed to update status.'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }


            function showLoaderFor2Sec() {
                $("#loader").show(); // show loader

                setTimeout(function() {
                    $("#loader").hide(); // hide loader after 2 sec
                }, 2000);
            }



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

            function submitSuperadminForm() {
                const form = document.getElementById('superadminForm');
                const formData = new FormData(form);
                formData.append('userType', 'Staff');

                const submitBtn = document.querySelector('[onclick="submitSuperadminForm()"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Saving...';

                // Clear any previous validation states and toasts
                $('#superadminForm .form-control, #superadminForm .form-select').removeClass('is-invalid');
                $('#toast-container').html('');

                let valid = true;
                let firstInvalid = null;

                // Manual validation for required fields
                $('#superadminForm [required]').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('is-invalid');
                        const label = $(this).closest('.form-group').find('label').text().trim();
                        showToast('error', `Please fill the ${label}`);

                        if (!firstInvalid) firstInvalid = this;
                        valid = false;
                    }
                });

                if (!valid) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Save';
                    if (firstInvalid) firstInvalid.focus();
                    return;
                }

                // Proceed with AJAX if all required fields are filled
                $.ajax({
                    url: "{{ route('settings.staff.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast('success', 'Staff added successfully!');
                            setTimeout(() => {
                                $('#addSuperadminModal').modal('hide');
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('error', response.message || 'Something went wrong');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(key => {
                                showToast('error', errors[key][0]);
                            });
                        } else {
                            showToast('error', 'Server error. Please try again.');
                        }
                    },
                    complete: function() {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Save';
                    }
                });
            }

            function openEditSuperadminModal(id) {
                $.ajax({
                    url: `/settings/staff/${id}/edit`, // This route must return JSON
                    type: 'GET',
                    success: function(data) {
                        $('#editId').val(data.id);
                        $('#editName').val(data.name);
                        $('#editEmail').val(data.email);
                        $('#editContactNo').val(data.contactNo);
                        $('#editGender').val(data.gender);
                        $('#editPassword').val(''); // Clear password field

                        $('#editSuperadminModal').modal('show');
                    },
                    error: function() {
                        showToast('error', 'Failed to fetch user data.');
                    }
                });
            }

            function updateSuperadmin() {
                const form = document.getElementById('editSuperadminForm');
                const formData = new FormData(form);
                const id = $('#editId').val();

                $.ajax({
                    url: `/settings/staff/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast('success', 'Superadmin updated successfully!');
                            setTimeout(() => {
                                $('#editSuperadminModal').modal('hide');
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('error', response.message || 'Update failed');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            Object.values(xhr.responseJSON.errors).forEach(error => {
                                showToast('error', error[0]);
                            });
                        } else {
                            showToast('error', 'Server error occurred');
                        }
                    }
                });
            }


            function deleteSuperadmin(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this Staff!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/settings/superadmin/${id}`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showToast('success', 'Staff deleted successfully!');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1500);
                                } else {
                                    showToast('error', response.message || 'Delete failed');
                                }
                            },
                            error: function() {
                                showToast('error', 'Server error occurred');
                            }
                        });
                    }
                });
            }
        </script>





        <script>
            // Toggle dropdown open/close
            document.querySelectorAll(".dropbtn").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("show");
                });
            });

            // Auto-submit on selecting hour
            document.querySelectorAll(".dropdown-content a").forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault();
                    let hour = this.getAttribute("data-hour");
                    let form = this.closest("form");
                    form.querySelector(".selected-hour").value = hour;
                    form.submit();
                });
            });

            // Close dropdown when clicking outside
            window.addEventListener("click", function(e) {
                document.querySelectorAll(".dropdown").forEach(drop => {
                    if (!drop.contains(e.target)) {
                        drop.classList.remove("show");
                    }
                });
            });
        </script>
        <script>
            function confirmRemoveAccess(event) {
                // Only confirm when button has "Access" (meaning removing access)
                const btn = event.target.querySelector("button[type='submit']");
                if (btn && btn.textContent.includes("Access")) {
                    return confirm("Are you sure you want to remove this user's Login access?");
                }
                return true;
            }
        </script>
        @include('layout.footer')
    @stop
