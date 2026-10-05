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
        Schema::create('mobile_coverage_reports', function (Blueprint $table) {
            $table->id();

            $table->string('location_name');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->enum('network_operator', [
                'dialog',
                'mobitel',
                'hutch',
                'airtel',
                'multiple',
                'other',
            ]);

            $table->enum('coverage_type', [
                '2g',
                '3g',
                '4g',
                '5g',
                'no_signal',
            ]);

            $table->enum('signal_strength', [
                'excellent',
                'good',
                'fair',
                'poor',
                'none',
            ]);

            $table->text('description')->nullable();

            $table->foreignId('reported_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->timestamp('reported_at');
            $table->timestamps();

            $table->index('status');
            $table->index('network_operator');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_coverage_reports');
    }
};
