@extends('layout.master')
@section('title', 'Announcements')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
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
                                @foreach ($media as $img)
                                <img class="img-thumbnail" src="{{ asset('assets/media/' . $img) }}"
                                    style="width: 80px;" alt="Image">
                                @endforeach
                                @else
                                No image
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

@endsection
@include('layout.footer')
