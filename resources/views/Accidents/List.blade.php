  @extends('layout.master')
@section('title', 'Accidents')
@section('parentPageTitle', 'Dashboard')
@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-bg: #f8fafc;
            --dark-text: #1e293b;
            --muted-text: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            color: var(--dark-text);
        }

        .main-container {
            padding: 2rem 1rem;
            min-height: 100vh;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .breadcrumb-custom {
            color: var(--muted-text);
            font-size: 0.95rem;
        }

        .breadcrumb-custom .separator {
            margin: 0 0.75rem;
            opacity: 0.5;
        }

        .program-plan-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .program-plan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 2rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .card-header-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-container {
            padding: 0;
            overflow: hidden;
        }

        .table-responsive {
            border-radius: 0;
            margin: 0;
        }

        .table {
            margin: 0;
            background: white;
        }

        .table thead th {
            background: var(--light-bg);
            color: var(--dark-text);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1.25rem 1rem;
            border: none;
            border-bottom: 2px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border: none;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid var(--border-color);
        }

        .id-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-block;
            min-width: 2.5rem;
            text-align: center;
        }

        .month-badge {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .info-text {
            color: var(--muted-text);
            font-size: 0.875rem;
        }

        .date-text {
            color: var(--dark-text);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        /* Fallback for browsers that don't support gap */
        .action-buttons > * + * {
            margin-left: 0.5rem;
        }

        .btn-action {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-action:hover::before {
            left: 100%;
        }

        .btn-print {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
            color: white;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            color: white;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
            color: white;
        }

        .animated-icon {
            transition: transform 0.2s ease;
        }

        .btn-action:hover .animated-icon {
            transform: scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted-text);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.125rem;
            margin: 0;
        }

        .pagination-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .no-data-message {
            text-align: center;
            color: var(--muted-text);
            font-size: 1.125rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            margin-top: 2rem;
            box-shadow: var(--shadow);
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .table-responsive {
                border-radius: 0;
                margin: 0 -15px;
            }

            .table {
                font-size: 0.875rem;
            }

            .table thead {
                display: none;
            }

            .table, 
            .table tbody, 
            .table tr, 
            .table td {
                display: block;
            }

            .table tbody tr {
                background: white;
                margin-bottom: 1rem;
                border-radius: 0.75rem;
                box-shadow: var(--shadow);
                border: 1px solid var(--border-color);
                overflow: hidden;
                padding: 0;
            }

            .table tbody tr:hover {
                transform: none;
            }

            .table tbody td {
                padding: 0.75rem 1rem;
                border: none;
                border-bottom: 1px solid var(--border-color);
                position: relative;
                padding-left: 35%;
                text-align: right;
            }

            .table tbody td:last-child {
                border-bottom: none;
            }

            .table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                top: 0.75rem;
                font-weight: 600;
                color: var(--dark-text);
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                width: 30%;
                text-align: left;
            }

            .action-buttons {
                justify-content: flex-end;
                gap: 0.375rem;
            }

            .btn-action {
                min-width: 2.25rem;
                height: 2.25rem;
                padding: 0.375rem;
            }

            .month-badge, .id-badge {
                font-size: 0.75rem;
                padding: 0.375rem 0.625rem;
            }

            /* Fix for gap not supported in older browsers */
            .action-buttons > * + * {
                margin-left: 0.375rem;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.75rem;
            }

            .table tbody td {
                padding-left: 40%;
                font-size: 0.8rem;
            }

            .table tbody td::before {
                width: 35%;
                font-size: 0.7rem;
            }

            /* Fix for gap not supported in older browsers */
            .action-buttons > * + * {
                margin-left: 0.25rem;
            }
        }

        /* Bootstrap 4 Compatibility Fixes */
        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
        }

        .btn {
            border-radius: 0.375rem;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Custom Bootstrap 4 Pagination Styles */
        .pagination {
            display: flex;
            list-style: none;
            border-radius: 0.25rem;
            margin: 0;
        }

        .page-item {
            margin: 0 2px;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: 0;
            line-height: 1.25;
            color: var(--primary-color);
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            z-index: 2;
            color: white;
            text-decoration: none;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.2);
        }

        .page-item.active .page-link {
            z-index: 1;
            color: white;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
            opacity: 0.5;
        }

        /* Flex utilities for Bootstrap 4 */
        .d-flex {
            display: flex !important;
        }

        .justify-content-center {
            justify-content: center !important;
        }

        .justify-content-between {
            justify-content: space-between !important;
        }

        .align-items-center {
            align-items: center !important;
        }

        .flex-wrap {
            flex-wrap: wrap !important;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mt-3 {
            margin-top: 1rem !important;
        }

        .px-0 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }

        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
            }
            
            .main-container {
                padding: 0;
            }
            
            .page-header, .pagination-container {
                background: white !important;
                box-shadow: none !important;
            }
            
            .program-plan-card {
                background: white !important;
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            
            .action-buttons {
                display: none !important;
            }
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

                      <div class="btn-group mr-1">
                       <div class="dropdown mr-2">
        @if(empty($rooms))
            <div class="btn btn-outline-info btn-lg dropdown-toggle">NO ROOMS AVAILABLE</div>
        @else
            <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="roomDropdown" data-toggle="dropdown">
                {{ strtoupper($rooms->firstWhere('id', request('roomid', $roomid))->name ?? 'Select Room') }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="roomDropdown">
                @foreach($rooms as $room)
                    <a class="dropdown-item" href="{{ url()->current() }}?centerid={{ $centerid }}&roomid={{ $room->id }}">
                        {{ strtoupper($room->name) }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

                    </div>

                    @if(isset($permission) && $permission->add == 1)
                        <!-- <a href="#" class="btn btn-primary btn-lg top-right-button" id="addnewbtn" data-toggle="modal" data-target="#templateModal">ADD NEW</a> -->
                    @endif

                    @if(Auth::user()->userType != 'Parent')
                 
                     
                        <a href="{{ route('Accidents.create', ['centerid' => $selectedCenter ?? $centers->first()->id,'roomid' => $roomid ?? $rooms->first()->id]) }}" class="btn btn-info btn-lg">ADD NEW ACCIDENT</a>
                    

                    @endif
                </div>

</div>
  <hr class="mt-3">


       <div class="container-fluid px-0" style="padding-block:2em;padding-inline:2em;">
            <div class="program-plan-container">
                <div class="row">
                    <div class="col-12">
                        <div class="program-plan-card">
                            <!-- <div class="card-header-custom">
                                <h5 class="card-header-title">
                                    <i class="fas fa-table"></i>
                                    Program Plans Overview
                                </h5>
                            </div> -->
                            <div class="table-container">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                <tr>
                                    <th scope="col">S No</th>
                                    <th scope="col">Child Name</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                                        <tbody>
                                            <!-- Sample Data Row 1 -->
                                               @foreach ($accidents as $index => $accident)
                                            <tr>
                                                <td data-label="ID">
                                                    <span class="id-badge">{{ $loop->iteration + ($accidents->currentPage() - 1) * $accidents->perPage() }}</span>
                                                </td>
                                                <td data-label="Month">
                                                    <span class="month-badge">
                                                                                    <a  class="text-white" href="{{ route('Accidents.details') }}?id={{ $accident->id }}&centerid={{ $centerid }}&roomid={{ $roomid }}">
    {{ $accident->child_name }}
</a>
                                                    </span>
                                                    <a href="{{ route('Accidents.edit') }}?id={{ $accident->id }}&centerid={{ $centerid }}&roomid={{ $roomid }}"
   class="ml-2 text-info"
   data-toggle="tooltip"
   data-placement="top"
   title="Edit Record">
   <i class="fas fa-pencil-alt"></i>
</a>
                                                </td>
                                                <td data-label="Room">
                                                    <span class="info-text">{{ $accident->username }}</span>
                                                </td>
                                             
                                                <td data-label="Created Date">
                                                    <span class="date-text">{{ \Carbon\Carbon::parse($accident->incident_date)->format('d.m.Y') }}</span>
                                                </td>
                                            
                                            </tr>
   @endforeach
                                            <!-- Empty State Example (uncomment to see) -->
                                            <!--
                                            <tr>
                                                <td colspan="7" class="empty-state">
                                                    <i class="fas fa-clipboard-list"></i>
                                                    <p>No program plans found</p>
                                                </td>
                                            </tr>
                                            -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <nav aria-label="Program plans pagination">
                        <ul class="pagination justify-content-center mb-0">
                          <span class="page-link"> {{ $accidents->appends(request()->query())->links() }}</span>
                           
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @endsection
    @push('scripts')
    	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

         <script>
        // Add smooth animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Add click handlers for action buttons
            const printButtons = document.querySelectorAll('.btn-print');
            const editButtons = document.querySelectorAll('.btn-edit');
            const deleteButtons = document.querySelectorAll('.btn-delete');

            printButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Add your print logic here
                    console.log('Print button clicked');
                    // Example: window.print();
                });
            });

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Add your edit logic here
                    console.log('Edit button clicked');
                });
            });

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Add your delete logic here
                    if (confirm('Are you sure you want to delete this program plan?')) {
                        console.log('Delete confirmed');
                        // Add delete logic here
                    }
                });
            });

            // Add loading animation for buttons
            const actionButtons = document.querySelectorAll('.btn-action');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    this.disabled = true;
                    
                    // Simulate loading (remove this in production)
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }, 1000);
                });
            });
        });

        // Responsive table enhancement
        function makeTableResponsive() {
            const table = document.querySelector('.table');
            const headers = table.querySelectorAll('thead th');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].textContent);
                    }
                });
            });
        }

        // Initialize responsive table
        makeTableResponsive();

        // Re-initialize on window resize
        window.addEventListener('resize', makeTableResponsive);
    </script>
        

   
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});


// rtooltip ends 
    $(document).ready(function () {
        $('#new-accident-btn').on('click', function (event) {
            var _centerid = $(this).data('centerid');
            var _roomid = $(this).data('roomid');
            var _url = '{{ url("accident/add") }}' + '?centerid=' + _centerid + '&roomid=' + _roomid;
            window.location.href = _url;
        });
    });

    $('#roomId').on('change', function (event) {
        var _centerid = $('#centerid').val();
        var _roomid = $('#roomId').val();
        var _url = '{{ url("accident") }}' + '?centerid=' + _centerid + '&roomid=' + _roomid;
        window.location.href = _url;
    });

    $("#centerid").on('change', function () {
        let _centerid = $(this).val();
        $.ajax({
            url: '{{ route("Accidents.getCenterRooms") }}',
            type: 'POST',
            data: {
                centerid: _centerid,
                _token: '{{ csrf_token() }}'
            },
        }).done(function (res) {
            if (res.Status === "SUCCESS") {
                $("#roomId").empty();
                $("#roomId").append(`<option value="">-- Select Room --</option>`);
                $.each(res.Rooms, function (index, val) {
                    $("#roomId").append(`<option value="${val.id}">${val.name}</option>`);
                });
            } else {
                console.log(res.Message);
                $("#roomId").empty();
                $("#roomId").append(`<option value="">No room found!</option>`);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus);
        });
    });
</script>

    @endpush
    @include('layout.footer')
