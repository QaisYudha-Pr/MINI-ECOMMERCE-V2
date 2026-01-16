<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemShop;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = ItemShop::withCount('reviews')
            ->withAvg('reviews as ratings_avg', 'rating')
            ->latest()
            ->get();
        // Hanya ambil item yang stoknya lebih dari 0
$items = ItemShop::where('stok', '>', 0)->get();
        return view('home', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
