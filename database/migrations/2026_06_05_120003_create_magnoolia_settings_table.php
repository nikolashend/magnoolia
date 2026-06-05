<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magnoolia_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('campaign_active')->default(false);
            $table->bigInteger('campaign_discount_cents')->nullable();
            $table->date('campaign_deadline')->nullable();
            $table->text('campaign_note_et')->nullable();
            $table->text('campaign_note_ru')->nullable();
            $table->text('campaign_note_en')->nullable();
            $table->string('campaign_legal_note', 500)->nullable();
            $table->string('stage_1_completion', 64)->default('kevad 2027');
            $table->string('stage_2_completion', 64)->default('kevad 2028');
            $table->boolean('default_stage_1_price_public')->default(true);
            $table->boolean('default_stage_2_price_public')->default(false);
            $table->string('sales_contact_name', 120)->default('Diana Tali');
            $table->string('sales_contact_phone', 32)->default('+37258164078');
            $table->string('sales_contact_email', 190)->default('diana@estlanda.ee');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magnoolia_settings');
    }
};
