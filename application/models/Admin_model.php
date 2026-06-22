<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ================================================================
    //  AUTH
    // ================================================================

    public function get_by_username($username) {
        return $this->db->get_where('admin', ['username' => $username])->row_array();
    }

    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->get('admin')->num_rows() > 0;
    }

    // ================================================================
    //  CRUD ADMIN
    // ================================================================

    public function get_all() {
        return $this->db->order_by('created_at', 'ASC')->get('admin')->result_array();
    }

    public function tambah($data) {
        return $this->db->insert('admin', $data);
    }

    public function update($id, $data) {
        return $this->db->update('admin', $data, ['id' => $id]);
    }

    public function hapus($id) {
        return $this->db->delete('admin', ['id' => $id]);
    }

    // ================================================================
    //  DASHBOARD
    // ================================================================

    public function get_stats() {
        return $this->db->query("
            SELECT
                COUNT(*) AS total_peserta,
                SUM(CASE WHEN status = 'pending'    THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN status = 'diterima'   THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN status = 'ditolak'    THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN status = 'kadaluarsa' THEN 1 ELSE 0 END) AS total_kadaluarsa,
                SUM(CASE WHEN status IS NULL        THEN 1 ELSE 0 END) AS total_belum_upload
            FROM (
                SELECT (
                    SELECT bp.status_verifikasi FROM bukti_pembayaran bp
                    WHERE bp.pendaftaran_id = p.id
                    ORDER BY bp.uploaded_at DESC LIMIT 1
                ) AS status
                FROM pendaftaran p
            ) sub
        ")->row_array();
    }

    public function get_bulanan_chart($tahun) {
        $raw = $this->db->query("
            SELECT
                MONTH(p.tanggal_daftar)  AS bulan,
                COUNT(p.id)              AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima' THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            WHERE YEAR(p.tanggal_daftar) = {$tahun}
            GROUP BY MONTH(p.tanggal_daftar)
        ")->result_array();

        $bulanan = array_fill(1, 12, ['total_daftar' => 0, 'total_pemasukan' => 0]);
        foreach ($raw as $row) {
            $bulanan[(int)$row['bulan']] = $row;
        }
        return $bulanan;
    }

    public function get_rekap_bulan_ini($bulan, $tahun) {
        $rows = $this->db->query("
            SELECT pk.harga,
                   COALESCE(bp.status_verifikasi, 'belum_upload') AS status_verifikasi
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            WHERE MONTH(p.tanggal_daftar) = {$bulan}
              AND YEAR(p.tanggal_daftar)  = {$tahun}
        ")->result_array();

        return [
            'total_daftar'    => count($rows),
            'total_diterima'  => count(array_filter($rows, fn($r) => $r['status_verifikasi'] === 'diterima')),
            'total_pending'   => count(array_filter($rows, fn($r) => $r['status_verifikasi'] === 'pending')),
            'total_pemasukan' => array_sum(array_column(
                array_filter($rows, fn($r) => $r['status_verifikasi'] === 'diterima'), 'harga'
            )),
        ];
    }

    public function get_tahunan() {
        return $this->db->query("
            SELECT
                YEAR(p.tanggal_daftar)   AS tahun,
                COUNT(p.id)              AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima' THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN bukti_pembayaran bp
                   ON bp.id = (
                       SELECT id FROM bukti_pembayaran
                       WHERE pendaftaran_id = p.id
                       ORDER BY uploaded_at DESC LIMIT 1
                   )
            GROUP BY YEAR(p.tanggal_daftar)
            ORDER BY tahun DESC
        ")->result_array();
    }

    public function get_recent_pendaftaran($limit = 8) {
        return $this->db->query("
            SELECT u.nama_lengkap, p.tanggal_daftar, pk.tingkat, pk.tipe_kelas,
                   COALESCE((
                       SELECT bp.status_verifikasi FROM bukti_pembayaran bp
                       WHERE bp.pendaftaran_id = p.id
                       ORDER BY bp.uploaded_at DESC LIMIT 1
                   ), 'belum_upload') AS status_verifikasi
            FROM pendaftaran p
            LEFT JOIN paket pk ON pk.id = p.paket_id
            LEFT JOIN users u  ON u.id  = p.user_id
            ORDER BY p.tanggal_daftar DESC
            LIMIT {$limit}
        ")->result_array();
    }

    // ================================================================
    //  REKAP
    // ================================================================

    public function get_tahun_list() {
        $list = $this->db->query("
            SELECT DISTINCT YEAR(uploaded_at) AS tahun
            FROM bukti_pembayaran ORDER BY tahun DESC
        ")->result_array();
        return empty($list) ? [['tahun' => date('Y')]] : $list;
    }

    public function get_rekap_tahunan_detail($tahun) {
        return $this->db->query("
            SELECT
                MONTH(bp.uploaded_at) AS bulan,
                COUNT(*)              AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN bp.status_verifikasi = 'pending'                 THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN bp.status_verifikasi IN ('ditolak','kadaluarsa') THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM bukti_pembayaran bp
            JOIN pendaftaran p ON p.id  = bp.pendaftaran_id
            JOIN paket pk      ON pk.id = p.paket_id
            WHERE YEAR(bp.uploaded_at) = {$tahun}
            GROUP BY MONTH(bp.uploaded_at)
            ORDER BY bulan
        ")->result_array();
    }

    public function get_summary_tahunan($tahun) {
        return $this->db->query("
            SELECT
                COUNT(*) AS total_daftar,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN 1 ELSE 0 END) AS total_diterima,
                SUM(CASE WHEN bp.status_verifikasi = 'pending'                 THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN bp.status_verifikasi IN ('ditolak','kadaluarsa') THEN 1 ELSE 0 END) AS total_ditolak,
                SUM(CASE WHEN bp.status_verifikasi = 'diterima'                THEN pk.harga ELSE 0 END) AS total_pemasukan
            FROM bukti_pembayaran bp
            JOIN pendaftaran p ON p.id  = bp.pendaftaran_id
            JOIN paket pk      ON pk.id = p.paket_id
            WHERE YEAR(bp.uploaded_at) = {$tahun}
        ")->row_array();
    }

    public function get_rekap_bulanan_detail($bulan, $tahun) {
        return $this->db->query("
            SELECT p.no_transaksi, u.nama_lengkap, p.tanggal_daftar,
                   pk.tingkat, pk.tipe_kelas, pk.harga,
                   bp.status_verifikasi,
                   bp.nama_pengirim, bp.jumlah_transfer, bp.tanggal_transfer, bp.uploaded_at
            FROM bukti_pembayaran bp
            JOIN pendaftaran p ON p.id  = bp.pendaftaran_id
            JOIN paket pk      ON pk.id = p.paket_id
            JOIN users u       ON u.id  = p.user_id
            WHERE MONTH(bp.uploaded_at) = {$bulan}
              AND YEAR(bp.uploaded_at)  = {$tahun}
            ORDER BY bp.uploaded_at ASC
        ")->result_array();
    }

    // ================================================================
    //  CRUD PESERTA (users)
    // ================================================================

    public function get_all_peserta() {
        return $this->db
            ->select('u.id, u.username, u.email, u.created_at,
                u.nama_lengkap, u.tempat_lahir, u.tanggal_lahir, u.jenis_kelamin,
                u.alamat, u.asal_sekolah, u.nama_ortu, u.no_wa_ortu, u.pekerjaan_ortu,
                COUNT(p.id) as jumlah_pendaftaran')
            ->from('users u')
            ->join('pendaftaran p', 'p.user_id = u.id', 'left')
            ->group_by('u.id')
            ->order_by('u.created_at', 'DESC')
            ->get()->result_array();
    }

    public function tambah_peserta($data) {
        return $this->db->insert('users', $data);
    }

    public function update_peserta($id, $data) {
        return $this->db->update('users', $data, ['id' => $id]);
    }

    public function hapus_peserta($id) {
        $pids = $this->db->select('id')->get_where('pendaftaran', ['user_id' => $id])->result_array();
        if (!empty($pids)) {
            $this->db->where_in('pendaftaran_id', array_column($pids, 'id'))->delete('bukti_pembayaran');
        }
        $this->db->delete('pendaftaran', ['user_id' => $id]);
        $this->db->delete('users',       ['id' => $id]);
    }

    public function peserta_username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->get('users')->num_rows() > 0;
    }

    public function peserta_email_exists($email, $exclude_id = null) {
        $this->db->where('email', $email);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->get('users')->num_rows() > 0;
    }
}
