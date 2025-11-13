<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditSettings extends EditRecord
{
    protected static string $resource = SettingResource::class;

    public function mount(int | string $record = null): void
    {
        // Ambil atau buat setting record pertama
        $setting = Setting::getSettings();

        $this->record = $setting;

        $this->fillForm();

        $this->previousUrl = static::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Muat Ulang')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->fillForm();
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Pengaturan website berhasil disimpan';
    }
}
