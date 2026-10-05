<?php

namespace Database\Seeders;

use App\Models\HomePageSetting;
use App\Models\HomePageSettingTranslation;
use App\Models\HomeSlide;
use App\Models\HomeSlideTranslation;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Default Home Slides
        |--------------------------------------------------------------------------
        */
        $slide1 = HomeSlide::firstOrCreate(
            ['sort_order' => 1],
            [
                'image_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=2200&q=90',
                'primary_button_url' => '/explore/destinations',
                'secondary_button_url' => '/explore/map',
                'planner_button_url' => '/plan/travel-guide',
                'is_active' => true,
            ]
        );

        $slide1Translations = [
            'en' => [
                'badge' => 'Explore Laggala • Knuckles Range',
                'title' => 'Discover the Green Heart of Laggala',
                'subtitle' => 'Journey through misty mountain passes, hidden cave waterfalls, sacred wilderness, and authentic village heritage in Sri Lanka’s central highlands.',
                'primary_button_text' => 'Explore Destinations',
                'secondary_button_text' => 'Interactive Map',
                'planner_button_text' => 'Smart Trip Planner',
                'planner_button_subtitle' => 'Plan Your Journey',
            ],
            'si' => [
                'badge' => 'ලග්ගල ගවේෂණය • නකල්ස් කඳුවැටිය',
                'title' => 'ලග්ගල හරිත හදවත සොයා යන්න',
                'subtitle' => 'ශ්‍රී ලංකාවේ මධ්‍යම කඳුකරයේ මිහිදුම් පිරි කඳු මුදුන්, සැඟවුණු දියඇලි, වනගත සෞන්දර්යය සහ පාරම්පරික ගැමි උරුමයන් සොයා සංචාරය කරන්න.',
                'primary_button_text' => 'සංචාරක ස්ථාන ගවේෂණය',
                'secondary_button_text' => 'සිතියම බලන්න',
                'planner_button_text' => 'ස්මාර්ට් චාරිකා සැලසුම්කරු',
                'planner_button_subtitle' => 'ඔබේ ගමන සැලසුම් කරන්න',
            ],
            'ta' => [
                'badge' => 'லக்கல ஆய்வு • நக்கிள்ஸ் மலைத்தொடர்',
                'title' => 'லக்கலவின் பசுமை இதயத்தைக் கண்டறியுங்கள்',
                'subtitle' => 'இலங்கையின் மத்திய மலைநாட்டின் பனிமூட்டமான கணவாய்கள், மறைந்திருக்கும் குகை நீர்வீழ்ச்சிகள் மற்றும் பாரம்பரிய கிராமப் பண்பாட்டை அனுபவியுங்கள்.',
                'primary_button_text' => 'சுற்றுலா தலங்களை ஆராயுங்கள்',
                'secondary_button_text' => 'வரைபடம்',
                'planner_button_text' => 'ஸ்மார்ட் பயணத் திட்டமிடுபவர்',
                'planner_button_subtitle' => 'உங்கள் பயணத்தைத் திட்டமிடுங்கள்',
            ],
        ];

        foreach ($slide1Translations as $locale => $data) {
            HomeSlideTranslation::updateOrCreate(
                ['home_slide_id' => $slide1->id, 'locale' => $locale],
                $data
            );
        }

        $slide2 = HomeSlide::firstOrCreate(
            ['sort_order' => 2],
            [
                'image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=2200&q=90',
                'primary_button_url' => '/explore/outdoor-adventure',
                'secondary_button_url' => '/explore/map',
                'planner_button_url' => '/plan/travel-guide',
                'is_active' => true,
            ]
        );

        $slide2Translations = [
            'en' => [
                'badge' => 'Outdoor & Trekking',
                'title' => 'Uncharted Trails & Pristine Mountain Vistas',
                'subtitle' => 'From the rugged ridges of Riverston to the sweeping vistas of Pitawala Pathana and Manigala, experience world-class outdoor hiking.',
                'primary_button_text' => 'Explore Adventures',
                'secondary_button_text' => 'Interactive Map',
                'planner_button_text' => 'Smart Trip Planner',
                'planner_button_subtitle' => 'Plan Your Itinerary',
            ],
            'si' => [
                'badge' => 'එළිමහන් වික්‍රමාන්විත සහ පා ගමන්',
                'title' => 'නොදුටු මංපෙත් සහ මනස්කාන්ත කඳු මුදුන්',
                'subtitle' => 'රිවස්ටන් කඳු මුදුනේ සිට පිටවල පතන හා මානිගල දක්වා වූ අසමසම කඳු තරණ අත්දැකීම් විඳගන්න.',
                'primary_button_text' => 'වික්‍රමාන්විත ගවේෂණය',
                'secondary_button_text' => 'සිතියම බලන්න',
                'planner_button_text' => 'ස්මාර්ට් චාරිකා සැලසුම්කරු',
                'planner_button_subtitle' => 'ඔබේ ගමන සැලසුම් කරන්න',
            ],
            'ta' => [
                'badge' => 'வெளிப்புற சாகசங்கள் மற்றும் நடைபயணம்',
                'title' => 'அறியப்படாத பாதைகள் & அழகிய மலைக் காட்சிகள்',
                'subtitle' => 'ரிவர்ஸ்டன் முதல் பிடவல பத்தன மற்றும் மணிகல வரையிலான உலகத்தரம் வாய்ந்த மலையேற்றத்தை அனுபவியுங்கள்.',
                'primary_button_text' => 'சாகசங்களை ஆராயுங்கள்',
                'secondary_button_text' => 'வரைபடம்',
                'planner_button_text' => 'ஸ்மார்ட் பயணத் திட்டமிடுபவர்',
                'planner_button_subtitle' => 'திட்டமிடுங்கள்',
            ],
        ];

        foreach ($slide2Translations as $locale => $data) {
            HomeSlideTranslation::updateOrCreate(
                ['home_slide_id' => $slide2->id, 'locale' => $locale],
                $data
            );
        }

        $slide3 = HomeSlide::firstOrCreate(
            ['sort_order' => 3],
            [
                'image_url' => 'https://images.unsplash.com/photo-1433086966358-54859d0ed716?auto=format&fit=crop&w=2200&q=90',
                'primary_button_url' => '/explore/destinations',
                'secondary_button_url' => '/explore/map',
                'planner_button_url' => '/plan/travel-guide',
                'is_active' => true,
            ]
        );

        $slide3Translations = [
            'en' => [
                'badge' => 'Waterfalls & Natural Wonder',
                'title' => 'Behind the Waterfall: The Wonders of Sera Ella',
                'subtitle' => 'Walk into the secret natural cave behind the roaring cascades of Sera Ella, swim in crystal-clear rivers, and marvel at timeless beauty.',
                'primary_button_text' => 'Explore Waterfalls',
                'secondary_button_text' => 'Interactive Map',
                'planner_button_text' => 'Smart Trip Planner',
                'planner_button_subtitle' => 'Custom Route',
            ],
            'si' => [
                'badge' => 'දියඇලි සහ ස්වභාවික අසිරිය',
                'title' => 'දියඇල්ල පිටුපස: සේර ඇල්ලේ සොබා අසිරිය',
                'subtitle' => 'සේර ඇල්ලේ ස්වභාවික ගුහාව තුළට ඇවිද යන්න, පිරිසිදු දිය දහරාවලින් නැහැවී අපූර්ව සොබා සෞන්දර්යය විඳගන්න.',
                'primary_button_text' => 'දියඇලි ගවේෂණය',
                'secondary_button_text' => 'සිතියම බලන්න',
                'planner_button_text' => 'ස්මාර්ට් චාරිකා සැලසුම්කරු',
                'planner_button_subtitle' => 'නියමිත ගමන් මග',
            ],
            'ta' => [
                'badge' => 'நீர்வீழ்ச்சிகள் மற்றும் இயற்கை அதிசயங்கள்',
                'title' => 'நீர்வீழ்ச்சியின் பின்னால்: சேர எல்லாவின் அதிசயம்',
                'subtitle' => 'சேர எல்லாவின் இயற்கை குகைக்குள் நடந்து செல்லுங்கள், தெளிவான நதிகளில் நீராடி மகிழுங்கள்.',
                'primary_button_text' => 'நீர்வீழ்ச்சிகளை ஆராயுங்கள்',
                'secondary_button_text' => 'வரைபடம்',
                'planner_button_text' => 'ஸ்மார்ட் பயணத் திட்டமிடுபவர்',
                'planner_button_subtitle' => 'தனிப்பயன் பாதை',
            ],
        ];

        foreach ($slide3Translations as $locale => $data) {
            HomeSlideTranslation::updateOrCreate(
                ['home_slide_id' => $slide3->id, 'locale' => $locale],
                $data
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Default Home Page Settings
        |--------------------------------------------------------------------------
        */
        $setting = HomePageSetting::firstOrCreate(
            ['id' => 1],
            [
                'featured_destination_ids' => null,
                'featured_blog_post_ids' => null,
                'featured_news_post_ids' => null,
                'featured_event_ids' => null,
                'destinations_count' => 6,
                'blog_posts_count' => 3,
                'news_posts_count' => 3,
                'events_count' => 3,
            ]
        );

        $settingTranslations = [
            'en' => [
                'destinations_title' => 'Featured Destinations',
                'destinations_subtitle' => 'Explore the pristine waterfalls, majestic misty peaks, and awe-inspiring nature trails that define the breathtaking wilderness of Laggala.',
                'blog_title' => 'Latest Blog',
                'blog_subtitle' => 'Stories & traveler guides',
                'news_title' => 'Latest News',
                'news_subtitle' => 'Laggala community updates',
                'events_title' => 'Latest Events',
                'events_subtitle' => 'What\'s happening in Laggala',
                'weather_title' => 'Planning a Hike in the Knuckles Range?',
                'weather_subtitle' => 'Mountain weather in Laggala can change rapidly. Check recent weather observations, rainfall levels, and trail safety before setting off.',
            ],
            'si' => [
                'destinations_title' => 'ප්‍රමුඛ සංචාරක ස්ථාන',
                'destinations_subtitle' => 'ලග්ගල සුන්දර පරිසරයේ විහිදී ඇති නොඉඳුල් දියඇලි, මීදුමින් වැසුණු කඳු මුදුන් සහ ස්වභාවික වන මංපෙත් ගවේෂණය කරන්න.',
                'blog_title' => 'නවතම බ්ලොග් සටහන්',
                'blog_subtitle' => 'සංචාරක කථා සහ මගපෙන්වීම්',
                'news_title' => 'නවතම පුවත්',
                'news_subtitle' => 'ලග්ගල ප්‍රජා තොරතුරු සහ පුවත්',
                'events_title' => 'ඉදිරි සිදුවීම්',
                'events_subtitle' => 'ලග්ගල ප්‍රදේශයේ ඉදිරි ක්‍රියාකාරකම්',
                'weather_title' => 'නකල්ස් කඳුවැටියේ සංචාරය කිරීමට සැලසුම් කරනවාද?',
                'weather_subtitle' => 'ලග්ගල කඳුකරයේ කාලගුණය ඉක්මනින් වෙනස් විය හැක. ගමන ආරම්භ කිරීමට පෙර කාලගුණය සහ මාර්ග ආරක්ෂාව පිළිබඳ පරීක්ෂා කරන්න.',
            ],
            'ta' => [
                'destinations_title' => 'முக்கிய சுற்றுலா தலங்கள்',
                'destinations_subtitle' => 'லக்கலவின் அழகிய நீர்வீழ்ச்சிகள், பனிமூட்டமான மலைகள் மற்றும் இயற்கை நடைபாதைகளை ஆராயுங்கள்.',
                'blog_title' => 'சமீபத்திய வலைப்பதிவு',
                'blog_subtitle' => 'கதைகள் மற்றும் பயண வழிகாட்டிகள்',
                'news_title' => 'சமீபத்திய செய்திகள்',
                'news_subtitle' => 'லக்கல சமூக செய்திகள்',
                'events_title' => 'வரவிருக்கும் நிகழ்வுகள்',
                'events_subtitle' => 'லக்கலவில் என்ன நடக்கிறது',
                'weather_title' => 'நக்கிள்ஸ் மலைத்தொடரில் மலையேற்றம் செய்ய திட்டமிடுகிறீர்களா?',
                'weather_subtitle' => 'லக்கல மலைப்பகுதியின் வானிலை விரைவாக மாறக்கூடும். புறப்படுவதற்கு முன் சமீபத்திய வானிலை அவதானிப்புகள் மற்றும் பாதை பாதுகாப்பை சரிபார்க்கவும்.',
            ],
        ];

        foreach ($settingTranslations as $locale => $data) {
            HomePageSettingTranslation::updateOrCreate(
                ['home_page_setting_id' => $setting->id, 'locale' => $locale],
                $data
            );
        }
    }
}
