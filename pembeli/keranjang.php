<?php
$id_pembeli = $_SESSION['id'];

$result = mysqli_query($koneksi, "
    SELECT
        k.id,
        k.qty,
        p.id_produk,
        p.nama AS nama_produk,
        p.harga,
        p.stok,
        p.foto,
        pj.id_penjual,
        pj.nama_toko
    FROM keranjang k
    JOIN produk  p  ON k.id_produk  = p.id_produk
    JOIN penjual pj ON p.id_penjual = pj.id_penjual
    WHERE k.id_pembeli = '$id_pembeli'
    ORDER BY pj.id_penjual, p.nama
");

// Kelompokkan per toko
$toko_grup = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id_toko = $row['id_penjual'];
    if (!isset($toko_grup[$id_toko])) {
        $toko_grup[$id_toko] = [
            'nama_toko' => $row['nama_toko'],
            'items'     => []
        ];
    }
    $toko_grup[$id_toko]['items'][] = $row;
}
?>

<style>
.keranjang-page {
    font-family: 'Poppins', sans-serif;
    background: #f1f5f9;
    min-height: 100vh;
    padding: 28px 32px;
}

/* HEADER */
.keranjang-header {
    margin-bottom: 24px;
}
.keranjang-title {
    font-size: 22px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px;
}
.keranjang-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

/* EMPTY STATE */
.keranjang-empty {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 60px 20px;
    text-align: center;
    color: #94a3b8;
}
.keranjang-empty svg {
    width: 52px; height: 52px;
    stroke: #cbd5e1; fill: none; stroke-width: 1.5;
    margin-bottom: 14px;
}
.keranjang-empty h5 {
    font-size: 15px; font-weight: 600; color: #64748b; margin-bottom: 6px;
}
.keranjang-empty p {
    font-size: 13px; margin: 0 0 20px;
}

/* TOKO CARD */
.toko-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 20px;
}

.toko-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.toko-card-name {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.toko-icon {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.toko-icon svg {
    width: 16px; height: 16px;
    stroke: #fff; fill: none; stroke-width: 2;
    stroke-linecap: round; stroke-linejoin: round;
}

/* ITEM TABLE */
.item-table {
    width: 100%;
    border-collapse: collapse;
}

.item-table th {
    padding: 10px 20px;
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left;
}

.item-table td {
    padding: 14px 20px;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
    font-size: 13px;
    color: #374151;
}

.item-table tr:last-child td {
    border-bottom: none;
}

.item-produk-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-produk-img {
    width: 52px; height: 52px;
    border-radius: 8px;
    object-fit: cover;
    background: #f1f5f9;
    flex-shrink: 0;
    border: 1px solid #e2e8f0;
}

.item-produk-nama {
    font-weight: 500;
    color: #1e293b;
    font-size: 13px;
    line-height: 1.4;
}

.item-produk-stok {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 2px;
}

.stok-habis {
    color: #ef4444 !important;
    font-weight: 500;
}

.item-harga {
    font-weight: 500;
    color: #374151;
    white-space: nowrap;
}

.item-subtotal {
    font-weight: 700;
    color: #15803d;
    white-space: nowrap;
}

/* QTY CONTROL */
.qty-control {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qty-btn {
    width: 28px; height: 28px;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    font-size: 15px;
    font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    line-height: 1;
    transition: all 0.15s;
    flex-shrink: 0;
}
.qty-btn:hover {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #15803d;
}

.qty-val {
    min-width: 24px;
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.btn-hapus {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px;
    border-radius: 7px;
    border: 1px solid #fecaca;
    background: #fff5f5;
    color: #ef4444;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.15s;
    margin-left: 4px;
    flex-shrink: 0;
}
.btn-hapus:hover {
    background: #fef2f2;
    border-color: #ef4444;
    color: #dc2626;
}
.btn-hapus svg {
    width: 13px; height: 13px;
    stroke: currentColor; fill: none; stroke-width: 2.5;
    stroke-linecap: round; stroke-linejoin: round;
}

/* TOKO FOOTER */
.toko-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 12px;
}

.toko-subtotal-label {
    font-size: 12px;
    color: #64748b;
}

.toko-subtotal-val {
    font-size: 16px;
    font-weight: 700;
    color: #15803d;
}

.btn-checkout-toko {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 2px 8px rgba(22,163,74,0.25);
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-checkout-toko:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(22,163,74,0.35);
    color: #fff;
}
.btn-checkout-toko:disabled,
.btn-checkout-toko.disabled {
    opacity: 0.5;
    pointer-events: none;
    box-shadow: none;
}

/* GRAND TOTAL */
.grand-total-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.grand-total-label {
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
}
.grand-total-val {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
}

/* RESPONSIVE */
@media (max-width: 767.98px) {
    .keranjang-page { padding: 16px; }
    .item-table th:nth-child(2),
    .item-table td:nth-child(2) { display: none; } /* sembunyikan kolom harga */
    .toko-card-footer { flex-direction: column; align-items: flex-start; }
    .btn-checkout-toko { width: 100%; justify-content: center; }
}
</style>

<div class="keranjang-page">

    <div class="keranjang-header">
        <a href="index.php?page=dashboard" class="btn-dashboard-solid mb-3">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Kembali ke dashboard
        </a>        
        <h2 class="keranjang-title">Keranjang Belanja</h2>
    </div>

    <?php if (empty($toko_grup)): ?>

        <div class="keranjang-empty">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
            <h5>Keranjang masih kosong</h5>
            <p>Yuk belanja produk di UMKM pilihan kamu!</p>
            <a href="index.php?page=produk" class="btn-checkout-toko" style="display:inline-flex;">
                Jelajahi Produk
            </a>
        </div>

    <?php else: ?>

        <?php
        $grand_total = 0;
        foreach ($toko_grup as $id_toko => $toko):
            $subtotal_toko  = 0;
            $ada_stok_habis = false;

            foreach ($toko['items'] as $item) {
                $qty_efektif   = min($item['qty'], $item['stok']);
                $subtotal_toko += $item['harga'] * $qty_efektif;
                if ($item['stok'] <= 0) $ada_stok_habis = true;
            }

            $grand_total += $subtotal_toko;

            $checkout_data = [];
            foreach ($toko['items'] as $item) {
                if ($item['stok'] > 0) {
                    $checkout_data[$item['id_produk']] = min($item['qty'], $item['stok']);
                }
            }
            $checkout_param = urlencode(base64_encode(json_encode($checkout_data)));
        ?>

        <div class="toko-card">

            <div class="toko-card-header">
                <div class="toko-card-name">
                    <div class="toko-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <?= htmlspecialchars($toko['nama_toko']) ?>
                </div>
                <span style="font-size:11px;color:#94a3b8;"><?= count($toko['items']) ?> produk</span>
            </div>

            <table class="item-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($toko['items'] as $item):
                        $qty_efektif = min($item['qty'], $item['stok']);
                        $subtotal    = $item['harga'] * $qty_efektif;
                    ?>
                    <tr>
                        <td>
                            <div class="item-produk-info">
                                <img class="item-produk-img"
                                     src="<?= htmlspecialchars($item['foto']) ?>"
                                     alt="<?= htmlspecialchars($item['nama_produk']) ?>"
                                     loading="lazy">
                                <div>
                                    <div class="item-produk-nama"><?= htmlspecialchars($item['nama_produk']) ?></div>
                                    <div class="item-produk-stok <?= $item['stok'] <= 0 ? 'stok-habis' : '' ?>">
                                        <?= $item['stok'] <= 0 ? 'Stok habis' : 'Stok: ' . $item['stok'] ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="item-harga">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td>
                            <div class="qty-control">
                                <a href="index.php?page=pembeli/keranjang&minus=<?= $item['id_produk'] ?>"
                                   class="qty-btn">−</a>
                                <span class="qty-val"><?= $qty_efektif ?></span>
                                <a href="index.php?page=pembeli/keranjang&plus=<?= $item['id_produk'] ?>"
                                   class="qty-btn">+</a>
                                <a href="index.php?page=pembeli/keranjang&hapus_cart=<?= $item['id_produk'] ?>"
                                   class="btn-hapus"
                                   onclick="return confirm('Hapus <?= htmlspecialchars(addslashes($item['nama_produk'])) ?> dari keranjang?')">
                                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                </a>
                            </div>
                        </td>
                        <td class="item-subtotal">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="toko-card-footer">
                <div>
                    <div class="toko-subtotal-label">Total belanja di <?= htmlspecialchars($toko['nama_toko']) ?></div>
                    <div class="toko-subtotal-val">Rp <?= number_format($subtotal_toko, 0, ',', '.') ?></div>
                </div>
                <a href="index.php?page=pembeli/checkout&toko=<?= $id_toko ?>&data=<?= $checkout_param ?>"
                   class="btn-checkout-toko <?= $ada_stok_habis && $subtotal_toko == 0 ? 'disabled' : '' ?>">
                    <svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    Checkout
                </a>
            </div>

        </div>

        <?php endforeach; ?>

        <div class="grand-total-card">
            <span class="grand-total-label">Total semua toko (<?= count($toko_grup) ?> toko)</span>
            <span class="grand-total-val">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
        </div>

    <?php endif; ?>

</div>