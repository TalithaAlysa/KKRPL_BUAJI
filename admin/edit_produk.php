
<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];

$data = mysqli_query($koneksi, "SELECT * FROM t_produk
                                WHERE id_produk='$id'");

$produk = mysqli_fetch_assoc($data);

if (isset($_POST['ubah'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi, "UPDATE t_produk SET
        nama_produk='$nama',
        harga='$harga',
        stok='$stok'
        WHERE id_produk='$id'");

    header("Location: produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Produk - Admin</title>

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

        <div class="card card-toko border-0 shadow-sm mt-3">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Edit Produk
                </h4>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Produk
                        </label>

                        <input type="text"
                               name="nama_produk"
                               class="form-control"
                               value="<?= $produk['nama_produk']; ?>"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Harga
                        </label>

                        <input type="number"
                               name="harga"
                               class="form-control"
                               value="<?= $produk['harga']; ?>"
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
                               value="<?= $produk['stok']; ?>"
                               min="0"
                               required>

                    </div>

                    <button type="submit"
                            name="ubah"
                            class="btn btn-toko">
                        Simpan Perubahan
                    </button>

                    <a href="produk.php"
                       class="btn btn-secondary ms-2">
                        Kembali
                    </a>

                </form>

            </div>

        </div>

    </div>

</body>

</html>