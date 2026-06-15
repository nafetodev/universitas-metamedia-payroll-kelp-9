<?php
// laporan.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil daftar pilihan periode bulan yang unik dari database untuk filter dropdown (Sudah difiks dari eror)
$periode_query = $conn->query("SELECT DISTINCT periode FROM penggajian ORDER BY periode DESC");

// Tangkap filter periode jika dipilih oleh admin
$filter_periode = isset($_GET['periode']) ? $conn->real_escape_string($_GET['periode']) : '';


// =========================================================
// 🔥 MASUKKAN LOGIKA PAGINATION DI SINI (HANYA TAMBAHAN)
// =========================================================
$limit = 5; // Batas maksimal data yang tampil per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $limit) - $limit : 0;	

// 1. Hitung jumlah total data yang sesuai dengan filter periode
if (!empty($filter_periode)) {
    $total_data_query = $conn->query("SELECT COUNT(*) as jumlah FROM v_laporan_gaji WHERE periode = '$filter_periode'");
} else {
    $total_data_query = $conn->query("SELECT COUNT(*) as jumlah FROM v_laporan_gaji");
}
$total_data_row = $total_data_query->fetch_assoc();
$total_data = $total_data_row['jumlah'];
$total_halaman = ceil($total_data / $limit);

// 2. Modifikasi kueri utama untuk menyisipkan LIMIT dan OFFSET (Halaman Awal)
if (!empty($filter_periode)) {
    $laporan_query = $conn->query("SELECT * FROM v_laporan_gaji WHERE periode = '$filter_periode' ORDER BY nama_karyawan ASC LIMIT $halaman_awal, $limit");
} else {
    $laporan_query = $conn->query("SELECT * FROM v_laporan_gaji ORDER BY id_penggajian DESC LIMIT $halaman_awal, $limit");
}
// =========================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Gaji - PT MANU</title>
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
        <a class="nav-link" href="karyawan.php"><i class="fa-solid fa-users me-2"></i> Data Karyawan</a>
        <a class="nav-link" href="payroll.php"><i class="fa-solid fa-money-bill-wave me-2"></i> Transaksi Penggajian</a>
        <a class="nav-link active" href="laporan.php"><i class="fa-solid fa-file-invoice me-2"></i> Laporan Gaji</a>
        <a class="nav-link" href="grafik_gaji.php"><i class="fa-solid fa-chart-pie me-2"></i> Grafik Anggaran Gaji</a>
    </div>
    <a href="logout.php" class="btn btn-danger btn-logout-sidebar fw-semibold">
        <i class="fa-solid fa-sign-out-alt me-1"></i> Logout
    </a>
</div>

<div class="main-content">
    
    <div class="card shadow-sm border-0 mb-3 rounded-3">
        <div class="card-body py-3 px-4">
            <h4 class="fw-bold mb-0" style="font-size: 18px;">Laporan Gaji</h4>
            <small class="text-muted">Pusat rekapitulasi, export excel, dan cetak slip UAS</small>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 rounded-3">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="cetak_tertinggi.php" target="_blank" class="btn btn-dark btn-sm fw-semibold py-1">
                    <i class="fa-solid fa-arrow-trend-up me-1 text-warning"></i> Cetak Gaji Tertinggi
                </a>
                <a href="cetak_tahunan.php" target="_blank" class="btn btn-secondary btn-sm fw-semibold py-1">
                    <i class="fa-solid fa-calendar-days me-1"></i> Cetak Rekap Tahunan
                </a>
                <?php if(!empty($filter_periode)): ?>
                    <a href="export_excel.php?periode=<?php echo $filter_periode; ?>" class="btn btn-success btn-sm fw-semibold py-1">
                        <i class="fa-solid fa-file-excel me-1"></i> Export Excel (Bulan Ini)
                    </a>
                <?php else: ?>
                    <a href="export_excel.php" class="btn btn-success btn-sm fw-semibold py-1">
                        <i class="fa-solid fa-file-excel me-1"></i> Export Semua ke Excel
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark" style="font-size: 13px;"><i class="fa-solid fa-table-list me-2 text-primary"></i>Pratinjau Data</h6>
            
            <form action="" method="GET" class="d-flex gap-2">
                <select name="periode" class="form-select form-select-sm" style="width: 180px; font-size: 12px;" onchange="this.form.submit()">
                    <option value="">-- Semua Bulan --</option>
                    <?php while($p = $periode_query->fetch_assoc()): ?>
                        <option value="<?php echo $p['periode']; ?>" <?php if($filter_periode == $p['periode']) echo 'selected'; ?>>
                            <?php echo $p['periode']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if(!empty($filter_periode)): ?>
                    <a href="cetak_bulanan.php?periode=<?php echo $filter_periode; ?>" target="_blank" class="btn btn-primary btn-sm fw-semibold px-2 text-nowrap" style="font-size: 12px;">
                        <i class="fa-solid fa-print me-1"></i> Cetak Rekap
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle mb-0" style="font-size: 12.5px;">
                    <thead class="table-dark">
                        <tr>
                            <th>Bulan</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Pendidikan</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Gaji Bersih</th>
                            <th class="text-center">Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($laporan_query->num_rows > 0): ?>
                            <?php while($row = $laporan_query->fetch_assoc()): ?>
                            <tr>
                                <td><span class="badge bg-secondary" style="font-size: 10px;"><?php echo $row['periode']; ?></span></td>
                                <td class="text-muted fw-bold"><?php echo $row['nik']; ?></td>
                                <td><?php echo $row['nama_karyawan']; ?></td>
                                <td><?php echo $row['pendidikan']; ?></td>
                                <td>Rp <?php echo number_format($row['gaji_pokok'], 0, ',', '.'); ?></td>
                                <td>Rp <?php echo number_format($row['tunjangan_keluarga'], 0, ',', '.'); ?></td>
                                <td class="text-success fw-bold">Rp <?php echo number_format($row['gaji_grid'] ?? $row['gaji_bersih'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <a href="cetak_slip.php?id=<?php echo $row['id_penggajian']; ?>" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2 fw-semibold" style="font-size: 11px;">
                                        <i class="fa-solid fa-file-invoice-dollar me-1"></i> Slip
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada catatan transaksi penggajian.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($total_halaman > 1): ?>
            <nav class="mt-3">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <li class="page-item <?php if($halaman <= 1) { echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($halaman > 1){ echo "?periode=".$filter_periode."&halaman=".($halaman - 1); } else { echo "#"; } ?>">Previous</a>
                    </li>
                    
                    <?php for($x=1; $x<=$total_halaman; $x++): ?>
                        <li class="page-item <?php if($halaman == $x) { echo 'active'; } ?>">
                            <a class="page-link" href="?periode=<?php echo $filter_periode; ?>&halaman=<?php echo $x; ?>"><?php echo $x; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if($halaman >= $total_halaman) { echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($halaman < $total_halaman) { echo "?periode=".$filter_periode."&halaman=".($halaman + 1); } else { echo "#"; } ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
            </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>