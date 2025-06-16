<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Customer & Order Info --}}
        <x-filament::card>
            <h2 class="text-xl font-bold mb-4">Order Details</h2>
            <div class="flex justify-end mb-4">
                <a href="{{ route('orders.download', $order) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                    Download PDF
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Customer Email:</strong> {{ $order->customer_email }}</p>
                </div>
                <div>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </x-filament::card>

        {{-- Order Items --}}
        <x-filament::card>
            <h2 class="text-xl font-bold mb-4">Order Items</h2>

            <table class="w-full text-sm table-auto border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Product</th>
                        <th class="p-2 text-right">Quantity</th>
                        <th class="p-2 text-right">Unit Price</th>
                        <th class="p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-t">
                            <td class="p-2">{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="p-2 text-right">{{ $item->quantity }}</td>
                            <td class="p-2 text-right">₹{{ number_format($item->price, 2) }}</td>
                            <td class="p-2 text-right">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="3" class="p-2 text-right">Total</td>
                        <td class="p-2 text-right">
                            ₹{{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </x-filament::card>
    </div>
</x-filament-panels::page>
