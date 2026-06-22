<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua paket aktif (untuk dropdown form)
    public function get_aktif() {
        return $this->db
            ->where('is_aktif', 1)
            ->order_by('tingkat')
            ->order_by('tipe_kelas')
            ->order_by('durasi_menit')
            ->get('paket')
            ->result_array();
    }

    // Ambil 1 paket berdasarkan id
    public function get_by_id($id) {
        return $this->db->get_where('paket', ['id' => $id])->row_array();
    }

    public function get_all() {
        return $this->db
            ->order_by('tingkat')
            ->order_by('tipe_kelas')
            ->get('paket')->result_array();
    }

    public function tambah($data) {
        return $this->db->insert('paket', $data);
    }

    public function update($id, $data) {
        return $this->db->update('paket', $data, ['id' => $id]);
    }

    public function hapus($id) {
        return $this->db->delete('paket', ['id' => $id]);
    }
}
