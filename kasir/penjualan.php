<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $username_kasir = $_SESSION['username'];
    $tanggal = date('Y-m-d');
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $total = 0;

    for ($i = 0; $i < count($id_produk); $i++) {
        if ($id_produk[$i] == '' || $jumlah[$i] < 1) {
            continue;
        }

        $produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_produk
            WHERE id_produk='{$id_produk[$i]}'"));

        if (!$produk) {
            continue;
        }

        if ($jumlah[$i] > $produk['stok']) {
            echo "<script>
                    alert('Stok {$produk['nama_produk']} tidak cukup!');
                    window.location='penjualan.php';
                  </script>";
            exit;
        }

        $subtotal = $produk['harga'] * $jumlah[$i];
        $total += $subtotal;
    }

    if ($total == 0) {
        echo "<script>
                alert('Pilih produk terlebih dahulu!');
                window.location='penjualan.php';
              </script>";
        exit;
    }

    $username_kasir = $_SESSION['username'];

    mysqli_query($koneksi, "INSERT INTO t_penjualan
        (id_pelanggan, username_kasir, tanggal, total_harga)
        VALUES ('$id_pelanggan', '$username_kasir', '$tanggal', '$total')");

    $id_penjualan = mysqli_insert_id($koneksi);

    for ($i = 0; $i < count($id_produk); $i++) {
        if ($id_produk[$i] == '' || $jumlah[$i] < 1) {
            continue;
        }

        $produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_produk
            WHERE id_produk='{$id_produk[$i]}'"));

        $subtotal = $produk['harga'] * $jumlah[$i];

        mysqli_query($koneksi, "INSERT INTO t_detailpenjualan
            (id_penjualan, id_produk, jumlah_produk, subtotal)
            VALUES ('$id_penjualan', '{$id_produk[$i]}', '{$jumlah[$i]}', '$subtotal')");

        mysqli_query($koneksi, "UPDATE t_produk
            SET stok = stok - {$jumlah[$i]}
            WHERE id_produk='{$id_produk[$i]}'");
    }

    echo "<script>
            alert('Transaksi berhasil disimpan!');
            window.location='penjualan.php';
          </script>";
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

$pelanggan = mysqli_query($koneksi, "SELECT * FROM t_pelanggan");

$produk = mysqli_query($koneksi, "SELECT * FROM t_produk
    WHERE stok > 0");

$penjualan = mysqli_query($koneksi, "SELECT
    t_penjualan.*,
    t_pelanggan.nama_pelanggan,
    GROUP_CONCAT(
        CONCAT(t_produk.nama_produk, ' (', t_detailpenjualan.jumlah_produk, ')')
        SEPARATOR ', '
    ) AS nama_barang
    FROM t_penjualan
    LEFT JOIN t_pelanggan
    ON t_penjualan.id_pelanggan = t_pelanggan.id_pelanggan
    LEFT JOIN t_detailpenjualan
    ON t_penjualan.id_penjualan = t_detailpenjualan.id_penjualan
    LEFT JOIN t_produk
    ON t_detailpenjualan.id_produk = t_produk.id_produk
    GROUP BY t_penjualan.id_penjualan
    ORDER BY t_penjualan.id_penjualan ASC");

$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan - Kasir</title>
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

        .btn-hapus {
            background-color: #C96B6B;
            color: white;
        }

        .btn-hapus:hover {
            background-color: #B85A5A;
            color: white;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-penjualan {
            min-width: 1000px;
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

        .table-penjualan .pelanggan {
            width: 140px;
            white-space: nowrap;
        }

        .table-penjualan .barang {
            min-width: 300px;
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

<nav class="navbar bg-toko">
    <div class="container">
        <span class="navbar-brand text-white fw-bold">
            Toko Bibit Bunga - Kasir
        </span>

        <a href="../logout.php" class="btn btn-light text-toko">
            Logout
        </a>
    </div>
</nav>

<div class="container py-4">

    <div class="card card-toko border-0 shadow-sm mt-3 mb-4">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-4">
                Transaksi Penjualan
            </h4>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Pelanggan
                    </label>

                    <select name="id_pelanggan" class="form-select" required>
                        <option value="">
                            Pilih Pelanggan
                        </option>

                        <?php while ($row = mysqli_fetch_assoc($pelanggan)) { ?>
                            <option value="<?= $row['id_pelanggan']; ?>">
                                <?= $row['nama_pelanggan']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <label class="form-label fw-semibold">
                    Produk
                </label>

                <div id="produk-container">

                    <div class="row mb-3 produk-row">

                        <div class="col-md-7">
                            <select name="id_produk[]" class="form-select" required>
                                <option value="">
                                    Pilih Produk
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
                                   min="1"
                                   placeholder="Jumlah"
                                   required>
                        </div>

                        <div class="col-md-2">
                            <button type="button"
                                    onclick="hapusProduk(this)"
                                    class="btn btn-hapus w-100">
                                Hapus
                            </button>
                        </div>

                    </div>

                </div>

                <button type="button"
                        onclick="tambahProduk()"
                        class="btn btn-secondary mb-3">
                    + Tambah Produk
                </button>

                <br>

                <button type="submit"
                        name="simpan"
                        class="btn btn-toko">
                    Simpan Transaksi
                </button>

                <a href="dashboard_kasir.php"
                   class="btn btn-secondary ms-2">
                    Kembali ke Dashboard
                </a>

            </form>

        </div>
    </div>

    <div class="card card-toko border-0 shadow-sm mt-3">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-4">
                Data Penjualan
            </h4>

            <div class="table-responsive">

                <table class="table table-hover align-middle table-penjualan">

                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Nama Barang</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (mysqli_num_rows($penjualan) > 0) { ?>

                            <?php while ($row = mysqli_fetch_assoc($penjualan)) { ?>

                                <tr>
                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= $row['id_penjualan']; ?>
                                    </td>

                                    <td class="tanggal">
                                        <?= date('d-m-Y', strtotime($row['tanggal'])); ?>
                                    </td>

                                    <td class="pelanggan">
                                        <?= $row['nama_pelanggan']; ?>
                                    </td>

                                    <td class="barang">
                                        <?= $row['nama_barang'] ?? '-'; ?>
                                    </td>

                                    <td class="total">
                                        Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                                    </td>

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
                                <td colspan="7" class="text-center">
                                    Data penjualan tidak ditemukan
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<script>
function tambahProduk() {
    let container = document.getElementById('produk-container');
    let row = document.querySelector('.produk-row').cloneNode(true);

    row.querySelector('select').value = '';
    row.querySelector('input').value = '';

    container.appendChild(row);
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