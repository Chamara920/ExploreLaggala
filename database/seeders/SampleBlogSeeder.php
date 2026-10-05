<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleBlogSeeder extends Seeder
{
    public function run(): void
    {
        if (BlogPost::count() > 0) {
            return;
        }

        $user = User::first() ?? User::factory()->create();

        $p1 = BlogPost::create([
            'user_id' => $user->id,
            'category_id' => 1,
            'status' => 'published',
            'featured' => true,
            'cover_image' => 'destinations/01M3780990Z0C7PF1MF32FNCSJ.jpg',
            'published_at' => now()->subDays(2),
            'views' => 285,
        ]);
        $p1->translations()->create([
            'locale' => 'en',
            'title' => 'An Unforgettable Journey Across Pitawala Pathana & Mini World\'s End',
            'slug' => 'journey-pitawala-pathana-mini-worlds-end',
            'excerpt' => 'Exploring the mystical grassland plateau of Pitawala Pathana, hidden flora, and the sheer drop at Mini World\'s End shrouded in Knuckles mountain mist.',
            'content' => '<p>Pitawala Pathana is one of the most mesmerizing montane grasslands in Sri Lanka. Situated along the winding road between Rattota and Illukkumbura, it sits upon a unique shallow rock slab draped in lush velvety turf.</p><p>As you embark on the trail towards Mini World’s End, the cool mountain breeze welcomes you. The sudden precipice reveals panoramic vistas of emerald valleys and distant peaks that take your breath away. In this guide, we share essential tips for hikers, photography spots, and how to respect this fragile endemic ecosystem.</p>',
        ]);
        $p1->translations()->create([
            'locale' => 'si',
            'title' => 'පිටවල පතන සහ පුංචි ලෝකාන්තය ඔස්සේ අපූර්ව සංචාරක අත්දැකීමක්',
            'slug' => 'journey-pitawala-pathana-si',
            'excerpt' => 'නකල්ස් කඳුකරයේ මිහිදුම් අතර සැඟවුණු පිටවල පතන තණබිම සහ මනරම් පුංචි ලෝකාන්තය නැරඹීමේ සුන්දර සංචාරක සටහන.',
            'content' => '<p>පිටවල පතන යනු ශ්‍රී ලංකාවේ පිහිටි සුවිශේෂී හා අතිශය සුන්දර කඳුකර තණබිම් පරිසර පද්ධතියකි. රත්තොට-ඉලුක්කුඹුර මාවත ඔස්සේ ගමන් කරන විට හමුවන මෙම අසිරිමත් ස්ථානය හරිත පලසකින් වැසී ගිය මනස්කාන්ත භූමියකි.</p><p>පුංචි ලෝකාන්තය දෙසට ඇවිද යන විට හමන සිසිල් මඳනලත්, මිටියාවත හරහා දිස්වන මනරම් දසුනත් ඔබේ සිත නිවා සනසනු නොඅනුමානය.</p>',
        ]);

        $p2 = BlogPost::create([
            'user_id' => $user->id,
            'category_id' => 3,
            'status' => 'published',
            'featured' => false,
            'cover_image' => 'destinations/01M3BXN0718Y0WT97JHQ23Z0AZ.webp',
            'published_at' => now()->subDays(5),
            'views' => 192,
        ]);
        $p2->translations()->create([
            'locale' => 'en',
            'title' => 'The Hidden Cave of Sera Ella: Walking Behind the Waterfall',
            'slug' => 'hidden-cave-sera-ella-waterfall',
            'excerpt' => 'A unique natural wonder in Pothatawela village where travelers can walk safely behind a thundering cascade of pure mountain water.',
            'content' => '<p>Nestled deep within the serene village of Pothatawela in Laggala, Sera Ella offers an experience unlike almost any other waterfall in the region. A natural stone cave runs directly behind the curtain of falling water, accessible via stone steps.</p><p>Standing behind the roaring cascade, shrouded in refreshing water vapor while looking out into the dense tropical forest, is an exhilarating memory every traveler must experience.</p>',
        ]);
        $p2->translations()->create([
            'locale' => 'si',
            'title' => 'සේර ඇල්ලේ සැඟවුණු ගුහාව: දිය ඇල්ල පිටුපසින් ඇවිද යාමේ අත්දැකීම',
            'slug' => 'hidden-cave-sera-ella-si',
            'excerpt' => 'පොතටවෙල ගම්මානයේ පිහිටි සේර ඇල්ල පිටුපස ඇති ස්වාභාවික ගුහාව තුළින් දිය ඇල්ල නැරඹීමේ අපූර්ව විස්තරය.',
            'content' => '<p>සේර ඇල්ල යනු ලග්ගල පොතටවෙල ගම්මානයේ පිහිටි ඉතාමත් මනස්කාන්ත දිය ඇල්ලකි. මෙහි ඇති සුවිශේෂීම අංගය වන්නේ කඩාහැලෙන ජල ධාරාවට පිටුපසින් පිහිටි ආරක්ෂිත ගුහාවයි.</p>',
        ]);

        $p3 = BlogPost::create([
            'user_id' => $user->id,
            'category_id' => 4,
            'status' => 'published',
            'featured' => false,
            'cover_image' => 'destinations/01M378099CK2ET20013MCSCJW5.jpg',
            'published_at' => now()->subDays(8),
            'views' => 140,
        ]);
        $p3->translations()->create([
            'locale' => 'en',
            'title' => 'Trekking the Misty Knuckles: A Guide to Responsible Camping',
            'slug' => 'trekking-knuckles-responsible-camping-guide',
            'excerpt' => 'Everything you need to know about hiking permits, weather patterns, safety precautions, and leaving no trace in the Knuckles World Heritage Forest.',
            'content' => '<p>The Knuckles Conservation Forest is a UNESCO World Heritage site known for its dramatic climatic variation, cloud forests, and endemic biodiversity. When planning an expedition here, preparation and ecological mindfulness are paramount.</p><p>Always verify local weather advisories at Laggala before starting your ascent, carry adequate water, and pack out every piece of non-biodegradable waste to preserve this paradise.</p>',
        ]);
        $p3->translations()->create([
            'locale' => 'si',
            'title' => 'නකල්ස් කඳු තරණය සහ වගකීම් සහගත කඳවුරු බැඳීම සඳහා මඟපෙන්වීමක්',
            'slug' => 'trekking-knuckles-camping-guide-si',
            'excerpt' => 'නකල්ස් ලෝක උරුම වනාන්තරයේ කඳවුරු බැඳීමේදී සැළකිලිමත් විය යුතු කරුණු, ආරක්ෂක උපදෙස් සහ පරිසර සංරක්ෂණය.',
            'content' => '<p>නකල්ස් රක්ෂිත වනාන්තරය යුනෙස්කෝ ලෝක උරුමයක් වන අතර එහි පරිසරය රැකගනිමින් සංචාරය කිරීම සෑම සංචාරකයෙකුගේම වගකීමකි.</p>',
        ]);
    }
}
