<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Child's Observation Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 5px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            color: blueviolet;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f8f8f8;
            font-weight: bold;
            width: 150px;
        }

        .photo-cell {
            height: 150px;
        }

        .observation-cell {
            height: 120px;
        }

        .outcomes-cell {
            height: 150px;
        }

        .analysis-cell,
        .reflection-cell {
            height: 120px;
            width: 50%;
        }

        .voice-cell {
            height: 80px;
        }

        .assessment-cell,
        .milestones-cell {
            height: 120px;
            width: 50%;
        }

        .plan-cell {
            height: 100px;
        }

        /* Photo Gallery Container */
        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            /* Responsive grid */
            gap: 10px;
            /* Space between images */
            margin-top: 10px;
        }

        /* Each Photo Item */
        .photo-item {
            border: 1px solid #ccc;
            /* Light border for clean look */
            border-radius: 12px;
            /* Rounded corners */
            padding: 5px;
            /* Space inside the box */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Light shadow */
            text-align: center;
            /* Center image */
        }

        /* Image Styling */
        .child-image {
            max-height: 100%;
            height: 100%;
            max-width: 100%;
            width: 100%;
            /* Consistent image height */
            border-radius: 8px;
            /* Rounded image corners */
            object-fit: cover;
            /* Crop image to fill space while maintaining aspect ratio */
        }

        /* .no-print {
            display: none;
        } */

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button {
            position: fixed;
            top: 70px;
            right: 30px;
            padding: 10px 20px;
            background-color: #2ee9ef;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }





        @media print {

            /* Hide print and back buttons */
            .print-button,
            .back-button {
                display: none;
            }

            /* Ensure body and page styling */
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
                /* Consistent font size for better print output */
                line-height: 1.4;
            }

            .container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }

            /* Ensure A4 size with consistent page margins */
            @page {
                size: A4;
                /* Standard print size */
                margin: 20mm;
                /* Ensure 20mm margin on all sides */
            }

            /* Ensure margin at the bottom of each page */
            .page::after {
                content: "";
                display: block;
                height: 15mm;
                /* Add bottom space to avoid cutoff */
            }



            /* Avoid breaking inside these elements */
            .no-break,
            .child-image,
            table,
            img {
                page-break-inside: avoid;
                /* Avoid breaking inside tables and images */
            }



            /* Force page break after specific sections */
            .page-break {
                page-break-after: always;
                /* Use where necessary to break pages */
            }

            /* Prevent headings and important sections from breaking */
            h1,
            h2,
            h3,
            th {
                page-break-after: avoid;
                /* Keep headings intact */
            }

            /* Table styling for better print readability */
            table {
                border-collapse: collapse;
                width: 100%;
            }

            /* Ensure header and title stay together but do not push the table to a new page */
            .title-wrapper {
                break-inside: avoid;
                /* Avoid breaking inside */
                page-break-inside: avoid;
                /* Avoid breaking inside (for older browsers) */
            }

            /* Ensure the table follows without forcing a new page */
            table {
                break-before: auto;
                /* Let the table naturally flow */
                page-break-before: auto;
                /* Same for compatibility */
                break-inside: auto;
                /* Avoid breaking inside if possible */
                page-break-inside: auto;
                /* Compatibility */
            }

            th,
            td {
                border: 1px solid #000;
                /* Solid border for print */
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f8f8f8;
                /* Light background for headers */
            }

            /* Image Styling */
            .photo-gallery {
                grid-template-columns: repeat(3, 1fr);
                /* 3 images per row on print */
                gap: 8px;
                /* Reduced gap for print */
            }

            .photo-item {
                box-shadow: none;
                /* Remove shadow for print */
                border: 0.5px solid #999;
                /* Thinner border for print */
            }
        }

          .circular-image {
            width: 110px;
            height: 120px;
            border-radius: 9px;
            object-fit: cover;
            border: 2px solid #aaa;
            margin-bottom: 8px;
        }

        .photo-gallery {

            gap: 2px;
            /* Reduce space between images */

        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button">Print Pages&nbsp;<i
            class="fa-solid fa-print fa-beat-fade"></i></button>


    <div class="container">

        <!-- Header with Logo -->
        <div class="header" title-wrapper>
            <img src="{{ asset('assets/profile_1739442700.jpeg') }}" alt="NEXTGEN Montessori" class="logo">
        </div>

        <!-- Title -->
        <div class="title">Child's Observation:</div>

        <!-- Child Info Table -->
        <table>
            <tr>
                <th colspan="2">Child's Name</th>
                <td colspan="2">
                    @if($observation->child && $observation->child->isNotEmpty())
                    {{ $observation->child->pluck('child.name')->implode(', ') }}
                    @endif
                </td>
            </tr>
            <tr>
                <th colspan="2">Date</th>
                <td colspan="2">{{ \Carbon\Carbon::parse($observation->date_added)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Educator's Name</th>
                <td colspan="3">{{ $observation->user->name ?? $observation->name ?? '' }}</td>
            </tr>
            <tr>
                <th>Classroom</th>
                <td colspan="3">
                    {{ $roomNames ?? $roomNames ?? '' }}
                </td>
            </tr>
            <tr>
                <td colspan="4" class="photo-cell">
                    <strong>
                        <p>Child's Photos</p>
                    </strong>

                    <div class="photo-gallery">
                           <img src="{{ asset('assets/profile_1739442700.jpeg') }}" alt="NEXTGEN Montessori"
                    class="circular-image">
                        @if($observation->media && $observation->media->isNotEmpty())
                        @foreach($observation->media as $mediaItem)
                        @if(Str::startsWith($mediaItem->mediaType, 'image'))
                        <img src="{{ asset($mediaItem->mediaUrl) }}" class="child-image" alt="Observation Media">
                        @endif
                        @endforeach
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <th>Observation</th>
                <td colspan="3" class="observation-cell">
                  {{ strip_tags($observation->title ?? '') }}
                </td>
            </tr>
            <tr>
                <td colspan="1" class="outcomes-cell" style="background-color: #f8f8f8;"><strong>EYLF Outcomes</strong>
                </td>
                <td colspan="3" style="text-align: justify;">
                    @if($observation->eylfLinks && $observation->eylfLinks->isNotEmpty())
                    @php
                    $groupedByOutcome = $observation->eylfLinks->groupBy(function($item) {
                    return $item->subActivity->activity->outcome->title ?? 'Unknown Outcome';
                    });
                    @endphp

                    @foreach($groupedByOutcome as $outcomeTitle => $links)
                    <strong>{{ $outcomeTitle }}</strong><br>
                    @foreach($links as $link)
                    - {{ $link->subActivity->activity->title ?? 'N/A' }}<br>
                    &nbsp;&nbsp;&nbsp;• {{ $link->subActivity->title ?? 'N/A' }}<br>
                    @endforeach
                    <br>
                    @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: justify;">
                    <strong>
                        <p>Analysis/Evaluation</p>
                    </strong>
                    {!! $observation->notes !!}
                </td>
                <td colspan="3" style="text-align: justify;">
                    <strong>
                        <p>Reflection</p>
                    </strong>
                    {!! $observation->reflection !!}
                </td>
            </tr>
            <tr>
                <th>Child's Voice</th>
                <td colspan="3" class="voice-cell">
                    {!! $observation->child_voice ?? 'Not recorded' !!}
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>
                        <p>Montessori Assessment</p>
                    </strong>
                    @if($observation->montessoriLinks && $observation->montessoriLinks->isNotEmpty())
                    @php
                    $groupedBySubject = $observation->montessoriLinks->groupBy(function($item) {
                    return $item->subActivity->activity->subject->name ?? 'Unknown';
                    });
                    @endphp

                    @foreach($groupedBySubject as $subjectName => $assessments)
                    <strong>{{ $subjectName }}</strong><br>
                    @foreach($assessments as $assessment)
                    - {{ $assessment->subActivity->activity->title ?? 'N/A' }}<br>
                    &nbsp;&nbsp;&nbsp;• {{ $assessment->subActivity->title ?? 'N/A' }}
                    @php
                    $statusClass = [
                    'Not Assessed' => 'badge-danger',
                    'Introduced' => 'badge-info',
                    'Working' => 'badge-warning',
                    'Completed' => 'badge-success'
                    ];
                    @endphp
                    ({{ $assessment->assesment }})<br>
                    @endforeach
                    <br>
                    @endforeach
                    @endif
                </td>
                <td colspan="2">
                    <strong>
                        <p>Development Milestones</p>
                    </strong>
                    @if($observation->devMilestoneSubs && $observation->devMilestoneSubs->isNotEmpty())
                    @php
                    $groupedByAgeGroup = $observation->devMilestoneSubs->groupBy(function($item) {
                    return $item->devMilestone->milestone->ageGroup ?? 'Unknown Age Group';
                    });
                    @endphp

                    @foreach($groupedByAgeGroup as $ageGroup => $milestones)
                    <strong>{{ $ageGroup }}</strong><br>
                    @php
                    $groupedByMain = $milestones->groupBy(function($item) {
                    return $item->devMilestone->main->name ?? 'Unknown Category';
                    });
                    @endphp

                    @foreach($groupedByMain as $mainCategory => $categoryMilestones)
                    - {{ $mainCategory }}<br>
                    @foreach($categoryMilestones as $milestone)
                    &nbsp;&nbsp;&nbsp;• {{ $milestone->devMilestone->name ?? 'N/A' }}
                    @php
                    $statusClass = [
                    'Introduced' => 'badge-info',
                    'Working towards' => 'badge-warning',
                    'Achieved' => 'badge-success'
                    ];
                    @endphp
                    ({{ $milestone->assessment }})<br>
                    @endforeach
                    @endforeach
                    <br>
                    @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <th>Future Plan/Extension</th>
                <td colspan="3" class="plan-cell">
                    {!! $observation->future_plan ?? '' !!}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
