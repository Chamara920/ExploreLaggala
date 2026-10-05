<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Emergency Contacts
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('hotline'); // hotline, medical, police, disaster, utility, local
            $table->string('phone_number');
            $table->string('alternate_phone')->nullable();
            $table->string('short_code')->nullable(); // e.g. 119, 1990, 117
            $table->string('website')->nullable();
            $table->boolean('is_toll_free')->default(false);
            $table->boolean('is_24x7')->default(true);
            $table->string('status')->default('published');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('emergency_contact_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_contact_id')->constrained('emergency_contacts')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('name');
            $table->string('department')->nullable();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->unique(['emergency_contact_id', 'locale'], 'emg_contact_trans_locale_unique');
            $table->index('locale');
        });

        // 2. Hospitals & Clinics
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('government'); // government, private, clinic, pharmacy, ayurvedic
            $table->string('category')->nullable(); // Base Hospital, Divisional Hospital, Rural Hospital, PMCU, Private Clinic
            $table->string('emergency_phone')->nullable();
            $table->string('phone');
            $table->string('ambulance_phone')->nullable();
            $table->boolean('has_ambulance')->default(false);
            $table->boolean('has_emergency_unit')->default(true);
            $table->boolean('is_24x7')->default(true);
            $table->string('operating_hours')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable(); // Laggala-Pallegama, Rattota, Matale, Naula, Dambulla
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('google_maps_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('published');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('hospital_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->text('available_facilities')->nullable();
            $table->timestamps();

            $table->unique(['hospital_id', 'locale'], 'hospital_trans_locale_unique');
            $table->index('locale');
        });

        // 3. Police Stations
        Schema::create('police_stations', function (Blueprint $table) {
            $table->id();
            $table->string('division')->default('Matale');
            $table->string('emergency_phone')->nullable();
            $table->string('phone');
            $table->string('oic_phone')->nullable(); // Officer In Charge direct
            $table->string('alternate_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('google_maps_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('published');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('police_station_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('police_station_id')->constrained('police_stations')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('jurisdiction')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['police_station_id', 'locale'], 'police_trans_locale_unique');
            $table->index('locale');
        });

        // 4. Wildlife & Forest
        Schema::create('wildlife_forest_offices', function (Blueprint $table) {
            $table->id();
            $table->string('department_type')->default('conservation'); // wildlife, forest, conservation
            $table->string('range_area')->nullable(); // e.g. Knuckles Range - Illukkumbura
            $table->string('emergency_hotline')->nullable(); // for elephant attacks, forest fires, snake bite rescue
            $table->string('phone');
            $table->string('officer_in_charge_phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('google_maps_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('published');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('wildlife_forest_office_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wildlife_forest_office_id')->constrained('wildlife_forest_offices', indexName: 'wfo_trans_wfo_id_fk')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('duties_description')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['wildlife_forest_office_id', 'locale'], 'wildlife_trans_locale_unique');
            $table->index('locale');
        });

        // 5. Vehicle Assistance
        Schema::create('vehicle_assistances', function (Blueprint $table) {
            $table->id();
            $table->string('service_type')->default('towing'); // towing, breakdown, tyre_repair, mechanic, recovery_4x4, multiple
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->boolean('is_24x7')->default(true);
            $table->boolean('has_flatbed_tow')->default(false);
            $table->boolean('has_4x4_recovery')->default(false);
            $table->string('city')->nullable();
            $table->string('base_location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('google_maps_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('published');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('vehicle_assistance_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_assistance_id')->constrained('vehicle_assistances')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('provider_name');
            $table->string('contact_person')->nullable();
            $table->string('covered_areas')->nullable();
            $table->text('services_offered')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['vehicle_assistance_id', 'locale'], 'vehicle_trans_locale_unique');
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_assistance_translations');
        Schema::dropIfExists('vehicle_assistances');
        Schema::dropIfExists('wildlife_forest_office_translations');
        Schema::dropIfExists('wildlife_forest_offices');
        Schema::dropIfExists('police_station_translations');
        Schema::dropIfExists('police_stations');
        Schema::dropIfExists('hospital_translations');
        Schema::dropIfExists('hospitals');
        Schema::dropIfExists('emergency_contact_translations');
        Schema::dropIfExists('emergency_contacts');
    }
};
