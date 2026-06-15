<?php
// payroll_hapus.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Memastikan parameter id transaksi penggajian dilempar di URL browser
if (isset($_GET['id'])) {
    $id_penggajian = (int)$_GET['id'];

    // Query menghapus satu baris data transaksi berdasarkan ID primary key-nya
    $query_delete = "DELETE FROM penggajian WHERE id_penggajian = $id_penggajian";

    if ($conn->query($query_delete)) {
        // Jika sukses terhapus, kembalikan ke halaman input penggajian
        header("Location: payroll.php");
        exit();
    } else {
        echo "Gagal menghapus riwayat transaksi gaji: " . $conn->error;
    }
} else {
    // Jika diakses ilegal tanpa ID, lempar balik ke payroll.php
    header("Location: payroll.php");
    exit();
}
?>