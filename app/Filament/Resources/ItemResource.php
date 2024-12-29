<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    public static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('item_code')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('item_name')
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('category_id')
                ->relationship('category', 'name')
                ->required(),
                Forms\Components\TextInput::make('satuan')
                ->label('Harga Satuan')
                ->numeric()
                ->required()->formatStateUsing(function ($state) {
                    return number_format($state, 0, ',', '.');
                }),
                Forms\Components\TextInput::make('stok_awal')
                ->label('Stock Awal')
                ->helperText('Nilai ini diperbarui secara otomatis berdasarkan transaksi stok.')
                ->default(0)
                ->disabled()
                ->numeric()
                ->required(),
                Forms\Components\TextInput::make('deskripsi')
                ->maxLength(255)
                ->required(),
                Forms\Components\FileUpload::make('photo')
                ->image()
                ->required(),
                Forms\Components\Select::make('supplier_id')
                ->relationship('supplier', 'name')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo'),
                Tables\Columns\TextColumn::make('item_code'),
                Tables\Columns\TextColumn::make('item_name')
                ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi'),
                Tables\Columns\TextColumn::make('stok_awal')
                ->label('Stock'),
                Tables\Columns\TextColumn::make('satuan')
                ->formatStateUsing(function ($state) {
                    return 'Rp ' . number_format($state, 0, ',', '.');
                }),
            ])
            ->filters([
                SelectFilter::make('category_id')
                ->label('category')
                ->relationship('category', 'name'),
                SelectFilter::make('supplier_id')
                ->label('supplier')
                ->relationship('supplier', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
