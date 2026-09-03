<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Pendaftaran_model', 'Paket_model', 'BuktiPembayaran_model', 'Akun_model']);
    }

    // Fungsi untuk menampilkan Halaman Awal
    public function index() {
        $this->load->view('v_home');
    }

	// Fungsi untuk menampilkan Halaman About Us
	public function tentang_kami() {
		$this->load->view('v_tentang_kami');
	}

	// Fungsi untuk menampilkan Halaman Fasilitas
	public function fasilitas() {
		$this->load->view('v_fasilitas');
	}

	// Fungsi untuk menampilkan Halaman Kontak Kami
	public function kontak_kami() {
		$this->load->view('v_kontak_kami');
	}

    // Fungsi untuk menampilkan Form Pendaftaran
    public function daftar() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('warning', 'Silakan masuk terlebih dahulu untuk mengakses formulir pendaftaran.');
            redirect('auth/login');
        }
        $data['paket']     = $this->Paket_model->get_aktif();
        $data['user_data'] = $this->Akun_model->get_user($this->session->userdata('user_id'));
        $this->load->view('v_daftar_new', $data);
    }

    // Fungsi Logika untuk memproses form
    public function proses_daftar() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $user_id = $this->session->userdata('user_id');

        // Simpan data pribadi ke tabel users
        $this->Pendaftaran_model->update_user_data($user_id, [
            'nama_lengkap'   => $this->input->post('nama_lengkap', TRUE),
            'tempat_lahir'   => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'  => $this->input->post('tanggal_lahir'),
            'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
            'alamat'         => $this->input->post('alamat', TRUE),
            'asal_sekolah'   => $this->input->post('asal_sekolah'),
            'nama_ortu'      => $this->input->post('nama_ortu', TRUE),
            'no_wa_ortu'     => $this->input->post('no_wa_ortu'),
            'pekerjaan_ortu' => $this->input->post('pekerjaan_ortu', TRUE),
        ]);

        // Simpan data pendaftaran (hanya field spesifik registrasi)
        $id_pendaftar = $this->Pendaftaran_model->simpan_data([
            'no_transaksi' => $this->Pendaftaran_model->generate_no_transaksi(),
            'user_id'      => $user_id,
            'paket_id'     => $this->input->post('paket_id'),
            'jadwal_hari'  => $this->input->post('jadwal'),
            'jadwal_jam'   => $this->input->post('jam'),
        ]);

        redirect('pendaftaran/pembayaran/' . $id_pendaftar);
    }

    // Halaman pembayaran
    public function pembayaran($id) {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('warning', 'Silakan masuk terlebih dahulu.');
            redirect('auth/login');
        }

        $pendaftaran = $this->Pendaftaran_model->get_data_by_id($id);

        if (!$pendaftaran) {
            $this->session->set_flashdata('error', 'Data pendaftaran tidak ditemukan.');
            redirect('pendaftaran/daftar');
        }

        if ((int)$pendaftaran['user_id'] !== (int)$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('pendaftaran');
        }

        $paket = $this->Paket_model->get_by_id($pendaftaran['paket_id']);

        $data['pendaftaran'] = $pendaftaran;
        $data['label_kelas'] = $paket
            ? $paket['tipe_kelas'] . ' (' . $paket['durasi_menit'] . ' Menit | ' . $paket['jumlah_pertemuan'] . 'x Pertemuan)'
            : '-';
        $data['total_biaya'] = $paket ? (int) $paket['harga'] : 0;

        $data['status_config'] = [
            'pending'    => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-warning text-dark', 'icon' => 'bi-clock'],
            'lunas'      => ['label' => 'Lunas',               'class' => 'bg-success text-white', 'icon' => 'bi-check-circle-fill'],
            'ditolak'    => ['label' => 'Ditolak',             'class' => 'bg-danger text-white',  'icon' => 'bi-x-circle-fill'],
            'kadaluarsa' => ['label' => 'Kadaluarsa',          'class' => 'bg-secondary text-white','icon' => 'bi-calendar-x-fill'],
        ];

        $data['bukti_terakhir'] = $this->BuktiPembayaran_model->get_latest($id);

        $this->load->view('v_pembayaran', $data);
    }

    // Upload bukti pembayaran
    public function upload_bukti() {
        if (!$this->input->post('pendaftaran_id')) {
            $this->session->set_flashdata('error', 'Data tidak valid.');
            redirect('pendaftaran');
        }

        $pendaftaran_id = (int) $this->input->post('pendaftaran_id');
        $nama_pengirim = $this->input->post('nama_pengirim', TRUE);
        $jumlah_transfer = (int) $this->input->post('jumlah_transfer');
        $tanggal_transfer = $this->input->post('tanggal_transfer');
        $catatan = $this->input->post('catatan', TRUE);

        // Validasi field wajib dilakukan SEBELUM file diproses, karena kolomnya
        // NOT NULL di database. Kalau dilewati, file terlanjur ter-upload lalu
        // INSERT gagal -> file jadi sampah di server.
        if ($nama_pengirim === '' || $nama_pengirim === null) {
            $this->session->set_flashdata('error', 'Nama pengirim wajib diisi.');
            redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
            return;
        }

        if ($jumlah_transfer <= 0) {
            $this->session->set_flashdata('error', 'Jumlah transfer wajib diisi dan harus lebih dari 0.');
            redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
            return;
        }

        $tgl = $tanggal_transfer ? DateTime::createFromFormat('Y-m-d', $tanggal_transfer) : false;
        if (!$tgl || $tgl->format('Y-m-d') !== $tanggal_transfer) {
            $this->session->set_flashdata('error', 'Tanggal transfer wajib diisi dengan format yang benar.');
            redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
            return;
        }

        // Tanggal transfer tidak boleh melewati hari ini
        if ($tanggal_transfer > date('Y-m-d')) {
            $this->session->set_flashdata('error', 'Tanggal transfer tidak boleh melewati tanggal hari ini.');
            redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
            return;
        }

        // Validasi file
        if (empty($_FILES['file_bukti']['name'])) {
            $this->session->set_flashdata('error', 'Pilih file bukti transfer.');
            redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
            return;
        }

        // Config upload
        $config['upload_path']   = 'assets/img/bukti/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf|gif|webp|bmp';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = $pendaftaran_id . '_' . time();
        $config['mimes']         = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => ['image/png', 'image/x-png'],
            'pdf'  => ['application/pdf', 'application/x-pdf'],
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'bmp'  => ['image/bmp', 'image/x-windows-bmp']
        ];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_bukti')) {
            $this->session->set_flashdata('error', 'Unggah gagal: ' . $this->upload->display_errors('', ''));
            redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
            return;
        }

        $upload_data = $this->upload->data();

        // Simpan ke database
        $data = [
            'pendaftaran_id'   => $pendaftaran_id,
            'file_bukti'       => $upload_data['file_name'],
            'nama_pengirim'    => $nama_pengirim,
            'jumlah_transfer'  => $jumlah_transfer,
            'tanggal_transfer' => $tanggal_transfer,
            'catatan'          => $catatan,
            'status_verifikasi'=> 'pending',
        ];

        // Kalau penyimpanan gagal, file yang sudah ter-upload ikut dihapus agar
        // tidak menumpuk jadi sampah di server. db_debug dimatikan sementara
        // supaya user tidak melihat halaman error database mentah.
        $db_debug_asal      = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $tersimpan          = $this->BuktiPembayaran_model->simpan($data);
        $this->db->db_debug = $db_debug_asal;

        if (!$tersimpan) {
            if (!empty($upload_data['full_path']) && is_file($upload_data['full_path'])) {
                @unlink($upload_data['full_path']);
            }
            $this->session->set_flashdata('error', 'Bukti pembayaran gagal disimpan. Silakan coba lagi.');
            redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
            return;
        }

        $this->session->set_flashdata('success', 'Bukti pembayaran berhasil diunggah. Admin akan segera memverifikasi.');
        redirect('pendaftaran/pembayaran/' . $pendaftaran_id);
    }

	// Halaman register
	public function register()
	{
		$this->load->view('v_register');
	}

	// Halaman login
	public function login() {
		$this->load->view('v_login');
	}
}
