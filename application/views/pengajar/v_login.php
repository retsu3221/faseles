<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Pengajar — FASE Les</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/login-admin.css') ?>" rel="stylesheet">
</head>
<body>

<div class="login-card">

    <!-- LOGO -->
    <div class="login-logo">
        <div class="logo-icon"><i class="bi bi-person-video3"></i></div>
        <div class="logo-name">FASE <span>Les</span></div>
        <div class="logo-sub">Portal Pengajar</div>
    </div>

    <hr class="divider-line">

    <?php if ($this->session->flashdata('error')): ?>
    <div class="login-alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= htmlspecialchars($this->session->flashdata('error')) ?>
    </div>
    <?php endif; ?>

    <!-- FORM -->
    <form action="<?= site_url('pengajar/login_proses') ?>" method="post">

        <div class="mb-0">
            <label class="form-label">Username</label>
            <div class="input-group-custom">
                <i class="bi bi-person-fill input-icon"></i>
                <input type="text" class="form-control" name="username"
                       placeholder="Masukkan username" required autocomplete="username">
            </div>
        </div>

        <div class="mb-0">
            <label class="form-label">Password</label>
            <div class="input-group-custom">
                <i class="bi bi-lock-fill input-icon"></i>
                <input type="password" class="form-control" id="password"
                       name="password" placeholder="Masukkan password" required>
                <button type="button" class="toggle-pass" id="togglePass">
                    <i class="bi bi-eye-fill" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>

    </form>

    <a href="<?= site_url('/') ?>" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Website
    </a>

    <div class="security-note">
        <i class="bi bi-shield-fill"></i> Khusus Pengajar
    </div>

</div>

<script>
document.getElementById('togglePass').addEventListener('click', function () {
    var input = document.getElementById('password');
    var icon  = document.getElementById('toggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash-fill';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye-fill';
    }
});
</script>

</body>
</html>
