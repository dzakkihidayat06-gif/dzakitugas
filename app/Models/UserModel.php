<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nama', 'username', 'password', 'role'];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    /** Cari akun berdasarkan username (dipakai saat login) */
    public function cariUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }
}
