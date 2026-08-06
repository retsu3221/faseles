<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajar extends CI_Controller {

    private $public_methods = ['login', 'login_proses'];

    public function __construct() {
        parent::__construct();
        $this->load->model('Pengajar_model');
        $this->load->library('session');

        $method = $this->router->fetch_method();
        if (!in_array($method, $this->public_methods) && !$this->session->userdata('pengajar_logged_in')) {
            redirect('pengajar/login');
        }
    }

    // ================================================================
    //  AUTH
    // ================================================================

    public function login() {
        if ($this->session->userdata('pengajar_logged_in')) {
            redirect('pengajar');
        }
        $this->load->view('pengajar/v_login');
    }

    public function login_proses() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        $pengajar = $this->Pengajar_model->get_by_username($username);

        if ($pengajar && !empty($pengajar['password']) && password_verify($password, $pengajar['password'])) {
            $this->session->set_userdata([
                'pengajar_logged_in' => TRUE,
                'pengajar_id'        => $pengajar['id'],
                'pengajar_username'  => $pengajar['username'],
                'pengajar_nama'      => $pengajar['nama_lengkap'],
            ]);
            redirect('pengajar');
        }

        $this->session->set_flashdata('error', 'Username atau password salah.');
        redirect('pengajar/login');
    }

    public function logout() {
        $this->session->unset_userdata(['pengajar_logged_in', 'pengajar_id', 'pengajar_username', 'pengajar_nama']);
        redirect('pengajar/login');
    }

    // ================================================================
    //  JADWAL MENGAJAR
    // ================================================================

    public function index() {
        $pengajar_id = $this->session->userdata('pengajar_id');
        $jadwal      = $this->Pengajar_model->get_jadwal_mengajar($pengajar_id);

        $data = [
            'jadwal'       => $jadwal,
            'total_aktif'  => count(array_filter($jadwal, fn($j) => $j['status'] === 'aktif')),
            'total_selesai'=> count(array_filter($jadwal, fn($j) => $j['status'] === 'selesai')),
        ];
        $this->load->view('pengajar/v_jadwal', $data);
    }
}
