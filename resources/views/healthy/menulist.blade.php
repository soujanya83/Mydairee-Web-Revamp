@extends('layout.master')
@section('title', 'Healthy Eating Menu')
@section('parentPageTitle', '')
<!-- Bootstrap 5 CSS -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('content')



<div class="d-flex justify-content-end" style="margin-top: -52px;">
    <button class="btn btn-outline-info dropdown-toggle" type="button" id="centerDropdown" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" style="    height: 39px;">
        {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
        style="top:3% !important;left:13px !important;">
        @foreach($centers as $center)
        <a href="javascript:void(0);"
            class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
            style="background-color:white;" data-id="{{ $center->id }}">
            {{ $center->centerName }}
        </a>
        @endforeach
    </div>

    &nbsp;&nbsp;&nbsp;&nbsp;
    <form method="GET" action="{{ route('healthy_menu') }}" id="dateFilterForm">
        <input type="text" id="calendarPicker" name="selected_date" class="btn btn-outline-info btn-lg" readonly
            value="{{ $selectedDate ?? now()->format('d-m-Y') }}">
    </form>



</div>

<hr>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-22px">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-22px">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@php
$weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$mealTypes = ['Breakfast', 'Morning Tea', 'Lunch', 'Afternoon Tea', 'Late Snacks'];
@endphp

<div class="container">
    <!-- Days Header as Tabs -->
    <ul class="nav nav-tabs mb-4" id="dayTabs">
        @foreach ($weekdays as $day)
        <li class="nav-item">
            <a class="nav-link {{ $day == $selectedDay ? 'active' : '' }}" href="#{{ strtolower($day) }}"
                data-day="{{ $day }}" data-bs-toggle="tab">{{ $day }}</a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach ($weekdays as $day)
        <div class="tab-pane fade {{ $day == $selectedDay ? 'show active' : '' }}" id="{{ strtolower($day) }}">
            <div class="card">
                <div class="card-body">
                    @foreach ($mealTypes as $meal)
                    <div class="row mb-3 align-items-center">
                        <div class="col-12">
                            <h6 class="mb-0"><u>{{ $meal }}:</u></h6>


                            <div class="d-flex justify-content-end">
                                @php
                                $items = $menus->where('day', $day)->where('mealType',
                                \Illuminate\Support\Str::upper($meal));
                                @endphp
                                <button class="btn btn-outline-info btn-sm add-item-btn" data-bs-toggle="modal"
                                    data-bs-target="#ingredientModal" data-day="{{ $day }}" data-meal="{{ $meal }}"
                                    style="margin-top:-24px;margin-right:0px">
                                    Add Item
                                </button>
                            </div>
                            @if ($items->isEmpty())
                            <p class="text-muted mb-0">No items added</p>
                            @else
                            <div class="d-flex flex-wrap">
                                @foreach ($items as $menu)
                                <div class="card m-2 shadow-sm" style="width: 240px;">


                                    @php
                                    $mediaFile = $menu->mediaUrl
                                    ? Str::replaceFirst('uploads/recipes/', '', $menu->mediaUrl)
                                    : 'default.png';
                                    @endphp

                                    <img src="{{ asset('uploads/recipes/' . $mediaFile) }}"
                                        onerror="this.onerror=null;this.src='{{ asset('storage/uploads/recipes/' . $mediaFile) }}';"
                                        class="card-img-top recipe-img" alt="{{ $menu->name }}" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-img-src="{{ asset('uploads/recipes/' . $mediaFile) }}"
                                        style="height: 180px; object-fit: cover; padding: 3px; width: 240px;">


                                    <h5 class="card-title mb-0">
                                        &nbsp;&nbsp;{{ \Illuminate\Support\Str::title($menu->name) }}
                                    </h5>





                                    <div class="d-flex justify-content-between align-items-center px-2 pt-2">
                                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                            {{-- <i class="fas fa-user me-1"></i> --}}
                                            {{-- {{ $menu->created_by_name ?? 'Unknown' }}
                                            ({{ $menu->created_by_role ?? 'N/A' }})<br> --}}
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($menu->createdAt)->format('d M Y') }}
                                        </p>

                                        <form method="POST" action="{{ route('menu.destroy', $menu->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this menu?');"
                                            style="margin-top:12px">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete this menu">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>



                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>


                    </div>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Image View Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" style="max-height: 90vh;" alt="Full Image">
                </div>
            </div>
        </div>
    </div>


    <!-- Tab Content -->
    {{-- <div class="tab-content">
        @foreach ($weekdays as $day)
        <div class="tab-pane fade {{ $day == $selectedDay ? 'show active' : '' }}" id="{{ strtolower($day) }}">
            <div class="card">
                <div class="card-body">
                    @foreach ($mealTypes as $meal)
                    <div class="row mb-3 align-items-center">
                        <div class="col-10">
                            <h6 class="mb-0">{{ $meal }}</h6>
                            @php
                            $items = $menus->where('day', $day)->where('mealType',
                            \Illuminate\Support\Str::snake($meal));
                            @endphp
                            @if ($items->isEmpty())
                            <p class="text-muted mb-0">No items added</p>
                            @else
                            @foreach ($items as $item)
                            <p class="mb-0">{{ \Illuminate\Support\Str::title($item->name) }}</p>
                            @endforeach
                            @foreach ($items as $menu)
                            <div class="card m-2 p-3" style="min-width: 250px;">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ \Illuminate\Support\Str::title($menu->name) }}</h5>
                                </div>

                                @php
                                $mediaFile = Str::replaceFirst('uploads/recipes/', '', $menu->mediaUrl);
                                @endphp

                                <img src="{{ asset('uploads/recipes/' . $mediaFile) }}"
                                    onerror="this.onerror=null;this.src='{{ asset('storage/uploads/recipes/' . $mediaFile) }}';"
                                    class="card-img-top" alt="{{ $menu->name }}"
                                    style="height: 180px; object-fit: cover; padding: 3px;">

                                <div class="card-body">
                                    <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                        <i class="fas fa-user me-1"></i> {{ $menu->created_by_name }} ({{
                                        $menu->created_by_role }})<br>
                                        <i class="fas fa-calendar-alt me-1"></i> {{
                                        \Carbon\Carbon::parse($menu->createdAt)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            @endforeach

                            @endif
                        </div>
                        <div class="col-2 text-end">
                            <button class="btn btn-outline-info btn-sm add-item-btn" data-bs-toggle="modal"
                                data-bs-target="#ingredientModal" data-day="{{ $day }}" data-meal="{{ $meal }}">
                                Add Item
                            </button>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div> --}}
</div>

<!-- Include Bootstrap JS for tab functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ingredientForm" method="POST" action="{{ route('menu.store') }}">
            @csrf
            <input type="hidden" name="day" id="modalDay">
            <input type="hidden" name="meal_type" id="modalMealType">
            <input type="hidden" name="selected_date" id="selectedDateInput">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ingredientModalLabel">Select Recipes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div id="recipeList">
                        <p class="text-muted">Loading recipes...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" id="ingredientSaveBtn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr("#calendarPicker", {
        dateFormat: "d-m-Y",
        defaultDate: "{{ $selectedDate ?? now()->format('d-m-Y') }}",
        onChange: function(selectedDates, dateStr, instance) {
            document.getElementById("dateFilterForm").submit();
        }
    });
       $('.add-item-btn').on('click', function () {
    // Get selected date from calendar
    var selectedDate = $('#calendarPicker').val();

    // Set in hidden input field in modal form
    $('#selectedDateInput').val(selectedDate);

    // Also set day and meal type if needed
    let day = $(this).data('day');  // Assuming you pass day
    let mealType = $(this).data('meal');  // Assuming you pass meal type

    $('#modalDay').val(day);
    $('#modalMealType').val(mealType);

    // Now show modal
    $('#ingredientModal').modal('show');
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    imageModal.addEventListener('show.bs.modal', function (event) {
        const triggerImg = event.relatedTarget;
        const imgSrc = triggerImg.getAttribute('data-img-src');
        modalImage.src = imgSrc;
    });
});
</script>


<script>
    $(document).on('click', '.add-item-btn', function () {
    const day = $(this).data('day');
    const meal = $(this).data('meal');

    $('#modalDay').val(day);
    $('#modalMealType').val(meal);
    $('#ingredientModalLabel').text(`Add Items for ${meal}`);

    $('#recipeList').html('<p class="text-muted">Loading recipes...</p>');

    // Fetch recipes based on type
    $.ajax({
        url: '/get-recipes-by-type',
        method: 'GET',
        data: { type: meal },
        success: function (data) {
            if (data.length > 0) {
                let html = '<div class="form-check">';
                data.forEach(item => {
                    html += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipe_ids[]" value="${item.id}" id="recipe-${item.id}">
                            <label class="form-check-label" for="recipe-${item.id}">
                                ${item.itemName}
                            </label>
                        </div>
                    `;
                });
                html += '</div>';
                $('#recipeList').html(html);
            } else {
                $('#recipeList').html('<p class="text-danger">No recipes found for this type.</p>');
            }
        },
        error: function () {
            $('#recipeList').html('<p class="text-danger">Failed to load recipes.</p>');
        }
    });
});

</script>

@stop
