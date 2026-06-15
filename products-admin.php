<h1>Kelola Produk</h1>

<h3>Daftar Produk</h3>
<table class="table table-bordered">
    <tr>
        <th>Nama Produk</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Aksi</th>
    </tr>

    <?php 
        $id_penjual = $_SESSION['id'];
        $result = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_penjual='$id_penjual'");

        while ($row = mysqli_fetch_assoc($result)): 
    ?>
    <tr>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['kategori'] ?></td>
        <td>Rp<?= number_format($row['harga']) ?></td>
        <td>
            <a href="edit-produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="?hapus=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk?')">Hapus</a>
        </td>
    </tr>

    <?php endwhile; ?>
</table>