<footer class="footer-umkm">
    <div class="footer-inner">

        <!-- BRAND -->
        <div class="footer-brand">
            <div class="footer-brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
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