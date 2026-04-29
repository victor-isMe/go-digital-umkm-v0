<?php
require_once "config/database.php";

$targetFolder = "img/";
$fileName = time() . "_" . basename($_FILES['image']['name']);
$targetFile = $targetFolder . $fileName;

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
    $nama = $_POST['name'];
    $kategori = $_POST['category'];
    $harga = (int)$_POST['price'];
    $stok = (int)$_POST['stock'];
    $deskripsi = $_POST['description'];
    $foto = $targetFile;

    $stmt = mysqli_prepare($koneksi, "INSERT INTO produk (nama, kategori, harga, stok, deskripsi, foto)
                VALUES (?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "ssiiss", $nama, $kategori, $harga, $stok, $deskripsi, $foto);

    mysqli_stmt_execute($stmt);

    header("Location: index.php?page=produk");
    exit;
}else {
    echo "Produk gagal ditambahkan!";
}