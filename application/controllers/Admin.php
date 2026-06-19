<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    // Method yang boleh diakses tanpa login
    private $public_methods = ['login', 'login_proses', 'setup_admin'];

    public function __construct() {
        parent::__construct();

        $method = $this->router->fetch_method();
        if (!in_array($method, $this->public_methods) && !$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }
    }

    // Halaman login admin
    public function login() {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        $this->load->view('admin/v_login');
    }

    // Proses login admin
    public function login_proses() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        $admin = $this->db->get_where('admin', ['username' => $username])->row_array();

        if ($admin && password_verify($password, $admin['password'])) {
            $this->session->set_userdata([
                'admin_logged_in' => TRUE,
                'admin_id'        => $admin['id'],
                'admin_username'  => $admin['username'],
                'admin_nama'      => $admin['nama_lengkap'],
                'admin_role'      => (int)$admin['role'],
            ]);
            redirect('admin');
        }

        $this->session->set_flashdata('error', 'Username atau password salah.');
        redirect('admin/login');
    }

    // Logout admin
    public function logout() {
        $this->session->unset_userdata(['admin_logged_in', 'admin_id', 'admin_username', 'admin_nama', 'admin_role']);
        redirect('admin/login');
    }

    // Cek apakah admin yang login adalah role 1 (super admin)
    private function cek_role_super() {
        if ((int)$this->session->userdata('admin_role') !== 1) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('admin');
        }
    }

    // Setup akun admin pertama — akses SEKALI lalu hapus method ini
    public function setup_admin() {
        $cek = $this->db->count_all('admin');
        if ($cek > 0) {
            echo 'Akun admin sudah ada. Hapus method setup_admin() dari controller.';
            return;
        }
        $this->db->insert('admin', [
            'username'     => 'admin',
            'password'     => password_hash('admin123', PASSWORD_BCRYPT),
            'nama_lengkap' => 'Administrator',
        ]);
        echo 'Akun admin berhasil dibuat.<br>Username: <b>admin</b><br>Password: <b>admin123</b><br><br>Segera hapus method setup_admin() dari controller!';
    }

    // Dashboard utama admin
    public function index() {
        // --- Stat cards ---
        $stats = $this->db->query("
            SELECT
                COUNT(*) AS total_peserta,
                SUM(CASE WHEN status = 'pending'    THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN status = 'diterima'   THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN status = 'ditolak'    THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN status = 'kadaluarsa' THEN 1 ELSE 0 END) AS total_kadaluarsa,
                SUM(CASE WHEN status IS NULL        THEN 1 ELSE 0 END) AS total_belum_upload
            FROM (
                SELECT (
                    SELECT bp.status_verifikasi FROM bukti_pembayaran bp
                    WHERE bp.pendaftaran_id = p.id
                    ORDER BY bp.uploaded_at DESC LIMIT 1
                ) AS status
                FROM pendaftaran p
            ) sub
        ")->row_array();

        $tahun_ini = date('Y');
        $bulan_ini = date('m');
        $nama_bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];

        // --- Data bulanan untuk grafik dashboard ---
        $bulanan_raw = $this->db->query("
            SELECT
                MONTH(p.tanggal_daftar)  AS bulan,
                COUNT(p.id)              AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima' THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            WHERE YEAR(p.tanggal_daftar) = {$tahun_ini}
            GROUP BY MONTH(p.tanggal_daftar)
        ")->result_array();

        $bulanan = array_fill(1, 12, ['total_daftar' => 0, 'total_pemasukan' => 0]);
        foreach ($bulanan_raw as $row) {
            $bulanan[(int)$row['bulan']] = $row;
        }

        // --- Ringkasan bulan ini untuk dashboard ---
        $rows_bulan = $this->db->query("
            SELECT pk.harga,
                   COALESCE(bp.status_verifikasi, 'belum_upload') AS status_verifikasi
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            WHERE MONTH(p.tanggal_daftar) = {$bulan_ini}
              AND YEAR(p.tanggal_daftar)  = {$tahun_ini}
        ")->result_array();

        $rekap_bulan = [
            'total_daftar'    => count($rows_bulan),
            'total_diterima'  => count(array_filter($rows_bulan, fn($r) => $r['status_verifikasi'] === 'diterima')),
            'total_pending'   => count(array_filter($rows_bulan, fn($r) => $r['status_verifikasi'] === 'pending')),
            'total_pemasukan' => array_sum(array_column(
                array_filter($rows_bulan, fn($r) => $r['status_verifikasi'] === 'diterima'), 'harga'
            )),
        ];

        // --- Rekap tahunan ---
        $tahunan = $this->db->query("
            SELECT
                YEAR(p.tanggal_daftar)   AS tahun,
                COUNT(p.id)              AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima' THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            GROUP BY YEAR(p.tanggal_daftar)
            ORDER BY tahun DESC
        ")->result_array();

        // --- Pendaftaran terbaru ---
        $recent = $this->db->query("
            SELECT u.nama_lengkap, p.tanggal_daftar, pk.tingkat, pk.tipe_kelas,
                   COALESCE((
                       SELECT bp.status_verifikasi FROM bukti_pembayaran bp
                       WHERE bp.pendaftaran_id = p.id
                       ORDER BY bp.uploaded_at DESC LIMIT 1
                   ), 'belum_upload') AS status_verifikasi
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN users u  ON u.id  = p.user_id
            ORDER BY p.tanggal_daftar DESC
            LIMIT 8
        ")->result_array();

        $data = [
            'page_title'      => 'Dashboard',
            'active_menu'     => 'dashboard',
            'stats'           => $stats,
            'rekap_bulan'     => $rekap_bulan,
            'nama_bulan_ini'  => $nama_bulan[(int)$bulan_ini],
            'tahun_ini'       => $tahun_ini,
            'bulanan'         => $bulanan,
            'tahunan'         => $tahunan,
            'recent'          => $recent,
        ];

        $this->load->view('admin/v_dashboard', $data);
    }

    // Halaman daftar peserta (users)
    public function peserta() {
        $users = $this->db->select('u.id, u.username, u.email, u.created_at,
            u.nama_lengkap, u.tempat_lahir, u.tanggal_lahir, u.jenis_kelamin,
            u.alamat, u.asal_sekolah, u.nama_ortu, u.no_wa_ortu, u.pekerjaan_ortu,
            COUNT(p.id) as jumlah_pendaftaran')
            ->from('users u')
            ->join('pendaftaran p', 'p.user_id = u.id', 'left')
            ->group_by('u.id')
            ->order_by('u.created_at', 'DESC')
            ->get()->result_array();

        $data = [
            'page_title'  => 'Data Peserta',
            'active_menu' => 'peserta',
            'users'       => $users,
        ];

        $this->load->view('admin/v_peserta', $data);
    }


    // ===== ADMIN =====

    public function admin_list() {
        $this->cek_role_super();
        $data = [
            'page_title'  => 'Data Admin',
            'active_menu' => 'admin',
            'admins'      => $this->db->order_by('created_at', 'ASC')->get('admin')->result_array(),
        ];
        $this->load->view('admin/v_admin_list', $data);
    }

    public function tambah_admin() {
        $this->cek_role_super();
        $username    = $this->input->post('username', TRUE);
        $nama        = $this->input->post('nama_lengkap', TRUE);
        $password    = $this->input->post('password');
        $konfirmasi  = $this->input->post('konfirmasi_password');

        if (strlen($password) < 6) {
            $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
            redirect('admin/admin_list');
            return;
        }
        if ($password !== $konfirmasi) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
            redirect('admin/admin_list');
            return;
        }
        if ($this->db->get_where('admin', ['username' => $username])->num_rows() > 0) {
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect('admin/admin_list');
            return;
        }

        $role = (int)$this->input->post('role') === 1 ? 1 : 2;
        $this->db->insert('admin', [
            'username'     => $username,
            'nama_lengkap' => $nama,
            'password'     => password_hash($password, PASSWORD_BCRYPT),
            'role'         => $role,
        ]);

        $this->session->set_flashdata('success', 'Admin baru berhasil ditambahkan.');
        redirect('admin/admin_list');
    }

    public function update_admin($id = null) {
        $this->cek_role_super();
        if (!$id) redirect('admin/admin_list');

        $username = $this->input->post('username', TRUE);
        $nama     = $this->input->post('nama_lengkap', TRUE);
        $role     = (int)$this->input->post('role') === 1 ? 1 : 2;
        $password = $this->input->post('password');
        $konfirmasi = $this->input->post('konfirmasi_password');

        // Cek username unik (kecuali milik sendiri)
        $cek = $this->db->where('username', $username)->where('id !=', $id)->get('admin')->num_rows();
        if ($cek > 0) {
            $this->session->set_flashdata('error', 'Username sudah digunakan admin lain.');
            redirect('admin/admin_list');
            return;
        }

        $data = [
            'nama_lengkap' => $nama,
            'username'     => $username,
            'role'         => $role,
        ];

        if (!empty($password)) {
            if (strlen($password) < 6) {
                $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
                redirect('admin/admin_list');
                return;
            }
            if ($password !== $konfirmasi) {
                $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
                redirect('admin/admin_list');
                return;
            }
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->db->update('admin', $data, ['id' => $id]);

        // Perbarui session jika admin mengedit dirinya sendiri
        if ((int)$id === (int)$this->session->userdata('admin_id')) {
            $this->session->set_userdata([
                'admin_username' => $username,
                'admin_nama'     => $nama,
                'admin_role'     => $role,
            ]);
        }

        $this->session->set_flashdata('success', 'Data admin berhasil diperbarui.');
        redirect('admin/admin_list');
    }

    public function hapus_admin($id = null) {
        $this->cek_role_super();
        if (!$id) redirect('admin/admin_list');

        // Jangan hapus diri sendiri
        if ((int)$id === (int)$this->session->userdata('admin_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus akun admin yang sedang aktif.');
            redirect('admin/admin_list');
            return;
        }

        $this->db->delete('admin', ['id' => $id]);
        $this->session->set_flashdata('success', 'Admin berhasil dihapus.');
        redirect('admin/admin_list');
    }

    // ===== REKAP TAHUNAN =====

    public function rekap_tahunan() {
        $this->cek_role_super();
        // Rentang tahun: dari uploaded_at bukti_pembayaran
        $tahun_list = $this->db->query("
            SELECT DISTINCT YEAR(uploaded_at) AS tahun FROM bukti_pembayaran ORDER BY tahun DESC
        ")->result_array();
        if (empty($tahun_list)) $tahun_list = [['tahun' => date('Y')]];

        $tahun_aktif = (int)($this->input->get('tahun') ?: date('Y'));

        // Ringkasan per bulan untuk tahun yang dipilih
        $bulanan = $this->db->query("
            SELECT
                MONTH(bp.uploaded_at) AS bulan,
                COUNT(*)              AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN bp.status_verifikasi = 'pending'                 THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN bp.status_verifikasi IN ('ditolak','kadaluarsa') THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM bukti_pembayaran bp
            JOIN pendaftaran p  ON p.id  = bp.pendaftaran_id
            JOIN paket pk       ON pk.id = p.paket_id
            WHERE YEAR(bp.uploaded_at) = {$tahun_aktif}
            GROUP BY MONTH(bp.uploaded_at)
            ORDER BY bulan
        ")->result_array();

        // Indeks per bulan (1–12) agar mudah dipetakan di view
        $bulanan_map = [];
        foreach ($bulanan as $b) {
            $bulanan_map[(int)$b['bulan']] = $b;
        }

        // Summary keseluruhan tahun
        $sum = $this->db->query("
            SELECT
                COUNT(*) AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN bp.status_verifikasi = 'pending'                 THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN bp.status_verifikasi IN ('ditolak','kadaluarsa') THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM bukti_pembayaran bp
            JOIN pendaftaran p  ON p.id  = bp.pendaftaran_id
            JOIN paket pk       ON pk.id = p.paket_id
            WHERE YEAR(bp.uploaded_at) = {$tahun_aktif}
        ")->row_array();

        $data = [
            'page_title'  => 'Rekap Tahunan',
            'active_menu' => 'rekap_tahunan',
            'tahun_list'  => $tahun_list,
            'tahun_aktif' => $tahun_aktif,
            'bulanan_map' => $bulanan_map,
            'summary'     => $sum,
        ];
        $this->load->view('admin/v_rekap_tahunan', $data);
    }

    // ===== REKAP BULANAN =====

    public function rekap_bulanan() {
        $this->cek_role_super();
        $bulan = (int) ($this->input->get('bulan') ?: date('m'));
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));

        // Rekap hanya dari bukti_pembayaran — aktivitas pembayaran yang nyata
        $rows = $this->db->query("
            SELECT p.no_transaksi, u.nama_lengkap, p.tanggal_daftar,
                   pk.tingkat, pk.tipe_kelas, pk.harga,
                   bp.status_verifikasi,
                   bp.nama_pengirim, bp.jumlah_transfer, bp.tanggal_transfer, bp.uploaded_at
            FROM bukti_pembayaran bp
            JOIN pendaftaran p  ON p.id  = bp.pendaftaran_id
            JOIN paket pk       ON pk.id = p.paket_id
            JOIN users u        ON u.id  = p.user_id
            WHERE MONTH(bp.uploaded_at) = {$bulan}
              AND YEAR(bp.uploaded_at)  = {$tahun}
            ORDER BY bp.uploaded_at ASC
        ")->result_array();

        // Hitung summary
        $summary = [
            'total_daftar'    => count($rows),
            'total_diterima'  => count(array_filter($rows, fn($r) => $r['status_verifikasi'] === 'diterima')),
            'total_pending'   => count(array_filter($rows, fn($r) => $r['status_verifikasi'] === 'pending')),
            'total_ditolak'   => count(array_filter($rows, fn($r) => in_array($r['status_verifikasi'], ['ditolak','kadaluarsa']))),
            'total_pemasukan' => array_sum(array_column(
                array_filter($rows, fn($r) => $r['status_verifikasi'] === 'diterima'), 'harga'
            )),
        ];

        // Rentang tahun untuk dropdown: dari uploaded_at bukti_pembayaran
        $tahun_list = $this->db->query("
            SELECT DISTINCT YEAR(uploaded_at) AS tahun FROM bukti_pembayaran ORDER BY tahun DESC
        ")->result_array();
        if (empty($tahun_list)) $tahun_list = [['tahun' => date('Y')]];

        $data = [
            'page_title'  => 'Rekap Bulanan',
            'active_menu' => 'rekap_bulanan',
            'rows'        => $rows,
            'summary'     => $summary,
            'bulan_aktif' => $bulan,
            'tahun_aktif' => $tahun,
            'tahun_list'  => $tahun_list,
        ];

        $this->load->view('admin/v_rekap_bulanan', $data);
    }

    // ===== PEMBAYARAN =====

    public function pembayaran() {
        $data = [
            'page_title'  => 'Data Pembayaran',
            'active_menu' => 'pembayaran',
            'users'       => $this->db->order_by('nama_lengkap', 'ASC')->get('users')->result_array(),
            'paket'       => $this->db->order_by('tingkat', 'ASC')->get('paket')->result_array(),
            'pendaftaran' => $this->db->query("
                SELECT p.id, p.no_transaksi, u.nama_lengkap, p.tanggal_daftar,
                       pk.tingkat, pk.tipe_kelas, pk.harga,
                       u.username,
                       bp.id              AS bp_id,
                       bp.file_bukti, bp.nama_pengirim, bp.jumlah_transfer,
                       bp.tanggal_transfer, bp.catatan, bp.catatan_admin,
                       bp.status_verifikasi, bp.uploaded_at
                FROM pendaftaran p
                LEFT JOIN paket pk ON pk.id = p.paket_id
                LEFT JOIN users  u  ON u.id  = p.user_id
                LEFT JOIN bukti_pembayaran bp
                       ON bp.id = (
                           SELECT id FROM bukti_pembayaran
                           WHERE pendaftaran_id = p.id
                           ORDER BY uploaded_at DESC LIMIT 1
                       )
                ORDER BY p.tanggal_daftar DESC
            ")->result_array(),
        ];
        $this->load->view('admin/v_pembayaran', $data);
    }

    // Tambah pendaftaran + opsional bukti pembayaran dari admin
    public function tambah_pembayaran() {
        $user_id     = (int) $this->input->post('user_id');
        $paket_id    = (int) $this->input->post('paket_id');
        $jadwal_hari = $this->input->post('jadwal_hari', TRUE);
        $jadwal_jam  = $this->input->post('jadwal_jam') ?: NULL;

        if (!$user_id || !$paket_id) {
            $this->session->set_flashdata('error', 'Pilih user dan paket terlebih dahulu.');
            redirect('admin/pembayaran');
            return;
        }

        // Generate no transaksi
        $count        = $this->db->where('DATE(tanggal_daftar)', date('Y-m-d'))->count_all_results('pendaftaran') + 1;
        $no_transaksi = 'FASE-' . date('Ymd') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        $this->db->insert('pendaftaran', [
            'no_transaksi' => $no_transaksi,
            'user_id'      => $user_id,
            'paket_id'     => $paket_id,
            'jadwal_hari'  => $jadwal_hari ?: NULL,
            'jadwal_jam'   => $jadwal_jam,
            'tanggal_daftar' => date('Y-m-d H:i:s'),
        ]);
        $pendaftaran_id = $this->db->insert_id();

        // Jika ada file bukti, upload sekaligus
        if (!empty($_FILES['file_bukti']['name'])) {
            $config = [
                'upload_path'   => 'assets/img/bukti/',
                'allowed_types' => 'jpg|jpeg|png|pdf|gif|webp|bmp',
                'max_size'      => 2048,
                'file_name'     => $pendaftaran_id . '_' . time(),
            ];
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file_bukti')) {
                $file = $this->upload->data('file_name');
                $status_verif = $this->input->post('status_verifikasi') === 'diterima' ? 'diterima' : 'pending';

                $this->db->insert('bukti_pembayaran', [
                    'pendaftaran_id'    => $pendaftaran_id,
                    'file_bukti'        => $file,
                    'nama_pengirim'     => $this->input->post('nama_pengirim', TRUE) ?: NULL,
                    'jumlah_transfer'   => (int) $this->input->post('jumlah_transfer') ?: NULL,
                    'tanggal_transfer'  => $this->input->post('tanggal_transfer') ?: NULL,
                    'catatan'           => $this->input->post('catatan', TRUE) ?: NULL,
                    'status_verifikasi' => $status_verif,
                    'uploaded_at'       => date('Y-m-d H:i:s'),
                    'verified_at'       => $status_verif === 'diterima' ? date('Y-m-d H:i:s') : NULL,
                ]);
            }
        }

        $this->session->set_flashdata('success', 'Pendaftaran ' . $no_transaksi . ' berhasil ditambahkan.');
        redirect('admin/pembayaran');
    }

    // Upload bukti pembayaran oleh admin (untuk bukti yang dikirim via WhatsApp)
    public function upload_bukti_admin($pendaftaran_id = null) {
        if (!$pendaftaran_id) redirect('admin/pembayaran');

        $nama_pengirim    = $this->input->post('nama_pengirim', TRUE);
        $jumlah_transfer  = (int) $this->input->post('jumlah_transfer');
        $tanggal_transfer = $this->input->post('tanggal_transfer');
        $catatan          = $this->input->post('catatan', TRUE);

        if (empty($_FILES['file_bukti']['name'])) {
            $this->session->set_flashdata('error', 'Pilih file bukti transfer.');
            redirect('admin/pembayaran');
            return;
        }

        $config = [
            'upload_path'   => 'assets/img/bukti/',
            'allowed_types' => 'jpg|jpeg|png|pdf|gif|webp|bmp',
            'max_size'      => 2048,
            'file_name'     => $pendaftaran_id . '_' . time(),
        ];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_bukti')) {
            $this->session->set_flashdata('error', 'Upload gagal: ' . $this->upload->display_errors('', ''));
            redirect('admin/pembayaran');
            return;
        }

        $upload_data = $this->upload->data();

        $this->db->insert('bukti_pembayaran', [
            'pendaftaran_id'    => $pendaftaran_id,
            'file_bukti'        => $upload_data['file_name'],
            'nama_pengirim'     => $nama_pengirim,
            'jumlah_transfer'   => $jumlah_transfer,
            'tanggal_transfer'  => $tanggal_transfer,
            'catatan'           => $catatan,
            'status_verifikasi' => 'pending',
        ]);

        $this->session->set_flashdata('success', 'Bukti pembayaran berhasil di-upload.');
        redirect('admin/pembayaran');
    }

    public function verifikasi_pembayaran($bp_id = null) {
        if (!$bp_id) redirect('admin/pembayaran');

        $status        = $this->input->post('status', TRUE);
        $catatan_admin = $this->input->post('catatan_admin', TRUE);

        $allowed = ['diterima', 'ditolak', 'kadaluarsa', 'pending'];
        if (!in_array($status, $allowed)) redirect('admin/pembayaran');

        $this->db->update('bukti_pembayaran', [
            'status_verifikasi' => $status,
            'catatan_admin'     => $catatan_admin,
            'verified_at'       => ($status !== 'pending') ? date('Y-m-d H:i:s') : null,
        ], ['id' => $bp_id]);

        $label = ['diterima' => 'diterima', 'ditolak' => 'ditolak', 'kadaluarsa' => 'dikadaluarsakan'];
        $this->session->set_flashdata('success', 'Pembayaran berhasil ' . ($label[$status] ?? 'diperbarui') . '.');
        redirect('admin/pembayaran');
    }

    // ===== PAKET =====

    public function paket() {
        $data = [
            'page_title'  => 'Data Paket',
            'active_menu' => 'paket',
            'paket'       => $this->db->order_by('tingkat, tipe_kelas, durasi_menit')->get('paket')->result_array(),
        ];
        $this->load->view('admin/v_paket', $data);
    }

    public function tambah_paket() {
        $this->db->insert('paket', [
            'tingkat'          => $this->input->post('tingkat', TRUE),
            'tipe_kelas'       => $this->input->post('tipe_kelas', TRUE),
            'durasi_menit'     => (int) $this->input->post('durasi_menit'),
            'jumlah_pertemuan' => (int) $this->input->post('jumlah_pertemuan'),
            'harga'            => (int) $this->input->post('harga'),
            'is_aktif'         => (int) $this->input->post('is_aktif'),
        ]);
        $this->session->set_flashdata('success', 'Paket baru berhasil ditambahkan.');
        redirect('admin/paket');
    }

    public function update_paket($id = null) {
        if (!$id) redirect('admin/paket');
        $this->db->update('paket', [
            'tingkat'          => $this->input->post('tingkat', TRUE),
            'tipe_kelas'       => $this->input->post('tipe_kelas', TRUE),
            'durasi_menit'     => (int) $this->input->post('durasi_menit'),
            'jumlah_pertemuan' => (int) $this->input->post('jumlah_pertemuan'),
            'harga'            => (int) $this->input->post('harga'),
            'is_aktif'         => (int) $this->input->post('is_aktif'),
        ], ['id' => $id]);
        $this->session->set_flashdata('success', 'Paket berhasil diperbarui.');
        redirect('admin/paket');
    }

    public function hapus_paket($id = null) {
        if (!$id) redirect('admin/paket');
        $this->db->delete('paket', ['id' => $id]);
        $this->session->set_flashdata('success', 'Paket berhasil dihapus.');
        redirect('admin/paket');
    }

    // ===== PESERTA =====

    // Tambah peserta baru
    public function tambah_peserta() {
        $username   = $this->input->post('username', TRUE);
        $email      = $this->input->post('email', TRUE);
        $password   = $this->input->post('password');
        $konfirmasi = $this->input->post('konfirmasi_password');

        if (strlen($password) < 6) {
            $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
            redirect('admin/peserta');
            return;
        }

        if ($password !== $konfirmasi) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
            redirect('admin/peserta');
            return;
        }

        if ($this->db->get_where('users', ['username' => $username])->num_rows() > 0) {
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect('admin/peserta');
            return;
        }

        if ($this->db->get_where('users', ['email' => $email])->num_rows() > 0) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar.');
            redirect('admin/peserta');
            return;
        }

        $this->db->insert('users', [
            'username'       => $username,
            'email'          => $email,
            'password'       => password_hash($password, PASSWORD_BCRYPT),
            'nama_lengkap'   => $this->input->post('nama_lengkap', TRUE) ?: NULL,
            'tempat_lahir'   => $this->input->post('tempat_lahir', TRUE) ?: NULL,
            'tanggal_lahir'  => $this->input->post('tanggal_lahir') ?: NULL,
            'jenis_kelamin'  => $this->input->post('jenis_kelamin') ?: NULL,
            'alamat'         => $this->input->post('alamat', TRUE) ?: NULL,
            'asal_sekolah'   => $this->input->post('asal_sekolah', TRUE) ?: NULL,
            'nama_ortu'      => $this->input->post('nama_ortu', TRUE) ?: NULL,
            'no_wa_ortu'     => $this->input->post('no_wa_ortu') ?: NULL,
            'pekerjaan_ortu' => $this->input->post('pekerjaan_ortu', TRUE) ?: NULL,
        ]);

        $this->session->set_flashdata('success', 'Peserta baru berhasil ditambahkan.');
        redirect('admin/peserta');
    }

    // Update data peserta
    public function update_peserta($id = null) {
        if (!$id) redirect('admin/peserta');

        $username    = $this->input->post('username', TRUE);
        $email       = $this->input->post('email', TRUE);
        $password    = $this->input->post('password');
        $konfirmasi  = $this->input->post('konfirmasi_password');

        // Validasi password jika diisi
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
                redirect('admin/peserta');
                return;
            }
            if ($password !== $konfirmasi) {
                $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
                redirect('admin/peserta');
                return;
            }
        }

        // Cek username/email sudah dipakai user lain
        $cek_username = $this->db->where('username', $username)->where('id !=', $id)->get('users')->num_rows();
        if ($cek_username > 0) {
            $this->session->set_flashdata('error', 'Username sudah digunakan pengguna lain.');
            redirect('admin/peserta');
            return;
        }

        $cek_email = $this->db->where('email', $email)->where('id !=', $id)->get('users')->num_rows();
        if ($cek_email > 0) {
            $this->session->set_flashdata('error', 'Email sudah digunakan pengguna lain.');
            redirect('admin/peserta');
            return;
        }

        $update = [
            'username'       => $username,
            'email'          => $email,
            'nama_lengkap'   => $this->input->post('nama_lengkap', TRUE),
            'tempat_lahir'   => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'  => $this->input->post('tanggal_lahir') ?: NULL,
            'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
            'alamat'         => $this->input->post('alamat', TRUE),
            'asal_sekolah'   => $this->input->post('asal_sekolah', TRUE) ?: NULL,
            'nama_ortu'      => $this->input->post('nama_ortu', TRUE) ?: NULL,
            'no_wa_ortu'     => $this->input->post('no_wa_ortu') ?: NULL,
            'pekerjaan_ortu' => $this->input->post('pekerjaan_ortu', TRUE) ?: NULL,
        ];
        if (!empty($password)) {
            $update['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->db->update('users', $update, ['id' => $id]);
        $this->session->set_flashdata('success', 'Data peserta berhasil diperbarui.');
        redirect('admin/peserta');
    }

    // Hapus pengguna beserta seluruh data terkait
    public function hapus_peserta($id = null) {
        if (!$id) redirect('admin/peserta');

        // Hapus bukti pembayaran terkait pendaftaran user ini
        $pendaftaran_ids = $this->db->select('id')->get_where('pendaftaran', ['user_id' => $id])->result_array();
        if (!empty($pendaftaran_ids)) {
            $ids = array_column($pendaftaran_ids, 'id');
            $this->db->where_in('pendaftaran_id', $ids)->delete('bukti_pembayaran');
        }

        $this->db->delete('pendaftaran', ['user_id' => $id]);
        $this->db->delete('users', ['id' => $id]);

        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('admin/peserta');
    }

    // ===== PENGAJAR =====

    public function pengajar() {
        $data['page_title']  = 'Data Pengajar';
        $data['active_menu'] = 'pengajar';
        $data['pengajar']    = $this->db->order_by('nama_lengkap', 'ASC')->get('pengajar')->result_array();
        $this->load->view('admin/v_pengajar', $data);
    }

    public function tambah_pengajar() {
        $nama = $this->input->post('nama_lengkap', TRUE);
        if (empty($nama)) {
            $this->session->set_flashdata('error', 'Nama lengkap tidak boleh kosong.');
            redirect('admin/pengajar');
            return;
        }
        $this->db->insert('pengajar', [
            'nama_lengkap'   => $nama,
            'no_wa'          => $this->input->post('no_wa') ?: NULL,
            'mata_pelajaran' => $this->input->post('mata_pelajaran', TRUE) ?: NULL,
        ]);
        $this->session->set_flashdata('success', 'Pengajar berhasil ditambahkan.');
        redirect('admin/pengajar');
    }

    public function update_pengajar($id = null) {
        if (!$id) redirect('admin/pengajar');
        $nama = $this->input->post('nama_lengkap', TRUE);
        if (empty($nama)) {
            $this->session->set_flashdata('error', 'Nama lengkap tidak boleh kosong.');
            redirect('admin/pengajar');
            return;
        }
        $this->db->update('pengajar', [
            'nama_lengkap'   => $nama,
            'no_wa'          => $this->input->post('no_wa') ?: NULL,
            'mata_pelajaran' => $this->input->post('mata_pelajaran', TRUE) ?: NULL,
        ], ['id' => $id]);
        $this->session->set_flashdata('success', 'Data pengajar berhasil diperbarui.');
        redirect('admin/pengajar');
    }

    public function hapus_pengajar($id = null) {
        if (!$id) redirect('admin/pengajar');
        $this->db->delete('pengajar', ['id' => $id]);
        $this->session->set_flashdata('success', 'Pengajar berhasil dihapus.');
        redirect('admin/pengajar');
    }

    // ===== JADWAL =====

    public function jadwal() {
        $data['page_title']  = 'Jadwal';
        $data['active_menu'] = 'jadwal';
        $data['jadwal'] = $this->db
            ->select('j.*, u.nama_lengkap as nama_siswa, u.username,
                      CONCAT(pak.tingkat, " – ", pak.tipe_kelas) as nama_paket,
                      pg.nama_lengkap as nama_pengajar, pg.mata_pelajaran')
            ->from('jadwal j')
            ->join('pendaftaran p',  'p.id  = j.pendaftaran_id')
            ->join('users u',        'u.id  = p.user_id')
            ->join('paket pak',      'pak.id = p.paket_id')
            ->join('pengajar pg',    'pg.id = j.pengajar_id')
            ->order_by('j.created_at', 'DESC')
            ->get()->result_array();

        // Pendaftaran lunas yang belum punya jadwal
        $data['pendaftaran_lunas'] = $this->db
            ->select('p.id, u.nama_lengkap, u.username,
                      CONCAT(pak.tingkat, " – ", pak.tipe_kelas) as nama_paket,
                      p.jadwal_hari, p.jadwal_jam')
            ->from('pendaftaran p')
            ->join('users u',   'u.id  = p.user_id')
            ->join('paket pak', 'pak.id = p.paket_id')
            ->where("(SELECT status_verifikasi FROM bukti_pembayaran WHERE pendaftaran_id = p.id ORDER BY uploaded_at DESC LIMIT 1) = 'diterima'", NULL, FALSE)
            ->order_by('u.nama_lengkap', 'ASC')
            ->get()->result_array();

        $data['pengajar'] = $this->db->order_by('nama_lengkap', 'ASC')->get('pengajar')->result_array();
        $this->load->view('admin/v_jadwal', $data);
    }

    public function tambah_jadwal() {
        $pendaftaran_id  = (int)$this->input->post('pendaftaran_id');
        $pengajar_id     = (int)$this->input->post('pengajar_id');
        $hari            = $this->input->post('hari', TRUE);
        $jam_mulai       = $this->input->post('jam_mulai');
        $jam_selesai     = $this->input->post('jam_selesai');
        $jumlah          = (int)$this->input->post('jumlah_pertemuan') ?: 8;
        $catatan         = $this->input->post('catatan', TRUE);

        if (!$pendaftaran_id || !$pengajar_id || !$hari || !$jam_mulai || !$jam_selesai) {
            $this->session->set_flashdata('error', 'Semua field wajib diisi.');
            redirect('admin/jadwal');
            return;
        }

        $this->db->insert('jadwal', [
            'pendaftaran_id'    => $pendaftaran_id,
            'pengajar_id'       => $pengajar_id,
            'hari'              => $hari,
            'jam_mulai'         => $jam_mulai,
            'jam_selesai'       => $jam_selesai,
            'jumlah_pertemuan'  => $jumlah,
            'pertemuan_selesai' => 0,
            'status'            => 'aktif',
            'catatan'           => $catatan ?: NULL,
        ]);
        $this->session->set_flashdata('success', 'Jadwal berhasil ditambahkan.');
        redirect('admin/jadwal');
    }

    public function selesai_pertemuan($id = null) {
        if (!$id) redirect('admin/jadwal');

        $jadwal = $this->db->get_where('jadwal', ['id' => $id])->row_array();
        if (!$jadwal) redirect('admin/jadwal');

        $baru = (int)$jadwal['pertemuan_selesai'] + 1;
        $status = ($baru >= (int)$jadwal['jumlah_pertemuan']) ? 'selesai' : 'aktif';

        $this->db->update('jadwal', [
            'pertemuan_selesai' => $baru,
            'status'            => $status,
        ], ['id' => $id]);

        $this->session->set_flashdata('success', 'Pertemuan ke-' . $baru . ' ditandai selesai.');
        redirect('admin/jadwal');
    }

    public function hapus_jadwal($id = null) {
        if (!$id) redirect('admin/jadwal');
        $this->db->delete('jadwal', ['id' => $id]);
        $this->session->set_flashdata('success', 'Jadwal berhasil dihapus.');
        redirect('admin/jadwal');
    }
}
