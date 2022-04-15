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
            <div id="loader_div">
                <div id="loader"></div>
            </div>
            @if (!(Route::is("index") || Route::is("documentation")))
            <div id="header_logo">
                <div id="header_background">
                    <img id="logo" src="{{ url('images/logo.png') }}" alt="Quizion Logo" title="Quizion">
                </div>
            </div>
            @endif
            <ul id="navbar_ul">
                @if (Route::is("quiz"))
                @elseif (Auth::user())
                <li class="navbar_li"><a href="{{ route('quizzes') }}">{{ __('Quizzes') }}</a></li>
                <li class="navbar_li">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <input id="logout" type="submit" value="{{ __('Logout') }}">
                    </form>
                </li>
                <li id="navbar_name">{{ Auth::user()->name }}</li>
                @else
                <li class="navbar_li"><a href="{{ route('index') }}">{{ __('Index') }}</a></li>
                <li class="navbar_li"><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                <li class="navbar_li"><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                @endif
            </ul>
            <div id="container">
                @yield("content")
            </div>
        </div>
        <div id="footer">
            <h4>© Copyright - quizion.hu - All rights reserved.</h4>
            <a href="" target="copyright">Legal and data protection statement.</a>
        </div>
    </body>
</html>