<nav class="navbar sticky-top shadow navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">Go Digital UMKM</a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            
            <ul class="navbar-nav justify-content-center text-center ms-auto">
                <?php if (!isset($_SESSION['login'])): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=produk">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=login">Login/Register</a></li>
                <?php else: ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=admin/daftar-umkm">Daftar UMKMK</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=admin/nonaktifkan-umkm">Nonaktifkan UMKM</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                    <?php elseif ($_SESSION['role'] == 'penjual'): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=products-admin">Kelola Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=daftar-pesanan">Daftar Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=pembayaran">Pembayaran</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=status-pesanan">Kirim Status Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=produk">Produk</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=riwayat-pesanan">Riwayat Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=pembeli/keranjang">Keranjang<?php $jumlah = 0; if (isset($_SESSION['cart'])) {$jumlah = array_sum($_SESSION['cart']);} ?>(<?= $jumlah ?>)</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                    <?php endif; ?>                    
                <?php endif; ?>    
            </ul>
        </div>
    </div>
</nav>