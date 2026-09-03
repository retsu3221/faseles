            </div>
            <!-- End Page Content -->

        </div>
        <!-- End #content -->

        <!-- Footer -->
        <footer class="sticky-footer mt-auto">
            <div class="container">
                <div class="text-center text-muted small">
                    &copy; 2026 FASE Les &mdash; All Rights Reserved.
                </div>
            </div>
        </footer>

    </div>
    <!-- End #content-wrapper -->

</div>
<!-- End #wrapper -->

<!-- Scroll to Top -->
<a class="scroll-to-top" href="#page-top" id="scrollTop">
    <i class="bi bi-chevron-up"></i>
</a>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Konfirmasi Keluar</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small text-muted">
                Apakah Anda yakin ingin keluar dari sesi admin ini?
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a class="btn btn-sm btn-danger" href="<?= site_url('admin/logout'); ?>">
                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<?php
$flash_success = $this->session->flashdata('success');
$flash_error   = $this->session->flashdata('error');
$flash_warning = $this->session->flashdata('warning');
$flash_info    = $this->session->flashdata('info');
?>
<?php if ($flash_success || $flash_error || $flash_warning || $flash_info): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999;">

    <?php if ($flash_success): ?>
    <div class="toast align-items-center text-bg-success border-0 show mb-2" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= htmlspecialchars($flash_success) ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($flash_error): ?>
    <div class="toast align-items-center text-bg-danger border-0 show mb-2" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= htmlspecialchars($flash_error) ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($flash_warning): ?>
    <div class="toast align-items-center text-bg-warning border-0 show mb-2" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= htmlspecialchars($flash_warning) ?></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($flash_info): ?>
    <div class="toast align-items-center text-bg-info border-0 show mb-2" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= htmlspecialchars($flash_info) ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php endif; ?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
    var wrapper    = document.getElementById('wrapper');
    var toggleBtn  = document.getElementById('sidebarToggle');
    var toggleTop  = document.getElementById('sidebarToggleTop');
    var toggleIcon = document.getElementById('sidebarToggleIcon');
    var scrollBtn  = document.getElementById('scrollTop');
    var isMobile   = function () { return window.innerWidth < 768; };

    function setSidebarIcon() {
        if (!toggleIcon) return;
        toggleIcon.className = wrapper.classList.contains('sidebar-toggled')
            ? 'bi bi-chevron-right'
            : 'bi bi-chevron-left';
    }

    // Tombol chevron di dalam sidebar — desktop: collapse/expand, mobile: tutup
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            wrapper.classList.toggle('sidebar-toggled');
            setSidebarIcon();
            if (!isMobile()) {
                localStorage.setItem('adminSidebarToggled', wrapper.classList.contains('sidebar-toggled'));
            }
        });
    }

    // Hamburger di topbar (mobile: buka sidebar)
    if (toggleTop) {
        toggleTop.addEventListener('click', function () {
            wrapper.classList.toggle('sidebar-toggled');
        });
    }

    // Klik overlay untuk tutup sidebar (mobile)
    document.addEventListener('click', function (e) {
        if (!isMobile() || !wrapper.classList.contains('sidebar-toggled')) return;
        var sidebar = document.getElementById('adminSidebar');
        if (sidebar && !sidebar.contains(e.target) && toggleTop && !toggleTop.contains(e.target)) {
            wrapper.classList.remove('sidebar-toggled');
            setSidebarIcon();
        }
    });

    // Restore state dari localStorage — desktop only
    if (!isMobile() && localStorage.getItem('adminSidebarToggled') === 'true') {
        wrapper.classList.add('sidebar-toggled');
        setSidebarIcon();
    }

    // Scroll to top button
    if (scrollBtn) {
        window.addEventListener('scroll', function () {
            scrollBtn.classList.toggle('visible', window.scrollY > 200);
        });
        scrollBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Auto-dismiss toasts after 5s
    document.querySelectorAll('.toast.show').forEach(function (el) {
        setTimeout(function () {
            bootstrap.Toast.getOrCreateInstance(el).hide();
        }, 5000);
    });
}());
</script>

</body>
</html>
