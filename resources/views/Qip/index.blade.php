@extends('layout.master')
@section('title', 'Qip')
@section('parentPageTitle', '')



@section('content')




<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;">


    @if(Auth::user()->userType != 'Parent')
    <!-- Filter Button -->

    &nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-outline-info"
        onclick="window.location.href='{{ route('qip.addnew') }}'"><i class="icon-plus"
            style="margin-right: 5px;"></i>Add New</button>
    @endif &nbsp;&nbsp;&nbsp;


    <div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="centerDropdown"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-brands fa-centercode"
                style="margin-right: 5px;"></i>{{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
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



<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Qip<small></small> </h2>
            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable c_list">
                        <thead class="thead-light">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Educators</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Educators</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($SelfAssessment as $index => $qips)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="c_name">{{ $qips['name'] }} </span>
                                </td>
                                <td>{{ $qips['name'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info"
                                        onclick="openEditSuperadminModal({{ $qips['id'] }})">
                                        <i class="fa-solid fa-pen-to-square fa-beat-fade"></i> Edit
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSuperadmin({{ $qips['id'] }})">
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
</div>




@include('layout.footer')
@stop