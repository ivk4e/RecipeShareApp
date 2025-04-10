@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

<h2>Добавяне на рецепта</h2>
<form method="POST" action="{{ route('recipes.store') }}">
    @csrf
    <div class="mb-3"><input type="text" name="title" class="form-control" placeholder="Заглавие" required></div>
    <div class="mb-3"><textarea name="description" class="form-control" placeholder="Описание" required></textarea></div>
    <div class="mb-3"><textarea name="ingredients" class="form-control" placeholder="Съставки" required></textarea></div>
    <div class="mb-3"><textarea name="instructions" class="form-control" placeholder="Инструкции" required></textarea></div>
    <div class="mb-3">
        <label>Категория</label>
        <select name="category_id" class="form-control">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-success">Запази</button>
</form>
@endsection
