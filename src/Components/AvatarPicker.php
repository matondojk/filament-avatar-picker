<?php

namespace MatondoJK\FilamentAvatarPicker\Components;

use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\ViewField;

class AvatarPicker extends FileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $actionId = 'hidden-avatar-action-btn';

        $this->avatar()
            ->disk('public')
            ->directory('avatars')
            ->placeholder(new \Illuminate\Support\HtmlString(__('filament-avatar-picker::avatar.upload_prompt')))
            ->extraAttributes([
                'class' => 'avatar-filepond-wrapper',
                'x-init' => "if(!document.getElementById('avatar-style')){ let s=document.createElement('style'); s.id='avatar-style'; s.innerHTML='.avatar-filepond-wrapper { cursor: pointer; } .avatar-filepond-wrapper .filepond--action-remove-item { position: absolute !important; top: auto !important; bottom: 0 !important; z-index: 50 !important; } .filament-avatar-picker-browse-link { color: #f97316; color: var(--primary-600); color: rgb(var(--primary-600)); font-weight: 500; text-decoration: underline; cursor: pointer; }'; document.head.appendChild(s); }",
                // This ensures we click our hidden hintAction instead of opening the standard file browser!
                'x-on:click.capture' => "if (\$event.target.closest('.filepond--action-remove-item')) { return; } \$event.preventDefault(); \$event.stopPropagation(); document.getElementById('{$actionId}')?.click();"
            ])
            ->hintAction(
                \Filament\Actions\Action::make('chooseAvatar')
                    ->label('Choose Avatar')
                    ->extraAttributes(['style' => 'display: none !important;', 'id' => $actionId])
                    ->extraModalWindowAttributes(['style' => 'border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;'])
                    ->modalHeading(new \Illuminate\Support\HtmlString('<div class="text-center pt-2 sm:pt-4 pb-1 sm:pb-2"><h2 class="text-gray-900 dark:text-white" style="font-size: clamp(1.1rem, 3vw, 1.4rem); font-weight: 700; line-height: 1.2;">' . __('filament-avatar-picker::avatar.title') . '</h2><p class="text-gray-500 dark:text-gray-400" style="font-size: clamp(0.8rem, 1.5vw, 0.9rem); font-weight: 400; margin-top: 8px;">' . __('filament-avatar-picker::avatar.description') . '</p></div>'))
                    ->modalWidth('2xl')
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
                                            ->placeholder(new \Illuminate\Support\HtmlString(__('filament-avatar-picker::avatar.upload_prompt')))
                                            ->extraAttributes([
                                                'x-on:filepond-processfile' => "setTimeout(() => \$el.closest('.fi-modal').querySelector('button[type=\"submit\"]').click(), 300)"
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->action(function (array $data, self $component) {
                        if (! empty($data['custom_upload'])) {
                            $component->state($data['custom_upload']);
                        } elseif (! empty($data['preset_avatar'])) {
                            $component->state($data['preset_avatar']);
                        }
                    })
            );
    }
}
