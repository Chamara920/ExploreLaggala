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
        Schema::create('travel_guides', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->enum('category', [
                'getting_here',
                'accommodation',
                'food_drink',
                'safety_tips',
                'cultural_etiquette',
                'packing_list',
                'best_time_to_visit',
                'local_customs',
                'transportation',
                'money_budget',
                'health_medical',
                'general',
            ])->default('general');

            $table->text('summary')->nullable();
            $table->longText('content');

            $table->string('cover_image')->nullable();

            $table->enum('status', [
                'draft',
                'published',
            ])->default('draft');

            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->foreignId('author_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_guides');
    }
};
