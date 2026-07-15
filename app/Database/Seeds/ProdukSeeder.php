<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Contoh menu kantin beserta fotonya */
class ProdukSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // [nama, harga, stok, kategori, foto]
        $menu = [
            ['Nasi Goreng Spesial', 15000, 25, 'Makanan', 'nasi-goreng.svg'],
            ['Mie Ayam Bakso',      13000, 30, 'Makanan', 'mie-ayam.svg'],
            ['Ayam Geprek',         16000, 20, 'Makanan', 'ayam-geprek.svg'],
            ['Soto Ayam',           12000, 18, 'Makanan', 'soto-ayam.svg'],
            ['Es Teh Manis',         3000, 60, 'Minuman', 'es-teh.svg'],
            ['Es Jeruk',             5000, 45, 'Minuman', 'es-jeruk.svg'],
            ['Kopi Susu',            8000, 35, 'Minuman', 'kopi-susu.svg'],
            ['Roti Bakar',          10000, 15, 'Snack',   'roti-bakar.svg'],
            ['Pisang Goreng',        8000, 22, 'Snack',   'pisang-goreng.svg'],
            ['Keripik Singkong',     5000, 40, 'Snack',   'keripik.svg'],
        ];

        $data = [];
        foreach ($menu as [$nama, $harga, $stok, $kategori, $foto]) {
            $data[] = [
                'nama'       => $nama,
                'harga'      => $harga,
                'stok'       => $stok,
                'kategori'   => $kategori,
                'foto'       => $foto,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->db->table('produk')->insertBatch($data);
    }
}
