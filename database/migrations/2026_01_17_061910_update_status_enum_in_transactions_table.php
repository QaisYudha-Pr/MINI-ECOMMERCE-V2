<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Di PostgreSQL, enum Laravel diimplementasikan sebagai CHECK constraint.
        // Cara terbaik agar fleksibel adalah mengubahnya menjadi STRING biasa.
        
        if (config('database.default') === 'pgsql') {
            // 1. Hapus constraint lama
            DB::statement('ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_status_check');
            
            // 2. Ubah tipe data kolom menjadi VARCHAR/STRING
            DB::statement('ALTER TABLE transactions ALTER COLUMN status TYPE VARCHAR(255)');
            
            // 3. Set default value jika perlu
            DB::statement("ALTER TABLE transactions ALTER COLUMN status SET DEFAULT 'pending'");
        } else {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('status')->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Balikkan ke enum jika diperlukan (tidak disarankan di Postgres jika ingin fleksibel)
        if (config('database.default') === 'pgsql') {
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('pending', 'success', 'failed'))");
        } else {
            Schema::table('transactions', function (Blueprint $table) {
                $table->enum('status', ['pending', 'success', 'failed'])->change();
            });
        }
    }
};
