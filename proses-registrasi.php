<?php
require_once 'config/database.php';

$role = $_POST['role'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$alamat = $_POST['alamat'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$cekEmail = $koneksi->query("SELECT email_pembeli FROM pembeli WHERE email_pembeli='$email' UNION SELECT email_penjual FROM penjual WHERE email_penjual='$email'");

if($cekEmail->num_rows > 0) {
    die("Email sudah terdaftar!");
}

if ($role === "pembeli") {
    $sql = "INSERT INTO pembeli (nama_pembeli, alamat_pembeli, email_pembeli, phone, password) VALUES ('$nama', '$alamat', '$email', '$phone', '$password')";
    $koneksi->query($sql);
} else if($role === "penjual") {
    $toko = $_POST['toko'];

    $sql = "INSERT INTO penjual (nama_penjual, alamat_penjual, phone, nama_toko, email_penjual, password) VALUES ('$nama', '$alamat', '$phone', '$toko', '$email', '$password')";
    $koneksi->query($sql);
}
header("Location: index.php?page=login");
exit;