@extends('layout.master')
@section('title', 'Recipes Update')
@section('parentPageTitle', 'Children')




@section('content')

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
                            <label for="ingredientSelect">Select Ingredient</label>
                            <select id="ingredientSelect" name="ingredient" class="form-control" required>
                                <option value="">Select Ingredient</option>
                                @foreach ($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" {{ $ingredient->id == $selectedIngredientId ?
                                    'selected' : '' }}>
                                    {{ $ingredient->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="recipe">Description Recipe</label>
                        <textarea name="recipe" id="recipe"
                            class="form-control">{{ strip_tags($recipe->recipe) }}</textarea>
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
</script>

@include('layout.footer')
@stop
