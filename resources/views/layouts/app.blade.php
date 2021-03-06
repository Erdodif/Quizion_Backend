<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="Quizion, Quiz, Quizzes">
        <meta name="description" content="Quizion, the multi platform quiz app.">
        <meta name="author" content="Quizion">
        <link rel="icon" href="{{ url('favicon.ico') }}">
        <link rel="stylesheet" href="{{ mix('scss/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/loader.js') }}"></script>
        <script src="{{ mix('js/variables.js') }}"></script>
        <title>Quizion @yield("title")</title>
    </head>
    <body>
        <div class="wrapper">
            <div id="loader-div">
                <div id="loader"></div>
            </div>
            @if (!(Route::is("index") || Route::is("documentation")))
            <header>
                <div id="header-background">
                    <img id="logo" src="{{ url('images/logo.png') }}" alt="Quizion Logo" title="Quizion">
                </div>
            </header>
            @endif
            <nav>
                <ul class="navbar-ul">
                    @if (Route::is("quiz"))
                    @elseif (Auth::user())
                    <li class="navbar-li"><a href="{{ route('quizzes') }}">{{ __('Quizzes') }}</a></li>
                    <li class="navbar-li">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input id="logout" type="submit" value="{{ __('Logout') }}">
                        </form>
                    </li>
                    <li id="navbar-name">{{ Auth::user()->name }}</li>
                    @else
                    <li class="navbar-li"><a href="{{ route('index') }}">{{ __('Index') }}</a></li>
                    <li class="navbar-li"><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    <li class="navbar-li"><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                    @endif
                </ul>
            </nav>
            <main>
                @yield("content")
            </main>
        </div>
        <footer>
            <h4>?? Copyright - quizion.hu - All rights reserved.</h4>
            <a href="">Legal and data protection statement.</a>
        </footer>
    </body>
</html>
