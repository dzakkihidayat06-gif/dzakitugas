<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php $isAdmin = session()->get('role') === 'admin'; ?>

<!-- ---------- Sapaan ---------- -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Halo, <?= esc(session()->get('nama')) ?> 👋</h4>
        <p class="text-muted small mb-0">
            Kamu masuk sebagai
            <span class="badge <?= $isAdmin ? 'bg-warning text-dark' : 'bg-secondary' ?>">
                <?= esc(session()->get('role')) ?>
            </span>
        </p>
    </div>

    <?php if ($isAdmin): ?>
        <a href="<?= base_url('produk/tambah') ?>" class="btn btn-dark">+ Tambah Menu Baru</a>
    <?php endif; ?>
</div>

<!-- ---------- Kartu statistik ---------- -->
<div class="row g-3 mb-4">
    <?php
    $kartu = [
        ['Total Menu',  $jmlProduk,                                     '🍽️', 'primary'],
        ['Total Stok',  $totalStok,                                     '📦', 'success'],
        ['Nilai Stok',  'Rp ' . number_format($nilaiStok, 0, ',', '.'), '💰', 'warning'],
        ['Jumlah Akun', $jmlUser,                                       '👥', 'info'],
    ];
    foreach ($kartu as [$label, $angka, $ikon, $warna]): ?>
        <div class="col-6 col-lg-3">
            <div class="card h-100 p-3 border-start border-4 border-<?= $warna ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="min-w-0">
                        <div class="text-muted small"><?= $label ?></div>
                        <div class="fs-5 fw-bold text-truncate"><?= $angka ?></div>
                    </div>
                    <span class="fs-3 ms-2"><?= $ikon ?></span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- ---------- Grafik ---------- -->
<div class="row g-3 mb-3">

    <div class="col-lg-5">
        <div class="card p-3 h-100">
            <h6 class="fw-bold mb-3">Jumlah Menu per Kategori</h6>
            <canvas id="grafikKategori" height="180"></canvas>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-3 h-100">
            <h6 class="fw-bold mb-3">Total Stok per Kategori</h6>
            <canvas id="grafikStok" height="180"></canvas>
        </div>
    </div>
</div>

<!-- ---------- Dua daftar ---------- -->
<div class="row g-3">

    <div class="col-lg-6">
        <div class="card p-3 h-100">
            <h6 class="fw-bold mb-3">
                ⚠️ Stok Menipis
                <span class="text-muted fw-normal small">(kurang dari 20)</span>
            </h6>

            <?php if (empty($stokMenipis)): ?>
                <p class="text-muted small mb-0">Semua stok masih aman. 👍</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($stokMenipis as $p): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="d-flex align-items-center gap-2">
                                <img src="<?= base_url('uploads/produk/' . ($p['foto'] ?: 'default.svg')) ?>"
                                     class="rounded border" style="width:36px; height:36px; object-fit:cover;" alt="">
                                <?= esc($p['nama']) ?>
                            </span>
                            <span class="badge <?= (int) $p['stok'] === 0 ? 'bg-dark' : 'bg-danger' ?>">
                                <?= (int) $p['stok'] === 0 ? 'Habis' : 'Sisa ' . (int) $p['stok'] ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">💎 Menu Termahal</h6>
                <a href="<?= base_url('produk') ?>" class="small text-decoration-none">Lihat semua →</a>
            </div>

            <?php if (empty($termahal)): ?>
                <p class="text-muted small mb-0">Belum ada menu.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($termahal as $p): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="d-flex align-items-center gap-2">
                                <img src="<?= base_url('uploads/produk/' . ($p['foto'] ?: 'default.svg')) ?>"
                                     class="rounded border" style="width:36px; height:36px; object-fit:cover;" alt="">
                                <span>
                                    <?= esc($p['nama']) ?><br>
                                    <small class="text-muted"><?= esc($p['kategori']) ?></small>
                                </span>
                            </span>
                            <span class="fw-semibold text-nowrap">
                                Rp <?= number_format((int) $p['harga'], 0, ',', '.') ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (! $isAdmin): ?>
    <div class="alert alert-info mt-3 mb-0 small">
        ℹ️ Sebagai <strong>user</strong>, kamu hanya dapat melihat menu.
        Untuk menambah atau mengubah menu, login sebagai <strong>admin</strong>.
    </div>
<?php endif; ?>

<!-- ---------- Skrip grafik ---------- -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Diagram lingkaran: jumlah menu per kategori
    new Chart(document.getElementById('grafikKategori'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_keys($perKategori)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($perKategori)) ?>,
                backgroundColor: ['#0d6efd', '#0dcaf0', '#ffc107'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            cutout: '58%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } } },
        },
    });

    // Diagram batang: total stok per kategori
    new Chart(document.getElementById('grafikStok'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($stokKategori)) ?>,
            datasets: [{
                label: 'Total Stok',
                data: <?= json_encode(array_values($stokKategori)) ?>,
                backgroundColor: ['#0d6efd', '#0dcaf0', '#ffc107'],
                borderRadius: 6,
                maxBarThickness: 70,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } },
            },
        },
    });
});
</script>

<?= $this->endSection() ?>
