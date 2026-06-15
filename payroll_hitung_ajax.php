<?php
// payroll_hitung_ajax.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Memastikan parameter NIK dikirimkan oleh JavaScript AJAX
if (isset($_GET['nik'])) {
    $nik = $conn->real_escape_string($_GET['nik']);

    // Query untuk mengambil data karyawan sekaligus menarik Gaji Pokok berdasarkan tingkat pendidikannya
    $query = "SELECT karyawan.*, gaji_pokok.gaji_pokok 
              FROM karyawan 
              JOIN gaji_pokok ON karyawan.pendidikan = gaji_pokok.pendidikan 
              WHERE karyawan.nik = '$nik'";
              
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $karyawan = $result->fetch_assoc();

        // 1. Ambil Nilai Gaji Pokok (GP)
        $gaji_pokok = (int)$karyawan['gaji_pokok'];

        // 2. Hitung Tunjangan Keluarga (TK) berdasarkan Jenis Kelamin (jekel)
        // LK = 20% dari GP, PR = 10% dari GP
        if ($karyawan['jekel'] == 'LK') {
            $tunjangan_keluarga = 0.20 * $gaji_pokok;
        } else {
            $tunjangan_keluarga = 0.10 * $gaji_pokok;
        }

        // 3. Hitung Gaji Bersih (GB = GP + TK)
        $gaji_bersih = $gaji_pokok + $tunjangan_keluarga;

        // 4. Bungkus data hasil hitungan ke dalam format JSON agar bisa dibaca oleh JavaScript
        $output = [
            'gaji_pokok' => $gaji_pokok,
            'tunjangan_keluarga' => $tunjangan_keluarga,
            'gaji_bersih' => $gaji_bersih
        ];

        echo json_encode($output);
    } else {
        // Jika data NIK tidak ditemukan di database
        echo json_encode([
            'gaji_pokok' => 0,
            'tunjangan_keluarga' => 0,
            'gaji_bersih' => 0
        ]);
    }
}
?>