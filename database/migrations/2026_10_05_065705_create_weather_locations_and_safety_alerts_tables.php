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
        Schema::create('weather_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('elevation_m')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('weather_location_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weather_location_id')->constrained('weather_locations')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['weather_location_id', 'locale'], 'w_loc_trans_loc_locale_unique');
        });

        Schema::create('safety_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('hazard_type', 50)->default('landslide'); // landslide, rockfall, flash_flood, high_wind, dense_mist, road_closure, other
            $table->string('severity', 20)->default('warning'); // advisory, warning, danger
            $table->string('location_name');
            $table->foreignId('weather_location_id')->nullable()->constrained('weather_locations')->nullOnDelete();
            $table->text('description');
            $table->text('safety_instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('hazard_type');
            $table->index('reported_at');
        });

        Schema::create('safety_alert_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safety_alert_id')->constrained('safety_alerts')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->string('location_name')->nullable();
            $table->text('description')->nullable();
            $table->text('safety_instructions')->nullable();
            $table->timestamps();

            $table->unique(['safety_alert_id', 'locale'], 'safety_alert_trans_alert_locale_unique');
        });

        if (! Schema::hasColumn('weather_observations', 'weather_location_id')) {
            Schema::table('weather_observations', function (Blueprint $table) {
                $table->foreignId('weather_location_id')->nullable()->after('location_name')->constrained('weather_locations')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('weather_observations', 'weather_location_id')) {
            Schema::table('weather_observations', function (Blueprint $table) {
                $table->dropForeign(['weather_location_id']);
                $table->dropColumn('weather_location_id');
            });
        }

        Schema::dropIfExists('safety_alert_translations');
        Schema::dropIfExists('safety_alerts');
        Schema::dropIfExists('weather_location_translations');
        Schema::dropIfExists('weather_locations');
    }
};
