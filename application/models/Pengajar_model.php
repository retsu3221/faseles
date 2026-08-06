<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajar_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db->order_by('nama_lengkap', 'ASC')->get('pengajar')->result_array();
    }

    public function get_by_username($username) {
        return $this->db->get_where('pengajar', ['username' => $username])->row_array();
    }

    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('pengajar')->num_rows() > 0;
    }

    // Semua jadwal yang diampu seorang pengajar
    public function get_jadwal_mengajar($pengajar_id) {
        return $this->db
            ->select('j.*, u.nama_lengkap as nama_siswa, u.username as username_siswa,
                      u.tempat_lahir, u.tanggal_lahir, u.jenis_kelamin,
                      u.asal_sekolah, u.no_wa_ortu,
                      CONCAT(pak.tingkat, " – ", pak.tipe_kelas) as nama_paket, pak.durasi_menit')
            ->from('jadwal j')
            ->join('pendaftaran p', 'p.id  = j.pendaftaran_id')
            ->join('users u',       'u.id  = p.user_id')
            ->join('paket pak',     'pak.id = p.paket_id')
            ->where('j.pengajar_id', $pengajar_id)
            ->order_by('j.status', 'ASC')
            ->order_by('j.created_at', 'DESC')
            ->get()->result_array();
    }

    public function tambah($data) {
        return $this->db->insert('pengajar', $data);
    }

    public function update($id, $data) {
        return $this->db->update('pengajar', $data, ['id' => $id]);
    }

    public function hapus($id) {
        return $this->db->delete('pengajar', ['id' => $id]);
    }
}
