<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getDetailsFormSchema())
                            ->columns(2),
                        Forms\Components\Section::make('Order items')
                            ->headerActions([
                                Action::make('reset')
                                    ->modalHeading('Are you sure?')
                                    ->modalDescription('All existing items will be removed from the order.')
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn (Forms\Set $set) => $set('orderItems', [])),
                            ])
                            ->footerActions([
                                Action::make('Update')
                                    ->action(fn (Forms\Set $set, Forms\Get $get) => $set('orderItems', $get('orderItems'))),                            ])
                            ->footerActionsAlignment(Alignment::End)
                            ->schema([
                                static::getItemsRepeater(),
                            ]),
                    ])->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),

                Repeater::make('transactions')
                    ->relationship()
                    ->schema([
                        select::make('status')
                            ->options([
                                'pending' => 'pending',
                                'paid' => 'paid',
                                'partial' => 'partial',
                            ]),
                        select::make('method')
                            ->label('Payment Method')
                            ->options([
                                'cod' => 'Cash On Delivery',
                                'online' => 'Online',
                                'jazzcash' => 'Jazzcash',
                            ]),
                        TextInput::make('user_id')
                            ->label('Order Booker')
                            ->readOnly()
                            ->default(Auth::user()->id),
                    ])->columnSpanFull(),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('buyer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cancel_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\Select::make('buyer_id')
                ->relationship('buyer', 'name')
                ->required(),
            Forms\Components\DatePicker::make('delivery_date'),
            Forms\Components\ToggleButtons::make('status')
                ->inline()
                ->options(OrderStatus::class)
                ->required(),
            TextInput::make('total')
                ->numeric()
                ->default(0)
                ->prefix('Rs')
                ->required(),
            TextInput::make('subtotal')
                ->numeric()
                ->default(0)
                ->prefix('Rs')
                ->required(),
            TextInput::make('tax')
                ->numeric()
                ->default(0)
                ->prefix('Rs')
                ->live(onBlur: true)
                ->required()
                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                    $subtotal = $get('subtotal');
                    $tax = $get('tax');
                    $total = $subtotal + $tax;
                    $set('total', $total);
                }),

        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('orderItems')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('price', Product::find($state)?->price ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 5,
                    ])
                    ->searchable(),

                Forms\Components\TextInput::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->live(onBlur: true)
                    ->default(1)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Unit Price')
                    ->dehydrated()
                    ->numeric()
                    ->live(onBlur: true)
                    ->required()
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])

            ->extraItemActions([
                Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['product_id']);

                        if (! $product) {
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->orderColumn('sort')
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ])
            ->required()
            ->afterStateUpdated(function (Get $get, Set $set, $old, $state) {
                $orderItems = $get('orderItems');
                $subtotal = 0;
                foreach ($orderItems as $item) {
                    $subtotal += $item['price'] * $item['qty'];
                }
                $set('subtotal', $subtotal);

                $tax = $get('tax');
                $total = $subtotal + $tax;
                $set('total', $total);
            });
    }
}
