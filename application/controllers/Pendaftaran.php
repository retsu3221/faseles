<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Panggil model yang baru kita buat
        $this->load->model('Pendaftaran_model');
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
        $this->load->view('v_daftar_new');
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
			'kelas'          => $this->input->post('kelas'),
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

        $harga = [
            'TK'  => ['Privat1' => 330000, 'Privat2' => 430000, 'Kelompok1' => 290000, 'Kelompok2' => 380000],
            'SMP' => ['Privat1' => 365000, 'Privat2' => 480000, 'Kelompok1' => 330000, 'Kelompok2' => 430000],
            'SMA' => ['Privat1' => 440000, 'Privat2' => 580000, 'Kelompok1' => 405000, 'Kelompok2' => 530000],
        ];

        $label_kelas = [
            'Privat1'   => 'Privat (45 Menit | 8x Pertemuan)',
            'Privat2'   => 'Privat (60 Menit | 8x Pertemuan)',
            'Kelompok1' => 'Kelompok (45 Menit | 8x Pertemuan)',
            'Kelompok2' => 'Kelompok (60 Menit | 8x Pertemuan)',
        ];

        $tingkat = $pendaftaran['asal_sekolah'];
        $kelas   = $pendaftaran['kelas'];

        $data['pendaftaran']  = $pendaftaran;
        $data['label_kelas']  = $label_kelas[$kelas] ?? $kelas;
        $data['total_biaya']  = $harga[$tingkat][$kelas] ?? 0;

        $data['status_config'] = [
            'pending'    => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-warning text-dark', 'icon' => 'bi-clock'],
            'lunas'      => ['label' => 'Lunas',               'class' => 'bg-success text-white', 'icon' => 'bi-check-circle-fill'],
            'ditolak'    => ['label' => 'Ditolak',             'class' => 'bg-danger text-white',  'icon' => 'bi-x-circle-fill'],
            'kadaluarsa' => ['label' => 'Kadaluarsa',          'class' => 'bg-secondary text-white','icon' => 'bi-calendar-x-fill'],
        ];

        $this->load->view('v_pembayaran', $data);
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
