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
        Schema::table('destination_planner_details', function (Blueprint $table) {
            // Public transport & accessibility logistics
            $table->string('nearest_bus_stop')->nullable()->after('difficulty');
            $table->string('bus_routes')->nullable()->after('nearest_bus_stop');
            $table->string('transport_accessibility')->nullable()->after('bus_routes');
            $table->string('recommended_vehicle')->nullable()->after('transport_accessibility');
            $table->boolean('parking_available')->default(true)->after('recommended_vehicle');

            // Mobile coverage & connectivity logistics
            $table->string('mobile_signal_level')->nullable()->after('parking_available');
            $table->string('best_mobile_networks')->nullable()->after('mobile_signal_level');
            $table->text('connectivity_notes')->nullable()->after('best_mobile_networks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destination_planner_details', function (Blueprint $table) {
            $table->dropColumn([
                'nearest_bus_stop',
                'bus_routes',
                'transport_accessibility',
                'recommended_vehicle',
                'parking_available',
                'mobile_signal_level',
                'best_mobile_networks',
                'connectivity_notes',
            ]);
        });
    }
};
