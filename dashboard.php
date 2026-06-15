<?php
// dashboard.php
// Project: Sistem Informasi Gaji Karyawan PT MANU
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Payroll PT MANU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; font-size: 14px; }
        
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
        
        /* Konten Utama Menyesuaikan */
        .main-content {
            margin-left: 230px;
            padding: 25px 35px;
            min-height: 100vh;
        }
        
        /* CSS Khusus Mengecilkan Kotak Statistik */
        .card-custom {
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .card-custom .card-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.85;
            margin-bottom: 5px;
        }
        .card-custom .card-value {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a class="sidebar-brand" href="dashboard.php">
        <i class="fa-solid fa-calculator me-2"></i>Payroll PT MANU
    </a>
    <div class="nav flex-column">
        <a class="nav-link active" href="dashboard.php"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a class="nav-link" href="karyawan.php"><i class="fa-solid fa-users me-2"></i> Data Karyawan</a>
        <a class="nav-link" href="payroll.php"><i class="fa-solid fa-money-bill-wave me-2"></i> Transaksi Penggajian</a>
        <a class="nav-link" href="laporan.php"><i class="fa-solid fa-file-invoice me-2"></i> Laporan Gaji</a>
        <a class="nav-link" href="grafik_gaji.php"><i class="fa-solid fa-chart-pie me-2"></i> Grafik Anggaran Gaji</a>
    </div>
    <a href="logout.php" class="btn btn-danger btn-logout-sidebar fw-semibold">
        <i class="fa-solid fa-sign-out-alt me-1"></i> Logout
    </a>
</div>

<div class="main-content">
    
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-body py-3 px-4">
            <h4 class="fw-bold mb-0">Dashboard</h4>
            <small class="text-muted">Selamat datang, <strong><?php echo $_SESSION['nama_lengkap']; ?></strong></small>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card-custom bg-primary">
                <div class="card-title">Total Karyawan</div>
                <?php $k_count = $conn->query("SELECT COUNT(*) as total FROM karyawan")->fetch_assoc(); ?>
                <p class="card-value"><?php echo $k_count['total']; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom bg-success">
                <div class="card-title">Total Transaksi Gaji</div>
                <?php $p_count = $conn->query("SELECT COUNT(*) as total FROM penggajian")->fetch_assoc(); ?>
                <p class="card-value"><?php echo $p_count['total']; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom bg-danger ">
                <div class="card-title">Total Alokasi Kas Bersih</div>
                <?php $sum_gb = $conn->query("SELECT SUM(gaji_bersih) as total FROM penggajian")->fetch_assoc(); ?>
                <p class="card-value" style="font-size: 21px; padding-top: 4px;">Rp <?php echo number_format($sum_gb['total'] ?? 0, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4 bg-white rounded-3">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-2"><i class="fa-solid fa-circle-info text-primary me-2"></i>Petunjuk Operasional</h6>
            <p class="text-muted mb-0 small">Gunakan menu di bilah navigasi sebelah kiri untuk mulai mengelola database master staf karyawan, memproses hitungan transaksi penggajian bulanan otomatis dengan AJAX, atau mencetak dokumen rekap laporan cetak UAS.</p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>