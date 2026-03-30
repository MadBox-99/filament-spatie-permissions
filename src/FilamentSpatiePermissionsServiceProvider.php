<?php

declare(strict_types=1);

namespace MadBox\FilamentSpatiePermissions;

use Illuminate\Support\ServiceProvider;
use MadBox\FilamentSpatiePermissions\Commands\SyncPermissionsCommand;

final class FilamentSpatiePermissionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-spatie-permissions.php',
            'filament-spatie-permissions',
        );
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-spatie-permissions');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-spatie-permissions');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncPermissionsCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/filament-spatie-permissions.php' => config_path('filament-spatie-permissions.php'),
            ], 'filament-spatie-permissions-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/filament-spatie-permissions'),
            ], 'filament-spatie-permissions-views');

            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('vendor/filament-spatie-permissions'),
            ], 'filament-spatie-permissions-translations');
        }
    }
}
