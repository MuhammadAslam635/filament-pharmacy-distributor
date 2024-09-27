<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use App\Models\Supplier;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateSupplier extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = SupplierResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Name')
                ->description('Give the supplier a clear and unique name, company, etc')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')

                        ->required()
                        ->unique(Supplier::class, 'slug', fn ($record) => $record),
                    TextInput::make('shop')
                        ->label('Company')
                        ->required()
                        ->columnSpanFull(),
                ]),
            Step::make('Address')
                ->description('Add Supplier address detail and Status')
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
                        ->postfix('days')
                ]),
        ];
    }
}
