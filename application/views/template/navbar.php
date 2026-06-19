    <?php $active = isset($active_page) ? $active_page : ''; ?>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm" style="background-color: #f2f1f1;">
        <div class="container-xxl px-4">
            <a class="navbar-brand p-0" href="<?= site_url('pendaftaran/index'); ?>">
                <img src="<?= base_url('assets/img/Logo.png'); ?>" alt="Logo" width="175" height="65" class="d-inline-block rounded align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav fw-bold ms-auto">
                    <li class="nav-item"><a class="nav-link <?= $active === 'home' ? 'text-primary' : ''; ?>" href="<?= site_url('pendaftaran'); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= $active === 'tentang_kami' ? 'text-primary' : ''; ?>" href="<?= site_url('pendaftaran/tentang_kami'); ?>">About Us</a></li>
                    <li class="nav-item"><a class="nav-link <?= $active === 'fasilitas' ? 'text-primary' : ''; ?>" href="<?= site_url('pendaftaran/fasilitas'); ?>">Benefits</a></li>
                    <li class="nav-item"><a class="nav-link <?= $active === 'kontak_kami' ? 'text-primary' : ''; ?>" href="<?= site_url('pendaftaran/kontak_kami'); ?>">Contact Us</a></li>
                    <?php if ($this->session->userdata('logged_in')):
                        $nama     = htmlspecialchars($this->session->userdata('nama_lengkap') ?: $this->session->userdata('username'));
                        $username = htmlspecialchars($this->session->userdata('username'));
                        $initial  = strtoupper(mb_substr($nama, 0, 1));
                    ?>
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-user-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="nav-user-avatar"><?= $initial; ?></span>
                            <span class="nav-user-name"><?= $nama; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 rounded-4 mt-2 overflow-hidden" style="min-width: 230px; box-shadow: 0 8px 30px rgba(0,0,0,0.12);">

                            <!-- Profile Header -->
                            <li>
                                <div class="d-flex align-items-center gap-3 px-4 py-3" style="background: linear-gradient(135deg, #0d6efd, #0dcaf0);">
                                    <div class="nav-user-avatar-lg"><?= $initial; ?></div>
                                    <div>
                                        <p class="fw-bold text-white mb-0 small"><?= $nama; ?></p>
                                        <p class="text-white mb-0" style="font-size: 0.75rem; opacity: 0.85;">@<?= $username; ?></p>
                                    </div>
                                </div>
                            </li>

                            <!-- Menu Items -->
                            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="<?= site_url('akun/paket_saya'); ?>">
                                <span class="dd-icon bg-primary-subtle text-primary"><i class="bi bi-journal-bookmark-fill"></i></span>
                                <span>Paket Saya</span>
                            </a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="<?= site_url('akun/pesanan_saya'); ?>">
                                <span class="dd-icon bg-success-subtle text-success"><i class="bi bi-bag-check-fill"></i></span>
                                <span>Pesanan Saya</span>
                            </a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="<?= site_url('akun/jadwal'); ?>">
                                <span class="dd-icon bg-warning-subtle text-warning"><i class="bi bi-calendar2-week-fill"></i></span>
                                <span>Jadwal Saya</span>
                            </a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="<?= site_url('akun/informasi'); ?>">
                                <span class="dd-icon bg-secondary-subtle text-secondary"><i class="bi bi-person-gear"></i></span>
                                <span>Informasi Akun</span>
                            </a></li>

                            <li><hr class="dropdown-divider my-1 mx-3"></li>

                            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4 text-danger" href="<?= site_url('auth/logout'); ?>">
                                <span class="dd-icon bg-danger-subtle text-danger"><i class="bi bi-box-arrow-right"></i></span>
                                <span>Logout</span>
                            </a></li>

                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-primary ms-lg-3 px-4" href="<?= site_url('auth/login'); ?>">Login</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="btn-daftar-les ms-lg-2" href="<?= site_url('pendaftaran/daftar'); ?>"><i class="bi bi-pencil-square me-1"></i>Daftar Les</a></li>
                </ul>
            </div>
        </div>
    </nav>
