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
     * Calculate Local Courier Rates (SADEWA 1, 2, 3)
     */
    public function getLocalRates($lat, $lng, array $items)
    {
        if (!$lat || !$lng || empty($items)) {
            return [];
        }

        $settings = SiteSetting::whereIn('key', [
            'shipping_base_fee',
            'shipping_per_km',
            'shipping_per_kg',
            'shipping_handling_fee'
        ])->pluck('value', 'key');

        $baseFee = (float)($settings['shipping_base_fee'] ?? 5000);
        $perKm = (float)($settings['shipping_per_km'] ?? 2000);
        $perKg = (float)($settings['shipping_per_kg'] ?? 1000);
        $handlingFee = (float)($settings['shipping_handling_fee'] ?? 0);

        // AMBIL LOKASI PENJUAL (Dari Item Pertama)
        // Kita asumsikan pengiriman dihitung dari toko penjual
        $firstItem = $items[0];
        $seller = null;

        // Jika item membawa user_id (seller), ambil koordinat aslinya
        if (isset($firstItem['seller_id'])) {
            $seller = \App\Models\User::find($firstItem['seller_id']);
        }

        // Kalau seller punya koordinat, pakai itu. Kalau nggak, pakai default Mojokerto.
        $originLat = $seller && $seller->latitude ? (float)$seller->latitude : -7.4726; 
        $originLng = $seller && $seller->longitude ? (float)$seller->longitude : 112.4381;

        $distance = $this->calculateDistance($lat, $lng, $originLat, $originLng);
        
        $totalWeightKg = 0;
        foreach ($items as $item) {
            $rawWeight = (float)($item['weight'] ?? 1);
            // Standarisasi: Jika > 20 asumsikan Gram (misal 1000g), jika <= 20 asumsikan KG
            $weightInKg = $rawWeight > 20 ? $rawWeight / 1000 : $rawWeight;
            $totalWeightKg += $weightInKg * (int)($item['quantity'] ?? 1);
        }

        // Hitung Biaya Berdasarkan Jarak & Berat
        $distanceFee = $distance > 2 ? ceil($distance - 2) * $perKm : 0;
        $weightFee = ceil($totalWeightKg) * $perKg;
        
        // --- Cek Logika Gratis Ongkir ---
        $fsSettings = SiteSetting::whereIn('key', [
            'free_shipping_min_order',
            'free_shipping_max_dist',
            'free_shipping_max_subsidy'
        ])->pluck('value', 'key');

        $minOrder = (float)($fsSettings['free_shipping_min_order'] ?? 25000);
        $maxDist = (float)($fsSettings['free_shipping_max_dist'] ?? 20);
        $maxSubsidy = (float)($fsSettings['free_shipping_max_subsidy'] ?? 10000);

        $cartTotal = collect($items)->sum(fn($i) => ($i['harga'] ?? 0) * ($i['quantity'] ?? 1));
        
        $subsidyApplied = 0;
        if ($cartTotal >= $minOrder) {
            if ($distance <= 5) {
                // Gratis TOTAL (Subsidi sebesar harga ongkir aslinya)
                $subsidyApplied = 999999; // Set sangat tinggi agar finalPrice jadi 0
            } elseif ($distance <= $maxDist) {
                // Potongan sesuai setting (Default 10rb)
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

            // Cek jika ada batasan jarak
            if ($courier->max_distance && $distance > $courier->max_distance) {
                $isDisabled = true;
                $disableReason = 'Jarak melebihi batas maksimum';
            }

            // Cek jika ada batasan berat
            if ($courier->max_weight && $totalWeightKg > $courier->max_weight) {
                $isDisabled = true;
                $disableReason = 'Berat melebihi batas maksimum';
            }

            // Rumus: (Ongkir Dasar * Multiplier) + Extra Fee Kurir + Layanan Sistem
            $freight = $baseFee + $distanceFee + $weightFee;
            $rawPrice = ($freight * $courier->multiplier) + ($courier->base_extra_cost ?? 0) + $handlingFee;
            
            // Terapkan Subsidi Ongkir
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
                    'base_fee' => (int)$baseFee,
                    'distance_km' => round($distance, 1),
                    'distance_fee' => (int)$distanceFee,
                    'weight_kg' => round($totalWeightKg, 1),
                    'weight_fee' => (int)$weightFee,
                    'handling_fee' => (int)$handlingFee,
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
