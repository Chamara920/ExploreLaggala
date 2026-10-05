<?php

namespace App\Filament\Resources\HomeSlides\Pages;

use App\Filament\Resources\HomeSlides\HomeSlideResource;
use App\Models\HomeSlideTranslation;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeSlide extends EditRecord
{
    protected static string $resource = HomeSlideResource::class;

    protected array $translationData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record->translations->keyBy('locale');

        foreach (['si', 'en', 'ta'] as $locale) {
            $translation = $translations->get($locale);

            $data["translation_{$locale}_badge"] = $translation?->badge;
            $data["translation_{$locale}_title"] = $translation?->title;
            $data["translation_{$locale}_subtitle"] = $translation?->subtitle;
            $data["translation_{$locale}_primary_button_text"] = $translation?->primary_button_text;
            $data["translation_{$locale}_secondary_button_text"] = $translation?->secondary_button_text;
            $data["translation_{$locale}_planner_button_text"] = $translation?->planner_button_text;
            $data["translation_{$locale}_planner_button_subtitle"] = $translation?->planner_button_subtitle;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->translationData = [
            'si' => [
                'badge' => $data['translation_si_badge'] ?? null,
                'title' => $data['translation_si_title'] ?? null,
                'subtitle' => $data['translation_si_subtitle'] ?? null,
                'primary_button_text' => $data['translation_si_primary_button_text'] ?? null,
                'secondary_button_text' => $data['translation_si_secondary_button_text'] ?? null,
                'planner_button_text' => $data['translation_si_planner_button_text'] ?? null,
                'planner_button_subtitle' => $data['translation_si_planner_button_subtitle'] ?? null,
            ],
            'en' => [
                'badge' => $data['translation_en_badge'] ?? null,
                'title' => $data['translation_en_title'] ?? null,
                'subtitle' => $data['translation_en_subtitle'] ?? null,
                'primary_button_text' => $data['translation_en_primary_button_text'] ?? null,
                'secondary_button_text' => $data['translation_en_secondary_button_text'] ?? null,
                'planner_button_text' => $data['translation_en_planner_button_text'] ?? null,
                'planner_button_subtitle' => $data['translation_en_planner_button_subtitle'] ?? null,
            ],
            'ta' => [
                'badge' => $data['translation_ta_badge'] ?? null,
                'title' => $data['translation_ta_title'] ?? null,
                'subtitle' => $data['translation_ta_subtitle'] ?? null,
                'primary_button_text' => $data['translation_ta_primary_button_text'] ?? null,
                'secondary_button_text' => $data['translation_ta_secondary_button_text'] ?? null,
                'planner_button_text' => $data['translation_ta_planner_button_text'] ?? null,
                'planner_button_subtitle' => $data['translation_ta_planner_button_subtitle'] ?? null,
            ],
        ];

        foreach (['si', 'en', 'ta'] as $locale) {
            unset(
                $data["translation_{$locale}_badge"],
                $data["translation_{$locale}_title"],
                $data["translation_{$locale}_subtitle"],
                $data["translation_{$locale}_primary_button_text"],
                $data["translation_{$locale}_secondary_button_text"],
                $data["translation_{$locale}_planner_button_text"],
                $data["translation_{$locale}_planner_button_subtitle"]
            );
        }

        return $data;
    }

    protected function afterSave(): void
    {
        foreach ($this->translationData as $locale => $translation) {
            if (blank($translation['title'])) {
                HomeSlideTranslation::where('home_slide_id', $this->record->id)
                    ->where('locale', $locale)
                    ->delete();

                continue;
            }

            HomeSlideTranslation::updateOrCreate(
                ['home_slide_id' => $this->record->id, 'locale' => $locale],
                $translation
            );
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
