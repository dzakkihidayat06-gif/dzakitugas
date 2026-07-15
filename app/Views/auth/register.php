<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Kantin Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f5f6fa; }</style>
</head>
<body>
<div class="container" style="max-width: 420px; margin-top: 8vh;">

    <div class="card p-4 shadow-sm border-0">
        <div class="text-center mb-4">
            <div style="font-size: 2.5rem;">📝</div>
            <h4 class="fw-bold mb-1">Daftar Akun</h4>
            <p class="text-muted small mb-0">Akun baru otomatis berperan sebagai <strong>user</strong></p>
        </div>

        <?php if ($m = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 small">⚠️ <?= esc($m) ?></div>
        <?php endif; ?>
        <?php if ($errs = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0 ps-3">
                    <?php foreach ($errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= old('nama') ?>" required
                       class="form-control" placeholder="Nama kamu">
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="<?= old('username') ?>" required
                       class="form-control" placeholder="username unik">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" required
                       class="form-control" placeholder="minimal 6 karakter">
            </div>

            <button class="btn btn-dark w-100">Daftar</button>
        </form>

        <p class="text-center small text-muted mt-3 mb-0">
            Sudah punya akun? <a href="<?= base_url('login') ?>">Login</a>
        </p>
    </div>
</div>
</body>
</html>
