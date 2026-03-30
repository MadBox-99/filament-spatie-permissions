<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Resources\PermissionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use MadBox\FilamentSpatiePermissions\Resources\PermissionResource;

final class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;
}
