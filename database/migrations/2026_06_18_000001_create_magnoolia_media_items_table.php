<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magnoolia_media_items', function (Blueprint $table) {
            $table->id();
            $table->string('title', 190);
            $table->string('category', 40)->index();        // hero, exterior, interior, asendiplaan, floorplan, gallery, location, materials, campaign, logo, other
            $table->string('original_name', 255)->nullable();
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('original_path', 255)->nullable();  // private storage path
            $table->string('public_path', 255)->nullable();    // web-accessible optimized asset (assets/magnoolia/media/...)
            $table->string('thumb_path', 255)->nullable();     // web-accessible thumbnail
            $table->string('alt_et', 255)->nullable();
            $table->string('alt_ru', 255)->nullable();
            $table->string('alt_en', 255)->nullable();
            // Where this image is intended/assigned to be used (e.g. "unit:tee-3-1:floor1", "page:home:hero", "gallery").
            $table->string('assignment_target', 190)->nullable()->index();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magnoolia_media_items');
    }
};
