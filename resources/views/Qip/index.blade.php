<style>
    /* DataTables pagination <li> background and border theme accent */
    .theme-purple .dataTables_paginate ul.pagination li,
    .theme-blue .dataTables_paginate ul.pagination li,
    .theme-cyan .dataTables_paginate ul.pagination li,
    .theme-green .dataTables_paginate ul.pagination li,
    .theme-orange .dataTables_paginate ul.pagination li,
    .theme-blush .dataTables_paginate ul.pagination li {
        border: 1px solid var(--sd-accent) !important;
        background: #fff !important;
        color: var(--sd-accent) !important;
        border-radius: 4px;
        margin: 0 2px;
        transition: background 0.2s, color 0.2s;
    }
    .theme-purple .dataTables_paginate ul.pagination li.active,
    .theme-blue .dataTables_paginate ul.pagination li.active,
    .theme-cyan .dataTables_paginate ul.pagination li.active,
    .theme-green .dataTables_paginate ul.pagination li.active,
    .theme-orange .dataTables_paginate ul.pagination li.active,
    .theme-blush .dataTables_paginate ul.pagination li.active {
        background: var(--sd-accent) !important;
        color: #fff !important;
        border-color: var(--sd-accent) !important;
        font-weight: bold;
    }
    .theme-purple .dataTables_paginate ul.pagination li:not(.active):hover,
    .theme-blue .dataTables_paginate ul.pagination li:not(.active):hover,
    .theme-cyan .dataTables_paginate ul.pagination li:not(.active):hover,
    .theme-green .dataTables_paginate ul.pagination li:not(.active):hover,
    .theme-orange .dataTables_paginate ul.pagination li:not(.active):hover,
    .theme-blush .dataTables_paginate ul.pagination li:not(.active):hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
        border-color: var(--sd-accent) !important;
    }
</style>
<style>
    /* DataTables pagination theme accent styles */
    .theme-purple .dataTables_paginate .paginate_button,
    .theme-blue .dataTables_paginate .paginate_button,
    .theme-cyan .dataTables_paginate .paginate_button,
    .theme-green .dataTables_paginate .paginate_button,
    .theme-orange .dataTables_paginate .paginate_button,
    .theme-blush .dataTables_paginate .paginate_button {
        color: var(--sd-accent) !important;
        background: #fff !important;
        border: 1px solid var(--sd-accent) !important;
        border-radius: 4px;
        margin: 0 2px;
        transition: background 0.2s, color 0.2s;
    }
    .theme-purple .dataTables_paginate .paginate_button.current,
    .theme-blue .dataTables_paginate .paginate_button.current,
    .theme-cyan .dataTables_paginate .paginate_button.current,
    .theme-green .dataTables_paginate .paginate_button.current,
    .theme-orange .dataTables_paginate .paginate_button.current,
    .theme-blush .dataTables_paginate .paginate_button.current {
        background: var(--sd-accent) !important;
        color: #fff !important;
        border-color: var(--sd-accent) !important;
        font-weight: bold;
    }
    .theme-purple .dataTables_paginate .paginate_button:not(.current):hover,
    .theme-blue .dataTables_paginate .paginate_button:not(.current):hover,
    .theme-cyan .dataTables_paginate .paginate_button:not(.current):hover,
    .theme-green .dataTables_paginate .paginate_button:not(.current):hover,
    .theme-orange .dataTables_paginate .paginate_button:not(.current):hover,
    .theme-blush .dataTables_paginate .paginate_button:not(.current):hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
        border-color: var(--sd-accent) !important;
    }
</style>
<style>
    /* Theme accent for DataTables export buttons with btn-primary/buttons-copy/buttons-html5/buttons-print/btn-round classes */
    .theme-purple .btn-primary.buttons-copy,
    .theme-blue .btn-primary.buttons-copy,
    .theme-cyan .btn-primary.buttons-copy,
    .theme-green .btn-primary.buttons-copy,
    .theme-orange .btn-primary.buttons-copy,
    .theme-blush .btn-primary.buttons-copy,
    .theme-purple .btn-primary.buttons-html5,
    .theme-blue .btn-primary.buttons-html5,
    .theme-cyan .btn-primary.buttons-html5,
    .theme-green .btn-primary.buttons-html5,
    .theme-orange .btn-primary.buttons-html5,
    .theme-blush .btn-primary.buttons-html5,
    .theme-purple .btn-primary.buttons-print,
    .theme-blue .btn-primary.buttons-print,
    .theme-cyan .btn-primary.buttons-print,
    .theme-green .btn-primary.buttons-print,
    .theme-orange .btn-primary.buttons-print,
    .theme-blush .btn-primary.buttons-print,
    .theme-purple .btn.btn-round.btn-primary,
    .theme-blue .btn.btn-round.btn-primary,
    .theme-cyan .btn.btn-round.btn-primary,
    .theme-green .btn.btn-round.btn-primary,
    .theme-orange .btn.btn-round.btn-primary,
    .theme-blush .btn.btn-round.btn-primary {
        background: var(--sd-accent) !important;
        border-color: var(--sd-accent) !important;
        color: #fff !important;
        border-radius: 4px;
    }
</style>
<style>
    /* Theme accent for DataTables export buttons with btn-primary/buttons-copy/buttons-html5 classes */
    .theme-purple .btn-primary.buttons-copy,
    .theme-blue .btn-primary.buttons-copy,
    .theme-cyan .btn-primary.buttons-copy,
    .theme-green .btn-primary.buttons-copy,
    .theme-orange .btn-primary.buttons-copy,
    .theme-blush .btn-primary.buttons-copy,
    .theme-purple .btn-primary.buttons-html5,
    .theme-blue .btn-primary.buttons-html5,
    .theme-cyan .btn-primary.buttons-html5,
    .theme-green .btn-primary.buttons-html5,
    .theme-orange .btn-primary.buttons-html5,
    .theme-blush .btn-primary.buttons-html5 {
        background: var(--sd-accent) !important;
        border-color: var(--sd-accent) !important;
        color: #fff !important;
        border-radius: 4px;
    }
</style>
<!-- Theme accent color overrides for specific elements when a theme is active -->
<style>
    /* Add New button accent */
    .theme-purple .btn-outline-info,
    .theme-blue .btn-outline-info,
    .theme-cyan .btn-outline-info,
    .theme-green .btn-outline-info,
    .theme-orange .btn-outline-info,
    .theme-blush .btn-outline-info {
        border-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    .theme-purple .btn-outline-info:hover,
    .theme-blue .btn-outline-info:hover,
    .theme-cyan .btn-outline-info:hover,
    .theme-green .btn-outline-info:hover,
    .theme-orange .btn-outline-info:hover,
    .theme-blush .btn-outline-info:hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
    }
    /* Center dropdown accent */
    .theme-purple .btn-outline-primary,
    .theme-blue .btn-outline-primary,
    .theme-cyan .btn-outline-primary,
    .theme-green .btn-outline-primary,
    .theme-orange .btn-outline-primary,
    .theme-blush .btn-outline-primary {
        border-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    .theme-purple .btn-outline-primary:hover,
    .theme-blue .btn-outline-primary:hover,
    .theme-cyan .btn-outline-primary:hover,
    .theme-green .btn-outline-primary:hover,
    .theme-orange .btn-outline-primary:hover,
    .theme-blush .btn-outline-primary:hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
    }
</style>
@extends('layout.master')
@section('title', 'Qip List')
@section('parentPageTitle', '')
@section('content')

<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -50px;">

    @if(!empty($permissions['addQip']) && $permissions['addQip'])

    @if(Auth::user()->userType != 'Parent')
    <!-- Filter Button -->

    &nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-outline-info" onclick="window.location.href='{{ route('qip.addnew') }}'"><i
            class="icon-plus" style="margin-right: 5px;"></i>Add New</button>
    @endif &nbsp;&nbsp;&nbsp;
    @endif

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



<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">

            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable c_list">
                        <thead class="thead-light">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Educators</th>
                                @if(!empty($permissions['editQip']) && $permissions['editQip'])
                                <th>Edit</th> @endif
                                @if(!empty($permissions['deleteQip']) && $permissions['deleteQip'])

                                <th>Delete</th>
                                @endif
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
                                @if(!empty($permissions['editQip']) && $permissions['editQip'])

                                <td>
                                    <button class="btn btn-sm btn-info"
                                        onclick="openEditSuperadminModal({{ $qips['id'] }})">
                                        <i class="fa-solid fa-pen-to-square fa-beat-fade"></i> Edit
                                    </button>
                                </td>
                                @endif
                                @if(!empty($permissions['deleteQip']) && $permissions['deleteQip'])

                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSuperadmin({{ $qips['id'] }})">
                                        <i class="fa-solid fa-trash fa-fade"></i> Delete
                                    </button>
                                </td>
                                @endif
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
