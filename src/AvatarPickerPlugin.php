<?php
namespace MatondoJK\FilamentAvatarPicker;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MatondoJK\FilamentAvatarPicker\Pages\EditProfile;

class AvatarPickerPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-avatar-picker';
    }

    public function register(Panel $panel): void
    {
        $panel->profile(EditProfile::class);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}