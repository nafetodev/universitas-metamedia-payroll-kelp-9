<?php
// cetak_tertinggi.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Query mengambil data gaji dari VIEW, diurutkan berdasarkan Gaji Bersih Terbesar (DESC)
$query = $conn->query("SELECT * FROM v_laporan_gaji ORDER BY gaji_bersih DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Gaji Tertinggi Karyawan - PT MANU</title>
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
        .highlight-row { background-color: #fff3cd; font-weight: bold; } /* Sorotan khusus urutan nomor 1 */
        .ttd-container { width: 100%; margin-top: 50px; }
        .ttd-box { width: 300px; float: right; text-align: center; }
    </style>
</head>
<body onload="window.print();">

<div class="header-laporan text-center">
    <h2 style="margin: 0 0 5px 0;">PT MANU</h2>
    <p style="margin: 0 0 5px 0;">Jl. Industri Manufaktur Utama No. 9</p>
    <h3 style="margin: 5px 0 0 0; text-transform: uppercase;">LAPORAN PERINGKAT GAJI TERTINGGI KARYAWAN</h3>
    <small>Dicetak otomatis berdasarkan nominal akumulasi Take Home Pay terbesar</small>
</div>

<table>
    <thead>
        <tr>
            <th width="50">Rank</th>
            <th width="100">Periode</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Pendidikan</th>
            <th>Jenis Kelamin</th>
            <th>Gaji Pokok</th>
            <th>Tunjangan Keluarga</th>
            <th>Gaji Bersih (Maksimal)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($query->num_rows > 0) {
            $rank = 1;
            while ($row = $query->fetch_assoc()) {
                // Berikan style highlight khusus kuning emas untuk peraih peringkat pertama (tertinggi)
                $row_style = ($rank == 1) ? 'class="highlight-row"' : '';
        ?>
        <tr <?php echo $row_style; ?>>
            <td class="text-center">
                <?php 
                if ($rank == 1) {
                    echo "⭐ 1";
                } else {
                    echo $rank;
                }
                ?>
            </td>
            <td class="text-center"><?php echo $row['periode']; ?></td>
            <td class="text-center"><?php echo $row['nik']; ?></td>
            <td><?php echo $row['nama_karyawan']; ?></td>
            <td class="text-center"><?php echo $row['pendidikan']; ?></td>
            <td class="text-center"><?php echo ($row['jenis_kelamin'] == 'LK') ? 'Laki-Laki' : 'Perempuan'; ?></td>
            <td class="text-right">Rp <?php echo number_format($row['gaji_pokok'], 0, ',', '.'); ?></td>
            <td class="text-right">Rp <?php echo number_format($row['tunjangan_keluarga'], 0, ',', '.'); ?></td>
            <td class="text-right" style="color: #b58105;">Rp <?php echo number_format($row['gaji_bersih'], 0, ',', '.'); ?></td>
        </tr>
        <?php 
                $rank++;
            }
        } else {
            echo '<tr><td colspan="9" class="text-center" style="padding: 20px;">Belum ada data transaksi gaji yang tercatat di sistem.</td></tr>';
        }
        ?>
    </tbody>
</table>

<div class="ttd-container">
    <div class="ttd-box">
        Medan, <?php echo date('d-m-Y'); ?><br>
        Mengetahui / Mengesahkan,<br>
        <strong>Kepala HRD PT MANU</strong>
        <br><br><br><br><br>
        ( _______________________ )
    </div>
</div>

</body>
</html>