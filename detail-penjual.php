<?php

// ── Validasi parameter ────────────────────────────────────────
$id_penjual = (int) ($_GET['id'] ?? 0);

if ($id_penjual <= 0) {
    header("Location: index.php?page=dashboard");
    exit;
}

// ── Query data penjual ────────────────────────────────────────
$q_penjual = mysqli_query($koneksi, "
    SELECT pj.*,
           COUNT(DISTINCT p.id_produk)  AS total_produk,
           COUNT(DISTINCT ps.id_pesanan) AS total_pesanan
    FROM penjual pj
    LEFT JOIN produk  p  ON p.id_penjual  = pj.id_penjual
    LEFT JOIN pesanan ps ON ps.id_penjual = pj.id_penjual
                        AND ps.status     = 'selesai'
    WHERE pj.id_penjual = '$id_penjual'
    GROUP BY pj.id_penjual
");

$penjual = mysqli_fetch_assoc($q_penjual);

if (!$penjual) {
    header("Location: index.php?page=dashboard");
    exit;
}

// ── Query produk toko (tampilkan semua, stok > 0 duluan) ─────
$q_produk = mysqli_query($koneksi, "
    SELECT * FROM produk
    WHERE id_penjual = '$id_penjual'
    ORDER BY stok DESC, id_produk DESC
");

$produk_list = [];
while ($r = mysqli_fetch_assoc($q_produk)) {
    $produk_list[] = $r;
}

// ── Query metode pembayaran ───────────────────────────────────
$q_metode = mysqli_query($koneksi, "
    SELECT * FROM metode_pembayaran
    WHERE id_seller = '$id_penjual'
    ORDER BY metode, provider
");

$metode_list = [];
while ($r = mysqli_fetch_assoc($q_metode)) {
    $metode_list[] = $r;
}

// ── Helper ────────────────────────────────────────────────────
$nama_toko   = htmlspecialchars($penjual['nama_toko']     ?? '');
$nama_pemilik= htmlspecialchars($penjual['nama_penjual']  ?? '');
$alamat      = htmlspecialchars($penjual['alamat']        ?? '-');
$phone       = htmlspecialchars($penjual['phone']         ?? '-');
$email       = htmlspecialchars($penjual['email_penjual'] ?? '-');
$status      = $penjual['status'] ?? 'nonaktif';

// $bergabung   = !empty($penjual['created_at'])
//                ? date('F Y', strtotime($penjual['created_at']))
//                : '-';

// Back URL: sesuaikan dengan role yang sedang login
$back_url = match($_SESSION['role'] ?? '') {
    'admin'  => 'index.php?page=admin/daftar-umkm',
    default  => 'javascript:history.back()'
};
?>

<div class="dp-wrap">

    <!-- ── HERO ──────────────────────────────────────────────── -->
    <div class="dp-hero">
        <a href="<?= $back_url ?>" class="dp-back">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>

        <div class="dp-hero-inner">
            <div class="dp-hero-info">
                <div class="dp-toko-name"><?= $nama_toko ?></div>
                <div class="dp-owner">👤 <?= $nama_pemilik ?> · Penjual UMKM</div>
                <div class="dp-badges">
                    <span class="dp-badge">
                        <?= $status === 'aktif' ? '✅ Aktif' : '🔴 Nonaktif' ?>
                    </span>
                    <span class="dp-badge">📦 <?= $penjual['total_produk'] ?> Produk</span>
                    <?php if (!empty($penjual['alamat'])): ?>
                        <span class="dp-badge">📍 <?= htmlspecialchars($penjual['alamat']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="dp-page">

        <!-- ── STAT CARDS ─────────────────────────────────────── -->
        <div class="dp-stats">
            <div class="dp-stat">
                <div class="dp-stat-val"><?= $penjual['total_produk'] ?></div>
                <div class="dp-stat-label">Total Produk</div>
            </div>
            <div class="dp-stat">
                <div class="dp-stat-val"><?= $penjual['total_pesanan'] ?></div>
                <div class="dp-stat-label">Pesanan Selesai</div>
            </div>
            <!-- <div class="dp-stat">
                <div class="dp-stat-val"><?= $bergabung ?></div>
                <div class="dp-stat-label">Bergabung</div>
            </div> -->
        </div>

        <div class="dp-grid">

            <!-- ── KOLOM KIRI: Produk ─────────────────────────── -->
            <div>
                <div class="dp-card">
                    <div class="dp-card-header">
                        <div class="dp-card-icon">🛍</div>
                        <span class="dp-card-title">
                            Produk Toko
                            <span style="font-weight:400;color:#94a3b8;font-size:12px;">
                                (<?= count($produk_list) ?>)
                            </span>
                        </span>
                    </div>
                    <div class="dp-card-body">
                        <?php if (empty($produk_list)): ?>
                            <div class="dp-empty">
                                Toko ini belum memiliki produk
                            </div>
                        <?php else: ?>
                        <div class="dp-produk-grid">
                            <?php foreach ($produk_list as $p):
                                $habis    = ((int)$p['stok']) <= 0;
                                $foto     = htmlspecialchars($p['foto'] ?? '');
                                $p_nama   = htmlspecialchars($p['nama']);
                                $p_harga  = 'Rp ' . number_format($p['harga'], 0, ',', '.');
                            ?>
                            <a href="index.php?page=produk/detail&id=<?= $p['id_produk'] ?>"
                               class="dp-produk-card <?= $habis ? 'habis' : '' ?>">

                                <?php if ($foto): ?>
                                    <img class="dp-produk-img"
                                         src="<?= $foto ?>"
                                         alt="<?= $p_nama ?>"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <div class="dp-produk-img-placeholder" style="display:none">🛒</div>
                                <?php else: ?>
                                    <div class="dp-produk-img-placeholder">🛒</div>
                                <?php endif; ?>

                                <div class="dp-produk-info">
                                    <div class="dp-produk-nama"><?= $p_nama ?></div>
                                    <div class="dp-produk-harga"><?= $p_harga ?></div>
                                    <?php if ($habis): ?>
                                        <div class="dp-habis-tag">STOK HABIS</div>
                                    <?php else: ?>
                                        <div class="dp-produk-stok">Stok: <?= $p['stok'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ── KOLOM KANAN: Info & Metode ────────────────── -->
            <div class="dp-col-right">

                <!-- INFO TOKO -->
                <div class="dp-card">
                    <div class="dp-card-header">
                        <div class="dp-card-icon">ℹ️</div>
                        <span class="dp-card-title">Info Toko</span>
                    </div>
                    <div class="dp-card-body">
                        <div class="dp-info-row">
                            <span class="dp-info-label">Pemilik</span>
                            <span class="dp-info-val"><?= $nama_pemilik ?></span>
                        </div>
                        <div class="dp-info-row">
                            <span class="dp-info-label">Alamat</span>
                            <span class="dp-info-val"><?= $alamat ?></span>
                        </div>
                        <div class="dp-info-row">
                            <span class="dp-info-label">No. Telp</span>
                            <span class="dp-info-val">
                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phone) ?>"
                                   style="color:#16a34a;text-decoration:none">
                                    <?= $phone ?>
                                </a>
                            </span>
                        </div>
                        <div class="dp-info-row">
                            <span class="dp-info-label">Email</span>
                            <span class="dp-info-val" style="font-size:12px"><?= $email ?></span>
                        </div>
                        <!-- <div class="dp-info-row">
                            <span class="dp-info-label">Bergabung</span>
                            <span class="dp-info-val"><?= $bergabung ?></span>
                        </div> -->
                    </div>
                </div>

                <!-- METODE PEMBAYARAN -->
                <div class="dp-card">
                    <div class="dp-card-header">
                        <div class="dp-card-icon">💳</div>
                        <span class="dp-card-title">Metode Pembayaran</span>
                    </div>
                    <div class="dp-card-body">
                        <?php if (empty($metode_list)): ?>
                            <div class="dp-empty" style="padding:12px">
                                Belum ada metode pembayaran
                            </div>
                        <?php else: ?>
                            <?php foreach ($metode_list as $m):
                                $icon = $m['metode'] === 'bank' ? '🏦' : '📱';
                            ?>
                            <div class="dp-metode-item">
                                <span class="dp-metode-icon"><?= $icon ?></span>
                                <div>
                                    <div class="dp-metode-provider">
                                        <?= htmlspecialchars($m['provider']) ?>
                                    </div>
                                    <div class="dp-metode-detail">
                                        <?= htmlspecialchars($m['nomor_akun']) ?>
                                        · a/n <?= htmlspecialchars($m['nama_akun']) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>