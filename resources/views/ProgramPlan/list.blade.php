@extends('layout.master')
@section('title', 'Program Plan')
@section('parentPageTitle', 'Dashboard')

@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;">

                <div class="text-zero top-right-button-container">

                    <div class="btn-group mr-1">
                        <div class="dropdown">
        <button class="btn btn-outline-info btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
                 style="background-color:white;"  data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>

                    </div>

                    @if(isset($permission) && $permission->add == 1)
                        <!-- <a href="#" class="btn btn-primary btn-lg top-right-button" id="addnewbtn" data-toggle="modal" data-target="#templateModal">ADD NEW</a> -->
                    @endif

                    @if($userType != 'Parent')
                 
                      <a href="{{ route('create.programplan', ['centerid' => $centerId]) }}" class="btn btn-outline-info" style="margin-left:5px;">
                            Add ProgramPlan
                        </a>

                    @endif
                </div>

</div>
<!-- resources/views/program_plan_list.blade.php -->
    
<main data-centerid="{{ $centerId ?? '' }}" style="padding-block:5em;padding-inline:2em;">
  <!-- <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
  <h2 class="mb-0">Program Plan</h2>
  <p class="mb-0 text-muted mx-md-4">
    Dashboard <span class="mx-2">|</span> <span>Program Plan</span>
  </p>
</div>



    </div>
    <hr class="mt-3">
  </div>    -->
    <div class="container-fluid">
   

        <div class="program-plan-container">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="id-column">ID</th>
                                            <th>Month</th>
                                            <th>Room</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                            <th>Updated Date</th>
                                            <th width="240">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($programPlans as $index => $plan)
                                            <tr>
                                                <td class="id-column">{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="month-label">
                                                        {{ $getMonthName($plan->months) }} {{ $plan->years ?? 'N/A' }}
                                                    </span>
                                                </td>
<td>{{ $plan->room->name ?? 'N/A' }}</td>
<td>{{ $plan->creator->name ?? 'N/A' }}</td>

                                                <td>{{ \Carbon\Carbon::parse($plan->created_at)->format('d M Y / H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($plan->updated_at)->format('d M Y / H:i') }}</td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('print.programplan', $plan->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fa-solid fa-print animated-icon"></i> Print
                                                        </a>

                                                        @if(session('UserType') != 'Parent')
                                                         <a href="{{ route('create.programplan', ['centerId' => $centerId, 'planId' => $plan->id]) }}" class="btn btn-sm btn-primary">
                                                            <i class="fa-solid fa-pen-to-square animated-icon"></i> Edit
                                                        </a>


                                                            <button type="button" class="btn btn-sm btn-danger delete-program" data-id="{{ $plan->id }}">
                                                                <i class="fa-solid fa-trash animated-icon"></i> Delete
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="empty-state">
                                                    <i class="fa-solid fa-clipboard-list"></i>
                                                    <p>No program plans found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($programPlans->count())
    <div class="mt-3 mx-auto">
        {{ $programPlans->appends(request()->query())->links() }}
    </div>
@else
    <p>No program plans found.</p>
@endif

            </div>
        </div>

    </div>
</main>
@endsection

@push('scripts')
    <script>
$(document).ready(function() {
    // Delete program plan
    $(document).on('click', '.delete-program', function() {
        var programId = $(this).data('id');
        var row = $(this).closest('tr');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                  const csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{route('LessonPlanList.deletedataofprogramplan') }}",
                    type: 'POST',
                    data: {
                        program_id: programId,
                      
                    },
                     headers: {
            'X-CSRF-TOKEN': csrfToken
        },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            // Remove the row from the table
                            row.fadeOut(400, function() {
                                $(this).remove();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong with the server. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush

@include('layout.footer')