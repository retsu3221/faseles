    <footer class="bg-primary text-white py-4 mt-auto">
        <div class="container text-center">
            <h6 class="mb-0 fw-light">&copy; Copyright 2026, FASE LES - All Rights Reserved.</h6>
        </div>
    </footer>

    <!-- Toast Notification -->
    <?php
    $flash_success = $this->session->flashdata('success');
    $flash_error   = $this->session->flashdata('error');
    $flash_warning = $this->session->flashdata('warning');
    $flash_info    = $this->session->flashdata('info');
    $flash_logout  = $this->session->flashdata('logout');
    ?>

    <?php if ($flash_success || $flash_error || $flash_warning || $flash_info || $flash_logout): ?>
    <div class="toast-container" style="position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; display: flex; flex-direction: column; gap: 10px;">

        <?php if ($flash_success): ?>
        <div class="toast-notif toast-notif-success" role="alert" id="toastSuccess">
            <div class="toast-notif-icon"><i class="bi bi-check-lg"></i></div>
            <div class="toast-notif-body">
                <div class="toast-notif-title">Login Berhasil!</div>
                <div class="toast-notif-msg"><?= htmlspecialchars($flash_success); ?></div>
            </div>
            <button class="toast-notif-close" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
        </div>
        <?php endif; ?>

        <?php if ($flash_error): ?>
        <div class="toast-notif toast-notif-error" role="alert" id="toastError">
            <div class="toast-notif-icon"><i class="bi bi-x-lg"></i></div>
            <div class="toast-notif-body">
                <div class="toast-notif-title">Gagal</div>
                <div class="toast-notif-msg"><?= htmlspecialchars($flash_error); ?></div>
            </div>
            <button class="toast-notif-close" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
        </div>
        <?php endif; ?>

        <?php if ($flash_logout): ?>
        <div class="toast-notif toast-notif-error" role="alert" id="toastLogout">
            <div class="toast-notif-icon"><i class="bi bi-box-arrow-right"></i></div>
            <div class="toast-notif-body">
                <div class="toast-notif-title">Berhasil</div>
                <div class="toast-notif-msg"><?= htmlspecialchars($flash_logout); ?></div>
            </div>
            <button class="toast-notif-close" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
        </div>
        <?php endif; ?>

        <?php if ($flash_warning): ?>
        <div class="toast-notif toast-notif-warning" role="alert" id="toastWarning">
            <div class="toast-notif-icon"><i class="bi bi-exclamation-lg"></i></div>
            <div class="toast-notif-body">
                <div class="toast-notif-title">Peringatan</div>
                <div class="toast-notif-msg"><?= htmlspecialchars($flash_warning); ?></div>
            </div>
            <button class="toast-notif-close" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
        </div>
        <?php endif; ?>

        <?php if ($flash_info): ?>
        <div class="toast-notif toast-notif-info" role="alert" id="toastInfo">
            <div class="toast-notif-icon"><i class="bi bi-info-lg"></i></div>
            <div class="toast-notif-body">
                <div class="toast-notif-title">Info</div>
                <div class="toast-notif-msg"><?= htmlspecialchars($flash_info); ?></div>
            </div>
            <button class="toast-notif-close" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
        </div>
        <?php endif; ?>

    </div>

    <style>
    .toast-notif {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        border-radius: 12px;
        padding: 14px 16px;
        min-width: 300px;
        max-width: 360px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        border-left: 5px solid;
        animation: toastSlideIn 0.3s ease;
        opacity: 1;
        transition: opacity 0.3s ease;
    }
    .toast-notif.hiding { opacity: 0; }

    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateX(60px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .toast-notif-success { border-color: #198754; }
    .toast-notif-error   { border-color: #dc3545; }
    .toast-notif-warning { border-color: #f59e0b; }
    .toast-notif-info    { border-color: #0dcaf0; }

    .toast-notif-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        color: #fff;
    }
    .toast-notif-success .toast-notif-icon { background-color: #198754; }
    .toast-notif-error   .toast-notif-icon { background-color: #dc3545; }
    .toast-notif-warning .toast-notif-icon { background-color: #f59e0b; }
    .toast-notif-info    .toast-notif-icon { background-color: #0dcaf0; }

    .toast-notif-body { flex: 1; }
    .toast-notif-title {
        font-weight: 700;
        font-size: 0.85rem;
        color: #212529;
        margin-bottom: 2px;
    }
    .toast-notif-msg {
        font-size: 0.82rem;
        color: #6c757d;
        line-height: 1.4;
    }
    .toast-notif-close {
        background: none;
        border: none;
        color: #adb5bd;
        font-size: 0.8rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
        flex-shrink: 0;
        transition: color 0.2s;
    }
    .toast-notif-close:hover { color: #495057; }
    </style>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($flash_success || $flash_error || $flash_warning || $flash_info || $flash_logout): ?>
    <script>
    document.querySelectorAll('.toast-notif').forEach(function (el) {
        setTimeout(function () {
            el.classList.add('hiding');
            setTimeout(function () { el.remove(); }, 300);
        }, 5000);

        el.querySelector('.toast-notif-close').addEventListener('click', function () {
            el.classList.add('hiding');
            setTimeout(function () { el.remove(); }, 300);
        });
    });
    </script>
    <?php endif; ?>

</body>
</html>
