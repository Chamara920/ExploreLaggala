<?php

namespace Database\Seeders;

use App\Models\EmergencyContact;
use App\Models\Hospital;
use App\Models\PoliceStation;
use App\Models\VehicleAssistance;
use App\Models\WildlifeForestOffice;
use Illuminate\Database\Seeder;

class EmergencyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Emergency Contacts
        $contacts = [
            [
                'category' => 'medical',
                'phone_number' => '1990',
                'short_code' => '1990',
                'is_toll_free' => true,
                'is_24x7' => true,
                'sort_order' => 1,
                'translations' => [
                    'si' => ['name' => '1990 සුවසැරිය නොමිලේ ගිලන්රථ සේවය', 'department' => 'සුවසැරිය පදනම', 'description' => 'ශ්‍රී ලංකාවේ ඕනෑම ප්‍රදේශයකට ක්ෂණික පූර්ව-රෝහල් හදිසි ගිලන්රථ සත්කාර සේවාව.'],
                    'en' => ['name' => '1990 Suwa Seriya Free Pre-Hospital Care Ambulance', 'department' => '1990 Suwa Seriya Foundation', 'description' => 'Nationwide 24/7 free emergency ambulance service with trained EMTs.'],
                    'ta' => ['name' => '1990 சுவசரிய இலவச ஆம்புலன்ஸ் சேவை', 'department' => 'சுவசரிய அறக்கட்டளை', 'description' => 'இலங்கை முழுவதற்கும் 24/7 இலவச அவசர ஆம்புலன்ஸ் சேவை.'],
                ],
            ],
            [
                'category' => 'police',
                'phone_number' => '119',
                'short_code' => '119',
                'is_toll_free' => true,
                'is_24x7' => true,
                'sort_order' => 2,
                'translations' => [
                    'si' => ['name' => '119 ජාතික පොලිස් හදිසි ඇමතුම් සේවාව', 'department' => 'ශ්‍රී ලංකා පොලිසිය', 'description' => 'ඕනෑම හදිසි නීතිමය හෝ ආරක්ෂක තත්ත්වයකදී පැය 24 පුරා සම්බන්ධ විය හැකි පොලිස් හදිසි ඒකකය.'],
                    'en' => ['name' => '119 National Police Emergency Hotline', 'department' => 'Sri Lanka Police', 'description' => '24/7 emergency response hotline for criminal, traffic, and security emergencies.'],
                    'ta' => ['name' => '119 தேசிய காவல்துறை அவசர உதவி எண்', 'department' => 'இலங்கை காவல்துறை', 'description' => 'அவசர பாதுகாப்பு மற்றும் குற்றவியல் சம்பவங்களுக்கு 24 மணி நேர சேவை.'],
                ],
            ],
            [
                'category' => 'disaster',
                'phone_number' => '117',
                'short_code' => '117',
                'is_toll_free' => true,
                'is_24x7' => true,
                'sort_order' => 3,
                'translations' => [
                    'si' => ['name' => '117 ආපදා කළමනාකරණ හදිසි මෙහෙයුම් මධ්‍යස්ථානය', 'department' => 'ආපදා කළමනාකරණ මධ්‍යස්ථානය (DMC)', 'description' => 'නායයෑම්, ගංවතුර, අධික වැසි හෝ කඳුකර අනතුරු පිළිබඳ තොරතුරු ලබාගැනීමට හා ආපදා දැනුම්දීමට.'],
                    'en' => ['name' => '117 Disaster Management Center (DMC)', 'department' => 'Ministry of Defence / DMC', 'description' => 'Emergency alerts, landslide warnings, river surge notices and mountain flood distress reporting.'],
                    'ta' => ['name' => '117 அனர்த்த முகாமைத்துவ மையம்', 'department' => 'அனர்த்த முகாமைத்துவ பிரிவு', 'description' => 'மண்சரிவு, வெள்ளம் மற்றும் மலைப்பகுதி அவசர நிலைகளுக்கான உதவி.'],
                ],
            ],
            [
                'category' => 'hotline',
                'phone_number' => '110',
                'short_code' => '110',
                'is_toll_free' => true,
                'is_24x7' => true,
                'sort_order' => 4,
                'translations' => [
                    'si' => ['name' => '110 ගිනි නිවන සහ ජීවිත ගලවා ගැනීමේ සේවය', 'department' => 'පළාත් පාලන ගිනි නිවන ඒකකය', 'description' => 'ගිනි නිවීම, හදිසි මුදවාගැනීම් සහ ජීවිතාරක්ෂක මෙහෙයුම්.'],
                    'en' => ['name' => '110 Fire & Rescue Emergency Service', 'department' => 'Fire & Rescue Department', 'description' => 'Fire extinguishing, vehicular crash extraction and rapid rescue operations.'],
                    'ta' => ['name' => '110 தீயணைப்பு மற்றும் மீட்பு சேவை', 'department' => 'தீயணைப்பு பிரிவு', 'description' => 'தீ விபத்துக்கள் மற்றும் உடனடி மீட்பு பணிகளுக்கான அவசர சேவை.'],
                ],
            ],
            [
                'category' => 'local',
                'phone_number' => '066-2288224',
                'alternate_phone' => '066-2288225',
                'short_code' => null,
                'is_toll_free' => false,
                'is_24x7' => false,
                'sort_order' => 5,
                'translations' => [
                    'si' => ['name' => 'ලග්ගල පල්ලේගම ප්‍රාදේශීය ලේකම් කාර්යාලය', 'department' => 'රාජ්‍ය පරිපාලන අමාත්‍යාංශය', 'description' => 'ප්‍රාදේශීය ආපදා සම්බන්ධීකරණ නිලධාරී සහ පරිපාලන සහාය.', 'address' => 'පල්ලේගම, ලග්ගල'],
                    'en' => ['name' => 'Laggala Pallegama Divisional Secretariat', 'department' => 'Ministry of Public Administration', 'description' => 'Local regional administration, relief coordination and grama niladhari services.', 'address' => 'Pallegama, Laggala'],
                    'ta' => ['name' => 'லக்கல பல்லேகம பிரதேச செயலகம்', 'department' => 'பொது நிர்வாக அமைச்சு', 'description' => 'பிரதேச நிர்வாகம் மற்றும் அனர்த்த நிவாரண ஒருங்கிணைப்பு.', 'address' => 'பல்லேகம, லக்கல'],
                ],
            ],
        ];

        foreach ($contacts as $c) {
            $trans = $c['translations'];
            unset($c['translations']);
            $record = EmergencyContact::create($c);
            foreach ($trans as $loc => $data) {
                $record->translations()->create(array_merge(['locale' => $loc], $data));
            }
        }

        // 2. Hospitals & Clinics
        $hospitals = [
            [
                'type' => 'government',
                'category' => 'Divisional Hospital',
                'phone' => '066-2288260',
                'emergency_phone' => '066-2288260',
                'ambulance_phone' => '1990',
                'has_ambulance' => true,
                'has_emergency_unit' => true,
                'is_24x7' => true,
                'city' => 'Laggala-Pallegama',
                'latitude' => 7.5458,
                'longitude' => 80.7483,
                'google_maps_url' => 'https://maps.google.com/?q=7.5458,80.7483',
                'sort_order' => 1,
                'translations' => [
                    'si' => [
                        'name' => 'ලග්ගල පල්ලේගම ප්‍රාදේශීය රෝහල',
                        'address' => 'රෝහල පාර, පල්ලේගම, ලග්ගල',
                        'available_facilities' => 'බාහිර රෝගී අංශය (OPD), හදිසි ප්‍රතිකාර ඒකකය (ETU), මාතෘ සායන, වාට්ටු ඇතුළත් කිරීම්, ගිලන්රථ සේවය.',
                        'description' => 'ලග්ගල නිම්නයේ ප්‍රධානතම රජයේ රෝහල වන අතර පැය 24 පුරා හදිසි ප්‍රතිකාර ලබාදෙයි.',
                    ],
                    'en' => [
                        'name' => 'Laggala Pallegama Divisional Hospital',
                        'address' => 'Hospital Road, Pallegama, Laggala',
                        'available_facilities' => 'Outpatient Department (OPD), Emergency Treatment Unit (ETU), Maternity Ward, Inpatient Admissions, Ambulance Transfer.',
                        'description' => 'The primary government medical center in Laggala valley operating 24/7 emergency and trauma services.',
                    ],
                    'ta' => [
                        'name' => 'லக்கல பல்லேகம பிரதேச வைத்தியசாலை',
                        'address' => 'வைத்தியசாலை வீதி, பல்லேகம, லக்கல',
                        'available_facilities' => 'வெளிநோயாளி பிரிவு (OPD), அவசர சிகிச்சை பிரிவு (ETU), நோயாளர் விடுதி, ஆம்புலன்ஸ் சேவை.',
                        'description' => 'லக்கல பகுதியின் பிரதான அரசு மருத்துவமனை, 24 மணி நேர அவசர சிகிச்சைகள் வழங்கப்படுகிறது.',
                    ],
                ],
            ],
            [
                'type' => 'government',
                'category' => 'Base Hospital',
                'phone' => '066-2222261',
                'emergency_phone' => '066-2222261',
                'ambulance_phone' => '1990',
                'has_ambulance' => true,
                'has_emergency_unit' => true,
                'is_24x7' => true,
                'city' => 'Rattota',
                'latitude' => 7.5167,
                'longitude' => 80.6667,
                'google_maps_url' => 'https://maps.google.com/?q=7.5167,80.6667',
                'sort_order' => 2,
                'translations' => [
                    'si' => [
                        'name' => 'රත්තොට මූලික රෝහල',
                        'address' => 'ප්‍රධාන වීදිය, රත්තොට',
                        'available_facilities' => 'ශල්‍යකර්ම ඒකකය, දැඩි සත්කාර, ප්‍රසව හා නාරිවේද, රසායනාගාර, එක්ස් කිරණ, හදිසි අනතුරු ඒකකය.',
                        'description' => 'රිවස්ටන් සහ නකල්ස් බටහිර බැවුමේ සිට ළඟාවිය හැකි ආසන්නතම ප්‍රධාන මූලික රෝහල.',
                    ],
                    'en' => [
                        'name' => 'Rattota Base Hospital',
                        'address' => 'Main Street, Rattota',
                        'available_facilities' => 'Surgical Ward, Intensive Care, Laboratory, X-Ray, Blood Bank, Emergency & Trauma Unit, 24/7 Ambulance.',
                        'description' => 'Closest major base hospital on the western ascent to Riverston and Knuckles mountain range.',
                    ],
                    'ta' => [
                        'name' => 'ரத்தோட்டை ஆதார வைத்தியசாலை',
                        'address' => 'பிரதான வீதி, ரத்தோட்டை',
                        'available_facilities' => 'அறுவை சிகிச்சை பிரிவு, அவசர விபத்து பிரிவு, எக்ஸ்-ரே, இரசாயன கூடம், ஆம்புலன்ஸ்.',
                        'description' => 'ரிவர்ஸ்டன் மற்றும் நக்கிள்ஸ் பகுதிக்கு அருகிலுள்ள பிரதான ஆதார மருத்துவமனை.',
                    ],
                ],
            ],
            [
                'type' => 'private',
                'category' => 'Private Clinic & Pharmacy',
                'phone' => '077-3456789',
                'emergency_phone' => '071-8899112',
                'ambulance_phone' => null,
                'has_ambulance' => false,
                'has_emergency_unit' => false,
                'is_24x7' => false,
                'operating_hours' => '7:00 AM - 9:30 PM',
                'city' => 'Laggala-Pallegama',
                'latitude' => 7.5462,
                'longitude' => 80.7490,
                'sort_order' => 3,
                'translations' => [
                    'si' => [
                        'name' => 'නකල්ස් මෙඩිකෙයාර් සහ ඖෂධසල (පෞද්ගලික)',
                        'address' => 'නගර මධ්‍යය, නව නගරය, පල්ලේගම',
                        'available_facilities' => 'සාමාන්‍ය වෛද්‍ය පරීක්ෂණ, ඖෂධ නිකුත් කිරීම, ප්‍රථමාධාර, දියවැඩියා පරීක්ෂණ, සංචාරක ප්‍රථමාධාර කට්ටල.',
                        'description' => 'පෞද්ගලික සායනයක් සහ අත්‍යවශ්‍ය ඖෂධ ලබාගත හැකි ඖෂධසලක්.',
                    ],
                    'en' => [
                        'name' => 'Knuckles Medicare & Pharmacy (Private Clinic)',
                        'address' => 'Town Center, New Town, Pallegama',
                        'available_facilities' => 'General Medical Consultation, Prescription Drugs, Wound Dressing, Traveller First Aid Kits, Over-The-Counter Medications.',
                        'description' => 'Private clinic and pharmacy for tourists and residents needing prompt medical consults and medications.',
                    ],
                    'ta' => [
                        'name' => 'நக்கிள்ஸ் மெடிகேர் மற்றும் மருந்தகம் (தனியார்)',
                        'address' => 'நகர மையம், புது நகரம், பல்லேகம',
                        'available_facilities' => 'பொது மருத்துவ சிகிச்சை, மருந்து விநியோகம், முதலுதவி வசதிகள்.',
                        'description' => 'தனியார் சிகிச்சை நிலையம் மற்றும் அத்தியாவசிய மருந்தகம்.',
                    ],
                ],
            ],
        ];

        foreach ($hospitals as $h) {
            $trans = $h['translations'];
            unset($h['translations']);
            $record = Hospital::create($h);
            foreach ($trans as $loc => $data) {
                $record->translations()->create(array_merge(['locale' => $loc], $data));
            }
        }

        // 3. Police Stations
        $police = [
            [
                'division' => 'Matale Division',
                'phone' => '066-2288222',
                'emergency_phone' => '066-2288222',
                'oic_phone' => '071-8591823',
                'city' => 'Laggala-Pallegama',
                'latitude' => 7.5450,
                'longitude' => 80.7470,
                'google_maps_url' => 'https://maps.google.com/?q=7.5450,80.7470',
                'sort_order' => 1,
                'translations' => [
                    'si' => [
                        'name' => 'ලග්ගල පොලිස් ස්ථානය',
                        'address' => 'නව නගරය, පල්ලේගම, ලග්ගල',
                        'jurisdiction' => 'පල්ලේගම, ඉලුක්කුඹුර, පිටවල පතන, හත්තොට අමුණ, නාරංගමුව, රිවස්ටන් නැගෙනහිර බැවුම.',
                        'description' => 'ලග්ගල නිම්නය සහ නකල්ස් සංචාරක කලාපය භාර ප්‍රධාන පොලිස් ස්ථානය. පැය 24 පුරා ක්‍රියාත්මකයි.',
                    ],
                    'en' => [
                        'name' => 'Laggala Police Station',
                        'address' => 'New Town, Pallegama, Laggala',
                        'jurisdiction' => 'Pallegama, Illukkumbura, Pitawala Pathana, Haththota Amuna, Narangamuwa, Eastern Riverston pass.',
                        'description' => 'The primary police station covering the Laggala valley and Knuckles wilderness sector with 24/7 patrol.',
                    ],
                    'ta' => [
                        'name' => 'லக்கல காவல் நிலையம்',
                        'address' => 'புது நகரம், பல்லேகம, லக்கல',
                        'jurisdiction' => 'பல்லேகம, இலுக்கும்புர, பிட்டவல பத்தன, ஹத்தொட்ட அமுண பகுதிகள்.',
                        'description' => 'லக்கல மற்றும் நக்கிள்ஸ் பகுதிக்கான பிரதான பொலிஸ் நிலையம், 24 மணி நேர ரோந்து சேவை.',
                    ],
                ],
            ],
            [
                'division' => 'Matale Division',
                'phone' => '066-2222222',
                'emergency_phone' => '066-2222222',
                'oic_phone' => '071-8591820',
                'city' => 'Rattota',
                'latitude' => 7.5180,
                'longitude' => 80.6650,
                'google_maps_url' => 'https://maps.google.com/?q=7.5180,80.6650',
                'sort_order' => 2,
                'translations' => [
                    'si' => [
                        'name' => 'රත්තොට පොලිස් ස්ථානය',
                        'address' => 'ප්‍රධාන වීදිය, රත්තොට',
                        'jurisdiction' => 'රත්තොට නගරය, බඹරකිරි ඇල්ල, රිවස්ටන් ආරම්භක කඳුකර මාර්ගය.',
                        'description' => 'මාතලේ සිට රිවස්ටන් දක්වා දිවෙන මාර්ගය සහ රත්තොට ප්‍රදේශය ආවරණය කරයි.',
                    ],
                    'en' => [
                        'name' => 'Rattota Police Station',
                        'address' => 'Main Street, Rattota',
                        'jurisdiction' => 'Rattota town, Bambarakiri Ella falls route, western ascent to Riverston.',
                        'description' => 'Covers the popular ascent route to Riverston mountain pass from Matale side.',
                    ],
                    'ta' => [
                        'name' => 'ரத்தோட்டை காவல் நிலையம்',
                        'address' => 'பிரதான வீதி, ரத்தோட்டை',
                        'jurisdiction' => 'ரத்தோட்டை நகரம் மற்றும் ரிவர்ஸ்டன் செல்லும் பிரதான மலைப்பாதை.',
                        'description' => 'மாத்தளையிலிருந்து ரிவர்ஸ்டன் நோக்கி செல்லும் வழியை கண்காணிக்கும் காவல் நிலையம்.',
                    ],
                ],
            ],
        ];

        foreach ($police as $p) {
            $trans = $p['translations'];
            unset($p['translations']);
            $record = PoliceStation::create($p);
            foreach ($trans as $loc => $data) {
                $record->translations()->create(array_merge(['locale' => $loc], $data));
            }
        }

        // 4. Wildlife & Forest
        $wildlife = [
            [
                'department_type' => 'conservation',
                'range_area' => 'Knuckles Conservation Center - Illukkumbura',
                'phone' => '066-3685412',
                'emergency_hotline' => '071-4455667',
                'officer_in_charge_phone' => '071-8899223',
                'city' => 'Illukkumbura',
                'latitude' => 7.5528,
                'longitude' => 80.7064,
                'google_maps_url' => 'https://maps.google.com/?q=7.5528,80.7064',
                'sort_order' => 1,
                'translations' => [
                    'si' => [
                        'name' => 'නකල්ස් සංරක්ෂණ මධ්‍යස්ථානය - ඉලුක්කුඹුර',
                        'address' => 'ඉලුක්කුඹුර, ලග්ගල',
                        'duties_description' => 'නකල්ස් රක්ෂිතයට ඇතුළුවීමේ නිල අවසරපත් නිකුත් කිරීම, මඟපෙන්වන්නන් ලබාදීම, වන සතුන්ගෙන් සිදුවන හදිසි ආපදා මුදවාගැනීම.',
                        'description' => 'නකල්ස් සංරක්ෂණ වනාන්තරයේ ප්‍රධානතම තොරතුරු හා ප්‍රවේශපත්‍ර නිකුත් කිරීමේ මධ්‍යස්ථානය.',
                    ],
                    'en' => [
                        'name' => 'Knuckles Conservation Center - Illukkumbura',
                        'address' => 'Illukkumbura, Laggala',
                        'duties_description' => 'Official forest trekking permit issuance, registered nature guide assignment, lost hiker search & rescue, wild animal incident reporting.',
                        'description' => 'The premier visitors and conservation command post for the Knuckles High Mountain Biosphere Reserve.',
                    ],
                    'ta' => [
                        'name' => 'நக்கிள்ஸ் பாதுகாப்பு மையம் - இலுக்கும்புர',
                        'address' => 'இலுக்கும்புர, லக்கல',
                        'duties_description' => 'காட்டுக்குள் நுழைவதற்கான அனுமதி சீட்டுகள், வழிகாட்டிகள் மற்றும் அவசர மீட்பு பணிகள்.',
                        'description' => 'நக்கிள்ஸ் பாதுகாக்கப்பட்ட வனப்பகுதியின் பிரதான தகவல் மற்றும் அனுமதி மையம்.',
                    ],
                ],
            ],
            [
                'department_type' => 'forest',
                'range_area' => 'Forest Department Range Office - Laggala',
                'phone' => '066-2288230',
                'emergency_hotline' => '1929',
                'city' => 'Pallegama',
                'latitude' => 7.5455,
                'longitude' => 80.7480,
                'sort_order' => 2,
                'translations' => [
                    'si' => [
                        'name' => 'ලග්ගල වන පරාස කාර්යාලය (වන සංරක්ෂණ දෙපාර්තමේන්තුව)',
                        'address' => 'පල්ලේගම, ලග්ගල',
                        'duties_description' => 'වන ගිනි මැඩපැවැත්වීම, රක්ෂිත සීමා ආරක්ෂාව, නීතිවිරෝධී දැව හා දඩයම් වැටලීම්.',
                        'description' => 'ලග්ගල ප්‍රාදේශීය ලේකම් කොට්ඨාසයේ වනාන්තර කලාප කළමනාකරණය කරන වන කාර්යාලය.',
                    ],
                    'en' => [
                        'name' => 'Laggala Forest Range Office (Forest Department)',
                        'address' => 'Pallegama, Laggala',
                        'duties_description' => 'Forest fire suppression, anti-poaching, protected boundary surveillance, reforestation stewardship.',
                        'description' => 'Regional forest department post managing gazetted catchment and reserve forests in Laggala basin.',
                    ],
                    'ta' => [
                        'name' => 'லக்கல வன சரக அலுவலகம் (வன பாதுகாப்பு திணைக்களம்)',
                        'address' => 'பல்லேகம, லக்கல',
                        'duties_description' => 'காட்டுத்தீ கட்டுப்பாடு, சட்டவிரோத மரம் வெட்டுதல் தடுப்பு மற்றும் வன பாதுகாப்பு.',
                        'description' => 'லக்கல பிரதேச வனப்பகுதியை நிர்வகிக்கும் பிரதான வனத்துறை அலுவலகம்.',
                    ],
                ],
            ],
        ];

        foreach ($wildlife as $w) {
            $trans = $w['translations'];
            unset($w['translations']);
            $record = WildlifeForestOffice::create($w);
            foreach ($trans as $loc => $data) {
                $record->translations()->create(array_merge(['locale' => $loc], $data));
            }
        }

        // 5. Vehicle Assistance
        $vehicles = [
            [
                'service_type' => 'recovery_4x4',
                'primary_phone' => '077-1234567',
                'secondary_phone' => '071-9876543',
                'is_24x7' => true,
                'has_flatbed_tow' => true,
                'has_4x4_recovery' => true,
                'city' => 'Pallegama / Riverston',
                'base_location' => 'Pallegama Junction',
                'latitude' => 7.5460,
                'longitude' => 80.7485,
                'sort_order' => 1,
                'translations' => [
                    'si' => [
                        'provider_name' => 'නකල්ස් 4x4 කඳුකර වාහන මුදවාගැනීම් සහ ටෝවින් සේවාව',
                        'contact_person' => 'චන්දන කුමාර',
                        'covered_areas' => 'රිවස්ටන් කඳුකර මාර්ගය, පිටවල පතන, ඉලුක්කුඹුර, ලග්ගල-පල්ලේගම, දස්ගිරිය.',
                        'services_offered' => 'කඳුකරයේ ලිස්සාගිය හෝ පෙරළුණු වාහන වින්ච් මඟින් ගොඩගැනීම, පැතලි ඇදගෙන යාමේ ලොරි (Flatbed), බැටරි ජම්ප් ස්ටාර්ට්, තිරිංග (Brake) අලුත්වැඩියාව.',
                        'description' => 'නකල්ස් කඳුකර මාර්ගවල අත්වැරදීම් හෝ කාර්මික දෝෂවලදී පැය 24 පුරා ක්‍රියාත්මක විශේෂ 4x4 මුදවාගැනීමේ කණ්ඩායම.',
                    ],
                    'en' => [
                        'provider_name' => 'Knuckles 4x4 Mountain Recovery & Heavy Towing',
                        'contact_person' => 'Chandana Kumara',
                        'covered_areas' => 'Riverston Pass hairpin bends, Pitawala Pathana, Illukkumbura, Pallegama valley, Rattota road.',
                        'services_offered' => 'Heavy winch recovery for off-road stuck vehicles, flatbed truck towing, brake lockup release, battery jump-start, emergency fuel.',
                        'description' => '24/7 dedicated mountain breakdown and winch rescue team equipped for high-gradient Knuckles routes.',
                    ],
                    'ta' => [
                        'provider_name' => 'நக்கிள்ஸ் 4x4 மலைப்பாதை வாகன மீட்பு மற்றும் இழுவை சேவை',
                        'contact_person' => 'சந்தன குமார',
                        'covered_areas' => 'ரிவர்ஸ்டன், பிட்டவல, இலுக்கும்புர, பல்லேகம பகுதிகள்.',
                        'services_offered' => 'வாகனங்களை இழுத்துச் செல்லுதல், வின்ச் மூலம் மீட்டல், டயர் மற்றும் இயந்திர பழுதுபார்ப்பு.',
                        'description' => 'மலைப்பாதைகளில் பழுதடையும் வாகனங்களை மீட்க 24 மணி நேரமும் இயங்கும் அவசர சேவை.',
                    ],
                ],
            ],
            [
                'service_type' => 'tyre_repair',
                'primary_phone' => '076-5544332',
                'is_24x7' => true,
                'has_flatbed_tow' => false,
                'has_4x4_recovery' => false,
                'city' => 'Laggala-Pallegama',
                'base_location' => 'Near Bus Stand, Pallegama',
                'sort_order' => 2,
                'translations' => [
                    'si' => [
                        'provider_name' => 'ලග්ගල එක්ස්ප්‍රස් ජංගම ටයර් සහ කාර්මික සේවය',
                        'contact_person' => 'සුනිල් ශාන්ත',
                        'covered_areas' => 'පල්ලේගම, ඉලුක්කුඹුර, කඳේවත්ත, හත්තොට අමුණ.',
                        'services_offered' => 'ජංගම ටයර් පැච් දැමීම (Tubeless / Tube), හුළං ගැසීම, නව ටයර්, යතුරුපැදි සහ ත්‍රිරෝද රථ අලුත්වැඩියාව.',
                        'description' => 'ඔබ සිටින තැනටම පැමිණ ටයර් අලුත්වැඩියා කරදීමේ ජංගම සේවාව.',
                    ],
                    'en' => [
                        'provider_name' => 'Laggala Express Mobile Tyre & Quick Mechanic',
                        'contact_person' => 'Sunil Shantha',
                        'covered_areas' => 'Pallegama, Illukkumbura, Kandegama, Haththota Amuna.',
                        'services_offered' => 'Mobile tyre puncture vulcanizing (tubeless & tube), on-site wheel swap, bike and tuk repairs, jump starts.',
                        'description' => 'On-call roadside mobile mechanic and tyre puncture response service delivering aid directly to your breakdown spot.',
                    ],
                    'ta' => [
                        'provider_name' => 'லக்கல எக்ஸ்பிரஸ் நடமாடும் டயர் மற்றும் மெக்கானிக் சேவை',
                        'contact_person' => 'சுனில் சாந்த',
                        'covered_areas' => 'பல்லேகம மற்றும் அதனை சுற்றியுள்ள பகுதிகள்.',
                        'services_offered' => 'டயர் பஞ்சர் ஒட்டுதல், காற்று நிரப்புதல், சிறிய மெக்கானிக் வேலைகள்.',
                        'description' => 'வாகனம் நின்ற இடத்திற்கே வந்து டயர் பழுதுபார்க்கும் நடமாடும் சேவை.',
                    ],
                ],
            ],
        ];

        foreach ($vehicles as $v) {
            $trans = $v['translations'];
            unset($v['translations']);
            $record = VehicleAssistance::create($v);
            foreach ($trans as $loc => $data) {
                $record->translations()->create(array_merge(['locale' => $loc], $data));
            }
        }
    }
}
