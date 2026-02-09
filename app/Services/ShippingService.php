<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $biteship;

    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    /**
     * Get all available shipping rates (Local + Biteship)
     */
    public function getAllRates($lat, $lng, $destinationAreaId, array $items)
    {
        $rates = [];

        // 1. Get Local Courier Rates
        $localRates = $this->getLocalRates($lat, $lng, $items);
        $rates = array_merge($rates, $localRates);

        // 2. Get Biteship Rates if area ID is provided
        if ($destinationAreaId) {
            $biteshipRates = $this->biteship->getRates(
                config('services.biteship.origin_area_id'),
                $destinationAreaId,
                $items
            );

            // Map Biteship rates to a common format if needed
            foreach ($biteshipRates as $rate) {
                $rates[] = [
                    'courier_service_id' => null, // Not a local agency
                    'courier_name' => $rate['courier_name'],
                    'service' => $rate['service'],
                    'price' => $rate['price'],
                    'duration' => $rate['duration'],
                    'type' => 'national'
                ];
            }
        }

        return $rates;
    }

    /**
     * Calculate Local Courier Rates (Sum of Multi-Origin Shipping)
     */
    public function getLocalRates($lat, $lng, array $items)
    {
        if (!$lat || !$lng || empty($items)) {
            return [];
        }

        $settings = SiteSetting::whereIn('key', [
            'shipping_base_fee',
            'shipping_per_km',
            'shipping_per_kg'
        ])->pluck('value', 'key');

        $baseFee = (float)($settings['shipping_base_fee'] ?? 5000);
        $perKm = (float)($settings['shipping_per_km'] ?? 2000);
        $perKg = (float)($settings['shipping_per_kg'] ?? 1000);

        // --- MULTI-ORIGIN LOGIC: Group items by seller ---
        $itemsBySeller = [];
        foreach ($items as $item) {
            // Check both seller_id and user_id for compatibility
            $sellerId = $item['seller_id'] ?? $item['user_id'] ?? 0;
            $itemsBySeller[$sellerId][] = $item;
        }

        // Calculate total distance and total weight for ALL sellers
        $totalDistanceSum = 0;
        $totalWeightKgSum = 0;
        
        foreach ($itemsBySeller as $sellerId => $sellerItems) {
            $seller = $sellerId ? \App\Models\User::find($sellerId) : null;
            $originLat = $seller && $seller->latitude ? (float)$seller->latitude : -7.4726; 
            $originLng = $seller && $seller->longitude ? (float)$seller->longitude : 112.4385;

            $dist = $this->calculateDistance($lat, $lng, $originLat, $originLng);
            $totalDistanceSum += $dist;

            foreach ($sellerItems as $item) {
                $rawWeight = (float)($item['weight'] ?? 1);
                $weightInKg = $rawWeight > 20 ? $rawWeight / 1000 : $rawWeight;
                $totalWeightKgSum += $weightInKg * (int)($item['quantity'] ?? 1);
            }
        }

        // Hitung Biaya Berdasarkan Jarak & Berat (Kumulatif)
        // Jarak dihitung per seller: Jika ada 2 seller, base fee mungkin bisa dihitung sekali atau per seller?
        // User minta "menjumlahkan ongkirnya", biasanya base fee dihitung per seller karena kurir harus jalan ke tiap titik.
        $sellerCount = count($itemsBySeller);
        
        $totalDistanceFee = 0;
        foreach ($itemsBySeller as $sellerId => $sellerItems) {
            $seller = $sellerId ? \App\Models\User::find($sellerId) : null;
            $originLat = $seller && $seller->latitude ? (float)$seller->latitude : -7.4726; 
            $originLng = $seller && $seller->longitude ? (float)$seller->longitude : 112.4385;
            
            $dist = $this->calculateDistance($lat, $lng, $originLat, $originLng);
            // Free 2km pertama per seller (atau per trip?) - mari buat per seller biar adil buat kurir
            $totalDistanceFee += $dist > 2 ? ceil($dist - 2) * $perKm : 0;
        }

        $totalWeightFee = ceil($totalWeightKgSum) * $perKg;
        $totalBaseFee = $baseFee * $sellerCount; // Berapa kali kurir harus jemput
        
        // --- Cek Logika Gratis Ongkir ---
        $fsSettings = SiteSetting::whereIn('key', [
            'free_shipping_min_order',
            'free_shipping_max_dist',
            'free_shipping_limit_dist',
            'free_shipping_subsidy'
        ])->pluck('value', 'key');

        $minOrder = (float)($fsSettings['free_shipping_min_order'] ?? 25000);
        $maxDist = (float)($fsSettings['free_shipping_max_dist'] ?? 20);
        $maxSubsidy = (float)($fsSettings['free_shipping_subsidy'] ?? 10000);
        $freeDist = (float)($fsSettings['free_shipping_limit_dist'] ?? 5);

        $cartTotal = collect($items)->sum(fn($i) => ($i['price'] ?? $i['harga'] ?? 0) * ($i['quantity'] ?? 1));
        
        $subsidyApplied = 0;
        // Jarak rata-rata atau jarak terjauh untuk subsidi? Menggunakan jarak terjauh biasanya lebih aman.
        $maxDistToSeller = 0;
        foreach ($itemsBySeller as $sellerId => $sellerItems) {
            $seller = $sellerId ? \App\Models\User::find($sellerId) : null;
            $originLat = $seller && $seller->latitude ? (float)$seller->latitude : -7.4726; 
            $originLng = $seller && $seller->longitude ? (float)$seller->longitude : 112.4381;
            $dist = $this->calculateDistance($lat, $lng, $originLat, $originLng);
            if($dist > $maxDistToSeller) $maxDistToSeller = $dist;
        }

        if ($cartTotal >= $minOrder) {
            if ($maxDistToSeller <= $freeDist) {
                $subsidyApplied = 999999;
            } elseif ($maxDistToSeller <= $maxDist) {
                $subsidyApplied = $maxSubsidy;
            }
        }
        // ---------------------------------

        // AMBIL KURIR DARI DATABASE
        $activeCouriers = \App\Models\Courier::where('is_active', true)->get();
        $rates = [];

        foreach ($activeCouriers as $courier) {
            $isDisabled = false;
            $disableReason = '';

            // 1. Cek Batasan Berat
            if ($courier->max_weight && $totalWeightKgSum > $courier->max_weight) {
                $isDisabled = true;
                $disableReason = "Berat paket " . round($totalWeightKgSum, 1) . "kg melebihi batas {$courier->max_weight}kg.";
            }

            // 2. Cek Batasan Jarak (Pakai Jarak Terjauh)
            if ($courier->max_distance && $maxDistToSeller > $courier->max_distance) {
                $isDisabled = true;
                $disableReason = "Salah satu seller berjarak " . round($maxDistToSeller, 1) . "km, melebihi batas {$courier->max_distance}km.";
            }

            // Rumus multi-origin
            $freight = $totalBaseFee + $totalDistanceFee + $totalWeightFee;
            $rawPrice = ($freight * $courier->multiplier) + ($courier->base_extra_cost ?? 0);
            
            $finalPrice = max(0, $rawPrice - $subsidyApplied);

            $rates[] = [
                'courier_service_id' => $courier->id,
                'courier_name' => $courier->name,
                'service' => $courier->service_name,
                'price' => (int)$finalPrice,
                'duration' => $courier->estimated_time ?? 'N/A',
                'type' => 'local',
                'description' => $courier->description,
                'disabled' => $isDisabled,
                'reason' => $disableReason,
                'breakdown' => [
                    'base_fee' => (int)$totalBaseFee,
                    'seller_count' => $sellerCount,
                    'distance_km' => round($totalDistanceSum, 1),
                    'distance_fee' => (int)$totalDistanceFee,
                    'weight_kg' => round($totalWeightKgSum, 1),
                    'weight_fee' => (int)$totalWeightFee,
                    'service_extra' => (int)($courier->base_extra_cost ?? 0),
                    'multiplier' => $courier->multiplier,
                    'raw_price' => (int)$rawPrice,
                    'subsidy' => (int)$subsidyApplied,
                    'total' => (int)$finalPrice
                ]
            ];
        }

        return $rates;
    }

    /**
     * Haversine formula to calculate distance between two points in KM
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
