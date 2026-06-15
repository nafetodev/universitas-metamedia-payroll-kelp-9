<?php
// cetak_bulanan.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Memastikan parameter periode dikirim melalui URL
if (!isset($_GET['periode'])) {
    echo "Periode bulan laporan tidak ditentukan!";
    exit();
}

$periode = $conn->real_escape_string($_GET['periode']);

// Mengambil seluruh data transaksi gaji berdasarkan periode bulan yang dipilih
$query = $conn->query("SELECT * FROM v_laporan_gaji WHERE periode = '$periode' ORDER BY nama_karyawan ASC");

// Menghitung ringkasan total uang untuk bagian bawah tabel
$total_gp = 0;
$total_tk = 0;
$total_gb = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan Periode - <?php echo $periode; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            background-color: #fff;
            padding: 30px;
            font-size: 13px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header-laporan { margin-bottom: 25px; border-bottom: 3px double #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #000; }
        th { padding: 8px; background-color: #f2f2f2; font-weight: bold; }
        td { padding: 8px; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .ttd-container { width: 100%; margin-top: 40px; }
        .ttd-box { width: 300px; float: right; text-align: center; }
    </style>
</head>
<body onload="window.print();">

<div class="header-laporan text-center">
    <h2 style="margin: 0 0 5px 0;">PT MANU</h2>
    <p style="margin: 0 0 5px 0;">Jl. Industri Manufaktur Utama No. 9</p>
    <h3 style="margin: 5px 0 0 0; text-transform: uppercase;">REKAPITULASI LAPORAN GAJI BULANAN</h3>
    <strong>Periode: <?php echo $periode; ?></strong>
</div>

<table>
    <thead>
        <tr>
            <th width="40">No</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Pendidikan</th>
            <th>Jenis Kelamin</th>
            <th>Gaji Pokok (GP)</th>
            <th>Tunjangan Keluarga (TK)</th>
            <th>Gaji Bersih (GB)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($query->num_rows > 0) {
            $no = 1;
            while ($row = $query->fetch_assoc()) {
                $total_gp += $row['gaji_pokok'];
                $total_tk += $row['tunjangan_keluarga'];
                $total_gb += $row['gaji_bersih'];
        ?>
        <tr>
            <td class="text-center"><?php echo $no++; ?></td>
            <td class="text-center"><?php echo $row['nik']; ?></td>
            <td><?php echo $row['nama_karyawan']; ?></td>
            <td class="text-center"><?php echo $row['pendidikan']; ?></td>
            <td class="text-center"><?php echo ($row['jenis_kelamin'] == 'LK') ? 'Laki-Laki' : 'Perempuan'; ?></td>
            <td class="text-right">Rp <?php echo number_format($row['gaji_pokok'], 0, ',', '.'); ?></td>
            <td class="text-right">Rp <?php echo number_format($row['tunjangan_keluarga'], 0, ',', '.'); ?></td>
            <td class="text-right" style="font-weight: bold;">Rp <?php echo number_format($row['gaji_bersih'], 0, ',', '.'); ?></td>
        </tr>
        <?php 
            }
        ?>
        <tr class="total-row">
            <td colspan="5" class="text-center">TOTAL KELUARAN DANA PERUSAHAAN</td>
            <td class="text-right">Rp <?php echo number_format($total_gp, 0, ',', '.'); ?></td>
            <td class="text-right">Rp <?php echo number_format($total_tk, 0, ',', '.'); ?></td>
            <td class="text-right">Rp <?php echo number_format($total_gb, 0, ',', '.'); ?></td>
        </tr>
        <?php
        } else {
            echo '<tr><td colspan="8" class="text-center" style="padding: 20px;">Belum ada catatan transaksi transaksi gaji pada periode bulan ini.</td></tr>';
        }
        ?>
    </tbody>
</table>

<div class="ttd-container">
    <div class="ttd-box">
        Medan, <?php echo date('d-m-Y'); ?><br>
        Mengetahui,<br>
        <strong>Direktur Utama PT MANU</strong>
        <br><br><br><br><br>
        ( _______________________ )
    </div>
</div>

</body>
</html>