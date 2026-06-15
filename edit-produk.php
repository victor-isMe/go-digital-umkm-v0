<?php
require_once 'config/database.php';

$id = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
$row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div>
        <a href="index.php?page=products-admin" class="btn btn-danger">&lt;Kembali</a>
        <h1 class="text-center">Edit Produk</h1>
    </div>
    <form class="form" method="POST" enctype="multipart/form-data">
        <h2>Edit Produk</h2>

        <label>Nama Produk</label>
        <input type="text" name="name" value="<?= $row['nama']; ?>">

        <label>Kategori</label>
        <select name="category" required>
            <option value="makanan" <?= ($row['kategori'] == 'makanan') ? 'selected':''; ?>>Makanan</option>
            <option value="kerajinan" <?= ($row['kategori'] == 'kerajinan') ? 'selected':''; ?>>Kerajinan</option>
            <option value="fashion" <?= ($row['kategori'] == 'fashion') ? 'selected':''; ?>>Fashion</option>
        </select>

        <label>Harga</label>
        <input type="number" name="price" value="<?= $row['harga']; ?>">

        <label>Stok</label>
        <input type="number" name="stock" value="<?= $row['stok']; ?>">

        <label>Deskripsi</label>
        <textarea name="description"><?= htmlspecialchars($row['deskripsi']); ?></textarea>

        <!-- <label>Upload Gambar</label>
        <input type="file" name="image" accept="img/*"> -->
        <img src="<?= $row['foto']; ?>" width="100">

        <button name="update" type="submit" class="btn btn-primary">
            Update
        </button>
    </form>

    <?php
    if (isset($_POST['update'])) {
        $nama = $_POST['name'];
        $kategori = $_POST['category'];
        $harga = (int)$_POST['price'];
        $stok = (int)$_POST['stock'];
        $deskripsi = $_POST['description'];

        $update = mysqli_query($koneksi, "UPDATE produk SET
        nama='$nama',
        kategori='$kategori',
        harga='$harga',
        stok='$stok',
        deskripsi='$deskripsi'
        
        WHERE id_produk='$id'");

        if ($update) {
            echo "<script>location='index.php?page=products-admin'</script>";
        } else {
            echo "<script>alert('Gagal update')</script>";
        }
    }
    ?>
</body>
</html>