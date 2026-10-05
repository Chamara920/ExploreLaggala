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
        Schema::table('public_transports', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('status');
            $table->string('suspension_reason')->nullable()->after('is_suspended');
            $table->string('suspended_until')->nullable()->after('suspension_reason');
            $table->text('key_stops')->nullable()->after('to_location');
            $table->string('frequency')->nullable()->after('arrival_time');
            $table->string('bus_category')->nullable()->default('sltb')->after('transport_type'); // sltb, private, village_shuttle
        });

        Schema::table('public_transport_translations', function (Blueprint $table) {
            $table->string('suspension_reason')->nullable()->after('notes');
            $table->text('key_stops')->nullable()->after('to_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_transport_translations', function (Blueprint $table) {
            $table->dropColumn(['suspension_reason', 'key_stops']);
        });

        Schema::table('public_transports', function (Blueprint $table) {
            $table->dropColumn([
                'is_suspended',
                'suspension_reason',
                'suspended_until',
                'key_stops',
                'frequency',
                'bus_category',
            ]);
        });
    }
};
