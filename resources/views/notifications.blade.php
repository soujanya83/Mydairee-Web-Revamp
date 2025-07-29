@extends('layout.master')
@section('title', 'Notifications List')
@section('parentPageTitle', '')
<!-- Bootstrap 5 CSS -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@section('content')


<button href="{{ route('notifications.markAllRead') }}" class="btn btn-outline-info"
    class="d-flex justify-content-between align-items-center" style="margin-left:88%;margin-top:-52px">Mark all
    read</button>
<hr>

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
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">

            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title & Message</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (auth()->user()->notifications as $key => $notification)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <strong>{{ $notification->data['title'] ?? 'No Title' }}</strong><br>
                                {{ $notification->data['message'] ?? 'No Message' }}
                            </td>
                            <td>
                                {{ $notification->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <i class="fa fa-eye" style="font-size: 22px; color: rgb(11, 185, 190); cursor: pointer;"
                                    title="Read Notification" data-bs-toggle="modal" data-bs-target="#notificationModal"
                                    data-title="{{ $notification->data['title'] ?? 'No Title' }}"
                                    data-message="{{ $notification->data['message'] ?? 'No Message' }}"
                                    data-time="{{ $notification->created_at->diffForHumans() }}">
                                </i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Notification Details Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <strong id="modalTitle">Title</strong>
                <p id="modalMessage">Message</p>
                <small id="modalTime">Time</small>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('notificationModal');
    modal.addEventListener('show.bs.modal', function (event) {
        const icon = event.relatedTarget;
        const title = icon.getAttribute('data-title');
        const message = icon.getAttribute('data-message');
        const time = icon.getAttribute('data-time');

        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('modalTime').textContent = time;
    });
    });
</script>

@stop
