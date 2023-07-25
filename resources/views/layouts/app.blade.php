<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{-- config('app.name', 'Kickz') --}}Kickz</title>
    <link rel="icon" href="http://127.0.0.1:8000/storage/images/kickz.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css"
        integrity="sha512-PT0RvABaDhDQugEbpNMwgYBCnGCiTZMh9yOzUsJHDgl/dMhD9yjHAwoumnUk3JydV3QTcIkNDuN40CJxik5+WQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="js/addons/rating.js"></script>

    <style>
        .has-search .form-control {
            padding-left: 2.375rem;
        }

        .has-search .form-control-feedback {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white fixed-top shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="http://127.0.0.1:8000/storage/images/kickz.png" width="30" height="30"
                        class="d-inline-block align-top" alt="">
                    <b>Kickz</b>
                    {{-- {{ config('app.name', 'Laravel') }} --}}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        {{-- <form class="form-inline my-2 my-lg-0" action="{{ url('/api/search') }}" method="GET" id="search-form">
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input class="form-control" type="search" id="search-text" placeholder="Search..." name="term"
                                    style="margin-right:10px">
                            </div>
                        </form> --}}
                        <form class="form-inline my-2 my-lg-0" id="search-form">
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input class="form-control" type="search" id="search-text" placeholder="Search..."
                                    style="margin-right:10px">
                            </div>
                        </form>

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <a class="nav-link" href="{{ url('/shoppingcart/' . Auth::user()->id) }}">Cart <span
                                    class="badge badge-dark"
                                    style="background-color:#212121">{{ Auth::user()->cart->count() }}</span></a>

                            @if (Auth::user()->role === 'administrator')
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        CRUD
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('/product') }}">Products</a>
                                        <a class="dropdown-item" href="{{ url('/shipment') }}">Shipments</a>
                                        <a class="dropdown-item" href="{{ url('/payment') }}">Payments</a>
                                        <a class="dropdown-item" href="{{ url('/brand') }}">Brands</a>
                                    </div>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (empty(Auth::user()->user_img))
                                        <img src="http://127.0.0.1:8000/storage/images/profile.jpg" width="25"
                                            height="25" class="d-inline-block align-top" style="border-radius: 50%;">
                                    @else
                                        <img src="{{ url(Auth::user()->user_img) }}" width="25" height="25"
                                            class="d-inline-block align-top" style="border-radius: 50%;">
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    @if (Auth::user()->role === 'administrator')
                                        <a class="dropdown-item" href="{{ url('/order') }}">Update Orders</a>
                                        <a class="dropdown-item" href="{{ url('/sales') }}">Sales</a>
                                        <a class="dropdown-item" href="{{ url('/profile') }}">Profile</a>
                                    @else
                                        <a class="dropdown-item" href="{{ url('/profile') }}">Profile</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="card-body" id="search-results">
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#search-text').on('input', function() {
                    var searchTerm = $(this).val();
                    if (searchTerm.trim() === '') {
                        $('#search-results').empty();
                    } else {
                        $.ajax({
                            url: '/api/search',
                            type: 'GET',
                            data: {
                                term: searchTerm
                            },
                            success: function(data) {
                                displaySearchResults(data);
                            },
                            error: function(error) {
                                console.error('Error:', error);
                            }
                        });
                    }
                });

                function displaySearchResults(data) {
                    $('#search-results').empty();
                    $('#search-results').append(
                        '<div class="text-center" style="margin-top:100px"><h1>Search</h1>There are ' + data
                        .searchResults.length + ' results.</div>');
                    data.searchResults.forEach(function(searchResult) {
                        var resultHtml =
                            '<div class="row">' +
                            '<div class="col-md-12">' +
                            '<h4 class="mb-1"><a href="' + searchResult.url + '">' + searchResult.title +
                            '</a></h4>' +
                            '<div class="font-13 text-success mb-3">' + searchResult.url + '</div>' +
                            '</div>' +
                            '</div><hr>';
                        $('#search-results').append(resultHtml);
                    });
                }
            });
        </script>
        <main class="py-4"><br><br>
            @yield('content')
        </main>
    </div>
    @include('layouts.scripts')
</body>
</html>
