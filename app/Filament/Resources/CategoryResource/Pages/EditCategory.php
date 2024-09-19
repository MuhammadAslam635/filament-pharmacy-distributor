<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    use EditRecord\Concerns\HasWizard;

    protected static string $resource = CategoryResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Name')
                ->description('Give the category a clear and unique name')
                ->schema([
                    TextInput::make('name')
                        ->required(),
                ]),
            Step::make('Logo')
                ->description('Category Logo')
                ->schema([
                    FileUpload::make('image')
                        ->disk('public')
                        ->directory('catgeories')
                        ->visibility('public'),
                ]),
            Step::make('Status')
                ->description('Control visibility')
                ->schema([
                    Select::make('status')
                        ->options([
                            'true' => 'Active',
                            'false' => 'Inactive',
                        ])
                        ->native(false),
                ]),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
