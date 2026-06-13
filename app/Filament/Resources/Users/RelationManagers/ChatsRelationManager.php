<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Resources\RelationManagers\RelationManager;

class ChatsRelationManager extends RelationManager
{
    protected static string $relationship = 'chats';
    protected static ?string $title = 'Chat History';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('message')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('sender_name')
                            ->state(fn ($record) => $record->is_admin ? 'Admin Support' : $record->user->name)
                            ->weight('bold')
                            ->color(fn ($record) => $record->is_admin ? 'primary' : 'success'),
                        
                        TextColumn::make('created_at')
                            ->since()
                            ->color('gray')
                            ->alignRight(),
                    ]),
                    
                    TextColumn::make('message')
                        ->extraAttributes(fn ($record) => [
                            'class' => $record->is_admin 
                                ? 'bg-blue-50 p-3 rounded-lg mt-1'
                                : 'bg-gray-50 p-3 rounded-lg mt-1',
                        ])
                        ->html(),
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 1,
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Balas Pesan')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['is_admin'] = true;
                        $data['user_id'] = $this->ownerRecord->id;
                        return $data;
                    }),
            ])
            ->poll('5s');
    }
}
