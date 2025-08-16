<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NextGen Montessori Program Plan</title>

    <!-- CSS Dependencies -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

     <style>
        body { 
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .page {
            
            margin-bottom: 30px;
            page-break-after: always;
        }

        /* .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            background: linear-gradient(to right, #8a7fb9, #e198b4, #87ceeb, #90ee90, #f4a460);
            padding: 20px;
            border-radius: 10px;
        } */


        .topdivs strong {
    display: inline-block;
    margin-bottom: 5px;
}
.topdivs ul {
    margin-top: 0;
    padding-left: 20px;
}

        .header {
    text-align: center;
    /* margin-top: 50px; */
    margin-bottom: 30px;
    position: relative;
    /* padding: 20px; */
    border-radius: 10px;

    /* Use url() for image */
    background-size: contain; 
    background-repeat: no-repeat; 
                 }

        .header img {
    padding: 20px;

            max-width: 300px;
        }

        .program-title {
            text-align: center;
            font-size: 1.2em;
            margin: 20px 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
        }

        th, td {
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
        }

        .room-name-row td {
            height: 30px;
        }

        .educators-row td {
            height: 30px;
        }

        .main-content-row td {
            height: 380px;
        }

        .focus-area {
            text-align: left;
            padding-right: 10px;
        }

        .planned-experiences {
            font-weight: bold;
            color: navy;
            margin-bottom: 10px;
        }

        .eylf-section {
            border: 1px solid #000;
            /* padding: 10px; */
            margin-top: 0px;
            min-height: 150px;
        }

        .outdoor-section {
            border: 1px solid #000;
            padding: 10px;
            min-height: 100px;
            /* margin-bottom: 20px; */
        }

        .section-label {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 0.8em;
            margin-top: 20px;
            color: #666;
            position: relative;
            bottom: 0;
        }

        .corner-decoration {
            position: absolute;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #87ceeb, transparent);
            border-radius: 50%;
        }

        .top-left { top: 0; left: 0; transform: translate(-50%, -50%); }
        .top-right { top: 0; right: 0; transform: translate(50%, -50%); }
        .bottom-left { bottom: 0; left: 0; transform: translate(-50%, 50%); }
        .bottom-right { bottom: 0; right: 0; transform: translate(50%, 50%); }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color:rgb(60, 87, 138);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button{
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

        .topdivs {
            min-height:250px;
        }

        .bottomdivs{
            margin-top:10px;
        }

        @media print {
    .print-button {
        display: none;
    }
    .back-button {
        display: none;
    }

    body {  
        margin: 0;
        padding: 0;
    }

    .page {
        margin-left:180px;
         /* Center the page horizontally */
        padding: 10px;
        width: 100%; /* Adjust width if needed */
        box-sizing: border-box;
        page-break-after: always; /* Force page break after each .page */
    }

    .page:last-child {
        page-break-after: auto; /* Prevent extra blank page at the end */
    }

    @page {
        size: A3 landscape;
        margin: 0;
    }

    .header {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    /* Force background images and colors to print */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}




    </style>
</head>
<body>

    <button onclick="window.print()" class="print-button">Print Pages&nbsp;<i class="fa-solid fa-print fa-beat-fade"></i></button>
    <button onclick="window.location.href='{{ route('programPlanList') }}'" class="back-button">
        <i class="fa-solid fa-arrow-left fa-beat"></i>&nbsp;Go Back
    </button>

    <!-- Page 1 -->
    <div class="page">
        <div class="header">
            <img src="{{ asset('assets/img/profile_1739442700.jpeg') }}" alt="NextGen Montessori Logo">
        </div>

        <div class="program-title">
            PROGRAM PLAN <span style="color:#22b1c4;">{{ $month_name }} {{ $plan['years'] ?? '' }}</span>
        </div>

        <table>
            <tr class="room-name-row">
                <td><strong>Room Name</strong></td>
                <td colspan="3">{{ $room_name }}</td>
                <td rowspan="2" class="focus-area"><strong>Focus Area</strong></td>
                <td rowspan="2">{{ $plan['focus_area'] ?? '' }}</td>
            </tr>
            <tr class="educators-row">
                <td><strong>Educators</strong></td>
                <td colspan="3">{{ $educator_names }}</td>
            </tr>
            <tr class="educators-row">
                <td><strong>Children</strong></td>
                <td colspan="5">{{ $children_names }}</td>
            </tr>

            <!-- Montessori Areas -->
            <tr>
                <th>Practical Life</th>
                <th>Sensorial</th>
                <th>Math</th>
                <th>Language</th>
                <th>Culture</th>
                <th>Art & Craft</th>
            </tr>

            <tr class="main-content-row">
                @php
                    function formatActivities($input) {
                        $html = '';
                        if ($input) {
                            $lines = explode("\n", $input);
                            $inList = false;
                            foreach ($lines as $line) {
                                if (str_starts_with($line, '**') && str_contains($line, '** - ')) {
                                    if ($inList) $html .= '</ul>';
                                    $html .= '<strong>' . str_replace(['**', ' - '], '', $line) . '</strong><ul>';
                                    $inList = true;
                                } elseif (str_starts_with($line, '**• **')) {
                                    $html .= '<li>' . str_replace('**• **', '', $line) . '</li>';
                                }
                            }
                            if ($inList) $html .= '</ul>';
                        } else {
                            $html = '';
                        }
                        return $html;
                    }
                @endphp

                @foreach (['practical_life', 'sensorial', 'math', 'language', 'culture'] as $area)
                    <td>
                        <div class="topdivs">{!! formatActivities($plan[$area] ?? '') !!}</div>
                        <div class="bottomdivs">{{ $plan[$area . '_experiences'] ?? '' }}</div>
                    </td>
                @endforeach

                <td>
                    <div class="topdivs">{{ $plan['art_craft'] ?? '' }}</div>
                    <div class="bottomdivs">{{ $plan['art_craft_experiences'] ?? '' }}</div>
                </td>
            </tr>
        </table>

        <div class="eylf-section">
            <div class="section-label" style="margin:10px;">EYLF:</div>
            <div style="margin:10px;">{!! nl2br(e($plan['eylf'] ?? '')) !!}</div>
        </div>

        <div class="footer">
            1 Capricorn Road, Truganina, VIC 3029
        </div>
    </div>

    <!-- Page 2 -->
    <div class="page">
        <div class="header">
            <img src="{{ asset('assets/img/profile_1739442700.jpeg') }}" alt="NextGen Montessori Logo">
        </div>

        <div class="outdoor-section">
            <div class="section-label">Outdoor Experiences:</div>
            <div style="margin:10px;">
                @if(!empty($plan['outdoor_experiences']))
                    <ul>
                        @foreach (explode(',', $plan['outdoor_experiences']) as $item)
                            <li>{{ trim($item) }}</li>
                        @endforeach
                    </ul>
                @else
                    <p> </p>
                @endif
            </div>
        </div>

        <table>
            <tr>
                <td><div class="section-label">Inquiry Topic:</div><div style="margin:10px;">{{ $plan['inquiry_topic'] ?? '' }}</div></td>
                <td><div class="section-label">Sustainability Topic:</div><div style="margin:10px;">{{ $plan['sustainability_topic'] ?? '' }}</div></td>
                <td>
                    <div class="section-label">Special Events:</div>
                    <div style="margin:10px;">
                        @if(!empty($plan['special_events']))
                            <ul>
                                @foreach (explode(',', $plan['special_events']) as $event)
                                    <li>{{ trim($event) }}</li>
                                @endforeach
                            </ul>
                        @else
                                <p> </p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td colspan="3">
                    <div class="section-label">Children's Voices:</div>
                    <div style="margin:10px;">{{ $plan['children_voices'] ?? '' }}</div>
                </td>
                <td>
                    <div class="section-label">Families Input:</div>
                    <div style="margin:10px;">{{ $plan['families_input'] ?? '' }}</div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td><div class="section-label">Group Experience:</div><div style="margin:10px;">{{ $plan['group_experience'] ?? '' }}</div></td>
                <td><div class="section-label">Spontaneous Experience:</div><div style="margin:10px;">{{ $plan['spontaneous_experience'] ?? '' }}</div></td>
                <td><div class="section-label">Mindfulness Experiences:</div><div style="margin:10px;">{{ $plan['mindfulness_experiences'] ?? '' }}</div></td>
            </tr>
        </table>

        <div class="footer">
            1 Capricorn Road, Truganina, VIC 3029
        </div>
    </div>

    <!-- JS for select2 interaction -->
    <script>
        $(document).ready(function() {
            $('.select2-multiple, #educators').select2({
                placeholder: "Select",
                allowClear: true,
                width: '100%'
            });

            $('.select2-multiple').on('change', function() {
                $('#printable-rooms').text($(this).find(':selected').map(function() {
                    return $(this).text();
                }).get().join(', '));
            });

            $('#educators').on('change', function() {
                $('#printable-educators').text($(this).find(':selected').map(function() {
                    return $(this).text();
                }).get().join(', '));
            });
        });
    </script>

</body>
</html>
