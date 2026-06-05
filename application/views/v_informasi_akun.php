<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="hero-bg text-center">
        <div class="container">
            <h1 class="display-5 fw-bold">Informasi Akun</h1>
            <p class="fs-5 mt-2 mb-0" style="opacity: 0.9;">Kelola data dan keamanan akun kamu di sini.</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center content-card-overlap">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <!-- Profil -->
                        <div class="text-center mb-4">
                            <div class="akun-avatar mx-auto mb-3">
                                <?= strtoupper(mb_substr($user->username, 0, 1)); ?>
                            </div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($user->username); ?></h5>
                            <span class="badge rounded-pill px-3 py-2 <?= $user->role === 'ortu' ? 'bg-success' : 'bg-primary'; ?>">
                                <?= $user->role === 'ortu' ? 'Orang Tua' : 'Siswa'; ?>
                            </span>
                        </div>

                        <div class="mb-4"></div>

                        <!-- Informasi Akun -->
                        <div class="row g-3 mb-2">
                            <div class="col-6">
                                <p class="text-muted small mb-1">ID Akun</p>
                                <p class="fw-bold mb-0">#<?= str_pad($user->id, 4, '0', STR_PAD_LEFT); ?></p>
                            </div>
                            <div class="col-6">
                                <p class="text-muted small mb-1">Username</p>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($user->username); ?></p>
                            </div>
                            <div class="col-12">
                                <p class="text-muted small mb-1">Email</p>
                                <p class="fw-bold mb-0">
                                    <?= !empty($user->email) ? htmlspecialchars($user->email) : '<span class="text-muted fst-italic fw-normal">Belum diisi</span>'; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Pembatas -->
                        <div class="d-flex align-items-center gap-3 my-4">
                            <hr class="flex-grow-1 m-0">
                            <span class="text-muted small fw-bold px-2">&#128274; Ubah Password</span>
                            <hr class="flex-grow-1 m-0">
                        </div>

                        <!-- Form Ubah Password -->
                        <form action="<?= site_url('akun/update_password'); ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small">Password Lama</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" name="password_lama" id="pwLama" class="form-control bg-light border-start-0" placeholder="Masukkan password lama">
                                    <button class="btn btn-light" type="button" onclick="togglePassword('pwLama', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold small">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" name="password_baru" id="pwBaru" class="form-control bg-light border-start-0" placeholder="Minimal 6 karakter">
                                        <button class="btn btn-light" type="button" onclick="togglePassword('pwBaru', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold small">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" name="konfirmasi_password" id="pwKonfirm" class="form-control bg-light border-start-0" placeholder="Ulangi password baru">
                                        <button class="btn btn-light" type="button" onclick="togglePassword('pwKonfirm', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid mt-2">
                                <button type="submit" class="btn btn-primary fw-bold py-2">
                                    Simpan Password
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
    </script>

    <style>
    .akun-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
    }
    </style>

<?php $this->load->view('template/footer'); ?>
