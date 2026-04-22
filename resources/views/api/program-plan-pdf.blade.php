<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Montessori Program Plan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.5;
            color: #333;
        }

        /* Base responsive container */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Page styling for print */
        .page {
            page-break-after: always;
            margin-bottom: 30px;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /* Header section */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
        }

        .header img {
            max-width: 250px;
            height: auto;
        }

        /* Title */
        .program-title {
            text-align: center;
            font-size: 1.5em;
            margin: 20px 0;
            font-weight: bold;
            color: #22b1c4;
        }

        /* Table styling */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
            text-align: left;
        }

        .info-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 180px;
        }

        /* Section styles */
        .section-label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #22b1c4;
        }

        /* Lists */
        ul {
            margin: 10px 0 10px 20px;
            padding-left: 0;
        }

        li {
            margin-bottom: 5px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 0.8em;
            margin-top: 30px;
            padding-top: 20px;
            color: #666;
        }

        /* Responsive adjustments for mobile */
        @media only screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .info-table th,
            .info-table td {
                padding: 8px;
                font-size: 14px;
            }

            .info-table th {
                width: 120px;
            }

            .program-title {
                font-size: 1.2em;
            }

            .header img {
                max-width: 150px;
            }

            ul {
                margin: 5px 0 5px 15px;
            }

            li {
                font-size: 13px;
            }
        }

        @media only screen and (max-width: 480px) {
            .info-table th,
            .info-table td {
                display: block;
                width: 100%;
            }

            .info-table th {
                border-bottom: none;
            }

            .info-table td {
                border-top: none;
                margin-bottom: 10px;
            }

            .program-title {
                font-size: 1em;
            }

            .section-label {
                font-size: 14px;
            }
        }

        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 0;
            }

            .page {
                page-break-after: always;
                margin: 0;
                padding: 20px;
            }

            @page {
                size: A3 landscape;
                margin: 1.5cm;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Utility classes */
        .mt-2 { margin-top: 10px; }
        .mb-2 { margin-bottom: 10px; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page 1 -->
        <div class="page">
            <div class="header">
                <img src="{{ public_path('assets/img/profile_1739442700.jpeg') }}" alt="NextGen Montessori Logo">
            </div>

            <div class="program-title">
                PROGRAM PLAN - {{ $month_name }} {{ $plan['years'] ?? '' }}
            </div>

            <table class="info-table">
                <tr>
                    <th>Room Name</th>
                    <td colspan="3">{{ $room_name }}</td>
                </tr>
                <tr>
                    <th>Educators</th>
                    <td colspan="3">{{ $educator_names }}</td>
                </tr>
                <tr>
                    <th>Children</th>
                    <td colspan="3">{{ $children_names }}</td>
                </tr>
                <tr>
                    <th>Focus Area</th>
                    <td colspan="3">
                        @php 
                            function splitItems($text) {
                                if (!is_string($text) || trim($text) === '') return [];
                                $text = html_entity_decode($text);
                                $text = preg_replace('/<\s*br\s*\/?>/i', "\n", $text);
                                $text = preg_replace('/<\s*\/p\s*>/i', "\n", $text);
                                $text = strip_tags($text);
                                $lines = explode("\n", $text);
                                $items = [];
                                foreach ($lines as $line) {
                                    $line = trim($line);
                                    if ($line !== '') {
                                        $items[] = $line;
                                    }
                                }
                                return $items;
                            }
                            
                            $focusAreaItems = splitItems($plan['focus_area'] ?? '');
                        @endphp
                        @if(count($focusAreaItems))
                            <ul>
                                @foreach($focusAreaItems as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div>&nbsp;</div>
                        @endif
                    </td>
                </tr>

                <!-- Montessori Areas -->
                @php
                    $montessoriAreas = [
                        'Practical Life' => 'practical_life',
                        'Sensorial' => 'sensorial',
                        'Math' => 'math',
                        'Language' => 'language',
                        'Culture' => 'culture',
                        'Art & Craft' => 'art_craft'
                    ];
                @endphp

                @foreach($montessoriAreas as $label => $field)
                    @php
                        $value = $plan[$field] ?? '';
                        $experiences = $plan[$field . '_experiences'] ?? '';
                        $hasContent = !empty(trim(strip_tags($value))) || !empty(trim(strip_tags($experiences)));
                    @endphp
                    @if($hasContent)
                        <tr>
                            <th>{{ $label }}</th>
                            <td colspan="3">
                                @if(!empty(trim(strip_tags($value))))
                                    <div class="mb-2">{!! nl2br(e($value)) !!}</div>
                                @endif
                                @if(!empty(trim(strip_tags($experiences))))
                                    <div class="mt-2">{!! nl2br(e($experiences)) !!}</div>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach

                <!-- EYLF Section -->
                @php
                    $eylfRaw = $plan['eylf'] ?? '';
                    $eylfOutcomes = preg_split('/(?=Outcome)/', $eylfRaw, -1, PREG_SPLIT_NO_EMPTY);
                @endphp
                @if(!empty($eylfRaw))
                    <tr>
                        <th>EYLF</th>
                        <td colspan="3">
                            <div>
                                @foreach($eylfOutcomes as $outcome)
                                    @php $outcome = trim($outcome); @endphp
                                    @if(!empty($outcome))
                                        <div class="mb-2">
                                            <strong>{{ $outcome }}</strong>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endif
            </table>

            <div class="footer">
                1 Capricorn Road, Truganina, VIC 3029
            </div>
        </div>

        <!-- Page 2 -->
        <div class="page">
            <div class="header">
                <img src="{{ public_path('assets/img/profile_1739442700.jpeg') }}" alt="NextGen Montessori Logo">
            </div>

            <table class="info-table">
                <!-- Outdoor Experiences -->
                @php
                    $outdoor = splitItems($plan['outdoor_experiences'] ?? '');
                @endphp
                @if(count($outdoor))
                    <tr>
                        <th>Outdoor Experiences</th>
                        <td colspan="3">
                            <ul>
                                @foreach($outdoor as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endif

                <!-- Special Events -->
                @php
                    $events = splitItems($plan['special_events'] ?? '');
                @endphp
                @if(count($events))
                    <tr>
                        <th>Special Events</th>
                        <td colspan="3">
                            <ul>
                                @foreach($events as $event)
                                    <li>{{ $event }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endif

                <!-- Other sections with similar pattern -->
                @php
                    $sections = [
                        'Inquiry Topic' => 'inquiry_topic',
                        'Sustainability Topic' => 'sustainability_topic',
                        "Children's Voices" => 'children_voices',
                        'Families Input' => 'families_input',
                        'Group Experience' => 'group_experience',
                        'Spontaneous Experience' => 'spontaneous_experience',
                        'Mindfulness Experiences' => 'mindfulness_experiences',
                        'What is working' => 'working',
                        'What is not working' => 'notworking'
                    ];
                @endphp

                @foreach($sections as $label => $field)
                    @php
                        $items = splitItems($plan[$field] ?? '');
                    @endphp
                    @if(count($items))
                        <tr>
                            <th>{{ $label }}</th>
                            <td colspan="3">
                                <ul>
                                    @foreach($items as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>

            <div class="footer">
                1 Capricorn Road, Truganina, VIC 3029
            </div>
        </div>
    </div>
</body>
</html>