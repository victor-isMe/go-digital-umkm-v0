<?php

$id_pembeli = $_SESSION['id'];
$nama = $_SESSION['nama'];

$total_produk = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT * FROM produk")
);

$total_pesanan = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pembeli = '$id_pembeli'")
);

?>

<style>
/* ===== DASHBOARD PEMBELI ===== */
.dashboard-wrapper {
    font-family: 'Poppins', sans-serif;
    background: #f1f5f9;
    min-height: 100vh;
}

.dashboard-inner {
    padding: 28px 32px;
}

.dashboard-topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
}

.dashboard-title {
    font-size: 22px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px;
    line-height: 1.3;
}

.dashboard-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

.badge-role {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #dcfce7;
    color: #15803d;
    font-size: 11px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    border: 1px solid #bbf7d0;
    white-space: nowrap;
}

/* STAT CARDS */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
    transition: transform 0.15s, box-shadow 0.15s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 4px; height: 100%;
    background: var(--card-accent, #16a34a);
    border-radius: 0 2px 2px 0;
}

.stat-card-icon {
    position: absolute;
    top: 18px; right: 18px;
    width: 38px; height: 38px;
    border-radius: 10px;
    background: var(--icon-bg, #dcfce7);
    display: flex; align-items: center; justify-content: center;
}

.stat-card-icon svg {
    width: 18px; height: 18px;
    stroke: var(--icon-color, #16a34a);
    fill: none; stroke-width: 2;
    stroke-linecap: round; stroke-linejoin: round;
}

.stat-card-label {
    font-size: 12px; font-weight: 500;
    color: #64748b; margin-bottom: 8px;
}

.stat-card-value {
    font-size: 30px; font-weight: 700;
    color: #1e293b; line-height: 1; margin-bottom: 6px;
}

.stat-card-desc {
    font-size: 11px; color: #94a3b8;
}

/* CONTENT CARD */
.content-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 20px;
}

.content-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
}

.content-card-title {
    font-size: 14px; font-weight: 600; color: #1e293b;
    display: flex; align-items: center; gap: 8px;
}

.content-card-title .card-title-dot {
    width: 8px; height: 8px;
    border-radius: 50%; background: #22c55e;
}

.content-card-badge {
    font-size: 10px; color: #94a3b8;
    background: #f8fafc; padding: 3px 8px;
    border-radius: 10px; border: 1px solid #e2e8f0;
}

.content-card-body {
    padding: 16px 20px;
}

/* HERO BELANJA */
.shop-hero {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 12px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #bbf7d0;
}

.shop-hero-text h3 {
    font-size: 15px;
    font-weight: 600;
    color: #15803d;
    margin: 0 0 4px;
}

.shop-hero-text p {
    font-size: 12px;
    color: #4ade80;
    margin: 0;
}

.action-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 10px;
    font-size: 13px; font-weight: 500;
    text-decoration: none; cursor: pointer;
    transition: all 0.15s; border: none;
    font-family: 'Poppins', sans-serif;
}

.action-btn-primary {
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
    box-shadow: 0 2px 8px rgba(22,163,74,0.25);
}

.action-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(22,163,74,0.35);
    color: #fff;
}

.action-btn svg {
    width: 15px; height: 15px;
    stroke: currentColor; fill: none;
    stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
}

/* ACTION LIST */
.action-list {
    list-style: none; padding: 0; margin: 0;
    display: flex; flex-direction: column; gap: 10px;
}

.action-item {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 10px; border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s, transform 0.1s;
}

.action-item:hover {
    background: #f0fdf4; border-color: #bbf7d0;
    transform: translateX(3px);
}

.action-item-icon {
    width: 34px; height: 34px; border-radius: 8px;
    background: var(--action-icon-bg, #dcfce7);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

.action-item-icon svg {
    width: 16px; height: 16px;
    stroke: var(--action-icon-color, #16a34a);
    fill: none; stroke-width: 2;
    stroke-linecap: round; stroke-linejoin: round;
}

.action-item-text {
    font-size: 13px; color: #374151; font-weight: 500; flex: 1;
}

.action-item-arrow {
    color: #94a3b8; font-size: 16px; line-height: 1;
}
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-inner">

        <!-- TOPBAR -->
        <div class="dashboard-topbar">
            <div>
                <h2 class="dashboard-title">Dashboard Pembeli</h2>
                <p class="dashboard-subtitle">Selamat datang kembali!</p>
            </div>
            <span class="badge-role">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <?= htmlspecialchars($nama) ?>
            </span>
        </div>

        <!-- STAT CARDS -->
        <div class="stat-grid">

            <div class="stat-card" style="--card-accent:#16a34a; --icon-bg:#dcfce7; --icon-color:#16a34a;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                </div>
                <div class="stat-card-label">Produk Tersedia</div>
                <div class="stat-card-value"><?= $total_produk ?></div>
                <div class="stat-card-desc">Siap untuk dibeli</div>
            </div>

            <div class="stat-card" style="--card-accent:#0ea5e9; --icon-bg:#e0f2fe; --icon-color:#0ea5e9;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
                </div>
                <div class="stat-card-label">Riwayat Pesanan</div>
                <div class="stat-card-value"><?= $total_pesanan ?></div>
                <div class="stat-card-desc">Total pesanan kamu</div>
            </div>

            <div class="stat-card" style="--card-accent:#8b5cf6; --icon-bg:#ede9fe; --icon-color:#7c3aed;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                </div>
                <div class="stat-card-label">Keranjang</div>
                <div class="stat-card-value"><?php $jumlah = 0; if (isset($_SESSION['cart'])) {$jumlah = array_sum($_SESSION['cart']);} ?><?= $jumlah ?></div>
                <div class="stat-card-desc">Item di keranjang</div>
            </div>

        </div>

        <!-- MULAI BELANJA -->
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <span class="card-title-dot"></span>
                    Mulai Belanja
                </div>
            </div>
            <div class="content-card-body">
                <div class="shop-hero">
                    <div class="shop-hero-text">
                        <h3>Temukan produk UMKM favoritmu</h3>
                        <p>Ribuan produk lokal menunggumu</p>
                    </div>
                    <a href="index.php?page=produk" class="action-btn action-btn-primary">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Jelajahi Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- MENU PEMBELI -->
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <span class="card-title-dot"></span>
                    Menu Pembeli
                </div>
                <span class="content-card-badge">Akses Cepat</span>
            </div>
            <div class="content-card-body">
                <ul class="action-list">
                    <li>
                        <a class="action-item" href="index.php?page=pembeli/riwayat-pesanan" style="--action-icon-bg:#dcfce7; --action-icon-color:#16a34a;">
                            <div class="action-item-icon">
                                <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                            </div>
                            <span class="action-item-text">Riwayat Pesanan</span>
                            <span class="action-item-arrow">›</span>
                        </a>
                    </li>
                    <li>
                        <a class="action-item" href="index.php?page=pembeli/keranjang" style="--action-icon-bg:#ede9fe; --action-icon-color:#7c3aed;">
                            <div class="action-item-icon">
                                <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                            </div>
                            <span class="action-item-text">Keranjang Belanja</span>
                            <span class="action-item-arrow">›</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>