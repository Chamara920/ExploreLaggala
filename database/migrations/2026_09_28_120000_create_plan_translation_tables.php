<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------
        // Travel Guide Translations
        // ---------------------------------------------------------
        Schema::create('travel_guide_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_guide_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);           // si | en | ta
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->unique(['travel_guide_id', 'locale'], 'tg_trans_guide_locale_unique');
        });

        // ---------------------------------------------------------
        // Public Transport Translations
        // ---------------------------------------------------------
        Schema::create('public_transport_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_transport_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('route_name');
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('fare_note')->nullable();
            $table->string('operator_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['public_transport_id', 'locale'], 'pt_trans_transport_locale_unique');
        });

        // ---------------------------------------------------------
        // Weather Observation Translations
        // ---------------------------------------------------------
        Schema::create('weather_observation_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weather_observation_id')->constrained('weather_observations', indexName: 'wot_trans_obs_id_fk')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('location_name')->nullable();
            $table->string('wind_condition')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['weather_observation_id', 'locale'], 'wo_trans_obs_locale_unique');
        });

        // ---------------------------------------------------------
        // Mobile Coverage Report Translations
        // ---------------------------------------------------------
        Schema::create('mobile_coverage_report_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobile_coverage_report_id')->constrained('mobile_coverage_reports', indexName: 'mcr_trans_report_id_fk')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('location_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['mobile_coverage_report_id', 'locale'], 'mcr_trans_report_locale_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_coverage_report_translations');
        Schema::dropIfExists('weather_observation_translations');
        Schema::dropIfExists('public_transport_translations');
        Schema::dropIfExists('travel_guide_translations');
    }
};
