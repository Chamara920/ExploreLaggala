<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('explore_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('explore_categories')
                ->cascadeOnDelete();

            $table->string('status')->default('draft');

            $table->boolean('featured')->default(false);

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('status');
            $table->index('featured');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explore_items');
    }
};
