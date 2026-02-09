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
        // Conversations between users
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_one')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_two')->constrained('users')->cascadeOnDelete();
            $table->foreignId('item_shop_id')->nullable()->constrained()->nullOnDelete(); // Related product (optional)
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_one', 'user_two']);
        });

        // Individual messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->string('attachment')->nullable(); // Image/file attachment
            $table->enum('type', ['text', 'image', 'product', 'order'])->default('text');
            $table->json('metadata')->nullable(); // For product/order references
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
