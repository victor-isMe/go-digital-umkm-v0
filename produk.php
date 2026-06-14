<?php
$per_page = 12;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman = max(1, $halaman);
$offset = ($halaman - 1) * $per_page;

//Filter Kategori
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : 'all';
$where = '';
if ($kategori_filter !== 'all') {
    $kategori = mysqli_real_escape_string($koneksi, $kategori_filter);
    $where = "WHERE kategori='$kategori'";
}

//Hitung total produk
$total_result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk $where");
$total_row = mysqli_fetch_assoc($total_result);
$total_produk = $total_row['total'];
$total_halaman = ceil($total_produk/ $per_page);

//Ambil produk sesuai halaman
$result = mysqli_query($koneksi, "SELECT * FROM produk $where LIMIT $per_page OFFSET $offset");

//URL helper untuk pagination
function pagination_url($h, $kat) {
    $params = ['page' => 'produk', 'halaman' => $h];
    if ($kat !== 'all') $params['kategori'] = $kat;
    return 'index.php?' . http_build_query($params);
}

function filter_url($kat) {
    return 'index.php?' . http_build_query(['page' => 'produk', 'kategori' => $kat]);
}
?>
    <div class="produk-header">
        <h1 class="text-center mt-4 fs-1">
            Produk UMKM
        </h1>
        <p class="text-center mt-4 fs-4">Temukan produk lokal terbaikmu</p>
    </div>
    <div class="product-container">
        <div class="sidebar">
            <h3>Kategori</h3>
            <a href="<?= filter_url('all') ?>" class="button">Semua</a>
            <a href="<?= filter_url('makanan') ?>" class="button">Makanan</a>
            <a href="<?= filter_url('kerajinan') ?>" class="button">Kerajinan</a>
            <a href="<?= filter_url('fashion') ?>" class="button">Fashion</a>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            <?php if (mysqli_num_rows($result) == 0): ?>
                <div>
                    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    <p>Tidak ada produk yang ditemukan.</p>
                </div>
            <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <div class="col">
                    <div class="card bg-white p-3 rounded-4 shadow-sm border-0 card-hover">
                        <img class="w-100 rounded-3 mb-2" src="<?= $row['foto']; ?>" alt="<?= $row['nama']; ?>" loading="lazy">

                        <h6 class="mt-2 mb-2"><?= $row['nama']; ?></h6>
                        <p class="text-success fw-semibold mb-0">Rp <?= number_format($row['harga'], 0, ',','.'); ?></p>

                        <div class="row g-1">
                            <div class="col-9">
                                <a href="index.php?page=pembeli/checkout" class="btn btn-primary w-100">Beli</a>
                            </div>
                            <div class="col-3">
                                <a href="index.php?page=produk&add_cart=<?= $row['id_produk'] ?>" class="btn btn-primary w-100">🛒</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <div>
        <?php if ($total_halaman > 1): ?>
            <div class="page-info">Halaman <?= $halaman ?> dari <?= $total_halaman ?></div>
            <div class="pagination-wrapper">
                <!-- TOMBOL PREV -->
                <a href="<?= pagination_url($halaman - 1, $kategori_filter) ?>" class="page-btn <?= $halaman <= 1 ? 'disabled' : ''?>">
                    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                </a>

                <?php
                $range = 2;
                for ($i = 1;$i <= $total_halaman;$i++):
                    if (
                        $i == 1 || 
                        $i == $total_halaman || 
                        ($i >= $halaman - $range && $i <= $halaman + $range)
                        ):
                ?>
                    <a href="<?= pagination_url($i, $kategori_filter) ?>" class="page-btn">
                        <?= $i ?>
                    </a>
                <?php 
                    elseif (
                            $i == $halaman - $range - 1 || 
                            $i == $halaman + $range + 1): 
                ?>
                    <span class="page-ellipsis"> ... </span>
                <?php 
                    endif; 
                    endfor;
                ?>

                <!-- TOMBOL NEXT -->
                <a href="<?= pagination_url($halaman + 1, $kategori_filter) ?>" class="page-btn <?= $halaman >= $total_halaman ? 'disabled' : '' ?>">
                    <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <script>

        function toggleMenu() {
            const menu = document.getElementById("navMenu");
            menu.classList.toggle("active");
        }

    </script>