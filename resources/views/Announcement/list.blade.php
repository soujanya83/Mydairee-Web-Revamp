@extends('layout.master')
@section('title', 'Announcements')
@section('parentPageTitle', 'Dashboard')

@section('content')
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
  <h2 class="mb-0">Program Plan</h2>
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
            <div class="col-12 list">
                @foreach($records as $announcement)
                    <div class="card d-flex flex-row mb-3">
                        <div class="card-body d-flex justify-content-between">
                            <a href="" class="list-item-heading mb-0 truncate w-40">{{ ucfirst($announcement->title) }}</a>
                            <p class="mb-0 text-muted text-small w-15">{{ ucfirst($announcement->createdBy) }}</p>
               <p>{{ \Carbon\Carbon::parse($announcement->eventDate)->format('d.m.Y') }}</p>
                            <span class="badge badge-pill {{ $announcement->status == 'Sent' ? 'badge-success' : ($announcement->status == 'Pending' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($announcement->status) }}
                            </span>
                            <div class="btn-group">
                                <a href="" class="btn btn-outline-success btn-xs">
                                    <i class="simple-icon-eye"></i>
                                </a>
                                @if(auth()->user()->can('update', $announcement))
                                    <a href="{{ route('announcements.create', $announcement->id) }}" class="btn btn-outline-primary btn-xs">
                                        <i class="simple-icon-pencil"></i>
                                    </a>
                                @endif
                                @if(auth()->user()->can('delete', $announcement))
                                    <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                     <button type="submit" class="btn btn-outline-danger btn-xs" onclick="return confirm('Do you really want to remove this item?');">
    <i class="fa fa-trash"></i>
</button>

                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</main>

@endsection
