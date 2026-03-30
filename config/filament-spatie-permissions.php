<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Permission Enum Class
    |--------------------------------------------------------------------------
    |
    | The backed enum class that defines all permissions in your application.
    | This enum must be a string-backed enum.
    |
    */
    'permission_enum' => null,

    /*
    |--------------------------------------------------------------------------
    | Role Enum Class
    |--------------------------------------------------------------------------
    |
    | The backed enum class that defines all roles and their default
    | permissions. This enum must be a string-backed enum and each case
    | must have a permissions() method returning an array of Permission enums.
    |
    */
    'role_enum' => null,

    /*
    |--------------------------------------------------------------------------
    | Authorization Gate
    |--------------------------------------------------------------------------
    |
    | Define who can access the permissions management features.
    | This closure receives the authenticated user and should return a boolean.
    | If null, only users with the first role enum case (typically Admin) can access.
    |
    */
    'gate' => null,

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    |
    | Configure how the plugin appears in the Filament navigation sidebar.
    |
    */
    'navigation' => [
        'group' => null,
        'sort' => 2,
        'icon' => 'heroicon-o-shield-check',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific resources provided by the plugin.
    |
    */
    'resources' => [
        'roles' => true,
        'permissions' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | Enable or disable the permissions overview page.
    |
    */
    'pages' => [
        'manage_permissions' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Guard Name
    |--------------------------------------------------------------------------
    |
    | The guard name to use for roles and permissions.
    | If null, the default guard from Spatie config will be used.
    |
    */
    'guard_name' => null,

];
