<?php

$id = $_SESSION['id'];
$nama = $_SESSION['nama'];

$total_umkm = mysqli_num_rows(
    mysqli_query($koneksi,"SELECT * FROM penjual")
);

$total_aktif = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT * FROM penjual WHERE status = 'aktif'")
);
 
$total_nonaktif = $total_umkm - $total_aktif;

?>

<div class="dashboard-wrapper">
    <div class="dashboard-inner">
 
        <!-- TOPBAR -->
        <div class="dashboard-topbar">
            <div>
                <h2 class="dashboard-title">Dashboard Admin</h2>
                <p class="dashboard-subtitle">Kelola seluruh UMKM dalam marketplace.</p>
            </div>
            <span class="badge-role">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <?= htmlspecialchars($nama) ?>
            </span>
        </div>
 
        <!-- STAT CARDS -->
        <div class="stat-grid">
 
            <div class="stat-card" style="--card-accent:#16a34a; --icon-bg:#dcfce7; --icon-color:#16a34a;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="stat-card-label">Total UMKM</div>
                <div class="stat-card-value"><?= $total_umkm ?></div>
                <div class="stat-card-desc">Terdaftar di platform</div>
            </div>
 
            <div class="stat-card" style="--card-accent:#0ea5e9; --icon-bg:#e0f2fe; --icon-color:#0ea5e9;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div class="stat-card-label">UMKM Aktif</div>
                <div class="stat-card-value"><?= $total_aktif ?></div>
                <div class="stat-card-desc">Sedang beroperasi</div>
            </div>
 
            <div class="stat-card" style="--card-accent:#f59e0b; --icon-bg:#fef3c7; --icon-color:#d97706;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div class="stat-card-label">UMKM Nonaktif</div>
                <div class="stat-card-value"><?= $total_nonaktif ?></div>
                <div class="stat-card-desc">Perlu tindakan</div>
            </div>
 
        </div>
 
        <!-- AKTIVITAS SISTEM -->
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <span class="card-title-dot"></span>
                    Aktivitas Sistem
                </div>
                <span class="content-card-badge">Akses Cepat</span>
            </div>
            <div class="content-card-body">
                <ul class="action-list">
 
                    <li>
                        <a class="action-item" href="index.php?page=admin/daftar-umkm" style="--action-icon-bg:#dcfce7; --action-icon-color:#16a34a;">
                            <div class="action-item-icon">
                                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                            </div>
                            <span class="action-item-text">Monitoring UMKM</span>
                            <span class="action-item-arrow">›</span>
                        </a>
                    </li>
 
                    <li>
                        <a class="action-item" href="index.php?page=admin/nonaktifkan-umkm" style="--action-icon-bg:#fef3c7; --action-icon-color:#d97706;">
                            <div class="action-item-icon">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            </div>
                            <span class="action-item-text">Nonaktifkan Akun Penjual</span>
                            <span class="action-item-arrow">›</span>
                        </a>
                    </li>
 
                </ul>
            </div>
        </div>
 
    </div>
</div>