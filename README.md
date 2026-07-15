# 🍽️ Kantin Digital — CodeIgniter 4

Aplikasi pengelolaan menu kantin dengan **login 2 peran (admin & user)**,
**Migration**, **Seeder**, dan **Model**. Database memakai **SQLite**, jadi
tidak perlu menyalakan MySQL/phpMyAdmin — langsung jalan.

---

## 🔑 Akun untuk Login

| Peran     | Username | Password   | Hak Akses                          |
|-----------|----------|------------|------------------------------------|
| **Admin** | `admin`  | `admin123` | Lihat + Tambah + Edit + Hapus menu |
| **User**  | `dzaki`  | `user123`  | Hanya melihat daftar menu          |

Pengunjung juga bisa mendaftar sendiri lewat halaman **Register**.
Akun hasil pendaftaran otomatis berperan sebagai **user**.

---

## ▶️ Cara Menjalankan

```bash
php spark serve
```

Lalu buka: **http://localhost:8080**

Database (`writable/database/kantin.db`) sudah disertakan dan berisi data,
jadi bisa langsung login.

### Kalau ingin mengulang database dari nol

```bash
php spark migrate:refresh
php spark db:seed DatabaseSeeder
```

---

## 🗄️ Struktur Database

### Tabel `users`
| Kolom      | Tipe    | Keterangan                        |
|------------|---------|-----------------------------------|
| id         | INTEGER | Primary key                       |
| nama       | VARCHAR | Nama lengkap                      |
| username   | VARCHAR | Unik, dipakai untuk login         |
| password   | VARCHAR | Disimpan dalam bentuk hash        |
| role       | VARCHAR | `admin` atau `user`               |
| created_at / updated_at | DATETIME | Waktu dibuat & diubah |

### Tabel `produk`
| Kolom      | Tipe    | Keterangan                        |
|------------|---------|-----------------------------------|
| id         | INTEGER | Primary key                       |
| nama       | VARCHAR | Nama menu                         |
| harga      | INTEGER | Harga satuan (Rupiah)             |
| stok       | INTEGER | Jumlah stok                       |
| kategori   | VARCHAR | Makanan / Minuman / Snack         |
| created_at / updated_at | DATETIME | Waktu dibuat & diubah |

---

## 📁 File yang Ditambahkan

**Migration** — `app/Database/Migrations/`
- `2026-01-01-000001_CreateUsers.php`
- `2026-01-01-000002_CreateProduk.php`

**Seeder** — `app/Database/Seeds/`
- `UserSeeder.php` — mengisi 1 admin + 1 user
- `ProdukSeeder.php` — mengisi 10 contoh menu
- `DatabaseSeeder.php` — menjalankan keduanya sekaligus

**Model** — `app/Models/`
- `UserModel.php`
- `ProdukModel.php`

**Filter (pengaman hak akses)** — `app/Filters/`
- `AuthFilter.php` — halaman wajib login
- `AdminFilter.php` — halaman khusus admin

---

## 🔒 Pembagian Hak Akses

| Halaman              | Belum Login | User | Admin |
|----------------------|:-----------:|:----:|:-----:|
| Login / Register     |     ✅      |  —   |   —   |
| Dashboard            |     ❌      |  ✅  |  ✅   |
| Daftar Menu          |     ❌      |  ✅  |  ✅   |
| Tambah / Edit / Hapus|     ❌      |  ❌  |  ✅   |

Kalau user biasa mencoba membuka `/produk/tambah`, ia otomatis dialihkan
kembali ke daftar menu — jadi tidak bisa ditembus lewat URL.


---

## ✨ Fitur

| Fitur | Keterangan |
|---|---|
| **Login 2 peran** | Admin (kelola menu) & User (hanya lihat) |
| **Register** | Akun baru otomatis berperan `user` |
| **CRUD menu** | Tambah, lihat, ubah, hapus |
| **Upload foto** | Foto menu + pratinjau sebelum disimpan (maks 2 MB) |
| **Pencarian** | Cari menu berdasarkan nama |
| **Filter kategori** | Makanan / Minuman / Snack |
| **Urutkan** | Terbaru, A-Z, termurah, termahal, stok tersedikit |
| **Pagination** | 8 menu per halaman, nomor urut berlanjut antar halaman |
| **Grafik dashboard** | Diagram lingkaran & batang (Chart.js) |
| **Peringatan stok** | Stok < 20 ditandai merah, stok 0 ditandai "Habis" |
| **Unduh CSV** | Daftar menu bisa diunduh & dibuka di Excel |

## 📸 Tentang Upload Foto

- Foto disimpan di `public/uploads/produk/`
- Nama file diacak otomatis agar tidak saling menimpa
- Saat menu diubah dengan foto baru, foto lama dihapus dari server
- Saat menu dihapus, fotonya ikut dihapus
- Kalau tidak mengunggah foto, dipakai gambar bawaan `default.svg`
- Kolom `foto` ditambahkan lewat migration terpisah
  (`2026-01-01-000003_AddFotoToProduk.php`), bukan mengubah migration lama

---

## 📝 Catatan Perubahan

Sebelumnya, data **produk dan akun disimpan di session**, sehingga hilang
setiap kali logout. Sekarang keduanya tersimpan permanen di database.
