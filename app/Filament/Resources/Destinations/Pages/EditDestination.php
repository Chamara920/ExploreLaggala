<?php

namespace App\Filament\Resources\Destinations\Pages;

use App\Filament\Resources\Destinations\DestinationResource;
use App\Models\DestinationTranslation;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDestination extends EditRecord
{
    protected static string $resource = DestinationResource::class;

    protected array $translationData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record
            ->translations
            ->keyBy('locale');

        foreach (['si', 'en', 'ta'] as $locale) {
            $translation = $translations->get($locale);

            $data["translation_{$locale}_name"] =
                $translation?->name;

            $data["translation_{$locale}_slug"] =
                $translation?->slug;

            $data["translation_{$locale}_short_description"] =
                $translation?->short_description;

            $data["translation_{$locale}_description"] =
                $translation?->description;

            $data["translation_{$locale}_location_name"] =
                $translation?->location_name;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->translationData = [
            'si' => [
                'name' => $data['translation_si_name'] ?? null,
                'slug' => $data['translation_si_slug'] ?? null,
                'short_description' => $data['translation_si_short_description'] ?? null,
                'description' => $data['translation_si_description'] ?? null,
                'location_name' => $data['translation_si_location_name'] ?? null,
            ],

            'en' => [
                'name' => $data['translation_en_name'] ?? null,
                'slug' => $data['translation_en_slug'] ?? null,
                'short_description' => $data['translation_en_short_description'] ?? null,
                'description' => $data['translation_en_description'] ?? null,
                'location_name' => $data['translation_en_location_name'] ?? null,
            ],

            'ta' => [
                'name' => $data['translation_ta_name'] ?? null,
                'slug' => $data['translation_ta_slug'] ?? null,
                'short_description' => $data['translation_ta_short_description'] ?? null,
                'description' => $data['translation_ta_description'] ?? null,
                'location_name' => $data['translation_ta_location_name'] ?? null,
            ],
        ];

        unset(
            $data['translation_si_name'],
            $data['translation_si_slug'],
            $data['translation_si_short_description'],
            $data['translation_si_description'],
            $data['translation_si_location_name'],

            $data['translation_en_name'],
            $data['translation_en_slug'],
            $data['translation_en_short_description'],
            $data['translation_en_description'],
            $data['translation_en_location_name'],

            $data['translation_ta_name'],
            $data['translation_ta_slug'],
            $data['translation_ta_short_description'],
            $data['translation_ta_description'],
            $data['translation_ta_location_name']
        );

        return $data;
    }

    protected function afterSave(): void
    {
        foreach ($this->translationData as $locale => $translation) {
            if (blank($translation['name'])) {
                DestinationTranslation::where('destination_id', $this->record->id)
                    ->where('locale', $locale)
                    ->delete();

                continue;
            }

            DestinationTranslation::updateOrCreate(
                [
                    'destination_id' => $this->record->id,
                    'locale' => $locale,
                ],
                [
                    'name' => $translation['name'],
                    'slug' => $translation['slug'],
                    'short_description' => $translation['short_description'],
                    'description' => $translation['description'],
                    'location_name' => $translation['location_name'],
                ]
            );
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
