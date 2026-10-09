
<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$sql = "SELECT t_penjualan.*, 
               t_pelanggan.nama_pelanggan,
               GROUP_CONCAT(DISTINCT t_produk.nama_produk SEPARATOR ', ') AS nama_barang
        FROM t_penjualan
        LEFT JOIN t_pelanggan
        ON t_penjualan.id_pelanggan = t_pelanggan.id_pelanggan
        LEFT JOIN t_detailpenjualan
        ON t_penjualan.id_penjualan = t_detailpenjualan.id_penjualan
        LEFT JOIN t_produk
        ON t_detailpenjualan.id_produk = t_produk.id_produk";

if ($tanggal_awal != '' && $tanggal_akhir != '') {
    $sql .= " WHERE DATE(t_penjualan.tanggal)
              BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

$sql .= " GROUP BY t_penjualan.id_penjualan
          ORDER BY t_penjualan.tanggal ASC";

$data = mysqli_query($koneksi, $sql);

$total = 0;
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Penjualan - Admin</title>

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

        .card-toko {
            background-color: #F1F3F0;
        }

        @media print {
            .navbar,
            .bagian-filter,
            .btn-cetak,
            .btn-dashboard {
                display: none !important;
            }

            body {
                background-color: white !important;
            }

            .card {
                box-shadow: none !important;
                background-color: white !important;
            }
        }
    </style>

</head>

<body class="bg-light">

    <nav class="navbar bg-toko">

        <div class="container">

            <span class="navbar-brand text-white fw-bold">
                Toko Bibit Bunga - Admin
            </span>

            <a href="../logout.php"
               class="btn btn-light text-toko">
                Logout
            </a>

        </div>

    </nav>

    <div class="container py-4">

        <!-- Filter -->
        <div class="card card-toko bagian-filter border-0 shadow-sm mt-3 mb-4">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Filter Laporan Penjualan
                </h4>

                <form method="GET">

                    <div class="row g-3 align-items-end">

                        <div class="col-md-4">

                            <label class="form-label">
                                Tanggal Awal
                            </label>

                            <input type="date"
                                   name="tanggal_awal"
                                   class="form-control"
                                   value="<?= $tanggal_awal; ?>">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Tanggal Akhir
                            </label>

                            <input type="date"
                                   name="tanggal_akhir"
                                   class="form-control"
                                   value="<?= $tanggal_akhir; ?>">

                        </div>

                        <div class="col-md-2">

                            <button type="submit"
                                    class="btn btn-toko w-100">
                                Tampilkan
                            </button>

                        </div>

                        <div class="col-md-2">

                            <a href="laporan.php"
                               class="btn btn-secondary w-100">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <!-- Laporan -->
        <div class="card card-toko border-0 shadow-sm">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Laporan Penjualan
                </h4>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-success">

                            <tr>
                                <th>No</th>
                                <th>ID Penjualan</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Barang</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (mysqli_num_rows($data) > 0) { ?>

                                <?php while ($row = mysqli_fetch_assoc($data)) { ?>

                                    <?php $total += $row['total_harga']; ?>

                                    <tr>

                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <td>
                                            <?= $row['id_penjualan']; ?>
                                        </td>

                                        <td>
                                            <?= $row['nama_pelanggan']; ?>
                                        </td>

                                        <td>
                                            <?= $row['nama_barang'] ?? '-'; ?>
                                        </td>

                                        <td>
                                            <?= date('d-m-Y', strtotime($row['tanggal'])); ?>
                                        </td>

                                        <td>
                                            Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>

                                    <td colspan="6"
                                        class="text-center">
                                        Data penjualan tidak ditemukan
                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

                <div class="text-end fw-bold fs-5 mt-3">

                    Total Penjualan:

                    <span class="text-toko">
                        Rp <?= number_format($total, 0, ',', '.'); ?>
                    </span>

                </div>

                <div class="mt-3">

                    <button onclick="window.print()"
                            class="btn btn-toko btn-cetak">
                        Cetak Laporan
                    </button>

                    <a href="dashboard_admin.php"
                       class="btn btn-secondary ms-2 btn-dashboard">
                        Kembali ke Dashboard
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>

</html>