<?php
    session_start();
    require_once 'config/database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $queryPembeli = mysqli_query($koneksi, "SELECT * FROM pembeli WHERE email_pembeli='$email'");

        if (mysqli_num_rows($queryPembeli) > 0) {
            $data = mysqli_fetch_assoc($queryPembeli);

            if (password_verify($password, $data["password"])) {
                $_SESSION["login"] = true;
                $_SESSION["email"] = $data["email_pembeli"];
                $_SESSION["role"] = "pembeli";
                $_SESSION["id"] = $data["id_pembeli"];
                $_SESSION["last_activity"] = time();

                header("Location: index.php?page=home&success=1");
                exit;
            }
        }

        $queryPenjual = mysqli_query($koneksi, "SELECT * FROM penjual WHERE email_penjual='$email'");

        if (mysqli_num_rows($queryPenjual) > 0) {
            $data = mysqli_fetch_assoc($queryPenjual);

            if (password_verify($password, $data["password"])) {
                $_SESSION["login"] = true;
                $_SESSION["email"] = $data["email_penjual"];
                $_SESSION["role"] = "penjual";
                $_SESSION["id"] = $data["id_penjual"];
                $_SESSION["toko"] = $data["nama_toko"];
                $_SESSION["last_activity"] = time();

                header("Location: index.php?page=home&success=1");
                exit;
            }
        }

        $queryAdmin = mysqli_query($koneksi, "SELECT * FROM admin WHERE email_admin='$email'");

        if (mysqli_num_rows($queryAdmin) > 0) {
            $data = mysqli_fetch_assoc($queryAdmin);

            if (password_verify($password, $data["password"])) {
                $_SESSION["login"] = true;
                $_SESSION["email"] = $data["email_admin"];
                $_SESSION["role"] = "admin";
                $_SESSION["id"] = $data["id_admin"];
                $_SESSION["last_activity"] = time();

                header("Location: index.php?page=home&success=1");
                exit;
            }
        }

        header("Location: index.php?page=login&error=1");
        exit;
    }