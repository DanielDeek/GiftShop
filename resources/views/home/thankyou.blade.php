<!DOCTYPE html>
<html>
<head>
  <title>Order Confirmation</title>
  @include('home.head')
  <style>
    .thank_you_page {
      margin: 60px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="hero_area">
    @include('home.header')
  </div>

  <div class="thank_you_page">
    <h2>Thank You for Your Order!</h2>
    <p>Order ID: {{ $order->id }}</p>
    <p>Total Amount: ${{ $order->total_amount }}</p>
    <p>We will process your order soon. Check your email for confirmation and further details.</p>
  </div>

  @include('home.footer')
</body>
</html>
