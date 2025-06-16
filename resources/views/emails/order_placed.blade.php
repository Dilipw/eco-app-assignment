<!DOCTYPE html>
<html>
<body>
    <h2>Thank you for your order, {{ $order->customer_name }}!</h2>
    <p>We've received your order. Here are the details:</p>

    <ul>
        @foreach ($order->items as $item)
            <li>
                {{ $item->product->name }} — ₹{{ number_format($item->price, 2) }} × {{ $item->quantity }} = ₹{{ number_format($item->price * $item->quantity, 2) }}
            </li>
        @endforeach
    </ul>

    <p><strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>

    <p>We will contact you soon. Thank you!</p>
</body>
</html>
