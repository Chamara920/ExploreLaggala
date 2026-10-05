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
        Schema::create('stay_eat_categories', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('name');
            $table->string('slug');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('section');
            $table->index('sort_order');
            $table->unique(['section', 'slug']);
        });

        Schema::create('stay_eat_items', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->foreignId('category_id')->nullable()->constrained('stay_eat_categories')->nullOnDelete();
            $table->string('status')->default('published');
            $table->boolean('featured')->default(false);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('price_range')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('section');
            $table->index('status');
            $table->index('featured');
            $table->index('sort_order');
        });

        Schema::create('stay_eat_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stay_eat_item_id')->constrained('stay_eat_items')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('title');
            $table->string('slug');
            $table->string('location_name')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('opening_hours')->nullable();
            $table->timestamps();

            $table->unique(['stay_eat_item_id', 'locale'], 'stay_eat_item_trans_item_locale_unique');
            $table->unique(['locale', 'slug'], 'stay_eat_item_trans_locale_slug_unique');
            $table->index('locale');
        });

        Schema::create('stay_eat_item_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stay_eat_item_id')->constrained('stay_eat_items')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();

            $table->index('stay_eat_item_id');
            $table->index('sort_order');
        });

        Schema::create('stay_eat_item_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stay_eat_item_id')->constrained('stay_eat_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['stay_eat_item_id', 'user_id'], 'stay_eat_item_user_review_unique');
            $table->index('status');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stay_eat_item_reviews');
        Schema::dropIfExists('stay_eat_item_images');
        Schema::dropIfExists('stay_eat_item_translations');
        Schema::dropIfExists('stay_eat_items');
        Schema::dropIfExists('stay_eat_categories');
    }
};
