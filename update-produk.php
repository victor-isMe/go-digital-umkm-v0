<?php
require_once "config/database.php";

    $id = $_GET['id'];
    $nama = $_POST['name'];
    $kategori = $_POST['category'];
    $harga = (int)$_POST['price'];
    $stok = (int)$_POST['stock'];
    $deskripsi = $_POST['description'];

    mysqli_query($koneksi, "UPDATE produk SET
    nama='$nama',
    kategori='$kategori',
    harga='$harga',
    stok='$stok',
    deskripsi='$deskripsi'
    
    WHERE id_produk='$id'");