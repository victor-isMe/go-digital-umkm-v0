<?php
require_once 'config/database.php';
session_start();

$page = $_GET['page'] ?? 'home';

$allowed_pages = ['home', 'produk', 'form', 'login', 'products-admin', 'register'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

if ($page == 'login') {
    if (isset($_SESSION['login'])) {
        header("Location: index.php?page=home");
        exit;
    }

}

if ($page == 'form') {
    if (!isset($_SESSION['login'])) {
        header("Location: index.php?page=login");
        exit;
    }
    if ($_SESSION['role'] !== 'penjual') {
        echo "Akses ditolak!";
        header("Location: index.php?page=home");
        exit;
    }
}

if ($page == 'produk') {
    $result = mysqli_query($koneksi, "SELECT * FROM produk");
}
if ($page == 'products-admin') {
    $result = mysqli_query($koneksi, "SELECT * FROM produk");
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk='$id'");
    echo "<script>location='index.php?page=products-admin'</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Go Digital UMKM</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'template/header.php'; ?>
    
    <?php include "$page.php"; ?>

    <?php if (isset($_GET['success'])): ?>
        <div id="toast" class="alert alert-success fixed-bottom text-center">
            Login berhasil!
        </div>

        <script>
            document.addEventListener("DOMContentLoaded",
                function() {
                    const toastEl = document.getElementById('toast');
                    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toast.show();
                }
            );
        </script>
    <?php endif; ?>

    <?php include 'template/footer.php'; ?>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>