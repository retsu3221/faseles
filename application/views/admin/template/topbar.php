            <!-- ===== TOPBAR ===== -->
            <nav class="topbar mb-4">

                <!-- Sidebar Toggle (Mobile) -->
                <button id="sidebarToggleTop" class="btn btn-link me-2 d-md-none p-0">
                    <i class="bi bi-list fs-4 text-secondary"></i>
                </button>

                <!-- Page Title -->
                <h6 class="mb-0 fw-bold text-secondary me-auto">
                    <?= htmlspecialchars($page_title ?? 'Dashboard') ?>
                </h6>

                <div class="d-flex align-items-center gap-2">
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <a href="#"
                           class="d-flex align-items-center gap-2 text-decoration-none"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none d-lg-inline text-secondary small fw-bold">
                                <?= htmlspecialchars($this->session->userdata('username') ?? 'Admin') ?>
                            </span>
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                 style="width:2rem;height:2rem;flex-shrink:0;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <span class="dropdown-item-text small text-muted">
                                    Login sebagai <strong><?= htmlspecialchars($this->session->userdata('username') ?? '') ?></strong>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                   data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </nav>
            <!-- ===== END TOPBAR ===== -->

            <!-- Begin Page Content -->
            <div class="container-fluid px-4 pb-4">
