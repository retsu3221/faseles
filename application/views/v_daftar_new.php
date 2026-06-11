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
                                <input type="text" name="nama_lengkap" class="form-control bg-light" required placeholder="Masukkan nama lengkap siswa">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control bg-light" required placeholder="Kota tempat lahir">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control bg-light" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold d-block">Jenis Kelamin</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" id="jkLaki">
                                        <label class="form-check-label" for="jkLaki">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" id="jkPerempuan">
                                        <label class="form-check-label" for="jkPerempuan">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Alamat Rumah</label>
                                <textarea name="alamat" class="form-control bg-light" rows="2" required placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                            </div>

                            <div class="form-section-title">&#128218; Data Sekolah & Paket Les</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold d-block">Tingkat Sekolah</label>
                                <div class="d-flex gap-3 mt-1 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input tingkat-sekolah" type="checkbox" name="asal_sekolah" value="TK" id="tingkatTK">
                                        <label class="form-check-label" for="tingkatTK">TK & SD</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input tingkat-sekolah" type="checkbox" name="asal_sekolah" value="SMP" id="tingkatSMP">
                                        <label class="form-check-label" for="tingkatSMP">SMP / MTs</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input tingkat-sekolah" type="checkbox" name="asal_sekolah" value="SMA" id="tingkatSMA">
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
                                <input type="text" name="nama_ortu" class="form-control bg-light" required placeholder="Nama lengkap orang tua atau wali">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">No. WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">&#127470;&#127465; +62</span>
                                        <input type="number" name="no_wa_ortu" class="form-control bg-light border-start-0" required placeholder="8xxxxxxxxxx">
                                    </div>
                                    <p class="text-muted small mt-1 mb-0">*Tanpa angka 0 di depan</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control bg-light" required placeholder="Contoh: Wiraswasta">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
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
    </script>

<?php $this->load->view('template/footer'); ?>
