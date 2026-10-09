<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    mysqli_query($koneksi, "INSERT INTO t_pelanggan
        (nama_pelanggan, alamat, no_hp)
        VALUES ('$nama', '$alamat', '$no_hp')");

    header("Location: pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Pelanggan - Admin</title>

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

        <div class="card card-toko border-0 shadow-sm mt-3">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Tambah Pelanggan
                </h4>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nama Pelanggan
                        </label>

                        <input type="text"
                               name="nama_pelanggan"
                               class="form-control"
                               placeholder="Masukkan nama pelanggan"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Alamat
                        </label>

                        <textarea name="alamat"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Masukkan alamat pelanggan"
                                  required></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            No. HP
                        </label>

                        <input type="text"
                               name="no_hp"
                               class="form-control"
                               placeholder="Contoh: 081234567890"
                               inputmode="numeric"
                               maxlength="13"
                               minlength="10"
                               pattern="08[0-9]{8,11}"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                               required>

                    </div>

                    <button type="submit"
                            name="simpan"
                            class="btn btn-toko">
                        Simpan
                    </button>

                    <a href="pelanggan.php"
                       class="btn btn-secondary ms-2">
                        Kembali
                    </a>

                </form>

            </div>

        </div>

    </div>

</body>

</html>