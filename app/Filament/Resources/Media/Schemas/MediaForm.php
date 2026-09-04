<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Support\Media\StoredImage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * Shared upload form for the media library and the article gallery. The upload
 * component stores the file; {@see StoredImage} derives the rest of the row.
 */
class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('path')
                ->label('Image')
                ->image()
                ->imageEditor()
                ->disk('public')
                ->directory('media')
                ->visibility('public')
                ->maxSize(8192)
                ->rules(['dimensions:min_width=400,min_height=400'])
                ->storeFileNamesIn('original_name')
                ->required()
                ->columnSpanFull(),
            TextInput::make('alt_text')
                ->label('Alt text')
                ->required()
                ->maxLength(255)
                ->helperText('Describes the image for screen readers and search engines.')
                ->columnSpanFull(),
        ]);
    }

    /**
     * Expand the submitted form data into a full `media` row.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function toMediaAttributes(array $data): array
    {
        return [
            ...StoredImage::attributes((string) $data['path'], $data['original_name'] ?? null),
            'alt_text' => $data['alt_text'] ?? null,
        ];
    }
}
