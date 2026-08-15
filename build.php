<?php
$pkgDir = '/home/matondo/projects/plugins/filament-avatar-picker';
$appDir = '/home/matondo/projects/apps/fillament-avatar-component';

// composer.json
file_put_contents("$pkgDir/composer.json", json_encode([
    'name' => 'matondojk/filament-avatar-picker',
    'description' => 'A beautiful, highly-customizable avatar gallery and upload picker component for Filament PHP.',
    'type' => 'filament-plugin',
    'license' => 'MIT',
    'authors' => [['name' => 'MatondoJK', 'email' => 'matondo@example.com']],
    'require' => [
        'php' => '^8.1',
        'filament/filament' => '^3.0|^4.0|^5.0',
        'illuminate/support' => '^10.0|^11.0|^12.0'
    ],
    'autoload' => ['psr-4' => ['MatondoJK\\FilamentAvatarPicker\\' => 'src/']],
    'extra' => ['laravel' => ['providers' => ['MatondoJK\\FilamentAvatarPicker\\FilamentAvatarPickerServiceProvider']]]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Service Provider
$sp = <<<EOT
<?php
namespace MatondoJK\FilamentAvatarPicker;

use Illuminate\Support\ServiceProvider;

class FilamentAvatarPickerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \$this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-avatar-picker');
        \$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-avatar-picker');
        \$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        \$this->publishes([
            __DIR__ . '/../stubs/avatars' => public_path('avatars'),
        ], 'filament-avatar-picker-assets');
        
        \$this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-avatar-picker'),
        ], 'filament-avatar-picker-views');
    }
}
EOT;
file_put_contents("$pkgDir/src/FilamentAvatarPickerServiceProvider.php", $sp);

// Plugin Class
$pl = <<<EOT
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

    public function register(Panel \$panel): void
    {
        \$panel->profile(EditProfile::class);
    }

    public function boot(Panel \$panel): void
    {
        //
    }
}
EOT;
file_put_contents("$pkgDir/src/AvatarPickerPlugin.php", $pl);

// EditProfile Page
$editProfile = file_get_contents("$appDir/app/Filament/Pages/Auth/EditProfile.php");
$editProfile = str_replace('namespace App\Filament\Pages\Auth;', 'namespace MatondoJK\FilamentAvatarPicker\Pages;', $editProfile);
$editProfile = str_replace("view('filament.forms.components.", "view('filament-avatar-picker::components.", $editProfile);
$editProfile = str_replace("__('avatar.", "__('filament-avatar-picker::avatar.", $editProfile);
file_put_contents("$pkgDir/src/Pages/EditProfile.php", $editProfile);

// Views
$gallery = file_get_contents("$appDir/resources/views/filament/forms/components/avatar-gallery.blade.php");
$gallery = str_replace("__('avatar.", "__('filament-avatar-picker::avatar.", $gallery);
file_put_contents("$pkgDir/resources/views/components/avatar-gallery.blade.php", $gallery);

$display = file_get_contents("$appDir/resources/views/filament/forms/components/main-avatar-display.blade.php");
file_put_contents("$pkgDir/resources/views/components/main-avatar-display.blade.php", $display);

// Migration
$migration = <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('users', 'avatar_url')) {
            Schema::table('users', function (Blueprint \$table) {
                \$table->string('avatar_url')->nullable();
            });
        }
    }
    public function down()
    {
        if (Schema::hasColumn('users', 'avatar_url')) {
            Schema::table('users', function (Blueprint \$table) {
                \$table->dropColumn('avatar_url');
            });
        }
    }
};
EOT;
file_put_contents("$pkgDir/database/migrations/2026_01_01_000000_add_avatar_url_to_users_table.php", $migration);

// Translations
exec("cp -r $appDir/lang $pkgDir/resources/");

// Dummy avatar
exec("touch $pkgDir/stubs/avatars/.gitkeep");

echo "Package base built.\n";
