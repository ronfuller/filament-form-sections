## How to add an infolist to a filament form

### Description

If you want to add an infolist to a form , you can use a Livewire form component and add the info list to the created
livewire component.

### Usage

in UserResource.php the following shows a form schema split into content form section and a details panel section. The
details panel includes Livewire Form Components with Infolists.

```php

 public static function form(Form $form): Form
 {
    return $form
        ->schema(static::formSchema());
 }

 public static function formSchema(): array
    {
        return [
            Forms\Components\Grid::make('page-section')->schema([
                Forms\Components\Grid::make('content-section')
                    ->schema([
                        static::getUserFormSection(),
                    ]
                    )->columnSpan(4),
                Forms\Components\Grid::make('panel-section')
                    ->schema([
                        static::getUserPanelSection(),
                    ])->columnSpan(2)->columns(2),
            ])->columns(6),
        ];
    }

protected static function getUserPanelSection(): Forms\Components\Component
{
    return Forms\Components\Section::make(fn (User $record) => $record->name.' Details')
        ->key('data.user-details')
        ->collapsible()
        ->schema([
            Forms\Components\Livewire::make(ViewUserVerification::class)
                ->key('user-verification'),
            Forms\Components\Livewire::make(ViewUserCreation::class)
                ->key('user-creation'),
        ]);
}
```

### Import Notes

1. Each Livewire component should have a unique key. You will see weird behavior with section header/footer actions if
   you don't.
2. The key for the section should be prefixed with `data.` to allow testing calling header/footer actions.

### Example Livewire Component

```php
class ViewUserCreation extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    public ?User $record = null;

    public function render()
    {
        return view('livewire.view-user-creation');
    }

    public function mount(?User $record = null): void
    {

        $this->record = $record;

    }

    public function userCreationInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                TextEntry::make('created_at'),
            ]);
    }
}
```

### Testing

Example test for the above form schema:

```php
    $user = User::factory()->create();

    // Arrange
    livewire(EditUser::class, ['record' => $user->id])
        ->assertFormFieldIsEnabled('name')
        ->callFormComponentAction('user-profile', 'make-hidden')
        ->assertFormFieldIsDisabled('name');
```
