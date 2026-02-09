@extends('layout.master')
@section('title', 'Sleep Check')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    #filterbychildname{
        display: none;
    }
     #FilterbyCreatedBy{
        display: none;
    }
     #StatusFilter{
        display: none;
    }
     #StatusFilter_label{
        display: none;
    }
     #Filterbydate_from_label{
        display: none;
    }
     #Filterbydate_from{
        display: none;
    }
     #Filterbydate_to_label{
        display: none;
    }
       #Filterbydate_to{
        display: none;
    }

    </style>
 <style>
        .d-flex-custom{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>

     <style>
        .drop-down{
            border: 1px solid #17a2b8 !important;
            /* border-bottom-left-radius: 50px!important;
            border-bottom-right-radius: 50px!important; */
            /* border-top-left-radius: 50px!important; */
            /* border-top-right-radius: 50px!important; */
            background-color: transparent!important;
            color: #17a2b8 !important;
            text-transform: uppercase!important;
            font-weight: bold!important;
            display: block!important;
            line-height: 19.2px!important;
            font-size: 12.8px!important;
            letter-spacing: 0.8px!important;
            vertical-align: middle!important;
            padding: 12px 41.6px 9.6px 41.6px!important;
            height: 42.78px!important;
            text-align: center!important;
            -webkit-transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,-webkit-box-shadow 0.15s ease-in-out;
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,-webkit-box-shadow 0.15s ease-in-out;
            transition-property: color, background-color, border-color, box-shadow, -webkit-box-shadow;
            transition-duration: 0.15s, 0.15s, 0.15s, 0.15s, 0.15s;
            transition-timing-function: ease-in-out, ease-in-out, ease-in-out, ease-in-out, ease-in-out;
            transition-delay: 0s, 0s, 0s, 0s, 0s;
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out,-webkit-box-shadow 0.15s ease-in-out;
        }

        .drop-down:hover{
            color: #ffffff!important;
            background-color: #17a2b8 !important;
        }
        .custom-cal{
            position: absolute;
            vertical-align: middle;
            top: 8px;
            right: 10px;
            border: none;
            color: #17a2b8;
            background: transparent;
            pointer-events: none;
        }
        .custom-cal:hover{
            color: #ffffff;
            background-color: transparent;
        }
        .input-group-text{
            color: #17a2b8 !important;
            background-color: transparent!important;
        }
        .btn-lg{
            height: 42.78px!important;
        }
        .form-number{
            border: 1px solid #d7d7d7;
            outline: none;
            height: 35px;
        }
    </style>

  <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
/* 
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            padding: 20px;
            /* color: #2d3748; */
            line-height: 1.6;
        } */

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .child-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            /* box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: transform 0.3s ease, box-shadow 0.3s ease; */
        }

        .child-section:hover {
            transform: translateY(-5px);
            /* box-shadow: 
                0 35px 70px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.3); */
        }

        .child-header {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            background: linear-gradient(135deg, var(--sd-accent, #667eea), var(--sd-accent, #764ba2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        body[class*="theme-"] .child-header {
            color: #000;
            -webkit-text-fill-color: #000;
        }

        .child-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, var(--sd-accent, #667eea), var(--sd-accent, #764ba2));
            border-radius: 2px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        th, td {
            padding: 16px 20px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s ease;
        }

        th {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        tr:hover td {
            background-color: #f7fafc;
        }

        tr:last-child td {
            border-bottom: none;
        }

        input[type="time"], 
        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: white;
        }

        input[type="time"]:focus, 
        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--sd-accent, #667eea);
            box-shadow: 
                0 0 0 3px rgba(102, 126, 234, 0.1),
                0 4px 12px rgba(102, 126, 234, 0.15);
            transform: translateY(-1px);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Modern Button Styles */
        .add-row-btn,
        .save-row-btn,
        .update-row-btn {
            background: linear-gradient(135deg, var(--sd-accent, #667eea), var(--sd-accent, #764ba2));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            margin: 8px 4px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        body[class*="theme-"] .add-row-btn,
        body[class*="theme-"] .save-row-btn,
        body[class*="theme-"] .update-row-btn {
            color: #000;
        }

        .add-row-btn::before,
        .save-row-btn::before,
        .update-row-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .add-row-btn:hover,
        .save-row-btn:hover,
        .update-row-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        body[class*="theme-"] .add-row-btn:hover,
        body[class*="theme-"] .save-row-btn:hover,
        body[class*="theme-"] .update-row-btn:hover {
            color: #000;
        }

        .add-row-btn:hover::before,
        .save-row-btn:hover::before,
        .update-row-btn:hover::before {
            left: 100%;
        }

        .remove-row-btn,
        .delete-row-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            margin: 8px 4px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .remove-row-btn::before,
        .delete-row-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            /* background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); */
            transition: left 0.5s;
        }

        .remove-row-btn:hover,
        .delete-row-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }

        .remove-row-btn:hover::before,
        .delete-row-btn:hover::before {
            left: 100%;
        }

        .remove-row-btn:active,
        .delete-row-btn:active {
            transform: translateY(0);
        }

        .add-row-btn:active,
        .save-row-btn:active,
        .update-row-btn:active {
            transform: translateY(0);
        }

        /* Enhanced Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .child-section {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Improved Form Controls */
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .child-section {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .child-header {
                font-size: 1.4rem;
            }
            
            th, td {
                padding: 12px 8px;
                font-size: 14px;
            }
            
            .add-row-btn,
            .save-row-btn,
            .update-row-btn,
            .remove-row-btn,
            .delete-row-btn {
                padding: 10px 16px;
                font-size: 12px;
                margin: 4px 2px;
            }
        }

        /* Loading Animation for Buttons */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Subtle Pulse Animation for Interactive Elements */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        .child-section:nth-child(even) {
            animation-delay: 0.2s;
        }

        .child-section:nth-child(odd) {
            animation-delay: 0.1s;
        }

        /* Dark Mode Support */
    
    </style>
@endsection
@section('content')
<div class="d-flex justify-content-end align-items-center" style="margin-right: 20px; margin-top: -50px; gap: 10px; flex-wrap: wrap;">

    {{-- Center Dropdown --}}
    <div class="dropdown mr-2">
        <button class="btn btn-outline-info btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important; left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
                   style="background-color:white;" data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Room Dropdown --}}
    <div class="dropdown mr-2">
        @if(empty($rooms))
            <div class="btn btn-outline-info btn-lg dropdown-toggle">NO ROOMS AVAILABLE</div>
        @else
            <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="roomDropdown" data-toggle="dropdown" data-selected-room="{{ request('roomid', $roomid) }}">
                 {{ $selectedroom->name ?? 'Select Room' }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="roomDropdown">
                @foreach($rooms as $room)
                    <a class="dropdown-item" href="{{ url()->current() }}?centerid={{ $centerid }}&roomid={{ $room->id }}">
                        {{ strtoupper($room->name) }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Date Picker --}}
    @php
        $calDate = request('date')
            ? \Carbon\Carbon::parse(request('date'))->format('d-m-Y')
            : \Carbon\Carbon::parse($date ?? now())->format('d-m-Y');
    @endphp
    <div class="form-group mb-0">
        <div class="input-group date">
            <input type="text" class="form-control drop-down" id="txtCalendar" name="start_date" value="{{ $calDate }}">
            <span class="input-group-text input-group-append custom-cal btn-outline-info">
                <i class="simple-icon-calendar"></i>
            </span>
        </div>
    </div>

</div>

 <hr class="mt-3">
      <!-- filter  -->
    
             <!-- filter ends here  -->

<main class="default-transition" style="padding-block:1em;padding-inline:2em;">
    @if(Auth::user()->userType != 'Parent')
           <div class="col-5 d-flex justify-content-start align-items-center top-right-button-container mb-4">
    <i class="fas fa-filter mx-2" style="color:#17a2b8;"></i>

       <select name="filter" id="" onchange="showfilter(this.value)" class="form-control form-control-sm border-info uniform-input col-3">
        <option value="">Choose</option>
        <option value="childname">Child Name</option>
      
    </select>

    <input 
        type="text" 
        name="filterbychildname" 
        id="filterbychildname"
        class="form-control border-info ml-2" 
        placeholder="Filter by Child name" onkeyup="filterbyChildname(this.value)">
</div>
@endif

    <div class="default-transition">
        <div class="container-fluid">
            <div class="row">


                    <!-- <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
  <h2 class="mb-0">Sleep Check</h2>
  <p class="mb-0 text-muted mx-md-4">
    <a href="">Dashboard</a><span class="mx-2">|</span> <span>Sleep check List</span>
  </p>
</div>



    </div>
    <hr class="mt-3">
  </div>   -->



<input type="hidden" id="roomid" value="{{ $roomid }}" >
<input type="hidden" id="date" value="{{ $calDate }}" >

<div class="container sleepcheck-data">
  @foreach($children as $child)
    <div class="card child-section" id="child{{ $child->id }}">
      <div class="child-header">
        @if (!empty($child->imageUrl))
          <div class="child-avatar" style="display: inline-block; margin-right: 10px;">
            <img src="{{ asset($child->imageUrl) }}" alt="{{ $child->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
          </div>
        @else
          <div class="child-avatar" style="display: inline-block; margin-right: 10px;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #ccc; display: inline-flex; align-items: center; justify-content: center;">
              <span style="font-size: 18px; color: #666;">
                {{ strtoupper(substr($child->name, 0, 1)) }}
              </span>
            </div>
          </div>
        @endif
        <span>{{ $child->name }} {{ $child->lastname }}</span>
      </div>

      <table>
        <thead>
          <tr>
            <th>Time</th>
            <th>Breathing</th>
            <th>Body Temperature</th>
            <th>Notes</th>
             <th>Signature</th>
                @if(Auth::user()->userType != 'Parent')
            <th>Action</th>

            @endif
          </tr>
        </thead>
        <tbody>
          @php
            $childSleepChecks = $sleepChecks->where('childid', $child->id)->sortBy('time');
          
          @endphp
        
          @foreach($childSleepChecks as $sleep)
            <tr data-id="{{ $sleep->id }}">
              <td><input type="time" value="{{ $sleep->time }}" /></td>
              <td>
                <select>
                  <option value="">Select</option>
                  <option value="Regular" {{ $sleep->breathing == 'Regular' ? 'selected' : '' }}>Regular</option>
                  <option value="Fast" {{ $sleep->breathing == 'Fast' ? 'selected' : '' }}>Fast</option>
                  <option value="Difficult" {{ $sleep->breathing == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                </select>
              </td>
              <td>
                <select>
                  <option value="">Select</option>
                  <option value="Warm" {{ $sleep->body_temperature == 'Warm' ? 'selected' : '' }}>Warm</option>
                  <option value="Cool" {{ $sleep->body_temperature == 'Cool' ? 'selected' : '' }}>Cool</option>
                  <option value="Hot" {{ $sleep->body_temperature == 'Hot' ? 'selected' : '' }}>Hot</option>
                </select>
              </td>
              <td><textarea rows="2">{{ $sleep->notes }}</textarea></td>
               <td><input type="text" value="{{ $sleep->signature }}" > </td>
               @if(Auth::user()->userType != 'Parent')
              <td>
                <button class="update-row-btn btn-outline-info" onclick="updateRow(this,' {{ $child->id }} ',' {{ $sleep->id }}')">Update</button>
                <button class="delete-row-btn btn-outline-info" onclick="deleteRow(this, '{{ $sleep->id }}')">Delete</button>
              </td>
              @endif
            </tr>
          @endforeach

          <tr>
            <td><input type="time" name="children[{{ $child->id }}][time][]"></td>
            <td>
              <select name="children[{{ $child->id }}][breathing][]">
                <option value="">Select</option>
                <option value="Regular">Regular</option>
                <option value="Fast">Fast</option>
                <option value="Difficult">Difficult</option>
              </select>
            </td>
            <td>
              <select name="children[{{ $child->id }}][temperature][]">
                <option value="">Select</option>
                <option value="Warm">Warm</option>
                <option value="Cool">Cool</option>
                <option value="Hot">Hot</option>
              </select>
            </td>
            <td><textarea rows="2" name="children[{{ $child->id }}][notes][]" placeholder="Sleep Check List Notes..."></textarea></td>
          <td><input type="text"name="children[{{ $child->id }}][signature][]" value="" placeholder="signature"> </td>
            @if(Auth::user()->userType != 'Parent')
            <td>
              <button class="save-row-btn btn-outline-info" onclick="saveRow(this, '{{ $child->id }}')">Save</button>
              <button class="remove-row-btn btn-outline-info" onclick="removeRow(this)">Remove</button>
            </td>
            @endif
          </tr>
        </tbody>
      </table>
       @if(Auth::user()->userType != 'Parent')
      <button class="add-row-btn" onclick="addRow('child{{ $child->id }}', '{{ $child->id }}')">+ Add 10-Min Entry</button>
      @endif
    </div>
  @endforeach
</div>
                </div>
        </div>
    </div>
</main>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>
    flatpickr("#txtCalendar", {
        dateFormat: "d-m-Y",
        defaultDate: "{{ $calDate }}",
        maxDate: "today"
    });
</script>

<script>
    function addRow(childId, childDbId) {
        const tableBody = document.querySelector(`#${childId} table tbody`);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="time" name="children[${childDbId}][time][]"></td>
            <td>
                <select name="children[${childDbId}][breathing][]">
                    <option value="">Select</option>
                    <option value="Regular">Regular</option>
                    <option value="Fast">Fast</option>
                    <option value="Difficult">Difficult</option>
                </select>
            </td>
            <td>
                <select name="children[${childDbId}][temperature][]">
                    <option value="">Select</option>
                    <option value="Warm">Warm</option>
                    <option value="Cool">Cool</option>
                    <option value="Hot">Hot</option>
                </select>
            </td>
            <td><textarea rows="2" name="children[${childDbId}][notes][]" placeholder="Sleep Check List Notes..."></textarea></td>
             <td><input type="text" name="children[${childDbId}][signature][]" placeholder="signature"></td>
            <td>
                <button class="save-row-btn btn-outline-info" onclick="saveRow(this, ${childDbId})">Save</button>
                <button class="remove-row-btn btn-outline-info" onclick="removeRow(this)">Remove</button>
            </td>
        `;
        tableBody.appendChild(row);
    }

    function removeRow(button) {
        button.closest('tr').remove();
    }

    function saveRow(button, childId) {
        const row = button.closest('tr');
        const timeInput = row.querySelector('input[type="time"]');
        const breathingSelect = row.querySelector('select[name*="breathing"]');
        const temperatureSelect = row.querySelector('select[name*="temperature"]');
      
        const notesTextarea = row.querySelector('textarea');

        const roomIdValue = document.getElementById("roomid").value;
        const dateValue = document.getElementById("date").value;

        const time = timeInput.value;
        const breathing = breathingSelect.value;
        const temperature = temperatureSelect.value;
        const notes = notesTextarea.value;
          const signature = row.querySelector('input[type="text"]').value;

        if (!time || !breathing || !temperature) {
            alert("Please fill all required fields.");
            return;
        }

        const formData = new FormData();
        formData.append('childid', childId);
        formData.append('roomid', roomIdValue);
        formData.append('diarydate', dateValue);
        formData.append('time', time);
        formData.append('breathing', breathing);
        formData.append('body_temperature', temperature);
          formData.append('signature', signature);
        if (notes) {
            formData.append('notes', notes);
        }

        button.disabled = true;
        button.textContent = 'Saving...';

        fetch("{{ route('sleepcheck.save') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(result => {
               Swal.fire({
    title: 'Success!',
    text: result.message,
    icon: 'success',
    confirmButtonColor: '#28a745',
    confirmButtonText: 'OK'
}).then(() => {
    // Wait for 2 seconds after clicking OK, then reload
    setTimeout(function () {
        location.reload();
    }, 1000); // 2 seconds
});
            // alert(result.message);
            // button.disabled = false;
            // button.textContent = 'Saved';
            // setTimeout(() => location.reload(), 500);
        })
        .catch(err => {
            console.error(err);
            alert("Error saving data.");
            button.disabled = false;
            button.textContent = 'Save';
        });
    }

    function updateRow(button, childId, entryId) {
        const row = button.closest('tr');
        const time = row.querySelector('input[type="time"]').value;
        const selects = row.querySelectorAll('select');
        const breathing = selects[0]?.value || '';
        const temperature = selects[1]?.value || '';
        const notes = row.querySelector('textarea').value;
        const roomIdValue = document.getElementById("roomid").value;
        const dateValue = document.getElementById("date").value;
          const signature = row.querySelector('input[type="text"]').value;

        if (!time || !breathing || !temperature) {
            alert("Please fill all fields.");
            return;
        }

        const formData = new FormData();
        formData.append('id', entryId);
        formData.append('childid', childId);
        formData.append('roomid', roomIdValue);
        formData.append('diarydate', dateValue);
        formData.append('time', time);
        formData.append('breathing', breathing);
        formData.append('body_temperature', temperature);
        formData.append('notes', notes);
        formData.append('signature', signature);
        button.disabled = true;
        button.textContent = "Updating...";

     fetch("{{ route('sleepcheck.update') }}", {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: formData
})
.then(res => res.json())
.then(result => {

    if(result.status === false) {
   Swal.fire({
    title: 'Error!',
    text: result.message,
    icon: 'error',
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'OK'
}).then(() => {
    // Re-enable the button after user clicks OK
    button.disabled = false;
    button.textContent = "Update";
});
      
    } else {
        Swal.fire({
            title: 'Success!',
            text: result.message,
            icon: 'success',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'OK'
        }).then(() => {
            // Reload page after 1 second
            setTimeout(() => {
                location.reload();
            }, 1000);
        });
    }

})
.catch(err => {
    console.error(err);
    Swal.fire({
        title: 'Error!',
        text: 'Update failed due to server error.',
        icon: 'error',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'OK'
    });
});
    }

    function deleteRow(button, entryId) {
        // if (!confirm("Are you sure you want to delete this entry?")) return;


        Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',    // Blue
    cancelButtonColor: '#d33',        // Red
    confirmButtonText: 'Yes, do it!',
    cancelButtonText: 'Cancel'
}).then((result) => {
    if (result.isConfirmed) {
        // User confirmed the action
        // Perform your logic here (e.g., AJAX request, delete, etc.)
             const formData = new FormData();
        formData.append('id', entryId);

        button.disabled = true;
        button.textContent = "Deleting...";

        fetch("{{ route('sleepcheck.delete') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
              Swal.fire({
    title: 'Success!',
    text: result.message,
    icon: 'success',
    confirmButtonColor: '#28a745',
    confirmButtonText: 'OK'
}).then(() => {
    // Wait for 2 seconds after clicking OK, then reload
    setTimeout(function () {
        location.reload();
    }, 1000); // 2 seconds
});
                // alert(result.message);
                // button.closest('tr').remove();
                // location.reload();
            } else {
                alert(result.message);
                button.disabled = false;
                button.textContent = "Delete";
                
            }
        })
        .catch(err => {
            console.error(err);
            alert("Delete failed.");
            button.disabled = false;
            button.textContent = "Delete";
        });
        // console.log("Confirmed");
    } else {
        console.log("Cancelled");
    }
});


   
    }

    $(document).on('change', '#txtCalendar', function() {
        const date = $(this).val();
        const centerid = "{{ $centerid }}";
        const roomid = "{{ $roomid }}";
        const url = "{{ route('sleepcheck.list') }}?centerid=" + centerid + "&roomid=" + roomid + "&date=" + date;
        window.location.href = url;
    });


function filterbyChildname(childname) {
    let roomid = $('#roomDropdown').data('selected-room');
    let date   = $('#txtCalendar').val();
    console.log(roomid + ' ' + date + ' ' + childname);

    $.ajax({
        url: 'filter-sleep-list-by-child',
        method: 'GET',
        data: {
            roomid: roomid,
            date: date,
            child_name:childname
        },
        success: function(response) {
          console.log(response);
            let container = $('.sleepcheck-data');
            container.empty(); // Clear old data

            response.data.forEach(item => {
                let child = item.child;
                let checks = item.sleep_checks;
                 const assetBaseUrl = "{{ asset('') }}";

                // Avatar handling
                let avatar = child.image 
                    ? `<img src="${assetBaseUrl}${child.image}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">`
                    : `<div style="width:40px;height:40px;border-radius:50%;background:#ccc;display:flex;align-items:center;justify-content:center;">
                           <span style="font-size:18px;color:#666;">${child.name.charAt(0).toUpperCase()}</span>
                       </div>`;

                let html = `
                    <div class="card child-section" id="child${child.id}">
                        <div class="child-header">
                            <div class="child-avatar" style="display:inline-block;margin-right:10px;">${avatar}</div>
                            <span>${child.name} ${child.lastname}</span>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Breathing</th>
                                    <th>Body Temperature</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                // Existing sleep checks
                checks.forEach(sleep => {
                    html += `
                        <tr data-id="${sleep.id}">
                            <td><input type="time" value="${sleep.time}" /></td>
                            <td>
                                <select>
                                    <option value="">Select</option>
                                    <option value="Regular" ${sleep.breathing === 'Regular' ? 'selected' : ''}>Regular</option>
                                    <option value="Fast" ${sleep.breathing === 'Fast' ? 'selected' : ''}>Fast</option>
                                    <option value="Difficult" ${sleep.breathing === 'Difficult' ? 'selected' : ''}>Difficult</option>
                                </select>
                            </td>
                            <td>
                                <select>
                                    <option value="">Select</option>
                                    <option value="Warm" ${sleep.body_temperature === 'Warm' ? 'selected' : ''}>Warm</option>
                                    <option value="Cool" ${sleep.body_temperature === 'Cool' ? 'selected' : ''}>Cool</option>
                                    <option value="Hot" ${sleep.body_temperature === 'Hot' ? 'selected' : ''}>Hot</option>
                                </select>
                            </td>
                            <td><textarea rows="2">${sleep.notes ?? ''}</textarea></td>
                             <td><input type="text" name="" value="${sleep.signature}" placeholder="signature"></td>
                            <td>
                                <button class="update-row-btn btn-outline-info" onclick="updateRow(this, '${child.id}', '${sleep.id}')">Update</button>
                                <button class="delete-row-btn btn-outline-info" onclick="deleteRow(this, '${sleep.id}')">Delete</button>
                            </td>
                        </tr>
                    `;
                });

                // Empty row for new entry
                html += `
                    <tr>
                        <td><input type="time" name="children[${child.id}][time][]"></td>
                        <td>
                            <select name="children[${child.id}][breathing][]">
                                <option value="">Select</option>
                                <option value="Regular">Regular</option>
                                <option value="Fast">Fast</option>
                                <option value="Difficult">Difficult</option>
                            </select>
                        </td>
                        <td>
                            <select name="children[${child.id}][temperature][]">
                                <option value="">Select</option>
                                <option value="Warm">Warm</option>
                                <option value="Cool">Cool</option>
                                <option value="Hot">Hot</option>
                            </select>
                        </td>
                        <td><textarea rows="2" name="children[${child.id}][notes][]" placeholder="Sleep Check List Notes..."></textarea></td>
                          <td><input type="text" name="children[${child.id}][signature][]" value="" placeholder="signature"></td>
                        <td>
                            <button class="save-row-btn btn-outline-info" onclick="saveRow(this, '${child.id}')">Save</button>
                            <button class="remove-row-btn btn-outline-info" onclick="removeRow(this)">Remove</button>
                        </td>
                    </tr>
                `;

                html += `
                            </tbody>
                        </table>
                        <button class="add-row-btn" onclick="addRow('child${child.id}', '${child.id}')">+ Add 10-Min Entry</button>
                    </div>
                `;

                container.append(html);
            });
        },
        error: function(xhr) {
            console.error('AJAX error:', xhr.responseText);
        }
    });
}

function showfilter(val) {
    // Hide all filters first
    $('#FilterbyTitle, #FilterbyCreatedBy, #StatusFilter_label, #statusFilter, #Filterbydate_to_label, #Filterbydate_to, #Filterbydate_from_label, #Filterbydate_from').hide();

    // Clear values of all fields
    $('#FilterbyTitle input, #FilterbyCreatedBy input, #statusFilter, #Filterbydate_to, #Filterbydate_from.childname')
        .val('')
        .prop('checked', false)
        .trigger('change');

    if (val === 'childname') {
        $('#filterbychildname').show();
    }
    else if (val === 'status') {
        $('#statusFilter_label').show();
        $('#statusFilter').show();
    }
    else if (val === 'title') {
        $('#FilterbyTitle').show();
    }
    else if (val === 'date') {
        $('#Filterbydate_to_label').show();
        $('#Filterbydate_to').show();
        $('#Filterbydate_from_label').show();
        $('#Filterbydate_from').show();
    }
    else {
        window.location.reload();
    }
}

</script>

@endpush
@stop