<?php
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
            $_SESSION["login"] = true;
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            header("Location: index.html");
            exit;
        }
    }

    echo "<span>Login Gagal!</span>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Selamat Datang</h2>
            <p>Masuk ke akun anda</p>
            <form class="login-form" method="POST">
                <label>Email</label>
                <input name="email" type="email" placeholder="Email" required>

                <label>Password</label>
                <input name="password" type="password" required>

                <button type="submit" class="btn-primary">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>

</html>