# Filament Avatar Picker

A beautiful, highly-customizable avatar gallery and upload picker component for Filament PHP.

![Avatar Picker Preview](https://via.placeholder.com/800x400.png?text=Filament+Avatar+Picker)

## Features

- **Curated Avatar Gallery**: Display a gorgeous grid of pre-defined avatars.
- **Custom Uploads**: Isolated secure directory for user uploads (`custom-avatars`).
- **Multi-language Support**: Fully translated into 12 languages (including Arabic, Spanish, French, Portuguese, Chinese, etc.).
- **Smart Sorting**: Customizable initial avatar priority.
- **Dark/Light Mode**: First-class support for Filament themes.

## Installation

1. Install the package via Composer:

```bash
composer require matondojk/filament-avatar-picker
```

2. Run the migrations. This will automatically verify and add the `avatar_url` field to your `users` table:

```bash
php artisan migrate
```

3. Publish the assets (this will copy the default curated avatars to your public directory):

```bash
php artisan vendor:publish --tag=filament-avatar-picker-assets
```

## Setup

### Model Preparation

Your `User` model must implement the `HasAvatar` interface from Filament. You must also add `avatar_url` to the `$fillable` array.

```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'avatar_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::disk('public')->url($this->avatar_url) : null;
    }
}
```

### Plugin Registration

Register the plugin inside your Panel Service Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use MatondoJK\FilamentAvatarPicker\AvatarPickerPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(AvatarPickerPlugin::make());
}
```

## Usage

Once registered, the plugin will automatically replace Filament's default `EditProfile` page with the new custom page containing the avatar component. Just navigate to your profile in the Filament dashboard, click on the avatar, and the beautiful gallery modal will appear!

## Publishing Views & Translations

If you wish to modify the blade views or translations, you can publish them:

```bash
php artisan vendor:publish --tag=filament-avatar-picker-views
```

## License

The MIT License (MIT). Please see License File for more information.
