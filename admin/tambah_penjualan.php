<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $id_pelanggan = $_POST['id_pelanggan'];
    $tanggal = $_POST['tanggal'];
    $username_kasir = $_SESSION['username'];

    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    $total = 0;

    for ($i = 0; $i < count($id_produk); $i++) {

        if ($id_produk[$i] != '' && $jumlah[$i] > 0) {

            $produk = mysqli_query($koneksi, "SELECT * FROM t_produk
                                              WHERE id_produk='{$id_produk[$i]}'");

            $data_produk = mysqli_fetch_assoc($produk);

            $harga = $data_produk['harga'];
            $stok = $data_produk['stok'];

            if ($jumlah[$i] > $stok) {
                echo "<script>
                        alert('Jumlah produk melebihi stok!');
                        window.location='tambah_penjualan.php';
                      </script>";
                exit;
            }

            $subtotal = $harga * $jumlah[$i];
            $total += $subtotal;
        }
    }

    if ($total == 0) {
        echo "<script>
                alert('Pilih produk terlebih dahulu!');
                window.location='tambah_penjualan.php';
              </script>";
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO t_penjualan
        (id_pelanggan, username_kasir, tanggal, total_harga)
        VALUES
        ('$id_pelanggan', '$username_kasir', '$tanggal', '$total')");

    $id_penjualan = mysqli_insert_id($koneksi);

    for ($i = 0; $i < count($id_produk); $i++) {

        if ($id_produk[$i] != '' && $jumlah[$i] > 0) {

            $produk = mysqli_query($koneksi, "SELECT * FROM t_produk
                                              WHERE id_produk='{$id_produk[$i]}'");

            $data_produk = mysqli_fetch_assoc($produk);

            $harga = $data_produk['harga'];
            $subtotal = $harga * $jumlah[$i];

            mysqli_query($koneksi, "INSERT INTO t_detailpenjualan
                (id_penjualan, id_produk, jumlah_produk, subtotal)
                VALUES
                ('$id_penjualan', '{$id_produk[$i]}', '{$jumlah[$i]}', '$subtotal')");

            mysqli_query($koneksi, "UPDATE t_produk
                SET stok = stok - {$jumlah[$i]}
                WHERE id_produk='{$id_produk[$i]}'");
        }
    }

    echo "<script>
            alert('Data penjualan berhasil disimpan!');
            window.location='penjualan.php';
          </script>";
    exit;
}

$pelanggan = mysqli_query($koneksi, "SELECT * FROM t_pelanggan
                                     ORDER BY nama_pelanggan ASC");

$produk = mysqli_query($koneksi, "SELECT * FROM t_produk
                                  WHERE stok > 0
                                  ORDER BY nama_produk ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Penjualan - Admin</title>

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

        <div class="card card-toko border-0 shadow-sm">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Tambah Penjualan
                </h4>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Pelanggan
                        </label>

                        <select name="id_pelanggan"
                                class="form-select"
                                required>

                            <option value="">
                                -- Pilih Pelanggan --
                            </option>

                            <?php while ($row = mysqli_fetch_assoc($pelanggan)) { ?>

                                <option value="<?= $row['id_pelanggan']; ?>">
                                    <?= $row['nama_pelanggan']; ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal
                        </label>

                        <input type="date"
                               name="tanggal"
                               class="form-control"
                               value="<?= date('Y-m-d'); ?>"
                               required>

                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">
                        Produk
                    </h6>

                    <div id="produk-container">

                        <div class="row mb-2 produk-row">

                            <div class="col-md-7">

                                <select name="id_produk[]"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        -- Pilih Produk --
                                    </option>

                                    <?php
                                    mysqli_data_seek($produk, 0);
                                    while ($row = mysqli_fetch_assoc($produk)) {
                                    ?>

                                        <option value="<?= $row['id_produk']; ?>">
                                            <?= $row['nama_produk']; ?>
                                            - Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                            (Stok: <?= $row['stok']; ?>)
                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <input type="number"
                                       name="jumlah[]"
                                       class="form-control"
                                       placeholder="Jumlah"
                                       min="1"
                                       required>

                            </div>

                            <div class="col-md-2">

                                <button type="button"
                                        class="btn btn-danger w-100"
                                        onclick="hapusProduk(this)">
                                    Hapus
                                </button>

                            </div>

                        </div>

                    </div>

                    <button type="button"
                            class="btn btn-secondary mb-3"
                            onclick="tambahProduk()">
                        + Tambah Produk
                    </button>

                    <br>

                    <button type="submit"
                            name="simpan"
                            class="btn btn-toko">
                        Simpan Penjualan
                    </button>

                    <a href="penjualan.php"
                       class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            </div>

        </div>

    </div>

    <script>

        function tambahProduk() {

            let container = document.getElementById('produk-container');
            let produk = document.querySelector('.produk-row');

            let clone = produk.cloneNode(true);

            clone.querySelector('select').value = '';
            clone.querySelector('input').value = '';

            container.appendChild(clone);
        }

        function hapusProduk(button) {

            let rows = document.querySelectorAll('.produk-row');

            if (rows.length > 1) {
                button.closest('.produk-row').remove();
            } else {
                alert('Minimal harus ada 1 produk!');
            }
        }

    </script>

</body>

</html>