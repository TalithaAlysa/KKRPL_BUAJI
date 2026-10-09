<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $detail = mysqli_query($koneksi, "SELECT * FROM t_detailpenjualan
        WHERE id_penjualan='$id'");

    while ($row = mysqli_fetch_assoc($detail)) {
        mysqli_query($koneksi, "UPDATE t_produk
            SET stok = stok + {$row['jumlah_produk']}
            WHERE id_produk='{$row['id_produk']}'");
    }

    mysqli_query($koneksi, "DELETE FROM t_penjualan
        WHERE id_penjualan='$id'");

    echo "<script>
            alert('Transaksi dihapus dan stok berhasil dikembalikan!');
            window.location='penjualan.php';
          </script>";
    exit;
}

$cari = $_GET['cari'] ?? '';
$where = '';

if ($cari != '') {
    $where = "WHERE t_pelanggan.nama_pelanggan LIKE '%$cari%'";
}

/* DATA PENJUALAN */
$data = mysqli_query($koneksi, "SELECT
    t_penjualan.*,
    t_pelanggan.nama_pelanggan,
    GROUP_CONCAT(
        CONCAT(
            t_produk.nama_produk,
            ' (',
            t_detailpenjualan.jumlah_produk,
            ')'
        )
        SEPARATOR ', '
    ) AS nama_barang,
    SUM(t_detailpenjualan.jumlah_produk) AS jumlah_barang,
    t_user.nama AS nama_kasir
    FROM t_penjualan
    LEFT JOIN t_pelanggan
    ON t_penjualan.id_pelanggan = t_pelanggan.id_pelanggan
    LEFT JOIN t_detailpenjualan
    ON t_penjualan.id_penjualan = t_detailpenjualan.id_penjualan
    LEFT JOIN t_produk
    ON t_detailpenjualan.id_produk = t_produk.id_produk
    LEFT JOIN t_user
    ON t_penjualan.username_kasir = t_user.username
    $where
    GROUP BY t_penjualan.id_penjualan
    ORDER BY t_penjualan.id_penjualan ASC");

$no = 1;
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Data Penjualan - Admin</title>

    <link rel="stylesheet"
          href="../css/bootstrap.min.css">

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

        .btn-hapus {
            background-color: #C96B6B;
            color: white;
        }

        .btn-hapus:hover {
            background-color: #B85A5A;
            color: white;
        }

        /* TABEL PENJUALAN */

        .table-responsive {
            overflow-x: auto;
        }

        .table-penjualan {
            min-width: 1100px;
        }

        .table-penjualan th {
            white-space: nowrap;
            vertical-align: middle;
        }

        .table-penjualan td {
            vertical-align: middle;
        }

        .table-penjualan .tanggal {
            width: 110px;
            white-space: nowrap;
        }

        .table-penjualan .barang {
            min-width: 280px;
        }

        .table-penjualan .jumlah {
            width: 80px;
            text-align: center;
            white-space: nowrap;
        }

        .table-penjualan .kasir {
            width: 100px;
            white-space: nowrap;
        }

        .table-penjualan .total {
            width: 140px;
            white-space: nowrap;
        }

        .table-penjualan .aksi {
            width: 170px;
            white-space: nowrap;
        }

        .table-penjualan .aksi .btn {
            margin-right: 3px;
        }

    </style>

</head>

<body class="bg-light">


    <!-- NAVBAR -->

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


        <!-- CARD DATA PENJUALAN -->

        <div class="card card-toko border-0 shadow-sm mt-3">

            <div class="card-body p-4">


                <!-- JUDUL + TAMBAH -->

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h4 class="fw-bold mb-0">
                        Data Penjualan
                    </h4>

                    <a href="tambah_penjualan.php"
                       class="btn btn-toko">
                        Tambah Penjualan
                    </a>

                </div>


                <!-- SEARCH -->

                <form method="GET"
                      class="d-flex mb-3">

                    <input type="text"
                           name="cari"
                           class="form-control me-2"
                           placeholder="Cari nama pelanggan..."
                           value="<?= $cari; ?>">

                    <button type="submit"
                            class="btn btn-toko">
                        Cari
                    </button>


                    <?php if ($cari != '') { ?>

                        <a href="penjualan.php"
                           class="btn btn-secondary ms-2">
                            Reset
                        </a>

                    <?php } ?>

                </form>


                <!-- TABEL -->

                <div class="table-responsive">

                    <table class="table table-hover align-middle table-penjualan">

                        <thead class="table-success">

                            <tr>

                                <th>
                                    No
                                </th>

                                <th>
                                    ID Penjualan
                                </th>

                                <th>
                                    Nama Pelanggan
                                </th>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    Nama Barang
                                </th>

                                <th>
                                    Jumlah
                                </th>

                                <th>
                                    Kasir
                                </th>

                                <th>
                                    Total Harga
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if (mysqli_num_rows($data) > 0) { ?>


                                <?php while ($row = mysqli_fetch_assoc($data)) { ?>


                                    <tr>


                                        <!-- NO -->

                                        <td>
                                            <?= $no++; ?>
                                        </td>


                                        <!-- ID PENJUALAN -->

                                        <td>
                                            <?= $row['id_penjualan']; ?>
                                        </td>


                                        <!-- PELANGGAN -->

                                        <td>
                                            <?= $row['nama_pelanggan']; ?>
                                        </td>


                                        <!-- TANGGAL -->

                                        <td class="tanggal">

                                            <?= date(
                                                'd-m-Y',
                                                strtotime($row['tanggal'])
                                            ); ?>

                                        </td>


                                        <!-- NAMA BARANG -->

                                        <td class="barang">

                                            <?= $row['nama_barang'] ?? '-'; ?>

                                        </td>


                                        <!-- JUMLAH -->

                                        <td class="jumlah">

                                            <?= $row['jumlah_barang'] ?? 0; ?>

                                        </td>


                                        <!-- KASIR -->

                                        <td class="kasir">

                                            <?php

                                            if (!empty($row['nama_kasir'])) {

                                                echo $row['nama_kasir'];

                                            } elseif (!empty($row['username_kasir'])) {

                                                echo $row['username_kasir'];

                                            } else {

                                                echo '-';

                                            }

                                            ?>

                                        </td>


                                        <!-- TOTAL -->

                                        <td class="total">

                                            Rp <?= number_format(
                                                $row['total_harga'],
                                                0,
                                                ',',
                                                '.'
                                            ); ?>

                                        </td>


                                        <!-- AKSI -->

                                        <td class="aksi">

                                            <a href="cetak_struk.php?id=<?= $row['id_penjualan']; ?>"
                                               target="_blank"
                                               class="btn btn-toko btn-sm">
                                                Cetak Struk
                                            </a>

                                            <a href="penjualan.php?hapus=<?= $row['id_penjualan']; ?>"
                                               class="btn btn-hapus btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus data penjualan ini?')">
                                                Hapus
                                            </a>

                                        </td>


                                    </tr>


                                <?php } ?>


                            <?php } else { ?>


                                <tr>

                                    <td colspan="9"
                                        class="text-center">

                                        Data penjualan tidak ditemukan

                                    </td>

                                </tr>


                            <?php } ?>


                        </tbody>

                    </table>

                </div>


                <!-- KEMBALI -->

                <a href="dashboard_admin.php"
                   class="btn btn-secondary">

                    Kembali ke Dashboard

                </a>


            </div>

        </div>

    </div>


</body>

</html>