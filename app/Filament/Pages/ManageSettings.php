<?php

namespace App\Filament\Pages;

use App\Models\Media;
use App\Models\Setting;
use App\Support\Settings\SettingsRepository;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $title = 'Site settings';

    protected static ?int $navigationSort = 12;

    /**
     * Free-text settings and their input type.
     *
     * @var array<string, 'text'|'textarea'>
     */
    private const TEXT_FIELDS = [
        'contact_email' => 'text',
        'newsletter_inbox' => 'text',
        'footer_note' => 'textarea',
        'seo_default_title' => 'text',
        'seo_default_description' => 'textarea',
    ];

    private const MEDIA_KEY = 'home_print_media_id';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public static function canAccess(): bool
    {
        return Gate::allows('viewAny', Setting::class);
    }

    public function mount(SettingsRepository $settings): void
    {
        $keys = [...array_keys(self::TEXT_FIELDS), self::MEDIA_KEY];

        $this->form->fill(
            collect($keys)->mapWithKeys(fn (string $key): array => [$key => $settings->get($key)])->all(),
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('General')->schema([
                    $this->textField('contact_email')->label('Contact email')->email(),
                    $this->textField('newsletter_inbox')->label('Newsletter inbox')->email()
                        ->helperText('Where newsletter sign-ups are emailed.'),
                    $this->textField('footer_note')->label('Footer note'),
                ]),
                Section::make('Home page')->schema([
                    Select::make(self::MEDIA_KEY)
                        ->label('Print Editions feature image')
                        ->helperText('The single image in the Print Editions block on the home page. Upload it in the Media library first.')
                        ->options(fn (): array => Media::query()
                            ->orderByDesc('created_at')
                            ->pluck('original_name', 'id')
                            ->all())
                        ->searchable()
                        ->native(false),
                ]),
                Section::make('Default SEO')->schema([
                    $this->textField('seo_default_title')->label('Default title'),
                    $this->textField('seo_default_description')->label('Default description'),
                ]),
            ]);
    }

    public function save(SettingsRepository $settings): void
    {
        Gate::authorize('viewAny', Setting::class);

        foreach ($this->form->getState() as $key => $value) {
            $settings->set($key, $value === '' || $value === null ? null : $value, 'string');
        }

        Notification::make()->success()->title('Settings saved')->send();
    }

    /**
     * @return array<int, Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Save')->submit('save'),
        ];
    }

    private function textField(string $key): TextInput|Textarea
    {
        return self::TEXT_FIELDS[$key] === 'textarea'
            ? Textarea::make($key)->rows(2)->maxLength(255)
            : TextInput::make($key)->maxLength(255);
    }
}
