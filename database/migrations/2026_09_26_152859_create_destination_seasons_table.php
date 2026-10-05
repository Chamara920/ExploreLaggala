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
        Schema::create('destination_seasons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('destination_id')
                ->constrained('destinations')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('month');

            $table->enum('rating', [
                'best',
                'suitable',
                'not_recommended',
            ]);

            $table->string('note', 500)->nullable();

            $table->timestamps();

            $table->unique([
                'destination_id',
                'month',
            ]);

            $table->index('month');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_seasons');
    }
};
