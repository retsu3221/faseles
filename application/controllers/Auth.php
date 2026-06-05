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
                'logged_in' => true,
                'user_id'   => $user->id,
                'username'  => $user->username,
                'role'      => $user->role,
            ]);
            $this->session->set_flashdata('success', 'Selamat datang ' . $user->username);
            redirect('pendaftaran');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }
    }

    // Proses form register
    public function proses_register() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $konfirmasi = $this->input->post('konfirmasi_password');
        $role = $this->input->post('role');

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

        // Simpan user baru
        $this->Auth_model->simpan_user([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => $role,
        ]);

        $this->session->set_flashdata('success', 'Akun berhasil dibuat! Silakan login.');
        redirect('auth/login');
    }

    // Logout
    public function logout() {
        $this->session->unset_userdata(['logged_in', 'user_id', 'username', 'role']);
        $this->session->set_flashdata('logout', 'Anda telah berhasil logout.');
        redirect('auth/login');
    }
}
