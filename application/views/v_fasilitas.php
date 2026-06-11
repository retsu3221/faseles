<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar', ['active_page' => 'fasilitas']); ?>

    <div class="hero-bg hero-bg-benefits text-center mb-5">
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">Fasilitas & Keuntungan</h1>
            <p class="fs-5 mt-2 mb-0" style="opacity: 0.9;">Komitmen kami memberikan nilai terbaik untuk masa depan pendidikan anak Anda.</p>
        </div>
    </div>

    <section id="brosur" class="py-4">
        <div class="container text-center">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-3 mx-auto" style="max-width: 900px;">
                <img src="<?= base_url('assets/img/Browsur.png'); ?>" alt="Brosur FASE Les" class="img-fluid rounded-3" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </section>

	<div class="container my-5 py-4 border-top" id="paketHarga">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase tracking-wide">Pilihan Terbaik</span>
            <h2 class="fw-bold mt-2">Daftar Kelas & Paket Harga</h2>
            <p class="text-muted">Pilih paket belajar yang paling sesuai dengan jenjang pendidikan dan kebutuhanmu di FASE Les.</p>
        </div>

        <div class="row g-4 justify-content-center">
            
            <!-- 1. PAKET TK & SD -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 border-top border-danger border-4 hover-lift">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="text-center mb-4">
                            <span class="badge bg-danger text-white rounded-pill px-3 py-2 mb-3">Tingkat TK & SD</span>
                            <h5 class="fw-bold text-muted mb-1">Privat</h5>
                            <h2 class="display-8 fw-bold text-dark mb-0">Rp 430.000<span class="fs-6 text-muted fw-normal">/8x Pertemuan</span></h2>
							<h5 class="fw-bold text-muted mt-3 mb-1">Kelompok</h5>
							<h2 class="display-8 fw-bold text-dark mb-0">Rp 380.000<span class="fs-6 text-muted fw-normal">/8x Pertemuan</span></h2>
                        </div>
                        <ul class="list-unstyled text-muted mb-4 flex-grow-1">
                            <li class="mb-3">&#10004; <strong class="text-dark">Durasi 45 - 60 Menit</strong> / Pertemuan</li>
                            <li class="mb-3">&#10004; <strong class="text-dark">Calistung</strong> & Pendampingan PR</li>
                            <li class="mb-3">&#10004; Konsep belajar <em>Fun Learning</em></li>
                            <li class="mb-3">&#10004; Pilihan: <strong>Privat atau Kelompok</strong></li>
                        </ul>
                        <a href="<?= site_url('pendaftaran/daftar'); ?>" class="btn btn-outline-danger fw-bold rounded-pill w-100 mt-auto">Pilih Paket SD</a>
                    </div>
                </div>
            </div>

            <!-- 2. PAKET SMP -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow border-0 border-top border-success border-4 position-relative">
                    <div class="card-body p-4 mt-2 d-flex flex-column">
                        <div class="text-center mb-4">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 mb-3 fw-bold">Tingkat SMP/MTs</span>
                            <h5 class="fw-bold text-muted mb-1">Privat</h5>
                            <h2 class="display-8 fw-bold text-dark mb-0">Rp 480.000<span class="fs-6 text-muted fw-normal">/8x Pertemuan</span></h2>
							<h5 class="fw-bold text-muted mt-3 mb-1">Kelompok</h5>
							<h2 class="display-8 fw-bold text-dark mb-0">Rp 430.000<span class="fs-6 text-muted fw-normal">/8x Pertemuan</span></h2>
                        </div>
                        <ul class="list-unstyled text-muted mb-4 flex-grow-1">
                            <li class="mb-3">&#10004; <strong class="text-dark">Durasi 45 - 60 Menit</strong> / Pertemuan</li>
                            <li class="mb-3">&#10004; Penguatan pemahaman konsep dasar</li>
                            <li class="mb-3">&#10004; Persiapan Ulangan & Ujian Sekolah</li>
                            <li class="mb-3">&#10004; Pilihan: <strong>Privat atau Kelompok</strong></li>
                        </ul>
                        <a href="<?= site_url('pendaftaran/daftar'); ?>" class="btn btn-success fw-bold rounded-pill w-100 mt-auto shadow-sm">Pilih Paket SMP</a>
                    </div>
                </div>
            </div>

            <!-- 3. PAKET SMA -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 border-top border-primary border-4 hover-lift">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="text-center mb-4">
                            <span class="badge bg-primary text-white rounded-pill px-3 py-2 mb-3">Tingkat SMA/SMK</span>
                            <h5 class="fw-bold text-muted mb-1">Privat</h5>
                            <h2 class="display-8 fw-bold text-dark mb-0">Rp 580.000<span class="fs-6 text-muted fw-normal">/8x Pertemuan</span></h2>
							<h5 class="fw-bold text-muted mt-3 mb-1">Kelompok</h5>
							<h2 class="display-8 fw-bold text-dark mb-0">Rp 530.000<span class="fs-6 text-muted fw-normal">/8x Pertemuan</span></h2>
                        </div>
                        <ul class="list-unstyled text-muted mb-4 flex-grow-1">
                            <li class="mb-3">&#10004; <strong class="text-dark">Durasi 45 - 60 Menit</strong> / Pertemuan</li>
                            <li class="mb-3">&#10004; Pembahasan soal level analisis & HOTS</li>
                            <li class="mb-3">&#10004; Fokus pendalaman materi UTBK/SNBT</li>
                            <li class="mb-3">&#10004; Pilihan: <strong>Privat atau Kelompok</strong></li>
                        </ul>
                        <a href="<?= site_url('pendaftaran/daftar'); ?>" class="btn btn-outline-primary fw-bold rounded-pill w-100 mt-auto">Pilih Paket SMA</a>
                    </div>
                </div>
            </div>
        </div>
	</div>

    <div class="container py-5 mb-4">
        <div class="row g-4 justify-content-center">

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 h-100 border-top border-success border-4">
                    <h4 class="fw-bold mb-4 text-success border-bottom pb-3">
                        &#128218; Layanan Yang Didapat
                    </h4>
                    <p class="mb-4">Di FASE Les, siswa akan mendapatkan fasilitas dan pelayanan prioritas berikut ini:</p>

                    <div class="service-item"><span>&#10004;</span> Pembelajaran sesuai kebutuhan (tidak disamaratakan)</div>
                    <div class="service-item"><span>&#10004;</span> Pendampingan PR & pemahaman materi sekolah</div>
                    <div class="service-item"><span>&#10004;</span> Penguatan konsep dasar sampai mahir</div>
                    <div class="service-item"><span>&#10004;</span> Latihan soal & pembahasan terarah</div>
                    <div class="service-item"><span>&#10004;</span> Persiapan ulangan / ujian</div>
                    <div class="service-item"><span>&#10004;</span> Metode belajar yang mudah dipahami</div>
                    <div class="service-item"><span>&#10004;</span> Suasana belajar yang nyaman & tidak menekan</div>
                    <div class="service-item"><span>&#10004;</span> Laporan perkembangan siswa secara berkala</div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 h-100 border-top border-primary border-4">
                    <h4 class="fw-bold mb-4 text-primary border-bottom pb-3">
                        &#128259; Alur Pembelajaran
                    </h4>

                    <div class="timeline-container mt-4">
                        <div class="timeline-step">
                            <div class="timeline-title">1. Konsultasi Awal</div>
                            <p class="timeline-text small">Orang tua menyampaikan kendala dan kebutuhan belajar anak.</p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-title">2. Analisis Kebutuhan</div>
                            <p class="timeline-text small">Menentukan metode yang paling efektif & memilihkan tentor yang sesuai karakter siswa.</p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-title">3. Penjadwalan</div>
                            <p class="timeline-text small">Waktu sangat fleksibel, disesuaikan dengan ketersediaan waktu siswa.</p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-title">4. Proses Pembelajaran</div>
                            <p class="timeline-text small">Fokus pada pemahaman materi sekolah ditambah dengan latihan intensif.</p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-title">5. Evaluasi Berkala</div>
                            <p class="timeline-text small">Melihat dan mengukur langsung perkembangan akademis siswa setiap bulan.</p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-title">6. Laporan ke Orang Tua</div>
                            <p class="timeline-text small">Update rutin hasil belajar agar orang tua merasa tenang dan anak terpantau.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var groups = [
                { selector: '#brosur .card',                extra: [],             stagger: 0 },
                { selector: '#paketHarga > .text-center',   extra: [],             stagger: 0 },
                { selector: '#paketHarga .card',            extra: [],             stagger: 0.15 },
                { selector: '.col-lg-6:first-child .card',  extra: ['from-left'],  stagger: 0 },
                { selector: '.col-lg-6:last-child .card',   extra: ['from-right'], stagger: 0 },
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
