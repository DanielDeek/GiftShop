@extends('home.index')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        /* Custom styles can go here */
        .box {
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .image-container {
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
        }

        .product-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .detail-box {
            margin-bottom: 15px;
        }

        .detail-box h6 {
            margin: 5px 0;
            font-size: 1.2em;
            font-weight: 600;
            color: #333;
        }

        .detail-box h6 span {
            font-weight: 700;
            color: #e74c3c;
        }

        .detail-box p {
            margin: 5px 0;
            font-size: 1em;
            color: #666;
            line-height: 1.5;
        }

        .add-to-cart button {
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart button:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="container">
        <div class="heading_container heading_center">
            <h2>Product Details</h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="image-container">
                        <img src="{{ asset($product->image) }}" alt="Product Image">
                    </div>
                    <div class="product-details">
                        <div class="detail-box">
                            <h6>{{ $product->title }}</h6>
                        </div>
                        <div class="detail-box">
                            <h6> <span>USD ${{ $product->price }}</span></h6>
                        </div>
                        <div class="detail-box">
                            <h6>Category: {{ $category->category_name }}</h6>
                        </div>
                        <div class="detail-box">
                            <p style="color: red"><b>Available Quantity: {{ $product->quantity }} </b></p>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity }}" required style="text-align: center">
                                    </div>
                                    <div class="detail-box">
                                        <p><b>{{ $product->description }}</b></p>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </body>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
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
@endsection
