<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="auth-wrapper">
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <!-- Header Card -->
                        <div class="text-center mb-4">
                            <div class="auth-icon-wrapper mx-auto mb-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Buat Akun Baru</h5>
                            <p class="text-muted small mb-0">Lengkapi data di bawah untuk mendaftar</p>
                        </div>

                        <form action="<?= site_url('auth/proses_register'); ?>" method="POST">

                            <div class="form-section-title mt-0">&#128100; Data Pribadi Siswa</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control bg-light" required placeholder="Nama lengkap siswa">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control bg-light" required placeholder="Kota tempat lahir">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control bg-light" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small d-block">Jenis Kelamin</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" id="jkL" required>
                                        <label class="form-check-label" for="jkL">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" id="jkP">
                                        <label class="form-check-label" for="jkP">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small">Alamat</label>
                                <textarea name="alamat" class="form-control bg-light" rows="2" required placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                            </div>

                            <div class="form-section-title">&#128106; Data Orang Tua / Wali</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small">Nama Lengkap Orang Tua / Wali</label>
                                <input type="text" name="nama_ortu" class="form-control bg-light" required placeholder="Nama lengkap orang tua atau wali">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">No. WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">+62</span>
                                        <input type="number" name="no_wa_ortu" class="form-control bg-light" required placeholder="8xxxxxxxxxx">
                                    </div>
                                    <div class="form-text">Tanpa angka 0 di depan</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control bg-light" required placeholder="Contoh: Wiraswasta">
                                </div>
                            </div>

                            <div class="form-section-title">&#128274; Data Akun</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-at"></i></span>
                                    <input type="text" name="username" class="form-control bg-light border-start-0" required placeholder="Buat username unik">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope-fill"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0" required placeholder="contoh@email.com">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="password" id="pw" class="form-control bg-light border-start-0" required minlength="8" placeholder="Minimal 8 karakter">
                                        <button class="btn btn-light" type="button" onclick="togglePassword('pw', this)"><i class="bi bi-eye"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="konfirmasi_password" id="pwKonfirm" class="form-control bg-light border-start-0" required placeholder="Ulangi password">
                                        <button class="btn btn-light" type="button" onclick="togglePassword('pwKonfirm', this)"><i class="bi bi-eye"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                    Daftar Sekarang &#8594;
                                </button>
                            </div>

                        </form>

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
