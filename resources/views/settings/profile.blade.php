@extends('layout.master')
@section('title', 'Profile Settings')
@section('parentPageTitle', 'Settings')




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
</style>
<style>
    .password-toggle {
        position: relative;
    }

    .password-toggle .toggle-eye {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
    }


    .profile-img-wrapper {
        perspective: 1000px;
        display: inline-block;
        transition: transform 0.4s ease;
    }

    .profile-img {
        border-radius: 12px;
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.4s ease;
        transform-style: preserve-3d;
    }

    .profile-img-wrapper:hover .profile-img {
        transform: rotateY(10deg) rotateX(10deg) scale(1.05);
        box-shadow: 0 18px 35px rgba(0, 0, 0, 0.3);
        filter: brightness(1.1);
    }
</style>

<script>
    $(document).ready(function () {
        $('#btn-upload-photo').on('click', function () {
            $('#filePhoto').click();
        });

        $('#filePhoto').on('change', function () {
            let file = this.files[0];
            if (!file) return;

            let formData = new FormData();
            formData.append('imageUrl', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('settings.upload.profile.image') }}", // Add this route
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                if (response.status === 'success') {
                    showToast('success', 'Image Uploaded successfully!');
                    setTimeout(() => {
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
            }
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





    });
</script>


<div class="row clearfix">

    <div class="col-lg-12">
        <div class="card">
            {{-- <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Settings">Profile
                            Settings</a></li>
                </ul>
            </div> --}}
            <div class="tab-content">

                <div class="tab-pane active" id="Settings">

                    <div class="body">
                        <h6>Profile Photo</h6>
                        <div class="media photo">
                            <div class="media-left m-r-15">
                                @php
                                $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg',
                                'avatar9.jpg','avatar10.jpg'];
                                $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg',
                                'avatar6.jpg','avatar7.jpg'];
                                $avatars = $user->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
                                $defaultAvatar = $avatars[array_rand($avatars)];
                                @endphp
                                <div class="profile-img-wrapper">
                                    <img id="profileImage"
                                        src="{{ $user->imageUrl ? asset($user->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}"
                                        class="user-photo media-object profile-img" alt="User"
                                        style="width:120px;height:105px;">
                                </div>
                            </div>
                            <div class="media-body">
                                <p>Upload your photo.
                                    <br> <em>Image should be less than 2 MB</em>
                                </p>
                                <button type="button" class="btn btn-default-dark" id="btn-upload-photo"
                                    style="border:1px solid lightblue;">Upload Photo</button>
                                <input type="file" id="filePhoto" class="sr-only" name="imageUrl" accept="image/*">
                            </div>
                        </div>
                    </div>


                    <div class="body">
                        <h6>Basic Information</h6>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Contact No</label>
                                    <input type="tel" class="form-control" name="contactNo"
                                        value="{{ $user->contactNo }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="MALE" {{ $user->gender == 'MALE' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="FEMALE" {{ $user->gender == 'FEMALE' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="OTHERS" {{ $user->gender == 'OTHERS' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="updateStaffBtn">Update</button>
                    </div>


                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12">
                                <h6>Change Password</h6>

                                <div class="form-group password-toggle">
                                    <input type="password" class="form-control" name="current_password"
                                        placeholder="Current Password" required>
                                    <i class="toggle-eye fas fa-eye"></i>
                                </div>

                                <div class="form-group password-toggle">
                                    <input type="password" class="form-control" name="new_password"
                                        placeholder="New Password" required>
                                    <i class="toggle-eye fas fa-eye"></i>
                                </div>

                                <div class="form-group password-toggle">
                                    <input type="password" class="form-control" name="new_password_confirmation"
                                        placeholder="Confirm New Password" required>
                                    <i class="toggle-eye fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="changePasswordBtn">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast-container" class="toast-bottom-right" style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;">
</div>



<script>
    $('#updateStaffBtn').click(function () {
        let formData = {
            name: $('input[name="name"]').val(),
            email: $('input[name="email"]').val(),
            contactNo: $('input[name="contactNo"]').val(),
            gender: $('select[name="gender"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("settings.profile.update", $user->id) }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    showToast('success', 'Basic Details Updated successfully!');
                    setTimeout(() => {
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
            }
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




    $('#changePasswordBtn').click(function () {
        let formData = {
    current_password: $('input[name="current_password"]').val(),
    new_password: $('input[name="new_password"]').val(),
    new_password_confirmation: $('input[name="new_password_confirmation"]').val(),
    _token: '{{ csrf_token() }}'
};

        $.ajax({
            url: '{{ route("settings.profile.change-password", $user->id) }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    showToast('success', 'Password updated successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast('error', response.message || 'Something went wrong');
                }
            },
            error: function(xhr) {
    if (xhr.status === 422 && xhr.responseJSON) {
        // Show Laravel validation errors if present
        if (xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            Object.keys(errors).forEach(key => {
                showToast('error', errors[key][0]);
            });
        } else if (xhr.responseJSON.message) {
            // Show custom 422 error messages (like password mismatch)
            showToast('error', xhr.responseJSON.message);
        } else if (xhr.responseJSON.error) {
            showToast('error', xhr.responseJSON.error);
        } else {
            showToast('error', 'Validation failed. Please check your input.');
        }
    } else {
        showToast('error', 'Server error. Please try again.');
    }
}
        });
    });



    $(document).ready(function () {
    $('.toggle-eye').click(function () {
        let input = $(this).siblings('input');
        let type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);

        // Toggle icon class
        $(this).toggleClass('fa-eye fa-eye-slash');
    });
});


</script>

<script>
    $(document).ready(function () {
        $('.profile-img-wrapper').on('mousemove', function (e) {
            const $this = $(this);
            const img = $this.find('.profile-img');
            const rect = $this[0].getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 10;
            const rotateY = (x - centerX) / -10;

            img.css('transform', `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`);
        });

        $('.profile-img-wrapper').on('mouseleave', function () {
            $(this).find('.profile-img').css('transform', 'rotateX(0deg) rotateY(0deg) scale(1)');
        });
    });
</script>

@include('layout.footer')
@stop
