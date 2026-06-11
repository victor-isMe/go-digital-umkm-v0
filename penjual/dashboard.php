<?php

$id_penjual = $_SESSION['id'];

$total_produk = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT * FROM produk WHERE id_penjual = '$id_penjual'")
);

$total_pesanan = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_penjual = '$id_penjual'")
);

$total_pending = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_penjual = '$id_penjual' AND status = 'diproses'")
);

?>

<style>
/* ===== DASHBOARD PENJUAL ===== */
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

.dashboard-subtitle span {
    color: #15803d;
    font-weight: 500;
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

/* ACTION BUTTONS */
.action-btn-group {
    display: flex; gap: 10px; flex-wrap: wrap;
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

.action-btn-outline {
    background: #fff; color: #15803d;
    border: 1px solid #bbf7d0;
}

.action-btn-outline:hover {
    background: #f0fdf4; color: #15803d;
    border-color: #86efac;
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
                <h2 class="dashboard-title">Dashboard Penjual</h2>
                <p class="dashboard-subtitle">Selamat datang kembali, <span><?= htmlspecialchars($_SESSION['toko']) ?></span> 👋</p>
            </div>
            <span class="badge-role">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                Penjual
            </span>
        </div>

        <!-- STAT CARDS -->
        <div class="stat-grid">

            <div class="stat-card" style="--card-accent:#16a34a; --icon-bg:#dcfce7; --icon-color:#16a34a;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                </div>
                <div class="stat-card-label">Total Produk</div>
                <div class="stat-card-value"><?= $total_produk ?></div>
                <div class="stat-card-desc">Produk kamu di toko</div>
            </div>

            <div class="stat-card" style="--card-accent:#0ea5e9; --icon-bg:#e0f2fe; --icon-color:#0ea5e9;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
                </div>
                <div class="stat-card-label">Total Pesanan</div>
                <div class="stat-card-value"><?= $total_pesanan ?></div>
                <div class="stat-card-desc">Semua pesanan masuk</div>
            </div>

            <div class="stat-card" style="--card-accent:#f59e0b; --icon-bg:#fef3c7; --icon-color:#d97706;">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div class="stat-card-label">Pesanan Pending</div>
                <div class="stat-card-value"><?= $total_pending ?></div>
                <div class="stat-card-desc">Menunggu diproses</div>
            </div>

        </div>

        <!-- AKSI CEPAT -->
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <span class="card-title-dot"></span>
                    Aksi Cepat
                </div>
                <span class="content-card-badge">Pintasan</span>
            </div>
            <div class="content-card-body">
                <div class="action-btn-group">
                    <a href="index.php?page=form" class="action-btn action-btn-primary">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Produk
                    </a>
                    <a href="index.php?page=products-admin" class="action-btn action-btn-outline">
                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Kelola Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- MENU NAVIGASI -->
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <span class="card-title-dot"></span>
                    Menu Penjual
                </div>
                <span class="content-card-badge">Akses Cepat</span>
            </div>
            <div class="content-card-body">
                <ul class="action-list">
                    <li>
                        <a class="action-item" href="index.php?page=penjual/daftar-pesanan" style="--action-icon-bg:#dcfce7; --action-icon-color:#16a34a;">
                            <div class="action-item-icon">
                                <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                            </div>
                            <span class="action-item-text">Daftar Pesanan</span>
                            <span class="action-item-arrow">›</span>
                        </a>
                    </li>
                    <li>
                        <a class="action-item" href="index.php?page=pembayaran" style="--action-icon-bg:#e0f2fe; --action-icon-color:#0ea5e9;">
                            <div class="action-item-icon">
                                <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <span class="action-item-text">Pembayaran</span>
                            <span class="action-item-arrow">›</span>
                        </a>
                    </li>
                    <li>
                        <a class="action-item" href="index.php?page=penjual/status-pesanan" style="--action-icon-bg:#fef3c7; --action-icon-color:#d97706;">
                            <div class="action-item-icon">
                                <svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                            </div>
                            <span class="action-item-text">Kirim Status Pesanan</span>
                            <span class="action-item-arrow">›</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>