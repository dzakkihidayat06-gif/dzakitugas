<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table         = 'produk';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nama', 'harga', 'stok', 'kategori', 'foto'];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    /** Pencarian menu berdasarkan nama */
    public function cari(?string $keyword)
    {
        if ($keyword) {
            return $this->like('nama', $keyword);
        }
        return $this;
    }

    /** Total nilai seluruh stok (harga x stok) */
    public function totalNilaiStok(): int
    {
        $total = 0;
        foreach ($this->findAll() as $p) {
            $total += (int) $p['harga'] * (int) $p['stok'];
        }
        return $total;
    }

    /** Jumlah menu per kategori — dipakai untuk tombol saring & grafik */
    public function hitungKategori(): array
    {
        $hasil = ['Makanan' => 0, 'Minuman' => 0, 'Snack' => 0];

        foreach ($this->select('kategori, COUNT(*) AS jml')->groupBy('kategori')->findAll() as $r) {
            $hasil[$r['kategori']] = (int) $r['jml'];
        }

        return $hasil;
    }

    /** Total stok per kategori — untuk grafik batang di dashboard */
    public function stokPerKategori(): array
    {
        $hasil = ['Makanan' => 0, 'Minuman' => 0, 'Snack' => 0];

        foreach ($this->select('kategori, SUM(stok) AS total')->groupBy('kategori')->findAll() as $r) {
            $hasil[$r['kategori']] = (int) $r['total'];
        }

        return $hasil;
    }
}
