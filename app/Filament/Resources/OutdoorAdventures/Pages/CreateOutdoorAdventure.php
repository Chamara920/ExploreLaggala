<?php

namespace App\Filament\Resources\OutdoorAdventures\Pages;

use App\Filament\Resources\OutdoorAdventures\OutdoorAdventureResource;
use App\Models\ExploreItemTranslation;
use Filament\Resources\Pages\CreateRecord;

class CreateOutdoorAdventure extends CreateRecord
{
    protected static string $resource = OutdoorAdventureResource::class;

    protected array $translationData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'outdoor-adventure';

        $this->translationData = [
            'si' => [
                'title' => $data['translation_si_title'] ?? null,
                'slug' => $data['translation_si_slug'] ?? null,
                'short_description' => $data['translation_si_short_description'] ?? null,
                'description' => $data['translation_si_description'] ?? null,
                'location_name' => $data['translation_si_location_name'] ?? null,
            ],
            'en' => [
                'title' => $data['translation_en_title'] ?? null,
                'slug' => $data['translation_en_slug'] ?? null,
                'short_description' => $data['translation_en_short_description'] ?? null,
                'description' => $data['translation_en_description'] ?? null,
                'location_name' => $data['translation_en_location_name'] ?? null,
            ],
            'ta' => [
                'title' => $data['translation_ta_title'] ?? null,
                'slug' => $data['translation_ta_slug'] ?? null,
                'short_description' => $data['translation_ta_short_description'] ?? null,
                'description' => $data['translation_ta_description'] ?? null,
                'location_name' => $data['translation_ta_location_name'] ?? null,
            ],
        ];

        unset(
            $data['translation_si_title'],
            $data['translation_si_slug'],
            $data['translation_si_short_description'],
            $data['translation_si_description'],
            $data['translation_si_location_name'],
            $data['translation_en_title'],
            $data['translation_en_slug'],
            $data['translation_en_short_description'],
            $data['translation_en_description'],
            $data['translation_en_location_name'],
            $data['translation_ta_title'],
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
            if (blank($translation['title'])) {
                continue;
            }

            ExploreItemTranslation::create([
                'explore_item_id' => $this->record->id,
                'locale' => $locale,
                'title' => $translation['title'],
                'slug' => $translation['slug'],
                'short_description' => $translation['short_description'],
                'description' => $translation['description'],
                'location_name' => $translation['location_name'],
            ]);
        }
    }
}
