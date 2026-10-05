<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('explore_item_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('explore_item_id')
                ->constrained('explore_items')
                ->cascadeOnDelete();

            $table->string('locale', 2);

            $table->string('title');
            $table->string('slug');

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->string('location_name')->nullable();

            $table->timestamps();

            $table->unique(
                ['explore_item_id', 'locale'],
                'explore_item_translation_locale_unique'
            );

            $table->unique(
                ['locale', 'slug'],
                'explore_item_translation_slug_unique'
            );

            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explore_item_translations');
    }
};
