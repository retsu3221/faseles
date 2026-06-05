<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5">

                            <!-- Header Card -->
                            <div class="text-center mb-5">
                                <div class="auth-icon-wrapper mx-auto mb-4">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <h4 class="fw-bold mb-2">Masuk ke Akun</h4>
                                <p class="text-muted small mb-0">Selamat datang kembali di FASE Les</p>
                            </div>

                            <form action="<?= site_url('auth/proses_login'); ?>" method="POST">

                                <div class="mb-4">
                                    <label class="form-label text-muted fw-bold small">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 px-3 <?= form_error('username') ? 'border-danger text-danger' : 'text-muted'; ?>">
                                            <i class="bi bi-person-fill"></i>
                                        </span>
                                        <input type="text" name="username" value="<?= set_value('username'); ?>" class="form-control bg-light border-start-0 py-2 <?= form_error('username') ? 'is-invalid' : ''; ?>" placeholder="Masukkan username">
                                    </div>
                                    <?php $err_username = trim(form_error('username', '', '')); if ($err_username): ?>
                                    <small class="text-danger d-block mt-1 ps-1" style="font-size:0.82rem;"><?= $err_username; ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label text-muted fw-bold small">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 px-3 <?= form_error('password') ? 'border-danger text-danger' : 'text-muted'; ?>">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" name="password" id="pwLogin" class="form-control bg-light border-start-0 py-2 <?= form_error('password') ? 'is-invalid' : ''; ?>" placeholder="Masukkan password">
                                        <button class="btn btn-light px-3 <?= form_error('password') ? 'border-danger' : ''; ?>" type="button" onclick="togglePassword('pwLogin', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <?php $err_password = trim(form_error('password', '', '')); if ($err_password): ?>
                                    <small class="text-danger d-block mt-1 ps-1" style="font-size:0.82rem;"><?= $err_password; ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary fw-bold py-3 shadow-sm" style="font-size: 1rem;">
                                        Masuk &#8594;
                                    </button>
                                </div>

                            </form>

                            <hr class="text-muted">
                            <div class="text-center mt-3">
                                <p class="text-muted small mb-0">
                                    Belum punya akun?
                                    <a href="<?= site_url('auth/register'); ?>" class="text-primary fw-bold text-decoration-none">Daftar di sini</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
    </script>

<?php $this->load->view('template/footer'); ?>
