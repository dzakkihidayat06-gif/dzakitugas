<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $produk = new ProdukModel();
        $user   = new UserModel();

        $semua = $produk->findAll();

        return view('dashboard', [
            'jmlProduk'   => count($semua),
            'jmlUser'     => $user->countAll(),
            'totalStok'   => array_sum(array_column($semua, 'stok')),
            'nilaiStok'   => $produk->totalNilaiStok(),

            // Data untuk grafik
            'perKategori' => $produk->hitungKategori(),
            'stokKategori'=> $produk->stokPerKategori(),

            // Daftar ringkas
            'stokMenipis' => $produk->where('stok <', 20)->orderBy('stok', 'ASC')->findAll(5),
            'termahal'    => $produk->orderBy('harga', 'DESC')->findAll(5),
        ]);
    }
}
