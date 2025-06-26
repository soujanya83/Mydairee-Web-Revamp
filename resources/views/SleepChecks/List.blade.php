@extends('layout.master')
@section('title', 'Sleep Check')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 <style>
        .d-flex-custom{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>

     <style>
        .drop-down{
            border: 1px solid #008ecc!important;
            border-bottom-left-radius: 50px!important;
            border-bottom-right-radius: 50px!important;
            border-top-left-radius: 50px!important;
            border-top-right-radius: 50px!important;
            background-color: transparent!important;
            color: #008ecc!important;
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
            background-color: #008ecc!important;
        }
        .custom-cal{
            position: absolute;
            vertical-align: middle;
            top: 8px;
            right: 10px;
            border: none;
            color: #0085bf;
            background: transparent;
            pointer-events: none;
        }
        .custom-cal:hover{
            color: #ffffff;
            background-color: transparent;
        }
        .input-group-text{
            color: #008ecc!important;
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
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4f8;
      padding: 20px;
      color: #333;
    }
    .container {
      max-width: 1200px;
      margin: auto;
    }
    .child-section {
      background-color: #ffffff;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .child-header {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: #2c3e50;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #e1e4e8;
    }
    th {
      background-color: #ecf0f1;
      font-weight: 600;
    }
    input[type="time"],
    select,
    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    textarea {
      resize: vertical;
    }
    .add-row-btn,
    .save-row-btn{
      background-color: #3498db;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      margin: 5px;
      transition: background-color 0.3s ease;
    
    
    }
 
    .remove-row-btn {
        background-color: red;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      margin: 5px;
      transition: background-color 0.3s ease;
    }
    .update-row-btn{
      background-color: #3498db;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      margin: 5px;
      transition: background-color 0.3s ease;
    
    
    }
 
    .delete-row-btn {
        background-color: red;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      margin: 5px;
      transition: background-color 0.3s ease;
    }

    .add-row-btn:hover,
    .save-row-btn:hover{
      background-color: #2980b9;
    }

    .remove-row-btn:hover {
    background-color: darkred;
    }
    
    .update-row-btn:hover{
      background-color: #2980b9;
    }

    .delete-row-btn:hover {
    background-color: darkred;
    }
  </style>
@endsection
@section('content')
<div class="d-flex justify-content-end align-items-center" style="margin-right: 20px; margin-top: -60px; gap: 10px; flex-wrap: wrap;">

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
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
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
            <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="roomDropdown" data-toggle="dropdown">
                {{ strtoupper($rooms->firstWhere('id', request('roomid', $roomid))->name ?? 'Select Room') }}
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
            <span class="input-group-text input-group-append custom-cal">
                <i class="simple-icon-calendar"></i>
            </span>
        </div>
    </div>

</div>


<main class="default-transition" style="padding-block:5em;padding-inline:2em;">
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

<div class="container">
  @foreach($children as $child)
    <div class="child-section" id="child{{ $child->id }}">
      <div class="child-header">
        @if (!empty($child->imageUrl))
          <div class="child-avatar" style="display: inline-block; margin-right: 10px;">
            <img src="{{ asset('assets/media/' . $child->imageUrl) }}" alt="{{ $child->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
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
            <th>Action</th>
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
              <td>
                <button class="update-row-btn" onclick="updateRow(this, {{ $child->id }}, {{ $sleep->id }})">Update</button>
                <button class="delete-row-btn" onclick="deleteRow(this, {{ $sleep->id }})">Delete</button>
              </td>
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
            <td>
              <button class="save-row-btn" onclick="saveRow(this, {{ $child->id }})">Save</button>
              <button class="remove-row-btn" onclick="removeRow(this)">Remove</button>
            </td>
          </tr>
        </tbody>
      </table>
      <button class="add-row-btn" onclick="addRow('child{{ $child->id }}', {{ $child->id }})">+ Add 10-Min Entry</button>
    </div>
  @endforeach
</div>
                </div>
        </div>
    </div>
</main>
@endsection

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
            <td>
                <button class="save-row-btn" onclick="saveRow(this, ${childDbId})">Save</button>
                <button class="remove-row-btn" onclick="removeRow(this)">Remove</button>
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


            // // alert(result.message);
            // button.disabled = false;
            // button.textContent = "Update";
            // location.reload();
        })
        .catch(err => {
            console.error(err);
            alert("Update failed.");
            button.disabled = false;
            button.textContent = "Update";
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
</script>

@endpush
@include('layout.footer')