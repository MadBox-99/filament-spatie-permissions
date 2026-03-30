<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Resources\RoleResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use MadBox\FilamentSpatiePermissions\Resources\RoleResource;

final class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
