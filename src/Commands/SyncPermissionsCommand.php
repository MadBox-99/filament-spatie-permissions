<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use MadBox\FilamentSpatiePermissions\FilamentSpatiePermissionsPlugin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

#[Signature('permissions:sync')]
#[Description('Sync all permissions and roles from enums to the database')]
final class SyncPermissionsCommand extends Command
{
    public function handle(): int
    {
        $plugin = app(FilamentSpatiePermissionsPlugin::class);
        $permissionEnum = $plugin->getPermissionEnum();
        $roleEnum = $plugin->getRoleEnum();

        if ($permissionEnum === null || $roleEnum === null) {
            $this->components->error('Permission or Role enum class is not configured.');
            $this->components->info('Set them in config/filament-spatie-permissions.php or via the plugin configuration.');

            return self::FAILURE;
        }

        $this->components->info('Syncing permissions and roles...');

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionCount = 0;

        foreach ($permissionEnum::cases() as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
            $permissionCount++;
        }

        $this->components->twoColumnDetail('Permissions synced', (string) $permissionCount);

        foreach ($roleEnum::cases() as $roleCase) {
            $role = Role::query()->firstOrCreate(['name' => $roleCase]);
            $permissions = $roleCase->permissions();
            $role->syncPermissions($permissions);
            $this->components->twoColumnDetail("Role [{$roleCase->value}]", count($permissions).' permissions');
        }

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->components->info('Done!');

        return self::SUCCESS;
    }
}
