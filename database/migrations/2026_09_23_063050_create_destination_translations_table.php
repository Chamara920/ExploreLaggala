<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destination_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('destination_id')
                ->constrained('destinations')
                ->cascadeOnDelete();

            $table->string('locale', 2);

            $table->string('name');
            $table->string('slug');

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->string('location_name')->nullable();

            $table->timestamps();

            $table->unique(
                ['destination_id', 'locale'],
                'destination_translation_locale_unique'
            );

            $table->unique(
                ['locale', 'slug'],
                'destination_translation_slug_unique'
            );

            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_translations');
    }
};
