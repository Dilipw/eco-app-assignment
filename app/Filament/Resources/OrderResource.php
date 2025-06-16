<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('customer_name')
                ->required()
                ->maxLength(255),

            TextInput::make('customer_email')
                ->email()
                ->required()
                ->maxLength(255),

            Repeater::make('items')
                ->relationship('items')
                ->label('Order Items')
                ->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->options(Product::all()->pluck('name', 'id'))
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $product = Product::find($state);
                            $set('price', $product?->price ?? 0);
                            $set('max_stock', $product?->stock ?? 0);
                            $set('quantity', null); // reset qty on product change
                        }),

                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->rules([
                            function (callable $get, callable $set) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $productId = $get('product_id');
                                    $quantity = $value;
                                    $itemId = $get('id'); // for checking in edit mode

                                    if (!$productId || !$quantity) return;

                                    $product = Product::find($productId);
                                    if (!$product) {
                                        $fail("Selected product not found.");
                                        return;
                                    }

                                    $availableStock = $product->stock;

                                    // Adjust stock if editing an existing order item
                                    if ($itemId) {
                                        $existingItem = \App\Models\OrderItem::find($itemId);
                                        if ($existingItem && $existingItem->product_id == $productId) {
                                            $availableStock += $existingItem->quantity;
                                        }
                                    }

                                    if ($availableStock <= 0) {
                                        $fail("Product is out of stock.");
                                    } elseif ($quantity > $availableStock) {
                                        $fail("Only {$availableStock} items available in stock.");
                                    }
                                };
                            }
                        ]),

                    TextInput::make('price')
                        ->label('Unit Price')
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                ])
                ->columns(3)
                ->createItemButtonLabel('Add Product')
                ->required(),

            Placeholder::make('total_amount')
                ->label('Total Amount')
                ->content(function ($get) {
                    $total = 0;
                    foreach ($get('items') as $item) {
                        $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                    }
                    return '₹' . number_format($total, 2);
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')->searchable(),
                Tables\Columns\TextColumn::make('customer_email')->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total (₹)')
                    ->money('INR', true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y, h:i A')
                    ->label('Created')
                    ->timezone('Asia/Kolkata'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(), // No Edit
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
