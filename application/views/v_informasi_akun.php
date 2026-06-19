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
            <div class="col-12 col-lg-9">


                <!-- Avatar & Nama -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 d-flex align-items-center gap-4">
                        <div class="akun-avatar flex-shrink-0">
                            <?= strtoupper(mb_substr($user->nama_lengkap ?: $user->username, 0, 1)); ?>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($user->nama_lengkap ?: $user->username) ?></h5>
                            <div class="text-muted small">@<?= htmlspecialchars($user->username) ?> &middot; <?= htmlspecialchars($user->email) ?></div>
                        </div>
                        <div class="ms-auto text-muted small d-none d-md-block">
                            Bergabung: <strong><?= date('d M Y', strtotime($user->created_at)) ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Data Pribadi -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white rounded-top-4 py-3 px-4 border-0">
                        <h6 class="fw-bold mb-0 text-primary">
                            <i class="bi bi-person-vcard me-2"></i>Data Pribadi
                        </h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form action="<?= site_url('akun/update_profil') ?>" method="POST">

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label class="form-label text-muted fw-bold small">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control bg-light"
                                           value="<?= htmlspecialchars($user->nama_lengkap ?: '') ?>"
                                           placeholder="Nama lengkap">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control bg-light"
                                           value="<?= htmlspecialchars($user->tempat_lahir ?: '') ?>"
                                           placeholder="Kota tempat lahir">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control bg-light"
                                           value="<?= htmlspecialchars($user->tanggal_lahir ?: '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small d-block">Jenis Kelamin</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                   value="Laki-laki" id="jkL"
                                                   <?= $user->jenis_kelamin === 'Laki-laki' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="jkL">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                   value="Perempuan" id="jkP"
                                                   <?= $user->jenis_kelamin === 'Perempuan' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="jkP">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Asal Sekolah</label>
                                    <input type="text" name="asal_sekolah" class="form-control bg-light"
                                           value="<?= htmlspecialchars($user->asal_sekolah ?: '') ?>"
                                           placeholder="Nama sekolah">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-bold small">Alamat</label>
                                    <textarea name="alamat" class="form-control bg-light" rows="2"
                                              placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan..."><?= htmlspecialchars($user->alamat ?: '') ?></textarea>
                                </div>
                            </div>

                            <hr class="my-3">
                            <p class="fw-bold text-warning small mb-3"><i class="bi bi-people me-1"></i>Data Orang Tua / Wali</p>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label text-muted fw-bold small">Nama Orang Tua / Wali</label>
                                    <input type="text" name="nama_ortu" class="form-control bg-light"
                                           value="<?= htmlspecialchars($user->nama_ortu ?: '') ?>"
                                           placeholder="Nama lengkap orang tua atau wali">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">No. WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">+62</span>
                                        <input type="text" name="no_wa_ortu" class="form-control bg-light"
                                               value="<?= htmlspecialchars($user->no_wa_ortu ?: '') ?>"
                                               placeholder="8xxxxxxxxxx">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control bg-light"
                                           value="<?= htmlspecialchars($user->pekerjaan_ortu ?: '') ?>"
                                           placeholder="Contoh: Wiraswasta">
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-bold py-2">
                                    <i class="bi bi-check-lg me-1"></i>Simpan Data Pribadi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Ubah Password -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white rounded-top-4 py-3 px-4 border-0">
                        <h6 class="fw-bold mb-0 text-danger">
                            <i class="bi bi-key me-2"></i>Ubah Password
                        </h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form action="<?= site_url('akun/update_password'); ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold small">Password Lama</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_lama" id="pwLama" class="form-control bg-light border-start-0" placeholder="Masukkan password lama">
                                    <button class="btn btn-light" type="button" onclick="togglePassword('pwLama', this)"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="password_baru" id="pwBaru" class="form-control bg-light border-start-0" placeholder="Minimal 6 karakter">
                                        <button class="btn btn-light" type="button" onclick="togglePassword('pwBaru', this)"><i class="bi bi-eye"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="konfirmasi_password" id="pwKonfirm" class="form-control bg-light border-start-0" placeholder="Ulangi password baru">
                                        <button class="btn btn-light" type="button" onclick="togglePassword('pwKonfirm', this)"><i class="bi bi-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger fw-bold py-2">
                                    <i class="bi bi-key me-1"></i>Simpan Password
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
