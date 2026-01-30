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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('admin_fee', 12, 2)->default(0)->after('shipping_fee');
            $table->foreignId('seller_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
            $table->string('parent_invoice')->nullable()->after('invoice_number')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn(['admin_fee', 'seller_id', 'parent_invoice']);
        });
    }
};
