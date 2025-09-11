@extends('layout.master')
@section('title', 'Public Holiday List')
@section('parentPageTitle', 'Setting')
<!-- Bootstrap 5 CSS -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<!-- Bootstrap 5 JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->


@section('content')




<div class="d-flex justify-content-end" style="margin-top: -52px;margin-right:50px">
    <a href="{{ route('announcements.create') }}" class="btn btn-outline-info">
        Add New Holiday
    </a>


</div>
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

                <form method="GET" action="{{ route('settings.public_holiday') }}" class="mb-3 d-flex gap-3">
                    <!-- Month Filter -->
                    <select name="month" class="form-control" style="width:150px;margin-left:12px">
                        <option value="">All Months</option>
                        @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ request('month')==$m ? 'selected' : ''
                            }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                            @endfor
                    </select>

                    <!-- Date Filter -->
                    <select name="date" class="form-control" style="width:150px;margin-left:12px">
                        <option value="">All Dates</option>
                        @for ($d = 1; $d <= 31; $d++) <option value="{{ $d }}" {{ request('date')==$d ? 'selected' : ''
                            }}>
                            {{ $d }}
                            </option>
                            @endfor
                    </select>

                    <button type="submit" class="btn btn-info" style="margin-left:12px"><i class="fas fa-filter"></i>
                        Filter</button>
                    <a href="{{ route('settings.public_holiday') }}" class="btn btn-secondary"
                        style="margin-left:12px"><i class="fas fa-refresh"></i> Reset</a>
                </form>



                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Occasion</th>
                            <th>State</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holidayData as $index => $holidays)
                        <tr>
                            <td>{{ $index + 1 }}</td> <!-- Use td instead of th -->
                            <td>{{ $holidays->full_date->format('d M Y') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($holidays->occasion, 75) }}</td>
                            <td>{{ $holidays->state ?: '--' }}</td>
                            <td>
                                @if($holidays->status == 1)
                                <span class="text-success">Active</span>
                                @else
                                <span class="text-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <!-- Change Status Button -->
                                <form action="{{ route('settings.holiday.changeStatus', $holidays->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    <button class="btn btn-sm btn-warning" title="Wifi IP Status change">
                                        <i class="fas fa-refresh"></i> Status
                                    </button>
                                </form>
                                &nbsp;
                                <button class="btn btn-sm btn-primary edit-holiday-btn p-2"
                                    data-id="{{ $holidays->id }}"
                                    data-date="{{ $holidays->full_date->format('Y-m-d') }}"
                                    data-occasion="{{ $holidays->occasion }}" data-state="{{ $holidays->state }}"
                                    data-status="{{ $holidays->status }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                &nbsp;
                                <!-- Delete Button -->
                                <form action="{{ route('settings.holiday.destroy', $holidays->id) }}" method="POST"
                                    style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger p-2" title="Record Delete">
                                        <i class="fas fa-trash-alt"></i>
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


<div class="modal" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="holidayForm" method="POST" action="{{ route('settings.holiday.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="holidayModalLabel">Add New Public Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>

                <div class="modal-body">
                    <!-- Date -->
                    <div class="mb-3">
                        <label for="holidayDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="holidayDate" name="date" required>
                    </div>

                    <!-- State -->
                    <div class="mb-3">
                        <label for="holidayState" class="form-label">State</label>
                        <input type="text" class="form-control" id="holidayState" name="state" required
                            placeholder="Enter State Name">
                    </div>

                    <!-- Occasion -->
                    <div class="mb-3">
                        <label for="holidayOccasion" class="form-label">Occasion</label>
                        <input type="text" class="form-control" id="holidayOccasion" name="occasion" required
                            placeholder="Enter Occasion">
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label d-block">Status</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="holidayActive" name="status" value="1"
                                    checked>
                                <label class="form-check-label" for="holidayActive">Active</label>
                            </div>
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="radio" id="holidayInactive" name="status"
                                    value="0">
                                <label class="form-check-label" for="holidayInactive">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" id="holidaySaveBtn">Save Holiday</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="holidayEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="holidayEditForm" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button> --}}
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" id="editDate" required>
                    </div>

                    <div class="mb-3">
                        <label>Occasion</label>
                        <input type="text" class="form-control" name="occasion" id="editOccasion" required>
                    </div>

                    <div class="mb-3">
                        <label>State</label>
                        <input type="text" class="form-control" name="state" id="editState" required>
                    </div>

                    <div class="mb-3">
                        <label>Status</label><br>
                        <label><input type="radio" name="status" value="1" id="editStatusActive"> Active</label>
                        <label style="margin-left:15px;"><input type="radio" name="status" value="0"
                                id="editStatusInactive"> Inactive</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.edit-holiday-btn').click(function() {
            let id = $(this).data('id');
            let date = $(this).data('date');
            let occasion = $(this).data('occasion');
            let state = $(this).data('state');
            let status = $(this).data('status');

            // fill modal fields
            $('#editDate').val(date);
            $('#editOccasion').val(occasion);
            $('#editState').val(state);

            if (status == 1) {
                $('#editStatusActive').prop('checked', true);
            } else {
                $('#editStatusInactive').prop('checked', true);
            }

            // set form action
            $('#holidayEditForm').attr('action', '/settings/holiday/update/' + id);

            // open modal
            $('#holidayEditModal').modal('show');
        });
    });
</script>


<script>
    $(document).ready(function() {
        // Edit modal fill
        $('.edit-holiday-btn').click(function() {
            const id = $(this).data('id');
            const date = $(this).data('date');
            const state = $(this).data('state');
            const occasion = $(this).data('occasion');
            const status = $(this).data('status');
            const action = $(this).data('action');

            $('#holidayDate').val(date);
            $('#holidayState').val(state);
            $('#holidayOccasion').val(occasion);
            $("input[name='status'][value='" + status + "']").prop('checked', true);

            $('#holidayForm').attr('action', action);
            $('#holidayModalLabel').text('Edit Public Holiday');
            $('#holidaySaveBtn').text('Update');

            // $('#holidayModal').modal('show');
        });

        // Reset modal
        $('#holidayModal').on('hidden.bs.modal', function() {
            $('#holidayForm').attr('action', "{{ route('settings.holiday.store') }}");
            $('#holidayForm')[0].reset();
            $('#holidayModalLabel').text('Add New Public Holiday');
            $('#holidaySaveBtn').text('Save Holiday');
        });
    });
</script>




@stop