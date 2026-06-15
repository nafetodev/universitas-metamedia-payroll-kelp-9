<?php
// proses_login.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

// Menyisipkan file koneksi agar bisa mengakses database
include 'koneksi.php';

// Memastikan data dikirim menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form login dan amankan dari SQL Injection
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username
    $query  = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Jika username ditemukan, ambil datanya
        $user = $result->fetch_assoc();
        
        // Verifikasi password terenkripsi (MD5 atau password_hash bawaan database)
        if (password_verify($password, $user['password'])) {
            // Jika cocok, simpan data user ke dalam session
            $_SESSION['id_user']      = $user['id_user'];
            $_SESSION['username']     = $user['username'];
            $_SESSION['nama_lengkap']  = $user['nama_lengkap'];
            
            // Alihkan halaman ke dashboard utama
            header("Location: dashboard.php");
            exit();
        } else {
            // Jika password salah
            $_SESSION['error'] = "Password yang Anda masukkan salah!";
        }
    } else {
        // Jika username tidak terdaftar
        $_SESSION['error'] = "Username tidak ditemukan!";
    }
    
    // Jika gagal login, kembalikan ke halaman login.php
    header("Location: login.php");
    exit();
} else {
    // Jika diakses ilegal tanpa POST, tendang balik ke login
    header("Location: login.php");
    exit();
}
?>