<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable();
            $table->text('image_url')->nullable();
            $table->string('primary_button_url')->nullable()->default('/explore/destinations');
            $table->string('secondary_button_url')->nullable()->default('/explore/map');
            $table->string('planner_button_url')->nullable()->default('/plan/travel-guide');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_slide_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_slide_id')->constrained('home_slides')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('badge')->nullable();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('primary_button_text')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('planner_button_text')->nullable();
            $table->string('planner_button_subtitle')->nullable();
            $table->timestamps();

            $table->unique(['home_slide_id', 'locale']);
        });

        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id();
            $table->json('featured_destination_ids')->nullable();
            $table->json('featured_blog_post_ids')->nullable();
            $table->json('featured_news_post_ids')->nullable();
            $table->json('featured_event_ids')->nullable();
            $table->integer('destinations_count')->default(6);
            $table->integer('blog_posts_count')->default(3);
            $table->integer('news_posts_count')->default(3);
            $table->integer('events_count')->default(3);
            $table->timestamps();
        });

        Schema::create('home_page_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_page_setting_id')->constrained('home_page_settings')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('destinations_title')->nullable();
            $table->text('destinations_subtitle')->nullable();
            $table->string('blog_title')->nullable();
            $table->text('blog_subtitle')->nullable();
            $table->string('news_title')->nullable();
            $table->text('news_subtitle')->nullable();
            $table->string('events_title')->nullable();
            $table->text('events_subtitle')->nullable();
            $table->string('weather_title')->nullable();
            $table->text('weather_subtitle')->nullable();
            $table->timestamps();

            $table->unique(['home_page_setting_id', 'locale'], 'hps_trans_setting_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_page_setting_translations');
        Schema::dropIfExists('home_page_settings');
        Schema::dropIfExists('home_slide_translations');
        Schema::dropIfExists('home_slides');
    }
};
