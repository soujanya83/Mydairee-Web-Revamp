<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Center;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use App\Models\RecipeModel;
use App\Models\Usercenter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\IngredientModel;

class ApiHealthyController extends Controller
{

    public function getUniqueMealTypes()
    {
        try {
            $uniqueMealTypes = Menu::select('mealType')
                ->distinct()
                ->pluck('mealType');

            return response()->json([
                'status' => 'success',
                'meal_types' => $uniqueMealTypes,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch meal types.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function ingredientsStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:ingredients,name',
            ]);

            $ingredient = IngredientModel::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient added successfully!',
                'data' => $ingredient
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroyIngredient($id)
    {
        try {
            $ingredient = IngredientModel::find($id);

            if (!$ingredient) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ingredient not found.'
                ], 404);
            }

            $ingredient->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function apiEditIngredient($id)
    {
        try {
            $recipe = IngredientModel::find($id);

            if (!$recipe) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ingredient not found.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient fetched successfully.',
                'recipe' => $recipe,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while fetching the Ingredient.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update ingredient via API
     */
    public function apiUpdateIngredient(Request $request, $id = null)
    {
        $idToUse = $id ?? $request->input('id');

        if (!$idToUse) {
            return response()->json(['status' => 'error', 'message' => 'Ingredient ID is required.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredients,name,' . $idToUse,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ingredient = IngredientModel::find($idToUse);
        if (!$ingredient) {
            return response()->json(['status' => 'error', 'message' => 'Ingredient not found.'], 404);
        }

        $ingredient->name = $request->name;
        $ingredient->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient updated successfully.',
            'data' => $ingredient
        ], 200);
    }

    public function apiRecipeIngredients()
    {
        try {
            $ingredients = IngredientModel::all()->map(function ($item) {
                $colors = ['xl-pink', 'xl-turquoise', 'xl-parpl', 'xl-blue', 'xl-khaki'];
                $item->colorClass = $colors[$item->id % count($colors)];
                return $item;
            });

            return response()->json([
                'status' => 'success',
                'ingredients' => $ingredients,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function apiStoreRecipe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'itemName'   => 'required|string|max:255',
            'mealType'   => 'required|string|max:255',
            'ingredient' => 'required|exists:ingredients,id',
            'recipe'     => 'required|string',
            'centerId'   => 'nullable|exists:centers,id',
            'notes'      => 'nullable|string',
            'foodtype'   => 'nullable|string|max:255',
            'RecipeVideolink' => 'nullable|string|max:255',
            'image.*'    => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video.*'    => 'nullable|file|mimes:mp4,avi,mov,webm|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $centerId = $request->centerId ?? $user?->user_center_id ?? session('user_center_id');

            $recipe = RecipeModel::create([
                'itemName'  => $request->itemName,
                'type'      => $request->mealType,
                'recipe'    => $request->recipe,
                'createdBy' => $user->id,
                'centerid'  => $centerId,
                'RecipeVideolink' => $request->RecipeVideolink,
                'foodtype'  => $request->foodtype ?? 'veg',
                'notes'     => $request->notes ?? '',

            ]);

            // Link ingredient
            DB::table('recipe_ingredients')->insert([
                'ingredientId' => $request->ingredient,
                'recipeId'     => $recipe->id,
            ]);

            // Save images
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $imagePath = $image->store('uploads/recipes', 'public');
                    DB::table('recipe_media')->insert([
                        'recipeId'  => $recipe->id,
                        'mediaUrl'  => $imagePath,
                        'mediaType' => 'Image',
                    ]);
                }
            }

            // Save videos
            if ($request->hasFile('video')) {
                foreach ($request->file('video') as $video) {
                    $videoPath = $video->store('recipes/videos', 'public');
                    DB::table('recipe_media')->insert([
                        'recipeId'  => $recipe->id,
                        'mediaUrl'  => $videoPath,
                        'mediaType' => 'Video',
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Recipe added successfully!',
                'recipe_id' => $recipe->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while saving the recipe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function apiDestroyRecipe($id)
    {
        try {
            $recipe = RecipeModel::find($id);

            if (!$recipe) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Recipe not found.',
                ], 404);
            }

            $recipe->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Recipe deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update recipe via API
     */
    public function apiUpdateRecipe(Request $request, $id = null)
    {
        $idToUse = $id ?? $request->input('id');

        if (!$idToUse) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe ID is required.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'itemName'      => 'required|string|max:255',
            'mealType'      => 'required_without:type|string|max:255',
            'type'          => 'nullable|string|max:255',
            'ingredients'   => 'required|array',
            'ingredients.*' => 'exists:ingredients,id',
            'recipe'        => 'nullable|string',
            'RecipeVideolink' => 'nullable|string|max:500',
            'notes'         => 'nullable|string',
            'foodtype'      => 'nullable|string|max:255',
            'image.*'       => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video.*'       => 'nullable|file|mimes:mp4,avi,mov,webm|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $normalizedMealType = $this->normalizeRecipeMealType($request->mealType ?? $request->type);
        if (!$normalizedMealType) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid meal type.',
                'allowed' => ['BREAKFAST', 'MORNING_TEA', 'LUNCH', 'AFTERNOON_TEA', 'SNACKS'],
            ], 422);
        }

        $recipe = RecipeModel::find($idToUse);
        if (!$recipe) {
            return response()->json(['status' => 'error', 'message' => 'Recipe not found.'], 404);
        }

        // Update main recipe fields
        $recipe->itemName = $request->itemName;
        $recipe->type = $normalizedMealType;
        $recipe->recipe = $request->recipe ?? $recipe->recipe;
        $recipe->RecipeVideolink = $request->RecipeVideolink ?? $recipe->RecipeVideolink;
        $recipe->notes = $request->notes ?? $recipe->notes;
        $recipe->foodtype = $request->foodtype ?? $recipe->foodtype;
        $recipe->save();

        // Sync ingredients: insert new, remove deleted
        $existingIngredients = DB::table('recipe_ingredients')
            ->where('recipeId', $recipe->id)
            ->pluck('ingredientId')
            ->toArray();

        $newIngredients = $request->ingredients ?? [];

        $toInsert = array_diff($newIngredients, $existingIngredients);
        foreach ($toInsert as $ingredientId) {
            DB::table('recipe_ingredients')->insert([
                'recipeId' => $recipe->id,
                'ingredientId' => $ingredientId,
            ]);
        }

        $toDelete = array_diff($existingIngredients, $newIngredients);
        if (!empty($toDelete)) {
            DB::table('recipe_ingredients')
                ->where('recipeId', $recipe->id)
                ->whereIn('ingredientId', $toDelete)
                ->delete();
        }

        // Save uploaded images
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imagePath = $image->store('uploads/recipes', 'public');
                DB::table('recipe_media')->insert([
                    'recipeId' => $recipe->id,
                    'mediaUrl' => $imagePath,
                    'mediaType' => 'Image',
                ]);
            }
        }

        // Save uploaded videos
        if ($request->hasFile('video')) {
            foreach ($request->file('video') as $video) {
                $videoPath = $video->store('recipes/videos', 'public');
                DB::table('recipe_media')->insert([
                    'recipeId' => $recipe->id,
                    'mediaUrl' => $videoPath,
                    'mediaType' => 'Video',
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe updated successfully.',
            'recipe_id' => $recipe->id
        ], 200);
    }


    public function apiEditRecipe($id)
    {
        try {
            $recipe = RecipeModel::with('ingredients')->find($id);

            if (!$recipe) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Recipe not found.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Recipe fetched successfully.',
                'recipe' => $recipe,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while fetching the recipe.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function apiHealthyRecipe(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 401);
            }

            $authId = $user->id;
            $centerid = $user->user_center_id ?? session('user_center_id');
            $requestedCenterId = $request->input('center_id');

            if ($user->userType == "Superadmin") {
                $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();

                if ($requestedCenterId) {
                    $centerIds = [$requestedCenterId];
                }

                $centers = Center::whereIn('id', $centerIds)->get();
            } else {
                $centerId = $centerid;

                if ($requestedCenterId && $requestedCenterId != $centerId) {
                    return response()->json(['status' => 'error', 'message' => 'Access denied for requested center.'], 403);
                }

                $centerIds = [$centerId];
                $centers = Center::whereIn('id', $centerIds)->get();
            }

            $recipes = RecipeModel::whereIn('recipes.centerid', $centerIds)
                ->join('recipe_media', 'recipe_media.recipeid', '=', 'recipes.id')
                ->join('users', 'users.id', '=', 'recipes.createdBy')
                ->select(
                    'recipes.*',
                    'recipe_media.mediaUrl',
                    'users.name as created_by_name',
                    'users.userType as created_by_role'
                )
                ->with('ingredients')
                ->get()
                ->groupBy('type');

            $uniqueMealTypes = Menu::select('mealType')->distinct()->pluck('mealType');
            $ingredients = IngredientModel::select('id', 'name')->get();

            return response()->json([
                'status' => 'success',
                'centers' => $centers,
                'Current Center Id' => $requestedCenterId,
                'recipes' => $recipes,
                'unique_meal_types' => $uniqueMealTypes,
                'ingredients' => $ingredients,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch healthy recipes.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function apiMenuDestroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu not found.'
            ], 404);
        }

        $menu->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Menu deleted successfully.'
        ], 200);
    }


    public function apiStoreMenu(Request $request)
    {
        try {
            $request->validate([
                'selected_date' => 'required|date_format:d-m-Y',
                'day' => 'required|string',
                'meal_type' => 'required|string',
                'recipe_ids' => 'required|array',
                'recipe_ids.*' => 'exists:recipes,id',
                'center_id' => 'required|string',
                'menuweek' => 'nullable|integer|min:1',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $currentDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->selected_date)->format('Y-m-d');
            $selectedCarbonDate = Carbon::createFromFormat('d-m-Y', $request->selected_date);
            $menuWeek = $request->filled('menuweek')
                ? (int) $request->menuweek
                : $this->resolveMenuWeek($selectedCarbonDate);

            $centerId = $request->center_id;
            if (!$centerId) {
                return response()->json(['status' => 'error', 'message' => 'Center ID not found.'], 400);
            }

            foreach ($request->recipe_ids as $recipeId) {
                Menu::create([
                    'day' => $request->day,
                    'mealType' => strtoupper($request->meal_type),
                    'recipeid' => $recipeId,
                    'addedBy' => $user->id,
                    'centerId' => $centerId,
                    'currentDate' => $currentDate,
                    'menuweek' => $menuWeek,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Recipes added successfully!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function apiHealthyMenu(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 401);
            }

            $authId = $user->id;
            $selectedDate = $request->selected_date ?? Carbon::now()->format('d-m-Y');
            $formattedDate = null;
            $selectedDay = null;
            $menuWeek = null;

            try {
                $carbonDate = Carbon::createFromFormat('d-m-Y', $selectedDate);
                $formattedDate = $carbonDate->format('Y-m-d');
                $selectedDay = $carbonDate->format('l');
                $menuWeek = $request->filled('menuweek')
                    ? (int) $request->input('menuweek')
                    : $this->resolveMenuWeek($carbonDate);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Invalid date format. Use d-m-Y.'], 400);
            }

            // Require an explicit `center_id` request param.
            $requestedCenterId = $request->input('center_id');

            if ($requestedCenterId === null || $requestedCenterId === '') {
                return response()->json(['status' => 'error', 'message' => 'center_id is required.'], 400);
            }

            $requestedCenterExists = Center::whereKey($requestedCenterId)->exists();

            if (!$requestedCenterExists) {
                return response()->json(['status' => 'error', 'message' => 'Requested center not found.'], 404);
            }

            $allowedCenterIds = Usercenter::where('userid', $authId)
                ->pluck('centerid')
                ->map(fn($id) => (string) $id)
                ->toArray();

            if (!in_array((string) $requestedCenterId, $allowedCenterIds, true)) {
                return response()->json(['status' => 'error', 'message' => 'Access denied for requested center.'], 403);
            }

            $centerIds = [$requestedCenterId];

            $centers = Center::whereIn('id', $centerIds)->get();

            // Fetch menus
            $query = Menu::select(
                'menu.*',
                'recipes.itemName',
                'recipes.createdAt as recepiesDate',
                'recipe_media.mediaUrl'
            )
                ->whereIn('menu.centerId', $centerIds)
                ->join('recipes', 'recipes.id', '=', 'menu.recipeid')
                ->leftJoin('recipe_media', 'recipe_media.recipeid', '=', 'recipes.id');

            if ($menuWeek !== null) {
                $query->where('menu.menuweek', $menuWeek);
            }

            $mealTypeLabels = [
                'BREAKFAST' => 'Breakfast',
                'MORNING_TEA' => 'Morning Tea',
                'LUNCH' => 'Lunch',
                'AFTERNOON_TEA' => 'Afternoon Tea',
                'SNACKS' => 'Late Snacks',
            ];
            $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            $menusByMealType = $query->get()->groupBy(fn ($item) => strtoupper($item->mealType));

            $menus = collect($mealTypeLabels)
                ->map(function ($label, $mealTypeKey) use ($menusByMealType, $weekDays) {
                    $mealItems = $menusByMealType->get($mealTypeKey, collect());

                    return [
                        'mealType' => $label,
                        'days' => collect($weekDays)
                            ->map(function ($day) use ($mealItems, $mealTypeKey) {
                                $dayItems = $mealItems
                                    ->where('day', $day)
                                    ->values()
                                    ->map(function ($item) use ($mealTypeKey) {
                                        return [
                                            'id' => $item->id,
                                            'name' => $item->itemName,
                                            'mealType' => $mealTypeKey,
                                            'colorClass' => $item->color_class ?? null,
                                            'mediaUrl' => $item->mediaUrl ?? null,
                                            'createdAt' => $item->recepiesDate ?? null,
                                        ];
                                    });

                                return [
                                    'day' => $day,
                                    'items' => $dayItems,
                                ];
                            })
                            ->values(),
                    ];
                })
                ->values();

            return response()->json([
                'status' => 'success',
                'selected_date' => $selectedDate,
                'selected_day' => $selectedDay,
                'menuweek' => $menuWeek,
                'Current Center' => $requestedCenterId,
                'menus' => $menus,
                
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch healthy menu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function apiGetRecipesByType(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'type' => 'required|string',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validated->errors(),
                ], 422);
            }

            // Normalize type input
            $type = strtolower($request->input('type'));
            switch ($type) {
                case 'morning tea':
                    $mealType = 'MORNING_TEA';
                    break;
                case 'afternoon tea':
                    $mealType = 'AFTERNOON_TEA';
                    break;
                case 'late snacks':
                    $mealType = 'SNACKS';
                    break;
                default:
                    $mealType = strtoupper($type); // Use input if no mapping found
                    break;
            }

            $recipes = RecipeModel::where('type', $mealType)
                ->select('id', 'itemName')
                ->get();

            return response()->json([
                'status' => 'success',
                'type' => $mealType,
                'count' => $recipes->count(),
                'recipes' => $recipes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function resolveMenuWeek(Carbon $selectedDate): int
    {
        $weeks = $this->getWeeksOfMonth($selectedDate->year, $selectedDate->month);

        foreach ($weeks as $weekIndex => $weekRange) {
            if ($selectedDate->betweenIncluded($weekRange['start'], $weekRange['end'])) {
                return (int) $weekIndex;
            }
        }

        return 1;
    }

    private function getWeeksOfMonth($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $weeks = [];
        $weekIndex = 1;

        $current = $startOfMonth->copy();
        if (!$current->isMonday()) {
            $current->next(Carbon::MONDAY);
        }

        while ($current->lte($endOfMonth)) {
            $weekStart = $current->copy();
            $weekEnd = $current->copy()->endOfWeek(Carbon::FRIDAY);

            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth->copy();

                if ($weekEnd->isSaturday()) {
                    $weekEnd->subDay();
                } elseif ($weekEnd->isSunday()) {
                    $weekEnd->subDays(2);
                }
            }

            $weeks[$weekIndex] = [
                'start' => $weekStart,
                'end' => $weekEnd,
            ];

            $weekIndex++;
            $current->addWeek();
        }

        return $weeks;
    }

    private function normalizeRecipeMealType(?string $rawType): ?string
    {
        if (!$rawType) {
            return null;
        }

        $normalized = strtoupper(trim($rawType));
        $normalized = preg_replace('/\s+/', '_', $normalized);

        $map = [
            'BREAKFAST' => 'BREAKFAST',
            'MORNING_TEA' => 'MORNING_TEA',
            'LUNCH' => 'LUNCH',
            'AFTERNOON_TEA' => 'AFTERNOON_TEA',
            'SNACK' => 'SNACKS',
            'SNACKS' => 'SNACKS',
            'LATE_SNACK' => 'SNACKS',
            'LATE_SNACKS' => 'SNACKS',
        ];

        return $map[$normalized] ?? null;
    }
}
