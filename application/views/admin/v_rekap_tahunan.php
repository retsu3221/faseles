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
$s = $summary;
?>

<!-- ===== FILTER ===== -->
<form method="get" class="card shadow-sm border-0 mb-4 no-print">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-auto">
                <label class="form-label fw-semibold small mb-1">Tahun</label>
                <select name="tahun" class="form-select form-select-sm" style="min-width:110px;">
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
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.print()">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
            </div>
        </div>
    </div>
</form>

<!-- ===== AREA CETAK ===== -->
<div id="areaCetak">

    <!-- Header cetak -->
    <div class="print-header text-center mb-4">
        <h4 class="fw-bold mb-1">FASE Les — Rekap Tahunan</h4>
        <h5 class="fw-normal">Tahun <?= $tahun_aktif ?></h5>
        <hr>
    </div>

    <!-- ===== SUMMARY CARDS ===== -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="card admin-stat-card primary shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="stat-label text-primary">Total Pendaftar</div>
                        <div class="stat-value"><?= $s['total_daftar'] ?? 0 ?></div>
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
                        <div class="stat-value"><?= $s['total_pending'] ?? 0 ?></div>
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
                        <div class="stat-value"><?= $s['total_diterima'] ?? 0 ?></div>
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
                        <div class="stat-value" style="font-size:1.05rem;">
                            Rp <?= number_format($s['total_pemasukan'] ?? 0, 0, ',', '.') ?>
                        </div>
                    </div>
                    <i class="bi bi-cash-coin stat-icon text-info"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== TABEL PER BULAN ===== -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold text-secondary">
                <i class="bi bi-table me-2 text-primary"></i>
                Rincian Per Bulan — Tahun <?= $tahun_aktif ?>
            </h6>
            <span class="badge bg-primary rounded-pill"><?= $s['total_daftar'] ?? 0 ?> total pendaftar</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Bulan</th>
                            <th class="text-center">Total Daftar</th>
                            <th class="text-center">Diterima</th>
                            <th class="text-center">Pending</th>
                            <th class="text-center">Ditolak / Kadaluarsa</th>
                            <th class="text-end pe-4">Pemasukan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $max_daftar = 0;
                    foreach ($bulanan_map as $b) {
                        if ((int)$b['total_daftar'] > $max_daftar) $max_daftar = (int)$b['total_daftar'];
                    }
                    $max_daftar = $max_daftar ?: 1;

                    $grand_pemasukan = 0;
                    for ($i = 1; $i <= 12; $i++):
                        $b = $bulanan_map[$i] ?? null;
                        $total    = $b ? (int)$b['total_daftar']    : 0;
                        $diterima = $b ? (int)$b['total_diterima']  : 0;
                        $pending  = $b ? (int)$b['total_pending']   : 0;
                        $ditolak  = $b ? (int)$b['total_ditolak']   : 0;
                        $masuk    = $b ? (int)$b['total_pemasukan'] : 0;
                        $grand_pemasukan += $masuk;
                        $pct = round(($total / $max_daftar) * 100);
                    ?>
                    <tr class="<?= $total === 0 ? 'text-muted' : '' ?>">
                        <td class="ps-4 fw-semibold"><?= $nama_bulan[$i] ?></td>
                        <td class="text-center">
                            <?= $total > 0 ? '<span class="fw-semibold">' . $total . '</span>' : '<span class="text-muted">—</span>' ?>
                        </td>
                        <td class="text-center">
                            <?php if ($diterima > 0): ?>
                            <span class="badge bg-success-subtle text-success fw-semibold"><?= $diterima ?></span>
                            <?php else: ?><span class="text-muted small">—</span><?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($pending > 0): ?>
                            <span class="badge bg-warning-subtle text-warning fw-semibold"><?= $pending ?></span>
                            <?php else: ?><span class="text-muted small">—</span><?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($ditolak > 0): ?>
                            <span class="badge bg-danger-subtle text-danger fw-semibold"><?= $ditolak ?></span>
                            <?php else: ?><span class="text-muted small">—</span><?php endif; ?>
                        </td>
                        <td class="text-end pe-4 fw-semibold <?= $masuk > 0 ? 'text-success' : 'text-muted' ?>">
                            <?= $masuk > 0 ? 'Rp ' . number_format($masuk, 0, ',', '.') : '—' ?>
                        </td>
                    </tr>
                    <?php endfor; ?>

                    <!-- Baris total -->
                    <tr class="table-light fw-bold border-top">
                        <td class="ps-4">Total</td>
                        <td class="text-center"><?= $s['total_daftar'] ?? 0 ?></td>
                        <td class="text-center text-success"><?= $s['total_diterima'] ?? 0 ?></td>
                        <td class="text-center text-warning"><?= $s['total_pending'] ?? 0 ?></td>
                        <td class="text-center text-danger"><?= $s['total_ditolak'] ?? 0 ?></td>
                        <td class="text-end pe-4 text-success">
                            Rp <?= number_format($s['total_pemasukan'] ?? 0, 0, ',', '.') ?>
                        </td>
                    </tr>
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

<style>
.print-header { display: none; }
.print-footer  { display: none; }

@media print {
    @page { size: A4 portrait; margin: 8mm; }

    .no-print, #wrapper > .sidebar, .topbar, .scroll-to-top { display: none !important; }
    #content-wrapper { margin: 0 !important; }
    #content { padding: 0 !important; }

    /* Skala konten agar memenuhi 1 halaman */
    #areaCetak {
        width: 100%;
        transform-origin: top left;
    }

    .print-header { display: block !important; }
    .print-footer  { display: block !important; }

    /* Cards lebih compact */
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; break-inside: avoid; }
    .row.g-3 { --bs-gutter-y: 0.4rem; --bs-gutter-x: 0.4rem; break-inside: avoid; }
    .card-body.py-3 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
    .stat-value { font-size: 1.4rem !important; }
    .stat-label { font-size: 0.72rem !important; }
    .stat-icon  { font-size: 1.6rem !important; }

    /* Header cetak compact */
    .print-header h4 { font-size: 1rem !important; margin-bottom: 0.1rem !important; }
    .print-header h5 { font-size: 0.85rem !important; }
    .print-header hr { margin: 0.3rem 0 !important; }

    /* Tabel compact */
    table td, table th { padding: 5px 10px !important; font-size: 12px !important; }
    .card-header.py-3 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
    .card-header h6 { font-size: 0.8rem !important; }
    .mb-4 { margin-bottom: 0.6rem !important; }
    .progress { display: none; }

    .print-footer { margin-top: 6px !important; font-size: 10px !important; }
}
</style>

<?php $this->load->view('admin/template/footer'); ?>
