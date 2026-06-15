<?php
// logout.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

session_start();

// Menghapus semua data session yang terdaftar
session_unset();

// Menghancurkan session login secara total
session_destroy();

// Mengalihkan halaman kembali ke form login utama
header("Location: login.php");
exit();
?>
