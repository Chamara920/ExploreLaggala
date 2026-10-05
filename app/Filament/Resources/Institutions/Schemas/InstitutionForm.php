<?php

namespace App\Filament\Resources\Institutions\Schemas;

use App\Models\Institution;
use App\Models\InstitutionCustomSection;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class InstitutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 1. BASIC PROFILE
                Section::make('Basic Profile')
                    ->columns(2)
                    ->schema([
                        Select::make('type')
                            ->label('Institution Type')
                            ->options(Institution::TYPES)
                            ->default('divisional_secretariat')
                            ->required()
                            ->searchable(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'published' => 'Published',
                                'draft' => 'Draft',
                                'archived' => 'Archived',
                            ])
                            ->default('published')
                            ->required(),

                        Toggle::make('featured')
                            ->label('Featured Institution')
                            ->default(false),

                        TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        FileUpload::make('image_path')
                            ->label('Cover / Main Photo (Single Image Only)')
                            ->image()
                            ->disk('public')
                            ->directory('services/institutions')
                            ->maxFiles(1)
                            ->helperText('Upload a single photo of the institution building or premises.')
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->label('Primary Phone / Hotline')
                            ->tel()
                            ->placeholder('066 227 5200'),

                        TextInput::make('email')
                            ->label('Official Email')
                            ->email()
                            ->placeholder('info@laggala.ds.gov.lk'),

                        TextInput::make('website')
                            ->label('Official Website')
                            ->url()
                            ->placeholder('https://laggala.ds.gov.lk'),

                        TextInput::make('fax')
                            ->label('Fax Number')
                            ->placeholder('066 227 5201'),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->placeholder('e.g. 7.5250000')
                            ->helperText('Coordinates for displaying interactive map & Google Maps location'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->placeholder('e.g. 80.7420000')
                            ->helperText('Coordinates for displaying interactive map & Google Maps location'),
                    ]),

                // 2. MULTILINGUAL PROFILE (SINHALA, ENGLISH, TAMIL)
                Tabs::make('Translations')
                    ->tabs([
                        Tabs\Tab::make('Sinhala (සිංහල)')
                            ->schema([
                                TextInput::make('translation_si_name')
                                    ->label('Institution Name / ආයතනයේ නම')
                                    ->required(),

                                TextInput::make('translation_si_slug')
                                    ->label('Slug / යොමුව')
                                    ->required(),

                                TextInput::make('translation_si_location_name')
                                    ->label('Address / ස්ථානය හෝ ලිපිනය')
                                    ->placeholder('ප්‍රාදේශීය ලේකම් කාර්යාලය, ලග්ගල-පල්ලේගම'),

                                TextInput::make('translation_si_office_hours')
                                    ->label('Office Hours / කාර්යාල වේලාවන්')
                                    ->placeholder('සඳුදා - සිකුරාදා: පෙ.ව. 8:30 - ප.ව. 4:15'),

                                Textarea::make('translation_si_short_description')
                                    ->label('Short Description / කෙටි හැඳින්වීම')
                                    ->rows(3),

                                RichEditor::make('translation_si_description')
                                    ->label('Overview, Vision & Mandate / සවිස්තරාත්මක විස්තරය සහ දැක්ම'),
                            ]),

                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('translation_en_name')
                                    ->label('Institution Name')
                                    ->required(),

                                TextInput::make('translation_en_slug')
                                    ->label('Slug')
                                    ->required(),

                                TextInput::make('translation_en_location_name')
                                    ->label('Address / Location')
                                    ->placeholder('Divisional Secretariat, Laggala-Pallegama'),

                                TextInput::make('translation_en_office_hours')
                                    ->label('Office Hours')
                                    ->placeholder('Monday - Friday: 8:30 AM - 4:15 PM'),

                                Textarea::make('translation_en_short_description')
                                    ->label('Short Description')
                                    ->rows(3),

                                RichEditor::make('translation_en_description')
                                    ->label('Overview, Vision & Mandate'),
                            ]),

                        Tabs\Tab::make('Tamil (தமிழ்)')
                            ->schema([
                                TextInput::make('translation_ta_name')
                                    ->label('Institution Name / பெயர்'),

                                TextInput::make('translation_ta_slug')
                                    ->label('Slug'),

                                TextInput::make('translation_ta_location_name')
                                    ->label('Address / முகவரி'),

                                TextInput::make('translation_ta_office_hours')
                                    ->label('Office Hours / அலுவலக நேரம்'),

                                Textarea::make('translation_ta_short_description')
                                    ->label('Short Description / சுருக்கம்')
                                    ->rows(3),

                                RichEditor::make('translation_ta_description')
                                    ->label('Overview, Vision & Mandate / முழு விளக்கம்'),
                            ]),
                    ]),

                // 3. ORGANIZATIONAL STRUCTURE: UNITS / DEPARTMENTS
                Section::make('Organizational Structure (Departments & Units)')
                    ->description('Define organizational units dynamically (e.g. Administration, Accounts, Planning, Land Branch, Samurdhi, Field Officers).')
                    ->schema([
                        Repeater::make('units')
                            ->schema([
                                TextInput::make('name_en')
                                    ->label('Unit Name (English)')
                                    ->required(),

                                TextInput::make('name_si')
                                    ->label('Unit Name (Sinhala / සිංහල)')
                                    ->required(),

                                TextInput::make('name_ta')
                                    ->label('Unit Name (Tamil / தமிழ்)'),

                                TextInput::make('slug')
                                    ->label('Unit Identifier / Slug')
                                    ->required()
                                    ->helperText('Unique key (e.g. administration, accounts, land, planning, field-officers)'),

                                TextInput::make('parent_slug')
                                    ->label('Parent Unit Slug (Optional)')
                                    ->placeholder('e.g. field-officers')
                                    ->helperText('Leave empty for root units, or specify parent slug for hierarchical sub-units.'),

                                Textarea::make('description_si')
                                    ->label('Unit Scope / කාර්යභාරය (Sinhala)')
                                    ->rows(2),

                                Textarea::make('description_en')
                                    ->label('Unit Scope (English)')
                                    ->rows(2),

                                TextInput::make('sort_order')
                                    ->label('Order')
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('is_active')
                                    ->label('Active Unit')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => ($state['name_en'] ?? null) ?: 'Department / Unit')
                            ->addActionLabel('+ Add Department / Unit'),
                    ]),

                // 4. PUBLIC SERVICES DIRECTORY
                Section::make('Public Services Directory')
                    ->description('Specify public services provided by the institution and link them to their respective departments.')
                    ->schema([
                        Repeater::make('services')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label('Service Title (English)')
                                    ->required(),

                                TextInput::make('title_si')
                                    ->label('Service Title (Sinhala / සිංහල)')
                                    ->required(),

                                TextInput::make('title_ta')
                                    ->label('Service Title (Tamil / தமிழ்)'),

                                TextInput::make('unit_slug')
                                    ->label('Assigned Unit / Department Slug')
                                    ->placeholder('e.g. land, administration, accounts, samurdhi')
                                    ->helperText('Enter the slug of the Department that handles this service'),

                                TextInput::make('fee')
                                    ->label('Service Fee / ගාස්තුව')
                                    ->placeholder('e.g. Free / LKR 500 / Revenue Stamp LKR 100'),

                                TextInput::make('processing_time')
                                    ->label('Processing Time / ගතවන කාලය')
                                    ->placeholder('e.g. Same day / 3 working days / 14 days'),

                                Textarea::make('requirements_si')
                                    ->label('Required Documents & Criteria (Sinhala / අවශ්‍ය ලියකියවිලි)')
                                    ->rows(3),

                                Textarea::make('requirements_en')
                                    ->label('Required Documents & Criteria (English)')
                                    ->rows(3),

                                Textarea::make('description_si')
                                    ->label('Service Description (Sinhala)')
                                    ->rows(2),

                                Textarea::make('description_en')
                                    ->label('Service Description (English)')
                                    ->rows(2),

                                TextInput::make('sort_order')
                                    ->label('Order')
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('is_active')
                                    ->label('Active Service')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => ($state['title_en'] ?? null) ?: 'Service')
                            ->addActionLabel('+ Add Public Service'),
                    ]),

                // 5. OFFICERS DIRECTORY
                Section::make('Officers Directory')
                    ->description('Key personnel, heads of departments, and field officers with designations and contact info.')
                    ->schema([
                        Repeater::make('officers')
                            ->schema([
                                TextInput::make('name_en')
                                    ->label('Officer Name (English)')
                                    ->required(),

                                TextInput::make('name_si')
                                    ->label('Officer Name (Sinhala / සිංහල)')
                                    ->required(),

                                TextInput::make('name_ta')
                                    ->label('Officer Name (Tamil / தமிழ்)'),

                                TextInput::make('designation_en')
                                    ->label('Designation (English)')
                                    ->required(),

                                TextInput::make('designation_si')
                                    ->label('Designation (Sinhala / තනතුර)')
                                    ->required(),

                                TextInput::make('designation_ta')
                                    ->label('Designation (Tamil / பதவி)'),

                                TextInput::make('unit_slug')
                                    ->label('Department / Unit Slug')
                                    ->placeholder('e.g. administration, land, accounts, field-officers'),

                                FileUpload::make('photo')
                                    ->label('Photo (Optional)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('services/institutions/officers')
                                    ->maxFiles(1),

                                TextInput::make('phone')
                                    ->label('Direct Phone / Mobile')
                                    ->tel(),

                                TextInput::make('email')
                                    ->label('Official Email')
                                    ->email(),

                                TextInput::make('extension')
                                    ->label('Internal Intercom Extension')
                                    ->placeholder('e.g. Ext. 102'),

                                TextInput::make('working_hours')
                                    ->label('Visiting / Public Inquiry Hours')
                                    ->placeholder('Wednesdays: 9:00 AM - 12:00 PM'),

                                Textarea::make('responsibilities_si')
                                    ->label('Key Responsibilities / වගකීම් ක්ෂේත්‍ර (Sinhala)')
                                    ->rows(2),

                                Textarea::make('responsibilities_en')
                                    ->label('Key Responsibilities (English)')
                                    ->rows(2),

                                TextInput::make('sort_order')
                                    ->label('Order')
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('is_active')
                                    ->label('Active Officer')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => ($state['name_en'] ?? null) ? ($state['name_en'].' ('.($state['designation_en'] ?? '').')') : 'Officer')
                            ->addActionLabel('+ Add Officer'),
                    ]),

                // 6. HYBRID PAGE BUILDER: CUSTOM CONTENT SECTIONS
                Section::make('Hybrid Page Builder: Custom Content Sections')
                    ->description('Build flexible dynamic sections on the institution page (Text, Notice Banners, FAQs, Important Links, Citizen Charters, etc.).')
                    ->schema([
                        Repeater::make('custom_sections')
                            ->schema([
                                Select::make('type')
                                    ->label('Section Block Type')
                                    ->options(InstitutionCustomSection::SECTION_TYPES)
                                    ->default('text')
                                    ->required(),

                                TextInput::make('title_en')
                                    ->label('Section Title (English)'),

                                TextInput::make('title_si')
                                    ->label('Section Title (Sinhala / සිංහල)'),

                                TextInput::make('title_ta')
                                    ->label('Section Title (Tamil / தமிழ்)'),

                                RichEditor::make('content_si')
                                    ->label('Section Content (Sinhala / සිංහල)')
                                    ->columnSpanFull(),

                                RichEditor::make('content_en')
                                    ->label('Section Content (English)')
                                    ->columnSpanFull(),

                                Textarea::make('data')
                                    ->label('Structured Items / Configuration (Optional JSON)')
                                    ->placeholder('{"links": [{"title": "e-Pensions Portal", "url": "https://pensions.gov.lk"}]}')
                                    ->helperText('Optional JSON payload for links, FAQ items, or custom table columns')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                TextInput::make('sort_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('is_active')
                                    ->label('Display Section')
                                    ->default(true),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => ($state['title_en'] ?? null) ? ($state['title_en'].' ['.($state['type'] ?? 'block').']') : 'Content Section')
                            ->addActionLabel('+ Add Custom Content Block'),
                    ]),

                // 7. DOCUMENTS, CIRCULARS & DOWNLOADS
                Section::make('Documents, Forms & Circulars')
                    ->description('Citizen charter, application forms, gazettes, circulars, and downloadable PDFs.')
                    ->schema([
                        Repeater::make('documents')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label('Document Title (English)')
                                    ->required(),

                                TextInput::make('title_si')
                                    ->label('Document Title (Sinhala / සිංහල)')
                                    ->required(),

                                TextInput::make('title_ta')
                                    ->label('Document Title (Tamil / தமிழ்)'),

                                FileUpload::make('file_path')
                                    ->label('Upload File (PDF / DOC / Image)')
                                    ->disk('public')
                                    ->directory('services/institutions/documents')
                                    ->required(),

                                TextInput::make('unit_slug')
                                    ->label('Related Department Slug (Optional)')
                                    ->placeholder('e.g. land, administration'),

                                TextInput::make('description_si')
                                    ->label('Brief Note (Sinhala)'),

                                TextInput::make('description_en')
                                    ->label('Brief Note (English)'),

                                TextInput::make('sort_order')
                                    ->label('Order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => ($state['title_en'] ?? null) ?: 'Document')
                            ->addActionLabel('+ Add Document / Application Form'),
                    ]),

            ]);
    }
}
