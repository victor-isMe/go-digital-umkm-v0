<?php
    session_start();

    $found = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $users = [
            [
                "email" => "admin@gmail.com",
                "password" => "admin123",
                "role" => "umkm"
            ],
            [
                "email" => "user@gmail.com",
                "password" => "user123",
                "role" => "customer"
            ]
        ];
        
        foreach ($users as $user) {
            if ($email === $user["email"] && $password === $user["password"]) {
                $found = true;
                break;
            }
        }
    }

    if ($found) {
        $_SESSION["login"] = true;
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        header("Location: index.php?page=home&success=1");
        exit;
    }else {
        header("Location: index.php?page=login&error=1");
    }