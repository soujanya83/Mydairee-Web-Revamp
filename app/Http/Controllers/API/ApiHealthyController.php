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
 $nowSydney = now()->setTimezone('Australia/Sydney');

            $recipe = RecipeModel::create([
                'itemName'  => $request->itemName,
                'type'      => $request->mealType,
                'recipe'    => $request->recipe,
                'createdBy' => $user->id,
                'centerid'  => $request->centerId,
              

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
            dd($e);
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


    public function apiEditRecipe($id)
    {
        try {
            $recipe = RecipeModel::find($id);

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

    public function apiHealthyRecipe()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 401);
            }

            $authId = $user->id;
            $centerid = $user->user_center_id ?? session('user_center_id');

            if ($user->userType == "Superadmin") {
                $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
                $centers = Center::whereIn('id', $centerIds)->get();
            } else {
                $centerIds = [$centerid];
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
                ->get()
                ->groupBy('type');

            $uniqueMealTypes = Menu::select('mealType')->distinct()->pluck('mealType');
            $ingredients = IngredientModel::select('id', 'name')->get();

            return response()->json([
                'status' => 'success',
                'centers' => $centers,
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
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $currentDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->selected_date)->format('Y-m-d');

            // Determine center ID (adjust if center is stored differently)
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

            try {
                $carbonDate = Carbon::createFromFormat('d-m-Y', $selectedDate);
                $formattedDate = $carbonDate->format('Y-m-d');
                $selectedDay = $carbonDate->format('l');
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Invalid date format. Use d-m-Y.'], 400);
            }

            // Get centers
            if ($user->userType === "Superadmin") {
                $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            } else {
                $centerId = Usercenter::where('userid', $authId)->value('centerid'); // Fetch single center ID
                $centerIds = [$centerId];
            }

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

            if ($formattedDate) {
                $query->where('menu.currentDate', $formattedDate);
            }

            $menus = $query->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->itemName,
                    'day' => $item->day,
                    'mealType' => strtoupper($item->mealType),
                    'colorClass' => $item->color_class ?? null,
                    'mediaUrl' => $item->mediaUrl ?? null,
                    'createdAt' => $item->recepiesDate ?? null,
                ];
            });

            return response()->json([
                'status' => 'success',
                'selected_date' => $selectedDate,
                'selected_day' => $selectedDay,
                'menus' => $menus,
                'centers' => $centers,
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
}
