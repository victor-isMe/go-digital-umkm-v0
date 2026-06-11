<?php
if ($_SERVER['HTTP_HOST'] == 'localhost'){
    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "db_marketplace";
} else {
    $host = "sql105.infinityfree.com";
    $user = "if0_42136918";
    $pw = "kelompok7chill";
    $db = "if0_42136918_db_marketplace";
}

$koneksi = mysqli_connect($host, $user, $pw, $db);

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}
?>