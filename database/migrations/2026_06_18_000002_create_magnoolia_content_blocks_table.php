<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magnoolia_content_blocks', function (Blueprint $table) {
            $table->id();
            // Dotted magnoolia translation key WITHOUT the leading "magnoolia." —
            // e.g. "page.kodudjahinnad.note". Public read falls back to the lang
            // file when no published override exists.
            $table->string('key', 190)->unique();
            $table->string('page', 60)->index();     // grouping in admin: home, kodud, asendiplaan, kontakt, ...
            $table->string('label', 190);            // human label
            $table->string('group', 60)->nullable(); // optional sub-section
            $table->text('et')->nullable();
            $table->text('ru')->nullable();
            $table->text('en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magnoolia_content_blocks');
    }
};
