<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\ItemShop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $items = ItemShop::all();

        if ($users->isEmpty() || $items->isEmpty()) {
            return;
        }

        // Create 20 random transactions over the last 7 days
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $transactionItems = $items->random(rand(1, 3));
            
            $itemsDetails = [];
            $totalPrice = 0;

            foreach ($transactionItems as $item) {
                $itemsDetails[] = [
                    'id' => $item->id,
                    'name' => $item->nama_barang,
                    'price' => $item->harga,
                    'kategori' => $item->kategori,
                    'qty' => 1
                ];
                $totalPrice += $item->harga;
            }

            Transaction::create([
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'total_price' => $totalPrice,
                'status' => 'success',
                'payment_method' => 'midtrans',
                'alamat' => 'Jl. Dummy No. ' . rand(1, 100),
                'items_details' => $itemsDetails,
                'created_at' => Carbon::now()->subDays(rand(0, 6))->subHours(rand(0, 23)),
            ]);
        }
    }
}
