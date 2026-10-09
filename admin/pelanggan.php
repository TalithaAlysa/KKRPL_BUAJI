
<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $cek = mysqli_query($koneksi, "SELECT * FROM t_penjualan
                                   WHERE id_pelanggan='$id'");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Data pelanggan tidak bisa dihapus! Hapus data penjualan pelanggan ini terlebih dahulu.');
                window.location='pelanggan.php';
              </script>";
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM t_pelanggan
                            WHERE id_pelanggan='$id'");

    echo "<script>
            alert('Data pelanggan berhasil dihapus!');
            window.location='pelanggan.php';
          </script>";
    exit;
}

$cari = $_GET['cari'] ?? '';

$where = "";

if ($cari != '') {
    $where = "WHERE t_pelanggan.nama_pelanggan LIKE '%$cari%'";
}

$data = mysqli_query($koneksi, "SELECT 
    t_pelanggan.*,
    GROUP_CONCAT(DISTINCT t_produk.nama_produk SEPARATOR ', ') AS nama_barang
    FROM t_pelanggan
    LEFT JOIN t_penjualan
    ON t_pelanggan.id_pelanggan = t_penjualan.id_pelanggan
    LEFT JOIN t_detailpenjualan
    ON t_penjualan.id_penjualan = t_detailpenjualan.id_penjualan
    LEFT JOIN t_produk
    ON t_detailpenjualan.id_produk = t_produk.id_produk
    $where
    GROUP BY t_pelanggan.id_pelanggan
    ORDER BY t_pelanggan.id_pelanggan ASC");

$no = 1;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pelanggan - Admin</title>

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

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h4 class="fw-bold mb-0">
                        Data Pelanggan
                    </h4>

                    <a href="tambah_pelanggan.php"
                       class="btn btn-toko">
                        Tambah Pelanggan
                    </a>

                </div>

                <!-- Pencarian -->
                <form method="GET" class="d-flex mb-4">

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

                        <a href="pelanggan.php"
                           class="btn btn-secondary ms-2">
                            Reset
                        </a>

                    <?php } ?>

                </form>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-success">

                            <tr>
                                <th>ID</th>
                                <th>Nama Pelanggan</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Nama Barang</th>
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
                                        <?= $row['nama_barang'] ?? '-'; ?>
                                    </td>

                                    <td>

                                        <a href="edit_pelanggan.php?id=<?= $row['id_pelanggan']; ?>"
                                           class="btn btn-sm btn-toko">
                                            Edit
                                        </a>

                                        <a href="pelanggan.php?hapus=<?= $row['id_pelanggan']; ?>"
                                           class="btn btn-sm btn-hapus"
                                           onclick="return confirm('Yakin ingin menghapus data pelanggan ini?')">
                                            Hapus
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    <a href="dashboard_admin.php"
                       class="btn btn-secondary">
                        Kembali ke Dashboard
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>

</html>