<div class="sidebar-dashboard">
    <div class="sidebar-brand">
        <h4>GO DIGITAL UMKM</h4>
    </div>

    <?php $currentPage = $_GET['page'] ?? ''; ?>

    <ul class="nav flex-column">
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=admin/daftar-umkm">Daftar UMKM</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=admin/nonaktifkan-umkm">Nonaktifkan UMKM</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
        <?php elseif ($_SESSION['role'] == 'penjual'): ?>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=products-admin">Kelola Produk</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=penjual/daftar-pesanan">Daftar Pesanan</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=pembayaran">Pembayaran</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=penjual/status-pesanan">Kirim Status Pesanan</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=produk">Produk</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=pembeli/riwayat-pesanan">Riwayat Pesanan</a></li>
            <li class="nav-item"><a class="nav-link <?= $currentPage == $page ? 'active' : '' ?>" href="index.php?page=pembeli/keranjang">Keranjang</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
        <?php endif; ?>                    
    </ul>
</div>