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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['admin', 'teknisi', 'klien'])->default('klien')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('area')->nullable()->after('phone');
            $table->string('address')->nullable()->after('area');
            $table->decimal('rating', 3, 1)->default(5.0)->after('address');
            $table->string('technician_status')->default('Available')->after('rating'); // Available, On Field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'phone', 'area', 'address', 'rating', 'technician_status']);
        });
    }
};
