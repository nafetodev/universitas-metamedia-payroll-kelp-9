<?php
// grafik_gaji.php
// Project: Sistem Informasi Gaji Karyawan PT MANU (Kelompok 9)
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Menangkap filter angka bulan dan tahun dari form (Default jika baru dibuka: Bulan & Tahun saat ini)
$bulan = $_GET['bulan'] ?? date('n'); 
$tahun = $_GET['tahun'] ?? '2026';

// Array bantu untuk menampilkan nama bulan murni di dropdown
$bulan_indo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$nama_bulan = $bulan_indo[$bulan] ?? '';
$keyword_periode = $nama_bulan . "-" . $tahun;

// Query SQL Logika Dosen: Membedah format tanggal YYYY-MM-DD menggunakan fungsi MONTH() dan YEAR()
$sql = "
SELECT 
    nama_karyawan,
    SUM(gaji_bersih) AS total_gaji
FROM v_laporan_gaji
WHERE MONTH(periode) = '$bulan' AND YEAR(periode) = '$tahun'
GROUP BY nama_karyawan
ORDER BY total_gaji DESC
";

$result = $conn->query($sql);

$labels = [];
$data = [];

while($row = $result->fetch_assoc()){
    $labels[] = $row['nama_karyawan'];
    $data[]   = $row['total_gaji'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Anggaran Gaji - Payroll PT MANU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; font-size: 14px; }
        
        /* Rampingkan Ukuran Sidebar (Sama dengan Dashboard) */
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
        .chart-container { width: 100%; max-width: 320px; margin: 0 auto; }
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
        <a class="nav-link" href="laporan.php"><i class="fa-solid fa-file-invoice me-2"></i> Laporan Gaji</a>
        <a class="nav-link active" href="grafik_gaji.php"><i class="fa-solid fa-chart-pie me-2"></i> Grafik Anggaran Gaji</a>
    </div>
    <a href="logout.php" class="btn btn-danger btn-logout-sidebar fw-semibold">
        <i class="fa-solid fa-sign-out-alt me-1"></i> Logout
    </a>
</div>

<div class="main-content">
    
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-body py-3 px-4">
            <h4 class="fw-bold mb-0">Visualisasi Anggaran Payroll</h4>
            <small class="text-muted">Analisis statistik porsi pengeluaran upah bersih karyawan</small>
        </div>
    </div>

    <div class="card shadow-sm border-0 bg-white rounded-3">
        <div class="card-body p-4">
            
            <form method="GET" class="row g-2 mb-4 align-items-center">
                <div class="col-auto">
                    <select name="bulan" class="form-select form-select-sm" style="width: 150px; font-size: 13px;">
                        <?php for($i=1; $i<=12; $i++){ ?>
                            <option value="<?= $i ?>" <?= $bulan==$i?'selected':'' ?>><?= $bulan_indo[$i] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="number" name="tahun" value="<?= $tahun ?>" class="form-control form-control-sm" style="width: 90px; font-size: 13px;">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold" style="font-size: 13px;">Tampilkan</button>
                </div>
            </form>

            <div class="row align-items-center mt-3">
                <div class="col-md-5 text-center mb-4 mb-md-0">
                    <?php if(!empty($labels)): ?>
                        <div class="chart-container">
                            <canvas id="pieChart"></canvas>
                        </div>
                    <?php else: ?>
                        <div class="py-5">
                            <i class="fa-solid fa-chart-area fa-3x text-muted mb-2"></i>
                            <p class="text-muted fw-bold mb-0">Grafik tidak tersedia</p>
                            <small class="text-muted">Tidak ada transaksi di periode <?= $bulan_indo[$bulan]; ?> <?= $tahun; ?></small>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-7">
                    <h6 class="fw-bold mb-2">Rincian Data Periode: <span class="text-primary"><?= $bulan_indo[$bulan]; ?> - <?= $tahun; ?></span></h6>
                    <table class="table table-sm table-bordered align-middle text-center shadow-sm mb-0" style="font-size: 13px;">
                        <thead class="table-dark">
                            <tr>
                                <th width="50">No</th>
                                <th class="text-start">Nama Karyawan</th>
                                <th class="text-end" width="160">Gaji Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($labels)){
                                $no=1;
                                foreach($labels as $k=>$karyawan){
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="text-start fw-semibold"><?= htmlspecialchars($karyawan) ?></td>
                                <td class="text-end text-success fw-bold">Rp <?= number_format($data[$k], 0, ',', '.') ?></td>
                            </tr>
                            <?php 
                                }
                            } else { 
                            ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-folder-open me-1"></i> Belum ada rekaman transaksi payroll pada periode ini.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
<?php if(!empty($labels)): ?>
const ctx = document.getElementById('pieChart').getContext('2d');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            data: <?= json_encode($data) ?>,
            backgroundColor: [
                '#1a43bf', // Biru Tua
                '#14a44d', // Hijau Tua
                '#b81d24', // Merah Tua
                '#f59e0b', // Kuning
                '#8b5cf6'  // Ungu
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 11 } }
            },
            title: {
                display: true,
                text: 'Distribusi Alokasi Pengeluaran Gaji Staf',
                font: { size: 12, weight: 'bold' }
            }
        }
    }
});
<?php endif; ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>