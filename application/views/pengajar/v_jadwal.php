<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mengajar — FASE Les</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; background: #f4f6f9; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e9ecef; }
        .logo-name { font-weight: 900; font-size: 1.15rem; }
        .logo-name span { color: #0d6efd; }
        .hero-pengajar {
            background: linear-gradient(135deg, #0d6efd, #4dabf7);
            color: #fff; border-radius: 0 0 1.5rem 1.5rem;
        }
    </style>
</head>
<body>

<!-- Topbar -->
<nav class="topbar sticky-top py-2">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
            <div class="logo-name">FASE <span>Les</span> <small class="text-muted fw-semibold ms-1" style="font-size:.75rem;">Portal Pengajar</small></div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold small"><?= htmlspecialchars($this->session->userdata('pengajar_nama')) ?></div>
                <div class="text-muted" style="font-size:.72rem;">@<?= htmlspecialchars($this->session->userdata('pengajar_username')) ?></div>
            </div>
            <a href="<?= site_url('pengajar/logout') ?>" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right me-1"></i>Keluar
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<div class="hero-pengajar py-4 mb-4">
    <div class="container">
        <h4 class="fw-bold mb-1">Jadwal Mengajar</h4>
        <p class="mb-3" style="opacity:.9;">Halo <?= htmlspecialchars($this->session->userdata('pengajar_nama')) ?>, berikut daftar les yang kamu ampu.</p>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-white text-primary px-3 py-2 fw-bold">
                <i class="bi bi-play-circle me-1"></i><?= $total_aktif ?> Aktif
            </span>
            <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 fw-bold">
                <i class="bi bi-check-circle me-1"></i><?= $total_selesai ?> Selesai
            </span>
        </div>
    </div>
</div>

<div class="container pb-5">

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
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">

                <!-- Header: siswa -->
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                             style="width:2.4rem;height:2.4rem;flex-shrink:0;">
                            <i class="bi bi-person-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($j['nama_siswa'] ?: $j['username_siswa']) ?></h6>
                            <div class="text-muted" style="font-size:.75rem;">
                                <?= htmlspecialchars($j['nama_paket']) ?>
                            </div>
                        </div>
                    </div>
                    <span class="badge <?= $sBadgeBg ?> bg-opacity-10 <?= $sText ?> px-2 py-1 fw-semibold flex-shrink-0">
                        <?= $sLabel ?>
                    </span>
                </div>

                <!-- Info jadwal -->
                <div class="d-flex gap-3 mb-3 flex-wrap">
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-calendar3 text-primary"></i>
                        <span><?= htmlspecialchars($j['hari']) ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-clock text-primary"></i>
                        <span><?= date('H:i', strtotime($j['jam_mulai'])) ?> – <?= date('H:i', strtotime($j['jam_selesai'])) ?></span>
                    </div>
                    <?php if ($j['asal_sekolah']): ?>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-building text-primary"></i>
                        <span><?= htmlspecialchars($j['asal_sekolah']) ?></span>
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

                <?php if ($j['catatan']): ?>
                <div class="mt-3 p-2 bg-light rounded-3 small text-muted">
                    <i class="bi bi-sticky me-1"></i><?= htmlspecialchars($j['catatan']) ?>
                </div>
                <?php endif; ?>

                <button type="button"
                        class="btn btn-sm btn-outline-primary w-100 mt-3 btn-detail"
                        data-nama="<?= htmlspecialchars($j['nama_siswa'] ?: $j['username_siswa']) ?>"
                        data-ttl="<?= htmlspecialchars(trim(($j['tempat_lahir'] ?: '') . ($j['tanggal_lahir'] ? ', ' . date('d-m-Y', strtotime($j['tanggal_lahir'])) : ''), ', ')) ?>"
                        data-jk="<?= htmlspecialchars($j['jenis_kelamin'] ?: '') ?>"
                        data-sekolah="<?= htmlspecialchars($j['asal_sekolah'] ?: '') ?>"
                        data-wa-ortu="<?= htmlspecialchars($j['no_wa_ortu'] ?: '') ?>">
                    <i class="bi bi-person-lines-fill me-1"></i>Detail Peserta
                </button>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-calendar2-x fs-1 d-block mb-3 opacity-50"></i>
            <h6 class="fw-bold">Belum Ada Jadwal Mengajar</h6>
            <p class="small mb-0">Jadwal akan muncul di sini setelah admin menetapkan kamu sebagai pengajar les.</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Modal Detail Peserta -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-person-lines-fill me-1 text-primary"></i>Detail Peserta
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">

                <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-primary bg-opacity-10 rounded-3">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                         style="width:3rem;height:3rem;flex-shrink:0;">
                        <i class="bi bi-person-fill text-white fs-5"></i>
                    </div>
                    <div class="fw-bold" id="dNama">—</div>
                </div>

                <table class="table table-sm align-middle mb-0 small">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:45%;"><i class="bi bi-geo-alt me-2 text-primary"></i>Tempat, Tgl Lahir</td>
                            <td class="fw-semibold" id="dTtl">—</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-gender-ambiguous me-2 text-primary"></i>Jenis Kelamin</td>
                            <td class="fw-semibold" id="dJk">—</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-building me-2 text-primary"></i>Asal Sekolah</td>
                            <td class="fw-semibold" id="dSekolah">—</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-whatsapp me-2 text-success"></i>WA Orang Tua</td>
                            <td class="fw-semibold" id="dWaOrtu">—</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-detail').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        var isi = function (id, val) {
            document.getElementById(id).textContent = val || '—';
        };
        isi('dNama', d.nama);
        isi('dTtl', d.ttl);
        isi('dJk', d.jk);
        isi('dSekolah', d.sekolah);

        var waCell = document.getElementById('dWaOrtu');
        if (d.waOrtu) {
            waCell.innerHTML = '<a href="https://wa.me/62' + d.waOrtu + '" target="_blank" class="text-success text-decoration-none">+62' + d.waOrtu + ' <i class="bi bi-box-arrow-up-right small"></i></a>';
        } else {
            waCell.textContent = '—';
        }

        new bootstrap.Modal(document.getElementById('modalDetail')).show();
    });
});
</script>

</body>
</html>
