@extends('layouts.app')

@section('content')
<h2>Топ 10 най-харесвани рецепти днес</h2>
<p>Ако днес си публикувал рецепта и някой я е харесал повече от 1 път, то ще я видиш тук.</p>

<div class="mt-4">
        <a href="{{ route('recipes.index') }}" class="btn btn-outline-primary">📖 Виж всички рецепти</a>
    </div>

@foreach($recipes as $recipe)
<div class="card mb-3">
    <div class="card-body">
        <h5>{{ $recipe->title }}</h5>
        <p>{{ $recipe->description }}</p>
        <small>Харесвания: {{ $recipe->likes_count }}</small>

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
                    <button type="submit" class="btn btn-sm btn-outline-secondary">💔 Премахни</button>
                </form>
            @endif
        @endauth

        <!-- Бутон Виж повече -->
        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#recipeModal{{ $recipe->id }}">
            🔍 Виж повече
        </button>
    </div>
</div>
<!-- Модал за показване на детайли за рецептата -->
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
