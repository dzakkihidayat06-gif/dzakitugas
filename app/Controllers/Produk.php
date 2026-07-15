<?php

namespace App\Controllers;

use App\Models\ProdukModel;

/**
 * MENU KANTIN (CRUD + upload foto)
 *
 * - Semua yang sudah login boleh MELIHAT daftar menu.
 * - Hanya ADMIN yang boleh menambah, mengubah, dan menghapus.
 *   (dijaga oleh AdminFilter pada Routes)
 */
class Produk extends BaseController
{
    protected ProdukModel $model;

    /** Folder penyimpanan foto menu */
    private const FOLDER = 'uploads/produk';

    public function __construct()
    {
        $this->model = new ProdukModel();
    }

    /** Daftar menu + pencarian + filter kategori + urutkan */
    public function index()
    {
        $keyword  = $this->request->getGet('cari');
        $kategori = $this->request->getGet('kategori');
        $urut     = $this->request->getGet('urut') ?: 'terbaru';

        $q = $this->model->cari($keyword);

        // Saring berdasarkan kategori
        if ($kategori && in_array($kategori, ['Makanan', 'Minuman', 'Snack'], true)) {
            $q->where('kategori', $kategori);
        }

        // Urutkan
        match ($urut) {
            'termurah' => $q->orderBy('harga', 'ASC'),
            'termahal' => $q->orderBy('harga', 'DESC'),
            'stok'     => $q->orderBy('stok', 'ASC'),
            'nama'     => $q->orderBy('nama', 'ASC'),
            default    => $q->orderBy('id', 'DESC'),
        };

        return view('produk/index', [
            'produk'   => $q->paginate(8, 'produk'),
            'pager'    => $this->model->pager,
            'keyword'  => $keyword,
            'kategori' => $kategori,
            'urut'     => $urut,
            // Jumlah menu per kategori (untuk tombol saring)
            'hitung'   => $this->model->hitungKategori(),
        ]);
    }

    public function tambah()
    {
        return view('produk/tambah', ['item' => null]);
    }

    public function create()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'nama'     => $this->request->getPost('nama'),
            'harga'    => (int) $this->request->getPost('harga'),
            'stok'     => (int) $this->request->getPost('stok'),
            'kategori' => $this->request->getPost('kategori') ?: 'Makanan',
            'foto'     => $this->upload() ?? 'default.svg',
        ]);

        return redirect()->to('/produk')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = $this->model->find($id);

        if (! $item) {
            return redirect()->to('/produk')->with('error', 'Menu tidak ditemukan.');
        }

        return view('produk/tambah', ['item' => $item]);
    }

    public function update($id)
    {
        $item = $this->model->find($id);

        if (! $item) {
            return redirect()->to('/produk')->with('error', 'Menu tidak ditemukan.');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $fotoBaru = $this->upload();

        // Kalau ada foto baru, foto lama dihapus dari server
        if ($fotoBaru) {
            $this->hapusFoto($item['foto']);
        }

        $this->model->update($id, [
            'nama'     => $this->request->getPost('nama'),
            'harga'    => (int) $this->request->getPost('harga'),
            'stok'     => (int) $this->request->getPost('stok'),
            'kategori' => $this->request->getPost('kategori') ?: 'Makanan',
            'foto'     => $fotoBaru ?? $item['foto'],   // tidak unggah = foto lama dipakai
        ]);

        return redirect()->to('/produk')->with('success', 'Menu berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $item = $this->model->find($id);

        if (! $item) {
            return redirect()->to('/produk')->with('error', 'Menu tidak ditemukan.');
        }

        $this->hapusFoto($item['foto']);
        $this->model->delete($id);

        return redirect()->to('/produk')->with('success', 'Menu berhasil dihapus.');
    }

    /** Unduh daftar menu sebagai CSV (bisa dibuka di Excel) */
    public function export()
    {
        $data = $this->model->orderBy('kategori', 'ASC')->orderBy('nama', 'ASC')->findAll();

        $out = fopen('php://temp', 'r+');
        fwrite($out, "\xEF\xBB\xBF");   // agar huruf tampil benar di Excel
        fputcsv($out, ['No', 'Nama Menu', 'Kategori', 'Harga', 'Stok', 'Nilai Stok']);

        $no = 1;
        $total = 0;
        foreach ($data as $p) {
            $nilai = (int) $p['harga'] * (int) $p['stok'];
            $total += $nilai;
            fputcsv($out, [$no++, $p['nama'], $p['kategori'], $p['harga'], $p['stok'], $nilai]);
        }

        fputcsv($out, []);
        fputcsv($out, ['', '', '', '', 'TOTAL NILAI STOK', $total]);

        rewind($out);
        $isi = stream_get_contents($out);
        fclose($out);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="daftar-menu-' . date('Y-m-d') . '.csv"')
            ->setBody($isi);
    }

    // ---------------- bantuan ----------------

    private function rules(): array
    {
        return [
            'nama'  => 'required|min_length[3]|max_length[100]',
            'harga' => 'required|integer|greater_than[0]',
            'stok'  => 'required|integer|greater_than_equal_to[0]',
            // Foto boleh dikosongkan. Kalau diisi: harus gambar, maksimal 2 MB.
            'foto'  => 'permit_empty|is_image[foto]|max_size[foto,2048]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp,image/svg+xml]',
        ];
    }

    /** Simpan foto yang diunggah; kembalikan nama filenya (null bila tidak ada unggahan) */
    private function upload(): ?string
    {
        $file = $this->request->getFile('foto');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $nama = $file->getRandomName();   // nama acak, agar tidak saling menimpa
        $file->move(FCPATH . self::FOLDER, $nama);

        return $nama;
    }

    /** Hapus file foto dari server (kecuali gambar bawaan) */
    private function hapusFoto(?string $foto): void
    {
        if (! $foto || $foto === 'default.svg') {
            return;
        }

        // Foto bawaan seeder (.svg) jangan ikut dihapus
        $bawaan = ['nasi-goreng.svg', 'mie-ayam.svg', 'ayam-geprek.svg', 'soto-ayam.svg',
                   'es-teh.svg', 'es-jeruk.svg', 'kopi-susu.svg', 'roti-bakar.svg',
                   'pisang-goreng.svg', 'keripik.svg'];

        if (in_array($foto, $bawaan, true)) {
            return;
        }

        $path = FCPATH . self::FOLDER . '/' . $foto;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
