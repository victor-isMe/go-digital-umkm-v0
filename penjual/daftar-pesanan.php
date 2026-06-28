<?php
$id_penjual = $_SESSION['id'];

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_penjual='$id_penjual' ORDER BY tanggal DESC");
$total_pesanan = mysqli_num_rows($query);
?>

<div class="orders-page">

    <div class="orders-header">
        <div>
            <div class="orders-title">Daftar pesanan</div>
            <div class="orders-subtitle">Pantau semua pesanan masuk</div>
        </div>
        <div class="orders-count" id="ordersCount"><?= $total_pesanan ?> pesanan</div>
    </div>

    <div class="orders-card">

        <div class="orders-toolbar">
            <div class="search-wrap">
                <input type="text" id="searchInput" placeholder="Cari nama pemesan..." oninput="filterOrders()">
            </div>
            <div class="filter-tabs">
                <button class="ftab active" onclick="setFilter('semua', this)">Semua</button>
                <button class="ftab" onclick="setFilter('diproses', this)">Diproses</button>
                <button class="ftab" onclick="setFilter('dikirim', this)">Dikirim</button>
                <button class="ftab" onclick="setFilter('selesai', this)">Selesai</button>
            </div>
        </div>

        <?php if ($total_pesanan === 0): ?>
            <div class="orders-empty">
                <div class="orders-empty-text">Belum ada pesanan masuk</div>
                <div class="orders-empty-sub">Pesanan dari pembeli akan muncul di sini</div>
            </div>
        <?php else: ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pemesan</th>
                    <th>Total</th>
                    <th>Metode bayar</th>
                    <th>Status</th>
                    <!-- <th>Aksi</th> -->
                </tr>
            </thead>
            <tbody id="ordersBody">
                <?php while ($row = mysqli_fetch_assoc($query)): 
                    // Tentukan icon metode bayar
                    $metode = $row['metode_bayar'];
                    $metode_icon = '💵';
                    if (stripos($metode, 'bank') !== false) $metode_icon = '🏦';
                    if (stripos($metode, 'ewallet') !== false) $metode_icon = '📱';

                    // Badge class per status
                    $badge_class = 'badge-default';
                    if ($row['status'] === 'diproses') $badge_class = 'badge-diproses';
                    if ($row['status'] === 'dikirim') $badge_class = 'badge-dikirim';
                    if ($row['status'] === 'selesai') $badge_class = 'badge-selesai';

                    // Tanggal — ganti 'created_at' sesuai nama kolom kamu
                    $tgl = isset($row['tanggal'])
                        ? date('d M Y', strtotime($row['tanggal']))
                        : '';
                ?>
                <tr data-status="<?= $row['status'] ?>"
                    data-nama="<?= strtolower($row['nama_pemesan']) ?>">
                    <td><span class="order-id">#<?= $row['id_pesanan'] ?></span></td>
                    <td>
                        <div class="pemesan-name"><?= htmlspecialchars($row['nama_pemesan']) ?></div>
                        <?php if ($tgl): ?>
                            <div class="pemesan-date"><?= $tgl ?></div>
                        <?php endif; ?>
                    </td>
                    <td><span class="total-harga">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span></td>
                    <td><span class="metode-pill"><?= $metode_icon ?> <?= htmlspecialchars($metode) ?></span></td>
                    <td><span class="status-badge <?= $badge_class ?>"><?= ucfirst($row['status']) ?></span></td>
                    <!-- <td>
                        <a href="index.php?page=penjual/detail-pesanan&id=<?= $row['id_pesanan'] ?>"
                           class="btn-detail">
                            👁 Detail
                        </a>
                    </td> -->
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>

    </div>
</div>

<script>
let activeFilter = 'semua';

function setFilter(filter, el) {
    activeFilter = filter;
    document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    filterOrders();
}

function filterOrders() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#ordersBody tr');
    let visible = 0;

    rows.forEach(row => {
        const nama = row.dataset.nama || '';
        const status = row.dataset.status || '';
        const matchCari = nama.includes(q);
        const matchTab = activeFilter === 'semua' || status === activeFilter;

        const show = matchCari && matchTab;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('ordersCount').textContent = visible + ' pesanan';
}
</script>