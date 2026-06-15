<?php
// koneksi.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

// Memulai session PHP untuk menyimpan status login admin/operator
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host     = "localhost";
$user     = "root";          // User bawaan Laragon
$password = "";              // Password bawaan Laragon (kosong)
$database = "gajiptmanu";     // Nama database yang kamu gunakan

// Membuat koneksi menggunakan objek MySQLi
$conn = new mysqli($host, $user, $password, $database);

// Periksa apakah koneksi ke database berhasil
if ($conn->connect_error) {
    die("Koneksi ke database gajiptmanu gagal: " . $conn->connect_error);
}
?>