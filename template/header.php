<?php
// Hitung jumlah item keranjang dari DB (bukan session)
$jumlah_keranjang = 0;
if (isset($_SESSION['login']) && $_SESSION['role'] === 'pembeli') {
    $id_pembeli = $_SESSION['id'];
    $q_cart = mysqli_query($koneksi, "SELECT SUM(qty) as total FROM keranjang WHERE id_pembeli='$id_pembeli'");
    $r_cart = mysqli_fetch_assoc($q_cart);
    $jumlah_keranjang = (int)($r_cart['total'] ?? 0);
}

$current_page = $_GET['page'] ?? 'home';
?>

<nav class="navbar-umkm">
    <div class="navbar-umkm-inner">

        <!-- BRAND -->
        <a href="index.php?page=home" class="navbar-brand-umkm">
            <div class="navbar-brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span class="navbar-brand-text">Go Digital <strong>UMKM</strong></span>
        </a>

        <!-- HAMBURGER (mobile) -->
        <button class="navbar-hamburger" id="navToggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>

        <!-- NAV LINKS -->
        <div class="navbar-menu" id="navMenu">

            <?php if (!isset($_SESSION['login'])): ?>

                <a href="index.php?page=home"   class="nav-link-umkm <?= $current_page === 'home'   ? 'active' : '' ?>">Home</a>
                <a href="index.php?page=produk" class="nav-link-umkm <?= $current_page === 'produk' ? 'active' : '' ?>">Produk</a>
                <a href="index.php?page=login"  class="nav-btn-umkm">Masuk / Daftar</a>

            <?php elseif ($_SESSION['role'] === 'admin'): ?>

                <a href="index.php?page=dashboard"              class="nav-link-umkm <?= $current_page === 'admin/dashboard'          ? 'active' : '' ?>">Dashboard</a>
                <a href="index.php?page=admin/daftar-umkm"      class="nav-link-umkm <?= $current_page === 'admin/daftar-umkm'        ? 'active' : '' ?>">Daftar UMKM</a>
                <a href="index.php?page=admin/nonaktifkan-umkm" class="nav-link-umkm <?= $current_page === 'admin/nonaktifkan-umkm'   ? 'active' : '' ?>">Nonaktifkan UMKM</a>
                <a href="logout.php" class="nav-link-umkm nav-link-danger">Logout</a>

            <?php elseif ($_SESSION['role'] === 'penjual'): ?>

                <a href="index.php?page=dashboard"                        class="nav-link-umkm <?= in_array($current_page, ['penjual/dashboard']) ? 'active' : '' ?>">Dashboard</a>
                <a href="index.php?page=products-admin"                   class="nav-link-umkm <?= $current_page === 'products-admin'                   ? 'active' : '' ?>">Kelola Produk</a>
                <a href="index.php?page=penjual/daftar-pesanan"           class="nav-link-umkm <?= $current_page === 'penjual/daftar-pesanan'           ? 'active' : '' ?>">Pesanan</a>
                <a href="index.php?page=penjual/verifikasi-pembayaran"    class="nav-link-umkm <?= $current_page === 'penjual/verifikasi-pembayaran'    ? 'active' : '' ?>">Pembayaran</a>
                <a href="index.php?page=penjual/status-pesanan"           class="nav-link-umkm <?= $current_page === 'penjual/status-pesanan'           ? 'active' : '' ?>">Status Pesanan</a>
                <a href="logout.php" class="nav-link-umkm nav-link-danger">Logout</a>

            <?php else: /* pembeli */ ?>

                <a href="index.php?page=dashboard"               class="nav-link-umkm <?= in_array($current_page, ['pembeli/dashboard']) ? 'active' : '' ?>">Dashboard</a>
                <a href="index.php?page=produk"                  class="nav-link-umkm <?= $current_page === 'produk'                    ? 'active' : '' ?>">Produk</a>
                <a href="index.php?page=pembeli/riwayat-pesanan" class="nav-link-umkm <?= $current_page === 'pembeli/riwayat-pesanan'   ? 'active' : '' ?>">Pesanan</a>

                <!-- KERANJANG dengan badge -->
                <a href="index.php?page=pembeli/keranjang" class="nav-cart-umkm <?= $current_page === 'pembeli/keranjang' ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                    </svg>
                    <?php if ($jumlah_keranjang > 0): ?>
                        <span class="cart-badge"><?= $jumlah_keranjang > 99 ? '99+' : $jumlah_keranjang ?></span>
                    <?php endif; ?>
                </a>

                <a href="logout.php" class="nav-link-umkm nav-link-danger">Logout</a>

            <?php endif; ?>

        </div>
    </div>
</nav>

<script>
document.getElementById('navToggle').addEventListener('click', function () {
    document.getElementById('navMenu').classList.toggle('open');
    this.classList.toggle('open');
});
</script>