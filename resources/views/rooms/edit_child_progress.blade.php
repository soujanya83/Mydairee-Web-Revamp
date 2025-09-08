@extends('layout.master')
@section('title', 'Edit')
@section('parentPageTitle', 'Children')




@section('content')

<div>
    <div class="row clearfix" style="margin-bottom: 43px;">
        <div class="col-lg-12 col-md-12 mb-1">
            <div class="card shadow-sm border-0 rounded p-3 hover-shadow">

                <form action="{{ route('update_child_progress', $data->id) }}" id="form-child" method="POST"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstname">First Name <span style="color:red">*</span></label>
                                <input type="text" name="firstname" id="firstname" class="form-control"
                                    value="{{ old('firstname', $data->name) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastname">Last Name <span style="color:red">*</span></label>
                                <input type="text" name="lastname" id="lastname" class="form-control"
                                    value="{{ old('lastname', $data->lastname) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dob">Date of Birth <span style="color:red">*</span></label>
                                <input type="date" name="dob" id="dob" class="form-control"
                                    value="{{ old('dob', $data->dob) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="doj">Date of Join <span style="color:red">*</span></label>
                                <input type="date" name="startDate" id="doj" class="form-control"
                                    value="{{ old('startDate', $data->startDate) }}" required>
                            </div>
                        </div>
                        <input type="hidden" name="centerid" value="{{ $data->centerid }}">
                        <input type="hidden" name="roomid" value="{{ $data->room }}">
                        <div class="form-row">

                            {{-- <div class="form-group col-md-6">
                                <label for="roomid">Select Room <span style="color:red">*</span></label>
                                <select name="roomid" id="roomid" class="form-control" required>
                                    <option value="">-- Select Room --</option>
                                    @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ $data->room == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>

                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="form-group col-md-6">
                                <label for="uploadImg">Choose Image</label><br>

                                <input id="uploadImg" name="file" class="form-control" type="file" style="height: 36px;">
                                @if($data->imageUrl)
                                <img src="{{ asset($data->imageUrl) }}" width="80" class="mb-2 mt-2">
                                @endif
                            </div>


                            <div class="form-group col-md-6">
                                <label for="status">Status <span style="color:red">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="Active" {{ old('status', $data->status) == 'Active' ? 'selected'
                                        : '' }}>Active</option>
                                    {{-- <option value="Enrolled" {{ old('status', $data->status) == 'Enrolled' ?
                                        'selected' : '' }} >Enrolled</option> --}}
                                    <option value="In Active" {{ old('status', $data->status) == 'In Active' ?
                                        'selected' : '' }}>In Active</option>
                                </select>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="gender">Gender <span style="color:red">*</span></label>
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male"
                                            value="Male" {{ $data->gender == 'Male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female"
                                            value="Female" {{ $data->gender == 'Female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other"
                                            value="Other" {{ $data->gender == 'Other' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other">Other</label>
                                    </div>
                                </div>
                            </div>



                            @php
                            $days = str_pad($data->daysAttending ?? '', 5, '0', STR_PAD_RIGHT);
                            @endphp

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Days Attending <span style="color:red">*</span></label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="days[]" id="mon"
                                                value="mon" {{ $days[0]=='1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mon">Monday</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="days[]" id="tue"
                                                value="tue" {{ $days[1]=='1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tue">Tuesday</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="days[]" id="wed"
                                                value="wed" {{ $days[2]=='1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="wed">Wednesday</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="days[]" id="thu"
                                                value="thu" {{ $days[3]=='1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="thu">Thursday</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="days[]" id="fri"
                                                value="fri" {{ $days[4]=='1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="fri">Friday</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        {{-- <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a> --}}
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>

@include('layout.footer')
@stop
