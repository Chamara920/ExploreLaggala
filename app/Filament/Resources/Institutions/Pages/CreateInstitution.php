<?php

namespace App\Filament\Resources\Institutions\Pages;

use App\Filament\Resources\Institutions\InstitutionResource;
use App\Models\InstitutionCustomSection;
use App\Models\InstitutionCustomSectionTranslation;
use App\Models\InstitutionDocument;
use App\Models\InstitutionDocumentTranslation;
use App\Models\InstitutionService;
use App\Models\InstitutionServiceTranslation;
use App\Models\InstitutionTranslation;
use App\Models\InstitutionUnit;
use App\Models\InstitutionUnitTranslation;
use App\Models\Officer;
use App\Models\OfficerTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateInstitution extends CreateRecord
{
    protected static string $resource = InstitutionResource::class;

    protected array $translationData = [];

    protected array $unitsData = [];

    protected array $servicesData = [];

    protected array $officersData = [];

    protected array $customSectionsData = [];

    protected array $documentsData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->translationData = [
            'si' => [
                'name' => $data['translation_si_name'] ?? null,
                'slug' => $data['translation_si_slug'] ?? null,
                'location_name' => $data['translation_si_location_name'] ?? null,
                'office_hours' => $data['translation_si_office_hours'] ?? null,
                'short_description' => $data['translation_si_short_description'] ?? null,
                'description' => $data['translation_si_description'] ?? null,
            ],
            'en' => [
                'name' => $data['translation_en_name'] ?? null,
                'slug' => $data['translation_en_slug'] ?? null,
                'location_name' => $data['translation_en_location_name'] ?? null,
                'office_hours' => $data['translation_en_office_hours'] ?? null,
                'short_description' => $data['translation_en_short_description'] ?? null,
                'description' => $data['translation_en_description'] ?? null,
            ],
            'ta' => [
                'name' => $data['translation_ta_name'] ?? null,
                'slug' => $data['translation_ta_slug'] ?? null,
                'location_name' => $data['translation_ta_location_name'] ?? null,
                'office_hours' => $data['translation_ta_office_hours'] ?? null,
                'short_description' => $data['translation_ta_short_description'] ?? null,
                'description' => $data['translation_ta_description'] ?? null,
            ],
        ];

        $this->unitsData = $data['units'] ?? [];
        $this->servicesData = $data['services'] ?? [];
        $this->officersData = $data['officers'] ?? [];
        $this->customSectionsData = $data['custom_sections'] ?? [];
        $this->documentsData = $data['documents'] ?? [];

        unset(
            $data['translation_si_name'],
            $data['translation_si_slug'],
            $data['translation_si_location_name'],
            $data['translation_si_office_hours'],
            $data['translation_si_short_description'],
            $data['translation_si_description'],

            $data['translation_en_name'],
            $data['translation_en_slug'],
            $data['translation_en_location_name'],
            $data['translation_en_office_hours'],
            $data['translation_en_short_description'],
            $data['translation_en_description'],

            $data['translation_ta_name'],
            $data['translation_ta_slug'],
            $data['translation_ta_location_name'],
            $data['translation_ta_office_hours'],
            $data['translation_ta_short_description'],
            $data['translation_ta_description'],

            $data['units'],
            $data['services'],
            $data['officers'],
            $data['custom_sections'],
            $data['documents']
        );

        return $data;
    }

    protected function afterCreate(): void
    {
        DB::transaction(function () {
            // 1. Translations
            foreach ($this->translationData as $locale => $translation) {
                if (blank($translation['name'])) {
                    continue;
                }

                $slug = ! empty($translation['slug']) ? Str::slug($translation['slug']) : Str::slug($translation['name']);
                InstitutionTranslation::create([
                    'institution_id' => $this->record->id,
                    'locale' => $locale,
                    'name' => $translation['name'],
                    'slug' => $slug,
                    'location_name' => $translation['location_name'],
                    'office_hours' => $translation['office_hours'],
                    'short_description' => $translation['short_description'],
                    'description' => $translation['description'],
                ]);
            }

            // 2. Units / Departments (first pass without parent, second pass with parent)
            $unitMap = []; // slug => unit_id
            foreach ($this->unitsData as $unitItem) {
                if (empty($unitItem['slug'])) {
                    continue;
                }
                $slug = Str::slug($unitItem['slug']);
                $unit = InstitutionUnit::create([
                    'institution_id' => $this->record->id,
                    'parent_id' => null,
                    'slug' => $slug,
                    'sort_order' => $unitItem['sort_order'] ?? 0,
                    'is_active' => $unitItem['is_active'] ?? true,
                ]);

                $unitMap[$slug] = $unit->id;

                foreach (['en', 'si', 'ta'] as $locale) {
                    if (! empty($unitItem["name_{$locale}"])) {
                        InstitutionUnitTranslation::create([
                            'institution_unit_id' => $unit->id,
                            'locale' => $locale,
                            'name' => $unitItem["name_{$locale}"],
                            'description' => $unitItem["description_{$locale}"] ?? null,
                        ]);
                    }
                }
            }

            // Update parent_id for units that defined parent_slug
            foreach ($this->unitsData as $unitItem) {
                if (! empty($unitItem['slug']) && ! empty($unitItem['parent_slug'])) {
                    $childSlug = Str::slug($unitItem['slug']);
                    $parentSlug = Str::slug($unitItem['parent_slug']);
                    if (isset($unitMap[$childSlug], $unitMap[$parentSlug])) {
                        InstitutionUnit::where('id', $unitMap[$childSlug])->update([
                            'parent_id' => $unitMap[$parentSlug],
                        ]);
                    }
                }
            }

            // 3. Services
            foreach ($this->servicesData as $serviceItem) {
                if (empty($serviceItem['title_en']) && empty($serviceItem['title_si'])) {
                    continue;
                }
                $unitId = null;
                if (! empty($serviceItem['unit_slug'])) {
                    $uSlug = Str::slug($serviceItem['unit_slug']);
                    $unitId = $unitMap[$uSlug] ?? null;
                }

                $service = InstitutionService::create([
                    'institution_id' => $this->record->id,
                    'unit_id' => $unitId,
                    'fee' => $serviceItem['fee'] ?? null,
                    'processing_time' => $serviceItem['processing_time'] ?? null,
                    'sort_order' => $serviceItem['sort_order'] ?? 0,
                    'is_active' => $serviceItem['is_active'] ?? true,
                ]);

                foreach (['en', 'si', 'ta'] as $locale) {
                    if (! empty($serviceItem["title_{$locale}"])) {
                        InstitutionServiceTranslation::create([
                            'institution_service_id' => $service->id,
                            'locale' => $locale,
                            'title' => $serviceItem["title_{$locale}"],
                            'description' => $serviceItem["description_{$locale}"] ?? null,
                            'requirements' => $serviceItem["requirements_{$locale}"] ?? null,
                        ]);
                    }
                }
            }

            // 4. Officers
            foreach ($this->officersData as $officerItem) {
                if (empty($officerItem['name_en']) && empty($officerItem['name_si'])) {
                    continue;
                }
                $unitId = null;
                if (! empty($officerItem['unit_slug'])) {
                    $uSlug = Str::slug($officerItem['unit_slug']);
                    $unitId = $unitMap[$uSlug] ?? null;
                }

                $officer = Officer::create([
                    'institution_id' => $this->record->id,
                    'unit_id' => $unitId,
                    'photo' => $officerItem['photo'] ?? null,
                    'phone' => $officerItem['phone'] ?? null,
                    'email' => $officerItem['email'] ?? null,
                    'extension' => $officerItem['extension'] ?? null,
                    'working_hours' => $officerItem['working_hours'] ?? null,
                    'sort_order' => $officerItem['sort_order'] ?? 0,
                    'is_active' => $officerItem['is_active'] ?? true,
                ]);

                foreach (['en', 'si', 'ta'] as $locale) {
                    if (! empty($officerItem["name_{$locale}"])) {
                        OfficerTranslation::create([
                            'officer_id' => $officer->id,
                            'locale' => $locale,
                            'name' => $officerItem["name_{$locale}"],
                            'designation' => $officerItem["designation_{$locale}"] ?? '',
                            'responsibilities' => $officerItem["responsibilities_{$locale}"] ?? null,
                        ]);
                    }
                }
            }

            // 5. Custom Content Sections
            foreach ($this->customSectionsData as $sectionItem) {
                $dataArray = null;
                if (! empty($sectionItem['data']) && is_string($sectionItem['data'])) {
                    $decoded = json_decode($sectionItem['data'], true);
                    $dataArray = json_last_error() === JSON_ERROR_NONE ? $decoded : ['raw' => $sectionItem['data']];
                }

                $section = InstitutionCustomSection::create([
                    'institution_id' => $this->record->id,
                    'type' => $sectionItem['type'] ?? 'text',
                    'data' => $dataArray,
                    'sort_order' => $sectionItem['sort_order'] ?? 0,
                    'is_active' => $sectionItem['is_active'] ?? true,
                ]);

                foreach (['en', 'si', 'ta'] as $locale) {
                    if (! empty($sectionItem["title_{$locale}"]) || ! empty($sectionItem["content_{$locale}"])) {
                        InstitutionCustomSectionTranslation::create([
                            'institution_custom_section_id' => $section->id,
                            'locale' => $locale,
                            'title' => $sectionItem["title_{$locale}"] ?? '',
                            'content' => $sectionItem["content_{$locale}"] ?? null,
                        ]);
                    }
                }
            }

            // 6. Documents
            foreach ($this->documentsData as $docItem) {
                if (empty($docItem['file_path'])) {
                    continue;
                }
                $unitId = null;
                if (! empty($docItem['unit_slug'])) {
                    $uSlug = Str::slug($docItem['unit_slug']);
                    $unitId = $unitMap[$uSlug] ?? null;
                }

                $filePath = $docItem['file_path'];
                $ext = pathinfo($filePath, PATHINFO_EXTENSION);

                $document = InstitutionDocument::create([
                    'institution_id' => $this->record->id,
                    'unit_id' => $unitId,
                    'file_path' => $filePath,
                    'file_type' => strtoupper($ext),
                    'file_size' => null,
                    'sort_order' => $docItem['sort_order'] ?? 0,
                ]);

                foreach (['en', 'si', 'ta'] as $locale) {
                    if (! empty($docItem["title_{$locale}"])) {
                        InstitutionDocumentTranslation::create([
                            'institution_document_id' => $document->id,
                            'locale' => $locale,
                            'title' => $docItem["title_{$locale}"],
                            'description' => $docItem["description_{$locale}"] ?? null,
                        ]);
                    }
                }
            }
        });
    }
}
