<?php
    session_start();
    require_once 'config/database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $queryPembeli = mysqli_query($koneksi, "SELECT * FROM pembeli WHERE email_pembeli='$email' AND password='$password'");

        if (mysqli_num_rows($queryPembeli) > 0) {
            $data = mysqli_fetch_assoc($queryPembeli);

            $_SESSION["login"] = true;
            $_SESSION["email"] = $data["email_pembeli"];
            $_SESSION["role"] = "pembeli";
            $_SESSION["id"] = $data["id_pembeli"];

            header("Location: index.php?page=home&success=1");
            exit;
        }

        $queryPenjual = mysqli_query($koneksi, "SELECT * FROM penjual WHERE email_penjual='$email' AND password='$password'");

        if (mysqli_num_rows($queryPenjual) > 0) {
            $data = mysqli_fetch_assoc($queryPenjual);

            $_SESSION["login"] = true;
            $_SESSION["email"] = $data["email_penjual"];
            $_SESSION["role"] = "penjual";
            $_SESSION["id"] = $data["id_penjual"];
            $_SESSION["toko"] = $data["nama_toko"];

            header("Location: index.php?page=home&success=1");
            exit;
        }

        header("Location: index.php?page=login&error=1");
        exit;
    }