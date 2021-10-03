<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="{{ $description ?? '' }}">
    <title>{{ $title }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSS Libraries -->

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/all.css') }}">

    <!-- Template CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}"> --}}
</head>

<body class="layout-3">
<div id="app">
    <div class="main-wrapper container">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <a href="{{ route('home') }}" class="navbar-brand sidebar-gone-hide">{{ config('app.name') }}</a>
            <div class="navbar-nav">
                <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
            </div>
            <div class="nav-collapse">
                <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <ul class="navbar-nav">
                    {{--@foreach(App\Models\Category::popular() as $category)
                        <li class="nav-item active">
                            <a href="{{ route('category.photos', [$category->slug]) }}" class="nav-link">{{ ucfirst($category->name) }}
                            </a>
                        </li>
                    @endforeach--}}
                </ul>
            </div>
            <form class="form-inline ml-auto" action="" method="get">

                <div class="search-element">

                    <input value="{{ request()->query('search', '') }}" class="form-control" name="search" type="search" placeholder="Rechercher" aria-label="Search" data-width="250">

                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>

                </div>

            </form>

            @auth

                <ul class="navbar-nav navbar-right">
                    @if(count(Auth::user()->unreadNotifications))
                        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg beep"><i class="far fa-bell"></i></a>
                            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                                <div class="dropdown-header">Notifications
                                    <div class="float-right">
                                        <a href="{{ route('notifications.read') }}">Marquer comme lues</a>
                                    </div>
                                </div>
                                <div class="dropdown-list-content dropdown-list-icons">
                                    @foreach(Auth::user()->unreadNotifications as $notification)
                                        <a href="#" class="dropdown-item dropdown-item-unread">
                                            <div class="dropdown-item-icon bg-primary text-white">
                                                {{-- <i class="fas fa-code"></i> --}}
                                                <img style="border-radius: 15%;" src="{{ $notification->data['photo']['thumbnail_url'] }}">
                                            </div>
                                            <div class="dropdown-item-desc">
                                                {{ $notification->data['user']['name'] }} a téléchargé {{ $notification->data['photo']['title'] }}
                                                <div class="time text-primary">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="dropdown-footer text-center">
                                    <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </li>
                    @endif

                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="../assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            {{-- <div class="dropdown-title">Logged in 5 min ago</div> --}}
                            <a href="{{ route('albums.create') }}" class="dropdown-item has-icon">
                                Nouvel Album
                            </a>
                            <a href="{{ route('albums.index') }}" class="dropdown-item has-icon">
                                Mes Albums
                            </a>
                            <a href="features-settings.html" class="dropdown-item has-icon">
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item has-icon text-danger">Déconnexion</button>
                            </form>
                        </div>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav navbar-right">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                    </li>
                </ul>
            @endauth
        </nav>

        <nav class="navbar navbar-secondary navbar-expand-lg">
            <div class="container">
                <ul class="navbar-nav">

                    {{--@forelse(App\Models\Tag::popular() as $tag)
                        <li class="nav-item active">
                            <a href="{{ route('tag.photos', [$tag->slug]) }}" class="nav-link"><span>{{ ucfirst($tag->name) }}</span></a>
                        </li>
                    @empty

                    @endforelse--}}

                </ul>
            </div>
        </nav>

        @yield('content')

        <footer class="main-footer">
            <div class="footer-left">
                Copyright &copy; 2018 <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad Nauval Azhar</a>
            </div>
            <div class="footer-right">
                2.3.0
            </div>
        </footer>
    </div>
</div>

<!-- General JS Scripts -->
{{--   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> --}}

<script src="{{ mix('js/app.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
{{-- <script src="../assets/js/stisla.js"></script> --}}

<!-- JS Libraies -->

<!-- Page Specific JS File -->

<!-- Template JS File -->
{{-- <script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script> --}}

<script src="{{ mix('js/all.js') }}"></script>

</body>
</html>
