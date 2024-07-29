<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip</title>
    <style>
        h2 {
            margin-top: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        .order-details, .order-items, .shipping-label {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .order-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-items table, .order-items th, .order-items td {
            border: 1px solid #ddd;
        }
        .order-items th, .order-items td {
            padding: 8px;
            text-align: left;
        }
        .shipping-label img {
            max-width: 100%;
            height: auto;
            width: 40%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="order-details">
        <h2>Order #{{ $order['number'] }}</h2>
        <p><strong>Billing Address:</strong></p>
        <p>{{ $order['billing_address']['name'] }}<br>
            {{ $order['billing_address']['street'] }} {{ $order['billing_address']['housenumber'] }}<br>
            {{ $order['billing_address']['zipcode'] }} {{ $order['billing_address']['city'] }}<br>
            {{ $order['billing_address']['country'] }}</p>
        <p><strong>Email:</strong> {{ $order['billing_address']['email'] }}<br>
            <strong>Phone:</strong> {{ $order['billing_address']['phone'] }}</p>
    </div>

    <div class="order-items">
        <h2>Order Items</h2>
        <table>
            <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>EAN</th>
                <th>Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order['order_lines'] as $item)
                <tr>
                    <td>{{ $item['sku'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['ean'] }}</td>
                    <td>{{ $item['amount_ordered'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="shipping-label">
        <h2>Shipping Label</h2>
        <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents($label)) }}" alt="Shipping Label">
    </div>
</div>
</body>
</html>
