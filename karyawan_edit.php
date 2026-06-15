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
        body { background-color: #f4f6f9; }
        .navbar-custom { background-color: #1e293b; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php"><i class="fa-solid fa-building-columns me-2"></i>PT MANU - Payroll</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="karyawan.php">Data Karyawan</a></li>
                <li class="nav-item"><a class="nav-link" href="payroll.php">Input Penggajian</a></li>
                <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan Gaji</a></li>
            </ul>
            <a href="logout.php" class="btn btn-danger btn-sm fw-semibold">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-user-pen me-2 text-warning"></i>Form Edit Data Karyawan</h5>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($pesan_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo $pesan_error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Nomor Induk Karyawan (NIK)</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $karyawan['nik']; ?>" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="<?php echo htmlspecialchars($karyawan['nama']); ?>" required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="pendidikan" class="form-label fw-semibold">Jenjang Pendidikan</label>
                    <select name="pendidikan" id="pendidikan" class="form-select" required>
                        <option value="SLTP" <?php if($karyawan['pendidikan'] == 'SLTP') echo 'selected'; ?>>SLTP (Gaji Pokok: Rp 2.500.000)</option>
                        <option value="SLTA" <?php if($karyawan['pendidikan'] == 'SLTA') echo 'selected'; ?>>SLTA (Gaji Pokok: Rp 2.700.000)</option>
                        <option value="S1" <?php if($karyawan['pendidikan'] == 'S1') echo 'selected'; ?>>S1 (Gaji Pokok: Rp 4.000.000)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold d-block">Jenis Kelamin</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jekel" id="lk" value="LK" <?php if($karyawan['jekel'] == 'LK') echo 'checked'; ?> required>
                        <label class="form-check-label" for="lk">Laki-Laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jekel" id="pr" value="PR" <?php if($karyawan['jekel'] == 'PR') echo 'checked'; ?> required>
                        <label class="form-check-label" for="pr">Perempuan</label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="karyawan.php" class="btn btn-secondary px-3 fw-semibold"><i class="fa-solid fa-arrow-left me-1"></i> Batal</a>
                    <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark"><i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Data</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>