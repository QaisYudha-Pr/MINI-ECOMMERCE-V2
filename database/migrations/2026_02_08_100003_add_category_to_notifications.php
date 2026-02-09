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
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('category', ['transaction', 'promo', 'follower', 'system', 'chat'])->default('system')->after('type');
            $table->string('link')->nullable()->after('category'); // Link to related page
            $table->string('icon')->nullable()->after('link'); // Icon class
            $table->json('data')->nullable()->after('icon'); // Additional data (item_id, transaction_id, etc)
            $table->boolean('email_sent')->default(false)->after('is_read'); // Track if email was sent
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['category', 'link', 'icon', 'data', 'email_sent']);
        });
    }
};
