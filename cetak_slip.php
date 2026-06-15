<?php
// cetak_slip.php
// Project: Sistem Informasi Gaji Karyawan PT MANU

include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Memastikan parameter ID transaksi penggajian dikirim di URL
if (!isset($_GET['id'])) {
    echo "ID Transaksi tidak ditemukan!";
    exit();
}

$id_penggajian = (int)$_GET['id'];

// Ambil data detail slip dari database View
$query = $conn->query("SELECT * FROM v_laporan_gaji WHERE id_penggajian = $id_penggajian");
if ($query->num_rows == 0) {
    echo "Data slip gaji tidak valid!";
    exit();
}

$data = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - <?php echo $data['nama_karyawan']; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            background-color: #fff;
            padding: 20px;
            font-size: 14px;
        }
        .slip-box {
            border: 2px dashed #000;
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        hr { border: none; border-top: 1px dashed #000; margin: 15px 0; }
        .table-data { width: 100%; border-collapse: collapse; }
        .table-data td { padding: 5px 0; vertical-align: top; }
        .footer-note { margin-top: 30px; font-size: 11px; }
        
        /* Menyembunyikan elemen tidak penting saat dicetak ke kertas/PDF */
        @media print {
            body { padding: 0; }
            .slip-box { border: none; }
        }
    </style>
</head>
<body onload="window.print();">

<div class="slip-box">
    <div class="text-center">
        <h3 style="margin: 0 0 5px 0;">PT MANU</h3>
        <small>Jl. Industri Manufaktur Utama No. 9</small><br>
        <strong style="font-size: 16px;">SLIP GAJI KARYAWAN</strong>
    </div>

    <hr>

    <table class="table-data">
        <tr>
            <td width="140">Periode Gaji</td>
            <td width="20">:</td>
            <td><strong><?php echo $data['periode']; ?></strong></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td><?php echo $data['nik']; ?></td>
        </tr>
        <tr>
            <td>Nama Staf</td>
            <td>:</td>
            <td><?php echo $data['nama_karyawan']; ?></td>
        </tr>
        <tr>
            <td>Pendidikan / Sex</td>
            <td>:</td>
            <td><?php echo $data['pendidikan'] . " / " . ($data['jenis_kelamin'] == 'LK' ? 'Laki-Laki' : 'Perempuan'); ?></td>
        </tr>
    </table>

    <hr>

    <table class="table-data">
        <tr>
            <td><strong>KOMPONEN PENDAPATAN</strong></td>
            <td></td>
        </tr>
        <tr>
            <td>1. Gaji Pokok Jabatan</td>
            <td class="text-right">Rp <?php echo number_format($data['gaji_pokok'], 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>2. Tunjangan Keluarga</td>
            <td class="text-right">Rp <?php echo number_format($data['tunjangan_keluarga'], 0, ',', '.'); ?></td>
        </tr>
        <tr style="height: 15px;"><td></td><td></td></tr>
        <tr style="border-top: 1px solid #000;">
            <td><strong>TOTAL GAJI BERSIH (TAKE HOME PAY)</strong></td>
            <td class="text-right"><strong>Rp <?php echo number_format($data['gaji_bersih'], 0, ',', '.'); ?></strong></td>
        </tr>
    </table>

    <hr>

    <table class="table-data" style="margin-top: 20px;">
        <tr>
            <td class="text-center" width="50%">
                Penerima,<br><br><br><br>
                ( <?php echo $data['nama_karyawan']; ?> )
            </td>
            <td class="text-center">
                Medan, <?php echo date('d-m-Y'); ?><br>
                Hrd PT MANU,<br><br><br><br>
                ( Staff Keuangan )
            </td>
        </tr>
    </table>

    <div class="text-center footer-note text-muted">
        <hr>
        *Dokumen ini sah dikeluarkan oleh SI Payroll kelompok 9 PT MANU*
    </div>
</div>

</body>
</html>