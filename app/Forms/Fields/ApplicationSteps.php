<?php

namespace App\Forms\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Hash;
use Shipu\WebInstaller\Concerns\StepContract;

class ApplicationSteps implements StepContract
{
    public static function form(): array
    {
        $applicationSteps = [];

        foreach (config('installer.applications', []) as $key => $value) {
            if ($key == 'admin.password') {
                $applicationSteps[] = TextInput::make('applications.' . $key)
                    ->label($value['label'])
                    ->password()
                    ->maxLength(255)
                    ->default($value['default'])
                    ->dehydrateStateUsing(fn ($state) => !empty($state)
                        ? Hash::make($state) : "");
            } else {
                $applicationSteps[] = TextInput::make('applications.' . $key)
                    ->label($value['label'])
                    ->required($value['required'])
                    ->rules($value['rules'])
                    ->default($value['default'] ?? '');
            }
        }

        return $applicationSteps;
    }

    public static function make(): Step
    {
        return Step::make('application')
            ->label('Application Settings')
            ->schema(self::form());
    }
}
