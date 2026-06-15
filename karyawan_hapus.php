<?php
// karyawan_hapus.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Memastikan parameter NIK ada di URL browser
if (isset($_GET['nik'])) {
    $nik = $conn->real_escape_string($_GET['nik']);

    // Jalankan query menghapus data karyawan berdasarkan NIK
    // Catatan: Karena di database dipasang ON DELETE CASCADE, 
    // riwayat transaksi gaji karyawan ini di tabel penggajian akan ikut terhapus otomatis.
    $query_delete = "DELETE FROM karyawan WHERE nik = '$nik'";

    if ($conn->query($query_delete)) {
        // Jika sukses terhapus, kembalikan ke halaman utama karyawan
        header("Location: karyawan.php");
        exit();
    } else {
        // Jika gagal karena galat sistem database
        echo "Gagal menghapus data karyawan: " . $conn->error;
    }
} else {
    // Jika mencoba akses langsung file tanpa melempar parameter NIK
    header("Location: karyawan.php");
    exit();
}
?>