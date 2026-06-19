<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Akun_model');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper('form');
        $this->cek_login();
    }

    // Pastikan user sudah login sebelum akses halaman akun
    private function cek_login() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('warning', 'Silakan login terlebih dahulu.');
            redirect('auth/login');
        }
    }

    // Halaman paket saya
    public function paket_saya() {
        $data['paket'] = $this->Akun_model->get_paket_aktif($this->session->userdata('user_id'));
        $this->load->view('v_paket_saya', $data);
    }

    // Halaman pesanan saya
    public function pesanan_saya() {
        $data['pesanan'] = $this->Akun_model->get_pesanan($this->session->userdata('user_id'));
        $data['status_config'] = [
            'pending'    => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-warning text-dark',  'icon' => 'bi-clock'],
            'diterima'   => ['label' => 'Lunas',               'class' => 'bg-success text-white',  'icon' => 'bi-check-circle-fill'],
            'ditolak'    => ['label' => 'Ditolak',             'class' => 'bg-danger text-white',   'icon' => 'bi-x-circle-fill'],
            'kadaluarsa' => ['label' => 'Kadaluarsa',          'class' => 'bg-secondary text-white','icon' => 'bi-calendar-x-fill'],
        ];
        $this->load->view('v_pesanan_saya', $data);
    }

    // Halaman informasi akun
    public function informasi() {
        $data['user'] = $this->Akun_model->get_user($this->session->userdata('user_id'));
        $this->load->view('v_informasi_akun', $data);
    }

    // Proses update data pribadi
    public function update_profil() {
        $user_id = $this->session->userdata('user_id');

        $this->db->update('users', [
            'nama_lengkap'   => $this->input->post('nama_lengkap', TRUE),
            'tempat_lahir'   => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'  => $this->input->post('tanggal_lahir') ?: NULL,
            'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
            'alamat'         => $this->input->post('alamat', TRUE),
            'asal_sekolah'   => $this->input->post('asal_sekolah', TRUE) ?: NULL,
            'nama_ortu'      => $this->input->post('nama_ortu', TRUE) ?: NULL,
            'no_wa_ortu'     => $this->input->post('no_wa_ortu') ?: NULL,
            'pekerjaan_ortu' => $this->input->post('pekerjaan_ortu', TRUE) ?: NULL,
        ], ['id' => $user_id]);

        $nama = $this->input->post('nama_lengkap', TRUE);
        if ($nama) {
            $this->session->set_userdata('nama_lengkap', $nama);
        }

        $this->session->set_flashdata('success', 'Data pribadi berhasil diperbarui.');
        redirect('akun/informasi');
    }

    // Proses update username
    public function update_username() {
        $this->form_validation->set_rules('username_baru', 'Username', 'required|trim|min_length[3]|max_length[50]', [
            'required'   => 'Username tidak boleh kosong.',
            'min_length' => 'Username minimal 3 karakter.',
            'max_length' => 'Username maksimal 50 karakter.',
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', trim(strip_tags($this->form_validation->error_string())));
            redirect('akun/informasi');
            return;
        }

        $username_baru = $this->input->post('username_baru', TRUE);
        $user_id       = $this->session->userdata('user_id');

        if ($this->Akun_model->username_exists($username_baru, $user_id)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan, coba yang lain.');
            redirect('akun/informasi');
            return;
        }

        $this->Akun_model->update_username($user_id, $username_baru);
        $this->session->set_userdata('username', $username_baru);
        $this->session->set_flashdata('success', 'Username berhasil diperbarui.');
        redirect('akun/informasi');
    }

    // Proses update email
    public function update_email() {
        $this->form_validation->set_rules('email_baru', 'Email', 'required|trim|valid_email', [
            'required'    => 'Email tidak boleh kosong.',
            'valid_email' => 'Format email tidak valid.',
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', trim(strip_tags($this->form_validation->error_string())));
            redirect('akun/informasi');
            return;
        }

        $email_baru = $this->input->post('email_baru', TRUE);
        $user_id    = $this->session->userdata('user_id');

        if ($this->Akun_model->email_exists($email_baru, $user_id)) {
            $this->session->set_flashdata('error', 'Email sudah digunakan akun lain.');
            redirect('akun/informasi');
            return;
        }

        $this->Akun_model->update_email($user_id, $email_baru);
        $this->session->set_flashdata('success', 'Email berhasil diperbarui.');
        redirect('akun/informasi');
    }

    // Halaman jadwal user
    public function jadwal() {
        $user_id = $this->session->userdata('user_id');
        $data['jadwal'] = $this->db
            ->select('j.*, CONCAT(pak.tingkat, " – ", pak.tipe_kelas) as nama_paket,
                      pg.nama_lengkap as nama_pengajar,
                      pg.mata_pelajaran, pg.no_wa as wa_pengajar')
            ->from('jadwal j')
            ->join('pendaftaran p',  'p.id  = j.pendaftaran_id')
            ->join('paket pak',      'pak.id = p.paket_id')
            ->join('pengajar pg',    'pg.id = j.pengajar_id')
            ->where('p.user_id', $user_id)
            ->order_by('j.created_at', 'DESC')
            ->get()->result_array();
        $this->load->view('v_jadwal', $data);
    }

    // Proses update password
    public function update_password() {
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required', [
            'required' => 'Password lama tidak boleh kosong.',
        ]);
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]', [
            'required'   => 'Password baru tidak boleh kosong.',
            'min_length' => 'Password baru minimal 6 karakter.',
        ]);
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]', [
            'required' => 'Konfirmasi password tidak boleh kosong.',
            'matches'  => 'Konfirmasi password tidak cocok.',
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', trim(strip_tags($this->form_validation->error_string())));
            redirect('akun/informasi');
            return;
        }

        $user_id      = $this->session->userdata('user_id');
        $password_lama = $this->input->post('password_lama');
        $password_baru = $this->input->post('password_baru');

        $user = $this->Akun_model->get_user($user_id);

        if (!password_verify($password_lama, $user->password)) {
            $this->session->set_flashdata('error', 'Password lama tidak sesuai.');
            redirect('akun/informasi');
            return;
        }

        $this->Akun_model->update_password($user_id, password_hash($password_baru, PASSWORD_BCRYPT));
        $this->session->set_flashdata('success', 'Password berhasil diperbarui.');
        redirect('akun/informasi');
    }
}
