<a href="index.php?page=products-admin" class="btn-dashboard-solid">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>    Kembali
</a>

<form class="form" action="save-product.php" method="POST" enctype="multipart/form-data">
    <?php $id_penjual = $_SESSION['id']; ?>

    <h2>Tambah Produk UMKM</h2>

    <label>Nama Produk</label>
    <input type="text" name="name" placeholder="Masukkan nama produk" required>

    <label>Kategori</label>
    <select name="category">
        <option value="makanan">Makanan</option>
        <option value="kerajinan">Kerajinan</option>
        <option value="fashion">Fashion</option>
    </select>

    <label>Harga</label>
    <input type="number" name="price" placeholder="Masukkan harga" required>

    <label>Stok</label>
    <input type="number" name="stock" placeholder="Masukkan stok" required>

    <label>Deskripsi</label>
    <textarea name="description" placeholder="Deskripsi produk"></textarea>

    <label>Upload Gambar</label>
    <input type="file" name="image" accept="img/*" required>

    <input type="hidden" name="id_penjual" value="<?= $id_penjual ?>">

    <button type="submit" class="btn btn-primary">
        Simpan Produk
    </button>

</form>