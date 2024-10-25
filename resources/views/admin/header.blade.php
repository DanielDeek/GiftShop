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
                        <a class="nav-link" href="{{route('admin.categories')}}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.products.index')}}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order') }}">Orders</a>
                    </li>
                </ul>

                <!-- Central area for search and user options -->
                <div class="central-area ml-auto">
                    <form id="searchForm" class="d-flex" action="{{ route('admin.products.search') }}"
                            method="GET">
                            <input id="searchInput" class="form-control me-2" type="search" name="q" placeholder="Search"
                                   aria-label="Search" required>
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
                                    <a class="dropdown-item" href="{{ route('admin.profile.show') }}">Profile</a>
                                    
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




