<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Resources\RoleResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use MadBox\FilamentSpatiePermissions\Resources\RoleResource;
use Spatie\Permission\PermissionRegistrar;

final class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
