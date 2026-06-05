<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Cek username & password untuk login
    public function cek_login($username, $password) {
        $user = $this->db->get_where('users', ['username' => $username])->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    // Cek apakah username sudah terdaftar
    public function username_exists($username) {
        return $this->db->get_where('users', ['username' => $username])->num_rows() > 0;
    }

    // Simpan user baru ke tabel users
    public function simpan_user($data) {
        $this->db->insert('users', $data);
    }
}
