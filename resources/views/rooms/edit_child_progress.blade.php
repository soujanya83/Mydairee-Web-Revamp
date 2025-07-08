@extends('layout.master')
@section('title', 'Rooms')
@section('parentPageTitle', 'Children')




@section('content')

<div >
    <h5>Edit Child</h5>

    <hr>


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
                            <div class="form-group col-md-6">
                                <label for="status">Status <span style="color:red">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="Active" {{ old('status', $data->status) == 'Active' ? 'selected'
                                        : '' }}>Active</option>
                                    <option value="Enrolled" {{ old('status', $data->status) == 'Enrolled' ?
                                        'selected' : '' }}>Enrolled</option>
                                    <option value="In Active" {{ old('status', $data->status) == 'In Active' ?
                                        'selected' : '' }}>In Active</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="uploadImg">Choose Image</label><br>

                                <input id="uploadImg" name="file" class="form-control" type="file">
                                @if($data->imageUrl)
                                <img src="{{ asset('storage/' . $data->imageUrl) }}" width="80" class="mb-2">
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="gender">Gender <span style="color:red">*</span></label>
                                <div class="d-flex">
                                    <label><input type="radio" name="gender" value="Male" {{ $data->gender == 'Male'
                                        ? 'checked' : '' }}> Male</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="gender" value="Female" {{ $data->gender ==
                                        'Female' ? 'checked' : '' }}> Female</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="gender" value="Other" {{ $data->gender ==
                                        'Other' ? 'checked' : '' }}> Other</label>
                                </div>
                            </div>


                            @php
                            $days = str_pad($data->daysAttending ?? '', 5, '0', STR_PAD_RIGHT);
                            @endphp

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Days Attending <span style="color:red">*</span></label>
                                    <div class="flexCheck">
                                        <input type="checkbox" name="days[]" value="mon" {{ $days[0]=='1' ? 'checked'
                                            : '' }}> Monday
                                        <input type="checkbox" name="days[]" value="tue" {{ $days[1]=='1' ? 'checked'
                                            : '' }}> Tuesday
                                        <input type="checkbox" name="days[]" value="wed" {{ $days[2]=='1' ? 'checked'
                                            : '' }}> Wednesday
                                        <input type="checkbox" name="days[]" value="thu" {{ $days[3]=='1' ? 'checked'
                                            : '' }}> Thursday
                                        <input type="checkbox" name="days[]" value="fri" {{ $days[4]=='1' ? 'checked'
                                            : '' }}> Friday
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
