<?php
$this->load->view('admin/template/header', [
    'page_title'  => $page_title,
    'active_menu' => $active_menu,
]);
$this->load->view('admin/template/topbar', [
    'page_title' => $page_title,
]);
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-secondary me-1">
                <i class="bi bi-people-fill me-2 text-primary"></i>Data Peserta
            </h6>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPeserta">
                <i class="bi bi-plus-lg me-1"></i>Tambah Peserta
            </button>
            <span class="badge bg-primary rounded-pill ms-auto"><?= count($users) ?> pengguna</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablePeserta">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width:40px;">#</th>
                        <th>Username</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th>Role</th>
                        <th class="text-center">Daftar</th>
                        <th class="d-none d-md-table-cell">Bergabung</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td class="ps-3 text-muted small"><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-none d-md-flex align-items-center justify-content-center bg-primary bg-opacity-10"
                                     style="width:2rem;height:2rem;flex-shrink:0;">
                                    <i class="bi bi-person-fill text-primary" style="font-size:.85rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($u['username']) ?></div>
                                    <div class="small text-muted d-md-none"><?= htmlspecialchars($u['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small d-none d-md-table-cell"><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <?php
                            $roleMap = [
                                'siswa'   => ['bg-info bg-opacity-10 text-info',         'Siswa'],
                                'ortu'    => ['bg-warning bg-opacity-10 text-warning',    'Orang Tua'],
                                'pengajar'=> ['bg-success bg-opacity-10 text-success',    'Pengajar'],
                                'admin'   => ['bg-danger bg-opacity-10 text-danger',      'Admin'],
                            ];
                            [$cls, $label] = $roleMap[$u['role']] ?? ['bg-secondary bg-opacity-10 text-secondary', ucfirst($u['role'])];
                            ?>
                            <span class="badge <?= $cls ?> fw-semibold px-2 py-1"><?= $label ?></span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold <?= $u['jumlah_pendaftaran'] > 0 ? 'text-primary' : 'text-muted' ?>">
                                <?= $u['jumlah_pendaftaran'] ?>
                            </span>
                        </td>
                        <td class="text-muted small d-none d-md-table-cell">
                            <?= date('d M Y', strtotime($u['created_at'])) ?>
                        </td>
                        <td class="text-center pe-3 text-nowrap">
                            <a href="<?= site_url('admin/detail_peserta/' . $u['id']) ?>"
                               class="btn btn-sm btn-outline-primary me-1" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-hapus"
                                    title="Hapus"
                                    data-id="<?= $u['id'] ?>"
                                    data-nama="<?= htmlspecialchars($u['username']) ?>">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada data pengguna.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Peserta -->
<div class="modal fade" id="modalTambahPeserta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-1 text-primary"></i>Tambah Peserta Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/tambah_peserta') ?>" method="post">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control"
                               placeholder="Masukkan username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control"
                               placeholder="Masukkan email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Role --</option>
                            <option value="siswa">Siswa</option>
                            <option value="ortu">Orang Tua</option>
                            <option value="pengajar">Pengajar</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold small">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="konfirmasi_password" class="form-control"
                               placeholder="Ulangi password" required>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Hapus Pengguna
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Hapus pengguna <strong id="namaHapus" class="text-dark"></strong>? Semua data pendaftaran terkait juga akan terhapus.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="linkHapus" href="#" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash3 me-1"></i>Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id   = this.dataset.id;
        var nama = this.dataset.nama;
        document.getElementById('namaHapus').textContent = nama;
        document.getElementById('linkHapus').href = '<?= site_url('admin/hapus_peserta/') ?>' + id;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    });
});
</script>

<?php $this->load->view('admin/template/footer'); ?>
