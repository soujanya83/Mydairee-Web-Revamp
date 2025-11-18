@extends('layout.master')
@section('title', 'Parents Settings')
@section('parentPageTitle', 'Settings')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

    /* Rounded checkbox styling */
    .parent-checkbox {
        width: 22px;
        height: 22px;
        // border-radius: 50%;
        cursor: pointer;
        border: 2px solid #17a2b8;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        //background-color: white;
        position: relative;
        transition: all 0.3s ease;
    }

    .parent-checkbox:checked {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .parent-checkbox:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
    }

    .parent-checkbox:hover {
        box-shadow: 0 0 5px rgba(23, 162, 184, 0.5);
    }

    /* Gmail-Style Email Modal */
    #emailModal .modal-dialog {
        max-width: 700px;
    }

    #emailModal .modal-content {
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: none;
    }

    .gmail-header {
        background: #f5f5f5;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 16px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .gmail-header h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 500;
        color: #202124;
    }

    .gmail-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #5f6368;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .gmail-close:hover {
        background: #e8eaed;
    }

    .gmail-body {
        padding: 0;
        background: white;
    }

    .gmail-field {
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .gmail-field:last-child {
        border-bottom: none;
    }

    .gmail-label {
        color: #5f6368;
        font-size: 14px;
        font-weight: 500;
        min-width: 50px;
        padding-top: 8px;
    }

    .gmail-input-wrapper {
        flex: 1;
    }

    .gmail-input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 14px;
        color: #202124;
        padding: 8px 0;
        background: transparent;
    }

    .gmail-input:focus {
        outline: none;
    }

    .gmail-textarea {
        border: none;
        outline: none;
        width: 100%;
        font-size: 14px;
        color: #202124;
        padding: 16px;
        min-height: 300px;
        resize: vertical;
        font-family: Arial, sans-serif;
        line-height: 1.5;
    }

    .gmail-recipients {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 4px 0;
        max-height: 120px;
        overflow-y: auto;
    }

    /* Recipient Badge with X button */
    .recipient-badge {
        display: inline-flex;
        align-items: center;
        background: #e8f0fe;
        color: #1967d2;
        padding: 4px 8px;
        border-radius: 16px;
        font-size: 13px;
        gap: 6px;
        border: 1px solid #d2e3fc;
    }

    .recipient-badge .remove-recipient {
        cursor: pointer;
        background: none;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #5f6368;
        transition: all 0.2s ease;
        font-weight: normal;
        line-height: 1;
    }

    .recipient-badge .remove-recipient:hover {
        background: rgba(0, 0, 0, 0.08);
        color: #202124;
    }

    .gmail-footer {
        padding: 16px;
        background: white;
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .gmail-send-btn {
        background: #1a73e8;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .gmail-send-btn:hover {
        background: #1765cc;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .gmail-attach-btn {
        background: none;
        border: none;
        color: #5f6368;
        padding: 8px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .gmail-attach-btn:hover {
        background: #f1f3f4;
    }

    .gmail-attach-label {
        font-size: 12px;
        color: #5f6368;
        margin-top: 8px;
    }

    .gmail-attached-files {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .gmail-file-chip {
        background: #f1f3f4;
        border-radius: 16px;
        padding: 6px 12px;
        font-size: 12px;
        color: #202124;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Email Sending Loader */
    #emailLoader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(32, 33, 36, 0.8);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
    }

    #emailLoader.show {
        display: flex;
    }

    .loader-content {
        background: white;
        padding: 40px 60px;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        text-align: center;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .loader-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid #e8f0fe;
        border-top-color: #1a73e8;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loader-content h5 {
        color: #202124;
        font-size: 18px;
        font-weight: 500;
        margin: 0 0 8px 0;
    }

    .loader-content p {
        color: #5f6368;
        font-size: 14px;
        margin: 0;
    }

    .loader-progress {
        width: 200px;
        height: 4px;
        background: #e8f0fe;
        border-radius: 2px;
        margin: 16px auto 0;
        overflow: hidden;
    }

    .loader-progress-bar {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #1a73e8 0%, #1557b0 100%);
        animation: progress 2s ease-in-out infinite;
    }

    @keyframes progress {
        0% {
            width: 0%;
        }

        50% {
            width: 70%;
        }

        100% {
            width: 100%;
        }
    }
</style>

@section('content')

    @php
        // Expose server-side upload_max_filesize (e.g. 2M, 8M) as bytes for client validation
        function md_bytes_from_ini($val) {
            $val = trim($val);
            $last = strtolower(substr($val, -1));
            $num = (int)$val;
            return match($last) {
                'g' => $num * 1024 * 1024 * 1024,
                'm' => $num * 1024 * 1024,
                'k' => $num * 1024,
                default => (int)$val,
            };
        }
        $serverUploadLimitBytes = md_bytes_from_ini(ini_get('upload_max_filesize'));
    @endphp
    <script>
        window.SERVER_UPLOAD_MAX = {{ $serverUploadLimitBytes }}; // numeric bytes
    </script>

    <!-- Single Row: Filter (left) + Center Dropdown + Add Parent (right) -->
    <div class="row align-items-center">
        <!-- Left: Filter -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-filter mx-2" style="color:#17a2b8; "></i>
            <input type="text" name="filterbyCentername" class="form-control border-info"
                placeholder="Filter by Parent or Child"
                style="width: 340px;  height: 35px; border-radius: 50px; padding-left: 18px;"
                onkeyup="filterbyParentsName(this.value)">

        </div>

        <!-- Right: Center Dropdown + Add Parent Button -->
        <div class="col-md-6 d-flex justify-content-end align-items-center" style="gap: 10px;">
            <div class="dropdown">
                <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="centerDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown">
                    @foreach ($centers as $center)
                        <a href="javascript:void(0);"
                            class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                            style="background-color:white;" data-id="{{ $center->id }}">
                            {{ $center->centerName }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if (!empty($permissions['addParent']) && $permissions['addParent'])
                <button class="btn btn-outline-info btn-lg" data-toggle="modal" data-target="#addParentModal">
                    <i class="fa fa-plus"></i>&nbsp; Add Parent
                </button>
            @endif
        </div>
    </div>
    <hr>
    <!-- Bulk Action Toolbar -->
    <div id="bulkActionToolbar" class="col-12 mt-3" style="display: none;">
        <div class="alert alert-info d-flex align-items-center justify-content-between mb-0">

            <!-- LEFT SIDE: Selected Count -->
            <div class="d-flex align-items-center">
                <strong><span id="selectedCount">0</span> parent(s) selected</strong>
            </div>

            <!-- RIGHT SIDE: All action buttons in order -->
            <div class="d-flex align-items-center" style="gap: 10px;">

                <!-- Track Mails -->
                <button class="btn btn-info btn-sm" onclick="openTrackMailsModal()">
                    <i class="fa-solid fa-envelope-open-text"></i> Track Mails
                </button>

                <!-- Select All -->
                <div class="d-flex align-items-center" style="gap:5px;" title="Select / Deselect All Parents">
                    <input type="checkbox" id="selectAllParents" class="parent-checkbox">
                    <label for="selectAllParents" class="m-0" style="cursor:pointer;">Select All</label>
                </div>

                <!-- Send Email -->
                <button class="btn btn-primary btn-sm" onclick="openBulkEmailModal()">
                    <i class="fa-solid fa-envelope"></i> Send Email
                </button>

                <!-- Clear Selection -->
                <button class="btn btn-secondary btn-sm" onclick="clearSelection()">
                    <i class="fa-solid fa-times"></i> Clear Selection
                </button>
            </div>

        </div>
    </div>


    <div class="row clearfix" style="margin-top:30px">
        <div class="col-lg-12">
            <div class="">

                <div class="body">
                    <div class="row parent-data">
                        @foreach ($parents as $index => $parent)
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
                                $avatars = $parent->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
                                $defaultAvatar = $avatars[array_rand($avatars)];
                                $avatar = $parent->imageUrl
                                    ? asset($parent->imageUrl)
                                    : asset('assets/img/xs/' . $defaultAvatar);
                            @endphp

                            <div class="col-md-3 mb-4">
                                <div class="card shadow-sm h-100 border-info parent-card"
                                    data-parent-id="{{ $parent->id }}" data-parent-email="{{ $parent->email }}"
                                    data-parent-name="{{ $parent->name }}">
                                    <div class="card-body text-center position-relative">
                                        <!-- Checkbox - Top Left -->
                                        <div class="position-absolute" style="top: 10px; left: 10px; z-index: 10;">
                                            <input type="checkbox" class="parent-checkbox" value="{{ $parent->id }}"
                                                data-email="{{ $parent->email }}" data-name="{{ $parent->name }}"
                                                title="Select {{ $parent->name }}">
                                        </div>

                                        <!-- Ellipsis Dropdown - Top Right -->
                                        <div class="position-absolute dropdown"
                                            style="top: 10px; right: 10px; z-index: 10;">
                                            <a href="#" class="text-info" id="parentDropdown{{ $parent->id }}"
                                                role="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" style="font-size: 22px; cursor: pointer;">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="parentDropdown{{ $parent->id }}"
                                                style="min-width: 120px;">
                                                <a class="dropdown-item" href="#"
                                                    onclick="event.preventDefault(); openEmailModal({{ $parent->id }})">
                                                    <i class="fa fa-envelope"></i> Send Mail
                                                </a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="event.preventDefault(); trackMail({{ $parent->id }})">
                                                    <i class="fa fa-envelope-open-text"></i> Track Mail
                                                </a>
                                            </div>
                                        </div>

                                        <img src="{{ $avatar }}" class="rounded-circle mb-3" width="80"
                                            height="80" alt="Parent Avatar">
                                        <h5 class="card-title mb-1">{{ $parent->name }}</h5>
                                        <p class="card-text mb-1"><strong>Email:</strong> {{ $parent->email }}</p>
                                        <p class="card-text mb-1"><strong>Contact:</strong> {{ $parent->contactNo }}</p>
                                        <p class="card-text mb-1"><strong>Children:</strong></p>
                                        <ul class="list-unstyled">
                                            @foreach ($parent->children as $child)
                                                <li>{{ $child->name }} {{ $child->lastname }}
                                                    ({{ $child->pivot->relation }})
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="d-flex justify-content-center mt-3">
                                            @if (!empty($permissions['updateParent']) && $permissions['updateParent'])
                                                <button class="btn btn-sm btn-info mr-2"
                                                    onclick="openEditParentModal({{ $parent->id }})">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteSuperadmin({{ $parent->id }})">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
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
        <div class="modal" id="addParentModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Add New Parent</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                        <form id="superadminForm" enctype="multipart/form-data">
                            @csrf
                            <!-- Laravel CSRF -->

                            <h6 class="mb-3">Parent Details</h6>
                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label>Parent Name</label>
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

                                <div class="form-group col-md-6">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="MALE">Male</option>
                                        <option value="FEMALE">Female</option>
                                        <option value="OTHERS">Other</option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>Profile Image</label>
                                    <input type="file" class="form-control" name="imageUrl" accept="image/*">
                                </div>


                            </div>

                            <!-- Link Children -->
                            <h6 class="mt-4">Link Children</h6>
                            <div id="childRelationContainer">
                                <div class="child-relation-group border p-3 rounded mb-2">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Child</label>
                                            <select name="children[0][childid]" class="form-control child-select"
                                                required>
                                                <option value="">Select Child</option>
                                                @foreach ($children as $child)
                                                    <option value="{{ $child->id }}">{{ $child->name }}
                                                        {{ $child->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label>Relation</label>
                                            <select name="children[0][relation]" class="form-control" required>
                                                <option value="">Select Relation</option>
                                                <option value="Mother">Mother</option>
                                                <option value="Father">Father</option>
                                                <option value="Brother">Brother</option>
                                                <option value="Sister">Sister</option>
                                                <option value="Relative">Relative</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addChildRelation()">Add
                                Another Child</button>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-primary" onclick="submitparentform()">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal" id="editParentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="editParentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="editParentId">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Parent</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body" style="max-height:500px; overflow-y:auto;">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Parent Name</label>
                                    <input type="text" class="form-control" name="name" id="editName" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="email" id="editEmail" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password <span style="color:green;">(Optional)</span></label>
                                    <input type="password" class="form-control" name="password" id="editPassword">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contact No</label>
                                    <input type="tel" class="form-control" name="contactNo" id="editContactNo"
                                        required>
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
                                <div class="form-group col-12">
                                    <label>Change Image <span class="text-success">(Optional)</span></label>
                                    <input type="file" class="form-control" name="imageUrl" accept="image/*">
                                </div>
                            </div>

                            <h6 class="mt-4">Linked Children</h6>
                            <div id="editChildRelationContainer"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addEditChildRelation()">Add Another Child</button>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" onclick="submitEditParent()" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Gmail-Style Email Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="emailForm">
                    @csrf
                    <div class="modal-content">
                        <!-- Gmail Header -->
                        <div class="gmail-header">
                            <h6 id="modalTitle">New Message</h6>
                            <button type="button" class="gmail-close" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        <!-- Gmail Body -->
                        <div class="gmail-body">
                            <!-- To Field -->
                            <div class="gmail-field">
                                <label class="gmail-label">To</label>
                                <div class="gmail-input-wrapper">
                                    <div id="recipientDisplay" class="gmail-recipients">
                                        <!-- Recipients will be populated here -->
                                    </div>
                                    <input type="hidden" name="parent_ids" id="parentIds">
                                    <input type="hidden" name="parent_emails" id="parentEmails">
                                </div>
                            </div>

                            <!-- Subject Field -->
                            <div class="gmail-field">
                                <label class="gmail-label">Subject</label>
                                <div class="gmail-input-wrapper">
                                    <input type="text" class="gmail-input" name="subject" id="emailSubject"
                                        placeholder="Subject" required>
                                </div>
                            </div>

                            <!-- Message Field -->
                            <div class="gmail-field"
                                style="border-bottom: none; flex-direction: column; align-items: stretch;">
                                <textarea class="gmail-textarea" name="message" id="emailMessage" placeholder="Type your message here..." required></textarea>
                            </div>
                        </div>

                        <!-- Gmail Footer -->
                        <div class="gmail-footer">
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <button type="button" onclick="sendEmail()" class="gmail-send-btn">
                                    <i class="fa fa-paper-plane"></i> Send
                                </button>

                                <label for="emailAttachments" class="gmail-attach-btn mb-0" title="Attach files">
                                    <i class="fa fa-paperclip"></i>
                                    <input type="file" class="d-none" name="attachments[]" id="emailAttachments"
                                        multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        onchange="displayAttachments(this)">
                                </label>
                            </div>

                            <div id="attachmentsList" class="gmail-attached-files"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


        <script>
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

            function submitparentform() {
                const form = document.getElementById('superadminForm');
                const formData = new FormData(form);
                formData.append('userType', 'Parent');

                const submitBtn = document.querySelector('[onclick="submitparentform()"]');
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
                    url: "{{ route('settings.parent.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast('success', 'Parent added successfully!');
                            setTimeout(() => {
                                $('#addParentModal').modal('hide');
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


            function openEditParentModal(parentId) {
                $('#editParentModal').modal('show');
                $('#editParentForm')[0].reset();
                $('#editChildRelationContainer').empty();
                childIndex = 0;

                $.ajax({
                    url: `/settings/parent/${parentId}/get`,
                    type: 'GET',
                    success: function(response) {
                        const parent = response.parent;
                        const children = response.children;

                        $('#editParentId').val(parent.id);
                        $('#editName').val(parent.name);
                        $('#editEmail').val(parent.emailid);
                        $('#editContactNo').val(parent.contactNo);
                        $('#editGender').val(parent.gender);

                        children.forEach(childRel => {
                            addEditChildRelation(childRel);
                        });
                    },
                    error: function() {
                        showToast('error', 'Failed to load parent data');
                        $('#editParentModal').modal('hide');
                    }
                });
            }


            function addEditChildRelation(data = null) {
                let childOptions =
                    `@foreach ($children as $child)<option value="{{ $child->id }}">{{ $child->name }} {{ $child->lastname }}</option>@endforeach`;

                let html = `
    <div class="child-relation-group border p-3 rounded mb-2" data-index="${childIndex}">
        <div class="form-row">
            <input type="hidden" name="children[${childIndex}][id]" value="${data?.id || ''}">
            <div class="form-group col-md-5">
                <label>Child</label>
                <select name="children[${childIndex}][childid]" class="form-control" required>
                    <option value="">Select Child</option>
                    ${childOptions}
                </select>
            </div>
            <div class="form-group col-md-5">
                <label>Relation</label>
                <select name="children[${childIndex}][relation]" class="form-control" required>
                    <option value="">Select Relation</option>
                    <option value="Mother">Mother</option>
                    <option value="Father">Father</option>
                    <option value="Brother">Brother</option>
                    <option value="Sister">Sister</option>
                    <option value="Relative">Relative</option>
                </select>
            </div>
            <div class="form-group col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeEditChildRelation(this)">Remove</button>
            </div>
        </div>
    </div>`;

                $('#editChildRelationContainer').append(html);

                if (data) {
                    $(`[name="children[${childIndex}][childid]"]`).val(data.childid);
                    $(`[name="children[${childIndex}][relation]"]`).val(data.relation);
                }

                childIndex++;
            }




            function removeEditChildRelation(btn) {
                $(btn).closest('.child-relation-group').remove();
            }

            function submitEditParent() {
                const form = document.getElementById('editParentForm');
                const formData = new FormData(form);

                const submitBtn = document.querySelector('#editParentModal button.btn-primary');
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Updating...';

                $.ajax({
                    url: "{{ route('settings.parent.update') }}", // define this route
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast('success', 'Parent updated successfully!');
                            setTimeout(() => {
                                $('#editParentModal').modal('hide');
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('error', response.message || 'Something went wrong');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(err => showToast('error', err[0]));
                        } else {
                            showToast('error', 'Server error. Please try again.');
                        }
                    },
                    complete: function() {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Update';
                    }
                });
            }
        </script>

        <script>
            // Email functionality
            let selectedParents = [];

            // Track checkbox changes
            $(document).on('change', '.parent-checkbox', function() {
                updateSelectedParents();
            });

            function updateSelectedParents() {
                selectedParents = [];
                $('.parent-checkbox:checked').each(function() {
                    const parentId = $(this).val();
                    const parentEmail = $(this).data('email');
                    const parentName = $(this).data('name');

                    // Only add if we have valid data
                    if (parentId && parentId !== 'undefined' && parentName && parentName !== 'undefined') {
                        selectedParents.push({
                            id: parentId,
                            email: parentEmail || '',
                            name: parentName
                        });
                    } else {
                        console.warn('Skipping invalid parent:', {
                            id: parentId,
                            email: parentEmail,
                            name: parentName
                        });
                    }
                });

                // Show/hide bulk action toolbar (only when more than 1 selected)
                if (selectedParents.length > 1) {
                    $('#bulkActionToolbar').slideDown();
                    $('#selectedCount').text(selectedParents.length);
                } else {
                    $('#bulkActionToolbar').slideUp();
                    // Also uncheck Select All if dropping below threshold
                    $('#selectAllParents').prop('checked', false);
                }
            }

            // Select All checkbox logic
            $(document).on('change', '#selectAllParents', function() {
                const isChecked = $(this).is(':checked');
                // Toggle all visible parent checkboxes
                $('.parent-checkbox').prop('checked', isChecked);
                updateSelectedParents();
            });

            function clearSelection() {
                $('.parent-checkbox').prop('checked', false);
                updateSelectedParents();
            }

            // Open email modal for single parent
            function openEmailModal(parentId) {
                console.log('openEmailModal called with parentId:', parentId);

                const card = $(`.parent-card[data-parent-id="${parentId}"]`);
                console.log('Found card:', card.length);

                const parentName = card.data('parent-name');
                const parentEmail = card.data('parent-email');

                console.log('Parent Name:', parentName, 'Email:', parentEmail);

                // Reset form
                if ($('#emailForm')[0]) {
                    $('#emailForm')[0].reset();
                }

                // Destroy CKEditor if exists
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['emailMessage']) {
                    CKEDITOR.instances['emailMessage'].destroy();
                }

                // Update modal title
                $('#modalTitle').text(`New Message - ${parentName}`);

                // Populate recipient info (single parent) - Gmail style
                $('#recipientDisplay').html(`
                    <span class="recipient-badge" data-parent-id="${parentId}" data-parent-email="${parentEmail}" data-parent-name="${parentName}">
                        <i class="fa fa-user"></i> ${parentName}
                    </span>
                `);
                $('#parentIds').val(parentId);
                $('#parentEmails').val(parentEmail);

                console.log('About to show modal');

                // Show modal
                $('#emailModal').modal('show');

                console.log('Modal show called');

                // CKEditor disabled for Gmail-style plain textarea
                // Uncomment below if you want to re-enable rich text editing
                /*
                // Initialize CKEditor after modal is shown (only if CKEDITOR is available)
                if (typeof CKEDITOR !== 'undefined') {
                    setTimeout(() => {
                        if (!CKEDITOR.instances['emailMessage']) {
                            CKEDITOR.replace('emailMessage', {
                                height: 250,
                                toolbar: [{
                                        name: 'basicstyles',
                                        items: ['Bold', 'Italic', 'Underline']
                                    },
                                    {
                                        name: 'paragraph',
                                        items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft',
                                            'JustifyCenter', 'JustifyRight'
                                        ]
                                    },
                                    {
                                        name: 'links',
                                        items: ['Link', 'Unlink']
                                    },
                                    {
                                        name: 'styles',
                                        items: ['Format', 'FontSize']
                                    }
                                ]
                            });
                        }
                    }, 300);
                }
                */
            }

            // Open email modal for bulk selection
            function openBulkEmailModal() {
                if (selectedParents.length === 0) {
                    showToast('error', 'Please select at least one parent');
                    return;
                }

                console.log('openBulkEmailModal called with', selectedParents.length, 'parents');

                // Reset form
                if ($('#emailForm')[0]) {
                    $('#emailForm')[0].reset();
                }

                // Destroy CKEditor if exists
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['emailMessage']) {
                    CKEDITOR.instances['emailMessage'].destroy();
                }

                // Update modal title
                $('#modalTitle').text(`Send Email to ${selectedParents.length} Parent(s)`);

                // Build recipients HTML (show all selected parents with X buttons)
                let recipientsHtml = '';
                let parentIds = [];
                let parentEmails = [];

                selectedParents.forEach(parent => {
                    // Skip if parent data is invalid
                    if (!parent || !parent.id || !parent.name) {
                        console.warn('Skipping invalid parent:', parent);
                        return;
                    }

                    recipientsHtml += `
                        <span class="recipient-badge" data-parent-id="${parent.id}" data-parent-email="${parent.email || ''}" data-parent-name="${parent.name}">
                            <i class="fa-solid fa-user"></i> ${parent.name}
                            <span class="remove-recipient" title="Remove">×</span>
                        </span>
                    `;
                    parentIds.push(parent.id);
                    parentEmails.push(parent.email);
                });

                $('#recipientDisplay').html(recipientsHtml);
                $('#parentIds').val(parentIds.join(','));
                $('#parentEmails').val(parentEmails.join(','));

                // Show modal
                $('#emailModal').modal('show');

                // CKEditor disabled for Gmail-style plain textarea
                // Uncomment below if you want to re-enable rich text editing
                /*
                // Initialize CKEditor if available
                if (typeof CKEDITOR !== 'undefined') {
                    setTimeout(() => {
                        if (!CKEDITOR.instances['emailMessage']) {
                            CKEDITOR.replace('emailMessage', {
                                height: 250,
                                toolbar: [{
                                        name: 'basicstyles',
                                        items: ['Bold', 'Italic', 'Underline']
                                    },
                                    {
                                        name: 'paragraph',
                                        items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft',
                                            'JustifyCenter', 'JustifyRight'
                                        ]
                                    },
                                    {
                                        name: 'links',
                                        items: ['Link', 'Unlink']
                                    },
                                    {
                                        name: 'styles',
                                        items: ['Format', 'FontSize']
                                    }
                                ]
                            });
                        }
                    }, 300);
                }
                */
            }

            // Event delegation for remove recipient buttons
            $(document).on('click', '.remove-recipient', function(e) {
                e.stopPropagation();
                const badge = $(this).closest('.recipient-badge');
                const parentId = badge.data('parent-id');

                console.log('Removing recipient with ID:', parentId, 'Type:', typeof parentId);

                // Handle both string "undefined" and actual undefined
                if (parentId === 'undefined' || parentId === undefined || parentId === null || parentId === '') {
                    console.warn('Invalid parent ID detected, removing badge anyway');
                    badge.remove();
                    // Also clean up selectedParents array from any invalid entries
                    selectedParents = selectedParents.filter(p => p && p.id && p.id !== 'undefined');
                    updateSelectedParents();

                    // Update modal title
                    $('#modalTitle').text(`Send Email to ${selectedParents.length} Parent(s)`);

                    // Update hidden inputs
                    const parentIds = selectedParents.map(p => p.id).filter(id => id && id !== 'undefined');
                    const parentEmails = selectedParents.map(p => p.email).filter(email => email);
                    $('#parentIds').val(parentIds.join(','));
                    $('#parentEmails').val(parentEmails.join(','));
                    return;
                }

                removeRecipient(parentId);
            });

            function removeRecipient(parentId) {
                console.log('removeRecipient called with:', parentId);

                // Convert to number for comparison if it's a valid number
                const numericId = parseInt(parentId);

                // Remove from selectedParents array
                selectedParents = selectedParents.filter(p => {
                    if (!p || !p.id) return false;
                    return p.id != parentId && p.id !== numericId;
                });

                // Update the selected count
                updateSelectedParents();

                // Remove the badge from display
                $(`.recipient-badge[data-parent-id="${parentId}"]`).remove();

                // Update hidden inputs - filter out invalid entries
                const parentIds = selectedParents.map(p => p.id).filter(id => id && id !== 'undefined');
                const parentEmails = selectedParents.map(p => p.email).filter(email => email);
                $('#parentIds').val(parentIds.join(','));
                $('#parentEmails').val(parentEmails.join(','));

                // Update modal title
                $('#modalTitle').text(`Send Email to ${selectedParents.length} Parent(s)`);

                // If no parents left, close modal
                if (selectedParents.length === 0) {
                    $('#emailModal').modal('hide');
                    toastr.info('No parents selected');
                }
            }

            // Display selected file attachments as Gmail-style chips, with client-side validation
            window.displayAttachments = function(input) {
                const attachmentsList = $('#attachmentsList');
                attachmentsList.empty();

                const allowedExt = ['pdf','doc','docx','jpg','jpeg','png'];
                const appMaxSize = 25 * 1024 * 1024; // App hard cap
                const serverMaxSize = window.SERVER_UPLOAD_MAX || (2 * 1024 * 1024); // Fallback 2MB
                const effectiveMax = appMaxSize; // respect lower of server/app

                // Rebuild files into a validated list
                const dt = new DataTransfer();
                if (input.files && input.files.length > 0) {
                    for (let i = 0; i < input.files.length; i++) {
                        const file = input.files[i];
                        const name = file.name || '';
                        const ext = name.split('.').pop().toLowerCase();
                        if (allowedExt.indexOf(ext) === -1) {
                            showToast('error', `Unsupported file type: .${ext}`);
                            continue;
                        }
                        if (file.size > effectiveMax) {
                            const mbLimit = (effectiveMax / (1024 * 1024)).toFixed(1);
                            showToast('error', `${name} exceeds limit (${mbLimit} MB).`);
                            continue;
                        }
                        dt.items.add(file);
                    }
                }

                // Update input with validated files
                input.files = dt.files;

                // Render chips
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const fileSizeDisp = file.size > 1024 * 1024
                        ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                        : (file.size / 1024).toFixed(1) + ' KB';
                    const chip = $(`
                        <div class="gmail-file-chip" data-index="${i}">
                            <i class="fa fa-paperclip"></i>
                            <span class="file-name">${file.name}</span>
                            <span class="file-size">(${fileSizeDisp})</span>
                            <span class="remove-file" onclick="removeFile(${i})">×</span>
                        </div>
                    `);
                    attachmentsList.append(chip);
                }
            };

            // Remove file from attachment list
            window.removeFile = function(index) {
                const input = document.getElementById('emailAttachments');
                const dt = new DataTransfer();

                // Copy all files except the one being removed
                for (let i = 0; i < input.files.length; i++) {
                    if (i !== index) {
                        dt.items.add(input.files[i]);
                    }
                }

                // Update the file input with the new FileList
                input.files = dt.files;

                // Refresh the display
                displayAttachments(input);
            };

            function sendEmail() {
                console.log('sendEmail called');

                const form = $('#emailForm')[0];
                const formData = new FormData(form);

                // Get CKEditor content if available
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['emailMessage']) {
                    const messageContent = CKEDITOR.instances['emailMessage'].getData();
                    formData.set('message', messageContent);
                }

                // Validation
                if (!formData.get('subject') || !formData.get('subject').trim()) {
                    showToast('error', 'Please enter email subject');
                    return;
                }

                if (!formData.get('message') || !formData.get('message').trim()) {
                    showToast('error', 'Please enter email message');
                    return;
                }

                // Show loader
                $('#emailLoader').addClass('show');

                const sendBtn = $('#emailModal .gmail-send-btn');
                sendBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Sending...');

                $.ajax({
                    url: "{{ route('settings.parent.sendEmail') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Hide loader
                        $('#emailLoader').removeClass('show');

                        if (response.status === 'success') {
                            showToast('success', response.message || 'Email sent successfully!');
                            $('#emailModal').modal('hide');

                            // Clear selection if it was a bulk email
                            if (selectedParents.length > 0) {
                                clearSelection();
                            }

                            // Destroy CKEditor instance if it exists
                            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['emailMessage']) {
                                CKEDITOR.instances['emailMessage'].destroy();
                            }
                        } else {
                            showToast('error', response.message || 'Failed to send email');
                        }
                    },
                    error: function(xhr) {
                        // Hide loader
                        $('#emailLoader').removeClass('show');

                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(err => {
                                showToast('error', err[0]);
                            });
                        } else {
                            showToast('error', 'Failed to send email. Please try again.');
                        }
                    },
                    complete: function() {
                        sendBtn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Send');
                    }
                });
            }

            // Clean up CKEditor on modal close
            $('#emailModal').on('hidden.bs.modal', function() {
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['emailMessage']) {
                    CKEDITOR.instances['emailMessage'].destroy();
                }
            });

            // Test that function is accessible
            $(document).ready(function() {
                console.log('Email modal script loaded');
                console.log('openEmailModal function exists:', typeof openEmailModal !== 'undefined');
            });
        </script>


        <script>
            let childRelationIndex = 1;

            function addChildRelation() {
                const index = childRelationIndex++;
                const html = `
                        <div class="child-relation-group border p-3 rounded mb-2 position-relative">
                            <button type="button" class="btn btn-sm btn-danger position-absolute" style="top:5px; right:5px;" onclick="removeChildRelation(this)"><i class="fa-solid fa-trash fa-fade"></i></button>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Child</label>
                                    <select name="children[${index}][childid]" class="form-control child-select" required>
                                        <option value="">Select Child</option>
                                        @foreach ($children as $child)
                                            <option value="{{ $child->id }}">{{ $child->name }} {{ $child->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label>Relation</label>
                                    <select name="children[${index}][relation]" class="form-control" required>
                                        <option value="">Select Relation</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Father">Father</option>
                                        <option value="Brother">Brother</option>
                                        <option value="Sister">Sister</option>
                                        <option value="Relative">Relative</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;
                $('#childRelationContainer').append(html);

                // Init Select2 for the newly added child select
                $(`select[name="children[${index}][childid]"]`).select2({
                    dropdownParent: $('#addParentModal'),
                    width: '100%',
                    placeholder: "Select Child",
                    allowClear: true
                });
            }

            function removeChildRelation(button) {
                $(button).closest('.child-relation-group').remove();
            }

            $(document).ready(function() {
                $('.child-select').select2({
                    width: '100%'
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                // Init existing select when modal is shown
                $('#addParentModal').on('shown.bs.modal', function() {
                    $('.child-select').select2({
                        dropdownParent: $('#addParentModal'),
                        width: '100%',
                        placeholder: "Select Child",
                        allowClear: true
                    });
                });
            });
        </script>

        <script>
            function filterbyParentsName(parentName) {
                console.log(parentName);
                $.ajax({
                    url: 'filter-parents', // Update this route to match your Laravel route
                    method: 'GET',
                    data: {
                        parent_name: parentName
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token if needed
                    },
                    success: function(response) {
                        console.log(response);
                        const parentContainer = $('.parent-data');
                        parentContainer.empty();

                        if (response.parents.length === 0) {
                            parentContainer.append('<p class="text-muted">No parents found.</p>');
                            return;
                        }

                        response.parents.forEach(function(parent) {
                            let defaultAvatars = parent.gender === 'FEMALE' ? ['avatar2.jpg', 'avatar3.jpg',
                                'avatar4.jpg', 'avatar6.jpg',
                                'avatar7.jpg'
                            ] : ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg',
                                'avatar10.jpg'
                            ];

                            let avatar = parent.imageUrl ?
                                parent.imageUrl :
                                '/assets/img/xs/' + defaultAvatars[Math.floor(Math.random() * defaultAvatars
                                    .length)];

                            let childrenList = '';
                            if (parent.children && parent.children.length > 0) {
                                parent.children.forEach(child => {
                                    childrenList +=
                                        `<li>${child.name} ${child.lastname} (${child.relation})</li>`;
                                });
                            }

                            let cardHtml = `
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm h-100 border-info parent-card" data-parent-id="${parent.id}" 
                             data-parent-email="${parent.email}" data-parent-name="${parent.name}">
                            <div class="card-body text-center position-relative">
                                <div class="position-absolute" style="top: 10px; left: 10px; z-index: 10;">
                                    <input type="checkbox" class="parent-checkbox" 
                                           value="${parent.id}" 
                                           data-email="${parent.email}" 
                                           data-name="${parent.name}"
                                           title="Select ${parent.name}">
                                </div>
                                <div class="position-absolute dropdown" style="top: 10px; left: 50px; z-index: 10;">
                                    <a href="#" class="text-info" id="parentDropdown${parent.id}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 22px; cursor: pointer;">
                                        <i class="fa-solid fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="parentDropdown${parent.id}" style="left: 0; min-width: 120px;">
                                        <a class="dropdown-item" href="#" onclick="openEmailModal(${parent.id})">Send Mail</a>
                                        <a class="dropdown-item" href="#" onclick="trackMail(${parent.id})">Track Mail</a>
                                    </div>
                                </div>
                                <img src="${avatar}" class="rounded-circle mb-3" width="80" height="80" alt="Parent Avatar">
                                <h5 class="card-title mb-1">${parent.name}</h5>
                                <p class="card-text mb-1"><strong>Email:</strong> ${parent.email}</p>
                                <p class="card-text mb-1"><strong>Contact:</strong> ${parent.contactNo}</p>
                                <p class="card-text mb-1"><strong>Children:</strong></p>
                                <ul class="list-unstyled">${childrenList}</ul>
                                <div class="d-flex justify-content-center mt-3">
                                    
                                        <button class="btn btn-sm btn-info mr-2" onclick="openEditParentModal(${parent.id})">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSuperadmin(${parent.id})">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                            parentContainer.append(cardHtml);
                        });
                    },
                    error: function(xhr) {
                        console.error('AJAX error:', xhr.responseText);
                    }
                });
            }
        </script>

        <script>
            function openTrackMailsModal() {
                // Redirect to track mails page with selected parent IDs
                let selected = selectedParents || [];
                if (selected.length === 0) {
                    alert('Please select at least one parent to view email history.');
                    return;
                }

                let parentIds = selected.map(p => p.id).join(',');
                window.location.href = "{{ route('settings.parent.trackMails') }}?parent_ids=" + parentIds;
            }

            function trackMail(parentId) {
                // Track mail for single parent from dropdown
                window.location.href = "{{ route('settings.parent.trackMails') }}?parent_ids=" + parentId;
            }
        </script>

        <!-- Email Sending Loader -->
        <div id="emailLoader">
            <div class="loader-content">
                <div class="loader-spinner"></div>
                <h5>Sending Email</h5>
                <p>Please wait while we send your email...</p>
                <div class="loader-progress">
                    <div class="loader-progress-bar"></div>
                </div>
            </div>
        </div>

        @include('layout.footer')
    @stop
