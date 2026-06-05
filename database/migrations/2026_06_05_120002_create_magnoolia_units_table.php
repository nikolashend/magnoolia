<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magnoolia_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_key', 64)->unique();
            $table->string('slug', 128)->unique();
            $table->string('address', 120)->unique();
            $table->unsignedTinyInteger('building_number');
            $table->unsignedTinyInteger('section_number');
            $table->unsignedTinyInteger('stage');
            $table->string('status', 32);
            $table->boolean('is_visible')->default(true);
            $table->bigInteger('price_cents')->nullable();
            $table->boolean('price_public')->default(false);
            $table->unsignedTinyInteger('rooms');
            $table->decimal('net_area', 8, 1);
            $table->decimal('terrace_area', 8, 1)->nullable();
            $table->decimal('balcony_area', 8, 1)->nullable();
            $table->decimal('storage_area', 8, 1)->nullable();
            $table->decimal('private_yard_area', 10, 1)->nullable();
            $table->unsignedTinyInteger('parking_spaces')->default(2);
            $table->string('completion_key', 32);
            $table->string('floorplan_floor_1', 255);
            $table->string('floorplan_floor_2', 255);
            $table->string('asendiplaan_key', 128)->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('internal_note')->nullable();
            $table->unsignedInteger('lock_version')->default(1);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['stage', 'status']);
            $table->index(['stage', 'price_public']);
            $table->index('is_visible');
            $table->index('building_number');
            $table->index('updated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magnoolia_units');
    }
};
