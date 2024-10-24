@extends('admin.index')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <style>
        .table_deg {
            text-align: center;
            margin: auto;
            border: 2px solid white;
            margin-top: 50px;
            width: 90%;
        }

        th,
        td {
            padding: 15px;
            font-size: 18px;
            color: skyblue;
            border: 1px solid white;
        }

        .btn-action {
            margin-right: 5px;
            display: inline-block;
        }

        h3 {
            color: skyblue;
            text-align: center;
        }

        .badge {
            font-size: 14px;
            font-weight: normal;
            padding: 8px 12px;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
            border-color: #ffc107;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border-color: #6c757d;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <h3 class="mb-4">Manage Orders</h3>
            <table class="table_deg table-bordered table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Change Status</th>
                    </tr>
                </thead>
                <tbody id="table_orders">
                    @foreach ($orders->reverse() as $order)
                        @foreach ($order->products as $index => $product)
                            <tr>
                                @if ($index === 0)
                                    <td rowspan="{{ $order->products->count() }}">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                                    <td rowspan="{{ $order->products->count() }}">{{ $order->user->name }}</td>
                                    <td rowspan="{{ $order->products->count() }}">{{ $order->user->address }}</td>
                                    <td rowspan="{{ $order->products->count() }}">{{ $order->user->phone }}</td>
                                @endif
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->pivot->price }}</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                @if ($index === 0)
                                    <td rowspan="{{ $order->products->count() }}">{{ $order->total_amount }}</td>
                                    <td rowspan="{{ $order->products->count() }}">
                                        @if ($order->status == 'in progress')
                                            <span class="badge bg-info text-white">{{ $order->status }}</span>
                                        @elseif($order->status == 'on the way')
                                            <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td rowspan="{{ $order->products->count() }}">
                                        @if ($order->status == 'in progress')
                                            <button class="btn btn-warning btn-action change-status-btn"
                                                data-order-id="{{ $order->id }}" data-status="on the way">On the Way</button>
                                        @elseif($order->status == 'on the way')
                                            <button class="btn btn-success btn-action change-status-btn"
                                                data-order-id="{{ $order->id }}" data-status="delivered">Delivered</button>
                                        @else
                                            <button class="btn btn-secondary btn-action" disabled>Order Delivered</button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.change-status-btn').on('click', function(e) {
                e.preventDefault();
                var orderId = $(this).data('order-id');
                var newStatus = $(this).data('status');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.change_status', ['id' => '__orderId__']) }}'.replace('__orderId__', orderId),
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function(response) {
                        toastr.success('Status updated successfully');
                        
                        // Reload the page to reflect the status change
                        location.reload();
                    },
                    error: function(error) {
                        toastr.error('Failed to update status. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
