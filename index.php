<?php
require_once 'config/database.php';
session_start();

$page = $_GET['page'] ?? 'home';

$allowed_pages = ['dashboard', 'home', 'produk', 'form', 'login', 'products-admin', 'register', 'edit-produk', 'admin/daftar-umkm', 'admin/nonaktifkan-umkm', 'pembeli/keranjang', 'pembeli/checkout', 'pembeli/riwayat-pesanan', 'penjual/status-pesanan', 'penjual/daftar-pesanan', 'produk/detail', 'penjual/verif-pembayaran', 'contact', 'detail-penjual'];

$sequre_pages = ['dashboard', 'form', 'products-admin', 'edit-produk', 'admin/daftar-umkm','admin/nonaktifkan-umkm', 'pembeli/keranjang', 'pembeli/checkout', 'pembeli/riwayat-pesanan', 'penjual/status-pesanan', 'penjual/daftar-pesanan', 'penjual/verif-pembayaran', 'contact'];

if ($page == 'home') {
    if (!isset($_SESSION['login'])) {
        $page = 'home';
    } else {
        header("Location: index.php?page=dashboard");
    }
}

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

//Function ambil qty item dari DB keranjang
function db_cart_qty ($koneksi, $id_pembeli, $id_produk) {
    $q = mysqli_query($koneksi, "SELECT qty FROM keranjang
                        WHERE id_pembeli='$id_pembeli' AND id_produk='$id_produk'");
    $row = mysqli_fetch_assoc($q);
    return $row ? (int)$row['qty'] : 0;
}

if (isset($_GET['add_cart'])) {
    if (isset($_SESSION['login']) && $_SESSION['role'] === 'pembeli') {
        $id_produk = $_GET['add_cart'];
        $id_pembeli = $_SESSION['id'];

        mysqli_query($koneksi, "INSERT INTO keranjang (id_pembeli, id_produk, qty)
                        VALUES ('$id_pembeli', '$id_produk', 1)
                        ON DUPLICATE KEY UPDATE qty=qty+1");
        header("Location: index.php?page=produk");
        exit;
    } else {
        header("Location: index.php?page=login");
        exit;
    }
}
if (isset($_GET['plus'])) {
    $id_produk = $_GET['plus'];
    $id_pembeli = $_SESSION['id'];

    $query = mysqli_query($koneksi, "SELECT stok FROM produk WHERE id_produk='$id_produk'");
    $data = mysqli_fetch_assoc($query);

    $tersedia = $data['stok'];

    $qurrent_qty = db_cart_qty($koneksi, $id_pembeli, $id_produk);

    if ($qurrent_qty < $tersedia) {
        mysqli_query($koneksi, "UPDATE keranjang SET qty=qty+1
                    WHERE id_pembeli='$id_pembeli' AND id_produk='$id_produk'");
    }

    header("Location: index.php?page=pembeli/keranjang");
    exit;
}
if (isset($_GET['minus'])) {
    $id_produk = $_GET['minus'];
    $id_pembeli = $_SESSION['id'];

    $qurrent_qty = db_cart_qty($koneksi, $id_pembeli, $id_produk);

    if ($qurrent_qty > 1) {
        mysqli_query($koneksi, "UPDATE keranjang SET qty=qty-1
                    WHERE id_pembeli='$id_pembeli' AND id_produk='$id_produk'");
    } else {
        mysqli_query($koneksi, "DELETE FROM keranjang WHERE id_produk='$id_produk' AND id_pembeli='$id_pembeli'");
    }

    header("Location: index.php?page=pembeli/keranjang");
    exit;
}
if (isset($_GET['hapus_cart'])) {
    $id_produk = $_GET['hapus_cart'];
    $id_pembeli = $_SESSION['id'];

    mysqli_query($koneksi, "DELETE FROM keranjang WHERE id_produk='$id_produk' AND id_pembeli='$id_pembeli'");

    header("Location: index.php?page=pembeli/keranjang");
    exit;
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
    <?php include 'template/header.php'; ?>

    <div class="min-vh-100">
        <?php include "$page.php"; ?>
    </div>

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