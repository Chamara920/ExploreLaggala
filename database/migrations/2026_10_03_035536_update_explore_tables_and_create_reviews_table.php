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
        Schema::table('explore_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('explore_categories', 'name')) {
                $table->string('name')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('explore_categories', 'type')) {
                $table->string('type')->default('culture-heritage')->after('name');
                $table->index('type');
            }
        });

        Schema::table('explore_items', function (Blueprint $table) {
            if (! Schema::hasColumn('explore_items', 'type')) {
                $table->string('type')->default('culture-heritage')->after('id');
                $table->index('type');
            }
            $table->foreignId('category_id')->nullable()->change();
        });

        if (! Schema::hasTable('explore_item_reviews')) {
            Schema::create('explore_item_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('explore_item_id')
                    ->constrained('explore_items')
                    ->cascadeOnDelete();
                $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->string('status')->default('pending');
                $table->text('admin_note')->nullable();
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->unique(
                    ['explore_item_id', 'user_id'],
                    'explore_item_user_review_unique'
                );
                $table->index('status');
                $table->index('rating');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('explore_item_reviews');

        Schema::table('explore_items', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });

        Schema::table('explore_categories', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn(['name', 'type']);
        });
    }
};
