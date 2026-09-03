<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="pesanan-header">
        <div class="container">
            <div class="d-flex align-items-center gap-4">
                <div class="pesanan-header-icon">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Pesanan Saya</h2>
                    <p class="mb-0" style="opacity: 0.75;">Riwayat dan status pendaftaran les kamu.</p>
                </div>
            </div>
        </div>
    </div>


    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <?php if (empty($pesanan)): ?>
                <!-- Kosong -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body py-5 text-center">
                        <i class="bi bi-bag-x text-muted" style="font-size: 3.5rem;"></i>
                        <h5 class="fw-bold mt-3 mb-1">Belum Ada Pesanan</h5>
                        <p class="text-muted small mb-4">Kamu belum pernah mendaftar les. Yuk mulai sekarang!</p>
                        <a href="<?= site_url('pendaftaran/daftar'); ?>" class="btn-daftar-les">
                            <i class="bi bi-pencil-square me-1"></i> Daftar Les Sekarang
                        </a>
                    </div>
                </div>

                <?php else: ?>
                <!-- List Pesanan -->
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($pesanan as $item):
                        $status = $item['status_verifikasi'];
                        $cfg    = $status_config[$status] ?? $status_config['pending'];

                        // Label paket lengkap, mis. "SMP – Privat · 90 menit · 8x pertemuan"
                        $paketLabel = '-';
                        if (!empty($item['tipe_kelas'])) {
                            $paketLabel = !empty($item['tingkat'])
                                ? $item['tingkat'] . ' – ' . $item['tipe_kelas']
                                : $item['tipe_kelas'];

                            $rincian = [];
                            if (!empty($item['durasi_menit']))     $rincian[] = $item['durasi_menit'] . ' menit';
                            if (!empty($item['jumlah_pertemuan'])) $rincian[] = $item['jumlah_pertemuan'] . 'x pertemuan';
                            if ($rincian) $paketLabel .= ' · ' . implode(' · ', $rincian);
                        }
                    ?>
                    <div class="card border-0 shadow-sm rounded-4 pesanan-card">
                        <div class="card-body p-4">
                            <div class="row align-items-center g-3">

                                <!-- Info Utama -->
                                <div class="col-12 col-md-4">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <span class="badge rounded-pill px-3 py-2 <?= $cfg['class']; ?> small">
                                            <i class="bi <?= $cfg['icon']; ?> me-1"></i><?= $cfg['label']; ?>
                                        </span>
                                    </div>
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($item['nama_lengkap'] ?? '-'); ?></h6>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($paketLabel); ?></p>
                                </div>

                                <!-- Info Tambahan -->
                                <div class="col-6 col-md-2">
                                    <p class="text-muted small mb-1">No. Transaksi</p>
                                    <p class="fw-bold small mb-0"><?= htmlspecialchars($item['no_transaksi'] ?? '-'); ?></p>
                                </div>

                                <div class="col-6 col-md-2">
                                    <p class="text-muted small mb-1">Tanggal Daftar</p>
                                    <p class="fw-bold small mb-0">
                                        <?= !empty($item['tanggal_daftar'])
                                            ? date('d M Y', strtotime($item['tanggal_daftar']))
                                            : '-'; ?>
                                    </p>
                                </div>

                                <div class="col-6 col-md-2">
                                    <p class="text-muted small mb-1">Total Biaya</p>
                                    <p class="fw-bold small mb-0 text-primary">
                                        <?= !empty($item['harga'])
                                            ? 'Rp ' . number_format($item['harga'], 0, ',', '.')
                                            : '-'; ?>
                                    </p>
                                </div>

                                <!-- Tombol -->
                                <div class="col-6 col-md-2 text-md-end">
                                    <a href="<?= site_url('pendaftaran/pembayaran/' . $item['id']); ?>"
                                       class="btn btn-outline-primary btn-sm fw-bold px-3 rounded-3">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <style>
    .pesanan-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .pesanan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
    }
    </style>

<?php $this->load->view('template/footer'); ?>
