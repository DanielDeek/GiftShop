@extends('home.index')

@section('content')
@include('home.slider')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Remove underline from links and preserve color */
    .product-link {
        text-decoration: none;
        color: inherit;
    }

    /* Card styling with smooth transition */
    .box {
        transition: box-shadow 0.3s ease, border 0.3s ease;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        height: 85%;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Hover effect for the card */
    .box:hover {
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 1);
    }

    /* Shadow effect for cards by default */
    .with-shadow  {
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 1);
    }

    /* Image styling */
    .img-box {
        height: 200px;
    }

    .img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    /* Image zoom effect on hover */
    .img-box:hover img {
        transform: scale(1.05);
    }

    /* Product details styling */
    .product-details {
        padding: 15px;
        text-align: center;
    }

    /* Product title styling */
    .product-details h6 {
        margin: 10px 0;
        font-size: 1.1em;
        font-weight: 600;
    }

    /* Price styling */
    .product-details p.price {
        margin: 5px 0;
        font-size: 1.2em;
        color: #e74c3c;
        font-weight: 700;
    }

    /* Description styling */
    .product-details p.description {
        margin: 10px 0;
        font-size: 0.9em;
        color: #777;
    }

    /* Quantity styling */
    .product-details p.quantity {
        margin: 5px 0;
        font-size: 1em;
        color: #555;
    }

    /* Button styling */
    .product-details .btn {
        margin: 5px 5px 0 5px;
        font-size: 0.9em;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Primary button hover effect */
    .product-details .btn-primary:hover {
        background-color: #0056b3;
        color: #fff;
    }

    /* Danger button hover effect */
    .product-details .btn-danger:hover {
        background-color: #c0392b;
        color: #fff;
    }

    /* Flex container for product cards */
    .product-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
    }

    /* Flex item for each product box */
    .product-box {
        flex: 1 1 calc(25% - 20px); /* Adjust width and spacing */
        display: flex;
        justify-content: center;
    }
</style>
</head>
<section class="shop_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                @if(isset($query))
                    Search Results for "{{ $query }}"
                @else
                    Latest Products
                @endif
            </h2>
        </div>
        <div class="product-container">
            @if(isset($latestProducts) && $latestProducts->isNotEmpty())
                @foreach ($latestProducts as $p)
                    <div class="product-box">
                        <div class="box with-shadow">
                            <div class="img-box" onclick="window.location.href = '{{ route('product.details', $p->id) }}';">
                                <img src="{{ asset($p->image) }}" alt="">
                            </div>
                            <div class="product-details">
                                <h6>{{ $p->title }}</h6>
                                <p class="price">Price: USD {{ $p->price }}</p>
                                <p>Available Quantity: {{ $p->quantity }}</p>
                                <form action="{{ route('cart.add', $p->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $p->quantity }}" required style="text-align: center">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <p>No products found.</p>
                </div>
            @endif
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
                        text: response.message,
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

        // Prevent click event propagation on the quantity input
        $('input[name="quantity"]').on('click', function(e){
            e.stopPropagation();
        });
    });
</script>

@endsection
