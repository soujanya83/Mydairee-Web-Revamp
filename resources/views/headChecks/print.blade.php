<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Children Head Checks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

    <style>
        table {
            font-size: 14px;
             border-collapse: collapse !important;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 6px !important;
        }
        input {
            width: 100%;
            text-align: center;
        }
        .print-btn{
            float: right;
        
        }
 
    </style>
</head>
<body class="p-4">
<a href="javascript:void(0)" onclick="printPage()" class="btn btn-primary shadow-sm px-4 py-2 rounded-pill print-btn">
    🖨 Print
</a>

  

@php
use Carbon\Carbon;

// Prepare weekdays
$weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$headcheckMap = [];
$uniqueTimes = [];
$diaryDates = [];

// Normalize function
function normalizeTime($str) {
    if (preg_match('/(\d+)h:(\d+)m/', $str, $matches)) {
        return sprintf('%02d.%02d', $matches[1], $matches[2]);
    }
    return $str;
}

// Process headchecks
foreach ($headchecks as $hc) {
    $day = Carbon::parse($hc->diarydate)->format('l');
    $dateFormatted = Carbon::parse($hc->diarydate)->format('d-m-Y');
    $normalizedTime = normalizeTime($hc->time);

    // Collect unique time slots
    if (!in_array($normalizedTime, $uniqueTimes)) {
        $uniqueTimes[] = $normalizedTime;
    }

    // Map date to weekday
    if (in_array($day, $weekdays)) {
        $headcheckMap[$normalizedTime][$day] = [
            'headcount' => $hc->headcount,
            'signature' => $hc->signature
        ];
        $diaryDates[$day] = $dateFormatted;
    }
}

// Sort times ascending
sort($uniqueTimes);
@endphp

<div class="container" style="margin-top:4em;border:1px solid 	#d3d3d3;padding:1rem;">
 <div class="text-center mb-4">
    <h4 style="text-decoration: underline;">Children Head Checks</h4>
</div>

<!-- Room Name and Month on same line -->
<div class="form-group row justify-content-center text-center align-items-center">
    <div class="col-auto d-flex align-items-center">
        <label class="mb-0 font-weight-bold mr-2">Room Name:</label>
        <span>{{ $room->name ?? '-' }}</span>
    </div>

    <div class="col-auto d-flex align-items-center ml-4">
        <label class="mb-0 font-weight-bold mr-2">Month:</label>
        <span>{{ \Carbon\Carbon::createFromFormat('m', $month)->format('F') ?? '-' }}</span>
    </div>
</div>


    <table class="table table-bordered mt-4">
   <thead class="bg-light text-dark">
    <tr>
        <th rowspan="2" class="align-middle">Time</th>
        @foreach($weekdays as $day)
            <th colspan="2" class="text-center">
                {{ $day }}<br>
                <small>
                    {{ isset($diaryDates[$day]) 
                        ? \Carbon\Carbon::parse($diaryDates[$day])->format('d/m/Y') 
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
        $formatted = \Carbon\Carbon::createFromTime((int) $hour, (int) $minute)->format('h:i A');
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
<script>
    function printPage() {
        const btn = document.querySelector('.print-btn');
        btn.style.display = 'none'; // Hide the button
        window.print();
        setTimeout(() => {
            btn.style.display = 'inline-block'; // Show it again after printing
        }, 1000);
    }
</script>

</body>
</html>
