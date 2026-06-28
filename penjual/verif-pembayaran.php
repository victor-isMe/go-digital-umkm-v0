<?php
$id_penjual = $_SESSION['id'];

if (isset($_GET['verifikasi'])) {
    $id_pesanan_verif = (int) $_GET['verifikasi'];
    mysqli_query($koneksi, "UPDATE pesanan SET verif_bayar='sudah'
        WHERE id_pesanan='$id_pesanan_verif' AND id_penjual='$id_penjual'");
    header("Location: index.php?page=penjual/verif-pembayaran");
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_penjual='$id_penjual' ORDER BY tanggal DESC");
$total_pesanan = mysqli_num_rows($query);
$rows = [];
while ($r = mysqli_fetch_assoc($query)) $rows[] = $r;

?>

<!-- LIGHTBOX -->
<div class="lbox-overlay" id="lbox" onclick="closeLboxOverlay(event)">
    <div class="lbox-inner">
        <button class="lbox-close" onclick="closeLightbox()" aria-label="Tutup">✕</button>
        <img class="lbox-img" id="lboxImg" src="" alt="Bukti pembayaran">
    </div>
</div>

<div class="vp-page">

    <a href="index.php?page=dashboard" class="btn-dashboard-ghost">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke dashboard
    </a>

    <div class="vp-header">
        <div>
            <div class="vp-title">Verifikasi pembayaran</div>
            <div class="vp-subtitle">Cek bukti bayar dan verifikasi setiap pesanan</div>
        </div>
        <div class="vp-count" id="ordersCount"><?= $total_pesanan ?> pesanan</div>
    </div>

    <div class="vp-card">

        <div class="vp-toolbar">
            <div class="vp-search">
                <input type="text" id="searchInput"
                       placeholder="Cari nama pemesan..."
                       oninput="filterOrders()">
            </div>
            <div class="filter-tabs">
                <button class="ftab active" onclick="setFilter('semua', this)">Semua</button>
                <button class="ftab" onclick="setFilter('belum', this)">Belum diverifikasi</button>
                <button class="ftab" onclick="setFilter('sudah', this)">Sudah diverifikasi</button>
            </div>
        </div>

        <?php if (empty($rows)): ?>
            <div class="vp-empty">
                <div class="vp-empty-text">Belum ada pesanan masuk</div>
                <div class="vp-empty-sub">Pesanan dari pembeli akan muncul di sini</div>
            </div>
        <?php else: ?>

        <!-- ── TABLE (desktop & tablet) ── -->
        <div class="vp-table-wrap">
            <table class="vp-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Metode bayar</th>
                        <th>Bukti bayar</th>
                        <th>Status verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                <?php foreach ($rows as $row):
                    $metode      = $row['metode_bayar'];
                    $isCOD       = ($metode === 'COD');
                    $metode_icon = stripos($metode, 'bank') !== false   ? '🏦'
                                 : (stripos($metode, 'wallet') !== false ? '📱' : '💵');
                    $sudah       = ($row['verif_bayar'] === 'sudah');
                    $tgl         = !empty($row['tanggal'])
                                 ? date('d M Y', strtotime($row['tanggal'])) : '';
                    $bukti       = htmlspecialchars($row['bukti_bayar'] ?? '');
                ?>
                <tr data-nama="<?= strtolower(htmlspecialchars($row['nama_pemesan'])) ?>"
                    data-verif="<?= $sudah ? 'sudah' : 'belum' ?>">

                    <td><span class="order-id">#<?= $row['id_pesanan'] ?></span></td>

                    <td>
                        <div class="pemesan-name"><?= htmlspecialchars($row['nama_pemesan']) ?></div>
                        <?php if ($tgl): ?>
                            <div class="pemesan-date"><?= $tgl ?></div>
                        <?php endif; ?>
                    </td>

                    <td><strong>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></strong></td>

                    <td><span class="metode-pill"><?= $metode_icon ?> <?= htmlspecialchars($metode) ?></span></td>

                    <td>
                        <?php if (!$isCOD && $bukti): ?>
                            <img class="bukti-thumb"
                                 src="<?= $bukti ?>"
                                 alt="Bukti bayar"
                                 onclick="openLightbox('<?= $bukti ?>')">
                        <?php else: ?>
                            <span class="bukti-none">— COD</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <span class="verif-badge <?= $sudah ? 'verif-sudah' : 'verif-belum' ?>">
                            <?= $sudah ? 'Sudah diverifikasi' : 'Belum diverifikasi' ?>
                        </span>
                    </td>

                    <td>
                        <?php if (!$sudah): ?>
                            <a href="index.php?page=penjual/verif-pembayaran&verifikasi=<?= $row['id_pesanan'] ?>"
                               class="btn-verif"
                               onclick="return confirm('Verifikasi pembayaran pesanan #<?= $row['id_pesanan'] ?>?')">
                                Verifikasi
                            </a>
                        <?php else: ?>
                            <span class="btn-verif btn-verif-done">Terverifikasi</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ── CARD LIST (mobile) ── -->
        <div class="vp-mobile-list" id="mobileList">
        <?php foreach ($rows as $row):
            $metode      = $row['metode_bayar'];
            $isCOD       = ($metode === 'COD');
            $metode_icon = stripos($metode, 'bank') !== false   ? '🏦'
                         : (stripos($metode, 'wallet') !== false ? '📱' : '💵');
            $sudah       = ($row['verif_bayar'] === 'sudah');
            $bukti       = htmlspecialchars($row['bukti_bayar'] ?? '');
            $tgl         = !empty($row['tanggal'])
                         ? date('d M Y', strtotime($row['tanggal'])) : '';
        ?>
            <div class="vp-mobile-card"
                 data-nama="<?= strtolower(htmlspecialchars($row['nama_pemesan'])) ?>"
                 data-verif="<?= $sudah ? 'sudah' : 'belum' ?>">

                <div class="mc-head">
                    <span class="order-id">#<?= $row['id_pesanan'] ?></span>
                    <span class="verif-badge <?= $sudah ? 'verif-sudah' : 'verif-belum' ?>">
                        <?= $sudah ? 'Sudah' : 'Belum' ?>
                    </span>
                </div>

                <div class="mc-row">
                    <span class="mc-label">Pemesan</span>
                    <span><?= htmlspecialchars($row['nama_pemesan']) ?></span>
                </div>
                <?php if ($tgl): ?>
                <div class="mc-row">
                    <span class="mc-label">Tanggal</span>
                    <span><?= $tgl ?></span>
                </div>
                <?php endif; ?>
                <div class="mc-row">
                    <span class="mc-label">Total</span>
                    <strong>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></strong>
                </div>
                <div class="mc-row">
                    <span class="mc-label">Metode</span>
                    <span class="metode-pill"><?= $metode_icon ?> <?= htmlspecialchars($metode) ?></span>
                </div>

                <div class="mc-foot">
                    <?php if (!$isCOD && $bukti): ?>
                        <img class="bukti-thumb"
                             src="<?= $bukti ?>" alt="Bukti bayar"
                             onclick="openLightbox('<?= $bukti ?>')"
                             style="width:52px;height:52px;">
                    <?php else: ?>
                        <span class="bukti-none">— COD</span>
                    <?php endif; ?>

                    <?php if (!$sudah): ?>
                        <a href="index.php?page=penjual/verif-pembayaran&verifikasi=<?= $row['id_pesanan'] ?>"
                           class="btn-verif"
                           onclick="return confirm('Verifikasi pesanan #<?= $row['id_pesanan'] ?>?')">
                            Verifikasi
                        </a>
                    <?php else: ?>
                        <span class="btn-verif btn-verif-done">Terverifikasi</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>
</div>

<script>
    // ── Lightbox ──────────────────────────────────────────────────
    function openLightbox(src) {
        document.getElementById('lboxImg').src = src;
        document.getElementById('lbox').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        document.getElementById('lbox').classList.remove('open');
        document.body.style.overflow = '';
    }
    // Klik di luar gambar = tutup
    function closeLboxOverlay(e) {
        if (e.target === document.getElementById('lbox')) closeLightbox();
    }
    // ESC = tutup lightbox
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

    // ── Filter & Search ───────────────────────────────────────────
    let activeFilter = 'semua';

    function setFilter(f, el) {
        activeFilter = f;
        document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        filterOrders();
    }

    function filterOrders() {
        const q   = document.getElementById('searchInput').value.toLowerCase();
        // Filter tabel (desktop) dan card (mobile) sekaligus
        const all = document.querySelectorAll('#ordersBody tr, .vp-mobile-card');
        let vis   = 0;

        all.forEach(el => {
            const match = el.dataset.nama.includes(q) &&
                        (activeFilter === 'semua' || el.dataset.verif === activeFilter);
            el.style.display = match ? '' : 'none';
            // Hitung hanya dari tabel agar tidak double-count
            if (match && el.tagName === 'TR') vis++;
        });

        // Kalau di mobile (tabel hidden), hitung dari card
        const isMobile = window.innerWidth < 768;
        if (isMobile) {
            vis = 0;
            document.querySelectorAll('.vp-mobile-card').forEach(c => {
                if (c.style.display !== 'none') vis++;
            });
        }

        document.getElementById('ordersCount').textContent = vis + ' pesanan';
    }

    // Recalculate counter saat resize melewati breakpoint
    window.addEventListener('resize', filterOrders);
</script>