<?php

namespace App\Filament\Resources\Destinations\Pages;

use App\Filament\Resources\Destinations\DestinationResource;
use App\Models\DestinationTranslation;
use Filament\Resources\Pages\CreateRecord;

class CreateDestination extends CreateRecord
{
    protected static string $resource = DestinationResource::class;

    protected array $translationData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
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

    protected function afterCreate(): void
    {
        foreach ($this->translationData as $locale => $translation) {
            if (blank($translation['name'])) {
                continue;
            }

            DestinationTranslation::create([
                'destination_id' => $this->record->id,
                'locale' => $locale,
                'name' => $translation['name'],
                'slug' => $translation['slug'],
                'short_description' => $translation['short_description'],
                'description' => $translation['description'],
                'location_name' => $translation['location_name'],
            ]);
        }
    }
}
