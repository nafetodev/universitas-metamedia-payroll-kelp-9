<?php
// payroll_simpan.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Memastikan data dikirim melalui metode POST dari form payroll
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $periode            = $conn->real_escape_string($_POST['periode']);
    $nik                = $conn->real_escape_string($_POST['nik']);
    $gaji_pokok         = (int)$_POST['gaji_pokok'];
    $tunjangan_keluarga = (int)$_POST['tunjangan_keluarga'];
    $gaji_bersih        = (int)$_POST['gaji_bersih'];

    // Validasi dasar agar tidak ada data kosong yang masuk ke database
    if (empty($periode) || empty($nik) || $gaji_pokok <= 0) {
        echo "<script>alert('Gagal simpan! Data transaksi tidak valid atau belum lengkap.'); window.location.href='payroll.php';</script>";
        exit();
    }

    // Cek duplikasi: Mencegah input gaji karyawan yang sama pada periode bulan yang sama
    $cek_transaksi = $conn->query("SELECT id_penggajian FROM penggajian WHERE nik = '$nik' AND periode = '$periode'");
    if ($cek_transaksi->num_rows > 0) {
        echo "<script>alert('Gagal simpan! Gaji karyawan tersebut pada periode $periode sudah pernah diinput sebelumnya.'); window.location.href='payroll.php';</script>";
        exit();
    }

    // Query INSERT data ke dalam tabel penggajian
    $query_insert = "INSERT INTO penggajian (nik, periode, gaji_pokok, tunjangan_keluarga, gaji_bersih, tanggal_hitung) 
                     VALUES ('$nik', '$periode', $gaji_pokok, $tunjangan_keluarga, $gaji_bersih, NOW())";

    if ($conn->query($query_insert)) {
        // Jika sukses menyimpan, alihkan kembali ke halaman payroll.php
        header("Location: payroll.php");
        exit();
    } else {
        echo "Terjadi kesalahan database saat menyimpan transaksi: " . $conn->error;
    }
} else {
    // Jika diakses langsung tanpa submit form, tendang balik ke payroll.php
    header("Location: payroll.php");
    exit();
}
?>