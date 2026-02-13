@extends('layout.master')
@section('title', 'Recipes List')

@section('parentPageTitle', '')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-CQZ2gdcX4H14R/2uOeGZ5ER5YjZL+Qyhr/KdxzeuL0qg6ldKMyjvIu5SozIpuH7/7MAHuD7msnXMjTfzVlv3CQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .card-img-top {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
/* THEME: card-header text and dropdown */
body[class*='theme-'] .card-header {
    color: var(--sd-accent, #176ba6) !important;
}
body[class*='theme-'] .card-header .card-title {
    color: var(--sd-accent, #176ba6) !important;
}
body[class*='theme-'] .card-header .dropdown .fa-ellipsis-v {
    color: var(--sd-accent, #176ba6) !important;
}
body[class*='theme-'] .dropdown-menu .dropdown-item.active,
body[class*='theme-'] .dropdown-menu .dropdown-item:active {
    background-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}

/* ===================== THEME SUPPORT (GLOBAL) ===================== */
body.theme-purple h4.border-bottom {
    border-bottom: 2px solid var(--sd-accent, #a259ec) !important;
}
body.theme-blue h4.border-bottom {
    border-bottom: 2px solid var(--sd-accent, #176ba6) !important;
}
body.theme-cyan h4.border-bottom {
    border-bottom: 2px solid var(--sd-accent, #00b8d9) !important;
}
body[class*='theme-'] h4.border-bottom {
    border-bottom: 2px solid var(--sd-accent, #176ba6) !important;
}



body[class*='theme-'] .btn-outline-info {
    border-color: var(--sd-accent, #176ba6) !important;
    color: var(--sd-accent, #176ba6) !important;
}
body[class*='theme-'] .btn-outline-info:hover, body[class*='theme-'] .btn-outline-info:focus {
    background-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
body[class*='theme-'] .dropdown-menu .active {
    background-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
/* =================== END THEME SUPPORT =================== */
</style>
<style>
    .card-header {
        position: relative;
        z-index: 1;
    }

    .dropdown-menu {
        z-index: 9999;
    }

    .custom-badge {
        color: #fff;
        /* white text */
        border: 1px solid #fff;
        /* white border */
        padding: 0.4em 0.8em;
        font-size: 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Light green for veg */
    .custom-badge.veg {
        background-color: #6cc070;
        /* light green */
    }

    /* Light red/orange for non-veg */
    .custom-badge.non-veg {
        background-color: #e57373;
        /* light red */
    }

    .custom-badge {
        color: #fff;
        border: 1px solid #fff;
        font-size: 0.75rem;
        padding: 0.35em 0.6em;
        border-radius: 8px;
        font-weight: 600;
    }

    .custom-badge.veg {
        background-color: #81c784;
        /* light green */
    }

    .custom-badge.non-veg {
        background-color: #ef5350;
        /* light red */
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
    }

    .custom-text {
        font-size: 1.5em;
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
<div class="d-flex justify-content-end" style="margin-top: -45px;">
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
    @if(!empty($permissions['addRecipe']) && $permissions['addRecipe']  || Auth::user()->userType == "Superadmin")

    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#roomModal"
        style="height: 36px;">
        Add Recipes
    </button>
    @endif
</div>



<hr>
<div class="row clearfix">
    <div class="col-sm-12">
        @foreach($recipes as $type => $recipeGroup)

        <!-- Recipe Type Heading -->
        <h4 class="mt-4 mb-3 fw-bold text-capitalize border-bottom border-2 pb-2" style="color: var(--sd-accent, #176ba6);">
            <i class="fas fa-utensils me-2"></i> {{ ucfirst(strtolower($type)) }}
        </h4>
        @if(isset($recipeGroup[0]) && isset($recipeGroup[0]->groupName))
            <div class="mb-2 fw-semibold text-secondary" style="color: var(--sd-accent, #176ba6);">
                Group: {{ $recipeGroup[0]->groupName }}
            </div>
        @endif

        <div class="row">
            @foreach($recipeGroup as $recipe)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">

                    <!-- Card Header with Title & Dropdown -->
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3 border-0">
                        <h6 class="card-title mb-0 fw-bold text-truncate text-dark" title="{{ $recipe->itemName }}">
                            {{ \Illuminate\Support\Str::title($recipe->itemName) }}
                        </h6>

                        @if(!empty($permissions['updateRecipe']) || !empty($permissions['deleteRecipe']))
                        <div class="dropdown">
                            <a href="#" class="text-muted small" id="dropdownMenu{{ $recipe->id }}"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                aria-labelledby="dropdownMenu{{ $recipe->id }}">
                                @if(!empty($permissions['updateRecipe']))
                                <li>
                                    <a class="dropdown-item" href="{{ route('recipes.edit', $recipe->id) }}">
                                        <i class="fas fa-edit me-2 text-primary"></i> Edit
                                    </a>
                                </li>
                                @endif
                                @if(!empty($permissions['deleteRecipe']))
                                <li>
                                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this recipe?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash-alt me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </div>
                        @endif
                    </div>

                    <!-- Recipe Image -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $recipe->id }}">
                        <img src="{{ asset('storage/'.$recipe->mediaUrl) }}"
                             onerror="this.onerror=null;this.src='{{ asset('storage/../'.$recipe->mediaUrl) }}';"
                             class="card-img-top"
                             alt="{{ $recipe->itemName }}"
                             style="height: 180px; object-fit: cover;">
                    </a>

                    <!-- Card Body -->
                    <div class="card-body p-3">

                        <!-- Food Type Badge -->
                        @if(!empty($recipe->foodtype))
                        <span class="badge rounded-pill px-3 py-1 mb-2 
                                    {{ $recipe->foodtype == 'veg' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                            {{ strtoupper($recipe->foodtype) }}
                        </span>
                        @endif

                        <!-- Recipe Description -->
                        <p class="mt-2 mb-2 text-muted small" style="word-wrap: break-word; white-space: normal; overflow-wrap: break-word;">
                            {{ Str::limit($recipe->recipe, 100, '...') }}
                        </p>

                        <!-- Author & Date -->
                        <p class="mb-1 text-muted small">
                            <i class="fas fa-user me-1"></i>
                            {{ $recipe->created_by_name }} ({{ $recipe->created_by_role }})
                        </p>
                        <p class="mb-2 text-muted small">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ \Carbon\Carbon::parse($recipe->createdAt)->format('d M Y') }}
                        </p>

                        <!-- Video Link -->
                        @if(!empty($recipe->RecipeVideolink))
                        <a href="{{ $recipe->RecipeVideolink }}"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm btn-outline-danger w-100">
                            <i class="fab fa-youtube me-1"></i> Watch Video
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal for Full Image -->
            <div class="modal fade" id="imageModal{{ $recipe->id }}" tabindex="-1"
                 aria-labelledby="imageModalLabel{{ $recipe->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content rounded-4 shadow-lg">
                        <div class="modal-body text-center p-0">
                            <img src="{{ asset('uploads/recipes/' . $recipe->mediaUrl) }}"
                                 class="img-fluid rounded-3"
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




<div class="modal" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel" aria-hidden="true">
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
                    <div class="form-group">
                        <label for="foodtype">Food Type</label>
                        <select name="foodtype" id="foodtype" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="veg">Veg</option>
                            <option value="non-veg">Non-Veg</option>
                        </select>
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
                                <select id="ingredientSelect" name="ingredients[]" class="form-control select2" multiple="multiple" required>
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

                    <div class="form-group">
                        <label for="recipe">Note(if ingredient not available)</label>
                        <textarea name="notes" id="recipe" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Add Image</label>
                            <div class="d-flex flex-wrap">
                                <div id="img-holder" class="d-flex flex-wrap gap-2"></div>
                            </div>
                            <input type="file" name="image[]" id="itemImages" class="form-control-hidden" multiple>
                            <div style="font-size: 14px;display:flex;font-family: auto;align-items: center;color: green;font-weight: 600;">
                                (Under 5 MB Only)
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Add Video Link</label>
                            <div class="d-flex flex-wrap">
                            </div>
                            <input type="text" name="RecipeVideolink" id="itemVideos" class="form-control">
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

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

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

<script>
    document.getElementById('itemImages').addEventListener('change', function(event) {
        const imgHolder = document.getElementById('img-holder');
        imgHolder.innerHTML = ''; // clear old previews

        const files = event.target.files;

        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                if (file.size <= 5 * 1024 * 1024) { // 5 MB limit
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('m-2');
                        img.style.height = '100px';
                        img.style.width = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '8px';
                        imgHolder.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert(`File "${file.name}" exceeds 5 MB limit.`);
                }
            }
        });
    });
</script>

@include('layout.footer')
@stop