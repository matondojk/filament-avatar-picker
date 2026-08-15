<?php

namespace MatondoJK\FilamentAvatarPicker\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar_url')
                    ->label('Avatar')
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->extraAttributes([
                        'class' => 'avatar-filepond-wrapper',
                        'x-init' => "if(!document.getElementById('avatar-style')){ let s=document.createElement('style'); s.id='avatar-style'; s.innerHTML='.avatar-filepond-wrapper { cursor: pointer; } .avatar-filepond-wrapper .filepond--action-remove-item { position: absolute !important; top: auto !important; bottom: 0 !important; z-index: 50 !important; }'; document.head.appendChild(s); }",
                        'x-on:click.capture' => "if (\$event.target.closest('.filepond--action-remove-item')) { return; } \$event.preventDefault(); \$event.stopPropagation(); document.getElementById('hidden-avatar-action-btn')?.click();"
                    ])
                    ->hintAction(
                        Action::make('chooseAvatar')
                            ->label('chooseAvatar')
                            ->extraAttributes(['style' => 'display: none !important;', 'id' => 'hidden-avatar-action-btn'])
                            ->extraModalWindowAttributes(['style' => 'border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;'])
                            ->modalHeading(new \Illuminate\Support\HtmlString('<div class="text-center pt-4 pb-2"><h2 class="text-gray-900 dark:text-white" style="font-size: 28px; font-weight: 700; line-height: 1.2;">' . __('filament-avatar-picker::avatar.title') . '</h2><p class="text-gray-500 dark:text-gray-400" style="font-size: 14px; font-weight: 400; margin-top: 8px;">' . __('filament-avatar-picker::avatar.description') . '</p></div>'))
                            ->modalWidth('4xl')
                            ->modalSubmitActionLabel(__('filament-avatar-picker::avatar.apply_button'))
                            ->form([
                                Tabs::make('Tabs')
                                    ->tabs([
                                        Tabs\Tab::make(__('filament-avatar-picker::avatar.gallery_tab'))
                                            ->icon('heroicon-m-photo')
                                            ->schema([
                                                ViewField::make('preset_avatar')
                                                    ->hiddenLabel()
                                                    ->view('filament-avatar-picker::components.avatar-gallery'),
                                            ]),
                                        Tabs\Tab::make(__('filament-avatar-picker::avatar.upload_tab'))
                                            ->icon('heroicon-m-arrow-up-tray')
                                            ->schema([
                                                FileUpload::make('custom_upload')
                                                    ->hiddenLabel()
                                                    ->disk('public')
                                                    ->directory('custom-avatars')
                                                    ->extraAttributes([
                                                        'x-on:filepond-processfile' => "setTimeout(() => \$el.closest('.fi-modal').querySelector('button[type=\"submit\"]').click(), 300)"
                                                    ]),
                                            ]),
                                    ]),
                            ])
                            ->action(function (array $data, \Filament\Schemas\Components\Utilities\Set $set) {
                                if (! empty($data['custom_upload'])) {
                                    $set('avatar_url', $data['custom_upload']);
                                } elseif (! empty($data['preset_avatar'])) {
                                    $set('avatar_url', $data['preset_avatar']);
                                }
                            })
                    ),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
