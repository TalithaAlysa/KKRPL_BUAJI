<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];

$data = mysqli_query($koneksi, "SELECT * FROM t_pelanggan WHERE id_pelanggan='$id'");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['ubah'])) {
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    mysqli_query($koneksi, "UPDATE t_pelanggan SET
        nama_pelanggan='$nama',
        alamat='$alamat',
        no_hp='$no_hp'
        WHERE id_pelanggan='$id'");

    header("Location: pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan</title>

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
                Toko Bibit Bunga
            </span>

            <a href="../logout.php" class="btn btn-light text-toko">
                Logout
            </a>
        </div>
    </nav>

    <div class="container py-4">

        <div class="card card-toko border-0 shadow-sm mt-3">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">Edit Pelanggan</h4>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text"
                               name="nama_pelanggan"
                               class="form-control"
                               value="<?= $row['nama_pelanggan']; ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat"
                                  class="form-control"
                                  rows="3"
                                  required><?= $row['alamat']; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">No. HP</label>
                        <input type="text"
                               name="no_hp"
                               class="form-control"
                               value="<?= $row['no_hp']; ?>"
                               required>
                    </div>

                    <button type="submit" name="ubah" class="btn btn-toko">
                        Simpan Perubahan
                    </button>

                    
                    <a href="dashboard_kasir.php" class="btn btn-secondary ms-2">
                        Kembali ke Dashboard
                    </a>
 

                </form>

            </div>
        </div>

    </div>

</body>

</html>