<?php

$query = mysqli_query($koneksi, 
                    "SELECT pj.*, COUNT(p.id_produk) AS jumlah_produk
                        FROM penjual pj
                        LEFT JOIN produk p ON pj.id_penjual=p.id_penjual
                        GROUP BY pj.id_penjual
                        ORDER BY pj.id_penjual DESC");

$rows = [];
$total = 0;
$jml_aktif = 0;
$jml_non = 0;

while ($r = mysqli_fetch_assoc($query)) {
    $rows[] = $r;
    $total++;
    if ($r['status'] === 'aktif') $jml_aktif++;
    else $jml_non++;
}
?>

<div class="ap-page">

    <a href="index.php?page=dashboard" class="btn-dashboard-ghost mb-3">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke dashboard
    </a>

    <div class="ap-header">
        <div>
            <div class="ap-title">Daftar akun UMKM</div>
            <div class="ap-subtitle">Pantau seluruh akun penjual yang terdaftar</div>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-label">TOTAL UMKM</div>
            <div class="stat-val"><?= $total ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">AKTIF</div>
            <div class="stat-val green"><?= $jml_aktif ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">NONAKTIF</div>
            <div class="stat-val red"><?= $jml_non ?></div>
        </div>
    </div>

    <div class="ap-card">

        <div class="ap-toolbar">
            <div class="ap-search">
                <input type="text" id="searchInput"
                       placeholder="Cari nama toko atau pemilik..."
                       oninput="filterTable()">
            </div>
            <div class="filter-tabs">
                <button class="ftab active" onclick="setFilter('semua', this)">Semua</button>
                <button class="ftab" onclick="setFilter('aktif', this)">Aktif</button>
                <button class="ftab" onclick="setFilter('nonaktif', this)">Nonaktif</button>
            </div>
        </div>

        <?php if (empty($rows)): ?>
            <div class="ap-empty">
                <div class="ap-empty-text">Belum ada akun UMKM terdaftar</div>
                <div class="ap-empty-sub">Akun penjual yang mendaftar akan muncul di sini</div>
            </div>
        <?php else: ?>

        <!-- TABLE desktop & tablet -->
        <div class="ap-table-wrap">
            <table class="ap-table">
                <thead>
                    <tr>
                        <th>Toko</th>
                        <th>Pemilik</th>
                        <th>Produk</th>
                        <th>Status</th>
                        <!-- <th>Detail</th> -->
                    </tr>
                </thead>
                <tbody id="tableBody">
                <?php foreach ($rows as $data):
                    $is_aktif   = $data['status'] === 'aktif';
                    $jml        = (int) $data['jumlah_produk'];
                    $cari_str   = strtolower($data['nama_toko'] . ' ' . $data['nama_penjual']);
                ?>
                <tr data-nama="<?= htmlspecialchars($cari_str) ?>"
                    data-status="<?= $data['status'] ?>">

                    <td>
                        <div class="toko-name"><?= htmlspecialchars($data['nama_toko']) ?></div>
                        <div class="toko-id-sub">#<?= $data['id_penjual'] ?></div>
                    </td>

                    <td>
                        <div class="owner-name"><?= htmlspecialchars($data['nama_penjual']) ?></div>
                        <div class="owner-email"><?= htmlspecialchars($data['email_penjual']) ?></div>
                    </td>

                    <td>
                        <span class="produk-count <?= $jml === 0 ? 'zero' : '' ?>">
                            <?= $jml ?> produk
                        </span>
                    </td>

                    <td>
                        <span class="status-badge <?= $is_aktif ? 'badge-aktif' : 'badge-nonaktif' ?>">
                            <?= $is_aktif ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </td>

                    <!-- <td></td> -->
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- CARD LIST mobile -->
        <div class="ap-mobile-list" id="mobileList">
        <?php foreach ($rows as $data):
            $is_aktif = $data['status'] === 'aktif';
            $jml      = (int) $data['jumlah_produk'];
            $cari_str = strtolower($data['nama_toko'] . ' ' . $data['nama_penjual']);
        ?>
            <div class="ap-mobile-card"
                 data-nama="<?= htmlspecialchars($cari_str) ?>"
                 data-status="<?= $data['status'] ?>">

                <div class="mc-head">
                    <div>
                        <div class="toko-name"><?= htmlspecialchars($data['nama_toko']) ?></div>
                        <div class="toko-id-sub">#<?= $data['id_penjual'] ?></div>
                    </div>
                    <span class="status-badge <?= $is_aktif ? 'badge-aktif' : 'badge-nonaktif' ?>">
                        <?= $is_aktif ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                </div>

                <div class="mc-row">
                    <span class="mc-label">Pemilik</span>
                    <span><?= htmlspecialchars($data['nama_penjual']) ?></span>
                </div>
                <div class="mc-row">
                    <span class="mc-label">Email</span>
                    <span style="font-size:12px"><?= htmlspecialchars($data['email_penjual']) ?></span>
                </div>
                <div class="mc-row">
                    <span class="mc-label">Produk</span>
                    <span class="produk-count <?= $jml === 0 ? 'zero' : '' ?>"><?= $jml ?> produk</span>
                </div>

                <!-- <div class="mc-foot"></div> -->
            </div>
        <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>
</div>

<script>
let activeFilter = 'semua';

function setFilter(f, el) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    filterTable();
}

function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tableBody tr, .ap-mobile-card').forEach(r => {
        const show = r.dataset.nama.includes(q) &&
                     (activeFilter === 'semua' || r.dataset.status === activeFilter);
        r.style.display = show ? '' : 'none';
    });
}
</script>