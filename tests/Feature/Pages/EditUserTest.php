<?php

use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->seed();
    actingAs(User::factory()->create());
});

it('can toggle field disabled', function () {
    $user = User::factory()->create();

    // Arrange
    livewire(EditUser::class, ['record' => $user->id])
        ->assertFormFieldIsEnabled('name')
        ->callFormComponentAction('user-profile', 'make-hidden')
        ->assertFormFieldIsDisabled('name');

});
