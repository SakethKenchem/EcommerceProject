<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\Pages\CreateOrder;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'debit_card' => 'Debit Card',
                                'mpesa' => 'M-Pesa',
                                'credit_card' => 'Credit Card',
                                'paypal' => 'PayPal',
                                'stripe' => 'Stripe',
                            ])
                            ->required(),
                        
                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required(),
                        
                        ToggleButtons::make('status')
                            ->label('Status')
                            ->inline()
                            ->default('new')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'canceled' => 'Canceled',
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'primary',
                                'shipped' => Color::Indigo,
                                'delivered' => 'success',
                                'canceled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-circle',
                                'canceled' => 'heroicon-m-x-circle',
                            ])
                            ->default('new')
                            ->required(),

                        Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'usd' => 'USD',
                                'inr' => 'INR',
                                'kes' => 'KES',
                                'eur' => 'EUR',
                                'gbp' => 'GBP',
                                'cad' => 'CAD',
                                'aud' => 'AUD',
                            ])
                            ->default('usd')  // Set a default value to avoid null
                            ->required(),
                        
                        Select::make('shipping_method')
                            ->label('Shipping Method')
                            ->options([
                                'fedex' => 'FedEx',
                                'dhl' => 'DHL',
                                'ups' => 'UPS',
                                'usps' => 'USPS',
                            ])
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, $get) {
                                        $product = Product::find($state);
                                        $unitAmount = $product ? $product->price ?? 0 : 0;
                                        $quantity = $get('quantity') ?? 1;
                                        $set('unit_amount', $unitAmount);
                                        $set('total_amount', $unitAmount * $quantity);
                                    }),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->type('number')
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, $get) {
                                        $unitAmount = $get('unit_amount');
                                        $set('total_amount', $unitAmount * $state);
                                    }),
                                TextInput::make('unit_amount')
                                    ->label('Unit Amount')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->columnSpan(3),
                                TextInput::make('total_amount')
                                    ->label('Total Amount')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->columnSpan(3),
                            ])->columns(12),
                        
                        Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->columnSpanFull()
                            ->content(function(Get $get, Set $set){
                                $total = 0;
                                $currency = $get('currency') ?? 'usd';  // Ensure currency is always set
                            
                                if(!$repeaters = $get('items')){
                                    return Number::currency($total, $currency);
                                }
                                foreach($repeaters as $key => $repeater){
                                    $total += $get("items.{$key}.total_amount");
                                }
                                $set('grand_total', $total);
                                return Number::currency($total, $currency);
                            }),

                        Hidden::make('grand_total')
                            ->default(0),
                    ])
                    
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Customer')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('payment_method')->label('Payment Method')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('payment_status')->label('Payment Status')->sortable()->searchable(),
                //grand total with currency found in database
                Tables\Columns\TextColumn::make('grand_total')->label('Grand Total')->sortable()->searchable(),
                //currency
                Tables\Columns\TextColumn::make('currency')->label('Currency')->sortable()->searchable(),
                Tables\Columns\SelectColumn::make('status')->label('Status')
                //incresed the width of the column to show full text
                ->options([
                    'new' => 'New',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'canceled' => 'Canceled',
                ])
                ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('currency')->label('Currency')->sortable()->searchable(),
                Tables\Columns\SelectColumn::make('shipping_method')->label('Shipping Method')
                ->options([
                    'fedex' => 'FedEx',
                    'dhl' => 'DHL',
                    'ups' => 'UPS',
                    'usps' => 'USPS',
                ])
                ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')
                ->toggleable(isToggledHiddenByDefault: true)
                ->dateTime()->sortable(),
            ])
            ->filters([
                //

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getNavigationBadge() > 10 ? 'success' : 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}