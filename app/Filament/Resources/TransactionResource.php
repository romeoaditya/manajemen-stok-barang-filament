<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Item;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ViewField;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    public static function getNavigationBadge(): ?string
    {
        return (string) Transaction::where('is_paid', false)->count();
    }
    public static ?string $navigationGroup = 'Customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Customer Informations')
                ->schema([
                    Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('phone_number')
                    ->required()
                    ->prefix('+62')
                    ->maxLength(13),
                    Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                ]),
                Forms\Components\Fieldset::make('Transaction Informations')
                ->schema([
                    Forms\Components\Select::make('item_id')
                    ->label('Item yang dibeli')
                    ->relationship('item', 'item_name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set){
                        $item = Item::find($state);
                        $set('satuan', $item ? $item->satuan : 0);
                    }),
                    Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state,callable $get, callable $set){
                        $satuan = $get('satuan');
                        $subtotal = $satuan * $state;
                        $totalAmount = $subtotal;
                        $set('total_amount', $totalAmount);
                     }),
                    Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->prefix('IDR')
                    ->numeric()
                    ->readOnly()
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 0, ',', '.');
                    }),
                    Forms\Components\FileUpload::make('proof_payment')
                    ->image()
                    ->required(),
                    ToggleButtons::make('is_paid')
                            ->label('Apakah sudah membayar?')
                            ->required()
                            ->boolean()
                            ->grouped()
                            ->icons([
                                true => 'heroicon-o-pencil',
                                false => 'heroicon-o-clock'
                            ])
                ])
            ]);
    }
    
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('item.item_name'),
                Tables\Columns\IconColumn::make('is_paid')
                ->boolean()
                ->trueColor('success')
                ->falseColor('danger')
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->label('Terverifikasi')
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make()
                // ->form([
                //     Tabs::make('Transaction Details')
                //         ->tabs([
                //             Tab::make('Customer Information')
                //                 ->schema([
                //                     TextInput::make('name')
                //                         ->label('Name')
                //                         ->disabled(),
                //                     TextInput::make('phone_number')
                //                         ->label('Phone Number')
                //                         ->disabled(),
                //                     TextInput::make('address')
                //                         ->label('Address')
                //                         ->disabled(),
                //                 ]),
                            
                //             Tab::make('Transaction Information')
                //                 ->schema([
                //                     TextInput::make('item_name')
                //                         ->label('Item')
                //                         ->formatStateUsing(fn ($record) => $record->item->item_name)
                //                         ->disabled(),
                //                     TextInput::make('quantity')
                //                         ->label('Quantity')
                //                         ->disabled(),
                //                     TextInput::make('total_amount')
                //                         ->label('Total Amount')
                //                         ->prefix('IDR')
                //                         ->disabled(),
                //                     TextInput::make('is_paid')
                //                         ->label('Payment Status')
                //                         ->formatStateUsing(fn ($state) => $state ? 'Sukses' : 'Belum Dibayar')
                //                         ->disabled(),
                //                 ]),

                //             Tab::make('Payment Proof')
                //                 ->schema([
                //                     Forms\Components\FileUpload::make('proof_payment')
                //                         ->image()
                //                         ->disabled()
                //                         ->visible(fn ($record) => $record->proof_payment !== null),
                //                 ]),
                //         ]),
                // ])
                // ->extraModalFooterActions([
                //     Tables\Actions\Action::make('print')
                //         ->label('Print')
                //         ->color('success')
                //         ->icon('heroicon-o-printer')
                //         ->openUrlInNewTab(),
                // ]),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('aprrove')
                ->label('✅ Approve')
                ->action(function(Transaction $record){
                    $record->is_paid = true;
                    $record->save();

                    //trigger custom notification
                    Notification::make()
                    ->title('Payment Approved')
                    ->success()
                    ->body('The payment has been successfully approved')
                    ->send();
                })
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn(Transaction $record)=>!$record->is_paid),
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
    // Fungsi untuk menghitung total amount
    protected static function calculateTotalAmount($itemId, $quantity)
    {
        if (!$itemId || !$quantity) {
            return 0;
        }
    
        $item = \App\Models\Item::find($itemId);
    
        return $item ? $item->price * $quantity : 0;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
