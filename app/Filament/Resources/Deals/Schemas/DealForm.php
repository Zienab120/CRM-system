<?php

namespace App\Filament\Resources\Deals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\KeyValue;

class DealForm
{
    public static function configure(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Section::make('General Information')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->label('Deal Title')
                                ->required()
                                ->maxLength(255),

                            Grid::make(2)->schema([
                                Select::make('stage')
                                    ->options([
                                        'discovery' => 'Discovery',
                                        'proposal' => 'Proposal',
                                        'negotiation' => 'Negotiation',
                                        'closed_won' => 'Closed Won',
                                        'closed_lost' => 'Closed Lost',
                                    ])
                                    ->required()
                                    ->native(false),

                                TextInput::make('probability')
                                    ->numeric()
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->default(0),
                            ]),
                            KeyValue::make('custom_fields')
                                ->label('Additional Attributes'),
                        ]),

                    Section::make('Associations & Timing')
                        ->columnSpan(1)
                        ->schema([
                            Select::make('owner_id')
                                ->label('Deal Owner')
                                ->relationship('owner', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('contact_id')
                                ->label('Primary Contact')
                                ->relationship('contact', 'title')
                                ->searchable()
                                ->preload(),

                            Select::make('company_id')
                                ->label('Company')
                                ->relationship('company', 'name')
                                ->searchable()
                                ->preload(),

                            DatePicker::make('close_expected_at')
                                ->label('Expected Close Date')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->required(),
                        ]),

                    Section::make('Financials')
                        ->columnSpanFull()
                        ->columns(3)
                        ->schema([
                            TextInput::make('amount')
                                ->numeric()
                                ->prefix('$')
                                ->required(),

                            Select::make('currency')
                                ->options([
                                    'USD' => 'USD',
                                    'EUR' => 'EUR',
                                    'GBP' => 'GBP',
                                ])
                                ->default('USD')
                                ->required(),

                            // Select::make('pipeline_id')
                            //     ->label('Sales Pipeline')
                            //     ->relationship('pipeline', 'name')
                            //     ->required(),
                        ]),
                ]),
        ];
    }
}
