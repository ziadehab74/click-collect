<?php

namespace App\Filament\Resources\PermissionGroupResource\Pages;

use App\Filament\Resources\PermissionGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPermissionGroup extends ViewRecord
{
    protected static string $resource = PermissionGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
