<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menambahkan kolom FOTO pada tabel produk.
 *
 * Dibuat sebagai migration BARU (bukan mengubah migration lama),
 * supaya riwayat perubahan database tetap tercatat rapi.
 */
class AddFotoToProduk extends Migration
{
    public function up()
    {
        $this->forge->addColumn('produk', [
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'default.svg',
                'after'      => 'kategori',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('produk', 'foto');
    }
}
