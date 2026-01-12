<?php

namespace App\Http\Controllers;

use App\Models\ItemShop;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function search(Request $request)
{
$query = trim($request->query('query'));

$results = ItemShop::where('nama_barang', 'ILIKE', "{$query}%")
    ->orWhere('nama_barang', 'ILIKE', "% {$query}%")
    ->paginate(10);



    return view('test', compact('results'));
}   
}
