<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Child's Observation Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        .logo {
            width: 120px;
            margin-bottom: 8px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .header-section h2 {
            margin: 8px 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        /* Avoid breaking tables across pages */
        table.keep-together {
            page-break-inside: avoid;
        }

        /* Allow breaking for large content tables */
        table.allow-break {
            page-break-inside: auto;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 25%;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .section-title {
            font-weight: bold;
            background-color: #eee;
            padding: 6px;
            border: 1px solid #333;
            margin-top: 8px;
            page-break-inside: avoid;
        }

        .photo-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 8px 0;
            justify-content: flex-start;
            min-height: 140px;
            max-height: 200px;
            overflow: hidden;
        }

        .photo-gallery img {
            width: 100px;
            height: 130px;
            object-fit: cover;
            border: 1px solid #999;
            border-radius: 4px;
        }

        .content-section {
            page-break-inside: avoid;
        }

        .large-content {
            page-break-inside: auto;
        }

        .outcome-group {
            margin-bottom: 8px;
        }

        .outcome-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .outcome-item {
            margin-left: 10px;
            margin-bottom: 2px;
        }

        .outcome-subitem {
            margin-left: 20px;
            margin-bottom: 2px;
        }

        .milestone-group {
            margin-bottom: 8px;
        }

        .milestone-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .milestone-category {
            margin-left: 10px;
            margin-bottom: 2px;
        }

        .milestone-item {
            margin-left: 20px;
            margin-bottom: 2px;
        }

        /* Prevent orphaned content */
        .avoid-orphan {
            orphans: 3;
            widows: 3;
        }

        /* Ensure proper spacing */
        .spacer {
            height: 5px;
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="header-section">
        <img src="{{ public_path('assets/profile_1739442700.jpeg') }}" class="logo" alt="Logo">
        <h2>Child's Observation Report</h2>
    </div>

    <!-- Basic Info -->
    <table class="keep-together">
        <tr>
            <th>Child's Name</th>
            <td>{{ $observation->child->pluck('child.name')->implode(', ') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ \Carbon\Carbon::parse($observation->date_added)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Educator</th>
            <td>{{ $observation->user->name ?? $observation->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Classroom</th>
            <td>{{ $roomNames ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="spacer"></div>

    <!-- Photos -->
    @if($observation->media && $observation->media->count() > 0)
    <table class="keep-together">
        <tr>
            <th>Child's Photos</th>
            <td>
                <div class="photo-gallery">
                    @foreach($observation->media as $mediaItem)
                        @if(Str::startsWith($mediaItem->mediaType, 'image'))
                            <img src="{{ public_path($mediaItem->mediaUrl) }}" alt="Photo">
                        @endif
                    @endforeach
                </div>
            </td>
        </tr>
    </table>
    @endif

    <div class="spacer"></div>

    <!-- Observation -->
    <table class="keep-together">
        <tr>
            <th>Observation</th>
            <td class="avoid-orphan">{!! $observation->title ?? 'N/A' !!}</td>
        </tr>
    </table>

    <div class="spacer"></div>

    <!-- EYLF Outcomes -->
    @if($observation->eylfLinks && $observation->eylfLinks->count() > 0)
    <table class="allow-break">
        <tr>
            <th>EYLF Outcomes</th>
            <td class="large-content">
                @php
                    $groupedByOutcome = $observation->eylfLinks->groupBy(function($item) {
                        return $item->subActivity->activity->outcome->title ?? 'Unknown Outcome';
                    });
                @endphp

                @foreach($groupedByOutcome as $outcomeTitle => $links)
                    <div class="outcome-group">
                        <div class="outcome-title">{{ $outcomeTitle }}</div>
                        @foreach($links as $link)
                            <div class="outcome-item">- {{ $link->subActivity->activity->title ?? 'N/A' }}</div>
                            <div class="outcome-subitem">• {{ $link->subActivity->title ?? 'N/A' }}</div>
                        @endforeach
                    </div>
                @endforeach
            </td>
        </tr>
    </table>
    @else
    <table class="keep-together">
        <tr>
            <th>EYLF Outcomes</th>
            <td>No EYLF outcomes recorded</td>
        </tr>
    </table>
    @endif

    <div class="spacer"></div>

    <!-- Evaluation & Reflection -->
    <table class="allow-break">
        <tr>
            <th>Analysis / Evaluation</th>
            <td class="large-content avoid-orphan">{!! $observation->notes ?? 'N/A' !!}</td>
        </tr>
        <tr>
            <th>Reflection</th>
            <td class="large-content avoid-orphan">{!! $observation->reflection ?? 'N/A' !!}</td>
        </tr>
    </table>

    <div class="spacer"></div>

    <!-- Child's Voice -->
    <table class="keep-together">
        <tr>
            <th>Child's Voice</th>
            <td class="avoid-orphan">{!! $observation->child_voice ?? 'Not recorded' !!}</td>
        </tr>
    </table>

    <div class="spacer"></div>

    <!-- Montessori Assessment -->
    @if($observation->montessoriLinks && $observation->montessoriLinks->count() > 0)
    <table class="allow-break">
        <tr>
            <th>Montessori Assessment</th>
            <td class="large-content">
                @php
                    $groupedBySubject = $observation->montessoriLinks->groupBy(function($item) {
                        return $item->subActivity->activity->subject->name ?? 'Unknown';
                    });
                @endphp

                @foreach($groupedBySubject as $subjectName => $assessments)
                    <div class="milestone-group">
                        <div class="milestone-title">{{ $subjectName }}</div>
                        @foreach($assessments as $assessment)
                            <div class="milestone-category">- {{ $assessment->subActivity->activity->title ?? 'N/A' }}</div>
                            <div class="milestone-item">• {{ $assessment->subActivity->title ?? 'N/A' }} ({{ $assessment->assesment ?? 'N/A' }})</div>
                        @endforeach
                    </div>
                @endforeach
            </td>
        </tr>
    </table>
    @else
    <table class="keep-together">
        <tr>
            <th>Montessori Assessment</th>
            <td>No Montessori assessment recorded</td>
        </tr>
    </table>
    @endif

    <div class="spacer"></div>

    <!-- Development Milestones -->
    @if($observation->devMilestoneSubs && $observation->devMilestoneSubs->count() > 0)
    <table class="allow-break">
        <tr>
            <th>Development Milestones</th>
            <td class="large-content">
                @php
                    $groupedByAgeGroup = $observation->devMilestoneSubs->groupBy(function($item) {
                        return $item->devMilestone->milestone->ageGroup ?? 'Unknown Age Group';
                    });
                @endphp

                @foreach($groupedByAgeGroup as $ageGroup => $milestones)
                    <div class="milestone-group">
                        <div class="milestone-title">{{ $ageGroup }}</div>
                        @php
                            $groupedByMain = $milestones->groupBy(function($item) {
                                return $item->devMilestone->main->name ?? 'Unknown Category';
                            });
                        @endphp
                        @foreach($groupedByMain as $mainCategory => $categoryMilestones)
                            <div class="milestone-category">- {{ $mainCategory }}</div>
                            @foreach($categoryMilestones as $milestone)
                                <div class="milestone-item">• {{ $milestone->devMilestone->name ?? 'N/A' }} ({{ $milestone->assessment ?? 'N/A' }})</div>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            </td>
        </tr>
    </table>
    @else
    <table class="keep-together">
        <tr>
            <th>Development Milestones</th>
            <td>No development milestones recorded</td>
        </tr>
    </table>
    @endif

    <div class="spacer"></div>

    <!-- Future Plan -->
    <table class="keep-together">
        <tr>
            <th>Future Plan / Extension</th>
            <td class="avoid-orphan">{!! $observation->future_plan ?? '' !!}</td>
        </tr>
    </table>

</div>
</body>
</html>