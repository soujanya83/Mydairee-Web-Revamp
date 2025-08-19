@extends('layout.master')
@section('title', 'Permissions Assigned List')
@section('parentPageTitle', '')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include Bootstrap (5.x) if not already -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@section('content')



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
<hr>
<div class="row clearfix">
    <div class=>
        <div class="card">
            <div class="body table-responsive">
                <div class="row mb-3">
                    <div class="col-md-3 ms-auto">
                        <input type="text" id="userSearchInput" class="form-control" placeholder="Search User Name...">
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($assignedUserList as $index => $user)
                        <tr class="{{ $user->colorClass ?? 'xl-default' }}">
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ \Illuminate\Support\Str::title($user->name) }}</td>
                            <td>
                                <a href="{{ route('settings.show.assigned_permissions', ['userId' => $user->id]) }}"
                                    class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>


                                {{-- <div class="modal" id="viewModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="viewModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <form action="{{ route('settings.update_user_permissions', $user->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content card">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewModalLabel{{ $user->id }}">
                                                        <u>Permissions for {{ $user->name }}</u>
                                                    </h5>
                                                    <button type="button" class="btn-close" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body row">
                                                    @php
                                                    $userPermissions = DB::table('permissions')->where('userid',
                                                    $user->id)->first();
                                                    @endphp

                                                    <!-- Select All Checkbox -->
                                                    <div class="col-12 d-flex align-items-center justify-content-between mb-3"
                                                        style="margin-left: 14px; margin-right: 14px;">
                                                        <div>
                                                            <input type="checkbox" class="form-check-input"
                                                                id="selectAll_{{ $user->id }}"
                                                                onclick="toggleAllPermissions({{ $user->id }})">
                                                            <label class="form-check-label fw-bold ms-2"
                                                                for="selectAll_{{ $user->id }}">
                                                                <span style="color: green;"><u>Select All
                                                                        Permissions</u>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <button type="submit" class="btn btn-info"
                                                            style="margin-right: 16px;">
                                                            Save Changes
                                                        </button>
                                                    </div>
                                                    <!-- Individual Permission Checkboxes -->
                                                    @foreach($permissionColumns as $column)
                                                    <div class="col-md-4 mb-2">
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input permission-checkbox-{{ $user->id }}"
                                                                type="checkbox"
                                                                name="permissions[{{ $column['name'] }}]" value="1" {{
                                                                isset($userPermissions->{$column['name']}) &&
                                                            $userPermissions->{$column['name']} ? 'checked' : '' }}>
                                                            <label class="form-check-label" style="color:black">
                                                                {{ $column['label'] }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer  d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById("userSearchInput").addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            let userNameCell = row.querySelector("td:nth-child(2)");
            if (userNameCell) {
                let userName = userNameCell.textContent.toLowerCase();
                row.style.display = userName.includes(filter) ? "" : "none";
            }
        });
    });
</script>

@stop
