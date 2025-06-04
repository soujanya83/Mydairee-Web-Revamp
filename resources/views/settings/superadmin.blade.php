@extends('layout.master')
@section('title', 'Superadmin Settings')
@section('parentPageTitle', 'Superadmin Settings')


@section('content')
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
    background-color: #28a745; /* Green for success */
}

.toast-error {
    background-color: #dc3545; /* Red for error */
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

.c_list .avatar{
    height:45px;
    width: 50px;
}
</style>

<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Super-Admin Settings<small></small> </h2>  
                <button class="btn btn-outline-info" style="float:right;margin-bottom:20px;" data-toggle="modal" data-target="#addSuperadminModal">
                <i class="fa fa-plus"></i>&nbsp;  Add Superadmin
</button>                    
            </div>
            <div class="body">
            <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable js-exportable c_list l-blush">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
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
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($superadmins as $index => $admin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
    @php
        $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg', 'avatar10.jpg'];
        $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg', 'avatar7.jpg'];
        $avatars = $admin->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
        $defaultAvatar = $avatars[array_rand($avatars)];
    @endphp
    <img src="{{ $admin->imageUrl ? asset($admin->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}" class="rounded-circle avatar" alt="">
    <span class="c_name">{{ $admin->name }} </span>
</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->contactNo }}</td>
                    <td>
    <button class="btn btn-sm btn-info" onclick="openEditSuperadminModal({{ $admin->id }})">
        <i class="fa-solid fa-pen-to-square fa-beat-fade"></i> Edit
    </button>
</td>
<td>
    <button class="btn btn-sm btn-danger" onclick="deleteSuperadmin({{ $admin->id }})">
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




<!-- Modal Form -->
<div class="modal fade" id="addSuperadminModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Add New Superadmin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                <form id="superadminForm" enctype="multipart/form-data">
                    @csrf <!-- Laravel CSRF -->

                    <h6 class="mb-3">Superadmin Details</h6>
                    <div class="form-row">
                        <!-- <div class="form-group col-md-6">
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div> -->
                        <div class="form-group col-md-6">
                            <label>Name</label>
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

                    <h6 class="mt-4 mb-3">Center Details</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Center Name</label>
                            <input type="text" class="form-control" name="centerName" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Street Address</label>
                            <input type="text" class="form-control" name="adressStreet" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>City</label>
                            <input type="text" class="form-control" name="addressCity" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>State</label>
                            <input type="text" class="form-control" name="addressState" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>ZIP Code</label>
                            <input type="text" class="form-control" name="addressZip" required>
                        </div>
                    </div>
               
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="Submit" class="btn btn-primary" onclick="submitSuperadminForm()">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editSuperadminModal" tabindex="-1" aria-hidden="true">
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
            <label>Password <span style="color:green;">(Optional- Leave blank if not changing)</span></label>
            <input type="password" class="form-control" name="password" id="editPassword">
          </div>

          <div class="form-group col-md-6">
            <label>Contact No</label>
            <input type="tel" class="form-control" name="contactNo" id="editContactNo" required>
          </div>

          <div class="form-group col-6">
                <label>Change Image <span style="color:green;">(Optional)</span></label>
                <input type="file" class="form-control" id="imageUrl" name="imageUrl" accept="image/*">
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



<div id="toast-container" class="toast-bottom-right" style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>



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
        $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function () {
            $(this).remove();
        });
    }, 3000);
}

function submitSuperadminForm() {
    const form = document.getElementById('superadminForm');
    const formData = new FormData(form);
    formData.append('userType', 'Superadmin');

    const submitBtn = document.querySelector('[onclick="submitSuperadminForm()"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Saving...';

    // Clear any previous validation states and toasts
    $('#superadminForm .form-control, #superadminForm .form-select').removeClass('is-invalid');
    $('#toast-container').html('');

    let valid = true;
    let firstInvalid = null;

    // Manual validation for required fields
    $('#superadminForm [required]').each(function () {
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
        url: "{{ route('settings.superadmin.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.status === 'success') {
                showToast('success', 'Superadmin added successfully!');
                setTimeout(() => {
                    $('#addSuperadminModal').modal('hide');
                    location.reload();
                }, 1500);
            } else {
                showToast('error', response.message || 'Something went wrong');
            }
        },
        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    showToast('error', errors[key][0]);
                });
            } else {
                showToast('error', 'Server error. Please try again.');
            }
        },
        complete: function () {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save';
        }
    });
}

function openEditSuperadminModal(id) {
    $.ajax({
        url: `/settings/superadmin/${id}/edit`, // This route must return JSON
        type: 'GET',
        success: function (data) {
            $('#editId').val(data.id);
            $('#editName').val(data.name);
            $('#editEmail').val(data.email);
            $('#editContactNo').val(data.contactNo);
            $('#editGender').val(data.gender);
            $('#editPassword').val(''); // Clear password field

            $('#editSuperadminModal').modal('show');
        },
        error: function () {
            showToast('error', 'Failed to fetch user data.');
        }
    });
}

function updateSuperadmin() {
    const form = document.getElementById('editSuperadminForm');
    const formData = new FormData(form);
    const id = $('#editId').val();

    $.ajax({
        url: `/settings/superadmin/${id}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
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
        error: function (xhr) {
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
        text: 'You will not be able to recover this Superadmin!',
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
                success: function (response) {
                    if (response.status === 'success') {
                        showToast('success', 'Superadmin deleted successfully!');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('error', response.message || 'Delete failed');
                    }
                },
                error: function () {
                    showToast('error', 'Server error occurred');
                }
            });
        }
    });
}


    </script>



@include('layout.footer')
@stop

