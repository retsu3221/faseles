<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

<?php
$nomorAdmin     = '6285795574037';
$bukti_terakhir = isset($bukti_terakhir) ? $bukti_terakhir : null;

// Status halaman ditentukan oleh bukti_pembayaran terbaru.
// Belum upload bukti = pending. 'diterima' setara 'lunas'.
$status       = $bukti_terakhir ? ($bukti_terakhir['status_verifikasi'] ?? 'pending') : 'pending';
$isPending    = $status === 'pending';
$isLunas      = $status === 'diterima';
$isDitolak    = $status === 'ditolak';
$isKadaluarsa = $status === 'kadaluarsa';
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
                            Setelah transfer, upload bukti pembayaran atau kirim via WhatsApp.
                        </p>

                        <?php if ($bukti_terakhir): ?>
                        <?php
                        $sv = $bukti_terakhir['status_verifikasi'] ?? 'pending';
                        $sv_config = [
                            'pending'    => ['class' => 'border-warning',   'bg' => 'bg-warning bg-opacity-10',   'badge' => 'bg-warning text-dark',    'icon' => 'bi-clock-fill text-warning',        'label' => 'Menunggu Verifikasi'],
                            'diterima'   => ['class' => 'border-success',   'bg' => 'bg-success bg-opacity-10',   'badge' => 'bg-success text-white',    'icon' => 'bi-check-circle-fill text-success', 'label' => 'Bukti Diterima'],
                            'ditolak'    => ['class' => 'border-danger',    'bg' => 'bg-danger bg-opacity-10',    'badge' => 'bg-danger text-white',     'icon' => 'bi-x-circle-fill text-danger',      'label' => 'Bukti Ditolak'],
                            'kadaluarsa' => ['class' => 'border-secondary', 'bg' => 'bg-secondary bg-opacity-10', 'badge' => 'bg-secondary text-white', 'icon' => 'bi-calendar-x-fill text-secondary',  'label' => 'Kadaluarsa'],
                        ];
                        $sv_info = $sv_config[$sv] ?? $sv_config['pending'];
                        ?>
                        <div class="rounded-3 border <?= $sv_info['class']; ?> <?= $sv_info['bg']; ?> mt-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold small">
                                    <i class="bi <?= $sv_info['icon']; ?> me-1"></i>Status Bukti Pembayaran
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="<?= base_url('assets/img/bukti/' . $bukti_terakhir['file_bukti']); ?>" target="_blank"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-flex align-items-center gap-1 fw-bold"
                                       style="font-size:0.75rem;" title="Lihat Bukti">
                                        <i class="bi bi-eye-fill"></i> Lihat
                                    </a>
                                    <span class="badge <?= $sv_info['badge']; ?>"><?= $sv_info['label']; ?></span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Upload pada:</span>
                                <span><?= date('d M Y, H:i', strtotime($bukti_terakhir['uploaded_at'])); ?> WIB</span>
                            </div>
                            <?php if (!empty($bukti_terakhir['verified_at']) && $sv !== 'pending'): ?>
                            <div class="d-flex justify-content-between small text-muted mt-1">
                                <span>Diverifikasi:</span>
                                <span><?= date('d M Y, H:i', strtotime($bukti_terakhir['verified_at'])); ?> WIB</span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($bukti_terakhir['catatan_admin'])): ?>
                            <div class="mt-2 pt-2 border-top small">
                                <span class="text-muted">Catatan Admin:</span>
                                <p class="mb-0 fw-bold"><?= htmlspecialchars($bukti_terakhir['catatan_admin']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php endif; ?>

                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-grid gap-2">
                    <?php if ($isPending): ?>
                    <button type="button" class="btn btn-primary btn-lg fw-bold py-3 rounded-3 shadow-sm d-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalUploadBukti">
                        <i class="bi bi-cloud-upload fs-5"></i> Upload Bukti Pembayaran
                    </button>
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

    <?php if ($isLunas): ?>
    <?php
        $tgl_lunas = !empty($bukti_terakhir['verified_at'])
            ? $bukti_terakhir['verified_at']
            : ($bukti_terakhir['uploaded_at'] ?? $pendaftaran['tanggal_daftar'] ?? date('Y-m-d H:i:s'));
    ?>
    <!-- ===== INVOICE KHUSUS CETAK (tersembunyi di layar) ===== -->
    <div id="invoiceCetak">
        <div class="inv-box">

            <div class="inv-card">
                <div class="inv-accent"></div>
                <div class="inv-pad">

                    <!-- Header -->
                    <div class="inv-head">
                        <img src="<?= base_url('assets/img/Logo.png'); ?>" alt="FASE Les" class="inv-logo">
                        <span class="inv-paid-pill">&#10003; LUNAS</span>
                    </div>

                    <!-- Meta -->
                    <div class="inv-meta">
                        <div>
                            <div class="inv-h">INVOICE</div>
                            <div class="inv-num"><?= htmlspecialchars($pendaftaran['no_transaksi'] ?? '-'); ?></div>
                        </div>
                        <div class="inv-meta-dates">
                            <div>
                                <span class="inv-k">Tanggal Daftar</span>
                                <span class="inv-v"><?= !empty($pendaftaran['tanggal_daftar']) ? date('d M Y', strtotime($pendaftaran['tanggal_daftar'])) : '-'; ?></span>
                            </div>
                            <div>
                                <span class="inv-k">Tanggal Lunas</span>
                                <span class="inv-v"><?= date('d M Y', strtotime($tgl_lunas)); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Ditagihkan kepada -->
                    <div class="inv-billto">
                        <span class="inv-k">Ditagihkan Kepada</span>
                        <div class="inv-name"><?= htmlspecialchars($pendaftaran['nama_lengkap']); ?></div>
                        <div class="inv-sub">
                            <?= htmlspecialchars($pendaftaran['asal_sekolah']); ?>
                            &middot; Orang Tua/Wali: <?= htmlspecialchars($pendaftaran['nama_ortu']); ?>
                            <?php if (!empty($pendaftaran['no_wa_ortu'])): ?>
                            &middot; WA: <?= htmlspecialchars($pendaftaran['no_wa_ortu']); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="inv-item-card">
                        <div class="inv-item-top">
                            <div>
                                <div class="inv-item-name">Paket Les FASE</div>
                                <div class="inv-item-desc">
                                    <?= htmlspecialchars($label_kelas); ?>
                                    &middot; <?= htmlspecialchars($pendaftaran['jadwal_hari']); ?>, <?= date('H:i', strtotime($pendaftaran['jadwal_jam'])); ?> WIB
                                </div>
                            </div>
                            <div class="inv-item-price">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="inv-total">
                        <span class="inv-total-k">Total Pembayaran</span>
                        <span class="inv-total-v">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></span>
                    </div>

                    <!-- Informasi -->
                    <div class="inv-note">
                        <div class="inv-note-title">Informasi Penting</div>
                        <ul class="inv-note-list">
                            <li>Tentor akan menghubungi Anda melalui WhatsApp untuk penjadwalan kelas.</li>
                            <li>Simpan invoice ini sebagai bukti pembayaran yang sah.</li>
                            <li>Untuk perubahan jadwal atau pertanyaan lain, hubungi admin di 0857-9557-4037.</li>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div class="inv-foot">
                        <div class="inv-thanks">
                            Terima kasih atas kepercayaan Anda.<br>
                            <span class="inv-printed">Dicetak <?= date('d M Y, H:i'); ?> WIB</span>
                        </div>
                        <div class="inv-sign">
                            <div class="inv-sign-line"></div>
                            <div class="inv-sign-name">Admin FASE Les</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <?php endif; ?>

    <style>
    /* Hilangkan spinner di input number */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }

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
    /* ===== INVOICE CETAK ===== */
    #invoiceCetak { display: none; }

    #invoiceCetak {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        color: #1f2937;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    #invoiceCetak .inv-box { max-width: 660px; margin: 0 auto; padding: 10px; }

    /* Kartu */
    #invoiceCetak .inv-card {
        border: 1px solid #e5e7eb; border-radius: 18px; overflow: hidden;
        box-shadow: 0 10px 30px rgba(13,110,253,.08);
        display: flex; flex-direction: column;
    }
    #invoiceCetak .inv-accent { height: 8px; background: linear-gradient(90deg, #0d6efd, #0dcaf0, #16a34a); }
    #invoiceCetak .inv-pad { padding: 34px 36px; flex: 1; display: flex; flex-direction: column; }

    /* Header */
    #invoiceCetak .inv-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 26px; }
    #invoiceCetak .inv-logo { height: 46px; width: auto; }
    #invoiceCetak .inv-paid-pill {
        background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; font-weight: 700;
        font-size: 12px; letter-spacing: .06em; padding: 7px 16px; border-radius: 999px;
    }

    /* Meta */
    #invoiceCetak .inv-meta { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 26px; }
    #invoiceCetak .inv-h { font-size: 12px; font-weight: 700; letter-spacing: .22em; color: #0d6efd; }
    #invoiceCetak .inv-num { font-size: 22px; font-weight: 800; color: #111827; margin-top: 3px; }
    #invoiceCetak .inv-meta-dates { text-align: right; display: flex; flex-direction: column; gap: 8px; }
    #invoiceCetak .inv-meta-dates > div { display: flex; flex-direction: column; }
    #invoiceCetak .inv-k {
        font-size: 10px; text-transform: uppercase; letter-spacing: .1em; color: #9ca3af; margin-bottom: 2px;
    }
    #invoiceCetak .inv-v { font-size: 13px; font-weight: 600; color: #111827; }

    /* Bill to */
    #invoiceCetak .inv-billto { margin-bottom: 22px; }
    #invoiceCetak .inv-name { font-size: 17px; font-weight: 700; margin-top: 4px; }
    #invoiceCetak .inv-sub { font-size: 12px; color: #6b7280; margin-top: 3px; line-height: 1.5; }

    /* Item card */
    #invoiceCetak .inv-item-card {
        background: #f8faff; border: 1px solid #eef2ff; border-radius: 14px; padding: 18px 20px; margin-bottom: 18px;
    }
    #invoiceCetak .inv-item-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
    #invoiceCetak .inv-item-name { font-size: 15px; font-weight: 700; }
    #invoiceCetak .inv-item-desc { font-size: 12px; color: #6b7280; margin-top: 4px; line-height: 1.5; }
    #invoiceCetak .inv-item-price { font-size: 15px; font-weight: 700; white-space: nowrap; }

    /* Total */
    #invoiceCetak .inv-total {
        display: flex; justify-content: space-between; align-items: center;
        background: linear-gradient(135deg, rgba(13,110,253,.08), rgba(22,163,74,.08));
        border-radius: 14px; padding: 16px 22px; margin-bottom: 22px;
    }
    #invoiceCetak .inv-total-k { font-size: 13px; font-weight: 600; color: #374151; }
    #invoiceCetak .inv-total-v { font-size: 26px; font-weight: 800; color: #0d6efd; }

    /* Info box */
    #invoiceCetak .inv-note {
        border: 1px dashed #c7d2fe; border-radius: 14px; padding: 18px 22px; margin-bottom: 24px;
        background: linear-gradient(135deg, rgba(13,110,253,.04), rgba(22,163,74,.04));
    }
    #invoiceCetak .inv-note-title {
        font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        color: #0d6efd; margin-bottom: 10px;
    }
    #invoiceCetak .inv-note-list { margin: 0; padding-left: 18px; font-size: 12px; color: #6b7280; line-height: 1.9; }
    #invoiceCetak .inv-note-list li { margin-bottom: 2px; }

    /* Footer */
    #invoiceCetak .inv-foot {
        margin-top: auto; display: flex; justify-content: space-between; align-items: flex-end;
        border-top: 1px solid #eef2ff; padding-top: 18px; font-size: 12px; color: #6b7280; line-height: 1.6;
    }
    #invoiceCetak .inv-printed { font-size: 11px; color: #9ca3af; }
    #invoiceCetak .inv-sign { text-align: center; }
    #invoiceCetak .inv-sign-line { width: 160px; border-bottom: 1px solid #9ca3af; height: 40px; margin-bottom: 6px; }
    #invoiceCetak .inv-sign-name { font-weight: 700; color: #111827; }

    @media print {
        /* Sembunyikan SEMUA selain invoice (display:none agar tidak ada halaman kosong) */
        body > *:not(#invoiceCetak) { display: none !important; }
        html, body { background: #fff !important; height: 100% !important; }
        /* Invoice mengisi penuh satu halaman */
        #invoiceCetak { display: flex !important; min-height: 100%; }
        #invoiceCetak .inv-box { flex: 1; display: flex; max-width: 100%; padding: 0; }
        #invoiceCetak .inv-card { flex: 1; }
        @page { size: A4 portrait; margin: 14mm; }
    }
    </style>

    <!-- Modal Upload Bukti Pembayaran -->
    <div class="modal fade" id="modalUploadBukti" tabindex="-1" aria-labelledby="labelUploadBukti" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="labelUploadBukti">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Bukti Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="<?= site_url('pendaftaran/upload_bukti'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaran['id']; ?>">

                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold small">Nama Pengirim</label>
                            <input type="text" name="nama_pengirim" class="form-control bg-light" required placeholder="Nama rekening pengirim">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label text-muted fw-bold small">Jumlah Transfer</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="jumlah_transfer" class="form-control bg-light" required value="<?= (int)$total_biaya; ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted fw-bold small">Tanggal Transfer</label>
                                <input type="date" name="tanggal_transfer" class="form-control bg-light"
                                       max="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold small">Bukti Transfer</label>
                            <input type="file" name="file_bukti" class="form-control bg-light" required>
                            <small class="text-muted">Format: JPG, PNG, PDF, GIF, WebP, BMP | Max 2MB</small>
                        </div>

                        <div class="mb-0">
                            <label class="form-label text-muted fw-bold small">Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control bg-light" rows="2" placeholder="Mis: Transfer dari rekening atas nama..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="bi bi-cloud-upload me-1"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $this->load->view('template/footer'); ?>
