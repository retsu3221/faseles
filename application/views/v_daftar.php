<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar'); ?>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
                        <h4 class="mb-0 fw-bold">Formulir Pendaftaran</h4>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="<?= site_url('pendaftaran/proses_daftar'); ?>" method="POST">
                            <!-- BAGIAN: DATA PRIBADI -->
                            <div class="form-section-title mt-0">Data Pribadi Siswa</div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label text-muted fw-bold">Nama Lengkap :</label>
                                    <input type="text" name="nama_lengkap" class="form-control bg-light" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Tempat :</label>
                                    <input type="text" name="tempat_lahir" class="form-control bg-light" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Tanggal Lahir :</label>
                                    <input type="date" name="tanggal_lahir" class="form-control bg-light" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label text-muted fw-bold d-block">Jenis Kelamin :</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input bg-primary" type="radio" name="jenis_kelamin" value="Laki-laki" id="jkLaki">
                                        <label class="form-check-label" for="jkLaki">Laki - laki</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input bg-primary" type="radio" name="jenis_kelamin" value="Perempuan" id="jkPerempuan">
                                        <label class="form-check-label" for="jkPerempuan">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Alamat Rumah :</label>
                                <textarea name="alamat" class="form-control bg-light" rows="2" required placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                            </div>

                            <!-- BAGIAN: DATA AKADEMIK & PILIHAN LES -->
                            <div class="form-section-title">Data Sekolah & Paket Les</div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold d-block">Tingkat Sekolah :</label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input bg-primary tingkat-sekolah" type="checkbox" name="asal_sekolah" value="TK" id="tingkatTK">
                                        <label class="form-check-label" for="tingkatTK">TK & SD</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input bg-primary tingkat-sekolah" type="checkbox" name="asal_sekolah" value="SMP" id="tingkatSMP">
                                        <label class="form-check-label" for="tingkatSMP">SMP/MTs</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input bg-primary tingkat-sekolah" type="checkbox" name="asal_sekolah" value="SMA" id="tingkatSMA">
                                        <label class="form-check-label" for="tingkatSMA">SMA/K</label>
                                    </div>
                                    <p class="text-muted small mt-1">*Pilih salah satu tingkat sekolah</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Kelas & Paket yang Diambil :</label>
                                    <select name="kelas" id="pilihanKelas" class="form-select bg-light" required>
                                        <option value="" disabled selected>-- Pilih Kelas & Paket --</option>

                                        <option value="Privat1" data-tingkat="TK">Kelas Privat TK & SD 1-6 -> Rp. 330.000 (45 Menit | 8x pertemuan)</option>
                                        <option value="Privat2" data-tingkat="TK">Kelas Privat TK & SD 1-6 -> Rp. 430.000 (60 Menit | 8x pertemuan)</option>
                                        <option value="Kelompok1" data-tingkat="TK">Kelas Kelompok TK & SD 1-6 -> Rp. 290.000 (45 Menit | 8x pertemuan)</option>
                                        <option value="Kelompok2" data-tingkat="TK">Kelas Kelompok TK & SD 1-6 -> Rp. 380.000 (60 Menit | 8x pertemuan)</option>

                                        <option value="Privat1" data-tingkat="SMP">Kelas Privat SMP -> Rp. 365.000 (45 Menit | 8x pertemuan)</option>
                                        <option value="Privat2" data-tingkat="SMP">Kelas Privat SMP -> Rp. 480.000 (60 Menit | 8x pertemuan)</option>
                                        <option value="Kelompok1" data-tingkat="SMP">Kelas Kelompok SMP -> Rp. 330.000 (45 Menit | 8x pertemuan)</option>
                                        <option value="Kelompok2" data-tingkat="SMP">Kelas Kelompok SMP -> Rp. 430.000 (60 Menit | 8x pertemuan)</option>

                                        <option value="Privat1" data-tingkat="SMA">Kelas Privat SMA/K -> Rp. 440.000 (45 Menit | 8x pertemuan)</option>
                                        <option value="Privat2" data-tingkat="SMA">Kelas Privat SMA/K -> Rp. 580.000 (60 Menit | 8x pertemuan)</option>
                                        <option value="Kelompok1" data-tingkat="SMA">Kelas Kelompok SMA/K -> Rp. 405.000 (45 Menit | 8x pertemuan)</option>
                                        <option value="Kelompok2" data-tingkat="SMA">Kelas Kelompok SMA/K -> Rp. 530.000 (60 Menit | 8x pertemuan)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label text-muted fw-bold">Jadwal Belajar (Hari & Jam) :</label>
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
                            </div>

                            <!-- BAGIAN: DATA ORANG TUA / WALI -->
                            <div class="form-section-title">Data Orang Tua / Wali</div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Nama Lengkap Orang Tua / Wali</label>
                                <input type="text" name="nama_ortu" class="form-control bg-light" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">No. WhatsApp Ortu</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">🇮🇩 +62</span>
                                        <input type="number" name="no_wa_ortu" class="form-control bg-light border-start-0" required>
                                        <p class="text-muted small mt-1">*Tanpa menggunakan 0</p>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fw-bold">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control bg-light" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold mt-4 shadow-sm">
                                Lanjut Pembayaran
                            </button>
                            <a href="<?= site_url('pendaftaran/index'); ?>" class="btn btn-danger btn-lg w-100 fw-bold mt-2 shadow-sm">
                                Kembali
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.tingkat-sekolah').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.tingkat-sekolah').forEach(other => {
                    if (other !== this) other.checked = false;
                });
            }

            const tingkatTerpilih = this.checked ? this.value : '';
            const selectKelas = document.getElementById('pilihanKelas');
            const options = selectKelas.querySelectorAll('option');

            selectKelas.value = "";

            options.forEach(option => {
                const tingkatOpsi = option.getAttribute('data-tingkat');

                if (!tingkatOpsi) return;

                if (tingkatTerpilih === '') {
                    option.style.display = 'none';
                    option.disabled = true;
                } else if (tingkatOpsi === tingkatTerpilih) {
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
        const selectKelas = document.getElementById('pilihanKelas');
        if (selectKelas) {
            selectKelas.querySelectorAll('option').forEach(option => {
                if (option.getAttribute('data-tingkat')) {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });
        }
    });
    </script>

<?php $this->load->view('template/footer'); ?>
