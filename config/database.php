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

$koneksi = mysqli_connect($host, $user, $pw);

$checkDB = mysqli_query($koneksi, "SHOW DATABASES LIKE '$db'");

if (mysqli_num_rows($checkDB) == 0) {
    mysqli_query($koneksi, "CREATE DATABASE $db");

    mysqli_select_db($koneksi, $db);
    
    $sql = file_get_contents(__DIR__ . "/db_marketplace.sql");

    if ($sql) {
        if (mysqli_multi_query($koneksi, $sql)) {
            do {
                if ($result = mysqli_store_result($koneksi)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($koneksi));
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
} else {
    mysqli_select_db($koneksi, $db);
}

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}
?>