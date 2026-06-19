<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magnoolia_leads', function (Blueprint $table) {
            // Sales handling status (separate from mail_status which tracks delivery).
            $table->string('lead_status', 16)->default('new')->index()->after('mail_status');
        });
    }

    public function down(): void
    {
        Schema::table('magnoolia_leads', function (Blueprint $table) {
            $table->dropColumn('lead_status');
        });
    }
};
