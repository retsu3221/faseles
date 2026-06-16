<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran_model extends CI_Model {

    public function simpan_data($data) {
        $this->db->insert('pendaftaran', $data);
        return $this->db->insert_id(); 
    }

    public function get_data_by_id($id) {
        return $this->db
            ->select('pendaftaran.*, u.nama_lengkap, u.tempat_lahir, u.tanggal_lahir,
                      u.jenis_kelamin, u.alamat, u.asal_sekolah,
                      u.nama_ortu, u.no_wa_ortu, u.pekerjaan_ortu')
            ->from('pendaftaran')
            ->join('users u', 'u.id = pendaftaran.user_id', 'left')
            ->where('pendaftaran.id', $id)
            ->get()->row_array();
    }

    public function count_hari_ini() {
        return $this->db
            ->where('DATE(tanggal_daftar)', date('Y-m-d'))
            ->count_all_results('pendaftaran');
    }
}
