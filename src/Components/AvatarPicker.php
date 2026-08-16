<?php

namespace MatondoJK\FilamentAvatarPicker\Components;

use Filament\Forms\Components\Field;
use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\FileUpload;

class AvatarPicker extends Field
{
    protected string $view = 'filament-avatar-picker::components.avatar-picker-field';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerActions([
            Action::make('chooseAvatar')
                ->label(__('filament-avatar-picker::avatar.chooseAvatar'))
                ->icon('heroicon-m-pencil-square')
                ->color('primary')
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
                                        ->image()
                                        ->imageEditor()
                                        ->imageCropAspectRatio('1:1')
                                        ->imageResizeMode('cover')
                                        ->imageResizeTargetWidth('512')
                                        ->imageResizeTargetHeight('512')
                                        ->maxSize(2048)
                                        ->helperText(__('filament-avatar-picker::avatar.upload_helper')),
                                ]),
                        ])
                ])
                ->action(function (array $data, self $component) {
                    $selected = $data['custom_upload'] ?? $data['preset_avatar'] ?? null;
                    if ($selected) {
                        $component->state($selected);
                    }
                }),
                
            Action::make('removeAvatar')
                ->label(__('filament-avatar-picker::avatar.remove_button') ?? 'Remove')
                ->color('danger')
                ->icon('heroicon-m-trash')
                ->size('sm')
                ->action(function (self $component) {
                    $component->state(null);
                })
        ]);
    }
}
