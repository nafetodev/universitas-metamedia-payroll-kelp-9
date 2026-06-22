<?php
// karyawan_edit.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pesan_error = '';

// Ambil parameter NIK dari URL
if (!isset($_GET['nik'])) {
    header("Location: karyawan.php");
    exit();
}

$nik_lama = $conn->real_escape_string($_GET['nik']);

// Query untuk mengambil data karyawan lama berdasarkan NIK
$query_ambil = "SELECT * FROM karyawan WHERE nik = '$nik_lama'";
$result_ambil = $conn->query($query_ambil);

if ($result_ambil->num_rows == 0) {
    header("Location: karyawan.php");
    exit();
}

$karyawan = $result_ambil->fetch_assoc();

// Memproses data ketika form di-submit (Update Data)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama       = $conn->real_escape_string(trim($_POST['nama']));
    $pendidikan = $conn->real_escape_string($_POST['pendidikan']);
    $jekel      = $conn->real_escape_string($_POST['jekel']);

    if (empty($nama) || empty($pendidikan) || empty($jekel)) {
        $pesan_error = "Semua kolom form wajib diisi!";
    } else {
        // Jalankan query update data ke tabel karyawan
        $query_update = "UPDATE karyawan SET nama = '$nama', pendidikan = '$pendidikan', jekel = '$jekel' WHERE nik = '$nik_lama'";
        
        if ($conn->query($query_update)) {
            $_SESSION['notifikasi'] = "Data karyawan berhasil diperbarui!";
            // Jika sukses, kembali ke tabel utama karyawan
            header("Location: karyawan.php");
            exit();
        } else {
            $pesan_error = "Gagal memperbarui data: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan - PT MANU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; font-size: 13px; }
        
        
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
            <h4 class="fw-bold mb-0" style="font-size: 18px;">Edit Data Karyawan</h4>
            <small class="text-muted">Perbarui biodata staf PT MANU</small>
        </div>
    </div>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 px-3 mb-3 fw-semibold d-flex align-items-center" role="alert" style="font-size: 13px;">
            <i class="fa-solid fa-triangle-exclamation me-2 fa-lg"></i> 
            <div><?php echo $pesan_error; ?></div>
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="" method="POST">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Nomor Induk Karyawan (NIK)</label>
                    <input type="text" class="form-control form-control-sm bg-light" value="<?php echo $karyawan['nik']; ?>" readonly>
                    <small class="text-danger" style="font-size: 11px;">*NIK sebagai Primary Key tidak dapat diubah</small>
                </div>
                
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold text-muted">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control form-control-sm" value="<?php echo htmlspecialchars($karyawan['nama']); ?>" required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="pendidikan" class="form-label fw-semibold text-muted">Tingkat Pendidikan Terakhir</label>
                    <select name="pendidikan" id="pendidikan" class="form-select form-select-sm" required>
                        <option value="SLTP" <?php if($karyawan['pendidikan'] == 'SLTP') echo 'selected'; ?>>SLTP (Gaji Pokok: Rp 2.500.000)</option>
                        <option value="SLTA" <?php if($karyawan['pendidikan'] == 'SLTA') echo 'selected'; ?>>SLTA (Gaji Pokok: Rp 2.700.000)</option>
                        <option value="S1" <?php if($karyawan['pendidikan'] == 'S1') echo 'selected'; ?>>S1 (Gaji Pokok: Rp 4.000.000)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted d-block">Jenis Kelamin</label>
                    <div class="form-check form-check-inline mt-1">
                        <input class="form-check-input" type="radio" name="jekel" id="lk" value="LK" <?php if($karyawan['jekel'] == 'LK') echo 'checked'; ?> required>
                        <label class="form-check-label" for="lk">Laki-Laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jekel" id="pr" value="PR" <?php if($karyawan['jekel'] == 'PR') echo 'checked'; ?> required>
                        <label class="form-check-label" for="pr">Perempuan</label>
                    </div>
                </div>

                <hr class="my-4 opacity-55">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm fw-semibold px-4 text-dark"><i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Data</button>
                    <a href="karyawan.php" class="btn btn-outline-secondary btn-sm px-3">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>