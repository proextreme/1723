<?php

namespace App\Filament\Resources\HomeGalleryImages;

use App\Filament\Resources\HomeGalleryImages\Pages\ManageHomeGalleryImages;
use App\Models\HomeGalleryImage;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HomeGalleryImageResource extends Resource
{
    protected static ?string $model = HomeGalleryImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Home gallery';

    protected static ?string $modelLabel = 'gallery image';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('path')
                ->label('Image')
                ->image()
                ->imageEditor()
                ->disk('public')
                ->directory('home/gallery')
                ->visibility('public')
                ->maxSize(8192)
                ->required()
                ->columnSpanFull(),
            TextInput::make('alt')
                ->label('Alt text')
                ->maxLength(255)
                ->helperText('Describes the image for screen readers and search engines.')
                ->columnSpanFull(),
            TextInput::make('url')
                ->label('Link (optional)')
                ->url()
                ->maxLength(255)
                ->helperText('If set, clicking the image opens this address in a new tab. Leave empty for a plain image.')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->ordered())
            ->reorderable('sort_order')
            ->emptyStateHeading('No gallery images yet')
            ->emptyStateDescription('Add images for the grid in the home page “Work gains value” section.')
            ->columns([
                ImageColumn::make('path')->label('Image')->disk('public')->height(64),
                TextColumn::make('alt')->label('Alt text')->limit(50)->placeholder('— missing —'),
                TextColumn::make('url')
                    ->label('Link')
                    ->placeholder('none')
                    ->limit(40)
                    ->url(fn (HomeGalleryImage $record): ?string => $record->url, shouldOpenInNewTab: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageHomeGalleryImages::route('/'),
        ];
    }
}
