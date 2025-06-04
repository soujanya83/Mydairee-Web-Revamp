@extends('layout.master')
@section('title', 'Centers Settings')
@section('parentPageTitle', 'Centers Settings')


@section('content')


<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Centers Settings<small></small> </h2>  
<button class="btn btn-outline-info" style="float:right;margin-bottom:20px;" data-toggle="modal" data-target="#addCenterModal">
<i class="fa fa-plus"></i>&nbsp; Add Center
</button>                    
            </div>
            <div class="body">
            <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable js-exportable c_list l-parpl">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Center Name</th>
                <th>Street Address</th>
                <th>City Address</th>
                <th>State Address</th>
                <th>Zip</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
            <th>Sr. No.</th>
                <th>Center Name</th>
                <th>Street Address</th>
                <th>City Address</th>
                <th>State Address</th>
                <th>Zip</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($centers as $index => $center)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
    <span class="c_name">{{ $center->centerName }} </span>
                   </td>
                    <td>{{ $center->adressStreet }}</td>
                    <td>{{ $center->addressCity }}</td>
                    <td>{{ $center->addressState }}</td>
                    <td>{{ $center->addressZip }}</td>
                    <td>
    <button class="btn btn-sm btn-info" onclick="openEditSuperadminModal({{ $center->id }})">
        <i class="fa-solid fa-pen-to-square fa-beat-fade"></i> Edit
    </button>
</td>
<td>
    <button class="btn btn-sm btn-danger" onclick="deleteSuperadmin({{ $center->id }})">
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







@include('layout.footer')
@stop