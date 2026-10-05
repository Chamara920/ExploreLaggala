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
        Schema::create('destination_planner_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('destination_id')
                ->constrained('destinations')
                ->cascadeOnDelete();

            $table->unsignedInteger('visit_duration_minutes');

            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();

            $table->decimal('entry_fee', 10, 2)->nullable();

            $table->enum('difficulty', [
                'easy',
                'moderate',
                'difficult',
            ])->default('easy');

            $table->boolean('planner_enabled')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique('destination_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_planner_details');
    }
};
