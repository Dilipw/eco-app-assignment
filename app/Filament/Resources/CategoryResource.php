<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Set;
use Illuminate\Support\Str;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
            TextInput::make('slug')
                ->helperText('Optional; auto-generated if left blank'),
            Textarea::make('description')
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y, h:i A')
                    ->label('Created')
                    ->timezone('Asia/Kolkata'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View') // Optional: Rename button
                    ->modalHeading('Category Details')
                    ->modalSubheading(fn($record) => 'Viewing details for ' . $record->name)
                    ->modalWidth('md') // Optional: sm, md, lg, xl, 2xl, full
                    ->form([
                        TextInput::make('name')->disabled(),
                        TextInput::make('slug')->disabled(),
                        Textarea::make('description')->disabled()->rows(3),
                    ]),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
