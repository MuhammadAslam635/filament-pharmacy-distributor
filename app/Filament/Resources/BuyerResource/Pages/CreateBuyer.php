<?php

namespace App\Filament\Resources\BuyerResource\Pages;

use App\Filament\Resources\BuyerResource;
use App\Models\Buyer;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBuyer extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = BuyerResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Name')
                ->description('Give the buyer a clear and unique name, shop')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')

                        ->required()
                        ->unique(Buyer::class, 'slug', fn ($record) => $record),
                    TextInput::make('shop')
                        ->required()
                        ->columnSpanFull(),
                ]),
            Step::make('Address')
                ->description('Add Buyer address detail and Status')
                ->schema([
                    MarkdownEditor::make('address')
                        ->nullable(),
                    Select::make('status')
                        ->options([
                            'approved' => 'Approved',
                            'pending' => 'Pending',
                            'block' => 'Block',
                        ]),
                ]),
            Step::make('History')
                ->description('Previous History Detail')
                ->schema([
                    TextInput::make('orders')
                        ->integer()
                        ->default(0),
                    TextInput::make('total')
                        ->numeric()
                        ->default(0),
                        TextInput::make('paid')
                        ->numeric()
                        ->default(0),
                        TextInput::make('payment_cycle')
                        ->numeric()
                        ->default(15)
                        ->postfix('days'),
                ]),
        ];
    }
}
