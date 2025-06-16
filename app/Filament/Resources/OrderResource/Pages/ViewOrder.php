<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;

class ViewOrder extends Page
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.resources.order-resource.pages.view-order';

    public Order $record;

    public function mount(Order $record): void
    {
        $this->record = $record;
    }

    public function getViewData(): array
    {
        return [
            'order' => $this->record,
        ];
    }
}
