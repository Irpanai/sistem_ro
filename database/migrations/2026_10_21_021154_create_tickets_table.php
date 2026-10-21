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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // e.g. TKT-2026-001
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('address');
            $table->string('area'); // Banjarmasin, Barito Kuala
            $table->string('odp_code'); // ODP-BJM-03
            $table->text('issue');
            $table->enum('status', ['Pending', 'In Progress', 'Solved'])->default('Pending');
            $table->enum('type', ['ISOLIR', 'OUTAGE'])->default('ISOLIR');
            $table->string('priority')->default('High'); // Low, Medium, High, Critical
            $table->text('analysis_details')->nullable(); // JSON or text summary
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
