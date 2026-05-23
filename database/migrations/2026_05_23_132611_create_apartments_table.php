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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_currency', 10)->default('EUR');
            $table->enum('status', ['for_rent', 'for_sale', 'sold', 'rented'])->default('for_rent');
            $table->unsignedSmallInteger('rooms')->default(1);
            $table->unsignedSmallInteger('bathrooms')->default(1);
            $table->decimal('area', 8, 2)->nullable();
            $table->boolean('is_published')->default(false);
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
