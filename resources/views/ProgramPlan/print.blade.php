<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

        

        .bottomdivs{
            margin-top:10px;
        }

        .name-badge-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .name-badge {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid #d5dbe3;
            border-radius: 999px;
            background: #f4f7fb;
            font-size: 13px;
            line-height: 1.2;
            white-space: nowrap;
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
    
    <!-- Theme-scoped overrides: apply only when a theme is active -->
    <style>
        /* Keep defaults intact for No Theme; only override under theme classes */
        body[class*="theme-"] .program-title span {
            color: var(--sd-accent);
        }

        body[class*="theme-"] .header {
            border-top: 6px solid var(--sd-accent);
        }

        body[class*="theme-"] th {
            background: var(--sd-accent-soft, rgba(0, 0, 0, 0.06));
        }

        body[class*="theme-"] .section-label {
            color: var(--sd-accent);
        }

        body[class*="theme-"] .print-button,
        body[class*="theme-"] .back-button {
            background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent));
            border-color: var(--sd-accent);
            color: #000;
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

        @php
            $roomBadges = array_values(array_filter(array_map('trim', explode(',', (string) $room_name))));
            $educatorBadges = array_values(array_filter(array_map('trim', explode(',', (string) $educator_names))));
            $childrenBadges = array_values(array_filter(array_map('trim', explode(',', (string) $children_names))));
        @endphp

        <table>
            <tr class="room-name-row">
                <td><strong>Room Name</strong></td>
                <td colspan="5">
                    @if(count($roomBadges))
                        <div class="name-badge-wrap">
                            @foreach($roomBadges as $name)
                                <span class="name-badge">{{ $name }}</span>
                            @endforeach
                        </div>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
            <tr class="educators-row">
                <td><strong>Educators</strong></td>
                <td colspan="5">
                    @if(count($educatorBadges))
                        <div class="name-badge-wrap">
                            @foreach($educatorBadges as $name)
                                <span class="name-badge">{{ $name }}</span>
                            @endforeach
                        </div>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
            <tr class="educators-row">
                <td><strong>Children</strong></td>
                <td colspan="5">
                    @if(count($childrenBadges))
                        <div class="name-badge-wrap">
                            @foreach($childrenBadges as $name)
                                <span class="name-badge">{{ $name }}</span>
                            @endforeach
                        </div>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
            <tr>
                <td class="focus-area"><strong>Focus Area</strong></td>
                <td colspan="5">
                    @php $focusAreaItems = splitItems($plan['focus_area'] ?? ''); @endphp
                    @if(count($focusAreaItems))
                        <ul style="margin:10px;">
                        @foreach($focusAreaItems as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>

            <!-- Montessori Areas (one per row) -->
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

            @foreach ([
                'Practical Life' => 'practical_life',
                'Sensorial' => 'sensorial',
                'Math' => 'math',
                'Language' => 'language',
                'Culture' => 'culture',
                'Art & Craft' => 'art_craft',
            ] as $label => $area)
                @php
                    $top = $area === 'art_craft' ? ($plan['art_craft'] ?? '') : (isset($plan[$area]) ? formatActivities($plan[$area]) : '');
                    $bottom = $area === 'art_craft' ? ($plan['art_craft_experiences'] ?? '') : ($plan[$area . '_experiences'] ?? '');
                @endphp
                @if(trim(strip_tags($top)) !== '' || trim(strip_tags($bottom)) !== '')
                <tr class="main-content-row">
                    <th style="width: 180px;">{{ $label }}</th>
                    <td>
                        @if($area === 'art_craft')
                            @php
                                echo '<!-- RAW_ART_CRAFT: ' . (isset($plan['art_craft']) ? $plan['art_craft'] : '') . ' -->';
                                $artCraftItems = splitItems($plan['art_craft'] ?? '');
                                echo '<!-- SPLIT_ART_CRAFT: ' . print_r($artCraftItems, true) . ' -->';
                            @endphp
                            @if(count($artCraftItems))
                                <ul style="margin:10px;">
                                @foreach($artCraftItems as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                                </ul>
                            @endif
                        @else
                            @if(trim(strip_tags($top)) !== '')
                                <div class="topdivs">{!! $top !!}</div>
                            @endif
                        @endif
                        @if(trim(strip_tags($bottom)) !== '')
                            <div class="bottomdivs">{!! $bottom !!}</div>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach

        @php
            $eylfRaw = $plan['eylf'] ?? '';
            $eylfOutcomes = preg_split('/(?=Outcome)/', $eylfRaw, -1, PREG_SPLIT_NO_EMPTY);
            $eylfList = [];
            foreach ($eylfOutcomes as $item) {
                $item = trim($item);
                if ($item === '') continue;
                if (strpos($item, ':') !== false) {
                    [$heading, $subs] = explode(':', $item, 2);
                    $eylfList[] = [trim($heading), array_filter(array_map('trim', preg_split('/[\n,]/', $subs)))];
                } else {
                    $eylfList[] = [$item, []];
                }
            }
        @endphp
        <tr class="main-content-row">
            <th style="width: 180px;">EYLF</th>
            <td>
                @if(count($eylfList))
                    <div style="margin:10px;">
                    @foreach($eylfList as [$heading, $subs])
                        <strong>{{ $heading }}</strong>
                        @if(count($subs))
                            <ul>
                            @foreach($subs as $sub)
                                @if($sub)
                                    <li>{{ $sub }}</li>
                                @endif
                            @endforeach
                            </ul>
                        @endif
                    @endforeach
                    </div>
                @else
                    <div style="margin:10px;">&nbsp;</div>
                @endif
            </td>
        </tr>
        </table>


        <div class="footer">
            1 Capricorn Road, Truganina, VIC 3029
        </div>
    </div>

    <!-- Page 2 -->
    <div class="page">
        <div class="header">
            <img src="{{ asset('assets/img/profile_1739442700.jpeg') }}" alt="NextGen Montessori Logo">
        </div>


        @php
            $outdoor = splitItems($plan['outdoor_experiences'] ?? '');
        @endphp
        <table>
            <tr>
                <td style="width:200px;"><div class="section-label">Outdoor Experiences:</div></td>
                <td>
                    @if(count($outdoor))
                        <ul style="margin:10px;">
                        @foreach($outdoor as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
        </table>

        @php
            // Helper to split only by newlines (Enter), trim, and remove empty
            function splitItems($text) {
                if (!is_string($text) || trim($text) === '') return [];
                // Decode HTML entities (e.g., &nbsp;)
                $text = html_entity_decode($text);
                // Replace <br>, <br/>, <br /> and <p> tags with a unique separator
                $text = preg_replace('/<\s*br\s*\/?>/i', '|||', $text);
                $text = preg_replace('/<\s*\/p\s*>/i', '|||', $text);
                $text = preg_replace('/<\s*p\s*>/i', '', $text);
                // Split on unique separator, literal \\n, literal \\r, or literal \n
                $lines = preg_split('/\|\|\||\\n|\\r|\n/', $text);
                $items = [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        $items[] = $line;
                    }
                }
                // DEBUG: Output the $items array
                echo '<!-- SPLIT_ITEMS: ' . print_r($items, true) . ' -->';
                return $items;
            }
            $inq = splitItems($plan['inquiry_topic'] ?? '');
            $sus = splitItems($plan['sustainability_topic'] ?? '');
            $voices = splitItems($plan['children_voices'] ?? '');
            $families = splitItems($plan['families_input'] ?? '');
            $group = splitItems($plan['group_experience'] ?? '');
            $spont = splitItems($plan['spontaneous_experience'] ?? '');
            $mind = splitItems($plan['mindfulness_experiences'] ?? '');
            $working = splitItems($plan['working'] ?? '');
            $notworking = splitItems($plan['notworking'] ?? '');
            // DEBUG: Output the raw HTML for troubleshooting
            echo '<!-- RAW_EVENTS: ' . (isset($plan['special_events']) ? $plan['special_events'] : '') . ' -->';
            $events = splitItems(isset($plan['special_events']) ? html_entity_decode($plan['special_events']) : '');
        @endphp
        <table>
            <tr>
                <td style="width:200px;"><div class="section-label">Special Events:</div></td>
                <td>
                    @if(count($events))
                        <ul style="margin:10px;">
                        @foreach($events as $event)
                            <li>{{ $event }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td><div class="section-label">Inquiry Topic:</div></td>
                <td>
                    @if(count($inq))
                        <ul style="margin:10px;">
                        @foreach($inq as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td><div class="section-label">Sustainability Topic:</div></td>
                <td>
                    @if(count($sus))
                        <ul style="margin:10px;">
                        @foreach($sus as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:200px;"><div class="section-label">Children's Voices:</div></td>
                <td>
                    @if(count($voices))
                        <ul style="margin:10px;">
                        @foreach($voices as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td><div class="section-label">Families Input:</div></td>
                <td>
                    @if(count($families))
                        <ul style="margin:10px;">
                        @foreach($families as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:200px;"><div class="section-label">Group Experience:</div></td>
                <td>
                    @if(count($group))
                        <ul style="margin:10px;">
                        @foreach($group as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:200px;"><div class="section-label">Spontaneous Experience:</div></td>
                <td>
                    @if(count($spont))
                        <ul style="margin:10px;">
                        @foreach($spont as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:200px;"><div class="section-label">Mindfulness Experiences:</div></td>
                <td>
                    @if(count($mind))
                        <ul style="margin:10px;">
                        @foreach($mind as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:200px;"><div class="section-label">What is working:</div></td>
                <td colspan="2">
                    @if(count($working))
                        <ul style="margin:10px;">
                        @foreach($working as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:200px;"><div class="section-label">What is not working:</div></td>
                <td colspan="2">
                    @if(count($notworking))
                        <ul style="margin:10px;">
                        @foreach($notworking as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div style="margin:10px;">&nbsp;</div>
                    @endif
                </td>
            </tr>
        </table>

        <div class="footer">
            1 Capricorn Road, Truganina, VIC 3029
        </div>
