<?php
// ============================================================
// DUMMY DATA - Ganti dengan query database sesuai kebutuhan
// ============================================================

$kategori_populer = [
    [
        'nama' => 'makanan',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#16a34a" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 2v7c0 1.657 1.343 3 3 3s3-1.343 3-3V2"/><line x1="6" y1="2" x2="6" y2="22"/><path d="M21 2s-2 2-2 5 2 5 2 5v10"/><path d="M19 2v20"/></svg>',
    ],
    [
        'nama' => 'fashion',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#16a34a" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>',
    ],
    [
        'nama' => 'kerajinan',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#16a34a" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 11V6a2 2 0 00-2-2 2 2 0 00-2 2"/><path d="M14 10V4a2 2 0 00-2-2 2 2 0 00-2 2v2"/><path d="M10 11V6a2 2 0 00-2-2 2 2 0 00-2 2v8"/><path d="M18 8a2 2 0 114 0v6a8 8 0 01-16 0v-5"/></svg>',
    ],
];

$query = mysqli_query($koneksi, "SELECT id_produk, nama, harga, foto FROM produk LIMIT 6");
$produk_unggulan = [];
while ($row = mysqli_fetch_assoc($query)) {
    $produk_unggulan[] = $row;
}

?>

<!-- ============================================================
     HERO SECTION
============================================================ -->
<section class="home-hero">
    <div class="home-hero-inner">

        <div class="home-hero-text">
            <h1>
                Dukung UMKM Lokal
                <br>
                <span>Wujudkan Ekonomi Berkelanjutan</span>
            </h1>
            <p>
                Temukan berbagai produk berkualitas dari UMKM Indonesia
                dan dukung pertumbuhan ekonomi lokal.
            </p>
            <div class="home-hero-btn">
                <a href="index.php?page=produk" class="btn-hero-primary">
                    Jelajahi Produk
                </a>
                <a href="index.php?page=login" class="btn-hero-outline">
                    Masuk Sekarang
                </a>
            </div>
        </div>

        <div class="home-hero-visual">
            <img src="img/dimsum.jpg" alt="Dimsum" class="hv-dimsum" width="300">
            <img src="img/baju.jpg" alt="Baju" class="hv-baju" width="400">
            <img src="img/tasrotan.jpg" alt="Tas Rotan" class="hv-tas" width="300">
            <img src="img/bunga_hias.jpg" alt="Bunga Hias" class="hv-bunga" width="250">

            <!-- <div class="hv-badge">
                <span class="hv-badge-icon">👥</span>
                <div>
                    <div class="hv-badge-number">10.000+</div>
                    <div class="hv-badge-label">UMKM Bergabung</div>
                </div>
            </div> -->
        </div>

    </div>

    <!-- FEATURE STRIP -->
    <div class="home-features">
        <div class="feature-item">
            <div class="feature-icon">🏷️</div>
            <div>
                <div class="feature-title">Produk Berkualitas</div>
                <div class="feature-desc">Kualitas terbaik dari UMKM pilihan</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">🛡️</div>
            <div>
                <div class="feature-title">Aman &amp; Terpercaya</div>
                <div class="feature-desc">Transaksi aman dengan sistem terpercaya</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">🚚</div>
            <div>
                <div class="feature-title">Pengiriman Cepat</div>
                <div class="feature-desc">Dikirim langsung oleh pemilik UMKM</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">🍃</div>
            <div>
                <div class="feature-title">Dukung Lokal</div>
                <div class="feature-desc">Bersama membangun ekonomi Lokal</div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     KATEGORI POPULER
============================================================ -->
<section class="home-section">
    <div class="home-section-header">
        <h2>Kategori Populer</h2>
    </div>

    <div class="kategori-grid">
        <?php foreach ($kategori_populer as $kat): ?>
            <a href="index.php?page=produk&kategori=<?= urlencode($kat['nama']) ?>" class="kategori-card">
                <span class="kategori-icon"><?= $kat['icon'] ?></span>
                <span class="kategori-nama"><?= htmlspecialchars($kat['nama']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ============================================================
     PRODUK UNGGULAN
============================================================ -->
<section class="home-section">
    <div class="home-section-header">
        <h2>Produk Unggulan</h2>
        <a href="index.php?page=produk" class="lihat-semua">Lihat Semua →</a>
    </div>
 
    <div class="produk-grid">
        <?php foreach ($produk_unggulan as $produk): ?>
            <a href="index.php?page=produk/detail&id=<?= $produk['id_produk'] ?>" class="produk-card">
                <div class="produk-img-wrap">
                    <img src="<?= htmlspecialchars($produk['foto']) ?>" alt="<?= htmlspecialchars($produk['nama']) ?>">
                </div>
                <div class="produk-info">
                    <div class="produk-nama"><?= htmlspecialchars($produk['nama']) ?></div>
                    <div class="produk-bottom">
                        <span class="produk-harga">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>