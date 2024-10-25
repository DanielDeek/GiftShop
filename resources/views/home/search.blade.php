@extends('home.index')

@section('content')
    <style>
        /* Remove underline from links and preserve color */
        .product-link {
        text-decoration: none;
        color: inherit;
    }

    /* Card styling with smooth transition */
    .box {
        transition: box-shadow 0.3s ease, border 0.3s ease; /* Smooth transition for box-shadow and border */
        border: 1px solid #ddd; /* Initial border */
        border-radius: 10px; /* Rounded corners for the card */
        overflow: hidden; /* Ensures the image and other contents don't spill out */
        height: 100%; /* Set height to 100% */
        cursor: pointer; /* Change cursor to pointer to indicate clickability */
    }

    /* Hover effect for the card */
    .box:hover {
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 1); /* Subtle shadow on hover */
    }

    /* Shadow effect for cards by default */
    .with-shadow  {
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 1); /* Slight shadow */
    }

    /* Image styling */
    .img-box {
        height: 200px; /* Set a fixed height for the image box */
    }

    .img-box img {
        width: 100%; /* Ensures the image covers the entire width of its container */
        height: 100%; /* Ensures the image covers the entire height of its container */
        object-fit: cover; /* Maintain aspect ratio */
        transition: transform 0.3s ease; /* Smooth zoom effect */
    }

    /* Image zoom effect on hover */
    .img-box:hover img {
        transform: scale(1.05); /* Slight zoom on hover */
    }
        /* Product details styling */
        .detail-box {
            padding: 15px; /* Padding around the text */
            text-align: center; /* Center-align text */
        }

        /* Product title styling */
        .detail-box h6 {
            margin: 10px 0; /* Space around the title */
            font-size: 1.1em; /* Slightly larger font for the title */
            font-weight: 600; /* Bolder text for title */
        }

        /* Price styling */
        .detail-box h6 span {
            margin: 5px 0; /* Space around the price */
            font-size: 1.2em; /* Slightly larger font for price */
            color: #e74c3c; /* Price color */
            font-weight: 700; /* Bold price */
        }

        /* Button styling */
        .detail-box .btn {
            margin: 5px 5px 0 5px; /* Space around buttons */
            font-size: 0.9em; /* Font size for buttons */
            border-radius: 5px; /* Rounded corners for buttons */
            transition: background-color 0.3s ease, color 0.3s ease; /* Smooth color change */
        }

        /* Primary button hover effect */
        .detail-box .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            color: #fff; /* White text */
        }

        /* Danger button hover effect */
        .detail-box .btn-danger:hover {
            background-color: #c0392b; /* Darker red on hover */
            color: #fff; /* White text */
        }
    </style>

    <div class="container">
        <div class="heading_container heading_center">
            <h2>Search Results for "{{ $query }}"</h2>
        </div>

        <div class="row">
            @if ($productResults->isEmpty() && $categoryProductResults->isEmpty())
                <div class="col-md-12">
                    <p>No results found.</p>
                </div>
            @else
                <!-- Display products that match the search query directly -->
                @foreach ($productResults as $p)
                    <div class="col-md-3 mb-4">
                        <div class="box with-shadow" onclick="window.location.href = '{{ url('product_details', $p->id) }}';">
                            <div class="img-box" style="height: 300px">
                                <img src="{{asset($p->image)}}" alt="{{ $p->title }}">
                            </div>
                            <div class="detail-box">
                                <h6>{{ $p->title }}</h6>
                                <h6><span>USD ${{ $p->price }}</span></h6>
                                <div class="detail-box">
                                    <p style="color: red"><b>Available Quantity: {{ $p->quantity }} </b></p>
                                        <form action="{{ route('cart.add', $p->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="quantity">Quantity:</label>
                                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $p->quantity }}" required style="text-align: center">
                                            </div>
                                            <div class="detail-box">
                                                <p><b>{{ $p->description }}</b></p>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Display products from categories that match the search query -->
                @foreach ($categoryProductResults as $p)
                    <div class="col-md-3 mb-4">
                        <div class="box with-shadow" onclick="window.location.href = '{{ url('product_details', $p->id) }}';">
                            <div class="img-box" style="height: 300px">
                                <img src="{{asset($p->image)}}" alt="{{ $p->title }}">
                            </div>
                            <div class="detail-box">
                                <h6>{{ $p->title }}</h6>
                                <h6><span>USD ${{ $p->price }}</span></h6>
                                <p>Available Quantity: {{ $p->quantity }}</p>
                                <p class="description">{{ $p->description }}</p>
                                <div style="padding: 15px;">
                                    <form action="{{ route('cart.add', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="quantity">Quantity:</label>
                                            <input type="number" name="quantity" class="form-control" value="1" min="1" 
                                                    max="{{ $p->quantity }}" required style="text-align: center">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
