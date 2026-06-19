<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="hero-bg text-center">
        <div class="container">
            <h1 class="display-5 fw-bold">Jadwal Saya</h1>
            <p class="fs-5 mt-2 mb-0" style="opacity: 0.9;">Lihat jadwal les dan progress pertemuan kamu.</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center content-card-overlap">
            <div class="col-12 col-lg-10">

                <?php if (!empty($jadwal)): ?>
                <div class="row g-3">
                <?php foreach ($jadwal as $j):
                    $pct = $j['jumlah_pertemuan'] > 0
                           ? round($j['pertemuan_selesai'] / $j['jumlah_pertemuan'] * 100)
                           : 0;
                    $statusCfg = [
                        'aktif'      => ['bg-success', 'text-success', 'Aktif'],
                        'selesai'    => ['bg-secondary', 'text-secondary', 'Selesai'],
                        'dibatalkan' => ['bg-danger', 'text-danger', 'Dibatalkan'],
                    ];
                    [$sBadgeBg, $sText, $sLabel] = $statusCfg[$j['status']] ?? ['bg-secondary', 'text-secondary', $j['status']];
                ?>
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">

                            <!-- Header -->
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($j['nama_paket']) ?></h6>
                                    <div class="text-muted small">
                                        <i class="bi bi-person-badge me-1"></i>
                                        <?= htmlspecialchars($j['nama_pengajar']) ?>
                                        <?php if ($j['mata_pelajaran']): ?>
                                        <span class="text-muted"> &middot; <?= htmlspecialchars($j['mata_pelajaran']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="badge <?= $sBadgeBg ?> bg-opacity-10 <?= $sText ?> px-2 py-1 fw-semibold flex-shrink-0">
                                    <?= $sLabel ?>
                                </span>
                            </div>

                            <!-- Info Jadwal -->
                            <div class="d-flex gap-3 mb-3 flex-wrap">
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i class="bi bi-calendar3 text-primary"></i>
                                    <span><?= htmlspecialchars($j['hari']) ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i class="bi bi-clock text-primary"></i>
                                    <span><?= date('H:i', strtotime($j['jam_mulai'])) ?> – <?= date('H:i', strtotime($j['jam_selesai'])) ?></span>
                                </div>
                                <?php if ($j['wa_pengajar']): ?>
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i class="bi bi-whatsapp text-success"></i>
                                    <a href="https://wa.me/62<?= htmlspecialchars($j['wa_pengajar']) ?>" target="_blank"
                                       class="text-decoration-none text-muted">+62<?= htmlspecialchars($j['wa_pengajar']) ?></a>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Progress -->
                            <div class="mb-1 d-flex justify-content-between align-items-center">
                                <span class="small text-muted fw-semibold">Progress Pertemuan</span>
                                <span class="small fw-bold <?= $pct >= 100 ? 'text-success' : 'text-primary' ?>">
                                    <?= $j['pertemuan_selesai'] ?> / <?= $j['jumlah_pertemuan'] ?>
                                </span>
                            </div>
                            <div class="progress rounded-pill" style="height:8px;">
                                <div class="progress-bar <?= $pct >= 100 ? 'bg-success' : 'bg-primary' ?> rounded-pill"
                                     style="width:<?= $pct ?>%;"></div>
                            </div>
                            <div class="text-end mt-1" style="font-size:.72rem; color:#aaa;">
                                <?= $pct ?>% selesai
                            </div>

                            <?php if ($j['catatan']): ?>
                            <div class="mt-3 p-2 bg-light rounded-3 small text-muted">
                                <i class="bi bi-sticky me-1"></i><?= htmlspecialchars($j['catatan']) ?>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>

                <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-calendar2-x fs-1 d-block mb-3 opacity-50"></i>
                        <h6 class="fw-bold">Belum Ada Jadwal</h6>
                        <p class="small mb-0">Jadwalmu akan muncul di sini setelah pembayaran dikonfirmasi dan admin menetapkan pengajar.</p>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

<?php $this->load->view('template/footer'); ?>
