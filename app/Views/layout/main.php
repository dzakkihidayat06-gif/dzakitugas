<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Kantin Digital') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f6fa; }
        .navbar-brand { font-weight: 700; }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    </style>
</head>
<body>

<!-- ---------- Navbar ---------- -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">🍽️ Kantin Digital</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('produk') ?>">Daftar Menu</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small">
                    <?= esc(session()->get('nama')) ?>
                    <!-- Lencana peran: admin atau user -->
                    <span class="badge <?= session()->get('role') === 'admin' ? 'bg-warning text-dark' : 'bg-secondary' ?>">
                        <?= esc(session()->get('role')) ?>
                    </span>
                </span>
                <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-sm">Keluar</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">

    <!-- ---------- Notifikasi ---------- -->
    <?php if ($m = session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            ✅ <?= esc($m) ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($m = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            ⚠️ <?= esc($m) ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($errs = session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Periksa kembali isian Anda:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach ($errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
            </ul>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
