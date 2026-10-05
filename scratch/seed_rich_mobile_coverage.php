<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Destination;
use App\Models\MobileCoverageReport;
use App\Models\MobileCoverageReportTranslation;

$admin = User::whereHas('roles', fn($q) => $q->whereIn('name', ['admin', 'super_admin']))->first()
    ?? User::first()
    ?? User::factory()->create(['name' => 'Laggala Admin', 'email' => 'admin@laggala.local']);

// Keep existing reports or clear only old test items
MobileCoverageReport::truncate();
MobileCoverageReportTranslation::truncate();

$seedData = [
    [
        'dest_slug' => 'riverston',
        'location_name' => 'Riverston Gap & Towers',
        'destination_id' => 4,
        'lat' => 7.5285,
        'lng' => 80.7315,
        'operator' => 'dialog',
        'coverage' => '4g',
        'signal' => 'good',
        'desc' => 'Strong 4G data coverage around the communication towers and windy gap summit. Works reliably for navigation.',
        'trans' => [
            'si' => [
                'location_name' => 'රිවස්ටන් ගැප් සහ කුළුණු පරිශ්‍රය',
                'description' => 'සන්නිවේදන කුළුණු සහ සුළං කපොල්ල අවට ප්‍රබල 4G ඩයලොග් සංඥා පවතී. GPS සහ සිතියම් භාවිතයට ඉතා සුදුසුයි.',
            ],
            'ta' => [
                'location_name' => 'ரிவர்ஸ்டன் கேப் & கோபுரங்கள் பகுதி',
                'description' => 'தகவல்தொடர்பு கோபுரங்கள் மற்றும் காற்று இடைவெளி உச்சியில் வலுவான 4G சிக்னல் கிடைக்கிறது.',
            ],
        ],
    ],
    [
        'dest_slug' => 'riverston',
        'location_name' => 'Riverston Mountain Trail',
        'destination_id' => 4,
        'lat' => 7.5270,
        'lng' => 80.7330,
        'operator' => 'mobitel',
        'coverage' => '3g',
        'signal' => 'fair',
        'desc' => 'Fair Mobitel coverage along the concrete access road; drops inside dense cloud mist.',
        'trans' => [
            'si' => [
                'location_name' => 'රිවස්ටන් කඳුකර මාර්ගය',
                'description' => 'ප්‍රවේශ මාර්ගය දිගේ මොබිටෙල් 3G සංඥා මධ්‍යස්ථ මට්ටමින් ලැබේ; ඝන මීදුම සහිත අවස්ථාවල දුර්වල විය හැක.',
            ],
            'ta' => [
                'location_name' => 'ரிவர்ஸ்டன் மலைப்பாதை',
                'description' => 'அணுகு சாலையில் மிதமான மொபிடெல் 3G சிக்னல் கிடைக்கிறது; மேகமூட்டத்தில் குறையக்கூடும்.',
            ],
        ],
    ],
    [
        'dest_slug' => 'pitawala-pathana',
        'location_name' => 'Pitawala Pathana & Mini World\'s End',
        'destination_id' => 1,
        'lat' => 7.5884,
        'lng' => 80.7554,
        'operator' => 'dialog',
        'coverage' => '4g',
        'signal' => 'fair',
        'desc' => 'Stable 4G near ticketing counter; drops to 1 bar at the sheer edge of Mini World\'s End cliff.',
        'trans' => [
            'si' => [
                'location_name' => 'පිටවල පතන සහ කුඩා ලෝකාන්තය',
                'description' => 'ප්‍රවේශ පත්‍ර කවුළුව අසල 4G සංඥා හොඳින් ලැබේ. කුඩා ලෝකාන්ත ප්‍රපාතය අසල සිග්නල් කණු 1-2 දක්වා පහත වැටේ.',
            ],
            'ta' => [
                'location_name' => 'பிடவல பதனா & சிறிய உலக முடிவு',
                'description' => 'நுழைவுச்சீட்டு கவுண்டர் அருகே நிலையான 4G சிக்னல்; பாறை விளிம்பில் குறைகிறது.',
            ],
        ],
    ],
    [
        'dest_slug' => 'thelgamu-oya',
        'location_name' => 'Thelgamu Oya & Ilukkumbura Bridge',
        'destination_id' => 5,
        'lat' => 7.5385,
        'lng' => 80.7510,
        'operator' => 'dialog',
        'coverage' => '3g',
        'signal' => 'fair',
        'desc' => 'Fair 3G signal around the Ilukkumbura roadway bridge and grocery shops; weak downstream.',
        'trans' => [
            'si' => [
                'location_name' => 'තෙල්ගමු ඔය සහ ඉලුක්කුඹුර පාලම',
                'description' => 'ඉලුක්කුඹුර පාලම සහ කඩමණ්ඩිය අවට ඩයලොග් 3G සංඥා මධ්‍යස්ථව ලැබේ. පහළ නාන තොටුපළවල සිග්නල් දුර්වලයි.',
            ],
            'ta' => [
                'location_name' => 'தெல்கமு ஓயா & இலுக்கும்புர பாலம்',
                'description' => 'இலுக்கும்புர பாலம் மற்றும் கடைகள் அருகே மிதமான 3G சிக்னல் கிடைக்கிறது.',
            ],
        ],
    ],
    [
        'dest_slug' => 'manigala',
        'location_name' => 'Manigala Rock Summit Ridge',
        'destination_id' => 3,
        'lat' => 7.5486,
        'lng' => 80.7350,
        'operator' => 'dialog',
        'coverage' => '3g',
        'signal' => 'fair',
        'desc' => 'Dialog voice & 3G data work on the open rock plateau. Dense forest ascent is a signal dead zone.',
        'trans' => [
            'si' => [
                'location_name' => 'මානිගල සමතලා ගල් මුදුන',
                'description' => 'විවෘත ගල් තලාව මත ඩයලොග් 3G සහ ඇමතුම් ලබා ගත හැක. කැලය මැදින් වැටී ඇති නැගීමේ මාර්ගයේ සිග්නල් නොමැත.',
            ],
            'ta' => [
                'location_name' => 'மானிகல பாறை உச்சி முகடு',
                'description' => 'திறந்த பாறை பீடபூமியில் 3G சிக்னல் வேலை செய்கிறது; காடுகளுக்குள் சிக்னல் இல்லை.',
            ],
        ],
    ],
    [
        'dest_slug' => 'meemure',
        'location_name' => 'Traditional Meemure Village',
        'destination_id' => 7,
        'lat' => 7.4335,
        'lng' => 80.8512,
        'operator' => 'dialog',
        'coverage' => '2g',
        'signal' => 'poor',
        'desc' => 'Weak 2G voice-only signal near the village school. Mobile data is generally unavailable throughout the valley.',
        'trans' => [
            'si' => [
                'location_name' => 'සම්ප්‍රදායික මීමුරේ ගම්මානය',
                'description' => 'පාසල අසල දුර්වල 2G ඇමතුම් සංඥාවක් පමණක් ඇත. ගම්මානයේ බොහෝ ප්‍රදේශවල ඩේටා (Mobile Internet) පහසුකම් නොමැත.',
            ],
            'ta' => [
                'location_name' => 'பாரம்பரிய மீமுரே கிராமம்',
                'description' => 'பள்ளி அருகே பலவீனமான 2G குரல் அழைப்பு மட்டுமே கிடைக்கிறது; இணைய சேவை இல்லை.',
            ],
        ],
    ],
    [
        'dest_slug' => 'meemure',
        'location_name' => 'Meemure Deep Valley',
        'destination_id' => 7,
        'lat' => 7.4360,
        'lng' => 80.8540,
        'operator' => 'mobitel',
        'coverage' => 'no_signal',
        'signal' => 'none',
        'desc' => 'Complete Dead Zone for Mobitel and other carriers inside the valley floor. Download offline maps in advance.',
        'trans' => [
            'si' => [
                'location_name' => 'මීමුරේ ගැඹුරු මිටියාවත',
                'description' => 'මොබිටෙල් ඇතුළු අනෙකුත් ජාලයන් සඳහා සම්පූර්ණයෙන්ම සිග්නල් රහිත කලාපයකි (Dead Zone). නොබැඳි සිතියම් (Offline Maps) කලින් බාගත කරගන්න.',
            ],
            'ta' => [
                'location_name' => 'மீமுரே ஆழமான பள்ளத்தாக்கு',
                'description' => 'முழுமையான சிக்னல் இல்லாத பகுதி (Dead Zone). புறப்படும் முன் ஆஃப்லைன் வரைபடங்களைப் பதிவிறக்கவும்.',
            ],
        ],
    ],
    [
        'dest_slug' => 'lakegala',
        'location_name' => 'Lakegala Mountain Base & Trail',
        'destination_id' => 6,
        'lat' => 7.4812,
        'lng' => 80.8354,
        'operator' => 'multiple',
        'coverage' => 'no_signal',
        'signal' => 'none',
        'desc' => 'Dangerous wilderness Dead Zone! Zero reception across all networks. Inform local villagers before attempting climb.',
        'trans' => [
            'si' => [
                'location_name' => 'ලකේගල පාමුල සහ තරණ මාර්ගය',
                'description' => 'අතිශය දුෂ්කර වනාන්තර සිග්නල් රහිත කලාපයකි (Dead Zone). කිසිදු ජාලයකට සිග්නල් නොමැත. කඳු තරණයට පෙර ප්‍රදේශවාසීන්ට දැනුම් දෙන්න.',
            ],
            'ta' => [
                'location_name' => 'லக்கேகல மலை அடிவாரம் & பாதை',
                'description' => 'அபாயகரமான காடு சிக்னல் இல்லாத பகுதி! எந்த நெட்வொர்க்கிலும் சிக்னல் இல்லை. ஏறும் முன் உள்ளூர்வாசிகளுக்கு தெரிவிக்கவும்.',
            ],
        ],
    ],
    [
        'dest_slug' => 'sera-ella',
        'location_name' => 'Sera Ella Waterfall & Gorge',
        'destination_id' => 2,
        'lat' => 7.5884,
        'lng' => 80.7555,
        'operator' => 'dialog',
        'coverage' => '2g',
        'signal' => 'poor',
        'desc' => 'Faint 2G signal near the upper trail parking; zero reception inside the waterfall cave gorge.',
        'trans' => [
            'si' => [
                'location_name' => 'සේර ඇල්ල සහ ගුහා පරිශ්‍රය',
                'description' => 'ඉහළ වාහන නැවතුම්පොළ අසල ඉතා දුර්වල 2G සංඥාවක් ලැබේ. දියඇල්ල පිටුපස ඇති ගුහාව තුළ සම්පූර්ණයෙන්ම සිග්නල් නොමැත.',
            ],
            'ta' => [
                'location_name' => 'சேரா எல்லா நீர்வீழ்ச்சி & குகை',
                'description' => 'மேல் பார்க்கிங் அருகே பலவீனமான 2G சிக்னல்; குகைக்குள் சிக்னல் இல்லை.',
            ],
        ],
    ],
    [
        'dest_slug' => 'moragahakanda',
        'location_name' => 'Moragahakanda Dam Viewpoint',
        'destination_id' => 8,
        'lat' => 7.7050,
        'lng' => 80.7850,
        'operator' => 'dialog',
        'coverage' => '4g',
        'signal' => 'excellent',
        'desc' => 'High-speed 4G LTE coverage across the entire dam viewpoint and recreation park.',
        'trans' => [
            'si' => [
                'location_name' => 'මොරගහකන්ද වේල්ල නැරඹුම් මැදිරිය',
                'description' => 'නැරඹුම් මැදිරිය සහ උද්‍යාන පරිශ්‍රය පුරා අතිශය ප්‍රබල අධිවේගී 4G LTE සංඥා ආවරණයක් පවතී.',
            ],
            'ta' => [
                'location_name' => 'மொரகஹகந்த அணை காட்சி முனை',
                'description' => 'அணை காட்சி பகுதி முழுவதும் அதிவேக 4G LTE சிக்னல் சிறப்பான முறையில் கிடைக்கிறது.',
            ],
        ],
    ],
    [
        'dest_slug' => null,
        'location_name' => 'Pallegama Administrative Hub & Bus Stand',
        'destination_id' => null,
        'lat' => 7.5583,
        'lng' => 80.7306,
        'operator' => 'dialog',
        'coverage' => '4g',
        'signal' => 'excellent',
        'desc' => 'Excellent 4G and steady crystal-clear voice service throughout the Pallegama town center.',
        'trans' => [
            'si' => [
                'location_name' => 'පල්ලේගම පරිපාලන නගරය සහ බස් නැවතුම',
                'description' => 'පල්ලේගම නගර මධ්‍යය, බැංකු සහ බස් නැවතුම්පොළ අවට විශිෂ්ට 4G සහ පැහැදිලි ඇමතුම් සබඳතාවක් ඇත.',
            ],
            'ta' => [
                'location_name' => 'பல்லேகம நிர்வாக மையம் & பேருந்து நிலையம்',
                'description' => 'பல்லேகம நகரம் முழுவதும் சிறந்த 4G மற்றும் தெளிவான அழைப்பு சேவை கிடைக்கிறது.',
            ],
        ],
    ],
    [
        'dest_slug' => null,
        'location_name' => 'Pallegama Town Center (Mobitel)',
        'destination_id' => null,
        'lat' => 7.5590,
        'lng' => 80.7312,
        'operator' => 'mobitel',
        'coverage' => '4g',
        'signal' => 'good',
        'desc' => 'Good 4G coverage by SLT-Mobitel in Pallegama town.',
        'trans' => [
            'si' => [
                'location_name' => 'පල්ලේගම නගර මධ්‍යය (මොබිටෙල්)',
                'description' => 'පල්ලේගම නගරයේ මොබිටෙල් 4G ආවරණය යහපත් මට්ටමක පවතී.',
            ],
            'ta' => [
                'location_name' => 'பல்லேகம நகர் மையம் (மொபிடெல்)',
                'description' => 'பல்லேகம நகரில் நல்ல மொபிடெல் 4G கவரேஜ் உள்ளது.',
            ],
        ],
    ],
];

foreach ($seedData as $item) {
    $report = MobileCoverageReport::create([
        'destination_id' => $item['destination_id'],
        'location_name' => $item['location_name'],
        'latitude' => $item['lat'],
        'longitude' => $item['lng'],
        'network_operator' => $item['operator'],
        'coverage_type' => $item['coverage'],
        'signal_strength' => $item['signal'],
        'description' => $item['desc'],
        'reported_by' => $admin->id,
        'status' => 'approved',
        'reported_at' => now()->subDays(rand(1, 10)),
    ]);

    // English translation
    $report->translations()->create([
        'locale' => 'en',
        'location_name' => $item['location_name'],
        'description' => $item['desc'],
    ]);

    // Sinhala translation
    if (isset($item['trans']['si'])) {
        $report->translations()->create([
            'locale' => 'si',
            'location_name' => $item['trans']['si']['location_name'],
            'description' => $item['trans']['si']['description'],
        ]);
    }

    // Tamil translation
    if (isset($item['trans']['ta'])) {
        $report->translations()->create([
            'locale' => 'ta',
            'location_name' => $item['trans']['ta']['location_name'],
            'description' => $item['trans']['ta']['description'],
        ]);
    }
}

echo "Successfully seeded " . count($seedData) . " mobile coverage reports with EN, SI, TA translations!\n";
