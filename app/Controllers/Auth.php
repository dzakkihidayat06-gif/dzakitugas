<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * LOGIN & REGISTER
 * Sebelumnya akun disimpan di session (hilang saat logout).
 * Sekarang akun tersimpan permanen di tabel "users".
 *
 * Ada dua peran:
 *  - admin : boleh tambah / edit / hapus menu
 *  - user  : hanya boleh melihat daftar menu
 */
class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        // Kalau sudah login, langsung ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function showRegister()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    /** Daftar akun baru — selalu berperan sebagai "user" */
    public function register()
    {
        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user',   // pendaftar baru selalu user biasa
        ]);

        return redirect()->to('/login')
            ->with('success', 'Pendaftaran berhasil. Silakan login.');
    }

    /** Proses login: cocokkan username & password dengan data di database */
    public function proses_login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->cariUsername($username);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Username atau password salah.');
        }

        session()->set([
            'id'        => $user['id'],
            'nama'      => $user['nama'],
            'username'  => $user['username'],
            'role'      => $user['role'],
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard')
            ->with('success', 'Selamat datang, ' . $user['nama'] . '!');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah keluar.');
    }
}
