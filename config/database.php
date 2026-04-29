<?php
$host = "localhost";
$user = "root";
$pw = "";
$db = "db_marketplace";

$koneksi = mysqli_connect($host, $user, $pw);

$checkDB = mysqli_query($koneksi, "SHOW DATABASES LIKE '$db'");

if (mysqli_num_rows($checkDB) == 0) {
    mysqli_query($koneksi, "CREATE DATABASE $db");

    mysqli_select_db($koneksi, $db);
    
    $sql = file_get_contents(__DIR__ . "/database.sql");

    if ($sql) {
        mysqli_multi_query($koneksi, $sql);
    }
} else {
    mysqli_select_db($koneksi, $db);
}

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}
?>