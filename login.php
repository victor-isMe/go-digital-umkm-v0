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

            header("Location: index.php?page=home");
            exit;
        }
    }

    echo "<span>Login Gagal!</span>";
}
?>
<div class="login-container">
    <div class="login-box">
        <h2>Selamat Datang</h2>
        <p>Masuk ke akun anda</p>
        <form class="login-form" method="POST">
            <label>Email</label>
            <input name="email" type="email" placeholder="Email" required>

            <label>Password</label>
            <input name="password" type="password" required>

            <button type="submit" class="btn btn-primary">
                Login
            </button>
        </form>
    </div>
</div>