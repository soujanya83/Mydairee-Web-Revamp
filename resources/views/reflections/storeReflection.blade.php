@extends('layout.master')
@section('title', 'Store')
@section('parentPageTitle', 'Reflection')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<style>
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
    box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
}
</style>


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

<style>
   .blur-nav {
    display: flex;
    gap: 10px;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.blur-nav .nav-item {
    flex: 1;
    text-align: center;
}

.blur-nav .nav-link {
    display: block;
    padding: 12px 20px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    color: #333;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.blur-nav .nav-link:hover {
    background: rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-color: rgba(0, 0, 0, 0.05);
    text-decoration: none;
}

.blur-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.5);
    color: #000;
    font-weight: bold;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.blur-nav .nav-link i {
    margin-right: 8px;
    color: #555;
    transition: color 0.3s ease;
}

.blur-nav .nav-link:hover i,
.blur-nav .nav-link.active i {
    color: #000;
}

/* Form group container for each select section */
.select-section {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    transition: box-shadow 0.3s ease;
}

.select-section:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}

/* Label style */
.select-section label {
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 10px;
    display: block;
    color: #333;
}

/* Button styling */
.select-section .btn {
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

/* Preview badges */
#selectedChildrenPreview .badge,
#selectedRoomsPreview .badge,
#selectedStaffPreview .badge {
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 8px;
    background: linear-gradient(to right, #00bcd4, #2196f3);
    color: white;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 4px;
}

/* Room badge override for green */
#selectedRoomsPreview .badge {
    background: linear-gradient(to right, #4caf50, #81c784);
}
#selectedStaffPreview .badge {
    background: linear-gradient(to right, #e80000, #e08e8e);
}


</style>
<style>
/* Section styling */
.form-section {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 18px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    position: relative;
    transition: box-shadow 0.3s ease;
}

/* Label styling */
.form-section label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
}

/* Textarea styling - CKEditor wrapper */
.form-section .form-control {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    border: 1px solid #ccc;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: border-color 0.3s ease;
}

.form-section .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15);
}

/* Refine button container - floated to right bottom */
.refine-container {
    display: flex;
    justify-content: flex-end;
    margin-top: 8px;
}

/* Refine button styling */
.refine-btn {
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 8px;
    background: linear-gradient(to right, #007bff, #339af0);
    color: white;
    border: none;
    box-shadow: 0 3px 6px rgba(0, 123, 255, 0.25);
    transition: background 0.3s ease, transform 0.2s ease;
}

.refine-btn:hover {
    background: linear-gradient(to right, #0056b3, #007bff);
    transform: translateY(-1px);
}

.refine-btn:active {
    transform: translateY(1px);
}

</style>


<style>
    .eylf-tree .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    
    .eylf-framework {
        background-color: #f8f9fa;
    }
    
    .eylf-outcomes-container {
        background-color: #ffffff;
        padding-left: 2rem;
    }
    
    .eylf-outcome {
        background-color: #ffffff;
        padding-left: 4rem;
    }
    
    .eylf-activity {
        background-color: #ffffff;
        padding-left: 6rem;
    }
    
    .toggle-icon {
        cursor: pointer;
        width: 20px;
        text-align: center;
    }
    
    .toggle-icon.expanded i {
        transform: rotate(90deg);
    }
</style>

<style>
 /* Styling for the submit button */
.btn-primary.submit-btn {
    padding: 10px 20px; /* Comfortable padding */
    font-size: 16px; /* Readable font size */
    font-weight: 500; /* Medium weight for emphasis */
    border-radius: 6px; /* Softer corners */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    transition: all 0.3s ease; /* Smooth transitions */
    float: right; /* Retain float:right */
    display: flex; /* Align icon and text */
    align-items: center; /* Center vertically */
    gap: 8px; /* Space between icon and text */
    position: relative; /* For animation positioning */
}

/* Icon styling */
.btn-primary.submit-btn .fas {
    font-size: 14px; /* Slightly smaller icon */
    transition: transform 0.3s ease; /* Smooth icon movement */
}

/* Hover effect */
.btn-primary.submit-btn:hover {
    background-color: #0056b3; /* Darker primary color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
    transform: translateY(-2px); /* Slight lift */
}

/* Icon animation on hover */
.btn-primary.submit-btn:hover .fas {
    transform: translateX(4px); /* Icon slides right */
}

/* Click animation */
.btn-primary.submit-btn:active {
    transform: translateY(0); /* Press down effect */
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); /* Reduced shadow */
    animation: pulse 0.2s ease-in-out; /* Pulse effect on click */
}

/* Disabled state */
.btn-primary.submit-btn:disabled {
    opacity: 0.7; /* Faded when disabled */
    cursor: not-allowed; /* Clear cursor feedback */
    transform: none; /* No transform when disabled */
}

/* Focus state for accessibility */
.btn-primary.submit-btn:focus {
    outline: 2px solid #80bdff; /* Visible focus ring */
    outline-offset: 2px; /* Offset for visibility */
}

/* Pulse animation for click */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Optional: Loading state animation */
.btn-primary.submit-btn.loading {
    pointer-events: none; /* Prevent interaction */
    opacity: 0.85; /* Slightly faded */
}

.btn-primary.submit-btn.loading .fas {
    animation: spin 1s linear infinite; /* Spinning icon for loading */
}

/* Spin animation for loading */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<style>
.btn-animated {
    position: relative;
    transition: all 0.3s ease-in-out;
    transform: translateY(0);
}

.btn-animated:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
</style>

@section('content')


@if(isset($reflection) && $reflection->id)
<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;margin-bottom:30px;">
    <button type="button" id="publishObservation" class="btn btn-success shadow-lg btn-animated mr-2">
        <i class="fas fa-upload mr-1"></i> Publish Now
    </button>
    <button type="button" id="draftObservation" class="btn btn-warning shadow-lg btn-animated">
        <i class="fas fa-file-alt mr-1"></i> Make Draft
    </button>
</div>
@endif


<div class="row clearfix">


<div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="body">
            <ul class="nav nav-tabs-new2 blur-nav">
    <li class="nav-item">
        <a class="nav-link active show" data-toggle="tab" href="#Home">
            <i class="fa-solid fa-window-restore"></i> <span>Daily Reflection</span>
        </a>
    </li>
</ul>

                <hr>
                <div class="tab-content">

                <!-- OBSERVATIONS Tabs -->
                <div class="tab-pane show active" id="Home">
                        
                    <form id="observationform" method="POST" enctype="multipart/form-data">

      <div class="row">

     <!-- Select Children -->
<div class="col-md-6 select-section">
    <label>Children</label><br>
    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#childrenModal">Select Children</button>
    <input type="hidden" name="selected_children" id="selected_children" value="{{ isset($childrens) ? implode(',', collect($childrens)->pluck('id')->toArray()) : '' }}">
    <div id="selectedChildrenPreview" class="mt-3">
        @if(isset($childrens))
            @foreach($childrens as $child)
                <span class="badge badge-info mr-1">{{ $child->name }}</span>
            @endforeach
        @endif
    </div>
</div>


<!-- Select Staff -->
<div class="col-md-6 select-section">
    <label>Staff</label><br>
    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#staffModal">Select Staff</button>
    <input type="hidden" name="selected_staff" id="selected_staff" value="{{ isset($staffs) ? implode(',', collect($staffs)->pluck('id')->toArray()) : '' }}">
    <div id="selectedStaffPreview" class="mt-3">
        @if(isset($staffs))
            @foreach($staffs as $staff)
                <span class="badge badge-info mr-1">{{ $staff->name }}</span>
            @endforeach
        @endif
    </div>
</div>


<!-- Select Rooms -->
<div class="col-md-6 select-section">
    <label>Rooms</label><br>
    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#roomsModal">Select Rooms</button>
    <input type="hidden" name="selected_rooms" id="selected_rooms" value="{{ isset($rooms) ? implode(',', collect($rooms)->pluck('id')->toArray()) : '' }}">
    <div id="selectedRoomsPreview" class="mt-3">
        @if(isset($rooms))
            @foreach($rooms as $room)
                <span class="badge badge-success mr-1">{{ $room['name'] }}</span>
            @endforeach
        @endif
    </div>
</div>


<div class="col-md-6 select-section">
    <label for="eylf">EYLF</label>
    <div class="input-group">
        <textarea class="form-control" id="eylf" name="eylf" rows="3" readonly>{{ old('eylf', $reflection->eylf ?? '') }}</textarea>
        <div class="input-group-append">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#eylfModal">
                <i class="fa fa-search"></i> Select EYLF
            </button>
        </div>
    </div>
</div>



<input type="hidden" name="id" id="reflection_id" value="{{ isset($reflection) ? $reflection->id : '' }}">
        <!-- Add more form elements -->
        <div class="col-md-6 mt-4 form-section">
    <label for="editor6">Title</label>
    <textarea id="editor6" name="title" class="form-control ckeditor">{!! isset($reflection) ? $reflection->title : '' !!}</textarea>
    <div class="refine-container">
<button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor6"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>

<div class="col-md-6 mt-4 form-section">
    <label for="editor3">Reflection</label>
    <textarea id="editor3" name="about" class="form-control ckeditor">{!! isset($reflection) ? $reflection->about : '' !!}</textarea>
    <div class="refine-container">
 <button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor3"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>





<div class="col-md-12 mt-4">
    <h4>Media Upload Section</h4>
    <div class="media-upload-box p-4 border rounded bg-light text-center">
        <label for="mediaInput" class="btn btn-outline-primary">
            Select up to 10 Images/Videos
        </label>
        <input type="file" id="mediaInput" name="media[]" class="d-none" multiple accept="image/*,video/*">
        <small class="form-text text-muted mt-2">Only images and videos are allowed. Max 10 files.</small>
    </div>

    <div id="mediaPreview" class="row mt-4"></div>


    @if(isset($reflection) && $reflection->media->isNotEmpty())
    <span>Uploaded Images/Videos</span>
    <div id="uploadedMedia" class="row mt-4">
        @foreach($reflection->media as $media)
            <div class="col-md-3 position-relative mb-3" id="media-{{ $media->id }}">
            @if(Str::startsWith($media->mediaType, ['image', 'Image']))
                    <img src="{{ asset($media->mediaUrl) }}" class="media-thumb img-fluid rounded">
                @elseif(Str::startsWith($media->mediaType, 'video'))
                    <video controls class="media-thumb rounded">
                        <source src="{{ asset($media->mediaUrl) }}" type="{{ $media->mediaType }}">
                        Your browser does not support the video tag.
                    </video>
                @endif
                <button type="button" class="btn btn-sm btn-danger remove-btn"
    onclick="deleteMedia({{ $media->id }}, '{{ asset($media->mediaUrl) }}')">Remove</button>             </div>
        @endforeach
    </div>
@endif


</div>


        <!-- Submit -->
        <div class="col-12 mt-4">
            <button type="submit" style="float:right" class="btn btn-primary submit-btn"><i class="fas fa-arrow-right"></i>Submit</button>
        </div>

    </div>
</form>






                    </div>



</div>


<!-- Modal -->
<div class="modal fade" id="childrenModal" tabindex="-1" role="dialog" aria-labelledby="childrenModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center justify-content-between">
        <h5 class="modal-title" id="childrenModalLabel">Select Children</h5>
        <input type="text" id="childSearch" class="form-control ml-3" placeholder="Search children..." style="max-width: 250px;">
        <button type="button" class="close ml-2" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height:550px;overflow-y:auto;">
        <div id="childrenList" class="row"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmChildren" class="btn btn-success" data-dismiss="modal">Confirm Selection</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="roomsModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <h5>Select Rooms</h5>
        <input type="text" id="roomSearch" class="form-control ml-3" placeholder="Search rooms..." style="max-width: 250px;">
      </div>
      <div class="modal-body" style="max-height:550px;overflow-y:auto;">
        <div id="roomsList" class="row"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmRooms" class="btn btn-success" data-dismiss="modal">Confirm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>




<!-- Staff Modal -->
<div class="modal fade" id="staffModal" tabindex="-1" role="dialog" aria-labelledby="staffModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center justify-content-between">
        <h5 class="modal-title" id="staffModalLabel">Select Staff</h5>
        <input type="text" id="staffSearch" class="form-control ml-3" placeholder="Search staff..." style="max-width: 250px;">
        <button type="button" class="close ml-2" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height:550px;overflow-y:auto;">
        <div id="staffList" class="row"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmStaff" class="btn btn-success" data-dismiss="modal">Confirm Selection</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>




<!-- EYLF Modal -->
@php
    $existingEylf = old('eylf', $reflection->eylf ?? '');
    $selectedLines = preg_split('/\r\n|\r|\n/', $existingEylf);
@endphp

<!-- EYLF Modal -->
<div class="modal fade" id="eylfModal" tabindex="-1" role="dialog" aria-labelledby="eylfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select EYLF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="max-height:500px; overflow-y:auto;">
                <div class="eylf-tree">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#eylfFramework">
                                    <i class="fa fa-chevron-right"></i>
                                </span>
                                <span>Early Years Learning Framework (EYLF) - Australia (V2.0 2022)</span>
                            </div>
                            <div id="eylfFramework" class="collapse mt-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#eylfOutcomes">
                                                <i class="fa fa-chevron-right"></i>
                                            </span>
                                            <span>EYLF Learning Outcomes</span>
                                        </div>
                                        <div id="eylfOutcomes" class="collapse mt-2">
                                            <ul class="list-group">
                                                @foreach($outcomes as $outcome)
                                                    <li class="list-group-item">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mr-2 toggle-icon" data-toggle="collapse" data-target="#outcome{{ $outcome->id }}">
                                                                <i class="fa fa-chevron-right"></i>
                                                            </span>
                                                            <span>{{ $outcome->title }} - {{ $outcome->name }}</span>
                                                        </div>
                                                        <div id="outcome{{ $outcome->id }}" class="collapse mt-2">
                                                            <ul class="list-group">
                                                                @foreach($outcome->activities as $activity)
                                                                    @php
                                                                        $lineText = "{$outcome->title} - {$outcome->name}: {$activity->title}";
                                                                        $isChecked = in_array($lineText, $selectedLines);
                                                                    @endphp
                                                                    <li class="list-group-item">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input eylf-activity-checkbox"
                                                                                   type="checkbox"
                                                                                   value="{{ $activity->id }}"
                                                                                   id="activity{{ $activity->id }}"
                                                                                   data-outcome-id="{{ $outcome->id }}"
                                                                                   data-outcome-title="{{ $outcome->title }}"
                                                                                   data-outcome-name="{{ $outcome->name }}"
                                                                                   data-activity-title="{{ $activity->title }}"
                                                                                   {{ $isChecked ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="activity{{ $activity->id }}">
                                                                                {{ $activity->title }}
                                                                            </label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEylfSelections" data-dismiss="modal">Save selections</button>
            </div>
        </div>
    </div>
</div>





<div id="toast-container" class="toast-bottom-right"
        style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>


        

        <script>
$(document).ready(function () {
    let selectedChildren = new Set($('#selected_children').val().split(',').filter(id => id));

    // Load children on modal open
    $('#childrenModal').on('show.bs.modal', function () {
        $.ajax({
            url: '{{ route("observation.get.children") }}',
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    let html = '';
                    response.children.forEach(child => {
                        const checked = selectedChildren.has(child.id.toString()) ? 'checked' : '';
                        html += `
                            <div class="col-md-4 mb-2 child-item">
                                <div class="form-check">
                                    <input class="form-check-input child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}" ${checked}>
                                    <label class="form-check-label" for="child-${child.id}">
                                        ${child.name} ${child.lastname}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    $('#childrenList').html(html);
                }
            }
        });
    });

    // Filter children
    $('#childSearch').on('keyup', function () {
        const search = $(this).val().toLowerCase();
        $('.child-item').each(function () {
            const name = $(this).find('.form-check-label').text().toLowerCase();
            $(this).toggle(name.includes(search));
        });
    });

    // Confirm selection
    $('#confirmChildren').on('click', function () {
        selectedChildren = new Set();
        let nameHtml = '';
        $('.child-checkbox:checked').each(function () {
            selectedChildren.add($(this).val());
            nameHtml += `<span class="badge badge-info mr-1">${$(this).next('label').text()}</span>`;
        });

        $('#selected_children').val([...selectedChildren].join(','));
        $('#selectedChildrenPreview').html(nameHtml);
        // $('#childrenModal').modal('hide');
    });




    let selectedRooms = new Set($('#selected_rooms').val().split(',').filter(id => id));

$('#roomsModal').on('show.bs.modal', function () {
    $.get('{{ route("observation.get.rooms") }}', function (res) {
        if (res.success) {
            let html = '';
            res.rooms.forEach(room => {
                const checked = selectedRooms.has(room.id.toString()) ? 'checked' : '';
                html += `<div class="col-md-4 mb-2 room-item">
                    <div class="form-check">
                        <input class="form-check-input room-checkbox" type="checkbox" value="${room.id}" id="room-${room.id}" ${checked}>
                        <label class="form-check-label" for="room-${room.id}">${room.name}</label>
                    </div>
                </div>`;
            });
            $('#roomsList').html(html);
        }
    });
});

$('#roomSearch').on('keyup', function () {
    const val = $(this).val().toLowerCase();
    $('.room-item').each(function () {
        const name = $(this).find('label').text().toLowerCase();
        $(this).toggle(name.includes(val));
    });
});

$('#confirmRooms').on('click', function () {
    selectedRooms = new Set();
    let nameHtml = '';
    $('.room-checkbox:checked').each(function () {
        selectedRooms.add($(this).val());
        nameHtml += `<span class="badge badge-success mr-1">${$(this).next('label').text()}</span>`;
    });
    $('#selected_rooms').val([...selectedRooms].join(','));
    $('#selectedRoomsPreview').html(nameHtml);
    // $('#roomsModal').modal('hide');
});




let selectedStaff = new Set($('#selected_staff').val().split(',').filter(id => id));

// Load staff on modal open
$('#staffModal').on('show.bs.modal', function () {
    $.ajax({
        url: '{{ route("observation.get-staff") }}',
        method: 'GET',
        success: function (response) {
            if (response.success) {
                let html = '';
                response.staff.forEach(staff => {
                    const checked = selectedStaff.has(staff.id.toString()) ? 'checked' : '';
                    html += `
                        <div class="col-md-4 mb-2 staff-item">
                            <div class="form-check">
                                <input class="form-check-input staff-checkbox" type="checkbox" value="${staff.id}" id="staff-${staff.id}" ${checked}>
                                <label class="form-check-label" for="staff-${staff.id}">
                                    ${staff.name}
                                </label>
                            </div>
                        </div>
                    `;
                });
                $('#staffList').html(html);
            }
        }
    });
});

// Filter staff
$('#staffSearch').on('keyup', function () {
    const search = $(this).val().toLowerCase();
    $('.staff-item').each(function () {
        const name = $(this).find('.form-check-label').text().toLowerCase();
        $(this).toggle(name.includes(search));
    });
});

// Confirm selection
$('#confirmStaff').on('click', function () {
    selectedStaff = new Set();
    let nameHtml = '';
    $('.staff-checkbox:checked').each(function () {
        selectedStaff.add($(this).val());
        nameHtml += `<span class="badge badge-info mr-1">${$(this).next('label').text()}</span>`;
    });

    $('#selected_staff').val([...selectedStaff].join(','));
    $('#selectedStaffPreview').html(nameHtml);
    // $('#staffModal').modal('hide');
});



});
</script>

<script>
  // Toggle icons
$(document).on('click', '.toggle-icon', function () {
    const icon = $(this).find('i');
    icon.toggleClass('fa-chevron-right fa-chevron-down');
});

// Save EYLF Selections
$('#saveEylfSelections').on('click', function () {
    const selectedActivities = [];

    $('.eylf-activity-checkbox:checked').each(function () {
        selectedActivities.push({
            activityId: $(this).val(),
            outcomeId: $(this).data('outcome-id'),
            outcomeTitle: $(this).data('outcome-title'),
            outcomeName: $(this).data('outcome-name'),
            activityTitle: $(this).data('activity-title')
        });
    });

    const formattedText = selectedActivities.map(item =>
        `${item.outcomeTitle} - ${item.outcomeName}: ${item.activityTitle}`
    ).join('\n');

    $('#eylf').val(formattedText);

    if (!$('#eylfData').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'eylfData',
            name: 'eylfData'
        }).appendTo('form');
    }

    $('#eylfData').val(JSON.stringify(selectedActivities));
    // $('#eylfModal').modal('hide');
});


</script>


<script>
    const editors = {};

    document.querySelectorAll('.ckeditor').forEach((el) => {
        ClassicEditor
            .create(el)
            .then(editor => {
                editors[el.id] = editor;
            })
            .catch(error => {
                console.error(error);
            });
    });

    document.querySelectorAll('.refine-btn').forEach(button => {
        button.addEventListener("click", function () {
            const editorId = this.getAttribute("data-editor");
            const editor = editors[editorId];

            if (!editor) return alert("Editor not found!");

            const content = editor.getData();
            const originalText = this.innerText;
            this.innerText = "Refining...";
            this.disabled = true;

            fetch("{{ route('observation.refine.text') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ text: content })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        editor.setData(data.refined_text);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(err => {
                    console.error("Refine Error:", err);
                    alert("Something went wrong!");
                })
                .finally(() => {
                    this.innerText = originalText;
                    this.disabled = false;
                });
        });
    });
</script>



<script>
    let selectedFiles = [];

document.getElementById('mediaInput').addEventListener('change', function (event) {
    const previewContainer = document.getElementById('mediaPreview');
    const newFiles = Array.from(event.target.files);
    const totalFiles = selectedFiles.length + newFiles.length;

    if (totalFiles > 10) {
        alert("You can upload a maximum of 10 files.");
        this.value = '';
        return;
    }

    newFiles.forEach((file, index) => {
        const reader = new FileReader();
        const fileIndex = selectedFiles.length;

        reader.onload = function (e) {
            const col = document.createElement('div');
            col.className = 'col-md-3 position-relative mb-3';

            let mediaContent = '';

            if (file.type.startsWith('image/')) {
                mediaContent = `<img src="${e.target.result}" class="media-thumb rounded">`;
            } else if (file.type.startsWith('video/')) {
                mediaContent = `<video src="${e.target.result}" class="media-thumb rounded" controls></video>`;
            }

            col.innerHTML = `
                <div class="position-relative">
                    ${mediaContent}
                    <button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${fileIndex}">✕</button>
                </div>
            `;

            previewContainer.appendChild(col);
        };

        reader.readAsDataURL(file);
        selectedFiles.push(file);
    });

    updateFileInput();
});

// Remove handler
document.getElementById('mediaPreview').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-btn')) {
        const index = parseInt(e.target.getAttribute('data-index'));
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderPreview();
    }
});

// Re-render preview
function renderPreview() {
    const previewContainer = document.getElementById('mediaPreview');
    previewContainer.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function (e) {
            const col = document.createElement('div');
            col.className = 'col-md-3 position-relative mb-3';

            let mediaContent = '';

            if (file.type.startsWith('image/')) {
                mediaContent = `<img src="${e.target.result}" class="media-thumb rounded">`;
            } else if (file.type.startsWith('video/')) {
                mediaContent = `<video src="${e.target.result}" class="media-thumb rounded" controls></video>`;
            }

            col.innerHTML = `
                <div class="position-relative">
                    ${mediaContent}
                    <button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${index}">✕</button>
                </div>
            `;

            previewContainer.appendChild(col);
        };

        reader.readAsDataURL(file);
    });
}

// Update file input value from selectedFiles
function updateFileInput() {
    const input = document.getElementById('mediaInput');
    const dataTransfer = new DataTransfer();

    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
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



    
$(document).ready(function () {
    $('#observationform').on('submit', function (e) {
        e.preventDefault();

     

        const form = $('#observationform')[0];
        const formData = new FormData(form);

        // Append selected files (including rotated ones)
        // selectedFiles.forEach((file, index) => {
        //     formData.append('media[]', file);
        // });

        $.ajax({
            url: "{{ route('reflection.store') }}", // 👈 Your Laravel route
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel
            },
            beforeSend: function () {
                // Optional: show loader or disable button
                $('button[type=submit]').prop('disabled', true).text('Submitting...');
            },
            success: function(response) {
    if (response.status === 'success') {
        showToast('success', 'Reflection Added Successfully!');
        setTimeout(() => {
            window.location.href = '/reflection/index/'; // or 'link', or 'observation'
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
            },
            complete: function () {
                $('button[type=submit]').prop('disabled', false).text('Submit');
            }
        });
    });
});


</script>


<script>
  function deleteMedia(id, fileUrl) {
    Swal.fire({
        title: 'What do you want to do?',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Download & Delete',
        denyButtonText: 'Delete Only',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#0d6efd',
        denyButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Download first
            const link = document.createElement('a');
            link.href = fileUrl;
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Then delete
            performReflectionDelete(id);
        } else if (result.isDenied) {
            // Delete without download
            performReflectionDelete(id);
        }
    });
}

function performReflectionDelete(id) {
    fetch(`/reflection/reflection-media/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            document.getElementById(`media-${id}`).remove();
            Swal.fire('Deleted!', 'The media has been removed.', 'success');
        } else {
            throw new Error('Delete failed');
        }
    })
    .catch(() => {
        Swal.fire('Error!', 'Something went wrong.', 'error');
    });
}

</script>


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


function handleObservationStatusChange(status) {
    const $button = status === 'Published' ? $('#publishObservation') : $('#draftObservation');
    const originalText = $button.html();
    $button.html('Processing...').prop('disabled', true);

    const obsId = $('#reflection_id').val();
    if (!obsId) {
        showToast('error', 'Please create the Reflection first');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    $.ajax({
        url: "{{ route('reflection.status.update') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            reflectionId: obsId,
            status: status
        },
        success: function(res) {
            if (res.status === 'success') {
                showToast('success', `reflection marked as ${status}`);
                $button.html('Saved!');
                setTimeout(() => {
                    window.location.href = "{{ route('reflection.index') }}";
                }, 1500);
            } else {
                showToast('error', res.message || 'Something went wrong');
                $button.html(originalText).prop('disabled', false);
            }
        },
        error: function(xhr) {
            $button.html(originalText).prop('disabled', false);
            if (xhr.status === 422) {
                Object.values(xhr.responseJSON.errors).forEach(err => showToast('error', err[0]));
            } else showToast('error', 'Server error occurred');
        }
    });
}

// Bind events
$('#publishObservation').on('click', function() {
    handleObservationStatusChange('Published');
});

$('#draftObservation').on('click', function() {
    handleObservationStatusChange('Draft');
});
</script>


@include('layout.footer')
@stop