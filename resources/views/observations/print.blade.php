<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Child's Reflection Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(to bottom, #f4f6f7, #d9e3fd);
        }

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
            z-index: 9999;
        }

        .container {
            position: relative;
            max-width: 800px;
            margin: auto;
            background-color: #e1eeff;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 60px 80px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            z-index: 1;
            overflow: hidden;
        }

        .container::before {
            content: "";
            position: absolute;
            top: 20%;
            left: 20%;
            transform: translate(-5%, -5%);
            background-image: url('{{ asset("assets/profile_1739442700.jpeg") }}');
            background-repeat: no-repeat;
            background-size: 500px;
            opacity: 0.06;
            z-index: 0;
            width: 600px;
            height: 600px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .logo {
            max-width: 200px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #8e24aa;
            margin-top: 10px;
        }

        .info-block {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .info-label {
            font-weight: bold;
            font-size: 15px;
            color: #222;
            margin-bottom: 5px;
        }

        .info-text {
            font-size: 14px;
            color: #000;
        }

        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .child-image {
            margin:10px;
            width: 100%;
            height: 145px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #bbb;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                background: none;
                margin: 0;
                padding: 0;
            }

            .container {
                border: 1px solid #000;
                box-shadow: none;
                page-break-inside: avoid;
            }

            .child-image {
                height: 120px;
            }

            @page {
                size: A4;
                margin: 20mm;
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

            gap: 15px;
            /* Reduce space between images */

        }
    </style>
</head>

<body>


    <button onclick="window.print()" class="print-button">Print Pages&nbsp;<i
            class="fa-solid fa-print fa-beat-fade"></i></button>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/profile_1739442700.jpeg') }}" alt="NEXTGEN Montessori" class="logo">
            <div class="title">Child's Observation</div>
            <hr>
        </div>

        <div class="info-block">
            <strong>Child's Name</strong>
            <span class="info-text">
                @if($observation->child && $observation->child->isNotEmpty())
                {{ $observation->child->pluck('child.name')->implode(', ') }}
                @endif
            </span>
        </div>

        <div class="info-block">
            <strong>Date:</strong>
            <span class="info-text">
                {{ \Carbon\Carbon::parse($observation->date_added)->format('d/m/Y') }}
            </span>
        </div>

        <div class="info-block">
            <strong>Educator's Name:</strong>
            <span class="info-text">
                {{ $observation->user->name ?? $observation->name ?? '' }}
            </span>
        </div>

        <div class="info-block">
            <strong>Classroom:</strong>
            <span class="info-text">
                {{ $roomNames ?? $roomNames ?? '' }}
            </span>
        </div>

        <div class="info-block">
            <strong>Child's Photos:</strong>
            <div class="photo-gallery">
               

                @if($observation->media && $observation->media->isNotEmpty())
                @foreach($observation->media as $mediaItem)
                @if(Str::startsWith($mediaItem->mediaType, ['image', 'Image']))
                <img src="{{ asset($mediaItem->mediaUrl) }}" class="child-image" alt="Observation Media">
                @endif
                @endforeach
                @endif
            </div>
        </div>

        <div class="info-block">
            <strong>Observation:</strong>
            <span class="info-text">
                {{ strip_tags($observation->title ?? '') }}
            </span>
        </div>




        <div class="info-block">
            <strong>EYLF Outcomes:</strong>
            <span class="info-text">
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
            </span>
        </div>

        <div class="info-block">
            <strong>Analysis/Evaluation:</strong>
            <span class="info-text">
               {{ strip_tags($observation->notes ?? '') }}
            </span>
        </div>


        <div class="info-block">
            <strong>Reflection:</strong>
            <span class="info-text">
            {{ strip_tags($observation->reflection ?? '') }}
            </span>
        </div>


        <div class="info-block">
            <strong>Child's Voice:</strong>
            <span class="info-text">
              {{ strip_tags($observation->child_voice ?? 'Not recorded') }}
            </span>
        </div>

        <div class="info-block">
            <strong>Montessori Assessment:</strong>
            <span class="info-text">
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
            </span>
        </div>


        <div class="info-block">
            <strong>Development Milestones:</strong>
            <span class="info-text">
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
            </span>
        </div>



        <div class="info-block">
            <strong>Future Plan/Extension:</strong>
            <span class="info-text">
             {{ strip_tags($observation->future_plan ?? '') }}
            </span>
        </div>



    </div>


</body>

</html>
