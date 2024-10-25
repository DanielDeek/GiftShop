{{-- @extends('home.index')
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
    .product-details {
        padding: 15px; /* Padding around the text */
        text-align: center; /* Center-align text */
    }

    /* Product title styling */
    .product-details h6 {
        margin: 10px 0; /* Space around the title */
        font-size: 1.1em; /* Slightly larger font for the title */
        font-weight: 600; /* Bolder text for title */
    }

    /* Price styling */
    .product-details p.price {
        margin: 5px 0; /* Space around the price */
        font-size: 1.2em; /* Slightly larger font for price */
        color: #e74c3c; /* Price color */
        font-weight: 700; /* Bold price */
    }

    /* Description styling */
    .product-details p.description {
        margin: 10px 0; /* Space around the description */
        font-size: 0.9em; /* Slightly smaller font for description */
        color: #777; /* Gray color for description */
    }

    /* Quantity styling */
    .product-details p.quantity {
        margin: 5px 0; /* Space around the quantity */
        font-size: 1em; /* Font size for quantity */
        color: #555; /* Text color */
    }

    /* Button styling */
    .product-details .btn {
        margin: 5px 5px 0 5px; /* Space around buttons */
        font-size: 0.9em; /* Font size for buttons */
        border-radius: 5px; /* Rounded corners for buttons */
        transition: background-color 0.3s ease, color 0.3s ease; /* Smooth color change */
    }

    /* Primary button hover effect */
    .product-details .btn-primary:hover {
        background-color: #0056b3; /* Darker blue on hover */
        color: #fff; /* White text */
    }

    /* Danger button hover effect */
    .product-details .btn-danger:hover {
        background-color: #c0392b; /* Darker red on hover */
        color: #fff; /* White text */
    }
</style>
</head>
<body>

<section class="all_products_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>All Products</h2>
        </div>
        <div class="row">
            @foreach ($allProducts as $product)
                <div class="col-md-3 mb-4">
                    <a href="{{ url('product_details', $product->id) }}" class="product-link">
                        <div class="box with-shadow">
                            <div class="img-box">
                                <img src="{{asset($product->image) }}" alt="{{ $product->title }}">
                            </div>
                    </a>
                            <div class="product-details">
                                <h6>{{ $product->title }}</h6>
                                <p class="price">USD {{ $product->price }}</p>
                                <p class="description">{{ $product->description }}</p>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1"> <!-- Assuming default quantity is 1 -->
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    
                </div>
            @endforeach
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
        $('form').on('submit', function(e){
            e.preventDefault();
    
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Product added to cart successfully!',
                        showConfirmButton: false,
                        timer: 1700
                    }).then(function() {
                        window.location.href = '{{ route('cart.view') }}'; 
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to add product to cart.'
                    });
                }
            });
        });
        
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> 
</body>
@endsection --}}
