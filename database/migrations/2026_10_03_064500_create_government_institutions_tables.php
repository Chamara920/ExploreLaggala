<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Basic Profile: Institutions Table
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('divisional_secretariat')->index(); // divisional_secretariat, local_authority, government_department, etc.
            $table->string('status')->default('published')->index(); // published, draft, archived
            $table->boolean('featured')->default(false)->index();
            $table->string('image_path')->nullable(); // Single photo only (Req 4)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('fax')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        // 2. Institution Translations
        Schema::create('institution_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name');
            $table->string('slug')->index();
            $table->string('location_name')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('office_hours')->nullable();
            $table->timestamps();

            $table->unique(['institution_id', 'locale']);
        });

        // 3. Organizational Structure: Units / Departments
        Schema::create('institution_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('institution_units')->nullOnDelete();
            $table->string('slug')->index();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 4. Institution Unit Translations
        Schema::create('institution_unit_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_unit_id')->constrained('institution_units', indexName: 'inst_unit_trans_unit_id_fk')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['institution_unit_id', 'locale'], 'inst_unit_trans_unit_locale_unique');
        });

        // 5. Services under Units / Institution
        Schema::create('institution_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('institution_units')->nullOnDelete();
            $table->string('fee')->nullable();
            $table->string('processing_time')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 6. Institution Service Translations
        Schema::create('institution_service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_service_id')->constrained('institution_services', indexName: 'inst_srv_trans_srv_id_fk')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->timestamps();

            $table->unique(['institution_service_id', 'locale'], 'inst_srv_trans_srv_locale_unique');
        });

        // 7. Officers Directory
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('institution_units')->nullOnDelete();
            $table->string('photo')->nullable(); // Optional photo
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('extension')->nullable();
            $table->string('working_hours')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 8. Officer Translations
        Schema::create('officer_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained('officers')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name');
            $table->string('designation');
            $table->text('responsibilities')->nullable();
            $table->timestamps();

            $table->unique(['officer_id', 'locale'], 'officer_trans_officer_locale_unique');
        });

        // 9. Custom Content Sections (Hybrid Page Builder Blocks)
        Schema::create('institution_custom_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->string('type')->index(); // text, services_list, officers_list, contact_info, important_links, documents, notice_alert, faq, table
            $table->json('data')->nullable(); // structured config (links, faqs, tables, etc.)
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 10. Custom Section Translations
        Schema::create('institution_custom_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_custom_section_id')->constrained('institution_custom_sections', indexName: 'inst_sec_trans_sec_id_fk')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->unique(['institution_custom_section_id', 'locale'], 'inst_sec_trans_sec_locale_unique');
        });

        // 11. Institution Documents & Downloads
        Schema::create('institution_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('institution_units')->nullOnDelete();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->string('file_size')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        // 12. Institution Document Translations
        Schema::create('institution_document_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_document_id')->constrained('institution_documents', indexName: 'inst_doc_trans_doc_id_fk')->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['institution_document_id', 'locale'], 'inst_doc_trans_doc_locale_unique');
        });

        // 13. Public Reviews & Ratings (Req 5)
        Schema::create('institution_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('status')->default('pending')->index(); // pending, approved, rejected
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_reviews');
        Schema::dropIfExists('institution_document_translations');
        Schema::dropIfExists('institution_documents');
        Schema::dropIfExists('institution_custom_section_translations');
        Schema::dropIfExists('institution_custom_sections');
        Schema::dropIfExists('officer_translations');
        Schema::dropIfExists('officers');
        Schema::dropIfExists('institution_service_translations');
        Schema::dropIfExists('institution_services');
        Schema::dropIfExists('institution_unit_translations');
        Schema::dropIfExists('institution_units');
        Schema::dropIfExists('institution_translations');
        Schema::dropIfExists('institutions');
    }
};
