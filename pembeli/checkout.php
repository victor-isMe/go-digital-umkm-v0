<div class="card shadow p-4">

    <h2>Checkout</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Ringkasan Pesanan</h5>

            <?php
            $total = 0;
            $items = [];
            $id_penjual = 0;

            foreach ($_SESSION['cart'] as $id => $qty) {
                $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
                $produk = mysqli_fetch_assoc($query); 
                $id_penjual = $produk['id_penjual'];
                $subtotal = $produk['harga']*$qty;
                $total += $subtotal;

                $items[] = [
                    'nama' => $produk['nama'],
                    'qty' => $qty,
                    'subtotal' => $subtotal
                ];
            }
            ?>

            <?php foreach ($items as $item): ?>
                <p>
                    <?= $item['nama'] ?>
                    <?= $item['qty'] ?>

                    Rp <?= number_format($item['subtotal']) ?>
                </p>
            <?php endforeach; ?>

            <hr>

            <h5>Total: Rp <?= number_format($total) ?></h5>
        </div>
    </div>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama penerima" required>
        </div>
        <div class="mb-3">
            <textarea name="alamat" class="form-control" placeholder="Alamat penerima" required></textarea>
        </div>
        <div class="mb-3">
            <label>
                <input type="radio" name="bayar" value="Transfer Bank" required> Transfer Bank
            </label>
            <br>
            <label>
                <input type="radio" name="bayar" value="E-Wallet" required> E-Wallet
            </label>
            <br>
            <label>
                <input type="radio" name="bayar" value="COD" required> COD
            </label>
        </div>
        <div class="mb-3">
            <textarea name="catatan" class="form-control" placeholder="Catatan (opsional)" required></textarea>
        </div>

        <button type="submit" name="checkout" class="btn btn-success w-100">Bayar</button>
    </form>

    <?php
    if (isset($_POST['checkout'])) {
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $bayar = $_POST['bayar'];

        mysqli_query($koneksi, "INSERT INTO pesanan(
                        id_pembeli,
                        nama_pemesan,
                        alamat_pemesan,
                        metode_bayar,
                        total_harga,
                        id_penjual)
                        VALUES(
                        '{$_SESSION['id']}',
                        '$nama',
                        '$alamat',
                        '$bayar',
                        '$total',
                        '$id_penjual')");

        unset($_SESSION['cart']);

        echo "
        <script>
            alert('Pesanan berhasil dibuat!');
            location='index.php?page=dashboard';
        </script>
        ";
    }
    ?>
</div>