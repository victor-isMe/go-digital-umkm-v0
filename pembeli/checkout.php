<?php
// ============================================================
// CHECKOUT.PHP — Checkout per toko
// Menerima parameter:
//   ?toko=<id_penjual>
//   &data=<base64(json({id_produk: qty, ...}))>
// Atau dari Buy Now:
//   ?buy_now=<id_produk>
// ============================================================

if (!isset($_SESSION['login'])) {
    header("Location: index.php?page=login");
    exit;
}

$id_pembeli = $_SESSION['id'];
$nama       = $_SESSION['nama']   ?? '';
$alamat     = $_SESSION['alamat'] ?? '';

// ---- Tentukan sumber data (buy_now atau checkout per toko) ----
$is_buy_now = false;
$checkout_items = []; // [id_produk => qty]

if (isset($_GET['buy_now'])) {
    // Beli langsung dari halaman produk
    $is_buy_now = true;
    $id_produk  = (int)$_GET['buy_now'];
    $checkout_items[$id_produk] = 1;
    $_SESSION['buy_now'] = $checkout_items;

} elseif (isset($_GET['data'])) {
    // Checkout dari keranjang (per toko)
    $decoded = json_decode(base64_decode(urldecode($_GET['data'])), true);
    if (is_array($decoded)) {
        foreach ($decoded as $id => $qty) {
            $checkout_items[(int)$id] = (int)$qty;
        }
    }
    // Simpan ke session agar tetap tersedia saat POST
    $_SESSION['checkout_items'] = $checkout_items;
    $_SESSION['checkout_toko']  = (int)($_GET['toko'] ?? 0);

} elseif (isset($_SESSION['buy_now'])) {
    $is_buy_now     = true;
    $checkout_items = $_SESSION['buy_now'];

} elseif (isset($_SESSION['checkout_items'])) {
    $checkout_items = $_SESSION['checkout_items'];

} else {
    // Tidak ada data checkout — redirect ke keranjang
    header("Location: index.php?page=pembeli/keranjang");
    exit;
}

// ---- Ambil info produk & validasi stok ----
$id_toko    = $_SESSION['checkout_toko'] ?? (int)($_GET['toko'] ?? 0);
$items      = [];
$total      = 0;
$nama_toko  = '';
$error_stok = [];

foreach ($checkout_items as $id => $qty) {
    $id = (int)$id; $qty = (int)$qty;
    $q  = mysqli_query($koneksi, "
        SELECT p.*, pj.nama_toko, pj.id_penjual
        FROM produk p
        JOIN penjual pj ON p.id_penjual = pj.id_penjual
        WHERE p.id_produk = '$id'
    ");
    $produk = mysqli_fetch_assoc($q);

    if (!$produk) continue;

    if (empty($nama_toko)) $nama_toko = $produk['nama_toko'];

    if ($qty > $produk['stok']) {
        if ($produk['stok'] <= 0) {
            $error_stok[] = "{$produk['nama']} sudah habis.";
            continue;
        }
        $qty = $produk['stok']; // batasi ke stok tersedia
    }

    $subtotal = $produk['harga'] * $qty;
    $total   += $subtotal;

    $items[] = [
        'id_produk'  => $id,
        'nama'       => $produk['nama'],
        'foto'       => $produk['foto'],
        'harga'      => $produk['harga'],
        'qty'        => $qty,
        'subtotal'   => $subtotal,
        'id_penjual' => $produk['id_penjual'],
    ];
}

// ---- Proses checkout saat form di-submit ----
if (isset($_POST['checkout'])) {
    $nama_post  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat_post= mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $bayar      = mysqli_real_escape_string($koneksi, $_POST['bayar']);
    $catatan    = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');

    if (empty($items)) {
        $error_stok[] = "Tidak ada produk yang bisa diproses.";
    } else {
        // Kelompokkan per penjual (untuk buy_now mungkin sudah 1 toko,
        // tapi tetap kita grup agar kodenya konsisten)
        $grup = [];
        foreach ($items as $item) {
            $grup[$item['id_penjual']][] = $item;
        }

        foreach ($grup as $seller_id => $seller_items) {
            $total_seller = array_sum(array_column($seller_items, 'subtotal'));

            mysqli_query($koneksi, "INSERT INTO pesanan
                (id_pembeli, nama_pemesan, alamat_pemesan, metode_bayar, total_harga, id_penjual, catatan, status)
                VALUES
                ('$id_pembeli', '$nama_post', '$alamat_post', '$bayar', '$total_seller', '$seller_id', '$catatan', 'menunggu pembayaran')
            ");

            $id_pesanan = mysqli_insert_id($koneksi);

            foreach ($seller_items as $item) {
                $sub = $item['harga'] * $item['qty'];
                mysqli_query($koneksi, "INSERT INTO detail_pesanan
                    (id_pesanan, id_produk, jumlah_produk, sub_total)
                    VALUES ('$id_pesanan', '{$item['id_produk']}', '{$item['qty']}', '$sub')
                ");
                mysqli_query($koneksi, "UPDATE produk SET stok = stok - {$item['qty']}
                    WHERE id_produk = {$item['id_produk']} AND stok >= {$item['qty']}
                ");
            }
        }

        // Hapus item yang sudah di-checkout dari keranjang
        foreach ($items as $item) {
            mysqli_query($koneksi, "DELETE FROM keranjang
                WHERE id_pembeli = '$id_pembeli' AND id_produk = '{$item['id_produk']}'
            ");
        }

        // Bersihkan session
        if ($is_buy_now) {
            unset($_SESSION['buy_now']);
        } else {
            unset($_SESSION['checkout_items'], $_SESSION['checkout_toko']);
        }

        echo "<script>
            alert('Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            location='index.php?page=pembeli/riwayat-pesanan';
        </script>";
        exit;
    }
}
?>

<style>
.checkout-page {
    font-family: 'Poppins', sans-serif;
    background: #f1f5f9;
    min-height: 100vh;
    padding: 28px 32px;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 20px;
    align-items: start;
}

/* HEADER */
.checkout-title {
    font-size: 22px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px;
}
.checkout-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 0 0 24px;
}

/* CARD */
.co-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 18px;
}

.co-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
}

.co-card-header-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: #dcfce7;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.co-card-header-icon svg {
    width: 15px; height: 15px;
    stroke: #16a34a; fill: none; stroke-width: 2;
    stroke-linecap: round; stroke-linejoin: round;
}

.co-card-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.co-card-body {
    padding: 16px 20px;
}

/* ITEM LIST */
.co-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f8fafc;
}
.co-item:last-child { border-bottom: none; }

.co-item-img {
    width: 48px; height: 48px;
    border-radius: 8px;
    object-fit: cover;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.co-item-nama {
    font-size: 13px; font-weight: 500; color: #1e293b;
    flex: 1;
    line-height: 1.4;
}

.co-item-meta {
    font-size: 11px; color: #94a3b8; margin-top: 2px;
}

.co-item-subtotal {
    font-size: 13px; font-weight: 700; color: #15803d;
    white-space: nowrap;
}

/* FORM */
.co-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 14px;
}
.co-form-group:last-child { margin-bottom: 0; }

.co-label {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
}

.co-input,
.co-textarea {
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    color: #1e293b;
    background: #fafafa;
    transition: border-color 0.15s;
    outline: none;
    width: 100%;
}
.co-input:focus,
.co-textarea:focus {
    border-color: #86efac;
    background: #fff;
}
.co-textarea { resize: vertical; min-height: 80px; }

/* METODE BAYAR */
.metode-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.metode-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.15s;
    background: #fafafa;
}
.metode-option:has(input:checked) {
    border-color: #86efac;
    background: #f0fdf4;
}
.metode-option input[type="radio"] { accent-color: #16a34a; }
.metode-label { font-size: 13px; font-weight: 500; color: #374151; }

/* RINGKASAN HARGA */
.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #64748b;
    padding: 5px 0;
}
.summary-row.total {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    border-top: 1px solid #e2e8f0;
    margin-top: 8px;
    padding-top: 12px;
}
.summary-row.total span:last-child { color: #15803d; }

/* TOMBOL */
.btn-bayar {
    width: 100%;
    padding: 13px;
    border-radius: 12px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 2px 10px rgba(22,163,74,0.3);
    transition: all 0.15s;
    margin-top: 4px;
}
.btn-bayar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(22,163,74,0.4);
}

/* ALERT ERROR */
.co-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 18px;
    font-size: 13px;
    color: #dc2626;
}

/* TOKO BADGE */
.toko-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #dcfce7;
    color: #15803d;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid #bbf7d0;
}
.toko-badge svg {
    width: 11px; height: 11px;
    stroke: currentColor; fill: none; stroke-width: 2;
    stroke-linecap: round; stroke-linejoin: round;
}

/* RESPONSIVE */
@media (max-width: 991.98px) {
    .checkout-grid { grid-template-columns: 1fr; }
}
@media (max-width: 575.98px) {
    .checkout-page { padding: 16px; }
}
</style>

<div class="checkout-page">

    <h2 class="checkout-title">Checkout</h2>
    <p class="checkout-subtitle">
        Periksa pesanan kamu sebelum melanjutkan pembayaran.
    </p>

    <?php if (!empty($error_stok)): ?>
        <div class="co-alert">
            ⚠️ <?= implode('<br>⚠️ ', $error_stok) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="co-card">
            <div class="co-card-body" style="text-align:center; padding: 40px; color:#94a3b8;">
                Tidak ada produk yang bisa di-checkout.
                <br><br>
                <a href="index.php?page=pembeli/keranjang" style="color:#16a34a; font-weight:500;">← Kembali ke keranjang</a>
            </div>
        </div>
    <?php else: ?>

    <form method="POST">
    <div class="checkout-grid">

        <!-- KOLOM KIRI: Ringkasan pesanan + form -->
        <div>

            <!-- RINGKASAN PESANAN -->
            <div class="co-card">
                <div class="co-card-header">
                    <div class="co-card-header-icon">
                        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
                    </div>
                    <div>
                        <div class="co-card-title">Ringkasan Pesanan</div>
                    </div>
                    <?php if ($nama_toko): ?>
                        <span class="toko-badge" style="margin-left:auto;">
                            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <?= htmlspecialchars($nama_toko) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="co-card-body">
                    <?php foreach ($items as $item): ?>
                    <div class="co-item">
                        <img class="co-item-img"
                             src="<?= htmlspecialchars($item['foto']) ?>"
                             alt="<?= htmlspecialchars($item['nama']) ?>"
                             loading="lazy">
                        <div style="flex:1;">
                            <div class="co-item-nama"><?= htmlspecialchars($item['nama']) ?></div>
                            <div class="co-item-meta">
                                <?= $item['qty'] ?> × Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="co-item-subtotal">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- DATA PENGIRIMAN -->
            <div class="co-card">
                <div class="co-card-header">
                    <div class="co-card-header-icon">
                        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div class="co-card-title">Data Pengiriman</div>
                </div>
                <div class="co-card-body">
                    <div class="co-form-group">
                        <label class="co-label">Nama Penerima</label>
                        <input type="text" name="nama" class="co-input"
                               placeholder="Masukkan nama penerima"
                               value="<?= htmlspecialchars($nama) ?>" required>
                    </div>
                    <div class="co-form-group">
                        <label class="co-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="co-textarea"
                                  placeholder="Masukkan alamat lengkap pengiriman"
                                  required><?= htmlspecialchars($alamat) ?></textarea>
                    </div>
                    <div class="co-form-group">
                        <label class="co-label">Catatan untuk Penjual <span style="font-weight:400;color:#94a3b8;">(opsional)</span></label>
                        <textarea name="catatan" class="co-textarea"
                                  placeholder="Contoh: tolong dibungkus rapih ya..."
                                  style="min-height:60px;"></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: Metode bayar + total + tombol -->
        <div>

            <!-- METODE PEMBAYARAN -->
            <div class="co-card">
                <div class="co-card-header">
                    <div class="co-card-header-icon">
                        <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <div class="co-card-title">Metode Pembayaran</div>
                </div>
                <div class="co-card-body">
                    <div class="metode-grid">
                        <label class="metode-option">
                            <input type="radio" name="bayar" value="Transfer Bank" required>
                            <span class="metode-label">🏦 Transfer Bank</span>
                        </label>
                        <label class="metode-option">
                            <input type="radio" name="bayar" value="E-Wallet">
                            <span class="metode-label">📱 E-Wallet</span>
                        </label>
                        <label class="metode-option">
                            <input type="radio" name="bayar" value="COD">
                            <span class="metode-label">💵 COD (Bayar di Tempat)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- RINGKASAN HARGA -->
            <div class="co-card">
                <div class="co-card-header">
                    <div class="co-card-header-icon">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div class="co-card-title">Rincian Harga</div>
                </div>
                <div class="co-card-body">
                    <?php foreach ($items as $item): ?>
                    <div class="summary-row">
                        <span><?= htmlspecialchars($item['nama']) ?> ×<?= $item['qty'] ?></span>
                        <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                    </div>
                    <?php endforeach; ?>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <button type="submit" name="checkout" class="btn-bayar">
                Konfirmasi &amp; Bayar — Rp <?= number_format($total, 0, ',', '.') ?>
            </button>

            <div style="text-align:center; margin-top:12px;">
                <a href="index.php?page=pembeli/keranjang"
                   style="font-size:12px; color:#94a3b8; text-decoration:none;">
                    ← Kembali ke keranjang
                </a>
            </div>

        </div>
    </div>
    </form>

    <?php endif; ?>
</div>