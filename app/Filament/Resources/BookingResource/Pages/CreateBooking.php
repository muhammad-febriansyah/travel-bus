<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate WhatsApp URL if status is pending
        if ($data['status'] === 'pending') {
            $adminPhone = config('app.admin_whatsapp', '6281234567890');
            // Will be generated after booking is created
            $data['whatsapp_url'] = null;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Generate WhatsApp URL after booking is created
        if ($this->record->status === 'pending') {
            $adminPhone = config('app.admin_whatsapp', '6281234567890');
            $url = $this->record->generateWhatsAppUrl($adminPhone);
            $this->record->update(['whatsapp_url' => $url]);
        }
    }
}
