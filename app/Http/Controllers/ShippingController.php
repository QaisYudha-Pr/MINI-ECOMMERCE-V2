<?php

namespace App\Http\Controllers;

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
}
