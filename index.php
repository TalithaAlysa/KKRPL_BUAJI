
<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM t_user
                                      WHERE username='$username'
                                      AND password='$password'");

    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level'];

        if ($data['level'] == 'admin') {
            header("Location: admin/dashboard_admin.php");
        } else {
            header("Location: kasir/dashboard_kasir.php");
        }

        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Toko Bibit Bunga</title>

    <!-- Bootstrap 5.3.3 Offline -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        .card-login {
            background-color: #F3F5F2;
        }

        .logo {
            width: 120px;
            height: 120px;
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

                            <h3 class="fw-bold mb-1 text-toko">
                                Toko Bibit Bunga
                            </h3>

                            <h3 class="fw-bold mb-1 text-toko">
                                ELORA GARDEN
                            </h3>

                            <p class="text-secondary mb-0">
                                Silakan login untuk melanjutkan
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
                                    Username
                                </label>

                                <input type="text"
                                       name="username"
                                       class="form-control"
                                       placeholder="Masukkan username"
                                       required>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">
                                    Password
                                </label>

                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="Masukkan password"
                                       required>

                            </div>

                            <button type="submit"
                                    name="login"
                                    class="btn btn-toko w-100">
                                Login
                            </button>

                        </form>

                        <div class="text-center mt-3">
                            <span class="text-secondary">
                                Belum punya akun?
                            </span>

                            <a href="daftar_kasir.php"
                               class="text-toko text-decoration-none fw-semibold">
                                Daftar
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
