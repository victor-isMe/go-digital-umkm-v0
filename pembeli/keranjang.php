<div class="container mt-4">
    <?php $id_pembeli = $_SESSION['id'] ?>

    <h2>Keranjang Belanja</h2>

    <?php if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0): ?>
        <div class="alert alert-warning">Keranjang masih kosong! Silahkan berbelanja terlebih dahulu.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $total = 0;

                foreach ($_SESSION['cart'] as $id => $qty):
                    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");

                    $produk = mysqli_fetch_assoc($query);

                    $tersedia = $produk['stok'] - $produk['stok_reserved'];

                    if($qty > $tersedia) {
                        $qty = $tersedia;
                    }

                    $subtotal = $produk['harga'] * $qty;

                    $total += $subtotal;

                    mysqli_query($koneksi, "INSERT INTO keranjang (id_pembeli, id_produk, qty)
                                    VALUES ('$id_pembeli', '$id', '$qty')
                                    ON DUPLICATE KEY UPDATE qty=qty+$qty");
                ?>

                <tr>
                    <td><?= $produk['nama'] ?>
                    <?php
                    if ($tersedia <= 0) {
                        echo "<span style='color: red'>Stok Habis</span>";
                    }
                    ?>
                </td>
                    <td>Rp <?= number_format($produk['harga']) ?></td>
                    <td>
                        <div>
                            <a href="index.php?page=pembeli/keranjang&minus=<?= $id ?>" class="btn btn-warning btn-sm">-</a>
                            <strong><?= $qty ?></strong>
                            <a href="index.php?page=pembeli/keranjang&plus=<?= $id ?>" class="btn btn-success btn-sm">+</a>
                            <a href="index.php?page=pembeli/keranjang&hapus_cart=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus dari keranjang?')">Hapus</a>
                        </div>
                    </td>
                    <td>Rp <?= number_format($subtotal) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="3">Total Harga</th>
                    <th>Rp <?= number_format($total) ?></th>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>

    <a href="index.php?page=pembeli/checkout" class="btn btn-success">Checkout</a>
</div>