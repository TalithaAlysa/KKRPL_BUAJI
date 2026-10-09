<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

/* Pesan berhasil */
if (isset($_SESSION['pesan'])) {
    $pesan = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}

/* Simpan pelanggan */
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    mysqli_query($koneksi, "INSERT INTO t_pelanggan
        (nama_pelanggan, alamat, no_hp)
        VALUES ('$nama', '$alamat', '$no_hp')");

    $_SESSION['pesan'] = "Pelanggan berhasil disimpan";

    header("Location: pelanggan.php");
    exit;
}

/* Hapus pelanggan */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM t_pelanggan
                            WHERE id_pelanggan='$id'");

    header("Location: pelanggan.php");
    exit;
}

/* Tampilkan data pelanggan */
$data = mysqli_query($koneksi, "SELECT * FROM t_pelanggan
                                ORDER BY id_pelanggan ASC");

$no = 1;
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pelanggan - Toko Bibit Bunga</title>

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
    </style>

</head>

<body class="bg-light">

    <nav class="navbar bg-toko">

        <div class="container">

            <span class="navbar-brand text-white fw-bold">
                Toko Bibit Bunga
            </span>

            <a href="../logout.php"
               class="btn btn-light text-toko">
                Logout
            </a>

        </div>

    </nav>

    <div class="container py-4">

        <div class="card card-toko border-0 shadow-sm mt-3 mb-4">

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

                    <a href="dashboard_kasir.php"
                       class="btn btn-secondary ms-2">
                        Kembali ke Dashboard
                    </a>

                </form>

            </div>

        </div>

        <div class="card card-toko border-0 shadow-sm">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Data Pelanggan
                </h4>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-success">

                            <tr>
                                <th>ID</th>
                                <th>Nama Pelanggan</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($data)) { ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= $row['nama_pelanggan']; ?>
                                    </td>

                                    <td>
                                        <?= $row['alamat']; ?>
                                    </td>

                                    <td>
                                        <?= $row['no_hp']; ?>
                                    </td>

                                    <td>

                                        <a href="edit_pelanggan.php?id=<?= $row['id_pelanggan']; ?>"
                                           class="btn btn-sm btn-toko">
                                            Edit
                                        </a>

                                        <a href="pelanggan.php?hapus=<?= $row['id_pelanggan']; ?>"
                                           class="btn btn-sm btn-hapus"
                                           onclick="return confirm('Yakin ingin menghapus data ini?')">
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

    <?php if (isset($pesan)) { ?>

        <script>
            alert("<?= $pesan; ?>");
        </script>

    <?php } ?>

</body>

</html>