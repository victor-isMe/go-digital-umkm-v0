<footer class="footer-umkm">
    <div class="footer-inner">

        <!-- BRAND -->
        <div class="footer-brand">
            <div>
                <div class="footer-brand-name">Go Digital UMKM</div>
                <div class="footer-brand-tagline">Platform marketplace untuk UMKM Indonesia</div>
            </div>
        </div>

        <!-- LINKS -->
        <div class="footer-links">
            <a href="index.php?page=home">Home</a>
            <a href="index.php?page=produk">Produk</a>
            <?php if (!isset($_SESSION['login'])): ?>
                <a href="index.php?page=login">Masuk</a>
            <?php endif; ?>
        </div>

        <!-- COPYRIGHT -->
        <div class="footer-copy">
            © <?= date('Y') ?> Go Digital UMKM. All rights reserved.
        </div>

    </div>
</footer>