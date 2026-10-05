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
        Schema::create('public_transports', function (Blueprint $table) {
            $table->id();

            $table->string('route_name');
            $table->string('route_number')->nullable();

            $table->enum('transport_type', [
                'bus',
                'train',
                'taxi',
                'tuk_tuk',
                'private_hire',
                'other',
            ])->default('bus');

            $table->string('from_location');
            $table->string('to_location');

            $table->string('departure_time')->nullable();
            $table->string('arrival_time')->nullable();

            // JSON array for multiple schedule times
            $table->json('schedule_times')->nullable();

            $table->decimal('fare', 8, 2)->nullable();
            $table->string('fare_note', 255)->nullable();

            $table->string('operator_name')->nullable();
            $table->string('contact_number', 30)->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'seasonal',
            ])->default('active');

            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('community_submitted')->default(false);

            $table->timestamps();

            $table->index('transport_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_transports');
    }
};
