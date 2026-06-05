<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navbar', ['active_page' => 'kontak_kami']); ?>

    <div class="hero-bg text-center mb-5">
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">Hubungi Kami</h1>
            <p class="fs-5 mt-2 mb-0" style="opacity: 0.9;">Punya pertanyaan seputar program les atau biaya? Jangan ragu untuk menghubungi tim FASE Les.</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4 align-items-stretch justify-content-center">

            <div class="col-lg-5 col-md-10">
                <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 h-100 border-top border-primary border-4 bg-white">
                    <h4 class="fw-bold mb-4 border-bottom pb-3">Informasi Kontak</h4>

                    <div class="d-flex align-items-start mb-4 mt-3">
                        <div class="icon-box me-3 shadow-sm">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Lokasi Kami</h6>
                            <p class="text-muted">Perum Pondok Jaya Indah Blok E1/21 RT 52 RW 13, Munjul Jaya - Purwakarta 41117</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-box me-3 shadow-sm text-success">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Telepon & WhatsApp</h6>
                            <p class="text-muted">+62 857-9557-4037<br><small>Senin - Sabtu (08:00 - 17:00 WIB)</small></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-box me-3 shadow-sm text-danger">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email</h6>
                            <p class="text-muted">faselesprivatdankelompok@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-10">
                <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 h-100 border-top border-success border-4 bg-white">
                    <h4 class="fw-bold mb-4 text-center border-bottom pb-3">Kirim Pesan</h4>

                    <form id="formKontak" onsubmit="kirimKeWhatsApp(event)" class="mt-3">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Nama Lengkap</label>
                            <input type="text" id="wa_nama" class="form-control bg-light py-2" required placeholder="Masukkan nama Anda">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Email atau No. WhatsApp</label>
                            <input type="text" id="wa_kontak" class="form-control bg-light py-2" required placeholder="Agar kami bisa membalas pesan Anda">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Subjek Pesan</label>
                            <select id="wa_subjek" class="form-select bg-light py-2" required>
                                <option value="" disabled selected>-- Pilih Topik --</option>
                                <option value="Pertanyaan Paket Les">Pertanyaan Paket Les</option>
                                <option value="Kendala Pendaftaran">Kendala Pendaftaran</option>
                                <option value="Konsultasi Jadwal">Konsultasi Jadwal</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small">Isi Pesan</label>
                            <textarea id="wa_pesan" class="form-control bg-light" rows="4" required placeholder="Tuliskan detail pertanyaan atau pesan Anda di sini..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                            Kirim Pesan <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var groups = [
                { selector: '.col-lg-5 .card',  extra: ['from-left'],  stagger: 0 },
                { selector: '.col-lg-6 .card',  extra: ['from-right'], stagger: 0 },
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

    <script>
    function kirimKeWhatsApp(event) {
        event.preventDefault();

        const nomorAdmin = "6285795574037";
        const nama = document.getElementById("wa_nama").value;
        const kontak = document.getElementById("wa_kontak").value;
        const subjek = document.getElementById("wa_subjek").value;
        const pesan = document.getElementById("wa_pesan").value;

        let formatPesan = "Halo Admin FASE Les, saya ingin bertanya.%0A%0A";
        formatPesan += "*Nama :* " + nama + "%0A";
        formatPesan += "*Kontak Balasan :* " + kontak + "%0A";
        formatPesan += "*Topik :* " + subjek + "%0A%0A";
        formatPesan += "*Pesan :*%0A" + pesan;

        const urlWhatsApp = "https://wa.me/" + nomorAdmin + "?text=" + formatPesan;
        window.open(urlWhatsApp, "_blank");
    }
    </script>

<?php $this->load->view('template/footer'); ?>
