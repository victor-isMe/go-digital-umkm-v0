<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<div class="sidebar-dashboard">
 
    <div class="sidebar-brand">
        <div class="sidebar-brand-inner">
            <div class="sidebar-brand-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="sidebar-brand-text">
                <h4>GO DIGITAL UMKM</h4>
                <span>Marketplace Platform</span>
            </div>
        </div>
    </div>
 
    <?php $currentPage = $_GET['page'] ?? ''; ?>
 
    <div class="sidebar-nav-section">
        <span class="sidebar-nav-label">Menu</span>
 
        <ul class="nav flex-column">
 
            <?php if ($_SESSION['role'] == 'admin'): ?>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></span>
                        Dashboard
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'admin/daftar-umkm' ? 'active' : '' ?>" href="index.php?page=admin/daftar-umkm">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></span>
                        Daftar UMKM
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'admin/nonaktifkan-umkm' ? 'active' : '' ?>" href="index.php?page=admin/nonaktifkan-umkm">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></span>
                        Nonaktifkan UMKM
                    </a>
                </li>
 
                <div class="sidebar-logout">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">
                            <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
                            Logout
                        </a>
                    </li>
                </div>
 
            <?php elseif ($_SESSION['role'] == 'penjual'): ?>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></span>
                        Dashboard
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'products-admin' ? 'active' : '' ?>" href="index.php?page=products-admin">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></span>
                        Kelola Produk
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'penjual/daftar-pesanan' ? 'active' : '' ?>" href="index.php?page=penjual/daftar-pesanan">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg></span>
                        Daftar Pesanan
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'pembayaran' ? 'active' : '' ?>" href="index.php?page=pembayaran">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></span>
                        Pembayaran
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'penjual/status-pesanan' ? 'active' : '' ?>" href="index.php?page=penjual/status-pesanan">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg></span>
                        Kirim Status Pesanan
                    </a>
                </li>
 
                <div class="sidebar-logout">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">
                            <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
                            Logout
                        </a>
                    </li>
                </div>
 
            <?php else: ?>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></span>
                        Dashboard
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'produk' ? 'active' : '' ?>" href="index.php?page=produk">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></span>
                        Produk
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'pembeli/riwayat-pesanan' ? 'active' : '' ?>" href="index.php?page=pembeli/riwayat-pesanan">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
                        Riwayat Pesanan
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'pembeli/keranjang' ? 'active' : '' ?>" href="index.php?page=pembeli/keranjang">
                        <span class="nav-icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg></span>
                        Keranjang
                    </a>
                </li>
 
                <div class="sidebar-logout">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">
                            <span class="nav-icon"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
                            Logout
                        </a>
                    </li>
                </div>
 
            <?php endif; ?>
 
        </ul>
    </div>
 
</div>