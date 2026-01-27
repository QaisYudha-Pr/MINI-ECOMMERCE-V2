<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.biteship.key');
        $this->baseUrl = config('services.biteship.url');
    }

    /**
     * Search area for destination or origin ID
     */
    public function searchArea($query)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey
            ])->get("{$this->baseUrl}/v1/maps/areas", [
                'countries' => 'ID',
                'input' => $query,
                'type' => 'single'
            ]);

            if ($response->successful()) {
                return $response->json('areas');
            }

            Log::error('Biteship searchArea Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Biteship searchArea Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get shipping rates
     */
    public function getRates($originAreaId, $destinationAreaId, array $items)
    {
        try {
            // Prepare items and calculate total weight
            $payloadItems = [];
            foreach ($items as $item) {
                // Biteship expects weight in GRAMS. 
                // If our DB stores in KG (e.g. 1.5), multiply by 1000.
                $weightInGrams = (float)($item['weight'] ?? 1) * 1000;

                $payloadItems[] = [
                    'name' => $item['name'],
                    'description' => $item['name'],
                    'value' => (int)$item['price'],
                    'weight' => (int)$weightInGrams,
                    'quantity' => (int)$item['quantity'],
                ];
            }

            // Note: In a real multiauthentication/multivendor system, 
            // the origin_area_id would typically come from the seller's location.
            // For now we use the global config or provided id.

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/v1/rates/couriers", [
                'origin_area_id' => $originAreaId,
                'destination_area_id' => $destinationAreaId,
                'couriers' => 'jne,sicepat,jnt,pos', // Hardcoded popular couriers
                'items' => $payloadItems
            ]);

            if ($response->successful()) {
                return $response->json('pricing');
            }

            Log::error('Biteship getRates Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Biteship getRates Exception: ' . $e->getMessage());
            return [];
        }
    }
}
