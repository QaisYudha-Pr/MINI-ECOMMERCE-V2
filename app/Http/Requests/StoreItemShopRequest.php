<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller policy
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|min:3|max:255',
            'harga' => 'required|numeric|min:0|max:999999999',
            'deskripsi' => 'required|string|min:10|max:5000',
            'gambar' => 'nullable|string',
            'stok' => 'required|numeric|min:0|max:999999',
            'berat' => 'required|numeric|min:1|max:100000',
            'kategori' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'stok.required' => 'Stok awal harus diisi',
            'kategori.required' => 'Kategori harus dipilih bolo!',
        ];
    }
}
