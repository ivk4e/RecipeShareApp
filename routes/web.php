<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CommentController;

// Начална страница – най-харесвани рецепти за днес
Route::get('/', [RecipeController::class, 'home'])->name('home');

// Аутентикация
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);

Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Само за логнати потребители
Route::middleware(['auth'])->group(function () {
    Route::get('/my-recipes', [RecipeController::class, 'my'])->name('recipes.my');
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');

    Route::get('/recipes/{id}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{id}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::post('/recipes/{id}/like', [LikeController::class, 'store'])->name('recipes.like');
    Route::delete('/recipes/{id}/like', [LikeController::class, 'destroy'])->name('recipes.unlike');
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
});

Route::middleware('auth')->post('/recipes/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
