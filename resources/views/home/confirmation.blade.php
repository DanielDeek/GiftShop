<!DOCTYPE html>
<html>
<head>
  <title>Order Confirmation</title>
  @include('home.head')
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .confirmation_page {
      padding: 60px;
      text-align: center;
      background-color: #fff;
      margin: 20px auto;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      width: 80%;
      max-width: 1000px;
    }
    .order_summary {
      margin: 20px 0;
      text-align: left;
    }
    .order_summary h3 {
      border-bottom: 2px solid #ddd;
      padding-bottom: 10px;
    }
    .product_item {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }
    .product_image {
      margin-right: 20px;
    }
    .product_image img {
      height: 80px;
      width: 80px;
      object-fit: cover;
      border-radius: 4px;
    }
    .product_details {
      flex: 1;
    }
    .product_price {
      font-weight: bold;
    }
    .total_amount {
      font-size: 1.2em;
      font-weight: bold;
      margin: 20px 0;
      text-align: right;
    }
    .confirm_button {
      display: inline-block;
      padding: 10px 20px;
      font-size: 1.2em;
      color: #fff;
      background-color: #28a745;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.3s;
    }
    .confirm_button:hover {
      background-color: #218838;
    }
    .instruction {
      margin: 20px 0;
      font-size: 1.1em;
    }
  </style>
</head>
<body>
  <div class="hero_area">
    @include('home.header')
  </div>

  <div class="confirmation_page">
    <h2>Order Confirmation</h2>
    <div class="instruction">
        Please review your order details and confirm your purchase.
    </div>
    <div class="order_summary">
        <h3>Order Summary:</h3>
        @foreach ($cart as $item)
        <div class="product_item">
            <div class="product_image">
                <img src="{{asset($item->product->image) }}" alt="{{ $item->product->title }}">
            </div>
            <div class="product_details">
                <div class="product_title">{{ $item->product->title }}</div>
                <div class="product_quantity" style="color: red">Remaining Quantity: {{ $item->product->quantity }}</div>
                <div class="product_price">${{ $item->product->price }} each</div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="total_amount">
        Total: ${{ $cart->sum(fn($item) => $item->product->price * $item->quantity) }}
    </div>
    <form action="{{ route('order.place') }}" method="POST">
        @csrf
        <input type="hidden" name="total" value="{{ $cart->sum(fn($item) => $item->product->price * $item->quantity) }}">
        <button type="submit" class="confirm_button">Confirm Order</button>
    </form>
    @if ($cart->isEmpty())
    <p>Unfortunately, some items in your cart are no longer available due to insufficient stock. Please review your order.</p>
    @endif
</div>

  @include('home.footer')
</body>
</html>
