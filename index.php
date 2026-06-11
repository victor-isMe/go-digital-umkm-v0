<?php
require_once 'config/database.php';
session_start();

$page = $_GET['page'] ?? 'home';

$allowed_pages = ['dashboard', 'home', 'produk', 'form', 'login', 'products-admin', 'register', 'edit-produk', 'admin/daftar-umkm', 'admin/nonaktifkan-umkm', 'pembeli/keranjang', 'pembeli/checkout'];

$sequre_pages = ['dashboard', 'form', 'products-admin', 'edit-produk', 'admin/daftar-umkm','admin/nonaktifkan-umkm', 'pembeli/keranjang', 'pembeli/checkout'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

if (in_array($page, $sequre_pages)) {
    require 'auth/auth.php';
}

if ($page == 'login') {
    if (isset($_SESSION['login'])) {
        header("Location: index.php?page=home");
        exit;
    }
}

if (in_array($page, $sequre_pages)) {
    if(!isset($_SESSION['login'])) {
        header("Location: index.php?page=login");
        exit;
    }
}

if ($page == 'dashboard') {
    if ($_SESSION['role'] == 'admin') {
        $page = 'admin/dashboard';
    } elseif ($_SESSION['role'] == 'penjual') {
        $page = 'penjual/dashboard';
    } elseif ($_SESSION['role'] == 'pembeli') {
        $page = 'pembeli/dashboard';
    }
}

if ($page == 'form') {
    if ($_SESSION['role'] !== 'penjual') {
        echo "Akses ditolak!";
        header("Location: index.php?page=dashboard");
        exit;
    }
}

if ($page == 'produk') {
    $result = mysqli_query($koneksi, "SELECT * FROM produk");
}
if (isset($_GET['add_cart'])) {
    $id = $_GET['add_cart'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    header("Location: index.php?page=produk");
    exit;
}
if (isset($_GET['plus'])) {
    $id = $_GET['plus'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    }

    header("Location: index.php?page=pembeli/keranjang");
    exit;
}
if (isset($_GET['minus'])) {
    $id = $_GET['minus'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;

        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    header("Location: index.php?page=pembeli/keranjang");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    unset($_SESSION['cart'][$id]);

    header("Location: index.php?page=pembeli/keranjang");
    exit;
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
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <?php if ($page != 'admin/dashboard' && $page != 'penjual/dashboard' && $page != 'pembeli/dashboard'): ?>
        <?php include 'template/header.php'; ?>
    <?php endif; ?>

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

    <?php if ($page != 'admin/dashboard' && $page != 'penjual/dashboard' && $page != 'pembeli/dashboard'): ?>
        <?php include 'template/footer.php'; ?>
    <?php endif; ?>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>