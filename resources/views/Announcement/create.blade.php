@extends('layout.master')
@section('title', 'Create Announcement')
@section('parentPageTitle', 'Dashboard')

@section('page-styles') {{-- ✅ Injects styles into layout --}}
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
        .select-all-box > label {
            margin-left: 22px;
            font-size: 15px;
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

<main data-centerid="{{ $centerid }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Manage Announcements</h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Announcements List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Announcement</li>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-5">
                            <h5 class="card-title">Enter Details</h5>
                        </div>

                        <form action="" method="POST" autocomplete="off">
                            @csrf
                            @if ($announcement)
                                <input type="hidden" name="annId" value="{{ $announcement->id }}">
                            @endif
                            <input type="hidden" name="centerid" value="{{ $centerid }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" id="title" required value="{{ old('title', $announcement->title ?? '') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="eventDate">Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control calendar" name="eventDate" id="eventDate" value=""
 data-date-format="dd-mm-yyyy">
                                            <span class="input-group-text input-group-append input-group-addon">
                                                <i class="simple-icon-calendar"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-secondary mb-1" data-toggle="modal" data-backdrop="static" data-target="#selectChildrenModal">+ Add Children</button>
                                    </div>

                                    <div class="children-tags">
                                        @forelse ($announcement->children ?? [] as $child)
                                            <a href="#!" class="rem" data-role="remove" data-child="{{ $child->childid }}">
                                                <input type="hidden" name="childId[]" value="{{ $child->childid }}">
                                                <span class="badge badge-pill badge-outline-primary mb-1">{{ $child->name }} X</span>
                                            </a>
                                        @empty
                                            <p>No children selected</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text">Description</label>
                                        <textarea name="text" id="about" class="form-control">{{ old('text', $announcement->text ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    @if ($announcement)
                                        @if ($edit)
                                            <button type="submit" class="btn btn-primary my-2">Save</button>
                                        @else
                                            <button type="button" class="btn btn-primary my-2" data-toggle="tooltip" data-placement="top" title="You need permission to save!">Save</button>
                                        @endif
                                    @else
                                        @if ($add)
                                            <button type="submit" class="btn btn-primary my-2">Save</button>
                                        @else
                                            <button type="button" class="btn btn-primary my-2" data-toggle="tooltip" data-placement="top" title="You need permission to save!">Save</button>
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


<div class="modal fade modal-right" id="selectChildrenModal" tabindex="-1" role="dialog" aria-labelledby="selectChildrenModal" aria-hidden="true">
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
                    <input type="text" class="form-control" id="filter-child" placeholder="Enter child name or age to search">
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
                        <div class="select-all-box">
                            <input type="checkbox" id="select-all-child">
                            <label for="select-all-child" id="select-all-child-label">Select All</label>
                        </div>
                        <table class="list-table table table-condensed">
                            @foreach ($Childrens as $child)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="common-child child-tab unique-tag"
                                            name="child[]" id="child_{{ $child->childid }}"
                                            value="{{ $child->childid }}"
                                            data-name="{{ $child->name . ' - ' . $child->age }}"
                                            {{ $child->checked }}>
                                    </td>
                                    <td>
                                        <label for="child_{{ $child->childid }}">
                                            <img src="{{ url('assets/media/' . $child->imageUrl) }}"
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
                                <input type="checkbox" id="select-group-child-{{ $group->groupid }}" class="select-group-child" data-groupid="{{ $group->groupid }}">
                                <label for="select-group-child-{{ $group->groupid }}">{{ $group->name }}</label>
                            </div>
                            <table class="list-table table table-condensed">
                                @foreach ($group->Childrens as $child)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="common-child child-group"
                                                name="child[]" data-groupid="{{ $group->groupid }}"
                                                id="child_{{ $child->childid }}"
                                                value="{{ $child->childid }}" {{ $child->checked }}>
                                        </td>
                                        <td>
                                            <label for="child_{{ $child->childid }}">
                                                <img src="{{ url('assets/media/' . $child->imageUrl) }}"
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
                                <input type="checkbox" class="select-room-child" id="select-room-child-{{ $room->roomid }}" data-roomid="{{ $room->roomid }}">
                                <label for="select-room-child-{{ $room->roomid }}">{{ $room->name }}</label>
                            </div>
                            <table class="list-table table table-condensed">
                             
                                @foreach ($room->Childrens as $child)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="common-child child-room"
                                                name="child[]" data-roomid="{{ $room->roomid }}"
                                                id="child_{{ $child->childid }}"
                                                value="{{ $child->childid }}" {{ $child->checked }}>
                                        </td>
                                        <td>
                                            <label for="child_{{ $child->childid }}">
                                                <img src="{{ url('assets/media/' . $child->imageUrl) }}"
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




@endsection

@push('scripts')


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


   CKEDITOR.replace('about', {
    height: 150,
    contentsCss: [
        'https://cdn.ckeditor.com/4.22.1/full-all/contents.css',
        'https://ckeditor.com/docs/ckeditor4/4.22.1/examples/assets/mentions/contents.css'
    ],
    extraPlugins: 'format,list', // Only if you need plugins not in the build (optional)
    toolbar: [
        { name: 'clipboard', items: ['Undo', 'Redo'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Strike'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
        { name: 'links', items: ['Link', 'Unlink'] },
        { name: 'styles', items: ['Format'] }
    ]
});


            $(document).on('click', "#select-all-child", function() {           
                //check if this checkbox is checked or not
                if ($(this).is(':checked')) {
                    alert();
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

