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
                <i class="bi bi-calendar2-week-fill me-2 text-primary"></i>Data Jadwal
            </h6>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i>Tambah Jadwal
            </button>
            <div class="input-group input-group-sm" style="max-width:220px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="searchJadwal" class="form-control bg-light border-start-0"
                       placeholder="Cari nama, pengajar, hari...">
            </div>
            <span class="badge bg-primary rounded-pill ms-auto" id="jumlahJadwal"><?= count($jadwal) ?> jadwal</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width:40px;">#</th>
                        <th>Siswa</th>
                        <th class="d-none d-lg-table-cell">Paket</th>
                        <th>Pengajar</th>
                        <th class="d-none d-md-table-cell">Jadwal</th>
                        <th class="text-center">Progress</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($jadwal)): ?>
                    <?php foreach ($jadwal as $i => $j): ?>
                    <?php
                        $pct    = $j['jumlah_pertemuan'] > 0
                                  ? round($j['pertemuan_selesai'] / $j['jumlah_pertemuan'] * 100)
                                  : 0;
                        $statusCfg = [
                            'aktif'       => ['bg-success', 'Aktif'],
                            'selesai'     => ['bg-secondary', 'Selesai'],
                            'dibatalkan'  => ['bg-danger', 'Dibatalkan'],
                        ];
                        [$sBadge, $sLabel] = $statusCfg[$j['status']] ?? ['bg-secondary', $j['status']];
                    ?>
                    <tr class="jadwal-row" data-search="<?= strtolower(htmlspecialchars(
                        ($j['nama_siswa'] ?? '') . ' ' . ($j['username'] ?? '') . ' ' .
                        ($j['nama_pengajar'] ?? '') . ' ' . ($j['nama_paket'] ?? '') . ' ' .
                        ($j['hari'] ?? '') . ' ' . $sLabel
                    )) ?>">
                        <td class="ps-3 text-muted small"><?= $i + 1 ?></td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($j['nama_siswa'] ?: '—') ?></div>
                            <div class="text-muted" style="font-size:.75rem;">@<?= htmlspecialchars($j['username']) ?></div>
                        </td>
                        <td class="small text-muted d-none d-lg-table-cell">
                            <?= htmlspecialchars($j['nama_paket']) ?>
                        </td>
                        <td>
                            <div class="fw-semibold small"><?= htmlspecialchars($j['nama_pengajar']) ?></div>
                            <?php if ($j['tingkat_diajar']): ?>
                            <div class="text-muted" style="font-size:.72rem;"><?= htmlspecialchars($j['tingkat_diajar']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted d-none d-md-table-cell">
                            <?= htmlspecialchars($j['hari']) ?><br>
                            <?= date('H:i', strtotime($j['jam_mulai'])) ?> – <?= date('H:i', strtotime($j['jam_selesai'])) ?>
                        </td>
                        <td class="text-center" style="min-width:110px;">
                            <div class="fw-bold small mb-1">
                                <?= $j['pertemuan_selesai'] ?> / <?= $j['jumlah_pertemuan'] ?>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar bg-primary" style="width:<?= $pct ?>%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $sBadge ?> px-2 py-1"><?= $sLabel ?></span>
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <?php if ($j['status'] === 'aktif' && $j['pertemuan_selesai'] < $j['jumlah_pertemuan']): ?>
                                <a href="<?= site_url('admin/selesai_pertemuan/' . $j['id']) ?>"
                                   class="btn btn-sm btn-outline-success btn-selesai"
                                   title="Tandai 1 Pertemuan Selesai"
                                   data-nama="<?= htmlspecialchars($j['nama_siswa'] ?: $j['username']) ?>"
                                   data-ke="<?= $j['pertemuan_selesai'] + 1 ?>"
                                   data-total="<?= $j['jumlah_pertemuan'] ?>">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                                <?php endif; ?>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-hapus"
                                        data-id="<?= $j['id'] ?>"
                                        data-nama="<?= htmlspecialchars($j['nama_siswa'] ?: $j['username']) ?>">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-calendar2-x fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada jadwal.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-calendar2-plus me-1 text-primary"></i>Tambah Jadwal
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/tambah_jadwal') ?>" method="post">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Siswa (Pendaftaran Lunas) <span class="text-danger">*</span></label>
                        <select name="pendaftaran_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Siswa --</option>
                            <?php foreach ($pendaftaran_lunas as $pl): ?>
                            <option value="<?= $pl['id'] ?>">
                                <?= htmlspecialchars($pl['nama_lengkap'] ?: $pl['username']) ?>
                                — <?= htmlspecialchars($pl['nama_paket']) ?>
                                <?php if ($pl['jadwal_hari']): ?>
                                (Pref: <?= $pl['jadwal_hari'] ?> <?= $pl['jadwal_jam'] ? date('H:i', strtotime($pl['jadwal_jam'])) : '' ?>)
                                <?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($pendaftaran_lunas)): ?>
                        <div class="form-text text-warning">Belum ada pendaftaran yang lunas.</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pengajar <span class="text-danger">*</span></label>
                        <select name="pengajar_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Pengajar --</option>
                            <?php foreach ($pengajar as $pg): ?>
                            <option value="<?= $pg['id'] ?>">
                                <?= htmlspecialchars($pg['nama_lengkap']) ?>
                                <?= $pg['tingkat_diajar'] ? '— ' . htmlspecialchars($pg['tingkat_diajar']) : '' ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Hari <span class="text-danger">*</span></label>
                            <select name="hari" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Hari --</option>
                                <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
                                <option value="<?= $h ?>"><?= $h ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Jumlah Pertemuan <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_pertemuan" class="form-control" value="8" min="1" max="100" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold small">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
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

<!-- Modal Konfirmasi +1 Pertemuan -->
<div class="modal fade" id="modalSelesai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-success">
                    <i class="bi bi-check-circle me-1"></i>Tandai Selesai
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Tandai pertemuan ke-<strong id="pertemuanKe" class="text-dark"></strong>
                dari <strong id="totalPertemuan" class="text-dark"></strong> untuk
                <strong id="namaSelesai" class="text-dark"></strong> sebagai selesai?
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="linkSelesai" href="#" class="btn btn-sm btn-success">
                    <i class="bi bi-check-lg me-1"></i>Ya, Selesai
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Hapus Jadwal
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Hapus jadwal untuk <strong id="namaHapus" class="text-dark"></strong>?
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
document.getElementById('searchJadwal').addEventListener('input', function () {
    var q    = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.jadwal-row');
    var shown = 0;
    rows.forEach(function (row) {
        var match = !q || row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    document.getElementById('jumlahJadwal').textContent = shown + ' jadwal';
});

document.querySelectorAll('.btn-selesai').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('namaSelesai').textContent   = this.dataset.nama;
        document.getElementById('pertemuanKe').textContent   = this.dataset.ke;
        document.getElementById('totalPertemuan').textContent = this.dataset.total;
        document.getElementById('linkSelesai').href           = this.href;
        new bootstrap.Modal(document.getElementById('modalSelesai')).show();
    });
});

document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('namaHapus').textContent = this.dataset.nama;
        document.getElementById('linkHapus').href = '<?= site_url('admin/hapus_jadwal/') ?>' + this.dataset.id;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    });
});
</script>

<?php $this->load->view('admin/template/footer'); ?>
