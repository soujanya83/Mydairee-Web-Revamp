@extends('layout.master')
@section('title', 'Recipes List')

@section('parentPageTitle', '')
<style>
    .card-img-top {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
</style>
<style>
    .card-header {
        position: relative;
        z-index: 1;
    }

    .dropdown-menu {
        z-index: 9999;
    }
</style>


@section('content')

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
<div class="d-flex justify-content-end" style="margin-top: -52px;">
    <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="centerDropdown" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
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
    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#roomModal"
        style="height: 36px;">
        Add Recipes
    </button>
</div>



<hr>
<div class="row clearfix">
    {{-- {{ session('user_center_id')}} --}}
    <div class="col-sm-12">
        @foreach($recipes as $type => $recipeGroup)
        <h4 class="mt-0 mb-3"> <u>{{ ucfirst(strtolower($type)) }}:</u> </h4>
        <div class="row">
            @foreach($recipeGroup as $recipe)
            <div class="col-md-3 mb-4">
                {{-- <div class="card h-100 shadow-sm">
                    <!-- Image clickable to open modal -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $recipe->id }}">
                        <img src="{{ asset('uploads/recipes/' . $recipe->mediaUrl) }}" class="card-img-top"
                            alt="{{ $recipe->itemName }}"
                            style="height: 180px; object-fit: cover; padding: 4px;border-radius: 10px">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title mb-1"> &nbsp;&nbsp;&nbsp;{{
                            \Illuminate\Support\Str::title($recipe->itemName) }}
                        </h5>
                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                            <i class="fas fa-user me-1"></i> {{ $recipe->created_by_name }} ({{
                            $recipe->created_by_role }})<br>
                            <i class="fas fa-calendar-alt me-1"></i> {{
                            \Carbon\Carbon::parse($recipe->createdAt)->format('d M Y') }}
                        </p>

                    </div>
                </div> --}}

                <div class="card h-100 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ \Illuminate\Support\Str::title($recipe->itemName) }}</h5>

                        {{-- dropdown trigger --}}
                        <div class="dropdown">
                            <a href="#" class="text-secondary" id="dropdownMenu{{ $recipe->id }}"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu{{ $recipe->id }}">
                                <li>
                                    <a class="dropdown-item" href="{{ route('recipes.edit', $recipe->id) }}">
                                        <i class="fas fa-edit me-2"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this recipe?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger">
                                            <i class="fas fa-trash-alt me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @php
                    $mediaFile = Str::replaceFirst('uploads/recipes/', '', $recipe->mediaUrl);
                    @endphp

                    <img src="{{ asset('uploads/recipes/' . $mediaFile) }}"
                        onerror="this.onerror=null;this.src='{{ asset('storage/uploads/recipes/' . $mediaFile) }}';"
                        class="card-img-top" alt="{{ $recipe->itemName }}"
                        style="height: 180px; object-fit: cover; padding: 3px;">



                    <div class="card-body">
                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                            <i class="fas fa-user me-1"></i> {{ $recipe->created_by_name }} ({{
                            $recipe->created_by_role }})<br>
                            <i class="fas fa-calendar-alt me-1"></i> {{
                            \Carbon\Carbon::parse($recipe->createdAt)->format('d M Y') }}
                        </p>
                    </div>
                </div>


            </div>


            <!-- Modal for Full Image -->
            <div class="modal fade" id="imageModal{{ $recipe->id }}" tabindex="-1"
                aria-labelledby="imageModalLabel{{ $recipe->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="{{ asset('uploads/recipes/' . $recipe->mediaUrl) }}" class="img-fluid"
                                alt="{{ $recipe->itemName }}">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach


    </div>

</div>


<div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content card">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModalLabel">Add Recipe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body card">
                <form action="{{ route('recipes.store') }}" method="post" enctype="multipart/form-data" id="recipe-form"
                    class="add-recipe-modal">
                    @csrf
                    <div class="form-group">
                        <label for="item-name">Item Name</label>
                        <input type="text" name="itemName" id="item-name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="ingredients">Select MealType</label>
                            <div class="input-group">
                                <select id="ingredients" name="mealType" class="form-control" required>
                                    <option value="">Select MealType</option>
                                    @foreach ($uniqueMealTypes as $keys => $mealType)
                                    <option value="{{ $mealType }}">
                                        {{ $keys + 1 }} - {{ str_replace('_', ' ', $mealType) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="ingredientSelect">Select Ingredient</label>
                            <div class="input-group">
                                <select id="ingredientSelect" name="ingredient" class="form-control" required>
                                    <option value="">Select Ingredient</option>
                                    @foreach ($ingredients as $key => $ingredient)
                                    <option value="{{ $ingredient->id }}">
                                        {{ $key + 1 }} - {{ $ingredient->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="recipe">Description Recipe</label>
                        <textarea name="recipe" id="recipe" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Add Image</label>
                            <div class="d-flex flex-wrap">
                                <div id="img-holder"></div>
                            </div>
                            <input type="file" name="image[]" id="itemImages" class="form-control-hidden" multiple>
                            <div
                                style="font-size: 14px;display:flex;font-family: auto;align-items: center;color: green;font-weight: 600;">
                                (Under 5 MB Only)</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Add Video</label>
                            <div class="d-flex flex-wrap">
                            </div>
                            <input type="file" name="video[]" id="itemVideos" class="form-control-hidden" multiple>
                            <div
                                style="font-size: 14px;display:flex;font-family: auto;align-items: center;color: green;font-weight: 600;">
                                (Under 10 MB Only)</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" type="submit">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- CKEditor 5 Classic -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#recipe'))
        .catch(error => {
            console.error(error);
        });
</script>


@include('layout.footer')
@stop
