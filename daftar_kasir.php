
<?php
include "koneksi.php";

if (isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password != $konfirmasi) {
        $error = "Konfirmasi password tidak sesuai!";
    } else {
        $password = md5($password);

        $cek = mysqli_query($koneksi, "SELECT * FROM t_user
                                        WHERE username='$username'");

        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            mysqli_query($koneksi, "INSERT INTO t_user
                                    (nama, username, password, level)
                                    VALUES
                                    ('$nama', '$username', '$password', 'kasir')");

            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar - Toko Bibit Bunga</title>

    <!-- Bootstrap 5.3.3 Offline -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        .card-login {
            background-color: #F3F5F2;
        }

        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
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
    </style>
</head>

<body class="bg-white">

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-md-5 col-lg-4">

                <div class="card card-login border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="text-center mb-4">

                            <img src="gambar/toko_bunga.png"
                                 class="logo mb-3"
                                 alt="Logo Toko Bibit Bunga">

                            <h3 class="fw-bold text-toko">
                                Daftar Akun
                            </h3>

                            <p class="text-secondary mb-0">
                                Toko Bibit Bunga ELORA GARDEN
                            </p>

                        </div>

                        <?php if (isset($error)) { ?>

                            <div class="alert alert-danger text-center">
                                <?= $error; ?>
                            </div>

                        <?php } ?>

                        <form method="POST">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama
                                </label>

                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       placeholder="Masukkan nama"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Username
                                </label>

                                <input type="text"
                                       name="username"
                                       class="form-control"
                                       placeholder="Masukkan username"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="Masukkan password"
                                       required>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">
                                    Konfirmasi Password
                                </label>

                                <input type="password"
                                       name="konfirmasi"
                                       class="form-control"
                                       placeholder="Masukkan ulang password"
                                       required>

                            </div>

                            <button type="submit"
                                    name="daftar"
                                    class="btn btn-toko w-100">
                                Daftar
                            </button>

                        </form>

                        <div class="text-center mt-3">

                            <span class="text-secondary">
                                Sudah punya akun?
                            </span>

                            <a href="index.php"
                               class="text-toko text-decoration-none fw-semibold">
                                Login
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>