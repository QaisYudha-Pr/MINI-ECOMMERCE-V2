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
        Schema::table('item_shops', function (Blueprint $table) {
            $table->integer('total_terjual')->default(0)->after('stok');
            $table->string('lokasi')->nullable()->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_shops', function (Blueprint $table) {
            $table->dropColumn(['total_terjual', 'lokasi']);
        });
    }
};
