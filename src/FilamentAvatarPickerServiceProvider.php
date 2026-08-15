<?php
namespace MatondoJK\FilamentAvatarPicker;

use Illuminate\Support\ServiceProvider;

class FilamentAvatarPickerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-avatar-picker');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-avatar-picker');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../stubs/avatars' => public_path('avatars'),
        ], 'filament-avatar-picker-assets');
        
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-avatar-picker'),
        ], 'filament-avatar-picker-views');
    }
}