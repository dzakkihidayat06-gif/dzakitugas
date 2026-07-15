<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kantin Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f5f6fa; }</style>
</head>
<body>
<div class="container" style="max-width: 420px; margin-top: 8vh;">

    <div class="card p-4 shadow-sm border-0">
        <div class="text-center mb-4">
            <div style="font-size: 2.5rem;">🍽️</div>
            <h4 class="fw-bold mb-1">Kantin Digital</h4>
            <p class="text-muted small mb-0">Masuk untuk melanjutkan</p>
        </div>

        <?php if ($m = session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 small">✅ <?= esc($m) ?></div>
        <?php endif; ?>
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

        <form action="<?= base_url('auth/proses_login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="<?= old('username') ?>" required
                       class="form-control" placeholder="admin">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" required
                       class="form-control" placeholder="••••••••">
            </div>

            <button class="btn btn-dark w-100">Masuk</button>
        </form>

        <p class="text-center small text-muted mt-3 mb-0">
            Belum punya akun? <a href="<?= base_url('register') ?>">Daftar di sini</a>
        </p>

        <!-- Akun bawaan dari seeder -->
        <hr class="my-3">
        <div class="small">
            <div class="fw-semibold mb-1">Akun demo:</div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Admin</span>
                <span><code>admin</code> / <code>admin123</code></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">User</span>
                <span><code>dzaki</code> / <code>user123</code></span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
