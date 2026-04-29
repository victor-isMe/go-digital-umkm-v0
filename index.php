<?php
require_once 'config/database.php';
session_start();

$page = $_GET['page'] ?? 'home';

$allowed_pages = ['home', 'produk', 'form', 'login'];

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
    if ($_SESSION['role'] !== 'umkm') {
        echo "Akses ditolak!";
        header("Location: index.php?page=home");
        exit;
    }
}

if ($page == 'produk') {
    $result = mysqli_query($koneksi, "SELECT * FROM produk");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Go Digital UMKM</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .product-container {
            display: grid;
            grid-template-columns: 2fr;
            gap: 15px;
            padding: 15px;
            align-items: center;
        }

        .sidebar {
            width: 100%;
            background: white;
            padding: 15px;
            border-radius: 10px;
        }

        .sidebar button {
            width: 100%;
            margin: 5px 0;
            padding: 8px;
            border: none;
            background: #eee;
            cursor: pointer;
            border-radius: 6px;
        }

        .sidebar button:hover {
            background: #4CAF50;
            color: white;
        }

        .cart-float {
            position: fixed;
            bottom: 40px;
            right: 40px;
            background: #16a34a;
            padding: 12px 16px;
            border-radius: 50px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            z-index: 9999;
        }

        .cart {
            width: 100%;
            max-height: 340px;
            position: static;
            overflow-y: scroll;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
            font-size: 14px;
        }

        .toggleCart {
            display: flex;
            gap: 5px;
        }

        .hapus {
            background: red;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 15px;
            width: 320px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .modal input {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        @media (min-width: 768px) {
            .sidebar {
                width: 100%;
            }
        }

        @media (min-width: 1200px) {
            .product-container {
                grid-template-columns: 220px 1fr 300px;
                align-items: start;
            }

            .sidebar {
                width: 100%;
                position: sticky;
                top: 100px;
            }

            .cart {
                width: 100%;
                position: sticky;
                top: 100px;
            }

            .cart-float {
                display: none;
            }
        }
    </style>
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