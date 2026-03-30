<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use MadBox\FilamentSpatiePermissions\Pages\ManagePermissions;
use MadBox\FilamentSpatiePermissions\Resources\PermissionResource;
use MadBox\FilamentSpatiePermissions\Resources\RoleResource;

final class FilamentSpatiePermissionsPlugin implements Plugin
{
    /** @var class-string|null */
    private ?string $permissionEnum = null;

    /** @var class-string|null */
    private ?string $roleEnum = null;

    private ?Closure $canAccessUsing = null;

    private ?string $navigationGroup = null;

    private int $navigationSort = 2;

    private string $navigationIcon = 'heroicon-o-shield-check';

    private bool $enableRoleResource = true;

    private bool $enablePermissionResource = true;

    private bool $enableManagePermissionsPage = true;

    public static function make(): static
    {
        return new self;
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-spatie-permissions';
    }

    public function register(Panel $panel): void
    {
        $resources = [];
        $pages = [];

        if ($this->enableRoleResource) {
            $resources[] = RoleResource::class;
        }

        if ($this->enablePermissionResource) {
            $resources[] = PermissionResource::class;
        }

        if ($this->enableManagePermissionsPage) {
            $pages[] = ManagePermissions::class;
        }

        $panel->resources($resources);
        $panel->pages($pages);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * @param  class-string  $enum
     */
    public function permissionEnum(string $enum): static
    {
        $this->permissionEnum = $enum;

        return $this;
    }

    /**
     * @param  class-string  $enum
     */
    public function roleEnum(string $enum): static
    {
        $this->roleEnum = $enum;

        return $this;
    }

    public function canAccessUsing(Closure $callback): static
    {
        $this->canAccessUsing = $callback;

        return $this;
    }

    public function navigationGroup(string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationSort(int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function navigationIcon(string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function enableRoleResource(bool $enable = true): static
    {
        $this->enableRoleResource = $enable;

        return $this;
    }

    public function enablePermissionResource(bool $enable = true): static
    {
        $this->enablePermissionResource = $enable;

        return $this;
    }

    public function enableManagePermissionsPage(bool $enable = true): static
    {
        $this->enableManagePermissionsPage = $enable;

        return $this;
    }

    /**
     * @return class-string|null
     */
    public function getPermissionEnum(): ?string
    {
        return $this->permissionEnum ?? config('filament-spatie-permissions.permission_enum');
    }

    /**
     * @return class-string|null
     */
    public function getRoleEnum(): ?string
    {
        return $this->roleEnum ?? config('filament-spatie-permissions.role_enum');
    }

    public function getCanAccessUsing(): ?Closure
    {
        return $this->canAccessUsing ?? config('filament-spatie-permissions.gate');
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup ?? config('filament-spatie-permissions.navigation.group');
    }

    public function getNavigationSort(): int
    {
        return $this->navigationSort;
    }

    public function getNavigationIcon(): string
    {
        return $this->navigationIcon;
    }

    public function canAccess(): bool
    {
        $callback = $this->getCanAccessUsing();

        if ($callback !== null) {
            return $callback(auth()->user());
        }

        // Default: check if user has the first role (typically Admin)
        $roleEnum = $this->getRoleEnum();

        if ($roleEnum !== null) {
            $cases = $roleEnum::cases();

            if (count($cases) > 0) {
                return auth()->user()?->hasRole($cases[0]) ?? false;
            }
        }

        return false;
    }
}
