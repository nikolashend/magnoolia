<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magnoolia_units', function (Blueprint $table) {
            $table->string('plan_type', 20)->nullable()->after('asendiplaan_key');
            $table->boolean('public_page_visible')->default(true)->after('is_visible');
        });
    }

    public function down(): void
    {
        Schema::table('magnoolia_units', function (Blueprint $table) {
            $table->dropColumn(['plan_type', 'public_page_visible']);
        });
    }
};
