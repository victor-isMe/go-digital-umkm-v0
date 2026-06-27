<?php
if (!isset($_SESSION['login'])) {
    header("Location: index.php?page=login");
    exit;
}

$id_pembeli = $_SESSION['id'];
$nama       = $_SESSION['nama']   ?? '';
$alamat     = $_SESSION['alamat'] ?? '';

$is_buy_now = false;
$checkout_items = []; // [id_produk => qty]

if (isset($_GET['buy_now']) && $_SESSION['role'] === 'pembeli') {
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
    header("Location: index.php?page=pembeli/keranjang");
    exit;
}

$id_toko    = $_SESSION['checkout_toko'] ?? (int)($_GET['toko'] ?? 0);
$items      = [];
$total      = 0;
$nama_toko  = '';
$error_stok = [];

foreach ($checkout_items as $id => $qty) {
    $id = (int)$id; 
    $qty = (int)$qty;
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

$metode_bayar_seller = [];
if (!empty($items)) {
    $id_penjual = $items[0]['id_penjual'];
    $id_penjual_esc = mysqli_real_escape_string($koneksi, $id_penjual);

    $query_metode = mysqli_query($koneksi, "SELECT * FROM metode_pembayaran WHERE id_penjual='$id_penjual_esc' ORDER BY metode, provider");
    while ($row = mysqli_fetch_assoc($query_metode)) {
        $metode_bayar_seller[] = $row;
    }
}

if (isset($_POST['checkout'])) {
    $nama_post  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat_post= mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $bayar      = mysqli_real_escape_string($koneksi, $_POST['bayar']);
    $catatan    = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');

    if (empty($items)) {
        $post_error = "Tidak ada produk yang bisa diproses.";
    } else {
        $bukti_bayar = null;
        if ($bayar !== 'COD') {
            if (empty($_FILES['bukti_bayar']['name'])) {
                $post_error = "Harap upload bukti pembayaran terlebih dahulu.";
            } else {
                $ext_allowed = ['jpg', 'png', 'jpeg', 'webp', 'pdf'];
                $ext = strtolower(pathinfo($_FILES['bukti_bayar']['name'], PATHINFO_EXTENSION));
                $max_size = 4 * 1024 * 1024;

                if (!in_array($ext, $ext_allowed)) {
                    $post_error = "Format file tidak didukung.";
                } elseif ($_FILES['bukti_bayar']['size'] > $max_size) {
                    $post_error = "Ukuran file terlalu besar. Max 4mb.";
                } else {
                    $nama_file = 'bukti_' . $id_pembeli . '_' . time() . '.' . $ext;
                    $upload_dir = 'img/bukti_bayar/';
                    if (move_uploaded_file($_FILES['bukti_bayar']['tmp_name'], $upload_dir . $nama_file)) {
                        $bukti_bayar = $upload_dir . $nama_file;
                    } else {
                        $post_error = "Gagal mengupload file. Silahkan coba lagi.";
                    }
                }
            }
        }

        if (empty($post_error)) {
            $status_bayar = ($bayar === 'COD') ? 'belum bayar' : 'sudah_bayar';
            $bukti_db = $bukti_bayar ? mysqli_real_escape_string($koneksi, $bukti_bayar) : null;
            $bukti_sql = $bukti_db ? "'$bukti_db'" : "NULL";

            $grup = [];
            foreach ($items as $item) {
                $grup[$item['id_penjual']][] = $item;
            }

            foreach ($grup as $seller_id => $seller_items) {
                $total_seller = array_sum(array_column($seller_items, 'subtotal'));
                mysqli_query($koneksi, "INSERT INTO pesanan
                    (id_pembeli, nama_pemesan, alamat_pemesan, metode_bayar,
                    total_harga, id_penjual, status, status_bayar, bukti_bayar)
                    VALUES
                    ('$id_pembeli', '$nama_post', '$alamat_post', '$bayar',
                    '$total_seller', '$seller_id', 'diproses', '$status_bayar', $bukti_sql)");
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
        }

        // Bersihkan session
        if ($is_buy_now) {
            unset($_SESSION['buy_now']);
        } else {
            unset($_SESSION['checkout_items'], $_SESSION['checkout_toko']);
        }

        $msg = ($bayar === 'COD') ? "Pesanan dibuat! Bayar tunai saat barang tiba." : "Pesanan dibuat! Menunggu verifikasi pembayaran oleh seller.";

        echo "<script>
            alert('$msg');
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
    grid-template-columns: 1fr 390px;
    gap: 20px;
    align-items: start;
}
.checkout-title { font-size: 22px; font-weight: 600; color: #1e293b; margin: 0 0 4px; }
.checkout-subtitle { font-size: 13px; color: #64748b; margin: 0 0 24px; }
 
/* CARD */
.co-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 18px; }
.co-card-header { display: flex; align-items: center; gap: 10px; padding: 14px 20px; border-bottom: 1px solid #f1f5f9; }
.co-card-header-icon { width: 30px; height: 30px; border-radius: 8px; background: #dcfce7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.co-card-header-icon svg { width: 15px; height: 15px; stroke: #16a34a; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.co-card-title { font-size: 14px; font-weight: 600; color: #1e293b; }
.co-card-body { padding: 16px 20px; }
 
/* ITEM */
.co-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f8fafc; }
.co-item:last-child { border-bottom: none; }
.co-item-img { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; background: #f1f5f9; border: 1px solid #e2e8f0; flex-shrink: 0; }
.co-item-nama { font-size: 13px; font-weight: 500; color: #1e293b; flex: 1; }
.co-item-meta { font-size: 11px; color: #94a3b8; margin-top: 2px; }
.co-item-subtotal { font-size: 13px; font-weight: 700; color: #15803d; white-space: nowrap; }
 
/* FORM */
.co-form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.co-form-group:last-child { margin-bottom: 0; }
.co-label { font-size: 12px; font-weight: 600; color: #374151; }
.co-input, .co-textarea {
    padding: 11px 14px; border-radius: 10px; border: 1px solid #e2e8f0;
    font-size: 13px; font-family: 'Poppins', sans-serif; color: #1e293b;
    background: #fafafa; transition: border-color 0.15s; outline: none; width: 100%;
}
.co-input:focus, .co-textarea:focus { border-color: #86efac; background: #fff; }
.co-textarea { resize: vertical; min-height: 80px; }
 
/* METODE BAYAR */
.metode-grid { display: flex; flex-direction: column; gap: 8px; }
.metode-option {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 14px; border-radius: 10px; border: 1px solid #e2e8f0;
    cursor: pointer; transition: all 0.15s; background: #fafafa;
}
.metode-option:has(input:checked) { border-color: #86efac; background: #f0fdf4; }
.metode-option input[type="radio"] { accent-color: #16a34a; }
.metode-label { font-size: 13px; font-weight: 500; color: #374151; }
 
/* INFO REKENING TUJUAN PEMBAYARAN */
.rekening-info {
    display: none;
    margin-top: 14px;
    padding: 12px 14px;
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-radius: 10px;
}
.rekening-info.show {
    display: block;
}

/* UPLOAD BUKTI */
.bukti-box {
    display: none;
    margin-top: 14px;
    padding: 14px;
    background: #f0fdf4;
    border: 1px dashed #86efac;
    border-radius: 10px;
}
.bukti-box.show { display: block; }
.bukti-box-label { font-size: 12px; font-weight: 600; color: #15803d; margin-bottom: 8px; display: block; }
.bukti-box-hint { font-size: 11px; color: #64748b; margin-top: 6px; }
.co-file-input {
    width: 100%; padding: 10px; border-radius: 8px;
    border: 1px solid #bbf7d0; background: #fff;
    font-size: 12px; font-family: 'Poppins', sans-serif;
    cursor: pointer;
}
.preview-img { width: 100%; max-height: 160px; object-fit: contain; border-radius: 8px; margin-top: 8px; display: none; border: 1px solid #e2e8f0; }
 
/* RINGKASAN HARGA */
.summary-row { display: flex; justify-content: space-between; font-size: 13px; color: #64748b; padding: 5px 0; }
.summary-row.total { font-size: 16px; font-weight: 700; color: #1e293b; border-top: 1px solid #e2e8f0; margin-top: 8px; padding-top: 12px; }
.summary-row.total span:last-child { color: #15803d; }
 
/* TOMBOL */
.btn-bayar {
    width: 100%; padding: 13px; border-radius: 12px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff; font-size: 14px; font-weight: 600; border: none; cursor: pointer;
    font-family: 'Poppins', sans-serif; box-shadow: 0 2px 10px rgba(22,163,74,0.3);
    transition: all 0.15s; margin-top: 4px;
}
.btn-bayar:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(22,163,74,0.4); }
 
/* ALERT */
.co-alert { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; font-size: 13px; color: #dc2626; }
 
/* TOKO BADGE */
.toko-badge { display: inline-flex; align-items: center; gap: 6px; background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; border: 1px solid #bbf7d0; }
.toko-badge svg { width: 11px; height: 11px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
 
/* COD INFO */
.cod-info { display: none; margin-top: 14px; padding: 12px 14px; background: #fefce8; border: 1px solid #fde68a; border-radius: 10px; font-size: 12px; color: #92400e; line-height: 1.6; }
.cod-info.show { display: block; }
 
@media (max-width: 991.98px) { .checkout-grid { grid-template-columns: 1fr; } }
@media (max-width: 575.98px) { .checkout-page { padding: 16px; } }
</style>

<div class="checkout-page">
 
    <h2 class="checkout-title">Checkout</h2>
    <p class="checkout-subtitle">Periksa pesanan kamu sebelum melanjutkan pembayaran.</p>
 
    <?php if (!empty($error_stok)): ?>
        <div class="co-alert">⚠️ <?= implode('<br>⚠️ ', $error_stok) ?></div>
    <?php endif; ?>
    <?php if (!empty($post_error)): ?>
        <div class="co-alert">⚠️ <?= htmlspecialchars($post_error) ?></div>
    <?php endif; ?>
 
    <?php if (empty($items)): ?>
        <div class="co-card">
            <div class="co-card-body" style="text-align:center;padding:40px;color:#94a3b8;">
                Tidak ada produk yang bisa di-checkout.<br><br>
                <a href="index.php?page=pembeli/keranjang" style="color:#16a34a;font-weight:500;">← Kembali ke keranjang</a>
            </div>
        </div>
    <?php else: ?>
 
    <form method="POST" enctype="multipart/form-data">
    <div class="checkout-grid">
 
        <!-- KOLOM KIRI -->
        <div>
 
            <!-- RINGKASAN PESANAN -->
            <div class="co-card">
                <div class="co-card-header">
                    <div class="co-card-header-icon">
                        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
                    </div>
                    <div class="co-card-title">Ringkasan Pesanan</div>
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
                        <img class="co-item-img" src="<?= htmlspecialchars($item['foto']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>" loading="lazy">
                        <div style="flex:1;">
                            <div class="co-item-nama"><?= htmlspecialchars($item['nama']) ?></div>
                            <div class="co-item-meta"><?= $item['qty'] ?> × Rp <?= number_format($item['harga'], 0, ',', '.') ?></div>
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
                        <input type="text" name="nama" class="co-input" placeholder="Nama penerima" value="<?= htmlspecialchars($nama) ?>" required>
                    </div>
                    <div class="co-form-group">
                        <label class="co-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="co-textarea" placeholder="Alamat lengkap pengiriman" required><?= htmlspecialchars($alamat) ?></textarea>
                    </div>
                    <div class="co-form-group">
                        <label class="co-label">Catatan <span style="font-weight:400;color:#94a3b8;">(opsional)</span></label>
                        <textarea name="catatan" class="co-textarea" placeholder="Catatan untuk penjual..." style="min-height:60px;"></textarea>
                    </div>
                </div>
            </div>
 
        </div>
 
        <!-- KOLOM KANAN -->
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

                        <?php if (empty($metode_bayar_seller)): ?>
                            <div style="font-size: 12px; color: #94a3b8; padding: 4px 0 8px;">
                                Penjual belum menambahkan metode transfer. Hanya tersedia COD.
                            </div>
                            <label class="metode-option">
                                <input type="radio" name="bayar" value="COD" onchange="handleMetode(this.value, null, null)" required checked>
                                <span class="metode-label">💵 COD (Bayar di Tempat)</span>
                            </label>
                        <?php else: ?>
                            <?php foreach ($metode_bayar_seller as $i => $m): 
                                $icon       = $m['metode'] === 'bank' ? '🏦' : '📱';
                                $tipe_label = $m['metode'] === 'bank' ? 'Transfer Bank' : 'E-Wallet';

                                $data_val = htmlspecialchars(json_encode([
                                    'provider'   => $m['provider'],
                                    'nomor_akun' => $m['nomor_akun'],
                                    'nama_akun'  => $m['nama_akun'],
                                    'metode'     => $m['metode'],
                                ]), ENT_QUOTES);
                            ?>
                            <label class="metode-option">
                                <input type="radio" name="bayar" 
                                    value="<?= htmlspecialchars($m['metode']) ?>"
                                    data-rekening="<?= $data_val ?>" 
                                    required 
                                    onchange="handleMetode('transfer', this)">
                                <div style="flex: 1;">
                                    <div class="metode-label">
                                        <?= $icon ?> <strong><?= htmlspecialchars($m['provider']) ?></strong>
                                        <span style="font-size: 11px;color: #94a3b8;font-weight:400;margin-left:4px;"><?= $tipe_label ?></span>
                                    </div>
                                    <div style="font-size: 11px;color:#64748b;margin-top:2px;">
                                        <?= htmlspecialchars($m['nomor_akun']) ?>
                                        . <?= htmlspecialchars($m['nama_akun']) ?>
                                    </div>
                                </div>
                            </label>
                            <?php endforeach; ?>

                            <label class="metode-option">
                                <input type="radio" name="bayar" value="COD" onchange="handleMetode(this.value, this)">
                                <span class="metode-label">💵 COD (Bayar di Tempat)</span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="rekening-info" id="rekeningInfo">
                        <div style="font-size: 11px;font-weight:600;color:#15803d;margin-bottom:6px;">
                            Transfer ke: 
                        </div>
                        <div style="font-size: 13px;font-weight:700;color:#1e293b;" id="rekeningProvider"></div>
                        <div style="font-size: 13px;color:#374151;margin-top:2px;" id="rekeningNomor"></div>
                        <div style="font-size: 11px;color:#64748b;margin-top:1px;">a/n <span id="rekeningNama"></span></div>
                    </div>
 
                    <!-- Upload bukti (muncul untuk Transfer Bank & E-Wallet) -->
                    <div class="bukti-box" id="buktiBox">
                        <span class="bukti-box-label">📎 Upload Bukti Pembayaran</span>
                        <input type="file" name="bukti_bayar" id="buktiInput" class="co-file-input"
                               accept=".jpg,.jpeg,.png,.webp,.pdf"
                               onchange="previewBukti(this)">
                        <img id="buktiPreview" class="preview-img" alt="Preview bukti">
                        <div class="bukti-box-hint">Format: JPG, PNG, WEBP, atau PDF. Maks. 4 MB.</div>
                    </div>
 
                    <!-- Info COD (muncul saat pilih COD) -->
                    <div class="cod-info" id="codInfo">
                        <strong>Bayar di Tempat (COD):</strong> Kamu akan membayar secara tunai saat barang diterima. Penjual akan mengkonfirmasi pembayaran setelah barang diserahkan.
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
                Konfirmasi &amp; Pesan — Rp <?= number_format($total, 0, ',', '.') ?>
            </button>
 
            <div style="text-align:center;margin-top:12px;">
                <a href="index.php?page=pembeli/keranjang" style="font-size:12px;color:#94a3b8;text-decoration:none;">← Kembali ke keranjang</a>
            </div>
 
        </div>
    </div>
    </form>
 
    <?php endif; ?>
</div>
 
<script>
function handleMetode(tipe, radio) {
    const bukti  = document.getElementById('buktiBox');
    const cod    = document.getElementById('codInfo');
    const input  = document.getElementById('buktiInput');
    const rekeningInfo = document.getElementById('rekeningInfo');

    const isCOD = (tipe === 'COD');
 
    bukti.classList.toggle('show', !isCOD);
    cod.classList.toggle('show', isCOD);
    input.required = !isCOD;

    if (isCOD || !radio) {
        rekeningInfo.classList.remove('show');
        return;
    }

    const rekening = JSON.parse(radio.dataset.rekening || '{}');

    document.getElementById('rekeningProvider').textContent = rekening.provider || '';
    document.getElementById('rekeningNomor').textContent = rekening.nomor_akun || '';
    document.getElementById('rekeningNama').textContent = rekening.nama_akun || '';

    rekeningInfo.classList.add('show');
}
 
function previewBukti(input) {
    const preview = document.getElementById('buktiPreview');
    const file    = input.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>