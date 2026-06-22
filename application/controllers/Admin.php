<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    private $public_methods = ['login', 'login_proses', 'setup_admin'];

    public function __construct() {
        parent::__construct();

        $this->load->model([
            'Admin_model',
            'Pendaftaran_model',
            'Paket_model',
            'BuktiPembayaran_model',
            'Pengajar_model',
            'Jadwal_model',
        ]);

        $method = $this->router->fetch_method();
        if (!in_array($method, $this->public_methods) && !$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }
    }

    // ================================================================
    //  AUTH
    // ================================================================

    public function login() {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        $this->load->view('admin/v_login');
    }

    public function login_proses() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        $admin = $this->Admin_model->get_by_username($username);

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

    public function logout() {
        $this->session->unset_userdata(['admin_logged_in', 'admin_id', 'admin_username', 'admin_nama', 'admin_role']);
        redirect('admin/login');
    }

    private function cek_role_super() {
        if ((int)$this->session->userdata('admin_role') !== 1) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('admin');
        }
    }

    // Setup akun admin pertama — hapus method ini setelah dipakai sekali
    public function setup_admin() {
        if ($this->Admin_model->get_all()) {
            echo 'Akun admin sudah ada. Hapus method setup_admin() dari controller.';
            return;
        }
        $this->Admin_model->tambah([
            'username'     => 'admin',
            'password'     => password_hash('admin123', PASSWORD_BCRYPT),
            'nama_lengkap' => 'Administrator',
        ]);
        echo 'Akun admin berhasil dibuat.<br>Username: <b>admin</b><br>Password: <b>admin123</b><br><br>Segera hapus method setup_admin() dari controller!';
    }

    // ================================================================
    //  DASHBOARD
    // ================================================================

    public function index() {
        $tahun_ini = date('Y');
        $bulan_ini = date('m');
        $nama_bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];

        $data = [
            'page_title'     => 'Dashboard',
            'active_menu'    => 'dashboard',
            'stats'          => $this->Admin_model->get_stats(),
            'rekap_bulan'    => $this->Admin_model->get_rekap_bulan_ini((int)$bulan_ini, (int)$tahun_ini),
            'nama_bulan_ini' => $nama_bulan[(int)$bulan_ini],
            'tahun_ini'      => $tahun_ini,
            'bulanan'        => $this->Admin_model->get_bulanan_chart($tahun_ini),
            'tahunan'        => $this->Admin_model->get_tahunan(),
            'recent'         => $this->Admin_model->get_recent_pendaftaran(8),
        ];

        $this->load->view('admin/v_dashboard', $data);
    }

    // ================================================================
    //  PESERTA (users)
    // ================================================================

    public function peserta() {
        $data = [
            'page_title'  => 'Data Peserta',
            'active_menu' => 'peserta',
            'users'       => $this->Admin_model->get_all_peserta(),
        ];
        $this->load->view('admin/v_peserta', $data);
    }

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
        if ($this->Admin_model->peserta_username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect('admin/peserta');
            return;
        }
        if ($this->Admin_model->peserta_email_exists($email)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar.');
            redirect('admin/peserta');
            return;
        }

        $this->Admin_model->tambah_peserta([
            'username'       => $username,
            'email'          => $email,
            'password'       => password_hash($password, PASSWORD_BCRYPT),
            'nama_lengkap'   => $this->input->post('nama_lengkap', TRUE)  ?: NULL,
            'tempat_lahir'   => $this->input->post('tempat_lahir', TRUE)  ?: NULL,
            'tanggal_lahir'  => $this->input->post('tanggal_lahir')       ?: NULL,
            'jenis_kelamin'  => $this->input->post('jenis_kelamin')       ?: NULL,
            'alamat'         => $this->input->post('alamat', TRUE)        ?: NULL,
            'asal_sekolah'   => $this->input->post('asal_sekolah', TRUE)  ?: NULL,
            'nama_ortu'      => $this->input->post('nama_ortu', TRUE)     ?: NULL,
            'no_wa_ortu'     => $this->input->post('no_wa_ortu')          ?: NULL,
            'pekerjaan_ortu' => $this->input->post('pekerjaan_ortu', TRUE) ?: NULL,
        ]);

        $this->session->set_flashdata('success', 'Peserta baru berhasil ditambahkan.');
        redirect('admin/peserta');
    }

    public function update_peserta($id = null) {
        if (!$id) redirect('admin/peserta');

        $username   = $this->input->post('username', TRUE);
        $email      = $this->input->post('email', TRUE);
        $password   = $this->input->post('password');
        $konfirmasi = $this->input->post('konfirmasi_password');

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

        if ($this->Admin_model->peserta_username_exists($username, $id)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan pengguna lain.');
            redirect('admin/peserta');
            return;
        }
        if ($this->Admin_model->peserta_email_exists($email, $id)) {
            $this->session->set_flashdata('error', 'Email sudah digunakan pengguna lain.');
            redirect('admin/peserta');
            return;
        }

        $update = [
            'username'       => $username,
            'email'          => $email,
            'nama_lengkap'   => $this->input->post('nama_lengkap', TRUE),
            'tempat_lahir'   => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'  => $this->input->post('tanggal_lahir')        ?: NULL,
            'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
            'alamat'         => $this->input->post('alamat', TRUE),
            'asal_sekolah'   => $this->input->post('asal_sekolah', TRUE)   ?: NULL,
            'nama_ortu'      => $this->input->post('nama_ortu', TRUE)      ?: NULL,
            'no_wa_ortu'     => $this->input->post('no_wa_ortu')           ?: NULL,
            'pekerjaan_ortu' => $this->input->post('pekerjaan_ortu', TRUE) ?: NULL,
        ];
        if (!empty($password)) {
            $update['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->Admin_model->update_peserta($id, $update);
        $this->session->set_flashdata('success', 'Data peserta berhasil diperbarui.');
        redirect('admin/peserta');
    }

    public function hapus_peserta($id = null) {
        if (!$id) redirect('admin/peserta');
        $this->Admin_model->hapus_peserta($id);
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('admin/peserta');
    }

    // ================================================================
    //  ADMIN MANAGEMENT
    // ================================================================

    public function admin_list() {
        $this->cek_role_super();
        $data = [
            'page_title'  => 'Data Admin',
            'active_menu' => 'admin',
            'admins'      => $this->Admin_model->get_all(),
        ];
        $this->load->view('admin/v_admin_list', $data);
    }

    public function tambah_admin() {
        $this->cek_role_super();
        $username   = $this->input->post('username', TRUE);
        $nama       = $this->input->post('nama_lengkap', TRUE);
        $password   = $this->input->post('password');
        $konfirmasi = $this->input->post('konfirmasi_password');

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
        if ($this->Admin_model->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect('admin/admin_list');
            return;
        }

        $role = (int)$this->input->post('role') === 1 ? 1 : 2;
        $this->Admin_model->tambah([
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

        $username   = $this->input->post('username', TRUE);
        $nama       = $this->input->post('nama_lengkap', TRUE);
        $role       = (int)$this->input->post('role') === 1 ? 1 : 2;
        $password   = $this->input->post('password');
        $konfirmasi = $this->input->post('konfirmasi_password');

        if ($this->Admin_model->username_exists($username, $id)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan admin lain.');
            redirect('admin/admin_list');
            return;
        }

        $data = ['nama_lengkap' => $nama, 'username' => $username, 'role' => $role];

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

        $this->Admin_model->update($id, $data);

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

        if ((int)$id === (int)$this->session->userdata('admin_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus akun admin yang sedang aktif.');
            redirect('admin/admin_list');
            return;
        }

        $this->Admin_model->hapus($id);
        $this->session->set_flashdata('success', 'Admin berhasil dihapus.');
        redirect('admin/admin_list');
    }

    // ================================================================
    //  REKAP
    // ================================================================

    public function rekap_tahunan() {
        $this->cek_role_super();

        $tahun_list  = $this->Admin_model->get_tahun_list();
        $tahun_aktif = (int)($this->input->get('tahun') ?: date('Y'));

        $bulanan = $this->Admin_model->get_rekap_tahunan_detail($tahun_aktif);
        $bulanan_map = [];
        foreach ($bulanan as $b) {
            $bulanan_map[(int)$b['bulan']] = $b;
        }

        $data = [
            'page_title'  => 'Rekap Tahunan',
            'active_menu' => 'rekap_tahunan',
            'tahun_list'  => $tahun_list,
            'tahun_aktif' => $tahun_aktif,
            'bulanan_map' => $bulanan_map,
            'summary'     => $this->Admin_model->get_summary_tahunan($tahun_aktif),
        ];
        $this->load->view('admin/v_rekap_tahunan', $data);
    }

    public function rekap_bulanan() {
        $this->cek_role_super();

        $bulan = (int)($this->input->get('bulan') ?: date('m'));
        $tahun = (int)($this->input->get('tahun') ?: date('Y'));
        $rows  = $this->Admin_model->get_rekap_bulanan_detail($bulan, $tahun);

        $summary = [
            'total_daftar'    => count($rows),
            'total_diterima'  => count(array_filter($rows, fn($r) => $r['status_verifikasi'] === 'diterima')),
            'total_pending'   => count(array_filter($rows, fn($r) => $r['status_verifikasi'] === 'pending')),
            'total_ditolak'   => count(array_filter($rows, fn($r) => in_array($r['status_verifikasi'], ['ditolak', 'kadaluarsa']))),
            'total_pemasukan' => array_sum(array_column(
                array_filter($rows, fn($r) => $r['status_verifikasi'] === 'diterima'), 'harga'
            )),
        ];

        $data = [
            'page_title'  => 'Rekap Bulanan',
            'active_menu' => 'rekap_bulanan',
            'rows'        => $rows,
            'summary'     => $summary,
            'bulan_aktif' => $bulan,
            'tahun_aktif' => $tahun,
            'tahun_list'  => $this->Admin_model->get_tahun_list(),
        ];
        $this->load->view('admin/v_rekap_bulanan', $data);
    }

    // ================================================================
    //  PEMBAYARAN
    // ================================================================

    public function pembayaran() {
        $data = [
            'page_title'  => 'Data Pembayaran',
            'active_menu' => 'pembayaran',
            'users'       => $this->Admin_model->get_all_peserta(),
            'paket'       => $this->Paket_model->get_all(),
            'pendaftaran' => $this->Pendaftaran_model->get_list_pembayaran(),
        ];
        $this->load->view('admin/v_pembayaran', $data);
    }

    public function tambah_pembayaran() {
        $user_id     = (int)$this->input->post('user_id');
        $paket_id    = (int)$this->input->post('paket_id');
        $jadwal_hari = $this->input->post('jadwal_hari', TRUE);
        $jadwal_jam  = $this->input->post('jadwal_jam') ?: NULL;

        if (!$user_id || !$paket_id) {
            $this->session->set_flashdata('error', 'Pilih user dan paket terlebih dahulu.');
            redirect('admin/pembayaran');
            return;
        }

        $no_transaksi   = $this->Pendaftaran_model->generate_no_transaksi();
        $pendaftaran_id = $this->Pendaftaran_model->simpan_data([
            'no_transaksi'   => $no_transaksi,
            'user_id'        => $user_id,
            'paket_id'       => $paket_id,
            'jadwal_hari'    => $jadwal_hari ?: NULL,
            'jadwal_jam'     => $jadwal_jam,
            'tanggal_daftar' => date('Y-m-d H:i:s'),
        ]);

        if (!empty($_FILES['file_bukti']['name'])) {
            $config = [
                'upload_path'   => 'assets/img/bukti/',
                'allowed_types' => 'jpg|jpeg|png|pdf|gif|webp|bmp',
                'max_size'      => 2048,
                'file_name'     => $pendaftaran_id . '_' . time(),
            ];
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file_bukti')) {
                $status_verif = $this->input->post('status_verifikasi') === 'diterima' ? 'diterima' : 'pending';
                $this->BuktiPembayaran_model->simpan([
                    'pendaftaran_id'    => $pendaftaran_id,
                    'file_bukti'        => $this->upload->data('file_name'),
                    'nama_pengirim'     => $this->input->post('nama_pengirim', TRUE) ?: NULL,
                    'jumlah_transfer'   => (int)$this->input->post('jumlah_transfer') ?: NULL,
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

    public function upload_bukti_admin($pendaftaran_id = null) {
        if (!$pendaftaran_id) redirect('admin/pembayaran');

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

        $this->BuktiPembayaran_model->simpan([
            'pendaftaran_id'    => $pendaftaran_id,
            'file_bukti'        => $this->upload->data('file_name'),
            'nama_pengirim'     => $this->input->post('nama_pengirim', TRUE),
            'jumlah_transfer'   => (int)$this->input->post('jumlah_transfer'),
            'tanggal_transfer'  => $this->input->post('tanggal_transfer'),
            'catatan'           => $this->input->post('catatan', TRUE),
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

        $this->BuktiPembayaran_model->verifikasi($bp_id, $status, $catatan_admin);

        $label = ['diterima' => 'diterima', 'ditolak' => 'ditolak', 'kadaluarsa' => 'dikadaluarsakan'];
        $this->session->set_flashdata('success', 'Pembayaran berhasil ' . ($label[$status] ?? 'diperbarui') . '.');
        redirect('admin/pembayaran');
    }

    // ================================================================
    //  PAKET
    // ================================================================

    public function paket() {
        $data = [
            'page_title'  => 'Data Paket',
            'active_menu' => 'paket',
            'paket'       => $this->Paket_model->get_all(),
        ];
        $this->load->view('admin/v_paket', $data);
    }

    public function tambah_paket() {
        $this->Paket_model->tambah([
            'tingkat'          => $this->input->post('tingkat', TRUE),
            'tipe_kelas'       => $this->input->post('tipe_kelas', TRUE),
            'durasi_menit'     => (int)$this->input->post('durasi_menit'),
            'jumlah_pertemuan' => (int)$this->input->post('jumlah_pertemuan'),
            'harga'            => (int)$this->input->post('harga'),
            'is_aktif'         => (int)$this->input->post('is_aktif'),
        ]);
        $this->session->set_flashdata('success', 'Paket baru berhasil ditambahkan.');
        redirect('admin/paket');
    }

    public function update_paket($id = null) {
        if (!$id) redirect('admin/paket');
        $this->Paket_model->update($id, [
            'tingkat'          => $this->input->post('tingkat', TRUE),
            'tipe_kelas'       => $this->input->post('tipe_kelas', TRUE),
            'durasi_menit'     => (int)$this->input->post('durasi_menit'),
            'jumlah_pertemuan' => (int)$this->input->post('jumlah_pertemuan'),
            'harga'            => (int)$this->input->post('harga'),
            'is_aktif'         => (int)$this->input->post('is_aktif'),
        ]);
        $this->session->set_flashdata('success', 'Paket berhasil diperbarui.');
        redirect('admin/paket');
    }

    public function hapus_paket($id = null) {
        if (!$id) redirect('admin/paket');
        $this->Paket_model->hapus($id);
        $this->session->set_flashdata('success', 'Paket berhasil dihapus.');
        redirect('admin/paket');
    }

    // ================================================================
    //  PENGAJAR
    // ================================================================

    public function pengajar() {
        $data = [
            'page_title'  => 'Data Pengajar',
            'active_menu' => 'pengajar',
            'pengajar'    => $this->Pengajar_model->get_all(),
        ];
        $this->load->view('admin/v_pengajar', $data);
    }

    public function tambah_pengajar() {
        $nama = $this->input->post('nama_lengkap', TRUE);
        if (empty($nama)) {
            $this->session->set_flashdata('error', 'Nama lengkap tidak boleh kosong.');
            redirect('admin/pengajar');
            return;
        }
        $this->Pengajar_model->tambah([
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
        $this->Pengajar_model->update($id, [
            'nama_lengkap'   => $nama,
            'no_wa'          => $this->input->post('no_wa') ?: NULL,
            'mata_pelajaran' => $this->input->post('mata_pelajaran', TRUE) ?: NULL,
        ]);
        $this->session->set_flashdata('success', 'Data pengajar berhasil diperbarui.');
        redirect('admin/pengajar');
    }

    public function hapus_pengajar($id = null) {
        if (!$id) redirect('admin/pengajar');
        $this->Pengajar_model->hapus($id);
        $this->session->set_flashdata('success', 'Pengajar berhasil dihapus.');
        redirect('admin/pengajar');
    }

    // ================================================================
    //  JADWAL
    // ================================================================

    public function jadwal() {
        $data = [
            'page_title'        => 'Jadwal',
            'active_menu'       => 'jadwal',
            'jadwal'            => $this->Jadwal_model->get_all(),
            'pendaftaran_lunas' => $this->Jadwal_model->get_pendaftaran_lunas(),
            'pengajar'          => $this->Pengajar_model->get_all(),
        ];
        $this->load->view('admin/v_jadwal', $data);
    }

    public function tambah_jadwal() {
        $pendaftaran_id = (int)$this->input->post('pendaftaran_id');
        $pengajar_id    = (int)$this->input->post('pengajar_id');
        $hari           = $this->input->post('hari', TRUE);
        $jam_mulai      = $this->input->post('jam_mulai');
        $jam_selesai    = $this->input->post('jam_selesai');
        $jumlah         = (int)$this->input->post('jumlah_pertemuan') ?: 8;
        $catatan        = $this->input->post('catatan', TRUE);

        if (!$pendaftaran_id || !$pengajar_id || !$hari || !$jam_mulai || !$jam_selesai) {
            $this->session->set_flashdata('error', 'Semua field wajib diisi.');
            redirect('admin/jadwal');
            return;
        }

        $this->Jadwal_model->tambah([
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

        $baru = $this->Jadwal_model->selesai_pertemuan($id);
        if ($baru === false) redirect('admin/jadwal');

        $this->session->set_flashdata('success', 'Pertemuan ke-' . $baru . ' ditandai selesai.');
        redirect('admin/jadwal');
    }

    public function hapus_jadwal($id = null) {
        if (!$id) redirect('admin/jadwal');
        $this->Jadwal_model->hapus($id);
        $this->session->set_flashdata('success', 'Jadwal berhasil dihapus.');
        redirect('admin/jadwal');
    }
}
