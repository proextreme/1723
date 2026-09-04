<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Actions\SetArticleStatus;
use App\Enums\ArticleStatus;
use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->transitionAction('submitForReview', 'Submit for review', ArticleStatus::Review, 'heroicon-o-paper-airplane', ArticleStatus::Draft),
            $this->transitionAction('publish', 'Publish', ArticleStatus::Published, 'heroicon-o-check-circle', ArticleStatus::Draft, ArticleStatus::Review)
                ->color('success'),
            $this->transitionAction('unpublish', 'Move to draft', ArticleStatus::Draft, 'heroicon-o-arrow-uturn-left', ArticleStatus::Review, ArticleStatus::Published)
                ->color('gray'),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = Auth::id();

        return $data;
    }

    private function transitionAction(string $name, string $label, ArticleStatus $target, string $icon, ArticleStatus ...$from): Action
    {
        return Action::make($name)
            ->label($label)
            ->icon($icon)
            ->visible(fn (): bool => in_array($this->record->status, $from, true)
                && Gate::allows($target === ArticleStatus::Published ? 'publish' : 'update', $this->record))
            ->requiresConfirmation($target === ArticleStatus::Published)
            ->action(function () use ($target): void {
                app(SetArticleStatus::class)->handle($this->record, $target);
                $this->refreshFormData(['status', 'published_at']);

                Notification::make()->success()->title('Status changed to '.$target->label())->send();
            });
    }
}
