<?php

namespace App\Filament\Resources\PaymentMethodsResource\Pages;

use App\Filament\Resources\PaymentMethodsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentMethods extends ViewRecord
{
    protected static string $resource = PaymentMethodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
