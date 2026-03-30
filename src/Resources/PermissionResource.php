<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use MadBox\FilamentSpatiePermissions\FilamentSpatiePermissionsPlugin;
use MadBox\FilamentSpatiePermissions\Resources\PermissionResource\Pages\ListPermissions;
use Spatie\Permission\Models\Permission;

final class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return app(FilamentSpatiePermissionsPlugin::class)->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-spatie-permissions::permissions.permissions');
    }

    public static function getModelLabel(): string
    {
        return __('filament-spatie-permissions::permissions.permission');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-spatie-permissions::permissions.permissions');
    }

    public static function canAccess(): bool
    {
        return app(FilamentSpatiePermissionsPlugin::class)->canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-spatie-permissions::permissions.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('guard_name')
                    ->label(__('filament-spatie-permissions::permissions.guard_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label(__('filament-spatie-permissions::permissions.roles'))
                    ->badge()
                    ->separator(','),

                TextColumn::make('created_at')
                    ->label(__('filament-spatie-permissions::permissions.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('filament-spatie-permissions::permissions.role'))
                    ->relationship('roles', 'name'),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
        ];
    }
}
