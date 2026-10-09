<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi, "INSERT INTO t_produk
        (nama_produk, harga, stok)
        VALUES ('$nama', '$harga', '$stok')");

    header("Location: produk.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM t_produk WHERE id_produk='$id'");

    header("Location: produk.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM t_produk ORDER BY id_produk ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Admin</title>

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

            <a href="../logout.php" class="btn btn-light text-toko">
                Logout
            </a>
        </div>
    </nav>

    <div class="container py-4">

        <!-- Tambah Produk -->
        <div class="card card-toko border-0 shadow-sm mt-3 mb-4">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Tambah Produk
                </h4>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Produk
                        </label>

                        <input type="text"
                               name="nama_produk"
                               class="form-control"
                               placeholder="Masukkan nama produk"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Harga
                        </label>

                        <input type="number"
                               name="harga"
                               class="form-control"
                               placeholder="Masukkan harga"
                               min="0"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Stok
                        </label>

                        <input type="number"
                               name="stok"
                               class="form-control"
                               placeholder="Masukkan stok"
                               min="0"
                               required>
                    </div>

                    <button type="submit"
                            name="simpan"
                            class="btn btn-toko">
                        Simpan
                    </button>

                    <a href="dashboard_admin.php" class="btn btn-secondary ms-2">
                        Kembali ke Dashboard
                    </a>

                </form>

            </div>
        </div>

        <!-- Data Produk -->
        <div class="card card-toko border-0 shadow-sm">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Data Produk
                </h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($data)) { ?>

                                <tr>
                                    <td><?= $row['id_produk']; ?></td>

                                    <td><?= $row['nama_produk']; ?></td>

                                    <td>
                                        Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                    </td>

                                    <td><?= $row['stok']; ?></td>

                                    <td>
                                        <a href="edit_produk.php?id=<?= $row['id_produk']; ?>"
                                           class="btn btn-toko btn-sm">
                                            Edit
                                        </a>

                                        <a href="produk.php?hapus=<?= $row['id_produk']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

</body>

</html>