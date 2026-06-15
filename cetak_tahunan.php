<?php
// cetak_tahunan.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Mengambil seluruh data riwayat penggajian sepanjang tahun dari database View
$query = $conn->query("SELECT * FROM v_laporan_gaji ORDER BY id_penggajian ASC");

// Variabel penampung total akumulasi di bagian bawah
$grand_total_gp = 0;
$grand_total_tk = 0;
$grand_total_gb = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akumulasi Penggajian Tahunan - PT MANU</title>
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
        .total-row { font-weight: bold; background-color: #eaeaea; }
        .ttd-container { width: 100%; margin-top: 50px; }
        .ttd-box { width: 300px; float: right; text-align: center; }
    </style>
</head>
<body onload="window.print();">

<div class="header-laporan text-center">
    <h2 style="margin: 0 0 5px 0;">PT MANU</h2>
    <p style="margin: 0 0 5px 0;">Jl. Industri Manufaktur Utama No. 9</p>
    <h3 style="margin: 5px 0 0 0;">REKAPITULASI LAPORAN PENGGAJIAN TAHUNAN</h3>
    <strong>Tahun Buku Berjalan: 2026</strong>
</div>

<table>
    <thead>
        <tr>
            <th width="40">No</th>
            <th width="110">Periode Bulan</th>
            <th>NIK</th>
            <th>Nama Karyawan</th>
            <th>Pendidikan</th>
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
                $grand_total_gp += $row['gaji_pokok'];
                $grand_total_tk += $row['tunjangan_keluarga'];
                $grand_total_gb += $row['gaji_bersih'];
        ?>
        <tr>
            <td class="text-center"><?php echo $no++; ?></td>
            <td class="text-center" style="font-weight: bold;"><?php echo $row['periode']; ?></td>
            <td class="text-center"><?php echo $row['nik']; ?></td>
            <td><?php echo $row['nama_karyawan']; ?></td>
            <td class="text-center"><?php echo $row['pendidikan']; ?></td>
            <td class="text-right">Rp <?php echo number_format($row['gaji_pokok'], 0, ',', '.'); ?></td>
            <td class="text-right">Rp <?php echo number_format($row['tunjangan_keluarga'], 0, ',', '.'); ?></td>
            <td class="text-right" style="font-weight: bold; color: #155724;">Rp <?php echo number_format($row['gaji_bersih'], 0, ',', '.'); ?></td>
        </tr>
        <?php 
            }
        ?>
        <tr class="total-row">
            <td colspan="5" class="text-center">GRAND TOTAL ALOKASI DANA REKAPITULASI TAHUNAN</td>
            <td class="text-right">Rp <?php echo number_format($grand_total_gp, 0, ',', '.'); ?></td>
            <td class="text-right">Rp <?php echo number_format($grand_total_tk, 0, ',', '.'); ?></td>
            <td class="text-right" style="color: #155724;">Rp <?php echo number_format($grand_total_gb, 0, ',', '.'); ?></td>
        </tr>
        <?php
        } else {
            echo '<tr><td colspan="8" class="text-center" style="padding: 20px;">Belum ada riwayat data transaksi transaksi untuk tahun ini.</td></tr>';
        }
        ?>
    </tbody>
</table>

<div class="ttd-container">
    <div class="ttd-box">
        Medan, <?php echo date('d-m-Y'); ?><br>
        Mengetahui / Mengesahkan,<br>
        <strong>Direktur Keuangan PT MANU</strong>
        <br><br><br><br><br>
        ( _______________________ )
    </div>
</div>

</body>
</html>