<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="auth-wrapper">
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <!-- Header Card -->
                        <div class="text-center mb-4">
                            <div class="auth-icon-wrapper mx-auto mb-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Buat Akun Baru</h5>
                            <p class="text-muted small mb-0">Pilih jenis akun yang ingin didaftarkan</p>
                        </div>

                        <!-- Tab Switcher -->
                        <div class="register-tab-wrapper mb-4">
                            <ul class="nav nav-pills register-tab" id="registerTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-siswa" type="button" role="tab">
                                        &#127891; Siswa
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-ortu" type="button" role="tab">
                                        &#128106; Orang Tua
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="registerTabContent">

                            <!-- ===== FORM SISWA ===== -->
                            <div class="tab-pane fade show active" id="tab-siswa" role="tabpanel">
                                <form action="<?= site_url('auth/proses_register'); ?>" method="POST">
                                    <input type="hidden" name="role" value="siswa">

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-bold small">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">
                                                <i class="bi bi-person-fill"></i>
                                            </span>
                                            <input type="text" name="username" class="form-control bg-light border-start-0" required placeholder="Buat username unik">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-bold small">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                            <input type="password" name="password" id="pwSiswa" class="form-control bg-light border-start-0" required placeholder="Minimal 8 karakter">
                                            <button class="btn btn-light" type="button" onclick="togglePassword('pwSiswa', this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-muted fw-bold small">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                            <input type="password" name="konfirmasi_password" id="pwSiswaKonfirm" class="form-control bg-light border-start-0" required placeholder="Ulangi password">
                                            <button class="btn btn-light" type="button" onclick="togglePassword('pwSiswaKonfirm', this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                            Daftar sebagai Siswa &#8594;
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- ===== FORM ORANG TUA ===== -->
                            <div class="tab-pane fade" id="tab-ortu" role="tabpanel">
                                <form action="<?= site_url('auth/proses_register'); ?>" method="POST">
                                    <input type="hidden" name="role" value="ortu">

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-bold small">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">
                                                <i class="bi bi-person-fill"></i>
                                            </span>
                                            <input type="text" name="username" class="form-control bg-light border-start-0" required placeholder="Buat username unik">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-bold small">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                            <input type="password" name="password" id="pwOrtu" class="form-control bg-light border-start-0" required placeholder="Minimal 8 karakter">
                                            <button class="btn btn-light" type="button" onclick="togglePassword('pwOrtu', this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-muted fw-bold small">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                            <input type="password" name="konfirmasi_password" id="pwOrtuKonfirm" class="form-control bg-light border-start-0" required placeholder="Ulangi password">
                                            <button class="btn btn-light" type="button" onclick="togglePassword('pwOrtuKonfirm', this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                            Daftar sebagai Orang Tua &#8594;
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">
                                Sudah punya akun?
                                <a href="<?= site_url('auth/login'); ?>" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
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
