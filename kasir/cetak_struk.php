<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];

$data = mysqli_query($koneksi, "SELECT t_penjualan.*,
    t_pelanggan.nama_pelanggan,
    t_user.nama AS nama_kasir
    FROM t_penjualan
    LEFT JOIN t_pelanggan
    ON t_penjualan.id_pelanggan = t_pelanggan.id_pelanggan
    LEFT JOIN t_user
    ON t_penjualan.username_kasir = t_user.username
    WHERE t_penjualan.id_penjualan='$id'");

$penjualan = mysqli_fetch_assoc($data);

$detail = mysqli_query($koneksi, "SELECT t_detailpenjualan.*,
    t_produk.nama_produk,
    t_produk.harga
    FROM t_detailpenjualan
    JOIN t_produk
    ON t_detailpenjualan.id_produk = t_produk.id_produk
    WHERE t_detailpenjualan.id_penjualan='$id'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Struk</title>

    <style>
        body {
            font-family: Arial;
            width: 430px;
            margin: 30px auto;
        }

        .judul {
            text-align: center;
        }

        .logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        h3 {
            text-align: center;
            margin: 0 0 8px 0;
        }

        h5 {
            text-align: center;
            margin: 3px 0;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px 5px;
        }

        th {
            text-align: left;
        }

        .kanan {
            text-align: right;
            white-space: nowrap;
        }

        .tombol {
            margin-top: 20px;
        }

        .pembayaran {
            margin-top: 15px;
        }

        .pembayaran input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .kembalian {
            margin-top: 10px;
            font-weight: bold;
        }

        @media print {
            .tombol {
                display: none;
            }

            .input-bayar {
                border: none;
                padding: 0;
                font-weight: bold;
            }
        }
    </style>
</head>

<body>

    <div class="judul">

        <img src="../gambar/toko_bunga.png"
            alt="Logo Elora Garden"
            class="logo">

        <h3>
            ELORA GARDEN
        </h3>

        <h5>
            Toko Bibit Bunga
        </h5>

        <h5>
            Jl. Raya Medini
        </h5>

        <h5>
            Kendal
        </h5>

    </div>

    <p>
        ID Penjualan : <?= $penjualan['id_penjualan']; ?><br>
        Pelanggan : <?= $penjualan['nama_pelanggan']; ?><br>
        Kasir : <?= $penjualan['nama_kasir'] ?? $penjualan['username_kasir']; ?><br>
        Tanggal : <?= date('d-m-Y', strtotime($penjualan['tanggal'])); ?>
    </p>

    <hr>

    <table>

        <tr>
            <th>Produk</th>
            <th>Jumlah</th>
            <th class="kanan">Harga Satuan</th>
            <th class="kanan">Subtotal</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($detail)) { ?>

        <tr>

            <td>
                <?= $row['nama_produk']; ?>
            </td>

            <td>
                <?= $row['jumlah_produk']; ?>
            </td>

            <td class="kanan">
                Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
            </td>

            <td class="kanan">
                Rp <?= number_format($row['subtotal'], 0, ',', '.'); ?>
            </td>

        </tr>

        <?php } ?>

        <tr>

            <td colspan="3">
                <b>Total</b>
            </td>

            <td class="kanan">
                <b>
                    Rp <?= number_format($penjualan['total_harga'], 0, ',', '.'); ?>
                </b>
            </td>

        </tr>

    </table>

    <hr>

    <div class="pembayaran">

        <label>
            <b>Uang Bayar</b>
        </label>

        <input type="number"
               id="uang_bayar"
               class="input-bayar"
               min="<?= $penjualan['total_harga']; ?>"
               placeholder="Masukkan uang bayar"
               oninput="hitungKembalian()">

        <div class="kembalian">

            Uang Kembalian :
            <span id="uang_kembalian">
                Rp 0
            </span>

        </div>

    </div>

    <p style="text-align: center;">
        Terima kasih telah berbelanja
    </p>

    <div class="tombol">

        <button onclick="window.print()">
            Cetak Struk
        </button>

        <a href="penjualan.php">
            <button>
                Kembali ke Penjualan
            </button>
        </a>

    </div>

    <script>

        function hitungKembalian() {

            let total = <?= $penjualan['total_harga']; ?>;

            let bayar = document.getElementById(
                'uang_bayar'
            ).value;

            bayar = Number(bayar);

            let kembalian = bayar - total;


            if (bayar == 0 || bayar == '') {

                document.getElementById(
                    'uang_kembalian'
                ).innerHTML = 'Rp 0';

                return;
            }


            if (kembalian < 0) {

                document.getElementById(
                    'uang_kembalian'
                ).innerHTML =
                    'Uang kurang Rp ' +
                    Math.abs(kembalian).toLocaleString('id-ID');

                return;
            }


            document.getElementById(
                'uang_kembalian'
            ).innerHTML =
                'Rp ' +
                kembalian.toLocaleString('id-ID');

        }

    </script>

</body>

</html>