<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 36, Module B — binds a named position on the site to a media item.
 *
 * Only the binding lives here; the slot's label, page and fallback file come from
 * config/magnoolia_slots.php. A slot with no row simply falls back to the file the
 * template shipped with, so the site is unchanged until something is assigned.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magnoolia_media_slots', function (Blueprint $table) {
            $table->id();
            $table->string('slot_key', 120)->unique();
            $table->foreignId('media_item_id')->nullable()
                  ->constrained('magnoolia_media_items')->nullOnDelete();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magnoolia_media_slots');
    }
};
