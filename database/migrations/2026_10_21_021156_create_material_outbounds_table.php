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
        Schema::create('material_outbounds', function (Blueprint $table) {
            $table->id();
            $table->string('outbound_code')->unique(); // e.g. REP-001
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->nullOnDelete();
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->string('spk_number')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('technician_name');
            $table->string('material_name');
            $table->integer('quantity');
            $table->string('unit')->default('Pcs');
            $table->string('approved_by')->default('Admin NOC');
            $table->string('status')->default('In Field Handover'); // In Field Handover, Used & Completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_outbounds');
    }
};
