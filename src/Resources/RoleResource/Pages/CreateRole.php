<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Resources\RoleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use MadBox\FilamentSpatiePermissions\Resources\RoleResource;
use Spatie\Permission\PermissionRegistrar;

final class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function afterCreate(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
