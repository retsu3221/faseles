<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar', ['active_page' => 'tentang_kami']); ?>

    <div class="hero-bg text-center shadow">
        <div class="container">
            <h1 class="display-5 fw-bold">Tentang Kami</h1>
            <p class="lead mt-3 fw-light">Mengenal lebih dekat perjalanan dan tujuan utama FASE Les.</p>
        </div>
    </div>

    <div class="container pb-5">

        <div class="row justify-content-center content-card-overlap mb-5">
            <div class="col-lg-10">
                <div class="card shadow border-0 rounded-4 p-4 p-md-5">
                    <h3 class="fw-bold text-center text-primary mb-4">FASE LES PRIVAT & KELOMPOK</h3>

                    <p style="text-align: justify; line-height: 1.8;">
                        Didirikan pada tanggal 11 Januari 2021 oleh <strong>Ibu Ulil Hikmah Pitasari</strong>. FASE LES berawal dari kegiatan les privat mandiri dengan jumlah siswa yang masih terbatas. Seiring meningkatnya kepercayaan orang tua terhadap kualitas pembelajaran, jumlah siswa terus bertambah.
                    </p>

                    <p style="text-align: justify; line-height: 1.8;">
                        Karena pendiri juga aktif mengajar di sekolah dasar, maka FASE LES kemudian mulai merekrut tenaga pengajar tambahan profesional yang masing-masing memiliki pengalaman dan kompetensi dalam bidangnya, mulai dari: <strong>Guru Prasekolah & PAUD, Guru SD, Guru SMP, hingga Guru SMA/K</strong>.
                    </p>

                    <div class="alert alert-info border-0 rounded-3 mt-4 mb-0" style="text-align: justify; line-height: 1.8;">
                        <strong>FASE LES PRIVAT & KELOMPOK</strong> adalah lembaga bimbingan belajar yang berfokus pada peningkatan prestasi akademik dan pengembangan karakter siswa. Kami hadir sebagai solusi belajar yang tidak hanya membantu siswa menyelesaikan tugas sekolah, tetapi juga memastikan mereka memahami materi, berkembang sesuai potensi, dan lebih percaya diri dalam belajar. Dengan metode pembelajaran yang disesuaikan dengan kebutuhan masing-masing siswa, serta didukung oleh tentor yang kompeten dan berpengalaman, FASE berkomitmen memberikan layanan yang amanah, profesional, dan terpercaya.
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">

            <div class="col-lg-4 col-md-10">
                <div class="card shadow-sm border-0 rounded-4 border-top border-warning border-4 h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-warning mb-3 text-center">🌿 VISI</h4>
                        <p style="line-height: 1.8;">
                            Menjadi lembaga bimbingan belajar yang unggul, amanah, dan terpercaya dalam mencetak siswa-siswi berprestasi, berkarakter, serta mampu berkembang sesuai potensi dan kebutuhan masing-masing.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-10">
                <div class="card shadow-sm border-0 rounded-4 border-top border-success border-4 h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-success mb-4 text-center">🌿 MISI</h4>
                        <ul class="list-unstyled" style="line-height: 1.8;">
                            <li class="mb-2">✅ Menyediakan pembelajaran yang berkualitas, efektif, dan terarah.</li>
                            <li class="mb-2">✅ Menerapkan metode belajar yang fleksibel dan disesuaikan dengan kebutuhan siswa.</li>
                            <li class="mb-2">✅ Mendorong siswa untuk mencapai prestasi akademik yang optimal.</li>
                            <li class="mb-2">✅ Menanamkan nilai disiplin, tanggung jawab, dan sikap positif dalam belajar.</li>
                            <li class="mb-2">✅ Menciptakan lingkungan belajar yang nyaman, suportif, dan komunikatif.</li>
                            <li class="mb-0">✅ Memberikan layanan yang profesional, amanah, dan konsisten.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var groups = [
                { selector: '.content-card-overlap .card',  extra: [],             stagger: 0 },
                { selector: '.col-lg-4 .card',              extra: ['from-left'],  stagger: 0 },
                { selector: '.col-lg-6 .card',              extra: ['from-right'], stagger: 0 },
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
