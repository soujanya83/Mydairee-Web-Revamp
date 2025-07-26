@extends('layout.master')
@section('title', 'Observation')
@section('parentPageTitle', '')

<style>
    .pagination {
        font-size: 0.9rem;
        /* Slightly larger for better readability */
        justify-content: center;
        /* Ensure pagination is centered */
        margin-bottom: 80px;
    }

    .page-item .page-link {
        padding: 0.5rem 0.75rem;
        /* Bootstrap 4 default padding for better spacing */
        font-size: 0.9rem;
        /* Match pagination font size */
        line-height: 1.5;
        /* Improved line height for readability */
        border-radius: 0.25rem;
        /* Keep your custom border radius */
        color: #007bff;
        /* Bootstrap primary color for links */
        background-color: #fff;
        /* Ensure background matches Bootstrap */
        border: 1px solid #dee2e6;
        /* Bootstrap default border */
    }

    .page-item.active .page-link {
        background-color: #007bff;
        /* Bootstrap primary color for active state */
        border-color: #007bff;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        /* Bootstrap disabled color */
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }

    /* SVG icons for Previous/Next arrows */
    .page-item .page-link svg {
        width: 1em;
        /* Slightly larger for better visibility */
        height: 1em;
        vertical-align: middle;
    }
</style>


<style>
    .list-thumbnail {
        height: 150px !important;
        width: 200px !important;
    }

    .obs-link {
        color: #008ecc;
    }

    .obs-link:hover {
        color: #000000;
    }

    .br-10 {
        border-radius: 10px;
    }



    @media (max-width: 575px) {
        .top-right-button-container {
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .filterbutton {
            width: 100% !important;
            ;
        }
    }
</style>
<style>
    .list-thumbnail {
        max-width: 200px;
        height: 75px;
        object-fit: cover;
        border-radius: 4px;
    }

    .checkbox input[type="checkbox"] {
        margin-top: 0;
    }

    #observationsList .checkbox:hover {
        background-color: #f8f9fa;
    }

    .icon-actions {
        min-width: 60px;
        /* Width of right icons area */
        text-align: center;
    }

    .icon-actions i {
        font-size: 22px;
        margin-bottom: 30px;
    }

    .list-thumbnail {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>


<style>
    .modal-right {
        position: fixed;
        margin: auto;
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal-right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal-right .modal-content {
        height: 100%;
        overflow-y: auto;
        border-radius: 0px;
    }

    .modal-right.fade .modal-dialog {
        right: -320px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }

    .modal-right.fade.show .modal-dialog {
        right: 0;
    }
</style>


<!-- Bootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

@section('content')





<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;">


    @if(Auth::user()->userType != 'Parent')
    <!-- Filter Button -->
    <button class="btn btn-outline-primary btn-lg mr-1 filterbutton" data-toggle="modal" data-backdrop="static"
        data-target="#filtersModal">
        <i class="fa-solid fa-filter" style="margin-right: 5px;"></i> FILTERS
    </button>
    &nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-outline-info"
        onclick="window.location.href='{{ route('observation.addnew') }}'"><i class="icon-plus"
            style="margin-right: 5px;"></i>Add New</button>
    @endif &nbsp;&nbsp;&nbsp;


    <div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="centerDropdown"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-brands fa-centercode" style="margin-right: 5px;"></i>{{ $centers->firstWhere('id',
            session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
            style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
            <a href="javascript:void(0);"
                class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                style="background-color:white;" data-id="{{ $center->id }}">
                {{ $center->centerName }}
            </a>
            @endforeach
        </div>
    </div>
</div>


<div class="row" id="observations-list" style="margin-top:20px;">

    @forelse($observations as $observation)
    @php
    $obsId = $observation->id;
    @endphp

    <div class="col-lg-6 col-md-3">
        <div class="d-flex flex-row mb-3 bg-white br-10 align-items-center justify-content-between p-3 card">

            <!-- LEFT SIDE: Image + Content -->
            <div class="d-flex flex-row align-items-center">

                @if(Auth::user()->userType != 'Parent')
                <a class="d-block position-relative" href="{{ route('observation.view', ['id' => $obsId]) }}">
                    @else
                    <a class="d-block position-relative" href="{{ route('observation.print', $obsId) }}"
                        target="_blank">
                        @endif

                        <!-- Image Part -->
                        @if($observation->media->isEmpty())
                        <img src="https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg"
                            alt="No Media" class="list-thumbnail border-0"
                            style="width:100px;height:100px;object-fit:cover;">
                        @else
                        @php
                        $firstMedia = $observation->media->first(); // Get first media item
                        @endphp
                        @if(file_exists(public_path($firstMedia->mediaUrl)))
                        <img src="{{ asset($firstMedia->mediaUrl) }}" alt="Image" class="list-thumbnail border-0"
                            style="width:100px;height:100px;object-fit:cover;">
                        @else
                        <img src="https://via.placeholder.com/320x240?text=Media+Deleted" alt="Image"
                            class="list-thumbnail border-0" style="width:100px;height:100px;object-fit:cover;">
                        @endif

                        @endif

                        @if($observation->status == 'Published')
                        <span class="badge badge-pill position-absolute badge-top-right badge-success"
                            style="top:8px;right: -7px;">PUBLISHED</span>
                        @else
                        <span class="badge badge-pill position-absolute badge-top-right badge-danger"
                            style="top:8px;right: -7px;">DRAFT</span>
                        @endif

                    </a>

                    <!-- Title and Details -->
                    <div class="pl-3">
                        @if(Auth::user()->userType != 'Parent')
                        <a href="{{ route('observation.view', ['id' => $obsId]) }}" class="obs-link">
                            @else
                            <a href="{{ route('observation.print', $obsId) }}" class="obs-link" target="_blank">
                                @endif

                                <p class="list-item-heading mb-1">
                                    @if(!empty($observation->obestitle))
                                    {{ strip_tags($observation->obestitle) }}
                                    @else
                                    {{ Str::limit(strip_tags(html_entity_decode($observation->title)), 40, '...') }}
                                    @endif
                                </p>
                            </a>

                            <p class="text-muted mb-1 text-small">
                                By: {{ $observation->user->name ?? 'Unknown' }}
                            </p>

                            <p class="text-primary text-small font-weight-medium mb-0">
                                {{ \Carbon\Carbon::parse($observation->created_at)->format('d.m.Y') }}
                            </p>

                            @if(Auth::user()->userType != 'Parent' && $observation->Seen->isNotEmpty())
                            <p><strong>Seen by Parents:</strong></p>
                            <ul style="max-height:60px;overflow-y:auto;">
                                @forelse($observation->Seen as $seen)

                                {{-- @php
                                $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg',
                                'avatar10.jpg'];
                                $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg',
                                'avatar7.jpg'];
                                $avatars = $seen->user->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
                                $defaultAvatar = $avatars[array_rand($avatars)];
                                @endphp --}}

                                @php
                                $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg',
                                'avatar10.jpg'];
                                $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg',
                                'avatar7.jpg'];

                                if ($seen->user && $seen->user->gender === 'FEMALE') {
                                $avatars = $femaleAvatars;
                                } else {
                                $avatars = $maleAvatars;
                                }

                                $defaultAvatar = $avatars[array_rand($avatars)];
                                @endphp


                                @if($seen->user && $seen->user->userType === 'Parent')
                                <li style="margin-bottom: 10px;">
                                    <img src="{{ $seen->user->imageUrl  ? asset($seen->user->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}"
                                        alt="Profile Image" width="40" height="40" style="border-radius: 50%;">
                                    {{ $seen->user->name }} <span style="color: #2196F3;">&#10003;&#10003;</span>
                                </li>
                                @endif
                                @empty
                                <li>No parent has seen this yet.</li>
                                @endforelse
                            </ul>
                            @endif

                    </div>



            </div>

            <!-- RIGHT SIDE: Icons (Print/Delete/Comment) -->
            <div class="d-flex flex-column align-items-center icon-actions">
                @if(Auth::user()->userType != 'Parent')
                <a href="{{ route('observation.print', $obsId) }}" target="_blank" class="mb-2">
                    <i class="fa-solid fa-print fa-lg" style="color: #74C0FC;"></i>
                </a>
                <i class="fa-sharp fa-solid fa-trash fa-lg" style="color: #da0711; cursor: pointer;"
                    onclick="deleteObservation({{ $obsId }})"></i>
                @else
                <i class="fa-solid fa-comment fa-bounce fa-sm" style="color: #74C0FC; cursor: pointer;"
                    onclick="openAddCommentModal({{ $obsId }})"></i>
                @endif
            </div>
        </div>
    </div>

    @empty
    <div class="col">
        <div class="text-center">
            <h6 class="mb-4">You don't have any Observations, Create New Observations.....</h6>
            <!-- <p class="mb-0 text-muted text-small mb-0">Error code</p> -->
            <!-- <p class="display-1 font-weight-bold mb-5"> -->
            <!-- 200 -->
            <!-- </p> -->
            <a href="{{ route('dashboard.university') }}" class="btn btn-info btn-lg btn-shadow"> <i
                    class="fa-solid fa-home fa-lg fa-beat" style="color: #74C0FC;"></i>&nbsp; GO BACK
                HOME</a>
        </div>
    </div>
    @endforelse

    @if ($observations->hasPages())
    <div class="col-12 d-flex justify-content-center mt-4">
        {{ $observations->links('vendor.pagination.bootstrap-4') }}
    </div>
    @endif


</div>






<!-- Filters Modal -->
<div class="modal fade modal-right" id="filtersModal" tabindex="-1" role="dialog" aria-labelledby="filtersModalRight"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filters</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="accordion">
                        <!-- Status Filter -->
                        <div class="border">
                            <button class="btn btn-link dropdown-toggle" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Status
                            </button>
                            <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_all" name="obs_status_filter"
                                            class="custom-control-input filter_observation" value="All" checked>
                                        <label class="custom-control-label" for="status_all">All</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_draft" name="obs_status_filter"
                                            class="custom-control-input filter_observation" value="Draft">
                                        <label class="custom-control-label" for="status_draft">Draft</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_published" name="obs_status_filter"
                                            class="custom-control-input filter_observation" value="Published">
                                        <label class="custom-control-label" for="status_published">Published</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Added Filter -->
                        <div class="border">
                            <button class="btn btn-link dropdown-toggle collapsed" data-toggle="collapse"
                                data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Added
                            </button>
                            <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_none" name="filter_added"
                                            class="custom-control-input filter_added" value="None" checked>
                                        <label class="custom-control-label" for="added_none">None</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_today" name="filter_added"
                                            class="custom-control-input filter_added" value="Today">
                                        <label class="custom-control-label" for="added_today">Today</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_this_week" name="filter_added"
                                            class="custom-control-input filter_added" value="This Week">
                                        <label class="custom-control-label" for="added_this_week">This Week</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_this_month" name="filter_added"
                                            class="custom-control-input filter_added" value="This Month">
                                        <label class="custom-control-label" for="added_this_month">This Month</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_custom" name="filter_added"
                                            class="custom-control-input filter_added" value="Custom">
                                        <label class="custom-control-label" for="added_custom">Custom Date</label>
                                    </div>
                                    <div id="custom_date_range" style="display:none; margin-top: 10px;">
                                        <input type="date" id="from_date" class="form-control mb-2"
                                            placeholder="From Date">
                                        <input type="date" id="to_date" class="form-control" placeholder="To Date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Child Filter -->
                        <div class="border">
                            <button class="btn btn-link dropdown-toggle collapsed" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Child
                            </button>
                            <div id="collapseThree" class="collapse" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" class="custom-control-input filter_child"
                                            id="filter_child_selectall" value="All">
                                        <label class="custom-control-label" for="filter_child_selectall">Select
                                            All</label>
                                    </div>
                                    <input type="text" id="childSearchInput" class="form-control mb-3"
                                        placeholder="Search child...">
                                    <!-- Dynamic child checkboxes will be loaded here -->
                                    <div id="child-checkboxes">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Author Filter -->
                        <div class="border">
                            <button class="btn btn-link dropdown-toggle collapsed" data-toggle="collapse"
                                data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Author
                            </button>
                            <div id="collapseFour" class="collapse" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input filter_author"
                                            id="filter_author_any" value="Any" checked>
                                        <label class="custom-control-label" for="filter_author_any">Any</label>
                                    </div>
                                    <hr>
                                    <div class="custom-control custom-checkbox">

                                        <input type="checkbox" class="custom-control-input filter_author"
                                            id="filter_author_me" value="Me">
                                        <label class="custom-control-label" for="filter_author_me">Me</label>
                                    </div>

                                    @if(Auth::user()->userType == 'Superadmin')

                                    <hr>

                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" class="custom-control-input filter_staff"
                                            id="filter_staff_selectall" value="All">
                                        <label class="custom-control-label" for="filter_staff_selectall">Select
                                            All</label>
                                    </div>


                                    <input type="text" id="staffSearchInput" class="form-control mb-3"
                                        placeholder="Search staff...">


                                    <div id="staff-checkboxes">

                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-primary" id="btn-apply-filters">Apply Filters</button>
                <button class="btn btn-secondary" id="btn-clear-filters">Clear Filters</button>
            </div>
        </div>
    </div>
</div>

<!-- Observations List Container -->
<div class="container mt-4">
    <div class="row" id="observations-list">
        <!-- Filtered observations will be loaded here -->
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script> -->

<script>
    $(document).ready(function() {
    // Set CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Custom date range toggle
    $('input[name="filter_added"]').change(function() {
        if ($(this).val() == 'Custom') {
            $('#custom_date_range').show();
        } else {
            $('#custom_date_range').hide();
            $('#from_date').val('');
            $('#to_date').val('');
        }
    });

    // Select All children functionality
    $('#filter_child_selectall').change(function() {
        if ($(this).is(':checked')) {
            $('.filter_child:not(#filter_child_selectall)').prop('checked', true);
        } else {
            $('.filter_child:not(#filter_child_selectall)').prop('checked', false);
        }
    });

    // Individual child checkbox change
    $(document).on('change', '.filter_child:not(#filter_child_selectall)', function() {
        var totalChildCheckboxes = $('.filter_child:not(#filter_child_selectall)').length;
        var checkedChildCheckboxes = $('.filter_child:not(#filter_child_selectall):checked').length;

        if (checkedChildCheckboxes === totalChildCheckboxes) {
            $('#filter_child_selectall').prop('checked', true);
        } else {
            $('#filter_child_selectall').prop('checked', false);
        }
    });



    $('#filter_staff_selectall').change(function() {
        if ($(this).is(':checked')) {
            $('.filter_staff:not(#filter_staff_selectall)').prop('checked', true);
        } else {
            $('.filter_staff:not(#filter_staff_selectall)').prop('checked', false);
        }
    });

    // Individual child checkbox change
    $(document).on('change', '.filter_staff:not(#filter_staff_selectall)', function() {
        var totalChildCheckboxes = $('.filter_staff:not(#filter_staff_selectall)').length;
        var checkedChildCheckboxes = $('.filter_staff:not(#filter_staff_selectall):checked').length;

        if (checkedChildCheckboxes === totalChildCheckboxes) {
            $('#filter_staff_selectall').prop('checked', true);
        } else {
            $('#filter_staff_selectall').prop('checked', false);
        }
    });


    // Main filter function
    function applyFilters() {
        // Get selected child IDs
        var childs = getSelectedChildIds();

        function getSelectedChildIds() {
            var childs = [];
            var hasSelectAll = false;

            $('.filter_child').each(function() {
                if ($(this).prop("checked") == true) {
                    if ($(this).val() == 'All') {
                        hasSelectAll = true;
                    } else {
                        if (childs.indexOf($(this).val()) === -1) {
                            childs.push($(this).val());
                        }
                    }
                }
            });

            // If "Select All" is checked, get all child IDs
            if (hasSelectAll) {
                childs = [];
                $('.filter_child:not(#filter_child_selectall)').each(function() {
                    if (childs.indexOf($(this).val()) === -1) {
                        childs.push($(this).val());
                    }
                });
            }

            return childs;
        }

        // Get selected authors


        var staffs = getSelectedStaffIds();

function getSelectedStaffIds() {
    var staffs = [];
    var hasSelectAll = false;

    $('.filter_staff').each(function () {
        if ($(this).prop("checked") == true) {
            if ($(this).val() == 'All') {
                hasSelectAll = true;
            } else {
                if (staffs.indexOf($(this).val()) === -1) {
                    staffs.push($(this).val());
                }
            }
        }
    });

    // If "Select All" is checked, get all staff IDs
    if (hasSelectAll) {
        staffs = [];
        $('.filter_staff:not(#filter_staff_selectall)').each(function () {
            if (staffs.indexOf($(this).val()) === -1) {
                staffs.push($(this).val());
            }
        });
    }

    return staffs;
}

var authors = [];
// If "Any" or "Me" is checked, push it
if ($('#filter_author_any').is(':checked')) {
    authors.push('Any');
} else if ($('#filter_author_me').is(':checked')) {
    authors.push('Me');
} else {
    // If neither "Any" nor "Me" is checked, treat selected staff as authors
    authors = staffs;
}

        // Get selected observation status
        var observations = [];
        $('.filter_observation').each(function() {
            if ($(this).prop("checked") == true) {
                observations.push($(this).val());
            }
        });

        // Get selected date range
        var added = [];
        var fromDate = '';
        var toDate = '';

        $('.filter_added').each(function() {
            if ($(this).prop("checked") == true) {
                let val = $(this).val();
                if (val == 'None') {
                    added = [];
                    return false;
                } else {
                    added.push(val);
                    if (val === 'Custom') {
                        fromDate = $('#from_date').val();
                        toDate = $('#to_date').val();
                    }
                }
            }
        });

        // AJAX request
        $.ajax({
            type: 'POST',
            url: '/observation/filters', // Laravel route
            data: {
                childs: childs,
                authors: authors,
                observations: observations,
                added: added,
                fromDate: fromDate,
                toDate: toDate,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#observations-list').empty();

                    if (response.observations.length === 0) {
                        $('#observations-list').append(`
                            <div class="col">
                                <div class="text-center">
                                    <h6 class="mb-4">No observations found matching your filters.</h6>
                                    <button class="btn btn-info btn-lg btn-shadow" id="btn-clear-filters-inline">
                                        <i class="fa-solid fa-filter-circle-xmark fa-lg" style="color: #74C0FC;"></i>&nbsp;
                                        Clear Filters
                                    </button>
                                </div>
                            </div>
                        `);
                    } else {
                        $.each(response.observations, function(key, val) {
                            var _status = '';
                            var _mediaUrl = '';
                            var _role = response.userRole;



                            // Media Handling
                            if (!val.media || val.media.mediaUrl === "") {
                                _mediaUrl = "https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg";
                            } else {
                                let cleanPath = val.media.mediaUrl.replace(/^\/?observation\//, '');
                                _mediaUrl = window.location.origin + '/' + cleanPath;
                            }

                            // console.log("djkjda",_mediaUrl);

                            // Status Badge
                            if (val.status === "Published") {
                                _status = `<span class="badge badge-pill position-absolute badge-top-right badge-success" style="top:8px;right: -7px;">PUBLISHED</span>`;
                            } else {
                                _status = `<span class="badge badge-pill position-absolute badge-top-right badge-danger" style="top:8px;right: -7px;">DRAFT</span>`;
                            }

                            // Link based on Role
                            var viewLink = (_role !== "Parent") ?
                                "/observation/view/" + val.id :
                                "/observation/print/" + val.id;

                            var targetAttr = (_role !== "Parent") ? '' : 'target="_blank"';

                            // Icons on Right side
                            var iconsHtml = '';
                            if (_role !== "Parent") {
                                iconsHtml = `
                                    <a href="/observation/print/${val.id}" target="_blank" class="mb-2">
                                        <i class="fa-solid fa-print fa-lg fa-beat" style="color: #74C0FC;"></i>
                                    </a>
                                    <i class="fa-sharp fa-solid fa-trash fa-lg fa-fade" style="color: #da0711;cursor:pointer;" onclick="deleteObservation(${val.id})"></i>
                                `;
                            } else {
                                iconsHtml = `
                                    <i class="fa-solid fa-comment fa-bounce fa-sm" style="color: #74C0FC;cursor:pointer;" onclick="openAddCommentModal(${val.id})"></i>
                                `;
                            }

                               // ⭐ Seen by Parents HTML Generation
                                var seenByParentsHtml = '';
                                if (_role !== "Parent") {
                                    seenByParentsHtml += '<p><strong>Seen by Parents:</strong></p><ul style="max-height:60px;overflow-y:auto;">';
                                    if (val.seen && val.seen.length > 0) {
                                        $.each(val.seen, function(index, seen) {
                                            const maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg', 'avatar10.jpg'];
                                            const femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg', 'avatar7.jpg'];
                                            const avatars = seen.gender === 'FEMALE' ? femaleAvatars : maleAvatars;
                                            const defaultAvatar = avatars[Math.floor(Math.random() * avatars.length)];

                                            const imageUrl = seen.imageUrl ? `{{ asset('') }}${seen.imageUrl}` : `{{ asset('assets/img/xs/') }}/${defaultAvatar}`;

                                            seenByParentsHtml += `
                                                <li style="margin-bottom: 10px;">
                                                    <img src="${imageUrl}" alt="Profile Image" width="40" height="40" style="border-radius: 50%;">
                                                    ${seen.name} <span style="color: #2196F3;">&#10003;&#10003;</span>
                                                </li>
                                            `;
                                        });
                                    } else {
                                        seenByParentsHtml += '<li>No parent has seen this yet.</li>';
                                    }
                                    seenByParentsHtml += '</ul>';
                                }

                            // Build observation card
                            var title = val.obestitle || val.title;
                            var displayTitle = title.length > 40 ? title.substring(0, 40) + '...' : title;

                            $('#observations-list').append(`
                                <div class="col-lg-6 col-md-3">
                                    <div class="d-flex flex-row mb-3 bg-white br-10 align-items-center justify-content-between p-3 card">
                                        <div class="d-flex flex-row align-items-center">
                                            <a class="d-block position-relative" href="${viewLink}" ${targetAttr}>
                                                <img src="${_mediaUrl}" alt="Media" class="list-thumbnail border-0" style="width:100px;height:100px;object-fit:cover;">
                                                ${_status}
                                            </a>
                                            <div class="pl-3">
                                                <a href="${viewLink}" class="obs-link" ${targetAttr}>
                                                    <p class="list-item-heading mb-1">${displayTitle}</p>
                                                </a>
                                                <p class="text-muted mb-1 text-small">By: ${val.userName || 'Unknown'}</p>
                                                <p class="text-primary text-small font-weight-medium mb-0">${val.date_added}</p>
                                                ${seenByParentsHtml}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-center icon-actions">
                                            ${iconsHtml}
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    }

                    $('#btn-apply-filters').prop('disabled', false).html('Apply Filters');
                    $('#filtersModal').modal('hide');
                } else {
                    alert(response.message || 'An error occurred while filtering observations.');
                    $('#btn-apply-filters').prop('disabled', false).html('Apply Filters');
                }
            },
            error: function(xhr, status, error) {
                console.error('Filter error:', error);
                alert('An error occurred while filtering observations.');
                $('#btn-apply-filters').prop('disabled', false).html('Apply Filters');
            }
        });
    }

    // Apply filters button click
    $('#btn-apply-filters').on('click', function() {
        $(this).prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        );
        applyFilters();
    });

    // Clear filters functionality
    function clearFilters() {
        // Reset status to "All"
        $('#status_all').prop('checked', true);

        // Reset date to "None"
        $('#added_none').prop('checked', true);
        $('#custom_date_range').hide();
        $('#from_date').val('');
        $('#to_date').val('');

        // Uncheck all children
        $('.filter_child').prop('checked', false);

        // Reset author to "Any"
        $('.filter_author').prop('checked', false);
        $('#filter_author_any').prop('checked', true);

        // Reload original observations
        location.reload();
    }

    $('#btn-clear-filters').on('click', function() {
        clearFilters();
    });

    // Clear filters from inline button
    $(document).on('click', '#btn-clear-filters-inline', function() {
        clearFilters();
    });

    // Load children data on modal open
    $('#filtersModal').on('show.bs.modal', function() {
        // Load children list via AJAX if needed
        loadChildren();
        loadStaff();

    });

    function loadChildren() {
        $.ajax({
            url: '/observation/get-children',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var childCheckboxes = '';
                    $.each(response.children, function(index, child) {
                        childCheckboxes += `
                            <div class="custom-control custom-checkbox mb-4">
                                <input type="checkbox" class="custom-control-input filter_child"
                                    id="filter_child_${child.id}" value="${child.id}">
                                <label class="custom-control-label" for="filter_child_${child.id}">${child.name}</label>
                            </div>
                        `;
                    });
                    $('#child-checkboxes').html(childCheckboxes);
                }
            }
        });
    }

    $(document).on('input', '#childSearchInput', function () {
    var searchTerm = $(this).val().toLowerCase();
    $('#child-checkboxes .custom-control').each(function () {
        var labelText = $(this).find('label').text().toLowerCase();
        $(this).toggle(labelText.indexOf(searchTerm) !== -1);
    });
});


function loadStaff() {
        $.ajax({
            url: '/observation/get-staff',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var childCheckboxes = '';
                    $.each(response.staff, function(index, child) {
                        childCheckboxes += `
                            <div class="custom-control custom-checkbox mb-4">
                                <input type="checkbox" class="custom-control-input filter_staff"
                                    id="filter_staff_${child.id}" value="${child.id}">
                                <label class="custom-control-label" for="filter_staff_${child.id}">${child.name}</label>
                            </div>
                        `;
                    });
                    $('#staff-checkboxes').html(childCheckboxes);
                }
            }
        });
    }

    $(document).on('input', '#staffSearchInput', function () {
    var searchTerm = $(this).val().toLowerCase();
    $('#staff-checkboxes .custom-control').each(function () {
        var labelText = $(this).find('label').text().toLowerCase();
        $(this).toggle(labelText.indexOf(searchTerm) !== -1);
    });
});


// Allow only one selection among Any, Me, or any staff
$(document).on('change', '.filter_author, .filter_staff', function () {
    const selectedId = $(this).attr('id');

    // If "Any" or "Me" is selected
    if (selectedId === 'filter_author_any' || selectedId === 'filter_author_me') {
        // Uncheck other author options and all staff
        $('.filter_author').not(this).prop('checked', false);
        $('.filter_staff').prop('checked', false);
    }

    // If any staff is selected
    else if ($(this).hasClass('filter_staff')) {
        // Uncheck author checkboxes
        $('.filter_author').prop('checked', false);
    }

    // Optional: Auto-select "Any" if nothing is selected
    setTimeout(function () {
        const anySelected = $('.filter_author:checked, .filter_staff:checked').length;
        if (!anySelected) {
            $('#filter_author_any').prop('checked', true);
        }
    }, 100); // slight delay to let changes settle
});



});
</script>





@include('layout.footer')
@stop
