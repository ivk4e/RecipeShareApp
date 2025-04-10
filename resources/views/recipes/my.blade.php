@extends('layouts.app')

@section('content')
<h2>Моите рецепти</h2>
<a href="{{ route('recipes.create') }}" class="btn btn-primary mb-3">+ Добави нова рецепта</a>

@foreach($recipes as $recipe)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $recipe->title }}</h5>
            <p>{{ $recipe->description }}</p>

            <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-sm btn-warning">✏️ Редактирай</a>

            <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Сигурен ли си, че искаш да изтриеш тази рецепта?')">
                    🗑️ Изтрий
                </button>
            </form>
        </div>
    </div>
@endforeach
@endsection
