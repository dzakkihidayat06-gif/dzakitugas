<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Jalankan semua seeder sekaligus: php spark db:seed DatabaseSeeder */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
        $this->call('ProdukSeeder');
    }
}
