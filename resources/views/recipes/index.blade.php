@extends('layouts.app')

@section('content')
<h2>Всички рецепти</h2>

@foreach($recipes as $recipe)
<div class="card mb-3">
    <div class="card-body">
        <h5>{{ $recipe->title }}</h5>
        <p>{{ $recipe->description }}</p>
        <small>Автор: {{ $recipe->user->name ?? 'No user' }} | Категория: {{ $recipe->category->name ?? 'Без категория' }}</small>
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
                    <!-- Секция коментари (в разработка) -->
                    <h6>Коментари</h6>
                    <div class="mb-2">
                        <textarea class="form-control" rows="2" placeholder="Остави коментар..." disabled></textarea>
                        <button class="btn btn-sm btn-primary mt-1" disabled>Публикувай (очаква се)</button>
                        <p class="text-muted mt-2">Функцията за коментари е в разработка 😊</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
