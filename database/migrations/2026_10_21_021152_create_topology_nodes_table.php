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
        Schema::create('topology_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('node_id')->unique(); // e.g. NODE-01
            $table->string('name')->unique();    // e.g. ODP-BJM-01
            $table->enum('type', ['OLT', 'ODP'])->default('ODP');
            $table->string('area');              // Banjarmasin, Barito Kuala
            $table->string('location');          // Address
            $table->string('coordinates')->nullable(); // Lat, Long
            $table->integer('total_ports')->default(16);
            $table->integer('used_ports')->default(0);
            $table->enum('status', ['Online', 'Offline'])->default('Online');
            $table->integer('connected_users')->default(0);
            $table->integer('connected_odp')->nullable();
            $table->string('issue')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topology_nodes');
    }
};
