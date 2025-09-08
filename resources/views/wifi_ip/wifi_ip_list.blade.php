@extends('layout.master')
@section('title', 'IP List')
@section('parentPageTitle', 'Setting')
<!-- Bootstrap 5 CSS -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@section('content')




<div class="d-flex justify-content-end" style="margin-top: -52px;margin-right:50px">
    <button class="btn btn-outline-info" type="button" data-bs-toggle="modal" data-bs-target="#ingredientModal">
        Add New IP
    </button>


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
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>IP</th>
                            <th>IP Name</th>
                            <th>IP Address</th>
                            <th>IP Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($all_wifi as $index => $wifi)
                        <tr class="{{ $ingredient->colorClass ?? 'xl-default' }}">
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{$wifi->wifi_ip }}</td>
                            <td>{{$wifi->wifi_name }}</td>
                            <td>{{$wifi->wifi_address ?: '--' }}</td>
                            <td>
                                @if($wifi->status == 1)
                               <span style="color:green">Active</span>
                                @else
                               <span style="color:red">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <!-- Change Status Button -->
                                <form action="{{ route('settings.WifiIp.changeStatus', $wifi->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    <button class="btn btn-sm btn-warning" title="IP Status change">
                                        <i class="fas fa-wifi"></i> IP Status
                                    </button>
                                </form>
                                <!-- Delete Button --> &nbsp;
                                <form action="{{ route('settings.WifiIp.destroy', $wifi->id) }}" method="POST"
                                    style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Record Delete">
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


<div class="modal" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ingredientForm" method="POST" action="{{ route('settings.WifiIp.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ingredientModalLabel">Add New IP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>

                <div class="modal-body">
                    <!-- WiFi IP -->
                    <div class="mb-3">
                        <label for="wifiIp" class="form-label">IP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="wifiIp" name="wifi_ip" required
                                placeholder="Enter IP">
                            <button type="button" class="btn btn-secondary" id="checkIpBtn">Check IP</button>
                            <button type="button" class="btn btn-success d-none" id="pasteIpBtn">Paste</button>
                        </div>
                        <small id="ipResult" class="text-muted"></small>
                    </div>

                    <!-- WiFi Name -->
                    <div class="mb-3">
                        <label for="wifiName" class="form-label">IP Name</label>
                        <input type="text" class="form-control" id="wifiName" name="wifi_name" required
                            placeholder="Enter IP Name">
                    </div>

                    <!-- WiFi Address -->
                    <div class="mb-3">
                        <label for="wifiAddress" class="form-label">IP Address</label>
                        <input type="text" class="form-control" id="wifiAddress" name="wifi_address"
                            placeholder="Enter IP Address">
                    </div>

                    <!-- WiFi Status -->
                    <div class="mb-3">
                        <label class="form-label d-block">IP Status</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="wifiActive" name="wifi_status"
                                    value="1" checked>
                                <label class="form-check-label" for="wifiActive">Active</label>
                            </div>
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="radio" id="wifiInactive" name="wifi_status"
                                    value="0">
                                <label class="form-check-label" for="wifiInactive">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" id="ingredientSaveBtn">Save IP</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let currentIP = "";

    $(document).ready(function () {

        // Check IP button click
        $('#checkIpBtn').click(function () {
            $('#ipResult').text("Fetching your IP...");
            $.getJSON("https://api.ipify.org?format=json", function (data) {
                currentIP = data.ip;
                $('#ipResult').text("Your IP: " + currentIP);
                $('#pasteIpBtn').removeClass('d-none'); // Show paste button
            });
        });

        // Paste IP button click
        $('#pasteIpBtn').click(function () {
            if (currentIP !== "") {
                $('#wifiIp').val(currentIP);
            }
        });

        // Edit modal fill
        $('.edit-ingredient-btn').click(function () {
            const id      = $(this).data('id');
            const ip      = $(this).data('ip');
            const name    = $(this).data('name');
            const address = $(this).data('address');
            const status  = $(this).data('status');
            const action  = $(this).data('action');

            $('#wifiIp').val(ip);
            $('#wifiName').val(name);
            $('#wifiAddress').val(address);
            $("input[name='wifi_status'][value='"+status+"']").prop('checked', true);

            $('#ingredientForm').attr('action', action);
            $('#formMethod').val('PUT');
            $('#ingredientModalLabel').text('Edit Wifi IP');
            $('#ingredientSaveBtn').text('Update');

            $('#ingredientModal').modal('show');
        });

        // Reset modal
        $('#ingredientModal').on('hidden.bs.modal', function () {
            $('#ingredientForm').attr('action', "{{ route('settings.WifiIp.store') }}");
            $('#formMethod').val('POST');
            $('#wifiIp').val('');
            $('#wifiName').val('');
            $('#wifiAddress').val('');
            $("input[name='wifi_status'][value='1']").prop('checked', true);
            $('#ingredientModalLabel').text('Add New Wifi IP');
            $('#ingredientSaveBtn').text('Save IP');
            $('#ipResult').text('');
            $('#pasteIpBtn').addClass('d-none');
            currentIP = "";
        });
    });
</script>


@include('layout.footer')
@stop
