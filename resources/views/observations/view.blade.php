@extends('layout.master')
@section('title', 'Observation Details')
@section('parentPageTitle', 'Observations')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    /* Custom CSS for Observation View */
    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .nav-tabs-custom .nav-link:hover {
        border-color: transparent;
        background-color: #f8f9fa;
        color: #495057;
    }

    .nav-tabs-custom .nav-link.active {
        background-color: transparent;
        border-bottom-color: #007bff;
        color: #007bff;
        font-weight: 600;
    }

    /* THEME SYSTEM: Accent color for nav tabs, card header, and highlights */
    body[class*="theme-"] {
        --sd-accent: #4f8cff;
    }
    body.theme-blue { --sd-accent: #4f8cff; }
    body.theme-green { --sd-accent: #28a745; }
    body.theme-pink { --sd-accent: #fb249b; }
    body.theme-orange { --sd-accent: #ff9800; }
    body.theme-purple { --sd-accent: #7c3aed; }
    body.theme-teal { --sd-accent: #20c997; }

    body[class*="theme-"] .nav-tabs-custom .nav-link.active,
    body[class*="theme-"] .nav-tabs-custom .nav-link {
        border-bottom-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    body[class*="theme-"] .nav-tabs-custom .nav-link.active {
        font-weight: 700;
    }
    body[class*="theme-"] .card-header,
    body[class*="theme-"] .card-title {
        color: var(--sd-accent) !important;
    }
    body[class*="theme-"] .info-card {
        border-left-color: var(--sd-accent) !important;
    }

    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #007bff;
    }

    .info-card h5 {
        margin-bottom: 15px;
        color: #495057;
        font-weight: 600;
    }

    .media-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }

    .media-item img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
    }

    .file-icon {
        text-align: center;
        padding: 10px;
        background: #e9ecef;
        border-radius: 4px;
    }

    .file-icon p {
        font-size: 12px;
        margin: 5px 0 0 0;
        word-break: break-all;
    }

    .child-card {
        display: flex;
        align-items: center;
        padding: 15px;
        background: white;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: box-shadow 0.3s ease;
    }

    .child-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .child-avatar {
        margin-right: 15px;
    }

    .child-info h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }

    .assessment-summary,
    .milestone-summary,
    .eylf-summary {
        margin-bottom: 30px;
    }

    .stat-card {
        text-align: center;
        padding: 20px;
        border-radius: 8px;
        color: white;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .subject-group,
    .outcome-group,
    .age-group {
        background: white;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #dee2e6;
        margin-bottom: 20px;
    }

    .subject-title,
    .outcome-title,
    .age-group-title,
    .category-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }

    .assessment-item,
    .outcome-item,
    .milestone-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .assessment-item:last-child,
    .outcome-item:last-child,
    .milestone-item:last-child {
        border-bottom: none;
    }

    .item-content {
        flex: 1;
    }

    .item-content strong {
        color: #495057;
        font-size: 0.95rem;
    }

    .sub-item,
    .milestone-name {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 3px;
    }

    .item-status {
        flex-shrink: 0;
    }

    .item-icon {
        margin-right: 10px;
    }

    .milestone-category {
        margin-left: 20px;
        border-left: 2px solid #e9ecef;
        padding-left: 15px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        opacity: 0.5;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.6em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .nav-tabs-custom .nav-link {
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .assessment-item,
        .outcome-item,
        .milestone-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .item-status {
            margin-top: 10px;
        }

        .stat-card {
            margin-bottom: 10px;
        }

        .milestone-category {
            margin-left: 10px;
            padding-left: 10px;
        }
    }

    /* Print styles */
    @media print {
        .nav-tabs-custom {
            display: none;
        }

        .tab-content .tab-pane {
            display: block !important;
            opacity: 1 !important;
        }

        .card {
            border: none;
            box-shadow: none;
        }
    }
</style>
@section('content')

@if(!empty($permissions['updateObservation']) && $permissions['updateObservation'])
<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;margin-bottom:30px;">
    <button type="button" class="btn btn-success shadow-lg btn-animated mr-2"
        onclick="window.location.href='{{ route('observation.addnew.optional', ['id' => $observation->id]) }}'">
        <i class="fas fa-edit mr-1"></i> Edit
    </button>

</div>
@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-eye mr-2"></i>Observation Details
                        <small class="text-muted ml-2">#{{ $observation->id }}</small>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Main Navigation Tabs -->
                    <ul class="nav nav-tabs nav-tabs-new2" id="mainTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="observation-tab" data-toggle="tab" href="#observation"
                                role="tab">
                                <i class="fas fa-clipboard-list mr-1"></i>Observation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="child-tab" data-toggle="tab" href="#child" role="tab">
                                <i class="fas fa-child mr-1"></i>Child
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="montessori-tab" data-toggle="tab" href="#montessori" role="tab">
                                <i class="fas fa-graduation-cap mr-1"></i>Montessori
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="eylf-tab" data-toggle="tab" href="#eylf" role="tab">
                                <i class="fas fa-star mr-1"></i>EYLF
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="devmilestone-tab" data-toggle="tab" href="#devmilestone" role="tab">
                                <i class="fas fa-chart-line mr-1"></i>Development
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="mainTabContent">

                        <!-- Observation Tab -->
                        <div class="tab-pane fade show active" id="observation" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-card">
                                        <h5><i class="fas fa-info-circle text-primary mr-2"></i>Basic Information</h5>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Date:</strong></td>
                                                <td>
                                                    {{ $observation->created_at ? $observation->created_at->format('M d,
                                                    Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Time:</strong></td>
                                                <td>
                                                    {{ $observation->created_at ? $observation->created_at->format('h:i
                                                    A') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <span class="badge badge-success">{{ ucfirst($observation->status ??
                                                        'Active') }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                <div class="info-card">
                                        <h5><i class="fas fa-info-circle text-primary mr-2"></i>Observation Information</h5>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Title:</strong></td>
                                                <td style="max-width:350px; word-break:break-word; white-space:pre-line;">
                                                    {!! $observation->obestitle ? html_entity_decode($observation->obestitle) : 'Not Update' !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Observation:</strong></td>
                                                <td style="max-width:350px; word-break:break-word; white-space:pre-line;">
                                                {!! $observation->title ? html_entity_decode($observation->title) : 'Not Update' !!}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Notes:</strong></td>
                                                <td style="max-width:350px; word-break:break-word; white-space:pre-line;">
                                                {!! $observation->notes ? html_entity_decode($observation->notes) : 'Not Update' !!}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Reflection:</strong></td>
                                                <td style="max-width:350px; word-break:break-word; white-space:pre-line;">
                                                {!! $observation->reflection ? html_entity_decode($observation->reflection) : 'Not Update' !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Future Plan:</strong></td>
                                                <td style="max-width:350px; word-break:break-word; white-space:pre-line;">
                                                {!! $observation->future_plan ? html_entity_decode($observation->future_plan) : 'Not Update' !!}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Child Voice:</strong></td>
                                                <td style="max-width:350px; word-break:break-word; white-space:pre-line;">
                                                {!! $observation->child_voice ? html_entity_decode($observation->child_voice) : 'Not Update' !!}

                                                </td>
                                            </tr>
                                            
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="info-card">
                                        <h5><i class="fas fa-images text-info mr-2"></i>Media Files</h5>
                                        @if($observation->media && $observation->media->count() > 0)
                                        <div class="media-gallery">
                                            @foreach($observation->media as $media)
                                            <div class="media-item">
                                                @if(in_array(pathinfo($media->mediaUrl, PATHINFO_EXTENSION), ['jpg',
                                                'jpeg', 'png', 'gif']))
                                                <img src="{{ asset($media->mediaUrl) }}" alt="Media"
                                                    class="img-thumbnail">
                                                @else
                                                <div class="file-icon">
                                                    <i class="fas fa-file-alt fa-3x text-muted"></i>
                                                    <p>{{ basename($media->mediaUrl) }}</p>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="text-muted">No media files attached</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Child Tab -->
                        <div class="tab-pane fade" id="child" role="tabpanel">
                            <div class="info-card">
                                <h5><i class="fas fa-child text-success mr-2"></i>Child Information</h5>
                                @if($observation->child && $observation->child->count() > 0)
                                <div class="row">
                                    @foreach($observation->child as $childLink)
                                    @if($childLink->child)
                                    <div class="col-md-6 mb-3">
                                        <div class="child-card">
                                            <div class="child-avatar">
                                                @if($childLink->child->imageUrl)
                                                <img src="{{ asset($childLink->child->imageUrl) }}" width="45px" height="45px" style="border-radius:50%;" >
                                                @else
                                                <i class="fas fa-user-circle fa-3x text-primary"></i>
                                                @endif
                                            </div>
                                            <div class="child-info">
                                                <h6>{{ $childLink->child->name }}</h6>


                                                
                                                @php
    $dob = $childLink->child->dob ?? null;
    $ageText = 'N/A';
    if ($dob) {
        $dobCarbon = \Carbon\Carbon::parse($dob); 
        $now = \Carbon\Carbon::now();
        $years = intval($dobCarbon->diffInYears($now));
        $months = intval($dobCarbon->copy()->addYears($years)->diffInMonths($now));
        $ageParts = [];
        if($years) $ageParts[] = $years.' year'.($years > 1 ? 's' : '');
        if($months) $ageParts[] = $months.' month'.($months > 1 ? 's' : '');
        $ageText = $ageParts ? implode(', ', $ageParts) : '0 month';
    }
@endphp

<p class="text-muted mb-1">Age: {{ $ageText }}</p>
                                        <p class="text-muted mb-0">DOB: {{ $dob ? \Carbon\Carbon::parse($dob)->format('d M Y') : 'N/A' }}</p>

                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                @else
                                <p class="text-muted">No children assigned to this observation</p>
                                @endif
                            </div>
                        </div>

                        <!-- Montessori Tab -->
                        <div class="tab-pane fade" id="montessori" role="tabpanel">
                            <div class="info-card">
                                <h5><i class="fas fa-graduation-cap text-warning mr-2"></i>Montessori Assessment</h5>
                                @if($observation->montessoriLinks && $observation->montessoriLinks->count() > 0)
                                <div class="assessment-summary mb-4">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="stat-card bg-danger">
                                                <div class="stat-number">{{
                                                    $observation->montessoriLinks->where('assesment', 'Not
                                                    Assessed')->count() }}</div>
                                                <div class="stat-label">Not Assessed</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-card bg-info">
                                                <div class="stat-number">{{
                                                    $observation->montessoriLinks->where('assesment',
                                                    'Introduced')->count() }}</div>
                                                <div class="stat-label">Introduced</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-card bg-warning">
                                                <div class="stat-number">{{
                                                    $observation->montessoriLinks->where('assesment',
                                                    'Working')->count() }}</div>
                                                <div class="stat-label">Working</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-card bg-success">
                                                <div class="stat-number">{{
                                                    $observation->montessoriLinks->where('assesment',
                                                    'Completed')->count() }}</div>
                                                <div class="stat-label">Completed</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="assessment-details">
                                    @php
                                    $groupedBySubject = $observation->montessoriLinks->groupBy(function($item) {
                                    return $item->subActivity->activity->subject->name ?? 'Unknown';
                                    });
                                    @endphp

                                    @foreach($groupedBySubject as $subjectName => $assessments)
                                    <div class="subject-group mb-4">
                                        <h6 class="subject-title">
                                            <i class="fas fa-book mr-2"></i>{{ $subjectName }}
                                            <span class="badge badge-success ml-2">{{ $assessments->count() }}
                                                items</span>
                                        </h6>
                                        <div class="assessment-items">
                                            @foreach($assessments as $assessment)
                                            <div class="assessment-item">
                                                <div class="item-content">
                                                    <strong>{{ $assessment->subActivity->activity->title ?? 'N/A'
                                                        }}</strong>
                                                    <div class="sub-item">{{ $assessment->subActivity->title ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                <div class="item-status">
                                                    @php
                                                    $statusClass = [
                                                    'Not Assessed' => 'badge-danger',
                                                    'Introduced' => 'badge-info',
                                                    'Working' => 'badge-warning',
                                                    'Completed' => 'badge-success'
                                                    ];
                                                    @endphp
                                                    <span
                                                        class="badge {{ $statusClass[$assessment->assesment] ?? 'badge-secondary' }}">
                                                        {{ $assessment->assesment }}
                                                    </span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="empty-state">
                                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No Montessori assessments recorded for this observation</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- EYLF Tab -->
                        <div class="tab-pane fade" id="eylf" role="tabpanel">
                            <div class="info-card">
                                <h5><i class="fas fa-star text-info mr-2"></i>EYLF Outcomes</h5>
                                @if($observation->eylfLinks && $observation->eylfLinks->count() > 0)
                                <div class="eylf-summary mb-4">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Total EYLF outcomes achieved: <strong>{{ $observation->eylfLinks->count()
                                            }}</strong>
                                    </div>
                                </div>

                                <div class="eylf-details">
                                    @php
                                    $groupedByOutcome = $observation->eylfLinks->groupBy(function($item) {
                                    return $item->subActivity->activity->outcome->title ?? 'Unknown Outcome';
                                    });
                                    @endphp

                                    @foreach($groupedByOutcome as $outcomeTitle => $links)
                                    <div class="outcome-group mb-4">
                                        <h6 class="outcome-title">
                                            <i class="fas fa-target mr-2"></i>{{ $outcomeTitle }}
                                            <span class="badge badge-primary ml-2">{{ $links->count() }}
                                                achievements</span>
                                        </h6>
                                        <div class="outcome-items">
                                            @foreach($links as $link)
                                            <div class="outcome-item">
                                                <div class="item-icon">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                </div>
                                                <div class="item-content">
                                                    <strong>{{ $link->subActivity->activity->title ?? 'N/A' }}</strong>
                                                    <div class="sub-item">{{ $link->subActivity->title ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="empty-state">
                                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No EYLF outcomes recorded for this observation</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Development Milestone Tab -->
                        <div class="tab-pane fade" id="devmilestone" role="tabpanel">
                            <div class="info-card">
                                <h5><i class="fas fa-chart-line text-success mr-2"></i>Development Milestones</h5>
                                @if($observation->devMilestoneSubs && $observation->devMilestoneSubs->count() > 0)
                                <div class="milestone-summary mb-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stat-card bg-info">
                                                <div class="stat-number">{{
                                                    $observation->devMilestoneSubs->where('assessment',
                                                    'Introduced')->count() }}</div>
                                                <div class="stat-label">Introduced</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stat-card bg-warning">
                                                <div class="stat-number">{{
                                                    $observation->devMilestoneSubs->where('assessment', 'Working
                                                    towards')->count() }}</div>
                                                <div class="stat-label">Working Towards</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stat-card bg-success">
                                                <div class="stat-number">{{
                                                    $observation->devMilestoneSubs->where('assessment',
                                                    'Achieved')->count() }}</div>
                                                <div class="stat-label">Achieved</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="milestone-details">
                                    @php
                                    $groupedByAgeGroup = $observation->devMilestoneSubs->groupBy(function($item) {
                                    return $item->devMilestone->milestone->ageGroup ?? 'Unknown Age Group';
                                    });
                                    @endphp

                                    @foreach($groupedByAgeGroup as $ageGroup => $milestones)
                                    <div class="age-group mb-4">
                                        <h6 class="age-group-title">
                                            <i class="fas fa-birthday-cake mr-2"></i>{{ $ageGroup }}
                                            <span class="badge badge-secondary ml-2">{{ $milestones->count() }}
                                                milestones</span>
                                        </h6>

                                        @php
                                        $groupedByMain = $milestones->groupBy(function($item) {
                                        return $item->devMilestone->main->name ?? 'Unknown Category';
                                        });
                                        @endphp

                                        @foreach($groupedByMain as $mainCategory => $categoryMilestones)
                                        <div class="milestone-category mb-3">
                                            <h6 class="category-title">
                                                <i class="fas fa-list-ul mr-2"></i>{{ $mainCategory }}
                                            </h6>
                                            <div class="milestone-items">
                                                @foreach($categoryMilestones as $milestone)
                                                <div class="milestone-item">
                                                    <div class="item-content">
                                                        <div class="milestone-name">{{ $milestone->devMilestone->name ??
                                                            'N/A' }}</div>
                                                    </div>
                                                    <div class="item-status">
                                                        @php
                                                        $statusClass = [
                                                        'Introduced' => 'badge-info',
                                                        'Working towards' => 'badge-warning',
                                                        'Achieved' => 'badge-success'
                                                        ];
                                                        @endphp
                                                        <span
                                                            class="badge {{ $statusClass[$milestone->assessment] ?? 'badge-secondary' }}">
                                                            {{ $milestone->assessment }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="empty-state">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No development milestones recorded for this observation</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@include('layout.footer')
@stop
