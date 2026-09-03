<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper('form');
    }

    // Redirect default ke login
    public function index() {
        redirect('auth/login');
    }

    // Tampilkan halaman login
    public function login() {
        // Jika sudah login, langsung redirect ke beranda
        if ($this->session->userdata('logged_in')) {
            redirect('pendaftaran');
        }
        $this->load->view('v_login');
    }

    // Tampilkan halaman register
    public function register() {
        // Jika sudah login, langsung redirect ke beranda
        if ($this->session->userdata('logged_in')) {
            redirect('pendaftaran');
        }
        $this->load->view('v_register');
    }

    // Proses form login
    public function proses_login() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim', [
            'required' => 'Username tidak boleh kosong.',
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]', [
            'required'   => 'Password tidak boleh kosong.',
            'min_length' => 'Password minimal 6 karakter.',
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('v_login');
            return;
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        $user = $this->Auth_model->cek_login($username, $password);

        if ($user) {
            $this->session->set_userdata([
                'logged_in'    => true,
                'user_id'      => $user->id,
                'username'     => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
            ]);
            $this->session->set_flashdata('success', 'Selamat datang ' . ($user->nama_lengkap ?: $user->username));
            redirect('pendaftaran');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }
    }

    // Proses form register
    public function proses_register() {
        $username     = $this->input->post('username', TRUE);
        $email        = $this->input->post('email', TRUE);
        $nama_lengkap = $this->input->post('nama_lengkap', TRUE);
        $password     = $this->input->post('password');
        $konfirmasi   = $this->input->post('konfirmasi_password');

        // Validasi field wajib
        if (empty($username) || empty($email) || empty($nama_lengkap) || empty($password)) {
            $this->session->set_flashdata('error', 'Semua kolom wajib diisi.');
            redirect('auth/register');
            return;
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Format email tidak valid.');
            redirect('auth/register');
            return;
        }

        // Validasi panjang username (disamakan dengan aturan ubah username di Akun)
        $panjang_username = mb_strlen($username);
        if ($panjang_username < 3 || $panjang_username > 50) {
            $this->session->set_flashdata('error', 'Username harus 3 sampai 50 karakter.');
            redirect('auth/register');
            return;
        }

        // Validasi panjang password (disamakan dengan aturan di halaman lain: 6 karakter)
        if (strlen($password) < 6) {
            $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
            redirect('auth/register');
            return;
        }

        // Validasi password cocok
        if ($password !== $konfirmasi) {
            $this->session->set_flashdata('error', 'Password dan konfirmasi password tidak cocok.');
            redirect('auth/register');
            return;
        }

        // Cek username sudah dipakai
        if ($this->Auth_model->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan, coba yang lain.');
            redirect('auth/register');
            return;
        }

        // Cek email sudah dipakai
        if ($this->Auth_model->email_exists($email)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar, gunakan email lain.');
            redirect('auth/register');
            return;
        }

        // Simpan user baru beserta data pribadi.
        // Kolom tanggal bertipe DATE menolak string kosong, jadi dikosongkan ke NULL.
        $tersimpan = $this->Auth_model->simpan_user([
            'username'      => $username,
            'email'         => $email,
            'password'      => password_hash($password, PASSWORD_BCRYPT),
            'nama_lengkap'  => $nama_lengkap,
            'tempat_lahir'  => $this->input->post('tempat_lahir', TRUE) ?: NULL,
            'tanggal_lahir' => $this->input->post('tanggal_lahir') ?: NULL,
            'jenis_kelamin' => $this->input->post('jenis_kelamin') ?: NULL,
            'alamat'        => $this->input->post('alamat', TRUE) ?: NULL,
            'asal_sekolah'  => $this->input->post('asal_sekolah') ?: NULL,
            'nama_ortu'     => $this->input->post('nama_ortu', TRUE) ?: NULL,
            'no_wa_ortu'    => $this->input->post('no_wa_ortu') ?: NULL,
            'pekerjaan_ortu'=> $this->input->post('pekerjaan_ortu', TRUE) ?: NULL,
        ]);

        // Pengaman terakhir: kalau dua orang mendaftar bersamaan dengan username
        // atau email yang sama, UNIQUE index di database yang menolaknya.
        if (!$tersimpan) {
            $this->session->set_flashdata('error', 'Username atau email sudah digunakan. Silakan coba yang lain.');
            redirect('auth/register');
            return;
        }

        $this->session->set_flashdata('success', 'Akun berhasil dibuat! Silakan masuk.');
        redirect('auth/login');
    }

    // Logout
    public function logout() {
        $this->session->unset_userdata(['logged_in', 'user_id', 'username', 'nama_lengkap']);
        $this->session->set_flashdata('logout', 'Anda telah berhasil keluar.');
        redirect('auth/login');
    }
}
