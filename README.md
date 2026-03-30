# Filament Spatie Permissions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/madbox-99/filament-spatie-permissions.svg?style=flat-square)](https://packagist.org/packages/madbox-99/filament-spatie-permissions)
[![Total Downloads](https://img.shields.io/packagist/dt/madbox-99/filament-spatie-permissions.svg?style=flat-square)](https://packagist.org/packages/madbox-99/filament-spatie-permissions)
[![License](https://img.shields.io/packagist/l/madbox-99/filament-spatie-permissions.svg?style=flat-square)](LICENSE)

A **Filament v5** plugin for managing [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) roles and permissions — with enum-driven sync, a permission checkbox matrix, and an overview dashboard.

## Features

- **Role Resource** — Full CRUD with a searchable, bulk-toggleable permission checkbox matrix
- **Permission Resource** — Filterable list with role badges
- **Manage Permissions Page** — Overview dashboard showing all roles, their permissions, and totals
- **Enum-driven Sync** — Define roles & permissions as PHP enums, sync to database with one click or artisan command
- **Relation Managers** — Drop-in `RolesRelationManager` and `PermissionsRelationManager` for your User resource
- **Configurable Access** — Gate closure or automatic first-role detection
- **Multi-tenancy Ready** — Resources are excluded from tenant scoping (roles/permissions are global)
- **Translations** — Ships with English and Hungarian, fully publishable
- **Publishable** — Config, views, and translations can all be published and customized

## Requirements

- PHP 8.3+
- Laravel 11+
- Filament 4.0+ / 5.0+
- Spatie Laravel Permission 6.0+

## Installation

```bash
composer require madbox-99/filament-spatie-permissions
```

## Setup

### 1. Register the plugin in your Panel Provider

```php
use MadBox\FilamentSpatiePermissions\FilamentSpatiePermissionsPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(
            FilamentSpatiePermissionsPlugin::make()
                ->permissionEnum(\App\Enums\Permission::class)
                ->roleEnum(\App\Enums\Role::class)
                ->navigationGroup('System')
                ->canAccessUsing(fn ($user) => $user?->hasRole('Admin'))
        );
}
```

### 2. Implement the contracts on your enums

Your **Permission** enum must be a string-backed enum implementing `PermissionEnum`:

```php
use MadBox\FilamentSpatiePermissions\Contracts\PermissionEnum;

enum Permission: string implements PermissionEnum
{
    case ViewAnyUser = 'view_any_user';
    case ViewUser = 'view_user';
    case CreateUser = 'create_user';
    // ...

    public static function values(): array
    {
        return array_map(fn (self $p) => $p->value, self::cases());
    }
}
```

Your **Role** enum must implement `RoleEnum` with a `permissions()` method:

```php
use MadBox\FilamentSpatiePermissions\Contracts\RoleEnum;

enum Role: string implements RoleEnum
{
    case Admin = 'Admin';
    case Editor = 'Editor';
    case Viewer = 'Viewer';

    public function permissions(): array
    {
        return match ($this) {
            self::Admin => Permission::cases(),
            self::Editor => [
                Permission::ViewAnyUser,
                Permission::ViewUser,
                Permission::CreateUser,
            ],
            self::Viewer => [
                Permission::ViewAnyUser,
                Permission::ViewUser,
            ],
        };
    }
}
```

### 3. Sync permissions to the database

```bash
php artisan permissions:sync
```

Or use the **Sync Permissions** button on the Manage Permissions page in Filament.

## Relation Managers

Add the relation managers to your User resource:

```php
use MadBox\FilamentSpatiePermissions\RelationManagers\RolesRelationManager;
use MadBox\FilamentSpatiePermissions\RelationManagers\PermissionsRelationManager;

public static function getRelations(): array
{
    return [
        RolesRelationManager::class,
        PermissionsRelationManager::class,
    ];
}
```

These are only visible to users who pass the plugin's access check.

## Configuration

### Plugin API (fluent)

```php
FilamentSpatiePermissionsPlugin::make()
    ->permissionEnum(Permission::class)       // Permission enum class
    ->roleEnum(Role::class)                   // Role enum class
    ->canAccessUsing(fn ($user) => ...)       // Access gate closure
    ->navigationGroup('System')               // Sidebar group label
    ->navigationSort(2)                       // Sidebar sort order
    ->enableRoleResource(true)                // Toggle Role resource
    ->enablePermissionResource(true)          // Toggle Permission resource
    ->enableManagePermissionsPage(true)       // Toggle overview page
```

### Config file

Publish the config to customize defaults:

```bash
php artisan vendor:publish --tag=filament-spatie-permissions-config
```

### Views

```bash
php artisan vendor:publish --tag=filament-spatie-permissions-views
```

### Translations

```bash
php artisan vendor:publish --tag=filament-spatie-permissions-translations
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
