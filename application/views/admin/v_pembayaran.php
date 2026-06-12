<?php
$this->load->view('admin/template/header', [
    'page_title'  => $page_title,
    'active_menu' => $active_menu,
]);
$this->load->view('admin/template/topbar', [
    'page_title' => $page_title,
]);
?>

<!-- Filter Status -->
<div class="d-flex gap-2 mb-3 flex-wrap">
    <?php
    $filter = $_GET['status'] ?? 'semua';
    $filters = [
        'semua'       => ['secondary', 'Semua'],
        'belum_upload'=> ['dark',      'Belum Upload'],
        'pending'     => ['warning',   'Pending'],
        'diterima'    => ['success',   'Diterima'],
        'ditolak'     => ['danger',    'Ditolak'],
        'kadaluarsa'  => ['secondary', 'Kadaluarsa'],
    ];
    foreach ($filters as $key => [$color, $label]):
        $active = $filter === $key ? '' : 'outline-';
    ?>
    <a href="?status=<?= $key ?>"
       class="btn btn-sm btn-<?= $active . $color ?>">
        <?= $label ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <h6 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-credit-card-fill me-2 text-primary"></i>Data Pembayaran
        </h6>
        <?php
        $total = count($pendaftaran);
        $filtered = array_filter($pendaftaran, function($r) use ($filter) {
            if ($filter === 'semua') return true;
            if ($filter === 'belum_upload') return empty($r['bp_id']);
            return !empty($r['bp_id']) && $r['status_verifikasi'] === $filter;
        });
        ?>
        <span class="badge bg-primary rounded-pill"><?= count($filtered) ?> data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width:40px;">#</th>
                        <th class="d-none d-md-table-cell">No. Transaksi</th>
                        <th>Nama Peserta</th>
                        <th class="d-none d-md-table-cell">Paket</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th class="d-none d-md-table-cell">Tgl Daftar</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 0;
                foreach ($pendaftaran as $row):
                    // Terapkan filter
                    if ($filter === 'belum_upload' && !empty($row['bp_id'])) continue;
                    if ($filter === 'pending'    && (empty($row['bp_id']) || $row['status_verifikasi'] !== 'pending'))    continue;
                    if ($filter === 'diterima'   && (empty($row['bp_id']) || $row['status_verifikasi'] !== 'diterima'))   continue;
                    if ($filter === 'ditolak'    && (empty($row['bp_id']) || $row['status_verifikasi'] !== 'ditolak'))    continue;
                    if ($filter === 'kadaluarsa' && (empty($row['bp_id']) || $row['status_verifikasi'] !== 'kadaluarsa')) continue;
                    $no++;

                    // Badge status
                    if (empty($row['bp_id'])) {
                        $stCls   = 'bg-dark bg-opacity-10 text-dark';
                        $stLabel = 'Belum Upload';
                        $stIcon  = 'bi-dash-circle';
                    } else {
                        $stMap = [
                            'pending'    => ['bg-warning bg-opacity-10 text-warning', 'Pending',    'bi-hourglass-split'],
                            'diterima'   => ['bg-success bg-opacity-10 text-success', 'Diterima',   'bi-check-circle-fill'],
                            'ditolak'    => ['bg-danger bg-opacity-10 text-danger',   'Ditolak',    'bi-x-circle-fill'],
                            'kadaluarsa' => ['bg-secondary bg-opacity-10 text-secondary', 'Kadaluarsa', 'bi-clock-history'],
                        ];
                        [$stCls, $stLabel, $stIcon] = $stMap[$row['status_verifikasi']] ?? ['bg-secondary bg-opacity-10 text-secondary', $row['status_verifikasi'], 'bi-question-circle'];
                    }

                    $tingkatMap = ['TK' => 'TK & SD', 'SMP' => 'SMP/MTs', 'SMA' => 'SMA/SMK'];
                    $tingkat    = ($tingkatMap[$row['tingkat']] ?? $row['tingkat']) . ' · ' . $row['tipe_kelas'];
                ?>
                <tr>
                    <td class="ps-3 text-muted small"><?= $no ?></td>
                    <td class="small fw-semibold text-muted d-none d-md-table-cell"><?= htmlspecialchars($row['no_transaksi'] ?? '-') ?></td>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($row['username'] ?? '') ?></div>
                        <div class="small text-muted d-md-none"><?= htmlspecialchars($tingkat) ?></div>
                    </td>
                    <td class="small d-none d-md-table-cell"><?= htmlspecialchars($tingkat) ?></td>
                    <td class="fw-semibold small text-nowrap">Rp <?= number_format($row['harga'] ?? 0, 0, ',', '.') ?></td>
                    <td>
                        <span class="badge <?= $stCls ?> fw-semibold px-2 py-1">
                            <i class="bi <?= $stIcon ?> me-1"></i><?= $stLabel ?>
                        </span>
                    </td>
                    <td class="text-muted small d-none d-md-table-cell"><?= date('d M Y', strtotime($row['tanggal_daftar'])) ?></td>
                    <td class="text-center pe-3 text-nowrap">
                        <?php if (!empty($row['bp_id'])): ?>
                        <button type="button"
                                class="btn btn-sm btn-outline-info btn-lihat-bukti"
                                title="Lihat & Verifikasi"
                                data-bp-id="<?= $row['bp_id'] ?>"
                                data-nama="<?= htmlspecialchars($row['nama_lengkap']) ?>"
                                data-pengirim="<?= htmlspecialchars($row['nama_pengirim'] ?? '') ?>"
                                data-jumlah="<?= number_format($row['jumlah_transfer'] ?? 0, 0, ',', '.') ?>"
                                data-tgl-transfer="<?= $row['tanggal_transfer'] ?? '' ?>"
                                data-catatan="<?= htmlspecialchars($row['catatan'] ?? '') ?>"
                                data-catatan-admin="<?= htmlspecialchars($row['catatan_admin'] ?? '') ?>"
                                data-status="<?= $row['status_verifikasi'] ?>"
                                data-file="<?= base_url('assets/img/bukti/' . $row['file_bukti']) ?>"
                                data-uploaded="<?= $row['uploaded_at'] ?? '' ?>">
                            <i class="bi bi-eye"></i>
                        </button>
                        <?php else: ?>
                        <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if ($no === 0): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                        Tidak ada data untuk filter ini.
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== MODAL LIHAT & VERIFIKASI BUKTI ===== -->
<div class="modal fade" id="modalBukti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-receipt me-1 text-primary"></i>Bukti Pembayaran
                    — <span id="m_nama" class="text-primary"></span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">

                    <!-- Preview gambar -->
                    <div class="col-md-6">
                        <a id="m_file_link" href="#" target="_blank">
                            <img id="m_file_img" src="" alt="Bukti Pembayaran"
                                 class="img-fluid rounded-3 shadow-sm w-100"
                                 style="max-height:320px;object-fit:contain;background:#f8f9fa;">
                        </a>
                        <div class="text-center mt-2">
                            <a id="m_file_open" href="#" target="_blank" class="small text-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Buka di tab baru
                            </a>
                        </div>
                    </div>

                    <!-- Detail transfer -->
                    <div class="col-md-6">
                        <table class="table table-borderless small mb-3">
                            <tr>
                                <td class="text-muted fw-semibold ps-0" style="width:45%">Nama Pengirim</td>
                                <td id="m_pengirim" class="fw-bold"></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-0">Jumlah Transfer</td>
                                <td id="m_jumlah" class="fw-bold text-success"></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-0">Tgl Transfer</td>
                                <td id="m_tgl_transfer"></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-0">Diunggah</td>
                                <td id="m_uploaded" class="text-muted"></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-0">Catatan</td>
                                <td id="m_catatan" class="fst-italic text-muted"></td>
                            </tr>
                        </table>

                        <!-- Status badge -->
                        <div class="mb-3">
                            <span class="small text-muted fw-semibold">Status Saat Ini: </span>
                            <span id="m_status_badge"></span>
                        </div>

                        <!-- Form verifikasi -->
                        <form id="formVerifikasi" action="" method="post">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Catatan Admin (opsional)</label>
                                <textarea name="catatan_admin" id="m_catatan_admin"
                                          class="form-control form-control-sm" rows="2"
                                          placeholder="Tulis catatan jika perlu..."></textarea>
                            </div>
                            <input type="hidden" name="status" id="inputStatus">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-success btn-verif" data-status="diterima">
                                    <i class="bi bi-check-lg me-1"></i>Terima
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-verif" data-status="ditolak">
                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary btn-verif" data-status="kadaluarsa">
                                    <i class="bi bi-clock me-1"></i>Kadaluarsa
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-lihat-bukti').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;

        document.getElementById('m_nama').textContent        = d.nama;
        document.getElementById('m_pengirim').textContent    = d.pengirim;
        document.getElementById('m_jumlah').textContent      = 'Rp ' + d.jumlah;
        document.getElementById('m_tgl_transfer').textContent = d.tglTransfer || '-';
        document.getElementById('m_uploaded').textContent    = d.uploaded || '-';
        document.getElementById('m_catatan').textContent     = d.catatan || '-';
        document.getElementById('m_catatan_admin').value     = d.catatanAdmin || '';

        document.getElementById('m_file_img').src    = d.file;
        document.getElementById('m_file_link').href  = d.file;
        document.getElementById('m_file_open').href  = d.file;

        // Status badge
        var stMap = {
            pending:    '<span class="badge bg-warning text-dark">Pending</span>',
            diterima:   '<span class="badge bg-success">Diterima</span>',
            ditolak:    '<span class="badge bg-danger">Ditolak</span>',
            kadaluarsa: '<span class="badge bg-secondary">Kadaluarsa</span>',
        };
        document.getElementById('m_status_badge').innerHTML = stMap[d.status] || d.status;

        // Action form
        var form = document.getElementById('formVerifikasi');
        form.action = '<?= site_url('admin/verifikasi_pembayaran/') ?>' + d.bpId;

        new bootstrap.Modal(document.getElementById('modalBukti')).show();
    });
});

// Submit verifikasi saat klik tombol terima/tolak/kadaluarsa
document.querySelectorAll('.btn-verif').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('inputStatus').value = this.dataset.status;
        document.getElementById('formVerifikasi').submit();
    });
});
</script>

<?php $this->load->view('admin/template/footer'); ?>
