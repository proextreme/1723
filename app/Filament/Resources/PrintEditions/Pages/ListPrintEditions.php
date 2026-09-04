<?php

namespace App\Filament\Resources\PrintEditions\Pages;

use App\Filament\Resources\PrintEditions\PrintEditionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrintEditions extends ListRecords
{
    protected static string $resource = PrintEditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
