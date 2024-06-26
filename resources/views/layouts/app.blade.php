<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/tag-edit.js') }}" defer></script>
    <script src="{{ asset('js/memo-save.js') }}" defer></script>
    @yield('javascript')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.4.2/css/all.css" rel="stylesheet">
    <link href="/css/layout.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-white" href="{{ url('/') }}">
                    <i class="fa-solid fa-file-pen"></i>
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="row adj-margin">
                <div class="col-sm-12 col-md-2 p-0">
                    <div class="card">
                        <h5 class="card-header">タグ一覧</h5>
                        <div class="card-body my-card-body">
                            <p class="card-text">
                                <a href="/" class="card-text d-block mb-4 text-success">すべて表示</a>
                            @foreach($tags as $tag)
                                <div class="tag-container mb-2">
                                    <a href="/?tag={{ $tag['id'] }}" class="card-text d-block text-decoration-none text-dark ellipsis">
                                        <span class="tag-name" data-id="{{ $tag['id'] }}">{{ ($tag['name']) }}</span>
                                    </a>
                                    <div class="icons">
                                        <i class="fa fa-pen" onclick="enableEdit(this)"></i>
                                        <i class="fa fa-trash-can" onclick="tagDeletion('{{ $tag['id'] }}')"></i>
                                    </div>
                                </div>
                            @endforeach
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 p-0">
                    <div class="card">
                        <h5 class="card-header d-flex justify-content-between">メモ一覧
                            <a href="{{ route('home') }}">
                                <span>
                                    <i class="fa-solid fa-plus text-white me-1"></i>
                                </span>
                            </a>
                        </h5>
                        <div class="card-body my-card-body">
                        @foreach($memos as $memo)
                            <a href="/edit/{{$memo['id']}}" class="card-text d-block ellipsis mb-2 text-decoration-none text-dark">{{ $memo['content']}}</a>
                        @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 p-0">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
