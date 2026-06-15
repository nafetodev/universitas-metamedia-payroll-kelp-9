A. NAMA PROJECT: metamedia-payroll-kelp-9 <br>
Deskripsi: <br>
Sistem Informasi Penggajian Karyawan (Payroll) PT MANU adalah aplikasi berbasis web (PHP) untuk mengelola data master karyawan, memproses komponen upah secara otomatis, serta menyajikan laporan payroll secara cepat dan akurat. Fitur utama meliputi login, dashboard, karyawan, transaksi penggajian (aplikasi menghitung gaji pokok dan tunjangan secara real-time via AJAX), cetak slip gaji (nota struk), laporan bulanan, tahunan, tertinggi, dan grafik alokasi anggaran gaji. Laporan bisa di print dan di export ke Excel. Sistem membantu meningkatkan efisiensi operasional dan mendukung keputusan manajemen.

Selain itu, aplikasi Payroll PT MANU ini juga menerapkan tata kelola transparansi dalam penggajian karyawan, hal ini terlihat dari Laporan dan Slip Gaji di mana nama petugas ditampilkan pada laporan sesuai dengan user yang bertugas saat itu.

KLASIFIKASI STACK <br>

Front End <br>
├── HTML <br>
├── Bootstrap 5 <br>
├── JavaScript <br>
├── AJAX <br>
├── Chart.js <br>
└── User Interface <br>
<br>
Back End <br>
├── PHP <br>
├── MySQL <br>
├── Session <br>
├── CRUD <br>
├── Login <br>
├── Export Excel <br>
└── Business Logic <br>
<br>
B. PENGEMBANGAN FITUR: <br>
Aplikasi metamedia-payroll-kelp-9 menggunakan code: PHP dan JavaScript, database MySQL dengan menggunakan Local Development Environment yaitu Laragon. Untuk mendukung unjuk kerja Aplikasi Payroll digunakan JavaScript digunakan sebagai bahasa pemrograman sisi klien, AJAX digunakan untuk komunikasi asynchronous antara client dan server saat menghitung gaji otomatis tanpa refresh, sedangkan data diproses langsung ke format Excel menggunakan file export_excel.php.
<br>

1. Login. Masuk ke Sistem.
2. Karyawan. Pengelolaan data master staf karyawan meliputi kegiatan: CRUD + Search + karyawan_tambah.php
3. Dashboard. Menampilkan informasi: Total Karyawan, Total Transaksi Gaji dan Total Alokasi Kas Bersih
4. Transaksi Penggajian menggunakan JavaScript dan AJAX (payroll_hitung_ajax.php)
   <br>
   4.1. CRUD <br>
   4.2. Transaksi penggajian secara real time <br>
   4.3. Searching data karyawan <br>
   4.4. Mencetak/print slip gaji per nomor slip (slip_gaji.png.jpeg)<br>
5. Laporan <br>
   5.1. Laporan Akumulasi Gaji, berdasarkan pencarian periode, Print (laporan.php) <br>
   5.2. Laporan Rekapitulasi Tahunan (cetak_tahunan.php): <br>
   5.2.1. Print <br>
   5.2.2. Export Laporan ke Excel (export_excel.php) <br>
   5.3. Laporan Alokasi Gaji Tertinggi (cetak_tertinggi.php) <br>
   5.4. Laporan Rekapitulasi Bulanan (cetak_bulanan.php) <br>
6. Grafik Anggaran Gaji (grafik_gaji.php)<br>
7. Logout. Keluar dari Sistem <br>

C. TEKNOLOGI: <br>

1.  HTML
2.  Bootstrap 5
3.  PHP
4.  JavaScript:
    AJAX
5.  CSS
6.  MySQL
7.  Server Laragon: apache

D. CARA INSTALL <br>

1. Install Laragon

2. Masuk ke folder www dan buat folder baru: metamedia-payroll-kelp-9
3. Download SourceCode dari [gitHub](https://github.com/nafetodev/metamedia-payroll-kelp-9)
4. Dari DBMS --> PHPMyAdmin
   1. pilih tab SQL, hapus semua script yang tampil
   2. buat Database sesuai nama file SQL Anda dengan cara: copy-paste script SQL database
   3. go / Kirim

5. dari Browser
   1. pastikan server Apache dan MySQL sudah aktif
6. run
7. localhost/metamedia-payroll-kelp-9

secara sederhana:

git clone https://github.com/nafetodev/metamedia-payroll-kelp-9.git

E. SCREENSHOT <br>
![/image](/image/login.png.jpeg)
![/image](/image/dashboard.png.jpeg)
![/image](/image/karyawan.png.jpeg)
![/image](/image/transaksi_penggajian.png.jpeg)
![/image](/image/slip_gaji.png.jpeg)
![/image](/image/laporann_gaji.png.jpeg)
![/image](/image/laporan_gaji_print.png.jpeg)
![/image](/image/laporan_gaji_excel.png.jpeg)
![/image](/image/grafik_gaji.png.jpeg)
![/image](/image/fitur_pencarian_data_karyawan.png.jpeg)