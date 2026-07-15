<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Akun bawaan: 1 admin dan 1 user biasa */
class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insertBatch([
            [
                'nama'       => 'Administrator',
                'username'   => 'admin',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Dzaki',
                'username'   => 'dzaki',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
