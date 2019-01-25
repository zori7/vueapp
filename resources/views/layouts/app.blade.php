<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{--Pusher--}}

    <meta name="pusher_key" content="{{ config('pusher.app_key', '') }}">
    <meta name="pusher_cluster" content="{{ config('pusher.app_cluster', '') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto mx-3">

                        {{--Vue-router-links--}}

                        <li class="nav-item mx-2">
                            @if (Request::is('login') || Request::is('register') || Request::is('password/*'))
                                <a href="{{ route('login') }}" class="nav-link">Posts</a>
                            @else
                                <router-link to="/posts" class="nav-link">Posts</router-link>
                            @endif
                        </li>

                        <li class="nav-item mx-2">
                            @if (Request::is('login') || Request::is('register') || Request::is('password/*'))
                                <a href="{{ route('login') }}" class="nav-link">Users</a>
                            @else
                                <router-link to="/users" class="nav-link">Users</router-link>
                            @endif
                        </li>

                        <li class="nav-item mx-2">
                            @if (Request::is('login') || Request::is('register') || Request::is('password/*'))
                                <a href="{{ route('login') }}" class="nav-link">Chats</a>
                            @else
                                <router-link to="/messages" class="nav-link">
                                    Chats
                                    @if($messagesCount > 0)
                                        <span class="badge badge-dark">{{ $messagesCount }}</span>
                                    @endif
                                </router-link>
                            @endif
                        </li>

                        <li class="nav-item mx-2">
                            @if (Request::is('login') || Request::is('register') || Request::is('password/*'))
                                <a href="{{ route('login') }}" class="nav-link">Global chat</a>
                            @else
                                <router-link to="/global" class="nav-link">Global chat</router-link>
                            @endif
                        </li>

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <router-link class="dropdown-item" to="/user/{{ Auth::user()->id }}">My profile</router-link>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
            @if (!(Request::is('login') || Request::is('register') || Request::is('password/*')))
                <router-view></router-view>
            @endif
        </main>
    </div>
</body>
</html>
