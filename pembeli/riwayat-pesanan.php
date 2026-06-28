<?php
$id_pembeli = $_SESSION['id'];

// Konfirmasi terima barang dari customer
if (isset($_GET['terima']) && is_numeric($_GET['terima'])) {
    $id_pesanan = (int)$_GET['terima'];
    // Pastikan pesanan ini milik pembeli yg login
    $cek = mysqli_query($koneksi, "SELECT id_pesanan FROM pesanan
        WHERE id_pesanan='$id_pesanan' AND id_pembeli='$id_pembeli' AND status='dikirim'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($koneksi, "UPDATE pesanan SET status='selesai'
            WHERE id_pesanan='$id_pesanan'");
    }
    header("Location: index.php?page=pembeli/riwayat-pesanan");
    exit;
}

$result = mysqli_query($koneksi, "
    SELECT ps.*,
           pj.nama_toko,
           pj.nama_penjual
    FROM pesanan ps
    JOIN penjual pj ON ps.id_penjual = pj.id_penjual
    WHERE ps.id_pembeli = '$id_pembeli'
    ORDER BY ps.tanggal DESC
");
?>

<style>
.riwayat-page { font-family:'Poppins',sans-serif; background:#f1f5f9; min-height:100vh; padding:28px 32px; }
.riwayat-title { font-size:22px; font-weight:600; color:#1e293b; margin:0 0 4px; }

/* EMPTY */
.riwayat-empty { background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:60px 20px; text-align:center; color:#94a3b8; }
.riwayat-empty svg { width:48px;height:48px;stroke:#cbd5e1;fill:none;stroke-width:1.5;margin-bottom:12px; }

/* PESANAN CARD */
.pesanan-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:16px; }
.pesanan-card-head { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0; flex-wrap:wrap; gap:8px; }
.pesanan-toko { display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#1e293b; }
.pesanan-toko-icon { width:28px;height:28px; background:linear-gradient(135deg,#16a34a,#22c55e); border-radius:7px; display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.pesanan-toko-icon svg { width:13px;height:13px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round; }
.pesanan-meta { font-size:11px; color:#94a3b8; }

/* STATUS BADGES */
.badge { display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600; }
.badge-diproses  { background:#fef9c3;color:#92400e;border:1px solid #fde68a; }
.badge-dikirim   { background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe; }
.badge-selesai   { background:#dcfce7;color:#15803d;border:1px solid #bbf7d0; }
.badge-menunggu  { background:#fef3c7;color:#d97706;border:1px solid #fde68a; }
.badge-belum     { background:#fef2f2;color:#dc2626;border:1px solid #fecaca; }
.badge-lunas     { background:#dcfce7;color:#15803d;border:1px solid #bbf7d0; }
.badge-verif     { background:#ede9fe;color:#6d28d9;border:1px solid #ddd6fe; }

/* DETAIL ITEM */
.pesanan-items { padding:14px 20px; }
.pesanan-item { display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid #f8fafc; }
.pesanan-item:last-child { border-bottom:none; }
.pesanan-item-img { width:44px;height:44px;border-radius:7px;object-fit:cover;background:#f1f5f9;border:1px solid #e2e8f0;flex-shrink:0; }
.pesanan-item-nama { font-size:13px;font-weight:500;color:#1e293b;flex:1; }
.pesanan-item-meta { font-size:11px;color:#94a3b8;margin-top:2px; }
.pesanan-item-sub { font-size:13px;font-weight:600;color:#15803d;white-space:nowrap; }

/* FOOTER CARD */
.pesanan-card-foot { display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-top:1px solid #f1f5f9;flex-wrap:wrap;gap:10px; }
.pesanan-total-label { font-size:12px;color:#64748b; }
.pesanan-total-val { font-size:15px;font-weight:700;color:#1e293b; }

/* BUKTI BAYAR */
.bukti-thumb { width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;cursor:pointer;transition:opacity .15s; }
.bukti-thumb:hover { opacity:.8; }
.bukti-link { font-size:11px;color:#16a34a;text-decoration:none;font-weight:500; }

/* TOMBOL */
.btn-terima { display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px; background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;font-size:12px;font-weight:600; text-decoration:none;border:none;cursor:pointer;font-family:'Poppins',sans-serif; box-shadow:0 2px 6px rgba(22,163,74,.25);transition:all .15s; }
.btn-terima:hover { transform:translateY(-1px);box-shadow:0 4px 12px rgba(22,163,74,.35);color:#fff; }
.btn-terima svg { width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round; }

@media(max-width:767.98px){ .riwayat-page{padding:16px;} .pesanan-card-foot{flex-direction:column;align-items:flex-start;} }
</style>

<div class="riwayat-page">
    <h2 class="riwayat-title">Riwayat Pesanan</h2>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="riwayat-empty">
            <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
            <p style="font-size:14px;font-weight:600;color:#64748b;">Belum ada pesanan</p>
            <p style="font-size:13px;margin:4px 0 20px;">Yuk mulai belanja produk UMKM favoritmu!</p>
            <a href="index.php?page=produk" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:9px;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;font-size:13px;font-weight:600;text-decoration:none;">Jelajahi Produk</a>
        </div>
    <?php else: ?>
        <?php while ($ps = mysqli_fetch_assoc($result)):
            // Ambil detail item pesanan
            $detail = mysqli_query($koneksi, "
                SELECT dp.*, p.nama, p.foto, p.harga
                FROM detail_pesanan dp
                JOIN produk p ON dp.id_produk = p.id_produk
                WHERE dp.id_pesanan = '{$ps['id_pesanan']}'
            ");

            // Badge status pesanan
            $badge_status = match($ps['status']) {
                'diproses' => '<span class="badge badge-diproses">⏳ Diproses</span>',
                'dikirim'  => '<span class="badge badge-dikirim">🚚 Dikirim</span>',
                'selesai'  => '<span class="badge badge-selesai">✅ Selesai</span>',
                default    => '<span class="badge badge-menunggu">' . htmlspecialchars($ps['status']) . '</span>',
            };

            // Badge status bayar
            $badge_bayar = match($ps['verif_bayar']) {
                'belum'          => '<span class="badge badge-belum">💸 Belum Terverifikasi </span>',
                'sudah'          => '<span class="badge badge-lunas">✅ Lunas</span>',
                default                => '',
            };

            $tgl = date('d M Y, H:i', strtotime($ps['tanggal']));
        ?>
        <div class="pesanan-card">

            <!-- HEADER -->
            <div class="pesanan-card-head">
                <div class="pesanan-toko">
                    <div class="pesanan-toko-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <?= htmlspecialchars($ps['nama_toko']) ?>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <?= $badge_status ?>
                    <?= $badge_bayar ?>
                </div>
                <div class="pesanan-meta"><?= $tgl ?> · <?= htmlspecialchars($ps['metode_bayar']) ?></div>
            </div>

            <!-- ITEM -->
            <div class="pesanan-items">
                <?php while ($item = mysqli_fetch_assoc($detail)): ?>
                <div class="pesanan-item">
                    <img class="pesanan-item-img" src="<?= htmlspecialchars($item['foto']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>" loading="lazy">
                    <div style="flex:1;">
                        <div class="pesanan-item-nama"><?= htmlspecialchars($item['nama']) ?></div>
                        <div class="pesanan-item-meta"><?= $item['jumlah_produk'] ?> × Rp <?= number_format($item['harga'], 0, ',', '.') ?></div>
                    </div>
                    <div class="pesanan-item-sub">Rp <?= number_format($item['sub_total'], 0, ',', '.') ?></div>
                </div>
                <?php endwhile; ?>
            </div>

            <!-- FOOTER -->
            <div class="pesanan-card-foot">
                <div>
                    <div class="pesanan-total-label">Total Pembayaran</div>
                    <div class="pesanan-total-val">Rp <?= number_format($ps['total_harga'], 0, ',', '.') ?></div>
                </div>

                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">

                    <!-- Bukti bayar (jika ada) -->
                    <?php if ($ps['bukti_bayar']): ?>
                        <a href="<?= htmlspecialchars($ps['bukti_bayar']) ?>" target="_blank" title="Lihat bukti bayar">
                            <?php if (str_ends_with(strtolower($ps['bukti_bayar']), '.pdf')): ?>
                                <span class="bukti-link">📄 Lihat Bukti Bayar</span>
                            <?php else: ?>
                                <img class="bukti-thumb" src="<?= htmlspecialchars($ps['bukti_bayar']) ?>" alt="Bukti bayar">
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Tombol konfirmasi terima (hanya jika status = dikirim) -->
                    <?php if ($ps['status'] === 'dikirim'): ?>
                        <a href="index.php?page=pembeli/riwayat-pesanan&terima=<?= $ps['id_pesanan'] ?>"
                           class="btn-terima"
                           onclick="return confirm('Konfirmasi kamu sudah menerima barang ini?')">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Konfirmasi Terima Barang
                        </a>
                    <?php endif; ?>

                </div>
            </div>

        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>