@extends('layout.master')
@section('title', 'Permissions Assign')
@section('parentPageTitle', 'Permissions Assign')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


@section('content')
<style>
    .form-check-inline {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 6px 12px;
        background-color: #f9f9f9;
        box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
    }
</style>


@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-22px">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-22px">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">

            <a class="btn btn-outline-info mt-3" style="float:right;margin-bottom:20px;margin-right:12px"
                href="{{ route('settings.assigned_permissions') }}">
                <i class="fa fa-users"></i>&nbsp; Assigned Users
            </a>
            <div class="card-header">
                <div class="tab-pane active show" id="request" role="tabpanel" aria-labelledby="request-tab">
                    <div class="card-body">
                        <form action="{{ route('settings.assign_permissions') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="user_ids" class="form-label">Select Users</label>
                                <select name="user_ids[]" id="user_ids" class="form-control" multiple required>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Select All Checkbox -->
                            <div class="d-flex justify-content-between align-items-center mb-3 px-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAllPermissions"
                                        onchange="toggleAllPermissions()">
                                    <label for="selectAllPermissions" class="form-check-label fw-bold ms-2">
                                        <span style="color: green"><u>Select All Permissions</u></span>
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-info">
                                    Assign Permissions
                                </button>
                            </div>


                            <!-- Permissions List -->
                            <div class="row" style="padding: 10px;">
                                @foreach($permissionColumns as $col)
                                <div class="form-check form-check-inline"
                                    style="min-width: 200px; margin-bottom: 10px;">
                                    <input type="checkbox" name="permissions[{{ $col['name'] }}]" value="1"
                                        class="form-check-input permission-checkbox" id="perm_{{ $col['name'] }}">
                                    <label for="perm_{{ $col['name'] }}" class="form-check-label">
                                        {{ $col['label'] }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleAllPermissions() {
        const isChecked = document.getElementById('selectAllPermissions').checked;
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(cb => cb.checked = isChecked);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('#user_ids').select2({
        placeholder: "Select users",
        width: '100%'
    });
</script>

@include('layout.footer')

@endsection
