<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Mail;


class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $total = 0;

        foreach ($this->record->items as $item) {
            $total += $item->price * $item->quantity;

            // Reduce product stock
            $product = Product::find($item->product_id);
            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }

        
        // Save total to the order
        $this->record->update([
            'total_amount' => $total,
        ]);
        
        $order = $this->record;

        // Send the email
        Mail::to($order->customer_email)->send(new OrderPlacedMail($order));
    }

}
