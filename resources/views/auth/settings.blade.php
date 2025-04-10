@extends('layouts.app')

@section('content')
<h2>Настройки на профила</h2>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('settings.update') }}" class="mb-4">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Име</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
    </div>
    <div class="mb-3">
        <label>Имейл</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Обнови данните</button>
</form>

<hr>

<form method="POST" action="{{ route('settings.password') }}" class="mb-4">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Нова парола</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Повтори паролата</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-warning">Смени паролата</button>
</form>

<hr>

<form method="POST" action="{{ route('settings.delete') }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Сигурен ли си, че искаш да изтриеш профила си?')">
        Изтрий профила
    </button>
</form>
@endsection
