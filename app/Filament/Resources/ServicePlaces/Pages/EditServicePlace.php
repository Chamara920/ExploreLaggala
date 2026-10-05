<?php

namespace App\Filament\Resources\ServicePlaces\Pages;

use App\Filament\Resources\ServicePlaces\ServicePlaceResource;
use App\Models\ServicePlaceTranslation;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditServicePlace extends EditRecord
{
    protected static string $resource = ServicePlaceResource::class;

    protected array $translationData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record->translations->keyBy('locale');

        foreach (['si', 'en', 'ta'] as $locale) {
            $t = $translations->get($locale);
            $data["translation_{$locale}_name"] = $t?->name;
            $data["translation_{$locale}_slug"] = $t?->slug;
            $data["translation_{$locale}_location_name"] = $t?->location_name;
            $data["translation_{$locale}_operating_hours"] = $t?->operating_hours;
            $data["translation_{$locale}_key_facilities"] = $t?->key_facilities;
            $data["translation_{$locale}_short_description"] = $t?->short_description;
            $data["translation_{$locale}_description"] = $t?->description;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->translationData = [
            'si' => [
                'name' => $data['translation_si_name'] ?? null,
                'slug' => $data['translation_si_slug'] ?? null,
                'location_name' => $data['translation_si_location_name'] ?? null,
                'operating_hours' => $data['translation_si_operating_hours'] ?? null,
                'key_facilities' => $data['translation_si_key_facilities'] ?? null,
                'short_description' => $data['translation_si_short_description'] ?? null,
                'description' => $data['translation_si_description'] ?? null,
            ],
            'en' => [
                'name' => $data['translation_en_name'] ?? null,
                'slug' => $data['translation_en_slug'] ?? null,
                'location_name' => $data['translation_en_location_name'] ?? null,
                'operating_hours' => $data['translation_en_operating_hours'] ?? null,
                'key_facilities' => $data['translation_en_key_facilities'] ?? null,
                'short_description' => $data['translation_en_short_description'] ?? null,
                'description' => $data['translation_en_description'] ?? null,
            ],
            'ta' => [
                'name' => $data['translation_ta_name'] ?? null,
                'slug' => $data['translation_ta_slug'] ?? null,
                'location_name' => $data['translation_ta_location_name'] ?? null,
                'operating_hours' => $data['translation_ta_operating_hours'] ?? null,
                'key_facilities' => $data['translation_ta_key_facilities'] ?? null,
                'short_description' => $data['translation_ta_short_description'] ?? null,
                'description' => $data['translation_ta_description'] ?? null,
            ],
        ];

        unset(
            $data['translation_si_name'],
            $data['translation_si_slug'],
            $data['translation_si_location_name'],
            $data['translation_si_operating_hours'],
            $data['translation_si_key_facilities'],
            $data['translation_si_short_description'],
            $data['translation_si_description'],

            $data['translation_en_name'],
            $data['translation_en_slug'],
            $data['translation_en_location_name'],
            $data['translation_en_operating_hours'],
            $data['translation_en_key_facilities'],
            $data['translation_en_short_description'],
            $data['translation_en_description'],

            $data['translation_ta_name'],
            $data['translation_ta_slug'],
            $data['translation_ta_location_name'],
            $data['translation_ta_operating_hours'],
            $data['translation_ta_key_facilities'],
            $data['translation_ta_short_description'],
            $data['translation_ta_description']
        );

        return $data;
    }

    protected function afterSave(): void
    {
        foreach ($this->translationData as $locale => $translation) {
            if (blank($translation['name'])) {
                ServicePlaceTranslation::where('service_place_id', $this->record->id)
                    ->where('locale', $locale)
                    ->delete();

                continue;
            }

            $slug = ! empty($translation['slug']) ? Str::slug($translation['slug']) : Str::slug($translation['name']);

            ServicePlaceTranslation::updateOrCreate(
                [
                    'service_place_id' => $this->record->id,
                    'locale' => $locale,
                ],
                [
                    'name' => $translation['name'],
                    'slug' => $slug,
                    'location_name' => $translation['location_name'],
                    'operating_hours' => $translation['operating_hours'],
                    'key_facilities' => $translation['key_facilities'],
                    'short_description' => $translation['short_description'],
                    'description' => $translation['description'],
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
