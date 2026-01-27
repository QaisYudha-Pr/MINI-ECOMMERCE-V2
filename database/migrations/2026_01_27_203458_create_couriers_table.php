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
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('YUDHARMA');
            $table->string('service_name'); // e.g., REGULAR, EXPRESS, KILAT
            $table->string('description')->nullable();
            $table->integer('base_extra_cost')->default(0); // Tambahan biaya flat (misal +5rb)
            $table->float('multiplier')->default(1); // Pengali biaya (misal x1.5)
            $table->integer('max_distance')->nullable(); // Maksimal jarak dalam KM
            $table->string('estimated_time')->nullable(); // e.g., 1-2 Jam
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
