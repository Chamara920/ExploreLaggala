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
        Schema::create('organization_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('community_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('organization_types')->nullOnDelete();
            $table->string('registration_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('status')->default('draft'); // draft, pending_review, published, rejected
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('community_organization_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('community_organizations')->cascadeOnDelete();
            $table->string('locale', 2);
            $table->string('name');
            $table->string('slug');
            $table->text('summary')->nullable();
            $table->longText('description');
            $table->text('services_offered')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'locale'], 'comm_org_trans_org_locale_unique');
            $table->unique(['locale', 'slug'], 'comm_org_trans_locale_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_organization_translations');
        Schema::dropIfExists('community_organizations');
        Schema::dropIfExists('organization_types');
    }
};
