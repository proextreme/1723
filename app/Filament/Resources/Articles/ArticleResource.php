<?php

namespace App\Filament\Resources\Articles;

use App\Enums\ArticleStatus;
use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Filament\Resources\Articles\RelationManagers\CreditsRelationManager;
use App\Filament\Resources\Articles\RelationManagers\MediaRelationManager;
use App\Models\Article;
use App\Models\ArticleTranslation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'defaultTranslation.title';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->relationship('defaultTranslation')
                    ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => [
                        'locale' => config('app.fallback_locale'),
                        ...$data,
                    ])
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Content')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                                        if ($operation === 'create' && filled($state)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(
                                        table: ArticleTranslation::class,
                                        column: 'slug',
                                        ignoreRecord: true,
                                        modifyRuleUsing: fn ($rule) => $rule->where('locale', config('app.fallback_locale')),
                                    )
                                    ->helperText('Used in the article URL.'),
                                Textarea::make('excerpt')
                                    ->rows(2)
                                    ->maxLength(500),
                                RichEditor::make('body_html')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                        Section::make('SEO')
                            ->description('Overrides the title and description in search results and social cards.')
                            ->collapsed()
                            ->schema([
                                TextInput::make('seo_title')->maxLength(255),
                                Textarea::make('seo_description')->rows(2)->maxLength(255),
                            ]),
                    ]),
                Section::make('Publication')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options(fn (): array => collect(ArticleStatus::cases())
                                ->mapWithKeys(fn (ArticleStatus $status): array => [$status->value => $status->label()])
                                ->all())
                            ->default(ArticleStatus::Draft->value)
                            ->selectablePlaceholder(false)
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->seconds(false)
                            ->helperText('The article becomes public at this time once its status is Published.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('defaultTranslation'))
            ->columns([
                TextColumn::make('defaultTranslation.title')
                    ->label('Title')
                    ->searchable()
                    ->limit(60)
                    ->weight('medium'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ArticleStatus $state): string => $state->label())
                    ->color(fn (ArticleStatus $state): string => match ($state) {
                        ArticleStatus::Draft => 'gray',
                        ArticleStatus::Review => 'warning',
                        ArticleStatus::Published => 'success',
                    }),
                TextColumn::make('published_at')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('updater.name')
                    ->label('Last edited by')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(fn (): array => collect(ArticleStatus::cases())
                        ->mapWithKeys(fn (ArticleStatus $status): array => [$status->value => $status->label()])
                        ->all()),
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

    public static function getRelations(): array
    {
        return [
            MediaRelationManager::class,
            CreditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
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
