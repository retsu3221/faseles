<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

<?php
$status    = $pendaftaran['status_pembayaran'];
$isPending = $status === 'pending';
$isLunas   = $status === 'lunas';
$isDitolak = $status === 'ditolak';
$isKadaluarsa = $status === 'kadaluarsa';
$nomorAdmin   = '6285795574037';
?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7">

                <?php if ($isLunas): ?>
                <!-- ===== STATUS: LUNAS ===== -->
                <div class="text-center mb-4">
                    <div class="status-circle status-circle-success mx-auto mb-3">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h4 class="fw-bold text-success mb-1">Pembayaran Berhasil!</h4>
                    <p class="text-muted">Pendaftaran kamu sudah dikonfirmasi. Tentor akan segera menghubungi kamu.</p>
                </div>

                <?php elseif ($isDitolak): ?>
                <!-- ===== STATUS: DITOLAK ===== -->
                <div class="text-center mb-4">
                    <div class="status-circle status-circle-danger mx-auto mb-3">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <h4 class="fw-bold text-danger mb-1">Pembayaran Ditolak</h4>
                    <p class="text-muted">Pembayaran kamu tidak dapat diverifikasi. Hubungi Admin untuk bantuan lebih lanjut.</p>
                    <a href="https://wa.me/<?= $nomorAdmin; ?>" target="_blank"
                       class="btn btn-outline-danger fw-bold px-4 rounded-3 mt-1">
                        <i class="bi bi-whatsapp me-1"></i> Hubungi Admin
                    </a>
                </div>

                <?php elseif ($isKadaluarsa): ?>
                <!-- ===== STATUS: KADALUARSA ===== -->
                <div class="text-center mb-4">
                    <div class="status-circle status-circle-secondary mx-auto mb-3">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h4 class="fw-bold text-secondary mb-1">Pendaftaran Kadaluarsa</h4>
                    <p class="text-muted">Batas waktu pembayaran telah habis. Silakan daftar ulang untuk melanjutkan.</p>
                    <a href="<?= site_url('pendaftaran/daftar'); ?>"
                       class="btn btn-outline-secondary fw-bold px-4 rounded-3 mt-1">
                        <i class="bi bi-arrow-repeat me-1"></i> Daftar Ulang
                    </a>
                </div>

                <?php else: ?>
                <!-- ===== STATUS: PENDING ===== -->
                <div class="rounded-4 p-4 mb-4 text-center"
                     style="background:#fffbeb; border: 1.5px solid #f59e0b;">
                    <div class="mb-2" style="font-size:2rem; color:#f59e0b;">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:#92400e;">Menunggu Pembayaran</h5>
                    <p class="small mb-0" style="color:#92400e; opacity:0.75;">
                        Selesaikan pembayaran sebelum batas waktu berakhir.
                    </p>
                </div>
                <?php endif; ?>

                <!-- Invoice Card -->
                <div class="card border-0 rounded-4 mb-4
                    <?= $isLunas ? 'shadow-sm border-success-subtle' : ($isDitolak ? 'shadow-sm' : ($isKadaluarsa ? 'shadow-sm opacity-75' : 'shadow-sm')); ?>">

                    <!-- Stamp LUNAS -->
                    <?php if ($isLunas): ?>
                    <div class="lunas-stamp">LUNAS</div>
                    <?php endif; ?>

                    <!-- Invoice Header -->
                    <div class="card-header bg-white rounded-top-4 border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">No. Transaksi</p>
                            <p class="fw-bold mb-0 text-primary"><?= htmlspecialchars($pendaftaran['no_transaksi'] ?? '-'); ?></p>
                        </div>
                        <div class="text-end">
                            <p class="text-muted small mb-0">Tanggal Daftar</p>
                            <p class="fw-bold mb-0 small">
                                <?= !empty($pendaftaran['tanggal_daftar'])
                                    ? date('d M Y, H:i', strtotime($pendaftaran['tanggal_daftar']))
                                    : '-'; ?> WIB
                            </p>
                        </div>
                    </div>

                    <div class="card-body px-4 py-4">

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <p class="text-muted small mb-1">Nama Siswa</p>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($pendaftaran['nama_lengkap']); ?></p>
                            </div>
                            <div class="col-6">
                                <p class="text-muted small mb-1">Tingkat Sekolah</p>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($pendaftaran['asal_sekolah']); ?></p>
                            </div>
                            <div class="col-6">
                                <p class="text-muted small mb-1">Orang Tua / Wali</p>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($pendaftaran['nama_ortu']); ?></p>
                            </div>
                            <div class="col-6">
                                <p class="text-muted small mb-1">Jadwal</p>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($pendaftaran['jadwal_hari']); ?>, <?= date('H:i', strtotime($pendaftaran['jadwal_jam'])); ?> WIB</p>
                            </div>
                        </div>

                        <div class="rounded-3 overflow-hidden border mb-4">
                            <div class="px-3 py-2 text-muted small fw-bold text-uppercase" style="background:#f8f9fa;">Rincian Paket</div>
                            <div class="d-flex justify-content-between align-items-center px-3 py-3">
                                <div>
                                    <p class="fw-bold mb-1">Paket Les FASE</p>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($label_kelas); ?></p>
                                </div>
                                <span class="fw-bold text-primary">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top" style="background:#f8faff;">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold fs-5 text-primary">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></span>
                            </div>
                        </div>

                        <?php if ($isPending): ?>
                        <div class="rounded-3 border overflow-hidden mb-4">
                            <div class="px-3 py-2 text-muted small fw-bold text-uppercase" style="background:#f8f9fa;">Transfer ke Rekening</div>
                            <div class="px-3 py-3">
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted small">Bank</span>
                                    <span class="fw-bold">BRI</span>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted small">No. Rekening</span>
                                    <span class="fw-bold">1234-5678-9012-3456</span>
                                </div>
                                <div class="d-flex justify-content-between py-2">
                                    <span class="text-muted small">Atas Nama</span>
                                    <span class="fw-bold">Ulil Hikmah Pitasari</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small text-center mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Setelah transfer, kirim bukti pembayaran via WhatsApp di bawah.
                        </p>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-grid gap-2">
                    <?php if ($isPending): ?>
                    <?php
                    $pesan  = 'Halo Admin FASE Les, saya ingin konfirmasi pembayaran.%0A%0A';
                    $pesan .= '*No. Transaksi :* ' . htmlspecialchars($pendaftaran['no_transaksi'] ?? '-') . '%0A';
                    $pesan .= '*Nama Siswa :* ' . urlencode($pendaftaran['nama_lengkap']) . '%0A';
                    $pesan .= '*Paket :* ' . urlencode($label_kelas) . '%0A';
                    $pesan .= '*Total :* Rp ' . number_format($total_biaya, 0, ',', '.') . '%0A%0A';
                    $pesan .= 'Terlampir bukti transfer.';
                    ?>
                    <a href="https://wa.me/<?= $nomorAdmin; ?>?text=<?= $pesan; ?>"
                       target="_blank"
                       class="btn btn-success btn-lg fw-bold py-3 d-flex justify-content-center align-items-center gap-2 rounded-3 shadow-sm">
                        <i class="bi bi-whatsapp fs-5"></i> Konfirmasi Pembayaran via WhatsApp
                    </a>
                    <?php elseif ($isLunas): ?>
                    <button onclick="window.print()"
                            class="btn btn-primary btn-lg fw-bold py-3 rounded-3 shadow-sm d-flex justify-content-center align-items-center gap-2">
                        <i class="bi bi-printer-fill fs-5"></i> Cetak Invoice
                    </button>
                    <a href="<?= site_url('akun/pesanan_saya'); ?>"
                       class="btn btn-success fw-bold py-2 rounded-3">
                        <i class="bi bi-bag-check me-1"></i> Lihat Pesanan Saya
                    </a>
                    <?php endif; ?>

                    <a href="<?= site_url('pendaftaran'); ?>"
                       class="btn btn-outline-secondary fw-bold py-2 rounded-3">
                        <i class="bi bi-house me-1"></i> Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>

    <style>
    .status-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #fff;
    }
    .status-circle-success  { background: linear-gradient(135deg, #22c55e, #16a34a); box-shadow: 0 8px 24px rgba(34,197,94,0.35); animation: popIn 0.4s cubic-bezier(0.175,0.885,0.32,1.275) both; }
    .status-circle-danger   { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 8px 24px rgba(239,68,68,0.35); }
    .status-circle-secondary{ background: linear-gradient(135deg, #9ca3af, #6b7280); box-shadow: 0 8px 24px rgba(107,114,128,0.3); }

    @keyframes popIn {
        from { opacity: 0; transform: scale(0.5); }
        to   { opacity: 1; transform: scale(1); }
    }

    /* Stamp LUNAS */
    .card { position: relative; }
    .lunas-stamp {
        position: absolute;
        top: 50%;
        right: 28px;
        transform: translateY(-50%) rotate(12deg);
        border: 3px solid #22c55e;
        color: #22c55e;
        font-weight: 900;
        font-size: 0.85rem;
        letter-spacing: 0.15em;
        padding: 4px 12px;
        border-radius: 6px;
        opacity: 0.55;
        z-index: 1;
        pointer-events: none;
    }
    @media print {
        nav, footer, .d-grid, .status-circle, h4.fw-bold, p.text-muted { display: none !important; }
        body { background: #fff !important; }
        .container { max-width: 100% !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
        .lunas-stamp { opacity: 1 !important; }
        .col-md-9, .col-lg-7 { width: 100% !important; max-width: 100% !important; }
    }
    </style>

<?php $this->load->view('template/footer'); ?>
