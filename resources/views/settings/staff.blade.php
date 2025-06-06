
@extends('layout.master')
@section('title', 'Staff Settings')
@section('parentPageTitle', 'Settings')


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
                <h2>Staff Settings<small></small> </h2>  
<button class="btn btn-outline-info" style="float:right;margin-bottom:20px;" data-toggle="modal" data-target="#addCenterModal">
<i class="fa fa-plus"></i>&nbsp; Add Staff
</button>                    
            </div>
            <div class="body">
            <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable js-exportable c_list">
        <thead class="thead-light">
            <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
          
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
            <th>Sr. No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
          
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($staff as $index => $staffs)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                    @php
        $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg', 'avatar10.jpg'];
        $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg', 'avatar7.jpg'];
        $avatars = $staffs->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
        $defaultAvatar = $avatars[array_rand($avatars)];
    @endphp
    <img src="{{ $staffs->imageUrl ? asset($staffs->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}" class="rounded-circle avatar" alt="">
    <span class="c_name">{{ $staffs->name }} </span>
                   </td>
                    <td>{{ $staffs->email }}</td>
                    <td>{{ $staffs->contactNo }}</td>
                    <td>
    <button class="btn btn-sm btn-info" onclick="openEditcenterModal({{ $staffs->id }})">
        <i class="fa-solid fa-pen-to-square fa-beat-fade"></i> Edit
    </button>
</td>
<td>
    <button class="btn btn-sm btn-danger" onclick="deletecenter({{ $staffs->id }})">
        <i class="fa-solid fa-trash fa-fade"></i> Delete
    </button>
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







@include('layout.footer')
@stop