<?php
if (isset($_POST['update-status'])){
    $id = $_POST['id_pesanan'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "UPDATE pesanan SET status='$status' WHERE id_pesanan='$id'");

    echo "
    <script>
        alert('Status pesanan berhasil diperbarui');
        location='index.php?page=penjual/status-pesanan';
    </script>";
    exit;
}
?>

<?php
$id_penjual = $_SESSION['id'];

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_penjual='$id_penjual' ORDER BY id_pesanan DESC");
$total_pesanan = mysqli_num_rows($query);
?>

<div class="orders-page">

    <div class="orders-header">
        <div>
            <div class="orders-title">Kirim status pesanan</div>
            <div class="orders-subtitle">Perbarui status pengiriman untuk setiap pesanan</div>
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
                    <th>Update status</th>
                </tr>
            </thead>
            <tbody id="ordersBody">
                <?php while ($row = mysqli_fetch_assoc($query)):
                    $metode      = $row['metode_bayar'];
                    $metode_icon = stripos($metode, 'bank') !== false ? '🏦'
                                 : (stripos($metode, 'wallet') !== false ? '📱' : '💵');

                    $badge_map = [
                        'diproses' => 'badge-diproses',
                        'dikirim'  => 'badge-dikirim',
                        'selesai'  => 'badge-selesai',
                    ];
                    $badge_class = $badge_map[$row['status']] ?? 'badge-default';

                    $tgl = isset($row['tanggal'])
                         ? date('d M Y', strtotime($row['tanggal'])) : '';
                ?>
                <tr data-status="<?= $row['status'] ?>"
                    data-nama="<?= strtolower(htmlspecialchars($row['nama_pemesan'])) ?>">

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
                        <span class="status-badge <?= $badge_class ?>"
                              id="badge-<?= $row['id_pesanan'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>

                    <td>
                        <form method="POST" style="margin:0">
                            <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                            <div class="status-form">
                                <select name="status" class="status-select"
                                        data-original="<?= $row['status'] ?>"
                                        data-id="<?= $row['id_pesanan'] ?>"
                                        onchange="onStatusChange(this)">
                                    <option value="diproses" <?= $row['status']==='diproses' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="dikirim"  <?= $row['status']==='dikirim'  ? 'selected' : '' ?>>Dikirim</option>
                                    <option value="selesai"  <?= $row['status']==='selesai'  ? 'selected' : '' ?>>Selesai</option>
                                </select>
                                <button type="submit" name="update-status"
                                        class="btn-save"
                                        id="btn-<?= $row['id_pesanan'] ?>">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php endif; ?>
    </div>
</div>

<script>
const badgeMap = {
    diproses : 'badge-diproses',
    dikirim  : 'badge-dikirim',
    selesai  : 'badge-selesai',
};
const labelMap = { diproses:'Diproses', dikirim:'Dikirim', selesai:'Selesai' };

// Tampilkan/sembunyikan tombol Simpan & highlight select saat nilai berubah
function onStatusChange(sel) {
    const id      = sel.dataset.id;
    const changed = sel.value !== sel.dataset.original;
    sel.classList.toggle('changed', changed);
    document.getElementById('btn-' + id).classList.toggle('visible', changed);
}

// Filter tab
let activeFilter = 'semua';
function setFilter(f, el) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    filterOrders();
}

// Search + filter kombinasi
function filterOrders() {
    const q    = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#ordersBody tr');
    let vis    = 0;
    rows.forEach(row => {
        const match = row.dataset.nama.includes(q) &&
                      (activeFilter === 'semua' || row.dataset.status === activeFilter);
        row.style.display = match ? '' : 'none';
        if (match) vis++;
    });
    document.getElementById('ordersCount').textContent = vis + ' pesanan';
}
</script>