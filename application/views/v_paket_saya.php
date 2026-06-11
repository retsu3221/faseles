<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="pesanan-header">
        <div class="container">
            <div class="d-flex align-items-center gap-4">
                <div class="pesanan-header-icon">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Paket Saya</h2>
                    <p class="mb-0" style="opacity: 0.75;">Paket les aktif yang sudah dikonfirmasi pembayarannya.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">

                <?php if (empty($paket)): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body py-5 text-center">
                        <i class="bi bi-journal-x text-muted" style="font-size: 3.5rem;"></i>
                        <h5 class="fw-bold mt-3 mb-1">Belum Ada Paket Aktif</h5>
                        <p class="text-muted small mb-4">Kamu belum memiliki paket les yang aktif. Selesaikan pembayaran terlebih dahulu.</p>
                        <a href="<?= site_url('akun/pesanan_saya'); ?>" class="btn btn-outline-primary fw-bold px-4 rounded-3">
                            <i class="bi bi-bag me-1"></i> Lihat Pesanan Saya
                        </a>
                    </div>
                </div>

                <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($paket as $item):
                        $tingkat   = $item['asal_sekolah'];
                        $isPrivat  = ($item['tipe_kelas'] ?? '') === 'Privat';
                        $namaKelas = !empty($item['tipe_kelas'])
                            ? $item['tipe_kelas'] . ' (' . $item['durasi_menit'] . ' Menit | ' . $item['jumlah_pertemuan'] . 'x Pertemuan)'
                            : '-';
                        $biaya     = (int) ($item['harga'] ?? 0);
                    ?>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden paket-item">
                        <div class="d-flex">

                            <!-- Accent kiri -->
                            <div class="paket-accent d-flex flex-column align-items-center justify-content-center px-4 py-4 text-white"
                                 style="background: linear-gradient(180deg, #0d6efd, #0dcaf0); min-width: 90px;">
                                <i class="bi <?= $isPrivat ? 'bi-person-fill' : 'bi-people-fill'; ?> fs-3 mb-2"></i>
                                <span class="small fw-bold text-center" style="font-size: 0.7rem; line-height: 1.3;">
                                    <?= $isPrivat ? 'PRIVAT' : 'KELOMPOK'; ?>
                                </span>
                            </div>

                            <!-- Konten -->
                            <div class="flex-grow-1 p-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($item['nama_lengkap']); ?></h5>
                                        <p class="text-muted small mb-0"><?= htmlspecialchars($namaKelas); ?> &mdash; <?= htmlspecialchars($tingkat); ?></p>
                                    </div>
                                    <span class="badge bg-success rounded-pill px-3 py-2 small">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif & Lunas
                                    </span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">&#128197; Jadwal</p>
                                        <p class="fw-bold mb-0 small"><?= htmlspecialchars($item['jadwal_hari']); ?>, <?= date('H:i', strtotime($item['jadwal_jam'])); ?> WIB</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">&#128100; Orang Tua</p>
                                        <p class="fw-bold mb-0 small"><?= htmlspecialchars($item['nama_ortu']); ?></p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">&#128176; Total Bayar</p>
                                        <p class="fw-bold mb-0 small text-success">Rp <?= number_format($biaya, 0, ',', '.'); ?></p>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">
                                        <i class="bi bi-hash me-1"></i><?= htmlspecialchars($item['no_transaksi'] ?? '-'); ?>
                                    </span>
                                    <a href="<?= site_url('pendaftaran/pembayaran/' . $item['id']); ?>"
                                       class="btn btn-outline-primary btn-sm fw-bold px-3 rounded-3">
                                        <i class="bi bi-eye me-1"></i> Lihat Invoice
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
    .paket-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .paket-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(13, 110, 253, 0.12) !important;
    }
    </style>

<?php $this->load->view('template/footer'); ?>
