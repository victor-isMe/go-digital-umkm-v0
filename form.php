<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "umkm") {
    echo "Akses ditolak!";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Form Produk</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

    <div id="header"></div>

    <form class="form" action="save-product.php" method="POST" enctype="multipart/form-data">

        <h2>Tambah Produk UMKM</h2>

        <label>Nama Produk</label>

        <input type="text" name="name" placeholder="Masukkan nama produk" required>

        <label>Kategori</label>

        <select name="category">

            <option value="makanan">Makanan</option>

            <option value="kerajinan">Kerajinan</option>

            <option value="fashion">Fashion</option>

        </select>

        <label>Harga</label>

        <input type="number" name="price" placeholder="Masukkan harga" required>

        <label>Stok</label>

        <input type="number" name="stock" placeholder="Masukkan stok" required>

        <label>Deskripsi</label>

        <textarea name="description" placeholder="Deskripsi produk"></textarea>

        <label>Upload Gambar</label>

        <input type="file" name="image" accept="img/*" required>

        <button type="submit" class="btn-primary">

            Simpan Produk

        </button>

    </form>

    <div id="footer"></div>

    <script>
        fetch("template/header.php")
            .then(res => res.text())
            .then(data => {
                document.getElementById("header").innerHTML = data;
            });

        fetch("template/footer.html")
            .then(res => res.text())
            .then(data => {
                document.getElementById("footer").innerHTML = data;
            });

        function toggleMenu() {
            const menu = document.getElementById("navMenu");
            menu.classList.toggle("active");
        }
    </script>

</body>

</html>