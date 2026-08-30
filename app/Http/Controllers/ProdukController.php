<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        // Data berupa id dan produk sesuai instruksi [cite: 14]
        $data = [
            ['id' => 1, 'produk' => 'Laptop'],
            ['id' => 2, 'produk' => 'Mouse'],
            ['id' => 3, 'produk' => 'Keyboard'],
        ];

        // Merujuk ke folder pages.list_product
        return view('pages.list_product', compact('data'));
    }
}
