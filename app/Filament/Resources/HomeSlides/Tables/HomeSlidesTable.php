<?php

namespace App\Filament\Resources\HomeSlides\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomeSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->getStateUsing(fn ($record) => $record->image_path ?: $record->resolved_image_url)
                    ->circular(false)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover w-20 h-12']),

                TextColumn::make('translations.title')
                    ->label('Title')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('translations.badge')
                    ->label('Badge')
                    ->limit(30),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
