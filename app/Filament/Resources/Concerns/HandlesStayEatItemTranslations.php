<?php

namespace App\Filament\Resources\Concerns;

use App\Models\StayEatItemTranslation;

trait HandlesStayEatItemTranslations
{
    protected array $translationData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record
            ->translations
            ->keyBy('locale');

        foreach (['si', 'en', 'ta'] as $locale) {
            $translation = $translations->get($locale);

            $data["translation_{$locale}_title"] = $translation?->title;
            $data["translation_{$locale}_slug"] = $translation?->slug;
            $data["translation_{$locale}_short_description"] = $translation?->short_description;
            $data["translation_{$locale}_description"] = $translation?->description;
            $data["translation_{$locale}_location_name"] = $translation?->location_name;
            $data["translation_{$locale}_opening_hours"] = $translation?->opening_hours;
        }

        return $data;
    }

    protected function extractTranslationData(array $data, ?string $section = null): array
    {
        if ($section) {
            $data['section'] = $section;
        }

        $this->translationData = [
            'si' => [
                'title' => $data['translation_si_title'] ?? null,
                'slug' => $data['translation_si_slug'] ?? null,
                'short_description' => $data['translation_si_short_description'] ?? null,
                'description' => $data['translation_si_description'] ?? null,
                'location_name' => $data['translation_si_location_name'] ?? null,
                'opening_hours' => $data['translation_si_opening_hours'] ?? null,
            ],
            'en' => [
                'title' => $data['translation_en_title'] ?? null,
                'slug' => $data['translation_en_slug'] ?? null,
                'short_description' => $data['translation_en_short_description'] ?? null,
                'description' => $data['translation_en_description'] ?? null,
                'location_name' => $data['translation_en_location_name'] ?? null,
                'opening_hours' => $data['translation_en_opening_hours'] ?? null,
            ],
            'ta' => [
                'title' => $data['translation_ta_title'] ?? null,
                'slug' => $data['translation_ta_slug'] ?? null,
                'short_description' => $data['translation_ta_short_description'] ?? null,
                'description' => $data['translation_ta_description'] ?? null,
                'location_name' => $data['translation_ta_location_name'] ?? null,
                'opening_hours' => $data['translation_ta_opening_hours'] ?? null,
            ],
        ];

        unset(
            $data['translation_si_title'],
            $data['translation_si_slug'],
            $data['translation_si_short_description'],
            $data['translation_si_description'],
            $data['translation_si_location_name'],
            $data['translation_si_opening_hours'],
            $data['translation_en_title'],
            $data['translation_en_slug'],
            $data['translation_en_short_description'],
            $data['translation_en_description'],
            $data['translation_en_location_name'],
            $data['translation_en_opening_hours'],
            $data['translation_ta_title'],
            $data['translation_ta_slug'],
            $data['translation_ta_short_description'],
            $data['translation_ta_description'],
            $data['translation_ta_location_name'],
            $data['translation_ta_opening_hours']
        );

        return $data;
    }

    protected function saveTranslations(): void
    {
        foreach ($this->translationData as $locale => $translation) {
            if (blank($translation['title'])) {
                StayEatItemTranslation::where('stay_eat_item_id', $this->record->id)
                    ->where('locale', $locale)
                    ->delete();

                continue;
            }

            StayEatItemTranslation::updateOrCreate(
                [
                    'stay_eat_item_id' => $this->record->id,
                    'locale' => $locale,
                ],
                [
                    'title' => $translation['title'],
                    'slug' => $translation['slug'],
                    'short_description' => $translation['short_description'],
                    'description' => $translation['description'],
                    'location_name' => $translation['location_name'],
                    'opening_hours' => $translation['opening_hours'],
                ]
            );
        }
    }
}
