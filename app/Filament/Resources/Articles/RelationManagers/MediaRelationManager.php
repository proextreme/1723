<?php

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Models\Media;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'Gallery';

    /**
     * Pivot fields, shown by the attach and edit actions. Upload new files in
     * the Media library first, then attach them here.
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Toggle::make('is_featured')
                ->label('Featured image')
                ->helperText('Shown as the article thumbnail and social card.'),
            TextInput::make('caption')->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alt_text')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('path')
                    ->label('Image')
                    ->disk('public')
                    ->height(48),
                TextColumn::make('alt_text')
                    ->label('Alt text')
                    ->limit(40)
                    ->placeholder('— missing —'),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->state(fn (Media $record): bool => (bool) $record->pivot->is_featured)
                    ->boolean(),
                TextColumn::make('caption')
                    ->state(fn (Media $record): ?string => $record->pivot->caption)
                    ->placeholder('—')
                    ->limit(40),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Attach image')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Toggle::make('is_featured')->label('Featured image'),
                        TextInput::make('caption')->maxLength(255),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
