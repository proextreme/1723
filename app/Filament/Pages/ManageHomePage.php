<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\Content\HomeContent;
use App\Support\Settings\SettingsRepository;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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
class ManageHomePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-home-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $title = 'Home page';

    protected static ?int $navigationSort = 0;

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        return Gate::allows('viewAny', Setting::class);
    }

    public function mount(SettingsRepository $settings): void
    {
        $keys = [...array_keys(HomeContent::DEFAULTS), ...HomeContent::IMAGE_KEYS];

        $this->form->fill(
            collect($keys)->mapWithKeys(fn (string $key): array => [$key => $settings->get('home.'.$key)])->all(),
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Hero')->schema([
                    $this->image('hero_image')->label('Background image')
                        ->helperText('The portrait behind the 17:23 MAG headline. Leave empty to keep the design default.'),
                    $this->line('hero_tagline')->label('Tagline'),
                ]),
                Section::make('Statement')->collapsed()->schema([
                    $this->line('statement_heading')->label('Heading'),
                    $this->paragraph('statement_body')->label('Paragraph'),
                ]),
                Section::make('Front Covers')->collapsed()->schema([
                    $this->line('covers_heading')->label('Heading'),
                    $this->paragraph('covers_body')->label('Paragraph'),
                ]),
                Section::make('Print Editions')->collapsed()->schema([
                    $this->image('print_image')->label('Feature image'),
                    $this->line('print_heading')->label('Heading'),
                    $this->line('print_quote')->label('Pull quote'),
                    $this->paragraph('print_body')->label('Paragraph'),
                ]),
                Section::make('Enter 17:23 Be Seen')->collapsed()->schema([
                    $this->image('beseen_image')->label('Background image'),
                    $this->line('beseen_heading')->label('Heading'),
                ]),
                Section::make('Newsletter')->collapsed()->schema([
                    $this->line('newsletter_heading')->label('Heading'),
                    $this->paragraph('newsletter_body')->label('Paragraph'),
                    $this->paragraph('newsletter_tagline')->label('Closing line'),
                ]),
            ]);
    }

    public function save(SettingsRepository $settings): void
    {
        Gate::authorize('viewAny', Setting::class);

        foreach ($this->form->getState() as $key => $value) {
            $settings->set('home.'.$key, filled($value) ? $value : null, 'string');
        }

        Notification::make()->success()->title('Home page saved')->send();
    }

    /** @return array<int, Action> */
    protected function getFormActions(): array
    {
        return [Action::make('save')->label('Save')->submit('save')];
    }

    private function line(string $key): TextInput
    {
        return TextInput::make($key)->maxLength(255)->placeholder(HomeContent::DEFAULTS[$key] ?? null);
    }

    private function paragraph(string $key): Textarea
    {
        return Textarea::make($key)->rows(3)->maxLength(600)->placeholder(HomeContent::DEFAULTS[$key] ?? null);
    }

    private function image(string $key): FileUpload
    {
        return FileUpload::make($key)
            ->image()
            ->imageEditor()
            ->disk('public')
            ->directory('home')
            ->visibility('public')
            ->maxSize(8192);
    }
}
