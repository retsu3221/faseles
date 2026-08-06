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
                <i class="bi bi-person-badge-fill me-2 text-primary"></i>Data Pengajar
            </h6>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i>Tambah Pengajar
            </button>
            <div class="input-group input-group-sm" style="max-width:220px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="searchPengajar" class="form-control bg-light border-start-0"
                       placeholder="Cari nama, tingkat...">
            </div>
            <span class="badge bg-primary rounded-pill ms-auto" id="jumlahPengajar"><?= count($pengajar) ?> pengajar</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width:40px;">#</th>
                        <th>Nama Lengkap</th>
                        <th class="d-none d-md-table-cell">Tingkat Diajar</th>
                        <th class="d-none d-md-table-cell">No. WhatsApp</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($pengajar)): ?>
                    <?php foreach ($pengajar as $i => $p): ?>
                    <tr class="pengajar-row" data-search="<?= strtolower(htmlspecialchars(
                        $p['nama_lengkap'] . ' ' . ($p['username'] ?? '') . ' ' . ($p['tingkat_diajar'] ?? '') . ' ' . ($p['no_wa'] ?? '')
                    )) ?>">
                        <td class="ps-3 text-muted small"><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                                     style="width:2rem;height:2rem;flex-shrink:0;">
                                    <i class="bi bi-person-fill text-success" style="font-size:.8rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($p['nama_lengkap']) ?></div>
                                    <?php if (!empty($p['username'])): ?>
                                    <div class="text-muted" style="font-size:.72rem;">@<?= htmlspecialchars($p['username']) ?></div>
                                    <?php else: ?>
                                    <div class="text-warning" style="font-size:.72rem;"><i class="bi bi-exclamation-circle me-1"></i>belum ada akun login</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <?php if (!empty($p['tingkat_diajar'])): ?>
                                <?php foreach (explode(',', $p['tingkat_diajar']) as $t): ?>
                                    <span class="badge bg-primary bg-opacity-75 me-1"><?= htmlspecialchars(trim($t)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small d-none d-md-table-cell">
                            <?= $p['no_wa'] ? '+62' . htmlspecialchars($p['no_wa']) : '—' ?>
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button"
                                        class="btn btn-sm btn-outline-warning btn-edit"
                                        data-id="<?= $p['id'] ?>"
                                        data-nama="<?= htmlspecialchars($p['nama_lengkap']) ?>"
                                        data-wa="<?= htmlspecialchars($p['no_wa'] ?: '') ?>"
                                        data-tingkat="<?= htmlspecialchars($p['tingkat_diajar'] ?: '') ?>"
                                        data-username="<?= htmlspecialchars($p['username'] ?: '') ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-hapus"
                                        data-id="<?= $p['id'] ?>"
                                        data-nama="<?= htmlspecialchars($p['nama_lengkap']) ?>">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-person-badge fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada data pengajar.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-1 text-primary"></i>Tambah Pengajar
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/tambah_pengajar') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama pengajar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small d-block">Tingkat yang Diajar</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach (['TK','SD','SMP','SMA'] as $lvl): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="tingkat_diajar[]" value="<?= $lvl ?>"
                                       id="tambah_<?= $lvl ?>">
                                <label class="form-check-label small" for="tambah_<?= $lvl ?>"><?= $lvl ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">No. WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted">+62</span>
                            <input type="text" name="no_wa" class="form-control" placeholder="8xxxxxxxxxx">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="small fw-bold text-secondary mb-2">
                        <i class="bi bi-key me-1"></i>Akun Login Portal Pengajar <span class="fw-normal text-muted">(opsional)</span>
                    </div>
                    <div class="row g-3 mb-1">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username pengajar" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" autocomplete="new-password">
                        </div>
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

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-1 text-warning"></i>Edit Pengajar
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small d-block">Tingkat yang Diajar</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach (['TK','SD','SMP','SMA'] as $lvl): ?>
                            <div class="form-check">
                                <input class="form-check-input edit-tingkat" type="checkbox"
                                       name="tingkat_diajar[]" value="<?= $lvl ?>"
                                       id="edit_<?= $lvl ?>">
                                <label class="form-check-label small" for="edit_<?= $lvl ?>"><?= $lvl ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">No. WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted">+62</span>
                            <input type="text" name="no_wa" id="editWa" class="form-control" placeholder="8xxxxxxxxxx">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="small fw-bold text-secondary mb-2">
                        <i class="bi bi-key me-1"></i>Akun Login Portal Pengajar <span class="fw-normal text-muted">(opsional)</span>
                    </div>
                    <div class="row g-3 mb-1">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Username</label>
                            <input type="text" name="username" id="editUsername" class="form-control" placeholder="username pengajar" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tetap" autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-semibold">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Hapus Pengajar
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Hapus pengajar <strong id="namaHapus" class="text-dark"></strong>?
                Jadwal yang terhubung juga akan terhapus.
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
document.getElementById('searchPengajar').addEventListener('input', function () {
    var q    = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.pengajar-row');
    var shown = 0;
    rows.forEach(function (row) {
        var match = !q || row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    document.getElementById('jumlahPengajar').textContent = shown + ' pengajar';
});

document.querySelectorAll('.btn-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        document.getElementById('formEdit').action = '<?= site_url('admin/update_pengajar/') ?>' + d.id;
        document.getElementById('editNama').value     = d.nama;
        document.getElementById('editWa').value       = d.wa;
        document.getElementById('editUsername').value = d.username;

        var checked = d.tingkat ? d.tingkat.split(',') : [];
        document.querySelectorAll('.edit-tingkat').forEach(function (cb) {
            cb.checked = checked.indexOf(cb.value) !== -1;
        });

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    });
});

document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('namaHapus').textContent = this.dataset.nama;
        document.getElementById('linkHapus').href = '<?= site_url('admin/hapus_pengajar/') ?>' + this.dataset.id;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    });
});
</script>

<?php $this->load->view('admin/template/footer'); ?>
