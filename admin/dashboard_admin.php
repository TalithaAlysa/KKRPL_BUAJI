<?php
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: index.php");
    exit;
}

include "../koneksi.php";

$produk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_produk"));
$pelanggan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_pelanggan"));
$penjualan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM t_penjualan"));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Toko Bibit Bunga</title>

    <!-- Bootstrap Offline -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <style>
        .bg-toko {
            background-color: #6B8E6B;
        }

        .text-toko {
            color: #6B8E6B;
        }

        .btn-toko {
            background-color: #6B8E6B;
            color: white;
        }

        .btn-toko:hover {
            background-color: #557355;
            color: white;
        }

        .card {
            background-color: #F1F3F0;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar bg-toko">
        <div class="container">

            <span class="navbar-brand text-white fw-bold">
                Toko Bibit Bunga
            </span>

            <a href="../logout.php" class="btn btn-light"onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </div>
    </nav>


    <!-- Isi -->
    <div class="container py-5">

        <!-- Welcome -->
        <div class="mb-4">

            <h2 class="fw-bold mb-1">
                Dashboard Admin
            </h2>

            <p class="text-secondary mb-0">
                Selamat datang, <?= $_SESSION['username']; ?>
            </p>

        </div>


        <!-- Card -->
        <div class="row g-3 mb-5">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-4">

                        <h6 class="text-secondary">
                            Data Produk
                        </h6>

                        <h2 class="fw-bold text-toko mb-0">
                            <?= $produk; ?>
                        </h2>

                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-4">

                        <h6 class="text-secondary">
                            Data Pelanggan
                        </h6>

                        <h2 class="fw-bold text-toko mb-0">
                            <?= $pelanggan; ?>
                        </h2>

                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-4">

                        <h6 class="text-secondary">
                            Total Penjualan
                        </h6>

                        <h2 class="fw-bold text-toko mb-0">
                            <?= $penjualan; ?>
                        </h2>

                    </div>
                </div>
            </div>

        </div>


        <!-- Menu -->
        <h4 class="fw-bold mb-3">
            Menu Admin
        </h4>

        <div class="row g-3">

            <div class="col-md-6">
                <a href="produk.php"
                   class="btn btn-toko w-100 py-3">
                    Data Produk
                </a>
            </div>

            <div class="col-md-6">
                <a href="pelanggan.php"
                   class="btn btn-toko w-100 py-3">
                    Data Pelanggan
                </a>
            </div>

            <div class="col-md-6">
                <a href="penjualan.php"
                   class="btn btn-toko w-100 py-3">
                    Data Penjualan
                </a>
            </div>

            <div class="col-md-6">
                <a href="laporan.php"
                   class="btn btn-toko w-100 py-3">
                    Laporan Penjualan
                </a>
            </div>

        </div>

    </div>

</body>

</html>