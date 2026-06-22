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

    // Generate nomor transaksi unik untuk hari ini
    public function generate_no_transaksi() {
        $count = $this->count_hari_ini() + 1;
        return 'FASE-' . date('Ymd') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    // Update data pribadi user saat proses daftar
    public function update_user_data($user_id, $data) {
        return $this->db->update('users', $data, ['id' => $user_id]);
    }

    // Query list pembayaran untuk halaman admin (JOIN 4 tabel)
    public function get_list_pembayaran() {
        return $this->db->query("
            SELECT p.id, p.no_transaksi, u.nama_lengkap, p.tanggal_daftar,
                   pk.tingkat, pk.tipe_kelas, pk.harga,
                   u.username,
                   bp.id              AS bp_id,
                   bp.file_bukti, bp.nama_pengirim, bp.jumlah_transfer,
                   bp.tanggal_transfer, bp.catatan, bp.catatan_admin,
                   bp.status_verifikasi, bp.uploaded_at
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN users  u  ON u.id  = p.user_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            ORDER BY p.tanggal_daftar DESC
        ")->result_array();
    }
}
