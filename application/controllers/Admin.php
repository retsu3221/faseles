<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
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

        // --- Rekap bulanan tahun berjalan ---
        $tahun_ini   = date('Y');
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
            ORDER BY bulan
        ")->result_array();

        // Isi semua 12 bulan (default 0)
        $bulanan = array_fill(1, 12, ['total_daftar' => 0, 'total_pemasukan' => 0]);
        foreach ($bulanan_raw as $row) {
            $bulanan[(int)$row['bulan']] = $row;
        }

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
            SELECT p.nama_lengkap, p.tanggal_daftar, pk.tingkat, pk.tipe_kelas,
                   COALESCE((
                       SELECT bp.status_verifikasi FROM bukti_pembayaran bp
                       WHERE bp.pendaftaran_id = p.id
                       ORDER BY bp.uploaded_at DESC LIMIT 1
                   ), 'belum_upload') AS status_verifikasi
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            ORDER BY p.tanggal_daftar DESC
            LIMIT 8
        ")->result_array();

        $data = [
            'page_title'    => 'Dashboard',
            'active_menu'   => 'dashboard',
            'stats'         => $stats,
            'bulanan'       => $bulanan,
            'tahun_ini'     => $tahun_ini,
            'tahunan'       => $tahunan,
            'recent'        => $recent,
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
            COALESCE((SELECT bp.status_verifikasi FROM bukti_pembayaran bp
                WHERE bp.pendaftaran_id = p.id ORDER BY bp.uploaded_at DESC LIMIT 1
            ), "pending") AS status_verifikasi')
            ->from('pendaftaran p')
            ->join('paket pk', 'pk.id = p.paket_id', 'left')
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

    // ===== PEMBAYARAN =====

    public function pembayaran() {
        $data = [
            'page_title'  => 'Data Pembayaran',
            'active_menu' => 'pembayaran',
            'pendaftaran' => $this->db->query("
                SELECT p.id, p.no_transaksi, p.nama_lengkap, p.tanggal_daftar,
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
