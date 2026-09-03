<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar', ['active_page' => 'home']); ?>

    <header id="beranda" class="hero-section text-center py-5">
        <div class="container my-5">
            <h1 class="display-3 fw-bold">"Tumbuh Sesuai Potensi, Berprestasi dengan Percaya Diri"</h1>
            <p class="lead mt-3">Temukan tentor terbaik untuk mendukung kesuksesan akademikmu dalam proses belajar yang menyenangkan.</p>
            <a href="<?= site_url('pendaftaran/daftar'); ?>" class="btn btn-lg btn-warning fw-bold mt-4 shadow-sm">Mulai Belajar Hari Ini</a>
        </div>
    </header>

    <section id="keunggulan" class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-5">🌟 NILAI UTAMA</h2>

            <div class="row justify-content-center g-4">
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100 bg-white">
                        <h4 class="text-primary fw-bold">Amanah</h4>
                        <p class="text-muted mb-0">Pengajar dari lulusan universitas ternama yang berpengalaman di bidangnya.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100 bg-white">
                        <h4 class="text-primary fw-bold">Profesional</h4>
                        <p class="text-muted mb-0">Atur jadwal belajarmu sendiri tanpa mengganggu aktivitas lainnya.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100 bg-white">
                        <h4 class="text-primary fw-bold">Disiplin</h4>
                        <p class="text-muted mb-0">Kurikulum yang selalu diperbarui sesuai dengan kebutuhan standar pendidikan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100 bg-white">
                        <h4 class="text-primary fw-bold">Adaptif</h4>
                        <p class="text-muted mb-0">Kurikulum yang selalu diperbarui sesuai dengan kebutuhan standar pendidikan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100 bg-white">
                        <h4 class="text-primary fw-bold">Peduli</h4>
                        <p class="text-muted mb-0">Kurikulum yang selalu diperbarui sesuai dengan kebutuhan standar pendidikan.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="kegiatan" class="py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold mb-5 text-center">📚 Kegiatan Belajar</h2>

            <div class="row justify-content-center">

                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="<?= base_url('assets/img/foto1.png'); ?>" class="card-img-top" alt="Matematika" style="height: 250px; object-fit: contain; background-color: #e9ecef;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-center text-danger">FASE Les (Privat SD)</h5>
                            <p class="card-text" style="font-size: 0.9rem;">
                                Hari : Sabtu, 2 Mei 2026 <br>
                                Jam : 08.00 - 09.00 <br>
                                Nama Siswa : Cedric <br>
                                Fase : Sekolah Dasar (SD) <br>
                                Tentor : Zahra <br>
                                Pertemuan : 4 <br>
                                Materi : <br> Tahfizh (murajaah Surat Al Insyiqaq, Surat An Naas-Surat Al Kafirun, ziyadah Surat Al Insan 4-5, mengenal hukum bacaan ghunnah, mencari ghunnah dalam Surat Al Insan, dan game sambung ayat).
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <video class="card-img-top" controls style="height: 250px; width: 100%; object-fit: cover; background-color: #000;">
                            <source src="<?= base_url('assets/img/video1.mp4'); ?>" type="video/mp4">
                            Maaf, browser Anda tidak mendukung pemutaran video.
                        </video>
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-center text-success">FASE Les (Privat TK)</h5>
                            <p class="card-text" style="font-size: 0.9rem;">
                                Hari : Jum'at, 15 Mei 2026 <br>
                                Jam : 19.10 - 20.10 <br>
                                Nama Siswa : Resik <br>
                                Fase : Taman Kanak-Kanak (TK) <br>
                                Tentor : Mega <br>
                                Pertemuan : 9 (Kontrak belajar baru) <br>
                                Materi : <br>  Bacalah 2 (Awalan kata bar-bir-bur-ber) || Fun Learning with shameeka (memindahkan tutup botol, gelang kertas dan gelas plastik).
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="<?= base_url('assets/img/Foto3.png'); ?>" class="card-img-top" alt="Informatika" style="height: 250px; object-fit: contain; background-color: #e9ecef;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-center text-primary">FASE Les (Privat SMK)</h5>
                            <p class="card-text" style="font-size: 0.9rem;">
                                Hari : Rabu, 4 April 2026 <br>
                                Jam : 16.10 - 17.10 <br>
                                Nama Siswa : Beryl <br>
                                Fase : Sekolah Menengah Kejuruan (SMK) <br>
                                Tentor : Inggia <br>
                                Pertemuan : 1 <br>
                                Materi : <br>  Cara menulis dan membaca bahasa jepang.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="informasi-program" class="py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">

                <div class="col-md-5 mb-4">
                    <div class="content-box p-4 bg-white rounded shadow-sm h-100 border-top border-success border-4">
                        <h5 class="fw-bold text-center text-success mb-4">Program Fase Les</h5>

                        <p class="mb-3">
                            <strong class="text-dark">Privat :</strong><br />
                            Bimbingan belajar kelas privat dilakukan secara tatap muka dengan 1–2 siswa per sesi, sehingga pengajar dapat memberikan perhatian lebih, menyesuaikan materi dengan kebutuhan siswa, dan memberikan umpan balik secara personal.
                        </p>

                        <p>
                            <strong class="text-dark">Kelompok :</strong><br />
                            Bimbingan belajar kelas kelompok dilakukan di tempat pengajar dengan jumlah siswa sekitar 3-6 orang per sesi.
                        </p>
                    </div>
                </div>

                <div class="col-md-5 mb-4">
                    <div class="content-box p-4 bg-white rounded shadow-sm h-100 border-top border-primary border-4 text-center">
                        <img src="<?= base_url('assets/img/Logo.png'); ?>" alt="Brosur" class="img-fluid mb-4" style="max-width: 80%; height: auto;">
                        <p>
                            <strong>📍 Alamat :</strong><br />
                            Perum Pondok Jaya Indah Blok E1 No 21<br>RT 52 RW 13, Munjul Jaya - Purwakarta 41117
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var groups = [
                { selector: '#keunggulan h2',                                       extra: [],              stagger: 0 },
                { selector: '#keunggulan .col-md-4 > div',                          extra: [],              stagger: 0.12 },
                { selector: '#kegiatan h2',                                         extra: [],              stagger: 0 },
                { selector: '#kegiatan .card',                                      extra: [],              stagger: 0.15 },
                { selector: '#informasi-program .col-md-5:first-child .content-box',extra: ['from-left'],   stagger: 0 },
                { selector: '#informasi-program .col-md-5:last-child .content-box', extra: ['from-right'],  stagger: 0 },
                { selector: 'footer',                                               extra: [],              stagger: 0 },
            ];

            groups.forEach(function (group) {
                document.querySelectorAll(group.selector).forEach(function (el, i) {
                    el.classList.add('will-anim');
                    group.extra.forEach(function (cls) { el.classList.add(cls); });
                    if (group.stagger) el.style.transitionDelay = (i * group.stagger) + 's';
                });
            });

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('appeared');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.will-anim').forEach(function (el) {
                observer.observe(el);
            });
        });
    </script>

<?php $this->load->view('template/footer'); ?>
