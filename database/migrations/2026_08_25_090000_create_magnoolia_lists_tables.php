<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 36, Module C — editable repeating blocks ("lists").
 *
 * Two tables: the list itself (which named block on the site) and its entries.
 *
 * Entry fields are stored as JSON rather than columns, because six list types
 * carry six different field sets and columns would mean either six tables or a
 * wide sparse one. The translatable fields live in payload_et/ru/en; anything
 * shared across languages (icon name, URL, badge) lives in `meta`. Pictures are
 * never stored as a path here — they point at a media item, so a re-optimised or
 * renamed file stays correct everywhere it is used.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magnoolia_lists', function (Blueprint $table) {
            $table->id();
            $table->string('list_key', 120)->unique();
            $table->string('type', 40);
            $table->string('page', 60)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('magnoolia_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->constrained('magnoolia_lists')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('media_item_id')->nullable()
                  ->constrained('magnoolia_media_items')->nullOnDelete();
            $table->json('payload_et')->nullable();
            $table->json('payload_ru')->nullable();
            $table->json('payload_en')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['list_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magnoolia_list_items');
        Schema::dropIfExists('magnoolia_lists');
    }
};
