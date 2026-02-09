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
            $table->decimal('seller_rating', 2, 1)->default(0)->after('seller_status');
            $table->integer('seller_rating_count')->default(0)->after('seller_rating');
            $table->boolean('is_top_seller')->default(false)->after('seller_rating_count');
        });

        // Separate seller reviews table (different from product reviews)
        Schema::create('seller_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->unique(['transaction_id', 'buyer_id']); // One review per transaction
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_reviews');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['seller_rating', 'seller_rating_count', 'is_top_seller']);
        });
    }
};
