<?php
$this->load->view('admin/template/header', [
    'page_title'  => $page_title,
    'active_menu' => $active_menu,
]);
$this->load->view('admin/template/topbar', [
    'page_title' => $page_title,
]);

$s = $stats;
$bulan_label = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
?>

<!-- ===== STAT CARDS ===== -->
<div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="card admin-stat-card primary shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <div class="stat-label text-primary">Total Pendaftar</div>
                    <div class="stat-value"><?= $s['total_peserta'] ?? 0 ?></div>
                </div>
                <i class="bi bi-people-fill stat-icon text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
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

    <div class="col-xl-3 col-md-6">
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

    <div class="col-xl-3 col-md-6">
        <div class="card admin-stat-card danger shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <div class="stat-label text-danger">Belum Upload</div>
                    <div class="stat-value"><?= $s['total_belum_upload'] ?? 0 ?></div>
                </div>
                <i class="bi bi-cloud-upload stat-icon text-danger"></i>
            </div>
        </div>
    </div>

</div>

<!-- ===== GRAFIK BULANAN + PENDAFTARAN TERBARU ===== -->
<div class="row g-4 mb-4">

    <!-- Grafik -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Rekap Bulanan <?= $tahun_ini ?>
                </h6>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">Tahun <?= $tahun_ini ?></span>
            </div>
            <div class="card-body">
                <canvas id="chartBulanan" height="110"></canvas>
            </div>
        </div>
    </div>

    <!-- Pendaftaran Terbaru -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Terbaru
                </h6>
                <a href="<?= site_url('admin/peserta') ?>" class="small text-primary text-decoration-none">Lihat semua</a>
            </div>
            <div class="card-body p-0" style="overflow-y:auto;max-height:300px;">
                <?php if (!empty($recent)): ?>
                <?php foreach ($recent as $r):
                    $stMap = [
                        'pending'      => ['bg-warning text-dark', 'Pending'],
                        'diterima'     => ['bg-success',           'Lunas'],
                        'ditolak'      => ['bg-danger',            'Ditolak'],
                        'kadaluarsa'   => ['bg-secondary',         'Kadaluarsa'],
                        'belum_upload' => ['bg-dark bg-opacity-75','Belum Upload'],
                    ];
                    [$stCls, $stLabel] = $stMap[$r['status_verifikasi']] ?? ['bg-secondary', '-'];
                    $tingkat = ($r['tingkat'] ?? '-') . ' · ' . ($r['tipe_kelas'] ?? '-');
                ?>
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                    <div>
                        <div class="fw-semibold small"><?= htmlspecialchars($r['nama_lengkap']) ?></div>
                        <div class="text-muted" style="font-size:.73rem;"><?= $tingkat ?></div>
                    </div>
                    <span class="badge <?= $stCls ?>" style="font-size:.68rem;"><?= $stLabel ?></span>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="text-center text-muted py-4 small">Belum ada data.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- ===== REKAP TAHUNAN ===== -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-calendar3 me-2 text-primary"></i>Rekap Tahunan
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Tahun</th>
                        <th class="text-center">Total Pendaftaran</th>
                        <th class="text-center">Pembayaran Diterima</th>
                        <th>Total Pemasukan</th>
                        <th class="pe-4">Progress</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($tahunan)): ?>
                    <?php
                    $max_daftar = max(array_column($tahunan, 'total_daftar')) ?: 1;
                    foreach ($tahunan as $t):
                        $pct = round(($t['total_daftar'] / $max_daftar) * 100);
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= $t['tahun'] ?></td>
                        <td class="text-center fw-semibold"><?= $t['total_daftar'] ?></td>
                        <td class="text-center">
                            <?php
                            // Hitung diterima untuk tahun ini
                            $diterima_tahun = $this->db->query("
                                SELECT COUNT(*) AS jml FROM pendaftaran p
                                LEFT JOIN bukti_pembayaran bp ON bp.id = (
                                    SELECT id FROM bukti_pembayaran WHERE pendaftaran_id = p.id
                                    ORDER BY uploaded_at DESC LIMIT 1
                                )
                                WHERE YEAR(p.tanggal_daftar) = {$t['tahun']}
                                  AND bp.status_verifikasi = 'diterima'
                            ")->row_array();
                            echo $diterima_tahun['jml'] ?? 0;
                            ?>
                        </td>
                        <td class="fw-bold text-success">
                            Rp <?= number_format($t['total_pemasukan'] ?? 0, 0, ',', '.') ?>
                        </td>
                        <td class="pe-4" style="min-width:140px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-primary" style="width:<?= $pct ?>%"></div>
                                </div>
                                <span class="small text-muted"><?= $pct ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4 small">Belum ada data pendaftaran.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    var labels   = <?= json_encode($bulan_label) ?>;
    var daftar   = <?= json_encode(array_values(array_map(fn($b) => (int)$b['total_daftar'],   $bulanan))) ?>;
    var pemasukan= <?= json_encode(array_values(array_map(fn($b) => (int)$b['total_pemasukan'], $bulanan))) ?>;

    new Chart(document.getElementById('chartBulanan'), {
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Jumlah Pendaftaran',
                    data: daftar,
                    backgroundColor: 'rgba(78,115,223,0.15)',
                    borderColor: 'rgba(78,115,223,0.8)',
                    borderWidth: 2,
                    borderRadius: 6,
                    yAxisID: 'yBar',
                },
                {
                    type: 'line',
                    label: 'Pemasukan (Rp)',
                    data: pemasukan,
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28,200,138,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#1cc88a',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'yLine',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { font: { size: 12 } } },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            if (ctx.dataset.yAxisID === 'yLine') {
                                return ' Pemasukan: Rp ' + ctx.raw.toLocaleString('id-ID');
                            }
                            return ' Pendaftaran: ' + ctx.raw;
                        }
                    }
                }
            },
            scales: {
                yBar: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    title: { display: true, text: 'Pendaftaran', font: { size: 11 } }
                },
                yLine: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        font: { size: 11 },
                        callback: function (v) {
                            return 'Rp ' + (v / 1000).toLocaleString('id-ID') + 'k';
                        }
                    },
                    title: { display: true, text: 'Pemasukan', font: { size: 11 } }
                },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });
}());
</script>

<?php $this->load->view('admin/template/footer'); ?>
