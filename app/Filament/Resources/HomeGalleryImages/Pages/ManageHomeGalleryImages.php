<?php

namespace App\Filament\Resources\HomeGalleryImages\Pages;

use App\Enums\HomeGallerySection;
use App\Filament\Resources\HomeGalleryImages\HomeGalleryImageResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageHomeGalleryImages extends ManageRecords
{
    protected static string $resource = HomeGalleryImageResource::class;

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            HomeGallerySection::Statement->value => Tab::make(HomeGallerySection::Statement->label())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('section', HomeGallerySection::Statement)),
            HomeGallerySection::Covers->value => Tab::make(HomeGallerySection::Covers->label())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('section', HomeGallerySection::Covers)),
        ];
    }
}
