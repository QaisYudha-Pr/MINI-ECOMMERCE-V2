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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('total_price', 12, 2);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('snap_token')->nullable();

            // Tambahan Kolom Baru
            $table->text('alamat');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('payment_method')->default('midtrans');

            $table->json('items_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
