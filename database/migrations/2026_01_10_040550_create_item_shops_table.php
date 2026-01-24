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
        Schema::create('item_shops', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga');
            $table->integer('stok')->default(0);
            $table->integer('berat')->default(1000)->comment('berat dalam gram');
            $table->integer('total_terjual')->default(0);
            $table->string('kategori')->nullable();
            $table->string('lokasi')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_shops');
    }
};
