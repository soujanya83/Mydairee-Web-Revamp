@extends('layout.master')
@section('title', 'Announcements')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<style>
    .thumbnail-hover {
        width: 80px;
        height: 80px;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .thumbnail-hover:hover {
        transform: scale(5); /* enlarge */
        z-index: 999;
        position: relative;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
</style>
<style>
    .d-flex-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
@endsection
@section('content')

<hr>
<main>
    <div class="container-fluid">
        <!-- Header -->
        <!-- <div class="row">
            <div class="col-12">
                <h1>View Announcement</h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('announcements.list') }}">Announcements List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Announcement</li>
                    </ol>
                </nav>
                <hr class="my-3">
            </div>
        </div> -->

        <!-- Announcement Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Title & Status -->
                        <div class="d-flex-custom mb-3">
                         @php
    $media = json_decode($Info->announcementMedia, true);
@endphp

<td>
   @if (!empty($media) && is_array($media))
    <div class="d-flex flex-wrap gap-2">
        @foreach ($media as $file)
            @php
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                $isPDF = strtolower($extension) === 'pdf';
            @endphp

            @if ($isImage)
                <img class="img-thumbnail thumbnail-hover" id="annoucementImg"src="{{ asset($file) }}" style="width: 80px;height:80px" alt="Image">
            @elseif ($isPDF)
                <a href="{{ asset($file) }}" target="_blank" class="d-block text-center">
                    <img src="{{ asset('svg/pdf-icon.svg') }}" alt="PDF" style="width: 40px;">
                </a>
            @endif
        @endforeach
    </div>
@else
    <span class="text-muted">No media</span>
@endif

</td>

                            <h5 class="mb-0">{{ $Info->title }}</h5>
                            <span class="badge
    {{ $Info->status === 'Sent' ? 'bg-success text-white' :
       ($Info->status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                                {{ ucfirst($Info->status) }}
                            </span>

                        </div>

                        <!-- Info Row -->
                        <div class="d-flex-custom text-muted mb-3">
                            <span>Event date: {{ \Carbon\Carbon::parse($Info->eventDate)->format('d.m.Y') }}</span>
                            <span>Created at: {{ \Carbon\Carbon::parse($Info->createdAt)->format('d.m.Y') }}</span>
                            <span>Created by: {{ $Info->username }}</span>
                        </div>

                        <hr class="my-3">

                        <!-- Description -->
                        <div class="announcement-text">
                            {!! html_entity_decode($Info->text) !!} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Fullscreen Modal -->
<!-- Half-page Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 50%;">
    <div class="modal-content">
      
      <!-- Modal Header with Close Button -->
      <div class="modal-header border-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-0 text-center">
        <img id="modalImage" src="" class="img-fluid" style="width:10s0%; height:80vh;" alt="Full Image">
      </div>

    </div>
  </div>
</div>




@endsection
@push('scripts')
<script>
 $('#annoucementImg').click(function () {
    let imgSrc = $(this).attr('src');
    $('#modalImage').attr('src', imgSrc); 
    $('#imageModal').modal('show');
});


</script>
@endpush
@include('layout.footer')
