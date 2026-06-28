<?php
$id_penjual = $_SESSION["id"];

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];

    $q_foto = mysqli_query($koneksi, "SELECT foto FROM produk WHERE id_produk='$id_hapus' AND id_penjual='$id_penjual'");
    $data_foto = mysqli_fetch_assoc($q_foto);

    $hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk='$id_hapus' AND id_penjual='$id_penjual'");

    if ($hapus && mysqli_affected_rows($koneksi) > 0) {
        if (!empty($data_foto['foto']) && file_exists($data_foto['foto'])) {
            @unlink($data_foto['foto']);
        }
        echo "<script>
            alert('Produk berhasil dihapus.');
            location='index.php?page=products-admin';
        </script>";
        exit;
    }
}

$result = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_penjual='$id_penjual' ORDER BY id_produk DESC");
$total_produk = mysqli_num_rows($result);
$rows = [];
while ($r = mysqli_fetch_assoc($result)) $rows[] = $r;
?>

<!-- MODAL HAPUS (di luar .kp-page agar z-index tidak terpotong) -->
<div class="modal-overlay" id="modalHapus">
    <div class="modal-box">
        <div class="modal-title">Hapus produk?</div>
        <div class="modal-desc" id="modalDesc">Produk ini akan dihapus permanen.</div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Batal</button>
            <a class="modal-confirm" id="modalConfirmBtn" href="#">Ya, hapus</a>
        </div>
    </div>
</div>

<div class="kp-page">

    <a href="index.php?page=dashboard" class="btn-dashboard-ghost">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke dashboard
    </a>

    <div class="kp-header">
        <div>
            <div class="kp-title">Kelola produk</div>
            <div class="kp-subtitle">Tambah, edit, dan hapus produk milikmu</div>
        </div>
        <a href="index.php?page=form" class="btn-tambah">
            + Tambah produk
        </a>
    </div>

    <div class="kp-card">

        <div class="kp-toolbar">
            <div class="kp-search">
                <input type="text" id="searchInput"
                       placeholder="Cari nama produk..."
                       oninput="filterProduk()">
            </div>
            <div class="filter-tabs">
                <button class="ftab active" onclick="setFilter('semua', this)">Semua</button>
                <button class="ftab" onclick="setFilter('makanan', this)">Makanan</button>
                <button class="ftab" onclick="setFilter('kerajinan', this)">Kerajinan</button>
                <button class="ftab" onclick="setFilter('fashion', this)">Fashion</button>
            </div>
        </div>

        <?php if (empty($rows)): ?>
            <div class="kp-empty">
                <div class="kp-empty-text">Belum ada produk</div>
                <div class="kp-empty-sub">Klik "Tambah produk" untuk mulai berjualan</div>
            </div>
        <?php else: ?>

        <?php
        // Helper stok badge
        function stokBadgeClass(int $stok): string {
            if ($stok <= 0)  return 'stok-empty';
            if ($stok <= 5)  return 'stok-low';
            return 'stok-ok';
        }
        // Helper icon metode (dipakai di card)
        ?>

        <!-- TABLE desktop & tablet -->
        <div class="kp-table-wrap">
            <table class="kp-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="produkBody">
                <?php foreach ($rows as $row):
                    $stok_class = stokBadgeClass((int)$row['stok']);
                    $foto       = htmlspecialchars($row['foto'] ?? 'img/no-image.png');
                    $nama_js    = addslashes(htmlspecialchars($row['nama']));
                ?>
                <tr data-nama="<?= strtolower(htmlspecialchars($row['nama'])) ?>"
                    data-kategori="<?= htmlspecialchars($row['kategori']) ?>">

                    <td>
                        <div class="prod-foto-wrap">
                            <img class="prod-foto"
                                 src="<?= $foto ?>"
                                 alt="<?= htmlspecialchars($row['nama']) ?>"
                                 onerror="this.src='img/no-image.png'">
                            <div>
                                <div class="prod-nama"><?= htmlspecialchars($row['nama']) ?></div>
                                <div class="prod-id-sub">#<?= $row['id_produk'] ?></div>
                            </div>
                        </div>
                    </td>

                    <td><span class="kategori-pill"><?= htmlspecialchars($row['kategori']) ?></span></td>

                    <td><span class="prod-harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span></td>

                    <td>
                        <span class="stok-badge <?= $stok_class ?>">
                            <?= $row['stok'] ?> pcs
                        </span>
                    </td>

                    <td>
                        <div class="aksi-group">
                            <a href="index.php?page=edit-produk&id=<?= $row['id_produk'] ?>"
                               class="btn-edit">Edit</a>
                            <button class="btn-hapus"
                                    onclick="confirmHapus(<?= $row['id_produk'] ?>, '<?= $nama_js ?>')">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- CARD LIST mobile -->
        <div class="kp-mobile-list" id="mobileList">
        <?php foreach ($rows as $row):
            $stok_class = stokBadgeClass((int)$row['stok']);
            $foto       = htmlspecialchars($row['foto'] ?? 'img/no-image.png');
            $nama_js    = addslashes(htmlspecialchars($row['nama']));
        ?>
            <div class="kp-mobile-card"
                 data-nama="<?= strtolower(htmlspecialchars($row['nama'])) ?>"
                 data-kategori="<?= htmlspecialchars($row['kategori']) ?>">

                <div class="mc-top">
                    <img class="prod-foto"
                         src="<?= $foto ?>"
                         alt="<?= htmlspecialchars($row['nama']) ?>"
                         onerror="this.src='img/no-image.png'">
                    <div class="mc-info">
                        <div class="mc-nama"><?= htmlspecialchars($row['nama']) ?></div>
                        <div class="mc-id">#<?= $row['id_produk'] ?></div>
                    </div>
                </div>

                <div class="mc-row">
                    <span class="mc-label">Kategori</span>
                    <span class="kategori-pill"><?= htmlspecialchars($row['kategori']) ?></span>
                </div>
                <div class="mc-row">
                    <span class="mc-label">Harga</span>
                    <span class="prod-harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                </div>
                <div class="mc-row">
                    <span class="mc-label">Stok</span>
                    <span class="stok-badge <?= $stok_class ?>"><?= $row['stok'] ?> pcs</span>
                </div>

                <div class="mc-foot">
                    <a href="index.php?page=edit-produk&id=<?= $row['id_produk'] ?>"
                       class="btn-edit">Edit</a>
                    <button class="btn-hapus"
                            onclick="confirmHapus(<?= $row['id_produk'] ?>, '<?= $nama_js ?>')">
                        Hapus
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>
</div>

<script>
// ── Modal konfirmasi hapus ─────────────────────────────────────
function confirmHapus(id, nama) {
    document.getElementById('modalDesc').textContent =
        `"${nama}" akan dihapus permanen dan tidak bisa dikembalikan.`;
    document.getElementById('modalConfirmBtn').href =
        `index.php?page=products-admin&hapus=${id}`;
    document.getElementById('modalHapus').classList.add('open');
}
function closeModal() {
    document.getElementById('modalHapus').classList.remove('open');
}
// Klik backdrop = tutup
document.getElementById('modalHapus').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
// ESC = tutup
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

// ── Filter kategori + search ───────────────────────────────────
let activeFilter = 'semua';

function setFilter(f, el) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    filterProduk();
}

function filterProduk() {
    const q   = document.getElementById('searchInput').value.toLowerCase();
    const all = document.querySelectorAll('#produkBody tr, .kp-mobile-card');

    all.forEach(el => {
        const matchNama = el.dataset.nama.includes(q);
        const matchKat  = activeFilter === 'semua' || el.dataset.kategori === activeFilter;
        el.style.display = (matchNama && matchKat) ? '' : 'none';
    });
}
</script>