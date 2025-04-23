<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Recipe;

class CommentController extends Controller
{
    public function store(Request $request, $recipeId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'recipe_id' => $recipeId,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Коментарът е добавен успешно!');
    }
}


?>
