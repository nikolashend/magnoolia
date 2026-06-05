<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magnoolia_leads', function (Blueprint $table) {
            $table->string('unit_key', 64)->nullable()->after('selected_unit');
            $table->string('unit_address', 120)->nullable()->after('unit_key');
            $table->unsignedInteger('published_version')->nullable()->after('unit_address');
            $table->string('status_at_submission', 32)->nullable()->after('published_version');
            $table->boolean('price_public_at_submission')->nullable()->after('status_at_submission');
            $table->string('source_page', 255)->nullable()->after('price_public_at_submission');
            $table->string('source_component', 120)->nullable()->after('source_page');
            $table->string('utm_content', 100)->nullable()->after('utm_campaign');

            $table->index('unit_key');
            $table->index('published_version');
        });
    }

    public function down(): void
    {
        Schema::table('magnoolia_leads', function (Blueprint $table) {
            $table->dropIndex(['unit_key']);
            $table->dropIndex(['published_version']);
            $table->dropColumn([
                'unit_key',
                'unit_address',
                'published_version',
                'status_at_submission',
                'price_public_at_submission',
                'source_page',
                'source_component',
                'utm_content',
            ]);
        });
    }
};
