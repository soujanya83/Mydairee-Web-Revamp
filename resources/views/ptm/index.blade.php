@extends('layout.master')
@section('title', 'View PTM')
@section('parentPageTitle', 'PTM')

<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

@section('content')
    <style>
                .bg-gradient-danger {
                    background: linear-gradient(90deg, #dc3545, #b02a37);
                }

                .modal-content {
                    max-width: 480px;
                    margin: auto;
                }

                .modal-header .close {
                    font-size: 1.2rem;
                    opacity: 0.9;
                    text-shadow: none;
                }

                .modal-header .close:hover {
                    opacity: 1;
                }

                .modal-body {
                    font-size: 0.92rem;
                }

                .modal-body ul li i {
                    width: 18px;
                    text-align: center;
                }

                .btn-outline-danger {
                    border-width: 1.5px;
                }

                .bg-gradient-danger {
                    background: linear-gradient(135deg, #dc3545, #b02a37);
                }

                .ptm-wrapper {
                    padding-bottom: 80px;
                    /* Adjust if footer height changes */
                }


                /* --- Layout --- */
                .horizontal-scroll {
                    overflow-x: auto;
                    flex-wrap: nowrap;
                    gap: 15px;
                    padding-bottom: 15px;
                    display: flex;
                }

                .horizontal-scroll::-webkit-scrollbar {
                    display: none;
                }

                /* --- PTM Card Base --- */
                .ptm-card {
                    width: 230px;
                    min-height: 180px;
                    transition: all 0.3s e border-radius: 14px;
                    overflow: hidden;
                    color: #2f3640;
                    border: none;
                    position: relative;
                    flex-shrink: 0;
                    
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
                    cursor: pointer;
                }

                /* Hover effect */
                .ptm-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
                }

                /* --- Status Border / Background Theme --- */
                .ptm-card.published {
                    border-top: 5px solid #28a745;
                }

                .ptm-card.draft {
                    border-top: 5px solid #ffc107;
                }

                .ptm-card.attended {
                    border-top: 5px solid #007bff;
                }

                /* --- Status badge --- */
                .status-badge {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    font-size: 0.75rem;
                    font-weight: 600;
                    border-radius: 30px;
                    padding: 4px 10px;
                    text-transform: capitalize;
                    color: #fff;
                }

                .ptm-card.published .status-badge {
                    background-color: #28a745;
                }

                .ptm-card.draft .status-badge {
                    background-color: #ffc107;
                    color: #212529;
                }

                .ptm-card.attended .status-badge {
                    background-color: #007bff;
                }

                /* --- Card content --- */
                .ptm-card .card-body {
                    padding: 15px 16px 10px;
                }

                .ptm-card .card-title {
                    font-weight: 600;
                    font-size: 1rem;
                    margin-bottom: 6px;
                    color: #1f2d3d;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                /* --- Card text --- */
                .ptm-card .card-text {
                    font-size: 0.88rem;
                    line-height: 1.4;
                    color: #6c757d;
                    margin-bottom: 0.4rem;
                }

                /* --- Truncation --- */
                .text-truncate-single {
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .text-truncate-multi {
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                    overflow: hidden;
                }

                /* --- Action buttons --- */
                .ptm-card .ptm-card-actions {
                    position: absolute;
                    bottom: 10px;
                    right: 14px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .ptm-card .ptm-card-actions a {
                    color: #6c757d;
                    transition: color 0.2s ease, transform 0.2s ease;
                }

                .ptm-card .ptm-card-actions a:hover {
                    color: #000;
                    transform: scale(1.1);
                }

                /* --- Section title --- */
                .section-title {
                    border-left: 4px solid #007bff;
                    padding-left: 10px;
                    font-weight: 600;
                    margin-bottom: 12px;
                    color: #1f2d3d;
                }

                /* --- Responsive --- */
                @media (max-width: 768px) {
                    .ptm-card {
                        width: 100%;
                        min-height: 160px;
                    }
                }
    </style>
    <div class="ptm-wrapper pb-5">
        <div class="text-zero top-right-button-container d-flex justify-content-end"
                 style="margin-right: 20px;margin-top: -45px;">
                <div class="dropdown mr-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="centerDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fab fa-centercode mr-2"></i>
                        {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown">
                        @foreach ($centers as $center)
                            <a href="javascript:void(0);" card
                                class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'act ive font-weight-bold text-primary' : '' }}"
                                data-id="{{ $center->id }}">
                                {{ $center->centerName }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if (auth()->user()->userType !== 'Parent')
                    <button class="btn btn-info shadow-sm px-4 py-2 " style="margin-top:-2px;"
                        onclick="window.location.href='{{ route('ptm.addnew') }}'">
                        <i class="fas fa-plus mr-2"></i> Add New PTM
                    </button>
                @endif
        </div>
        

        {{-- ✅ Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- ✅ Validation Errors (from $errors variable) --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <h6 class="mb-2"><i class="fas fa-times-circle mr-2"></i> Please fix the following errors:</h6>
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif


        

        {{-- ✅ Upcoming PTMs --}}
    
         <h4 class="section-title " style="margin-top: 10px;">Upcoming PTMs</h4>
        <div class="d-flex overflow-auto flex-nowrap horizontal-scroll" style="margin-bottom: -35px;">
            @forelse($upcomingptms as $ptm)
                <div class="card ptm-card {{ strtolower($ptm->status) === 'published' ? 'published' : 'draft' }}">
                    <div class="card-body ptm-card-click" data-ptm='@json($ptm)'>
                        <span class="status-badge">{{ ucfirst($ptm->status) }}</span>
                        <h5 class="card-title text-truncate-single">{{ $ptm->title ?? 'PTM #' . $ptm->id }}</h5>
                        <p class="card-text">
                            <strong>Date:</strong>
                            @php
                                $userType = Auth::user()->userType;

                                $latestReschedule = $ptm->reschedules->sortByDesc('created_at')->first();
                                $fallbackFinalDate = $ptm->finalDate ?? optional($latestReschedule->rescheduledate)->date ?? $ptm->originalDate ?? ($ptm->ptmDates->min('date') ?? null);


                                $fallbackFinalSlot = $ptm->finalSlot ?? optional($latestReschedule->rescheduleslot)->slot ?? $ptm->slot ?? ($ptm->ptmSlots->first()->slot ?? null);

                                $dateToShow = $userType === 'Parent' ? $fallbackFinalDate : $ptm->originalDate;
                            @endphp

                            {{ $dateToShow ? \Carbon\Carbon::parse($dateToShow)->format('d M Y') : 'N/A' }}

                            @if ($userType === 'Parent')
                                <br>
                                <strong>Time:</strong> {{ $fallbackFinalSlot ?? 'N/A' }}
                            @endif
                        </p>



                        {{--  <p class="card-text"><strong>Objective:</strong> <span
                                class="text-truncate-multi">{{ $ptm->objective ?? 'N/A' }}</span></p>  --}}
                        @if ($user->userType === 'Superadmin')
                            <p class="card-text">
                                <strong>Created By:</strong>
                                <span>{{ $ptm->user->name ?? 'Unknown' }}</span>
                            </p>
                        @endif
                    </div>
                    @if ($user->userType === 'Superadmin' || $user->userType === 'Staff')
                        <div class="ptm-card-actions">
                            @if ($user->userType === 'Superadmin' || $ptm->userId === $user->id)
                                @if ($ptm->status === 'Draft')
                                    <a href="#" class="publish-btn" data-ptm-id="{{ $ptm->id }}"
                                        title="Publish PTM"><i class="fas fa-upload"></i></a>
                                    <a href="{{ route('ptm.editptm', $ptm->id) }}" title="Edit PTM"><i
                                            class="fas fa-pen-square"></i></a>
                                @endif
                                <a href="#" class="delete-btn" data-ptm-id="{{ $ptm->id }}"
                                    title="Delete PTM"><i class="fas fa-trash"></i></a>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-muted">No upcoming PTMs.</p>
            @endforelse
        </div>

        <hr>
        {{-- ✅ Attended PTMs --}}
        <h4 class="mb-3 section-title">Attended PTMs</h4>
        <div class="d-flex overflow-auto flex-nowrap horizontal-scroll">
            @forelse($attendedptms as $ptm)
                <div class="card ptm-card attended">
                    <div class="card-body ptm-card-click" data-ptm='@json($ptm)'>
                        <span class="status-badge">Attended</span>
                        <h5 class="card-title text-truncate-single" title="{{ $ptm->title ?? 'PTM #' . $ptm->id }}">
                            {{ $ptm->title ?? 'PTM #' . $ptm->id }}
                        </h5>
                        <p class="card-text">
                            <strong>Date:</strong>
                            @php
                                $userType = Auth::user()->userType;
                                $latestReschedule = $ptm->reschedules->sortByDesc('created_at')->first();
                                $fallbackFinalDate = $ptm->finalDate ?? optional($latestReschedule->rescheduledate)->date ?? $ptm->originalDate ?? ($ptm->ptmDates->min('date') ?? null);
                                $fallbackFinalSlot = $ptm->finalSlot ?? optional($latestReschedule->rescheduleslot)->slot ?? $ptm->slot ?? ($ptm->ptmSlots->first()->slot ?? null);
                                $dateToShow = $userType === 'Parent' ? $fallbackFinalDate : $ptm->originalDate;
                            @endphp

                            {{ $dateToShow ? \Carbon\Carbon::parse($dateToShow)->format('d M Y') : 'N/A' }}

                            @if ($userType === 'Parent')
                                <br>
                                <small><strong>Time:</strong> {{ $fallbackFinalSlot ?? 'N/A' }}</small>
                            @endif
                        </p>
                        {{--  <p class="card-text"><strong>Objective:</strong> <span
                                class="text-truncate-multi">{{ $ptm->objective ?? 'N/A' }}</span></p>  --}}
                        @if ($user->userType === 'Superadmin')
                            <p class="card-text"> <strong>Created By:</strong>
                                <span>{{ $ptm->user->name ?? 'Unknown' }}</span>
                            </p>
                        @endif
                    </div>
                    @if ($user->userType === 'Superadmin')
                        <div class="ptm-card-actions">
                            <a href="#" class="delete-btn text-danger" data-ptm-id="{{ $ptm->id }}"
                                title="Delete PTM">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <h5 class="text-muted">No attended PTMs.</h5>
            @endforelse
        </div>

        {{-- ✅ Publish Confirmation Modal --}}
        <div class="modal fade" id="publishConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Confirm Publish</h5>
                        <button type="button" class="close text-white"
                            data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to <strong>publish</strong> this PTM?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmPublishBtn" class="btn btn-success">Yes, Publish</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ PTM Details Modal --}}
        <div class="modal fade" id="ptmModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content shadow-lg border-0 rounded-3 overflow-hidden">

                    <!-- Header -->
                    <div class="modal-header  text-success border-0 py-2">
                        <h6 class="modal-title d-flex align-items-center mb-0">
                            <i class="fas fa-calendar-alt me-2 text-black mr-2"></i> PTM Details
                        </h6>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body bg-light p-3">
                        <input type="hidden" id="currentPtmId">
                        <h6 id="modal-title" class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="fas fa-info-circle text-black me-2"></i>
                            <span id="modal-title-text"></span>
                        </h6>

                        <ul class="list-unstyled small mb-3 text-secondary">
                            
                            <li>
                                <i class="fas fa-toggle-on text-black me-1"></i>
                                <strong>Status:</strong> <span id="modal-status"></span>
                            </li>
                        </ul>

                        <div>
                            <h6 class="text-black fw-bold mb-2">
                                <i class="fas fa-bullseye me-1"></i> Objective
                            </h6>
                            <div id="modal-objective" class="p-3 bg-white rounded border shadow-sm small text-muted">
                                Discuss academic progress and upcoming goals for the next term.
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0 bg-white py-2 justify-content-center">
                        <button type="button" id="viewPtmBtn" class="btn btn-sm btn-primary px-3">
                            <i class="fas fa-eye me-1"></i> View Full Details
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger px-3" data-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Close
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ✅ Delete Confirmation Modal --}}
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0 rounded-3 overflow-hidden">

                    <!-- Header -->
                    <div class="modal-header bg-gradient-danger text-white border-0">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                            Confirm Deletion
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>

                    <!-- Body -->
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <h5 class="fw-bold mb-2">Are you sure you want to delete this PTM?</h5>
                        <p class="text-muted mb-0">This action cannot be undone.</p>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer d-flex justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-outline-secondary px-4 me-2" data-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <form id="deletePTMForm" method="POST" action="" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fas fa-check me-1"></i> Yes, Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('layout.footer')

    {{-- ✅ Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Show PTM details
            $('.ptm-card-click').on('click', function() {
                const ptm = $(this).data('ptm');
                
                $('#currentPtmId').val(ptm.id);
                $('#modal-title-text').text(ptm.title || `PTM #${ptm.id}`);
                $('#modal-date').text(ptm?.ptm_dates[0]?.date ? new Date(ptm.ptm_dates[0].date)
                    .toLocaleDateString() : 'N/A');
                $('#modal-status').text(ptm.status || 'N/A');
                $('#modal-objective').text(ptm.objective || 'N/A');
                $('#ptmModal').modal('show');
            });

            // Handle View Details button click
            $('#viewPtmBtn').on('click', function() {
                const ptmId = $('#currentPtmId').val();
                
                if (ptmId) {
                    window.location.href = "{{ url('ptm/view') }}/" + ptmId;


                }
            });

            // Publish confirmation
            let publishId = null;
            $('.publish-btn').on('click', function(e) {
                e.preventDefault();
                publishId = $(this).data('ptm-id');
                $('#publishConfirmModal').modal('show');
            });

            $('#confirmPublishBtn').on('click', function() {
                if (publishId) {
                    window.location.href = "{{ url('ptm/directpublish') }}/" + publishId;
                }
            });


            // Delete confirmation
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                const id = $(this).data('ptm-id');
                const actionUrl = "{{ url('ptm/delete') }}/" + id; // RESTful route like /ptm/{id}

                $('#deletePTMForm').attr('action', actionUrl);
                $('#deleteConfirmModal').modal('show');
            });

        });
    </script>

@stop
