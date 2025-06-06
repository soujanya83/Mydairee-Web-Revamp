@extends('layout.master')
@section('title', 'Centers Settings')
@section('parentPageTitle', 'Centers Settings')

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

@section('content')


<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Centers Settings<small></small> </h2>  
<button class="btn btn-outline-info" style="float:right;margin-bottom:20px;" data-toggle="modal" data-target="#addCenterModal">
<i class="fa fa-plus"></i>&nbsp; Add Center
</button>                    
            </div>
            <div class="body">
            <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable js-exportable c_list">
        <thead class="thead-light">
            <tr>
                <th>Sr. No.</th>
                <th>Center Name</th>
                <th>Street Address</th>
                <th>City Address</th>
                <th>State Address</th>
                <th>Zip</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
            <th>Sr. No.</th>
                <th>Center Name</th>
                <th>Street Address</th>
                <th>City Address</th>
                <th>State Address</th>
                <th>Zip</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($centers as $index => $center)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
    <span class="c_name">{{ $center->centerName }} </span>
                   </td>
                    <td>{{ $center->adressStreet }}</td>
                    <td>{{ $center->addressCity }}</td>
                    <td>{{ $center->addressState }}</td>
                    <td>{{ $center->addressZip }}</td>
                    <td>
    <button class="btn btn-sm btn-info" onclick="openEditcenterModal({{ $center->id }})">
        <i class="fa-solid fa-pen-to-square fa-beat-fade"></i> Edit
    </button>
</td>
<td>
@if(count($centers) > 1)    
    <button class="btn btn-sm btn-danger" onclick="deletecenter({{ $center->id }})">
        <i class="fa-solid fa-trash fa-fade"></i> Delete
    </button>
    @endif
</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        </div>
    </div>

</div>


<div id="toast-container" class="toast-bottom-right" style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>



<!-- Modal Form -->
<div class="modal fade" id="addCenterModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Add New Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="max-height:500px;overflow-y:auto;">
                <form id="centerForm" enctype="multipart/form-data">
                    @csrf <!-- Laravel CSRF -->               

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
                <button type="Submit" class="btn btn-primary" onclick="submitcenterForm()">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editcenterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editcenterForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Center</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body row">
          <input type="hidden" name="id" id="editId">

          <div class="form-group col-md-6">
            <label>Center Name</label>
            <input type="text" class="form-control" id="centerName" name="centerName" required>
          </div>
          <div class="form-group col-md-6">
            <label>Street Address</label>
            <input type="text" class="form-control" id="adressStreet" name="adressStreet" required>
          </div>
          <div class="form-group col-md-4">
            <label>City</label>
            <input type="text" class="form-control" id="addressCity" name="addressCity" required>
          </div>
          <div class="form-group col-md-4">
            <label>State</label>
            <input type="text" class="form-control" id="addressState" name="addressState" required>
          </div>
          <div class="form-group col-md-4">
            <label>ZIP Code</label>
            <input type="text" class="form-control" id="addressZip" name="addressZip" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" onclick="updatecenter()" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>



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

function submitcenterForm() {
    const form = document.getElementById('centerForm');
    const formData = new FormData(form);
  
    

    const submitBtn = document.querySelector('[onclick="submitcenterForm()"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Saving...';

    // Clear any previous validation states and toasts
    $('#centerForm .form-control, #centerForm .form-select').removeClass('is-invalid');
    $('#toast-container').html('');

    let valid = true;
    let firstInvalid = null;

    // Manual validation for required fields
    $('#centerForm [required]').each(function () {
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
        url: "{{ route('settings.center_store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.status === 'success') {
                showToast('success', 'Center added successfully!');
                setTimeout(() => {
                    $('#addCenterModal').modal('hide');
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



function openEditcenterModal(id) {
    $.ajax({
        url: `/settings/center/${id}/edit`, // This route must return JSON
        type: 'GET',
        success: function (data) {
            $('#editId').val(data.id);
            $('#centerName').val(data.centerName);
            $('#addressCity').val(data.addressCity);
            $('#adressStreet').val(data.adressStreet);
            $('#addressState').val(data.addressState);
            $('#addressZip').val(data.addressZip);
            

            $('#editcenterModal').modal('show');
        },
        error: function () {
            showToast('error', 'Failed to fetch user data.');
        }
    });
}


function updatecenter() {
    const form = document.getElementById('editcenterForm');
    const formData = new FormData(form);
    const id = $('#editId').val();

    $.ajax({
        url: `/settings/center/${id}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.status === 'success') {
                showToast('success', 'Center updated successfully!');
                setTimeout(() => {
                    $('#editcenterModal').modal('hide');
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



function deletecenter(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this Center!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/settings/center/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showToast('success', 'Center deleted successfully!');
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