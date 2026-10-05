<?php

namespace App\Filament\Resources\HomePageSettings\Pages;

use App\Filament\Resources\HomePageSettings\HomePageSettingResource;
use App\Models\HomePageSettingTranslation;
use Filament\Resources\Pages\EditRecord;

class EditHomePageSetting extends EditRecord
{
    protected static string $resource = HomePageSettingResource::class;

    protected array $translationData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record->translations->keyBy('locale');

        foreach (['si', 'en', 'ta'] as $locale) {
            $translation = $translations->get($locale);

            $data["translation_{$locale}_destinations_title"] = $translation?->destinations_title;
            $data["translation_{$locale}_destinations_subtitle"] = $translation?->destinations_subtitle;
            $data["translation_{$locale}_blog_title"] = $translation?->blog_title;
            $data["translation_{$locale}_blog_subtitle"] = $translation?->blog_subtitle;
            $data["translation_{$locale}_news_title"] = $translation?->news_title;
            $data["translation_{$locale}_news_subtitle"] = $translation?->news_subtitle;
            $data["translation_{$locale}_events_title"] = $translation?->events_title;
            $data["translation_{$locale}_events_subtitle"] = $translation?->events_subtitle;
            $data["translation_{$locale}_weather_title"] = $translation?->weather_title;
            $data["translation_{$locale}_weather_subtitle"] = $translation?->weather_subtitle;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->translationData = [
            'si' => [
                'destinations_title' => $data['translation_si_destinations_title'] ?? null,
                'destinations_subtitle' => $data['translation_si_destinations_subtitle'] ?? null,
                'blog_title' => $data['translation_si_blog_title'] ?? null,
                'blog_subtitle' => $data['translation_si_blog_subtitle'] ?? null,
                'news_title' => $data['translation_si_news_title'] ?? null,
                'news_subtitle' => $data['translation_si_news_subtitle'] ?? null,
                'events_title' => $data['translation_si_events_title'] ?? null,
                'events_subtitle' => $data['translation_si_events_subtitle'] ?? null,
                'weather_title' => $data['translation_si_weather_title'] ?? null,
                'weather_subtitle' => $data['translation_si_weather_subtitle'] ?? null,
            ],
            'en' => [
                'destinations_title' => $data['translation_en_destinations_title'] ?? null,
                'destinations_subtitle' => $data['translation_en_destinations_subtitle'] ?? null,
                'blog_title' => $data['translation_en_blog_title'] ?? null,
                'blog_subtitle' => $data['translation_en_blog_subtitle'] ?? null,
                'news_title' => $data['translation_en_news_title'] ?? null,
                'news_subtitle' => $data['translation_en_news_subtitle'] ?? null,
                'events_title' => $data['translation_en_events_title'] ?? null,
                'events_subtitle' => $data['translation_en_events_subtitle'] ?? null,
                'weather_title' => $data['translation_en_weather_title'] ?? null,
                'weather_subtitle' => $data['translation_en_weather_subtitle'] ?? null,
            ],
            'ta' => [
                'destinations_title' => $data['translation_ta_destinations_title'] ?? null,
                'destinations_subtitle' => $data['translation_ta_destinations_subtitle'] ?? null,
                'blog_title' => $data['translation_ta_blog_title'] ?? null,
                'blog_subtitle' => $data['translation_ta_blog_subtitle'] ?? null,
                'news_title' => $data['translation_ta_news_title'] ?? null,
                'news_subtitle' => $data['translation_ta_news_subtitle'] ?? null,
                'events_title' => $data['translation_ta_events_title'] ?? null,
                'events_subtitle' => $data['translation_ta_events_subtitle'] ?? null,
                'weather_title' => $data['translation_ta_weather_title'] ?? null,
                'weather_subtitle' => $data['translation_ta_weather_subtitle'] ?? null,
            ],
        ];

        foreach (['si', 'en', 'ta'] as $locale) {
            unset(
                $data["translation_{$locale}_destinations_title"],
                $data["translation_{$locale}_destinations_subtitle"],
                $data["translation_{$locale}_blog_title"],
                $data["translation_{$locale}_blog_subtitle"],
                $data["translation_{$locale}_news_title"],
                $data["translation_{$locale}_news_subtitle"],
                $data["translation_{$locale}_events_title"],
                $data["translation_{$locale}_events_subtitle"],
                $data["translation_{$locale}_weather_title"],
                $data["translation_{$locale}_weather_subtitle"]
            );
        }

        return $data;
    }

    protected function afterSave(): void
    {
        foreach ($this->translationData as $locale => $translation) {
            HomePageSettingTranslation::updateOrCreate(
                ['home_page_setting_id' => $this->record->id, 'locale' => $locale],
                $translation
            );
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
