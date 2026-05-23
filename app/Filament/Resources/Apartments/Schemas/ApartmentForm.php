<?php

namespace App\Filament\Resources\Apartments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ApartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('price_currency')
                    ->required()
                    ->default('EUR'),
                TextInput::make('status')
                    ->required()
                    ->default('for_rent'),
                TextInput::make('rooms')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('bathrooms')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('area')
                    ->numeric(),
                Toggle::make('is_published')
                    ->required(),
                Textarea::make('images')
                    ->columnSpanFull(),
            ]);
    }
}
