<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel USERS
 * Menyimpan akun login. Ada dua peran (role):
 *  - admin : boleh menambah, mengubah, dan menghapus menu
 *  - user  : hanya boleh melihat daftar menu
 */
class CreateUsers extends Migration
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
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,   // password disimpan dalam bentuk hash
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'user', // 'admin' atau 'user'
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');   // username tidak boleh kembar
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
