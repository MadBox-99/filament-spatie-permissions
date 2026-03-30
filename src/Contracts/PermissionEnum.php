<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions\Contracts;

interface PermissionEnum
{
    /**
     * Get all permission enum cases.
     *
     * @return array<self>
     */
    public static function cases(): array;

    /**
     * Get all permission values as strings.
     *
     * @return array<string>
     */
    public static function values(): array;
}
