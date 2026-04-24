<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_marketplace");

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}
?>