<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Payroll PT MANU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .card-login {
            margin-top: 10%;
            border: none;
            border-radius: 10px;
        }
        .card-header-custom {
            background-color: #1e293b;
            color: #ffffff;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card card-login shadow-sm">
                <div class="card-header card-header-custom text-center py-3">
                    <h4 class="mb-0 fw-bold">PT MANU</h4>
                    <small>Sistem Informasi Gaji Karyawan</small>
                </div>
                <div class="card-body p-4">
                    
                    <?php 
                    include 'koneksi.php';
                    // Menampilkan pesan error jika login gagal
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                '. $_SESSION['error'] .'
                              </div>';
                        unset($_SESSION['error']);
                    }
                    ?>

                    <form action="proses_login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-semibold">Masuk ke Sistem</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>