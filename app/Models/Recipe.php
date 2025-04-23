<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'instructions',
        'image',
        'category_id',
        'user_id',
        'is_recipe_deleted',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => 'Без категория']);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'No user']);
    }

    public function likes()
    {
        return $this->hasMany(RecipeLike::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
