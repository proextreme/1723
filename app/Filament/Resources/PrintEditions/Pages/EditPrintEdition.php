<?php

namespace App\Filament\Resources\PrintEditions\Pages;

use App\Actions\SetCurrentPrintEdition;
use App\Filament\Resources\PrintEditions\PrintEditionResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Gate;

class EditPrintEdition extends EditRecord
{
    protected static string $resource = PrintEditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('makeCurrent')
                ->label('Make current issue')
                ->icon('heroicon-o-star')
                ->color('warning')
                ->visible(fn (): bool => ! $this->record->is_current && Gate::allows('setCurrent', $this->record))
                ->requiresConfirmation()
                ->modalDescription('This becomes the issue shown on the Print page. The previous current issue is unset.')
                ->action(function (): void {
                    app(SetCurrentPrintEdition::class)->handle($this->record);
                    $this->refreshFormData(['is_current']);

                    Notification::make()->success()->title('This is now the current issue')->send();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
