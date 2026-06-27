<?php
require_once 'config/database.php';

$koneksi->begin_transaction();

try {
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
    $payment = $_POST['payment'] ?? [];

    $validPayment = false;

    foreach ($payment as $metode => $entries) {
        foreach ($entries as $entry) {
            if (!empty($entry['nomor_akun']) && !empty($entry['nama_akun'])) {
                $validPayment = true;
                break 2;
            }
        }
    }

    if (!$validPayment) {
        throw new Exception("Minimal 1 metode pembayaran harus diisi!");
    }

    $sql = "INSERT INTO penjual (nama_penjual, alamat_penjual, phone, nama_toko, email_penjual, password) VALUES ('$nama', '$alamat', '$phone', '$toko', '$email', '$password')";
    $koneksi->query($sql);

    $id_penjual = $koneksi->insert_id;

    $payment = $_POST['payment'] ?? [];

    $stmt = $koneksi->prepare("INSERT INTO metode_pembayaran (id_penjual, metode, provider, nomor_akun, nama_akun)
                                VALUES (?, ?, ?, ?, ?)");
    
    foreach ($payment as $metode => $entries) {
        foreach ($entries as $entry) {
            if (empty($entry['nomor_akun']) || empty($entry['nama_akun'])) continue;

            $provider = $entry['provider'];
            $nomor_akun = $entry['nomor_akun'];
            $nama_akun = $entry['nama_akun'];

            $stmt->bind_param("issss", $id_penjual, $metode, $provider, $nomor_akun, $nama_akun);
            $stmt->execute();
        }
    }
}
$koneksi->commit();

header("Location: index.php?page=login");
exit;
} catch (Exception $e) {
    $koneksi->rollback();
    die("Register gagal: " . $e->getMessage());
}