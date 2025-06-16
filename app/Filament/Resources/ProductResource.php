<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Select, Textarea, FileUpload};
use Filament\Tables\Columns\{TextColumn, ImageColumn};
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Grid;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductTableExport;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->searchable(),

            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->helperText('Optional; auto-generated'),

            Textarea::make('description')->rows(3),

            TextInput::make('price')
                ->numeric()
                ->required(),

            TextInput::make('stock')
                ->numeric()
                ->required()
                ->minValue(0),

            TextInput::make('sku')
                ->unique(ignoreRecord: true),

            FileUpload::make('image')
                ->image()
                ->directory('products')
                ->imageEditor(),
        ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular()->width(50)->height(50),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('price')->money('INR'),
                TextColumn::make('stock')->sortable(),
                TextColumn::make('created_at')->dateTime('d M Y'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->modalHeading('📦 Product Details')
                    ->modalSubheading(fn($record) => 'Viewing details for "' . $record->name . '"')
                    ->modalWidth('lg')
                    ->form(fn($record) => [
                        Group::make([
                            Section::make('Basic Info')
                                ->schema([
                                    Placeholder::make('image')
                                        ->label('')
                                        ->content(fn() => new HtmlString(
                                            '<img src="' . asset('storage/' . $record->image) . '" alt="' . $record->name . '" class="h-32 rounded-lg mx-auto">'
                                        ))
                                        ->extraAttributes(['class' => 'text-center'])
                                        ->columnSpanFull()
                                        ->disableLabel(),
                                    Grid::make(2)->schema([
                                        Placeholder::make('name')
                                            ->label('Product Name')
                                            ->content($record->name),
                                        Placeholder::make('slug')
                                            ->label('Slug')
                                            ->content($record->slug),
                                    ]),
                                ]),

                            Section::make('Details')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Placeholder::make('category')
                                            ->label('Category')
                                            ->content($record->category?->name ?? '-'),
                                        Placeholder::make('price')
                                            ->label('Price')
                                            ->content('₹' . number_format($record->price, 2)),
                                    ]),

                                    Placeholder::make('stock')
                                        ->label('Stock')
                                        ->content($record->stock . ' units'),

                                    Placeholder::make('description')
                                        ->label('Description')
                                        ->content($record->description ?? '—')
                                        ->columnSpanFull(),

                                    Placeholder::make('created_at')
                                        ->label('Created At')
                                        ->content($record->created_at->format('d M Y, h:i A')),
                                ]),
                        ]),
                    ]),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Action::make('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new ProductTableExport, 'products-table.xlsx');
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
