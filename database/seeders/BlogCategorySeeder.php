<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Travel & Tourism',
                'slug' => 'travel-tourism',
                'description' => 'Travel and tourism related articles.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Culture & Heritage',
                'slug' => 'culture-heritage',
                'description' => 'Local culture, traditions and heritage.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Nature & Environment',
                'slug' => 'nature-environment',
                'description' => 'Nature, environment and conservation.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Adventure & Outdoor',
                'slug' => 'adventure-outdoor',
                'description' => 'Outdoor activities and adventure.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Local Community',
                'slug' => 'local-community',
                'description' => 'Community stories and local information.',
                'sort_order' => 5,
            ],
            [
                'name' => 'News & Events',
                'slug' => 'news-events',
                'description' => 'Local news, events and announcements.',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'sort_order' => $category['sort_order'],
                ]
            );
        }
    }
}
