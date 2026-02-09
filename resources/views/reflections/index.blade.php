@extends('layout.master')
@section('title', 'Reflections')
@section('parentPageTitle', '')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .reflection-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        background: #fff;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .reflection-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .image-carousel {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .carousel-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .carousel-image.active {
        opacity: 1;
    }

    .carousel-indicators {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
    }

    .carousel-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-indicator.active {
        background: white;
        transform: scale(1.2);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px;
        position: relative;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-date {
        position: absolute;
        top: 15px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        backdrop-filter: blur(10px);
    }

    .card-body {
        padding: 25px;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #667eea;
    }

    .children-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 25px;
    }

    .child-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 8px;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        min-width: 80px;
    }

    .child-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .child-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #667eea;
        margin-bottom: 8px;
    }

    .child-name {
        font-size: 0.75rem;
        font-weight: 500;
        color: #495057;
        line-height: 1.2;
    }

    .educators-list {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .educator-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 15px;
        background: linear-gradient(135deg, #667eea20, #764ba220);
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .educator-item:hover {
        background: linear-gradient(135deg, #667eea30, #764ba230);
        transform: translateY(-1px);
    }

    .educator-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #667eea;
    }

    .educator-name {
        font-size: 0.9rem;
        font-weight: 500;
        color: #495057;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .btn-action {
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-edit {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        max-height: 35px;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #218838, #1ba085);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-print {
        background: linear-gradient(135deg, #57e4bf, #0f88bc);
        color: white;
        max-height: 35px;
    }

    .btn-print:hover {
        background: linear-gradient(135deg, #84eddd, #0b5c73);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        color: white;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #c82333, #d91a72);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        color: white;
    }

    .no-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 3rem;
    }

    @media (max-width: 768px) {
        .reflection-card {
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px;
        }

        .card-body {
            padding: 20px;
        }

        .children-grid {
            justify-content: center;
        }

        .educators-list {
            justify-content: center;
        }

        .card-actions {
            justify-content: center;
        }
    }


    .status-badge {
        z-index: 10;
            position: absolute;
            top: 15px;
            right: 20px;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-published {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .status-draft {
            background: rgba(255, 193, 7, 0.9);
            color: #856404;
        }
</style>

<style>
    .pagination {
        font-size: 0.9rem;
        /* Slightly larger for better readability */
        justify-content: center;
        /* Ensure pagination is centered */
        margin-bottom: 80px;
    }

    .page-item .page-link {
        padding: 0.5rem 0.75rem;
        /* Bootstrap 4 default padding for better spacing */
        font-size: 0.9rem;
        /* Match pagination font size */
        line-height: 1.5;
        /* Improved line height for readability */
        border-radius: 0.25rem;
        /* Keep your custom border radius */
        color: #007bff;
        /* Bootstrap primary color for links */
        background-color: #fff;
        /* Ensure background matches Bootstrap */
        border: 1px solid #dee2e6;
        /* Bootstrap default border */
    }

    .page-item.active .page-link {
        background-color: #007bff;
        /* Bootstrap primary color for active state */
        border-color: #007bff;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        /* Bootstrap disabled color */
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }

    /* SVG icons for Previous/Next arrows */
    .page-item .page-link svg {
        width: 1em;
        /* Slightly larger for better visibility */
        height: 1em;
        vertical-align: middle;
    }
</style>


<style>
    .modal-right {
        position: fixed;
        margin: auto;
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal-right .modal-dialog {
        position: fixed;
        margin: 0;
        right: 0; /* Force to right */
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal-right .modal-content {
        height: 100%;
        overflow-y: auto;
        border-radius: 0px;
    }

    .modal-right.fade .modal-dialog {
        right: -320px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }

    .modal-right.fade.show .modal-dialog {
        right: 0;
    }
</style>


<!-- Bootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">


<!-- Theme-scoped overrides: apply only when a theme is active -->
<style>
    /* Keep defaults above intact for No Theme. */
    /* Theme-only accents under body[class*="theme-"] */

    body[class*="theme-"] .card-header,
    body[class*="theme-"] .image-carousel,
    body[class*="theme-"] .no-image-placeholder {
        background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent)) !important;
        color: #000;
    }

    body[class*="theme-"] .section-title i {
        color: var(--sd-accent);
    }

    body[class*="theme-"] .child-avatar,
    body[class*="theme-"] .educator-avatar {
        border-color: var(--sd-accent);
    }

    /* Outline buttons pick up theme accent */
    body[class*="theme-"] .btn-outline-primary,
    body[class*="theme-"] .btn-outline-info {
        border-color: var(--sd-accent);
        color: var(--sd-accent);
    }

    body[class*="theme-"] .btn-outline-primary:hover,
    body[class*="theme-"] .btn-outline-info:hover {
        background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent));
        color: #000;
    }

    /* Action buttons use accent */
    body[class*="theme-"] .btn-edit,
    body[class*="theme-"] .btn-print {
        background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent));
        color: #000;
    }

    body[class*="theme-"] .btn-edit:hover,
    body[class*="theme-"] .btn-print:hover {
        background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent));
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        color: #000;
    }

    /* Pagination accent */
    body[class*="theme-"] .page-item .page-link {
        color: var(--sd-accent);
        border-color: var(--sd-accent);
    }

    body[class*="theme-"] .page-item.active .page-link,
    body[class*="theme-"] .page-item .page-link:hover {
        background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent));
        color: #000;
        border-color: var(--sd-accent);
    }

    /* Center dropdown active item */
    body[class*="theme-"] .dropdown-item.active,
    body[class*="theme-"] .dropdown-item.text-primary {
        color: var(--sd-accent) !important;
    }
</style>
@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -50px;">





@if(Auth::user()->userType != 'Parent')
                      <!-- Filter Button -->
<button class="btn btn-outline-primary btn-lg mr-1 filterbutton" data-toggle="modal"
        data-backdrop="static" data-target="#filtersModal">
        <i class="fa-solid fa-filter" style="margin-right: 5px;"></i> FILTERS
</button>
&nbsp;&nbsp;&nbsp;
<button type="button" class="btn btn-outline-info" onclick="window.location.href='{{ route('reflection.addnew') }}'"><i class="icon-plus" style="margin-right: 5px;"></i>Add New</button>
@endif &nbsp;&nbsp;&nbsp;


<div class="dropdown">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa-brands fa-centercode" style="margin-right: 5px;"></i> {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}

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

</div>





<div class="container mt-4">
    <div class="row" id="observations-list">
        @forelse($reflection as $reflectionItem)
        @php
        $statusClass = strtolower($reflectionItem->status) === 'published' ? 'status-published' : 'status-draft';
        @endphp

        <div class="col-lg-6 col-md-12">
        <span class="status-badge {{ $statusClass }}">{{ $reflectionItem->status }}</span>
            <div class="card reflection-card">
                {{-- Image Carousel --}}
                <div class="image-carousel">

                    @if($reflectionItem->media && $reflectionItem->media->count() > 0)
                    @foreach($reflectionItem->media as $index => $media)
                    <img src="{{ asset($media->mediaUrl) }}" alt="Reflection Image"
                        class="carousel-image {{ $index === 0 ? 'active' : '' }}">
                    @endforeach
                    @if($reflectionItem->media->count() > 1)
                    <div class="carousel-indicators">
                        @foreach($reflectionItem->media as $index => $media)
                        <div class="carousel-indicator {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @else
                    <div class="no-image-placeholder">
                        <i class="fas fa-image"></i>
                    </div>
                    @endif
                </div>

                {{-- Card Header --}}
                <div class="card-header">
                    <h5 class="card-title">{!! $reflectionItem->title !!}</h5>
                    <div class="card-date">
                        <i class="fas fa-calendar-alt"></i>
                        @if ($reflectionItem->created_at)
    {{ (new \DateTime($reflectionItem->created_at))->format('M d, Y') }}
@endif
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body">
                    {{-- Children Section --}}
                    @if($reflectionItem->children && $reflectionItem->children->count() > 0)
                    <div class="section-title">
                        <i class="fas fa-child"></i>
                        Children
                    </div>
                    <div class="children-grid"  style="
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    gap: 16px;
    padding: 8px 0;
    white-space: nowrap;
    flex-wrap: nowrap;
    max-width: 100vw;
  ">
                        @foreach($reflectionItem->children as $childRelation)

                        @if($childRelation->child)
                        <div class="child-item"   style="
          min-width: 110px;
          flex: 0 0 auto;
          text-align: center;
          background: #fff;
          border-radius: 12px;
          box-shadow: 0px 2px 6px rgba(0,0,0,0.07);
          padding: 12px 0;
        ">
                            <img src="{{ $childRelation->child->imageUrl ? asset($childRelation->child->imageUrl) : 'https://e7.pngegg.com/pngimages/565/301/png-clipart-computer-icons-app-store-child-surprise-in-collection-game-child.png' }}"
                                alt="{{ $childRelation->child->name }}" class="child-avatar">
                            <div class="child-name">{{ $childRelation->child->name }}</div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif

                    {{-- Educators Section --}}
                    @if($reflectionItem->staff && $reflectionItem->staff->count() > 0)
                    <div class="section-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Educators
                    </div>
                    <div class="educators-list" style="
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    gap: 16px;
    padding: 8px 0;
    white-space: nowrap;
    flex-wrap: nowrap;
    max-width: 100vw;
  ">
                        @foreach($reflectionItem->staff as $staffRelation)

                        @php
                        $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg',
                        'avatar10.jpg'];
                        $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg',
                        'avatar7.jpg'];
                        $avatars = ($staffRelation->staff->gender ?? 'FEMALE') === 'FEMALE' ? $femaleAvatars : $maleAvatars;
                        $defaultAvatar = $avatars[array_rand($avatars)];
                        @endphp


                        @if($staffRelation->staff)
                        <div class="educator-item" style="
          min-width: 110px;
          flex: 0 0 auto;
          text-align: center;

          box-shadow: 0px 2px 6px rgba(0,0,0,0.07);
          padding: 12px 6px;
        ">
                            <img src="{{ $staffRelation->staff->imageUrl ? asset($staffRelation->staff->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}"
                                alt="{{ $staffRelation->staff->name }}" class="educator-avatar">
                            <div class="educator-name">{{ $staffRelation->staff->name }}</div>
                        </div>
                        @endif
                        @endforeach


                    </div>
                    @endif



                    @if(Auth::user()->userType != 'Parent')

                    @if($reflectionItem->Seen && $reflectionItem->Seen->count() > 0)
                    <div class="section-title">
                        <i class="fa-solid fa-users-between-lines"></i>
                        Seen by Parents:
                    </div>

                    <div class="educators-list" style="
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    gap: 16px;
    padding: 8px 0;
    white-space: nowrap;
    flex-wrap: nowrap;
    max-width: 100vw;
  ">
                        @forelse($reflectionItem->Seen as $seen)

                        @php
                        $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg',
                        'avatar10.jpg'];
                        $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg',
                        'avatar7.jpg'];
                        $avatars = $seen->user->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
                        $defaultAvatar = $avatars[array_rand($avatars)];
                        @endphp

                        @if($seen->user && $seen->user->userType === 'Parent')


                        <!-- <li style="margin-bottom: 10px;">
                                                                    <img src="{{ $seen->user->imageUrl  ? asset($seen->user->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}" alt="Profile Image" width="40" height="40" style="border-radius: 50%;">
                                                                    {{ $seen->user->name }} <span style="color: #2196F3;">&#10003;&#10003;</span>
                                                                    </li> -->

                          <div class="educator-item" style="
          min-width: 110px;
          flex: 0 0 auto;
          text-align: center;

          box-shadow: 0px 2px 6px rgba(0,0,0,0.07);
          padding: 12px 6px;
        ">
                            <img src="{{ $seen->user->imageUrl ? asset($seen->user->imageUrl) : asset('assets/img/xs/' . $defaultAvatar) }}"
                                alt="{{ $seen->user->name }}" class="educator-avatar">
                            <div class="educator-name">{{ $seen->user->name }}</div>
                        </div>



                        @endif
                        @empty
                        <li>No parent has seen this yet.</li>
                        @endforelse

                    </div>

                    @endif
                    @endif




                    {{-- Action Buttons --}}
                    <div class="card-actions">
                        @if(!empty($permissions['updateReflection']) && $permissions['updateReflection'])

                        <a href="{{ route('reflection.addnew.optional', ['id' => $reflectionItem->id]) }}"
                            class="btn btn-edit btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif

                        @if(Auth::user()->userType != 'Parent')
                        <a href="{{ route('reflection.print', ['id' => $reflectionItem->id]) }}" target="_blank"
                            class="btn btn-print btn-action">
                            <i class="fas fa-print"></i> Print
                        </a>
                        @else
                        <a href="{{ route('reflection.print', ['id' => $reflectionItem->id]) }}" target="_blank"
                            class="btn btn-print btn-action">
                            <i class="fas fa-eye"></i> View
                        </a>
                        @endif


                        @if(!empty($permissions['deleteReflection']) && $permissions['deleteReflection'])

                        <button class="btn btn-delete btn-action delete-reflection" data-id="{{ $reflectionItem->id }}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> No reflections found.
            </div>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if ($reflection->hasPages())
        <div class="col-12 d-flex justify-content-center mt-4">
            {{ $reflection->links('vendor.pagination.bootstrap-4') }}
        </div>
        @endif
    </div>


</div>

<style>
    /* New close button on the middle left side of the modal */

    .modal-right .close-left {
  position: absolute;
  top: 50%;
  left: -9px; /* Adjust for spacing outside the modal */
  transform: translateY(-50%);
  border: none;
  background: #fff;
  box-shadow: 0 2px 8px rgba(60,60,60,0.10), 0 1.5px 3px rgba(60,60,60,0.08);
  border-radius: 50%;
  font-size: 1.6rem;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s, box-shadow 0.2s, color 0.2s;
  cursor: pointer;
  color: #6c757d; /* Bootstrap secondary text color */
  z-index: 1051;
  outline: none;
}

.modal-right .close-left:hover,
.modal-right .close-left:focus {
  background: #e9ecef; /* Bootstrap's light gray */
  color: #0056b3;      /* Bootstrap's primary darker shade */
  box-shadow: 0 4px 16px rgba(0, 86, 179, 0.12);
}


    </style>


<!-- Filters Modal -->
<div class="modal modal-right" id="filtersModal" tabindex="-1" role="dialog" aria-labelledby="filtersModalRight"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <button type="button" class="close-left" data-dismiss="modal" aria-label="Close">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
    <path d="M18 24L10 14L18 4" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</button>
            <div class="modal-header">
                <h5 class="modal-title">Filters</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="accordion">
                        <!-- Status Filter -->
                        <div class="border">
                            <button class="btn btn-link dropdown-toggle" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Status
                            </button>
                            <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_all" name="obs_status_filter"
                                            class="custom-control-input filter_observation" value="All" checked>
                                        <label class="custom-control-label" for="status_all">All</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_draft" name="obs_status_filter"
                                            class="custom-control-input filter_observation" value="Draft">
                                        <label class="custom-control-label" for="status_draft">Draft</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_published" name="obs_status_filter"
                                            class="custom-control-input filter_observation" value="Published">
                                        <label class="custom-control-label" for="status_published">Published</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Added Filter -->
                        <div class="border">
                            <button class="btn btn-link dropdown-toggle collapsed" data-toggle="collapse"
                                data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Added
                            </button>
                            <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_none" name="filter_added"
                                            class="custom-control-input filter_added" value="None" checked>
                                        <label class="custom-control-label" for="added_none">None</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_today" name="filter_added"
                                            class="custom-control-input filter_added" value="Today">
                                        <label class="custom-control-label" for="added_today">Today</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_this_week" name="filter_added"
                                            class="custom-control-input filter_added" value="This Week">
                                        <label class="custom-control-label" for="added_this_week">This Week</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_this_month" name="filter_added"
                                            class="custom-control-input filter_added" value="This Month">
                                        <label class="custom-control-label" for="added_this_month">This Month</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="added_custom" name="filter_added"
                                            class="custom-control-input filter_added" value="Custom">
                                        <label class="custom-control-label" for="added_custom">Custom Date</label>
                                    </div>
                                    <div id="custom_date_range" style="display:none; margin-top: 10px;">
                                        <input type="date" id="from_date" class="form-control mb-2"
                                            placeholder="From Date">
                                        <input type="date" id="to_date" class="form-control" placeholder="To Date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Child Filter -->
                        <div class="border" style="max-height:450px;overflow-y:auto;">
                            <button class="btn btn-link dropdown-toggle collapsed" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Child
                            </button>
                            <div id="collapseThree" class="collapse" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" class="custom-control-input filter_child"
                                            id="filter_child_selectall" value="All">
                                        <label class="custom-control-label" for="filter_child_selectall">Select
                                            All</label>
                                    </div>
                                    <input type="text" id="childSearchInput" class="form-control mb-3"
                                        placeholder="Search child...">
                                    <!-- Dynamic child checkboxes will be loaded here -->
                                    <div id="child-checkboxes">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Author Filter -->
                        <div class="border" style="max-height:450px;overflow-y:auto;">
                            <button class="btn btn-link dropdown-toggle collapsed" data-toggle="collapse"
                                data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Author
                            </button>
                            <div id="collapseFour" class="collapse" data-parent="#accordion">
                                <div class="p-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input filter_author"
                                            id="filter_author_any" value="Any" checked>
                                        <label class="custom-control-label" for="filter_author_any">Any</label>
                                    </div>
                                    <hr>
                                    <div class="custom-control custom-checkbox">

                                        <input type="checkbox" class="custom-control-input filter_author"
                                            id="filter_author_me" value="Me">
                                        <label class="custom-control-label" for="filter_author_me">Me</label>
                                    </div>

                                    @if(Auth::user()->userType == 'Superadmin')

                                    <hr>

                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" class="custom-control-input filter_staff"
                                            id="filter_staff_selectall" value="All">
                                        <label class="custom-control-label" for="filter_staff_selectall">Select
                                            All</label>
                                    </div>


                                    <input type="text" id="staffSearchInput" class="form-control mb-3"
                                        placeholder="Search staff...">


                                    <div id="staff-checkboxes">

                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-primary" id="btn-apply-filters">Apply Filters</button>
                <button class="btn btn-secondary" id="btn-clear-filters">Clear Filters</button>
            </div>
        </div>
    </div>
</div>

<!-- Observations List Container -->
<div class="container mt-4">
    <div class="row" id="observations-list">
        <!-- Filtered observations will be loaded here -->
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<script>
    $(document).ready(function() {
    // Set CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Custom date range toggle
    $('input[name="filter_added"]').change(function() {
        if ($(this).val() == 'Custom') {
            $('#custom_date_range').show();
        } else {
            $('#custom_date_range').hide();
            $('#from_date').val('');
            $('#to_date').val('');
        }
    });

    // Select All children functionality
    $('#filter_child_selectall').change(function() {
        if ($(this).is(':checked')) {
            $('.filter_child:not(#filter_child_selectall)').prop('checked', true);
        } else {
            $('.filter_child:not(#filter_child_selectall)').prop('checked', false);
        }
    });

    // Individual child checkbox change
    $(document).on('change', '.filter_child:not(#filter_child_selectall)', function() {
        var totalChildCheckboxes = $('.filter_child:not(#filter_child_selectall)').length;
        var checkedChildCheckboxes = $('.filter_child:not(#filter_child_selectall):checked').length;

        if (checkedChildCheckboxes === totalChildCheckboxes) {
            $('#filter_child_selectall').prop('checked', true);
        } else {
            $('#filter_child_selectall').prop('checked', false);
        }
    });



    $('#filter_staff_selectall').change(function() {
        if ($(this).is(':checked')) {
            $('.filter_staff:not(#filter_staff_selectall)').prop('checked', true);
        } else {
            $('.filter_staff:not(#filter_staff_selectall)').prop('checked', false);
        }
    });

    // Individual child checkbox change
    $(document).on('change', '.filter_staff:not(#filter_staff_selectall)', function() {
        var totalChildCheckboxes = $('.filter_staff:not(#filter_staff_selectall)').length;
        var checkedChildCheckboxes = $('.filter_staff:not(#filter_staff_selectall):checked').length;

        if (checkedChildCheckboxes === totalChildCheckboxes) {
            $('#filter_staff_selectall').prop('checked', true);
        } else {
            $('#filter_staff_selectall').prop('checked', false);
        }
    });


    // Main filter function
    function applyFilters() {
        // Get selected child IDs
        var childs = getSelectedChildIds();

        function getSelectedChildIds() {
            var childs = [];
            var hasSelectAll = false;

            $('.filter_child').each(function() {
                if ($(this).prop("checked") == true) {
                    if ($(this).val() == 'All') {
                        hasSelectAll = true;
                    } else {
                        if (childs.indexOf($(this).val()) === -1) {
                            childs.push($(this).val());
                        }
                    }
                }
            });

            // If "Select All" is checked, get all child IDs
            if (hasSelectAll) {
                childs = [];
                $('.filter_child:not(#filter_child_selectall)').each(function() {
                    if (childs.indexOf($(this).val()) === -1) {
                        childs.push($(this).val());
                    }
                });
            }

            return childs;
        }

        // Get selected authors


        var staffs = getSelectedStaffIds();

function getSelectedStaffIds() {
    var staffs = [];
    var hasSelectAll = false;

    $('.filter_staff').each(function () {
        if ($(this).prop("checked") == true) {
            if ($(this).val() == 'All') {
                hasSelectAll = true;
            } else {
                if (staffs.indexOf($(this).val()) === -1) {
                    staffs.push($(this).val());
                }
            }
        }
    });

    // If "Select All" is checked, get all staff IDs
    if (hasSelectAll) {
        staffs = [];
        $('.filter_staff:not(#filter_staff_selectall)').each(function () {
            if (staffs.indexOf($(this).val()) === -1) {
                staffs.push($(this).val());
            }
        });
    }

    return staffs;
}

var authors = [];
// If "Any" or "Me" is checked, push it
if ($('#filter_author_any').is(':checked')) {
    authors.push('Any');
} else if ($('#filter_author_me').is(':checked')) {
    authors.push('Me');
} else {
    // If neither "Any" nor "Me" is checked, treat selected staff as authors
    authors = staffs;
}

        // Get selected observation status
        var observations = [];
        $('.filter_observation').each(function() {
            if ($(this).prop("checked") == true) {
                observations.push($(this).val());
            }
        });

        // Get selected date range
        var added = [];
        var fromDate = '';
        var toDate = '';

        $('.filter_added').each(function() {
            if ($(this).prop("checked") == true) {
                let val = $(this).val();
                if (val == 'None') {
                    added = [];
                    return false;
                } else {
                    added.push(val);
                    if (val === 'Custom') {
                        fromDate = $('#from_date').val();
                        toDate = $('#to_date').val();
                    }
                }
            }
        });

        // AJAX request
        $.ajax({
            type: 'POST',
            url: '/reflection/filters', // Laravel route
            data: {
                childs: childs,
                authors: authors,
                observations: observations,
                added: added,
                fromDate: fromDate,
                toDate: toDate,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
        $('#observations-list').empty(); // your container

        if (response.status === "success") {
            const reflections = response.reflections;

            if (reflections.length === 0) {
                $('#observations-list').append(`
                    <div class="col">
                        <div class="text-center">
                            <h6 class="mb-4">No reflections found matching your filters.</h6>
                            <button class="btn btn-info btn-lg btn-shadow" id="btn-clear-filters-inline">
                                <i class="fa-solid fa-filter-circle-xmark fa-lg" style="color: #74C0FC;"></i>&nbsp;
                                Clear Filters
                            </button>
                        </div>
                    </div>
                `);
            } else {
                reflections.forEach(function(val) {
                    // Media Carousel
                    let imagesHtml = '';
                    let indicatorsHtml = '';
                    if (val.media && val.media.length > 0) {
                        val.media.forEach((mediaItem, index) => {
                            imagesHtml += `
                                <img src="${window.location.origin}/${mediaItem.mediaUrl}" alt="Reflection Image" class="carousel-image ${index === 0 ? 'active' : ''}">
                            `;
                            indicatorsHtml += `<div class="carousel-indicator ${index === 0 ? 'active' : ''}" data-slide="${index}"></div>`;
                        });
                    } else {
                        imagesHtml = `
                            <div class="no-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        `;
                    }

                    // Children Section
                    let childrenHtml = '';
                    if (val.children && val.children.length > 0) {
                        val.children.forEach(childItem => {
                            const imageUrl = childItem.child?.imageUrl || 'https://e7.pngegg.com/pngimages/565/301/png-clipart-computer-icons-app-store-child-surprise-in-collection-game-child.png';
                            childrenHtml += `
                                <div class="child-item" style="
          min-width: 110px;
          flex: 0 0 auto;
          text-align: center;
          background: #fff;
          border-radius: 12px;
          box-shadow: 0px 2px 6px rgba(0,0,0,0.07);
          padding: 12px 0;
        ">
                                    <img src="${window.location.origin}/${imageUrl}" alt="${childItem.child?.name}" class="child-avatar">
                                    <div class="child-name">${childItem.child?.name}</div>
                                </div>
                            `;
                        });
                    }

                    // Educators Section
                    let educatorsHtml = '';
                    if (val.staff && val.staff.length > 0) {
                        val.staff.forEach(staffItem => {
                            const gender = staffItem.staff?.gender === 'FEMALE' ? 'female' : 'male';
                            const imageUrl = staffItem.staff?.imageUrl || `/assets/img/xs/avatar${Math.floor(Math.random() * 10) + 1}.jpg`;
                            educatorsHtml += `
                                <div class="educator-item" style="
          min-width: 110px;
          flex: 0 0 auto;
          text-align: center;

          box-shadow: 0px 2px 6px rgba(0,0,0,0.07);
          padding: 12px 6px;
        ">
                                    <img src="${window.location.origin}/${imageUrl}" alt="${staffItem.staff?.name}" class="educator-avatar">
                                    <div class="educator-name">${staffItem.staff?.name}</div>
                                </div>
                            `;
                        });
                    }

                    let seenparentHtml = '';
                    if (val.seen && val.seen.length > 0) {
                        val.seen.forEach(parentItem => {
                            const gender = parentItem.gender === 'FEMALE' ? 'female' : 'male';
                            const imageUrl = parentItem.imageUrl || `/assets/img/xs/avatar${Math.floor(Math.random() * 10) + 1}.jpg`;
                            seenparentHtml += `
                                <div class="educator-item" style="
          min-width: 110px;
          flex: 0 0 auto;
          text-align: center;

          box-shadow: 0px 2px 6px rgba(0,0,0,0.07);
          padding: 12px 6px;
        ">
                                    <img src="${window.location.origin}/${imageUrl}" alt="${parentItem.name}" class="educator-avatar">
                                    <div class="educator-name">${parentItem.name}</div>
                                </div>
                            `;
                        });
                    }

                    const statusClass = val.status.toLowerCase() === 'published'
                            ? 'status-published'
                            : 'status-draft';

                    $('#observations-list').append(`
                        <div class="col-lg-6 col-md-12">
                        <span class="status-badge ${statusClass}">${val.status}</span>
                            <div class="card reflection-card">
                                <div class="image-carousel">
                                    ${imagesHtml}
                                    ${val.media?.length > 1 ? `<div class="carousel-indicators">${indicatorsHtml}</div>` : ''}
                                </div>

                                <div class="card-header">
                                    <h5 class="card-title">${val.title}</h5>
                                    <div class="card-date">
                                        <i class="fas fa-calendar-alt"></i> ${val.created_at_formatted}
                                    </div>
                                </div>

                                <div class="card-body">
                                    ${childrenHtml ? `
                                        <div class="section-title"><i class="fas fa-child"></i> Children</div>
                                        <div class="children-grid" style="
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    gap: 16px;
    padding: 8px 0;
    white-space: nowrap;
    flex-wrap: nowrap;
    max-width: 100vw;
  ">${childrenHtml}</div>
                                    ` : ''}

                                    ${educatorsHtml ? `
                                        <div class="section-title"><i class="fas fa-chalkboard-teacher"></i> Educators</div>
                                        <div class="educators-list" style="
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    gap: 16px;
    padding: 8px 0;
    white-space: nowrap;
    flex-wrap: nowrap;
    max-width: 100vw;
  ">${educatorsHtml}</div>
                                    ` : ''}

                                    ${seenparentHtml ? `
                                        <div class="section-title"><i class="fas fa-chalkboard-teacher"></i>Seen by Parents:</div>
                                        <div class="educators-list" style="
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    gap: 16px;
    padding: 8px 0;
    white-space: nowrap;
    flex-wrap: nowrap;
    max-width: 100vw;
  ">${seenparentHtml}</div>
                                    ` : ''}

                                    <div class="card-actions">
                                        <a href="/reflection/addnew/${val.id}" class="btn btn-edit btn-action">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="/reflection/print/${val.id}" target="_blank"
                            class="btn btn-print btn-action">
                            <i class="fas fa-print"></i> Print
                        </a>

                                        <button class="btn btn-delete btn-action delete-reflection" data-id="${val.id}">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }

            $('#btn-apply-filters').prop('disabled', false).html('Apply Filters');
            $('#filtersModal').modal('hide');
        } else {
            alert(response.message || 'An error occurred while filtering reflections.');
        }
    },

            error: function(xhr, status, error) {
                console.error('Filter error:', error);
                alert('An error occurred while filtering observations.');
                $('#btn-apply-filters').prop('disabled', false).html('Apply Filters');
            }
        });
    }

    // Apply filters button click
    $('#btn-apply-filters').on('click', function() {
        $(this).prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        );
        applyFilters();
    });

    // Clear filters functionality
    function clearFilters() {
        // Reset status to "All"
        $('#status_all').prop('checked', true);

        // Reset date to "None"
        $('#added_none').prop('checked', true);
        $('#custom_date_range').hide();
        $('#from_date').val('');
        $('#to_date').val('');

        // Uncheck all children
        $('.filter_child').prop('checked', false);

        // Reset author to "Any"
        $('.filter_author').prop('checked', false);
        $('#filter_author_any').prop('checked', true);

        // Reload original observations
        location.reload();
    }

    $('#btn-clear-filters').on('click', function() {
        clearFilters();
    });

    // Clear filters from inline button
    $(document).on('click', '#btn-clear-filters-inline', function() {
        clearFilters();
    });

    // Load children data on modal open
    $('#filtersModal').on('show.bs.modal', function() {
        // Load children list via AJAX if needed
        loadChildren();
        loadStaff();

    });

    // function loadChildren() {
    //     $.ajax({
    //         url: '/observation/get-children',
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.status === 'success') {
    //                 var childCheckboxes = '';
    //                 $.each(response.children, function(index, child) {
    //                     childCheckboxes += `
    //                         <div class="custom-control custom-checkbox mb-4">
    //                             <input type="checkbox" class="custom-control-input filter_child"
    //                                 id="filter_child_${child.id}" value="${child.id}">
    //                             <label class="custom-control-label" for="filter_child_${child.id}">${child.name}</label>
    //                         </div>
    //                     `;
    //                 });
    //                 $('#child-checkboxes').html(childCheckboxes);
    //             }
    //         }
    //     });
    // }

function loadChildren(selectedIds = []) {
    $.ajax({
        url: '/observation/filter/get-children',
        type: 'GET',
        data: { child_ids: selectedIds }, // pass selected IDs
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var childCheckboxes = '';
                $.each(response.children, function(index, child) {
                    childCheckboxes += `
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input filter_child"
                                id="filter_child_${child.id}" value="${child.id}">
                            <label class="custom-control-label" for="filter_child_${child.id}">${child.name}</label>
                        </div>
                    `;
                });
                $('#child-checkboxes').html(childCheckboxes);
            }
        }
    });
}

    $(document).on('input', '#childSearchInput', function () {
    var searchTerm = $(this).val().toLowerCase();
    $('#child-checkboxes .custom-control').each(function () {
        var labelText = $(this).find('label').text().toLowerCase();
        $(this).toggle(labelText.indexOf(searchTerm) !== -1);
    });
});


function loadStaff() {
        $.ajax({
            url: '/observation/get-staff',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var childCheckboxes = '';
                    $.each(response.staff, function(index, child) {
                        childCheckboxes += `
                            <div class="custom-control custom-checkbox mb-4">
                                <input type="checkbox" class="custom-control-input filter_staff"
                                    id="filter_staff_${child.id}" value="${child.id}">
                                <label class="custom-control-label" for="filter_staff_${child.id}">${child.name}</label>
                            </div>
                        `;
                    });
                    $('#staff-checkboxes').html(childCheckboxes);
                }
            }
        });
    }

    $(document).on('input', '#staffSearchInput', function () {
    var searchTerm = $(this).val().toLowerCase();
    $('#staff-checkboxes .custom-control').each(function () {
        var labelText = $(this).find('label').text().toLowerCase();
        $(this).toggle(labelText.indexOf(searchTerm) !== -1);
    });
});


// Allow only one selection among Any, Me, or any staff
$(document).on('change', '.filter_author, .filter_staff', function () {
    const selectedId = $(this).attr('id');

    // If "Any" or "Me" is selected
    if (selectedId === 'filter_author_any' || selectedId === 'filter_author_me') {
        // Uncheck other author options and all staff
        $('.filter_author').not(this).prop('checked', false);
        $('.filter_staff').prop('checked', false);
    }

    // If any staff is selected
    else if ($(this).hasClass('filter_staff')) {
        // Uncheck author checkboxes
        $('.filter_author').prop('checked', false);
    }

    // Optional: Auto-select "Any" if nothing is selected
    setTimeout(function () {
        const anySelected = $('.filter_author:checked, .filter_staff:checked').length;
        if (!anySelected) {
            $('#filter_author_any').prop('checked', true);
        }
    }, 100); // slight delay to let changes settle
});



});
</script>

















{{-- JavaScript for auto-sliding carousel --}}
<script>
    $(document).ready(function() {
    // Auto-sliding carousel functionality
    $('.reflection-card').each(function() {
        const card = $(this);
        const images = card.find('.carousel-image');
        const indicators = card.find('.carousel-indicator');
        let currentIndex = 0;
        let intervalId;

        if (images.length > 1) {
            function showImage(index) {
                images.removeClass('active');
                indicators.removeClass('active');
                images.eq(index).addClass('active');
                indicators.eq(index).addClass('active');
            }

            function nextImage() {
                currentIndex = (currentIndex + 1) % images.length;
                showImage(currentIndex);
            }

            function startCarousel() {
                intervalId = setInterval(nextImage, 4000); // Change image every 4 seconds
            }

            function stopCarousel() {
                clearInterval(intervalId);
            }

            // Start auto-sliding
            startCarousel();

            // Pause on hover
            card.hover(stopCarousel, startCarousel);

            // Manual navigation
            indicators.click(function() {
                currentIndex = $(this).data('slide');
                showImage(currentIndex);
                stopCarousel();
                startCarousel(); // Restart auto-sliding
            });
        }
    });
});
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-reflection').forEach(button => {
            button.addEventListener('click', function () {
                const reflectionId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/reflection/delete/${reflectionId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire('Deleted!', data.message, 'success').then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        });
                    }
                });
            });
        });
    });
</script>

@include('layout.footer')
@stop
