<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db
            ->select('j.*, u.nama_lengkap as nama_siswa, u.username,
                      CONCAT(pak.tingkat, " – ", pak.tipe_kelas) as nama_paket,
                      pg.nama_lengkap as nama_pengajar, pg.tingkat_diajar')
            ->from('jadwal j')
            ->join('pendaftaran p', 'p.id  = j.pendaftaran_id')
            ->join('users u',       'u.id  = p.user_id')
            ->join('paket pak',     'pak.id = p.paket_id')
            ->join('pengajar pg',   'pg.id = j.pengajar_id')
            ->order_by('j.created_at', 'DESC')
            ->get()->result_array();
    }

    // Pendaftaran yang sudah lunas tapi belum punya jadwal
    public function get_pendaftaran_lunas() {
        return $this->db
            ->select('p.id, u.nama_lengkap, u.username,
                      CONCAT(pak.tingkat, " – ", pak.tipe_kelas) as nama_paket,
                      pak.jumlah_pertemuan, pak.durasi_menit,
                      p.jadwal_hari, p.jadwal_jam')
            ->from('pendaftaran p')
            ->join('users u',   'u.id  = p.user_id')
            ->join('paket pak', 'pak.id = p.paket_id')
            ->where("(SELECT status_verifikasi FROM bukti_pembayaran WHERE pendaftaran_id = p.id ORDER BY uploaded_at DESC LIMIT 1) = 'diterima'", NULL, FALSE)
            ->where("NOT EXISTS (SELECT 1 FROM jadwal j WHERE j.pendaftaran_id = p.id)", NULL, FALSE)
            ->order_by('u.nama_lengkap', 'ASC')
            ->get()->result_array();
    }

    // Cek apakah sebuah pendaftaran sudah punya jadwal
    public function sudah_dijadwalkan($pendaftaran_id) {
        return $this->db
            ->where('pendaftaran_id', $pendaftaran_id)
            ->count_all_results('jadwal') > 0;
    }

    // Paket yang dibayar pada sebuah pendaftaran — dipakai untuk menentukan
    // jumlah pertemuan jadwal agar selalu sesuai paket
    public function get_paket_pendaftaran($pendaftaran_id) {
        return $this->db
            ->select('pak.id, pak.tingkat, pak.tipe_kelas, pak.durasi_menit, pak.jumlah_pertemuan')
            ->from('pendaftaran p')
            ->join('paket pak', 'pak.id = p.paket_id')
            ->where('p.id', $pendaftaran_id)
            ->get()->row_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('jadwal', ['id' => $id])->row_array();
    }

    public function tambah($data) {
        return $this->db->insert('jadwal', $data);
    }

    // Increment pertemuan_selesai dan update status otomatis
    public function selesai_pertemuan($id) {
        $jadwal = $this->get_by_id($id);
        if (!$jadwal) return false;

        $baru   = (int)$jadwal['pertemuan_selesai'] + 1;
        $status = ($baru >= (int)$jadwal['jumlah_pertemuan']) ? 'selesai' : 'aktif';

        $this->db->update('jadwal', [
            'pertemuan_selesai' => $baru,
            'status'            => $status,
        ], ['id' => $id]);

        return $baru;
    }

    public function hapus($id) {
        return $this->db->delete('jadwal', ['id' => $id]);
    }
}
