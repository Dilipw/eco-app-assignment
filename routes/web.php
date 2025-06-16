<?php

use Illuminate\Support\Facades\Route;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/orders/{order}/download', function (Order $order) {
    $pdf = Pdf::loadView('pdf.order', ['order' => $order]);
    return $pdf->download('order-' . $order->id . '.pdf');
})->name('orders.download');

Route::get('/', function () {
    return view('welcome');
});
