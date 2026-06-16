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
                'username'        => $admin['username'],
                'nama_lengkap'    => $admin['nama_lengkap'],
            ]);
            redirect('admin');
        }

        $this->session->set_flashdata('error', 'Username atau password salah.');
        redirect('admin/login');
    }

    // Logout admin
    public function logout() {
        $this->session->unset_userdata(['admin_logged_in', 'admin_id', 'username', 'nama_lengkap']);
        redirect('admin/login');
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
        $users = $this->db->select('u.id, u.username, u.email, u.role, u.created_at,
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

    // Detail satu peserta beserta riwayat pendaftarannya
    public function detail_peserta($id = null) {
        if (!$id) redirect('admin/peserta');

        $user = $this->db->get_where('users', ['id' => $id])->row_array();
        if (!$user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('admin/peserta');
        }

        $pendaftaran = $this->db->select('p.*, pk.tingkat, pk.tipe_kelas, pk.harga,
            u.nama_lengkap,
            COALESCE((SELECT bp.status_verifikasi FROM bukti_pembayaran bp
                WHERE bp.pendaftaran_id = p.id ORDER BY bp.uploaded_at DESC LIMIT 1
            ), "pending") AS status_verifikasi')
            ->from('pendaftaran p')
            ->join('paket pk', 'pk.id = p.paket_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('p.user_id', $id)
            ->order_by('p.tanggal_daftar', 'DESC')
            ->get()->result_array();

        $data = [
            'page_title'  => 'Detail Peserta',
            'active_menu' => 'peserta',
            'user'        => $user,
            'pendaftaran' => $pendaftaran,
        ];

        $this->load->view('admin/v_detail_peserta', $data);
    }

    // ===== REKAP TAHUNAN =====

    public function rekap_tahunan() {
        $tahun_list = $this->db->query("
            SELECT DISTINCT YEAR(tanggal_daftar) AS tahun FROM pendaftaran ORDER BY tahun DESC
        ")->result_array();
        if (empty($tahun_list)) $tahun_list = [['tahun' => date('Y')]];

        $tahun_aktif = (int)($this->input->get('tahun') ?: date('Y'));

        // Ringkasan per bulan untuk tahun yang dipilih
        $bulanan = $this->db->query("
            SELECT
                MONTH(p.tanggal_daftar) AS bulan,
                COUNT(p.id)             AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'   THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN bp.status_verifikasi = 'pending'    THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN bp.status_verifikasi IN ('ditolak','kadaluarsa') THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'   THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            WHERE YEAR(p.tanggal_daftar) = {$tahun_aktif}
            GROUP BY MONTH(p.tanggal_daftar)
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
                COUNT(p.id) AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'   THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN bp.status_verifikasi = 'pending'    THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN bp.status_verifikasi IN ('ditolak','kadaluarsa') THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'   THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            WHERE YEAR(p.tanggal_daftar) = {$tahun_aktif}
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
        $bulan = (int) ($this->input->get('bulan') ?: date('m'));
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));

        // Pendaftaran bulan ini beserta status pembayaran terbaru
        $rows = $this->db->query("
            SELECT p.no_transaksi, u.nama_lengkap, p.tanggal_daftar,
                   pk.tingkat, pk.tipe_kelas, pk.harga,
                   COALESCE(bp.status_verifikasi, 'belum_upload') AS status_verifikasi,
                   bp.nama_pengirim, bp.jumlah_transfer, bp.tanggal_transfer
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN users u  ON u.id  = p.user_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            WHERE MONTH(p.tanggal_daftar) = {$bulan}
              AND YEAR(p.tanggal_daftar)  = {$tahun}
            ORDER BY p.tanggal_daftar ASC
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

        // Rentang tahun untuk dropdown
        $tahun_list = $this->db->query("
            SELECT DISTINCT YEAR(tanggal_daftar) AS tahun FROM pendaftaran ORDER BY tahun DESC
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
        $role       = $this->input->post('role', TRUE);
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
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => $role,
        ]);

        $this->session->set_flashdata('success', 'Peserta baru berhasil ditambahkan.');
        redirect('admin/peserta');
    }

    // Update data peserta
    public function update_peserta($id = null) {
        if (!$id) redirect('admin/peserta');

        $username    = $this->input->post('username', TRUE);
        $email       = $this->input->post('email', TRUE);
        $role        = $this->input->post('role', TRUE);
        $password    = $this->input->post('password');
        $konfirmasi  = $this->input->post('konfirmasi_password');

        // Validasi password jika diisi
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
                redirect('admin/detail_peserta/' . $id);
                return;
            }
            if ($password !== $konfirmasi) {
                $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
                redirect('admin/detail_peserta/' . $id);
                return;
            }
        }

        // Cek username/email sudah dipakai user lain
        $cek_username = $this->db->where('username', $username)->where('id !=', $id)->get('users')->num_rows();
        if ($cek_username > 0) {
            $this->session->set_flashdata('error', 'Username sudah digunakan pengguna lain.');
            redirect('admin/detail_peserta/' . $id);
            return;
        }

        $cek_email = $this->db->where('email', $email)->where('id !=', $id)->get('users')->num_rows();
        if ($cek_email > 0) {
            $this->session->set_flashdata('error', 'Email sudah digunakan pengguna lain.');
            redirect('admin/detail_peserta/' . $id);
            return;
        }

        $update = ['username' => $username, 'email' => $email, 'role' => $role];
        if (!empty($password)) {
            $update['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->db->update('users', $update, ['id' => $id]);
        $this->session->set_flashdata('success', 'Data peserta berhasil diperbarui.');
        redirect('admin/detail_peserta/' . $id);
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
}
