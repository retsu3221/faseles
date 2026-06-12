<?php
$this->load->view('admin/template/header', [
    'page_title'  => $page_title,
    'active_menu' => $active_menu,
]);
$this->load->view('admin/template/topbar', [
    'page_title' => $page_title,
]);

$nama_bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];
?>

<!-- ===== FILTER ===== -->
<form method="get" class="card shadow-sm border-0 mb-4 no-print">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-auto">
                <label class="form-label fw-semibold small mb-1">Bulan</label>
                <select name="bulan" class="form-select form-select-sm" style="min-width:130px;">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $bulan_aktif == $i ? 'selected' : '' ?>>
                        <?= $nama_bulan[$i] ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label fw-semibold small mb-1">Tahun</label>
                <select name="tahun" class="form-select form-select-sm" style="min-width:100px;">
                    <?php foreach ($tahun_list as $t): ?>
                    <option value="<?= $t['tahun'] ?>" <?= $tahun_aktif == $t['tahun'] ? 'selected' : '' ?>>
                        <?= $t['tahun'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
            </div>
            <div class="col-auto ms-auto">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="cetakPDF()">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
            </div>
        </div>
    </div>
</form>

<!-- ===== AREA CETAK ===== -->
<div id="areaCetak">

    <!-- Header cetak (hanya muncul saat print) -->
    <div class="print-header text-center mb-4">
        <h4 class="fw-bold mb-1">FASE Les — Rekap Bulanan</h4>
        <h5 class="fw-normal"><?= $nama_bulan[$bulan_aktif] ?> <?= $tahun_aktif ?></h5>
        <hr>
    </div>

    <!-- ===== SUMMARY CARDS ===== -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="card admin-stat-card primary shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="stat-label text-primary">Total Pendaftar</div>
                        <div class="stat-value"><?= $summary['total_daftar'] ?></div>
                    </div>
                    <i class="bi bi-people-fill stat-icon text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card admin-stat-card warning shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="stat-label text-warning">Menunggu Verifikasi</div>
                        <div class="stat-value"><?= $summary['total_pending'] ?></div>
                    </div>
                    <i class="bi bi-hourglass-split stat-icon text-warning"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card admin-stat-card success shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="stat-label text-success">Pembayaran Diterima</div>
                        <div class="stat-value"><?= $summary['total_diterima'] ?></div>
                    </div>
                    <i class="bi bi-check-circle-fill stat-icon text-success"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card admin-stat-card info shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="stat-label text-info">Total Pemasukan</div>
                        <div class="stat-value" style="font-size:1.1rem;">
                            Rp <?= number_format($summary['total_pemasukan'], 0, ',', '.') ?>
                        </div>
                    </div>
                    <i class="bi bi-cash-coin stat-icon text-info"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== TABEL ===== -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <h6 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-table me-2 text-primary"></i>
                    Detail Pendaftaran — <?= $nama_bulan[$bulan_aktif] ?> <?= $tahun_aktif ?>
                </h6>
                <span class="badge bg-primary rounded-pill ms-auto"><?= $summary['total_daftar'] ?> data</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3" style="width:40px;">#</th>
                            <th class="d-none d-md-table-cell">No. Transaksi</th>
                            <th>Nama Peserta</th>
                            <th>Paket</th>
                            <th>Tgl Daftar</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $i => $r):
                            $stMap = [
                                'pending'      => ['bg-warning text-dark', 'Pending'],
                                'diterima'     => ['bg-success',           'Diterima'],
                                'ditolak'      => ['bg-danger',            'Ditolak'],
                                'kadaluarsa'   => ['bg-secondary',         'Kadaluarsa'],
                                'belum_upload' => ['bg-dark bg-opacity-75','Belum Upload'],
                            ];
                            [$stCls, $stLabel] = $stMap[$r['status_verifikasi']] ?? ['bg-secondary','—'];
                            $tingkat = ($r['tingkat'] ?? '-') . ' · ' . ($r['tipe_kelas'] ?? '-');
                        ?>
                        <tr>
                            <td class="ps-3 text-muted small"><?= $i + 1 ?></td>
                            <td class="small text-muted d-none d-md-table-cell"><?= htmlspecialchars($r['no_transaksi'] ?? '-') ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                            <td class="small"><?= htmlspecialchars($tingkat) ?></td>
                            <td class="small text-muted text-nowrap"><?= date('d M Y', strtotime($r['tanggal_daftar'])) ?></td>
                            <td><span class="badge <?= $stCls ?>"><?= $stLabel ?></span></td>
                            <td class="text-end pe-3 fw-semibold text-nowrap <?= $r['status_verifikasi'] === 'diterima' ? 'text-success' : 'text-muted' ?>">
                                <?= $r['status_verifikasi'] === 'diterima'
                                    ? 'Rp ' . number_format($r['harga'], 0, ',', '.')
                                    : '—' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <!-- Baris total -->
                        <tr class="table-light fw-bold">
                            <td colspan="3" class="ps-3 text-end d-md-none">Total Pemasukan</td>
                            <td colspan="6" class="ps-3 text-end d-none d-md-table-cell">Total Pemasukan</td>
                            <td class="text-end pe-3 text-success text-nowrap">
                                Rp <?= number_format($summary['total_pemasukan'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                Tidak ada pendaftaran pada <?= $nama_bulan[$bulan_aktif] ?> <?= $tahun_aktif ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer cetak -->
    <div class="print-footer mt-4 text-end small text-muted">
        Dicetak pada: <?= date('d F Y, H:i') ?> WIB
    </div>

</div>
<!-- End areaCetak -->

<style>
.print-header { display: none; }
.print-footer  { display: none; }

@media print {
    .no-print, #wrapper > .sidebar, .topbar, .scroll-to-top { display: none !important; }
    #content-wrapper { margin: 0 !important; }
    #content { padding: 0 !important; }
    .print-header { display: block !important; }
    .print-footer  { display: block !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
    .admin-stat-card { break-inside: avoid; }
    @page { size: A4 portrait; margin: 15mm; }
}
</style>

<script>
function cetakPDF() {
    window.print();
}
</script>

<?php $this->load->view('admin/template/footer'); ?>
