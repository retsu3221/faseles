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
                <i class="bi bi-shield-fill-check me-2 text-primary"></i>Data Admin
            </h6>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                <i class="bi bi-plus-lg me-1"></i>Tambah Admin
            </button>
            <div class="input-group input-group-sm" style="max-width:220px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="searchAdmin" class="form-control bg-light border-start-0"
                       placeholder="Cari nama, username...">
            </div>
            <span class="badge bg-primary rounded-pill ms-auto" id="jumlahAdmin"><?= count($admins) ?> admin</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width:40px;">#</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th class="text-center">Role</th>
                        <th class="d-none d-md-table-cell">Dibuat</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($admins)): ?>
                    <?php foreach ($admins as $i => $a): ?>
                    <?php $isSuper = (int)$a['role'] === 1; ?>
                    <tr class="admin-row" data-search="<?= strtolower(htmlspecialchars(
                        ($a['nama_lengkap'] ?? '') . ' ' . $a['username'] . ' ' .
                        ($isSuper ? 'super admin' : 'admin')
                    )) ?>">
                        <td class="ps-3 text-muted small"><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                     style="width:2rem;height:2rem;flex-shrink:0;">
                                    <i class="bi bi-shield-fill text-primary" style="font-size:.8rem;"></i>
                                </div>
                                <div class="fw-semibold"><?= htmlspecialchars($a['nama_lengkap'] ?: '—') ?></div>
                            </div>
                        </td>
                        <td class="text-muted small">@<?= htmlspecialchars($a['username']) ?></td>
                        <td class="text-center">
                            <?php if ($isSuper): ?>
                                <span class="badge bg-warning text-dark fw-semibold">Super Admin</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold">Admin</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small d-none d-md-table-cell">
                            <?= date('d M Y', strtotime($a['created_at'])) ?>
                        </td>
                        <td class="text-center pe-3 text-nowrap">
                            <button type="button"
                                    class="btn btn-sm btn-outline-warning me-1 btn-edit"
                                    data-id="<?= $a['id'] ?>"
                                    data-nama="<?= htmlspecialchars($a['nama_lengkap'] ?: '') ?>"
                                    data-username="<?= htmlspecialchars($a['username']) ?>"
                                    data-role="<?= $a['role'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if ((int)$a['id'] !== (int)$this->session->userdata('admin_id')): ?>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-hapus"
                                    data-id="<?= $a['id'] ?>"
                                    data-nama="<?= htmlspecialchars($a['nama_lengkap'] ?: $a['username']) ?>">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-shield fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada data admin.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-shield-plus me-1 text-primary"></i>Tambah Admin Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/tambah_admin') ?>" method="post">
                <div class="modal-body">

                    <input type="hidden" name="role" value="2">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control"
                               placeholder="Nama lengkap admin" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" class="form-control"
                                   placeholder="Buat username unik" required>
                        </div>
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

<!-- Modal Edit Admin -->
<div class="modal fade" id="modalEditAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-pencil me-1 text-warning"></i>Edit Admin
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditAdmin" action="" method="post">
                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold small">Role <span class="text-danger">*</span></label>
                            <select name="role" id="edit_role" class="form-select" required>
                                <option value="2">Admin</option>
                                <option value="1">Super Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" id="edit_username" class="form-control" required>
                        </div>
                    </div>

                    <hr>
                    <p class="small text-muted mb-2">Kosongkan password jika tidak ingin menggantinya.</p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold small">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password baru">
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning text-white">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
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
                    <i class="bi bi-exclamation-triangle me-1"></i>Hapus Admin
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Hapus admin <strong id="namaHapus" class="text-dark"></strong>?
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
document.getElementById('searchAdmin').addEventListener('input', function () {
    var q    = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.admin-row');
    var shown = 0;
    rows.forEach(function (row) {
        var match = !q || row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    document.getElementById('jumlahAdmin').textContent = shown + ' admin';
});

document.querySelectorAll('.btn-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        var form = document.getElementById('formEditAdmin');
        form.action = '<?= site_url('admin/update_admin/') ?>' + d.id;
        document.getElementById('edit_nama').value     = d.nama;
        document.getElementById('edit_username').value = d.username;
        document.getElementById('edit_role').value     = d.role;
        form.querySelector('[name=password]').value            = '';
        form.querySelector('[name=konfirmasi_password]').value = '';
        new bootstrap.Modal(document.getElementById('modalEditAdmin')).show();
    });
});

document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('namaHapus').textContent = this.dataset.nama;
        document.getElementById('linkHapus').href = '<?= site_url('admin/hapus_admin/') ?>' + this.dataset.id;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    });
});
</script>

<?php $this->load->view('admin/template/footer'); ?>
