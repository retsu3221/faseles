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
                <i class="bi bi-box-seam-fill me-2 text-primary"></i>Data Paket
            </h6>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
                <i class="bi bi-plus-lg me-1"></i>Tambah Paket
            </button>
            <span class="badge bg-primary rounded-pill ms-auto"><?= count($paket) ?> paket</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width:40px;">#</th>
                        <th>Tingkat</th>
                        <th>Tipe</th>
                        <th class="text-center">Durasi</th>
                        <th class="text-center d-none d-md-table-cell">Pertemuan</th>
                        <th>Harga</th>
                        <th class="text-center d-none d-md-table-cell">Status</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($paket)): ?>
                    <?php foreach ($paket as $i => $p): ?>
                    <?php
                    $tingkatMap = [
                        'TK'  => ['bg-danger bg-opacity-10 text-danger',   'TK & SD'],
                        'SMP' => ['bg-success bg-opacity-10 text-success',  'SMP / MTs'],
                        'SMA' => ['bg-primary bg-opacity-10 text-primary',  'SMA / SMK'],
                    ];
                    [$tCls, $tLabel] = $tingkatMap[$p['tingkat']] ?? ['bg-secondary bg-opacity-10 text-secondary', $p['tingkat']];
                    $tipeCls = $p['tipe_kelas'] === 'Privat' ? 'bg-primary text-white' : 'bg-success text-white';
                    ?>
                    <tr>
                        <td class="ps-3 text-muted small"><?= $i + 1 ?></td>
                        <td>
                            <span class="badge <?= $tCls ?> fw-semibold px-2 py-1"><?= $tLabel ?></span>
                        </td>
                        <td>
                            <span class="badge <?= $tipeCls ?> fw-semibold px-2 py-1"><?= $p['tipe_kelas'] ?></span>
                        </td>
                        <td class="text-center fw-semibold small"><?= $p['durasi_menit'] ?> mnt</td>
                        <td class="text-center fw-semibold small d-none d-md-table-cell"><?= $p['jumlah_pertemuan'] ?>x</td>
                        <td class="fw-bold text-dark small text-nowrap">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                        <td class="text-center d-none d-md-table-cell">
                            <?php if ($p['is_aktif']): ?>
                                <span class="badge bg-success-subtle text-success fw-semibold">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center pe-3 text-nowrap">
                            <!-- Tombol Detail -->
                            <button type="button" class="btn btn-sm btn-outline-info me-1 btn-detail"
                                    title="Detail"
                                    data-id="<?= $p['id'] ?>"
                                    data-tingkat="<?= $p['tingkat'] ?>"
                                    data-tingkat-label="<?= $tLabel ?>"
                                    data-tipe="<?= $p['tipe_kelas'] ?>"
                                    data-durasi="<?= $p['durasi_menit'] ?>"
                                    data-pertemuan="<?= $p['jumlah_pertemuan'] ?>"
                                    data-harga="<?= number_format($p['harga'], 0, ',', '.') ?>"
                                    data-aktif="<?= $p['is_aktif'] ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                            <!-- Tombol Edit -->
                            <button type="button" class="btn btn-sm btn-outline-warning me-1 btn-edit"
                                    title="Edit"
                                    data-id="<?= $p['id'] ?>"
                                    data-tingkat="<?= $p['tingkat'] ?>"
                                    data-tipe="<?= $p['tipe_kelas'] ?>"
                                    data-durasi="<?= $p['durasi_menit'] ?>"
                                    data-pertemuan="<?= $p['jumlah_pertemuan'] ?>"
                                    data-harga="<?= $p['harga'] ?>"
                                    data-aktif="<?= $p['is_aktif'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <!-- Tombol Hapus -->
                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-paket"
                                    title="Hapus"
                                    data-id="<?= $p['id'] ?>"
                                    data-label="<?= $tLabel ?> - <?= $p['tipe_kelas'] ?> <?= $p['durasi_menit'] ?>mnt">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada data paket.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== MODAL DETAIL ===== -->
<div class="modal fade" id="modalDetailPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-eye me-1 text-info"></i>Detail Paket
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless mb-0 small">
                    <tr>
                        <td class="text-muted fw-semibold" style="width:40%">ID Paket</td>
                        <td id="d_id" class="fw-bold"></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tingkat</td>
                        <td id="d_tingkat"></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tipe Kelas</td>
                        <td id="d_tipe"></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Durasi</td>
                        <td id="d_durasi"></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jumlah Pertemuan</td>
                        <td id="d_pertemuan"></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Harga</td>
                        <td id="d_harga" class="fw-bold text-dark"></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Status</td>
                        <td id="d_aktif"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL TAMBAH ===== -->
<div class="modal fade" id="modalTambahPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-1 text-primary"></i>Tambah Paket Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/tambah_paket') ?>" method="post">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="TK">TK & SD</option>
                                <option value="SMP">SMP / MTs</option>
                                <option value="SMA">SMA / SMK</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Tipe Kelas <span class="text-danger">*</span></label>
                            <select name="tipe_kelas" class="form-select" required>
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="Privat">Privat</option>
                                <option value="Kelompok">Kelompok</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Durasi (menit) <span class="text-danger">*</span></label>
                            <input type="number" name="durasi_menit" class="form-control" min="1" placeholder="cth: 60" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Jumlah Pertemuan <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_pertemuan" class="form-control" min="1" value="8" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" min="0" placeholder="cth: 480000" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Status</label>
                            <select name="is_aktif" class="form-select">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
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

<!-- ===== MODAL EDIT ===== -->
<div class="modal fade" id="modalEditPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-pencil me-1 text-warning"></i>Edit Paket
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPaket" action="" method="post">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                                <option value="TK">TK & SD</option>
                                <option value="SMP">SMP / MTs</option>
                                <option value="SMA">SMA / SMK</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Tipe Kelas <span class="text-danger">*</span></label>
                            <select name="tipe_kelas" class="form-select" required>
                                <option value="Privat">Privat</option>
                                <option value="Kelompok">Kelompok</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Durasi (menit) <span class="text-danger">*</span></label>
                            <input type="number" name="durasi_menit" class="form-control" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Jumlah Pertemuan <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_pertemuan" class="form-control" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" min="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Status</label>
                            <select name="is_aktif" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
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

<!-- ===== MODAL HAPUS ===== -->
<div class="modal fade" id="modalHapusPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Hapus Paket
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Hapus paket <strong id="labelHapusPaket" class="text-dark"></strong>? Tindakan ini tidak bisa dibatalkan.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="linkHapusPaket" href="#" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash3 me-1"></i>Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// --- Detail ---
document.querySelectorAll('.btn-detail').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        document.getElementById('d_id').textContent        = '#' + d.id;
        document.getElementById('d_tingkat').innerHTML     = d.tingkatLabel;
        document.getElementById('d_tipe').textContent      = d.tipe;
        document.getElementById('d_durasi').textContent    = d.durasi + ' menit';
        document.getElementById('d_pertemuan').textContent = d.pertemuan + 'x pertemuan';
        document.getElementById('d_harga').textContent     = 'Rp ' + d.harga;
        document.getElementById('d_aktif').innerHTML       = d.aktif == 1
            ? '<span class="badge bg-success-subtle text-success">Aktif</span>'
            : '<span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>';
        new bootstrap.Modal(document.getElementById('modalDetailPaket')).show();
    });
});

// --- Edit ---
document.querySelectorAll('.btn-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        var form = document.getElementById('formEditPaket');
        form.action = '<?= site_url('admin/update_paket/') ?>' + d.id;
        form.querySelector('[name=tingkat]').value        = d.tingkat;
        form.querySelector('[name=tipe_kelas]').value     = d.tipe;
        form.querySelector('[name=durasi_menit]').value   = d.durasi;
        form.querySelector('[name=jumlah_pertemuan]').value = d.pertemuan;
        form.querySelector('[name=harga]').value          = d.harga;
        form.querySelector('[name=is_aktif]').value       = d.aktif;
        new bootstrap.Modal(document.getElementById('modalEditPaket')).show();
    });
});

// --- Hapus ---
document.querySelectorAll('.btn-hapus-paket').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('labelHapusPaket').textContent = this.dataset.label;
        document.getElementById('linkHapusPaket').href = '<?= site_url('admin/hapus_paket/') ?>' + this.dataset.id;
        new bootstrap.Modal(document.getElementById('modalHapusPaket')).show();
    });
});
</script>

<?php $this->load->view('admin/template/footer'); ?>
