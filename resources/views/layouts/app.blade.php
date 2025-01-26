<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Batil_e_Sahig</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Adjust dropdown position */
        .dropdown-menu.show {
            top: 100%;
            left: 0;
            right: auto;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body style="background-color: #eb441340;">
    <div id="app">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    Batil_e_Sahig
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportContent" aria-controls="navbarSupportContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('user.index') }}">Profile</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Search Form with AJAX Suggestions -->
                    <form class="d-flex position-relative" action="{{ route('user.search') }}" method="GET">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query" id="search-input">
                        <div id="search-results" class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="search-input">
                            <!-- Suggestions will be dynamically added here via AJAX -->
                        </div>
                        <button class="btn btn-outline-danger" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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

        <!-- Main Content -->
        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Your custom JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- AJAX Script for Search Suggestions -->
    <script>
        $(document).ready(function() {
            $('#search-input').keyup(function() {
                var query = $(this).val();
                if(query.length > 1) {
                    $.ajax({
                        url: '{{ route('user.search') }}',
                        type: 'GET',
                        data: { query: query },
                        success: function(response) {
                            var users = response;
                            var resultsDropdown = $('#search-results');
                            resultsDropdown.empty();

                            if(users.length > 0) {
                                users.forEach(function(user) {
                                    var profileUrl = '{{ route('user.show', ':id') }}'.replace(':id', user.id);
                                    resultsDropdown.append('<a href="' + profileUrl + '" class="dropdown-item">' + user.name + '</a>');
                                });
                                resultsDropdown.addClass('show');
                            } else {
                                resultsDropdown.removeClass('show');
                            }
                        }
                    });
                } else {
                    $('#search-results').removeClass('show').empty();
                }
            });

            // Hide dropdown on click outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.dropdown-menu').length) {
                    $('#search-results').removeClass('show').empty();
                }
            });
        });
    </script>

</body>
</html>
