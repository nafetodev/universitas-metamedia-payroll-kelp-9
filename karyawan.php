<?php
// karyawan.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

//Query untuk mengambil data karyawan beserta gaji pokoknya dengan JOIN ke tabel gaji_pokok berdasarkan pendidikan
$query_karyawan = "SELECT karyawan.*, gaji_pokok.gaji_pokok 
                   FROM karyawan 
                   JOIN gaji_pokok ON karyawan.pendidikan = gaji_pokok.pendidikan
                   ORDER BY karyawan.nama ASC";
$result_karyawan = $conn->query($query_karyawan);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - PT MANU</title>
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
        <div class="card-body py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0" style="font-size: 18px;">Data Karyawan</h4>
                <small class="text-muted">Kelola database master staf karyawan</small>
            </div>
            <a href="karyawan_tambah.php" class="btn btn-primary btn-sm fw-semibold px-3"><i class="fa-solid fa-user-plus me-1"></i> Tambah</a>
        </div>
    </div>

    <?php if (isset($_SESSION['notifikasi'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm py-2 px-3 mb-3 fw-semibold d-flex align-items-center" role="alert" style="font-size: 13px;">
            <i class="fa-solid fa-circle-check me-2 fa-lg"></i> 
            <div><?php echo $_SESSION['notifikasi']; ?></div>
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
        // Langsung hapus session notifikasi agar tidak muncul lagi saat halaman di-refresh manual
        unset($_SESSION['notifikasi']); 
        ?>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            
            <div class="row g-2 mb-3 justify-content-end">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="text" id="liveSearch" class="form-control" placeholder="Ketik Nama / NIK langsung..." autocomplete="off" autofocus>
                        <span class="input-group-text bg-secondary text-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle" style="font-size: 13px;">
                    <thead class="table-dark">
                        <tr>
                            <th width="50" class="py-2 text-center">No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Pendidikan</th>
                            <th>Jenis Kelamin</th>
                            <th>Gaji Pokok</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody id="tabelKaryawan">

                
                        <?php 
                        if ($result_karyawan->num_rows > 0) {
                            $no = 1;
                            while ($row = $result_karyawan->fetch_assoc()) {
                        ?>
                        <tr class="baris-data">
                            <td class="text-center nomor-urut"><?php echo $no++; ?></td>
                            <td class="fw-bold text-secondary kolom-nik"><?php echo $row['nik']; ?></td>
                            <td class="kolom-nama"><?php echo $row['nama']; ?></td>
                            <td><span class="badge bg-info text-dark fw-bold" style="font-size: 11px;"><?php echo $row['pendidikan']; ?></span></td>
                            <td><?php echo ($row['jekel'] == 'LK') ? 'Laki-Laki' : 'Perempuan'; ?></td>
                            <td>Rp <?php echo number_format($row['gaji_pokok'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <a href="karyawan_edit.php?nik=<?php echo $row['nik']; ?>" class="btn btn-warning btn-sm py-0 px-2 text-dark"><i class="fa-solid fa-pen-to-square" style="font-size: 11px;"></i></a>
                                <a href="karyawan_hapus.php?nik=<?php echo $row['nik']; ?>" class="btn btn-danger btn-sm py-0 px-2" onclick="return confirm('Hapus data karyawan ini?');"><i class="fa-solid fa-trash" style="font-size: 11px;"></i></a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr id="dataKosong"><td colspan="7" class="text-center text-muted py-3">Data tidak ditemukan.</td></tr>';
                        }
                        ?>
                        <tr id="notifKosong" style="display: none;">
                            <td colspan="7" class="text-center text-muted py-3">Karyawan tidak ditemukan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('liveSearch').addEventListener('keyup', function() {
    let kataKunci = this.value.toLowerCase();
    let barisTabel = document.getElementsByClassName('baris-data');
    let adaData = false;
    let nomorBaru = 1;

    for (let i = 0; i < barisTabel.length; i++) {
        let textNIK  = barisTabel[i].getElementsByClassName('kolom-nik')[0].textContent.toLowerCase();
        let textNama = barisTabel[i].getElementsByClassName('kolom-nama')[0].textContent.toLowerCase();

        // Jika cocok dengan NIK atau Nama
        if (textNIK.includes(kataKunci) || textNama.includes(kataKunci)) {
            barisTabel[i].style.display = "";
            // Perbarui nomor urut di tabel agar tetap berurutan (1, 2, 3...)
            barisTabel[i].getElementsByClassName('nomor-urut')[0].textContent = nomorBaru++;
            adaData = true;
        } else {
            barisTabel[i].style.display = "none";
        }
    }

    // Tampilkan notifikasi jika semua data hilang terfilter
    let notif = document.getElementById('notifKosong');
    if (!adaData) {
        notif.style.display = "";
    } else {
        notif.style.display = "none";
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>