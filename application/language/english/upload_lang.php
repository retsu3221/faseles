<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Terjemahan pesan error upload CodeIgniter ke bahasa Indonesia.
 *
 * File di folder application/language/ dimuat lebih dulu daripada
 * system/language/, jadi pesan di sini menimpa bawaan CodeIgniter tanpa
 * perlu mengubah $config['language'] — yang kalau diubah justru berisiko
 * error karena file bahasa lain (form_validation, db, dsb.) belum ada
 * terjemahannya.
 *
 * Istilah "file" dipertahankan agar selaras dengan teks yang sudah dipakai
 * aplikasi, misalnya "Pilih file bukti transfer."
 */

// Kesalahan dari sisi pengguna (paling sering muncul)
$lang['upload_no_file_selected']       = 'Anda belum memilih file untuk diunggah.';
$lang['upload_invalid_filetype']       = 'Jenis file yang Anda unggah tidak diizinkan. Gunakan JPG, PNG, PDF, GIF, WebP, atau BMP.';
$lang['upload_invalid_filesize']       = 'Ukuran file terlalu besar. Maksimal 2 MB.';
$lang['upload_invalid_dimensions']     = 'Ukuran dimensi gambar melebihi batas yang diizinkan.';
$lang['upload_file_exceeds_limit']     = 'Ukuran file melebihi batas maksimum yang diizinkan server.';
$lang['upload_file_exceeds_form_limit']= 'Ukuran file melebihi batas yang ditentukan formulir.';
$lang['upload_bad_filename']           = 'Nama file tersebut sudah ada di server.';

// Kesalahan dari sisi server / konfigurasi
$lang['upload_file_partial']           = 'File hanya terunggah sebagian. Silakan coba lagi.';
$lang['upload_no_temp_directory']      = 'Folder sementara tidak ditemukan di server.';
$lang['upload_unable_to_write_file']   = 'File tidak dapat disimpan ke server.';
$lang['upload_stopped_by_extension']   = 'Proses unggah dihentikan oleh ekstensi PHP.';
$lang['upload_destination_error']      = 'Terjadi masalah saat memindahkan file ke folder tujuan.';
$lang['upload_no_filepath']            = 'Lokasi penyimpanan file tidak valid.';
$lang['upload_no_file_types']          = 'Jenis file yang diizinkan belum ditentukan.';
$lang['upload_not_writable']           = 'Folder tujuan unggahan tidak dapat ditulisi.';
$lang['upload_userfile_not_set']       = 'File tidak terkirim dengan benar. Silakan coba lagi.';
