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
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EditInstitution extends EditRecord
{
    protected static string $resource = InstitutionResource::class;

    protected array $translationData = [];

    protected array $unitsData = [];

    protected array $servicesData = [];

    protected array $officersData = [];

    protected array $customSectionsData = [];

    protected array $documentsData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // 1. Translations
        $translations = $this->record->translations->keyBy('locale');
        foreach (['si', 'en', 'ta'] as $locale) {
            $t = $translations->get($locale);
            $data["translation_{$locale}_name"] = $t?->name;
            $data["translation_{$locale}_slug"] = $t?->slug;
            $data["translation_{$locale}_location_name"] = $t?->location_name;
            $data["translation_{$locale}_office_hours"] = $t?->office_hours;
            $data["translation_{$locale}_short_description"] = $t?->short_description;
            $data["translation_{$locale}_description"] = $t?->description;
        }

        // 2. Units
        $units = $this->record->units()->with(['translations', 'parent'])->orderBy('sort_order')->get();
        $data['units'] = $units->map(function (InstitutionUnit $unit) {
            $tEn = $unit->translationFor('en');
            $tSi = $unit->translationFor('si');
            $tTa = $unit->translationFor('ta');

            return [
                'name_en' => $tEn?->name,
                'name_si' => $tSi?->name,
                'name_ta' => $tTa?->name,
                'slug' => $unit->slug,
                'parent_slug' => $unit->parent?->slug,
                'description_si' => $tSi?->description,
                'description_en' => $tEn?->description,
                'sort_order' => $unit->sort_order,
                'is_active' => (bool) $unit->is_active,
            ];
        })->toArray();

        // 3. Services
        $services = $this->record->services()->with(['translations', 'unit'])->orderBy('sort_order')->get();
        $data['services'] = $services->map(function (InstitutionService $service) {
            $tEn = $service->translationFor('en');
            $tSi = $service->translationFor('si');
            $tTa = $service->translationFor('ta');

            return [
                'title_en' => $tEn?->title,
                'title_si' => $tSi?->title,
                'title_ta' => $tTa?->title,
                'unit_slug' => $service->unit?->slug,
                'fee' => $service->fee,
                'processing_time' => $service->processing_time,
                'requirements_si' => $tSi?->requirements,
                'requirements_en' => $tEn?->requirements,
                'description_si' => $tSi?->description,
                'description_en' => $tEn?->description,
                'sort_order' => $service->sort_order,
                'is_active' => (bool) $service->is_active,
            ];
        })->toArray();

        // 4. Officers
        $officers = $this->record->officers()->with(['translations', 'unit'])->orderBy('sort_order')->get();
        $data['officers'] = $officers->map(function (Officer $officer) {
            $tEn = $officer->translationFor('en');
            $tSi = $officer->translationFor('si');
            $tTa = $officer->translationFor('ta');

            return [
                'name_en' => $tEn?->name,
                'name_si' => $tSi?->name,
                'name_ta' => $tTa?->name,
                'designation_en' => $tEn?->designation,
                'designation_si' => $tSi?->designation,
                'designation_ta' => $tTa?->designation,
                'unit_slug' => $officer->unit?->slug,
                'photo' => $officer->photo,
                'phone' => $officer->phone,
                'email' => $officer->email,
                'extension' => $officer->extension,
                'working_hours' => $officer->working_hours,
                'responsibilities_si' => $tSi?->responsibilities,
                'responsibilities_en' => $tEn?->responsibilities,
                'sort_order' => $officer->sort_order,
                'is_active' => (bool) $officer->is_active,
            ];
        })->toArray();

        // 5. Custom Sections
        $sections = $this->record->customSections()->with('translations')->orderBy('sort_order')->get();
        $data['custom_sections'] = $sections->map(function (InstitutionCustomSection $sec) {
            $tEn = $sec->translationFor('en');
            $tSi = $sec->translationFor('si');
            $tTa = $sec->translationFor('ta');

            return [
                'type' => $sec->type,
                'title_en' => $tEn?->title,
                'title_si' => $tSi?->title,
                'title_ta' => $tTa?->title,
                'content_si' => $tSi?->content,
                'content_en' => $tEn?->content,
                'data' => ! empty($sec->data) ? json_encode($sec->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : null,
                'sort_order' => $sec->sort_order,
                'is_active' => (bool) $sec->is_active,
            ];
        })->toArray();

        // 6. Documents
        $documents = $this->record->documents()->with(['translations', 'unit'])->orderBy('sort_order')->get();
        $data['documents'] = $documents->map(function (InstitutionDocument $doc) {
            $tEn = $doc->translationFor('en');
            $tSi = $doc->translationFor('si');
            $tTa = $doc->translationFor('ta');

            return [
                'title_en' => $tEn?->title,
                'title_si' => $tSi?->title,
                'title_ta' => $tTa?->title,
                'file_path' => $doc->file_path,
                'unit_slug' => $doc->unit?->slug,
                'description_si' => $tSi?->description,
                'description_en' => $tEn?->description,
                'sort_order' => $doc->sort_order,
            ];
        })->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        DB::transaction(function () {
            // 1. Translations update/delete
            foreach ($this->translationData as $locale => $translation) {
                if (blank($translation['name'])) {
                    InstitutionTranslation::where('institution_id', $this->record->id)
                        ->where('locale', $locale)
                        ->delete();

                    continue;
                }

                $slug = ! empty($translation['slug']) ? Str::slug($translation['slug']) : Str::slug($translation['name']);
                InstitutionTranslation::updateOrCreate(
                    [
                        'institution_id' => $this->record->id,
                        'locale' => $locale,
                    ],
                    [
                        'name' => $translation['name'],
                        'slug' => $slug,
                        'location_name' => $translation['location_name'],
                        'office_hours' => $translation['office_hours'],
                        'short_description' => $translation['short_description'],
                        'description' => $translation['description'],
                    ]
                );
            }

            // Clean previous child records to sync repeaters cleanly
            // (units, services, officers, custom_sections, documents)
            $this->record->units()->delete();
            $this->record->services()->delete();
            $this->record->officers()->delete();
            $this->record->customSections()->delete();
            $this->record->documents()->delete();

            // 2. Units / Departments
            $unitMap = [];
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

            // Parent unit links
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

            // 5. Custom Sections
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

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
