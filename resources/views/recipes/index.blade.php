@extends('layouts.app')

@section('content')
<h2>Всички рецепти</h2>

<!-- Форма за филтриране -->
<form method="GET" action="{{ route('recipes.index') }}" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label for="category" class="form-label">Категория</label>
            <select name="category" id="category" class="form-select">
                <option value="">Всички</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="user" class="form-label">Автор</label>
            <input type="text" name="user" id="user" class="form-control"
                   placeholder="Име на потребител" value="{{ request('user') }}">
        </div>

        <div class="col-md-3">
            <label for="rating" class="form-label">Рейтинг</label>
            <select name="rating" id="rating" class="form-select">
                <option value="">Всички</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                        {{ $i }} ⭐
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <label for="sort" class="form-label">Сортирай по</label>
            <select name="sort" id="sort" class="form-select">
                <option value=""></option>
                <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Най-харесвани</option>
                <option value="comments" {{ request('sort') == 'comments' ? 'selected' : '' }}>Най-коментирани</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Най-висок рейтинг</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Най-нови</option>
            </select>
        </div>

        <div class="col-md-4 d-flex justify-content-start align-items-end">
            <button type="submit" class="btn btn-primary">Филтрирай</button>
            <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary ms-2">Изчисти</a>
        </div>
    </div>
</form>

@foreach($recipes as $recipe)
<div class="card mb-3">
    <div class="card-body">
        <h5>{{ $recipe->title }}</h5>
        <p>{{ $recipe->description }}</p>
        <small>Автор: {{ $recipe->user->name ?? 'No user' }} | Категория: {{ $recipe->category->name ?? 'Без категория' }}
               | Дата на създаване: {{ $recipe->created_at->diffForHumans() ?? 'Няма дата' }}
        </small>
        <br>
        <small>Харесвания: {{ $recipe->likes->count() }}</small>

        @auth
            @php
                $hasLiked = $recipe->likes->contains('user_id', auth()->id());
            @endphp

            @if (!$hasLiked)
                <form action="{{ route('recipes.like', $recipe->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">❤️ Харесай</button>
                </form>
            @else
                <form action="{{ route('recipes.unlike', $recipe->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-secondary">💔 Премахни харесване</button>
                </form>
            @endif
        @endauth
        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#recipeModal{{ $recipe->id }}">
                🔍 Виж повече
        </button>
    </div>
</div>
<!-- Модал за рецептата -->
<div class="modal fade" id="recipeModal{{ $recipe->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel{{ $recipe->id }}">{{ $recipe->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($recipe->image)
                        <img src="{{ asset('storage/' . $recipe->image) }}" alt="Image" class="img-fluid mb-3">
                    @endif

                    <p><strong>Описание:</strong> {{ $recipe->description }}</p>
                    <p><strong>Съставки:</strong><br>{{ $recipe->ingredients }}</p>
                    <p><strong>Инструкции:</strong><br>{{ $recipe->instructions }}</p>

                    <hr>
                    @auth
                        @php
                            $hasLiked = $recipe->likes->contains('user_id', auth()->id());
                        @endphp

                        @if (!$hasLiked)
                            <form action="{{ route('recipes.like', $recipe->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">❤️ Харесай</button>
                            </form>
                        @else
                            <form action="{{ route('recipes.unlike', $recipe->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary">💔 Премахни</button>
                            </form>
                        @endif
                    @endauth

                    <hr>
                    <!-- Секция коментари -->
                    <h6>Коментари</h6>

                    @auth
                    <form action="{{ route('comments.store', $recipe->id) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-2">
                            <textarea name="comment" class="form-control" rows="2" placeholder="Остави коментар..." required></textarea>
                        </div>
                        <div class="mb-2">
                            <label for="rating">Оценка:</label>
                            <select name="rating" class="form-select form-select-sm w-auto d-inline-block ms-2">
                                <option value="">Без</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Публикувай</button>
                    </form>
                    @endauth

                    <!-- Показване на коментари -->
                    @if($recipe->comments->count())
                        @foreach($recipe->comments as $comment)
                            <div class="border p-2 mb-2 rounded">
                                <strong>{{ $comment->user->name }}</strong>
                                @if($comment->rating)
                                    – {{ $comment->rating }} ⭐
                                @endif
                                <p class="mb-1">{{ $comment->comment }}</p>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Още няма коментари.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
