@extends('layout.master')
@section('title', 'Healthy Eating Menu')
@section('parentPageTitle', '')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    :root {
        --primary-blue: #2c5aa0;
        --light-blue: #87ceeb;
        --accent-green: #4a9b4a;
        --warning-orange: #ff8c42;
        --success-green: #28a745;
        --header-bg: linear-gradient(135deg, #2c5aa0 0%, #1e3a5f 100%);
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    /* body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        } */

    .main-container {
        /* background: white; */
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin: 0 auto;
        max-width: 1400px;
    }

    .header-section {
        background: var(--header-bg);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .header-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="25" cy="25" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="35" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="75" r="2.5" fill="rgba(255,255,255,0.1)"/><circle cx="15" cy="65" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="85" cy="15" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        animation: float 25s infinite linear;
    }

    @keyframes float {
        0% {
            transform: translate(0, 0) rotate(0deg);
        }

        100% {
            transform: translate(-50px, -50px) rotate(360deg);
        }
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .menu-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .menu-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .week-selector {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        padding: 0.7rem 2rem;
        color: white;
        font-size: 1.1rem;
        /* transition: all 0.3s ease; */

        backdrop-filter: blur(10px);
    }

    .week-selector:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .menu-table {
        margin: 0;
        box-shadow: none;
    }

    .table-container {
        overflow-x: auto;
        /* padding: 2rem; */
    }

    /* .menu-table th {
        background: var(--light-blue);
        color: var(--primary-blue);
        font-weight: 700;
        text-align: center;
        padding: 1.2rem 0.8rem;
        border: none;
        position: relative;
        font-size: 1.1rem;
    } */

    .menu-table th:first-child {
        background: var(--primary-blue);
        color: white;
        border-top-left-radius: 15px;
    }

    .menu-table th:last-child {
        border-top-right-radius: 15px;
    }

    /* .menu-table td {
        padding: 1rem 0.8rem;
        border: 1px solid #e9ecef;
        vertical-align: top;
        transition: all 0.3s ease;
        position: relative;
    
    } */
    .menu-table th,
    .menu-table td {
        text-align: left;
        vertical-align: Start;
        padding: 8px;
        max-width: 350px;
        /* limit column width */
        width: 350px;
        /* fix column width */
        white-space: normal;
        /* allow wrapping */
        word-break: break-word;
        /* break long words if needed */
        overflow-wrap: break-word;
        /* modern standard for word wrapping */
    }

    .menu-table td:first-child {
        background: #f8f9fa;
        font-weight: 600;
        color: var(--primary-blue);
        text-align: center;
        width: 120px;
    }

    .menu-table td:hover:not(:first-child) {
        background: #f0f8ff;
        transform: scale(1.02);
        box-shadow: inset 0 0 0 2px var(--light-blue);
        cursor: pointer;

    }

    .meal-content {
        justify-items: start;
        position: relative;
        z-index: 1;
        display: flex;
    }

    .meal-item {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .meal-description {
        color: #666;
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 0.3rem;
        white-space: normal;
        /* allow wrapping */
        word-break: break-word;
        /* break long words if needed */
        overflow-wrap: break-word;
        /* modern way to ensure wrapping */
        max-width: 350px;
        /* keep inside cell */
    }


    .dietary-tag {
        display: inline-block;
        background: var(--success-green);
        color: white;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        margin: 0.2rem 0.2rem 0.2rem 0;
        font-weight: 500;
    }

    .dietary-tag.veg {
        background: var(--success-green);
    }

    .dietary-tag.halal {
        background: var(--accent-green);
    }

    .dietary-tag.allergy {
        background: #dc3545;
    }

    .time-slot {
        font-size: 0.8rem;
        color: #666;
        font-weight: normal;
    }

    .requirements-section {
        background: #f8f9fa;
        padding: 2rem;
        border-top: 3px solid var(--light-blue);
    }

    .requirements-box {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        /* width: 150px; */
    }

    .requirements-box:hover {
        transform: translateY(-3px);
        box-shadow: var(--hover-shadow);
        /* width: 320px; */
    }

    .requirements-title {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .requirement-item {
        margin-bottom: 0.5rem;
        color: #555;
    }

    .floating-actions {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
    }

    .floating-btn {
        background: var(--primary-blue);
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        display: block;
    }

    .floating-btn:hover {
        transform: scale(1.1);
        box-shadow: var(--hover-shadow);
        background: #1e3a5f;
    }

    .fade-in {
        animation: fadeIn 0.8s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .highlight-row td {
        background: linear-gradient(90deg, #fff3cd 0%, #ffffff 100%);
    }

    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header {
        background: var(--header-bg);
        color: white;
        border-bottom: none;
        border-radius: 15px 15px 0 0;
    }

    @media (max-width: 768px) {
        .menu-title {
            font-size: 1.8rem;
        }

        .table-container {
            padding: 1rem;
        }

        .menu-table th,
        .menu-table td {
            padding: 0.7rem 0.4rem;
            font-size: 0.85rem;
        }

        .floating-btn {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
    }

    .nutrition-badge {
        background: linear-gradient(45deg, #ff6b6b, #feca57);
        color: white;
        font-size: 0.7rem;
        padding: 0.15rem 0.4rem;
        border-radius: 8px;
        margin-left: 0.3rem;
        display: inline-block;
    }

    .special-meal {
        border-left: 4px solid var(--warning-orange);
        background: linear-gradient(90deg, #fff8f0 0%, #ffffff 100%);
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .week-selector {
        appearance: none;
        /* Remove default browser arrow */
        -webkit-appearance: none;
        /* Safari/Chrome */
        -moz-appearance: none;
        /* Firefox */

        padding-right: 2rem;
        /* Add space for custom arrow */
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24'><path fill='white' d='M7 10l5 5 5-5z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        /* Move arrow inside */
        background-size: 1rem auto;
        background-color: #0d6bb4ff ;

        border: 1px solid #036d82ff;
        /* match bg-info border */
        border-radius: 50px;
        /* capsule shape */
        color: white;
        font-weight: 500;
        padding-left: 1rem;
        /* add left padding for symmetry */
        height: 50px;
        /* consistent capsule height */
        /* line-height: 40px; */
    }

    .week-selector option {
    background-color: #0d6bb4;   /* Same as select bg */
    color: white;
    font-weight: 500;
}

.week-selector option:hover,
.week-selector option:focus {
    background-color: #036d82;   /* Darker on hover */
    color: #fff;
}

</style>
<style>
/* ===================== THEME SUPPORT (GLOBAL) ===================== */
body.theme-purple .header-section {
    background: linear-gradient(135deg, var(--sd-accent, #a259ec) 0%, #d291bc 100%) !important;
}
body.theme-blue .header-section {
    background: linear-gradient(135deg, var(--sd-accent, #176ba6) 0%, #00a8ff 100%) !important;
}
body.theme-cyan .header-section {
    background: linear-gradient(135deg, var(--sd-accent, #00b8d9) 0%, #00e1d9 100%) !important;
}
body[class*='theme-'] .header-section {
    background: linear-gradient(135deg, var(--sd-accent, #176ba6) 0%, #00a8ff 100%) !important;
}

body.theme-purple .menu-title .fa-utensils {
    color: var(--sd-accent, #a259ec) !important;
}
body.theme-blue .menu-title .fa-utensils {
    color: var(--sd-accent, #176ba6) !important;
}
body.theme-cyan .menu-title .fa-utensils {
    color: var(--sd-accent, #00b8d9) !important;
}
body[class*='theme-'] .menu-title .fa-utensils {
    color: var(--sd-accent, #176ba6) !important;
}

body[class*='theme-'] .week-selector {
    background-color: var(--sd-accent, #176ba6) !important;
    border-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
body[class*='theme-'] .week-selector option {
    background-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}

body[class*='theme-'] .btn-outline-info {
    border-color: var(--sd-accent, #176ba6) !important;
    color: var(--sd-accent, #176ba6) !important;
}
body[class*='theme-'] .btn-outline-info:hover, body[class*='theme-'] .btn-outline-info:focus {
    background-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
/* =================== END THEME SUPPORT =================== */

</style>

@section('content')
<div class="d-flex justify-content-end" style="margin-top: -47px;">
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
        <input type="hidden" name="menuweek" value=" {{$menuweek}}">
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
<!-- header ends here  -->


<div class="container-fluid">

    <div class="main-container fade-in">
        <!-- Header Section -->


        <div class="header-section mb-md-4">
            <div class="header-content">
                <div class="menu-title">
                    <i class="fas fa-utensils"></i> {{ $season }} Menu
                </div>
                <div class="menu-subtitle">
                    Nutritious meals crafted for growing minds and bodies
                </div>
                <form method="GET" action="{{ route('healthy_menu') }}" id="menuweekform">
                    <input type="hidden" name="selected_date" value="{{ $selectedDate }}">



                    <select class="week-selector " id="menuweek" name="menuweek">
                        @foreach ($weeks as $index => $range)
                        <option value="{{ $index }}" @selected($menuweek==$index)>
                            Week {{ $index }} - {{ $range['start']->format('M j, Y') }} to {{ $range['end']->format('M j, Y') }}
                        </option>


                        @endforeach
                    </select>
                </form>

            </div>
        </div>

        <!-- Menu Table -->
        <div class="table-container">
            <table class="table menu-table">
                <thead>
                    <tr>
                        <th scope="col">Meal Times</th>
                        @foreach ($weekdays as $day)
                        <th scope="col" class="{{ $day == $selectedDay ? 'table-active' : '' }}">
                            <a href="#{{ strtolower($day) }}"
                                data-day="{{ $day }}"
                                data-bs-toggle="tab">
                                <i class="fas fa-calendar-day"></i> {{ $day }}
                            </a>
                        </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>

                    @foreach ($mealTypes as $meal)
                    <tr>
                        <td>
                            <strong>{{ $meal }}</strong><br>
                            <!-- <span class="time-slot">7:00 - 8:00 am</span> -->
                        </td>

                        @foreach ($weekdays as $day)

                        <td>

                            @php
                            if($meal === "Morning Tea"){
                            $meal = "MORNING_TEA";
                            }else if($meal === "Afternoon Tea"){
                            $meal = "AFTERNOON_TEA";
                            }else if($meal === "Late Snacks"){
                            $meal = "SNACKS";
                            }


                            $menu = \App\Models\Menu::where('centerid', session('user_center_id'))
                            ->where('mealType', $meal)
                            ->where('menuweek',$menuweek)
                            ->where('day', $day)
                            ->get();

                            $recipeIds = $menu->pluck('recipeid');
                            $recipes = \App\Models\RecipeModel::whereIn('id', $recipeIds)->get();

                            $permission = \App\Models\Permission::where('userid', Auth::user()->userid)->first();
                            @endphp



                            @if ($recipes->isEmpty())
                            <p class="text-muted mb-2">No menu available</p>
                            <!-- Show Add button -->
                            @if(($permission && $permission->addMenu == "1")
                            || Auth::user()->userType == "Superadmin"
                            || Auth::user()->admin == "1")

                            <button type="button"
                                class="btn btn-sm btn-outline-success openIngredientModal"
                                data-day="{{ $day }}"
                                data-meal="{{ $meal }}"
                                data-bs-toggle="modal"
                                data-bs-target="#ingredientModal">
                                <i class="fas fa-plus"></i>
                            </button>
                            @endif

                            @else
                            @foreach ($recipes as $r)
                            @php
                            $ingredients = DB::table('recipe_ingredients')
                            ->where('recipeId', $r->id)
                            ->join('ingredients', 'recipe_ingredients.ingredientId', '=', 'ingredients.id')
                            ->pluck('ingredients.name')
                            ->toArray();

                            $ingredientList = !empty($ingredients)
                            ? implode(', ', $ingredients)
                            : 'No ingredients';

                            $menuEntry = $menu->where('recipeid', $r->id)->first();
                            @endphp

                            <div class="meal-content mb-2 border rounded p-2 position-relative">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="meal-item fw-bold text-wrap">{{ $r->itemName ?? 'Untitled Dish' }}</div>
                                        <div class="meal-description small text-muted">
                                            {{ $r->recipe ?? 'No recipe description' }}


                                        </div>
                                        <div class="meal-description small text-muted">

                                            {{ $ingredientList }}<br>

                                        </div>

                                        <div class="meal-description small text-muted">

                                            @if(!empty($r->notes))
                                            <strong>Notes</strong>: {{ $r->notes}}
                                            @endif
                                        </div>


                                        @if(!empty($r->foodtype))
                                        @if($r->foodtype == "veg")
                                        <span class="dietary-tag veg">{{ strtoupper($r->foodtype) }}</span>
                                        @else
                                        <span class="dietary-tag allergy">{{ strtoupper($r->foodtype) }}</span>
                                        @endif
                                        @endif


                                        @if (!empty($r->nutrition))
                                        <span class="nutrition-badge">{{ $r->nutrition }}</span>
                                        @endif
                                    </div>

                                    <!-- Delete button -->
                                          @if(($permission && $permission->addMenu == "1")
                            || Auth::user()->userType == "Superadmin"
                            || Auth::user()->admin == "1")

                                    <form action="{{ route('menu.destroy', $menuEntry->id) }}" method="POST" class="delete-menu-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2 delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif

                                </div>
                            </div>
                            @endforeach

                            <!-- Add Another button -->
                                  @if(($permission && $permission->addMenu == "1")
                            || Auth::user()->userType == "Superadmin"
                            || Auth::user()->admin == "1")

                            <button type="button"
                                class="btn btn-sm btn-outline-success mt-2 openIngredientModal"
                                data-day="{{ $day }}"
                                data-meal="{{ $meal }}"
                                data-bs-toggle="modal"
                                data-bs-target="#ingredientModal">
                                <i class="fas fa-plus"></i>
                            </button>
                            @endif
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>



        </div>

        <!-- Requirements Section -->
        <div class="requirements-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="requirements-box">
                        <div class="requirements-title">
                            <i class="fas fa-clipboard-list"></i> Daily Requirements
                        </div>
                        <div class="requirement-item">• 1 serve meat (30g cooked meat, 40g cooked chicken, 50g fish, 85g legumes)</div>
                        <div class="requirement-item">• 1 serve fruit (75g fresh fruit or equivalent - 3 types)</div>
                        <div class="requirement-item">• 1 serve vegetables (½ cup cooked, 1 cup salad)</div>
                        <div class="requirement-item">• 2 serves dairy (100ml milk, 100ml custard, 25g yoghurt, 15g hard cheese)</div>
                        <div class="requirement-item">• 2 serves grains (1 slice bread, ¼ cooked rice or pasta, 35g crispbread)</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="requirements-box">
                        <div class="requirements-title">
                            <i class="fas fa-calendar-alt"></i> Fortnightly Requirements
                        </div>
                        <div class="requirement-item">• 4 times red meat meal</div>
                        <div class="requirement-item">• 2 times white meat meal</div>
                        <div class="requirement-item">• 2 times fish meal</div>
                        <div class="requirement-item">• 2 times vegetarian meal</div>
                        <div style="margin-top: 1rem; padding: 1rem; background: #e3f2fd; border-radius: 8px; font-style: italic;">
                            <strong>Note:</strong> This menu follows Long Day Care nutritional guidelines and Australian Dietary Guidelines. Water is available to children with all meals.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Buttons -->
<!-- <div class="floating-actions">
    <button class="floating-btn pulse" data-bs-toggle="modal" data-bs-target="#printModal" title="Print Menu">
        <i class="fas fa-print"></i>
    </button>
    <button class="floating-btn" onclick="editMenu()" title="Edit Menu">
        <i class="fas fa-edit"></i>
    </button>
</div> -->

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-print"></i> Print Menu Options</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-3">
                    <button class="btn btn-primary btn-lg" onclick="printMenu()">
                        <i class="fas fa-print"></i> Print Complete Menu
                    </button>
                    <button class="btn btn-outline-primary" onclick="printDietaryInfo()">
                        <i class="fas fa-leaf"></i> Print Dietary Information Only
                    </button>
                    <button class="btn btn-outline-success" onclick="printRequirements()">
                        <i class="fas fa-clipboard-list"></i> Print Nutritional Requirements
                    </button>
                    <button class="btn btn-outline-secondary" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> Export to PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add menu modal -->
<div class="modal" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ingredientForm" method="POST" action="{{ route('menu.store') }}">
            @csrf
            <input type="hidden" name="day" id="modalDay">
            <input type="hidden" name="meal_type" id="modalMealType">

            <input type="hidden" name="menuweek" id="menuweekinput">
            <input type="hidden" name="selected_date" id="selectedDateInput">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ingredientModalLabel">Select Recipes</h5>
                    <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close"></button>
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


<!-- Include Bootstrap JS for tab functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to table cells
        const tableCells = document.querySelectorAll('.menu-table td:not(:first-child)');
        tableCells.forEach(cell => {
            cell.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
                this.style.transition = 'transform 0.2s ease';
            });

            cell.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });

            // Add click effect
            cell.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1.02)';
                }, 100);
            });
        });

        // Week selector animation
        const weekSelector = document.querySelector('.week-selector');
        if (weekSelector) {
            weekSelector.addEventListener('change', function() {
                const mainContainer = document.querySelector('.main-container');
                if (mainContainer) {
                    mainContainer.style.opacity = '0.8';
                    mainContainer.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        mainContainer.style.opacity = '1';
                        showNotification('Week updated successfully!');
                    }, 300);
                }
            });
        }

        // Add fade-in animation to requirements boxes
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe requirements boxes
        const requirementBoxes = document.querySelectorAll('.requirement-box, .info-box, .card');
        requirementBoxes.forEach(box => {
            // Set initial state for animation
            box.style.opacity = '0';
            box.style.transform = 'translateY(20px)';
            observer.observe(box);
        });

        // Notification function
        function showNotification(message) {
            // Create notification element if it doesn't exist
            let notification = document.getElementById('notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'notification';
                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 12px 20px;
                border-radius: 5px;
                z-index: 1000;
                opacity: 0;
                transition: opacity 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                font-weight: 500;
            `;
                document.body.appendChild(notification);
            }

            notification.textContent = message;
            notification.style.opacity = '1';

            // Hide after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
            }, 3000);
        }

        // Make showNotification globally available
        window.showNotification = showNotification;
    });
</script>

<script>
    flatpickr("#calendarPicker", {
        dateFormat: "d-m-Y",
        defaultDate: "{{ $selectedDate ?? now()->format('d-m-Y') }}",
        onChange: function(selectedDates, dateStr, instance) {
            document.getElementById("dateFilterForm").submit();
        }
    });
    $('.add-item-btn').on('click', function() {
        // alert();

        // Get selected date from calendar
        var selectedDate = $('#calendarPicker').val();

        // Set in hidden input field in modal form
        $('#selectedDateInput').val(selectedDate);

        // Also set day and meal type if needed
        let day = $(this).data('day'); // Assuming you pass day
        let mealType = $(this).data('meal'); // Assuming you pass meal type


        $('#modalDay').val(day);
        $('#modalMealType').val(mealType);

        // Now show modal
        $('#ingredientModal').modal('show');
    });
</script>
<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        imageModal.addEventListener('show.bs.modal', function(event) {
            const triggerImg = event.relatedTarget;
            const imgSrc = triggerImg.getAttribute('data-img-src');
            modalImage.src = imgSrc;
        });
    });
</script> -->


<script>
    $(document).on('click', '.openIngredientModal', function() {
        // console.log('here');
        const day = $(this).data('day');
        const meal = $(this).data('meal');
        var selectedDate = $('#calendarPicker').val();

        // Set in hidden input field in modal form
        $('#selectedDateInput').val(selectedDate);

        let menuweek = $('#menuweek').val();
        // console.log(menuweek);
        $('#menuweekinput').val(menuweek);

        $('#modalDay').val(day);
        $('#modalMealType').val(meal);
        $('#ingredientModalLabel').text(`Add Items for ${meal}`);

        $('#recipeList').html('<p class="text-muted">Loading recipes...</p>');

        // Fetch recipes based on type
        $.ajax({
            url: '/get-recipes-by-type',
            method: 'GET',
            data: {
                type: meal
            },
            success: function(data) {
                // console.log(data);
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
            error: function() {
                $('#recipeList').html('<p class="text-danger">Failed to load recipes.</p>');
            }
        });
    });
</script>

<script>
    document.getElementById('menuweek').addEventListener('change', function() {

        document.getElementById('menuweekform').submit();
    });
</script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This recipe will be removed from the menu!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@stop