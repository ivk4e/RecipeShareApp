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

    public function index(Request $request)
    {
        // Започваме с query builder и зареждаме нужните релации
        $query = Recipe::with(['user', 'category', 'likes', 'comments'])
            ->withAvg('comments', 'rating')
            ->where('is_recipe_deleted', false);

        // Филтър по категория
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Филтър по име на потребител (автор)
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        // Филтър по точно избран рейтинг
        if ($request->filled('rating')) {
            $query->havingRaw('ROUND(comments_avg_rating, 0) = ?', [$request->rating]);
        }

        // Сортиране
        if ($request->sort === 'likes') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } elseif ($request->sort === 'comments') {
            $query->withCount('comments')->orderByDesc('comments_count');
        } elseif ($request->sort === 'rating') {
            $query->orderByDesc('comments_avg_rating');
        } else {
            $query->orderByDesc('created_at'); // по подразбиране (най-нови)
        }

        // Взимаме резултатите с пагинация
        $recipes = $query->paginate(10)->withQueryString();

        // Взимаме всички категории за dropdown менюто
        $categories = Category::all();

        // Подаваме данните към view-то
        return view('recipes.index', compact('recipes', 'categories'));
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
