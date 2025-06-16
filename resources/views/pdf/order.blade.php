<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Report</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px; /* Slightly smaller base font for a cleaner look */
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #eeb448; /* A vibrant blue for the header */
            color: #fff;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
            margin: -20px -20px 20px -20px; /* Adjust to cover container padding */
        }

        .header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: normal;
        }

        .section-title {
            font-size: 18px;
            color: #eeb448;
            border-bottom: 2px solid #eeb448;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .customer-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .customer-info strong {
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.03);
            border-radius: 5px;
            overflow: hidden; /* Ensures border-radius applies to table */
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
            color: #eeb448; /* Match header color */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        tbody tr:nth-child(even) {
            background-color: #fbfbfb; /* Light stripe for readability */
        }

        tfoot tr {
            background-color: #eeb448; /* Footer with primary color */
            color: #fff;
            font-size: 16px;
            font-weight: bold;
        }

        tfoot th, tfoot td {
            border: 1px solid #eeb448; /* Match footer background */
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Order Report</h2>
        </div>

        <div class="section-title">Customer Details</div>
        <div class="customer-info">
            <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>

        <div class="section-title">Order Items</div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Total</th>
                    <th>₹{{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>