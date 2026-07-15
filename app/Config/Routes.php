<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------- Halaman depan ----------
$routes->get('/', 'Home::index');

// ---------- Login & Register (tanpa perlu login) ----------
$routes->get('/login', 'Auth::login');
$routes->post('/auth/proses_login', 'Auth::proses_login');
$routes->get('/register', 'Auth::showRegister');
$routes->post('/register', 'Auth::register');
$routes->get('/logout', 'Auth::logout');

// ---------- Wajib login (admin maupun user) ----------
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Melihat daftar menu: admin & user sama-sama boleh
$routes->get('/produk', 'Produk::index', ['filter' => 'auth']);

// Unduh daftar menu (CSV) — admin & user boleh
$routes->get('/produk/export', 'Produk::export', ['filter' => 'auth']);

// ---------- HANYA ADMIN: tambah, ubah, hapus menu ----------
$routes->get('/produk/tambah', 'Produk::tambah', ['filter' => 'admin']);
$routes->post('/produk/create', 'Produk::create', ['filter' => 'admin']);
$routes->get('/produk/edit/(:num)', 'Produk::edit/$1', ['filter' => 'admin']);
$routes->post('/produk/update/(:num)', 'Produk::update/$1', ['filter' => 'admin']);
$routes->get('/produk/hapus/(:num)', 'Produk::hapus/$1', ['filter' => 'admin']);
