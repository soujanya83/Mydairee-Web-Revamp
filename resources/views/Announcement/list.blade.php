@extends('layout.master')
@section('title', 'Announcements')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    transition: all 0.2s ease;
}

img {
    transition: all 0.2s ease;
}

img:hover {
    transform: scale(1.1);
    cursor: pointer;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
}

.border-start {
    border-left-width: 4px !important;
}

.text-sm small {
    font-size: 0.875rem;
}

/* Custom scrollbar for better mobile experience */
.table-responsive::-webkit-scrollbar {
    height: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>


<style>


        main{
padding-block:4em;
padding-inline:2em;
    }
    @media screen and (max-width: 600px) {
    main{

padding-inline:0;
    }
}

</style>
@endsection
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
        <button class="btn btn-outline-info btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
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
                        <a href="{{ route('announcements.create', ['centerid' => $selectedCenter ?? $centers->first()->id]) }}" class="btn btn-outline-info btn-lg">ADD NEW</a>
                    @endif
                </div>

</div>

<main class="py-4">
    <div class="container-fluid px-3 px-md-4">
        @if($records->isEmpty())
            <!-- Empty State -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-bullhorn fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Announcements Found</h4>
                            <p class="text-muted mb-4">You don't have any announcement data yet. Get started by creating your first announcement.</p>
                            <a href="" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-home me-2"></i>Go Back Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Announcements List -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0">
                                    <i class="fas fa-bullhorn me-2"></i>Announcements List
                                </h5>
                                <div class="badge bg-white text-primary fs-6">
                                    {{ $records->total() ?? count($records) }} Total
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>S.no</th>
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
                                            @php $media = json_decode($announcement->announcementMedia, true); @endphp
                                            <tr>
<td data-label="ID">
    <span class="id-badge">
        {{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}
    </span>
</td>

                                                <td>{{ ucfirst($announcement->title) }}</td>
                                                <td>
                                                    @if (!empty($media) && is_array($media))
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach (array_slice($media, 0, 2) as $img)
                                                                <img src="{{ asset('assets/media/' . $img) }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" alt="Media">
                                                            @endforeach
                                                            @if(count($media) > 2)
                                                                <div class="d-flex align-items-center justify-content-center bg-light rounded" style="width: 50px; height: 50px;">
                                                                    <small class="text-muted">+{{ count($media) - 2 }}</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-muted"><i class="fas fa-image me-1"></i>No media</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                            <small class="text-white fw-bold">{{ strtoupper(substr($announcement->createdBy, 0, 1)) }}</small>
                                                        </div>
                                                        <span>{{ ucfirst($announcement->createdBy) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($announcement->eventDate)->format('d M Y') }}</div>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($announcement->eventDate)->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge fs-6 {{ $announcement->status == 'Sent' ? 'bg-success' : ($announcement->status == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                        <i class="fas {{ $announcement->status == 'Sent' ? 'fa-check' : ($announcement->status == 'Pending' ? 'fa-clock' : 'fa-times') }} me-1"></i>
                                                        {{ ucfirst($announcement->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <!-- View -->
                                                        <a href="{{ route('announcements.view', $announcement->id) }}" class="btn btn-outline-success btn-sm" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <!-- Edit -->
                                                        @if($permissions && $permissions->updateAnnouncement == 1)
                                                            <a href="{{ route('announcements.create', $announcement->id) }}" class="btn btn-outline-info btn-sm" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif

                                                        <!-- Delete -->
                                                        @if($permissions && $permissions->deleteAnnouncement == 1)
                                                            <form action="{{ route('announcements.delete') }}" method="POST" class="d-inline m-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="announcementid" value="{{ $announcement->id }}">
                                                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" title="Delete">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                                    No announcements found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        @if(!$records->isEmpty())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <div class="bg-white rounded shadow-sm p-3">
                            {{ $records->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
