<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giftos - Your Gift Shop</title>
    <style>
        /* Custom CSS styles can go here */
        .nav_search-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
        }

        .nav_search-btn i {
            font-size: 18px;
        }

        .navbar-nav .nav-link {
            color: #555;
            /* Default link color */
        }

        .navbar-nav .nav-link:hover {
            color: #007bff;
            /* Hover link color */
        }

        .navbar-nav .nav-item.active .nav-link {
            color: #007bff;
            /* Active link color */
        }

        /* Center the central area */
        .central-area {
            display: flex;
            align-items: center;
        }

        .user_option .dropdown-menu {
            left: auto;
            /* Align dropdown to the right side */
            right: 0;
        }
    </style>
</head>

<body>

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giftos - Your Gift Shop</title>
    <style>
        /* Custom CSS styles can go here */
        .nav_search-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
        }

        .nav_search-btn i {
            font-size: 18px;
        }

        .navbar-nav .nav-link {
            color: #555;
            /* Default link color */
        }

        .navbar-nav .nav-link:hover {
            color: #007bff;
            /* Hover link color */
        }

        .navbar-nav .nav-item.active .nav-link {
            color: #007bff;
            /* Active link color */
        }

        /* Center the central area */
        .central-area {
            display: flex;
            align-items: center;
        }

        .user_option .dropdown-menu {
            left: auto;
            /* Align dropdown to the right side */
            right: 0;
        }
    </style>
</head>

<body>

    <header class="header_section">
        <div align="center"> <a class="navbar-brand">
                <span>TechShop</span>
            </a></div>
        <nav class="navbar navbar-expand-lg custom_nav-container">
    
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Shop
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @foreach ($categories as $c)
                                <li>
                                    <a class="dropdown-item" href="{{ route('category.products', $c->id) }}">
                                        {{ $c->category_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('contact') }}">Contact Us</a>
                    </li>
                </ul>
    
                <!-- Central area for search and user options -->
                <div class="central-area ml-auto">
                    <form id="searchForm" class="form-inline" action="/search" method="GET">
                        <input id="searchInput" class="form-control mr-sm-2" type="search" name="q"
                            placeholder="Search" aria-label="Search" required>
                        <button id="searchButton" class="btn nav_search-btn" type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
    
                    <div class="user_option dropdown ml-3">
                        @if (Route::has('login'))
                            @auth
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user" aria-hidden="true"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a>
                                    <a class="dropdown-item" href="{{ route('cart.view') }}">
                                        My Cart <span class="badge badge-pill badge-primary">{{ $count }}</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item m-0 p-0">
                                        @csrf
                                        <button type="submit" class="btn btn-link w-100 text-left">Logout</button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ url('/login') }}" class="ml-2">
                                    <i class="fas fa-user-circle" aria-hidden="true"></i> Login
                                </a>
                                <a href="{{ url('/register') }}" class="ml-2">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Register
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap and JavaScript files -->
    {{-- <script src="{{asset('jquery-3.7.1.js')}}"></script> --}}

</body>

</html>

