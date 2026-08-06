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

    public function email_exists($email, $exclude_id) {
        return $this->db
            ->where('email', $email)
            ->where('id !=', $exclude_id)
            ->get('users')
            ->num_rows() > 0;
    }

    public function update_username($id, $username) {
        $this->db->update('users', ['username' => $username], ['id' => $id]);
    }

    public function update_email($id, $email) {
        $this->db->update('users', ['email' => $email], ['id' => $id]);
    }

    public function update_password($id, $hashed_password) {
        $this->db->update('users', ['password' => $hashed_password], ['id' => $id]);
    }

    public function update_profil($id, $data) {
        $this->db->update('users', $data, ['id' => $id]);
    }

    public function get_jadwal_user($user_id) {
        return $this->db
            ->select('j.*, CONCAT(pak.tingkat, " – ", pak.tipe_kelas) as nama_paket,
                      pg.nama_lengkap as nama_pengajar,
                      pg.tingkat_diajar, pg.no_wa as wa_pengajar')
            ->from('jadwal j')
            ->join('pendaftaran p', 'p.id  = j.pendaftaran_id')
            ->join('paket pak',     'pak.id = p.paket_id')
            ->join('pengajar pg',   'pg.id = j.pengajar_id')
            ->where('p.user_id', $user_id)
            ->order_by('j.created_at', 'DESC')
            ->get()->result_array();
    }

    private function status_subquery() {
        return "COALESCE((SELECT bp.status_verifikasi FROM bukti_pembayaran bp WHERE bp.pendaftaran_id = pendaftaran.id ORDER BY bp.uploaded_at DESC LIMIT 1), 'pending')";
    }

    public function get_paket_aktif($user_id) {
        $sub = $this->status_subquery();
        return $this->db
            ->select("pendaftaran.*, paket.tipe_kelas, paket.durasi_menit, paket.jumlah_pertemuan, paket.harga,
                      u.nama_lengkap, u.asal_sekolah, u.nama_ortu, u.no_wa_ortu,
                      $sub AS status_verifikasi", FALSE)
            ->from('pendaftaran')
            ->join('paket', 'paket.id = pendaftaran.paket_id', 'left')
            ->join('users u', 'u.id = pendaftaran.user_id', 'left')
            ->where('pendaftaran.user_id', $user_id)
            ->where("$sub = 'diterima'", NULL, FALSE)
            ->order_by('pendaftaran.tanggal_daftar', 'DESC')
            ->get()->result_array();
    }

    public function get_pesanan($user_id) {
        $sub = $this->status_subquery();
        return $this->db
            ->select("pendaftaran.*, paket.tipe_kelas, paket.durasi_menit, paket.jumlah_pertemuan, paket.harga,
                      u.nama_lengkap, u.asal_sekolah, u.nama_ortu, u.no_wa_ortu,
                      $sub AS status_verifikasi", FALSE)
            ->from('pendaftaran')
            ->join('paket', 'paket.id = pendaftaran.paket_id', 'left')
            ->join('users u', 'u.id = pendaftaran.user_id', 'left')
            ->where('pendaftaran.user_id', $user_id)
            ->order_by('pendaftaran.tanggal_daftar', 'DESC')
            ->get()->result_array();
    }
}
