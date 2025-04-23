@extends('layouts.app')

@section('content')
<h2>Редактиране на рецепта</h2>
<form method="POST" action="{{ route('recipes.update', $recipe->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $recipe->title) }}" placeholder="Заглавие" required>
    </div>

    <div class="mb-3">
        <textarea name="description" class="form-control" required>{{ old('description', $recipe->description) }}</textarea>
    </div>

    <div class="mb-3">
        <textarea name="ingredients" class="form-control" required>{{ old('ingredients', $recipe->ingredients) }}</textarea>
    </div>

    <div class="mb-3">
        <textarea name="instructions" class="form-control" required>{{ old('instructions', $recipe->instructions) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Категория</label>
        <select name="category_id" class="form-control">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $recipe->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success">Запази</button>
</form>
@endsection
