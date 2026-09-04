<?php

namespace App\Filament\Resources\PrintEditions;

use App\Filament\Resources\PrintEditions\Pages\CreatePrintEdition;
use App\Filament\Resources\PrintEditions\Pages\EditPrintEdition;
use App\Filament\Resources\PrintEditions\Pages\ListPrintEditions;
use App\Models\PrintEdition;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PrintEditionResource extends Resource
{
    protected static ?string $model = PrintEdition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'defaultTranslation.title';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Group::make()
                ->relationship('defaultTranslation')
                ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => [
                    'locale' => config('app.fallback_locale'),
                    ...$data,
                ])
                ->columnSpanFull()
                ->schema([
                    Section::make('Edition')->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                                if ($operation === 'create' && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')->required()->maxLength(255),
                        Textarea::make('description')->rows(3)->maxLength(1000),
                    ]),
                ]),
            Section::make('Details')
                ->columns(2)
                ->schema([
                    TextInput::make('issue_number')->numeric()->minValue(1),
                    DatePicker::make('release_date'),
                    TextInput::make('magcloud_url')
                        ->label('MagCloud URL')
                        ->url()
                        ->required()
                        ->maxLength(255),
                    Select::make('cover_media_id')
                        ->label('Cover image')
                        ->relationship('coverMedia', 'original_name')
                        ->searchable()
                        ->preload(),
                    TextInput::make('sort_order')->numeric()->default(0)->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('defaultTranslation'))
            ->columns([
                TextColumn::make('issue_number')->label('#')->sortable()->placeholder('—'),
                TextColumn::make('defaultTranslation.title')->label('Title')->searchable()->weight('medium'),
                IconColumn::make('is_current')->label('Current')->boolean(),
                TextColumn::make('release_date')->date('M Y')->sortable()->placeholder('—'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrintEditions::route('/'),
            'create' => CreatePrintEdition::route('/create'),
            'edit' => EditPrintEdition::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
