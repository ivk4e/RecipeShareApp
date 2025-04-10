<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($id)
    {
        $recipe = Recipe::findOrFail($id);

        // Проверка дали потребителят вече е харесал
        $alreadyLiked = RecipeLike::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->exists();

        if (!$alreadyLiked) {
            RecipeLike::create([
                'user_id' => Auth::id(),
                'recipe_id' => $recipe->id,
            ]);
        }

        return back(); // връща към същата страница
    }

    public function destroy($id)
    {
        $like = \App\Models\RecipeLike::where('user_id', auth()->id())
            ->where('recipe_id', $id)
            ->first();

        if ($like) {
            $like->delete();
        }

        return back();
    }
}
