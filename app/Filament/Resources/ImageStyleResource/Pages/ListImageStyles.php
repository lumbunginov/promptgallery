<?php

namespace App\Filament\Resources\ImageStyleResource\Pages;

use App\Filament\Resources\ImageStyleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImageStyles extends ListRecords
{
    protected static string $resource = ImageStyleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
