<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('arrendamiento_vivienda');
            $table->string('landlord_name');
            $table->string('landlord_dui');
            $table->string('landlord_address');
            $table->string('tenant_name');
            $table->string('tenant_dui');
            $table->string('tenant_address');
            $table->string('property_address');
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->integer('duration_months')->default(12);
            $table->date('start_date');
            $table->json('custom_clauses')->nullable();
            $table->string('status')->default('draft');
            $table->string('pdf_path')->nullable();
            $table->string('pdf_hash')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
