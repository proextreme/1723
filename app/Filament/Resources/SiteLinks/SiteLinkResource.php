<?php

namespace App\Filament\Resources\SiteLinks;

use App\Filament\Resources\SiteLinks\Pages\CreateSiteLink;
use App\Filament\Resources\SiteLinks\Pages\EditSiteLink;
use App\Filament\Resources\SiteLinks\Pages\ListSiteLinks;
use App\Models\SiteLink;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteLinkResource extends Resource
{
    protected static ?string $model = SiteLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $navigationLabel = 'External links';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')
                ->required()
                ->maxLength(255)
                ->disabledOn('edit')
                ->helperText('Referenced in code and templates. Cannot change after creation.'),
            TextInput::make('label')->required()->maxLength(255),
            TextInput::make('url')
                ->url()
                ->maxLength(255)
                ->helperText('Leave empty for the Media Kit — use the file field instead.'),
            Select::make('target')
                ->options([
                    '_self' => 'Same tab',
                    '_blank' => 'New tab',
                ])
                ->default('_blank')
                ->selectablePlaceholder(false)
                ->required(),
            Select::make('media_id')
                ->label('Attached file')
                ->relationship('media', 'original_name')
                ->searchable()
                ->preload()
                ->helperText('Used by the Media Kit link.'),
            Toggle::make('is_active')
                ->default(true)
                ->helperText('Inactive links are hidden from the site.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->badge()->searchable(),
                TextColumn::make('label')->searchable()->weight('medium'),
                TextColumn::make('url')->limit(45)->placeholder('—')->url(fn (SiteLink $record): ?string => $record->url, shouldOpenInNewTab: true),
                TextColumn::make('target')->badge()->formatStateUsing(fn (?string $state): string => $state === '_blank' ? 'New tab' : 'Same tab'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('key')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteLinks::route('/'),
            'create' => CreateSiteLink::route('/create'),
            'edit' => EditSiteLink::route('/{record}/edit'),
        ];
    }
}
