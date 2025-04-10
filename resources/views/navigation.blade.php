<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">RecipeShare</a>

        <ul class="navbar-nav ms-auto">
            @auth
                <li class="nav-item"><a class="nav-link" href="{{ route('recipes.my') }}">Моите рецепти</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-link nav-link" type="submit">Изход</button>
                    </form>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('settings') }}">Настройки</a></li>
            @endauth
            @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Регистрация</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Вход</a></li>
            @endguest
        </ul>
    </div>
</nav>
