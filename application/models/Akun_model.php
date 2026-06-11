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

    // Status pembayaran diambil dari bukti_pembayaran terbaru.
    // Belum ada bukti = 'pending'. ('diterima' setara lunas)
    private function status_subquery() {
        return "COALESCE((SELECT bp.status_verifikasi FROM bukti_pembayaran bp WHERE bp.pendaftaran_id = pendaftaran.id ORDER BY bp.uploaded_at DESC LIMIT 1), 'pending')";
    }

    public function get_paket_aktif($user_id) {
        $sub = $this->status_subquery();
        return $this->db
            ->select("pendaftaran.*, paket.tipe_kelas, paket.durasi_menit, paket.jumlah_pertemuan, paket.harga, $sub AS status_pembayaran", FALSE)
            ->from('pendaftaran')
            ->join('paket', 'paket.id = pendaftaran.paket_id', 'left')
            ->where('pendaftaran.user_id', $user_id)
            ->where("$sub = 'diterima'", NULL, FALSE)
            ->order_by('pendaftaran.tanggal_daftar', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_pesanan($user_id) {
        $sub = $this->status_subquery();
        return $this->db
            ->select("pendaftaran.*, paket.tipe_kelas, paket.durasi_menit, paket.jumlah_pertemuan, paket.harga, $sub AS status_pembayaran", FALSE)
            ->from('pendaftaran')
            ->join('paket', 'paket.id = pendaftaran.paket_id', 'left')
            ->where('pendaftaran.user_id', $user_id)
            ->order_by('pendaftaran.tanggal_daftar', 'DESC')
            ->get()
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
