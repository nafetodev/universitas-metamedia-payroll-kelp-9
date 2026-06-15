<?php
// payroll.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data semua karyawan untuk dimasukkan ke dalam Dropdown Pilihan
$karyawan_query = $conn->query("SELECT nik, nama FROM karyawan ORDER BY nama ASC");


// ==========================================
// LOGIKA SAKTI PAGINATION (TAMBAHAN BARU)
// ==========================================
$limit = 5; // Mengatur maksimal 5 baris data transaksi per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $limit) - $limit : 0;	

// 1. Hitung total seluruh data transaksi yang ada di database
$total_data_query = $conn->query("SELECT COUNT(*) as jumlah FROM v_laporan_gaji");
$total_data_row = $total_data_query->fetch_assoc();
$total_data = $total_data_row['jumlah'];
$total_halaman = ceil($total_data / $limit);

// 2. Jalankan query dengan LIMIT dan OFFSET untuk membatasi data yang tampil
$riwayat_query = $conn->query("SELECT * FROM v_laporan_gaji ORDER BY id_penggajian DESC LIMIT $halaman_awal, $limit");
// ==========================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penggajian - PT MANU</title>
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
        .form-label-sm { font-size: 12px; font-weight: 600; margin-bottom: 4px; }
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
        <a class="nav-link active" href="payroll.php"><i class="fa-solid fa-money-bill-wave me-2"></i> Transaksi Penggajian</a>
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
            <h4 class="fw-bold mb-0" style="font-size: 18px;">Transaksi Penggajian</h4>
            <small class="text-muted">Proses hitung komponen upah staf terintegrasi AJAX</small>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-2 px-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-calculator me-2 text-success"></i>Hitung Gaji</h6>
                </div>
                <div class="card-body p-3">
                    
                    <form action="payroll_simpan.php" method="POST">
                        <div class="mb-2">
                            <label class="form-label fw-bold">Periode Tanggal, Bulan & Tahun</label>
                            <input type="date" name="periode" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label-sm text-muted">Pilih Karyawan (NIK)</label>
                            <select name="nik" id="nik" class="form-select form-select-sm" required onchange="hitungGajiOtomatis()">
                                <option value="" disabled selected>-- Pilih Anggota Staf --</option>
                                <?php while($k = $karyawan_query->fetch_assoc()): ?>
                                    <option value="<?php echo $k['nik']; ?>"><?php echo $k['nik'] . " - " . $k['nama']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <hr class="my-2 opacity-55">

                        <div class="mb-2">
                            <label class="form-label-sm text-muted">GAJI POKOK (GP)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control bg-light fw-bold text-secondary" readonly required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label-sm text-muted">TUNJANGAN KELUARGA (TK)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" name="tunjangan_keluarga" id="tunjangan_keluarga" class="form-control bg-light fw-bold text-secondary" readonly required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-sm text-dark">GAJI BERSIH (GB)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-success text-white">Rp</span>
                                <input type="text" name="gaji_bersih" id="gaji_bersih" class="form-control bg-success-subtle fw-bold text-dark" readonly required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-sm fw-semibold py-1.5"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Transaksi</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-2 px-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-history me-2 text-secondary"></i>Riwayat Terdaftar</h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover align-middle" style="font-size: 12px;">
                            <thead class="table-dark">
                                <tr>
                                    <th>Periode</th>
                                    <th>Nama</th>
                                    <th>Gaji Pokok</th>
                                    <th>Tunjangan</th>
                                    <th>Gaji Bersih</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($riwayat_query->num_rows > 0): ?>
                                    <?php while($r = $riwayat_query->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo $r['periode']; ?></strong></td>
                                        <td><?php echo $r['nama_karyawan']; ?></td>
                                        <td>Rp <?php echo number_format($r['gaji_pokok'], 0, ',', '.'); ?></td>
                                        <td>Rp <?php echo number_format($r['tunjangan_keluarga'], 0, ',', '.'); ?></td>
                                        <td class="text-success fw-bold">Rp <?php echo number_format($r['gaji_bersih'], 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <a href="payroll_hapus.php?id=<?php echo $r['id_penggajian']; ?>" class="btn btn-outline-danger btn-sm py-0 px-1" onclick="return confirm('Hapus riwayat transaksi gaji ini?');" style="font-size: 10px;"><i class="fa-solid fa-trash-can"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted py-2">Belum ada transaksi.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($total_halaman > 1): ?>
                    <nav class="mt-3">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <li class="page-item <?php if($halaman <= 1) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php if($halaman > 1){ echo "?halaman=".($halaman - 1); } else { echo "#"; } ?>">Previous</a>
                            </li>
                            
                            <?php for($x=1; $x<=$total_halaman; $x++): ?>
                                <li class="page-item <?php if($halaman == $x) { echo 'active'; } ?>">
                                    <a class="page-link" href="?halaman=<?php echo $x; ?>"><?php echo $x; ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php if($halaman >= $total_halaman) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php if($halaman < $total_halaman) { echo "?halaman=".($halaman + 1); } else { echo "#"; } ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
function hitungGajiOtomatis() {
    var nikKaryawan = document.getElementById("nik").value;
    if (nikKaryawan === "") return;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "payroll_hitung_ajax.php?nik=" + nikKaryawan, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var dataGaji = JSON.parse(xhr.responseText);
            document.getElementById("gaji_pokok").value        = dataGaji.gaji_pokok;
            document.getElementById("tunjangan_keluarga").value = dataGaji.tunjangan_keluarga;
            document.getElementById("gaji_bersih").value        = dataGaji.gaji_bersih;
        }
    };
    xhr.send();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>