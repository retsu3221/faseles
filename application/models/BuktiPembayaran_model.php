<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BuktiPembayaran_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function simpan($data) {
        return $this->db->insert('bukti_pembayaran', $data);
    }

    public function get_by_pendaftaran($pendaftaran_id) {
        return $this->db
            ->where('pendaftaran_id', $pendaftaran_id)
            ->order_by('uploaded_at', 'DESC')
            ->get('bukti_pembayaran')
            ->result_array();
    }

    public function get_latest($pendaftaran_id) {
        return $this->db
            ->where('pendaftaran_id', $pendaftaran_id)
            ->order_by('uploaded_at', 'DESC')
            ->limit(1)
            ->get('bukti_pembayaran')
            ->row_array();
    }
}
