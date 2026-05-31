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
        Schema::create('translation_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 5);           // et, ru, en
            $table->string('file', 50)->default('magnoolia'); // lang file name
            $table->json('data');                  // full nested array of the file
            $table->string('label')->nullable();   // e.g. "Before save 14:32"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_snapshots');
    }
};
