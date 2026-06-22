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
