@extends('layout.master')
@section('title', 'Permissions Role List')
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

<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;">

    <div class="text-zero top-right-button-container">

      <a href="javascript:void(0)" 
   class="btn btn-outline-info" 
   id="addRoleBtn" 
   style="margin-left:5px;" 
   data-route="{{ route('settings.add-permission-role') }}">
   Add Role
</a>


    </div>

</div>

 <hr class="mt-3">

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
<th>S.No</th>
            <th scope="col">Role Name</th>
            <th scope="col">Created on</th>
            <th scope="col" style="width: 180px;text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
       
       @php
    $sno = 1;
@endphp

@foreach($role as $index => $user)
    <tr class="{{ $user->colorClass ?? 'xl-default' }}">
        <td>{{ $sno++ }}</td>

        <td>{{ \Illuminate\Support\Str::title($user->name) }}</td>
        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>

        <td>
            <a href="{{ route('settings.role-permission', ['id' => $user->id]) }}"
               class="btn btn-sm px-3 py-1 mr-1 rounded-pill"
               style="font-size: 12px; background-color: #126dcf; color: #fff; border: none;">
               <i class="fas fa-eye"></i> View
            </a>

            <a href="{{ route('settings.role-permission', ['id' => $user->id]) }}"
               class="btn btn-sm px-3 py-1 rounded-pill"
               style="font-size: 12px; background-color: #0dc2bfff; color: #fff; border: none;">
               <i class="fas fa-pencil-alt"></i> Edit
            </a>

            <form id="delete-form-{{ $user->id }}"
                  action="{{ route('settings.role-permission-delete', ['id' => $user->id]) }}"
                  method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button"
                        class="btn btn-sm px-3 py-1 rounded-pill delete-btn"
                        data-id="{{ $user->id }}"
                        style="font-size: 12px; background-color: #ec7147ff; color: #fff; border: none;">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </form>
        </td>
    </tr>
@endforeach

   
    </tbody>
</table>

        </div>
    </div>
</div>
</div>

<!-- role modal -->
 <!-- Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="addRoleForm" method="POST" action="">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="roleName">Role Name</label>
            <input type="text" class="form-control" id="roleName" name="role" placeholder="Enter role name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Role</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
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

<!-- JS to trigger modal and set form action -->
<script>
    $(document).ready(function() {
        $('#addRoleBtn').on('click', function() {
            const route = $(this).data('route');
            $('#addRoleForm').attr('action', route); // Set form action dynamically
            $('#addRoleModal').modal('show'); // Show modal
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            let userId = this.getAttribute("data-id");
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("delete-form-" + userId).submit();
                }
            });
        });
    });
});
</script>

@stop