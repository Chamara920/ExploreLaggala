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
        // 1. Service Places (Health, Shops, Banks/ATMs, Fuel/EV, Education)
        Schema::create('service_places', function (Blueprint $table) {
            $table->id();
            // health, shops-businesses, banks-atms, fuel-ev, education
            $table->string('section', 50)->index();
            $table->string('sub_category', 100)->nullable()->index(); // e.g. hospital, clinic, pharmacy / supermarket, hardware / bank, atm / petrol, ev_charging / school, college
            $table->string('status', 20)->default('published')->index();
            $table->boolean('featured')->default(false)->index();

            // Single photo constraint
            $table->string('image_path')->nullable();

            // Location coordinates
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Contacts & details
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('emergency_hotline')->nullable();
            $table->boolean('is_24_hours')->default(false);

            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        // 2. Multilingual Translations (Sinhala, English, Tamil)
        Schema::create('service_place_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_place_id')->constrained('service_places')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name');
            $table->string('slug')->index();
            $table->string('location_name')->nullable(); // Address / Location
            $table->string('operating_hours')->nullable(); // e.g. Mon-Fri 8AM-5PM
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('key_facilities')->nullable(); // Available facilities, special services
            $table->timestamps();

            $table->unique(['service_place_id', 'locale']);
        });

        // 3. Service Place Reviews (Comments & Ratings with moderation)
        Schema::create('service_place_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_place_id')->constrained('service_places')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('rating')->default(5);
            $table->text('comment')->nullable();
            $table->string('status', 20)->default('pending')->index(); // pending, approved, rejected
            $table->text('admin_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['service_place_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_place_reviews');
        Schema::dropIfExists('service_place_translations');
        Schema::dropIfExists('service_places');
    }
};
