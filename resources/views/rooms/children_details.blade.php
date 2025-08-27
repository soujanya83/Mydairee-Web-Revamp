@extends('layout.master')
@section('title', 'Children')
@section('parentPageTitle', 'Rooms')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Flatpickr CSS -->
{{--
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .top-right-button-container {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .radio-label,
    .flexCheck label {
        margin-right: 15px;
    }

    .error_firstname,
    .error_lastname,
    .error_dob,
    .error_doj {
        font-size: 0.8em;
    }

    .modal-backdrop {
        transition: opacity 0.15s linear;
    }

    .modal-backdrop.show {
        opacity: 0.5;
    }

    .modal-backdrop.fade {
        opacity: 0;
    }
</style>


<style>
    .gender-box {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #fff;
        padding: 8px;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        width: 253px;
        margin: auto;
    }

    .gender-column {
        flex: 1;
        text-align: center;
    }

    .gender-column i {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .gender-column h5 {
        margin: 0;
        font-size: 20px;
        font-weight: bold;
    }

    .divider {
        width: 1px;
        background-color: #ccc;
        height: 60px;
        margin: 0 20px;
    }

    .male {
        color: blue;
    }

    .female {
        color: red;
    }
</style>

@section('content')


<div class="container mt-4" style="margin-bottom: 30px">



    <div class="text-zero top-right-button-container mt-3" style="margin-right:40px">
        <div class="btn-group">
            <button data-toggle="modal" data-target="#newChildModal" class="btn btn-outline-info"> + Add
                New Child</button>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-22px">
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
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-22px">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif


    <div class="row clearfix" style="margin-top:30px">
        <div class="col-lg-3 col-md-6">
            <div class="card top_counter">
                <div class="body">
                    <div class="icon text-info"><i class="fa fa-university"></i> </div>
                    <div class="content">
                        <div class="text">Room Name</div>
                        <h5 class="number">{{ $roomcapacity->name }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card top_counter">
                <div class="body">
                    <div class="icon text-info"><i class="fa fa-university"></i> </div>
                    <div class="content">
                        <div class="text">Room Capacity</div>
                        <h5 class="number">{{ $roomcapacity->capacity }}</h5>
                    </div>
                </div>
            </div>
        </div>





        <div class="col-lg-3 col-md-6">
            <div class="card top_counter">
                <div class="body">

                    <div class="icon text-success"><i class="fa fa-users"></i> </div>
                    <div class="content">
                        <div class="text">Active Children</div>
                        <h5 class="number">{{ $activechilds }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="gender-box" style="background-image: url('{{ asset('assets/img/doodle1.png') }}')">
                <div class="gender-column male">
                    <i class="fa fa-male"></i>
                    <div>Male</div>
                    <h5>{{ $malechilds }}</h5>
                </div>

                <div class="divider"></div>

                <div class="gender-column female">
                    <i class="fa fa-female"></i>
                    <div>Female</div>
                    <h5>{{ $femalechilds }}</h5>
                </div>
            </div>
        </div>
    </div>



    <div class="container mt-4">
        <!-- Bootstrap Nav Tabs -->
        <ul class="nav nav-tabs mb-3" id="roomTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="children-tab" data-bs-toggle="tab" href="#children"
                    role="tab">Children</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="educators-tab" data-bs-toggle="tab" href="#educators" role="tab">Educators</a>
            </li>
        </ul>

        <div class="tab-content" id="roomTabContent">
            <!-- CHILDREN TAB -->
            <div class="tab-pane fade show active" id="children" role="tabpanel" aria-labelledby="children-tab">



                <form action="{{ route('move_children') }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-end mb-3 align-items-center">
                        <select name="room_id" class="form-control mr-2" style="width: 200px;" id="roomSelect" disabled>
                            <option value="" selected>Select a room</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary btn-xs" id="moveButton"
                            disabled>MOVE</button>
                        &nbsp;&nbsp;
                        <button type="submit" formaction="{{ route('delete_selected_children') }}"
                            class="btn btn-outline-danger btn-xs" id="deleteButton"
                            onclick="return confirm('Are you sure you want to delete the selected children?')" disabled>
                            DELETE
                        </button>
                    </div>

                    <div class="row">
                        @foreach($allchilds as $child)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm border-0 rounded p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $child->imageUrl ? asset($child->imageUrl) : 'https://e7.pngegg.com/pngimages/565/301/png-clipart-computer-icons-app-store-child-surprise-in-collection-game-child.png' }}"
                                        alt="Profile" class="rounded-circle" width="50" height="50"
                                        style="object-fit: cover;">
                                    <div class="ms-3" style="margin-left:12px">
                                        <h5 class="mb-1">
                                            <a href="{{ route('edit_child', ['id' => $child->id]) }}">{{
                                                ucfirst($child->name) }}</a>
                                        </h5>
                                        <p class="mb-0">Date of Birth: {{ date('d-M-Y', strtotime($child->dob)) }}</p>
                                        <p class="mb-0">Joining Date: {{ date('d-M-Y', strtotime($child->startDate)) }}
                                        </p>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($child->dob)->age }} years</p>
                                    </div>
                                </div>
                                {{-- <a href="#" class="btn btn-outline-primary btn-sm mt-2">Last Observation</a> --}}
                                <input type="checkbox" name="child_ids[]" value="{{ $child->id }}"
                                    class="child-checkbox mr-2"
                                    style="margin-left: 250px;z-index: 1;width: 15px; height: 15px;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <!-- EDUCATORS TAB -->
            <div class="tab-pane fade" id="educators" role="tabpanel" aria-labelledby="educators-tab">

                <button type="button" class="btn btn-outline-info mb-3" data-bs-toggle="modal"
                    data-bs-target="#manageEducatorsModal" style="margin-left: 84%">
                    Manage Educators
                </button>


                <div class="row">
                    @foreach($roomEducators as $educator)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0 rounded p-3">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $educator->imageUrl ? asset($educator->imageUrl) : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}"
                                    alt="Profile" class="rounded-circle" width="50" height="50"
                                    style="object-fit: cover;">
                                <div class="ms-3" style="margin-left:12px">
                                    <h5 class="mb-1">{{ ucfirst($educator->name) }}</h5>
                                    <p class="mb-0">{{ $educator->gender }}</p>
                                </div>
                            </div>
                            {{-- <a href="#" class="btn btn-outline-secondary btn-sm mt-2">View Profile</a> --}}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>




</div>



<!-- Modal -->
<div class="modal" id="manageEducatorsModal" tabindex="-1" aria-labelledby="manageEducatorsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card">
            <form method="POST" action="{{ route('rooms.assign.educators', $roomid) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="manageEducatorsModalLabel">Manage Educators</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
                </div>
                <hr>
                <div class="modal-body">
                    @foreach ($AllEducators as $educator)
                    <div class="form-check d-flex align-items-center mb-2">
                        <img src="{{ asset($educator->imageUrl) }}" class="rounded-circle ms-2 me-2" width="40"
                            height="40">

                        <input class="form-check-input" type="checkbox" name="educators[]"
                            value="{{ $educator->userid }}" {{ in_array($educator->userid, $assignedEducatorIds) ?
                        'checked' : '' }}>

                        &nbsp;&nbsp;&nbsp; <label class="form-check-label">{{ $educator->name }}</label>
                    </div>
                    @endforeach

                </div>

                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                    <button type="submit" class="btn btn-info">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="newChildModal" tabindex="-1" role="dialog" aria-labelledby="newChildModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newChildModalLabel">+Add New Child</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><b>X</b></span>
                </button>
            </div>
            <form action="{{ route('add_children') }}" id="form-child" method="post" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{ $roomid }}">
                {{-- <input type="hidden" value="1" name="centerId"> --}}
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name <span style="color:red">*</span></label>
                            <span class="text-danger error_firstname"></span>
                            <input type="text" name="firstname" id="firstname" placeholder="Enter first name"
                                class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name <span style="color:red">*</span></label>
                            <span class="text-danger error_lastname"></span>
                            <input type="text" name="lastname" id="lastname" placeholder="Enter last name"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dob">Date of Birth <span style="color:red">*</span></label>
                            <span class="text-danger error_dob"></span>
                            {{-- <input type="text" name="dob" id="dob" value=""
                                class="form-control date-input flatpickr-input" required> --}}
                            <input type="date" name="dob" id="dob" class="form-control date-input flatpickr-input"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="doj">Date of Join <span style="color:red">*</span></label>
                            <span class="text-danger error_doj"></span>
                            <input type="date" name="startDate" id="doj" class="form-control date-input flatpickr-input"
                                required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="uploadImg">Choose Image</label>
                            <input id="uploadImg" name="file" class="form-control" type="file" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="status">Status <span style="color:red">*</span></label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Active" selected>Active</option>
                                <option value="In Active">In Active</option>
                            </select>

                        </div>

                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="gender">Gender <span style="color:red">*</span></label>
                            <div class="d-flex">
                                <span class="radio-label">
                                    <input type="radio" name="gender" id="radioMale" value="Male" checked>
                                    <label for="radioMale"> Male</label>
                                </span>
                                <span class="radio-label">
                                    <input type="radio" name="gender" id="radioFemale" value="Female">
                                    <label for="radioFemale"> Female</label>
                                </span>
                                <span class="radio-label">
                                    <input type="radio" name="gender" id="radioOther" value="Other">
                                    <label for="radioOther"> Other</label>
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="daysAttending">Days Attending <span style="color:red">*</span></label>
                            <div class="flexCheck">
                                <input type="checkbox" name="mon" value="1" id="Monday" checked>
                                <label for="Monday"> Monday</label>
                                <input type="checkbox" name="tue" value="1" id="Tuesday" checked>
                                <label for="Tuesday"> Tuesday</label>
                                <input type="checkbox" name="wed" value="1" id="Wednesday" checked>
                                <label for="Wednesday"> Wednesday</label>
                                <input type="checkbox" name="thu" value="1" id="Thursday" checked>
                                <label for="Thursday"> Thursday</label>
                                <input type="checkbox" name="fri" value="1" id="Friday" checked>
                                <label for="Friday"> Friday</label>
                            </div>
                        </div>
                    </div>





                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-danger" data-dismiss="modal"
                        id="closeModalBtn">Close</button> --}}
                    <button type="submit" class="btn btn-success btn-add-child">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery (required for Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let flatpickrInstances = [];

        // Function to initialize Flatpickr
        function initializeFlatpickr() {
            try {
                // Destroy any existing Flatpickr instances
                flatpickrInstances.forEach(instance => {
                    if (instance && typeof instance.destroy === 'function') {
                        instance.destroy();
                    }
                });
                flatpickrInstances = [];

                // Initialize Flatpickr for date inputs
                const dobPicker = flatpickr("#dob", {
                    dateFormat: "Y-m-d",
                    maxDate: "today"
                });
                const dojPicker = flatpickr("#doj", {
                    dateFormat: "Y-m-d",
                    maxDate: "today"
                });

                flatpickrInstances.push(dobPicker, dojPicker);
                console.log("Flatpickr initialized successfully");
            } catch (error) {
                console.error("Flatpickr initialization failed:", error);
            }
        }

        // Function to clean up modal state
        function cleanupModal() {
            console.log("Cleaning up modal state");
            // Remove all backdrops
            $('.modal-backdrop').remove();
            // Remove modal-open class from body
            $('body').removeClass('modal-open');
            // Ensure modal is hidden
            $('#newChildModal').removeClass('show').css({
                'display': 'none',
                'opacity': 0
            });
            // Reset z-index and padding on body
            $('body').css({
                'padding-right': 0,
                'overflow': 'auto'
            });
            // Remove any inline styles on modal
            $('#newChildModal').css('z-index', '');
        }

        // Initialize Flatpickr and reset form when modal is shown
        $('#newChildModal').on('shown.bs.modal', function () {
            console.log("Modal shown, initializing Flatpickr and resetting form");
            initializeFlatpickr();
            $('#form-child')[0].reset();
            $('.error_firstname, .error_lastname, .error_dob, .error_doj').text('');

            // Ensure only one backdrop exists
            $('.modal-backdrop').not(':first').remove();
        });

        // Clean up when modal is hidden
        $('#newChildModal').on('hidden.bs.modal', function () {
            console.log("Modal hidden, performing cleanup");
            cleanupModal();

            // Destroy Flatpickr instances
            flatpickrInstances.forEach(instance => {
                if (instance && typeof instance.destroy === 'function') {
                    instance.destroy();
                }
            });
            flatpickrInstances = [];

            // Force cleanup of any lingering backdrops after a short delay
            setTimeout(() => {
                if ($('.modal-backdrop').length > 0) {
                    console.warn("Lingering backdrop detected, forcing removal");
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                }
            }, 300);
        });

        // Remove previous event listeners before attaching new ones
        $('#newChildModal').off('shown.bs.modal hidden.bs.modal');
        $('#form-child').off('submit');
        $('#closeModalBtn').off('click');

        // Attach event listeners
        $('#newChildModal').on('shown.bs.modal', function () {
            console.log("Modal shown, initializing Flatpickr and resetting form");
            initializeFlatpickr();
            $('#form-child')[0].reset();
            $('.error_firstname, .error_lastname, .error_dob, .error_doj').text('');
        });

        $('#newChildModal').on('hidden.bs.modal', function () {
            console.log("Modal hidden, performing cleanup");
            cleanupModal();
        });

        // Handle form submission
        // $('#form-child').on('submit', function (e) {
        //     e.preventDefault();
        //     console.log("Form submitted");
        //     $('#newChildModal').modal('hide');
        // });

        // Additional safeguard for close button
        $('#closeModalBtn').on('click', function () {
            console.log("Close button clicked");
            $('#newChildModal').modal('hide');
        });



</script>


<!-- JavaScript to enable/disable MOVE button and room select based on checkbox selection -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.child-checkbox');
        const moveButton = document.getElementById('moveButton');
        const roomSelect = document.getElementById('roomSelect');

        function updateMoveButtonState() {
            const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            moveButton.disabled = !anyChecked;
            roomSelect.disabled = !anyChecked;
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateMoveButtonState);
        });

        updateMoveButtonState();
    });
</script>


<script>
    document.querySelectorAll('.child-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const selected = document.querySelectorAll('.child-checkbox:checked').length;
            document.getElementById('moveButton').disabled = selected === 0;
            document.getElementById('deleteButton').disabled = selected === 0;
        });
    });
</script>

<script src="/js/bootstrap.bundle.min.js"></script>



@include('layout.footer')
@stop
