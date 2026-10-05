<?php

namespace App\Filament\Resources\CultureHeritages\Pages;

use App\Filament\Resources\CultureHeritages\CultureHeritageResource;
use App\Models\ExploreItemTranslation;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCultureHeritage extends EditRecord
{
    protected static string $resource = CultureHeritageResource::class;

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
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

    protected function afterSave(): void
    {
        foreach ($this->translationData as $locale => $translation) {
            if (blank($translation['title'])) {
                ExploreItemTranslation::where('explore_item_id', $this->record->id)
                    ->where('locale', $locale)
                    ->delete();

                continue;
            }

            ExploreItemTranslation::updateOrCreate(
                [
                    'explore_item_id' => $this->record->id,
                    'locale' => $locale,
                ],
                [
                    'title' => $translation['title'],
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
