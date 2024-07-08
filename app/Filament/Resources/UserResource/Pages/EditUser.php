<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

/**
 * @property Form $form
 */
class EditUser extends Page
{
    use InteractsWithFormActions;
    use InteractsWithRecord;

    protected static string $resource = UserResource::class;

    public ?array $data = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getView(): string
    {
        return 'filament.pages.edit-user';
    }

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->fillForm();
    }

    public function fillForm(): void
    {
        $data = $this->record->toArray();

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form->schema(UserResource::formSchema())
            ->model($this->record)
            ->statePath('data')
            ->operation('edit');
    }
}
