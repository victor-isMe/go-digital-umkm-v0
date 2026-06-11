<?php
$id = $_SESSION['id'];

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pembeli='$id' ORDER BY id_pesanan DESC");
?>

<div class="container mt-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Riwayat Pesanan</h4>
    </div>

    <div class="card-body">
        <?php if (mysqli_num_rows($query) == 0): ?>
            <div class="alert alert-info">Belum ada pesanan.</div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Nama Penerima</td>
                        <td>Total</td>
                        <td>Metode Bayar</td>
                        <td>Status</td>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $row['id_pesanan'] ?></td>
                            <td><?= $row['nama_pemesan'] ?></td>
                            <td>Rp <?= number_format($row['total_harga']) ?></td>
                            <td><?= $row['metode_bayar'] ?></td>
                            <td>
                                <?php
                                switch($row['status']){
                                    case 'diproses':
                                        echo '<span class="badge bg-info">Diproses</span>';
                                        break;
                                    case 'dikirim':
                                        echo '<span class="badge bg-primary">Dikirim</span>';
                                        break;
                                    case 'selesai':
                                        echo '<span class="badge bg-success">Selesai</span>';
                                        break;
                                    default:
                                        echo $row['status'];
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>