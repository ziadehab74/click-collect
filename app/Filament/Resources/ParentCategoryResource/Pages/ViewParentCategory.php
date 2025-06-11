<?php

namespace App\Filament\Resources\ParentCategoryResource\Pages;

use App\Filament\Resources\ParentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParentCategory extends ViewRecord
{
    protected static string $resource = ParentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
