<div class="card shadow p-4">

    <h2>Checkout</h2>

    <?php
    if (isset($_GET['buy_now'])) {
        $id_produk = $_GET['buy_now'];

        $_SESSION['buy_now'] = [];

        $_SESSION['buy_now'][$id_produk] = 1;

        $nama = $_SESSION['nama'];
        $alamat = $_SESSION['alamat'];
    }
    ?>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Ringkasan Pesanan</h5>

            <?php
            $total = 0;
            $items = [];


            $data = isset($_SESSION['buy_now']) ? $_SESSION['buy_now'] : $_SESSION['cart'];

            foreach ($data as $id => $qty) {
                $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
                $produk = mysqli_fetch_assoc($query);
                
                $tersedia = $produk['stok'];

                if ($qty > $tersedia) {
                    die("Stok habis untuk {$produk['nama']}");
                }
                
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
            <input type="text" name="nama" class="form-control" placeholder="Nama penerima" value="<?= $nama ?>" required>
        </div>
        <div class="mb-3">
            <textarea name="alamat" class="form-control" placeholder="Alamat penerima" required><?= $alamat ?></textarea>
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
            <textarea name="catatan" class="form-control" placeholder="Catatan (opsional)"></textarea>
        </div>

        <button type="submit" name="checkout" class="btn btn-success w-100">Bayar</button>
    </form>

    <?php
    if (isset($_POST['checkout'])) {
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $bayar = $_POST['bayar'];

        $grup = [];

        foreach ($data as $id => $qty) {
            $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
            $produk = mysqli_fetch_assoc($query);

            $id_penjual = $produk['id_penjual'];

            $grup[$id_penjual][] = [
                'id_produk' => $id,
                'qty' => $qty,
                'harga' => $produk['harga']
            ];

            mysqli_query($koneksi, "UPDATE produk set stok=stok-$qty WHERE id_produk=$id AND stok>=$qty");
        }

        foreach ($grup as $seller => $items) {
            $total_per_seller = 0;

            foreach ($items as $item) {
                $total_per_seller += $item['harga'] * $item['qty'];
            }

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
                        '$total_per_seller',
                        '$seller')");

            $id_pesanan = mysqli_insert_id($koneksi);

            foreach ($items as $item) {
                $subtotal = $item['harga']*$item['qty'];

                mysqli_query($koneksi, "INSERT INTO detail_pesanan(
                            id_pesanan,
                            id_produk,
                            jumlah_produk,
                            sub_total)
                            VALUES(
                            '$id_pesanan',
                            '{$item['id_produk']}',
                            '{$item['qty']}',
                            '$subtotal')
                            ");
            }

        }

        if (isset($_SESSION['buy_now'])) {
            unset($_SESSION['buy_now']);
        } else {
            unset($_SESSION['cart']);
            mysqli_query($koneksi, "DELETE FROM keranjang WHERE id_pembeli='{$_SESSION['id']}'");
        }

        echo "
        <script>
            alert('Pesanan berhasil dibuat!');
            location='index.php?page=dashboard';
        </script>
        ";
    }
    ?>
</div>