<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_user($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function username_exists($username, $exclude_id) {
        return $this->db
            ->where('username', $username)
            ->where('id !=', $exclude_id)
            ->get('users')
            ->num_rows() > 0;
    }

    public function update_username($id, $username) {
        $this->db->update('users', ['username' => $username], ['id' => $id]);
    }

    public function update_password($id, $hashed_password) {
        $this->db->update('users', ['password' => $hashed_password], ['id' => $id]);
    }

    public function get_paket_aktif($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->where('status_pembayaran', 'lunas')
            ->order_by('tanggal_daftar', 'DESC')
            ->get('pendaftaran')
            ->result_array();
    }

    public function get_pesanan($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->order_by('tanggal_daftar', 'DESC')
            ->get('pendaftaran')
            ->result_array();
    }

    public function update_email($id, $email) {
        $this->db->update('users', ['email' => $email], ['id' => $id]);
    }

    public function email_exists($email, $exclude_id) {
        return $this->db
            ->where('email', $email)
            ->where('id !=', $exclude_id)
            ->get('users')
            ->num_rows() > 0;
    }
}
