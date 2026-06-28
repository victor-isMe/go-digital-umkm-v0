<?php
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
$produk = mysqli_fetch_assoc($query);
?>

<div class="container mt-5">
    <a href="index.php?page=produk" class="btn-dashboard-ghost">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke halaman produk
    </a>
    <div class="card shadow p-4">
        <div class="row">
            <div class="col-md-5 text-center">
                <img src="<?= $produk['foto'] ?>" alt="<?= $produk['nama'] ?>" class="img-fluid rounded" style="max-height: 300px; object-fit:cover;">
            </div>

            <div class="col-md-7">
                <h3><?= $produk['nama'] ?></h3>

                <h4 class="text-success mt-3">Rp<?= number_format($produk['harga']) ?></h4>

                <p class="text-muted">Stok: <?= $produk['stok'] ?></p>
                <hr>
                <div class="row g-1">
                    <div class="col-9">
                        <a href="index.php?page=pembeli/checkout&buy_now=<?= $produk['id_produk'] ?>" class="btn btn-primary w-100">Beli Sekarang</a>
                    </div>
                    <div class="col-3">
                        <a href="index.php?page=produk&add_cart=<?= $produk['id_produk'] ?>" class="btn btn-primary w-100">🛒</a>
                    </div>
                </div>
                <hr>
                <h5>Deskripsi</h5>
                <p><?= $produk['deskripsi'] ?></p>
            </div>
        </div>
    </div>
</div>