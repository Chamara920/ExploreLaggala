<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use App\Models\ForumCategory;
use App\Models\NewsCategory;
use App\Models\OrganizationType;
use Illuminate\Database\Seeder;

class CommunityDataSeeder extends Seeder
{
    public function run(): void
    {
        // News Categories
        $newsCategories = [
            ['name' => 'General News', 'slug' => 'general-news', 'sort_order' => 1],
            ['name' => 'Community Updates', 'slug' => 'community-updates', 'sort_order' => 2],
            ['name' => 'Development & Infrastructure', 'slug' => 'development-infrastructure', 'sort_order' => 3],
            ['name' => 'Agriculture & Environment', 'slug' => 'agriculture-environment', 'sort_order' => 4],
            ['name' => 'Tourism & Culture', 'slug' => 'tourism-culture', 'sort_order' => 5],
        ];

        foreach ($newsCategories as $cat) {
            NewsCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Event Categories
        $eventCategories = [
            ['name' => 'Cultural & Religious Festivals', 'slug' => 'cultural-religious', 'sort_order' => 1],
            ['name' => 'Community Gathering', 'slug' => 'community-gathering', 'sort_order' => 2],
            ['name' => 'Workshops & Training', 'slug' => 'workshops-training', 'sort_order' => 3],
            ['name' => 'Environmental & Clean-up Drives', 'slug' => 'environmental-drives', 'sort_order' => 4],
            ['name' => 'Sports & Youth', 'slug' => 'sports-youth', 'sort_order' => 5],
        ];

        foreach ($eventCategories as $cat) {
            EventCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Organization Types
        $orgTypes = [
            ['name' => 'Non-Governmental Organization (NGO)', 'slug' => 'ngo', 'sort_order' => 1],
            ['name' => 'Community Based Organization (CBO)', 'slug' => 'cbo', 'sort_order' => 2],
            ['name' => 'Farmers Association (ගොවි සමිති)', 'slug' => 'farmers-association', 'sort_order' => 3],
            ['name' => 'Women Welfare Society (කාන්තා සමිති)', 'slug' => 'women-welfare', 'sort_order' => 4],
            ['name' => 'Youth & Sports Club', 'slug' => 'youth-sports-club', 'sort_order' => 5],
            ['name' => 'Environmental Protection Society', 'slug' => 'environmental-protection', 'sort_order' => 6],
            ['name' => 'Voluntary & Religious Society', 'slug' => 'voluntary-religious', 'sort_order' => 7],
        ];

        foreach ($orgTypes as $type) {
            OrganizationType::firstOrCreate(['slug' => $type['slug']], $type);
        }

        // Forum Categories
        $forumCategories = [
            [
                'name' => 'General Discussion & Inquiries (පොදු සාකච්ඡා හා විමසීම්)',
                'slug' => 'general-discussion',
                'description' => 'General questions and discussions about Laggala-Pallegama local area.',
                'color' => 'blue',
                'sort_order' => 1,
            ],
            [
                'name' => 'Agriculture, Farming & Crops (කෘෂිකර්මය හා ගොවිතැන්)',
                'slug' => 'agriculture-farming',
                'description' => 'Discussions about farming practices, paddy, chena cultivation, prices, and weather advice.',
                'color' => 'emerald',
                'sort_order' => 2,
            ],
            [
                'name' => 'Travel & Tourism Advice (සංචාරක උපදෙස් හා මගපෙන්වීම්)',
                'slug' => 'travel-tourism',
                'description' => 'Visiting Knuckles, Riverston, Pitawala Pathana, camping spots, road conditions, and local homestays.',
                'color' => 'amber',
                'sort_order' => 3,
            ],
            [
                'name' => 'Public Services & Daily Life (රාජ්‍ය සේවා හා එදිනෙදා අවශ්‍යතා)',
                'slug' => 'public-services',
                'description' => 'Questions regarding Divisional Secretariat, Pradeshiya Sabha, hospitals, transport, and utilities.',
                'color' => 'purple',
                'sort_order' => 4,
            ],
        ];

        foreach ($forumCategories as $cat) {
            ForumCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
