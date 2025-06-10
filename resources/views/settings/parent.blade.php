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
</style>






@section('content')



<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;">
    <div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                 style="background-color:white;"  data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>
</div>


<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Parent Settings<small></small> </h2>
                <button class="btn btn-outline-info" style="float:right;margin-bottom:20px;" data-toggle="modal"
                    data-target="#addParentModal">
                    <i class="fa fa-plus"></i>&nbsp; Add Parent
                </button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable c_list">
                        <thead class="thead-light">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact No.</th>
                                <th>Children</th>

                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact No.</th>
                                <th>Children</th>

                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($parents as $index => $staffs)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                    $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg',
                                    'avatar10.jpg'];
                                    $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg',
                                    'avatar7.jpg'];
                                    $avatars = $staffs->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
                                    $defaultAvatar = $avatars[array_rand($avatars)];
                                    @endphp
                                    <img src="{{ $staffs->imageUrl ? asset($staffs->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}"
                                        class="rounded-circle avatar" alt="">
                                    <span class="c_name">{{ $staffs->name }} </span>
                                </td>
                                <td>{{ $staffs->email }}</td>
                                <td>{{ $staffs->contactNo }}</td>
                                <td>
                                @foreach ($staffs->children as $child)
            <li>{{ $child->name }} {{ $child->lastname }} ({{ $child->pivot->relation }})</li>
        @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info"
                                        onclick="openEditParentModal({{ $staffs->id }})">
                                        <i class="fa-solid fa-pen-to-square fa-beat-fade"></i> Edit
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSuperadmin({{ $staffs->id }})">
                                        <i class="fa-solid fa-trash fa-fade"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>


    <div id="toast-container" class="toast-bottom-right"
        style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>



    <!-- Modal Form -->
    <div class="modal fade" id="addParentModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
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
                            <select name="children[0][childid]" class="form-control child-select" required>
        <option value="">Select Child</option>
        @foreach($children as $child)
            <option value="{{ $child->id }}">{{ $child->name }} {{ $child->lastname }}</option>
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

        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addChildRelation()">Add Another Child</button>

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


    <div class="modal fade" id="editParentModal" tabindex="-1" aria-hidden="true">
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
                            <input type="tel" class="form-control" name="contactNo" id="editContactNo" required>
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
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEditChildRelation()">Add Another Child</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" onclick="submitEditParent()" class="btn btn-primary">Update</button>
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
        success: function (response) {
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
        error: function () {
            showToast('error', 'Failed to load parent data');
            $('#editParentModal').modal('hide');
        }
    });
}


function addEditChildRelation(data = null) {
    let childOptions = `@foreach($children as $child)<option value="{{ $child->id }}">{{ $child->name }} {{ $child->lastname }}</option>@endforeach`;

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
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function (response) {
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
        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                Object.values(xhr.responseJSON.errors).forEach(err => showToast('error', err[0]));
            } else {
                showToast('error', 'Server error. Please try again.');
            }
        },
        complete: function () {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Update';
        }
    });
}


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
                        @foreach($children as $child)
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

    $(document).ready(function () {
        $('.child-select').select2({ width: '100%' });
    });
</script>


<script>
$(document).ready(function () {
    // Init existing select when modal is shown
    $('#addParentModal').on('shown.bs.modal', function () {
        $('.child-select').select2({
            dropdownParent: $('#addParentModal'),
            width: '100%',
            placeholder: "Select Child",
            allowClear: true
        });
    });
});
</script>



    @include('layout.footer')
    @stop