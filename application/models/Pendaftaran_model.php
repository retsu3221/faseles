<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran_model extends CI_Model {

    public function simpan_data($data) {
        $this->db->insert('pendaftaran', $data);
        return $this->db->insert_id(); 
    }

    public function get_data_by_id($id) {
        return $this->db->get_where('pendaftaran', ['id' => $id])->row_array();
    }

    public function count_hari_ini() {
        return $this->db
            ->where('DATE(tanggal_daftar)', date('Y-m-d'))
            ->count_all_results('pendaftaran');
    }
}
