<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pendaftaran_model');
        $this->load->model('Paket_model');
        $this->load->model('BuktiPembayaran_model');
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
            $this->session->set_flashdata('warning', 'Silakan login terlebih dahulu untuk mengakses formulir pendaftaran.');
            redirect('auth/login');
        }
        $data['paket'] = $this->Paket_model->get_aktif();
        $this->load->view('v_daftar_new', $data);
    }

   // Fungsi Logika untuk memproses form
    public function proses_daftar() {
        // Tangkap data dari form yang baru
        $tanggal      = date('Ymd');
        $count        = $this->Pendaftaran_model->count_hari_ini() + 1;
        $no_transaksi = 'FASE-' . $tanggal . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        $data = array(
            'no_transaksi'   => $no_transaksi,
            'user_id'        => $this->session->userdata('user_id'),
            'nama_lengkap'   => $this->input->post('nama_lengkap'),
            'tempat_lahir'   => $this->input->post('tempat_lahir'),
            'tanggal_lahir'  => $this->input->post('tanggal_lahir'),
			'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
            'alamat'         => $this->input->post('alamat'),
			'asal_sekolah'   => $this->input->post('asal_sekolah'),
			'paket_id'       => $this->input->post('paket_id'),
			'jadwal_hari'    => $this->input->post('jadwal'),
            'jadwal_jam'     => $this->input->post('jam'),
			'nama_ortu'      => $this->input->post('nama_ortu'),
            'no_wa_ortu'     => $this->input->post('no_wa_ortu'),
            'pekerjaan_ortu' => $this->input->post('pekerjaan_ortu'),
        );

        // Simpan ke database melalui Model dan ambil ID-nya
        $id_pendaftar = $this->Pendaftaran_model->simpan_data($data);

        // Alihkan ke halaman pembayaran
        redirect('pendaftaran/pembayaran/' . $id_pendaftar);
    }

    // Halaman pembayaran
    public function pembayaran($id) {
        $pendaftaran = $this->Pendaftaran_model->get_data_by_id($id);

        if (!$pendaftaran) {
            $this->session->set_flashdata('error', 'Data pendaftaran tidak ditemukan.');
            redirect('pendaftaran/daftar');
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
            $this->session->set_flashdata('error', 'Upload gagal: ' . $this->upload->display_errors('', ''));
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

        $this->BuktiPembayaran_model->simpan($data);

        $this->session->set_flashdata('success', 'Bukti pembayaran berhasil di-upload. Admin akan segera memverifikasi.');
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
