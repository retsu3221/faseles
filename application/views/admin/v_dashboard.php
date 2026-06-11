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

<!-- ===== REKAP BULAN INI + PENDAFTARAN TERBARU ===== -->
<div class="row g-4 mb-4">

    <!-- Grafik Bulanan -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3 flex-wrap gap-2">
                <h6 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Grafik Bulanan <?= $tahun_ini ?>
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <!-- Toggle dataset -->
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-primary active" id="btnPendaftaran">
                            <i class="bi bi-people me-1"></i>Pendaftaran
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" id="btnPemasukan">
                            <i class="bi bi-cash me-1"></i>Pemasukan
                        </button>
                    </div>
                    <a href="<?= site_url('admin/rekap_bulanan') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="chartBulanan" height="130"></canvas>
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
                <a href="<?= site_url('admin/pembayaran') ?>" class="small text-primary text-decoration-none">Lihat semua</a>
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
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-calendar3 me-2 text-primary"></i>Rekap Tahunan
        </h6>
        <a href="<?= site_url('admin/rekap_tahunan') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i>Lihat Detail
        </a>
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
    var labels    = <?= json_encode(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']) ?>;
    var daftar    = <?= json_encode(array_values(array_map(fn($b) => (int)$b['total_daftar'],    $bulanan))) ?>;
    var pemasukan = <?= json_encode(array_values(array_map(fn($b) => (int)$b['total_pemasukan'], $bulanan))) ?>;

    var chart = new Chart(document.getElementById('chartBulanan'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    id: 'pendaftaran',
                    label: 'Jumlah Pendaftaran',
                    data: daftar,
                    backgroundColor: 'rgba(78,115,223,0.2)',
                    borderColor: 'rgba(78,115,223,0.9)',
                    borderWidth: 2,
                    borderRadius: 6,
                    hidden: false,
                },
                {
                    id: 'pemasukan',
                    label: 'Pemasukan (Rp)',
                    data: pemasukan,
                    type: 'line',
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28,200,138,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#1cc88a',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                    hidden: true,
                    yAxisID: 'yLine',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            if (ctx.datasetIndex === 1) {
                                return ' Pemasukan: Rp ' + ctx.raw.toLocaleString('id-ID');
                            }
                            return ' Pendaftaran: ' + ctx.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: { size: 13, weight: '600' },
                        color: '#444',
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                },
                yLine: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    display: false,
                    grace: '20%',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        font: { size: 13, weight: '600' },
                        color: '#1cc88a',
                        callback: function (v) {
                            return 'Rp ' + (v / 1000).toLocaleString('id-ID') + 'k';
                        }
                    },
                },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });

    var btnDaftar   = document.getElementById('btnPendaftaran');
    var btnMasuk    = document.getElementById('btnPemasukan');

    // Toggle Pendaftaran
    btnDaftar.addEventListener('click', function () {
        chart.data.datasets[0].hidden = false;
        chart.data.datasets[1].hidden = true;
        chart.options.scales.y.display     = true;
        chart.options.scales.yLine.display = false;
        chart.update();
        btnDaftar.classList.remove('btn-outline-primary'); btnDaftar.classList.add('btn-primary');    btnDaftar.classList.add('active');
        btnMasuk.classList.remove('btn-success');          btnMasuk.classList.add('btn-outline-success'); btnMasuk.classList.remove('active');
    });

    // Toggle Pemasukan
    btnMasuk.addEventListener('click', function () {
        chart.data.datasets[0].hidden = true;
        chart.data.datasets[1].hidden = false;
        chart.options.scales.y.display     = false;
        chart.options.scales.yLine.display = true;
        chart.update();
        btnMasuk.classList.remove('btn-outline-success'); btnMasuk.classList.add('btn-success');    btnMasuk.classList.add('active');
        btnDaftar.classList.remove('btn-primary');        btnDaftar.classList.add('btn-outline-primary'); btnDaftar.classList.remove('active');
    });
}());
</script>

<?php $this->load->view('admin/template/footer'); ?>
