<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\Media\Schemas\MediaForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    public function form(Schema $schema): Schema
    {
        return MediaForm::configure($schema);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return MediaForm::toMediaAttributes($data);
    }
}
