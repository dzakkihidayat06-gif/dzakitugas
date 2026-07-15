<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel PRODUK (menu kantin)
 * Sebelumnya data menu disimpan di session sehingga hilang saat logout.
 * Sekarang disimpan permanen di database.
 */
class CreateProduk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'harga' => [
                'type' => 'INTEGER',
            ],
            'stok' => [
                'type'    => 'INTEGER',
                'default' => 0,
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'Makanan',   // Makanan / Minuman / Snack
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('produk');
    }

    public function down()
    {
        $this->forge->dropTable('produk');
    }
}
