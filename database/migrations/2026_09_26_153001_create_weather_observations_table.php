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
        Schema::create('weather_observations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('location_name');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->enum('condition', [
                'sunny',
                'partly_cloudy',
                'cloudy',
                'rain',
                'heavy_rain',
                'mist',
                'fog',
                'windy',
            ]);

            $table->decimal('temperature', 5, 2)->nullable();

            $table->decimal('rainfall', 6, 2)->nullable();

            $table->string('wind_condition', 100)->nullable();

            $table->text('description')->nullable();

            $table->timestamp('observed_at');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->timestamps();

            $table->index('user_id');
            $table->index('observed_at');
            $table->index('status');
            $table->index([
                'latitude',
                'longitude',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_observations');
    }
};
