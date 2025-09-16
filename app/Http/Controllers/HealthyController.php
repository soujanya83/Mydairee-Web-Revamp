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
use Carbon\CarbonPeriod;
use App\Models\Permission;

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
            'menuweek' => 'required'
        ]);
        // dd($request->menuweek);
        $currentDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->selected_date)->format('Y-m-d');

        // dd($request->meal_type );
        $meal_type = "";
        if ($request->meal_type === "Morning Tea") {
            $meal_type = "MORNING_TEA";
        } else {
            $meal_type = $request->meal_type;
        }

        foreach ($request->recipe_ids as $recipeId) {
            Menu::create([
                'day' => $request->day,
                'mealType' =>  strtoupper($meal_type),
                'recipeid' => $recipeId,
                'addedBy' => auth::user()->id,
                'centerId' => session('user_center_id'),
                'currentDate' => $currentDate,
                'menuweek' => $request->menuweek
            ]);
        }

        return redirect()->back()->with('success', 'Recipes added successfully!');
    }

    //     public function healthy_menu(Request $request)
    //     {
    //         dd($request->all());
    //         $authId = Auth::user()->id;
    //         $centerid = Session('user_center_id');

    //         // Format selected date
    //         $selectedDate = $request->selected_date ?? Carbon::now()->format('d-m-Y');

    //         $formattedDate = null;
    //         $selectedDay = null;

    //         if ($selectedDate) {
    //             try {
    //                 $carbonDate = Carbon::createFromFormat('d-m-Y', $selectedDate)->timezone('Asia/Kolkata');
    //                 $formattedDate = $carbonDate->format('Y-m-d');
    //                 $selectedDay   = $carbonDate->format('l');
    //             } catch (\Exception $e) {
    //                 $formattedDate = null;
    //                 $selectedDay = null;
    //             }
    //         }

    //         // Get center(s)
    //         if (Auth::user()->userType == "Superadmin") {
    //             $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
    //             $centers = Center::whereIn('id', $center)->get();
    //             $centerIds = $centers->pluck('id')->toArray();
    //         } else {
    //             $centers = Center::where('id', $centerid)->get();
    //             $centerIds = [$centerid];
    //         }

    //         // Get menu + recipe details
    //         // $query = Menu::select('menu.*', 'recipes.itemName', 'recipes.recipe', 'recipes.createdAt as recepiesDate', 'recipe_media.mediaUrl')
    //         //     ->where('menu.centerId', $centerid)
    //         //     ->join('recipes', 'recipes.id', '=', 'menu.recipeid')
    //         //     ->leftJoin('recipe_media', 'recipe_media.recipeid', '=', 'recipes.id');

    //         // if ($formattedDate) {
    //         //     $query->where('menu.currentDate', $formattedDate);
    //         // }

    //         // $menus = $query->get()->map(function ($item) {
    //         //     return (object)[
    //         //         'id' => $item->id,
    //         //         'name' => $item->itemName,
    //         //         'day' => $item->day,
    //         //         'mealType' => strtoupper($item->mealType),
    //         //         'colorClass' => $item->color_class ?? null,
    //         //         'mediaUrl' => $item->mediaUrl ?? null,
    //         //         'createdAt' => $item->recepiesDate ?? null,
    //         //         'recipe' => $this->cleanText($item->recipe)
    //         //     ];
    //         // });

    //               // Detect weeks
    //         $currentWeek = now()->weekOfYear;
    //         $menuweek = $request->get('menuweek', $currentWeek);
    //         $weeks = $this->getWeeksOfMonth();

    //         $mealTypes = ['Breakfast', 'Morning Tea', 'Lunch', 'Afternoon Tea', 'Late Snacks'];
    //         $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    //         $recipes = RecipeModel::all();

    //         // ✅ Detect season
    //         $month = now()->month;
    //         if (in_array($month, [12, 1, 2])) {
    //             $season = "Winter";
    //         } elseif (in_array($month, [3, 4, 5])) {
    //             $season = "Summer";
    //         } elseif (in_array($month, [6, 7, 8])) {
    //             $season = "Monsoon";
    //         } else {
    //             $season = "Autumn";
    //         }

    //         $menusID = Menu::where('centerid', $centerid)->where('menuweek', $menuweek)->pluck('recipeid');


    // $recipes = RecipeModel::whereIn('id',$menusID)->get();


    //         $permission = Permission::where('userid', Auth::user()->userid)->first();
    //         return view('healthy.menu_list_new', compact(
    //             'centers',
    //             'recipes',
    //             'selectedDate',
    //             'selectedDay',
    //             'menuweek',
    //             'weeks',
    //             'mealTypes',
    //             'weekdays',
    //             'season',
    //             'permission',
    //             'currentWeek'
    //         ));
    //     }
    public function healthy_menu(Request $request)
    {
        // dd($request->all());
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Format selected date
        $selectedDate = $request->selected_date ?? Carbon::now()->format('d-m-Y');

        $formattedDate = null;
        $selectedDay = null;

        if ($selectedDate) {
            try {
                $carbonDate = Carbon::createFromFormat('d-m-Y', $selectedDate)->timezone('Asia/Kolkata');
                $formattedDate = $carbonDate->format('Y-m-d');
                $selectedDay   = $carbonDate->format('l');
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

        // Detect weeks
        $weeks = $this->getWeeksOfMonth(); // only weekdays (Mon–Fri)

        $currentWeek = null;
        $today = now()->startOfDay();

        foreach ($weeks as $index => $range) {
            if ($today->between($range['start'], $range['end'])) {
                $currentWeek = $index; // e.g., 1, 2, 3 within the month
                break;
            }
        }

        // fallback if not found
        if ($currentWeek === null) {
            $currentWeek = 1;
        }

        // ✅ now handle request value
        // $menuweek = (int) $request->get('menuweek', $currentWeek);

        // check if user explicitly chose menuweek
if ($request->filled('menuweek')) {
    $menuweek = (int) $request->get('menuweek');
} else {
    // otherwise, detect week from selected_date
    $menuweek = $currentWeek;
}



        $mealTypes = ['Breakfast', 'Morning Tea', 'Lunch', 'Afternoon Tea', 'Late Snacks'];
        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $recipes = RecipeModel::all();

        // ✅ Detect season
        $month = now()->month;
        if (in_array($month, [12, 1, 2])) {
            $season = "Winter";
        } elseif (in_array($month, [3, 4, 5])) {
            $season = "Summer";
        } elseif (in_array($month, [6, 7, 8])) {
            $season = "Monsoon";
        } else {
            $season = "Autumn";
        }

        // $menusID = Menu::where('centerid', $centerid)->where('menuweek', '1')->pluck('recipeid');
        $menusID = Menu::where('centerid', $centerid)
            ->where('menuweek', $menuweek)
            ->whereYear('currentDate', now()->year)
            ->pluck('recipeid');


        $recipes1 = RecipeModel::whereIn('id', $menusID)->get();

        // dd($recipes);

        // dd( $menuweek);

        $permission = Permission::where('userid', Auth::user()->userid)->first();
        return view('healthy.menu_list_new', compact(
            'centers',
            'recipes1',
            'selectedDate',
            'selectedDay',
            'menuweek',
            'weeks',
            'mealTypes',
            'weekdays',
            'season',
            'permission',
            'currentWeek',

        ));
    }

    // private function getWeeksOfMonth($year = null, $month = null)
    // {
    //     $year = $year ?? now()->year;
    //     $month = $month ?? now()->month;

    //     // First and last day of month
    //     $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
    //     $endOfMonth   = (clone $startOfMonth)->endOfMonth();

    //     // Period for all days of the month
    //     $days = CarbonPeriod::create($startOfMonth, $endOfMonth);

    //     $weeks = [];
    //     $weekIndex = 1;
    //     $weekStart = null;

    //     foreach ($days as $day) {
    //         // Skip weekends
    //         if ($day->isSaturday() || $day->isSunday()) {
    //             continue;
    //         }

    //         if ($weekStart === null) {
    //             $weekStart = $day->copy();
    //         }

    //         // Close the week on Friday or last weekday of month
    //         if ($day->isFriday() || $day->equalTo($endOfMonth)) {
    //             $weeks[$weekIndex] = [
    //                 'start' => $weekStart,
    //                 'end'   => $day->copy(),
    //             ];
    //             $weekIndex++;
    //             $weekStart = null;
    //         }
    //     }

    //     return $weeks;
    // }
    // private function getWeeksOfMonth($year = null, $month = null)
    // {
    //     $year = $year ?? now()->year;
    //     $month = $month ?? now()->month;

    //     // First and last day of month
    //     $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
    //     $endOfMonth   = (clone $startOfMonth)->endOfMonth();

    //     $weeks = [];
    //     $weekIndex = 1;
    //     $weekStart = null;

    //     for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {

    //         // ✅ Include weekends in loop (so current week detection works)

    //         // If weekday, mark start
    //         if (!$date->isSaturday() && !$date->isSunday()) {
    //             if ($weekStart === null) {
    //                 $weekStart = $date->copy();
    //             }
    //         }

    //         // Close the week on Friday, OR at end of month
    //         if ($date->isFriday() || $date->equalTo($endOfMonth)) {

    //             // Find last valid weekday (not Sat/Sun)
    //             $weekEnd = $date->copy();
    //             if ($weekEnd->isSaturday()) {
    //                 $weekEnd->subDay(); // Friday
    //             } elseif ($weekEnd->isSunday()) {
    //                 $weekEnd->subDays(2); // Friday
    //             }

    //             if ($weekStart) {
    //                 $weeks[$weekIndex] = [
    //                     'start' => $weekStart,
    //                     'end'   => $weekEnd,
    //                 ];
    //                 $weekIndex++;
    //                 $weekStart = null;
    //             }
    //         }
    //     }

    //     return $weeks;
    // }

    private function getWeeksOfMonth($year = null, $month = null)
{
    $year = $year ?? now()->year;
    $month = $month ?? now()->month;

    // First and last day of month
    $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
    $endOfMonth   = $startOfMonth->copy()->endOfMonth();

    $weeks = [];
    $weekIndex = 1;

    // Find first Monday in the month
    $current = $startOfMonth->copy();
    if (!$current->isMonday()) {
        $current->next(Carbon::MONDAY);
    }

    while ($current->lte($endOfMonth)) {
        // Start = Monday
        $weekStart = $current->copy();

        // End = Friday of same week
        $weekEnd = $current->copy()->endOfWeek(Carbon::FRIDAY);

        // Ensure it does not go beyond month end
        if ($weekEnd->gt($endOfMonth)) {
            $weekEnd = $endOfMonth->copy();
            // If it lands on Sat/Sun, shift back to Friday
            if ($weekEnd->isSaturday()) {
                $weekEnd->subDay();
            } elseif ($weekEnd->isSunday()) {
                $weekEnd->subDays(2);
            }
        }

        $weeks[$weekIndex] = [
            'start' => $weekStart,
            'end'   => $weekEnd,
        ];

        $weekIndex++;

        // Move to next Monday
        $current->addWeek();
    }

    return $weeks;
}


    public function recipes_store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'itemName'   => 'required|string|max:255',
            'mealType'   => 'required|string|max:255',
            'ingredients' => 'required|array|exists:ingredients,id',
            'foodtype' => 'required',
            'image.*'    => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video.*'    => 'nullable|file|mimes:mp4,avi,mov,webm|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // storing ingredient into string form converted from array
        // $ingredient = '';
        // if (!empty($request->ingredient)) {
        //     $ingredient = implode(',', $request->ingredient);
        // }

        // Create recipe
        $recipe = RecipeModel::create([
            'itemName'  => $request->itemName,
            'type'      => $request->mealType,
            'recipe'    => $request->recipe,
            'createdBy' => Auth::user()->id,
            'centerid'  => session('user_center_id'),
            'RecipeVideolink' => $request->RecipeVideolink,
            'foodtype' => $request->foodtype ?? "veg",
            'notes' => $request->notes ?? ""

        ]);

        // Link ingredient
        if (!empty($request->ingredients)) {
            foreach ($request->ingredients as $ingredientId) {
                DB::table('recipe_ingredients')->insert([
                    'ingredientId' => $ingredientId,
                    'recipeId'     => $recipe->id,
                ]);
            }
        }


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

        $recipes->each(function ($group) {
            $group->each(function ($recipe) {
                $recipe->recipe = $this->cleanText($recipe->recipe);
            });
        });

        $uniqueMealTypes = Menu::select('mealType')
            ->distinct()
            ->pluck('mealType');

        $ingredients = IngredientModel::select('name', 'id')->get();

        return view('healthy.recipe_list', compact('centers', 'recipes', 'uniqueMealTypes', 'ingredients'));
    }


    public function edit($id)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        $ingredients = IngredientModel::select('name', 'id')->get();

        $uniqueMealTypes = Menu::select('mealType')
            ->distinct()
            ->pluck('mealType');

        $recipe = RecipeModel::findOrFail($id);

        // Get selected ingredient
      $selectedIngredientId = DB::table('recipe_ingredients')
    ->where('recipeId', $id)
    ->pluck('ingredientId')
    ->toArray();

            // dd(  $selectedIngredientId);

        return view('healthy.edit', compact('recipe', 'uniqueMealTypes', 'ingredients', 'selectedIngredientId'));
    }



    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'itemName'   => 'required|string|max:255',
    //         'mealType'   => 'required|string|max:255',
    //         'ingredients' => 'required|exists:ingredients,id',
    //         'recipe'     => 'required|string',
    //     ]);

    //     $recipe = RecipeModel::findOrFail($id);

    //     // Update fields
    //     $recipe->update([
    //         'itemName' => $request->itemName,
    //         'type'     => $request->mealType,
    //         'recipe'   => $request->recipe,
    //     ]);

    //     // Update or Insert ingredient mapping
    //     DB::table('recipe_ingredients')->updateOrInsert(
    //         ['recipeId' => $recipe->id],
    //         ['ingredientId' => $request->ingredient]
    //     );

    //     return redirect()->route('healthy_recipe')->with('success', 'Recipe updated successfully!');
    // }

    public function update(Request $request, $id)
{
    $request->validate([
        'itemName'      => 'required|string|max:255',
        'mealType'      => 'required|string|max:255',
        'ingredients'   => 'required|array',
        'ingredients.*' => 'exists:ingredients,id',
        'recipe'        => 'nullable|string',
        'RecipeVideolink'     => 'nullable|string|max:500',
        'notes'         => 'nullable|string',
    ]);

    $recipe = RecipeModel::findOrFail($id);

    // ✅ Update main recipe fields
    $recipe->update([
        'itemName'  => $request->itemName,
        'type'      => $request->mealType,
        'recipe'    => $request->recipe ?? $recipe->recipe,
        'RecipeVideolink' => $request->RecipeVideolink ?? $recipe->RecipeVideolink,  // new field
        'notes'     => $request->notes,      // new field
        'foodtype' => $request->foodtype ??   $recipe->foodtype
    ]);

    // ✅ Get existing ingredient IDs for this recipe
    $existingIngredients = DB::table('recipe_ingredients')
        ->where('recipeId', $recipe->id)
        ->pluck('ingredientId')
        ->toArray();

    // ✅ Requested ingredients
    $newIngredients = $request->ingredients;

    // Find which ones to insert
    $toInsert = array_diff($newIngredients, $existingIngredients);

    foreach ($toInsert as $ingredientId) {
        DB::table('recipe_ingredients')->insert([
            'recipeId'     => $recipe->id,
            'ingredientId' => $ingredientId,
        ]);
    }

    // (Optional) Find which ones to delete if removed from form
    $toDelete = array_diff($existingIngredients, $newIngredients);

    if (!empty($toDelete)) {
        DB::table('recipe_ingredients')
            ->where('recipeId', $recipe->id)
            ->whereIn('ingredientId', $toDelete)
            ->delete();
    }

    return redirect()->route('healthy_recipe')->with('success', 'Recipe updated successfully!');
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

    private function cleanText($text)
    {
        // dd('here');
        if (empty($text)) {
            return '';
        }

        // 1. Remove all HTML tags
        $cleanText = strip_tags($text);

        // 2. Decode HTML entities (&amp; → &, &nbsp; → space)
        $cleanText = html_entity_decode($cleanText, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 3. Remove control / unknown characters
        $cleanText = preg_replace('/[^\P{C}\n]+/u', '', $cleanText);

        // 4. Normalize multiple spaces/newlines
        $cleanText = preg_replace('/\s+/', ' ', $cleanText);

        // 5. Final trim
        return trim($cleanText);
    }
}
