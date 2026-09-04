<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\Settings\SettingsRepository;
use BackedEnum;
use Filament\Actions\Action;
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
     * The editable keys, with their input type.
     *
     * @var array<string, 'text'|'textarea'>
     */
    private const FIELDS = [
        'contact_email' => 'text',
        'newsletter_inbox' => 'text',
        'footer_note' => 'textarea',
        'seo_default_title' => 'text',
        'seo_default_description' => 'textarea',
    ];

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
        $this->form->fill(
            collect(self::FIELDS)->keys()
                ->mapWithKeys(fn (string $key): array => [$key => $settings->get($key)])
                ->all(),
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('General')->schema([
                    $this->field('contact_email')->label('Contact email')->email(),
                    $this->field('newsletter_inbox')->label('Newsletter inbox')->email()
                        ->helperText('Where newsletter sign-ups are emailed.'),
                    $this->field('footer_note')->label('Footer note'),
                ]),
                Section::make('Default SEO')->schema([
                    $this->field('seo_default_title')->label('Default title'),
                    $this->field('seo_default_description')->label('Default description'),
                ]),
            ]);
    }

    public function save(SettingsRepository $settings): void
    {
        Gate::authorize('viewAny', Setting::class);

        foreach ($this->form->getState() as $key => $value) {
            $settings->set($key, $value === '' ? null : $value, 'string');
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

    private function field(string $key): TextInput|Textarea
    {
        return self::FIELDS[$key] === 'textarea'
            ? Textarea::make($key)->rows(2)->maxLength(255)
            : TextInput::make($key)->maxLength(255);
    }
}
