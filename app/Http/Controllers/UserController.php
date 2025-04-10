<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Показва формата за регистрация
    public function showRegister()
    {
        return view('auth.register');
    }

    // Обработка на регистрация
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user); // автоматичен вход след регистрация

        return redirect()->route('home');
    }

    // Показва формата за вход
    public function showLogin()
    {
        return view('auth.login');
    }

    // Обработка на вход
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Търсим потребителя ръчно, за да проверим дали е маркиран като изтрит
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if (!$user || $user->is_user_deleted) {
        return back()->withErrors([
            'email' => 'Профилът не съществува или е деактивиран.',
        ]);
    }

    // Опитваме да логнем потребителя
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('home'));
    }

    return back()->withErrors([
        'email' => 'Невалиден имейл или парола.',
    ]);
}

    // Изход
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
