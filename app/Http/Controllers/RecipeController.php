<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    // Начална страница – рецепти с най-много лайкове днес
    public function home()
    {
        $recipes = Recipe::withCount('likes')
            ->whereDate('created_at', today())
            ->where('is_recipe_deleted', false)
            ->orderByDesc('likes_count')
            ->take(10)
            ->having('likes_count', '>=', 1)
            ->get();

        return view('recipes.home', compact('recipes'));
    }

    // Моите рецепти
    public function my()
    {
        $recipes = Recipe::where('user_id', Auth::id())
            ->where('is_recipe_deleted', false)
            ->get();

        return view('recipes.my', compact('recipes'));
    }

    // Показва форма за създаване
    public function create()
    {
        $categories = Category::all();
        return view('recipes.create', compact('categories'));
    }

    public function index()
    {
        $recipes = Recipe::with('user', 'category', 'likes')
            ->where('is_recipe_deleted', false)
            ->orderByDesc('created_at')
            ->get();

        return view('recipes.index', compact('recipes'));
    }


    // Записва нова рецепта
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'is_recipe_deleted' => false,
        ]);

        return redirect()->route('recipes.my')->with('success', 'Рецептата е добавена успешно!');
    }

    // Показва форма за редакция
    public function edit($id)
    {
        $recipe = Recipe::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $categories = Category::all();

        return view('recipes.edit', compact('recipe', 'categories'));
    }

    // Обновява рецепта
    public function update(Request $request, $id)
    {
        $recipe = Recipe::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $recipe->update($request->only([
            'title', 'description', 'ingredients', 'instructions', 'category_id'
        ]));

        return redirect()->route('recipes.my')->with('success', 'Рецептата е обновена успешно!');
    }

    public function destroy($id)
    {
        $recipe = Recipe::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $recipe->update(['is_recipe_deleted' => true]);

        return back()->with('success', 'Рецептата е изтрита успешно.');
    }
}
