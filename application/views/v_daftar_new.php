<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-primary mb-1">Data Pendaftaran</h4>
                        </div>

                        <form action="<?= site_url('pendaftaran/proses_daftar'); ?>" method="POST">

                            <div class="form-section-title mt-0">&#128100; Data Pribadi Siswa</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control bg-light" required placeholder="Masukkan nama lengkap siswa"
                                       value="<?= htmlspecialchars($user_data->nama_lengkap ?? '') ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control bg-light" required placeholder="Kota tempat lahir"
                                           value="<?= htmlspecialchars($user_data->tempat_lahir ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control bg-light" required
                                           value="<?= htmlspecialchars($user_data->tanggal_lahir ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold d-block">Jenis Kelamin</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" id="jkLaki"
                                               <?= ($user_data->jenis_kelamin ?? '') === 'Laki-laki' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="jkLaki">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" id="jkPerempuan"
                                               <?= ($user_data->jenis_kelamin ?? '') === 'Perempuan' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="jkPerempuan">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Alamat Rumah</label>
                                <textarea name="alamat" class="form-control bg-light" rows="2" required placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan..."><?= htmlspecialchars($user_data->alamat ?? '') ?></textarea>
                            </div>

                            <div class="form-section-title">&#128218; Data Sekolah & Paket Les</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold d-block">Tingkat Sekolah</label>
                                <?php $asal = $user_data->asal_sekolah ?? ''; ?>
                                <div class="d-flex gap-3 mt-1 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input tingkat-sekolah" type="checkbox" name="asal_sekolah" value="TK" id="tingkatTK" <?= $asal === 'TK' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tingkatTK">TK & SD</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input tingkat-sekolah" type="checkbox" name="asal_sekolah" value="SMP" id="tingkatSMP" <?= $asal === 'SMP' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tingkatSMP">SMP / MTs</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input tingkat-sekolah" type="checkbox" name="asal_sekolah" value="SMA" id="tingkatSMA" <?= $asal === 'SMA' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tingkatSMA">SMA / K</label>
                                    </div>
                                </div>
                                <p class="text-muted small mt-2 mb-0">*Pilih salah satu tingkat sekolah terlebih dahulu</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Kelas & Paket yang Diambil</label>
                                <select name="paket_id" id="pilihanKelas" class="form-select bg-light" required>
                                    <option value="" disabled selected>-- Pilih tingkat sekolah dulu --</option>
                                    <?php
                                    $tingkatLabel = ['TK' => 'TK & SD', 'SMP' => 'SMP/MTs', 'SMA' => 'SMA/K'];
                                    foreach ($paket as $p):
                                    ?>
                                    <option value="<?= $p['id']; ?>" data-tingkat="<?= $p['tingkat']; ?>">
                                        <?= htmlspecialchars($p['tipe_kelas']); ?> <?= $tingkatLabel[$p['tingkat']] ?? $p['tingkat']; ?> &nbsp;|&nbsp; <?= $p['durasi_menit']; ?> Menit · <?= $p['jumlah_pertemuan']; ?>x · Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Jadwal Belajar</label>
                                <div class="d-flex gap-2">
                                    <select name="jadwal" class="form-select bg-light">
                                        <option value="" disabled selected>-- Pilih Hari --</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                    </select>
                                    <input type="time" name="jam" class="form-control bg-light" required>
                                </div>
                            </div>

                            <div class="form-section-title">&#128106; Data Orang Tua / Wali</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Nama Lengkap Orang Tua / Wali</label>
                                <input type="text" name="nama_ortu" class="form-control bg-light" required placeholder="Nama lengkap orang tua atau wali"
                                       value="<?= htmlspecialchars($user_data->nama_ortu ?? '') ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">No. WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">&#127470;&#127465; +62</span>
                                        <input type="number" name="no_wa_ortu" class="form-control bg-light border-start-0" required placeholder="8xxxxxxxxxx"
                                               value="<?= htmlspecialchars($user_data->no_wa_ortu ?? '') ?>">
                                    </div>
                                    <p class="text-muted small mt-1 mb-0">*Tanpa angka 0 di depan</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control bg-light" required placeholder="Contoh: Wiraswasta"
                                           value="<?= htmlspecialchars($user_data->pekerjaan_ortu ?? '') ?>">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="button" id="btnKonfirmasi" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                    Lanjut Pembayaran &#8594;
                                </button>
                                <a href="<?= site_url('pendaftaran/index'); ?>" class="btn btn-outline-secondary btn-lg fw-bold">
                                    &#8592; Kembali ke Beranda
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-clipboard2-check-fill text-primary me-2"></i>Konfirmasi Data Pendaftaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <p class="text-muted small mb-3">Pastikan data di bawah ini sudah benar sebelum melanjutkan.</p>

                    <!-- Data Pribadi -->
                    <div class="mb-3">
                        <div class="fw-bold text-primary small text-uppercase mb-2">&#128100; Data Pribadi Siswa</div>
                        <div class="rounded-3 border p-3 bg-light">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted">Nama Lengkap</span>
                                    <div class="fw-semibold" id="kNamaLengkap">—</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Jenis Kelamin</span>
                                    <div class="fw-semibold" id="kJenisKelamin">—</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Tempat Lahir</span>
                                    <div class="fw-semibold" id="kTempatLahir">—</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Tanggal Lahir</span>
                                    <div class="fw-semibold" id="kTanggalLahir">—</div>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted">Alamat</span>
                                    <div class="fw-semibold" id="kAlamat">—</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Paket -->
                    <div class="mb-3">
                        <div class="fw-bold text-primary small text-uppercase mb-2">&#128218; Paket & Jadwal</div>
                        <div class="rounded-3 border p-3 bg-light">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted">Tingkat Sekolah</span>
                                    <div class="fw-semibold" id="kAsalSekolah">—</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Jadwal</span>
                                    <div class="fw-semibold" id="kJadwal">—</div>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted">Paket</span>
                                    <div class="fw-semibold" id="kPaket">—</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Ortu -->
                    <div class="mb-2">
                        <div class="fw-bold text-primary small text-uppercase mb-2">&#128106; Orang Tua / Wali</div>
                        <div class="rounded-3 border p-3 bg-light">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted">Nama</span>
                                    <div class="fw-semibold" id="kNamaOrtu">—</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Pekerjaan</span>
                                    <div class="fw-semibold" id="kPekerjaanOrtu">—</div>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted">No. WhatsApp</span>
                                    <div class="fw-semibold" id="kNoWa">—</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4" data-bs-dismiss="modal">
                        <i class="bi bi-pencil me-1"></i> Ubah Data
                    </button>
                    <button type="button" id="btnSubmitFinal" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-check-circle me-1"></i> Ya, Daftar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.tingkat-sekolah').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                document.querySelectorAll('.tingkat-sekolah').forEach(other => {
                    if (other !== this) other.checked = false;
                });
            }

            const tingkatTerpilih = this.checked ? this.value : '';
            const selectKelas = document.getElementById('pilihanKelas');

            selectKelas.value = '';

            selectKelas.querySelectorAll('option').forEach(option => {
                const tingkat = option.getAttribute('data-tingkat');
                if (!tingkat) return;

                if (tingkatTerpilih && tingkat === tingkatTerpilih) {
                    option.style.display = 'block';
                    option.disabled = false;
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });
        });
    });

    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('pilihanKelas').querySelectorAll('option[data-tingkat]').forEach(option => {
            option.style.display = 'none';
            option.disabled = true;
        });
    });

    // Konfirmasi sebelum submit
    document.getElementById('btnKonfirmasi').addEventListener('click', function () {
        var form = document.querySelector('form');

        // Validasi HTML5 native
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Ambil nilai dari form
        var jk = document.querySelector('input[name="jenis_kelamin"]:checked');
        var asal = document.querySelector('input[name="asal_sekolah"]:checked');
        var paketEl = document.getElementById('pilihanKelas');
        var paketText = paketEl.options[paketEl.selectedIndex]
            ? paketEl.options[paketEl.selectedIndex].text.trim()
            : '—';
        var hari  = document.querySelector('select[name="jadwal"]').value;
        var jam   = document.querySelector('input[name="jam"]').value;

        // Format tanggal lahir
        var tglRaw = document.querySelector('input[name="tanggal_lahir"]').value;
        var tglFmt = tglRaw ? new Date(tglRaw).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'}) : '—';

        // Isi modal
        document.getElementById('kNamaLengkap').textContent  = document.querySelector('input[name="nama_lengkap"]').value || '—';
        document.getElementById('kJenisKelamin').textContent  = jk ? jk.value : '—';
        document.getElementById('kTempatLahir').textContent   = document.querySelector('input[name="tempat_lahir"]').value || '—';
        document.getElementById('kTanggalLahir').textContent  = tglFmt;
        document.getElementById('kAlamat').textContent        = document.querySelector('textarea[name="alamat"]').value || '—';
        document.getElementById('kAsalSekolah').textContent   = asal ? asal.value : '—';
        document.getElementById('kPaket').textContent         = paketText;
        document.getElementById('kJadwal').textContent        = (hari && jam) ? hari + ', ' + jam + ' WIB' : '—';
        document.getElementById('kNamaOrtu').textContent      = document.querySelector('input[name="nama_ortu"]').value || '—';
        document.getElementById('kNoWa').textContent          = '+62 ' + (document.querySelector('input[name="no_wa_ortu"]').value || '—');
        document.getElementById('kPekerjaanOrtu').textContent = document.querySelector('input[name="pekerjaan_ortu"]').value || '—';

        new bootstrap.Modal(document.getElementById('modalKonfirmasi')).show();
    });

    document.getElementById('btnSubmitFinal').addEventListener('click', function () {
        document.querySelector('form').submit();
    });
    </script>

<?php $this->load->view('template/footer'); ?>
