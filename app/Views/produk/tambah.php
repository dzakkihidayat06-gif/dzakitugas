<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
// $item terisi -> mode EDIT, $item null -> mode TAMBAH
$edit    = ! empty($item);
$action  = $edit ? base_url('produk/update/' . $item['id']) : base_url('produk/create');
$fotoAwal = base_url('uploads/produk/' . ($edit ? ($item['foto'] ?: 'default.svg') : 'default.svg'));
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="<?= base_url('produk') ?>" class="btn btn-sm btn-outline-secondary">← Kembali</a>
            <h4 class="fw-bold mb-0"><?= $edit ? 'Edit Menu' : 'Tambah Menu' ?></h4>
        </div>

        <div class="card p-4">
            <!-- enctype WAJIB ada, kalau tidak file foto tidak akan terkirim -->
            <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">

                    <!-- ---------- Kolom kiri: foto ---------- -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Menu</label>

                        <!-- Pratinjau foto -->
                        <div class="border rounded p-2 text-center mb-2 bg-light">
                            <img id="pratinjau" src="<?= $fotoAwal ?>" alt="Pratinjau"
                                 class="img-fluid rounded"
                                 style="height:170px; width:100%; object-fit:cover;">
                        </div>

                        <input type="file" name="foto" id="inputFoto" accept="image/*"
                               class="form-control form-control-sm">

                        <div class="form-text small">
                            JPG, PNG, atau WEBP. Maksimal 2 MB.
                            <?php if ($edit): ?>
                                <br><span class="text-muted">Kosongkan bila tidak ingin mengganti foto.</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ---------- Kolom kanan: data ---------- -->
                    <div class="col-md-8">

                        <div class="mb-3">
                            <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama" required
                                   value="<?= old('nama', $edit ? $item['nama'] : '') ?>"
                                   class="form-control" placeholder="Contoh: Nasi Goreng Spesial">
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga" required min="1"
                                           value="<?= old('harga', $edit ? $item['harga'] : '') ?>"
                                           class="form-control" placeholder="15000">
                                </div>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" required min="0"
                                       value="<?= old('stok', $edit ? $item['stok'] : 0) ?>"
                                       class="form-control" placeholder="20">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                <?php
                                $pilihan  = ['Makanan', 'Minuman', 'Snack'];
                                $terpilih = old('kategori', $edit ? $item['kategori'] : 'Makanan');
                                foreach ($pilihan as $k): ?>
                                    <option value="<?= $k ?>" <?= $terpilih === $k ? 'selected' : '' ?>><?= $k ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <button class="btn btn-dark">
                        <?= $edit ? '💾 Simpan Perubahan' : '💾 Simpan Menu' ?>
                    </button>
                    <a href="<?= base_url('produk') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Tampilkan pratinjau foto begitu file dipilih (belum diunggah ke server)
document.getElementById('inputFoto').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = ev => document.getElementById('pratinjau').src = ev.target.result;
    reader.readAsDataURL(file);
});
</script>

<?= $this->endSection() ?>
