<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use MadBox\FilamentSpatiePermissions\FilamentSpatiePermissionsPlugin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class ManagePermissions extends Page
{
    /** @var array<string, array{name: string, permissions: array<string>, total: int}> */
    public array $roles = [];

    public int $totalPermissions = 0;

    protected string $view = 'filament-spatie-permissions::pages.manage-permissions';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return app(FilamentSpatiePermissionsPlugin::class)->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-spatie-permissions::permissions.manage_permissions');
    }

    public static function canAccess(): bool
    {
        return app(FilamentSpatiePermissionsPlugin::class)->canAccess();
    }

    public function getTitle(): string
    {
        return __('filament-spatie-permissions::permissions.manage_permissions');
    }

    public function mount(): void
    {
        $this->loadRoles();
    }

    public function syncPermissions(): void
    {
        $plugin = app(FilamentSpatiePermissionsPlugin::class);
        $permissionEnum = $plugin->getPermissionEnum();
        $roleEnum = $plugin->getRoleEnum();

        if ($permissionEnum === null || $roleEnum === null) {
            Notification::make()
                ->danger()
                ->title(__('filament-spatie-permissions::permissions.sync_failed'))
                ->body(__('filament-spatie-permissions::permissions.enums_not_configured'))
                ->send();

            return;
        }

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($permissionEnum::cases() as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
        }

        foreach ($roleEnum::cases() as $roleCase) {
            $role = Role::query()->firstOrCreate(['name' => $roleCase]);
            $role->syncPermissions($roleCase->permissions());
        }

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->loadRoles();

        Notification::make()
            ->success()
            ->title(__('filament-spatie-permissions::permissions.sync_success_title'))
            ->body(__('filament-spatie-permissions::permissions.sync_success_body'))
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync')
                ->label(__('filament-spatie-permissions::permissions.sync_permissions'))
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading(__('filament-spatie-permissions::permissions.sync_permissions'))
                ->modalDescription(__('filament-spatie-permissions::permissions.sync_confirmation'))
                ->action(fn () => $this->syncPermissions()),
        ];
    }

    private function loadRoles(): void
    {
        $this->totalPermissions = Permission::count();

        $this->roles = Role::with('permissions')->get()->map(fn (Role $role): array => [
            'name' => $role->name,
            'permissions' => $role->permissions->pluck('name')->sort()->values()->all(),
            'total' => $role->permissions->count(),
        ])->keyBy('name')->all();
    }
}
