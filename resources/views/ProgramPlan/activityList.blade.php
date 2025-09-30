@extends('layout.master')
@section('title', 'Activities')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<style>
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
    /* prevent layout shift */
  }

  body.modal-open {
    overflow: hidden !important;
  }

  body {
    overflow-y: auto !important;
  }

  /* Clickable cards */
  .activity-card,
  .subactivity-card,
  .card-title,
  .card-body {
    cursor: pointer !important;
    user-select: none !important;
  }

  /* .sub-activity-scroll {
    max-height: 100px; */
  /* adjust as needed */
  /* overflow-y: auto;
    overflow-x: hidden; */
  /* prevent horizontal scrollbar */
  /* space for scrollbar */
  /* } */

  .top-right-button-container {
    position: relative;
    z-index: 10;
  }

  .top-right-button-container .btn {
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s ease-in-out;
  }

  .top-right-button-container .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  /* for backdrop */
  /* -------------------------
   Make modal body reliably scrollable
   ------------------------- */
  .modal-dialog.modal-dialog-scrollable {
    max-height: calc(100vh - 120px);
    /* room for header/footer */
    margin: 1.5rem auto;
  }

  .modal-dialog.modal-dialog-scrollable .modal-content {
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 120px);
    overflow: hidden;
    /* keep scroll inside modal-body */
  }

  .modal-dialog.modal-dialog-scrollable .modal-body {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    /* smooth scrolling on iOS */
    padding-right: 1rem;
    /* avoid hidden overflow behind scrollbar */
  }

  /* ensure any lists/cards inside modal also scroll if they are tall */
  #activityList,
  #subActivityList,
  #editActivityList,
  #editsubActivityList {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* -------------------------
   Backdrop / stacking helpers
   ------------------------- */
  /* Base values (Bootstrap uses 1040/1050); keep backdrop under modal */
  .modal-backdrop {
    z-index: 1040 !important;
  }

  /* default modal above backdrop */
  .modal {
    z-index: 1050 !important;
    pointer-events: auto !important;
  }

  /* topmost modal should sit above other modals/backdrops.
   This rule raises the currently shown modal a lot so it won't be
   visually blocked by leftover backdrops from previous modals */
  .modal.show {
    z-index: 2000 !important;
  }

  /* ensure backdrops sit under the topmost modal */
  .modal-backdrop.show {
    z-index: 1990 !important;
  }

  /* avoid accidental pointer blocking by stray backdrop layers */
  .modal-backdrop {
    pointer-events: auto;
  }

  /* small visual tweaks for nested cards so scrollbar is visible */
  .card>.card-body {
    overflow: visible;
  }

  /* optional: give a clear class you can apply to nested modals
   (useful if you add a small JS to mark nested modals) */
  .modal.nested-modal {
    z-index: 2100 !important;
  }

  .modal-backdrop.nested-backdrop {
    z-index: 2090 !important;
  }

  .card-title {
    cursor: pointer !important;
  }
</style>
@endsection

@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end"
  style="margin-right: 20px;margin-top: -60px;">
  <div class="top-right-button-container d-flex justify-content-end align-items-center mb-3">
    <div class="btn-group">
      @if(isset($permission) && $permission->addActivity == 1 || Auth::user()->userType == "Superadmin" )
      <button class="btn btn-outline-info mr-2" id="addActivityBtn">
        <i class="fas fa-plus-circle"></i> Add Activity
      </button>
      @endif
      @if(isset($permission) && $permission->addsubActivity == 1 || Auth::user()->userType == "Superadmin" )
      <button class="btn btn-outline-info" id="addSubActivityBtn">
        <i class="fas fa-plus"></i> Add Sub-Activity
      </button>
      @endif
    </div>
  </div>


</div>

<hr>

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
      <!-- <div class="card-header-custom mb-3 mt-4">
        <h5 class="card-header-title text-info">
          <i class="fas fa-table"></i> SUBJECTS / Activities
        </h5>
      </div> -->
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
        <div>
          <button type="button" class="btn btn-back" id="backToActivity">Back</button>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>



      </div>
      <div class="modal-body" id="subActivityList">

      </div>
    </div>
  </div>
</div>

<!-- edit mode of activity and sub activity -->
<div class="modal fade" id="editactivitymodal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content shadow-lg border-0 rounded-3">

      <!-- Header -->
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold text-success" id="editActivityModalLabel">
          <i class="fa fa-tasks me-2"></i> Edit Activity
        </h5>

        <!-- Search Box -->


        <!-- Back Button -->
        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="editbackToActivity">
          <i class="fa fa-arrow-left me-1"></i> Back
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body p-4" id="editActivityList">
        <!-- Content will be injected here -->
      </div>

    </div>
  </div>
</div>



<div class="modal fade" id="editsubactivitymodal" tabindex="-1" aria-labelledby="editSubActivityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content shadow-lg border-0 rounded-3">

      <!-- Header -->
      <div class="modal-header bg-light d-flex align-items-center">
        <h5 class="modal-title fw-bold text-success" id="editSubActivityModalLabel">
          <i class="fa fa-tasks me-2"></i> Edit Sub-Activity
        </h5>

        <!-- Search Box -->


        <!-- Back Button -->
        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="backTosubActivity">
          <i class="fa fa-arrow-left me-1"></i> Back
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body p-4" id="editsubActivityList">
        <!-- Sub-activity content will load here -->
      </div>

    </div>
  </div>
</div>

<!-- add activity nd sub activity -->

<!-- Modal Structure -->
<div class="modal" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="activityModalLabel">Add New Activity</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="activityForm">
          <div class="mb-3">
            <label for="subjectSelect" class="form-label">Montessori Subject</label>
            <select class="form-control" id="subjectSelect" name="idSubject" required>
              <option value="" selected disabled>Select a subject</option>
              <!-- Options will be loaded via AJAX -->
            </select>
          </div>
          <div class="mb-3">
            <label for="activityTitle" class="form-label">Activity Title</label>
            <input type="text" class="form-control" id="activityTitle" name="title" required>
            <!-- Success message will appear here -->
            <div class="alert alert-success mt-2" id="successMessage" style="display: none;">
              Activity added successfully!
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveActivityBtn">Save Activity</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Structure -->
<div class="modal" id="subActivityModal" tabindex="-1" aria-labelledby="subActivityModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subActivityModalLabel">Add New Sub-Activity</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body">
        <form id="subActivityForm">
          <div class="mb-3">
            <label for="subjectSelectForSub" class="form-label">Montessori Subject</label>
            <select class="form-control" id="subjectSelectForSub" name="idSubject" required>
              <option value="" selected disabled>Select a subject</option>
              <!-- Options will be loaded via AJAX -->
            </select>
          </div>
          <div class="mb-3">
            <label for="activitySelect" class="form-label">Activity</label>
            <select class="form-control" id="activitySelect" name="idActivity" required disabled>
              <option value="" selected disabled>Select a subject first</option>
              <!-- Options will be loaded via AJAX based on subject selection -->
            </select>
          </div>
          <div class="mb-3">
            <label for="subActivityTitle" class="form-label">Sub-Activity Title</label>
            <input type="text" class="form-control" id="subActivityTitle" name="title" required>
            <!-- Success message will appear here -->
            <div class="alert alert-success mt-2" id="subActivitySuccessMessage" style="display: none;">
              Sub-Activity added successfully!
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveSubActivityBtn">Save Sub-Activity</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>






<script>
  $(document).ready(function() {
    // Handle Subject click -> open Activity Modal
    $('.subject-card').on('click', function() {
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
    $('#activitySearch').on('keyup', function() {
      let value = $(this).val().toLowerCase();
      $('#activityList .activity-card').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });

    // Handle Activity click -> open SubActivity Modal
    // Open SubActivity modal from Activity
    $(document).on('click', '.activity-card', function() {
      let subactivities = $(this).data('subactivities');

      // Close Activity Modal first
      $('#activitymodal').modal('hide');

      // Wait until activity modal is hidden
      $('#activitymodal').one('hidden.bs.modal', function() {
        // Fill subactivities
        let subHtml = '';
        if (subactivities && subactivities.length > 0) {
          subactivities.forEach(sub => {
            let addedAt = new Date(sub.added_at);
            let cutoffDate = new Date("2025-08-25");

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

            subHtml += `
                    <div class="col-md-6 mb-3 subactivity-card">
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

        // Open Subactivity Modal safely
        $('#subactivitymodal').modal('show');
      });
    });


    // Search in SubActivity Modal
    $('#subActivitySearch').on('keyup', function() {
      let value = $(this).val().toLowerCase();
      $('#subActivityList .card').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });

    // Close first modal before opening SubActivity modal
    $(document).on('click', '.activity-card', function() {
      $('#activitymodal').modal('hide');
      setTimeout(() => {
        $('#subactivitymodal').modal('show');
      }, 300);
    });


    // 🔹 Debug Logs for Activity buttons
    $(document).on('click', '.editactivity', function(e) {
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

    $(document).on('click', '.deleteactivity', function(e) {
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
            data: {
              idActivity: idActivity
            },
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
    $(document).on('click', '.editsubactivity', function() {
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

    $(document).on('click', '.deletesubactivity', function() {
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
            data: {
              idSubActivity: idSubActivity
            },
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


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addActivityBtn = document.getElementById('addActivityBtn');
    const activityForm = document.getElementById('activityForm');
    const subjectSelect = document.getElementById('subjectSelect');
    const activityModal = new bootstrap.Modal(document.getElementById('activityModal'));
    const successMessage = document.getElementById('successMessage');

    // When the Add Activity button is clicked
    addActivityBtn.addEventListener('click', function() {
      // Fetch subjects via AJAX before opening the modal
      fetchSubjects();
    });

    // Function to fetch subjects from the database
    function fetchSubjects() {
      // Show loading state
      subjectSelect.innerHTML = '<option value="" selected disabled>Loading subjects...</option>';

      // AJAX call to get subjects
      $.ajax({
        url: "{{url('Observation/getSubjects') }}",
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          // Clear loading state
          subjectSelect.innerHTML = '<option value="" selected disabled>Select a subject</option>';

          // Add the fetched subjects to the select element
          data.forEach(function(subject) {
            const option = document.createElement('option');
            option.value = subject.idSubject;
            option.textContent = subject.name;
            subjectSelect.appendChild(option);
          });

          // Open the modal after data is loaded
          activityModal.show();
        },
        error: function(xhr, status, error) {
          console.error('Error fetching subjects:', error);
          alert('Failed to load subjects. Please try again.');
        }
      });
    }

    // Form submission handler
    activityForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Get form data
      const idSubject = subjectSelect.value;
      const title = document.getElementById('activityTitle').value;
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      // AJAX call to save the activity
      $.ajax({
        url: "{{ route('Observation.addActivity') }}",
        type: 'POST',
        data: {
          idSubject: idSubject,
          title: title
        },
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // Clear the title field
            document.getElementById('activityTitle').value = '';

            // Inline success message (if you want to keep it)
            // successMessage.style.display = 'block';
            // setTimeout(function() {
            //     successMessage.style.display = 'none';
            // }, 3000);

            // 🎉 SweetAlert success popup
            Swal.fire({
              icon: 'success',
              title: 'Activity Added!',
              text: response.message || 'The activity was created successfully.',
              timer: 2000,
              showConfirmButton: false
            });

          } else {
            // ❌ SweetAlert error popup (instead of alert)
            Swal.fire({
              icon: 'error',
              title: 'Failed to Add',
              text: response.message || 'Something went wrong. Please try again.'
            });
          }

        },
        error: function(xhr, status, error) {
          console.error('Error adding activity:', error);
          alert('Failed to add activity. Please try again.');
        }
      });
    });

    // Ensure page refresh when modal is closed
    document.getElementById('activityModal').addEventListener('hidden.bs.modal', function() {
      location.reload();
    });
  });
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addSubActivityBtn = document.getElementById('addSubActivityBtn');
    const subActivityForm = document.getElementById('subActivityForm');
    const subjectSelectForSub = document.getElementById('subjectSelectForSub');
    const activitySelect = document.getElementById('activitySelect');
    const subActivityModal = new bootstrap.Modal(document.getElementById('subActivityModal'));
    const subActivitySuccessMessage = document.getElementById('subActivitySuccessMessage');

    // When the Add Sub-Activity button is clicked
    addSubActivityBtn.addEventListener('click', function() {
      // Fetch subjects via AJAX before opening the modal
      fetchSubjectsForSubActivity();
    });

    // Function to fetch subjects from the database
    function fetchSubjectsForSubActivity() {
      // Show loading state
      subjectSelectForSub.innerHTML = '<option value="" selected disabled>Loading subjects...</option>';
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      // AJAX call to get subjects
      $.ajax({
        url: "{{ route('Observation.getSubjects') }}",
        type: 'GET',
        dataType: 'json',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(data) {
          // Clear loading state
          subjectSelectForSub.innerHTML = '<option value="" selected disabled>Select a subject</option>';

          // Add the fetched subjects to the select element
          data.forEach(function(subject) {
            const option = document.createElement('option');
            option.value = subject.idSubject;
            option.textContent = subject.name;
            subjectSelectForSub.appendChild(option);
          });

          // Open the modal after data is loaded
          subActivityModal.show();
        },
        error: function(xhr, status, error) {
          console.error('Error fetching subjects:', error);
          alert('Failed to load subjects. Please try again.');
        }
      });
    }

    // Function to fetch activities based on selected subject
    function fetchActivitiesBySubject(subjectId) {
      // Disable activity select and show loading
      activitySelect.disabled = true;
      activitySelect.innerHTML = '<option value="" selected disabled>Loading activities...</option>';
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      // AJAX call to get activities for the selected subject
      $.ajax({
        url: "{{ route('Observation.getActivitiesBySubject') }}",
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        data: {
          idSubject: subjectId
        },
        dataType: 'json',
        success: function(data) {
          // Clear loading state
          activitySelect.innerHTML = '<option value="" selected disabled>Select an activity</option>';

          if (data.length === 0) {
            activitySelect.innerHTML = '<option value="" selected disabled>No activities found for this subject</option>';
          } else {
            // Add the fetched activities to the select element
            data.forEach(function(activity) {
              const option = document.createElement('option');
              option.value = activity.idActivity;
              option.textContent = activity.title;
              activitySelect.appendChild(option);
            });

            // Enable the activity select
            activitySelect.disabled = false;
          }
        },
        error: function(xhr, status, error) {
          console.error('Error fetching activities:', error);
          activitySelect.innerHTML = '<option value="" selected disabled>Error loading activities</option>';
        }
      });
    }

    // When subject is selected, fetch related activities
    subjectSelectForSub.addEventListener('change', function() {
      const selectedSubjectId = this.value;
      if (selectedSubjectId) {
        fetchActivitiesBySubject(selectedSubjectId);
      } else {
        // Reset and disable activity select if no subject is selected
        activitySelect.innerHTML = '<option value="" selected disabled>Select a subject first</option>';
        activitySelect.disabled = true;
      }
    });

    // Form submission handler
    subActivityForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Get form data
      const idActivity = activitySelect.value;
      const title = document.getElementById('subActivityTitle').value;
      const subjectSelectForSub = document.getElementById('subjectSelectForSub').value;
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      // alert(idActivity);
      // AJAX call to save the sub-activity
      $.ajax({
        url: "{{ url('Observation/addSubActivity') }} ",
        type: 'POST',
        data: {
          idActivity: idActivity,
          title: title,
          subjectSelectForSub: subjectSelectForSub
        },
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // Clear the title field
            document.getElementById('subActivityTitle').value = '';

            // Show success message below the input (your existing behavior)
            // subActivitySuccessMessage.style.display = 'block';

            // Hide success message after 3 seconds
            // setTimeout(function() {
            //     subActivitySuccessMessage.style.display = 'none';
            // }, 3000);

            // 🎉 SweetAlert success popup
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: response.message || 'Sub-Activity added successfully!',
              timer: 2000,
              showConfirmButton: false
            });
          } else {
            // ❌ SweetAlert error popup
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: response.message || 'Something went wrong!'
            });
          }
        },
        error: function(xhr, status, error) {
          console.error('Error adding sub-activity:', error);
          alert('Failed to add sub-activity. Please try again.');
        }
      });
    });

    // Ensure page refresh when modal is closed
    document.getElementById('subActivityModal').addEventListener('hidden.bs.modal', function() {
      location.reload();
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#activityModal').on('hidden.bs.modal', function() {
      location.reload();
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#subActivityModal').on('hidden.bs.modal', function() {
      location.reload();
    });
  });




  // backbutton
  $(document).on('click', '#backToActivity', function() {
    let activityData = $(this).data('activity'); // ✅ get activity data

    $('#subactivitymodal').modal('hide');

    $('#subactivitymodal').on('hidden.bs.modal', function() {
      // ✅ repopulate activity modal with data
      if (activityData) {
        $('#activityDetails').html(`
                <h5 class="text-primary">${activityData.title}</h5>
                <p>${activityData.description}</p>
            `);
      }

      // ✅ show activity modal
      $('#activitymodal').modal('show');

      // detach this handler to prevent duplicates
      $(this).off('hidden.bs.modal');
    });
  });

  $(document).on('click', '#editbackToActivity', function() {
    let activityData = $(this).data('activity'); // ✅ get activity data

    // Close the current modal (#editactivitymodal)
    $('#editactivitymodal').modal('hide');

    // When closed, open the main activity modal again
    $('#editactivitymodal').on('hidden.bs.modal', function() {
      if (activityData) {
        $('#activityDetails').html(`
                <h5 class="text-primary">${activityData.title}</h5>
                <p>${activityData.description}</p>
            `);
      }

      // ✅ show activity modal
      $('#activitymodal').modal('show');

      // detach this handler to prevent duplicates
      $(this).off('hidden.bs.modal');
    });
  });




  $(document).on('click', '#backTosubActivity', function() {
    $('#editsubactivitymodal').modal('hide');

    $('#editsubactivitymodal').on('hidden.bs.modal', function() {
      $('#subactivitymodal').modal('show');
      $(this).off('hidden.bs.modal');
    });
  });

  // Keep stacked modals/backdrops ordered so the top modal always receives scroll
  $(document).on('shown.bs.modal', '.modal', function() {
    $('.modal.show').each(function(i) {
      // each shown modal gets higher z-index so topmost is on top
      $(this).css('z-index', 1050 + (i * 20));
    });
    $('.modal-backdrop').each(function(i) {
      $(this).css('z-index', 1040 + (i * 20));
    });
  });
</script>
@include('layout.footer')
@stop