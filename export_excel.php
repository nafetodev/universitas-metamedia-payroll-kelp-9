<?php
// export_excel.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 1. Tangkap filter bulan & tahun yang dikirim dari dropdown laporan.php
$bulan = isset($_GET['bulan']) ? $conn->real_escape_string($_GET['bulan']) : '';
$tahun = isset($_GET['tahun']) ? $conn->real_escape_string($_GET['tahun']) : '2026';

// Array bantu untuk penamaan file download excel
$bulan_indo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// 2. ATUR HEADER UNTUK MEMAKSA BROWSER MENDOWNLOAD FORMAT EXCEL
header("Content-type: application/vnd-ms-excel");
if (!empty($bulan)) {
    header("Content-Disposition: attachment; filename=Rekap_Gaji_PT_MANU_" . $bulan_indo[$bulan] . "_" . $tahun . ".xls");
} else {
    header("Content-Disposition: attachment; filename=Rekap_Total_Gaji_PT_MANU_All.xls");
}
header("Pragma: no-cache");
header("Expires: 0");

// 3. AMBIL DATA DARI VIEW DATABASE (SINKRON DENGAN FORMAT YYYY-MM-DD)
if (!empty($bulan)) {
    $query = $conn->query("SELECT * FROM v_laporan_gaji WHERE MONTH(periode) = '$bulan' AND YEAR(periode) = '$tahun' ORDER BY nama_karyawan ASC");
} else {
    $query = $conn->query("SELECT * FROM v_laporan_gaji WHERE YEAR(periode) = '$tahun' ORDER BY id_penggajian ASC");
}
?>

<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14px; font-weight: bold; text-align: center; height: 30px; vertical-align: middle;">LAPORAN REKAPITULASI PENGGAJIAN KARYAWAN PT MANU</th>
        </tr>
        <?php if(!empty($bulan)): ?>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">Periode Bulan: <?php echo $bulan_indo[$bulan] . " " . $tahun; ?></th>
        </tr>
        <?php endif; ?>
        <tr>
            <th colspan="8"></th>
        </tr>
        <tr style="background-color: #1e293b; color: #ffffff; font-weight: bold; text-align: center;">
            <th width="50">No</th>
            <th width="100">Periode</th>
            <th width="100">NIK</th>
            <th width="200">Nama Lengkap Karyawan</th>
            <th width="100">Pendidikan</th>
            <th width="120">Gaji Pokok (GP)</th>
            <th width="150">Tunjangan Keluarga (TK)</th>
            <th width="120">Gaji Bersih (GB)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $total_gp = 0;
        $total_tk = 0;
        $total_gb = 0;

        if ($query && $query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                // Menghitung grand total data dari kolom database asli kamu
                $total_gp += $row['gaji_pokok'];
                $total_tk += $row['tunjangan_keluarga'] ?? $row['tunjangan']; // antisipasi perbedaan nama kolom tunjangan
                $total_gb += $row['gaji_bersih'];
        ?>
        <tr>
            <td style="text-align: center;"><?php echo $no++; ?></td>
            <td style="text-align: center; mso-number-format:'\@';"><?php echo $row['periode']; ?></td>
            <td style="text-align: center; mso-number-format:'\@';"><?php echo $row['nik']; ?></td>
            <td style="padding-left: 5px;"><?php echo htmlspecialchars($row['nama_karyawan']); ?></td>
            <td style="text-align: center;"><?php echo $row['pendidikan']; ?></td>
            <td style="text-align: right;">Rp <?php echo number_format($row['gaji_pokok'], 0, ',', '.'); ?></td>
            <td style="text-align: right;">Rp <?php echo number_format(($row['tunjangan_keluarga'] ?? $row['tunjangan']), 0, ',', '.'); ?></td>
            <td style="text-align: right; font-weight: bold; color: #155724;">Rp <?php echo number_format($row['gaji_bersih'], 0, ',', '.'); ?></td>
        </tr>
        <?php 
            }
        ?>
        <tr style="font-weight: bold; background-color: #e2e8f0; height: 25px;">
            <td colspan="5" style="text-align: center; vertical-align: middle;">TOTAL KESELURUHAN DANA</td>
            <td style="text-align: right;">Rp <?php echo number_format($total_gp, 0, ',', '.'); ?></td>
            <td style="text-align: right;">Rp <?php echo number_format($total_tk, 0, ',', '.'); ?></td>
            <td style="text-align: right; color: #155724;">Rp <?php echo number_format($total_gb, 0, ',', '.'); ?></td>
        </tr>
        <?php
        } else {
            echo '<tr><td colspan="8" style="text-align: center; padding: 15px; color: gray; font-style: italic;">Data transaksi pada periode ini masih kosong.</td></tr>';
        }
        ?>
    </tbody>
</table>