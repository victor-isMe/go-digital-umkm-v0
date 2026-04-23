<?php
session_start();

$page = $_GET['page'] ?? 'home';

$allowed_pages = ['home', 'produk', 'form', 'login'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
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

    <div>
        <?php include "$page.php"; ?>
    </div>

    <div id="footer">
        <?php include 'template/footer.php'; ?>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        // fetch("template/header.php")
        //     .then(res => res.text())
        //     .then(data => {
        //         document.getElementById("header").innerHTML = data;
        //     });
            
        // fetch("template/footer.html")
        //     .then(res => res.text())
        //     .then(data => {
        //         document.getElementById("footer").innerHTML = data;
        //     });
    </script> -->
</body>

</html>