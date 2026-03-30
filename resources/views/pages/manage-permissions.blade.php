<x-filament-panels::page>
    <div class="grid gap-6">
        {{-- Summary --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('filament-spatie-permissions::permissions.total_permissions') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPermissions }}</p>
            </div>
            @foreach ($roles as $role)
                <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $role['name'] }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $role['total'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Roles & Permissions --}}
        @foreach ($roles as $role)
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role['name'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $role['total'] }} {{ __('filament-spatie-permissions::permissions.permissions_lowercase') }}</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($role['permissions'] as $permission)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                {{ $permission }}
                            </span>
                        @endforeach
                        @if (empty($role['permissions']))
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('filament-spatie-permissions::permissions.no_permissions') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
