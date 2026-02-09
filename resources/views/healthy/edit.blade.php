
@extends('layout.master')
@section('title', 'Recipes Update')
@section('parentPageTitle', 'Children')


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('content')
<style>
body[class*='theme-'] label {
    color: var(--sd-accent, #176ba6) !important;
}
</style>
<style>
/* ===================== THEME SUPPORT (GLOBAL) ===================== */
body[class*='theme-'] .btn-outline-info {
    border-color: var(--sd-accent, #176ba6) !important;
    color: var(--sd-accent, #176ba6) !important;
}
body[class*='theme-'] .btn-outline-info:hover, body[class*='theme-'] .btn-outline-info:focus {
    background-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
body[class*='theme-'] .btn-info {
    background-color: var(--sd-accent, #176ba6) !important;
    border-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
body[class*='theme-'] .card {
    border-color: var(--sd-accent, #176ba6) !important;
}
/* =================== END THEME SUPPORT =================== */
</style>
<div>

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
    <div class="row clearfix" style="margin-bottom: 43px;">
        <div class="col-lg-12 col-md-12 mb-1">
            <div class="card shadow-sm border-0 rounded p-3 hover-shadow">

                <form action="{{ route('recipes.update', $recipe->id) }}" method="post" enctype="multipart/form-data"
                    id="recipe-form" class="add-recipe-modal">
                    @csrf
                    <div class="form-group">
                        <label for="item-name">Item Name</label>
                        <input type="text" name="itemName" id="item-name" class="form-control"
                            value="{{ $recipe->itemName }}" required>
                    </div>

                    <div class="form-group">
                        <label for="foodtype">Food Type</label>
                        <select name="foodtype" id="foodtype" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="veg" {{ $recipe->foodtype == "veg" ? "selected" : "" }}>Veg</option>
                            <option value="non-veg" {{ $recipe->foodtype == "non-veg" ? "selected" : "" }}>Non-Veg</option>
                        </select>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="ingredients">Select MealType</label>
                            <select id="ingredients" name="mealType" class="form-control" required>
                                <option value="">Select MealType</option>
                                @foreach ($uniqueMealTypes as $mealType)
                                <option value="{{ $mealType }}" {{ $recipe->type == $mealType ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', $mealType) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="ingredientSelect">Select Ingredients</label>
                            <div class="input-group">
                                <select id="ingredientSelect" name="ingredients[]" class="form-control select2" multiple="multiple" required>
                                    @foreach ($ingredients as $key => $ingredient)
                                    <option value="{{ $ingredient->id }}"
                                        {{ in_array($ingredient->id, $selectedIngredientId ?? []) ? 'selected' : '' }}>
                                        {{ $key + 1 }} - {{ $ingredient->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="recipe">Description Recipe</label>
                        <textarea name="recipe" id="recipe"
                            class="form-control">{{ strip_tags($recipe->recipe) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="recipe">Note(if ingredient not available)</label>
                        <textarea name="notes" id="recipe" class="form-control">{{ $recipe->notes}}</textarea>
                    </div>

                    <div class="form-group ">
                        <label>Add Video Link</label>

                        <input type="text" name="RecipeVideolink" id="itemVideos" class="form-control" value="{{ $recipe->RecipeVideolink }}">
                        <div
                            style="font-size: 14px;display:flex;font-family: auto;align-items: center;color: green;font-weight: 600;">
                            (Under 10 MB Only)</div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-info" type="submit">Update</button>
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

    $(document).ready(function() {

        // Ingredients static Select2
        $('#ingredientSelect').select2({
            placeholder: 'Select ingredients',
            allowClear: true
        });
    });
</script>

@include('layout.footer')
@stop