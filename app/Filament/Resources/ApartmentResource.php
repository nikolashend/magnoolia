<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApartmentResource\Pages;
use App\Models\Apartment;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class ApartmentResource extends Resource
{
    protected static ?string $model = Apartment::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Info')->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->rows(4)->columnSpanFull(),
                Forms\Components\TextInput::make('address')->maxLength(255),
            ])->columns(2),

            Forms\Components\Section::make('Details')->schema([
                Forms\Components\TextInput::make('price')->numeric()->prefix('€'),
                Forms\Components\TextInput::make('price_currency')->default('EUR')->maxLength(10),
                Forms\Components\Select::make('status')
                    ->options([
                        'for_sale' => 'For Sale',
                        'for_rent' => 'For Rent',
                        'sold'     => 'Sold',
                        'rented'   => 'Rented',
                    ])->required(),
                Forms\Components\TextInput::make('rooms')->numeric(),
                Forms\Components\TextInput::make('bathrooms')->numeric(),
                Forms\Components\TextInput::make('area')->numeric()->suffix('m²'),
                Forms\Components\Toggle::make('is_published')->default(true),
            ])->columns(3),

            Forms\Components\Section::make('Images')->schema([
                Forms\Components\Repeater::make('images')
                    ->schema([
                        Forms\Components\TextInput::make('url')->label('Image URL')->required(),
                    ])
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match($state) {
                        'for_sale' => 'info',
                        'for_rent' => 'warning',
                        'sold'     => 'danger',
                        'rented'   => 'success',
                        default    => 'gray',
                    }),
                Tables\Columns\TextColumn::make('price')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('rooms'),
                Tables\Columns\TextColumn::make('area')->suffix(' m²'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'for_sale' => 'For Sale',
                    'for_rent' => 'For Rent',
                    'sold'     => 'Sold',
                    'rented'   => 'Rented',
                ]),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make(),
            ])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApartments::route('/'),
            'create' => Pages\CreateApartment::route('/create'),
            'edit'   => Pages\EditApartment::route('/{record}/edit'),
        ];
    }
}
