<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Filament\Resources\StockResource\RelationManagers;
use App\Models\Item;
use App\Models\Stock;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    public static ?string $navigationGroup = 'Management';

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Forms\Components\Select::make('item_id')
                ->relationship('item', 'item_name')
                ->searchable()
                ->preload()
                ->required(),
                Forms\Components\TextInput::make('quantity')
                ->minValue(1)
                ->helperText('Jumlah stok tidak boleh negatif atau nol.')
                ->numeric()
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('item.photo'),
                
                Tables\Columns\TextColumn::make('item.item_name')
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d-m-Y')),
            ])
            ->filters([
                SelectFilter::make('item_id')
                ->label('item')
                ->relationship('item', 'item_name'),
                SelectFilter::make('created_at')
                ->label('tanggal dibuat')
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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
