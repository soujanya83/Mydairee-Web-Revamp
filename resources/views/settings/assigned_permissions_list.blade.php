@extends('layout.master')
@section('title', 'Permissions Assigned List')
@section('parentPageTitle', '')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include Bootstrap (5.x) if not already -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .btn {
        font-size: 10px;
    }
</style>
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
         <table class="table  table-hover align-middle  rounded">
    <thead class="thead-light">
        <tr>

            <th scope="col">User Name</th>
            <th scope="col" style="width: 180px;text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assignedUserList as $index => $user)
        <tr class="{{ $user->colorClass ?? 'xl-default' }}">
   
            <td>{{ \Illuminate\Support\Str::title($user->name) }}</td>
            <td>
                <a href="{{ route('settings.show.assigned_permissions', ['userId' => $user->id]) }}"
                   class="btn btn-sm px-3 py-1 mr-1 rounded-pill"
                   style="font-size: 12px; background-color: #126dcf; color: #fff; border: none;">
                   <i class="fas fa-eye"></i> View
                </a>

                <a href="{{ route('settings.show.assigned_permissions', ['userId' => $user->id]) }}"
                   class="btn btn-sm px-3 py-1 rounded-pill"
                   style="font-size: 12px; background-color: #076a91; color: #fff; border: none;">
                   <i class="fas fa-pencil-alt"></i> Edit
                </a>
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
    document.getElementById("userSearchInput").addEventListener("keyup", function() {
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