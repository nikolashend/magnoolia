<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magnoolia_settings', function (Blueprint $table) {
            // 'text' = text-only campaign, 'fixed' = a € discount amount, 'none' = no discount.
            $table->string('campaign_discount_type', 16)->default('text')->after('campaign_discount_cents');
            $table->string('campaign_cta_label', 120)->nullable()->after('campaign_legal_note');
            $table->string('campaign_cta_target', 255)->nullable()->after('campaign_cta_label');
        });
    }

    public function down(): void
    {
        Schema::table('magnoolia_settings', function (Blueprint $table) {
            $table->dropColumn(['campaign_discount_type', 'campaign_cta_label', 'campaign_cta_target']);
        });
    }
};
