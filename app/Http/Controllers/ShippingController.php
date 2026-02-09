<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BiteshipService;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $biteship;
    protected $shippingService;

    public function __construct(BiteshipService $biteship, ShippingService $shippingService)
    {
        $this->biteship = $biteship;
        $this->shippingService = $shippingService;
    }

    /**
     * AJAX search for areas
     */
    public function searchArea(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $areas = $this->biteship->searchArea($query);
        
        return response()->json($areas);
    }

    /**
     * AJAX call to get rates
     */
    public function getRates(Request $request)
    {
        $request->validate([
            'destination_area_id' => 'nullable',
            'items' => 'required|array',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric'
        ]);

        $rates = $this->shippingService->getAllRates(
            $request->lat,
            $request->lng,
            $request->destination_area_id,
            $request->items
        );

        return response()->json($rates);
    }

    /**
     * Return latest seller coordinates for checkout reconciliation.
     */
    public function getSellerCoordinates(Request $request)
    {
        $sellerIds = collect($request->input('seller_ids', []))
            ->filter(fn($id) => is_numeric($id))
            ->unique()
            ->take(30)
            ->all();

        if (empty($sellerIds)) {
            return response()->json([]);
        }

        $sellers = User::whereIn('id', $sellerIds)
            ->get(['id', 'name', 'nama_toko', 'alamat', 'latitude', 'longitude'])
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->nama_toko ?? $user->name ?? 'Official Store',
                'alamat' => $user->alamat,
                'lat' => $user->latitude,
                'lng' => $user->longitude,
            ]);

        return response()->json($sellers);
    }
}
