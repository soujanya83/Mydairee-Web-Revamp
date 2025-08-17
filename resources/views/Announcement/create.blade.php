@extends('layout.master')
@section('title', 'Create Announcement')
@section('parentPageTitle', 'Dashboard')

@section('page-styles') {{-- ✅ Injects styles into layout --}}
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
    .list-table td, .list-table tr {
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
    .list-thumbnail.xsmall {
        width: 40px;
    }

    .list-table td {
        vertical-align: middle !important;
    }

    .select-all-box {
        padding-left: 12px;
    }

    .select-all-box>label {
        margin-left: 22px;
        font-size: 15px;
    }
</style>
<style>
    .ck-editor__editable_inline {
        min-height: 300px;
        /* This is like setting more "rows" */
    }
</style>
@endsection

@section('content')

@php
$role = Auth::user()->userType;
$edit = $add = 0;

if ($role === 'Superadmin') {
$edit = $add = 1;
} elseif ($role === 'Staff') {
if (isset($permissions->addAnnouncement) && $permissions->addAnnouncement == 1) {
$add = 1;
}
if (isset($permissions->updateAnnouncement) && $permissions->updateAnnouncement == 1) {
$edit = 1;
}
}
@endphp

<hr>
<main data-centerid="{{ $centerid }}">
    <div class="container-fluid">
        <!-- <div class="row">
            <div class="col-12">
                <h1>Manage Announcements</h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                        <li class="breadcrumb-item">
    <a href="{{ route('announcements.list') }}">Announcements List</a>
</li>

                        <li class="breadcrumb-item active" aria-current="page">Manage Announcement</li>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-5">
                            <h5 class="card-title">Enter Details</h5>
                        </div>

                        <form action="{{ route('announcements.store') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @if ($announcement)
                            <input type="hidden" name="annId" value="{{ $announcement->id }}">
                            @endif
                            <input type="hidden" name="centerid" value="{{ $centerid }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" id="title" required
                                            value="{{ old('title', $announcement->title ?? '') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="eventDate">Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control calendar" name="eventDate"
                                                value="{{ isset($announcement->eventDate) ? \Carbon\Carbon::parse($announcement->eventDate)->format('d-m-Y') : '' }}"
                                                data-date-format="dd-mm-yyyy">

                                            <span class="input-group-text input-group-append input-group-addon">
                                                <i class="simple-icon-calendar"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h4>Media Upload Section</h4>
                                        <div class="media-upload-box p-4 border rounded bg-light text-center">
                                            <label for="mediaInput" class="btn btn-outline-info">
                                                Select Image (png,jpeg,jpg) or pdf 
                                            </label>
                                           <input type="file" id="mediaInput" name="media[]" class="d-none" multiple accept="image/*,application/pdf">

                                            <small class="form-text text-muted mt-2">Only image and pdf allowed</small>
                                        </div>

                                        <div id="mediaPreview" class="row mt-4"></div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-info mb-1" data-toggle="modal"
                                            data-backdrop="static" data-target="#selectChildrenModal">+ Add
                                            Children</button>
                                    </div>
                                    <div class="children-tags">
                                        @forelse ($announcement->children ?? [] as $child)
                                        <a href="#!" class="rem" data-role="remove" data-child="{{ $child->id }}">
                                            <input type="hidden" name="childId[]" value="{{ $child->id }}">
                                            <span class="badge badge-pill badge-outline-info mb-1">{{ $child->name }}
                                                X</span>
                                        </a>
                                        @empty
                                        <p>No children selected</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text">Description</label>
                                        <textarea name="text" id="about"
                                            class="form-control">{{ old('text', $announcement->text ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    @if ($announcement)
                                    @if ($edit)
                                    <button type="submit" class="btn btn-outline-info my-2">Save</button>
                                    @else
                                    <button type="button" class="btn btn-outline-info my-2" data-toggle="tooltip"
                                        data-placement="top" title="You need permission to save!">Save</button>
                                    @endif
                                    @else
                                    @if ($add)
                                    <button type="submit" class="btn btn-outline-info my-2">Save</button>
                                    @else
                                    <button type="button" class="btn btn-outline-info my-2" data-toggle="tooltip"
                                        data-placement="top" title="You need permission to save!">Save</button>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


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

<div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 1rem; right: 1rem; z-index: 1080;">
    <div class="toast-container">

        {{-- Validation Errors --}}
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="toast bg-danger text-white mb-2" data-delay="10000">

            <div class="toast-body">
                {{ $error }}
            </div>
        </div>
        @endforeach
        @endif

        {{-- Custom Flash Message
        @if (session('status') && session('message'))
        <div class="toast {{ session('status') === 'success' ? 'bg-success' : 'bg-danger' }} text-white mb-2"
            data-delay="5000">
            <div class="toast-header {{ session('status') === 'success' ? 'bg-success' : 'bg-danger' }} text-white">
                <strong class="mr-auto">{{ ucfirst(session('status')) }}</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('message') }}
            </div>
        </div>
        @endif --}}



    </div>
</div>




@endsection

@push('scripts')
<script>
    let selectedFiles = [];

// document.getElementById('mediaInput').addEventListener('change', function (event) {
//     const previewContainer = document.getElementById('mediaPreview');
//     const newFiles = Array.from(event.target.files);
//     const totalFiles = selectedFiles.length + newFiles.length;

//     if (totalFiles > 1) {
//         alert("You can upload a maximum of 1 files.");
//         this.value = '';
//         return;
//     }

//     newFiles.forEach((file, index) => {
//         const reader = new FileReader();
//         const fileIndex = selectedFiles.length;

//         reader.onload = function (e) {
//             const col = document.createElement('div');
//             col.className = 'col-md-3 position-relative mb-3';

//             let mediaContent = '';

//             if (file.type.startsWith('image/')) {
//                 mediaContent = `<img src="${e.target.result}" class="media-thumb rounded">`;
//             } else if (file.type.startsWith('video/')) {
//                 mediaContent = `<video src="${e.target.result}" class="media-thumb rounded" controls></video>`;
//             }

//             col.innerHTML = `
//                 <div class="position-relative">
//                     ${mediaContent}
//                     <button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${fileIndex}">✕</button>
//                 </div>
//             `;

//             previewContainer.appendChild(col);
//         };

//         reader.readAsDataURL(file);
//         selectedFiles.push(file);
//     });

//     updateFileInput();
// });

document.getElementById('mediaInput').addEventListener('change', function (event) {
    const previewContainer = document.getElementById('mediaPreview');
    const newFiles = Array.from(event.target.files);

    // Ensure selectedFiles is defined globally
    window.selectedFiles = window.selectedFiles || [];

    const totalFiles = selectedFiles.length + newFiles.length;

    if (totalFiles > 1) {
        alert("You can upload a maximum of 1 file.");
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
                mediaContent = `<img src="${e.target.result}" class="media-thumb rounded w-100" alt="Image">`;
            } else if (file.type === 'application/pdf') {
                mediaContent = `<embed src="${e.target.result}" type="application/pdf" class="media-thumb rounded w-100" height="200px"/>`;
            } else if (file.type.startsWith('video/')) {
                mediaContent = `<video src="${e.target.result}" class="media-thumb rounded w-100" controls></video>`;
            } else {
                mediaContent = `<div class="alert alert-warning">Unsupported file type</div>`;
            }

            col.innerHTML = `
                <div class="position-relative">
                    ${mediaContent}
                    <button type="button" class="btn btn-danger btn-sm remove-btn position-absolute top-0 end-0 m-1" data-index="${fileIndex}">✕</button>
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
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = ''; // clear previous

    selectedFiles.forEach((file, i) => {
        const div = document.createElement('div');
        div.innerHTML = `
            <span>${file.name}</span>
            <button type="button" class="remove-btn" data-index="${i}">Remove</button>
        `;
        preview.appendChild(div);
    });
}

function updateFileInput() {
    const input = document.getElementById('mediaInput');
    const dataTransfer = new DataTransfer();

    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
}


</script>

<script>
    $(document).ready(function () {
        $('.toast').toast('show');
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('msg'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('msg') }}",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('status') === 'error' && session('message'))
            Swal.fire({
                title: 'Error!',
                text: "{{ session('message') }}",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Close'
            });
        @endif
    });
</script>
<script>
    $(document).ready(function () {
    $('#filter-child').on('keyup', function () {
        const searchValue = $(this).val().toLowerCase();

        $('.tab-pane').each(function () {
            let tabHasVisibleChildren = false;

            // Loop each select-all box and its table
            $(this).find('.select-all-box').each(function () {
                const $selectAllBox = $(this);
                const $table = $selectAllBox.next('table');
                let visibleRows = 0;

                // Filter rows based on name or age
                $table.find('tr').each(function () {
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


   ClassicEditor
        .create(document.querySelector('#about'), {
            toolbar: [
                'undo', 'redo', '|',
                'bold', 'italic', 'strikethrough', '|',
                'numberedList', 'bulletedList', '|',
                'link'
            ],
            height: '150px' // Note: You need CSS for height in CKEditor 5
        })
        .then(editor => {
            // console.log('CKEditor 5 initialized', editor);
        })
        .catch(error => {
            // console.error('There was a problem initializing the editor:', error);
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
                }else{
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
                    $('input.common-child[value="'+_value+'"]').prop('checked', true);
                    $('input.child-group[value="'+_value+'"]').trigger('change');
                    $('input.child-room[value="'+_value+'"]').trigger('change');

                }else{
                    $('input.common-child[value="'+_value+'"]').prop('checked', false);
                    $('input.child-group[value="'+_value+'"]').trigger('change');
                    $('input.child-room[value="'+_value+'"]').trigger('change');
                }

                var _totalChildChecked = $('.child-tab:checked').length;
                if (_totalChildChecked == _totalchilds) {
                    $("#select-all-child").prop('checked', true);
                }else{
                    $("#select-all-child").prop('checked', false);
                }
            });

            $(document).on("click",".select-group-child",function(){
                var _groupid = $(this).data('groupid');
                var _selector = $('input.common-child[data-groupid="'+_groupid+'"]');

                if ($(this).is(':checked')) {
                    // $(_selector).prop('checked', true);
                    $.each(_selector, function(index, val) {
                        $(".common-child[value='"+$(this).val()+"']").prop('checked', true);
                    });
                }else{
                    // $(_selector).prop('checked', false);
                    $.each(_selector, function(index, val) {
                        $(".common-child[value='"+$(this).val()+"']").prop('checked', false);
                    });
                }

                var _totalChildChecked = $('.child-tab:checked').length;
                if (_totalChildChecked == _totalchilds) {
                    $("#select-all-child").prop('checked', true);
                }else{
                    $("#select-all-child").prop('checked', false);
                }
            });

            $(document).on("change", ".child-group", function(){
                var _groupid = $(this).data('groupid');
                var _selector = '#select-group-child-'+_groupid;
                var _totalGroupChilds = $('.child-group[data-groupid="'+_groupid+'"]').length;
                var _totalGroupChildsChecked = $('.child-group[data-groupid="'+_groupid+'"]:checked').length;
                if (_totalGroupChilds == _totalGroupChildsChecked) {
                    $(_selector).prop('checked', true);
                }else{
                    $(_selector).prop('checked', false);
                }
            });

            $(document).on("click",".select-room-child",function(){
                var _roomid = $(this).data('roomid');
                var _selector = $('input.common-child[data-roomid="'+_roomid+'"]');

                if ($(this).is(':checked')) {
                    $.each(_selector, function(index, val) {
                        $(".common-child[value='"+$(this).val()+"']").prop('checked', true);
                    });
                }else{
                    $.each(_selector, function(index, val) {
                        $(".common-child[value='"+$(this).val()+"']").prop('checked', false);
                    });
                }

                var _totalChildChecked = $('.child-tab:checked').length;
                if (_totalChildChecked == _totalchilds) {
                    $("#select-all-child").prop('checked', true);
                }else{
                    $("#select-all-child").prop('checked', false);
                }
            });

            $(document).on("change", ".child-room", function(){
                var _roomid = $(this).data('roomid');
                var _selector = '#select-room-child-'+_roomid;
                var _totalRoomChilds = $('.child-room[data-roomid="'+_roomid+'"]').length;
                var _totalRoomChildsChecked = $('.child-room[data-roomid="'+_roomid+'"]:checked').length;
                if (_totalRoomChilds == _totalRoomChildsChecked) {
                    $(_selector).prop('checked', true);
                }else{
                    $(_selector).prop('checked', false);
                }
            });

            $(document).on("click","#insert-childtags",function(){
                $('.children-tags').empty();
                $('.unique-tag:checked').each(function(index, val) {
                    $('.children-tags').append(`
                        <a href="#!" class="rem" data-role="remove" data-child="`+ $(this).val() +`">
                            <input type="hidden" name="childId[]" value="`+ $(this).val() +`">
                            <span class="badge badge-pill badge-outline-primary mb-1">`+ $(this).data('name') +` X </span>
                        </a>
                    `);
                });
                $(".children-tags").show();
            });

            $(document).on('click', '.rem', function() {
                var _childid = $(this).data('child');
                $(".common-child[value='"+_childid+"']").trigger('click');
                $(this).remove();
            });
        });

</script>

@endpush

@include('layout.footer')
