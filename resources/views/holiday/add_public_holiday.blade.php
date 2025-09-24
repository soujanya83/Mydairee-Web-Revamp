@extends('layout.master')
@section('title', 'Public Holiday List')
@section('parentPageTitle', 'Setting')

@section('page-styles')
<!-- Font Awesome 6 CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>



    #holidayEditModal .modal-body {
    max-height: 70vh;   /* or any value */
    overflow-y: auto;
}

    /* Limit modal height and allow scroll */
    #selectChildrenModal .modal-body {
        max-height: 80vh;
        /* limit vertical height */
        overflow-y: auto;
        /* enable vertical scroll */
        overflow-x: hidden;
        /* prevent horizontal scroll */
        padding-right: 10px;
        /* optional */
        width: 100%;
        /* full width */
        box-sizing: border-box;
        /* include padding in width */
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

    .media-upload-box {
        border: 2px dashed #007bff;
        background-color: #f8f9fa;
        position: relative;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }

    .media-upload-box:hover {
        background-color: #e9f0ff;
    }

    .list-table td,
    .list-table tr {
        border: none !important;
    }

    .media-thumb {
        height: 150px;
        object-fit: cover;
        width: 100%;
    }

    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        padding: 2px 5px;
        font-size: 12px;
    }

    #mediaPreview .btn {
        margin-right: 5px;
        margin-top: 5px;
    }

    .media-thumb {
        max-height: 200px;
        object-fit: cover;
        width: 100%;
        border: 1px solid #ddd;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

<style>
    .ck-editor__editable_inline {
        min-height: 300px;
        /* This is like setting more "rows" */
    }

/* Slide-out style for child modal */
.modal.right .modal-dialog {
  position: fixed;
  margin: auto;
  width: 80%;
  height: 100%;
  right: 0;
  top: 0;
  transform: translate3d(100%, 0, 0);
  transition: all 0.3s ease-out;
}

.modal.right.show .modal-dialog {
  transform: translate3d(0, 0, 0);
}


th.sortable {
    cursor: pointer;
    user-select: none;
    white-space: nowrap;
}
.arrow {
    display: inline-block;
    width: 1em;
    text-align: center;
}
.arrow::before {
    content: "⇅"; /* neutral */
    font-size: 0.8em;
    color: #666;
}
th.asc .arrow::before {
    content: "↑";
    color: green;
}
th.desc .arrow::before {
    content: "↓";
    color: red;
}


</style>


@endsection

@section('content')

<div class="d-flex justify-content-end" style="margin-top: -52px;margin-right:50px">
    <a href="{{ route('announcements.create') }}" class="btn btn-outline-info">
        Add New Holiday
    </a>


</div>
<hr>

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
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">

            <div class="body table-responsive">

                <form method="GET" action="{{ route('settings.public_holiday') }}" class="mb-3 d-flex gap-3">
                    <!-- Month Filter -->
                    <select name="month" class="form-control" style="width:150px;margin-left:12px">
                        <option value="">All Months</option>
                        @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ request('month')==$m ? 'selected' : ''
                            }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                            @endfor
                    </select>

                    <!-- Date Filter -->
                    <!-- <select name="date" class="form-control" style="width:150px;margin-left:12px">
                        <option value="">All Dates</option>
                        @for ($d = 1; $d <= 31; $d++) <option value="{{ $d }}" {{ request('date')==$d ? 'selected' : ''
                            }}>
                            {{ $d }}
                            </option>
                            @endfor
                    </select> -->

                    <button type="submit" class="btn btn-info" style="margin-left:12px"><i class="fas fa-filter"></i>
                        Filter</button>
                    <a href="{{ route('settings.public_holiday') }}" class="btn btn-secondary"
                        style="margin-left:12px"><i class="fas fa-refresh"></i> Reset</a>
                </form>

<table id="holidayTable" class="table">
   <thead>
    <tr>
        <th class="sortable" data-col="0">sno <i class="fas fa-sort"></i></th>
        <th class="sortable" data-col="1">Date <i class="fas fa-sort"></i></th>
        <th class="sortable" data-col="2">Occasion <i class="fas fa-sort"></i></th>
        <th class="sortable" data-col="3">State <i class="fas fa-sort"></i></th>
        <th>Action</th>
    </tr>
</thead>

    <tbody>
        @foreach($holidayData as $index => $holidays)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $holidays->full_date->format('d M Y') }}</td>
            <td>{{ \Illuminate\Support\Str::limit($holidays->occasion, 75) }}</td>
            <td>{{ $holidays->state ?: '--' }}</td>
            <td>
                <button type="button"
                        class="btn btn-sm btn-info p-2"
                        onclick="window.location.href='{{ route('settings.holidays.edit', $holidays->id) }}'">
                    <i class="fas fa-edit"></i>
                </button>
                &nbsp;
                <form action="{{ route('settings.holiday.destroy', $holidays->id) }}" method="POST"
                      style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger p-2" title="Record Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

            </div>

        </div>
    </div>
</div>





<!-- add children -->
<div class="modal modal-right" id="selectChildrenModal" tabindex="-1" role="dialog"
    aria-labelledby="selectChildrenModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Select Children</h5>
                <button type="button" class="close select-children" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group filter-box">
                    <input type="text" class="form-control" id="filter-child"
                        placeholder="Enter child name or age to search">
                </div>

                <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab"
                            aria-controls="first" aria-selected="true">Children</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="second-tab" data-toggle="tab" href="#second" role="tab"
                            aria-controls="second" aria-selected="false">Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="third-tab" data-toggle="tab" href="#third" role="tab"
                            aria-controls="third" aria-selected="false">Rooms</a>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- Children Tab --}}
                    <div class="tab-pane show  active" id="first" role="tabpanel" aria-labelledby="first-tab">
                        <div class="select-all-box" id="select-all-box">
                            <input type="checkbox" id="select-all-child">
                            <label for="select-all-child" id="select-all-child-label">Select All</label>
                        </div>
                        <table class="list-table table table-condensed">
                            @foreach ($Childrens as $child)
                            <tr>
                                <td>
                                    <input type="checkbox" class="common-child child-tab unique-tag" name="child[]"
                                        id="child_{{ $child->childid }}" value="{{ $child->childid }}"
                                        data-name="{{ $child->name . ' - ' . $child->age }}" {{ $child->checked }}>
                                </td>
                                <td>
                                    <label for="child_{{ $child->childid }}">
                                        <img src="{{ public_path($child->imageUrl) }}"
                                            class="img-thumbnail border-0 rounded-circle list-thumbnail align-self-center xsmall">
                                        {{ $child->name . ' - ' . $child->age }}
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>

                    {{-- Groups Tab --}}
                    <div class="tab-pane show" id="second" role="tabpanel" aria-labelledby="second-tab">
                        @foreach ($Groups as $group)
                        <div class="select-all-box">
                            <input type="checkbox" id="select-group-child-{{ $group->groupid }}"
                                class="select-group-child" data-groupid="{{ $group->groupid }}">
                            <label for="select-group-child-{{ $group->groupid }}">{{ $group->name }}</label>
                        </div>
                        <table class="list-table table table-condensed">
                            @foreach ($group->Childrens as $child)
                            <tr>
                                <td>
                                    <input type="checkbox" class="common-child child-group" name="child[]"
                                        data-groupid="{{ $group->groupid }}" id="child_{{ $child->childid }}"
                                        value="{{ $child->childid }}" {{ $child->checked }}>
                                </td>
                                <td>
                                    <label for="child_{{ $child->childid }}">
                                        <img src="{{ public_path($child->imageUrl) }}"
                                            class="img-thumbnail border-0 rounded-circle list-thumbnail align-self-center xsmall">
                                        {{ $child->name . ' - ' . $child->age }}
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endforeach
                    </div>

                    {{-- Rooms Tab --}}
                    <div class="tab-pane show" id="third" role="tabpanel" aria-labelledby="third-tab">
                        @foreach ($Rooms as $room)
                        <div class="select-all-box">
                            <input type="checkbox" class="select-room-child" id="select-room-child-{{ $room->roomid }}"
                                data-roomid="{{ $room->roomid }}">
                            <label for="select-room-child-{{ $room->roomid }}">{{ $room->name }}</label>
                        </div>
                        <table class="list-table table table-condensed">

                            @foreach ($room->Childrens as $child)
                            <tr>
                                <td>
                                    <input type="checkbox" class="common-child child-room" name="child[]"
                                        data-roomid="{{ $room->roomid }}" id="child_{{ $child->childid }}"
                                        value="{{ $child->childid }}" {{ $child->checked }}>
                                </td>
                                <td>
                                    <label for="child_{{ $child->childid }}">
                                        <img src="{{ public_path($child->imageUrl) }}"
                                            class="img-thumbnail border-0 rounded-circle list-thumbnail align-self-center xsmall">
                                        {{ $child->name . ' - ' . $child->age }}
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="insert-childtags" data-dismiss="modal">Submit</button>
            </div>

        </div>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('status') == 'success')
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('msg') }}",
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('status') == 'error')
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('msg') ?? 'Something went wrong!' }}",
    });
</script>
@endif


<script>
    $(document).ready(function() {
        $('.edit-holiday-btn').click(function() {
            let id = $(this).data('id');
            let date = $(this).data('date');
            let occasion = $(this).data('occasion');
            let state = $(this).data('state');
            let status = $(this).data('status');

            // fill modal fields
            $('#editDate').val(date);
            $('#editOccasion').val(occasion);
            $('#editState').val(state);

            if (status == 1) {
                $('#editStatusActive').prop('checked', true);
            } else {
                $('#editStatusInactive').prop('checked', true);
            }

            // set form action
            $('#holidayEditForm').attr('action', '/settings/holiday/update/' + id);

            // open modal
            $('#holidayEditModal').modal('show');
        });
    });
</script>


<script>
    $(document).ready(function() {
        // Edit modal fill
        $('.edit-holiday-btn').click(function() {
            const id = $(this).data('id');
            const date = $(this).data('date');
            const state = $(this).data('state');
            const occasion = $(this).data('occasion');
            const status = $(this).data('status');
            const action = $(this).data('action');

            $('#holidayDate').val(date);
            $('#holidayState').val(state);
            $('#holidayOccasion').val(occasion);
            $("input[name='status'][value='" + status + "']").prop('checked', true);

            $('#holidayForm').attr('action', action);
            $('#holidayModalLabel').text('Edit Public Holiday');
            $('#holidaySaveBtn').text('Update');

            // $('#holidayModal').modal('show');
        });

        // Reset modal
        $('#holidayModal').on('hidden.bs.modal', function() {
            $('#holidayForm').attr('action', "{{ route('settings.holiday.store') }}");
            $('#holidayForm')[0].reset();
            $('#holidayModalLabel').text('Add New Public Holiday');
            $('#holidaySaveBtn').text('Save Holiday');
        });


        $('#eventType').on('change', function() {
            const eventType = $(this).val(); // ✅ fixed
            const edit_holidays = $('.edit-holidays');
            edit_holidays.html(''); // ✅ reset container

            let html = '';

            if (eventType === 'events') {
                alert();
                let eventtitle = $('#holidayOccasion').val() || '';
                let date = $('#editDate').val() || '';

                html = `
            <input type="hidden" name="type" value="events">

            <div class="mb-3">
                <label for="eventDate" style="font-weight: bold;">Date</label>
                <input type="date" class="form-control" name="date" value="${date}" id="eventDate" required>
            </div>

            <div class="mb-3">
                <label for="eventTitle" style="font-weight: bold;">Title</label>
                <input type="text" class="form-control" name="occasion" id="eventTitle" value="${eventtitle}" required>
            </div>

            <div class="mb-3">
                <label for="eventDescription" style="font-weight: bold;">Description</label>
                <input type="text" class="form-control" name="description" id="eventDescription" required>
            </div>

            <div class="mb-3">
                <label for="audience" class="form-label fw-bold">👥 Access</label>
                <div class="">
                    <select class="form-select form-control" name="audience" required>
                        <option value="all" {{ isset($announcement) && $announcement->audience == 'all' ? 'selected' : '' }}>All</option>
                        <option value="parents" {{ isset($announcement) && $announcement->audience == 'parents' ? 'selected' : '' }}>Parents</option>
                        <option value="staff" {{ isset($announcement) && $announcement->audience == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
            </div>

       <div class="form-group media-div">
    <h4>Media Upload Section</h4>
    <div class="media-upload-box p-4 border rounded bg-light text-center">
        <label for="mediaInputUpload1" class="btn btn-outline-info">
            Select Image (png, jpeg, jpg) or PDF
        </label>
        <input type="file" id="mediaInputUpload1" name="mediaUpload1[]" class="d-none accept="image/*,application/pdf">
        <small class="form-text text-muted mt-2">Only image and PDF allowed, up to 2MB</small>
    </div>
    <div id="mediaPreviewUpload1" class="row mt-4"></div>
</div>

                 <div class="form-group select-children">
                                        <button type="button" class="btn btn-info mb-1" data-toggle="modal"
                                            data-backdrop="static" data-target="#selectChildrenModal">+ Add
                                            Children</button>
                                    </div>
            <div class="children-tags">
                @forelse ($announcement->children ?? [] as $child)
                    <a href="#!" class="rem" data-role="remove" data-child="{{ $child->id }}">
                        <input type="hidden" name="childId[]" value="{{ $child->id }}">
                        <span class="badge badge-pill badge-outline-info mb-1">{{ $child->name }} ✖</span>
                    </a>
                @empty
                    <p>No children selected</p>
                @endforelse
            </div>
        `;

            } else if (eventType === 'announcement') { // ✅ corrected spelling
                html = `
            <input type="hidden" name="type" value="announcement">

            <div class="mb-3">
                <label for="announcementDate" style="font-weight: bold;">Date</label>
                <input type="date" class="form-control" name="date" id="announcementDate" required>
            </div>

            <div class="mb-3">
                <label for="announcementOccasion" style="font-weight: bold;">Occasion</label>
                <input type="text" class="form-control" name="occasion" id="announcementOccasion" required>
            </div>

            <div class="mb-3">
                <label for="announcementState" style="font-weight: bold;">State</label>
                <input type="text" class="form-control" name="state" id="announcementState" required>
            </div>

            <div class="mb-3">
                <label style="font-weight: bold;">Status</label><br>
                <label><input type="radio" name="status" value="1" id="announcementStatusActive"> Active</label>
                <label style="margin-left: 20px;"><input type="radio" name="status" value="0" id="announcementStatusInactive"> Inactive</label>
            </div>
        `;
            }

            edit_holidays.html(html);
        });

    });

    
</script>


<script>
    $(document).ready(function() {
        $('#filter-child').on('keyup', function() {
            const searchValue = $(this).val().toLowerCase();

            $('.tab-pane').each(function() {
                let tabHasVisibleChildren = false;

                // Loop each select-all box and its table
                $(this).find('.select-all-box').each(function() {
                    const $selectAllBox = $(this);
                    const $table = $selectAllBox.next('table');
                    let visibleRows = 0;

                    // Filter rows based on name or age
                    $table.find('tr').each(function() {
                        const label = $(this).find('label').text().toLowerCase();
                        const isMatch = label.includes(searchValue);
                        $(this).toggle(isMatch);
                        if (isMatch) visibleRows++;
                    });

                    // Show or hide select-all and table
                    if (visibleRows > 0) {
                        $selectAllBox.show();
                        $table.show();
                        tabHasVisibleChildren = true;
                    } else {
                        $selectAllBox.hide();
                        $table.hide();
                    }
                });

                // Hide the entire tab-pane if no visible children in any section
                $(this).toggle(tabHasVisibleChildren);
            });
        });
    });
</script>





<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#about'), {
                toolbar: [
                    'undo', 'redo', '|',
                    'bold', 'italic', 'strikethrough', '|',
                    'numberedList', 'bulletedList', '|',
                    'link'
                ]
            })
            .then(editor => {
                console.log('CKEditor 5 initialized');
            })
            .catch(error => {
                console.error('CKEditor 5 initialization failed:', error);
            });
    });

    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#aboutevent'), {
                toolbar: [
                    'undo', 'redo', '|',
                    'bold', 'italic', 'strikethrough', '|',
                    'numberedList', 'bulletedList', '|',
                    'link'
                ]
            })
            .then(editor => {
                console.log('CKEditor 5 initialized');
            })
            .catch(error => {
                console.error('CKEditor 5 initialization failed:', error);
            });
    });


    $(document).off('click', '.nav-link').on('click', 'select-children', function(e) {
        e.preventDefault();
        $(this).modal('hide'); // Manually trigger Bootstrap tab
    });




    $(document).ready(function() {


        var date = new Date();
        date.setDate(date.getDate());

        $('.calendar').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate: date,
            templates: {
                leftArrow: '<i class="simple-icon-arrow-left"></i>',
                rightArrow: '<i class="simple-icon-arrow-right"></i>'
            }
        });





        $(document).on('click', "#select-all-child", function() {
            //check if this checkbox is checked or not
            if ($(this).is(':checked')) {
                // alert();
                //check all children
                var _childid = $('input.common-child');
                $(_childid).prop('checked', true);
                $(".select-group-child").prop('checked', true);
                $(".select-room-child").prop('checked', true);
            } else {
                //uncheck all children
                var _childid = $('input.common-child');
                $(_childid).prop('checked', false);
                $(".select-group-child").prop('checked', false);
                $(".select-room-child").prop('checked', false);
            }
        });

        var _totalchilds = '<?= count($Childrens); ?>';

        $(document).on('click', '.common-child', function() {
            var _value = $(this).val();
            if ($(this).is(':checked')) {
                $('input.common-child[value="' + _value + '"]').prop('checked', true);
                $('input.child-group[value="' + _value + '"]').trigger('change');
                $('input.child-room[value="' + _value + '"]').trigger('change');

            } else {
                $('input.common-child[value="' + _value + '"]').prop('checked', false);
                $('input.child-group[value="' + _value + '"]').trigger('change');
                $('input.child-room[value="' + _value + '"]').trigger('change');
            }

            var _totalChildChecked = $('.child-tab:checked').length;
            if (_totalChildChecked == _totalchilds) {
                $("#select-all-child").prop('checked', true);
            } else {
                $("#select-all-child").prop('checked', false);
            }
        });

        $(document).on("click", ".select-group-child", function() {
            var _groupid = $(this).data('groupid');
            var _selector = $('input.common-child[data-groupid="' + _groupid + '"]');

            if ($(this).is(':checked')) {
                // $(_selector).prop('checked', true);
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', true);
                });
            } else {
                // $(_selector).prop('checked', false);
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', false);
                });
            }

            var _totalChildChecked = $('.child-tab:checked').length;
            if (_totalChildChecked == _totalchilds) {
                $("#select-all-child").prop('checked', true);
            } else {
                $("#select-all-child").prop('checked', false);
            }
        });

        $(document).on("change", ".child-group", function() {
            var _groupid = $(this).data('groupid');
            var _selector = '#select-group-child-' + _groupid;
            var _totalGroupChilds = $('.child-group[data-groupid="' + _groupid + '"]').length;
            var _totalGroupChildsChecked = $('.child-group[data-groupid="' + _groupid + '"]:checked').length;
            if (_totalGroupChilds == _totalGroupChildsChecked) {
                $(_selector).prop('checked', true);
            } else {
                $(_selector).prop('checked', false);
            }
        });

        $(document).on("click", ".select-room-child", function() {
            var _roomid = $(this).data('roomid');
            var _selector = $('input.common-child[data-roomid="' + _roomid + '"]');

            if ($(this).is(':checked')) {
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', true);
                });
            } else {
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', false);
                });
            }

            var _totalChildChecked = $('.child-tab:checked').length;
            if (_totalChildChecked == _totalchilds) {
                $("#select-all-child").prop('checked', true);
            } else {
                $("#select-all-child").prop('checked', false);
            }
        });

        $(document).on("change", ".child-room", function() {
            var _roomid = $(this).data('roomid');
            var _selector = '#select-room-child-' + _roomid;
            var _totalRoomChilds = $('.child-room[data-roomid="' + _roomid + '"]').length;
            var _totalRoomChildsChecked = $('.child-room[data-roomid="' + _roomid + '"]:checked').length;
            if (_totalRoomChilds == _totalRoomChildsChecked) {
                $(_selector).prop('checked', true);
            } else {
                $(_selector).prop('checked', false);
            }
        });

        $(document).on("click", "#insert-childtags", function() {
            $('.children-tags').empty();
            $('.unique-tag:checked').each(function(index, val) {
                $('.children-tags').append(`
                        <a href="#!" class="rem" data-role="remove" data-child="` + $(this).val() + `">
                            <input type="hidden" name="childId[]" value="` + $(this).val() + `">
                            <span class="badge badge-pill badge-outline-primary mb-1">` + $(this).data('name') + ` X </span>
                        </a>
                    `);
            });
            $(".children-tags").show();
        });

        $(document).on('click', '.rem', function() {
            var _childid = $(this).data('child');
            $(".common-child[value='" + _childid + "']").trigger('click');
            $(this).remove();
        });
    });
</script>


<!-- image preview  -->
<script>
   let mediaFilesUpload1 = []; // store uploaded files

// Handle file selection (delegated because input is dynamic)
$(document).on("change", "#mediaInputUpload1", function (e) {
    const mediaInputUpload1 = this;
    const mediaPreviewUpload1 = document.getElementById("mediaPreviewUpload1");

    const newFiles = Array.from(e.target.files);

    // Restrict only 1 file
    if (newFiles.length > 1 || mediaFilesUpload1.length >= 1) {
        this.value = "";
        return;
    }

    const file = newFiles[0];

    // Validate size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        this.value = "";
        alert("File must be under 2MB.");
        return;
    }

    mediaPreviewUpload1.innerHTML = ""; // reset preview
    const wrapper = document.createElement("div");
    wrapper.className = "col-md-4 position-relative mb-3";

    let previewHtml = "";
    const fileURL = URL.createObjectURL(file);

    if (file.type.startsWith("image/")) {
        previewHtml = `<img src="${fileURL}" class="media-thumb rounded w-100" alt="Image">`;
    } else if (file.type === "application/pdf") {
        previewHtml = `<embed src="${fileURL}" type="application/pdf" class="media-thumb rounded w-100" height="200px"/>`;
    } else {
        previewHtml = `<div class="alert alert-warning">Unsupported file type</div>`;
    }

    wrapper.innerHTML = `
        <div class="position-relative">
            ${previewHtml}
            <button type="button" 
                    class="btn btn-danger btn-sm remove-file position-absolute top-0 end-0 m-1">
                ✕
            </button>
        </div>
    `;

    mediaPreviewUpload1.appendChild(wrapper);

    mediaFilesUpload1 = [file]; // keep only one
    syncMediaInputUpload1(mediaInputUpload1);
});

// Remove handler
$(document).on("click", ".remove-file", function () {
    mediaFilesUpload1 = [];
    $("#mediaInputUpload1").val("");
    $("#mediaPreviewUpload1").html("");
});

// Sync selected files back to input
function syncMediaInputUpload1(inputEl) {
    const dt = new DataTransfer();
    mediaFilesUpload1.forEach((file) => dt.items.add(file));
    inputEl.files = dt.files;
}


// When child modal closes, restore parent modal state
// when clicking "Add Children"


// When child modal closes, restore parent modal scroll
$('#selectChildrenModal').on('hidden.bs.modal', function () {
    if ($('#holidayEditModal').hasClass('show')) {
        $('body').addClass('modal-open'); // restore scroll lock
    }
});



// document.addEventListener("DOMContentLoaded", () => {
//     const table = document.getElementById("holidayTable");
//     const headers = table.querySelectorAll("th.sortable");

//     headers.forEach(header => {
//         header.addEventListener("click", () => {
//             const columnIndex = header.getAttribute("data-col");
//             const tbody = table.querySelector("tbody");
//             const rows = Array.from(tbody.querySelectorAll("tr"));

//             // Determine current sort order
//             const isAsc = header.classList.contains("asc");
//             const isDesc = header.classList.contains("desc");

//             // Reset all headers
//             headers.forEach(h => h.classList.remove("asc", "desc"));

//             // Toggle direction
//             if (!isAsc && !isDesc) {
//                 header.classList.add("asc");
//             } else if (isAsc) {
//                 header.classList.remove("asc");
//                 header.classList.add("desc");
//             } else {
//                 header.classList.remove("desc");
//                 header.classList.add("asc");
//             }

//             const newIsAsc = header.classList.contains("asc");

//             // Sort rows
//             rows.sort((a, b) => {
//                 const cellA = a.querySelectorAll("td")[columnIndex].innerText.trim();
//                 const cellB = b.querySelectorAll("td")[columnIndex].innerText.trim();

//                 // Handle numbers separately
//                 if (!isNaN(cellA) && !isNaN(cellB)) {
//                     return newIsAsc ? cellA - cellB : cellB - cellA;
//                 }

//                 // Handle dates (if format looks like "12 Sep 2025")
//                 if (Date.parse(cellA) && Date.parse(cellB)) {
//                     return newIsAsc
//                         ? new Date(cellA) - new Date(cellB)
//                         : new Date(cellB) - new Date(cellA);
//                 }

//                 // Default string comparison
//                 return newIsAsc
//                     ? cellA.localeCompare(cellB)
//                     : cellB.localeCompare(cellA);
//             });

//             // Append sorted rows back
//             rows.forEach(row => tbody.appendChild(row));
//         });
//     });
// });


</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("holidayTable");
    const headers = table.querySelectorAll("th.sortable");

    headers.forEach(header => {
        header.addEventListener("click", () => {
            const columnIndex = header.getAttribute("data-col");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            // Determine current sort order
            const isAsc = header.classList.contains("asc");
            const isDesc = header.classList.contains("desc");

            // Reset all headers and icons
            headers.forEach(h => {
                h.classList.remove("asc", "desc");
                const icon = h.querySelector("i");
                if (icon) icon.className = "fas fa-sort";
            });

            // Toggle direction
            if (!isAsc && !isDesc) {
                header.classList.add("asc");
            } else if (isAsc) {
                header.classList.remove("asc");
                header.classList.add("desc");
            } else {
                header.classList.remove("desc");
                header.classList.add("asc");
            }

            const newIsAsc = header.classList.contains("asc");

            // Update clicked header icon
            const icon = header.querySelector("i");
            if (icon) {
                icon.className = newIsAsc ? "fas fa-sort-up" : "fas fa-sort-down";
            }

            // Sort rows
            rows.sort((a, b) => {
                const cellA = a.querySelectorAll("td")[columnIndex].innerText.trim();
                const cellB = b.querySelectorAll("td")[columnIndex].innerText.trim();

                // Handle numbers
                if (!isNaN(cellA) && !isNaN(cellB)) {
                    return newIsAsc ? cellA - cellB : cellB - cellA;
                }

                // Handle dates (format "12 Sep 2025")
                if (Date.parse(cellA) && Date.parse(cellB)) {
                    return newIsAsc
                        ? new Date(cellA) - new Date(cellB)
                        : new Date(cellB) - new Date(cellA);
                }

                // Default string comparison
                return newIsAsc
                    ? cellA.localeCompare(cellB)
                    : cellB.localeCompare(cellA);
            });

            // Append sorted rows back
            rows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>



@stop