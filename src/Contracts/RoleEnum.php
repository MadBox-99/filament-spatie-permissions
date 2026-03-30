<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Contracts;

interface RoleEnum
{
    /**
     * Get all role enum cases.
     *
     * @return array<self>
     */
    public static function cases(): array;

    /**
     * Get permissions for this role.
     *
     * @return array<PermissionEnum>
     */
    public function permissions(): array;
}
