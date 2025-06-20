@extends('layout.master')
@section('title', 'Announcements')
@section('parentPageTitle', 'Dashboard')

@section('content')
@if (session('msg'))
    <script>
        $(document).ready(function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('msg') }}",
                icon: 'success'
            });
        });
    </script>
@endif

<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;">

                <div class="text-zero top-right-button-container">

                    <div class="btn-group mr-1">
                        <div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                 style="background-color:white;"  data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>

                    </div>

                    @if(isset($permission) && $permission->add == 1)
                        <!-- <a href="#" class="btn btn-primary btn-lg top-right-button" id="addnewbtn" data-toggle="modal" data-target="#templateModal">ADD NEW</a> -->
                    @endif

                    @if(Auth::user()->userType != 'Parent')
                 
                     
                        <a href="{{ route('announcements.create', ['centerid' => $selectedCenter ?? $centers->first()->id]) }}" class="btn btn-primary btn-lg">ADD NEW</a>
                    

                    @endif
                </div>

</div>
<main style="padding-block:5em;padding-inline:2em;">
    <div class="container-fluid">
        <!-- <div class="row"> -->

            <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
  <h2 class="mb-0">Announcement</h2>
  <p class="mb-0 text-muted mx-md-4">
    <a href="">Dashboard</a><span class="mx-2">|</span> <span>Announcements List</span>
  </p>
</div>



    </div>
    <hr class="mt-3">
  </div>   
        <!-- </div> -->
       

        @if($records->isEmpty())
            <div class="row">
                <div class="col text-center">
                    <h6 class="mb-4">Don't have any Announcement Data.... Create Add New</h6>
                    <a href="" class="btn btn-primary btn-lg">GO BACK HOME</a>
                </div>
            </div>
        @else
        <div class="col-12">
    <div class="card">
        <div class="card-header bg-light">
            <!-- <h5 class="mb-0">Announcements List</h5> -->
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="">
                        <tr>
                            <th>Title</th>
                            <th>Media</th>
                            <th>Created By</th>
                            <th>Event Date</th>
                            <th>Status</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $announcement)
                            <tr>
                                <td>{{ ucfirst($announcement->title) }}</td>
                              @php
    $media = json_decode($announcement->announcementMedia, true);
@endphp

<td>
    @if (!empty($media) && is_array($media))
        @foreach ($media as $img)
            <img class="img-thumbnail" src="{{ asset('assets/media/' . $img) }}" style="width: 80px;" alt="Image">
        @endforeach
    @else
        No image
    @endif
</td>

                                <td>{{ ucfirst($announcement->createdBy) }}</td>
                                <td>{{ \Carbon\Carbon::parse($announcement->eventDate)->format('d.m.Y') }}</td>
                                <td>
                                    <span class="badge badge-pill 
                                        {{ $announcement->status == 'Sent' ? 'badge-success' : 
                                           ($announcement->status == 'Pending' ? 'badge-warning' : 'badge-danger') }}">
                                        {{ ucfirst($announcement->status) }}
                                    </span>
                                </td>
                                <td>
                              <div class="d-flex " style="gap: 0.2rem;">

    {{-- View --}}
    <a href="{{ route('announcements.view', $announcement->id) }}" 
       class="btn btn-outline-success btn-sm me-1" title="View">
        <i class="fas fa-eye"></i>
    </a>

    {{-- Edit --}}
    @if($permissions && $permissions->updateAnnouncement == 1)
        <a href="{{ route('announcements.create', $announcement->id) }}" 
           class="btn btn-outline-primary btn-sm me-1" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    {{-- Delete --}}
    @if($permissions && $permissions->deleteAnnouncement == 1)
        <form action="{{ route('announcements.delete') }}" method="POST" class="m-0">
            @csrf
            @method('DELETE')
            <input type="hidden" name="announcementid" value="{{ $announcement->id }}">
            <button type="button" class="btn btn-outline-danger delete-btn btn-sm"
                   
                    title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    @endif
</div>

                                </td>
                            </tr>
                            
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No announcements found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

        @endif
    </div>
    <div class="d-flex justify-content-center mt-3">
    {{ $records->links() }}
</div>

</main>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
 
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this announcement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if user confirms
                    button.closest('form').submit();
                }
            });
        });
    });
});
</script>


@endpush
@include('layout.footer')