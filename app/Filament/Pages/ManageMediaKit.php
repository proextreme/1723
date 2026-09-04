<?php

namespace App\Filament\Pages;

use App\Models\Media;
use App\Models\SiteLink;
use App\Support\Media\StoredImage;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Gate;

/**
 * @property-read Schema $form
 */
class ManageMediaKit extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-media-kit';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static ?string $title = 'Media Kit';

    protected static ?int $navigationSort = 13;

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public static function canAccess(): bool
    {
        return Gate::allows('viewAny', SiteLink::class);
    }

    public function mount(): void
    {
        $this->form->fill([
            'file' => $this->link()->media?->path,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Current Media Kit')
                    ->description('One PDF, shown on the Partnerships page. Uploading replaces it.')
                    ->schema([
                        FileUpload::make('file')
                            ->label('PDF file')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('media-kit')
                            ->visibility('public')
                            ->maxSize(20480)
                            ->storeFileNamesIn('original_name')
                            ->downloadable()
                            ->required(),
                    ]),
            ]);
    }

    public function save(): void
    {
        Gate::authorize('update', $this->link());

        $data = $this->form->getState();

        $media = Media::query()->create([
            ...StoredImage::attributes((string) $data['file'], $data['original_name'] ?? null),
            'mime_type' => 'application/pdf',
            'alt_text' => 'Media Kit',
        ]);

        $this->link()->update(['media_id' => $media->id, 'url' => null, 'is_active' => true]);

        Notification::make()->success()->title('Media Kit updated')->send();
    }

    private function link(): SiteLink
    {
        return SiteLink::query()->firstOrCreate(
            ['key' => 'media_kit'],
            ['label' => 'Media Kit', 'target' => '_blank', 'is_active' => false],
        );
    }
}
