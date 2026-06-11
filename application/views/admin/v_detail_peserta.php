<?php
$this->load->view('admin/template/header', [
    'page_title'  => $page_title,
    'active_menu' => $active_menu,
]);
$this->load->view('admin/template/topbar', [
    'page_title' => $page_title,
]);
?>

<div class="mb-3">
    <a href="<?= site_url('admin/peserta') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">

    <!-- Info Akun -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center pt-4">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:4.5rem;height:4.5rem;">
                    <i class="bi bi-person-fill text-primary" style="font-size:2rem;"></i>
                </div>
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($user['username']) ?></h5>
                <p class="text-muted small mb-3"><?= htmlspecialchars($user['email']) ?></p>

                <?php
                $roleMap = [
                    'siswa'    => ['bg-info bg-opacity-10 text-info',          'Siswa'],
                    'ortu'     => ['bg-warning bg-opacity-10 text-warning',    'Orang Tua'],
                    'pengajar' => ['bg-success bg-opacity-10 text-success',    'Pengajar'],
                    'admin'    => ['bg-danger bg-opacity-10 text-danger',      'Admin'],
                ];
                [$cls, $label] = $roleMap[$user['role']] ?? ['bg-secondary bg-opacity-10 text-secondary', ucfirst($user['role'])];
                ?>
                <span class="badge <?= $cls ?> fw-semibold px-3 py-2"><?= $label ?></span>
            </div>
            <hr class="mx-3 my-0">
            <div class="card-body">
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">ID Pengguna</span>
                    <span class="fw-semibold">#<?= $user['id'] ?></span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Bergabung</span>
                    <span class="fw-semibold"><?= date('d M Y', strtotime($user['created_at'])) ?></span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Total Pendaftaran</span>
                    <span class="fw-bold text-primary"><?= count($pendaftaran) ?></span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 pb-3 px-3">
                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalEditPeserta">
                    <i class="bi bi-pencil-square me-1"></i>Edit Data Peserta
                </button>
            </div>
        </div>
    </div>

    <!-- Riwayat Pendaftaran -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pendaftaran
                </h6>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pendaftaran)): ?>
                <?php foreach ($pendaftaran as $p): ?>
                <?php
                $st = $p['status_verifikasi'];
                $stMap = [
                    'pending'    => ['bg-warning text-dark', 'Pending'],
                    'diterima'   => ['bg-success',           'Lunas'],
                    'ditolak'    => ['bg-danger',            'Ditolak'],
                    'kadaluarsa' => ['bg-secondary',         'Kadaluarsa'],
                ];
                [$stCls, $stLabel] = $stMap[$st] ?? ['bg-secondary', ucfirst($st)];
                ?>
                <div class="px-4 py-3 border-bottom">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold mb-1"><?= htmlspecialchars($p['nama_lengkap']) ?></div>
                            <div class="small text-muted mb-1">
                                <i class="bi bi-book me-1"></i>
                                <?= htmlspecialchars($p['tingkat'] ?? '-') ?> &mdash;
                                <?= htmlspecialchars($p['tipe_kelas'] ?? '-') ?>
                                &nbsp;&bull;&nbsp;
                                <span class="fw-semibold text-dark">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?= date('d M Y', strtotime($p['tanggal_daftar'])) ?>
                                &nbsp;&bull;&nbsp;
                                No. <?= htmlspecialchars($p['no_transaksi'] ?? '-') ?>
                            </div>
                        </div>
                        <span class="badge <?= $stCls ?> flex-shrink-0"><?= $stLabel ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                    Belum ada riwayat pendaftaran.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Modal Edit Peserta -->
<div class="modal fade" id="modalEditPeserta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-1 text-primary"></i>Edit Data Peserta
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/update_peserta/' . $user['id']) ?>" method="post">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username</label>
                        <input type="text" name="username" class="form-control"
                               value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role</label>
                        <select name="role" class="form-select">
                            <?php foreach (['siswa' => 'Siswa', 'ortu' => 'Orang Tua', 'pengajar' => 'Pengajar', 'admin' => 'Admin'] as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $user['role'] === $val ? 'selected' : '' ?>>
                                <?= $lbl ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <hr>

                    <p class="small text-muted mb-2 fw-semibold">Ganti Password <span class="fw-normal">(kosongkan jika tidak diubah)</span></p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password Baru</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Minimal 6 karakter">
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold small">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_password" class="form-control"
                               placeholder="Ulangi password baru">
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('admin/template/footer'); ?>
