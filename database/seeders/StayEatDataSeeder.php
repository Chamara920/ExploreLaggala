<?php

namespace Database\Seeders;

use App\Models\StayEatCategory;
use App\Models\StayEatItem;
use App\Models\StayEatItemImage;
use App\Models\StayEatItemReview;
use App\Models\StayEatItemTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StayEatDataSeeder extends Seeder
{
    public function run(): void
    {
        $categoriesData = [
            // 🏨 Accommodation
            'accommodation' => [
                'Hotels & Resorts',
                'Guest Houses',
                'Homestays',
                'Villas & Bungalows',
                'Eco Lodges',
                'Camping',
            ],
            // 🍽️ Restaurants & Cafés
            'restaurants-cafes' => [
                'Restaurants',
                'Cafés',
                'Family Dining',
                'Takeaway / Food Delivery',
            ],
            // 🍛 Local Food
            'local-food' => [
                'Traditional Meals',
                'Village Food',
                'Local Snacks',
                'Traditional Drinks',
                'Food Experiences',
            ],
            // 🏕️ Outdoor Dining & Catering
            'outdoor-dining' => [
                'Picnic Meals',
                'Outdoor Catering',
                'Camp Meals',
                'Group / Event Catering',
            ],
        ];

        $categoryMap = [];

        foreach ($categoriesData as $section => $categories) {
            foreach ($categories as $index => $catName) {
                $category = StayEatCategory::firstOrCreate(
                    [
                        'section' => $section,
                        'slug' => Str::slug($catName),
                    ],
                    [
                        'name' => $catName,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );

                $categoryMap[$section][$catName] = $category->id;
            }
        }

        $adminUser = User::role('admin')->first() ?? User::first();

        // Sample available image paths in storage
        $sampleImages = [
            'explore/01M3780990Z0C7PF1MF32FNCSJ.jpg',
            'explore/01M378099CK2ET20013MCSCJW5.jpg',
            'explore/01M378099KZ4RTM049K0AR82F1.jpg',
            'explore/01M3BXN0718Y0WT97JHQ23Z0AZ.webp',
            'explore/01M3BXN079JSXGNBPSG2ZXTWJD.webp',
        ];

        // Sample Places
        $places = [
            // 1. ACCOMMODATION
            [
                'section' => 'accommodation',
                'category_id' => $categoryMap['accommodation']['Eco Lodges'] ?? null,
                'featured' => true,
                'price_range' => 'LKR 8,500 - 18,000',
                'phone' => '+94 66 227 4589',
                'email' => 'stay@knucklesviewlodge.lk',
                'website' => 'https://knucklesviewlodge.lk',
                'latitude' => 7.5312,
                'longitude' => 80.7431,
                'sort_order' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'Knuckles Mist Mountain Eco Lodge',
                        'slug' => 'knuckles-mist-mountain-eco-lodge',
                        'location_name' => 'Riverston Road, Laggala',
                        'opening_hours' => 'Check-in: 2:00 PM | Check-out: 11:00 AM',
                        'short_description' => 'A tranquil eco retreat perched on the misty slopes of Knuckles with panoramic mountain views and natural spring water.',
                        'description' => '<p>Immerse yourself in virgin rainforest breezes and pristine mountain mornings at Knuckles Mist Mountain Eco Lodge. Featuring sustainable timber chalets, solar-powered comforts, and organic farm-to-table dining overlooking the Riverston gap.</p><p>Guests enjoy guided nature trails, bird watching decks, and cosy campfire evenings under starry skies.</p>',
                    ],
                    'si' => [
                        'title' => 'නකල්ස් මිස්ට් මවුන්ටන් පරිසර ලැගුම්හල',
                        'slug' => 'knuckles-mist-mountain-eco-lodge',
                        'location_name' => 'රිවස්ටන් පාර, ලග්ගල',
                        'opening_hours' => 'පැමිණීම: ප.ව. 2:00 | පිටවීම: පෙ.ව. 11:00',
                        'short_description' => 'නකල්ස් කඳුකරයේ මනරම් නිම්නයකට මුහුණලා පිහිටි ස්වභාවික පරිසර හිතකාමී සුවපහසු නවාතැනකි.',
                        'description' => '<p>නකල්ස් කඳුවැටියේ සුන්දරත්වය හා මීදුම් සළුව මැද පිහිටි පරිසර හිතකාමී සුවපහසු කුටි සංකීර්ණයකි. ස්වභාවික දිය දහරාවන්, පක්ෂි නැරඹුම් ස්ථාන සහ නැවුම් ගම්බද ආහාර වේල් සමඟින් නිස්කලංක නිවාඩුවක් ගත කිරීමට කදිම තෝතැන්නකි.</p>',
                    ],
                    'ta' => [
                        'title' => 'நக்கிள்ஸ் மிஸ்ட் மவுண்டன் சுற்றுச்சூழல் லாட்ஜ்',
                        'slug' => 'knuckles-mist-mountain-eco-lodge',
                        'location_name' => 'ரிவர்ஸ்டன் வீதி, லக்கல',
                        'opening_hours' => 'வருகை: பிற்பகல் 2:00 | புறப்பாடு: முற்பகல் 11:00',
                        'short_description' => 'நக்கிள்ஸ் மலைகளின் எழில்மிகு காட்சிகளுடன் கூடிய அமைதியான சூழல் நட்பு தங்குமிடம்.',
                        'description' => '<p>இயற்கையான சூழலில் மரத்தாலான குடில்கள், பறவைகள் பார்க்கும் தளங்கள் மற்றும் இயற்கையான நீரூற்றுடன் அமைதியான தங்குமிட வசதி.</p>',
                    ],
                ],
            ],
            [
                'section' => 'accommodation',
                'category_id' => $categoryMap['accommodation']['Camping'] ?? null,
                'featured' => true,
                'price_range' => 'LKR 3,500 - 6,000 / tent',
                'phone' => '+94 77 345 8912',
                'email' => 'camp@thelgamariver.lk',
                'website' => 'https://thelgamacamping.lk',
                'latitude' => 7.5450,
                'longitude' => 80.7580,
                'sort_order' => 2,
                'translations' => [
                    'en' => [
                        'title' => 'Thelgamu Oya Riverside Camp Site',
                        'slug' => 'thelgamu-oya-riverside-camp-site',
                        'location_name' => 'Thelgamu Oya Riverbanks, Laggala',
                        'opening_hours' => 'Camp Check-in: 3:00 PM | Open Daily',
                        'short_description' => 'Set up camp beside the crystal-clear gushing waters of Thelgamu Oya with safe natural river pools and campfire spots.',
                        'description' => '<p>Thelgamu Oya Riverside Camp Site offers pre-pitched waterproof tents, clean sanitary facilities, security, and campfire pits right on the edge of the scenic river. Perfect for stargazing, river bathing, and adventure groups.</p>',
                    ],
                    'si' => [
                        'title' => 'තෙල්ගමු ඔය ගං ඉවුරු කඳවුරු භූමිය',
                        'slug' => 'thelgamu-oya-riverside-camp-site',
                        'location_name' => 'තෙල්ගමු ඔය ඉවුර, ලග්ගල',
                        'opening_hours' => 'පැමිණීම: ප.ව. 3:00 සිට | දිනපතා විවෘතයි',
                        'short_description' => 'පිරිසිදු තෙල්ගමු ඔයේ සිසිල් දිය පහස විඳිමින් ආරක්ෂිතව කඳවුරු බැඳ රාත්‍රිය ගත කිරීමට කදිම ස්ථානයකි.',
                        'description' => '<p>තෙල්ගමු ඔයේ මනරම් ඉවුරේ පිහිටි මෙම කඳවුරු බිමෙහි කූඩාරම්, සනීපාරක්ෂක පහසුකම්, ගිනිමැල සහ ගං දිය නෑමේ පහසුකම් ඉතා ආරක්ෂිතව සලසා ඇත.</p>',
                    ],
                    'ta' => [
                        'title' => 'தெல்கமு ஓயா ஆற்றுப்படுகை முகாம் தளம்',
                        'slug' => 'thelgamu-oya-riverside-camp-site',
                        'location_name' => 'தெல்கமு ஓயா, லக்கல',
                        'opening_hours' => 'வருகை: பிற்பகல் 3:00 | தினசரி',
                        'short_description' => 'தெல்கமு ஓயாவின் அழகிய நதிக்கரையில் பாதுகாப்பான மற்றும் இயற்கை எழில் கொஞ்சும் முகாம் தளம்.',
                        'description' => '<p>தெளிந்த நதிநீர் குளியல் மற்றும் முகாம் தீ வசதிகளுடன் கூடிய அமைதியான இயற்கை முகாம் தளம்.</p>',
                    ],
                ],
            ],

            // 2. RESTAURANTS & CAFES
            [
                'section' => 'restaurants-cafes',
                'category_id' => $categoryMap['restaurants-cafes']['Restaurants'] ?? null,
                'featured' => true,
                'price_range' => 'LKR 800 - 2,200',
                'phone' => '+94 66 227 8890',
                'email' => 'dine@laggalaheritage.lk',
                'website' => null,
                'latitude' => 7.5180,
                'longitude' => 80.7390,
                'sort_order' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'The Knuckles Ridge View Restaurant',
                        'slug' => 'knuckles-ridge-view-restaurant',
                        'location_name' => 'New Town, Laggala-Pallegama',
                        'opening_hours' => 'Daily: 6:30 AM - 10:00 PM',
                        'short_description' => 'A charming dining spot serving authentic Sri Lankan rice & curry, Chinese dishes, and freshly brewed Ceylon tea with breezy valley vistas.',
                        'description' => '<p>Overlooking the green agricultural valleys of New Laggala, The Knuckles Ridge View Restaurant serves hearty buffet spreads, clay-pot curries, fresh freshwater fish, and a variety of beverages for passing adventurers and families.</p>',
                    ],
                    'si' => [
                        'title' => 'නකල්ස් රිජ් වීව් අවන්හල',
                        'slug' => 'knuckles-ridge-view-restaurant',
                        'location_name' => 'නව නගරය, ලග්ගල-පල්ලේගම',
                        'opening_hours' => 'දිනපතා: පෙ.ව. 6:30 - රාත්‍රී 10:00',
                        'short_description' => 'ලග්ගල නිම්නයේ සුන්දර දසුන් සමඟින් රසවත් දේශීය බත් හා වෑංජන සහ කෙටි ආහාර රසවිඳිය හැකි සුහදශීලී අවන්හලකි.',
                        'description' => '<p>නව ලග්ගල නගරය ආසන්නයේ පිහිටි මෙම අවන්හල නැවුම් වැව් මාළු, මැටි වළං බත් වෑංජන, ශ්‍රී ලාංකීය හා චීන ආහාර වර්ග සහ රසවත් සිසිල් බීම වර්ග පිරිනමයි.</p>',
                    ],
                    'ta' => [
                        'title' => 'நக்கிள்ஸ் ரிட்ஜ் வியூ உணவகம்',
                        'slug' => 'knuckles-ridge-view-restaurant',
                        'location_name' => 'புது நகர், லக்கல-பல்லேகம',
                        'opening_hours' => 'தினசரி: மு.ப 6:30 - இர 10:00',
                        'short_description' => 'சுவையான பாரம்பரிய இலங்கை உணவுகள் மற்றும் தேநீர் வழங்கும் அழகிய மலை உணவகம்.',
                        'description' => '<p>சுற்றுலாப் பயணிகளுக்கான மதிய உணவு, நன்னீர் மீன் உணவுகள் மற்றும் சுவையான சிற்றுண்டிகள் கிடைக்கும் இடம்.</p>',
                    ],
                ],
            ],

            // 3. LOCAL FOOD
            [
                'section' => 'local-food',
                'category_id' => $categoryMap['local-food']['Traditional Meals'] ?? null,
                'featured' => true,
                'price_range' => 'LKR 450 - 950',
                'phone' => '+94 71 889 1234',
                'email' => null,
                'website' => null,
                'latitude' => 7.5250,
                'longitude' => 80.7480,
                'sort_order' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'Illukkumbura Traditional Claypot Rice & Curry',
                        'slug' => 'illukkumbura-traditional-claypot-rice',
                        'location_name' => 'Illukkumbura Junction, Laggala',
                        'opening_hours' => 'Lunch: 11:30 AM - 4:00 PM Daily',
                        'short_description' => 'Authentic village lunch cooked on woodfire hearths in traditional clay pots, featuring wild foraged herbs, lake fish, and homemade sambols.',
                        'description' => '<p>Prepared by local village mothers, this culinary haven serves fragrant heirloom red rice on banana leaves, accompanied by jackfruit curry (polos), river fish ambul thiyal, foraged watercress mallum, and woodapple chutney.</p>',
                    ],
                    'si' => [
                        'title' => 'ඉලුක්කුඹුර පාරම්පරික මැටි වළං ගමේ බත්',
                        'slug' => 'illukkumbura-traditional-claypot-rice',
                        'location_name' => 'ඉලුක්කුඹුර හන්දිය, ලග්ගල',
                        'opening_hours' => 'දවල් ආහාරය: පෙ.ව. 11:30 - ප.ව. 4:00',
                        'short_description' => 'දර ළිපේ මැටි වළඳේ පිසූ සුවඳැති ගමේ කැකුළු බත්, පොළොස් ඇඹුල, වැව් මාළු හා ගමේ කොළ මැල්ලුම් කෙසෙල් කොළයේ පිළිගන්වයි.',
                        'description' => '<p>ගැමි මව්වරුන්ගේ අත්ගුණෙන් පිසෙන පාරම්පරික දිවා ආහාරයකි. නකල්ස් වනාන්තර මායිමේ නැවුම් එළවළු, පලා වර්ග සහ දේශීය කුළුබඩු මිශ්‍රිත අසමසම රස අත්දැකීමකි.</p>',
                    ],
                    'ta' => [
                        'title' => 'இலுக்கும்புர பாரம்பரிய மண் பானை கிராமத்து உணவு',
                        'slug' => 'illukkumbura-traditional-claypot-rice',
                        'location_name' => 'இலுக்கும்புர சந்தி, லக்கல',
                        'opening_hours' => 'மதிய உணவு: மு.ப 11:30 - பி.ப 4:00',
                        'short_description' => 'வாழை இலையில் பாரம்பரிய மண் பானையில் சமைக்கப்பட்ட உண்மையான கிராமத்து சோறும் கறியும்.',
                        'description' => '<p>இயற்கையான மூலிகைகள், பலாக்காய் கறி மற்றும் ஆற்று மீன் உடன் வழங்கப்படும் சுவையான பாரம்பரிய மதிய உணவு.</p>',
                    ],
                ],
            ],

            // 4. OUTDOOR DINING & CATERING
            [
                'section' => 'outdoor-dining',
                'category_id' => $categoryMap['outdoor-dining']['Picnic Meals'] ?? null,
                'featured' => true,
                'price_range' => 'LKR 1,500 - 3,500 / person',
                'phone' => '+94 76 991 4567',
                'email' => 'picnic@knucklesoutdoor.lk',
                'website' => 'https://knucklesoutdoorcatering.lk',
                'latitude' => 7.5400,
                'longitude' => 80.7350,
                'sort_order' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'Riverston Highland Wilderness Picnic & BBQ',
                        'slug' => 'riverston-highland-wilderness-picnic',
                        'location_name' => 'Riverston Gap & Mini World’s End Vista',
                        'opening_hours' => 'Booking: 24h prior notice required',
                        'short_description' => 'Custom curated mountain picnic spreads and live campfire barbecue experiences amidst the windswept grasslands of Riverston.',
                        'description' => '<p>Experience dining in nature at its finest! We provide eco-friendly wooden picnic hampers, roasted meats, jacket potatoes, garlic bread, tropical fruit skewers, and hot ginger tea delivered right to your hiking summit or riverside spot.</p>',
                    ],
                    'si' => [
                        'title' => 'රිවස්ටන් හයිලන්ඩ් එළිමහන් විනෝද චාරිකා ආහාර හා BBQ',
                        'slug' => 'riverston-highland-wilderness-picnic',
                        'location_name' => 'රිවස්ටන් සහ කුඩා ලෝකාන්තය ආශ්‍රිතව',
                        'opening_hours' => 'පැය 24කට පෙර වෙන්කරවා ගැනීම අවශ්‍යයි',
                        'short_description' => 'රිවස්ටන් සුළං කපොල්ලේ සිසිල් පරිසරය මැද එළිමහන් විනෝද චාරිකා ආහාර (Picnic) සහ සජීවී බාබකිව් (BBQ) අත්දැකීම්.',
                        'description' => '<p>නකල්ස් කඳු මුදුන් වලදී හෝ ගං ඉවුරකදී විඳිය හැකි විශේෂ එළිමහන් භෝජන සේවාවකි. උණුසුම් සුප්, පුළුස්සන ලද මස්, නැවුම් බේකරි නිෂ්පාදන සහ ඉඟුරු තේ සහිත පාරිසරික පැකේජ සපයනු ලැබේ.</p>',
                    ],
                    'ta' => [
                        'title' => 'ரிவர்ஸ்டன் வனப்பகுதி சுற்றுலா உணவு & பார்பிக்யூ',
                        'slug' => 'riverston-highland-wilderness-picnic',
                        'location_name' => 'ரிவர்ஸ்டன் முனை, லக்கல',
                        'opening_hours' => 'முன்பதிவு தேவை (24 மணி நேரத்திற்கு முன்)',
                        'short_description' => 'மலை உச்சியில் இயற்கை எழில் கொஞ்சும் சுற்றுலா உணவுப் பொதிகள் மற்றும் பார்பிக்யூ சேவை.',
                        'description' => '<p>நக்கிள்ஸ் மலைகளில் நடைபயணம் மேற்கொள்பவர்களுக்கான தனிப்பயனாக்கப்பட்ட வெளிப்புற உணவு மற்றும் கேம்ப் ஃபயர் சேவைகள்.</p>',
                    ],
                ],
            ],
        ];

        foreach ($places as $placeData) {
            $translations = $placeData['translations'];
            unset($placeData['translations']);

            $item = StayEatItem::create(array_merge($placeData, [
                'status' => 'published',
            ]));

            // Add translations
            foreach ($translations as $locale => $tData) {
                StayEatItemTranslation::create(array_merge($tData, [
                    'stay_eat_item_id' => $item->id,
                    'locale' => $locale,
                ]));
            }

            // Add up to 3-5 images for testing the slider (Requirement 4)
            foreach ($sampleImages as $index => $imgPath) {
                StayEatItemImage::create([
                    'stay_eat_item_id' => $item->id,
                    'image_path' => $imgPath,
                    'caption' => 'Scenic view of '.($translations['en']['title'] ?? 'Laggala').' (Photo '.($index + 1).')',
                    'sort_order' => $index,
                    'is_cover' => $index === 0,
                ]);
            }

            // Add sample approved review (Requirement 5)
            if ($adminUser) {
                StayEatItemReview::create([
                    'stay_eat_item_id' => $item->id,
                    'user_id' => $adminUser->id,
                    'rating' => 5,
                    'comment' => 'Exceptional hospitality and truly authentic mountain experience in Laggala! Highly recommended for any traveler.',
                    'status' => 'approved',
                    'reviewed_by' => $adminUser->id,
                    'reviewed_at' => now(),
                ]);
            }
        }
    }
}
