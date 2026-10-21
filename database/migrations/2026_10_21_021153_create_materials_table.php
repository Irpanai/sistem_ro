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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();    // SKU code e.g. DC-1C-1000
            $table->string('name');              // e.g. Kabel Dropcore 1 Core
            $table->string('category');          // Kabel & Fiber, CPE Hardware, etc.
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->string('unit')->default('Pcs'); // Roll, Unit, Pcs
            $table->string('price')->nullable();
            $table->string('location')->nullable(); // Rak gudang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
