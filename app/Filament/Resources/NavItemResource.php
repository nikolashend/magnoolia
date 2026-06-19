<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavItemResource\Pages;
use App\Models\NavItem;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class NavItemResource extends Resource
{
    protected static ?string $model = NavItem::class;
    protected static string|\BackedEnum|null $navigationIcon  = 'heroicon-o-bars-3';
    protected static string|\UnitEnum|null   $navigationGroup = 'Site Settings';
    protected static ?string $navigationLabel = 'Navigation Menu';
    protected static ?int    $navigationSort  = 1;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Labels (per language)')->schema([
                Forms\Components\TextInput::make('label.et')
                    ->label('Label (ET / Estonian)')
                    ->required(),
                Forms\Components\TextInput::make('label.ru')
                    ->label('Label (RU / Russian)'),
                Forms\Components\TextInput::make('label.en')
                    ->label('Label (EN / English)'),
            ])->columns(3),

            Section::make('Link')->schema([
                Forms\Components\TextInput::make('route_name')
                    ->label('Route name')
                    ->helperText('e.g. home, magnoolia.homes, magnoolia.contact')
                    ->placeholder('home'),
                Forms\Components\TextInput::make('url')
                    ->label('Manual URL')
                    ->helperText('Used only if route name is empty or resolves to nothing')
                    ->url()
                    ->placeholder('https://...'),
            ])->columns(2),

            Section::make('Visibility & Order')->schema([
                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active (visible in menu)')
                    ->default(true),
                Forms\Components\Toggle::make('open_blank')
                    ->label('Open in new tab')
                    ->default(false),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                Tables\Columns\TextColumn::make('label.et')
                    ->label('ET')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('label.ru')
                    ->label('RU'),
                Tables\Columns\TextColumn::make('label.en')
                    ->label('EN'),
                Tables\Columns\TextColumn::make('route_name')
                    ->label('Route')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('â€”')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->hidden(fn (NavItem $record) => $record->trashed()),
                Actions\Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->visible(fn (NavItem $record) => $record->trashed())
                    ->requiresConfirmation()
                    ->modalHeading('Restore menu item?')
                    ->modalDescription('The item will be made active again and reappear in the navigation.')
                    ->action(fn (NavItem $record) => $record->restore()),
                Actions\DeleteAction::make()
                    ->hidden(fn (NavItem $record) => $record->trashed())
                    ->label('Move to trash'),
                Actions\ForceDeleteAction::make()
                    ->visible(fn (NavItem $record) => $record->trashed())
                    ->label('Delete permanently')
                    ->modalDescription('This will permanently delete the menu item. This cannot be undone.'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->label('Move to trash'),
                    Actions\RestoreBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make()
                        ->label('Delete permanently'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNavItems::route('/'),
            'create' => Pages\CreateNavItem::route('/create'),
            'edit'   => Pages\EditNavItem::route('/{record}/edit'),
        ];
    }
}