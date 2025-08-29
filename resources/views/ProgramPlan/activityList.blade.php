@extends('layout.master')
@section('title', 'Activities')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<style>
    .sub-activity-scroll {
    max-height: 100px;  /* adjust as needed */
    overflow-y: auto;
    overflow-x: hidden; /* prevent horizontal scrollbar */
   /* space for scrollbar */
}

</style>
@endsection

@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;">

    <div class="text-zero top-right-button-container">

        <!-- <div class="btn-group mr-1">
            <div class="dropdown">
                <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="centerDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
                    style="top:3% !important;left:13px !important;">
                
                </div>
            </div>

        </div> -->

    
        <!-- <a href="#" class="btn btn-primary btn-lg top-right-button" id="addnewbtn" data-toggle="modal" data-target="#templateModal">Activities</a>
        

        <a href="" class="btn btn-outline-info"
            style="margin-left:5px;">
            Add ProgramPlan
        </a> -->

   
    </div>

</div>


  <!-- filter  -->
   

             <!-- filter ends here  -->
<!-- resources/views/program_plan_list.blade.php -->

<div class="main-container mt-4">
   
    <!-- Page Header -->
    <!-- <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-clipboard-list"></i>
                Program Plan Management
            </h1>
            <div class="breadcrumb-custom">
                <i class="fas fa-home"></i>
                Dashboard
                <span class="separator">|</span>
                <span>Program Plan</span>
            </div>
        </div> -->

    <!-- Main Content -->
     
 <div class="container-fluid px-0 mt-5">
    <div class="program-plan-container">
          <!-- @if(Auth::user()->userType != 'Parent') -->
        <div class="card-header-custom mb-3 mt-4">
            <h5 class="card-header-title">
                <i class="fas fa-table"></i> SUBJECTS / Activities
            </h5>
        </div>
        <!-- @endif -->
<div class="program-plan">


<div class="row">
    @foreach($subjects as $subject)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0 rounded-3 subject-card"
                 data-toggle="modal"
                 data-target="#activitymodal"
                 data-activities='@json($subject->activities)'>
                <div class="card-body text-center">
                    <h6 class="card-title text-primary fw-bold">
                        {{ $subject->name }}
                    </h6>
                </div>
            </div>
        </div>
    @endforeach
</div>



 


</div>
    </div>
</div>

</div>

{{-- Activity Modal --}}
<div class="modal fade" id="activitymodal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Select Activity</h5>
        <input type="text" id="activitySearch" class="form-control ml-3" placeholder="Search activity..." style="max-width:250px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="activityList"></div>
    </div>
  </div>
</div>

{{-- SubActivity Modal --}}
<div class="modal fade" id="subactivitymodal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Select SubActivity</h5>
        <input type="text" id="subActivitySearch" class="form-control ml-3" placeholder="Search subactivity..." style="max-width:250px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="subActivityList">
     
      </div>
    </div>
  </div>
</div>

<!-- edit mode of activity and sub activity -->
 <div class="modal fade" id="editactivitymodal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Edit Activity</h5>
        <input type="text" id="subActivitySearch" class="form-control ml-3" placeholder="Search subactivity..." style="max-width:250px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="editActivityList"></div>
    </div>
  </div>
</div>


 <div class="modal fade" id="editsubactivitymodal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Edit Sub-Activity</h5>
        <input type="text" id="subActivitySearch" class="form-control ml-3" placeholder="Search subactivity..." style="max-width:250px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="editsubActivityList"></div>
    </div>
  </div>
</div>

@endsection



@push('scripts')
<script>
$(document).ready(function () {
    // Handle Subject click -> open Activity Modal
    $('.subject-card').on('click', function () {
        let activities = $(this).data('activities');
        let activityHtml = '';

      if (activities.length > 0) {
    activities.forEach(activity => {
        // ✅ parse added_at to Date object
        let addedAt = new Date(activity.added_at); 
        let cutoffDate = new Date("2025-08-25"); // yyyy-mm-dd format

        // ✅ prepare buttons conditionally
        let actionButtons = '';
        if (addedAt > cutoffDate) {
            actionButtons = `
                <button type="button" class="btn btn-warning mr-2 editactivity" 
                    data-activity='${JSON.stringify(activity)}'>
                    <i class="fa fa-pencil mr-1"></i>
                </button>
                <button type="button" class="btn btn-danger deleteactivity" 
                    data-activity_id="${activity.idActivity}">
                    <i class="fa fa-trash mr-1"></i>
                </button>
            `;
        }

        // ✅ main card template
        activityHtml += `
            <div class="col-md-6 mb-3">
                <div class="card  shadow-sm h-100 border-0 rounded-3"
                    >
                    <div class="card-body text-center activity-card" data-subactivities='${JSON.stringify(activity.sub_activities)}'>
                        <h6 class="card-title text-primary fw-bold">${activity.title}</h6>
                    </div>
                    <div class="card-footer text-center d-flex flex-row gap-2">
                        ${actionButtons}
                    </div>
                </div>
            </div>`;
    });
} else {
    activityHtml = `<p class="text-center text-muted">No activities available</p>`;
}


        $('#activityList').html(`<div class="row">${activityHtml}</div>`);
    });

    // Search in Activity Modal
    $('#activitySearch').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $('#activityList .activity-card').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Handle Activity click -> open SubActivity Modal
    $(document).on('click', '.activity-card', function () {
        let subactivities = $(this).data('subactivities');
        let subHtml = '';

     if (subactivities && subactivities.length > 0) {
    subactivities.forEach(sub => {
        // ✅ parse sub.added_at as Date (assuming your DB/API returns it)
        let addedAt = new Date(sub.added_at);
        let cutoffDate = new Date("2025-08-25"); // YYYY-MM-DD format

        // ✅ prepare buttons conditionally
        let actionButtons = '';
        if (addedAt > cutoffDate) {
            actionButtons = `
                <button type="button" class="btn btn-warning mr-2 editsubactivity" 
                    data-subactivity='${JSON.stringify(sub)}'>
                    <i class="fa fa-pencil mr-1"></i>
                </button>
                <button type="button" class="btn btn-danger deletesubactivity" 
                    data-subactivity_id="${sub.idSubActivity}">
                    <i class="fa fa-trash mr-1"></i>
                </button>
            `;
        }

        // ✅ final HTML
        subHtml += `
            <div class="col-md-6 mb-3 ">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center">
                        <h6 class="card-title text-success fw-bold">${sub.title}</h6>
                    </div>
                    <div class="card-footer text-center d-flex flex-row gap-2">
                        ${actionButtons}
                    </div>
                </div>
            </div>`;
    });
} else {
    subHtml = `<p class="text-center text-muted">No subactivities available</p>`;
}


        $('#subActivityList').html(`<div class="row">${subHtml}</div>`);
    });

    // Search in SubActivity Modal
    $('#subActivitySearch').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $('#subActivityList .card').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Close first modal before opening SubActivity modal
    $(document).on('click', '.activity-card', function () {
        $('#activitymodal').modal('hide');
        setTimeout(() => {
            $('#subactivitymodal').modal('show');
        }, 300);
    });

    // 🔹 Debug Logs for Activity buttons
    $(document).on('click', '.editactivity', function (e) {
         e.stopPropagation(); // prevent opening subactivity modal

let Activity = $(this).data('activity');

   if (typeof Activity === 'string') {
        Activity = JSON.parse(Activity);
    }

           let html = `
<form id="editSubActivityForm " class="w-100" action={{ route('observation.update-activity')}} method="post">
    <input type="hidden" name="activityid" value="${Activity.idActivity}" />
    @csrf

    <div class="form-group mb-3">
        <label for="subActivityTitle" class="form-label">Title</label>
        <input 
            type="text" 
            id="subActivityTitle" 
            name="title" 
            class="form-control" 
            placeholder="Enter Sub-Activity Title" 
            value="${Activity.title}" 
            required
        />
    </div>

    <div class="text-right">
        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save mr-1"></i> Save Changes
        </button>
    </div>
</form>`;
        
   $('#editActivityList').html(html);
    $('#activitymodal').modal('hide');
    $('#editactivitymodal').modal('show');
    console.log("Edit Activity clicked:", Activity);

        console.log("Edit Activity clicked:", $(this).data('activity'));
    });

$(document).on('click', '.deleteactivity', function (e) {
    e.stopPropagation(); // prevent triggering other click events

    let idActivity = $(this).data('activity_id');
    let row = $(this).closest('.col-md-3'); // optional: remove row from DOM after deletion

    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the activity and all related subactivities!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('observation.delete-activity') }}",
                type: "POST",
                data: { idActivity: idActivity },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Activity has been deleted.',
                        'success'
                    ).then(() => {
                        // Reload page after confirmation
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong while deleting.',
                        'error'
                    );
                    console.error("❌ Error:", error);
                    console.error("Status:", status);
                    console.error("Response:", xhr.responseText);
                }
            });
        }
    });
});


    // 🔹 Debug Logs for SubActivity buttons
    $(document).on('click', '.editsubactivity', function () {
          let subActivity = $(this).data('subactivity');

    // if jQuery reads string, parse it
    if (typeof subActivity === 'string') {
        subActivity = JSON.parse(subActivity);
    }

   let html = `
<form id="editSubActivityForm " class="w-100" action={{ route('observation.update-subactivity')}} method="post">
    <input type="hidden" name="subactivityid" value="${subActivity.idSubActivity}" />
    @csrf

    <div class="form-group mb-3">
        <label for="subActivityTitle" class="form-label">Title</label>
        <input 
            type="text" 
            id="subActivityTitle" 
            name="title" 
            class="form-control" 
            placeholder="Enter Sub-Activity Title" 
            value="${subActivity.title}" 
            required
        />
    </div>

    <div class="text-right">
        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save mr-1"></i> Save Changes
        </button>
    </div>
</form>`;


    $('#editsubActivityList').html(`<div class="row">${html}</div>`);
      $('.modal').modal('hide');
    $('#editsubactivitymodal').modal('show');
        console.log("Edit SubActivity clicked:", $(this).data('subactivity_id'));
    });

$(document).on('click', '.deletesubactivity', function () {
    let idSubActivity = $(this).data('subactivity_id');
    let row = $(this).closest('.col-md-6'); // optional: row to remove on success

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request only if confirmed
            $.ajax({
                url: "{{ route('observation.delete-subactivity') }}",
                type: "POST",
                data: { idSubActivity: idSubActivity },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Subactivity has been deleted.',
                        'success'
                    );
                    // Optionally remove the row from DOM
                  location.reload();

                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong while deleting.',
                        'error'
                    );
                    console.error("❌ Error:", error);
                    console.error("Status:", status);
                    console.error("Response:", xhr.responseText);
                }
            });
        }
    });
});

});
</script>




@endpush

@include('layout.footer')