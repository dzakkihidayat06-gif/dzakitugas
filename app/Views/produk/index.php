<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$isAdmin = session()->get('role') === 'admin';

// Nomor urut yang BERLANJUT antar halaman.
// Contoh: halaman 2 (8 data/halaman) mulai dari nomor 9, bukan kembali ke 1.
$perHalaman = 8;
$halaman    = $pager->getCurrentPage('produk');
$no         = ($halaman - 1) * $perHalaman + 1;
?>

<!-- ---------- Judul ---------- -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h4 class="fw-bold mb-0">Daftar Menu</h4>
        <p class="text-muted small mb-0">
            Menampilkan <strong><?= count($produk) ?></strong> menu
            dari total <strong><?= array_sum($hitung) ?></strong>
        </p>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= base_url('produk/export') ?>" class="btn btn-outline-success btn-sm text-nowrap">
            ⬇ Unduh CSV
        </a>
        <?php if ($isAdmin): ?>
            <a href="<?= base_url('produk/tambah') ?>" class="btn btn-dark btn-sm text-nowrap">+ Tambah Menu</a>
        <?php endif; ?>
    </div>
</div>

<!-- ---------- Panel saring ---------- -->
<div class="card p-3 mb-3">
    <form method="get" class="row g-2 align-items-end">

        <div class="col-md-5">
            <label class="form-label small text-muted mb-1">Cari menu</label>
            <input type="text" name="cari" value="<?= esc($keyword ?? '') ?>"
                   class="form-control form-control-sm" placeholder="Ketik nama menu...">
        </div>

        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">Kategori</label>
            <select name="kategori" class="form-select form-select-sm">
                <option value="">Semua Kategori</option>
                <?php foreach (['Makanan', 'Minuman', 'Snack'] as $k): ?>
                    <option value="<?= $k ?>" <?= ($kategori ?? '') === $k ? 'selected' : '' ?>>
                        <?= $k ?> (<?= $hitung[$k] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">Urutkan</label>
            <select name="urut" class="form-select form-select-sm">
                <?php
                $opsi = [
                    'terbaru'  => 'Terbaru',
                    'nama'     => 'Nama (A-Z)',
                    'termurah' => 'Harga Termurah',
                    'termahal' => 'Harga Termahal',
                    'stok'     => 'Stok Paling Sedikit',
                ];
                foreach ($opsi as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($urut ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-1 d-grid">
            <button class="btn btn-dark btn-sm">Terapkan</button>
        </div>
    </form>

    <?php if (! empty($keyword) || ! empty($kategori)): ?>
        <div class="mt-2 small">
            <span class="text-muted">Saringan aktif:</span>
            <?php if (! empty($keyword)): ?>
                <span class="badge bg-secondary">Cari: <?= esc($keyword) ?></span>
            <?php endif; ?>
            <?php if (! empty($kategori)): ?>
                <span class="badge bg-secondary">Kategori: <?= esc($kategori) ?></span>
            <?php endif; ?>
            <a href="<?= base_url('produk') ?>" class="ms-1">Hapus saringan</a>
        </div>
    <?php endif; ?>
</div>

<?php if (! $isAdmin): ?>
    <div class="alert alert-info py-2 small">
        ℹ️ Kamu login sebagai <strong>user</strong> — hanya bisa melihat. Tombol ubah/hapus khusus admin.
    </div>
<?php endif; ?>

<!-- ---------- Daftar menu ---------- -->
<?php if (empty($produk)): ?>
    <div class="card p-5 text-center">
        <div class="fs-1">🍽️</div>
        <p class="text-muted mb-2">
            <?= (! empty($keyword) || ! empty($kategori)) ? 'Menu tidak ditemukan.' : 'Belum ada menu.' ?>
        </p>
        <?php if (! empty($keyword) || ! empty($kategori)): ?>
            <div><a href="<?= base_url('produk') ?>" class="btn btn-sm btn-outline-dark">Tampilkan semua menu</a></div>
        <?php endif; ?>
    </div>
<?php else: ?>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width:56px;">No</th>
                    <th style="width:76px;">Foto</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga</th>
                    <th class="text-center">Stok</th>
                    <?php if ($isAdmin): ?>
                        <th class="text-center" style="width:150px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produk as $item): ?>
                <tr>
                    <!-- Nomor berlanjut antar halaman -->
                    <td class="text-center text-muted"><?= $no++ ?></td>

                    <td>
                        <img src="<?= base_url('uploads/produk/' . ($item['foto'] ?: 'default.svg')) ?>"
                             alt="<?= esc($item['nama']) ?>"
                             class="rounded border"
                             style="width:56px; height:56px; object-fit:cover;">
                    </td>

                    <td class="fw-semibold"><?= esc($item['nama']) ?></td>

                    <td>
                        <?php
                        $warna = ['Makanan' => 'primary', 'Minuman' => 'info', 'Snack' => 'warning'];
                        $w = $warna[$item['kategori']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $w ?>-subtle text-<?= $w ?>-emphasis border border-<?= $w ?>-subtle">
                            <?= esc($item['kategori']) ?>
                        </span>
                    </td>

                    <td class="text-end fw-semibold">
                        Rp <?= number_format((int) $item['harga'], 0, ',', '.') ?>
                    </td>

                    <td class="text-center">
                        <?php $stok = (int) $item['stok']; ?>
                        <?php if ($stok === 0): ?>
                            <span class="badge bg-dark">Habis</span>
                        <?php elseif ($stok < 20): ?>
                            <span class="badge bg-danger" title="Stok menipis"><?= $stok ?></span>
                        <?php else: ?>
                            <span class="badge bg-success"><?= $stok ?></span>
                        <?php endif; ?>
                    </td>

                    <?php if ($isAdmin): ?>
                    <td class="text-center">
                        <a href="<?= base_url('produk/edit/' . $item['id']) ?>"
                           class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="<?= base_url('produk/hapus/' . $item['id']) ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Yakin ingin menghapus menu <?= esc($item['nama'], 'js') ?>?')">
                            Hapus
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ---------- Nomor halaman ---------- -->
<div class="mt-3">
    <?= $pager->only(['cari', 'kategori', 'urut'])->links('produk', 'bootstrap') ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
