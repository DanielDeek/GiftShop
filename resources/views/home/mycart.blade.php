<!DOCTYPE html>
<html>
<head>
  @include('home.head')
  <style>
    .div_deg {
      display: flex;
      flex-direction: column; /* Arrange items vertically */
      align-items: center;
      margin: 60px;
      width: 90%; /* Reduce width slightly to prevent horizontal scrolling */
    }
    table {
      border: 2px solid black;
      text-align: center;
      width: 100%; /* Full width */
      margin-bottom: 20px; /* Space below the table */
      table-layout: fixed; /* Ensure the table layout is fixed to prevent content from stretching */
    }
    th, td {
      border: 2px solid black;
      text-align: center;
      padding: 10px;
      overflow: hidden; /* Hide any overflow text or content */
      white-space: nowrap; /* Prevent text from wrapping */
      text-overflow: ellipsis; /* Show ellipsis for overflowing text */
    }
    th {
      background-color: black;
      color: white;
    }
    .cart_value_row td {
      background-color: #f8f8f8; /* Light background for total row */
      font-weight: bold;
    }
    .cart_value {
      text-align: center;
      font-size: 1.5em; /* Increase font size */
      padding: 10px 0;
    }
    .place_order {
      text-align: center; /* Center align the button */
      margin-top: 20px; /* Space above the button */
      width: 100%; /* Full width */
    }
    .place_order button {
      padding: 10px 20px;
      font-size: 1.2em; /* Increase button font size */
      border: none;
      background-color: #4CAF50; /* Green background */
      color: white; /* White text */
      cursor: pointer;
      border-radius: 5px; /* Rounded corners */
      transition: background-color 0.3s ease; /* Smooth transition */
    }
    .place_order button:hover {
      background-color: #45a049; /* Darker green on hover */
    }
    img {
      height: 100px; /* Reduce image height */
      width: auto; /* Maintain aspect ratio */
    }
  </style>
</head>

<body>
  <div class="hero_area">
    @include('home.header')
  </div>

  <div class="div_deg">
    <table>
      <tr>
        <th>Product Title</th>
        <th>Unit Price</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th>Image</th>
        <th>Remove</th>
      </tr>
      @php
        $total = 0;
      @endphp
      @foreach ($cart as $item)
        <tr>
          <td>{{ $item->product->title }}</td>
          <td>${{ $item->product->price }}</td>
          <td>{{ $item->quantity }}</td>
          <td>${{ $item->product->price * $item->quantity }}</td>
          <td>
            <img src="{{ asset($item->product->image) }}" alt="Product Image">
          </td>
          <td>
            <a href="{{ route('cart.delete', $item->id) }}" class="btn btn-danger delete-cart">Remove</a>
          </td>
        </tr>
        @php
          $total += $item->product->price * $item->quantity;
        @endphp
      @endforeach
      <tr class="cart_value_row">
        <td colspan="5" class="cart_value">Total Cart Value</td>
        <td class="cart_value">USD {{ $total }}</td> 
      </tr>
    </table>

    <div class="place_order">
      <button onclick="window.location.href='{{ url('confirm_order') }}'">Place Order</button>
    </div>
  </div>

  @include('home.footer')

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
    // Add a click event listener to all elements with the class 'delete-cart'
    document.querySelectorAll('.delete-cart').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default action of the anchor tag
            
            // Show the SweetAlert confirmation dialog
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this product from the cart!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    // If the user confirms the deletion, proceed with the redirection
                    window.location.href = this.getAttribute('href');
                }
            });
        });
    });
  </script>
</body>
</html>
