<?php
// karyawan_tambah.php
// Project: Sistem Informasi Gaji Karyawan PT MANU
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Eksekusi ketika tombol Simpan diklik
if (isset($_POST['submit'])) {
    $nik = $conn->real_escape_string($_POST['nik']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $pendidikan = $conn->real_escape_string($_POST['pendidikan']);
    $jekel = $conn->real_escape_string($_POST['jekel']);

    // Validasi duplikasi NIK agar tidak bentrok di database primary key
    $cek_nik = $conn->query("SELECT * FROM karyawan WHERE nik = '$nik'");
    if ($cek_nik->num_rows > 0) {
        echo "<script>alert('Error: NIK tersebut sudah terdaftar di sistem!'); window.location='karyawan_tambah.php';</script>";
        exit();
    }

    // Query simpan data ke tabel master karyawan
    $query_insert = "INSERT INTO karyawan (nik, nama, pendidikan, jekel) VALUES ('$nik', '$nama', '$pendidikan', '$jekel')";

    if ($conn->query($query_insert)) {
        $_SESSION['notifikasi'] = "Data karyawan baru berhasil ditambahkan!";
        header("Location: karyawan.php");
        exit();
    } else {
        echo "Gagal menambahkan data staf baru: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan - PT MANU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; font-size: 13px; }
        
        /* Rampingkan Ukuran Sidebar */
        .sidebar {
            height: 100vh;
            width: 230px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #062552;
            padding-top: 15px;
            z-index: 1000;
        }
        .sidebar-brand {
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            margin-bottom: 15px;
            display: block;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 10px 20px;
            font-size: 13.5px;
            display: block;
            text-decoration: none;
        }
        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: rgba(0, 0, 0, 0.15);
            font-weight: bold;
        }
        .btn-logout-sidebar {
            position: absolute;
            bottom: 15px;
            left: 15px;
            width: 200px;
            font-size: 13px;
            padding: 6px;
        }
        
        /* Konten Utama Kanan */
        .main-content {
            margin-left: 230px;
            padding: 25px 35px;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a class="sidebar-brand" href="dashboard.php">
        <i class="fa-solid fa-calculator me-2"></i>Payroll PT MANU
    </a>
    <div class="nav flex-column">
        <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a class="nav-link active" href="karyawan.php"><i class="fa-solid fa-users me-2"></i> Data Karyawan</a>
        <a class="nav-link" href="payroll.php"><i class="fa-solid fa-money-bill-wave me-2"></i> Transaksi Penggajian</a>
        <a class="nav-link" href="laporan.php"><i class="fa-solid fa-file-invoice me-2"></i> Laporan Gaji</a>
        <a class="nav-link" href="grafik_gaji.php"><i class="fa-solid fa-chart-pie me-2"></i> Grafik Anggaran Gaji</a>
    </div>
    <a href="logout.php" class="btn btn-danger btn-logout-sidebar fw-semibold">
        <i class="fa-solid fa-sign-out-alt me-1"></i> Logout
    </a>
</div>

<div class="main-content">
    
    <div class="card shadow-sm border-0 mb-3 rounded-3">
        <div class="card-body py-3 px-4">
            <h4 class="fw-bold mb-0" style="font-size: 18px;">Tambah Anggota Karyawan</h4>
            <small class="text-muted">Masukkan biodata staf baru PT MANU</small>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nik" class="form-label fw-semibold text-muted">Nomor Induk Karyawan (NIK)</label>
                    <input type="text" name="nik" id="nik" class="form-control form-control-sm" placeholder="Contoh: 205" required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold text-muted">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control form-control-sm" placeholder="Masukkan nama staf baru..." required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="pendidikan" class="form-label fw-semibold text-muted">Tingkat Pendidikan Terakhir</label>
                    <select name="pendidikan" id="pendidikan" class="form-select form-select-sm" required>
                        <option value="" disabled selected>-- Pilih Kualifikasi --</option>
                        <option value="SLTP">SLTP</option>
                        <option value="SLTA">SLTA</option>
                        <option value="S1">S1</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted d-block">Jenis Kelamin</label>
                    <div class="form-check form-check-inline mt-1">
                        <input class="form-check-input" type="radio" name="jekel" id="lk" value="LK" checked required>
                        <label class="form-check-label" for="lk">Laki-Laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jekel" id="pr" value="PR" required>
                        <label class="form-check-label" for="pr">Perempuan</label>
                    </div>
                </div>

                <hr class="my-4 opacity-55">

                <div class="d-flex gap-2">
                    <button type="submit" name="submit" class="btn btn-primary btn-sm fw-semibold px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Data</button>
                    <a href="karyawan.php" class="btn btn-outline-secondary btn-sm px-3">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>