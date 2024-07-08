<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Livewire\ViewUserCreation;
use App\Livewire\ViewUserVerification;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::formSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
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

    protected static function getUserFormSection(): Forms\Components\Component
    {
        return Forms\Components\Section::make(fn (User $record) => $record->name.' Profile')
            ->key('data.user-profile')
            ->collapsible()
            ->headerActions([
                Forms\Components\Actions\Action::make('make-hidden')
                    ->label('Make Profile Disabled')
                    ->action(fn (Forms\Set $set, Forms\Get $get) => $set('is-disabled', ! $get('is-disabled'))),
            ])
            ->schema([
                Forms\Components\Hidden::make('is-disabled')
                    ->live(),
                Forms\Components\TextInput::make('name')
                    ->disabled(fn (Forms\Get $get) => $get('is-disabled'))
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->disabled(fn (Forms\Get $get) => $get('is-disabled'))
                    ->email()
                    ->required(),
            ]);
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
}
