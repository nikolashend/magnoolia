<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The campaign has two texts on the site, not one.
 *
 * The gold ribbon on the home page shows a one-line version ("20 000 € soodustus —
 * pakkumine kehtib augusti 2026 lõpuni") while the red banner above the price table
 * shows the full sentence. Before Phase 35.1 those came from two separate config
 * keys (`body_short_*` and `pricing.special_offer`) plus a legal note hardcoded per
 * language in the template.
 *
 * Phase 35.1 routed both through the admin Campaign screen, which only had ONE text
 * field — so the ribbon started printing the long sentence in a strip sized for a
 * short one, and the legal note lost its Russian and English wording.
 *
 * These columns give the admin screen the fields the site actually needs.
 * `campaign_legal_note` stays as the Estonian note (it is already in use); the two
 * new ones carry its translations.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magnoolia_settings', function (Blueprint $table) {
            $table->string('campaign_note_short_et', 255)->nullable()->after('campaign_note_en');
            $table->string('campaign_note_short_ru', 255)->nullable()->after('campaign_note_short_et');
            $table->string('campaign_note_short_en', 255)->nullable()->after('campaign_note_short_ru');
            $table->string('campaign_legal_note_ru', 500)->nullable()->after('campaign_legal_note');
            $table->string('campaign_legal_note_en', 500)->nullable()->after('campaign_legal_note_ru');
        });
    }

    public function down(): void
    {
        Schema::table('magnoolia_settings', function (Blueprint $table) {
            $table->dropColumn([
                'campaign_note_short_et', 'campaign_note_short_ru', 'campaign_note_short_en',
                'campaign_legal_note_ru', 'campaign_legal_note_en',
            ]);
        });
    }
};
