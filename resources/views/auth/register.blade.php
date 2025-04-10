@extends('layouts.app')

@section('content')
<h2>Регистрация</h2>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <label>Име</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Имейл</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Парола</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Повтори паролата</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Регистрация</button>
</form>
@endsection
