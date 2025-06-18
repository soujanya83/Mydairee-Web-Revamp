<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Http\Controllers\Controller;
use App\Models\IngredientModel;
use App\Models\Menu;
use App\Models\RecipeModel;
use App\Models\Usercenter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Str;

class HealthyController extends Controller
{

    public function getByType(Request $request)
    {
        $type = $request->input('type');

        if ($type == 'Morning Tea') {
            $MEALTYPE = 'MORNING_TEA';
        } elseif ($type == 'Afternoon Tea') {
            $MEALTYPE = 'AFTERNOON_TEA';
        } elseif ($type == 'Late Snacks') {
            $MEALTYPE = 'SNACKS';
        } else {

            $MEALTYPE = $type;
        }
        $recipes = RecipeModel::where('type', strtoupper($MEALTYPE))->select('id', 'itemName')->get();
        return response()->json($recipes);
    }

    public function store_menu(Request $request)
    {

        $request->validate([
            'selected_date' => 'required',
            'day' => 'required|string',
            'meal_type' => 'required|string',
            'recipe_ids' => 'required|array',
            'recipe_ids.*' => 'exists:recipes,id', // or your recipe table
        ]);

        $currentDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->selected_date)->format('Y-m-d');

        foreach ($request->recipe_ids as $recipeId) {
            Menu::create([
                'day' => $request->day,
                'mealType' =>  strtoupper($request->meal_type),
                'recipeid' => $recipeId,
                'addedBy' => auth::user()->id,
                'centerId' => session('user_center_id'),
                'currentDate' => $currentDate,
            ]);
        }

        return redirect()->back()->with('success', 'Recipes added successfully!');
    }


    public function healthy_menu(Request $request)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Format selected date
        $selectedDate = $request->selected_date ?? Carbon::now()->format('d-m-Y');
        $formattedDate = null;
        $selectedDay = null;

        if ($selectedDate) {
            try {
                $carbonDate = Carbon::createFromFormat('d-m-Y', $selectedDate);
                $formattedDate = $carbonDate->format('Y-m-d');
                $selectedDay = $carbonDate->format('l'); // "Monday", "Tuesday", etc.
            } catch (\Exception $e) {
                $formattedDate = null;
                $selectedDay = null;
            }
        }

        // Get center(s)
        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
            $centerIds = $centers->pluck('id')->toArray();
        } else {
            $centers = Center::where('id', $centerid)->get();
            $centerIds = [$centerid];
        }

        // Get menu + recipe details
        $query = Menu::select('menu.*', 'recipes.itemName', 'recipes.createdAt as recepiesDate', 'recipe_media.mediaUrl')
            ->where('menu.centerId', $centerid)
            ->join('recipes', 'recipes.id', '=', 'menu.recipeid')
            ->leftJoin('recipe_media', 'recipe_media.recipeid', '=', 'recipes.id');

        if ($formattedDate) {
            $query->where('menu.currentDate', $formattedDate);
        }

        $menus = $query->get()->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'name' => $item->itemName,
                'day' => $item->day,
                'mealType' => strtoupper($item->mealType), // normalize to UPPERCASE
                'colorClass' => $item->color_class ?? null,
                'mediaUrl' => $item->mediaUrl ?? null,
                'createdAt' => $item->recepiesDate ?? null,
            ];
        });

        $recipes = RecipeModel::all(); // optional

        return view('healthy.menulist', compact('menus', 'centers', 'recipes', 'selectedDate', 'selectedDay'));
    }





    // public function healthy_menu(Request $request)
    // {
    //     $authId = Auth::user()->id;
    //     $centerid = Session('user_center_id');

    //     // Format selected date if available
    //     $selectedDate = $request->selected_date ?? null;
    //     $formattedDate = null;
    //     if ($selectedDate) {
    //         try {
    //             $formattedDate = \Carbon\Carbon::createFromFormat('d-m-Y', $selectedDate)->format('Y-m-d');
    //         } catch (\Exception $e) {
    //             $formattedDate = null;
    //         }
    //     }

    //     // Get center(s)
    //     if (Auth::user()->userType == "Superadmin") {
    //         $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
    //         $centers = Center::whereIn('id', $center)->get();
    //         $centerIds = $centers->pluck('id')->toArray();
    //     } else {
    //         $centers = Center::where('id', $centerid)->get();
    //         $centerIds = [$centerid];
    //     }

    //     // Base query
    //     $query = Menu::whereIn('menu.centerId', $centerIds)
    //         ->join('recipes', 'recipes.id', '=', 'menu.recipeid');

    //     // Apply date filter if selected
    //     if ($formattedDate) {
    //         $query->where('menu.currentDate', $formattedDate);
    //     }

    //     $menus = $query->get()
    //         ->map(function ($item) {
    //             return (object)[
    //                 'id' => $item->id,
    //                 'name' => $item->name,
    //                 'day' => $item->day,
    //                 'mealType' => $item->meal_type,
    //                 'colorClass' => $item->color_class ?? null,
    //             ];
    //         });

    //     $recipes = RecipeModel::all();

    //     return view('healthy.menulist', compact('menus', 'centers', 'recipes', 'selectedDate'));
    // }

    // public function healthy_menu(Request $request)
    // {

    //     $authId = Auth::user()->id;
    //     $centerid = Session('user_center_id');


    //     if (Auth::user()->userType == "Superadmin") {
    //         $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
    //         $centers = Center::whereIn('id', $center)->get();
    //         $centerIds = $centers->pluck('id')->toArray();
    //     } else {
    //         $centers = Center::where('id', $centerid)->get();
    //         $centerIds = [$centerid]; // wrap single ID in array for consistency
    //     }

    //     $menus = Menu::whereIn('menu.centerId', $centerIds)
    //         ->join('recipes', 'recipes.id', '=', 'menu.recipeid')
    //         ->get()
    //         ->map(function ($item) {
    //             return (object)[
    //                 'id' => $item->id,
    //                 'name' => $item->name,
    //                 'day' => $item->day,
    //                 'mealType' => $item->meal_type,
    //                 'colorClass' => $item->color_class ?? null,
    //             ];
    //         });

    //     $recipes = RecipeModel::all();
    //     return view('healthy.menulist', compact('menus', 'centers', 'recipes'));
    // }



    public function recipes_store(Request $request)
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create recipe
        $recipe = RecipeModel::create([
            'itemName'  => $request->itemName,
            'type'      => $request->mealType,
            'recipe'    => $request->recipe,
            'createdBy' => Auth::user()->id,
            'centerid'  => session('user_center_id'),

        ]);

        // Link ingredient
        DB::table('recipe_ingredients')->insert([
            'ingredientId' => $request->ingredient,
            'recipeId'     => $recipe->id,
        ]);

        // Save image files to recipe_media
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

        // Save video files to recipe_media
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
        return redirect()->back()->with('success', 'Recipe added successfully!');
    }

    public function ingredients_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $id,
        ]);

        $ingredient = IngredientModel::findOrFail($id);
        $ingredient->name = $request->name;
        $ingredient->save();

        return redirect()->back()->with('success', 'Ingredient updated successfully!');
    }


    public function destroy_ingredent($id)
    {
        $recipe = IngredientModel::findOrFail($id);
        $recipe->delete();
        return redirect()->back()->with('success', 'Ingredient deleted successfully');
    }


    public function ingredients_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
        ]);

        IngredientModel::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Ingredient added successfully!');
    }


    public function recipes_Ingredients()
    {

        $ingredients = IngredientModel::all()->map(function ($item) {
            $colors = ['xl-pink', 'xl-turquoise', 'xl-parpl', 'xl-blue', 'xl-khaki'];
            $item->colorClass = $colors[$item->id % count($colors)];
            return $item;
        });
        return view('healthy.ingredients', compact('ingredients'));
    }

    public function healthy_recipe()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');


        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        $recipes = RecipeModel::where('recipes.centerid', $centerid)
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

        $uniqueMealTypes = Menu::select('mealType')
            ->distinct()
            ->pluck('mealType');

        $ingredients = IngredientModel::select('name', 'id')->get();

        return view('healthy.recipe_list', compact('centers', 'recipes', 'uniqueMealTypes', 'ingredients'));
    }


    public function edit($id)
    {
        $recipe = RecipeModel::findOrFail($id);
        return view('recipes.edit', compact('recipe'));
    }

    public function destroy($id)
    {
        $recipe = RecipeModel::findOrFail($id);
        $recipe->delete();
        return redirect()->back()->with('success', 'Recipe deleted successfully');
    }

    public function menu_destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return back()->with('error', 'Menu not found.');
        }

        $menu->delete();

        return back()->with('success', 'Menu deleted successfully.');
    }
}
