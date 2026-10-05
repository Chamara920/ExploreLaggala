<?php

namespace Database\Seeders;

use App\Models\ExploreCategory;
use App\Models\ExploreItem;
use App\Models\ExploreItemImage;
use App\Models\ExploreItemReview;
use App\Models\ExploreItemTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExploreDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // 1. Categories for Culture & Heritage
        $cultCat1 = ExploreCategory::firstOrCreate(
            ['slug' => 'ancient-temples'],
            [
                'name' => 'Ancient Temples & Sacred Sites',
                'type' => 'culture-heritage',
                'icon' => 'landmark',
                'color' => '#d97706',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $cultCat2 = ExploreCategory::firstOrCreate(
            ['slug' => 'legends-folklore'],
            [
                'name' => 'Legends & Folklore',
                'type' => 'culture-heritage',
                'icon' => 'scroll',
                'color' => '#b45309',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $cultCat3 = ExploreCategory::firstOrCreate(
            ['slug' => 'traditional-heritage'],
            [
                'name' => 'Traditional Village Heritage',
                'type' => 'culture-heritage',
                'icon' => 'home',
                'color' => '#92400e',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // 2. Categories for Outdoor & Adventure
        $advCat1 = ExploreCategory::firstOrCreate(
            ['slug' => 'mountain-trekking'],
            [
                'name' => 'Mountain Peaks & Trekking',
                'type' => 'outdoor-adventure',
                'icon' => 'hiking',
                'color' => '#059669',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $advCat2 = ExploreCategory::firstOrCreate(
            ['slug' => 'waterfalls-pools'],
            [
                'name' => 'Waterfalls & Rock Pools',
                'type' => 'outdoor-adventure',
                'icon' => 'water',
                'color' => '#0d9488',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $advCat3 = ExploreCategory::firstOrCreate(
            ['slug' => 'scenic-viewpoints'],
            [
                'name' => 'Scenic Viewpoints & Escarpments',
                'type' => 'outdoor-adventure',
                'icon' => 'mountain',
                'color' => '#047857',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // -------------------------------------------------------------
        // CULTURE & HERITAGE ITEM 1: Lakegala Rock & Legend
        // -------------------------------------------------------------
        $itemLakegala = ExploreItem::create([
            'category_id' => $cultCat2->id,
            'type' => 'culture-heritage',
            'status' => 'published',
            'featured' => true,
            'latitude' => 7.488056,
            'longitude' => 80.825833,
            'sort_order' => 1,
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemLakegala->id,
            'locale' => 'si',
            'title' => 'ලකේගල පුරාවෘත්තය හා ඓතිහාසික පර්වතය',
            'slug' => 'lakegala-legend-and-rock',
            'location_name' => 'මීමුරේ / ලග්ගල මායිම',
            'short_description' => 'රාවණා රජුගේ සූර්ය කිරණ බලගැන්වූ ස්ථානයක් ලෙස ජනප්‍රවාදගත, පිරමිඩාකාර හැඩැති ලකේගල පර්වතය ලග්ගල සංස්කෘතික අනන්‍යතාවයේ කේන්ද්‍රස්ථානයකි.',
            'description' => '<p>නකල්ස් කඳුකරයේ අතිශය ගුප්ත හා මනස්කාන්ත පර්වතය වන ලකේගල, ලග්ගල සහ මීමුරේ ගම්මානයට ප්‍රෞඪත්වයක් ගෙන දෙයි. පිරමිඩ හැඩැති උල් මුදුනකින් යුත් මෙම පර්වතය වටා ශ්‍රී ලාංකේය ජනප්‍රවාද රැසක් බැඳී පවතී. විශේෂයෙන්ම රාවණ රජුගේ යුගය හා සබැඳි පුරාවෘත්තයන්හි දැක්වෙන්නේ, රාවණා රජු ලකේගල මුදුනේ සිට දඬුමොණරය ගුවන්ගත කළ බව සහ සූර්ය ශක්තිය ආශ්‍රයෙන් බලශක්තිය ලබාගත් බවයි.</p><p>ගම්මුන් අදටත් ලකේගල හඳුන්වන්නේ ගමේ ජීවය සහ මුරදේවතාවා වශයෙනි. සවස් යාමයේදී හිරු බැස යන මොහොතේ ලකේගල මනරම් ඡායා චිත්‍රයක් මෙන් දර්ශනය වේ.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemLakegala->id,
            'locale' => 'en',
            'title' => 'Lakegala Rock & Ancient Legends',
            'slug' => 'lakegala-legend-and-rock',
            'location_name' => 'Meemure / Laggala Border',
            'short_description' => 'Steeped in King Ravana mythology, the iconic pyramidal Lakegala Rock stands as the spiritual guardian and legendary landmark of Laggala.',
            'description' => '<p>Rising majestically in the Knuckles mountain forest, Lakegala is famous for its distinctive triangular pyramid silhouette and deep mythical significance. Local legends link Lakegala to King Ravana, who according to folklore used its peak as a launch pad for the flying aircraft Dandumonara and solar power ceremonies.</p><p>For centuries, the villagers have regarded Lakegala as a sacred presence safeguarding the valleys. Visiting this ancient sentinel offers travelers unforgettable historical insights and scenic splendor.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemLakegala->id,
            'locale' => 'ta',
            'title' => 'லகேகல பாறை மற்றும் பண்டைய புராணங்கள்',
            'slug' => 'lakegala-legend-and-rock',
            'location_name' => 'மீமுரே / லக்கல எல்லை',
            'short_description' => 'இராவண மன்னனின் வரலாற்றுப் பின்னணியைக் கொண்ட பிரமிடு வடிவ லகேகல பாறை லக்கலவின் வரலாற்றுச் சின்னமாகும்.',
            'description' => '<p>நக்கிள்ஸ் மலைத்தொடரின் தனித்துவமான பிரமிடு வடிவ பாறையான லகேகல வரலாற்று முக்கியத்துவம் வாய்ந்தது. இராவண மன்னனின் காலத்து கதைகளுடன் இது தொடர்புபடுத்தப்படுகிறது.</p>',
        ]);

        // Images for Lakegala (up to 3 images)
        ExploreItemImage::create([
            'explore_item_id' => $itemLakegala->id,
            'image_path' => 'explore/01M3780990Z0C7PF1MF32FNCSJ.jpg',
            'caption' => 'Lakegala pyramid peak rising through morning mist',
            'sort_order' => 1,
            'is_cover' => true,
        ]);
        ExploreItemImage::create([
            'explore_item_id' => $itemLakegala->id,
            'image_path' => 'explore/01M378099CK2ET20013MCSCJW5.jpg',
            'caption' => 'Scenic view of Lakegala from the valley approach',
            'sort_order' => 2,
            'is_cover' => false,
        ]);

        // Review
        if ($user) {
            ExploreItemReview::create([
                'explore_item_id' => $itemLakegala->id,
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => 'An absolute awe-inspiring place! The folklore and stories told by village elders make the visit truly spiritual.',
                'status' => 'approved',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);
        }

        // -------------------------------------------------------------
        // CULTURE & HERITAGE ITEM 2: Traditional Meemure Heritage Village
        // -------------------------------------------------------------
        $itemMeemure = ExploreItem::create([
            'category_id' => $cultCat3->id,
            'type' => 'culture-heritage',
            'status' => 'published',
            'featured' => false,
            'latitude' => 7.433333,
            'longitude' => 80.850000,
            'sort_order' => 2,
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemMeemure->id,
            'locale' => 'si',
            'title' => 'මීමුරේ පාරම්පරික ගම්මානය හා ගැමි උරුමය',
            'slug' => 'traditional-meemure-village',
            'location_name' => 'මීමුරේ, ලග්ගල',
            'short_description' => 'ශතවර්ෂ ගණනාවක් පැරණි සාම්ප්‍රදායික ගොවිතැන් ක්‍රම, පැරණි මැටි නිවාස සහ සිරිත් විරිත් සුරැකිව පවතින සජීවී උරුම ගම්මානයකි.',
            'description' => '<p>ශ්‍රී ලංකාවේ හුදෙකලාව පිහිටි පැරණිම ගම්මාන අතරින් එකක් වන මීමුරේ, සාම්ප්‍රදායික කෘෂිකාර්මික ජීවන රටාව හා ගැමි සංස්කෘතිය අදටත් නොවෙනස්ව පවත්වාගෙන යන අපූරු ඉසව්වකි. ගල් කටු මැටි ගෙවල්, පාරම්පරික කමත, පැරණි කෘෂි මෙවලම් මෙන්ම මීමුරේ ගැමියන්ගේ ආගන්තුක සත්කාරය විස්මිත අත්දැකීමකි.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemMeemure->id,
            'locale' => 'en',
            'title' => 'Traditional Meemure Heritage Village',
            'slug' => 'traditional-meemure-village',
            'location_name' => 'Meemure, Laggala',
            'short_description' => 'A living heritage sanctuary where centuries-old agrarian customs, traditional clay homes, and indigenous crafts continue uninterrupted.',
            'description' => '<p>One of the most authentic and historical isolated settlements in Sri Lanka, Meemure showcases living cultural traditions that have endured for centuries. From wattle-and-daub houses and ancestral farming methods to indigenous herbal practices, Meemure offers a journey back in time.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemMeemure->id,
            'locale' => 'ta',
            'title' => 'பாரம்பரிய மீமுரே கிராமம்',
            'slug' => 'traditional-meemure-village',
            'location_name' => 'மீமுரே, லக்கல',
            'short_description' => 'பாரம்பரிய விவசாய முறைகள் மற்றும் மண் வீடுகள் கொண்ட வரலாற்றுச் சிறப்புமிக்க கிராமம்.',
            'description' => '<p>பண்டைய பாரம்பரிய வாழ்க்கை முறையை இன்றும் பாதுகாத்து வரும் ஒரு அழகிய கிராமம்.</p>',
        ]);

        ExploreItemImage::create([
            'explore_item_id' => $itemMeemure->id,
            'image_path' => 'explore/01M378099KZ4RTM049K0AR82F1.jpg',
            'caption' => 'Traditional rural pathway and mountain silhouette',
            'sort_order' => 1,
            'is_cover' => true,
        ]);

        // -------------------------------------------------------------
        // OUTDOOR & ADVENTURE ITEM 1: Pitawala Pathana & Mini World's End
        // -------------------------------------------------------------
        $itemPitawala = ExploreItem::create([
            'category_id' => $advCat3->id,
            'type' => 'outdoor-adventure',
            'status' => 'published',
            'featured' => true,
            'latitude' => 7.540134,
            'longitude' => 80.751240,
            'sort_order' => 1,
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemPitawala->id,
            'locale' => 'si',
            'title' => 'පිටවල පතන සහ කුඩා ලෝකාන්තය',
            'slug' => 'pitawala-pathana-mini-worlds-end',
            'location_name' => 'පිටවල, රිවස්ටන් පාර, ලග්ගල',
            'short_description' => 'විශේෂ තෘණ භූමියක් හරහා විහිදෙන මංපෙතකින් හමුවන මීටර් සිය ගණනක අගාධ බෑවුම සහ අසමසම පරිදර්ශක දර්ශනය විඳින්න.',
            'description' => '<p>පිටවල පතන යනු නකල්ස් කඳු පන්තියට ආවේණික දුර්ලභ ශාක හා සත්ව විශේෂ රැසක් වෙසෙන සුවිශේෂී තෘණ භූමියකි. මෙහි අවසානයේ පිහිටි කුඩා ලෝකාන්තය මීටර් 700කට අධික සිරස් බෑවුමකින් යුක්ත වන අතර, එතැන් සිට පහළ නිම්නය සහ ලග්ගලා ගම්මාන අංශක 360ක දර්ශනයක් ලෙස දිස්වේ. දැඩි සුළං සහ මීදුම නිසා මෙය ත්‍රාසජනක මෙන්ම විශ්මයජනක අත්දැකීමකි.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemPitawala->id,
            'locale' => 'en',
            'title' => 'Pitawala Pathana & Mini World\'s End',
            'slug' => 'pitawala-pathana-mini-worlds-end',
            'location_name' => 'Pitawala, Riverston Road, Laggala',
            'short_description' => 'A unique ecological grassland trail leading to a breathtaking sheer precipice dropping hundreds of meters into the valley below.',
            'description' => '<p>Pitawala Pathana is an ecologically unique pygmy grassland plateau in the Knuckles Conservation Forest. The scenic trail leads directly to "Mini World’s End," a dramatic vertical drop of over 700 meters offering panoramic views of the Laggala valley, jagged cliffs, and distant reservoirs. The rushing wind and sweeping vistas make this a premier outdoor destination.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemPitawala->id,
            'locale' => 'ta',
            'title' => 'பிடவல பதன மற்றும் மினி உலக முடிவு',
            'slug' => 'pitawala-pathana-mini-worlds-end',
            'location_name' => 'பிடவல, லக்கல',
            'short_description' => 'நூற்றுக்கணக்கான மீட்டர் செங்குத்தான சரிவுடன் கூடிய அழகிய புல்வெளி நிலப்பரப்பு.',
            'description' => '<p>நக்கிள்ஸ் மலைப்பகுதியில் அமைந்துள்ள கண்கவர் இயற்கை அழகு கொண்ட இடம்.</p>',
        ]);

        ExploreItemImage::create([
            'explore_item_id' => $itemPitawala->id,
            'image_path' => 'explore/01M3BXN0718Y0WT97JHQ23Z0AZ.webp',
            'caption' => 'Dramatic cliff edge at Mini World’s End',
            'sort_order' => 1,
            'is_cover' => true,
        ]);
        ExploreItemImage::create([
            'explore_item_id' => $itemPitawala->id,
            'image_path' => 'explore/01M3BXN079JSXGNBPSG2ZXTWJD.webp',
            'caption' => 'Lush green plateau and mountain vistas',
            'sort_order' => 2,
            'is_cover' => false,
        ]);

        if ($user) {
            ExploreItemReview::create([
                'explore_item_id' => $itemPitawala->id,
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => 'The view from the drop is mindblowing! High winds, great walking trails, and well maintained pathways.',
                'status' => 'approved',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);
        }

        // -------------------------------------------------------------
        // OUTDOOR & ADVENTURE ITEM 2: Manigala Mountain Trek
        // -------------------------------------------------------------
        $itemManigala = ExploreItem::create([
            'category_id' => $advCat1->id,
            'type' => 'outdoor-adventure',
            'status' => 'published',
            'featured' => false,
            'latitude' => 7.545600,
            'longitude' => 80.738900,
            'sort_order' => 2,
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemManigala->id,
            'locale' => 'si',
            'title' => 'මානිගල කඳු තරණය හා මංපෙත',
            'slug' => 'manigala-mountain-trek',
            'location_name' => 'ඇටන්වල, ලග්ගල',
            'short_description' => 'ඈත අතීතයේ වේලාව බැලීමට සෙවණැල්ල උපයෝගී කරගත්, නකල්ස් හි අතිශය ආකර්ෂණීය පාගමන් කඳු මුදුනකි.',
            'description' => '<p>මානිගල (ඔරලෝසු කන්ද) යනු ඇටන්වල ගම්මානය අසල පිහිටි සුවිශේෂී හැඩයකින් යුත් කන්දකි. අතීතයේ ගැමියන් හිරු එළිය නිසා කන්දෙන් වැටෙන සෙවණැල්ල අනුව වේලාව දැනගත් බව පැවසේ. තරණය ආරම්භයේ සිට ඉහළට යන තුරු කුඹුරු යායවල්, තෙල්ගමු ඔය සහ නකල්ස් කඳු පන්තිය මනරම්ව දිස්වේ. මධ්‍යස්ථ මට්ටමේ වික්‍රමාන්විත පාගමනක් සඳහා කදිම ස්ථානයකි.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemManigala->id,
            'locale' => 'en',
            'title' => 'Manigala Mountain Trek',
            'slug' => 'manigala-mountain-trek',
            'location_name' => 'Atanwala, Laggala',
            'short_description' => 'A famous ridge hike historically used by ancient villagers as a giant sundial to track time, offering rewarding 360-degree vistas.',
            'description' => '<p>Manigala, or the "Clock Mountain," got its name because farmers in Atanwala historically read time based on the shadows cast by the sun over this ridge. The trek climbs through terraced paddy fields, pine woods, and open ridges to reach the summit, giving you thrilling vistas across the Knuckles peaks and Thelgamu Oya valley.</p>',
        ]);

        ExploreItemTranslation::create([
            'explore_item_id' => $itemManigala->id,
            'locale' => 'ta',
            'title' => 'மானிகல மலை நடைபயணம்',
            'slug' => 'manigala-mountain-trek',
            'location_name' => 'அடன்வல, லக்கல',
            'short_description' => 'நேரத்தை அறிய பண்டைய காலத்தில் பயன்படுத்தப்பட்ட புகழ்பெற்ற மலை உச்சி.',
            'description' => '<p>நடைபயண விரும்பிகளுக்கான ஒரு அருமையான சாகச தளம்.</p>',
        ]);

        ExploreItemImage::create([
            'explore_item_id' => $itemManigala->id,
            'image_path' => 'explore/01M3BXN07J9MM6ER67ZZ94DRBP.webp',
            'caption' => 'The ridge walk along Manigala summit',
            'sort_order' => 1,
            'is_cover' => true,
        ]);
    }
}
