<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Resources;

use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use MadBox\FilamentSpatiePermissions\FilamentSpatiePermissionsPlugin;
use MadBox\FilamentSpatiePermissions\Resources\RoleResource\Pages\CreateRole;
use MadBox\FilamentSpatiePermissions\Resources\RoleResource\Pages\EditRole;
use MadBox\FilamentSpatiePermissions\Resources\RoleResource\Pages\ListRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return app(FilamentSpatiePermissionsPlugin::class)->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-spatie-permissions::permissions.roles');
    }

    public static function getModelLabel(): string
    {
        return __('filament-spatie-permissions::permissions.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-spatie-permissions::permissions.roles');
    }

    public static function canAccess(): bool
    {
        return app(FilamentSpatiePermissionsPlugin::class)->canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament-spatie-permissions::permissions.name'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('guard_name')
                    ->label(__('filament-spatie-permissions::permissions.guard_name'))
                    ->default(config('auth.defaults.guard'))
                    ->required()
                    ->maxLength(255),

                CheckboxList::make('permissions')
                    ->label(__('filament-spatie-permissions::permissions.permissions'))
                    ->relationship('permissions', 'name')
                    ->options(fn (): array => Permission::query()->pluck('name', 'id')->all())
                    ->columns(3)
                    ->searchable()
                    ->bulkToggleable(),
            ]);
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

                TextColumn::make('permissions_count')
                    ->label(__('filament-spatie-permissions::permissions.permissions_count'))
                    ->counts('permissions')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament-spatie-permissions::permissions.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('filament-spatie-permissions::permissions.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
