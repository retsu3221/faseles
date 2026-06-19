<?php
$this->load->view('admin/template/header', [
    'page_title'  => $page_title,
    'active_menu' => $active_menu,
]);
$this->load->view('admin/template/topbar', [
    'page_title' => $page_title,
]);
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-secondary me-1">
                <i class="bi bi-people-fill me-2 text-primary"></i>Data Peserta
            </h6>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPeserta">
                <i class="bi bi-plus-lg me-1"></i>Tambah Peserta
            </button>
            <div class="input-group input-group-sm" style="max-width:240px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="searchUser" class="form-control bg-light border-start-0"
                       placeholder="Cari nama, username, email...">
            </div>
            <span class="badge bg-primary rounded-pill ms-auto" id="jumlahUser"><?= count($users) ?> pengguna</span>
        </div>
    </div>
    <div class="card-body p-3">
        <?php if (!empty($users)): ?>
        <div class="row g-3">
        <?php foreach ($users as $i => $u): ?>
        <div class="col-12 col-xl-6 user-card"
             data-search="<?= strtolower(htmlspecialchars(
                 $u['nama_lengkap'] . ' ' . $u['username'] . ' ' . $u['email'] . ' ' .
                 $u['asal_sekolah'] . ' ' . $u['nama_ortu'] . ' ' .
                 $u['tempat_lahir'] . ' ' . $u['tanggal_lahir']
             )) ?>">
            <div class="border rounded-3 p-3 h-100 bg-white">

                <!-- Header kartu -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                             style="width:2.2rem;height:2.2rem;flex-shrink:0;">
                            <span class="fw-bold text-primary small"><?= $i + 1 ?></span>
                        </div>
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($u['nama_lengkap'] ?: '—') ?></div>
                            <div class="small text-muted">@<?= htmlspecialchars($u['username']) ?> &middot; <?= htmlspecialchars($u['email']) ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <button type="button"
                                class="btn btn-sm btn-outline-warning btn-edit"
                                title="Edit"
                                data-id="<?= $u['id'] ?>"
                                data-username="<?= htmlspecialchars($u['username']) ?>"
                                data-nama="<?= htmlspecialchars($u['nama_lengkap'] ?: '') ?>"
                                data-email="<?= htmlspecialchars($u['email']) ?>"
                                data-tempat="<?= htmlspecialchars($u['tempat_lahir'] ?: '') ?>"
                                data-tgl="<?= htmlspecialchars($u['tanggal_lahir'] ?: '') ?>"
                                data-jk="<?= htmlspecialchars($u['jenis_kelamin'] ?: '') ?>"
                                data-alamat="<?= htmlspecialchars($u['alamat'] ?: '') ?>"
                                data-sekolah="<?= htmlspecialchars($u['asal_sekolah'] ?: '') ?>"
                                data-namaortu="<?= htmlspecialchars($u['nama_ortu'] ?: '') ?>"
                                data-wa="<?= htmlspecialchars($u['no_wa_ortu'] ?: '') ?>"
                                data-pekerjaan="<?= htmlspecialchars($u['pekerjaan_ortu'] ?: '') ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger btn-hapus"
                                title="Hapus"
                                data-id="<?= $u['id'] ?>"
                                data-nama="<?= htmlspecialchars($u['nama_lengkap'] ?: $u['username']) ?>">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>

                <hr class="my-2">

                <!-- Grid data -->
                <div class="row g-2 small">

                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">JENIS KELAMIN</div>
                        <div class="fw-semibold"><?= htmlspecialchars($u['jenis_kelamin'] ?: '—') ?></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">TEMPAT LAHIR</div>
                        <div class="fw-semibold"><?= htmlspecialchars($u['tempat_lahir'] ?: '—') ?></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">TANGGAL LAHIR</div>
                        <div class="fw-semibold"><?= $u['tanggal_lahir'] ? date('d M Y', strtotime($u['tanggal_lahir'])) : '—' ?></div>
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="text-muted" style="font-size:.72rem;">ALAMAT</div>
                        <div class="fw-semibold"><?= htmlspecialchars($u['alamat'] ?: '—') ?></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">ASAL SEKOLAH</div>
                        <div class="fw-semibold"><?= htmlspecialchars($u['asal_sekolah'] ?: '—') ?></div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">NAMA ORTU/WALI</div>
                        <div class="fw-semibold"><?= htmlspecialchars($u['nama_ortu'] ?: '—') ?></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">NO. WA ORTU</div>
                        <div class="fw-semibold"><?= $u['no_wa_ortu'] ? '+62' . htmlspecialchars($u['no_wa_ortu']) : '—' ?></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">PEKERJAAN ORTU</div>
                        <div class="fw-semibold"><?= htmlspecialchars($u['pekerjaan_ortu'] ?: '—') ?></div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">PENDAFTARAN</div>
                        <div class="fw-bold <?= $u['jumlah_pendaftaran'] > 0 ? 'text-primary' : 'text-muted' ?>">
                            <?= $u['jumlah_pendaftaran'] ?> kali
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:.72rem;">BERGABUNG</div>
                        <div class="fw-semibold"><?= date('d M Y', strtotime($u['created_at'])) ?></div>
                    </div>

                </div>

            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-people fs-2 d-block mb-2 opacity-50"></i>
            Belum ada data pengguna.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Edit Peserta -->
<div class="modal fade" id="modalEditPeserta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-white fs-5"></i>
                    <h6 class="modal-title fw-bold text-white mb-0">Edit Data Peserta</h6>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPeserta" method="post">
                <div class="modal-body p-4" style="max-height:75vh;overflow-y:auto;">

                    <!-- Data Pribadi -->
                    <p class="fw-bold text-primary small mb-2 text-uppercase">
                        <i class="bi bi-person-vcard me-1"></i>Data Pribadi
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="editNamaLengkap" class="form-control" placeholder="Nama lengkap">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="editTempatLahir" class="form-control" placeholder="Kota tempat lahir">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="editTanggalLahir" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small d-block">Jenis Kelamin</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" id="editJKL">
                                    <label class="form-check-label" for="editJKL">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" id="editJKP">
                                    <label class="form-check-label" for="editJKP">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" id="editAsalSekolah" class="form-control" placeholder="Nama sekolah">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Alamat</label>
                            <textarea name="alamat" id="editAlamat" class="form-control" rows="2" placeholder="Nama jalan, RT/RW, Kelurahan..."></textarea>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Data Orang Tua -->
                    <p class="fw-bold text-warning small mb-2 text-uppercase">
                        <i class="bi bi-people me-1"></i>Data Orang Tua / Wali
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_ortu" id="editNamaOrtu" class="form-control" placeholder="Nama orang tua atau wali">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">No. WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="no_wa_ortu" id="editNoWa" class="form-control" placeholder="8xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ortu" id="editPekerjaan" class="form-control" placeholder="Pekerjaan orang tua">
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Data Akun -->
                    <p class="fw-bold text-secondary small mb-2 text-uppercase">
                        <i class="bi bi-shield-lock me-1"></i>Data Akun
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="editUsername" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Ganti Password -->
                    <p class="fw-bold text-danger small mb-2 text-uppercase">
                        <i class="bi bi-key me-1"></i>Ganti Password <span class="text-muted fw-normal text-lowercase">(kosongkan jika tidak ingin diubah)</span>
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Password Baru</label>
                            <input type="password" name="password" id="editPassword" class="form-control" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" id="editKonfirmasi" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-semibold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Peserta -->
<div class="modal fade" id="modalTambahPeserta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-1 text-primary"></i>Tambah Peserta Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/tambah_peserta') ?>" method="post">
                <div class="modal-body p-4" style="max-height:75vh;overflow-y:auto;">

                    <p class="fw-bold text-primary small text-uppercase mb-2">
                        <i class="bi bi-person-vcard me-1"></i>Data Pribadi
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap siswa">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota tempat lahir">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small d-block">Jenis Kelamin</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" id="tambahJKL">
                                    <label class="form-check-label" for="tambahJKL">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" id="tambahJKP">
                                    <label class="form-check-label" for="tambahJKP">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="form-control" placeholder="Nama sekolah">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Nama jalan, RT/RW, Kelurahan..."></textarea>
                        </div>
                    </div>

                    <hr class="my-3">

                    <p class="fw-bold text-warning small text-uppercase mb-2">
                        <i class="bi bi-people me-1"></i>Data Orang Tua / Wali
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_ortu" class="form-control" placeholder="Nama orang tua atau wali">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">No. WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="no_wa_ortu" class="form-control" placeholder="8xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ortu" class="form-control" placeholder="Contoh: Wiraswasta">
                        </div>
                    </div>

                    <hr class="my-3">

                    <p class="fw-bold text-secondary small text-uppercase mb-2">
                        <i class="bi bi-shield-lock me-1"></i>Data Akun
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Buat username unik" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Hapus Pengguna
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Hapus pengguna <strong id="namaHapus" class="text-dark"></strong>? Semua data pendaftaran terkait juga akan terhapus.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="linkHapus" href="#" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash3 me-1"></i>Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d  = this.dataset;
        var jk = d.jk;

        document.getElementById('formEditPeserta').action =
            '<?= site_url('admin/update_peserta/') ?>' + d.id;

        document.getElementById('editNamaLengkap').value  = d.nama;
        document.getElementById('editTempatLahir').value  = d.tempat;
        document.getElementById('editTanggalLahir').value = d.tgl;
        document.getElementById('editAsalSekolah').value  = d.sekolah;
        document.getElementById('editAlamat').value       = d.alamat;
        document.getElementById('editNamaOrtu').value     = d.namaortu;
        document.getElementById('editNoWa').value         = d.wa;
        document.getElementById('editPekerjaan').value    = d.pekerjaan;
        document.getElementById('editUsername').value     = d.username;
        document.getElementById('editEmail').value        = d.email;
        document.getElementById('editPassword').value     = '';
        document.getElementById('editKonfirmasi').value   = '';

        document.getElementById('editJKL').checked = (jk === 'Laki-laki');
        document.getElementById('editJKP').checked = (jk === 'Perempuan');

        new bootstrap.Modal(document.getElementById('modalEditPeserta')).show();
    });
});

document.getElementById('searchUser').addEventListener('input', function () {
    var q     = this.value.toLowerCase().trim();
    var cards = document.querySelectorAll('.user-card');
    var shown = 0;
    cards.forEach(function (card) {
        var match = !q || card.dataset.search.includes(q);
        card.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    document.getElementById('jumlahUser').textContent = shown + ' pengguna';
});

document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id   = this.dataset.id;
        var nama = this.dataset.nama;
        document.getElementById('namaHapus').textContent = nama;
        document.getElementById('linkHapus').href = '<?= site_url('admin/hapus_peserta/') ?>' + id;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    });
});
</script>

<?php $this->load->view('admin/template/footer'); ?>
