<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Children Head Checks</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .container { margin-top: 2em; border: 1px solid #d3d3d3; padding: 1em; }
        h4 { text-align: center; text-decoration: underline; color: #2a3b8f; margin-bottom: 1em; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #e6e6fa; color: #222; }
        .form-group { margin-bottom: 1em; }
        .row { display: table; width: 100%; }
        .col-auto { display: table-cell; padding: 0 10px; }
        .text-muted { color: #888; font-size: 12px; }
        .font-weight-bold { font-weight: bold; }
        .mb-0 { margin-bottom: 0; }
        .ml-4 { margin-left: 1.5em; }
        .mt-2 { margin-top: 1em; }
        .mb-4 { margin-bottom: 2em; }
    </style>
</head>
@php
    use Carbon\Carbon;
    $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $headcheckMap = [];
    $uniqueTimes = [];
    $diaryDates = [];
    function normalizeTime($str) {
        if (preg_match('/(\d+)h:(\d+)m/', $str, $matches)) {
            return sprintf('%02d.%02d', $matches[1], $matches[2]);
        }
        return $str;
    }
    foreach ($headchecks as $hc) {
        $day = Carbon::parse($hc->diarydate)->format('l');
        $dateFormatted = Carbon::parse($hc->diarydate)->format('d-m-Y');
        $normalizedTime = normalizeTime($hc->time);
        if (!in_array($normalizedTime, $uniqueTimes)) {
            $uniqueTimes[] = $normalizedTime;
        }
        if (in_array($day, $weekdays)) {
            $headcheckMap[$normalizedTime][$day] = [
                'headcount' => $hc->headcount,
                'signature' => $hc->signature
            ];
            $diaryDates[$day] = $dateFormatted;
        }
    }
    sort($uniqueTimes);
@endphp
<body>
<div class="container">
    <h4>Children Head Checks</h4>
    <div style="width:100%; text-align:center;  margin-bottom:0.5em;">
        @if(isset($startOfWeek))
            <span><b>Week:</b> {{ Carbon::parse($startOfWeek)->format('d M Y') }} - {{ Carbon::parse($startOfWeek)->addDays(4)->format('d M Y') }}</span>
        @elseif(isset($inputDate))
            <span><b>Date:</b> {{ Carbon::parse($inputDate)->format('d M Y') }}</span>
        @endif
    </div>
    <div style="width:100%; display:table; margin-bottom: 1.5em;">
        <div style="display:table-row;">
            <div style="display:table-cell; text-align:right; vertical-align:middle; width:50%;">
                <span class="font-weight-bold">Room Name:</span> {{ $room->name ?? '-' }}
            </div>
            <div style="display:table-cell; text-align:left; vertical-align:middle; width:50%; padding-left:60px;">
                <span class="font-weight-bold">Month:</span> {{ Carbon::createFromFormat('m', $month)->format('F') ?? '-' }}
            </div>
        </div>
    </div>
    <table>
   <thead>
    <tr>
        <th rowspan="2">Time</th>
        @foreach($weekdays as $day)
            <th colspan="2">
                {{ $day }}<br>
                <small>
                    {{ isset($diaryDates[$day]) 
                        ? Carbon::parse($diaryDates[$day])->format('d/m/Y') 
                        : '__/__/__' }}
                </small>
            </th>
        @endforeach
    </tr>
    <tr>
        @foreach($weekdays as $day)
            <th>No.</th>
            <th>Sign</th>
        @endforeach
    </tr>
</thead>
        <tbody>
            @foreach($uniqueTimes as $slot)
                <tr>
                  <td>
    @php
        [$hour, $minute] = explode('.', $slot);
        $formatted = Carbon::createFromTime((int) $hour, (int) $minute)->format('h:i A');
    @endphp
    {{ $formatted }}
</td>
                    @foreach($weekdays as $day)
                        <td>{{ $headcheckMap[$slot][$day]['headcount'] ?? '' }}</td>
                        <td>{{ $headcheckMap[$slot][$day]['signature'] ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="text-muted mt-3 ml-5">
        <strong>Note:</strong> <small>Staff needs to complete the head checks every half hour and sign off.</small>
    </p>
</div>
</body>
</html>